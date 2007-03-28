<?php
if(iCMSa!='X159E' || !ChPrv('NM')) exit;
#Generator plików menu
if(is_writable('cfg'))
{
 db_read('*','menu','mnew','ta',' WHERE disp!=2 ORDER BY seq');
 $ile=count($mnew);
 $xqmenu='';
 
 #Zamieñ '
 function Ap($x) { return str_replace('\'','\\\'',$x); }
 
 #Sk³ad SQL
 for($i=0;$i<$ile;$i++)
 {
  if(!is_numeric($mnew[$i]['ID'])) exit('B£¡D ID menu!');
  $xqmenu.=' || menu='.$mnew[$i]['ID'];
 }
 
 #Linki
 db_read('menu,type,text,url,nw','mitems','item','tn',' WHERE menu="G"'.$xqmenu.' ORDER BY seq');
 unset($xqmenu);
 
 #Jêzyki
 if($dh=opendir('lang'))
 {
  while(($file = readdir($dh))!==false)
  {
   unset($_xm);
   if(is_dir('lang/'.$file) && $file!='.' && $file!='..')
   {
    for($i=0;$i<$ile;$i++)
    {
     if($mnew[$i]['disp']==$file || $mnew[$i]['disp']==1)
     {
		  #Nowy blok
      $_xt=&$mnew[$i]['menu'];
      $_xm[$_xt].='mnew(\''.Ap($mnew[$i]['text']).'\',\''.((empty($mnew[$i]['img']))?'':' style="background-image: url('.Ap($mnew[$i]['img']).'); background-position: bottom right; background-repeat: no-repeat"').'\'); ';

			#Tekst
      if($mnew[$i]['type']==1) { $_xm[$_xt].='echo \''.Ap($mnew[$i]['value']).'\'; '; }
			#Plik
      elseif($mnew[$i]['type']==2) { $_xm[$_xt].='include(\''.Ap($mnew[$i]['value']).'\'); '; }

			#Linki
      else {
       $ileym=count($item);
			 $_xm[$_xt].='?><ul>';
       for($ym=0;$ym<$ileym;$ym++)
			 {
        if($item[$ym][0]==$mnew[$i]['ID'])
				{
         switch($item[$ym][1])
				 {
          case 1: $_xm[$_xt].='<li><a href="'.Ap($item[$ym][3]).'"'.(($item[$ym][4]==1)?' target="_blank"':'').'>'.Ap($item[$ym][2]).'</a></li>'; break;
          case 2: $_xm[$_xt].='<li><a href="?d='.Ap($item[$ym][3]).'"'.(($item[$ym][4]==1)?' target="_blank"':'').'>'.Ap($item[$ym][2]).'</a></li>'; break;
          case 3: $_xm[$_xt].='<li><a href="?co=page&amp;id='.Ap($item[$ym][3]).'"'.(($item[$ym][4]==1)?' target="_blank"':'').'>'.Ap($item[$ym][2]).'</a></li>'; break;
         }
        }
       }
       unset($ileym,$ym);
			 $_xm[$_xt].='<ul><?php ';
      }
      $_xm[$_xt].='mend(); ';
     }
    }
    $f=fopen('cfg/menu'.$file.'.php','w');
		flock($f,2);
		fwrite($f,'<?php function newnav($co) { if($co==1) { '.$_xm[1].' } else { '.$_xm[2].' } } ?>');
		flock($f,3);
		fclose($f);
   }
  }
 }
}
else
{
 exit('ERROR: CHMOD "CFG" DIRECTORY TO 777!');
}
unset($mnew,$item,$_xm,$_xt,$file);
?>
