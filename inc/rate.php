<?php if(iCMS!=1) exit;
if(isset($_COOKIE[$cfg['c'].'vt'.$_GET['co'].$_GET['id']]))
{
 define('SPECIAL',6);
 require('special.php');
 exit;
}
if($_GET['co'] && $_GET['id'])
{
 $id=$_GET['id'];
 #Test i odczyt
 switch($_GET['co'])
 {
  case 'art': $xt='arts'; break;
  case 'file': $xt='files'; break;
  case 'img': $xt='imgs'; break;
  default: exit('ERR!');
 }
 db_read('ID,name,rates',$xt,'xrt','on',' WHERE ID='.$id);
 if(empty($xrt[0])) exit('WRONG ID!');
 #Zapis
 if($_POST['vt'])
 {
  $vt=$_POST['vt'];
  if($vt!=1 && $vt!=2 && $vt!=3 && $vt!=4 && $vt!=5) exit('ERR!');
  if($xrt[2]==null || empty($xrt[2]) || $xrt[2]=='0|0')
  {
   $ao[0]='0';
   $ao[1]='0';
  }
  else
  {
   $ao=explode('|',$xrt[2]);
  }
  $newrt=($ao[0]+$_POST['vt']).'|'.($ao[1]+1);
  setcookie($cfg['c'].'vt'.$_GET['co'].$id,'NO',time()+2592000);
  db_q('UPDATE '.$db_pre.$xt.' SET rates="'.db_esc($newrt).'" WHERE ID='.$id);
  define('SPECIAL',5);
  require('special.php');
  exit;
 }
 #Wybór
 else
 {
  require($catl.'special.php');
  require('special.php');
  echo sHTML.'<form action="?mode=o&amp;co='.$_GET['co'].'&amp;id='.$id.'" method="post">';
  cTable($xrt[1],1);
  echo '<tr><td>
   <input type="radio" name="vt" value="5" checked="checked" /> 5 ('.$lang['vgood'].')<br />
   <input type="radio" name="vt" value="4" /> 4 ('.$lang['good'].')<br />
   <input type="radio" name="vt" value="3" /> 3 ('.$lang['dstg'].')<br />
   <input type="radio" name="vt" value="2" /> 2 ('.$lang['weak'].')<br />
   <input type="radio" name="vt" value="1" /> 1 ('.$lang['fatal'].')<br />
  </td></tr>
  <tr class="eth">
   <td><input value="OK" type="submit" /></td>
  </tr>';
  eTable(); echo '</form>'.eHTML;
 }
}
exit;
?>
