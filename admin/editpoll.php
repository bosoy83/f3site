<?php
if(iCMSa!='X159E') exit;
$id=(isset($_GET['id']))?$_GET['id']:'new';
require($catl.'f3s.php');

#Zapis
if($_POST['sav'])
{
 extract($_POST);
 $xp_n=db_esc(TestForm($xp_n,1,1,0));
 $xp_q=db_esc(TestForm($xp_q,1,1,0));
 $ile=count($xp_an);
 $del=array();
 
 if($id=='new')
 {
  db_q('INSERT INTO {pre}polls (name,q,ison,type,num,access,date) VALUES ("'.$xp_n.'","'.$xp_q.'",'.(int)$xp_i.','.(int)$xp_t.',0,"'.db_esc($xp_a).'","'.strftime('%Y-%m-%d').'")');
  $id=db_id();
 }
 else
 {
  db_q('UPDATE {pre}polls SET name="'.$xp_n.'", q="'.$xp_q.'", ison='.(int)$xp_i.', type='.(int)$xp_t.', access="'.db_esc($xp_a).'" WHERE ID='.$id);
 }

 #Opcje
 for($i=0;$i<$ile;$i++)
 {
	if(!is_numeric($xp_id[$i])) exit;

	#Dodaj
	if($xp_id[$i]==0 && is_numeric($xp_seq[$i]))
	{
	 db_q('INSERT INTO {pre}answers (ID,IDP,seq,a,num) VALUES('.$xp_id[$i].','.$id.','.$xp_seq[$i].',"'.db_esc(TestForm($xp_an[$i],1,1,0)).'",0)');
	}
	
	#Zmieñ/usuñ
	else
	{
	 if(is_numeric($xp_seq[$i]))
	 {
		db_q('UPDATE {pre}answers SET seq='.$xp_seq[$i].', a="'.db_esc(TestForm($xp_an[$i],1,1,0)).'" WHERE ID='.$xp_id[$i]);
	 }
	 else
	 {
		$del[]=$xp_id[$i];
	 }
	}
 }
 if($del) db_q('DELETE FROM {pre}answers WHERE ID IN ('.join(',',$del).')');
 
 Info('<center>'.$lang['saved'].'<br /><br /><a href="?a=editpoll">'.$lang['addpoll'].'</a> | <a href="index.php?co=poll&amp;id='.$id.'">'.$xp_n.'</a></center>');
}

#Form
if(!$_POST['sav'])
{
 unset($poll,$an);
 
 #Odczyt
 if($id!='new')
 {
  db_read('*','polls','poll','oa',' WHERE ID='.$id);
  if(empty($poll['ID'])) exit('Sonda nie istnieje! Poll doesn\'t exists!');
	$ile=db_read('ID,a,num','answers','an','tn',' WHERE IDP='.$id.' ORDER BY seq');
 }
 else { $ile=3; }
 ?>
 <script type="text/javascript">
 <!--
 ileusr=<?=$ile?>;
 function Dodaj() { ii=ileusr+1; document.getElementById("odp"+ileusr).innerHTML='<?=$lang['answ']?> <input name="xp_seq[]" size="1" value="'+ii+'" /> <input name="xp_an[]" size="30" /> <input type="hidden" name="xp_id[]" value="0" /><br /><div id="odp'+ii+'"></div>'; ileusr++; }
 -->
 </script>
 <?php
 echo '<form action="?a=editpoll'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
 cTable( (($id=='new')?$lang['addpoll']:$lang['editpoll']),2);
 echo '
 <tr>
  <td><b>1. '.$lang['name'].':</b></td>
  <td><input name="xp_n" maxlength="50" value="'.$poll['name'].'" /></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['que'].':</b></td>
  <td><input name="xp_q" maxlength="150" value="'.$poll['q'].'" style="width: 80%" /></td>
 </tr>
 <tr>
  <td><b>3. '.$lang['allowv'].'?</b></td>
  <td><input name="xp_i" type="radio" value="1"'.(($poll['ison']==1 || $id=='new')?' checked="checked"':'').' /> '.$lang['yes'].' &nbsp;<input type="radio" name="xp_i" value="2"'.(($poll['ison']==2)?' checked="checked"':'').' /> '.$lang['no'].' &nbsp;<input type="radio" name="xp_i" value="3"'.(($poll['ison']==3)?' checked="checked"':'').' /> '.$lang['forregt'].'</td>
 </tr>
 <tr>
  <td><b>4. '.$lang['lang'].':</b></td>
  <td><select name="xp_a">'.sListBox('lang',1,$poll['access']).'</select></td>
 </tr>
 <tr>
  <td><b>5. '.$lang['ap_type'].':</b></td>
  <td><input type="radio" name="xp_t" value="1"'.(($poll['type']==1 || $id=='new')?' checked="checked"':'').' /> '.$lang['ap_max1'].' &nbsp;<input type="radio" name="xp_t" value="2"'.(($poll['type']==2)?' checked="checked"':'').' /> '.$lang['ap_maxd'].'</td>
 </tr>';
 eTable();
 
 #Odp.
 cTable($lang['answs'],1);
 echo '<tr><td align="center">'.$lang['ppuo'].'<br /><br /><div id="odp0"></div>';
 for($i=0;$i<$ile;++$i)
 {
	$xid=$an[$i][0];
  echo '<div id="odp'.$i.'">'.$lang['answ'].'
	<input name="xp_seq[]" value="'.($i+1).'" size="1" />
	<input name="xp_an[]" value="'.$an[$i][1].'" size="30" />
	<input type="hidden" name="xp_id[]" value="'.(($id=='new')?0:$an[$i][0]).'" />
	</div>';
 }
 echo '
 <div id="odp'.$i.'"></div>'; ?>
 <br />
 <div align="center">
  <a href="javascript:Dodaj()"><b><?=$lang['addans']?></b></a>
 </div>
 <br />
 </td></tr>
 <tr class="eth">
  <td><input type="submit" name="sav" value="<?=$lang['save']?>" /></td>
 </tr>
 <?php
 eTable();
 echo '</form>';
}
?>
