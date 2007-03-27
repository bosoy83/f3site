<?php
if(iCMS!='E123' || $_REQUEST['mnew'] || $_REQUEST['item']) exit;
#Odczyt
db_read('*','menu','mnew','ta',' WHERE disp=1 OR disp="'.$nlang.'" ORDER BY seq');
$ile=count($mnew);
$xqmenu='';
for($i=0;$i<$ile;$i++)
{
 if(!is_numeric($mnew[$i]['ID'])) exit('B£¡D ID menu!');
 $xqmenu.=' || menu='.$mnew[$i]['ID'];
}
db_read('menu,type,text,url,nw','mitems','item','tn',' WHERE menu="G"'.$xqmenu.' ORDER BY seq');
unset($xqmenu);
#Wy¶w.
function newnav($co)
{
 global $mnew,$nlang,$item;
 $ilem=count($mnew);
 for($im=0;$im<$ilem;$im++)
 {
  if($mnew[$im]['menu']==$co)
  {
   #Nag³ówek
   mnew($mnew[$im]['text'],((empty($mnew[$im]['img']))?'':' style="background-image: url('.$mnew[$im]['img'].'); background-position: bottom right; background-repeat: no-repeat"'));
   #Tekst?
   if($mnew[$im]['type']==1) {
    echo($mnew[$im]['value']);
   }
   #Plik?
   elseif($mnew[$im]['type']==2) {
    if(file_exists($mnew[$im]['value'])) { include($mnew[$im]['value']); } else { echo '[X]'; }
   }
   #Linki?
   else {
    $ileym=count($item);
    for($y=0;$y<$ileym;$y++)
    {
     if($item[$y][0]==$mnew[$im]['ID'])
     {
      switch($item[$y][1])
      {
       case 1: mlink($item[$y][2],$item[$y][3],$item[$y][4]); break;
       case 2: mlink($item[$y][2],'?d='.$item[$y][3],$item[$y][4]); break;
       case 3: mlink($item[$y][2],'?co=page&amp;id='.$item[$y][3],$item[$y][4]); break;
      }
     } 
    }
    unset($ileym,$y);
   }
   mend();
  }
} unset($im); } ?>
