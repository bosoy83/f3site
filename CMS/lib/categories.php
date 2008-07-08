<?php /* Funkcje kategorii */

#Struktura kategorii
function UpdateCatPath($cat)
{
	global $db;

	#Pobierz kategoriê?
	if(is_numeric($cat))
	{
		$cat = $db->query('SELECT ID,name,sc,lft,rgt FROM '.PRE.'cats WHERE ID='.$cat)->fetch(2);
	}
	$out = '';

	#Nadkategorie
	if($cat['sc'] != 0)
	{
		$res = $db->query('SELECT ID,name FROM '.PRE.'cats WHERE lft<'.$cat['lft'].
		' AND rgt>'.$cat['rgt'].' AND (access!=2 OR access!=3) ORDER BY lft DESC');

		$res -> setFetchMode(3);
		foreach($res as $c)
		{
			$out.= '<a href="'.((MOD_REWRITE) ? '/'.$c[0] : '?d='.$c[0]).'">'.$c[1].'</a> &raquo; ';
		}
	}
	$out.= '<a href="'.((MOD_REWRITE) ? '/'.$cat['ID'] : '?d='.$cat['ID']).'">'.$cat['name'].'</a>';

	#Zapisz
	file_put_contents('./cache/cat'.$cat['ID'].'.php', $out, 2);

	return $out;
	/*Popraw strukturê tak¿e podkategoriom
	$res = $db->query('SELECT ID,name FROM '.PRE.'cats WHERE lft>'.$cat[3].' AND rgt<'.$cat[4]);
	$res ->setFetchMode(3);

	foreach($res as $c)
	{
		$out.= ' &raquo; <a href="'.((MOD_REWRITE) ? '/cat/'.$c[0] : '?co=cats&amp;id='.$c[0]).'">'.$c[1].'</a>';
	}*/
}

#Zmieñ iloœæ pozycji
function SetItems($id,$ile)
{
	global $db;
	$id=(int)$id;
	$ile=(int)$ile;
	$ile=($ile>0) ? '+'.$ile : '-'.$ile;

	#Zmieñ iloœæ w docelowej kategorii
	$db->exec('UPDATE '.PRE.'cats SET num=num'.$ile.' WHERE ID='.$id);

	#Pobierz LFT i RGT
	$res=$db->query('SELECT sc,lft,rgt FROM '.PRE.'cats WHERE ID='.$id);
	$cat=$res->fetch(3); //NUM
	$res->closeCursor();
	$IDs=array($id);

	#Z³ó¿ dane
	if($cat[0])
	{
		$res=$db->query('SELECT sc FROM '.PRE.'cats WHERE lft<='.$cat[1].
			' AND rgt>='.$cat[0].' AND access!=2 AND access!=3');

		foreach($res as $cat) { if($cat['sc']) $IDs[]=$cat['sc']; }
	}

	#Zmieñ iloœæ ca³kowit¹ w wy¿szych
	if($IDs)
	{
		$db->exec('UPDATE '.PRE.'cats SET nums=nums'.$ile.' WHERE ID IN('.join(',',$IDs).')');
	}	
}

#Lista podkategorii
function Slaves($type=0,$id=0,$o=null)
{
	$where=array();
	if(is_numeric($o)) $where[]='ID!='.$o;

	#Prawa i typ
	if(LEVEL!=4 && !$where && !Admit('GLOBAL'))
	{
		$where[]='ID IN (SELECT CatID FROM '.PRE.'acl WHERE UID='.UID.')';
	}
	if($type!=0)
	{
		$where[]='type='.(int)$type;
	}

	#Odczyt
	$res=$GLOBALS['db']->query('SELECT ID,name,lft,rgt FROM '.PRE.'cats'.
		(($where)?' WHERE '.join(' && ',$where):'').' ORDER BY lft');
	$depth=0;
	$last=1;
	$o='';

	#Lista
	foreach($res as $cat)
	{
		#Poziom
		if($last>$cat['rgt'])
			++$depth;

		elseif($depth>0 && $last+2!=$cat['rgt'] && $last+1!=$cat['lft'])
			$depth-=floor(($cat['rgt']-$last)/2);

		$last=$cat['rgt'];

		$o.='<option value="'.$cat['ID'].'" style="margin-left: '.$depth.'em"'
		.(($id==$cat['ID'])? ' selected="selected"':'').'">'.$cat['name'].'</option>';
	}
	return $o;
}

#Przelicz zawartoœæ w kat.
function CountItems()
{
	#Odczyt
	global $db;
	$res=$db->query('SELECT ID,type,access,sc FROM '.PRE.'cats');
	$cat=$res->fetchAll(3); //NUM
	unset($res);

	$ile=count($cat);
	if($ile>0)
	{
		for($i=0;$i<$ile;$i++)
		{
			$id=$cat[$i][0];
			$num[$id]=db_count('ID',typeOf($cat[$i][1]).' WHERE cat='.$id.' AND access!=2');
			$sub[$id]=$cat[$i][3];
			$total[$id]=$num[$id];
		}
		for($i=0;$i<$ile;$i++)
		{
			#Je¿eli dostêpna
			if($cat[$i][2]!=2 && $cat[$i][2]!=3)
			{
				$x=$cat[$i][3]; #Nadkat.
				while($x!=0 && is_numeric($x))
				{
					#Dolicz
					$total[$x]+=$total[$cat[$i][0]];
					$x=$sub[$x];
				}
			}
		}
		foreach($total as $k=>$x)
		{
			#Zapis
			if(is_numeric($x) && is_numeric($num[$k])) $db->exec('UPDATE '.PRE.'cats SET num='.$num[$k].', nums='.$x.' WHERE ID='.$k);
		}
	}
}

//Help: sitepoint.com
function RTR($parent,$left)
{
	global $db;
	if($left)
	$right = $left+1;
	$result = $db->query('SELECT ID FROM '.PRE.'cats WHERE sc="'.$parent.'";');
	$all=$result->fetchAll(3);
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
		$left=RTR($x['ID'],$left);
	}
}