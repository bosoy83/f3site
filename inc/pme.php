<?php
if(iCMS!='E123' || isset($_REQUEST['pm'])) exit;
#Wys³ane dane
if($_POST)
{
 #Dane
 $_bl='';
 $pm_to=TestForm($_POST['pm_to'],1,1,0);
 $pm_th=TestForm($_POST['pm_th'],1,1,0);
 if(empty($pm_th)) $pm_th=$lang['notopic'];
 if(strlen($_POST['pm_txt'])>20000)
 {
  $_bl.=$lang['pms_18'].'<br /><br />';
  $pm_txt=&$_POST['pm_txt'];
 }
 else
 {
  $pm_txt=TestForm($_POST['pm_txt'],1,0,0);
 }
 #Odbiorca
 $xusr[0]='';
 db_read('ID','users','xusr','on',' WHERE login="'.db_esc($pm_to).'"');
 if($xusr[0]=='')
 {
  $_bl.=$lang['pms_20'].'<br /><br />';
 }
 #Limit
 if(db_count('ID','pms',' WHERE owner='.$xusr[0])>=$cfg['pm_limit'])
 {
  $_bl.=$lang['pms_21'].'<br /><br />';
 }
}
elseif($id!='')
{
 db_read('a.*,u.login','pms a LEFT JOIN {pre}users u ON a.usr=u.ID','pm','oa',' WHERE (a.owner='.UID.' OR (a.usr='.UID.' AND a.st=1)) AND a.ID='.$id);
 if(!is_numeric($pm['usr'])) { exit('ERR!'); }
}
#Limit
if($_POST['pm_s'] && $id=='')
{
 if($ilew>=$cfg['pm_limit']) { $_bl.=$lang['pms_22']; }
}
#Zapis
if($_POST['sav'] && $_bl=='')
{
 #Nowa
 if($id=='')
 {
  db_q('INSERT INTO {pre}pms VALUES ("","'.db_esc($pm_th).'",'.UID.',"'.$xusr[0].'",1,"'.strftime('%Y-%m-%d %H:%M:%S').'",'.(($_POST['pm_b'])?1:2).',"'.db_esc(Words($pm_txt)).'")');
  db_q('UPDATE {pre}users SET pms=pms+1 WHERE ID='.$xusr[0]);
  #Do wys³anych?
  if($_POST['pm_s'])
  {
   db_q('INSERT INTO {pre}pms VALUES ("","'.db_esc($pm_th).'","'.$xusr[0].'",'.UID.',4,"'.strftime('%Y-%m-%d %H:%M:%S').'",'.(($_POST['pm_b'])?1:2).',"'.db_esc(Words($pm_txt)).'")');
  }
  Info($lang['pms_23']);
 }
 #Edycja
 else
 {
  db_q('UPDATE {pre}pms SET topic="'.db_esc($pm_th).'", usr="'.$xusr[0].'", bbc='.(($_POST['pm_b'])?1:2).', txt="'.db_esc(Words($pm_txt)).'" WHERE ID='.$id);
  Info($lang['pms_24']);
 }
}
#Podgl±d
if($_POST['preview'] && $_bl=='')
{
 cTable($pm_th,1);
 echo '<tr><td class="txt">';
 #BBCode
 if($_POST['pm_b'] && $cfg['bbc']==1)
 {
  require_once('inc/bbcode.php');
  echo Emots(nl2br(ParseBBC(Words($pm_txt))));
 }
 else
 {
  echo Emots(nl2br(Words($pm_txt)));
 }
 echo '</td></tr>';
 eTable();
}
#B³êdy
if($_bl!='') Info($_bl);
#Formularz
if(!$_POST['sav'] || $_bl!='')
{
 echo '<form action="?co=pms&amp;act=e'.(($id=='' || $_GET['pm_r']==1)?'':'&amp;id='.$id).'" method="post">';
 cTable((($id=='' || $_GET['pm_r']==1)?$lang['write']:$lang['editpm']),2);
 echo '
 <tr>
  <td style="width: 25%"><b>1. '.$lang['pms_13'].':</b></td>
  <td><input name="pm_to" value="'.(($_POST)?$pm_to:(($id=='')?$_GET['a']:$pm['login'])).'" maxlength="30" /></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['topic'].':</b></td>
  <td><input name="pm_th" value="'.(($_POST)?$pm_th:$pm['topic']).'" style="width: 70%" maxlength="40" /></td>
 </tr>
 <tr>
  <td><b>3. Opcje:</b></td>
  <td><input type="checkbox" onclick="setCookie(\''.$cfg['c'].'pm_s\',1,(this.checked)?3000:-3000)" name="pm_s"'.((($_POST && !$_POST['pm_s']) || (!$_POST && $_COOKIE[$cfg['c'].'pm_s']!=1))?'':' checked="checked"').' /> '.$lang['pms_17'].'<br /><input type="checkbox" name="pm_b"'.(($cfg['bbc']!=1)?' disabled="disabled"':'').((($_POST['pm_b'] || $pm['bbc']==1) && $cfg['bbc']==1)?' checked="checked"':'').' /> '.$lang['pms_19'].'</td>
 </tr>
 <tr>
  <th colspan="2"><b>'.$lang['text'].'</b></th>
 </tr>
 <tr>
  <td colspan="2" style="padding: 3px" align="center">'; if($cfg['bbc']==1) { require_once('inc/btn.php'); echo '<div style="padding: 3px">'; Colors('pm_txt',2); FontBtn('pm_txt',2); echo '</div>'; } echo '<textarea rows="10" id="pm_txt" name="pm_txt" style="width: 95%">'.(($_POST)?$pm_txt:(($_GET['pm_r']==1)?'[quote]'.$pm['txt'].'[/quote]':$pm['txt'])).'</textarea>';
  if($cfg['bbc']==1) { echo '<div style="padding: 3px">'; Btns(2,2,'pm_txt'); echo '</div>'; }
  include_once('cfg/emots.php');
  $ile=count($emodata);
  if($ile>0)
  {
   echo '<div style="padding: 3px">';
   for($i=0;$i<$ile;$i++)
   {
    echo '<img src="img/emo/'.$emodata[$i][1].'" style="cursor: pointer" title="'.$emodata[$i][0].'" alt="'.$emodata[$i][0].'" onclick="BBC(\'pm_txt\',\''.$emodata[$i][2].'\',\'\')" /> ';
   }
   echo '</div>';
  }
  echo '</td>
 </tr>
 <tr>
  <td colspan="2" class="eth"><input type="submit" value="OK" name="sav" /> <input type="submit" value="'.$lang['preview'].'" name="preview" /></td>
 </tr>
 ';
 eTable();
 echo '</form>';
}
?>
