<?php
if(iCMSa!=1 || !Admit('LOG')) exit;

#Usuñ?
if($_POST)
{
	$x=GetIDs($_POST['item']);
	if($x) $db->exec('DELETE FROM '.PRE.'log WHERE ID IN ('.join(',',$x).')');
}

#Strona
if(isset($_GET['page']) && $_GET['page']!=1)
{
	$page=$_GET['page'];
	$st=($page-1)*30;
}
else
{
	$page=1;
	$st=0;
}

#Suma
$total=db_count('ID','log');

#Pobierz
$res=$db->query('SELECT l.*,u.login FROM '.PRE.'log l LEFT JOIN '.PRE.'users u
	ON l.user=u.ID AND l.user!=0 LIMIT '.$st.',30');
$res->setFetchMode(2); //Assoc

#Lista
echo '<form action="?a=log&amp;page='.$page.'" method="post">';
OpenBox('Log',4);

echo '<tr>
	<th>'.$lang['title'].'</th>
	<th>'.$lang['added'].'</th>
	<th>'.$lang['user'].'</th>
	<th style="width: 30px"><input type="checkbox" onclick="x=document.getElementsByTagName(\'input\'); for(i in x) { if(x[i].type==\'checkbox\') { if(this.checked) { x[i].checked=true } else { x[i].checked=false } } }" /></th>
</tr>';

foreach($res as $log)
{
	echo '<tr>
	<td>'.$log['name'].'</td>
	<td align="center">'.genDate($log['date']).'</td>
	<td align="center">'.(($log['user']==0)?$log['ip']:'<a href="index.php?co=user&amp;id='.$log['user'].'" title="IP: '.$log['ip'].'">'.$log['login'].'</a>').'</td>
	<td align="center"><input type="checkbox" name="item['.$log['ID'].']" /></td>
</tr>';
}

echo '<tr>
	<td colspan="2" class="eth"><input type="button" value="'.$lang['del'].'" onclick="if(confirm(\''.$lang['ap_delc'].'\')) submit()" /></td>
	<td colspan="2" class="eth">'.$lang['page'].': '.Pages($page,$total,30,'?a=log',1).'</td>
</tr>';

$res=null;
CloseBox();
?>
</form>
