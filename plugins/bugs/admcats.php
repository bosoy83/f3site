<?php
if(iCMSa!='X159E') exit;

#Zapis
if($_POST && count($_POST['chk'])>0)
{
 $ids=GetIDs($_POST['chk']);
 if($_POST['ch_d'])
 {
  if(ChPrv('DEL'))
	{
	 db_q('DELETE FROM {pre}bugcats WHERE ID='.join(' || ID=',$ids));
	}
 }
 else
 {
  $_q=Array();
  if($_POST['ch_s']!='N') $_q[]='sect='.db_esc($_POST['ch_s']);
  if($_POST['ch_a']!='N') $_q[]='see="'.db_esc($_POST['ch_a']).'"';
	echo 'UPDATE {pre}bugcats SET '.join(', ',$_q).' WHERE ID IN('.join(',',$ids).')';
	if(count($_q)>0) db_q('UPDATE {pre}bugcats SET '.join(', ',$_q).' WHERE ID IN('.join(',',$ids).')');
 }
 unset($_POST,$ids,$_q);
}

#SQL
$cat=array();
$sect='';
db_read('c.ID,c.name,c.see,c.num,s.title','bugcats c LEFT JOIN {pre}bugsect s ON c.sect=s.ID','cat','ta',' ORDER BY s.seq,c.name');
$ile=count($cat);

#Info
Info($lang['ab_info'].'<br /><br /><center><a href="?a=bugs&amp;act=e">'.$lang['ab_add'].'</a> | <a href="?a=bugs&amp;act=s">'.$lang['ab_mans'].'</a> | <a href="?a=bugs&amp;act=o">'.$lang['opt'].'</a></center>');
?>

<form action="?a=bugs" method="post">
<?php
#Kategorie
cTable($lang['cats'],5);
echo '
<tr>
 <th>'.$lang['name'].'</th><th style="width: 40px">ID</th><th style="width: 50px">'.$lang['ap_ison'].'</th><th style="width: 170px">'.$lang['opt'].'</th><th style="width: 20px"></th>
</tr>';

for($i=0;$i<$ile;$i++)
{
 $c=&$cat[$i];
 #Sekcja
 if($c['title']!=$sect)
 {
	$sect=$c['title'];
	echo '<tr><th colspan="5">'.$sect.'</th></tr>';
 }
 echo '
 <tr>
  <td>'.$c['name'].' ('.$c['num'].')</td>
	<td align="center">'.$c['ID'].'</td>
	<td align="center">';
	switch($c['see'])
	{
	 case 1: echo $lang['yes']; break;
	 case 2: echo $lang['no']; break;
	 default: echo $c['see'];
	}
	echo '</td>
	<td align="center"><a href="?a=bugs&amp;act=e&amp;id='.$c['ID'].'">'.$lang['edit'].'</a>'.((ChPrv('DEL'))?' &middot; <a href="?a=bugs&amp;del='.$c['ID'].'">'.$lang['del'].'</a>':'').'</td>
	<td align="center"><input type="checkbox" name="chk['.$c['ID'].']" /></td>
 </tr>';
}
eTable();

#Zaznaczone
cTable($lang['ab_cho'],2);
echo '
<tr>
 <td style="width: 30%"><b>1. '.$lang['ab_s'].':</b></td>
 <td><select name="ch_s"><option value="N">'.$lang['noch'].'</option>';
 
 #Sekcje
 $sect=array();
 db_read('ID,title','bugsect','sect','tn',' ORDER BY seq');
 $ile=count($sect);
 for($i=0;$i<$ile;$i++)
 {
  echo '<option value="'.$sect[$i][0].'">'.$sect[$i][1].'</option>';
 }
 
 echo '</select></td>
</tr>
<tr>
 <td><b>2. '.$lang['ap_acc'].':</b></td>
 <td><select name="ch_a"><option value="N">'.$lang['noch'].'</option><option value="1">'.$lang['ap_isaon'].'</option>'.sListBox('lang',1,'').'<option value="2">'.$lang['ap_isaoff'].'</option></select></td>
</tr>
<tr>
 <td><b>3. '.$lang['opt'].':</b></td>
 <td><input type="checkbox" name="ch_d" /> '.$lang['delch'].'</td>
</tr>
<tr>
 <td class="eth" colspan="2"><input type="submit" value="OK" /></td>
</tr>';
eTable();
?>
</form>