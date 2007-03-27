<?php
define('iCMS','E123');
define('HURL','Location: http://'.$_SERVER['HTTP_HOST'].str_replace('//','/',dirname($_SERVER['PHP_SELF']).'/'));
set_magic_quotes_runtime(0);

#ZABEZPIECZENIA
if(isset($_GET['id'])) { if(!is_numeric($_GET['id'])) exit('Wrong ID!'); }
if(isset($_GET['d'])) { if(!is_numeric($_GET['d'])) exit('$D error!'); }
if(isset($_GET['page'])) { if(!is_numeric($_GET['page'])) exit('Wrong page number!'); }
if(isset($_REQUEST['user']) || isset($_REQUEST['group']) || isset($_REQUEST['cfg']) || isset($dinfo) || isset($_REQUEST['lang']) || isset($_REQUEST['emodata'])) exit('Acces Violation');

#Modu³
if($_GET['co']) $_GET['co']=str_replace('/','',TestForm($_GET['co'],0,1,0,20));

#Baza
@require('cfg/db.php');
require('db/'.$db_db);

#£±czenie
$sqlc=0;
db_c($db_h,$db_u,$db_p,$db_d);

#Opcje
require('cfg/main.php');

#Data
$date=strftime($cfg['fdate']);
$time=getdate();

#Styl
if(isset($_COOKIE[$cfg['c'].'tstyle']))
{
  $nstyl=$_COOKIE[$cfg['c'].'tstyle'];
  $nstyl=str_replace('/','',$nstyl);
  $nstyl=str_replace('.','',$nstyl);
  if(is_dir('style/'.$nstyl)) { $catst='style/'.$nstyl.'/'; } else { $catst='style/'.$cfg['cms_styl'].'/'; }
}
else
{
  $catst='style/'.$cfg['cms_styl'].'/';
  $nstyl=$cfg['cms_styl'];
}

#Jêzyk
if($_COOKIE[$cfg['c'].'tlang'])
{
 $nlang=$_COOKIE[$cfg['c'].'tlang'];
 $nlang=str_replace('\'','',$nlang);
 $nlang=str_replace('"','',$nlang);
 if(is_dir('lang/'.$nlang)) { $catl='lang/'.$nlang.'/'; } else { $catl='lang/'.$cms_lang.'/'; $nlang=$cms_lang; }
}
else
{
 $catl='lang/'.$cms_lang.'/';
 $nlang=$cms_lang;
 #Auto?
 if($cfg['lng']==1) 
 {
  $x=explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$ile=count($x);
	for($i=0;$i<$ile&&$i<10;$i++)
	{
	 $x=str_replace('\'','',$x);
	 $x=str_replace('"','',$x);
	 if(strpos($x[$i],';')) $x[$i]=substr($x[$i],0,strpos($x[$i],';'));
	 if(is_dir('lang/'.$x[$i])) { $catl='lang/'.$x[$i].'/'; $nlang=$x[$i]; break; }
	}
	unset($x,$ile);
 }
}
$nlang=str_replace('/','',$nlang);
$nlang=str_replace('.','',$nlang);

if(defined('LOGD')) exit('Login problem!');

#Bany
if(!empty($cfg['ban']))
{
 $ban=explode("\n",$cfg['ban']);
 if(in_array($_SERVER['REMOTE_ADDR'],$ban))
 {
  $lang=Array();
	define('SPECIAL',21);
  require('special.php');
  exit;
 }
}

#Sesja
session_name($cfg['c']);
@ini_set('session.gc_maxlifetime',3600);
session_start();

#Zalogowany?
if(isset($_SESSION['uid']))
{
 $xuid=$_SESSION['uid'];
 if(is_numeric($xuid) && $_SESSION['ip']==$_SERVER['REMOTE_ADDR']) {
  db_read('*','users','user',$xuid,' WHERE lv!=5 AND ID='.$xuid);
  if(isset($user[$xuid]['ID']))
  {
   #Test
   if($_SESSION['uidl']===$user[$xuid]['login'] && $_SESSION['uidp']===$user[$xuid]['pass'])
   {
    define('LOGD',1);
    define('UID',$xuid);
   }
	 else
	 {
	  unset($user);
	 }
  }
 }
 unset($xuid);
}
#Auto-login
elseif($_COOKIE[$cfg['c'].'login'])
{
 $xuid=explode(':',$_COOKIE[$cfg['c'].'login']);
 if(is_numeric($xuid[0]))
 {
  db_read('*','users','user',$xuid[0],' WHERE lv!=5 AND ID='.$xuid[0]);
  if(isset($user[$xuid[0]]['ID']))
  {
   #Test
   if($xuid[1]===$user[$xuid[0]]['pass'])
   {
    define('LOGD',1);
    define('UID',$xuid[0]);
   }
	 else
	 {
	  unset($user);
	 }
  }
 }
 unset($xuid);
}
#Ost. wizyta
if(LOGD==1)
{
 if($cfg['lastv']==1)
 {
	db_q('UPDATE '.$db_pre.'users SET lvis="'.strftime('%Y-%m-%d %H:%M:%S').'" WHERE ID='.UID);
	#Zapisz datê do sesji
	if(!$_SESSION['recent']) $_SESSION['recent']=$user[UID]['lvis'];
 }
}
else
{
 #Nie usuwaj!
 define('LOGD',2);
 define('UID',0);
}

require($catl.'cms.php');
require($catst.'global.php');

#FUNKCJE

#Prawa
function ChPrv($co)
{
 global $user,$yrights;
 if(LOGD==1)
 {
  if($user[UID]['lv']==3)
  {
   return true;
  }
  else
  {
   if(!isset($yrights)) { $yrights=explode('|',$user[UID]['adm']); }
   if(in_array($co,$yrights)) { return true; } else { return false; }
  }
 }
}

#Struktura kategorii
function CatStr($c)
{
 global $cfg,$lang,$dinfo,$nlang;
 if($cfg['cstr']!=1 || strstr($dinfo['opt'],'S') || $_GET['om']==1 || !isset($dinfo['sc'])) return false;
 $y='';
 echo '<div class="cs"><a href="?co=cats&amp;id='.$dinfo['type'].'">';
 switch($dinfo['type'])
 {
  case 1: echo $lang['arts']; break;
  case 2: echo $lang['files']; break;
  case 3: echo $lang['gallery']; break;
  case 4: echo $lang['links']; break;
  default: echo $lang['news']; break;
 }
 echo '</a>';
 if($dinfo['sc']!='P')
 {
  $i=$dinfo['sc'];
  while($i!='P') {
   unset($cs);
   db_read('ID,name,sc','cats','cs','on',' WHERE ID='.$i.' && (access=1 || access="'.$nlang.'")');
   global $cs;
   if(isset($cs))
   {
    $y=' &raquo; <a href="?d='.$cs[0].'">'.$cs[1].'</a>'.$y;
    $i=$cs[2];
   }
   else
   {
    break;
   }
  }
 }
 echo $y.(($c==1)?' &raquo; <a href="?d='.$dinfo['ID'].'">'.$dinfo['name'].'</a>':'').'</div>';
 unset($cs,$y,$i);
}

#Skanowanie katalogu
function sListBox($cat,$co,$ch) {
 if($copen=opendir($cat))
 {
  while(false !== ($dirq=readdir($copen)))
  {
   if($co==1) {
    #Katalogi
    if(is_dir($cat.'/'.$dirq) && $dirq!='.' && $dirq!='..')
    {
     $xtm.= '<option'.(($ch==$dirq)?' selected="selected"':'').'>'.$dirq.'</option>';
    }
   }
   else {
    if(is_file($cat.'/'.$dirq))
    {
     $dirq=str_replace('.php','',$dirq);
     $xtm.= '<option'.(($ch==$dirq)?' selected="selected"':'').'>'.$dirq.'</option>';
    }
   }
  }
  closedir($copen);
} return $xtm; }

#Strony
function Pages($co,$ile,$np,$l,$t) {
 global $lang;
 $ilep=ceil($ile / $np);
 $xt=(($t==1)?'<select onchange="location=\''.$l.'&amp;page=\'+(this.selectedIndex+1)">':'<table cellspacing="4" cellpadding="0"><tbody><tr><td>'.$lang['page'].':</td>');
 for($y=0;$y<$ilep;$y++) {
  $yy=$y+1;
  if($t==1) {
    $xt.='<option class="pgs"'.(($co==$yy)?' selected="selected"':'').'>'.$yy.'</option>';
  }
  else {
    $xt.='<td class="'.(($co==$yy)?'apag"><span style="font-size: 8px">&nbsp;</span>'.$yy.'<span style="font-size: 8px">&nbsp;</span>':'upag"><a href="'.$l.'&amp;page='.$yy.'"><span style="font-size: 8px">&nbsp;</span>'.$yy.'<span style="font-size: 8px">&nbsp;</span></a>').'</td>';
  }
 }
 $xt.=(($t==1)?'</select> '.$lang['of'].' '.$ilep:'</tr></tbody></table>');
 return $xt;
}

#Bannery
function Banners($co)
{
 db_read('code','banners','bnr','on',' WHERE gen="'.$co.'" AND ison=1 ORDER BY RAND() LIMIT 1');
 return $GLOBALS['bnr'][0];
}

#Oceny
function Rating($co,$c)
{
 global $lang;
 if($co!=null && $co!='0|0') {
  $x=explode('|',$co);
  return '<b>'.round(($x[0] / $x[1]),1).'</b> / 5'.(($c==1)?' ('.$lang['rates'].': '.$x[1].')':'');
 }
 else {
  return $lang['lack'];
 }
}

#Emoty
function Emots($wh) {
 global $emodata;
 include_once('cfg/emots.php');
 $ile=count($emodata);
 if($ile>0)
 {
  for($n=0;$n<$ile;$n++)
  {
   $wh=str_replace($emodata[$n][2],'<img src="img/emo/'.$emodata[$n][1].'" title="'.$emodata[$n][0].'" alt="'.$emodata[$n][2].'" border="0" />',$wh);
  }
  unset($ile,$n);
 }
 return $wh;
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
 return $d; }

#Autorzy
function Autor($v) {
 global $user;
 if(is_numeric($v)) {
  if(!isset($user[$v])) db_read('login','users','user',$v,' WHERE ID='.$v);
  if($user[$v]['login']) { return '<a href="index.php?co=user&amp;id='.$v.'">'.$user[$v]['login'].'</a>'; } else { return $v; }
 }
 else {
  return $v; }
}

#TestForm
function TestForm($val,$c,$s,$t,$max=0) {
 if($max) { if(strlen($val)>$max && is_numeric($max)) $val=substr($val,0,$max); }
 if($t==1) { $val=strip_tags($val); }
 if($c==1) { $val=trim(htmlspecialchars($val,ENT_QUOTES)); }
 if($s==1) { $val=str_replace('\\','',$val); } elseif(get_magic_quotes_gpc()) { $val=stripslashes($val); }
 return $val;
}

#Cenzura
function Words($t) {
 global $cfg;
 if($cfg['wordc']==1)
 {
  @include_once('cfg/words.php');
  return str_replace($words1,$words2,$t);
 }
 else
 {
  return $t;
 }
}
?>