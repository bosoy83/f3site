<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr>
 <td class="th"><b><?= $lang['comms'] ?></b></td>
</tr>

<?php for($i=0;$i<$ile;++$i) echo '
<tr>
<td class="'.$comm[$i]['class'].'">
	<b>'.$comm[$i]['name'].'</b>
	<div><small>'.$comm[$i]['date'].' &middot; '.$comm[$i]['author'].

	(($comm[$i]['rights']) ? ' (<a href="'.$comm[$i]['del_url'].'">'.$lang['del'].'</a> | <a href="'.$comm[$i]['edit_url'].'">'.$lang['edit'].'</a> | '.$comm[$i]['ip'].')' : '').

	'</small></div>'.$comm[$i]['text'].'
</td>
</tr>';

//Strony
if($pages) echo '<tr><td align="center">'.$pages.'</td></tr>';
?>

</tbody></table>