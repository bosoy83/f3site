<?php
if(!$_POST || iCMSa!=1 || !admit('C')) exit;
require LANG_DIR.'admAll.php';
require './lib/categories.php';

#Usuñ
if(isset($_POST['del']) && $x = GetID(1))
{
	$res = $db->query('SELECT ID,name,type,lft,rgt FROM '.PRE.'cats WHERE ID IN ('.$x.')');

	#Wykonaj
	if($_POST['del'] == 'OK')
	{
		$type = parse_ini_file('cfg/types.ini',1);
		$db -> beginTransaction();

		foreach($res as $cat)
		{
			$id  = $cat['ID'];
			$sub = (int)$_POST['x'][$id];
			$c   = (int)$_POST['items'][$id];
			$del = 'ID='.$id;

			if($c > 0) //Przenieœ zawartoœæ
			{
				$db->exec('UPDATE '.PRE.$type[$cat['type']]['table'].' SET cat='.$c.' WHERE cat='.$id);
			}
			elseif($c < 0) //Usuñ
			{
				$db->exec('DELETE FROM '.PRE.$type[$cat['type']]['table'].' WHERE cat='.$c);
			}
			if($cat['rgt'] > $cat['lft'] + 1)
			{
				if($sub > 0) //Przenieœ podkategorie
				{
					$db->exec('UPDATE '.PRE.'cats SET sc='.$sub.' WHERE sc='.$id);
				}
				elseif($sub == -1) //Usuñ
				{
					$del = 'lft BETWEEN '.$cat['lft'].' AND '.$cat['rgt'];
				}
				else //Uczyñ kategoriami g³ównymi
				{
					$db->exec('UPDATE '.PRE.'cats SET sc=0 WHERE sc='.$id);
				}
				UpdateCatPath();
			}
			$db->exec('DELETE FROM '.PRE.'cats WHERE '.$del); //Usuñ kategoriê
		}
		RebuildTree();
		CountItems();
		$db->commit();
		header('Location: '.URL.url('cats', '', 'admin'));
	}
	else
	{
		$cat = array();
		foreach($res as $x)
		{
			$cat[] = array(
				'id'    => $x['ID'],
				'title' => $x['name'],
				'cats'  => Slaves($x['type'],0,$x['ID'])
			);
		}
		$content->data = array('cat'=>$cat);
	}
	$content->title = $lang['delCat'];
}

else
{
	header('Location: '.URL.url('cats'));
	$content->info($lang['nocats']);
}