<?php /* Wyœwietl komentarze */

return; //Jeszcze nie dzia³aj¹

class Comments
{
	public
		$id,   #ID pozycji
		$type; #Typ pozycji

	#Utwórz obiekt
	function __construct($id, $type)
	{
		$this->id = (int)$id;
		$this->type = (int)$type;
	}

	#Wyœwietl komentarze
	function display()
	{
		global $cfg;

		#Podzia³ na strony?
		if($cfg['commNum']!=0)
		{
			#Strona
			if(isset($_GET['page']) && $_GET['page']>1)
			{
				$page = $_GET['page'];
				$st = ($page-1)*$cfg['commNum'];
			}
			else
			{
				$page = 1;
				$st = 0;
			}

			#Policz wszystkie komentarze
			$total = db_count('ID','comms WHERE TYPE='.$this->type.' AND CID='.$this->id);
		}
		else
		{
			$total = null;
		}

		if($total !== 0)
		{
			#SQL
			$res = $GLOBALS['db']->query('SELECT c.ID,c.name,c.author,c.ip,c.date,c.text,u.login
				FROM '.PRE.'comms c LEFT JOIN '.PRE.'users u ON c.author=u.ID AND c.guest!=1
				WHERE c.TYPE='.CT.' AND c.CID='.$this->id.
				(($cfg['commSort']==2) ? '' : ' ORDER BY ID DESC').
				(($cfg['commNum']!=0) ? ' LIMIT '.$st.','.$cfg['commNum'] : ''));
	}
}

#Strona
if($cfg['commNum']!=0)
{ 
	if(isset($_GET['page']) && $_GET['page']>1)
	{
		$page=$_GET['page'];
		$st=($page-1)*$cfg['commNum'];
	}
	else
	{
		$page=1;
		$st=0;
	}

	#Policz wszystkie komentarze
	$c_total=db_count('ID','comms WHERE TYPE='.CT.' AND CID='.$id);
}
else $c_total=null;

if($c_total!==0)
{
	#SQL
	$res=$db->query('SELECT c.ID,c.name,c.author,c.ip,c.date,c.text,u.login
		FROM '.PRE.'comms c LEFT JOIN '.PRE.'users u ON c.author=u.ID AND c.guest!=1
		WHERE c.TYPE='.CT.' AND c.CID='.$id.(($cfg['commSort']==2)?'':' ORDER BY ID DESC').
		(($cfg['commNum']!=0)?' LIMIT '.$st.','.$cfg['commNum']:''));

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
		$pages=($c_total>$ile && $cfg['commNum']!=0) ?
			Pages($page,$c_total,$cfg['commNum'],'?co='.$_GET['co'].'&amp;id='.$id,2):'';

		require(VIEW_DIR.'comments.php'); //Styl
	}
	$res=null;
	unset($y,$ile,$comm,$c);
}

#Form
if(LOGD==1 || $cfg['commGuest']==1)
{
	if(!isset($_SESSION['postc']) || $_SESSION['postc']<time())
	{
		$c_name='';
		$c_text='';
		$c_url='?co=comm&amp;id='.$id.'&amp;type='.CT;
		$c_guest=(LOGD==1)?0:1;
		$c_code=($c_guest && $cfg['captcha']==1)?1:0;
		$c_box_title=$lang['addcomm'];
		include(VIEW_DIR.'comment_edit.php');
	}
}
else
{
 $content->info($lang['nounrc']);
}
?>
