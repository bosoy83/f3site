<?php
class Installer
{
	public
		$sample = false,  //Przyk³ady
		$db;
	protected
		$lang,
		$catid = array(), //ID kategorii startowych
		$groupID = 2;     //Jêzyk i grupa admina

	function __construct($lang, &$data)
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
		$this->db->setAttribute(3,2); //ERRMODE: Exceptions
		$this->db->beginTransaction();
		$this->lang = $lang;
	}

	function loadSQL($file)
	{
		#Schemat tabel
		$sql = str_replace('{pre}', PRE, file_get_contents($file));

		#Znak nowej linii
		if(strpos($sql, "\r\n"))
		{
			$sql = explode(";\r\n\r\n", $sql); //Windows
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

	#Instaluj zawarto¶æ dla jêzyka $x
	function setupLang($x)
	{
		static $c, $g, $m, $n, $i, $lft, $db;
		require './install/lang/'.$x.'.php';

		#Przygotuj zapytania
		if(!$c)
		{
			$lft = 0;
			$db = $this->db;
			$g = $db->prepare('INSERT INTO '.PRE.'groups (name,access,opened) VALUES (?,?,?)');
			$m = $db->prepare('INSERT INTO '.PRE.'menu (seq,text,disp,menu,type,value) VALUES (?,?,?,?,?,?)');
			$n = $db->prepare('INSERT INTO '.PRE.'news (cat,name,txt,date,author,access) VALUES (?,?,?,?,?,?)');
			$i = $db->prepare('INSERT INTO '.PRE.'mitems (menu,text,url,seq) VALUES (?,?,?,?)');
			$c = $db->prepare('INSERT INTO '.PRE.'cats (name,access,type,num,nums,opt,lft,rgt)
			VALUES (?,?,?,?,?,?,?,?)');
		}

		#Strona g³ówna
		$c->execute(array($lang[0], $x, 5, 1, 1, 6, ++$lft, ++$lft));
		$catID = $db->lastInsertId();
		$this->catid[$x] = $catID;

		#Przyk³adowe kategorie
		if($this->sample)
		{
			$c->execute(array($lang[12], $x, 1, 0, 0, 15, ++$lft, ++$lft));
			$c->execute(array($lang[13], $x, 2, 0, 0, 15, ++$lft, ++$lft));
			$c->execute(array($lang[8], $x, 3, 0, 0, 15, ++$lft, ++$lft));
			$c->execute(array($lang[9], $x, 4, 0, 0, 15, ++$lft, ++$lft));
		}

		#Grupy
		$g->execute(array($lang[1], $x, 1));
		$g->execute(array($lang[2], $x, 0));
		if($this->lang == $x) $this->groupID = $db->lastInsertId();

		#Menu
		$m->execute(array(1, 'Menu', $x, 1, 3, null));
		$menuID = $db->lastInsertId();
		$m->execute(array(2, $lang[3], $x, 2, 2, './mod/panels/user.php'));
		$m->execute(array(3, $lang[4], $x, 2, 2, './mod/panels/poll.php'));
		$m->execute(array(4, $lang[5], $x, 1, 2, './mod/panels/online.php'));
		$m->execute(array(5, $lang[6], $x, 2, 2, './mod/panels/new.php'));

		#Pierwszy NEWS
		$n->execute(array($catID, $lang[11], $lang[14], gmdate('Y-m-d H:i:s'), 1, 1));

		#Pozycje menu
		$i->execute(array($menuID, $lang[0], '.', 1));
		$i->execute(array($menuID, $lang[7], '?co=archive', 2));
		$i->execute(array($menuID, $lang[8], '?co=cats&amp;id=4', 3));
		$i->execute(array($menuID, $lang[9], '?co=cats&amp;id=3', 4));
		$i->execute(array($menuID, $lang[1], '?co=users', 5));
		$i->execute(array($menuID, $lang[10], '?co=groups', 6));
	}

	#Dodaj admina
	function admin($login, $pass)
	{
		$u = $this->db->prepare('REPLACE INTO '.PRE.'users (ID,login,pass,gid,lv,regt) VALUES (?,?,?,?,?,?)');
		$u -> execute(array(1, $login, md5($pass), $this->groupID, 4, $_SERVER['REQUEST_TIME']));
	}

	#Zapisz ID startowych kategorii i zakoñcz
	function commit()
	{
		$cfg = array();
		require './cfg/content.php';

		foreach($this->catid as $lang => $id)
		{
			$cfg['start'][$lang] = $id;
		}

		$o = new Config('content');
		$o -> save($cfg);
		$this->db->commit();
	}
}