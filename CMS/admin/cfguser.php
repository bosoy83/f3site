<?php
if(iCMSa!=1 || !Admit('CFG')) exit;

#Zapisz
if($_POST)
{
	$_POST['mailban']=empty($_POST['mailban'])?array():explode("\n",$_POST['mailban']);
	$_POST['nickban']=empty($_POST['nickban'])?array():explode("\n",$_POST['nickban']);

	require('./lib/config.php');
	$f=new Config('account');
	if($f->save($_POST))
	{
		Info($lang['saved']);
		include('./admin/config.php');
	}
	$f=null; return;
}

#Opcje
include('./cfg/account.php');
include('./cfg/mail.php');

#Jêzyk
require($catl.'adm_cfgm.php');

echo '<form action="?a=cfguser" method="post">';
OpenBox($lang['opt'].' :: '.$lang['regme'],2);
?>

<tr>
	<td style="width: 35%">
		<b><?= $lang['reg_on'] ?>?</b><br />
	</td>
	<td>
		<input type="checkbox" name="reg_on" <?= ((isset($cfg['reg_on']))?' checked="checked"':'') ?>/>
	</td>
</tr>
<tr>
	<td>
		<b><?= $lang['domainban'] ?>:</b><br /><br />
		<small><?= $lang['domainex'] ?></small>
	</td>
	<td>
		<textarea cols="30" rows="4" name="mailban"><?= join("\n",$cfg['mailban']) ?></textarea>
	</td>
</tr>
<tr>
	<td>
		<b><?= $lang['nickban'] ?>:</b><br /><br />
		<small><?= $lang['nickex'] ?></small>
	</td>
	<td>
		<textarea cols="30" rows="4" name="nickban"><?= join("\n",$cfg['nickban']) ?></textarea>
	</td>
</tr>
<tr>
	<td>
		<b><?= $lang['u_meth'] ?>:</b>
	</td>
	<td>
	<?php
	echo
	'<input type="radio" name="actmeth" value="1"'.(($cfg['actmeth']<2)?' checked="checked"':'').' />
	'.$lang['auto'].'<br />
	<input type="radio" name="actmeth" value="2"'.(($cfg['actmeth']==2)?' checked="checked"':'')
	.((isset($cfg['mailon']))?'':' disabled="disabled"').' /> '.$lang['bymail'].'<br />
	<input type="radio" name="actmeth" value="3"'.(($cfg['actmeth']==3)?' checked="checked"':'').' />
	'.$lang['byadmin'];
	?>
	</td>
</tr>
<tr>
	<td class="eth" colspan="2"><input type="submit" value="OK" /></td>
</tr>
<?php CloseBox() ?>
</form>