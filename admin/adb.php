<?php
if(iCMSa!='X159E' || !ChPrv('CDB')) exit;
require($catl.'adm_cfgdb.php');
#Tworzenie
if($_POST['xtb'] && $_GET['x']=='db')
{
 $n="\n";
 @set_time_limit(50);
 if($_POST['x_co']=='no') { ob_start(); } else { ob_start('ob_gzhandler'); }
 db_q('SET SQL_QUOTE_SHOW_CREATE=1');
 echo '#F3Site Database Backup ('.strftime('%Y-%m-%d').')'.$n;
 echo '#Database: '.$db_d.$n;
 echo '#----------'.$n.$n;
 foreach($_POST['xtb'] as $tab)
 {
  db_q('OPTIMIZE TABLE '.$tab);
  #Tworzenie tabeli
  if($_POST['x_ct'])
  {
   db_a( db_q('SHOW CREATE TABLE '.$tab), 'xcr','on');
   echo '#Creating table '.$tab.$n;
   #Usuwanie?
   if($_POST['x_ct1']) echo 'DROP TABLE IF EXISTS `'.$tab.'`;'.$n;
   echo $xcr[1].';'.$n.$n;
  }
  #Dane
  db_a(db_q('SELECT * FROM '.$tab),'data','tn');
  $ile=count($data);
  if($ile>0)
  {
   echo '#Table data for '.$tab.$n;
   $ile2=count($data[0]);
   for($i=0;$i<$ile;$i++)
   {
    echo 'INSERT INTO `'.$tab.'` VALUES (';
    #Warto¶ci pól
    for($y=0;$y<$ile2;$y++)
    {
     if(is_numeric($data[$i][$y])) { echo db_esc($data[$i][$y]).((($y+1)==$ile2)?'':','); } else { echo '"'.db_esc($data[$i][$y]).'"'.((($y+1)==$ile2)?'':','); }
    }
    echo '); '.$n;
   }
   echo $n;
  }
  unset($ile,$ile2,$xcr,$data);
 }
 $xchars=Array('?','*',':','\\','/','<','>','|','"');
 #Kompresja
 if($_POST['x_co']=='no')
 {
  header('Content-type: text/plain'); $ex='.sql';
 }
 else
 {
  header('Content-type: application/x-gzip'); $ex='.sql.gz';
 }
 header('Content-Disposition: attachment; filename='.str_replace($xchars,'',$_POST['x_fn']).$ex);
 ob_end_flush();
 exit;
}
#Opcje
else
{
 if($_GET['x']) exit('Error!');
 Info($lang['adb_i']);
 echo '<form action="?x=db" method="post">';
 cTable($lang['adb_t'],1);
 db_a(db_q('SHOW TABLES'),'xtbs','tn');
 $ile=count($xtbs);
 echo '<tr><td>
 <table style="width: 100%"><tbody valign="top"><tr>
 <td style="width: 60%">
  <fieldset style="height: 200px">
   <legend>'.$lang['opt'].'</legend>
   <input name="x_ct" type="checkbox" checked="checked"> '.$lang['adb_c1'].'<br />
   <input name="x_ct1" type="checkbox" style="margin-left: 20px; margin-top: 7px"> '.$lang['adb_c3'].'<br /><br />
   '.$lang['adb_co'].'<br />
   <select name="x_co"><option value="no">'.$lang['adb_nco'].'</option>'.((function_exists('gzopen'))?'<option>.gz</option>':'').'</select><br /><br />
   '.$lang['adb_fn'].':<br />
   <input name="x_fn" value="DB_'.$db_d.'_'.strftime('%Y-%m-%d').'" style="width: 205px" maxlength="30" />
  </fieldset>
 </td>
 <td style="width: 40%" align="center">
  <fieldset style="height: 200px">
   <legend>'.$lang['adb_c2'].'</legend>
   <select name="xtb[]" multiple="multiple" style="width: 80%; height: 150px">';
  for($i=0;$i<$ile;$i++)
  {
   echo '<option'.(($i==0)?' selected="selected"':'').'>'.$xtbs[$i][0].'</option>';
  }
  echo '</select><br /><br />
  <a href="javascript:z=document.forms[0].elements[\'xtb[]\']; z1=z.length; for(i=0;i<z1;i++) { z.options[i].selected=true } void(0)">'.$lang['adb_z'].'</a>
  </fieldset>
 </td></tr></tbody></table>
 </td></tr>
 <tr><td class="eth"><input type="submit" value="OK" /></td></tr>';
 eTable(); echo '</form>';
}
?>
