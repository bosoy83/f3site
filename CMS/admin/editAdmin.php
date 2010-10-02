<?php
if(iCMSa!=1 || !admit('U')) return;
require LANG_DIR.'rights.php';
require LANG_DIR.'profile.php';

#Brak uprawnieñ
if(!$id || $id < 1 || ($id == UID && !IS_OWNER)) return;

#Uprawnienia
$set = array(
	'C',  //Kategorie
	'P',  //Wolne strony
	'Q',  //Ankiety
	'R',  //RSS
	'U',  //U¿ytkownicy
	'G',  //Grupy
	'L',  //Indeks zdarzeñ
	'M',  //Masowe listy
	'CFG',//Opcje
	'DB', //Kopia bazy danych
	'N',  //Menu
	'B',  //Bannery
	'E',  //PI
	'CM', //Komentarze
	'TAG',//S³owa kluczowe
	'FM', //Mened¿er plików
	'UP', //Upload
	'$',  //WYSIWYG
	'+'   //Globalny redaktor
);

#Pobierz u¿ytkownika - FETCH_NUM
$adm = $db->query('SELECT login,lv,adm FROM '.PRE.'users WHERE ID='.$id.
	(!IS_OWNER ? ' && lv!=4' : '')) -> fetch(3);

#Brak uprawnieñ
if(!$adm OR (!IS_OWNER && $adm[1] >= LEVEL)) return;

#Pobierz wtyczki - FETCH_NUM
$plug1 = $db->query('SELECT ID,text FROM '.PRE.'admmenu WHERE rights=1')->fetchAll(3);

#Pobierz kategorie
$cats1 = $db->query('SELECT ID,name,c.type,CatID FROM '.PRE.'cats c LEFT JOIN '.PRE.'acl a
	ON c.ID=a.CatID AND a.type="CAT" AND a.UID='.$id.' ORDER BY c.type') -> fetchAll(3);

#Tytu³ strony
$content->title = sprintf('%s - %s', $lang['editAdm'], $adm[0]);

#Zapis
if($_POST)
{
	#Poziom
	$lv = (int)$_POST['lv'];

	#Mo¿e zmieniæ w³a¶ciciela?
	if(!IS_OWNER && ($lv>3 OR $lv<0)) return;

	#Globalne i nowe uprawnienia
	$glo = array();
	$new = array();
	foreach($set as $x)
	{
		if(isset($_POST[$x])) $glo[] = $x;
	}
	foreach($plug1 as &$x)
	{
		if(isset($_POST[$x[0]])) $glo[] = $x[0];
	}
	$checked = isset($_POST['c']) ? join(',', array_map('intval',$_POST['c'])) : ''; //Wybrane
	try
	{
		$db->beginTransaction();
		if($checked)
		$db->exec('DELETE FROM '.PRE.'acl WHERE UID='.$id.' AND type="CAT" AND CatID NOT IN('.$checked.')');

		#Zapytanie - ACL
		$q = $db->prepare('REPLACE INTO '.PRE.'acl (UID,CatID,type) VALUES (?,?,"CAT")');
		foreach($cats1 as $x)
		{
			if(isset($_POST['c'][$x[0]])) $q->execute(array($id, $x[0]));
		}
		$q = null;

		#Globalne prawa i poziom
		$db->exec('UPDATE '.PRE.'users SET adm="'.join('|',$glo).'", lv='.$lv.' WHERE ID='.$id);

		#Koniec
		$db->commit();
		$content->info($lang['saved']);
		header('Location: '.URL.url('admins','','admin'));
	}
	catch(PDOException $e)
	{
		$content->info($e->getMessage());
	}
	return 1;
}

/* FORM */

#Funkcje
require './lib/user.php';
require './lib/categories.php';

$prv = explode('|', $adm[2]); //Prawa
$lv  = $adm[1]; //Poziom

#Prawa
$rights = array();
foreach($set as $x)
{
	if(in_array($x,$prv)) $rights[$x] = true;
}

#Wtyczki
$plugins = '';
foreach($plug1 as &$x)
{
	$plugins .= '<label><input type="checkbox" name="'.$x[0].'"'.((in_array($x[0],$prv)) ?
		' checked="checked"' : '').' /> '.$x[1].'</label><br />';
}

#Prawa do kategorii
$cats = '';
$type = 0;
foreach($cats1 as &$x)
{
	if($x[2] > $type) //Inny typ
	{
		if($type!=0) $cats .= '</fieldset>';
		$cats .= '<fieldset><legend>'.$lang['cats'].': '.$lang[ typeOf($x[2]) ].'</legend>';
		$type = $x[2];
	}
	$cats .= '<label><input type="checkbox" name="c['.$x[0].']"'.(($x[3]) ?
		' checked="checked"' : '').' /> '.$x[1].'</label><br />';
}
if($cats!='') $cats.='</fieldset>';

$content->data = array(
	'owner' => IS_OWNER,
	'lv'    => $lv,
	'cats'  => &$cats,
	'plugins' => &$plugins,
	'rights'  => &$rights
);