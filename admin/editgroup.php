<?php
if(iCMSa!='X159E' || !ChPrv('UGR')) exit;
if($_GET['id']) { $id=$_GET['id']; } else { $id='new'; }
#Zapis
if($_POST)
{
 $g_n=db_esc(TestForm($_POST['g_n'],1,1,0));
 $g_d=db_esc(TestForm($_POST['g_d'],0,1,0));
 #Nowy
 if($id=='new')
 {
  db_q('INSERT INTO {pre}groups VALUES ("","'.$g_n.'","'.$g_d.'","'.db_esc($_POST['g_a']).'",'.(($_POST['g_o'])?1:2).')');
 }
 #Edycja
 else
 {
  db_q('UPDATE {pre}groups SET name="'.$g_n.'", dsc="'.$g_d.'", access="'.db_esc($_POST['g_a']).'", opened='.(($_POST['g_o'])?1:2).' WHERE ID='.$id);
 }
 Info($lang['saved']);
}
#Form
else
{
require($catl.'adm_o.php');
echo '<form action="?a=editgroup'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
#Lista
if($id!='new')
{
 db_read('*','groups','group','oa',' WHERE ID='.$id);
 cTable($lang['gredit'],2);
}
else
{
 cTable($lang['gradd'],2);
}
include_once('inc/btn.php');
echo '<tr>
 <td style="width: 30%"><b>1. '.$lang['name'].':</b></td>
 <td><input name="g_n" maxlength="50" value="'.$group['name'].'" /></td>
</tr>
<tr>
 <td><b>2. '.$lang['ap_acc'].':</b></td>
 <td><select name="g_a"><option value="1">'.$lang['ap_isaon'].'</option>'.sListBox('lang',1,$group['access']).'<option value="2"'.(($group['access']==2)?' selected="selected"':'').'>'.$lang['ap_isahid'].'</option></select></td>
</tr>
<tr>
 <td><b>3. '.$lang['opened'].'?</b></td>
 <td><input name="g_o" type="checkbox"'.(($group['opened']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>4. '.$lang['desc'].':</b></td>
 <td>'; Colors('g_d',1); FontBtn('g_d',1); echo '<br /><textarea id="g_d" name="g_d" rows="6" style="margin: 3px 0px" cols="45">'.$group['dsc'].'</textarea><br />'; Btns(1,1,'g_d'); SpecChr('g_d'); echo '</td>
</tr>
<tr>
 <td class="eth" colspan="2"><input type="submit" value="'.$lang['save'].'" /></td>
</tr>';
eTable();
echo '</form>';
}
?>
