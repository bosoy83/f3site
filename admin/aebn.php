<?php
if(iCMSa!='X159E' || !ChPrv('B')) { exit; }
if(isset($_GET['id'])) { $id=$_GET['id']; } else { $id='new'; }
if($_POST['sav'])
{
 $xb_n=db_esc(TestForm($_POST['xb_n'],1,1,1));
 $xb_g=db_esc(TestForm($_POST['xb_g'],1,1,1));
 $xb_c=db_esc(TestForm($_POST['xb_c'],0,0,0));
 if($id=='new')
 {
  db_q('INSERT INTO {pre}banners VALUES ("","'.$xb_g.'","'.$xb_n.'","'.db_esc($_POST['xb_a']).'","'.$xb_c.'")');
 }
 else
 {
  db_q('UPDATE {pre}banners SET gen="'.$xb_g.'", name="'.$xb_n.'", ison="'.db_esc($_POST['xb_a']).'", code="'.$xb_c.'" WHERE ID='.$id);
 }
 Info($lang['saved']);
}
else
{
 if($id!='new')
 {
  db_read('*','banners','bnr','oa',' WHERE ID='.$id);
  if(!isset($bnr['ID'])) { exit('Banner nie istnieje! Banner doesn\'t exist!'); }
 }
 require($catl.'adm_o.php');
 require('inc/btn.php');
 ?>
<script type="text/javascript">
function WstO(co)
{
 a=prompt('<?= $lang['wwwp'] ?>','http://');
 if(a)
 {
  b=prompt('<?= $lang['adrib'] ?>','http://');
  if(b)
  {
   with(document.getElementById('xb_c')) { if(co==1) { value='<a href="'+a+'" target="_blank"><img src="'+b+'" border="0" /></a>'; } }
  }
 }
}
</script>
 <?php
 echo '<form action="?a=ebn'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
 cTable( (($id=='new')?$lang['addbn']:$lang['editbn']) ,2);
 echo '
 <tr>
  <td><b>1. '.$lang['name'].':</b></td>
  <td><input name="xb_n" value="'.$bnr['name'].'" maxlength="50" /></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['ap_acc'].':</b></td>
  <td><input type="radio" name="xb_a" value="1"'.(($bnr['ison']==1 || $id=='new')?' checked="checked"':'').' /> '.$lang['ap_ison'].' &nbsp;<input type="radio" name="xb_a" value="2"'.(($bnr['ison']==2)?' checked="checked"':'').' /> '.$lang['ap_isoff'].'</td>
 </tr>
 <tr>
  <td><b>3. '.$lang['genid'].':</b></td>
  <td><input name="xb_g" value="'.(($id=='new')?1:$bnr['gen']).'" style="width: 50px" /></td>
 </tr>
 <tr>
  <td><b>4. '.$lang['htmlc'].':</b></td>
  <td>'; Colors('xb_c',1); FontBtn('xb_c',1); echo '<textarea name="xb_c" id="xb_c" style="width: 90%; margin: 3px 0px 3px 0px" rows="7">'.htmlspecialchars($bnr['code']).'</textarea><br />'; Btns(1,1,'xb_c'); SpecChr('xb_c'); echo '</td>
 </tr>
 <tr>
  <td colspan="2" class="eth"><input type="submit" value="'.$lang['save'].'" name="sav" /></td>
 </tr>
 ';
 eTable();
 echo '</form>';
}
?>
