<?php
if(iCMSa!='X159E' || !ChPrv('A') || $_REQUEST['art']) exit;
require($catl.'adm_z.php');
if(isset($_GET['id'])) { $id=$_GET['id']; } else { $id='new'; }

#Zapis
if($_POST)
{
 #Dane
 $xu_c=db_esc($_POST['xu_c']);
 $xu_n=db_esc(TestForm($_POST['xu_n'],1,1,0));
 $xu_d=db_esc(TestForm($_POST['xu_d'],0,1,0));
 $xu_au=db_esc(TestForm($_POST['xu_au'],1,1,0));
 $licz=0;
 
 #Nowy
 if($id=='new')
 {
  db_q('INSERT INTO {pre}arts (cat,name,dsc,date,author,access,priority,pages) VALUES ("'.$xu_c.'","'.$xu_n.'","'.$xu_d.'","'.strftime('%Y-%m-%d').'","'.$xu_au.'","'.db_esc($_POST['xu_a']).'","'.db_esc($_POST['xu_p']).'",0)');
	$id=db_id();
	ChItmN($xu_c,'+1');
 }
 
 #Edycja
 else
 {
  $art[0]=0;
  db_read('cat,access','arts','art','on',' WHERE ID='.$id);
  db_q('UPDATE {pre}arts SET cat="'.$xu_c.'", name="'.$xu_n.'", dsc="'.$xu_d.'", author="'.$xu_au.'", access="'.db_esc($_POST['xu_a']).'", priority="'.db_esc($_POST['xu_p']).'" WHERE ID='.$id);
	$licz=db_count('*','artstxt',' WHERE ID='.$id);
	
  #Ilo¶æ artów
	if($art[0]!=$xu_c)
	{
	 ChItmN($art[0],'-1');
	 if($_POST['xu_a']==1) ChItmN($xu_c,'+1');
	}
	else
	{
	 if($art[1]>$_POST['xu_a']) ChItmN($xu_c,'+1');
	 if($art[1]<$_POST['xu_a']) ChItmN($xu_c,'-1');
	}
 }
 
 #Tre¶æ
 $ile=count($_POST['xu_txt']);
 $o=(($_POST['xu_br'])?'L':'').($_POST['xu_emo'])?'E':'';
 for($i=1;$i<=$ile;$i++)
 {
  if($i>$licz)
	{
	 db_q('INSERT INTO {pre}artstxt VALUES ('.$id.','.$i.',"'.$xu_c.'","'.db_esc(TestForm($_POST['xu_txt'][$i],0,0,0)).'","'.$o.'")');
	}
	else
	{
	 db_q('UPDATE {pre}artstxt SET cat="'.$xu_c.'", text="'.db_esc(TestForm($_POST['xu_txt'][$i],0,0,0)).'", opt="'.$o.'" WHERE ID='.$id.' AND page='.$i);
	}
 }
 
 Info('<center>'.$lang['saved'].'<br /><br /><a href="?a=editart">'.$lang['addart'].'</a> | <a href="?a=editart&amp;id='.$id.'">'.$lang['ed2'].'</a> | <a href="index.php?co=art&amp;id='.$id.'">'.$lang['goto'].' &raquo;</a></center>');
}

#Form
if(!$_POST)
{
 #Odczyt
 if($id!='new')
 {
	db_read('*','arts','art','oa',' WHERE ID='.$id);
	db_read('*','artstxt','fart','ta',' WHERE ID='.$id.' ORDER BY page');
	$ile=count($fart);
	if(empty($art['ID'])) exit('Artyku³ nie istnieje! Article does not exists!');
 }
 else { $ile=1; }
?>
<script type="text/javascript">
<!--
var pv=new Request('request.php?co=text','tbox')
pv.method='POST'
function Prev()
{
 d('dbox').style.display='block'
 location='#p'
 pv.reset()
 pv.add('o',((d('emo').checked)?'E':'')+((d('br').checked)?'L':'')+'H')
 pv.add('text',d('tp'+c).value)
 pv.run()
}
var c=1;
var ile=<?=$ile?>;
function CP(p)
{
 d('tp'+c).style.display='none'
 d('tab'+c).removeAttribute('style')
 tp1.id='tp'+p
 d('tp'+p).style.display='block'
 d('tab'+p).style.fontWeight='bold'
 c=p
}
function NP()
{
 ile++
 d('tabs').innerHTML+='<input class="tab" value="'+ile+'" type="button" id="tab'+ile+'" onclick="CP('+ile+')" />'
 var x=document.createElement('textarea')
 x.rows=18
 x.cols=70
 x.id='tp'+ile
 x.name='xu_txt['+ile+']'
 d('tps').appendChild(x)
 CP(ile)
}
-->
</script>
<?php

 db_read('ID,name','cats','xcat','tn',' WHERE type=1');
 $ilec=count($xcat);
 
 #Podgl±d
 echo '<a id="p"></a><div id="dbox" style="display: none">';
 cTable($lang['preview'],1);
 echo '<tr><td id="tbox" class="txt"></td></tr>';
 eTable();
 
 #Form
 echo '</div><form action="adm.php?a=editart'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
 cTable( (($id=='new')?$lang['addart']:$lang['editart']) ,2);
 echo '
 <tr>
  <td style="width: 31%"><b>1. '.$lang['cat'].':</b></td>
  <td><select name="xu_c">'; for($i=0;$i<$ilec;$i++) { echo '<option value="'.$xcat[$i][0].'"'.(($art['cat']==$xcat[$i][0])?' selected="selected"':'').'>'.$xcat[$i][1].'</option>'; } echo '<option value="0">'.$lang['lack'].'</option></select></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['name'].':</b></td>
  <td><input maxlength="50" name="xu_n" value="'.$art['name'].'" /></td>
 </tr>
 <tr>
  <td><b>3. '.$lang['ap_acc'].':</b></td>
  <td><input type="radio" name="xu_a" value="1"'.(($art['access']==1)?' checked="checked"':'').' /> '.$lang['ap_isaon'].' &nbsp;<input type="radio" name="xu_a" value="2"'.(($art['access']==2)?' checked="checked"':'').' /> '.$lang['ap_isaoff'].'</td>
 </tr>
 <tr>
  <td><b>4. '.$lang['priot'].':</b></td>
  <td><select name="xu_p"><option value="1">'.$lang['high'].'</option><option value="2"'.(($art['priority']==2)?' selected="selected"':'').'>'.$lang['normal'].'</option><option value="3"'.(($art['priority']==3)?' selected="selected"':'').'>'.$lang['low'].'</option></select></td>
 </tr>
 <tr>
  <td><b>5. '.$lang['desc'].':</b></td>
  <td><textarea name="xu_d" cols="45" rows="2">'.$art['dsc'].'</textarea></td>
 </tr>
 <tr>
  <td><b>6. '.$lang['author'].':</b><div class="txtm">'.$lang['nameid'].'</div></td>
  <td><input name="xu_au" value="'.(($id=='new')?UID:$art['author']).'" maxlength="30" /></td>
 </tr>
 <tr>
  <td><b>7. '.$lang['opt'].':</b></td>
  <td><input type="checkbox" id="emo" name="xu_emo"'.((strpos($fart[0]['opt'],'E')!==false)?' checked="checked"':'').' /> '.$lang['emoon'].'<br /><input type="checkbox" id="br" name="xu_br"'.((strpos($fart[0]['opt'],'L')!==false || $id=='new')?' checked="checked"':'').' /> '.$lang['br'].'</td>
 </tr>';
 eTable();
 include_once('inc/btn.php');
 
 cTable($lang['text'],1);
 echo '
 <tr>
  <td align="center"><div style="padding-bottom: 5px" id="tabs">'.$lang['page'].': <input type="button" class="tab" value="'.$lang['add'].'" onclick="NP()" /> ';
	
	#Tre¶æ
	for($i=1;$i<=$ile;$i++)
	{
	 echo '<input type="button" class="tab" value="'.$i.'" id="tab'.$i.'" onclick="CP('.$i.')"'.(($i==1)?' style="font-weight: bold"':'').' />';
	}
	echo '</div><div style="padding: 3px" id="tps">';
	
	Tools('tp1');
	for($i=1;$i<=$ile;$i++)
	{
	 echo '<textarea id="tp'.$i.'" '.(($i==1)?'':'style="display: none" ').'rows="18" cols="70" name="xu_txt['.$i.']">'.htmlspecialchars($fart[($i-1)]['text']).'</textarea>';
	}
	
	echo '</div>'.$lang['arttip'].'</td>
 </tr>
 <tr>
  <td class="eth"><input type="button" value="'.$lang['preview'].'" onclick="Prev()" /> <input type="submit" value="'.$lang['save'].'" /></td>
 </tr>';
 eTable();
}
?>
</form>
