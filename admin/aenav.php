<?php
if(iCMSa!='X159E' || !ChPrv('NM') || $_REQUEST['m']) exit;
require($catl.'adm_o.php');
if(isset($_GET['id'])) { $id=$_GET['id']; } else { $id='new'; }

#Odczyt
if($id!='new')
{
 db_read('*','menu','m','oa',' WHERE ID='.$id);
 if(!isset($m['ID'])) exit;
 #Typ: linki
 if($m['type']==3)
 {
	db_read('*','mitems','eitm','ta',' WHERE menu='.$_GET['id'].' ORDER BY seq');
  $ile=count($eitm);
 }
 else
 {
  $ile=1;
	$eitm[0]['seq']=1;
 }
}
else
{
 $ile=1;
 $eitm[0]['seq']=1;
}
?>

<script type="text/javascript">
<!--
ile=<?=$ile?>;
function Dodaj()
{
 for(i=0;i<ile;i++)
 {
	ii=ile+1;
	document.getElementById("itm"+ile).innerHTML='<table><tbody><tr><td><?= $lang['ap_mkol'] ?><br /><input name="i_seq['+ile+']" value="'+ii+'" class="itm" />&nbsp;</td><td><?= $lang['ap_txt'] ?><br /><input style="width: 170px" name="i_txt['+ile+']" />&nbsp;</td><td><?= $lang['ap_type'] ?>:<br /><select name="i_type['+ile+']"><option value="1"><?= $lang['ap_navadr'] ?></option><option value="2"><?= $lang['ap_navcat'] ?></option><option value="3"><?= $lang['ap_navpage'] ?></option></select>&nbsp;</td><td><?= $lang['ap_adr'] ?> / ID<br /><input name="i_adr['+ile+']" style="width: 180px" /></td><td><br /><input type="checkbox" name="i_nw['+ile+']" /></td></tr></tbody></table><div id="itm'+ii+'" align="center"></div>';
 }
 ile++;
}
-->
</script>

<?php
echo '<form action="adm.php?a=nav'.(($id!='new')?'&amp;id='.$id:'').'" method="post">';
cTable((($id=='new')?$lang['ap_navbn']:$lang['ap_navbe']),2);
echo '<tr>
 <td width="38%"><b>1. '.$lang['ap_navbtit'].'</b></td>
 <td><input name="m_tit" maxlength="50" value="'.htmlspecialchars($m['text']).'" /></td>
</tr>
<tr>
 <td><b>2. '.$lang['ap_acc'].':</b></td>
 <td><select name="m_vis"><option value="1">'.$lang['ap_isaon'].'</option>'.sListBox('lang',1,$m['disp']).'<option value="2"'.(($m['disp']==2)?' selected="selected"':'').'>'.$lang['ap_isahid'].'</option></select></td>
</tr>
<tr>
 <td><b>3. '.$lang['ap_page'].':</b></td>
 <td><input type="radio" value="1" name="m_page"'.(($m['menu']!=2)?' checked="checked"':'').' /> '.$lang['ap_leftp'].' &nbsp;<input type="radio" value="2" name="m_page"'.(($m['menu']==2)?' checked="checked"':'').' /> '.$lang['ap_rightp'].'</td>
</tr>
<tr>
 <td><b>4. '.$lang['ap_mtype'].':</b></td>
 <td><input type="radio" value="1" name="m_type"'.(($m['type']==1)?' checked="checked"':'').' /> '.$lang['ap_txt'].' &nbsp;<input type="radio" value="2" name="m_type"'.(($m['type']==2)?' checked="checked"':'').' /> '.$lang['ap_file'].' &nbsp;<input type="radio" value="3" name="m_type"'.(($m['type']==3 || $id=='new')?' checked="checked"':'').' /> '.$lang['ap_urls'].'</td>
</tr>
<tr>
 <td><b>5. '.$lang['bgimg'].':</b><br /><span class="txtm">'.$lang['0off'].'</span></td>
 <td><input id="m_img" name="m_img" maxlength="200" value="'.(($id=='new')?'0':$m['img']).'" />'.((ChPrv('FM'))?' <input type="button" value="'.$lang['files'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=m_img\',580,400,150,150)" />':'').'</td>
</tr>
<tr>
 <td><b>6. '.$lang['ap_txtfile'].'</b><br /><span class="txtm">'.$lang['ap_nmlink'].'</span></td>
 <td><textarea rows="4" name="m_txt" cols="45">'.htmlspecialchars($m['value']).'</textarea></td>
</tr>
<tr>
 <th colspan="2"><b>'.$lang['ap_navbody'].'</b></td>
</tr>
<tr>
 <td colspan="2">';
 
if($ile==0) echo('<div id="itm0" align="center"></div>');
#Linki
for($g=0;$g<$ile;$g++)
 {
  $ig=$g+1;
  echo '<div id="itm'.$g.'" align="center">
  <table><tbody>
  <tr>
   <td>'.$lang['ap_mkol'].'<br /><input value="'.$eitm[$g]['seq'].'" name="i_seq['.$g.']" class="itm" />&nbsp;</td>
   <td>'.$lang['ap_txt'].'<br /><input name="i_txt['.$g.']" maxlength="50" value="'.htmlspecialchars($eitm[$g]['text']).'" style="width: 170px" />&nbsp;</td>
   <td>'.$lang['ap_type'].':<br /><select name="i_type['.$g.']"><option value="1">'.$lang['ap_navadr'].'</option><option value="2"'.(($eitm[$g]['type']==2)?' selected="selected"':'').'>'.$lang['ap_navcat'].'</option><option value="3"'.(($eitm[$g]['type']==3)?' selected="selected"':'').'>'.$lang['ap_navpage'].'</option></select>&nbsp;</td>
   <td>'.$lang['ap_adr'].' / ID:<br /><input name="i_adr['.$g.']" maxlength="255" value="'.$eitm[$g]['url'].'" style="width: 180px" /></td>
   <td><br /><input type="checkbox" name="i_nw['.$g.']"'.(($eitm[$g]['nw']==1)?' checked="checked"':'').' /></td>
  </tr>
  </tbody></table>
 </div>';
 }
 echo '<div id="itm'.$ig.'" align="center"></div>
 <br />
 <center>
  <a href="javascript:Dodaj()"><b>'.$lang['ap_navadd'].'</b></a>
  <br /><br />
  <input type="checkbox" checked="checked" disabled="disabled" /> = '.$lang['ap_nwn'].'
 </center>
 <br />'.$lang['ap_whdelmi'].'
 </td>
</tr>
<tr class="eth">
 <td colspan="2"><input type="submit" value="'.$lang['save'].'" /> <input type="submit" value="'.$lang['savenew'].'" name="savenew" /></td>
</tr>';
eTable();
?>
