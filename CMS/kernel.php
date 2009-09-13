<?php /* Jądro systemu */
if(iCMS!=1) exit;
define('PATH',str_replace('//','/',dirname($_SERVER['PHP_SELF']).'/'));
define('URL','http://'.$_SERVER['SERVER_NAME'].PATH);

#Ochrona przed CSRF
if($_POST && isset($_SERVER['HTTP_REFERER']))
{
	$pos = strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']);
	if($pos < 3 OR $pos > 8) exit;
}

#Register globals: usuń zmienne
if(ini_get('register_globals'))
{
	foreach(array_keys($_REQUEST) as $x) unset($$x);
}

#ID do zmienej: $id zawsze istnieje
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;

#Nr strony
if(isset($_GET['page']) && !is_numeric($_GET['page']))
{
	$_GET['page'] = 1;
}

#Utwórz krytyczne tablice
$lang = array();
$cfg  = array();
$user = null;

#Opcje
require './cfg/main.php';
require './cfg/db.php';

#Bany
if(!empty($cfg['ban']))
{
	$ban = explode("\n", $cfg['ban']);
	if(in_array($_SERVER['REMOTE_ADDR'], $ban))
	{
		exit;
	}
}

#Czy to żądanie AJAX?
define('JS', isset($_SERVER['HTTP_X_REQUESTED_WITH']));

#Katalog skórki
define('SKIN_DIR', './style/'.$cfg['skin'].'/');
define('VIEW_DIR', './cache/'.$cfg['skin'].'/');

#Sesja
session_name(PRE);
session_start();

#Język: zmiana
if(isset($_GET['setlang']) && ctype_alnum($_GET['setlang']) && is_dir('./lang/'.$_GET['setlang']))
{
	$nlang = $_SESSION['lang'] = $_GET['setlang'];
	setcookie(PRE.'lang', $nlang, time()+23328000); //9 miesięcy
}
#Język: sesja
elseif(isset($_SESSION['lang']))
{
	$nlang = $_SESSION['lang'];
}
#Język: cookies
elseif(isset($_COOKIE[PRE.'lang']))
{
	if(ctype_alnum($_COOKIE[PRE.'lang']) && is_dir('./lang/'.$_COOKIE[PRE.'lang']))
	{
		$nlang = $_SESSION['lang'] = $_COOKIE[PRE.'lang'];
	}
}
#Autowykrywanie języka
elseif(isset($cfg['detectLang']))
{
	foreach(explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $x)
	{
		if(isset($x[2]))
		{
			$x = $x[0].$x[1];
		}
		if(ctype_alnum($x) && is_dir('./lang/'.$x))
		{
			$nlang = $_SESSION['lang'] = $x; break;
		}
	}
	unset($x);
}
if(!isset($nlang)) $nlang = 'en';

#Katalog z plikami językowymi
define('LANG_DIR', './lang/'.$nlang.'/');

#Dołącz główny plik języka
require LANG_DIR.'main.php';

#Dołącz klasę skórek i utwórz obiekt
require './lib/content.php';
$content = new Content;

#Arkusz CSS
if(isset($_COOKIE['CSS']) && is_numeric($_COOKIE['CSS']))
{
	define('CSS', $_COOKIE['CSS']);
}
else
{
	define('CSS', '1');
}

#Połącz z bazą danych
try
{
	#SQLite
	if($db_db=='sqlite')
	{
		$db = new PDO('sqlite:'.$db_d);
	}
	#MySQL
	else
	{
		$db = new PDO('mysql:host='.$db_h.';dbname='.$db_d,$db_u,$db_p);
		$db -> exec('SET NAMES utf8');
	}
	$db->setAttribute(3,2); #ERRMODE: Exceptions
	$db->setAttribute(19,2); #DefaultFetchMode: ASSOC
}
catch(PDOException $e)
{
	$content->message(23);
}

$xuid=false;

#Użytkownik - sesja
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

#Użytkownik - pamiętanie
elseif(isset($_COOKIE[PRE.'login']))
{
	$usrc = $_COOKIE[PRE.'login'];
	if($pos = strpos($usrc,':'))
	{
		$xuid = substr($usrc,0,$pos);
		$xpass = substr($usrc,++$pos);
	}
	unset($usrc,$pos);
}

#Dane poprawne?
if(is_numeric($xuid))
{
	if(isset($_SESSION['userdata']))
	{
		$user[$xuid] =& $_SESSION['userdata'];
	}
	else
	{
		$user[$xuid] = $_SESSION['userdata'] = $db->query('SELECT login,pass,gid,lv,adm,lvis,pms
		FROM '.PRE.'users WHERE lv>0 AND ID='.$xuid) -> fetch(2); //ASSOC
	}

	#Test
	if(isset($user[$xuid]))
	{
		if($xpass===$user[$xuid]['pass'])
		{
			define('LOGD', 1);
			define('UID', $xuid);
			define('LEVEL', $user[UID]['lv']);
			define('GID', $user[UID]['gid']);
		}
		else
		{
			unset($user);
		}
	}
}
unset($xuid,$xpass);

#Data wejścia na stronę, RECENT w sesji = data ostatniej wizyty
if(defined('LOGD'))
{
	if(isset($cfg['lastVisit']) && !isset($_SESSION['recent']))
	{
		$db->exec('UPDATE '.PRE.'users SET lvis='.$_SERVER['REQUEST_TIME'].' WHERE ID='.UID);
		$_SESSION['recent'] = (int) $user[UID]['lvis'];
	}
}
else
{
	#Nie usuwaj!
	define('LOGD',0);
	define('UID',0);
	define('LEVEL',0);
	define('GID',0);
}

#Dzisiaj
define('TODAY', strftime($cfg['now']));

#Typ,kodowanie
Header('Cache-Control: public');
Header('Content-type: text/html; charset=utf-8');

#FUNKCJE

#Prawa - wpuścić użytkownika?
function Admit($id,$type=null)
{
	if(LOGD!=1) return false; //Gość
	global $user, $db;
	static $global, $all;

	#Właściciel?
	if($user[UID]['lv']==4) return true;

	#Kategoria
	if($type)
	{
		if($type=='CAT')
		{
			if(LEVEL>1 && Admit('+'))
			{
				return true;
			}
			elseif(LEVEL<2)
			{
				return false;
			}
		}
		else
		{
			if(!isset($all[$type]))
			{
				$q = $db->prepare('SELECT CatID,1 FROM '.PRE.'acl WHERE type=? AND UID=?');
				$q -> execute(array($type, UID));
				$all[$type] = $q->fetchAll(12); //KEY_PAIR
			}
			return isset($all[$type][$id]);
		}
	}
	else
	{
		if(!isset($global)) $global = explode('|',$user[UID]['adm']);
		return in_array($id,$global);
	}
}

#Struktura kategorii
function CatPath($id, &$cat=null)
{
	if(file_exists('./cache/cat'.$id.'.php'))
	{
		return file_get_contents('./cache/cat'.$id.'.php');
	}
	else
	{
		include_once './lib/categories.php';
		return UpdateCatPath($cat ? $cat : $id);
	}
}

#Skanowanie katalogu
function ListBox($dir,$co,$ch)
{
	if(!is_dir($dir)) return '';
	$out = '';

	#Katalogi
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
	#Pliki
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

#Strony
function Pages($page,$ile,$max,$url,$type=0)
{
	global $lang;
	$all = ceil($ile / $max);
	$out = '';

	#URL
	$url .= '&amp;page=';

	#Select
	if($type)
	{
		$out = '<select onchange="location=\''.$url.'\'+(this.selectedIndex+1)">';
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
			$out.='<a class="'.(($page==$i)?'pageAct':'').'" href="'.$url.$i.'">'.$i.'</a>';
		}
		return $out;
	}
}

#Banner
function Banner($gid)
{
	return $GLOBALS['db']->query('SELECT code FROM '.PRE.'banners WHERE gen='.(int)$gid.
		' AND ison=1 ORDER BY RAND'.(($GLOBALS['db_db']=='sqlite') ? 'OM' : '').'() LIMIT 1')
		-> fetchColumn();
}

#Emoty
function Emots($txt)
{
	static $emodata;
	include_once './cfg/emots.php';
	foreach($emodata as $x)
	{
		$txt = str_replace($x[2],'<img src="img/emo/'.$x[1].'" title="'.$x[0].'" alt="'.$x[2].'" />',$txt);
	}
	return $txt;
}

#Data
function genDate($x, $time=false)
{
	static $now,$yda,$tom;
	global $cfg,$lang;

	if(!$x) return $x;
	if($x[4] === '-') $x = strtotime($x.' GMT'); //Zamień DATETIME na znacznik czasu

	#Różnica czasu
	$diff = $_SERVER['REQUEST_TIME'] - $x;

	#X minut temu (do 99)
	if($diff < 5941 && $diff >= 0)
	{
		return sprintf($lang['ago'], ceil($diff/60));
	}
	#Za X minut
	elseif($diff > -5941 && $diff < 0)
	{
		return sprintf($lang['in'], ceil(-$diff/60));
	}

	#Formatuj datę
	$date = strftime($cfg['date'], $x);
	
	#Dzisiaj, wczoraj, jutro
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

#Autorzy
function Autor($v)
{
	global $user,$db;
	if(is_numeric($v))
	{
		if(!isset($user[$v]))
		{
			$user[$v] = $db->query('SELECT login FROM '.PRE.'users WHERE ID='.$v) -> fetch(2);
		}
		if($user[$v]['login'])
		{
			return '<a href="?co=user&amp;id='.$v.'">'.$user[$v]['login'].'</a>';
		}
		else return $v;
	}
	else return $v;
}

#Znaki specjalne, cenzura
function Clean($val,$max=0,$wr=0)
{
	if($max) $val = substr($val,0,$max);
	if($wr && isset($GLOBALS['cfg']['censor']))
	{
		static $words1,$words2;
		include_once './cfg/words.php';
		$val = str_replace($words1,$words2,$val); //Zamiana słów
	}
	return trim(htmlspecialchars($val, 2, 'UTF-8'));
}

#Licz w bazie
function db_count($table)
{
	return (int)$GLOBALS['db']->query('SELECT COUNT(*) FROM '.PRE.$table)->fetchColumn();
}