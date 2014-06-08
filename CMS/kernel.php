<?php /* F3Site Kernel */
if(iCMS!=1) exit;

#Filter against CSRF
if($_POST && isset($_SERVER['HTTP_REFERER']))
{
	$pos = strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']);
	if($pos < 3 OR $pos > 8) exit;
}

#Register globals: kill vars
if(ini_get('register_globals'))
{
	foreach(array_keys($_REQUEST) as $x) unset($$x);
}

#Magic quotes: kill slashes
if(ini_get('magic_quotes_gpc'))
{
	$gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	array_walk_recursive($gpc, function(&$x) { $x = stripslashes($x); });
}

#Main arrays
$lang = array();
$cfg  = array();
$user = null;

#Settings
require './cfg/main.php';
require './cfg/db.php';

#AJAX request, protocol
define('JS', isset($_SERVER['HTTP_X_REQUESTED_WITH']));
define('PROTO', isset($_SERVER['HTTPS']) ? 'https://' : 'http://');
define('NICEURL', $cfg['niceURL']);

#Path based on PATH_INFO or GET param
if(isset($_SERVER['PATH_INFO'][1]))
{
	$URL = explode('/', substr($_SERVER['PATH_INFO'],1));
	define('PATH', substr(dirname($_SERVER['PHP_SELF']),0,-9));
}
else
{
	$URL = isset($_GET['go']) ? explode('/', $_GET['go']) : array();
	define('PATH', str_replace('//','/',dirname($_SERVER['PHP_SELF']).'/'));
}

#Detect full URL
define('URL','http://'.$_SERVER['SERVER_NAME'].PATH);
define('ID', isset($URL[1]) ? (int)$URL[1] : 0);

#Skin paths TODO:remove
define('SKIN_DIR', './style/'.$cfg['skin'].'/');
define('VIEW_DIR', './cache/'.$cfg['skin'].'/');

session_start();

#Default language
$nlang = $cfg['lang'];

#Lang: set
if(isset($URL[0][1]) && empty($URL[0][2]) && file_exists('lang/'.$URL[0].'/main.php'))
{
	$nlang = $_SESSION['LANG'] = array_shift($URL);
	setcookie('lang', $nlang, time()+23328000); //9 months
}
#Lang: session
elseif(isset($_SESSION['LANG']))
{
	$nlang = $_SESSION['LANG'];
}
#Lang: cookies
elseif(isset($_COOKIE['lang']) && ctype_alnum($_COOKIE['lang']) && is_dir('lang/'.$_COOKIE['lang']))
{
	$nlang = $_SESSION['LANG'] = $_COOKIE['lang'];
}
#Lang: detect
elseif(isset($cfg['detectLang']))
{
	foreach(explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $x)
	{
		if(isset($x[2]))
		{
			$x = $x[0].$x[1];
		}
		if(ctype_alnum($x) && file_exists('lang/'.$x.'/main.php'))
		{
			$nlang = $_SESSION['LANG'] = $x; break;
		}
	}
	unset($x);
}

#Lang: paths
define('LANG', $nlang);
define('LANG_DIR', './lang/'.LANG.'/');

#Include main lang file
require LANG_DIR.'main.php';

#Include skin class and create object
require './lib/view.php';
$view = new View;

#Stylesheet
if(isset($_COOKIE['CSS']) && ctype_alnum($_COOKIE['CSS']))
{
	define('CSS', $_COOKIE['CSS']);
}
else
{
	define('CSS', '1');
}

#Cache, charset
header('Cache-Control: public');
header('Content-type: text/html; charset=utf-8');

#Connect to database
try
{
	if($db_db=='sqlite')
	{
		$db = new PDO('sqlite:'.$db_d);
	}
	else
	{
		$db = new PDO('mysql:host='.$db_h.';dbname='.$db_d,$db_u,$db_p);
		$db->exec('SET NAMES utf8');
	}
	$db->setAttribute(3,2); #ERRMODE: Exceptions
	$db->setAttribute(19,2); #DefaultFetchMode: ASSOC
}
catch(PDOException $e)
{
	$view->message(23);
}

$xuid=false;

#User: temporary
if(isset($_SESSION['uid']))
{
	if($_SERVER['REMOTE_ADDR']===$_SESSION['IP'])
	{
		$xuid = $_SESSION['uid'];
		$xpass = $_SESSION['uidp'];
	}
	else
	{
		session_regenerate_id(1);
	}
}

#User: permanent
elseif(isset($_COOKIE[PRE.'login']))
{
	list($xuid,$xpass) = explode(':',$_COOKIE[PRE.'login']);
}

#Check user data
if(is_numeric($xuid))
{
	if(isset($_SESSION['userdata']))
	{
		$user =& $_SESSION['userdata'];
	}
	else
	{
		$user = $_SESSION['userdata'] = $db->query('SELECT login,pass,lv,adm,lvis,pms
		FROM '.PRE.'users WHERE lv>0 AND ID='.$xuid) -> fetch(2); //ASSOC
	}

	#Check password
	if(isset($user))
	{
		if($xpass===$user['pass'])
		{
			define('UID', $xuid);
			define('LEVEL', $user['lv']);
			define('IS_EDITOR', LEVEL > 1);
			define('IS_ADMIN', LEVEL > 2);
			define('IS_OWNER', LEVEL > 3);
		}
		else
		{
			unset($user);
		}
	}
}
unset($xuid,$xpass);

#Last visit date
if(defined('UID'))
{
	if(!isset($_SESSION['recent']))
	{
		$db->exec('UPDATE '.PRE.'users SET lvis='.$_SERVER['REQUEST_TIME'].' WHERE ID='.UID);
		$_SESSION['recent'] = (int)$user['lvis'];
	}
}
else
{
	#DO NOT DELETE!
	define('UID',0);
	define('LEVEL',1);
	define('IS_ADMIN',0);
	define('IS_EDITOR',0);
	define('IS_OWNER',0);
}

#Check if user is allowed to...
function admit($id,$type=null)
{
	if(!UID) return false;
	global $user, $db;
	static $global, $all;

	#Owner may access everything
	if(IS_OWNER) return true;

	#Category
	if($type)
	{
		if($type=='CAT')
		{
			if(IS_EDITOR)
			{
				if(admit('+')) return true;
			}
			else
			{
				return false;
			}
		}
		if(!isset($all[$type]))
		{
			$q = $db->prepare('SELECT CatID,1 FROM '.PRE.'acl WHERE type=? AND UID=?');
			$q -> execute(array($type, UID));
			$all[$type] = $q->fetchAll(12); //KEY_PAIR
		}
		return isset($all[$type][$id]);
	}
	else
	{
		if(empty($global)) $global = explode('|',$user['adm']);
		return in_array($id,$global);
	}
}

#URL - mod_rewrite
function url($x, $query=null, $path=null)
{
	if($path) $path .= '/';
	if($query && is_array($query)) $query = http_build_query($query);
	switch(NICEURL)
	{
		case 1: return $path . $x . ($query ? '?'.$query : ''); break;
		case 2: return $path . 'index.php/' . $x . ($query ? '?'.$query : ''); break;
		default: return $path . '?go=' . $x . ($query ? '&'.$query : '');
	}
}

#Category path
function catPath($id, &$cat=null)
{
	if(file_exists('cache/cat'.$id.'.php'))
	{
		return file_get_contents('cache/cat'.$id.'.php');
	}
	else
	{
		include_once './lib/categories.php';
		return UpdateCatPath($cat ? $cat : $id);
	}
}

#Scan folder
function listBox($dir,$co,$ch)
{
	if(!is_dir($dir)) return '';
	$out = '';

	#Folders
	if($co == 1)
	{
		foreach(scandir($dir) as $x)
		{
			if(is_dir($dir.'/'.$x) && $x[0]!='.')
			{
				$out.= '<option'.(($ch==$x)?' selected="selected"':'').'>'.$x.'</option>';
			}
		}
	}
	#Files
	else
	{
		foreach(scandir($dir) as $x)
		{
			if(is_file($dir.'/'.$x))
			{
				$x = str_replace('.php', '', $x);
				$out.= '<option'.(($ch==$x)?' selected="selected"':'').'>'.$x.'</option>';
			}
		}
	}
	return $out;
}

#Pages
function pages($page,$ile,$max,$url='',$type=0,$p='')
{
	global $lang;
	$all = ceil($ile / $max);
	$out = '';

	#URL
	if(!$p) $p = strpos($url, '?')===false ? '?page=' : '&amp;page=';

	#Select
	if($type)
	{
		$out = '<select onchange="location=\''.$url.$p.'\'+(this.selectedIndex+1)">';
		for($i=1; $i<=$all; ++$i)
		{
			$out.='<option'.(($page==$i)?' selected="selected"':'').'>'.$i.'</option>';
		}
		return $out.'</select> '.$lang['of'].$all;
	}
	else
	{
		for($i=1; $i<=$all; ++$i)
		{
			if($all > 9 && $i > 1)
			{
				if($i+2 < $page)
				{
					$i = $page-2;
				}
				elseif($i-2 > $page)
				{
					$i = $all;
				}
			}
			if($page==$i)
			{
				$out.='<a class="active">'.$i.'</a>';
			}
			else
			{
				$out.='<a href="'.$url.($i>1 ? $p.$i : '').'">'.$i.'</a>';
			}
		}
		return $out;
	}
}

#Emoticons
function emots($txt)
{
	static $emodata;
	include_once './cfg/emots.php';
	foreach($emodata as $x)
	{
		$txt = str_replace($x[2],'<img src="img/emo/'.$x[1].'" title="'.$x[0].'" alt="'.$x[2].'" />',$txt);
	}
	return $txt;
}

#Date
function genDate($x, $time=false)
{
	static $now,$yda,$tom;
	global $cfg,$lang;

	if(!$x) return $x;
	if($x[4] === '-') $x = strtotime($x.' GMT'); //Convert DATETIME to timestamp

	#Time difference
	$diff = $_SERVER['REQUEST_TIME'] - $x;

	#X min ago (to 99)
	if($diff < 5941 && $diff >= 0)
	{
		return sprintf($lang['ago'], ceil($diff/60));
	}
	#In X min
	elseif($diff > -5941 && $diff < 0)
	{
		return sprintf($lang['in'], ceil(-$diff/60));
	}

	#Format date
	$date = strftime($cfg['date'], $x);
	
	#Today, yesterday, tomorrow
	if(!$now)
	{
		$now = strftime($cfg['date'], $_SERVER['REQUEST_TIME']);
		$yda = strftime($cfg['date'], $_SERVER['REQUEST_TIME'] - 86400);
		$tom = strftime($cfg['date'], $_SERVER['REQUEST_TIME'] + 86400);
	}
	if($now === $date)
	{
		$date = $lang['today'];
	}
	elseif($yda === $date)
	{
		$date = $lang['YDA'];
	}
	elseif($tom === $date)
	{
		$date = $lang['TOM'];
	}
	if($time)
	{
		return $date . strftime($cfg['time'], $x);
	}
	else
	{
		return $date;
	}
}

#Authors
function autor($x)
{
	global $db,$user;
	static $all;
	if(is_numeric($x))
	{
		if($x == UID)
		{
			$login = $user['login'];
		}
		else
		{
			if(!isset($all[$x]))
			{
				$all[$x] = $db->query('SELECT login FROM '.PRE.'users WHERE ID='.$x)->fetchColumn();
			}
			if($all[$x])
			{
				$login = $all[$x];
			}
			else return $x;
		}
		return '<a href="'.url('user/'.urlencode($login)).'">'.$login.'</a>';
	}
	else return $x;
}

#Protect against HTML, censorship
function clean($val,$max=0,$wr=0)
{
	if($max) $val = substr($val,0,$max);
	if($wr)
	{
		static $words1,$words2;
		include_once './cfg/words.php';
		$val = str_replace($words1,$words2,$val);
	}
	return trim(htmlspecialchars($val, 2, 'UTF-8'));
}

#Write log entry
function event($type, $u = UID, $force = TRUE)
{
	#static $cfg;
	global $db,$cfg;

	#Record to database - no errors
	#Force always TRUE - event options not implemented yet
	if($force || isset($cfg['log']) && isset($cfg['log'][$type]))
	{
		try
		{
			$q = $db->prepare('INSERT INTO '.PRE.'log (name,ip,user) VALUES (?,?,?)');
			$q->execute(array($type, $_SERVER['REMOTE_ADDR'], $u));
		}
		catch(Exception $e) {}
	}
}

#Count in database
function dbCount($table)
{
	global $db;
	return (int)$db->query('SELECT COUNT(*) FROM '.PRE.$table)->fetchColumn();
}
