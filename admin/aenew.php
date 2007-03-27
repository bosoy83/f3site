<?php
if(iCMSa!='X159E' || !ChPrv('N') || $_REQUEST['news']) exit;
$send=(($_POST['send'])?1:2);
require($catl.'adm_z.php');
if(isset($_GET['id'])) { $id=$_GET['id']; } else { $id='new'; }
#Zapis?
if($send==1)
{
 $xu_c=db_esc($_POST['xu_c']);
 $xu_n=db_esc(TestForm($_POST['xu_n'],1,1,0));
 $xu_ftxt=TestForm($_POST['xu_ftxt'],0,0,0);
 $xu_i=db_esc(TestForm($_POST['xu_i'],1,1,0));
 $xu_txt=TestForm($_POST['xu_txt'],0,0,0);
 if(strlen($xu_txt)>30000 || strlen($xu_ftxt)>50000) { unset($_POST['sav'],$sav); echo '<script type="text/javascript">alert("'.$lang['txttoolong'].'")</script>'; }
}

if($_POST['sav'])
{
 if($id=='new')
 {
  db_q('INSERT INTO {pre}news VALUES ("","'.$xu_c.'","'.$xu_n.'","'.db_esc($xu_txt).'","'.strftime('%Y-%m-%d %H:%M:%S').'","'.$user[UID]['ID'].'",'.(($_POST['xu_fn'])?1:2).',"'.$xu_i.'",'.(($_POST['xu_emo'])?1:2).',0,'.(($_POST['xu_br'])?1:2).',"'.db_esc($_POST['xu_a']).'")');
  db_q('INSERT INTO {pre}fnews VALUES ('.db_id().',"'.$xu_c.'","'.db_esc($xu_ftxt).'")');
  if($_POST['xu_a']!=2) ChItmN($xu_c,'+1');
 }
 else
 {
  $news[0]=0;
  db_read('cat','news','news','on',' WHERE ID='.$id);
  db_q('UPDATE {pre}news SET cat="'.$xu_c.'", name="'.$xu_n.'", txt="'.db_esc($xu_txt).'", fn='.(($_POST['xu_fn'])?1:2).', img="'.$xu_i.'", emo='.(($_POST['xu_emo'])?1:2).', br='.(($_POST['xu_br'])?1:2).', access="'.db_esc($_POST['xu_a']).'" WHERE ID='.$id);
  db_q('UPDATE {pre}fnews SET cat="'.$xu_c.'", text="'.db_esc($xu_ftxt).'" WHERE ID='.$id);
  #Ilo¶æ artów
  if($news[0]!=$xu_c) { ChItmN($xu_c,'+1'); ChItmN($news[0],'-1'); }
  if($news[1]==2 && $_POST['xu_a']!=2) ChItmN($xu_c,'+1');
  if($news[1]!=2 && $_POST['xu_a']==2) ChItmN($xu_c,'-1');
 }
 Info('<div align="center">'.$lang['saved'].'<br /><br /><a href="?a=enew">'.$lang['addnews'].'</a></div>');
}
#Podgl±d
if($_POST['preview'])
{
 cTable($lang['preview'].': '.$lang['text'],1);
 if($_POST['xu_br']) { $xprev=nl2br($xu_txt); } else { $xprev=&$xu_txt; }
 echo '<tr><td class="txt">'.(($xu_i==null || empty($xu_i))?'':'<img src="'.$xu_i.'" alt="..." align="left" style="padding: 3px; padding-right: 5px" />').(($_POST['xu_emo'])?Emots($xprev):$xprev).'</td></tr>';
 eTable();
 #Pe³ny
 if($_POST['xu_fn'])
 {
  cTable($lang['preview'].': '.$lang['ftxt'],1);
  if($_POST['xu_br']) { $xprev2=nl2br($xu_ftxt); } else { $xprev2=&$xu_ftxt; }
  echo '<tr><td class="txt">'.(($_POST['xu_emo'])?Emots($xprev2):$xprev2).'</td></tr>';
  eTable();
 }
}

#Odczyt
if($id!='new' && $send==2)
{
 db_read('*','news','news','oa',' WHERE ID='.$id);
 db_read('*','fnews','fnews','oa',' WHERE ID='.$id);
 if(empty($news['ID'])) { exit('News nie istnieje!'); }
}

#Form
if(!$_POST['sav'])
{
 db_read('ID,name','cats','xcat','tn',' WHERE type=5');
 $ile=count($xcat);
 #Zmienne
 if($send==1)
 {
  $xyc=&$_POST['xu_c'];
  $xya=&$_POST['xu_a'];
  $xybr=($_POST['xu_br'])?1:2;
  $xyemo=($_POST['xu_emo'])?1:2;
  $xyfn=($_POST['xu_fn'])?1:2;
 }
 else
 {
  $xyc=&$news['cat'];
  $xya=($id=='new')?1:$news['access'];
  $xybr=($id=='new')?1:$news['br'];
  $xyemo=($id=='new')?2:$news['emo'];
  $xyfn=($id=='new')?2:$news['fn'];
 }
 require('inc/btn.php');
 echo '<form action="adm.php?a=enew'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
 cTable( (($id=='new')?$lang['addnews']:$lang['editnews']) ,0);
 echo '
 <tr>
  <td style="width: 31%"><b>1. '.$lang['cat'].':</b></td>
  <td><select name="xu_c">'; for($i=0;$i<$ile;$i++) { echo '<option value="'.$xcat[$i][0].'"'.(($xyc==$xcat[$i][0])?' selected="selected"':'').'>'.$xcat[$i][1].'</option>'; } echo '<option value="0">'.$lang['lack'].'</option></select></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['title'].':</b></td>
  <td><input maxlength="50" name="xu_n" value="'.(($send==1)?$xu_n:$news['name']).'" /></td>
 </tr>
 <tr>
  <td><b>3. '.$lang['ap_acc'].':</b></td>
  <td><select name="xu_a"><option value="1">'.$lang['ap_isaon'].'</option>'.sListBox('lang',1,$xya).'<option value="2"'.(($xya==2)?' selected="selected"':'').'> '.$lang['ap_isaoff'].'</option></td>
 </tr>
 <tr>
  <td><b>4. '.$lang['img'].':</b><div class="txtm">'.$lang['0off'].'</div></td>
  <td><input name="xu_i" id="xu_i" value="'.(($send==1)?$xu_i: (($id=='new')?'0':$news['img']) ).'" />'.((ChPrv('FM'))?' <input type="button" value="'.$lang['images'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=xu_i\',580,400,150,150)" />':'').'</td>
 </tr>
 <tr>
  <td><b>5. '.$lang['opt'].':</b></td>
  <td><input type="checkbox" name="xu_emo"'.(($xyemo==1)?' checked="checked"':'').' /> '.$lang['emoon'].'<br /><input type="checkbox" name="xu_br"'.(($xybr==1)?' checked="checked"':'').' /> '.$lang['br'].'<br /><input type="checkbox" name="xu_fn"'.(($xyfn==1)?' checked="checked"':'').' /> '.$lang['ftxt'].'</td>
 </tr>
 ';
 eTable();
 cTable($lang['text'],1);
 echo '
 <tr>
  <td align="center">'; Colors('xu_txt',1); FontBtn('xu_txt',1); echo '<textarea style="width: 95%; margin: 3px 0px 3px 0px" rows="7" id="xu_txt" name="xu_txt">'.htmlspecialchars( (($send==1)?$xu_txt:$news['txt']) ).'</textarea><br />'; Btns(1,1,'xu_txt'); SpecChr('xu_txt'); echo '</td>
 </tr>
 ';
 eTable();
 cTable($lang['ftxt'],1);
 echo '
 <tr>
  <td align="center">'; Colors('xu_ftxt',1); FontBtn('xu_ftxt',1); echo '<textarea style="width: 95%; margin: 3px 0px 3px 0px" id="xu_ftxt" rows="8" name="xu_ftxt">'.htmlspecialchars( (($send==1)?$xu_ftxt:$fnews['text']) ).'</textarea><br />'; Btns(1,1,'xu_ftxt'); SpecChr('xu_ftxt'); echo '</td>
 </tr>
 <tr class="eth">
  <td><input type="hidden" name="send" value="1" /><input type="submit" name="preview" value="'.$lang['preview'].'" /> <input type="submit" name="sav" value="'.$lang['save'].'" /></td>
 </tr>
 ';
 eTable();
}
?>
</form>
