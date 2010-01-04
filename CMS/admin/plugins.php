<?php
if(iCMSa!=1 || !admit('E')) exit;
require LANG_DIR.'plugin.php';

#Pobierz zainstalowane
$setup = array();
if(file_exists('./cfg/plug.php')) include './cfg/plug.php';

#Tytu�
$content->title = $lang['plugs'];

#Instalacja
if(isset($URL[1]) && ctype_alnum($URL[1]))
{
	$name = $URL[1];
	$data = parse_ini_file('./plugins/'.$name.'/plugin.ini');

	if(!isset($data['install']))
	{
		$content->info($lang['noinst']); //Nie wymaga instalacji
	}
	elseif($_POST)
	{
		define('DB_TYPE', $db_db);
		define('AUTONUM', $db_db == 'mysql' ?
			'INT NOT NULL auto_increment PRIMARY KEY' :
			'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL');
		require './lib/config.php';
		require './plugins/'.$name.'/setup.php';
		try
		{
			#Operacje obj�te transakcj�
			$db->beginTransaction();

			#Usu� rozszerzenie
			if(isset($setup[$name]))
			{
				unset($setup[$name]);
				Uninstall();
				if(isset($data['link']))
				{
					$db->exec('DELETE FROM '.PRE.'mitems WHERE url="'.$name.'"');
					include './lib/mcache.php';
					RenderMenu();
				}
			}
			#Zainstaluj rozszerzenie i dodaj pozycje do menu
			else
			{
				$setup[$name] = (float)$data['version'];
				Install();
				if(isset($_POST['m']))
				{
					$q = $db->prepare('INSERT INTO '.PRE.'mitems (menu,text,url,seq) VALUES (?,?,?,?)');
					for($i=0, $num = count($_POST['mt']); $i<$num; ++$i)
					{
						if(!empty($_POST['mt'][$i]))
						{
							$q->execute(array($_POST['mid'][$i], $_POST['mt'][$i], $name, $_POST['mp'][$i]));
						}
					}
					include './lib/mcache.php';
					RenderMenu();
				}
			}
			$o = new Config('plug');
			$o -> add('setup', $setup);
			$o -> save();
			$db -> commit();
			unset($_SESSION['admenu']);
		}
		catch(Exception $e)
		{
			$content->info($e->getMessage()); return 1;
		}
	}
	else
	{
		$opt = array();
		$useOpt = isset($setup[$name]) ? 'o.del.' : 'o.add.';
		$useOpt.= isset($data[$useOpt.LANG]) ? LANG : 'en';
		if(isset($data[$useOpt]))
		{
			$i = 0;
			foreach(explode('+', $data[$useOpt]) as $o)
			{
				$opt['o'.++$i] = clean($o); //Opcje
			}
		}
		if(isset($data['link']) && !isset($setup[$name]))
		{
			$menus = $db->query('SELECT ID,text,disp FROM '.PRE.'menu WHERE type=3 ORDER BY disp,text')->fetchAll(3);
			$langs = array();
			foreach(scandir('./lang') as $l)
			{
				if($l[0] != '.' && isset($data[$l]) && is_dir('./lang/'.$l))
				{
					$menuList = '';
					foreach($menus as $m)
					{
						if($m[2] == '1' OR $m[2] == $l)
						{
							$menuList .= '<option value="'.$m[0].'">'.$m[1].'</option>';
						}
					}
					$langs[] = array(
						'title' => $data[$l],
						'url'   => $name,
						'menus' => $menuList
					);
				}
			}
		}
		else
		{
			$menus = $langs = false;
		}
		$content->data = array(
			'setup' => true,
			'www'   => isset($data['www']) ? clean($data['www']) : null,
			'name'  => isset($data[LANG]) ? clean($data[LANG]) : $name,
			'ver'   => (float)$data['version'],
			'menu'  => $langs,
			'credits' => isset($data['credits']) ? clean($data['credits']) : 'N/A',
			'options' => $opt
		);
		$content->info(isset($setup[$name]) ? $lang['warn2'] : $lang['warn']);
		return 1;
	}
}
else $content->info($lang['api']);

#Utw�rz zmienne
$plugs = array();

#Niezainstalowane wtyczki
foreach(scandir('./plugins') as $plug)
{
	if($plug[0] == '.' OR !file_exists('./plugins/'.$plug.'/plugin.ini'))
	{
		continue;
	}
	$data = parse_ini_file('./plugins/'.$plug.'/plugin.ini'); #Dane z pliku INI

	#Do tablicy
	$plugs[] = array(
		'id'    => $plug,
		'ready' => isset($setup[$plug]) OR empty($data['install']),
		'name'  => isset($data[LANG]) ? clean($data[LANG]) : $plug,
		'url'   => isset($data['install']) ? url('plugins/'.$plug, '', 'admin') : false,
		'config'=> isset($data['config']) ? url($plug, '', 'admin') : false
	);
}

#Do szablonu
$content->data = array('plugin' => &$plugs, 'setup' => false);
