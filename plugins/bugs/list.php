<?php
if(iCMS!='E123') exit;
$id=($_GET['id'])?$_GET['id']:1;
$cat['name']='';

db_read('name,report,num,text','bugcats','cat','oa',' WHERE (see=1 OR see="'.$nlang.'") AND ID='.$id);
if($cat['name']!='')
{
 #Info
 if($cfg['bugs_i1']==1) { if(!empty($cat['text'])) Info(nl2br($cat['text'])); }

 #Kat.
 echo '
 <div class="cs">
  <table cellspacing="0" cellpadding="0" style="width: 100%">
	<tr>
	 <td><a href="?co=bugs">'.$lang['bugs_mp'].'</a></td>
	 '.((BugRights($cat['report']))?'<td align="right"><a href="?co=bugs&amp;act=e&amp;f='.$id.'">'.$lang['bugs_new'].'</a></td>':'').'
	</tr>
	</table>
 </div>';

 #Strona
 if($_GET['page'] && $_GET['page']!=1)
 {
	$page=$_GET['page'];
	$st=($page-1)*$cfg['bugsnum'];
 }
 else
 {
	$page=1;
	$st=0;
 }
 
 #SQL
 $bug=array();
 db_read('ID,name,num,date,status,level','bugs','bug','ta',' WHERE cat='.$id.((ChPrv('BUGS'))?'':' AND status<>5').' ORDER BY ID DESC LIMIT '.$st.','.$cfg['bugsnum']);
 $ile=count($bug);
 
 #Lista
 if($ile>0)
 {
  cTable($cat['name'],4);
	echo '<tr><th style="width: 30px"></th><th>'.$lang['title'].'</th><th style="width: 80px">'.$lang['level'].'</th><th style="width: 20px"></th></tr>';
	
	for($i=0;$i<$ile;$i++)
	{
	 $xbug=$bug[$i];

	 #Poziom
	 $title=&$lang['bugs_s'.$xbug['level']];
	 $class=(BugIsNew('',$xbug['date']))?'new':'img';

	 #Wyœw.
	 echo '
	 <tr class="bug'.$xbug['status'].'">
	  <td class="bug'.$class.'"></td>
	  <td><a class="listlink" href="?co=bugs&amp;act=v&amp;id='.$xbug['ID'].'">'.$xbug['name'].'</a> ('.$xbug['num'].')<div class="txtm">'.$lang['bugs_s'].genDate($xbug['date']).'.</div></td>
		<td title="'.$title.'" class="lv'.$xbug['level'].'"></td>
		<td><input type="checkbox" name="ch['.$xbug['ID'].']" /></td>
	 </tr>';
	}
	#Strony
	if($cat['num']>$ile)
	{
	 echo '
	 <tr><td colspan="4" align="center">'.
	 Pages($page,$cat['num'],$cfg['bugs_num'],'?co=bugs&amp;act=l&amp;id='.$id,2)
	 .'</td></tr>';
	}
	#Legenda
	echo '
	<tr>
	 <td colspan="4" align="center" id="legend">'.$lang['legend'].':
		<span class="bug1">'.$lang['bugs_1'].'</span> |
		<span class="bug2">'.$lang['bugs_2'].'</span> |
		<span class="bug3">'.$lang['bugs_3'].'</span> |
		<span class="bug4">'.$lang['bugs_4'].'</span>
	 </td>
	</tr>';
	eTable();
 }
 else
 {
  Info($lang['noc']);
 }
}
?>