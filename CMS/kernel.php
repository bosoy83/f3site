<?php
define('iCMS',1);
define('VER',3);
define('URL','http://'.$_SERVER['HTTP_HOST'].str_replace('//','/',dirname($_SERVER['PHP_SELF']).'/'));
set_magic_quotes_runtime(0);
date_default_timezone_set('Europe/Paris'); ///POPRAIWÆ TO!!!!!!!!!

#ZABEZPIECZENIA
if(isset($_GET['id'])) { if(!is_numeric($_GET['id'])) exit('Wrong ID!'); }
if(isset($_GET['page'])) { if(!is_numeric($_GET['page'])) exit('Wrong page number!'); }
if(isset($user) || isset($group) || isset($cfg) || isset($cat)) exit('Acces Violation');

#Baza
require('./cfg/db.php');
try
{
	#SQLite
	if($db_db=='sqlite')
	{
		$db=new PDO('sqlite:'.$db_h);
		define('DB_RAND','RANDOM()');
	}
	#MySQL
	else
	{
		$db=new PDO('mysql:host='.$db_h.';dbname='.$db_d,$db_u,$db_p);
		$db->exec('SET CHARACTER SET "latin2"'); //!!!!!!!!!!!!
		define('DB_RAND','RAND()');
	}
	$db->setAttribute(3,2); #ERRMODE: Exceptions
	$db->setAttribute(19,2); #DefaultFetchMode: ASSOC
}
catch(PDOException $e)
{
	echo $e->getCode().': Cannot connect to database.';
}

#Opcje
require('./cfg/main.php');

#Data
define('TODAY',strftime($cfg['fdate']));
define('NOW',strftime('%Y-%m-%d %H:%m:%i'));
$time=getdate();

#Skóra
if(isset($_COOKIE[$cfg['c'].'tstyle']))
{
	$nstyl=$_COOKIE[$cfg['c'].'tstyle'];
	$nstyl=str_replace('/','',$nstyl);
	$nstyl=str_replace('.','',$nstyl);
	if(!is_dir('./style/'.$nstyl))
	{
		$nstyl=$cfg['cms_styl'];
	}
}
else
{
	$nstyl=$cfg['cms_styl'];
}

#Szablony
$catst='./style/'.$nstyl.'/';

#Jêzyk
if(isset($_GET['setlang']))
{
	$nlang=Clean($_GET['setlang'],3);
	setcookie($cfg['c'].'l',$nlang,time()+12960000); //Ustaw na 5 mies.
}
elseif(isset($_COOKIE[$cfg['c'].'tlang']))
{
	$nlang=$_COOKIE[$cfg['c'].'tlang'];
	$nlang=str_replace(array('\'','"'),'',$nlang);
	if(!is_dir('./lang/'.$nlang))
	{
		$nlang=$cfg['lang'];
	}
}
else
{
	$nlang=$cfg['lang'];
	#Auto?
	if($cfg['lng']==1)
	{
		$x=explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$ile=count($x);
		for($i=0;$i<$ile&&$i<10;++$i)
		{
			if($x[$i])
			{
				$x[$i]=str_replace( array('\'','"','/'), '', $x[$i]);
				if(strpos($x[$i],';')) $x[$i]=substr($x[$i],0,strpos($x[$i],';'));
				if(is_dir('./lang/'.$x[$i])) { $nlang=$x[$i]; break; }
			}
		}
		unset($x,$ile);
	}
}
$nlang=str_replace('/','',$nlang);
$nlang=str_replace('.','',$nlang);
$catl='./lang/'.$nlang.'/';

#Bany
if(!empty($cfg['ban']))
{
	$ban=explode("\n",$cfg['ban']);
	if(in_array($_SERVER['REMOTE_ADDR'],$ban))
	{
		Header('Location: '.$cfg['banurl']);
		exit;
	}
}

#Sesja
session_name($cfg['c']);
session_start();

$xuid=false;

#U¿ytkownik - pamiêtanie
if(isset($_COOKIE[$cfg['c'].'login']))
{
	$usrc=$_COOKIE[$cfg['c'].'login'];
	if($pos=strpos($usrc,':'))
	{
		$xuid=substr($usrc,0,$pos);
		$xpass=substr($usrc,++$pos);
	}
	unset($usrc,$pos);
}

#U¿ytkownik - sesja
elseif(isset($_SESSION['uid']))
{
	if($_SESSION['ip']===$_SESSION['REMOTE_ADDR'])
	{
		$xuid=$_SESSION['uid'];
		$xpass=$_SESSION['uidp'];
	}
	else
	{
		session_regenerate_id(1);
	}
}

#Dane poprawne?
if(is_numeric($xuid))
{
	if(isset($_SESSION['userdata']))
	{
		$user[$xuid]=$_SESSION['userdata'];
	}
	else
	{
		$res=$db->query('SELECT login,pass,gid,lv,adm,lvis,pms
		FROM '.PRE.'users WHERE lv!=0 AND ID='.$xuid);

		$user[$xuid]=$res->fetch(2);
		$res=null;
		$_SESSION['userdata']=$user[$xuid];
	}

	#Test
	if(isset($user[$xuid]))
	{
		if($xpass===$user[$xuid]['pass'])
		{
			define('LOGD',1);
			define('UID',$xuid);
			define('LEVEL',$user[UID]['lv']);
			define('GID',$user[UID]['gid']);
		}
		else
		{
			unset($user);
		}
	}
}
unset($xuid,$xpass);

#Ost. wizyta
if(defined('LOGD'))
{
	if($cfg['lastv']==1)
	{
		$db->exec('UPDATE '.PRE.'users SET lvis="'.strftime('%Y-%m-%d %H:%M:%S').'" WHERE ID='.UID);
		#Zapisz datê do sesji
		if(!isset($_SESSION['recent'])) $_SESSION['recent']=$user[UID]['lvis'];
	}
}
else
{
	#Nie usuwaj!
	define('LOGD',2);
	define('UID',0);
}

require($catl.'main.php');
require('style/'.$nstyl.'/global.php');

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
		return db_get('all','acl',' WHERE CatID='.(int)$id.' && UID='.UID.
			' && type="'.$type.'"'.(($a==null || $a==UID)?'':' && all=1')); //1 = wszystkie
	}
	else
	{
		if(!isset($global)) $global=explode('|',$user[UID]['adm']);
		if(in_array($id,$global)) { return true; } else { return false; }
	}
}

#Struktura kategorii
function CatPath($id=0,$type,$code='')
{
	global $cat,$nlang;
	if($GLOBALS['cfg']['cstr']!=1 || !($cat['opt']&1)) return false;

	#Dodatkowy kod
	$out='<div class="cs">'.(($code=='')?'':'<div style="float: right; clear: both">'.$code.'</div>').'<a href="?co=cats&amp;id='.$cat['type'].'">'.$type.'</a>';

	#Nadkategorie
	if($cat['sc']!=0)
	{
		$res=$GLOBALS['db']->query('SELECT ID,name FROM '.PRE.'cats WHERE lft<'.$cat['lft'].
		' && rgt>'.$cat['rgt'].' && (access=1 || access="'.$nlang.'") ORDER BY lft DESC');

		foreach($res as $c)
			$out.=' &raquo; <a href="?d='.$c['ID'].'">'.$c['name'].'</a>';
	}

	echo $out.(($id)?' &raquo; <a href="?d='.$id.'">'.$cat['name'].'</a>':'').'</div>';
}

#Skanowanie katalogu
function ListBox($cat,$co,$ch)
{
	if($open=opendir($cat)) {
		$out='';
		while(false !== ($dir=readdir($open))) {

			if($co==1) {
				#Katalogi
				if(is_dir($cat.'/'.$dir) && strpos($dir,'.')!==0)
				{
					$out.= '<option'.(($ch==$dir)?' selected="selected"':'').'>'.$dir.'</option>';
				}
			}
			else {
				if(is_file($cat.'/'.$dir))
				{
					$dir=str_replace('.php','',$dir);
					$out.= '<option'.(($ch==$dir)?' selected="selected"':'').'>'.$dir.'</option>';
				}
			}
		}
		closedir($open);
	} return $out;
}

#Strony
function Pages($page,$ile,$max,$url,$type=2)
{
	global $lang;
	$stron=ceil($ile / $max);
	$out=($type==1)?'<select onchange="location=\''.$url.'&amp;page=\'+(this.selectedIndex+1)">':'<table cellspacing="4" cellpadding="0"><tbody><tr><td>'.$lang['page'].':</td>';

	for($i=1;$i<=$stron;++$i)
	{
		if($type==1)
		{
			$out.='<option class="pgs"'.(($page==$i)?' selected="selected"':'').'>'.$i.'</option>';
		}
		else
		{
			$out.='<td class="'.(($page==$i)?'apag"><span style="font-size: 8px">&nbsp;</span>'.$i.'<span style="font-size: 8px">&nbsp;</span>':'upag"><a href="'.$url.'&amp;page='.$i.'"><span style="font-size: 8px">&nbsp;</span>'.$i.'<span style="font-size: 8px">&nbsp;</span></a>').'</td>';
		}
	}
	return $out.(($type==1)?'</select> '.$lang['of'].' '.$stron:'</tr></tbody></table>');
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
function genDate($co)
{
	global $time,$cfg,$lang;
	$f=explode(' ',$co);
	$e=explode('-',$f[0]);
	if($e[2]==$time['mday'] && $e[1]==$time['mon'] && $e[0]==$time['year'])
	{
		$d=$lang['today'];
	}
	else
	{
		$d=str_replace('%d',$e[2],$cfg['fdate1']);
		$d=str_replace('%m',$e[1],$d);
		$d=str_replace('%y',$e[0],$d);
	}
	if(!empty($f[1]) && $f[1]!='00:00:00')
	{
		$g=explode(':',$f[1]);
		$d.=str_replace('%h',$g[0],$cfg['fdate2']);
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
	if($wr && $GLOBALS['cfg']['wordc']==1)
	{
		static $words1,$words2;
		include_once('./cfg/words.php');
		$val=str_replace($words1,$words2,$val); //Zamiana s³ów
	}
	return trim(htmlspecialchars($val));
}

#Do³±cz JS
function Init($f)
{
	static $done;
	if(!isset($done[$f])) { echo '<script type="text/javascript" src="'.$f.'"></script>'; $done[$f]=1; }
}

#Licz w bazie
function db_count($co,$table,$o='')
{
	return (int)$GLOBALS['db']->query('SELECT COUNT('.$co.') FROM '.PRE.$table.$o)->fetchColumn();
}

#Pobierz warto¶æ z bazy
function db_get($co,$table,$o='')
{
	return $GLOBALS['db']->query('SELECT '.$co.' FROM '.PRE.$table.$o)->fetchColumn();
}
?>
