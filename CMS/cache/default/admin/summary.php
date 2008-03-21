<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$lang['admin'];?></b></td>
</tr>
<tr>
<td><?= $intro ?><ul class="go">
<li><a href="?compile=1"><?=$lang['skincomp'];?></a></li>
<li><a href="http://kurshtml.boo.pl"><?=$lang['html_boo'];?></a></li>
<li><a href="http://www.famfamfam.com/lab/icons/silk">Silk Icons</a></li>
</ul>
<?php
if($warning)
{
echo '<p style="color: red">'.$warning.'</p>';
}
echo $_SERVER['SERVER_SOFTWARE'].'<br />'.$_SERVER['SERVER_SIGNATURE'].'<br />OS: '.$_ENV['OS'];
?>
</td>
</tr>
</tbody>
</table>