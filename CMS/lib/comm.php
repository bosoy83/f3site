<?php
if(iCMS!=1) exit;
if(!is_numeric(CT) || !is_numeric($id)) exit('Error: wrong content type!');

#Strona
if($cfg['cnp']!=0)
{ 
	if(isset($_GET['page']) && $_GET['page']>1)
	{
		$page=$_GET['page'];
		$st=($page-1)*$cfg['cnp'];
	}
	else
	{
		$page=1;
		$st=0;
	}
	#ILE?
	$c_total=db_count('ID','comms',' WHERE TYPE='.CT.' AND CID='.$id);
}
else $c_total=null;

if($c_total!==0)
{
	#SQL
	$res=$db->query('SELECT c.ID,c.name,c.author,c.ip,c.date,c.text,u.login
		FROM '.PRE.'comms c LEFT JOIN '.PRE.'users u ON c.author=u.ID AND c.guest!=1
		WHERE c.TYPE='.CT.' AND c.CID='.$id.(($cfg['csort']==2)?'':' ORDER BY ID DESC').
		(($cfg['cnp']!=0)?' LIMIT '.$st.','.$cfg['cnp']:''));

	$ile=0;
	$y=1;
	$rights=Admit('CM');

	#Komentarze
	$comm=array();
	foreach($res as $c)
	{
		$comm[]=array(
		'author'=>(($c['login'])?'<a href="?co=user&amp;id='.$c['author'].'">'.$c['login'].'</a>':$c['author']),
		'edit_url'=>'?co=comm&amp;id='.$c['ID'].'&amp;turl='.$_GET['co'],
		'del_url'=>'?',
		'class'=>'post'.$y,
		'text'=>nl2br(Emots($c['text'])),
		'ip'  =>$c['ip'],
		'name'=>$c['name'],
		'date'=>genDate($c['date']),
		'rights'=>$rights);

		if($y==2) $y=1; else ++$y;
		++$ile;
	}

	#Strony
	if($ile>0)
	{
		$pages=($c_total>$ile && $cfg['cnp']!=0) ?
			Pages($page,$c_total,$cfg['cnp'],'?co='.$_GET['co'].'&amp;id='.$id,2):'';

		require($catst.'comments.php'); //Styl
	}
	$res=null;
	unset($y,$ile,$comm,$c);
}

#Form
if(LOGD==1 || $cfg['gcomm']==1)
{
	if(!isset($_SESSION['postc']) || $_SESSION['postc']<time())
	{
		$c_name='';
		$c_text='';
		$c_url='?co=comm&amp;id='.$id.'&amp;type='.CT;
		$c_guest=(LOGD==1)?0:1;
		$c_code=($c_guest && $cfg['imgsec']==1)?1:0;
		$c_box_title=$lang['addcomm'];
		include($catst.'comment_edit.php');
	}
}
else
{
 Info($lang['nounrc']);
}
?>
