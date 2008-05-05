<?php
if(iCMSa!=1 || !Admit('NM')) exit;
require LANG_DIR.'adm_o.php';

#Tytu� strony
$content->title = $id ? $lang['navbe'] : $lang['navbn'];

#Zapis
if($_POST)
{
	#Dane
	$m = array(
		'text' => Clean($_POST['text']),
		'disp' => Clean($_POST['disp']),
		'img'  => Clean($_POST['img']),
		'menu' => (int)$_POST['menu'],
		'type' => (int)$_POST['type'],
		'value'=> &$_POST['value']
	);

	#START
	try
	{
		$db->beginTransaction(); 
	
		#Edytuj
		if($id && !isset($_POST['savenew']))
		{
			$q = $db->prepare('UPDATE '.PRE.'menu SET text=:title, disp=:disp, menu=:menu,
				type=:type, img=:img, value=:value WHERE ID='.$id);
			$db->exec('DELETE FROM '.PRE.'mitems WHERE menu='.$id);
		}
		#Nowy
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'menu (seq,text,disp,menu,type,img,value)
				VALUES ('.(db_count('ID','menu')+1).',:title,:disp,:menu,:type,:img,:value)');
		}
		$q->execute();

		#Linki
		if($m['type']==3)
		{
			#ID
			if(!$id) $id = $db->lastInsertId();

			#Zapytanie i pozycje menu
			$q = $db->prepare('INSERT INTO '.PRE.'mitems (menu,text,url,nw) VALUES ('.$id.',?,?,?)');
			$o = array();

			$ile = count($_POST['i_seq']);
			for($i=0;$i<$ile;++$i)
			{
				if(!empty($_POST['i_seq'][$i]))
				{
					$o[$i] = array(
						2 => &$_POST['txt'][$i],
						3 => Clean($_POST['adr'][$i]),
						4 => isset($_POST['nw'][$i]) ? 1 : 0
					);
					$q->execute($o[$i]);
				}
			}
		}
		$db->commit();

		#Generuj menu
		include('./admin/inc/mcache.php');
		RenderMenu();

		#Lista
		unset($q,$id);
		$_POST = array();
		include './admin/nav.php';
	}
	catch(PDOException $e)
	{
		$content->info($lang['error'].$e->errorInfo[2]);
	}
}

#Odczyt
elseif($id)
{
	$m = $db->query('SELECT * FROM '.PRE.'menu WHERE ID='.$id) -> fetch(2); //ASSOC
	if(!$m) { $content->info($lang['noex']); return; }

	if($m['type'] == 3)
	{
		$o = $db->query('SELECT text,url,nw FROM '.PRE.'mitems WHERE menu='.$id.' ORDER BY seq') -> fetchAll(3);
	}
}
else
{
	$m = array('text'=>'', 'disp'=>'', 'img'=>'0', 'menu'=>1, 'type'=>3, 'value'=>'');
	$o = array( array('', '', true) );
}

$content->data = array(
	'menu' => &$m,
	'url'  => '?a=editnav'.(($id) ? '&amp;id='.$id : ''),
	'fileman'  => Admit('FM'),
	'langlist' => ListBox('lang', 1, $m['disp'])
);

/*
#ID
	if($id && $m.type']==3)
	{
		$res=$db->query('SELECT text,url,nw FROM '.PRE.'mitems WHERE menu={id.' ORDER BY seq');
		$res->setFetchMode(3);
		$s='<script type="text/javascript">';

		#Linki
		foreach($res as $i)
		{
			$s.='Dodaj("'.Clean($i[0]).'","{i[1].'",{i[2].');';
		}
		echo $s.'</script>';
	}*/