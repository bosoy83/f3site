<?php
if(iCMSa!=1 || !Admit('C')) exit;
require LANG_DIR.'admAll.php';
require './lib/categories.php';

#Zapis
if($_POST)
{
	#Wy¿sza kat.
	$up = (int)$_POST['sc'];

	#Dane - 1: struktura kategorii 2: komentarze 4: oceny 8: lista kat, 16: zawartoœæ z podkat.
	$cat = array(
		'sc'  => $up,
		'text'  => $_POST['txt'],
		'dsc'   => Clean($_POST['dsc']),
		'name'  => Clean($_POST['name']),
		'access'=> Clean($_POST['vis']),
		'type'  => (int)$_POST['type'],
		'sort'  => (int)$_POST['sort'],
		'opt'   => (isset($_POST['o1']) ? 1 : 0) + (isset($_POST['o2']) ? 2 : 0) +
			(isset($_POST['o3']) ? 4 : 0) + (isset($_POST['o4']) ? 8 : 0) + (isset($_POST['o5']) ? 16 : 0)
	);

	#START
	try {
		$db->beginTransaction();

		#Edytuj
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'cats SET name=:name,dsc=:dsc,access=:access,
				type=:type,sc=:sc,sort=:sort,text=:text,opt=:opt WHERE ID='.$id);
			$old = $db->query('SELECT ID,access,sc,lft,rgt FROM '.PRE.'cats WHERE ID='.$id)->fetch(3); //NUM
		}
		#Nowa
		else
		{
			#Zapis
			$q = $db->prepare('INSERT INTO '.PRE.'cats (name,dsc,access,type,sc,sort,text,opt,lft,rgt)
				VALUES (:name,:dsc,:access,:type,:sc,:sort,:text,:opt,:lft,:rgt)');

			#Pobierz prawy indeks ostatniej kategorii
			$cat['lft'] = (int) $db->query('SELECT rgt FROM '.PRE.'cats'. (($up) ?
				' WHERE ID='.$up : ' ORDER BY lft DESC LIMIT 1') ) -> fetchColumn();

			#Przesuñ kategorie
			if($up)
			{
				$db->exec('UPDATE '.PRE.'cats SET lft=lft+2 WHERE lft>='.$cat['lft']);
				$db->exec('UPDATE '.PRE.'cats SET rgt=rgt+2 WHERE rgt>='.$cat['lft']);
			}
			else
			{
				++$cat['lft'];
			}
			$cat['rgt'] = $cat['lft']+1;
		}

		#ZatwierdŸ
		$q->execute($cat);

		#Pobierz ID lub dokonaj zmian LFT i RGT
		if(!$id)
		{
			$id = $db->lastInsertId();
		}
		elseif($up!=$old[2])
		{
			RebuildTree();
		}

		#ZatwierdŸ i przebuduj strukturê kategorii
		$db->commit();
		UpdateCatPath($id);
		$content->info($lang['saved'].' ID: '.$id, array(
			'.?d='.$id   => $lang['goCat'],
			'?a=editCat' => $lang['addCat'],
			'?a=editCat&amp;id='.$id => $lang['editCat'],
			'.?co=list&amp;id='.$id  => $lang['mantxt'],
			'.?co=edit&amp;act='.$cat['type'].'&amp;catid='.$id => $lang['addItem']
		));
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($e); //B³¹d
	}
}

#FORMULARZ: Odczyt
elseif($id)
{
	if(!$cat = $db->query('SELECT * FROM '.PRE.'cats WHERE ID='.$id) -> fetch(2)) //ASSOC
	{
		$content->info($lang['noex']); return;
	}
}
#Domyœlne dane
else
{
	$cat = array('name'=>'','dsc'=>'','access'=>1,'type'=>5,'sc'=>0,'text'=>'','sort'=>2,'opt'=>15);
}
#Edytor JS, tytu³, dane
$content->addScript(LANG_DIR.'edit.js');
$content->addScript('lib/editor.js');
$content->title = $id ? $lang['editCat'] : $lang['addCat'];
$content->data = array(
	'cat'  => &$cat,
	'o1'   => $cat['opt'] & 1,
	'o2'   => $cat['opt'] & 2,
	'o3'   => $cat['opt'] & 4,
	'o4'   => $cat['opt'] & 8,
	'o5'   => $cat['opt'] & 16,
	'cats' => Slaves(0,$cat['sc'],$id),
	'langs'=> ListBox('lang',1,$cat['access'])
);