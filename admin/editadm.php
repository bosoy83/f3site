<?php
if(iCMSa!='X159E' || !ChPrv('AD') || $_GET['id']==UID || !$_GET['id']) exit('B£¡D!');
require($catl.'rights.php');
require($catl.'profile.php');
$id=$_GET['id'];
if($_POST['sav'])
{
 $newpr='';
 foreach($_POST as $key=>$val)
 {
  if($key!='sav' && $key!='xu_lv') {
   $key=str_replace('x_','',$key);
   $newpr.=$key.'|';
  }
 }
 db_q('UPDATE {pre}users SET adm="'.db_esc($newpr).'", lv="'.db_esc($_POST['xu_lv']).'" WHERE ID='.$id);
 Info($lang['saved']);
}
else
{
 unset($ausr);
 #Admin
 db_read('login,lv,adm','users','ausr','on',' WHERE ID='.$id);
 if(empty($ausr[0])) exit('Wrong ID!');
 $xprvs=explode('|',$ausr[2]);
 function DPrvs($co)
 {
  global $xprvs;
  if(in_array($co,$xprvs)) return ' checked="checked"';
 }
 echo '<form action="?a=editadm&amp;id='.$id.'" method="post">';
 cTable($lang['editadm'].': '.$ausr[0],2);
 $xit='<input type="checkbox" name="x_';
 echo '
<tr valign="top">
 <td style="padding: 4px; width: 50%">
  <fieldset>
   <legend>'.$lang['cpanel'].'</legend>
   '.$xit.'C"'.DPrvs('C').' /> '.$lang['cats'].'<br />
   '.$xit.'A"'.DPrvs('A').' /> '.$lang['arts'].'<br />
   '.$xit.'F"'.DPrvs('F').' /> '.$lang['files'].'<br />
   '.$xit.'L"'.DPrvs('L').' /> '.$lang['links'].'<br />
   '.$xit.'N"'.DPrvs('N').' /> '.$lang['news'].'<br />
   '.$xit.'G"'.DPrvs('G').' /> '.$lang['imgs'].'<br />
   '.$xit.'IP"'.DPrvs('IP').' /> '.$lang['ipages'].'<br />
   '.$xit.'f3s"'.DPrvs('f3s').' /> '.$lang['poll'].'<br /><br />
   '.$xit.'U"'.DPrvs('U').' /> '.$lang['users'].'<br />
   '.$xit.'AD"'.DPrvs('AD').' /> '.$lang['admins'].'<br />
	 '.$xit.'LOG"'.DPrvs('LOG').' /> '.$lang['evlog'].'<br />
   '.$xit.'MM"'.DPrvs('MM').' /> '.$lang['massl'].'<br /><br />
   '.$xit.'CFG"'.DPrvs('CFG').' /> '.$lang['opt'].'<br />
   '.$xit.'CDB"'.DPrvs('CDB').' /> '.$lang['ldb'].'<br />
   '.$xit.'NM"'.DPrvs('NM').' /> '.$lang['ap_navs'].'<br />
   '.$xit.'B"'.DPrvs('B').' /> '.$lang['banners'].'<br />
   '.$xit.'PI"'.DPrvs('PI').' /> '.$lang['plugs'].'<br />
  </fieldset>
 </td>
 <td style="padding: 4px">
  '.$xit.'DEL"'.DPrvs('DEL').' /> '.$lang['aldel'].'<br /><span class="txtm">'.$lang['aldeld'].'</span><br /><br style="font-size: 5px" />
  '.$xit.'CM"'.DPrvs('CM').' /> '.$lang['urdc'].' (HTML)<br />'.$xit.'FM"'.DPrvs('FM').' /> '.$lang['fmv'].'<br />'.$xit.'FM2"'.DPrvs('FM2').' /> '.$lang['fmv2'].'<br /><br />
  <fieldset>
   <legend>'.$lang['plugs'].'</legend>';
   $ile=count($admenu);
   for($i=0;$i<$ile;$i++)
   {
    if($admenu[$i][3]!=3) echo $xit.$admenu[$i][0].'"'.DPrvs($admenu[$i][0]).' /> '.$admenu[$i][1].'<br />';
   }
   echo '
  </fieldset><br />
  <fieldset>
  <legend>'.$lang['rights'].'</legend>
  <select name="xu_lv"><option value="1">'.$lang['user'].'</option><option value="2"'.(($ausr[1]==2)?' selected="selected"':'').'>'.$lang['admin'].'</option><option value="4"'.(($ausr[1]==4)?' selected="selected"':'').'>'.$lang['editor'].'</option><option value="5"'.(($ausr[1]==5)?' selected="selected"':'').'>'.$lang['locked'].'</option></select><br /><br style="font-size: 5px" />'.$lang['ureqto'].'
 </fieldset>
 </td>
</tr>
<tr>
 <td colspan="2" class="eth"><input type="submit" name="sav" value="'.$lang['save'].'" /></td>
</tr>
'; eTable(); }
?>
