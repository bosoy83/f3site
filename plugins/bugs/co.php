<?php
if(iCMS!='E123') exit;
require_once('plugins/bugs/lang/'.$nlang.'.php');

#Prawa
function BugRights($x)
{
 global $user;
 switch($x)
 {
  case ''; case NULL: if(ChPrv('BUGS')) return true; break;
  case 'ALL': return true; break;
	case 'LOGD': if(LOGD==1) return true; break;
	default:
	 if(LOGD==1)
	 {
	  $r=explode(' ',$x);
	  if(in_array('U:'.UID,$r) || in_array('G:'.$user[UID]['gid'],$r)) return true;
	 }
 }
 return false;
}

#Nowe wpisy?
function BugIsNew($d1,$d2)
{
 if(empty($d1)) $d1=$_SESSION['recent'];
 if(empty($d2)) return false;
 if(strtotime($d2)>strtotime($d1)) { return true; } else { return false; }
}

#Modu³
if($cfg['bugs_on']==1)
{
 switch($_GET['act'])
 {
  case 'v': require('plugins/bugs/view.php'); break;
  case 'e': require('plugins/bugs/edit.php'); break;
  case 'l': require('plugins/bugs/list.php'); break;
  default: require('plugins/bugs/cats.php');
 }
}
else
{
 Info($lang['bugs_dis']);
}
?>