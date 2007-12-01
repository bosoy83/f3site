<?php
if(iCMSa!=1 || !Admit('CFG')) exit;

#Zapisz
if($_POST)
{
	$_POST['o']['title']=Clean($_POST['tt'],40); //Tytu³
	$_POST['o']['meta_d']=Clean($_POST['md']);
	$_POST['o']['meta_k']=Clean($_POST['mk']);
	$_POST['o']['c']=ctype_alpha($_POST['coo'])?$_POST['coo']:$cfg['c']; //Nazwa cookie

	require('./lib/config.php');
	$f=new Config('main');
	$f->add('cfg',$_POST['o']);

	#Zapis
	if($f->save())
	{
		Info($lang['saved']);
		include('./admin/config.php');
	}
	$f=null; return;
}

include($catl.'adm_cfg.php');
?>

<form action="?a=cfg" method="post">
<?php
OpenBox($lang['ap_cfgo'],2);
echo '
<tr>
	<th colspan="2">'.$lang['ap_docmeta'].'</th>
</tr>
<tr>
	<td style="width: 45%"><b>'.$lang['deflang'].':</b></td>
	<td><select name="o[lang]">'.ListBox('lang',1,$cfg['lang']).'</select></td>
</tr>
<tr>
	<td><b>'.$lang['ap_doctit'].':</b></td>
	<td><input name="tt" size="30" value="'.$cfg['title'].'" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_footer'].':</b></td>
	<td><textarea name="o[footer]" cols="37" rows="4">'.Clean($cfg['footer']).'</textarea></td>
</tr>
<tr>
	<td><b>'.$lang['ap_metad'].':</b><div class="txtm">'.$lang['ap_ind'].'</div></td>
	<td><textarea name="md" cols="37" rows="3">'.$cfg['meta_d'].'</textarea></td>
</tr>
<tr>
	<td><b>'.$lang['ap_metak'].':</b><div class="txtm">'.$lang['ap_ind'].'</div></td>
	<td><textarea name="mk" cols="37">'.$cfg['meta_k'].'</textarea></td>
</tr>
<tr>
	<td><b>'.$lang['gmenu'].' (HTML):</b><div class="txtm">'.$lang['gmenusz'].'</div></td>
	<td><textarea name="o[gmenu]" rows="6" cols="37">'.Clean($cfg['gmenu']).'</textarea></td>
</tr>
<tr>
	<td><b>'.$lang['ap_dkh'].' &lt;head&gt;:</b><div class="txtm">'.$lang['ap_dkhd'].'</div></td>
	<td><textarea name="o[dkh]" rows="6" cols="37">'.Clean($cfg['dkh']).'</textarea></td>
</tr>
<tr>
	<td><b>'.$lang['ap_robots'].':</b></td>
	<td><select name="o[robots]">
		<option value="all">'.$lang['ap_iall'].' (all)</option>
		<option value="index,nofollow"'.(($cfg['robots']=='index,nofollow')?' selected="selected"':'').'>'.$lang['ap_iindex'].' (index, nofollow)</option>
		<option value="noindex,follow"'.(($cfg['robots']=='noindex,follow')?' selected="selected"':'').'>'.$lang['ap_iflw'].' (noindex, follow)</option>
		<option value="none"'.(($cfg['robots']=='none')?' selected="selected"':'').'>'.$lang['ap_inone'].' (none)</option>
	</select></td>
</tr>
<tr>
	<td><b>'.$lang['ap_ncoo'].':</b></td>
	<td><input name="coo" value="'.$cfg['c'].'" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_styl'].':</b></td>
	<td><select name="o[cms_styl]">'.ListBox('style',1,$cfg['cms_styl']).'</select></td>
</tr>

<tr>
	<th colspan="2">'.$lang['ap_date'].'</th>
</tr>
<tr>
	<td><b>'.$lang['ap_dateaf'].':</b><div class="txtm">'.$lang['ap_datefd'].'</div></td>
	<td><input type="text" name="o[fdate]" value="'.$cfg['fdate'].'" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_date1f'].':</b><div class="txtm">'.$lang['ap_date1d'].'</div></td>
	<td><input type="text" name="o[fdate1]" value="'.$cfg['fdate1'].'" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_date2f'].':</b><div class="txtm">'.$lang['ap_date2d'].'</div></td>
	<td><input type="text" name="o[fdate2]" value="'.$cfg['fdate2'].'" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_lastv'].'?</b></td>
	<td><input type="checkbox" name="o[lastv]"'.((isset($cfg['lastv']))?' checked="checked"':'').' /></td>
</tr>

<tr>
	<th colspan="2">'.$lang['users'].'</th>
</tr>
<tr>
	<td><b>'.$lang['ap_pms'].'?</b></td>
	<td><input type="checkbox" name="o[pms_on]"'.((isset($cfg['pms_on']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_pml'].':</b></td>
	<td><input name="o[pm_limit]" value="'.$cfg['pm_limit'].'" style="width: 50px" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_ufind'].':</b></td>
	<td><input type="checkbox" name="o[ufind]"'.((isset($cfg['ufind']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_pswp'].':</b></td>
	<td><input type="checkbox" name="o[pswp]"'.((isset($cfg['pswp']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_alng'].'?</b><div class="txtm">'.$lang['ap_alngd'].'</div></td>
	<td><input type="checkbox" name="o[lng]"'.((isset($cfg['lng']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['banip'].':</b><div class="txtm">'.$lang['banipd'].'</div></td>
	<td><textarea rows="4" cols="30" name="o[ban]">'.$cfg['ban'].'</textarea></td>
</tr>

<tr>
	<th colspan="2">'.$lang['comms'].'</th>
</tr>
<tr>
	<td><b>'.$lang['ap_cnp'].':</b><div class="txtm">'.$lang['ap_c0o'].'</div></td>
	<td><input name="o[cnp]" value="'.$cfg['cnp'].'" style="width: 30px" /></td>
</tr>
<tr>
	<td><b>'.$lang['guestcomm'].'?</b><div class="txtm">'.$lang['gcommd'].'</div></td>
	<td><input type="checkbox" name="o[gcomm]"'.((isset($cfg['gcomm']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_isec'].':</b><div class="txtm">'.$lang['ap_isecd'].'</div></td>
	<td><input type="checkbox" name="o[imgsec]"'.((isset($cfg['imgsec']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_bbcon'].'?</b></td>
	<td><input type="checkbox" name="o[bbc]"'.((isset($cfg['bbc']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_wch'].'?</b></td>
	<td><input type="checkbox" name="o[wordc]"'.((isset($cfg['wordc']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_comml'].':</b><br /><small>'.$lang['0off'].'</small></td>
	<td><input name="o[coml]" value="'.$cfg['coml'].'" style="width: 50px" /> sec.</td>
</tr>
<tr>
	<td><b>'.$lang['comm_mod'].':</b><br /><small>'.$lang['cmod_d'].'</small></td>
	<td><input type="checkbox" name="o[comm_mod]"'.((isset($cfg['comm_mod']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['csort'].':</b></td>
	<td><input type="radio" name="o[csort]" value="1"'.(($cfg['csort']!=2)?' checked="checked"':'').' /> '.$lang['csort1'].' &nbsp;<input type="radio" name="o[csort]" value="2"'.(($cfg['csort']==2)?' checked="checked"':'').' /> '.$lang['csort2'].'</td>
</tr>

<tr>
	<th colspan="2">'.$lang['poll'].'</th>
</tr>
<tr>
	<td><b>'.$lang['ap_common'].'?</b></td>
	<td><input type="checkbox" name="o[pcomm]"'.((isset($cfg['pcomm']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['pcount'].':</b><div class="txtm">'.$lang['pcountd'].'</div></td>
	<td><input name="o[cproc]" value="'.$cfg['cproc'].'" style="width: 30px" /></td>
</tr>
<tr>
	<td><b>'.$lang['pspdr'].':</b><div class="txtm">Katalog: lib/pollres/</div></td>
	<td><select name="o[pollr1]">'.ListBox('lib/pollres',2,$cfg['pollr1']).'</select></td>
</tr>
<tr>
	<td><b>'.$lang['pspdr'].' 2:</b><div class="txtm">'.$lang['inblock'].'</div></td>
	<td><select name="o[pollr2]">'.ListBox('lib/pollres',2,$cfg['pollr2']).'</select></td>
</tr>

<tr>
	<td class="eth" colspan="2"><input type="submit" value="'.$lang['save'].'" /> <input type="reset" value="'.$lang['reset'].'" /></td>
</tr>';

CloseBox(); ?>
</form>
