<?php
if(iCMSa!='X159E' || !ChPrv('C')) exit;
require($catl.'adm_z.php');

#Przelicz ilo¶æ
if($_GET['rec'])
{
 unset($cat,$ilez,$sub);
 db_read('ID,type,access,sc','cats','cat','tn','');
 $ile=count($cat);
 if($ile>0)
 {
  for($i=0;$i<$ile;$i++)
  {
	 $id=$cat[$i][0];
	 $ilez[$id]=db_count('ID',GetCType($cat[$i][1]),' WHERE cat='.$id.' AND access!=2');
	 $sub[$id]=$cat[$i][3];
	 $num[$id]=$ilez[$id];
	}
	for($i=0;$i<$ile;$i++)
  {
	 #Je¿eli dostêpna
	 if($cat[$i][2]!=2 && $cat[$i][2]!=3)
	 {
    $x=$cat[$i][3];
    while($x!='P')
    {
	   #Dolicz
		 $ilez[$x]+=$ilez[$cat[$i][0]];
     $x=$sub[$x];
		}
   }
  }
	foreach($ilez as $k=>$x)
  {
	 #Zapis
   if(is_numeric($x) && is_numeric($num[$k])) db_q('UPDATE {pre}cats SET num='.$num[$k].', nums='.$x.' WHERE ID='.$k);
  }
  unset($ilez,$cat,$k,$x,$sub,$num);
 }
}
?>
<script type="text/javascript">
<!--
function Del(id)
{
	if(confirm("<?=$lang['ap_delcat']?>"))
	{
		del=new Request("adm.php?x=del&id="+id,'i'+id);
		del.method='POST';
		del.add('co','cat')
		del.run()
	}
}
-->
</script>
<?php
if(!is_numeric($_GET['co']) && isset($_GET['co'])) exit;

Info($lang['ap_dinfo'].'<br /><br /><div align="center"><a href="adm.php?a=editcat">'.$lang['ap_kaddc'].'</a> | <a href="?a=cats&amp;rec=1">'.$lang['ap_catrz'].'</a></div>',1);
cTable($lang['ap_dnagz'],5);

#Nag³ówek
echo '
<tr>
 <th>'.$lang['name'].'</th>
 <th style="width: 30px">ID</th>
 <th style="width: 50px">'.$lang['ap_disp'].'</th>
 <th>'.$lang['ap_type'].'</th>
 <th>'.$lang['opt'].'</th>
</tr>';

#Odczyt
db_read('ID,name,access,type,sc,num','cats','dinfo','ta',((isset($_GET['co']))?' WHERE type='.$_GET['co']:'').' ORDER BY name, ID DESC');
$xtypes=Array('',$lang['arts'],$lang['files'],$lang['imgs'],$lang['links'],$lang['news']);
$ile=count($dinfo);

for($i=0,$ii=0;$i<$ile;++$i)
{
	$xid=$dinfo[$i]['ID'];
	echo '<tr>
	<td id="i'.$xid.'">'.(($cfg['num']==1)?++$ii.'. ':'').'<a href="?a=list&amp;co='.$dinfo[$i]['type'].'&amp;id='.$xid.'">'.$dinfo[$i]['name'].'</a> ('.$dinfo[$i]['num'].') '.(($dinfo[$i]['sc']=='P')?'':'*').'</td>
	<td align="center">'.$xid.'</td>
	<td align="center">';
	switch($dinfo[$i]['access'])
	{
		case 1: echo $lang['ap_ison']; break;
		case 2: echo $lang['ap_ishid']; break;
		case 3: echo $lang['ap_isoff']; break;
		default: echo $dinfo[$i]['access'];
	}
	echo '</td>
  <td align="center">'.$xtypes[$dinfo[$i]['type']].'</td>
  <td align="center"><a href="adm.php?a=editcat&amp;id='.$xid.'">'.$lang['edit'].'</a>'.((ChPrv('DEL'))?' &middot; <a href="javascript:Del('.$xid.')">'.$lang['del'].'</a>':'').'</td>
 </tr>';
}
eTable();
?>
