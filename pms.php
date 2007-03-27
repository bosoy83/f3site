<?php
if(iCMS!='E123') exit;
require($catl.'pms.php');
if($_GET['id']) { $id=$_GET['id']; } else { $id=''; }
if($cfg['pms_on']==1 && LOGD==1)
{
 #Ilo¶æ
 $ilew=db_count('ID','pms',' WHERE owner='.UID);
 cTable($lang['pms_3'],1);
 echo '
 <tr>
  <td style="padding: 3px">
  <table width="100%" cellspacing="0" cellpadding="0"><tbody>
  <tr>
   <td colspan="2"><b>'.$lang['pms_4'].':</b></td>
   <td rowspan="5" style="width: 60%" align="center">'.(($user[UID]['pms']==0)?$lang['pms_nm0']:str_replace('%','<b>'.$user[UID]['pms'].'</b>',(($user[UID]['pms']==1)?$lang['pms_nm1']:$lang['pms_nm']))).'<br /><br />'.$lang['pms_15'].$ilew.' / '.$cfg['pm_limit'].'<br /><br /><input type="button" value="'.$lang['write'].'" onclick="location=\'?co=pms&amp;act=e\'" /></td>
  </tr>
  <tr>
   <td style="width: 23px"><img src="'.SCIMG.'" alt="[f]" /></td>
   <td><a href="?co=pms">'.$lang['pms_5'].'</a></td>
  </tr>
  <tr>
   <td><img src="'.SCIMG.'" alt="[f]" /></td>
   <td><a href="?co=pms&amp;id=1">'.$lang['pms_6'].'</a></td>
  </tr>
  <tr>
   <td><img src="'.SCIMG.'" alt="[f]" /></td>
   <td><a href="?co=pms&amp;id=2">'.$lang['pms_8'].'</a></td>
  </tr>
  <tr>
   <td><img src="'.SCIMG.'" alt="[f]" /></td>
   <td><a href="?co=pms&amp;id=3">'.$lang['pms_7'].'</a></td>
  </tr>
  </tbody></table>
  </td>
 </tr>';
 eTable();
 switch($_GET['act'])
 {
  case 'v': require('inc/pmv.php'); break;
  case 'e': require('inc/pme.php'); break;
  case 'm': require('inc/pmm.php'); break;
  default: require('inc/pml.php');
 }
}
elseif(LOGD==2)
{
 Info($lang['pms_2']);
}
else
{
 Info($lang['pms_1']);
}
?>
