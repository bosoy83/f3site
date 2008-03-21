<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td colspan="2" class="h"><b><?=$u['login'];?></b></td>
</tr>
<tr>
<td style="width: 30%"><b><?=$lang['joined'];?>:</b></td>
<td><?=$join_date;?></td>
</tr>
<tr>
<td><b><?=$lang['lastv'];?>:</b></td>
<td><?=$last_visit;?></td>
</tr>
<tr>
<td><b><?=$lang['ufrom'];?></b></td>
<td id="testx"><?=$u['city'];?></td>
</tr>
<tr>
<td><b><?=$lang['wwwp'];?>:</b></td>
<td><?php if($u['www']){ ?><a href="<?=$u['www'];?>"><?=$u['www'];?></a><?php }else{ NA;  } ?></td>
</tr>
<tr>
 <td><b><?=$lang['mail'];?>:</b></td>
 <td><?php if($u['mvis']){ ?><a href="<?=$u['mail'];?>"><?=$u['mail'];?></a><?php }else{ $lang['private'];  } ?></td>
</tr>
<tr>
<td><b>Gadu-Gadu:</b></td>
<td>
<?php if($u['gg']){ ?>
<a href="gg:<?=$u['gg'];?>"><img src="http://status.gadu-gadu.pl/users/status.asp?id=<?=$u['gg'];?>" alt="GG" /></a> <?=$u['gg'];?>
<?php }else{?>
<?=NA;?>
<?php } ?>
</td>
</tr>
<tr>
<td><b>Tlen.pl:</b></td>
<td>
<?php if($u['tlen']){ ?>
<a href="http://ludzie.tlen.pl/<?=$u['tlen'];?>/" target="_blank">
<img src="http://status.tlen.pl/?u=<?=$u['tlen'];?>&amp;t=1" alt="TLEN" />
</a>
<?php }else{?>
<?=NA;?>
<?php } ?>
</td>
</tr>
<tr>
<td><b>ICQ:</b></td>
<td>
<?php if($u['icq']){ ?>
<a href="http://www.icq.com/people/about_me.php?uin=<?=$u['icq'];?>">
<img src="http://status.icq.com/online.gif?icq=<?=$u['icq'];?>&amp;img=5" alt="ICQ" />
</a>
<?php }else{?>
<?=NA;?>
<?php } ?>
</td>
</tr>
<tr>
<td><b>Skype:</b></td>
<td>
<?php if($u['skype']){ ?>
<!-- Skype My status button http://www.skype.com/go/skypebuttons -->
<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>
<img src="http://mystatus.skype.com/smallclassic/<?=$u['skype'];?>" style="cursor: pointer" onclick="if(confirm('<?=$lang['callq']; $u['skype'];?>?')) location='skype:<?=$u['skype'];?>?call'" alt="My status" />
<?php }else{?>
<?=NA;?>
<?php } ?>
</td>
</tr>
<tr>
<td><b><?=$lang['opt'];?>:</b></td>
<td style="padding: 10px">
<?php if($pm_url){ ?>&bull; <a href="<?=$pm_url;?>"><?=$lang['send_pm'];?></a><?php } ?>
</td>
</tr>
</tbody>
</table>

<?php if($u['about']){ ?>
<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h"><b><?=$lang['abouty'];?></b></td>
</tr>
<tr>
<td class="txt"><?=$u['about'];?></td>
</tr>
</tbody>
</table>
<?php } ?>
