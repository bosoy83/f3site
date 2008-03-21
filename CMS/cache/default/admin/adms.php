<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="5"><b><?=$lang['admins'];?></b></td>
</tr>
<tr>
<th>'.$lang['login'].'</th>
<th style="width: 40%">'.$lang['privs'].'</th>
<th>'.$lang['opt'].'</th>
</tr>

<?php foreach($admins as $admin): ?>

<tr>
<td><a href="index.php?co=user&amp;id='.$admin[0].'">'.$admin[1].'</a></td>
<td align="center">'.str_replace('|',' ',$admin[2]).'</td>
<td align="center">
'.$lang['edit'].':
<a href="?a=editadm&amp;id='.$admin[0].'">'.$lang['privs'].'</a> &middot; 
<a href="?a=edituser&amp;id='.$admin[0].'">'.$lang['profile'].'</a>
</td>
</tr>

<?php endforeach ?>