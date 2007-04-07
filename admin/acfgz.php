<?php
if(iCMSa!='X159E' || !ChPrv('CFG')) exit;
if($_POST)
{
 define('WHS','cfg/c.php');
 define('CFGA','cfg+');
 require('admin/zc.php');
 Info($lang['saved']);
}
else
{
 include($catl.'adm_cfgz.php');
 require('cfg/c.php');
?>
<form action="?a=cfgz" method="post">
<?php
cTable($lang['ap_cfgz'],2);
echo '
<tr>
 <th colspan="2">'.$lang['gen'].'</th>
</tr>
<tr>
 <td style="width: 45%"><b>1. '.$lang['ap_lcnt'].'?</b></td>
 <td><input type="checkbox" name="u_lcnt"'.(($cfg['lcnt']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>2. '.$lang['ap_hmop'].'?</b><div class="txtm">'.$lang['ap_hmopd'].'</div></td>
 <td><input name="u_np" value="'.$cfg['np'].'" style="width: 30px" /></td>
</tr>
<tr>
 <td><b>3. '.$lang['ap_cstr'].'?</b></td>
 <td><input name="u_cstr" type="checkbox"'.(($cfg['cstr']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>4. '.$lang['ap_cfind'].'?</b></td>
 <td><input name="u_cfind" type="checkbox"'.(($cfg['cfind']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>5. '.$lang['ap_tfind'].':</b></td>
 <td><input name="u_ftfind" type="checkbox"'.(($cfg['ftfind']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>6. '.$lang['ap_ffind'].':</b></td>
 <td><input name="u_afind" value="'.(($cfg['afind']=='')?30:$cfg['afind']).'" style="width: 30px" /> sec.</td>
</tr>
<tr>
 <th colspan="2">'.$lang['arts'].'</th>
</tr>
<tr>
 <td><b>7. '.$lang['ap_crates'].'?</b></td>
 <td><input type="checkbox" name="u_arate"'.(($cfg['arate']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>8. '.$lang['ap_artd'].'?</b></td>
 <td><input type="checkbox" name="u_adisp"'.(($cfg['adisp']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>9. '.$lang['ap_common'].'?</b></td>
 <td><input type="checkbox" name="u_acomm"'.(($cfg['acomm']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <th colspan="2">'.$lang['files'].'</th>
</tr>
<tr>
 <td><b>10. '.$lang['ap_crates'].'?</b></td>
 <td><input type="checkbox" name="u_frate"'.(($cfg['frate']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>11. '.$lang['ap_filesd'].'?</b></td>
 <td><input type="checkbox" name="u_fcdl"'.(($cfg['fcdl']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>12. '.$lang['ap_common'].'?</b></td>
 <td><input type="checkbox" name="u_fcomm"'.(($cfg['fcomm']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>13. '.$lang['ap_oinw'].'?</b><div class="txtm">'.$lang['ap_oinwf'].'</div></td>
 <td><input type="checkbox" name="u_file_nw"'.(($cfg['file_nw']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <th colspan="2">'.$lang['news'].'</th>
</tr>
<tr>
 <td><b>14. '.$lang['ap_newsact'].':</b></td>
 <td><input name="u_numofn" value="'.$cfg['numofn'].'" style="width: 30px" /></td>
</tr>
<tr>
 <td><b>15. '.$lang['ap_common'].'?</b></td>
 <td><input type="checkbox" name="u_ncomm"'.(($cfg['ncomm']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>16. '.$lang['ap_oinw'].'?</b><div class="txtm">'.$lang['ap_oinwn'].'</div></td>
 <td><input type="checkbox" name="u_news_nw"'.(($cfg['news_nw']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <th colspan="2">'.$lang['imgs'].'</th>
</tr>
<tr>
 <td><b>17. '.$lang['ap_hmir'].':</b></td>
 <td><input name="u_imgsrow" value="'.$cfg['imgsrow'].'" style="width: 30px" /></td>
</tr>
<tr>
 <td><b>18. '.$lang['ap_common'].'?</b></td>
 <td><input type="checkbox" name="u_icomm"'.(($cfg['icomm']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>19. '.$lang['ap_oinw'].'?</b><div class="txtm">'.$lang['ap_oinwi'].'</div></td>
 <td><input type="checkbox" name="u_img_nw"'.(($cfg['img_nw']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>20. '.$lang['ap_min'].':</b></td>
 <td><input name="u_inp" value="'.$cfg['inp'].'" style="width: 30px" /></td>
</tr>
<tr>
 <td><b>21. '.$lang['ap_crates'].'?</b></td>
 <td><input type="checkbox" name="u_irate"'.(($cfg['irate']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <th colspan="2">'.$lang['dfcat'].'</th>
</tr>';
#Jêzyki
if($dir=opendir('lang'))
{
 $i=22;
 while(false!==($f=readdir($dir)))
 {
  if(is_dir('lang/'.$f) && $f!='.' && $f!='..')
	{
	 echo '<tr>
 <td><b>'.$i++.'. '.$lang['forlng'].strtoupper($f).':</b></td>
 <td><input name="u_dfc['.$f.']" value="'.((is_numeric($cfg['dfc'][$f]))?$cfg['dfc'][$f]:1).'" style="width: 30px" /> <select name="u_dfct['.$f.']"><option value="1">'.$lang['cat'].'</option><option value="2"'.(($cfg['dfct'][$f]==2)?' selected="selected"':'').'>'.$lang['infp'].'</option></select></td>
</tr>';
	}
 }
}

echo '<tr>
 <td class="eth" colspan="2"><input type="submit" value="'.$lang['save'].'" /> <input type="reset" value="'.$lang['reset'].'" /></td>
</tr>';
eTable();
?>
</form>
<?php } ?>
