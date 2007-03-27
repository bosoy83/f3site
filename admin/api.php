<?php
if(iCMSa!='X159E' || !ChPrv('PI')) exit;
require($catl.'adm_pi.php');
if($_GET['idp']) $idp=$_GET['idp'];
if($_POST['idp']) $idp=$_POST['idp'];
if($idp)
{
 $idp=str_replace('/','',$idp);
 $idp=str_replace('.','',$idp);
 #Nowa wersja?
 if(file_exists('plugins/'.$idp.'/setup.php'))
 {
  $nv=1;
 }
 else
 {
  $nv=0;
 }
 if($_GET['inst'])
 {
  if($nv==1)
	{
	 require('plugins/'.$idp.'/setup.php');
	 Install();
	}
	else
	{
	 require('plugins/'.$idp.'/install.php');
	}
  Info($lang['api_6']);
 }
 elseif($_GET['uninst'])
 {
  if($nv==1)
	{
	 require('plugins/'.$idp.'/setup.php');
	 Uninstall();
	}
	else
	{
   require('plugins/'.$idp.'/uninstall.php');
	}
  Info($lang['api_7']);
 }
 else
 {
  cTable($lang['api_1'],1);
  echo '
  <tr>
   <td align="center">'.$lang['api_3'].'<br /><br /><textarea style="width: 90%" rows="10">'.htmlspecialchars(file_get_contents('plugins/'.$idp.(($nv==1)?'/setup.php':(($_GET['del'])?'/uninstall.php':'/install.php')))).'</textarea><br /><br /></td>
  </tr>
  <tr class="eth">
   <td><input type="button" value="'.$lang['yes'].'" onclick="location=\'?a=pi&amp;'.(($_GET['del'])?'uninst=1':'inst=1').'&amp;idp='.$idp.'\'" /></td></tr>';
  eTable();
 }
}
else
{
 db_read('*','plugins','plug','ta','');
 Info($lang['api_5']);
 #Lista
 cTable($lang['api_4'],3);
 echo '<tr><th>'.$lang['name'].'</th><th>Category (ID)</th><th>'.$lang['opt'].'</th></tr>';
 $ile=count($plug);
 for($i=0;$i<$ile;$i++)
 {
  echo '
  <tr align="center">
   <td align="left"><span style="color: '.((file_exists('plugins/'.$plug[$i]['ID']))?'green':'#CA2204').'">'.$plug[$i]['name'].'</span></td>
   <td>'.$plug[$i]['ID'].'</td>
   <td><a href="?a=pi&amp;del=1&amp;idp='.$plug[$i]['ID'].'">'.$lang['uninst'].'</a></td>
  </tr>';
  $usedp[]=$plug[$i]['ID'];
 }
 $usedp[]='..';
 eTable();
 #Inst.
 cTable($lang['inst'],1);
 echo '
 <tr>
  <td align="center" style="padding: 5px">
   <form action="?a=pi" method="post">
    ID: <select name="idp">';
  if($copen=opendir('plugins'))
  {
   while(false !== ($dirq=readdir($copen)))
   {
    if(is_dir('plugins'.'/'.$dirq) && $dirq!='.' && $dirq!='..' && !in_array($dirq,$usedp))
    {
     echo '<option>'.$dirq.'</option>';
    }
   }
   closedir($copen);
  }
  unset($copen,$dirq);
    echo '
    </select>
    <input type="submit" value="'.$lang['inst'].'" />
   </form>
  </td>
 </tr>
 ';
 eTable();
}
?>
