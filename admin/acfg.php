<?php
if(iCMSa!='X159E' || !ChPrv('CFG')) exit;
if($_POST)
{
 if(!ereg('[[:alnum:]_]',$_POST['u_c'])) $_POST['u_c']=$cfg['c'];
 define('WHS','cfg/main.php');
 define('CFGA','cfg');
 require('admin/zc.php');
 Info($lang['saved']);
}
else
{
 include($catl.'adm_cfg.php');
?>
<form action="?a=cfg" method="post">
<?php
cTable($lang['ap_cfgo'],2);
echo '<tr>
 <th colspan="2">'.$lang['ap_docmeta'].'</th>
</tr>
<tr>
 <td style="width: 45%"><b>1. '.$lang['ap_doctit'].':</b></td>
 <td><input name="u_doc_title" value="'.$cfg['doc_title'].'" /></td>
</tr>
<tr>
 <td><b>2. '.$lang['ap_footer'].':</b></td>
 <td><textarea name="u_footer" cols="37" rows="4">'.htmlspecialchars($cfg['footer']).'</textarea></td>
</tr>
<tr>
 <td><b>3. '.$lang['ap_metad'].':</b><div class="txtm">'.$lang['ap_ind'].'</div></td>
 <td><textarea name="u_meta_d" cols="37" rows="3">'.$cfg['meta_d'].'</textarea></td>
</tr>
<tr>
 <td><b>4. '.$lang['ap_metak'].':</b><div class="txtm">'.$lang['ap_ind'].'</div></td>
 <td><textarea name="u_meta_k" cols="37">'.$cfg['meta_k'].'</textarea></td>
</tr>
<tr>
 <td><b>5. '.$lang['gmenu'].' (HTML):</b><div class="txtm">'.$lang['gmenusz'].'</div></td>
 <td><textarea name="u_gmenu" rows="6" style="width: 90%">'.htmlspecialchars($cfg['gmenu']).'</textarea></td>
</tr>
<tr>
 <td><b>6. '.$lang['ap_dkh'].' &lt;head&gt;:</b><div class="txtm">'.$lang['ap_dkhd'].'</div></td>
 <td><textarea name="u_dkh" rows="6" style="width: 90%">'.htmlspecialchars($cfg['dkh']).'</textarea></td>
</tr>
<tr>
 <td><b>7. '.$lang['ap_robots'].':</b></td>
 <td><select name="u_robots"><option value="all">'.$lang['ap_iall'].' (all)</option><option value="index,nofollow"'.(($cfg['robots']=='index,nofollow')?' selected="selected"':'').'>'.$lang['ap_iindex'].' (index, nofollow)</option><option value="noindex,follow"'.(($cfg['robots']=='noindex,follow')?' selected="selected"':'').'>'.$lang['ap_iflw'].' (noindex, follow)</option><option value="none"'.(($cfg['robots']=='none')?' selected="selected"':'').'>'.$lang['ap_inone'].' (none)</option></select></td>
</tr>
<tr>
 <td><b>8. '.$lang['ap_ncoo'].':</b></td>
 <td><input name="u_c" value="'.$cfg['c'].'" /></td>
</tr>
<tr>
 <td><b>9. '.$lang['ap_styl'].':</b></td>
 <td><select name="u_cms_styl">'.sListBox('style',1,$cfg['cms_styl']).'</select></td>
</tr>

<tr>
 <td><b>10. '.$lang['ap_num'].'?</b></td>
 <td><input type="checkbox" name="u_num"'.(($cfg['num']==1)?' checked="checked"':'').' />
</tr>
<tr>
 <td><b>11. '.$lang['ap_mc'].':</b><div class="txtm">'.$lang['ap_mcd'].'</div></td>
 <td><input type="checkbox" name="u_mc"'.(($cfg['mc']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <th colspan="2">'.$lang['ap_date'].'</th>
</tr>
<tr>
 <td><b>12. '.$lang['ap_dateaf'].':</b><div class="txtm">'.$lang['ap_datefd'].'</div></td>
 <td><input type="text" name="u_fdate" value="'.$cfg['fdate'].'" /></td>
</tr>
<tr>
 <td><b>13. '.$lang['ap_date1f'].':</b><div class="txtm">'.$lang['ap_date1d'].'</div></td>
 <td><input type="text" name="u_fdate1" value="'.$cfg['fdate1'].'" /></td>
</tr>
<tr>
 <td><b>14. '.$lang['ap_date2f'].':</b><div class="txtm">'.$lang['ap_date2d'].'</div></td>
 <td><input type="text" name="u_fdate2" value="'.$cfg['fdate2'].'" /></td>
</tr>
<tr>
 <td><b>15. '.$lang['ap_lastv'].'?</b></td>
 <td><input type="checkbox" name="u_lastv"'.(($cfg['lastv']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <th colspan="2">'.$lang['users'].'</th>
</tr>
<tr>
 <td><b>16. '.$lang['reg_on'].'?</b></td>
 <td><input type="checkbox" name="u_reg_on"'.(($cfg['reg_on']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>17. '.$lang['ap_pms'].'?</b></td>
 <td><input type="checkbox" name="u_pms_on"'.(($cfg['pms_on']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>18. '.$lang['ap_pml'].':</b></td>
 <td><input name="u_pm_limit" value="'.$cfg['pm_limit'].'" style="width: 50px" /></td>
</tr>
<tr>
 <td><b>19. '.$lang['ap_ufind'].':</b></td>
 <td><input type="checkbox" name="u_ufind"'.(($cfg['ufind']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>20. '.$lang['ap_pswp'].':</b><div class="txtm">'.$lang['ap_mailr'].'</div></td>
 <td><input type="checkbox" name="u_pswp"'.(($cfg['pswp']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>21. '.$lang['ap_alng'].'?</b><div class="txtm">'.$lang['ap_alngd'].'</div></td>
 <td><input type="checkbox" name="u_lng"'.(($cfg['lng']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>21. '.$lang['banip'].':</b><div class="txtm">'.$lang['banipd'].'</div></td>
 <td><textarea rows="4" cols="30" name="u_ban">'.$cfg['ban'].'</textarea></td>
</tr>
<tr>
 <th colspan="2">'.$lang['comms'].'</th>
</tr>
<tr>
 <td><b>22. '.$lang['ap_cnp'].':</b><div class="txtm">'.$lang['ap_c0o'].'</div></td>
 <td><input name="u_cnp" value="'.$cfg['cnp'].'" style="width: 30px" /></td>
</tr>
<tr>
 <td><b>23. '.$lang['guestcomm'].'?</b><div class="txtm">'.$lang['gcommd'].'</div></td>
 <td><input type="checkbox" name="u_gcomm"'.(($cfg['gcomm']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>24. '.$lang['ap_isec'].':</b><div class="txtm">'.$lang['ap_isecd'].'</div></td>
 <td><input type="checkbox" name="u_imgsec"'.(($cfg['imgsec']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>25. '.$lang['ap_bbcon'].'?</b></td>
 <td><input type="checkbox" name="u_bbc"'.(($cfg['bbc']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>26. '.$lang['ap_wch'].'?</b></td>
 <td><input type="checkbox" name="u_wordc"'.(($cfg['wordc']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>27. '.$lang['ap_comml'].':</b><div class="txtm">'.$lang['0off'].'</div></td>
 <td><input name="u_coml" value="'.$cfg['coml'].'" style="width: 50px" /> sec.</td>
</tr>
<tr>
 <td><b>28. '.$lang['csort'].':</b></td>
 <td><input type="radio" name="u_csort" value="1"'.(($cfg['csort']!=2)?' checked="checked"':'').' /> '.$lang['csort1'].' &nbsp;<input type="radio" name="u_csort" value="2"'.(($cfg['csort']==2)?' checked="checked"':'').' /> '.$lang['csort2'].'</td>
</tr>
<tr>
 <th colspan="2">'.$lang['poll'].'</th>
</tr>
<tr>
 <td><b>29. '.$lang['ap_common'].'?</b></td>
 <td><input type="checkbox" name="u_pcomm"'.(($cfg['pcomm']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
 <td><b>30. '.$lang['pcount'].':</b><div class="txtm">'.$lang['pcountd'].'</div></td>
 <td><input name="u_cproc" value="'.$cfg['cproc'].'" style="width: 30px" /></td>
</tr>
<tr>
 <td><b>31. '.$lang['pspdr'].':</b><div class="txtm">Katalog: inc/pollres/</div></td>
 <td><select name="u_pollr1">'.sListBox('inc/pollres',2,$cfg['pollr1']).'</select></td>
</tr>
<tr>
 <td><b>32. '.$lang['pspdr'].' 2:</b><div class="txtm">'.$lang['inblock'].'</div></td>
 <td><select name="u_pollr2">'.sListBox('inc/pollres',2,$cfg['pollr2']).'</select></td>
</tr>
<tr>
 <td class="eth" colspan="2"><input type="submit" value="'.$lang['save'].'" /> <input type="reset" value="'.$lang['reset'].'" /></td>
</tr>
';
eTable();
echo '</form>'; } ?>
