<?php
if(iCMS!='E123' || !isset($_GET['id']) || $_REQUEST['comm']) exit('Error!');
$id=$_GET['id'];
if($_GET['type']) { $_xn=2; $type=$_GET['type']; if(!is_numeric($type)) exit('$TYPE error!'); } else { if(!ChPrv('CM')) exit; $c_a='a'; $type='e'; $_xn=1; }
if(LOGD==1 || $cfg['gcomm']==1)
{
 require($catl.'comm.php');
 $_xetxt='';
 #Pola
 if($_POST)
 {
  $c_n=TestForm($_POST['c_n'],1,1,0);
  $c_t=TestForm($_POST['c_t'],(($type=='e')?2:1),1,0);
  if(strlen($c_n)>50 || strlen($c_t)>800)
  {
   $_xetxt.=$lang['c5'].'<br /><br />';
  }
  if(empty($c_t))
  {
   $_xetxt.=$lang['c4'].'<br /><br />';
  }
  #BBCode
  if($type!='e' && ($_POST['prev'] || $_POST['sav']))
  {
   if($cfg['bbc']==1)
   {
    require('inc/bbcode.php');
    $c_t1=ParseBBC($c_t);
   }
   else
   {
    $c_t1=&$c_t;
   }
  }
 }
 #Odczyt
 elseif($type=='e')
 {
  db_read('*','comms','comm','oa',' WHERE ID='.$id);
 }
 #Podgl±d
 if($_POST['prev'] && $_xetxt=='')
 {
  cTable($lang['preview'],1);
  echo '<tr><td class="txt"><div class="txtm">'.genDate(strftime('%Y-%m-%d %H:%M:%S')).' &middot; '.Autor(UID).'</div>'.Emots(nl2br(Words($c_t1))).'</td></tr>';
  eTable();
 }
 #Zapis
 if($_POST['sav'])
 {
  #Nowy
  if($type!='e')
  {
   if(LOGD==1)
   {
    $c_a=UID;
   }
   else
   {
    $c_a=(empty($_POST['c_a']))?$lang['c9']:TestForm($_POST['c_a'],1,1,1,30);
    #Kod
    if($cfg['imgsec']==1 && ($_POST['c_code']!=$_SESSION['code'] || empty($_POST['c_code'])))
    {
     $_xetxt.=$lang['c2'].'<br /><br />';
    }
   }
   if($_SESSION['postc']>time())
   {
    $_xetxt.=$lang['c3'].'<br /><br />';
   }
   if($_xetxt=='')
   {
    $_SESSION['postc']=time()+$cfg['coml'];
    db_q('INSERT INTO {pre}comms VALUES ("","'.$type.'_'.$id.'","'.db_esc($c_n).'","'.db_esc($c_a).'",'.((LOGD==1)?1:2).',"'.db_esc(TestForm($_SERVER['REMOTE_ADDR'],0,1,1)).'","'.strftime('%Y-%m-%d %H:%M:%S').'","'.db_esc(Words($c_t1)).'")');
    if($type==5) db_q('UPDATE {pre}news SET comm=comm+1 WHERE ID='.$id);
    Info('<center>'.$lang['c6'].'<br /><br /><a href="?co='.$_GET['turl'].'&amp;id='.$id.(($_GET['om'])?'&amp;om=1':'').'">'.$lang['c8'].'</a></center>');
   }
  }
  elseif($_xetxt=='')
  {
   db_q('UPDATE {pre}comms SET name="'.db_esc($c_n).'", text="'.db_esc(Words($c_t)).'" WHERE ID='.$id);
   Info('<center>'.$lang['c7'].'</center>');
  }
 }
 #B³êdy
 if($_xetxt!='' && !$_POST['xcm']) Info($_xetxt);
 #Nowy i edycja
 if(!$_POST['sav'] || $_xetxt!='')
 {
  echo '<form action="?co=comm&amp;id='.$id.(($type=='e')?'':'&amp;type='.$type).'&amp;turl='.$_GET['turl'].(($_GET['om'])?'&amp;om=1':'').'" method="post">';
  cTable((($type=='e')?$lang['c1']:$lang['addcomm']),2);
  echo ((LOGD==2)?(($cfg['imgsec']==1)?'
 <tr>
  <td><b>'.$lang['code'].':</b><div class="txtm">'.$lang['imgcode'].'.</div></td>
  <td><img src="code.php" alt="CODE" style="margin-bottom: 5px; border: 1px solid gray" /><br /><input name="c_code" /></td>
 </tr>':'').'
 <tr>
  <td><b>'.$lang['author'].':</b></td>
  <td><input name="c_a" maxlength="20" value="'.$_POST['c_a'].'" /></td>
 </tr>':'').'
 <tr>
  <td style="width: 110px"><b>'.$lang['title'].':</b></td>
  <td><input name="c_n" value="'.(($_POST)?$c_n:$comm['name']).'" maxlength="40" /></td>
 </tr>
 <tr>
  <td><b>'.$lang['text'].':</b>'.(($cfg['bbc']==1)?'<br /><br /><a href="javascript:Okno(\'?mode=doc&amp;id=1\',500,300,180,170)">BBCode &raquo;</a>':'').'</td>
  <td>'; if($cfg['bbc']==1) { include_once('inc/btn.php'); echo '<div style="padding-bottom: 3px">'; Colors('c_t',$_xn); FontBtn('c_t',$_xn); echo '</div>'; } echo '<textarea name="c_t" id="c_t" rows="7" cols="38">'.(($_POST)?$c_t:htmlspecialchars($comm['text'])).'</textarea>';
  if($cfg['bbc']==1) { echo '<div style="padding-top: 3px">'; Btns($_xn,0,'c_t'); echo '</div>'; }
  #Emoty
  include_once('cfg/emots.php');
  $ile=count($emodata);
  if($ile>0)
  {
   echo '<div style="padding: 3px">';
   for($i=0;$i<$ile;$i++)
   {
    echo '<img src="img/emo/'.$emodata[$i][1].'" style="cursor: pointer" alt="'.$emodata[$i][2].'" title="'.$emodata[$i][0].'" onclick="BBC(\'c_t\',\''.$emodata[$i][2].'\',\'\')" /> ';
   }
   echo '</div>';
  }
  echo '</td>
 </tr>
 <tr class="eth">
  <td colspan="2"><input type="submit" value="'.$lang['preview'].'" name="prev" /> <input type="submit" value="'.$lang['save'].'" name="sav" /></td>
 </tr>';
 eTable();
  echo '</form>';
 }
}
?>
