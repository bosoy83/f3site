<?php
if(iCMS!=1 || $_REQUEST['file']) exit;
require($catl.'files.php');

if($file['ID'])
{
 db_read('ID,name,access,type,sc,opt','cats','dinfo','oa',' WHERE ID='.$file['cat']);
 if($dinfo['access']!=3 && $file['access']==1)
 {
	#Dane
	$data=array($file['name'],genDate($file['date']),nl2br($file['dsc']),Autor($file['author']),$file['dl'],$lang['dl'],$lang['author'],$lang['rate'],$lang['numofd'],$lang['size'],$lang['desc']);
	
	#Rozmiar i URL
  if($file['size']=='A')
  {
   if(file_exists('./'.$file['file']))
   {
    $size=filesize('./'.$file['file']);
    if($size>=1048576)
		{
		 $data[12]=round($size/1048576 ,2).' MB';
		}
		elseif($xsize>=1024)
		{
		 $data[12]=round($size/1024 ,2).' KB';
		}
		else
		{
		 $data[12]=$size.' B';
		}
    $data[13]=($cfg['fcdl']==1)?'getfile.php?id='.$id:$file['file'];
   }
   else
   {
    $data[12]=$lang['nof'];
    $data[13]='#';
   }
  }
  else
  {
	 $data[12]=$file['size'];
   $data[13]=($cfg['fcdl']==1)?'getfile.php?id='.$id:$file['file'];
  }
	
  #Prawa
  if(ChPrv('F'))
	{
	 $buttons='<img src="img/icon/edit.png" alt="E" /> <a href="">'.$lang['edit'].'</a>';
	}
	CatStr(1,$buttons);
	
  #Ocena
  $data[14]=($cfg['frate']==1 && !strstr($dinfo['opt'],'O'))?Rating($file['rates'],1).' &middot; <a href="javascript:Okno(\'?mode=o&amp;co=file&amp;id='.$id.'\',400,250,200,200)">'.$lang['ratedo'].'</a>':'-';
	
	#Szablon
	$tpl=file_get_contents($catst.'file.php');
	$tpl=str_replace(array('{title}','{date}','{desc}','{who}','{num}','{lang.dl}','{lang.who}','{lang.rate}','{lang.num}','{lang.size}','{lang.desc}','{size}','{url}','{rate}'),$data,$tpl);
	$tpl=explode('{fulldesc}',$tpl);
	echo $tpl[0].nl2br($file['fulld']).$tpl[1];
	
	unset($data,$tpl,$file,$size);
	
  #Komentarze
  if($cfg['fcomm']==1 && !strstr($dinfo['opt'],'C'))
  {
   define('CT','2');
   require('inc/comm.php');
  }
 }
 else {
 Info($lang['noex']);
 }
}
else {
 Info($lang['noex']);
}
?>
