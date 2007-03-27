<?php
if(iCMSa!='X159E' || !ChPrv('B')) exit;
require($catl.'adm_o.php');
Info($lang['bnrinfo'].'<br /><br /><div align="center"><a href="?a=ebn">'.$lang['addbn'].'</a></div>');
db_read('ID,gen,name,ison','banners','bnr','tn',' ORDER BY gen,name');
echo '
<script type="text/javascript">
<!--
function Del(co)
{
 a=confirm("'.$lang['ap_delc'].'");
 if(a) { location="?x=del&co=b&id="+co; }
}
-->
</script>
';
cTable($lang['banners'],4);
echo '
<tr>
 <th>'.$lang['name'].'</th>
 <th style="width: 80px">'.$lang['ap_disp'].'</th>
 <th style="width: 50px">GID</th>
 <th>'.$lang['opt'].'</th>
</tr>
';
$ile=count($bnr);
for($i=0;$i<$ile;$i++)
{
 echo '
 <tr align="center">
  <td align="left">'.(($cfg['num']==1)?($i+1).'. ':'').$bnr[$i][2].'</td>
  <td>'.(($bnr[$i][3]==1)?$lang['ap_ison']:$lang['ap_isoff']).'</td>
  <td>'.$bnr[$i][1].'</td>
  <td><a href="?a=ebn&amp;id='.$bnr[$i][0].'">'.$lang['edit'].'</a>'.((ChPrv('DEL'))?' &middot; <a href="javascript:Del('.$bnr[$i][0].')">'.$lang['del'].'</a>':'').'</td>
 </tr>
 ';
}
eTable();
?>
