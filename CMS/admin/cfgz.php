<?php
if(iCMSa!=1 || !Admit('CFG')) exit;

#Zapisz
if($_POST)
{
	require('./lib/config.php');
	$f=new Config('c');
	if($f->save($_POST))
	{
		Info($lang['saved']);
		include('./admin/config.php');
	}
	$f=null; return;
}

include($catl.'adm_cfgz.php');
require('cfg/c.php');
?>
<form action="?a=cfgz" method="post">
<?php
OpenBox($lang['ap_cfgz'],2);
echo '
<tr>
	<th colspan="2">'.$lang['gen'].'</th>
</tr>
<tr>
	<td style="width: 45%"><b>'.$lang['ap_lcnt'].'?</b></td>
	<td><input type="checkbox" name="lcnt"'.(($cfg['lcnt']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_hmop'].'?</b><div class="txtm">'.$lang['ap_hmopd'].'</div></td>
	<td><input name="np" value="'.(int)$cfg['np'].'" size="5" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_cstr'].'?</b></td>
	<td><input name="cstr" type="checkbox"'.((isset($cfg['cstr']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_grate'].':</b><br /><small>'.$lang['ap_grated'].'</small></td>
	<td><input name="grate" type="checkbox"'.((isset($cfg['grate']))?' checked="checked"':'').' /></td>
</tr>

<tr>
	<th colspan="2">'.$lang['arts'].'</th>
</tr>
<tr>
	<td><b>'.$lang['ap_crates'].'?</b></td>
	<td><input type="checkbox" name="arate"'.((isset($cfg['arate']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_artd'].'?</b></td>
	<td><input type="checkbox" name="adisp"'.((isset($cfg['adisp']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_common'].'?</b></td>
	<td><input type="checkbox" name="acomm"'.((isset($cfg['acomm']))?' checked="checked"':'').' /></td>
</tr>

<tr>
	<th colspan="2">'.$lang['files'].'</th>
</tr>
<tr>
	<td><b>'.$lang['ap_crates'].'?</b></td>
	<td><input type="checkbox" name="frate"'.((isset($cfg['frate']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_filesd'].'?</b></td>
	<td><input type="checkbox" name="fcdl"'.((isset($cfg['fcdl']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_common'].'?</b></td>
	<td><input type="checkbox" name="fcomm"'.((isset($cfg['fcomm']))?' checked="checked"':'').' /></td>
</tr>

<tr>
	<th colspan="2">'.$lang['news'].'</th>
</tr>
<tr>
	<td><b>'.$lang['ap_newsact'].':</b></td>
	<td><input name="numofn" value="'.(int)$cfg['numofn'].'" size="5" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_common'].'?</b></td>
	<td><input type="checkbox" name="ncomm"'.((isset($cfg['ncomm']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_ay'].'</b>:<br /><small>'.$lang['ap_ayd'].'</small></td>
	<td><input type="checkbox" name="archyear"'.((isset($cfg['archyear']))?' checked="checked"':'').' /></td>
</tr>

<tr>
	<th colspan="2">'.$lang['imgs'].'</th>
</tr>
<tr>
	<td><b>'.$lang['ap_hmir'].':</b></td>
	<td><input name="imgsrow" value="'.(int)$cfg['imgsrow'].'" size="5" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_common'].'?</b></td>
	<td><input type="checkbox" name="icomm"'.((isset($cfg['icomm']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_min'].':</b></td>
	<td><input name="inp" value="'.(int)$cfg['inp'].'" size="5" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_crates'].'?</b></td>
	<td><input type="checkbox" name="irate"'.((isset($cfg['irate']))?' checked="checked"':'').' /></td>
</tr>

<tr>
	<th colspan="2">'.$lang['se'].'</th>
</tr>
<tr>
	<td><b>'.$lang['ap_cfind'].'?</b></td>
	<td><input name="cfind" type="checkbox"'.((isset($cfg['cfind']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_tfind'].':</b><br /><small>'.$lang['ap_tfind2'].'</small></td>
	<td><input name="ftfind" type="checkbox"'.((isset($cfg['ftfind']))?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_ffind'].':</b></td>
	<td><input name="afind" value="'.(($cfg['afind']==0)?30:(int)$cfg['afind']).'" size="5" /> sec.</td>
</tr>

<tr>
	<th colspan="2">'.$lang['dfcat'].'</th>
</tr>';

#Zmienna zawiera opcje <select>
$out='<optgroup label="'.$lang['cats'].'">';

#Kategorie
$res=$db->query('SELECT ID,name FROM '.PRE.'cats WHERE sc=0 && access!=3 ORDER BY name');
$res->setFetchMode(3); //NUM

foreach($res as $cat)
{
	$out.='<option value="'.$cat[0].'">'.$cat[1].'</option>'; //Bez 1-
}
$res=null;

/*
#Strony inf.
$out.='</optgroup><optgroup label="'.$lang['infp'].'">';

$res=$db->query('SELECT ID,name FROM '.PRE.'pages WHERE access!=2 ORDER BY name');
$res->setFetchMode(3); //NUM

foreach($res as $p)
{
	$out.='<option value="2-'.$p[0].'">'.$p[1].'</option>';
}
$out.='</optgroup>';
$res=null; */

#Dla ka¿dego jêzyka
$i=0;
$js='';
if($dir=opendir('./lang'))
{
	while(false!==($f=readdir($dir)))
	{
		if(is_dir('./lang/'.$f) && strpos($f,'.')!==0)
		{
			$js.='d("df'.++$i.'").value="'.(float)$cfg['start'][$f].'";';
			echo '<tr><td><b>'.$lang['forlng'].strtoupper($f).':</b></td><td><select name="start['.$f.']" id="df'.$i.'">'.$out.'</select></td></tr>';
		}
	}
}

echo '
<tr>
	<td class="eth" colspan="2">
		<input type="submit" value="'.$lang['save'].'" />
		<input type="reset" value="'.$lang['reset'].'" />
	</td>
</tr>';
CloseBox();
?>
</form>
<script type="text/javascript">
<?= $js ?>
</script>
