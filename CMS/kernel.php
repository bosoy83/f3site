<?php /* J±dro systemu */
if(iCMS!=1) exit;
define('URL','http://'.$_SERVER['HTTP_HOST'].str_replace('//','/',dirname($_SERVER['PHP_SELF']).'/'));

#Ochrona przed CSRF
if($_POST)
{
	if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])===false) exit;
}

#Register globals: usuñ zmienne
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
		Header('Location: '.$cfg['banurl']);
		exit;
	}
}

#Przepisywanie linków
define('MOD_REWRITE', 0); //It's disabled now

#Katalog skórki
define('SKIN_DIR', './style/'.$cfg['skin'].'/');
define('VIEW_DIR', './cache/'.$cfg['skin'].'/');

#Sesja
session_name(PRE);
session_start();

#Jêzyk: zmiana
if(isset($_GET['setlang']) && ctype_alnum($_GET['setlang']) && is_dir('./lang/'.$_GET['setlang']))
{
	$nlang = $_SESSION['lang'] = $_GET['setlang'];
	setcookie(PRE.'lang', $nlang, time()+12960000); //Ustaw na 5 mies.
}
#Jêzyk: sesja
elseif(isset($_SESSION['lang']))
{
	$nlang = $_SESSION['lang'];
}
#Jêzyk: cookies
elseif(isset($_COOKIE[PRE.'lang']))
{
	if(ctype_alnum($_COOKIE[PRE.'lang']) && is_dir('./lang/'.$_COOKIE[PRE.'lang']))
	{
		$nlang = $_SESSION['lang'] = $_COOKIE[PRE.'lang'];
	}
}
#Autowykrywanie jêzyka
elseif($cfg['detectLang']===1)
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

#Katalog z plikami jêzykowymi
define('LANG_DIR', './lang/'.$nlang.'/');

#Do³±cz g³ówny plik jêzyka
require LANG_DIR.'main.php';

#Dzisiaj
define('TODAY', strftime($cfg['now']));

#Do³±cz klasê skórek i utwórz obiekt
require './lib/content.php';
$content = new Content;

#Arkusz CSS
if(isset($_COOKIE['CSS']) && is_numeric($_COOKIE['CSS']))
{
	$content->addCSS(SKIN_DIR . $_COOKIE['CSS'] . '.css');
}
else
{
	$content->addCSS(SKIN_DIR . '1.css');
}

#Po³±cz z baz± danych
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
		$db = new PDO('mysql:host='.$db_h.';dbname='.$db_d,$db_u,$db_p); //Potem: $cfg['sqlpc']
		$db ->exec('SET CHARACTER SET "latin2"'); //!!!!!!!!!!!!
	}
	$db->setAttribute(3,2); #ERRMODE: Exceptions
	$db->setAttribute(19,2); #DefaultFetchMode: ASSOC
}
catch(PDOException $e)
{
	$content->message(23);
}

$xuid=false;

#U¿ytkownik - sesja
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

#U¿ytkownik - pamiêtanie
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

#Data wej¶cia na stronê, RECENT w sesji = data ostatniej wizyty
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
}

#Typ,kodowanie
Header('Cache-Control: public');
Header('Content-type: text/html; charset=iso-8859-2');

#FUNKCJE

#Prawa ($a - autor)
function Admit($id,$type=null)
{
	if(LOGD!=1) return false; //Go¶æ?
	global $user, $db;
	static $global, $all;

	#W³a¶ciciel?
	if($user[UID]['lv']==4) return true;

	#Kategoria
	if($type)
	{
		if($type=='CAT' && LEVEL>1 && Admit('GLOBAL'))
		{
			return true;
		}
		else
		{
			if(!isset($all[$type]))
			{
				$all[$type] = $db->query('SELECT CatID FROM '.PRE.'acl WHERE type="'.$type.'" AND UID='.UID) -> fetchAll(7);
			}
			return isset($all[$type][$id]);
		}
	}
	else
	{
		if(!isset($global)) $global=explode('|',$user[UID]['adm']);
		if(in_array($id,$global)) { return true; } else { return false; }
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
function Pages($page,$ile,$max,$url,$type=2)
{
	global $lang;
	$stron = ceil($ile / $max);
	$out = $type==1 ? '<select onchange="location=\''.$url.'&amp;page=\'+(this.selectedIndex+1)">':''.$lang['page'].': ';

	for($i=1;$i<=$stron;++$i)
	{
		if($type==1)
		{
			$out.='<option class="pgs"'.(($page==$i)?' selected="selected"':'').'>'.$i.'</option>';
		}
		else
		{
			$out.='<a class="'.(($page==$i)?'pageAct':'').'" href="'.$url.'&amp;page='.$i.'">'.$i.'</a>';
		}
	}
	return $out.(($type==1)?'</select> '.$lang['of'].' '.$stron:'');
}

#Banner
function Banner($gid)
{
	return $GLOBALS['db']->query('SELECT code FROM '.PRE.'banners WHERE gen='.(int)$gid.
		' AND ison=1 ORDER BY RAND'.(($GLOBALS['db_db']=='sqlite') ? 'OM' : '').'() LIMIT 1')
		-> fetchColumn();
}

#Emoty
function Emots($txt=null)
{
	static $emodata;
	include_once('./cfg/emots.php');
	$ile=count($emodata);
	for($n=0;$n<$ile;$n++)
	{
		$txt=str_replace($emodata[$n][2],'<img src="img/emo/'.$emodata[$n][1].'" title="'.$emodata[$n][0].'" alt="'.$emodata[$n][2].'" />',$txt);
	}
	return $txt;
}

#Data
function genDate($x, $time=false)
{
	static $cur;
	global $cfg,$lang;

	if(!$x) return $x;
	if($x[4] === '-') $x = strtotime($x.' GMT'); //Zamieñ DATETIME na znacznik czasu

	#Ró¿nica czasu
	$diff = $_SERVER['REQUEST_TIME'] - $x;

	#X minut temu (do 99)
	if($diff < 5941 && $diff > 0)
	{
		return ceil($diff/60).$lang['ago'];
	}
	#Za X minut
	elseif($diff > -5941 && $diff < 0)
	{
		return str_replace('%', ceil(-$diff/60), $lang['in']);
	}

	#Formatuj datê
	if(!$cur) $cur = strftime($cfg['date'], $_SERVER['REQUEST_TIME']);
	$date = strftime($cfg['date'], $x);

	if($cur === $date)
	{
		$date = $lang['today'];
	}
	if($time)
	{
		return $date.strftime($cfg['time'], $x);
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
			return '<a href="index.php?co=user&amp;id='.$v.'">'.$user[$v]['login'].'</a>';
		}
		else return $v;
	}
	else return $v;
}

#Encje + trim()
function Clean($val,$max=0,$wr=0)
{
	if($max) $val=substr($val,0,$max);
	if($wr && $GLOBALS['cfg']['censor']==1)
	{
		static $words1,$words2;
		include_once './cfg/words.php';
		$val = str_replace($words1,$words2,$val); //Zamiana s³ów
	}
	return trim(htmlspecialchars($val));
}

#Licz w bazie
function db_count($table)
{
	return (int)$GLOBALS['db']->query('SELECT COUNT(*) FROM '.PRE.$table)->fetchColumn();
}