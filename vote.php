<?php
if(!$_POST) exit;
require('./kernel.php');
if($_GET['id']) { $id=$_GET['id']; } else { exit('Error: wrong ID.'); }

#Oceny
if(is_numeric($_GET['t']))
{
 #Opcje
 require_once('./cfg/c.php');
 $c=$_COOKIE[$cfg['c'].'rates'];
 
 if(LOGD!=1 && $cfg['grate']!=1) exit('Error: access denied.');
 
 #Tabela (nie usuwaj!)
 $table='';
 switch($_GET['t'])
 {
	case 1: if($cfg['arate']==1) { $table='art'; } break;
	case 2: if($cfg['frate']==1) { $table='file'; } break;
	case 3: if($cfg['irate']==1) { $table='img'; } break;
 }

 #B³¹d?
 if($table=='')
 {
	exit('Error: rating disabled.');
 }
 #Ocenia³?
 if(strpos($c,'x'.$_GET['t'].':'.$id.'x')!==false)
 {
	define('SPECIAL',6);
 }
 else
 {
  #ID/IP
	if(LOGD==1)
	{
	 $usr=UID;
	}
	else
	{
	 $usr='"'.db_esc($_SERVER['REMOTE_ADDR'].' '.$_SERVER['HTTP_X_FORWARDED_FOR']).'"';
	}
	
	#Jest blokada?
	if(db_count('ID',$table.'rates',' WHERE ID='.$id.' AND user='.$usr)==0)
	{
	 $new=1;
	}
	else { $new=0; }
	
	#Zapis
	if($_POST['v'])
	{
	 //ISNUMERIC
	 
	}
	
	#Formularz
	else
	{
	 echo '<form action="vote.php?id='.$id.'&amp;t='.$_GET['t'].'" method="post">
	 <input type="radio" name="v" value="5" /> '.$lang['vgood'].'
	 <input type="radio" name="v" value="4" /> '.$lang['good'].'
	 <input type="radio" name="v" value="3" /> '.$lang['dstg'].'
	 <input type="radio" name="v" value="2" /> '.$lang['weak'].'
	 <input type="radio" name="v" value="1" /> '.$lang['fatal'].'
	 </form>';
	}
 }
}

#Ankieta
if($_GET['t']=='poll')
{
 $poll['ID']='';
 $option=array();
 $vote=&$_POST['u_vote'];
 $c=$_COOKIE[$cfg['c'].'polls'];
 
 db_read('ID,ison,type','polls','poll','oa',' WHERE access="'.$nlang.'" ORDER BY ID DESC LIMIT 1');
 
 #Brak odp.
 if(!isset($vote))
 {
	define('SPECIAL',22);
 }
 elseif($poll['ID']==$id)
 {
  #ID/IP
	if($poll['ison']==3 && LOGD==1)
	{
	 $usr=UID;
	}
	elseif($poll['ison']==1)
	{
	 $usr='"'.db_esc($_SERVER['REMOTE_ADDR'].' '.$_SERVER['HTTP_X_FORWARDED_FOR']).'"';
	}
	else { exit; }
	
	#G³osowa³?
	if(strpos($c,'x'.$id.'x')!==false)
	{
	 define('SPECIAL',6);
	}
	else
	{
	 if(db_count('ID','pollvotes',' WHERE ID='.$id.' AND user='.$usr)==0)
	 {
		$q='';
		#1 odp.
		if($poll['type']==1)
		{
		 if(!is_numeric($vote)) exit;
		 $q=$vote;
		}
		else
		{
		 $key=array_keys($vote);
		 $ile=count($key);
		 for($i=0;$i<$ile;++$i) { if(!is_numeric($key[$i])) exit('Error: wrong option value!'); }
		 $q=implode(',',$key);
	  }
		#Aktualizuj
		db_q('UPDATE {pre}polls SET num=num+1 WHERE ID='.$id);
		db_q('UPDATE {pre}answers SET num=num+1 WHERE IDP='.$id.' AND ID IN ('.$q.')');

		#Zabezpiecz
		db_q('INSERT INTO {pre}pollvotes (user,ID,date) VALUES ('.$usr.','.$id.',NOW())');
	 }
	 $c=strrchr($c,'x').'x'.$id.'x';
	 setcookie($cfg['c'].'polls',$c,time()+7776000);
	 define('SPECIAL',5);
	 define('WHERE','index.php?co=poll&amp;id='.$id);
	}
 }
 else
 {
	define('SPECIAL',13);
 }
 require('special.php');
}
exit;
?>
