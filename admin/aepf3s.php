<?php
if(iCMSa!='X159E') exit;
$id=((isset($_GET['id']))?$_GET['id']:'new');
require($catl.'f3s.php');
#Zapis
if($_POST['sav'])
{
 $xp_n=db_esc(TestForm($_POST['xp_n'],1,1,1));
 $xp_q=db_esc(TestForm($_POST['xp_q'],0,1,0));
 $xile=count($_POST['xp_an']);
 if($id=='new')
 {
  db_q('INSERT INTO {pre}polls VALUES ("","'.$xp_n.'","'.$xp_q.'","'.db_esc($_POST['xp_i']).'","'.db_esc($_POST['xp_t']).'",0,"'.db_esc($_POST['xp_a']).'","'.strftime('%Y-%m-%d').'")');
  $xnum=db_id();
 }
 else
 {
  db_q('UPDATE {pre}polls SET name="'.$xp_n.'", q="'.$xp_q.'", ison="'.db_esc($_POST['xp_i']).'", type="'.db_esc($_POST['xp_t']).'", access="'.db_esc($_POST['xp_a']).'" WHERE ID='.$id);
  $xnum=$id;
  db_q('DELETE FROM {pre}answers WHERE IDP='.$id);
 }
 #Odp.
 for($i=0;$i<$xile;$i++)
 {
  if(!empty($_POST['xp_seq'][$i]))
  {
   db_q('INSERT INTO {pre}answers VALUES('.$xnum.',"'.$_POST['xp_seq'][$i].'","'.db_esc(TestForm($_POST['xp_an'][$i],1,1,1)).'",'.((is_numeric($_POST['xp_av'][$i]))?$_POST['xp_av'][$i]:0).')');
  }
 }
 Info('<div align="center">'.$lang['saved'].'<br /><br /><a href="?a=epf3s">'.$lang['addpoll'].'</a></div>');
}
#Form
if(!$_POST['sav'])
{
 #Odczyt
 if($id!='new')
 {
  db_read('*','polls','poll','oa',' WHERE ID='.$id);
  if(empty($poll['ID'])) { exit('Sonda nie istnieje! Poll doesn\'t exists!'); }
  db_read('seq,a,num','answers','an','tn',' WHERE IDP='.$id.' ORDER BY seq');
  $ile=count($an);
 }
 else {
  $ile=3;
 }
 echo '<script type="text/javascript">
 <!--
 ileusr='.$ile.';
 function Dodaj() { ii=ileusr+1; document.getElementById("odp"+ileusr).innerHTML=\''.$lang['answ'].' <input name="xp_seq[\'+ileusr+\']" style="width: 20px" value="\'+ii+\'" /> <input id="xp_an[\'+ileusr+\']" name="xp_an[\'+ileusr+\']" style="width: 234px" /><br /><span id="odp\'+ii+\'"></span>\'; ileusr++; }
 -->
 </script>
 <form action="?a=epf3s'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
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
 </tr>
 ';
 eTable();
 cTable($lang['answs'],1);
 echo '<tr><td align="center">'.$lang['ppuo'].'<br /><br /><span id="odp0"></span>';
 #Odp.
 for($i=0;$i<$ile;$i++)
 {
  $ii=$i+1;
  echo '<span id="odp'.$i.'">'.$lang['answ'].' <input name="xp_seq['.$i.']" value="'.(($id=='new')?$ii:$an[$i][0]).'" style="width: 20px" /> <input name="xp_an['.$i.']" value="'.$an[$i][1].'" style="width: 195px" id="xp_an['.$i.']" /> <input readonly="readonly" style="width: 30px" name="xp_av['.$i.']" value="'.(($id=='new')?0:$an[$i][2]).'" /><br />';
 }
 echo '
 <span id="odp'.$ii.'"></span>
 <span style="font-size: 5px"><br /></span>
 <div align="center">
  <a href="javascript:Dodaj()"><b>'.$lang['addans'].'</b></a>
 </div>
 <br />
 </td></tr>
 <tr class="eth">
  <td><input type="submit" name="sav" value="'.$lang['save'].'" /></td>
 </tr>
 ';
 eTable();
 echo '</form>';
}
?>
