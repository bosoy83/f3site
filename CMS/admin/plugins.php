<?php
if(iCMSa!=1 || !Admit('PI')) exit;
require LANG_DIR.'adm_pi.php';


/*
#ID wtyczki
if(isset($_GET['idp'])) $idp=$_GET['idp'];
elseif(isset($_POST['idp'])) $idp=$_POST['idp'];
if($idp)
{
	$idp=str_replace( array('/','.','\\'),'',$idp);
	#Nowa wersja?
	if(file_exists('plugins/'.$idp.'/setup.php'))
	{
		$nv=1;
	}
	else
	{
		$nv=0;
	}
	if($_GET['inst'])
	{
		if($nv==1)
		{
			require('plugins/'.$idp.'/setup.php');
			Install();
		}
		else
		{
			require('plugins/'.$idp.'/install.php');
		}
		$content->info($lang['api_6']);
	}
	elseif($_GET['uninst'])
	{
		if($nv==1)
		{
			require('plugins/'.$idp.'/setup.php');
			Uninstall();
		}
		else
		{
			require('plugins/'.$idp.'/uninstall.php');
		}
		$content->info($lang['api_7']);
	}
	else
	{
		OpenBox($lang['api_1'],1);
		echo '<tr>
		<td align="center">'.$lang['api_3'].'<br /><br /><textarea style="width: 90%" rows="10">'.htmlspecialchars(file_get_contents('plugins/'.$idp.(($nv==1)?'/setup.php':(($_GET['del'])?'/uninstall.php':'/install.php')))).'</textarea><br /><br /></td>
  </tr>
  <tr class="eth">
		<td><input type="button" value="'.$lang['yes'].'" onclick="location=\'?a=plugins&amp;'.(($_GET['del'])?'uninst=1':'inst=1').'&amp;idp='.$idp.'\'" /></td>
	</tr>';
  CloseBox();
 }
}*/

#Pobierz zainstalowane
$res = $db->query('SELECT * FROM '.PRE.'plugins');
$res ->setFetchMode(3);
$content->info($lang['api_5']);

#Utwórz zmienne
$used  = array();
$plugs = array();
$list  = '';

#Lista
foreach($res as $plug)
{
	$used[$plug[0]] = 1;
	$plugs[] = array(
		'color' => file_exists('plugins/'.$plug[0]) ? 'green' : '#CA2204',
		'name'  => $plug[1],
		'id'    => $plug[0],
		'del'   => '?a=plugins&amp;del=1&amp;idp='.$plug[0]
	);
}

#Niezainstalowane wtyczki
foreach(scandir('./plugins') as $plug)
{
	if(!isset($used[$plug]) && strpos($plug, '.')===false)
	{
		$list .= '<option>'.$plug.'</option>';
	}
}

#Do szablonu
$content->data = array(
	'plugin' => &$plugs,
	'list'   => &$list,
);
?>
