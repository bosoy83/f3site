<?php
if(iCMS!='E123') exit;
require('cfg/c.php');
require($catl.'search.php');
if($cfg['cfind']==1)
{
#Wyniki
if($_SESSION['find'] && !$_GET['new'])
{
 define('SCO','?co=s');
 $x=join(' OR ID=',$_SESSION['find']);
 $dinfo=Array('name'=>$lang['found'].$_SESSION['ifind'],'num'=>$_SESSION['ifind']);
 #Strona
 if($_GET['page'] && $_GET['page']!=1)
 {
  $page=$_GET['page'];
  $st=($page-1)*(($dinfo['type']==3)?$cfg['inp']:$cfg['np']);
 }
 else
 {
  $page=1;
  $st=0;
 }
 define('SEARCH','ID='.$x);
 switch($_SESSION['findw'])
 {
  case 2: require('inc/dlist2.php'); break;
  case 3: require('inc/dlist3.php'); break;
  case 4: $_GET['id']=1; db_read('ID,name,date','news','news','tn',' WHERE ID='.$x.' ORDER BY ID DESC LIMIT 30'); require('archive.php'); break;
  default: require('inc/dlist1.php'); break;
 }
}
#Form
elseif(!$_POST || $_GET['new'])
{
 echo '<form action="?mode=s" method="post">';
 cTable($lang['search'],2);
 echo '<tr>
  <td><b>'.$lang['s_1'].':</b></td>
  <td><input name="s_n" style="margin: 3px 3px" maxlength="40" /><br /><input type="checkbox" name="s_ot" checked="checked"'.(($cfg['ftfind']==1)?'':' disabled="disabled"').' /> '.$lang['s_ft'].'</td>
 </tr>
 <tr>
  <td><b>'.$lang['s_2'].':</b></td>
  <td><input type="radio" name="s_nt" value="1" checked="checked" /> '.$lang['s_21'].'<br /><input type="radio" name="s_nt" value="2" /> '.$lang['s_22'].'</td>
 </tr>
 <tr>
  <td><b>'.$lang['s_3'].'</b></td>
  <td><input type="radio" name="s_t" value="1" checked="checked" /> '.$lang['arts'].'<br /><input type="radio" name="s_t" value="2" /> '.$lang['files'].'<br /><input type="radio" name="s_t" value="3" /> '.$lang['imgs'].'<br /><input type="radio" name="s_t" value="4" /> '.$lang['news'].'</td>
 </tr>
 <tr>
  <td class="eth" colspan="2"><input type="submit" value="OK" /></td>
 </tr>';
 eTable();
 echo '</form>';
}
}
else
{
 Info($lang['sdis']);
}
