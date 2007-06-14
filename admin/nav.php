<?php
if(iCMSa!='X159E' || !ChPrv('NM')) exit;
require($catl.'adm_o.php');
if(ChPrv('DEL')) { $del=1; } else { $del=0; }

#Zapis bloków
if($_POST)
{
 if($_POST['savg'])
 {
  $ile=count($_POST['m_s']);
  for($i=0;$i<$ile;$i++)
  {
   $ii=$i+1;
   db_q('UPDATE {pre}menu SET seq="'.db_esc($_POST['m_s'][$i]).'", disp="'.db_esc($_POST['m_vis'][$i]).'",  menu="'.db_esc($_POST['m_page'][$i]).'" WHERE ID='.$_POST['m_id'][$i]);
  }
 }
 
 #Odbuduj menu
 if($cfg['mc']==1) require('./admin/inc/mcache.php');
}
?>
<script type="text/javascript">
<!--
function Del(id)
{
 if(confirm("<?=$lang['ap_delc']?>"))
 {
	del=new Request("adm.php?x=del&id="+id,'i'+id);
	del.method='POST';
	del.add('co','nav')
	del.run()
 }
}
-->
</script>

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

db_read('ID,seq,text,disp,menu,type','menu','m','ta',' ORDER BY menu,seq');
$ile=count($m);
for($i=0,$ii=0;$i<$ile;$i++)
{
 echo '<tr>
	<td id="i'.$m[$i]['ID'].'">'.(($cfg['num']==1)?(++$ii).'. ':'').$m[$i]['text'].'</td>
	<td align="center">
		<input name="m_s['.$i.']" onblur="if(value==\'\') value=0" class="itm" value="'.$m[$i]['seq'].'" />
		<input type="hidden" name="m_id['.$i.']" value="'.$m[$i]['ID'].'" />
	</td>
  <td align="center">
		<select name="m_vis['.$i.']">
			<option value="1">'.$lang['ap_ison'].'</option>
			'.sListBox('lang',1,$m[$i]['disp']).'
			<option value="2"'.(($m[$i]['disp']==2)?' selected="selected"':'').'>'.$lang['ap_isoff'].'</option>
		</select>
	</td>
  <td align="center">
		&larr;<input type="radio" value="1" name="m_page['.$i.']"'.(($m[$i]['menu']!=2)?' checked="checked"':'').' />
		&nbsp;<input type="radio" value="2" name="m_page['.$i.']"'.(($m[$i]['menu']==2)?' checked="checked"':'').' /> &rarr;
	</td>
  <td align="center">
		<a href="adm.php?a=editnav&amp;id='.$m[$i]['ID'].'">'.$lang['edit'].'</a>
		'.(($del==1)?' &middot; <a href="javascript:Del('.$m[$i]['ID'].')">'.$lang['del'].'</a>':'').'
	</td>
 </tr>';
}
echo '<tr>
 <td colspan="5" class="eth">
	<input type="submit" name="savg" value="'.$lang['save'].'" />
	<input type="button" value="'.$lang['ap_navnewm'].'" onclick="location=\'adm.php?a=enav\'" />
 </td>
</tr>';
eTable();
?>
</form>