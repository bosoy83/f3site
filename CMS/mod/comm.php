<?php
if(iCMS!=1 || !isset($_GET['id'])) return; 

//ID komentarza lub pozycji
$id=$_GET['id'];

//Je¶li istnieje TYP pozycji w GET - nowy komentarz, inaczej - edycja
if(isset($_GET['type']))
{
	$type=(int)$_GET['type'];
	switch($type)
	{
		case 1: $modID='art'; break;
		case 2: $modID='file'; break;
		case 3: $modID='img'; break;
		case 5: $modID='new'; break;
		case 12: $modID='poll'; break;
		case 59: $modID='page'; break;
		default: $modID='';
	}
	#Go¶æ nie mo¿e pisaæ?
	if(LOGD!=1 && $cfg['gcomm']!=1) $error[]=$lang['c11'];
}
elseif(Admit('CM'))
{
	$type=null;
}
else
{
	Info($lang['noex']); return; //B³êdny typ
}

#Jêzyk
require($catl.'comm.php');

#B³êdy
$error=array();

#Dane POST
if($_POST)
{
	#Dane
	$c_name=Clean($_POST['c_n'],30,1);
	$c_text=($type)?Clean($_POST['c_t'],0,1):$_POST['c_t'];
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
		echo '<tr><td class="txt">'.nl2br(Emots($c_text2)).'</td></tr>';
		CloseBox();
	}

	#Zapis
	elseif(isset($_POST['save']))
	{
		#START
		$db->beginTransaction();

		if($type) //NOWY KOM.
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

			#Moderowaæ?
			$c_access=($cfg['comm_mod']!=1 || LEVEL>1 || Admit('CM'))?1:0;

			#Gdy nie ma b³êdów...
			if(!$error)
			{
				#Ustaw anty-flood
				$_SESSION['postc']=time()+$cfg['coml'];

				#Zapytanie
				$q=$db->prepare('INSERT INTO '.PRE.'comms (TYPE,CID,name,access,author,guest,ip,date,text)
					VALUES ('.$type.','.$id.',:n,:ac,:au,:g,:ip,"'.NOW.'",:txt)');

				$q->bindValue(':ip',$_SERVER['REMOTE_ADDR']);
				$q->bindValue(':au',$c_author);
				$q->bindValue(':g',$c_guest,1);
				$q->bindValue(':ac',$c_access,1); //1 = INT

				#News?
				if($type==5) { $db->exec('UPDATE '.PRE.'news SET comm=comm+1 WHERE ID='.$id); $modID='news'; }
			}
		}
		elseif(!$error) //EDYCJA KOM.
		{
			#Zapytanie
			$q=$db->prepare('UPDATE '.PRE.'comms SET name=:n, text=:txt WHERE ID='.$id);

			#Autor
			if(isset($_POST['c_a']))
			{
				$c_author=Clean($_POST['c_a'],30);
				$db->exec('UPDATE '.PRE.'comms SET author='.$db->quote($c_author).' WHERE guest=1 && ID='.$id);
			}
		}
		if(!$error) //WSPÓLNE
		{
			$q->bindValue(':n',$c_name);
			$q->bindParam(':txt',$c_text2);
			$q->execute();

			#OK?
			try
			{
				$db->commit();
				Info($lang[ (($type && $c_access!=1)?'c6':'c7') ]); return;
			}
			catch(PDOException $e)
			{
				Info($lang['c10'].$e->errorCode());
			}
		}
		$db->rollBack();
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
		$c_guest=$comm['guest'];
	}
	$c_text=&$comm['text'];
}

$c_code=($c_guest && $cfg['imgsec']==1)?1:0;
$c_url='?co=comm&amp;id='.$id.(($type)?'&amp;type='.$type:'');
$c_box_title=($type)?$lang['addcomm']:$lang['c1'];

#Styl
include($catst.'comment_edit.php');

#JS
if($cfg['bbc']==1) {
	Init($catl.'edit.js');
	Init('cache/emots.js');
	Init('lib/editor.js'); ?>

<script type="text/javascript">
<!--
var ed=new Editor("c_t")
ed.bbcode=1
ed.Emots()
ed.Rows(12)
-->
</script>
<?php } ?>