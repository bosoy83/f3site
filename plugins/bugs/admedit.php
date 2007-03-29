<?php
if(iCMSa!='X159E') exit;
$id=($_GET['id'])?$_GET['id']:'new';

#Zapis
if($_POST)
{
 $c_n=db_esc(TestForm($_POST['c_n'],1,1,0));
 $c_d=db_esc(TestForm($_POST['c_d'],1,1,0));
 $c_p=db_esc(TestForm($_POST['c_p'],1,1,0));
 $c_txt=db_esc(TestForm($_POST['c_txt'],0,0,0));
 
 #Nowy
 if($id=='new')
 {
	db_q('INSERT INTO {pre}bugcats VALUES ("","'.db_esc($_POST['c_s']).'","'.$c_n.'","'.$c_d.'","'.db_esc($_POST['c_a']).'","'.$c_p.'","'.db_esc($_POST['c_v']).'",0,NULL,"'.$c_txt.'")');
 }
 else
 {
  db_q('UPDATE {pre}bugcats SET sect="'.db_esc($_POST['c_s']).'", name="'.$c_n.'", dsc="'.$c_d.'", see="'.db_esc($_POST['c_a']).'", report="'.$c_p.'", trate="'.db_esc($_POST['c_v']).'", text="'.$c_txt.'" WHERE ID='.$id);
 }
 Info($lang['saved']);
}

#Form
else
{
 echo '<form action="?a=bugs&amp;act=e'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
 
 if($id=='new')
 {
	cTable($lang['ab_add'],2);
 }
 else
 {
  db_read('*','bugcats','cat','oa',' WHERE ID='.$id);
	cTable($lang['ab_ecat'],2);
 }
 
 echo '
 <tr>
  <td style="width: 25%"><b>1. '.$lang['title'].'</b></td>
	<td><input size="30" name="c_n" maxlength="40" value="'.$cat['name'].'" /></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['desc'].':</b></td>
	<td><input size="50" maxlength="80" value="'.$cat['dsc'].'" name="c_d" /></td>
 </tr>
 <tr>
  <td><b>3. '.$lang['ab_s'].':</b></td>
	<td><select name="c_s">
	 <option value="0">'.$lang['ab_nos'].'</option>';
	 
	 #Sekcje
	 $sect=array();
	 db_read('ID,title','bugsect','sect','tn',' ORDER BY seq');
	 $ile=count($sect);	 
	 
	 for($i=0;$i<$ile;$i++)
	 {
	  echo '<option value="'.$sect[$i][0].'"'.(($cat['sect']==$sect[$i][0])?' selected="selected"':'').'>'.$sect[$i][1].'</option>';
	 }
	 
	 echo '</select></td>
 </tr>
 <tr>
  <td><b>4. '.$lang['ap_acc'].':</b></td>
	<td><select name="c_a">
	 <option value="1">'.$lang['ap_isaon'].'</option>
	 '.sListBox('lang',1,$cat['see']).'
	 <option value="2">'.$lang['ap_isaoff'].'</option>
	</select></td>
 </tr>
 <tr>
  <td><b>5. '.$lang['rate'].':</b></td>
	<td>
	 <input type="radio" name="c_v" value="1"'.(($id=='new' || $cat['trate']==1)?' checked="checked"':'').' />
		<img src="plugins/bugs/thup.png" alt="UP" />
		<img src="plugins/bugs/thd.png" alt="D" />
		<br />
	 <input type="radio" name="c_v" value="2"'.(($cat['trate']==2)?' checked="checked"':'').' /> 1 - 5
		<br />
	 <input type="radio" name="c_v" value="0"'.(($cat['trate']==0)?' checked="checked"':'').' /> '.$lang['ap_isoff'].'</td>
 </tr>
 <tr>
  <td><b>6. '.$lang['ab_is'].'</b><div class="txtm">'.$lang['ab_isd'].'</div></td>
	<td><input name="c_p" maxlength="100" size="50" value="'.(($id=='new')?'LOGD':$cat['report']).'" /><div class="txtm">'.$lang['ab_rd'].'</div></td>
 </tr>
 <tr>
  <td><b>7. '.$lang['info'].':</b></td>
  <td>';
	include_once('inc/btn.php');
	Colors('c_txt',1);
	FontBtn('c_txt',1);
	echo '<textarea name="c_txt" id="c_txt" cols="50" rows="7">'.htmlspecialchars($cat['text']).'</textarea><br />';
	Btns(1,1,'c_txt');
	SpecChr('c_txt');
	echo '</td>
 </tr>
 <tr>
  <td colspan="2" class="eth"><input type="submit" value="OK" /></td>
 </tr>';
 eTable();
 echo '</form>';
}
?>