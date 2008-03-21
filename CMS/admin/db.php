<?php
if(iCMSa!=1 || !Admit('CDB')) exit;
require(LANG_DIR.'adm_cfgdb.php');

#Funkcje
if($db_db=='mysql') { }
else exit('Cannot parse database type.');

#Tworzenie
if(isset($_POST['xtb']) && $_GET['x']=='db')
{
	$n="\n";
	@set_time_limit(50);

	#Typ + kompresja?
	if($_POST['x_co']=='no')
	{
		header('Content-type: text/plain'); $ex='.sql';
	}
	else
	{
		header('Content-type: application/x-gzip'); $ex='.sql.gz';
	}
	header('Content-Disposition: attachment; filename='.
		str_replace( array('?','*',':','\\','/','<','>','|','"'),'',$_POST['x_fn']).$ex);

	#GZ?
	if($_POST['x_co']=='no') { ob_start(); } else { ob_start('ob_gzhandler'); }

	#Nag³ówek - komentarz
	$db->exec('SET SQL_QUOTE_SHOW_CREATE=1');
	echo '#'.$cfg['title'].' - Data Backup ('.strftime('%Y-%m-%d').')'.$n;
	echo '#Database: '.$db_d.$n;
	echo '#----------'.$n.$n;

	#Tabele
	foreach($_POST['xtb'] as $tab)
	{
		#Optymalizuj
		$x=$db->query('OPTIMIZE TABLE '.$tab); $x=null;

		#Tworzenie tabeli
		if(isset($_POST['x_ct']))
		{
			$q=$db->query('SHOW CREATE TABLE '.$tab);
			$create=$q->fetch(3);
			echo '#Creating table '.$tab.$n;

			#Usuwanie?
			if(isset($_POST['x_ct1'])) echo 'DROP TABLE IF EXISTS `'.$tab.'`;'.$n;
			echo $create[1].';'.$n.$n;
			$create=null;
			$q=null;

			#Wypu¶æ dane
			ob_flush();
		}

		#Dane
		$all=$db->query('SELECT * FROM '.$tab);
		$all->setFetchMode(3);
		echo '#Table data for '.$tab.$n;

		#Warto¶ci pól
		foreach($all as $row)
		{
			echo 'INSERT INTO `'.$tab.'` VALUES ('.join(',',array_map(array($db,'quote'),$row)).');'.$n;
			ob_flush(); //Zwolnij pamiêæ
		}
		unset($ile,$all,$row);
		echo $n;
		ob_flush(); //Zwolnij pamiêæ
	}

	#Zakoñcz
	ob_end_flush();
	exit;
}

#FORM
if(isset($_GET['x'])) exit('Error!');
Info($lang['adb_i']);
echo '<form action="?x=db" method="post">';

OpenBox($lang['adb_t'],1);
echo '<tr><td>
<table style="width: 100%"><tbody valign="top">
<tr>
<td style="width: 60%">
	<fieldset style="height: 200px">
		<legend>'.$lang['opt'].'</legend>
		<input name="x_ct" type="checkbox" checked="checked"> '.$lang['adb_c1'].'<br />
		<input name="x_ct1" type="checkbox" style="margin-left: 20px; margin-top: 7px"> '.$lang['adb_c3'].'<br /><br />
		'.$lang['adb_co'].'<br />
		<select name="x_co">
			<option value="no">'.$lang['adb_nco'].'</option>'.
			((function_exists('gzopen'))?'<option>.gz</option>':'').'
		</select>
		<br /><br />'.$lang['adb_fn'].':<br />
		<input name="x_fn" value="DB_'.$db_d.'_'.strftime('%Y-%m-%d').'" style="width: 205px" maxlength="30" />
	</fieldset>
</td>
<td style="width: 40%" align="center">
	<fieldset style="height: 200px">
		<legend>'.$lang['adb_c2'].'</legend>
		<select name="xtb[]" multiple="multiple" style="width: 80%; height: 150px">';
		
		#TABELE
		$list=$db->query('SHOW TABLES');
		$list->setFetchMode(3); //NUM
		foreach($list as $tab)
		{
			echo '<option>'.$tab[0].'</option>';
		}
		?>
		</select><br /><br />
		<a href="javascript:z=document.forms[0].elements['xtb[]']; z1=z.length; for(i=0;i<z1;++i) { z.options[i].selected=true } void(0)"><?=$lang['adb_z']?></a>
	</fieldset>
</td></tr>
</tbody></table>
</td></tr>
<tr><td class="eth"><input type="submit" value="OK" /></td></tr>
<?php CloseBox() ?>
</form>
