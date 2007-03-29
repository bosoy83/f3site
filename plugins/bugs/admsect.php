<?php
if(iCMSa!='X159E') exit;

#Zapis sekcji
if($_POST)
{
 $ile=count($_POST['s_q']);
 for($i=0;$i<$ile;$i++)
 {
  $s_t=db_esc(TestForm($_POST['s_t'][$i],1,1,0));
	$id=$_POST['s_i'][$i];
	$q=$_POST['s_q'][$i];
  if(is_numeric($id))
	{
	 #Edytuj
	 if($q=='')
	 {
	  db_q('DELETE FROM {pre}bugsect WHERE ID='.$id);
	 }
	 #Usuñ
	 else
	 {
	  db_q('UPDATE {pre}bugsect SET title="'.$s_t.'", seq='.$q.' WHERE ID='.$id);
	 }
	}
	#Nowy
	else
	{
	 if(!is_numeric($q)) $q=0;
	 if(!$_POST['s_d'][$i]) db_q('INSERT INTO {pre}bugsect VALUES ("","'.$q.'","'.$s_t.'")');
	}
 }
}

#SQL
$sect=array();
db_read('*','bugsect','sect','ta',' ORDER BY seq');
$ile=count($sect);
?>

<script type="text/javascript">
<!--
ile=<?=$ile?>;
function NS()
{
 d("s"+ile).innerHTML='<?=$lang['ap_mkol']?> <input type="hidden" name="s_i[]" /><input size="5" value="'+(ile+1)+'" name="s_q[]" maxlength="50" /> &nbsp;<?=$lang['title']?>: <input size="50" name="s_t[]" maxlength="30" /><div id="s'+(ile+1)+'"></div>';
 ile++;
}
-->
</script>

<form action="?a=bugs&amp;act=s" method="post">
<?php
#Sekcje
cTable($lang['ab_s'],1);
echo '<tr><td align="center">';

for($i=0;$i<$ile;$i++)
{
 echo
 $lang['ap_mkol'].'
  <input type="hidden" value="'.$sect[$i]['ID'].'" name="s_i[]" />
	<input size="5" value="'.$sect[$i]['seq'].'" name="s_q[]" maxlength="3" />&nbsp;
 '.$lang['title'].':
	<input size="50" value="'.$sect[$i]['title'].'" name="s_t[]" maxlength="30" />
 <br />';
}

echo '
 <div id="s'.$i.'"></div>
 <center style="padding: 3px">'.$lang['ab_del'].'</center>
 </td>
</tr>
<tr>
 <td class="eth">
	<input type="button" value="'.$lang['add'].'" onclick="NS()" />
	<input type="submit" value="'.$lang['save'].'" />
 </td>
</tr>';
eTable();
?>
</form>