<?php
/**************

default: 
			if(file_exists('./mod/cat/'.$type.'.txt'))
				$modID=file_get_contents('./mod/cat/'.$type.'.txt');
			else {
				Info($lang['noex']); return; }
	}
	#Go¶æ nie mo¿e pisaæ?
	if(LOGD!=1 && $cfg['gcomm']!=1) $error[]=$lang['c11'];

	#Pozycja istnieje?
	if(db_count('ID',$modID.'s',' WHERE access=1 && ID='.$id)!=1) { Info($lang['noex']); return; }

**************/


if(iCMS!=1 || !isset($_GET['id'])) exit('Error!');

#ID KOMENTARZA
$commID=isset($_GET['comm_id'])?(int)$_GET['comm_id']:0;

#ID i typ pozycji
$id   = $_GET['id'];
$type = isset($_GET['type'])?(int)$_GET['type']:0;

#Edycja? - ID komentarza
if(isset($_GET['comm_id']) && Admit('CM'))
{
	$commID=(int)$_GET['comm_id'];
}

#Nowy kom. - typ pozycji
elseif(is_numeric($_GET['type']))
{
	$type=$_GET['type'];
}

#B£¡D
else { Info($lang['noex']); return; }

#Jêzyk
require($catl.'comm.php');

#B³êdy
$error=array();

#Go¶æ nie mo¿e pisaæ?
if(LOGD!=1 && $cfg['gcomm']!=1) $error[]=$lang['c11'];

#Dane POST
if($_POST)
{
	#Dane
	$c_name=Clean($_POST['c_n']);
	$c_text=($type)?Clean($_POST['c_t']):$_POST['c_t'];
	$c_guest=(LOGD==1)?0:1;

	#D³ugo¶æ
	if(isset($c_name[51]) || isset($c_text[801]))
	{
		$error[]=$lang['c5'];
	}
  if(empty($c_text))
  {
		$error[]=$lang['c4'];
  }

  #BBCode
  if($type && $cfg['bbc']==1)
  {
		require('lib/bbcode.php');
		$c_text2=ParseBBC($c_text);
  }
	else $c_text2=&$c_text;

	#Podgl±d
	if(isset($_POST['prev']) && !$error)
	{
		OpenBox($lang['preview'],1);
		echo '<tr><td class="txt">'.nl2br(Emots(Words($c_text2))).'</td></tr>';
		CloseBox();
	}

	#Zapis
	elseif(isset($_POST['save']))
	{
		if($type)
		{
			if(LOGD==1)
			{
				$c_author=UID; //Autor
			}
			else
			{
				$c_author=empty($_POST['c_a'])?$lang['c9']:Clean($_POST['c_a'],30);

				#KOD
				if($cfg['imgsec']==1 && (empty($_POST['c_code']) || $_POST['c_code']!=$_SESSION['code']))
				{
					$error[]=$lang['c2'];
				}
			}
			#Anty-flood
			if($_SESSION['postc']>time()) $error[]=$lang['c3'];

			#Gdy nie ma b³êdów...
			if(!$error)
			{
				#Ustaw anty-flood
				$_SESSION['postc']=time()+$cfg['coml'];

				#START
				$db->beginTransaction();

				#Zapytanie
				$q=$db->prepare('INSERT INTO '.PRE.'comms (TYPE,CID,name,access,author,at,ip,date,text)
					VALUES ('.$type.','.$id.',:n,:ac,:au,:g,:ip,"'.NOW.'",:txt)');

				$q->bindValue(':ip',$_SERVER['REMOTE_ADDR']);
				$q->bindValue(':g',$c_guest,1);
				$q->bindValue(':ac',(($cfg['comm_mod']==1 && !Admit('CM'))?0:1),1); //1 = INT

				#News?
				if($type==5) $db->exec('UPDATE '.PRE.'news SET comm=comm+1 WHERE ID='.$id);

				#Jaki typ?
				switch($type)
				{
					case 1: $back_url='?co=art&amp;id='.$id; break;
					case 2: $back_url='?co=file&amp;id='.$id; break;
					case 3: $back_url='?co=img&amp;id='.$id; break;
					case 5: $back_url='?co=news&amp;id='.$id; $db->exec('UPDATE '.PRE.'news SET comm=comm+1 WHERE ID='.$id); break; //NEWS
					default: $back_url='';
				}
			}
		}
		elseif(!$error)
		{
			#Zapytanie
			$q=$db->prepare('UPDATE '.PRE.'comms SET name=:n, text=:txt WHERE ID='.$id);

			#Autor
			$c_author=Clean($_POST['c_a'],30);
			$db->exec('UPDATE '.PRE.'comms SET author='.$db->quote($c_author).' WHERE guest=1 && ID='.$id);
		}
		#B³±d?
		if(!$error)
		{
			$q->bindValue(':n',$c_name);
			$q->bindValue(':au',$c_author);
			$q->bindParam(':txt',$c_text2);
			$q->execute();

			#OK?
			try
			{
				$db->commit();
				Info($lang['c6']); return;
			}
			catch(PDOException $e)
			{
				$db->rollBack();
				Info($lang['c10'].$e->errorCode());
			}
		}
	}
	#B³±d
	if($error) Info('<ul><li>'.join('</li><li>',$error).'</li></ul>');
	if(!$type) $c_text=Clean($c_text);
}

#Odczyt
else
{
	if($type)
	{
		$c_name='';
		$c_author=UID;
		$c_guest=(LOGD==1)?0:1;
	}
	else
	{
		$comm=$db->query('SELECT * FROM '.PRE.'comms WHERE ID='.$id)->fetch(2);
		$c_name=$comm['name'];
		$c_author=$comm['author'];
		$c_guest=$comm['at'];
	}
	$c_text=&$comm['text'];
}

$c_code=($c_guest && $cfg['imgsec']==1)?1:0;
$c_url='?co=comm&amp;id='.$id.(($type)?'&amp;type='.$type:'');
$c_box_title=($type)?$lang['addcomm']:$lang['c1'];

#Styl
include($catst.'comment_edit.php');

#JS
Init($catl.'edit.js');
Init('lib/forms.js');
if($cfg['bbc']==1) Init('lib/editor.js');

?>
<script type="text/javascript">
<!--
<?= (($cfg['bbc']==1)?'var ed=new Editor("c_t"); ed.bbcode=1;':'') ?>
ed.Emots();
var cf=new Request('comm','cbox');
-->
</script>