<?php
if(iCMS!='E123' || $_REQUEST['comm']) exit;
if(!defined('CT')) exit('Lack of CT constant!');
#Strona
if($cfg['cnp']!=0)
{ 
 if($_GET['page']>1)
 {
  $page=$_GET['page'];
  $st=($page-1)*$cfg['cnp'];
 }
 else
 {
  $page=1;
  $st=0;
 }
 $ilec=db_count('ID','comms',' WHERE th="'.CT.'_'.$id.'"');
}
#SQL
db_read('c.*,u.login','comms c LEFT JOIN {pre}users u ON c.author=u.ID AND c.at=1','comm','ta',' WHERE c.th="'.CT.'_'.$id.'"'.(($cfg['csort']==2)?'':' ORDER BY ID DESC').(($cfg['cnp']!=0)?' LIMIT '.$st.','.$cfg['cnp']:''));
if(!empty($comm[0]['ID']))
{
 $ile=count($comm);
 cTable($lang['comms'],1);
 $y=1;
 #Komentarze
 for($i=0;$i<$ile;$i++)
 {
  $xcomm=$comm[$i];
  echo '
  <tr>
  <td class="post'.$y.'">
  <b>',$xcomm['name'].'</b>
  <div class="txtm">'.genDate($xcomm['date']).' &middot; '.(($xcomm['at']==1)?'<a href="?co=user&amp;id='.$xcomm['author'].'">'.$xcomm['login'].'</a>':$xcomm['author']).((ChPrv('CM'))?' (<a href="javascript:Okno(\'?mode=dc&amp;id='.$xcomm['ID'].'\',100,100,200,200)">'.$lang['del'].'</a> | <a href="?co=comm&amp;id='.$xcomm['ID'].'&amp;turl='.$_GET['co'].'">'.$lang['edit'].'</a> | '.$xcomm['ip'].')':'').'</div>
  '.Emots(nl2br($xcomm['text'])).'
  </td>
 </tr>';
  if($y==2) { $y=1; } else { $y++; }
 }
 if($cfg['cnp']!=0)
 {
  if($ilec>$ile) echo '<tr><td align="center">'.Pages($page,$ilec,$cfg['cnp'],'?co='.$_GET['co'].'&amp;id='.$id.(($_GET['om']==1)?'&amp;om=1':''),2).'</td></tr>';
 }
 unset($y);
 eTable();
}

#Form
if(LOGD==1 || $cfg['gcomm']==1)
{
if($_SESSION['postc']<time() || !isset($_SESSION['postc']))
{
 echo '<form action="?co=comm&amp;id='.$id.'&amp;turl='.$_GET['co'].'&amp;type='.CT.(($_GET['om'])?'&amp;om=1':'').'" method="post">';
 cTable($lang['addcomm'],2);
 echo ((LOGD==2)?(($cfg['imgsec']==1)?'
 <tr>
  <td><b>'.$lang['code'].':</b><div class="txtm">'.$lang['imgcode'].'.</div></td>
  <td><img src="code.php" alt="IMG" style="margin-bottom: 5px; border: 1px solid gray" /><br /><input name="c_code" /></td>
 </tr>':'').'
 <tr>
  <td><b>'.$lang['author'].':</b></td>
  <td><input name="c_a" maxlength="20" /></td>
 </tr>
 ':'').'
 <tr>
  <td style="width: 110px"><b>'.$lang['title'].':</b></td>
  <td><input name="c_n" maxlength="40" /></td>
 </tr>
 <tr>
  <td><b>'.$lang['text'].':</b>'.(($cfg['bbc']==1)?'<br /><br /><a href="javascript:Okno(\'?mode=doc&amp;id=1\',500,300,180,170)">BBCode &raquo;</a>':'').'</td>
  <td><textarea name="c_t" id="c_t" rows="4" cols="38"></textarea>';
  #Emoty
  include_once('cfg/emots.php');
  $ile=count($emodata);
  if($ile>0)
  {
   echo '<div style="padding: 3px">';
   for($i=0;$i<$ile&&$i<10;$i++)
   {
    echo '<img src="img/emo/'.$emodata[$i][1].'" style="cursor: pointer" title="'.$emodata[$i][0].'" alt="'.$emodata[$i][2].'" onclick="BBC(\'c_t\',\''.$emodata[$i][2].'\',\'\')" /> ';
   }
   echo '</div>';
  }
  echo '</td>
 </tr>
 <tr class="eth">
  <td colspan="2"><input type="submit" value="'.$lang['preview'].'" name="prev" /> <input type="submit" name="sav" value="'.$lang['save'].'" /> <input type="submit" value="'.$lang['more'].'" name="xcm" /></td>
 </tr>
 ';
 eTable();
 echo '</form>';
}
}
else
{
 Info($lang['nounrc']);
}
?>
