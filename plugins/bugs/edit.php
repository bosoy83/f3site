<?php 
if(iCMS!='E123') exit;
$id=($_GET['id'])?$_GET['id']:'new';
$_err='';
$cat=array();
$bug=array();

#SQL
if($id!='new')
{
 db_read('cat,name,level,poster,text','bugs','bug','oa',' WHERE ID='.$id);
 #Prawa
 if(!ChPrv('BUGS') && ($bug['poster']!=UID || $cfg['bugs_ae']!=1)) $_err.=$lang['bugs_nr'].'<br /><br />';
 #Kat.
 if(isset($bug['cat']))
 {
  $f=$bug['cat'];
 }
 else
 {
  $_err.=$lang['noex'].'<br /><br />';
	$f=0;
 }
}
else
{
 #Kat.
 if(!$_GET['f'] && !is_numeric($_GET['f'])) exit;
 $f=$_GET['f'];
}

#Kategoria
db_read('name,see,report,text','bugcats','cat','on',' WHERE ID='.$f);
if(!isset($cat[1]) || !BugRights($cat[2])) $_err.=$lang['bugs_nr'].'<br /><br />';

#Test
if($_POST)
{
 $xb_t=db_esc(TestForm($_POST['xb_t'],1,1,0));
 $xb_e=db_esc(TestForm($_POST['xb_e'],1,1,0));
 $xb_txt=db_esc(Words(TestForm($_POST['xb_txt'],1,1,0)));
 if(!is_numeric($_POST['xb_lv'])) exit;
 if($xb_t=='' || $xb_txt=='') $_err.=$lang['bugs_f'].'<br /><br />';
 if(strlen($_POST['xb_txt'])>$cfg['bugs_tl']) $_err.=str_replace('%',$cfg['bugs_tl'],$lang['bugs_max']).'<br /><br />';
 if($_SESSION['postc']>time()) $_err.=$lang['bugs_t'].'<br /><br />';
 
 #Zapis
 if($_err=='')
 {
  $_SESSION['postc']=time()+$cfg['coml'];
	
	#Linki
	$l='<br /><br /><a href="?co=bugs">'.$lang['bugs_mp'].'</a> &raquo; <a href="?co=bugs&amp;act=l&amp;id='.$f.'">'.$cat[0].'</a> &raquo; <a href="?co=bugs&amp;act=v&amp;id=';
	$l2='">'.$xb_t.'</a>';
	
  #Nowy
  if($id=='new')
	{
	 #Autor
	 if(LOGD==1)
	 {
		$xb_a=UID;
	 }
	 else
	 {
		$xb_a=(empty($_POST['xb_a']))?$lang['guest']:db_esc(TestForm($_POST['xb_a'],1,1,0,30));
	 }
	 if($cfg['imgsec']==1 && ($_POST['xb_c']!=$_SESSION['code'] || empty($_POST['xb_c'])))
	 {
		$_err.=$lang['bugs_i'].'<br /><br />';
	 }
	 else
	 {
	  #Status 5 = niemoderowany
		db_q('INSERT INTO {pre}bugs VALUES ("",'.$f.',"'.$xb_t.'",0,NOW(),'.(($cfg['bugs_mod']==1)?5:4).','.$_POST['xb_lv'].',"'.$_POST['xb_e'].'",0,0,"'.$xb_a.'","'.db_esc($_SERVER['REMOTE_ADDR']).'","'.$xb_txt.'")');
		#Dodano
		if($cfg['bugs_mod']==1)
		{
		 Info($lang['bugs_sm'].$l.db_id().$l2);
		}
		else
		{
		 Info($lang['bugs_sv'].$l.db_id().$l2);
		}
		db_q('UPDATE {pre}bugcats SET last=NOW(), num=num+1 WHERE ID='.$f);
	 }
	}
	#Edycja
	else
	{
	 db_q('UPDATE {pre}bugs SET name="'.$xb_t.'", env="'.$xb_e.'", level='.$_POST['xb_lv'].', text="'.$xb_txt.'" WHERE ID='.$id);
	 Info($lang['bugs_sv'].$l.$id.$l2);
	}
 } 
}

#Edycja
if(!$_POST || $_err!='')
{
 #B³êdy
 if($_err!='')
 {
  Info($lang['bugs_fix'].'<br /><br />'.$_err);
 }
 #Info
 if($cfg['bugs_i2']==1) { if(!empty($cat[3]) && $_err=='') Info(nl2br($cat[3])); }
 
 #Form
 echo '<form method="post" action="?co=bugs&amp;act=e'.(($id=='new')?'&amp;f='.$f:'&amp;id='.$id).'">';
 if($id=='new')
 {
  cTable($lang['bugs_new'],2);
	$xlv=($_POST)?$_POST['xb_lv']:3;
 }
 else
 {
  cTable($lang['bugs_e'],2);
  $xlv=($_POST)?$_POST['xb_lv']:$bug['level'];
 }
 echo 
 ((LOGD==2)?
 (($cfg['imgsec']==1)?'
 <tr>
  <td><b>'.$lang['code'].':</b><div class="txtm">'.$lang['imgcode'].'.</div></td>
  <td><img src="code.php" alt="CODE" style="margin-bottom: 5px; border: 1px solid gray" /><br /><input name="c_code" /></td>
 </tr>':'').'
 <tr>
  <td>'.$lang['author'].':</td>
	<td><input name="xb_a" maxlength="20" value="'.$_POST['xb_a'].'" /></td>
 </tr>':'').'
 <tr>
  <td>'.$lang['title'].':</td>
	<td><input name="xb_t" maxlength="30" size="30" value="'.(($_POST)?$_POST['xb_t']:$bug['name']).'" /></td>
 </tr>
 <tr>
  <td>'.$lang['bugs_p'].':<div class="txtm">'.$lang['bugs_o'].'</div></td>
	<td><input name="xb_e" maxlength="100" size="50" value="'.(($_POST)?$_POST['xb_e']:$bug['env']).'"</td>
 </tr>
 <tr>
  <td>'.$lang['level'].':</td>
	<td><select name="xb_lv" style="font-weight: bold">
	 <option style="color: red" value="1">'.$lang['bugs_s1'].'</option>
	 <option style="color: #c4ab00"'.(($xlv==2)?' selected="selected"':'').' value="2">'.$lang['bugs_s2'].'</option>
	 <option style="color: #8aa61e"'.(($xlv==3)?' selected="selected"':'').' value="3">'.$lang['bugs_s3'].'</option>
	 <option style="color: #21a393"'.(($xlv==4)?' selected="selected"':'').' value="4">'.$lang['bugs_s4'].'</option>
	</select></td>
 </tr>';
 eTable();
 cTable($lang['text'],1);
 echo '
 <tr>
  <td align="center">';
	
	#BBCode
	if($cfg['bbc']==1)
	{
	 include_once('inc/btn.php');
	 echo '<div style="padding-bottom: 3px">';
	 Colors('xb_txt',2);
	 FontBtn('xb_txt',2); 
	 echo '</div>';
	}
	echo '<textarea name="xb_txt" id="xb_txt" rows="10" cols="50">'.(($_POST)?htmlspecialchars($_POST['xb_txt']):$bug['text']).'</textarea>';
  if($cfg['bbc']==1) { echo '<div style="padding-top: 3px">'; Btns(2,0,'xb_txt'); echo '</div>'; }
	
  #Emoty
  include_once('cfg/emots.php');
  $ile=count($emodata);
  if($ile>0)
  {
   echo '<div style="padding: 3px">';
   for($i=0;$i<$ile;$i++)
   {
    echo '<img src="img/emo/'.$emodata[$i][1].'" style="cursor: pointer" alt="'.$emodata[$i][2].'" title="'.$emodata[$i][0].'" onclick="BBC(\'xb_txt\',\''.$emodata[$i][2].'\',\'\')" /> ';
   }
   echo '</div>';
  }
	
  echo '</td>
 </tr>
 <tr>
  <td class="eth"><input type="submit" value="'.$lang['save'].'" /></td>
 </tr>';
 eTable();
 echo '</form>';
}
?>