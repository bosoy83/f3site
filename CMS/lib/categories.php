<?php /* Funkcje kategorii */

#Nazwa tabeli
function typeOf($x)
{
	static $data;
	switch($x)
	{
		case 1: return 'arts'; break;
		case 2: return 'files'; break;
		case 3: return 'imgs'; break;
		case 4: return 'links'; break;
		case 5: return 'news'; break;
		default: if(!$data) $data = parse_ini_file('./cfg/types.ini',1); return $data[$x]['table'];
	}
}

#Najnowsze pozycje
function Latest($lang=array())
{
	global $db,$cfg;
	include './cfg/latest.php';
	if(!isset($cfg['newOn']) OR !isset($cfg['newTypes'])) return;

	#Języki
	if($lang)
	{
		$lang = array($lang);
	} 
	else
	{
		foreach(scandir('./lang') as $x)
		{
			if($x[0] != '.' && is_dir('./lang/'.$x)) $lang[] = $x;
		}
	}

	#Typy kategorii
	$data = parse_ini_file('./cfg/types.ini',1);
	
	#Dla każdego języka
	foreach($lang as $l)
	{
		$out = '';
		foreach($cfg['newTypes'] as $t=>$x)
		{
			$res = $db->query('SELECT i.'.(isset($data[$t]['get']) ? $data[$t]['get'] : 'ID').',
				i.name, c.name as cat, c.type FROM '.PRE.$data[$t]['table'].' i
				INNER JOIN '.PRE.'cats c ON i.cat=c.ID WHERE i.access=1 AND
				(c.access=1 OR c.access="'.$l.'") ORDER BY i.ID DESC LIMIT '.(int)$cfg['newNum']);
			$res -> setFetchMode(3);

			$got = '';
			$cur = &$data[$t];
			$url = isset($cur['name']) ? url($cur['name'].'/') : '';

			foreach($res as $x)
			{
				$got .= '<li><a href="'.$url.$x[0].'" title="'.$x[2].'">'.$x[1].'</a></li>';
			}
			if($got) $out .= '<h3>'.(isset($cur[$l]) ? $cur[$l] : $cur['en']).'</h3><ul class="latest">'.$got.'</ul>';
		}
		if($out) file_put_contents('./cache/new-'.$l.'.php', $out, 2);
	}
}

#Zbuduj RSS
function RSS($id = null, $cat = null)
{
	global $db;
	$q = is_numeric($id) ? 'ID='.$id : 'auto=1';
	$all = $db->query('SELECT ID,name,dsc,url,lang,num FROM '.PRE.'rss WHERE '.$q)->fetchAll(3);
	foreach($all as $x)
	{
		require_once './lib/rss.php';
		$rss = new RSS;
		$rss -> title = $x[1];
		$rss -> desc  = $x[2];
		$rss -> link  = $x[3];

		#Pobierz ostatnie nowości
		$q = $db->query('SELECT i.ID,i.name,i.date,i.txt,i.opt,c.name as cat FROM '.
		PRE.'news i JOIN '.PRE.'cats c ON i.cat=c.ID WHERE i.access=1 AND
		(c.access=1 OR c.access="'.$x[4].'") ORDER BY i.ID DESC LIMIT '.$x[5]);

		foreach($q as $item)
		{
			$rss->add( array(
				'ID'    => $item['ID'],
				'title' => $item['name'],
				'text'  => $item['opt'] & 1 ? nl2br($item['txt']) : $item['txt'],
				'cat'   => $item['cat'],
				'date'  => $item['date'],
				'url'   => URL . 'news/' . $item['ID']
			));
		}
		$rss->save('rss/'.$x[0].'.xml');
	}
}

#Struktura kategorii
function UpdateCatPath($cat)
{
	global $db;

	#Pobierz kategorię?
	if(is_numeric($cat))
	{
		$cat = $db->query('SELECT ID,name,sc,lft,rgt FROM '.PRE.'cats WHERE ID='.$cat)->fetch(2);
	}
	$out = '';

	#Nadkategorie
	if($cat['sc'] != 0)
	{
		$res = $db->query('SELECT ID,name FROM '.PRE.'cats WHERE lft<'.$cat['lft'].
		' AND rgt>'.$cat['rgt'].' AND (access!=2 OR access!=3) ORDER BY lft');

		$res -> setFetchMode(3);
		foreach($res as $c)
		{
			$out.= '<a href="'.url($c[0]).'">'.$c[1].'</a> &raquo; ';
		}
	}
	$out.= '<a href="'.url($cat['ID']).'">'.$cat['name'].'</a>';

	#Zapisz
	file_put_contents('./cache/cat'.$cat['ID'].'.php', $out, 2);
	return $out;
}

#Zmień ilość pozycji
function SetItems($id,$ile)
{
	global $db;
	static $new;
	$id  = (int)$id;
	$ile = (int)$ile;
	$ile = ($ile>0) ? '+'.$ile : '-'.$ile;

	#Pobierz LFT i RGT i zmień ilość całkowitą w aktualnym katalogu i wyższych
	$res = $db->query('SELECT sc,lft,rgt FROM '.PRE.'cats WHERE access!=2 AND access!=3 AND ID='.$id);
	if(!$cat = $res->fetch(3)) return;

	#Kategoria pozycji
	$db->exec('UPDATE '.PRE.'cats SET num=num'.$ile.', nums=nums'.$ile.' WHERE ID='.$id);

	#Nadkategorie
	if($cat[0]) $db->exec('UPDATE '.PRE.'cats SET nums=nums'.$ile.
		' WHERE access!=2 AND access!=3 AND lft<'.$cat[1].' AND rgt>'.$cat[2]);
}

#Lista podkategorii
function Slaves($type=0,$id=0,$o=null)
{
	$where = array();
	if(is_numeric($o)) $where[]='ID!='.$o;

	#Prawa i typ
	if(LEVEL!=4 && !$where && !admit('+'))
	{
		$where[] = 'ID IN (SELECT CatID FROM '.PRE.'acl WHERE UID='.UID.')';
	}
	if($type!=0)
	{
		$where[] = 'type='.(int)$type;
	}

	#Odczyt
	$res = $GLOBALS['db']->query('SELECT ID,name,lft,rgt FROM '.PRE.'cats'.
		(($where)?' WHERE '.join(' AND ',$where):'').' ORDER BY lft');
	$depth = 0;
	$last = 1;
	$o = '';

	#Lista
	foreach($res as $cat)
	{
		#Poziom
		if($last > $cat['rgt'])
			++$depth;

		elseif($depth>0 && $last+2!=$cat['rgt'] && $last+1!=$cat['lft'])
			$depth -= floor(($cat['lft']-$last)/2);

		if($depth < 0) $depth = 0;

		$last = $cat['rgt'];

		$o.='<option value="'.$cat['ID'].'"'.(($id==$cat['ID'])? ' selected="selected"':'').
			'" style="padding-left: '.$depth.'em">'.$cat['name'].'</option>';
	}
	return $o;
}

#Przelicz zawartość w kategoriach
function CountItems()
{
	global $db;
	$cat = $db->query('SELECT ID,type,access,sc FROM '.PRE.'cats') -> fetchAll(3); //FETCH_NUM
	$ile = count($cat);
	if($ile > 0)
	{
		#Dla każdej kategorii policz liczbę zawartości
		for($i=0; $i<$ile; ++$i)
		{
			$id = $cat[$i][0];
			$num[$id] = dbCount(typeOf($cat[$i][1]).' WHERE cat='.$id.' AND access=1');

			#ID kategorii nadrzędnej
			$sub[$id] = $cat[$i][3];

			#Tablica będzie zawierać ilość kategorii w podkategoriach
			$total[$id] = $num[$id];
		}
		for($i=0; $i<$ile; $i++)
		{
			#Jeżeli dostępna - znajdź podkategorie i dolicz ilość zawartości
			if($cat[$i][2] != 2 && $cat[$i][2] != 3)
			{
				$x = $cat[$i][3]; #Nadkategoria
				while($x!=0 && is_numeric($x))
				{
					$total[$x] += $total[$cat[$i][0]];
					$x = $sub[$x];
				}
			}
		}
		#Zapisz ilość dla każdej kategorii
		$q = $db->prepare('UPDATE '.PRE.'cats SET num=?, nums=? WHERE ID=?');
		foreach($total as $k=>$x)
		{
			if(is_numeric($x) && is_numeric($num[$k]))
			{
				$q->execute(array($num[$k], $x, $k));
			}
		}
	}
}

//Help: sitepoint.com
function RTR($parent,$left)
{
	global $db;
	$right = $left+1;
	$all = $db->query('SELECT ID FROM '.PRE.'cats WHERE sc='.$parent)->fetchAll(3);
	foreach($all as $row)
	{
		$right = RTR($row[0], $right);
	}
	$db->exec('UPDATE '.PRE.'cats SET lft='.$left.', rgt='.$right.' WHERE ID='.$parent);
	return $right+1;
}
function RebuildTree()
{
	$left=1;
	foreach($GLOBALS['db']->query('SELECT ID FROM '.PRE.'cats WHERE sc=0 ORDER BY type,name') as $x)
	{
		$left = RTR($x['ID'],$left);
	}
}