<?php
if(iCMSa!='X159E') exit;
if(!include('plugins/bugs/lang/adm'.$nlang.'.php')) require('plugins/bugs/lang/admen.php');

function Install()
{
	#Instaluj
	global $cfg,$mnew,$lang,$item;
	if($_POST['ready'])
	{
		db_q('INSERT INTO {pre}plugins (ID,name) VALUES ("bugs","Issue System")');
		db_q('INSERT INTO {pre}admmenu (ID,text,file,hid) VALUES ("BUGS","'.$lang['ab_sys2'].'","bugs",1)');
		db_q('INSERT INTO {pre}admmenu (ID,text,file,hid) VALUES ("BUGADM","'.$lang['ab_sys'].'","bugs",2)');
		db_q('CREATE TABLE {pre}bugsect (ID int NOT NULL auto_increment PRIMARY KEY, seq int, title varchar(70))');
		db_q('CREATE TABLE {pre}bugcats (ID int NOT NULL auto_increment PRIMARY KEY, sect int NOT NULL, name varchar(70), dsc varchar(255), see varchar(9), report varchar(50), trate tinyint(1), num int unsigned, last datetime, text text)');
		db_q('CREATE TABLE {pre}bugs (ID int NOT NULL auto_increment PRIMARY KEY, cat int NOT NULL, name varchar(70), num int, date datetime, status tinyint(1), level tinyint(1), env varchar(99), pos int unsigned, neg int unsigned, poster varchar(50), ip varchar(23), text text)');
		db_q('CREATE TABLE {pre}bugrate (UID varchar(23), BID int, wh tinyint(1), date datetime)');
		
		#Zapis opcji
		if(!file_exists('cfg/plug_bugs.php'))
		{
			if($f=fopen('cfg/plug_bugs.php','w'))
			{
				fwrite($f,'<?php $cfg+=Array(\'bugs_on\'=>1,\'bugsnum\'=>20,\'bugs_ae\'=>1,\'bugs_tl\'=>1000,\'bugs_s\'=>\'default\',\'bugs_i2\'=>1); ?>');
				fclose($f);
			}
		}
		
		#Wpis do menu
		foreach($_POST['x'] as $lng=>$id)
		{
			db_q('INSERT INTO {pre}mitems VALUES
			('.(int)$_POST['seq'][$lng].','.(int)$id.',1,
			"'.db_esc(TestForm($_POST['tit'][$lng],0,1,0)).'","?co=bugs",2)');
		}
		if($cfg['mc']==1) include('admin/mcache.php');
	}
	#Opcje
	else
	{
		global $nlang,$menu;
		
		echo '<form action="?a=pi&amp;inst=1&amp;idp=bugs" method="post">';
		cTable($lang['opt'],1);
		echo '
		<tr><td style="line-height: 30px"><tt>
		'.$lang['ab_inst1'].':<br />';
		
		#Menu
		$menulist='<option value="NO">- - -</option>';
		db_read('ID,text','menu','menu','tn',' WHERE type=3');
		$ile=count($menu);
		for($i=0;$i<$ile;$i++)
		{
			$menulist.='<option value="'.$menu[$i][0].'">'.$menu[$i][1].'</option>';
		}
		if($f=opendir('lang'))
		{
			while(false!==($d=readdir($f)))
			{
				if(is_dir('lang/'.$d) && $d!='.' && $d!='..')
				{
					echo
					strtoupper($d).': <select name="x['.$nlang.']">'.$menulist.'</select>
					'.$lang['ap_mkol'].' <input size="3" name="seq['.$nlang.']" value="5" />
					'.$lang['title'].': <input name="tit['.$nlang.']" value="Issue System" /><br />';
				}
			}
			echo '</tt></td></tr>
			<tr><td class="eth"><input type="submit" value="START" name="ready" /></td></tr>';
			eTable();
			echo '</form>';
		}
	}
}

function Uninstall()
{
	#Usuñ
	global $cfg,$lang,$mnew,$item;
	if($_GET['ready'])
	{
		db_q('DELETE FROM {pre}plugins WHERE ID="bugs"');
		db_q('DELETE FROM {pre}admmenu WHERE ID="BUGS" || ID="BUGADM"');
		db_q('DROP TABLE {pre}bugcats');
		db_q('DROP TABLE {pre}bugsect');
		db_q('DROP TABLE {pre}bugs');
		db_q('DROP TABLE {pre}bugrate');
		db_q('DELETE FROM {pre}mitems WHERE url="?co=bugs"');
		if($cfg['mc']==1) include('admin/mcache.php');
	}
	#Upewnij
	else
	{
		Info($lang['ab_unin'].'<br /><br />
		<a href="?a=pi&amp;uninst=1&amp;idp=bugs&amp;ready=1">'.$lang['del'].'</a>');
	}
}
?>