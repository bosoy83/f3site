<?php
if(iCMS!='E123') exit;
$id=($_GET['id'])?$_GET['id']:0;
$bug=array();

#Prawa
if(ChPrv('BUGS'))
{
 $yes=1;
 echo '
 <div class="hint menulist txtm" id="chst">
	<div class="title">'.$lang['status'].':</div>
	<ul>
	 <li onclick="ZSt(1)">'.$lang['bugs_1'].'</li>
	 <li onclick="ZSt(2)">'.$lang['bugs_2'].'</li>
	 <li onclick="ZSt(3)">'.$lang['bugs_3'].'</li>
	 <li onclick="ZSt(4)">'.$lang['bugs_4'].'</li>
	 <li onclick="ZSt(5)">'.$lang['bugs_5'].'</li>
	</ul>
 </div>';
}
else
{
 $yes=0;
}

#SQL
db_read('b.*,c.name as cn,c.trate','bugs b INNER JOIN {pre}bugcats c ON b.cat=c.ID','bug','oa',' WHERE b.ID='.$id.' AND (c.see=1 || c.see="'.$nlang.'")');

if($bug['ID'])
{
 #Niemoderowany?
 if($bug['status']==5)
 {
  if($bug['author']!=UID && $yes==0) exit;
 }
 #Hierarchia
 echo '
 <div class="cs">
  <a href="?co=bugs">'.$lang['bugs_mp'].'</a>
	&raquo;
	<a href="?co=bugs&amp;act=l&amp;id='.$bug['cat'].'">'.$bug['cn'].'</a>
 </div>';
 
 #Dane
 require_once('inc/bbcode.php');
 cTable($bug['name'],2);
 echo '
 <tr>
  <td class="pth" align="right" style="width: 25%">'.$lang['added'].':</td>
	<td>'.genDate($bug['date']).'</td>
 </tr>
 <tr>
  <td class="pth" align="right">'.$lang['wrote'].':</td>
	<td>'.Autor($bug['poster']).'</td>
 </tr>
 <tr>
	<td class="pth" align="right">'.$lang['status'].':</td>
	<td><span id="st">'.$lang['bugs_'.$bug['status']].(($yes==1)?'</span> <img src="plugins/bugs/d.png" alt="+" onclick="Hint(\'chst\',cx-10,cy+7,1)" style="cursor: pointer" />':'').'</td>
 </tr>
 <tr>
  <td class="pth" align="right">'.$lang['level'].':</td>
	<td>'.$lang['bugs_s'.$bug['level']].'</td>
 </tr>';
 
 #Ocena
 if($bug['trate']!=0)
 {
  echo '
 <tr>
	<td class="pth" align="right">'.$lang['rate'].':</td>
	<td id="brate">';
	#Za/przeciw
	if($bug['trate']==1)
	{
	 echo '
	 <span onclick="RateBug(1)"><img src="plugins/bugs/thup.png" alt="UP" onclick="RateBug(1)" /> '.$bug['pos'].'</span> <span onclick="RateBug(2)"><img src="plugins/bugs/thd.png" alt="DOWN" /> '.$bug['neg'].'</span>';
	}
	#5ocen
	else
	{
	 echo
	 (($bug['pos']==0)?$lang['lack']:Rating($bug['pos'].'|'.$bug['neg'],1)).
	 ' <img src="plugins/bugs/d.png" alt="+" onclick="RateBug(0)" style="cursor: pointer" />';
	}
	echo '</td>
 </tr>';
 }
 
 echo '
 <tr>
  <td class="pth" align="right">'.$lang['bugs_p'].':</td>
	<td>'.$bug['env'].'</td>
 </tr>
 <tr>
  <td colspan="2" class="txt">'.Emots(nl2br(ParseBBC($bug['text']))).'</td>
 </tr>'.
 
 #Edytuj
 (($yes==1 || ($bug['poster']==UID && $cfg['bugs_ae']==1))?
 '<tr>
	<td class="eth" colspan="2">
		<input type="button" value="'.$lang['edit'].'" onclick="location=\'?co=bugs&amp;act=e&amp;id='.$id.'\'" />
		'.(($yes==1)?'<input type="button" value="'.$lang['del'].'" onclick="DelBug()" id="delbtn" />':'').'
	</td>
</tr>':'');
 
 eTable();
}
else
{
 Info($lang['noex']);
}
?>