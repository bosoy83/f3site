<?php
class Installer
{
	public
		$sample = false,  //Przykłady
		$title,           //Tytuł strony
		$urls,            //Format URL
		$lang;            //Język instalatora
	static
		$urlMode;
	protected
		$catid = array(), //ID kategorii startowych
		$need = array(),  //Wrong CHMOD
		$db;

	#Wybierz obsługiwany język
	function __construct()
	{
		foreach(explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']) as $x)
		{
			if(isset($x[2]))
			{
				$x = $x[0].$x[1];
			}
			if(ctype_alnum($x) && file_exists('./install/lang/'.$x.'.php'))
			{
				$this->lang = $x;
				return true;
			}
		}
		$this->lang = 'en';
	}

	#Rozpocznij transakcję
	function connect(&$data)
	{
		if($data['type'] == 'sqlite')
		{
			@touch($data['file']);
			@chmod($data['file'], 0600);
			$this->db = new PDO('sqlite:'.$data['file']);
		}
		else
		{
			$this->db = new PDO('mysql:host='.$data['host'].';dbname='.$data['db'],$data['user'],$data['pass']);
			$this->db->exec('SET CHARACTER SET "utf8"');
			$this->db->exec('SET NAMES "utf8"');
		}
		$this->db->setAttribute(3,2); //Exceptions
		$this->db->beginTransaction();
	}

	#Wczytaj plik SQL z pliku i wykonaj zapytania
	function loadSQL($file)
	{
		#Schemat tabel
		$sql = str_replace('f3_', PRE, file_get_contents($file));

		#Znak nowej linii
		if(strpos($sql, "\r\n"))
		{
			$sql = explode(";\r\n\r\n", $sql); //Win
		}
		elseif(strpos($sql, "\r"))
		{
			$sql = explode(";\r\r", $sql); //MacOS
		}
		else
		{
			$sql = explode(";\n\n", $sql); //Unix
		}

		#Zapytania
		try
		{
			foreach($sql as $q)
			{
				if(substr($q, 0, 2) != '--')
				{
					$this->db->exec($q);
				}
			}
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage().'<pre>'.$q.'</pre>');
		}
	}

	#Instaluj dane dla języka $x
	function setupLang($x)
	{
		static $c, $m, $n, $i, $lft, $db;
		require './install/lang/'.$x.'.php';

		#Przygotuj zapytania
		if(!$c)
		{
			$lft = 0;
			$db = $this->db;
			$m = $db->prepare('INSERT INTO '.PRE.'menu (seq,text,disp,menu,type,value) VALUES (?,?,?,?,?,?)');
			$n = $db->prepare('INSERT INTO '.PRE.'news (cat,name,txt,date,author,access) VALUES (?,?,?,?,?,?)');
			$i = $db->prepare('INSERT INTO '.PRE.'mitems (menu,text,type,url,seq) VALUES (?,?,?,?,?)');
			$c = $db->prepare('INSERT INTO '.PRE.'cats (name,access,type,num,nums,opt,lft,rgt)
			VALUES (?,?,?,?,?,?,?,?)');
		}

		#Strona główna
		$c->execute(array($lang['main'], $x, 5, 1, 1, 6, ++$lft, ++$lft));
		$catID = $db->lastInsertId();
		$this->catid[$x] = $catID;

		#Przykładowe kategorie
		if($this->sample)
		{
			$c->execute(array($lang['arts'], $x, 1, 0, 0, 15, ++$lft, ++$lft));
			$c->execute(array($lang['files'], $x, 2, 0, 0, 15, ++$lft, ++$lft));
			$c->execute(array($lang['foto'], $x, 3, 0, 0, 15, ++$lft, ++$lft));
			$c->execute(array($lang['links'], $x, 4, 0, 0, 15, ++$lft, ++$lft));
		}

		#Menu
		$m->execute(array(1, 'Menu', $x, 1, 3, null));
		$menuID = $db->lastInsertId();
		$m->execute(array(2, $lang['UA'], $x, 2, 2, './mod/panels/user.php'));
		$m->execute(array(3, $lang['poll'], $x, 2, 2, './mod/panels/poll.php'));
		$m->execute(array(4, $lang['stat'], $x, 1, 2, './mod/panels/online.php'));
		$m->execute(array(5, $lang['new'], $x, 2, 2, './mod/panels/new.php'));
		$m->execute(array(6, $lang['cats'], $x, 1, 2, './mod/panels/cats.php'));

		#Pierwszy NEWS
		$n->execute(array($catID, $lang['1st'], $lang['NEWS'], gmdate('Y-m-d H:i:s'), 1, 1));

		#Pozycje menu
		$i->execute(array($menuID, $lang['main'], 1, '.', 1));
		$i->execute(array($menuID, $lang['arch'], 2, 'archive', 2));
		$i->execute(array($menuID, $lang['links'], 2, 'cats/4', 3));
		$i->execute(array($menuID, $lang['foto'], 2, 'cats/3', 4));
		$i->execute(array($menuID, $lang['users'], 2, 'users', 5));
		$i->execute(array($menuID, $lang['group'], 2, 'groups', 6));
	}

	#Instaluj dla wszystkich języków
	function setupAllLang()
	{
		foreach(scandir('lang') as $dir)
		{
			if($dir[0]!='.' && file_exists('install/lang/'.$dir.'.php'))
			{
				$this->setupLang($dir);
			}
		}
	}

	#Dodaj admina
	function admin($login, $pass)
	{
		$u = $this->db->prepare('REPLACE INTO '.PRE.'users (ID,login,pass,lv,regt) VALUES (?,?,?,?,?)');
		$u->execute(array(1, $login, md5($pass), 4, $_SERVER['REQUEST_TIME']));
	}

	#Zapisz ID startowych kategorii i zakończ
	function commit(&$data)
	{
		$cfg = array();

		#Ustawienia zawartości - kategorie startowe
		foreach($this->catid as $lang => $id)
		{
			$cfg['start'][$lang] = $id;
		}
		require './cfg/content.php';
		$o = new Config('content');
		$o->save($cfg);

		$cfg = array();
		require './cfg/main.php';

		#Tytuł strony i format URL
		$cfg['title'] = $this->title;
		$cfg['niceURL'] = $this->urls;
		$cfg['captcha'] = extension_loaded('gd') ? 1 : 0;

		$o = new Config('main');
		$o->add('cfg', $cfg);
		$o->save();

		#Plik db.php
		$this->buildConfig($data);

		#Ankieta
		if(file_exists('./mod/polls'))
		{
			include './mod/polls/poll.php';
			RebuildPoll(null, $this->db);
		}

		#Sortuj kategorie
		if(file_exists('./lib/categories.php'))
		{
			include './lib/categories.php';
			RebuildTree($this->db);
		}

		#Zapisz menu do cache
		Installer::$urlMode = $this->urls;
		include './lib/mcache.php';
		RenderMenu($this->db);

		#Nareszcie koniec - akceptujemy zmiany w bazie :)
		$this->db->commit();
	}

	#Utwórz plik konfiguracyjny
	function buildConfig(&$data)
	{
		$f = new Config('./cfg/db.php');
		$f->add('db_db', $data['type']);
		$f->add('db_d', $data['file'] ? $data['file'] : $data['db']);
		$f->addConst('PRE', PRE);
		$f->addConst('PATH', PATH);
		$f->addConst('URL', URL);

		#Tylko dla MySQL
		if($data['type'] == 'mysql')
		{
			$f->add('db_h', $data['host']);
			$f->add('db_u', $data['user']);
			$f->add('db_p', $data['pass']);
		}
		return $f->save();
	}

	#Znajdź skórki
	function getSkins($selected)
	{
		$skins = '';
		foreach(scandir('style') as $x)
		{
			if($x[0]!='.' && file_exists('./style/'.$x.'/body.html'))
			{
				$skins .= '<option'.($selected==$x ? ' selected' : '').'>'.$x.'</option>';
			}
		}
		return $skins;
	}

	#Domyślny format URL
	function urls()
	{
		if(function_exists('apache_get_modules') && file_exists('.htaccess'))
		{
			if(in_array('mod_rewrite', apache_get_modules()))
			{
				return 1;
			}
		}
		return 3;
	}

	#Zbadaj CHMOD-y
	function chmods()
	{
		$table = array(
			'cache',
			'cfg',
			'rss',
			'img/user',
		);
		if(file_exists('cfg/db.db') && !is_writable('cfg/db.db'))
		{
			@chmod('cfg/db.db', 0600);
		}
		foreach($table as $folder)
		{
			if(!is_writable($folder) || !is_readable($folder))
			{
				@chmod($folder, 0777);
				if(!is_writable($folder))
				{
					$this->need[] = array(
						'file' => $folder,
						'good' => '777',
						'bad'  => substr(sprintf('%o', fileperms($folder)), -3)
					);
				}
			}
			foreach(scandir($folder) as $file)
			{
				$path = $folder.'/'.$file;
				if($file[0] != '.' && (!is_writable($path) || !is_readable($path)))
				{
					if(is_dir($path))
					{
						@chmod($path, 0777);
						$chmod = '777';
					}
					else
					{
						@chmod($path, 0666);
						$chmod = '666';
					}
					if(!is_writable($path))
					{
						$this->need[] = array(
							'file' => $path,
							'good' => $chmod,
							'bad'  => substr(sprintf('%o', fileperms($path)), -3)
						);
					}
				}
			}
		}
		return empty($this->need);
	}

	#Pobierz tablicę CHMOD-ów
	function buildChmodTable()
	{
		return $this->need;
	}
}

#Zbuduj adres URL
function url($x)
{
	switch(Installer::$urlMode)
	{
		case 1: return $x; break;
		case 2: return 'index.php/' . $x; break;
		default: return '?go=' . $x;
	}
}