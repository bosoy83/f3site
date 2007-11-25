<?php
if(iCMS!=1) exit;
include('./cfg/c.php');

#Lista nowo¶ci
if(isset($_GET['id']))
{
	$date=explode('.',$_GET['id']);
	$res=$db->query('SELECT ID,name,date FROM '.PRE.'news WHERE YEAR(date)='.$date[0].
		((!empty($date[1]) && !$cfg['archyear'])?' && MONTH(date)='.$date[1]:'').' && access=1 ORDER BY ID DESC');
	$res->setFetchMode(3);

	OpenBox($lang['arch'],2);
	echo '
<tr>
	<th>'.$lang['title'].'</th>
	<th>'.$lang['added'].'</th>
</tr>';

	$i=0;
	foreach($res as $news)
	{
		echo '
<tr>
	<td>'.++$i.'. <a href="?co=news&amp;id='.$news[0].'">'.$news[1].'</a></td>
	<td align="center">'.genDate($news[2]).'</td>
</tr>';
	}
	$res=null;
	CloseBox();
}

#Daty
else
{
	$res=$db->query('SELECT date FROM '.PRE.'news LIMIT 1');
	$date=$res->fetchColumn();
	$res=null;

	OpenBox($lang['arch'],1);
	echo '<tr><td align="center" class="txt" style="line-height: 150%">';

	$date=explode('-',$date);

	#Usuñ 0
	if($date[1]<10) $date[1]=str_replace('0','',$date[1]);

	#Miesi±c i rok
  $m=$time['mon'];
  $y=$time['year'];

	#Lata
	if($cfg['archyear']==1)
	{
		do { echo '<a href="?co=arch&amp;id='.$y.'">'.$y.'</a><br />'; --$y; }
		while( $y>$date[0] && $y>0 );
	}
	#Miesi±ce
	elseif($date[1])
  {
		do {
			echo '<a href="?co=arch&amp;id='.$y.'.'.$m.'">'.$mlang[$m].' '.$y.'</a><br />';
			if($m==1) { $m=12; --$y; echo '<br />'; } else --$m;
		}
		while( $y!=$date[0] || $m!=$date[1] );
  }

	echo '</td></tr>';
	CloseBox();
	unset($y,$m,$date);
}
?>
