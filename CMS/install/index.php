<?php
define('INSTALL',1);
Header('Cache-Control: public');
Header('Content-type: text/html; charset=iso-8859-2');

#Język
$nlang='en';
foreach(explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']) as $l)
{
	if($l)
	{
		$l=str_replace( array('\'','"','/'), '', $l);
		if(strpos($l,';')) $l=substr($l,0,strpos($l,';'));
		if(file_exists('./'.$l.'.php'))
		{
			$nlang=$l;
		}
	}
}
unset($l);
$lang=file('./'.$nlang.'.php');

#Sterowniki PDO
$dr=PDO::getAvailableDrivers();
$my=in_array('mysql',$dr);
$li=in_array('sqlite',$dr);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
	<meta http-equiv="Robots" content="no-index" />
	<title>F3Site Installer</title>
	<link type="text/css" href="s.css" rel="stylesheet" />
</head>
<body>

<?php
#START
if(!$_POST && !$_GET)
{
	#Błędy
	$not=0;
	$error=array();

	#Wersja PHP
	if(version_compare('5.2',PHP_VERSION,'<='))
		$php='<span>'.PHP_VERSION.'</span>';
	else {
		$php='<del>'.PHP_VERSION.'</del>';
		$not=1;
	}

	#PDO
	if(extension_loaded('pdo'))
	{
		if($li && $my)
			$pdo='<span>OK</span>';
		elseif($my)
			$pdo='<span>MySQL</span>';
		elseif($li)
			$pdo='<span>SQLite</span>';
		else {
			$pdo='<del>'.$lang[10].'</del>';
			$not=1;
		}
	}
	else { $pdo='<del>'.$lang[5].'</del>'; $not=1; }

	#RegGl.
	if(ini_get('register_globals'))
	{
		$rg='<del>On</del>'; $error[]=$lang[8];
	}
	else $rg='<span>OK</span>';

	#MagicQuotes
	if(get_magic_quotes_gpc())
	{
		$mq='<del>On</del>'; $error[]=$lang[9];
	}
	else $mq='<span>OK</span>';

	#CHMOD
	if(is_writable('./../cache') && is_writable('./../cfg') && is_writable('./../cfg/db.php'))
		$ch='<span>cfg + cache</span>';
	else {
		$ch='<del>cfg + cache</span>';
		$error[]=$lang[12];
		$not=1;
	}
	?>
	<table cellspacing="1"><tbody>
		<tr><th><?= $lang[1] ?></th></tr>
		<tr><td><?= $lang[2] ?></td></tr>
	</tbody></table>
	<table><tbody align="center">
		<tr><th><?= $lang[3] ?></th><th><?= $lang[4] ?></th></tr>
		<tr><td><?= $lang[6] ?></td><td><?= $php ?></td></tr>
		<tr><td>PDO (MySQL &or; SQLite)</td><td><?= $pdo ?></td></tr>
		<tr><td>register_globals = Off<td><?= $rg ?></td></tr>
		<tr><td>magic_quotes_gpc = Off<td><?= $mq ?></td></tr>
		<tr><td><?= $lang[11] ?> (CHMOD)<td><?= $ch ?></td></tr>
		<tr><td colspan="2" align="left">
	<?php
	#Błędy?
	if($not===1) $error[]=$lang[13];
	if($error)
	{
		echo '<ul><li>'.join('</li><li>',$error).'</li></ul>';
	}
	if(!$not)
	{
		echo '<div style="text-align: center"><input type="button" value="'.$lang[14].'" onclick="location=\'?next=1\'"></div>';
	}
}

#FORM
elseif(isset($_GET['next']))
{
	require('./form.php');
}

#INSTALUJ
if($_POST)
{
	?>
	<table align="center">
	<tbody>
	<tr><th><b><?= $lang[32] ?></b></th></tr>
	<tr><td>
	<?php

	#Rozpakuj POST i dołącz klasę zapisu do .php
	extract($_POST);
	require('./../lib/config.php');

	#Hasło admina
	if($u_pass!=$u_pass2) exit($lang[49]);

	#Połącz
	echo $lang[35];
	try
	{
		if($db_db=='sqlite')
		{
			$db=new PDO('sqlite:../'.$db_d);
		}
		else
		{
			$db=new PDO('mysql:host='.$db_h.';dbname='.$db_d,$db_u,$db_p);
			$db->exec('SET CHARACTER SET "latin2"');
		}
		$db->setAttribute(3,2); #ERRMODE: Exceptions
	}
	catch(PDOException $e)
	{
		exit($lang[33].$e->getMessage());
	}
	echo '<span>OK</span><br /><br />'.$lang[34];

	#Wczytaj plik definicji
	$def=explode(';',str_replace('{pre}',$db_pre,file_get_contents('./'.str_replace('/','',$db_db).'.sql')));

	#Usuń tabele
	if(isset($_POST['db_del']))
	{
		// NOT COMPLETED !!!!!!!!
	}

	#Zapytania
	foreach($def as $q)
	{
		if(substr($q,0,2)!='--')
		{
			try {
				$db->exec($q);
			}
			catch(PDOException $e) {
				echo '<pre>'.nl2br($e).htmlspecialchars($q).'</pre>'; exit; }
		}
	}
	echo '<span>OK</span><br /><br />'.$lang[53];

	#Dodawanie zawartości
	$i='REPLACE INTO '.$db_pre;
	$db->exec($i.'cats VALUES (1,"'.$lang[36].'","",1,5,0,2,"",0,0,2,1,2)');

	$db->exec($i.'groups VALUES (1,"'.$lang[37].'","",1,1)');
	$db->exec($i.'groups VALUES (2,"'.$lang[38].'","",1,2)');

	$db->exec($i.'menu VALUES (1,1,"Menu",1,1,3,0,"")');
	$db->exec($i.'menu VALUES (2,2,"'.$lang[39].'",1,2,2,0,"mod/panels/user.php")');
	$db->exec($i.'menu VALUES (3,3,"'.$lang[40].'",1,2,1,0,"Coming soon...")');
	$db->exec($i.'menu VALUES (4,4,"'.$lang[41].'",1,1,2,0,"mod/panels/msets.php")');
	$db->exec($i.'menu VALUES (5,5,"'.$lang[42].'",1,2,2,0,"mod/panels/online.php")');
	$db->exec($i.'menu VALUES (6,6,"'.$lang[54].'",1,2,1,0,"Coming soon...")');

	$db->exec($i.'mitems VALUES (1,1,"'.$lang[36].'","index.php",0)');
	$db->exec($i.'mitems VALUES (2,1,"'.$lang[43].'","?co=arch",0)');
	$db->exec($i.'mitems VALUES (3,1,"'.$lang[44].'","?co=cats&amp;id=4",0)');
	$db->exec($i.'mitems VALUES (4,1,"'.$lang[45].'","?co=cats&amp;id=3",0)');
	$db->exec($i.'mitems VALUES (6,1,"'.$lang[37].'","?co=users",0)');
	$db->exec($i.'mitems VALUES (7,1,"'.$lang[46].'","?co=groups",0)');

	$newpass=md5($u_pass);
	$db->exec($i.'users VALUES (1,'.$db->quote(trim(htmlspecialchars($u_login))).',"'.$newpass.'","",2,2,4,"","'.strftime('%Y-%m-%d').'","",0,"",1,"","","","","",null)');

	#Konfiguracja
	echo '<span>OK</span><br /><br />'.$lang[47];
	$f=new Config('./../cfg/db.php');

	#Wszystkie
	$f->add('db_d',$db_d);
	$f->add('db_db',$db_db);
	$f->addConst('PRE',$db_pre);

	#Dla MySQL
	if($db_db=='mysql')
	{
		$f->add('db_h',$db_h);
		$f->add('db_u',$db_u);
		$f->add('db_p',$db_p);
	}

	#Zapisz
	if($f->save())
	{
		echo '<span>OK</span><br /><br />';
	}
	else
	{
		echo '<br /><br />'.$lang[48].'<br /><textarea cols="50" rows="9" style="width: 100%">'.
		htmlspecialchars($f->in).'</textarea><br /><br />';
	}

	#Menu cache
	chdir('./../');
	define('PRE',$db_pre);
	include('./admin/inc/mcache.php');
	RenderMenu();

	echo $lang[50]
	?>
	<br /><br />
	<div style="text-align: center">
		<input type="button" value="OK &raquo;" onclick="location='../index.php'" />
	</div>
	</td></tr>
	</tbody></table>
	<?php
	}
?>
</body>
</html>
