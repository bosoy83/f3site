<?php /* J±dro systemu */
define('iCMS',1);
define('VER',3);
define('URL','http://'.$_SERVER['HTTP_HOST'].str_replace('//','/',dirname($_SERVER['PHP_SELF']).'/'));
//set_magic_quotes_runtime(0);
//date_default_timezone_set('Europe/Paris');

#Register globals: usuñ zmienne
if(ini_get('register_globals'))
{
	foreach(array_keys($_REQUEST) as $x) unset($$x);
}

#ID do zmienej ($id zawsze istnieje)
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;

#Nr strony
if(isset($_GET['page']) && !is_numeric($_GET['page']))
{
	$_GET['page'] = 1; //Nr strony
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

#Data
define('TODAY', strftime($cfg['date']));
define('NOW', $_SERVER['REQUEST_TIME']);
define('DATETIME', '\''.strftime('%Y-%m-%d %H:%M:%S').'\'');

#Skóra
if(isset($_COOKIE[PRE.'tstyle']))
{
	$nstyl = str_replace('/', '', $_COOKIE[PRE.'tstyle']);
	if(!is_dir('./style/'.$nstyl))
	{
		$nstyl = $cfg['skin'];
	}
}
else
{
	$nstyl = $cfg['skin'];
}

#Katalog skórki
define('SKIN_DIR', './style/'.$nstyl.'/');
define('VIEW_DIR', './cache/view/');

#Sesja
session_name(PRE);
session_start();

#Jêzyk: zmiana
if(isset($_GET['setlang']) && ctype_alnum($_GET['setlang']) && is_dir('./lang/'.$_GET['setlang']))
{
	$nlang = $_GET['setlang'];
	setcookie(PRE.'tlang', $nlang, time()+12960000); //Ustaw na 5 mies.
}
#Jêzyk: sesja
elseif(isset($_SESSION['lang']))
{
	$nlang = $_SESSION['lang'];
}
#Jêzyk: cookies
elseif(isset($_COOKIE[PRE.'tlang']))
{
	if(ctype_alnum($_COOKIE[PRE.'tlang']) && is_dir('./lang/'.$_COOKIE[PRE.'tlang']))
	{
		$nlang = $_SESSION['lang'] = $_COOKIE[PRE.'tlang'];
	}
}
#Autowykrywanie jêzyka
elseif($cfg['detectLang']===1)
{
	foreach(explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $x)
	{
		if(isset($x[3]))
		{
			$x = $x[1].$x[2];
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

#Do³±cz klasê skórek i utwórz obiekt
require './lib/content.php';
$content = new Content;

#Po³±cz z baz± danych
try
{
	#SQLite
	if($db_db=='sqlite')
	{
		$db = new PDO('sqlite:'.$db_d);
		define('DB_RAND', 'RANDOM()');
	}
	#MySQL
	else
	{
		$db = new PDO('mysql:host='.$db_h.';dbname='.$db_d,$db_u,$db_p); //Potem: $cfg['sqlpc']
		$db-> exec('SET CHARACTER SET "latin2"'); //!!!!!!!!!!!!
		define('DB_RAND', 'RAND()');
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
		FROM '.PRE.'users WHERE lv!=0 AND ID='.$xuid) -> fetch(2); //ASSOC
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
		$db->exec('UPDATE '.PRE.'users SET lvis='.NOW.' WHERE ID='.UID);
		$_SESSION['recent'] = $user[UID]['lvis'];
	}
}
else
{
	#Nie usuwaj!
	define('LOGD',2);
	define('UID',0);
}

#Typ,kodowanie
Header('Cache-Control: public');
Header('Content-type: text/html; charset=iso-8859-2');

#FUNKCJE

#Prawa ($a - autor)
function Admit($id,$type=null,$a=null)
{
	if(LOGD!=1) return false; //Go¶æ?
	global $user;
	static $global;

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
			return db_get('`all`','acl',' WHERE CatID='.(int)$id.' && UID='.UID.
			' && type="'.$type.'"'.(($a==null || $a==UID)?'':' && `all`=1')); //1 = wszystkie
		}
	}
	else
	{
		if(!isset($global)) $global=explode('|',$user[UID]['adm']);
		if(in_array($id,$global)) { return true; } else { return false; }
	}
}

#Struktura kategorii
function CatPath($id)
{
	return file_get_contents('./cache/cat'.$id.'.php');
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
function Banners($gid)
{
	if(is_numeric($gid))
	{
		$res=$GLOBALS['db']->query('SELECT code FROM '.PRE.'banners
			WHERE gen='.$gid.' AND ison=1 ORDER BY '.DB_RAND.' LIMIT 1');
		return $res->FetchColumn();
	}
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
function genDate($x, $hour=true)
{
	static $time;
	if(!isset($time)) $time=getdate();

	$f=explode(' ',$x);
	$e=explode('-',$f[0]);

	if(empty($f[1])) return $x;

	if($e[2]==$time['mday'] && $e[1]==$time['mon'] && $e[0]==$time['year'])
	{
		$d=$GLOBALS['lang']['today'];
	}
	else
	{
		$d=str_replace('%d',$e[2],$GLOBALS['cfg']['dateFormat']);
		$d=str_replace('%m',$e[1],$d);
		$d=str_replace('%y',$e[0],$d);
	}
	if($hour && !empty($f[1]) && $f[1]!='00:00:00')
	{
		$g=explode(':',$f[1]);
		$d.=str_replace('%h',$g[0],$GLOBALS['cfg']['timeFormat']);
		$d=str_replace('%i',$g[1],$d);
		$d=str_replace('%s',$g[2],$d);
	}
	return $d;
}

#Autorzy
function Autor($v)
{
	global $user,$db;
	if(is_numeric($v)) {
		if(!isset($user[$v])) {
			$res=$db->query('SELECT login FROM '.PRE.'users WHERE ID='.$v);
			$user[$v]=$res->fetch(2);
		}
		if($user[$v]['login']) {
			return '<a href="index.php?co=user&amp;id='.$v.'">'.$user[$v]['login'].'</a>';
		}
		else { return $v; }
	}
	else { return $v; }
}

#Encje + trim()
function Clean($val,$max=0,$wr=0)
{
	if($max) $val=substr($val,0,$max);
	if($wr && $GLOBALS['cfg']['censor']==1)
	{
		static $words1,$words2;
		include_once('./cfg/words.php');
		$val=str_replace($words1,$words2,$val); //Zamiana s³ów
	}
	return trim(htmlspecialchars($val));
}

#Licz w bazie
function db_count($co,$table)
{
	return (int)$GLOBALS['db']->query('SELECT COUNT('.$co.') FROM '.PRE.$table)->fetchColumn();
}

#Pobierz warto¶æ z bazy
function db_get($co,$table,$o='')
{
	return $GLOBALS['db']->query('SELECT '.$co.' FROM '.PRE.$table.$o)->fetchColumn();
}