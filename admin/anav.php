<?php
if(iCMSa!='X159E' || !ChPrv('NM')) exit;
require($catl.'adm_o.php');

if($_POST)
{
 #Bloki
 if($_POST['savg'])
 {
  $ile=count($_POST['m_s']);
  for($i=0;$i<$ile;$i++)
  {
   $ii=$i+1;
   db_q('UPDATE {pre}menu SET seq="'.db_esc($_POST['m_s'][$i]).'", disp="'.db_esc($_POST['m_vis'][$i]).'",  menu="'.db_esc($_POST['m_page'][$i]).'" WHERE ID='.$_POST['m_id'][$i]);
  }
 }
 
 #1 blok
 else
 {
  $m_tit=db_esc(TestForm($_POST['m_tit'],0,1,0));
	$m_txt=db_esc(TestForm($_POST['m_txt'],0,1,0));
	$m_img=db_esc(TestForm($_POST['m_img'],1,1,0));
  
	if($_GET['id'] && !$_POST['savenew'])
	{
	 $id=$_GET['id'];
   db_q('UPDATE {pre}menu SET text="'.$m_tit.'", disp="'.db_esc($_POST['m_vis']).'", menu="'.db_esc($_POST['m_page']).'", type="'.db_esc($_POST['m_type']).'", img="'.$m_img.'", value="'.$m_txt.'" WHERE ID='.$id);
   db_q('DELETE FROM {pre}mitems WHERE menu='.$id);
  }
  else
  {
   db_q('INSERT INTO {pre}menu VALUES ("",'.(db_count('ID','menu','')+1).',"'.$m_tit.'","'.db_esc($_POST['m_vis']).'","'.db_esc($_POST['m_page']).'","'.db_esc($_POST['m_type']).'","'.$m_img.'","'.$m_txt.'")');
   $id=db_id();
  }
  #Linki
  if($_POST['m_type']==3)
  {
   $ile=count($_POST['i_seq']);
   for($i=0;$i<$ile;$i++)
	 {
    if(!empty($_POST['i_seq'][$i]))
	  {
     $i_txt[$i]=db_esc(TestForm($_POST['i_txt'][$i],0,1,0));
     $i_adr[$i]=db_esc(TestForm($_POST['i_adr'][$i],1,1,0));
     db_q('INSERT INTO {pre}mitems VALUES ("'.db_esc($_POST['i_seq'][$i]).'",'.$id.',"'.db_esc($_POST['i_type'][$i]).'","'.$i_txt[$i].'","'.$i_adr[$i].'",'.(($_POST['i_nw'][$i])?1:2).')');
    }
   }
  }
  unset($id,$m_tit,$m_txt,$m_img,$i_txt,$i_adr);
 }
 
 #Gen.
 require('admin/mcache.php');
}
?>
<form method="post" action="?a=nav">
<?php
cTable($lang['ap_navs'],5);
echo '<tr>
 <th>'.$lang['ap_txt'].'</th>
 <th style="width: 30px">'.$lang['ap_seq'].'</th>
 <th style="width: 100px">'.$lang['ap_mvis'].'</th>
 <th style="width: 100px">'.$lang['ap_page'].'</th>
 <th>'.$lang['opt'].'</th>
</tr>';

db_read('ID,seq,text,disp,menu,type','menu','m','ta',' ORDER BY seq');
$ile=count($m);
for($i=0;$i<$ile;$i++)
{
 echo '<tr>
  <td>'.(($cfg['num']==1)?($i+1).'. ':'').$m[$i]['text'].'</td>
  <td align="center"><input name="m_s['.$i.']" onblur="if(this.value==0) { this.value=\''.$m[$i]['seq'].'\' }" class="itm" value="'.$m[$i]['seq'].'" /><input type="hidden" name="m_id['.$i.']" value="'.$m[$i]['ID'].'" /></td>
  <td align="center"><select name="m_vis['.$i.']"><option value="1">'.$lang['ap_ison'].'</option>'.sListBox('lang',1,$m[$i]['disp']).'<option value="2"'.(($m[$i]['disp']==2)?' selected="selected"':'').'>'.$lang['ap_isoff'].'</option></select></td>
  <td align="center">&larr;<input type="radio" value="1" name="m_page['.$i.']"'.(($m[$i]['menu']==1 || !isset($m[$i]['menu']))?' checked="checked"':'').' /> &nbsp;<input type="radio" value="2" name="m_page['.$i.']"'.(($m[$i]['menu']==2)?' checked="checked"':'').' /> &rarr;</td>
  <td align="center"><a href="adm.php?a=enav&amp;id='.$m[$i]['ID'].'">'.$lang['edit'].'</a>'.((ChPrv('DEL'))?' &middot; <a href="javascript:a=confirm(\''.$lang['ap_delc'].'\'); if(a) { location=\'adm.php?x=del&amp;co=nav&amp;id='.$m[$i]['ID'].'\' } void(0)">'.$lang['del'].'</a>':'').'</td>
 </tr>';
}
echo '<tr class="eth">
 <td colspan="5"><input type="submit" name="savg" value="'.$lang['save'].'" /> <input type="button" value="'.$lang['ap_navnewm'].'" onclick="location=\'adm.php?a=enav\'" /></td>
</tr>';
eTable();
?>
</form>