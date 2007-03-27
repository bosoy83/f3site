<?php
if(iCMS!='E123') { exit; }
if(!isset($d))
{
 if(isset($_GET['id']) && $_GET['id']<10) { $id=$_GET['id']; } else { $id=1; }
 db_read('ID,name,dsc,nums','cats','cat','tn',' WHERE (access=1 || access="'.db_esc($nlang).'") AND sc="P" AND type='.$id.' ORDER BY name');
 $ile=count($cat);
}
else
{
 if(!defined('D')) exit;
}
if($ile>0)
{
 cTable($lang['cats'],2);
 for($i=0;$i<$ile;$i++)
 {
  $xcat=$cat[$i];
  echo '
  <tr>
   <td style="width: 40px" align="center" valign="center"><img src="'.CATIMG.'" alt="CAT" /></td>
   <td><a class="listlink" href="?d='.$xcat[0].'">'.$xcat[1].'</a> ('.$xcat[3].')<br /><span class="txtm">'.$xcat[2].'</span></td>
  </tr>
  ';
 }
 eTable();
}
elseif(!isset($d))
{
 Info($lang['nocats']);
}
unset($cat,$xcat,$ile);
?>
