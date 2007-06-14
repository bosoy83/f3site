<?php
require('cfg/c.php');
if(iCMS!=1 || $_REQUEST['cat'] || $_REQUEST['find'] || $cfg['cfind']!=1) exit;
#Znajd¼
if($_POST)
{
 if($_SESSION['findt']>time())
 {
  define('SPECIAL',17);
 }
 else
 {
  extract($_POST);
  $x='';
  $y=Array();
  if($cfg['ftfind']!=1 || $s_ot) $s_ot=1;
  #S³owa
  $s_n=db_esc(str_replace('*','%',TestForm($s_n,1,1,0)));
  if($s_nt==2) { $y[0]=&$s_n; } else { $y=explode(' ',$s_n); }
  $ile=count($y);
  for($i=0;$i<$ile;$i++)
  {
   if(strlen($y[$i])>70 || strlen($y[$i])<4) { define('iERR',1); break; }
   switch($s_t)
   {
    case 2: $x.=' AND (name LIKE "'.$y[$i].'"'.(($s_ot==1)?'':' OR dsc LIKE "'.$y[$i].'" OR fulld LIKE "'.$y[$i].'"').')'; break;
    case 3: $x.=' AND (name LIKE "'.$y[$i].'"'.(($s_ot==1)?'':' OR dsc LIKE "'.$y[$i].'"').')'; break;
    case 4: $x.=' AND (n.name LIKE "'.$y[$i].'"'.(($s_ot==1)?'':' OR n.text LIKE "'.$y[$i].'" OR f.text LIKE "'.$y[$i].'"').')'; break;
    default: $x.=' AND (a.name LIKE "'.$y[$i].'"'.(($s_ot==1)?'':' OR a.dsc LIKE "'.$y[$i].'" OR f.text LIKE "'.$y[$i].'"').')';
   }
  }
  if($ile==0) define('iERR',1);
  #Zapytanie
  if(iERR==1)
  {
   define('SPECIAL',19);
  }
  else
  {
   $_SESSION['findt']=time()+$cfg['afind'];
   $_SESSION['findw']=$s_t;
   switch($s_t)
   {
   case 2: db_read('ID,cat','files','find','tn',' WHERE access=1'.$x); break;
   case 3: db_read('ID,cat','imgs','find','tn',' WHERE access=1'.$x); break;
   case 4: db_read('n.ID,n.cat','news n, {pre}fnews f','find','tn',' WHERE (n.access="'.$nlang.'" OR n.access=1)'.$x); break;
   default: db_read('a.ID,a.cat','arts as a, {pre}artstxt as f','find','tn',' WHERE a.access=1'.$x);
   }
   #Zapis do sesji
   $ile=count($find);
   unset($_SESSION['find']);
   $_SESSION['ifind']=$ile;
   if($ile>0)
   {
    #Wy³. kat.
    db_read('ID','cats','cat','tn',' WHERE access=3');
    $cat[][0]=0;
    for($i=0;$i<$ile;$i++)
    {
     if(!in_array($find[$i][1],$cat)) $_SESSION['find'][]=$find[$i][0];
    }
    if(count($_SESSION['find'])>0)
    {
     define('WHERE','?co=s');
     define('SPECIAL',20);
    }
    else
    {
     define('SPECIAL',18);
    }
   }
   else
   {
    define('SPECIAL',18);
   }
  }
 }
 require('special.php');
}
exit;
?>
