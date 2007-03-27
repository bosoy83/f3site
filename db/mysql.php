<?php
if(iCMS!='E123') exit;
#B³±d
function db_err()
{
  exit('DB ERROR: '.mysql_error());
}
#£±czenie
function db_c($h,$u,$p,$d)
{
 $mysql=mysql_connect($h,$u,$p) or db_err();
 mysql_select_db($d) or db_err();
 $GLOBALS['sqlc']+=2;
}
#Odczyt
function db_read($co,$t,$a,$y,$o)
{
 global $db_pre;
 $result=mysql_query('SELECT '.$co.' FROM '.$db_pre.str_replace('{pre}',$db_pre,$t).$o.';') or db_err();
 $GLOBALS['sqlc']++;
 db_a($result,$a,$y);
 mysql_free_result($result);
 unset($result);
}
#Zapis do tablicy
function db_a($r,$a,$y)
{
 $rows=mysql_num_rows($r);
 switch($y)
 {
  case 'oa': $GLOBALS[$a]=mysql_fetch_assoc($r); break;
  case 'on': $GLOBALS[$a]=mysql_fetch_row($r); break;
  case 'ta': $i=0; while($i<$rows && $GLOBALS[$a][$i]=mysql_fetch_assoc($r)) { $i++; } break;
  case 'tn': $i=0; while($i<$rows && $GLOBALS[$a][$i]=mysql_fetch_row($r)) { $i++; } break;
  default: $GLOBALS[$a][$y]=mysql_fetch_assoc($r);
 }
 unset($rows,$i);
}
#Inne zap.
function db_q($z)
{
 global $db_pre;
 $res=mysql_query(str_replace('{pre}',$db_pre,$z).';') or db_err();
 $GLOBALS['sqlc']++;
 return $res;
}
#Liczenie
function db_count($co,$t,$x)
{
 $res=mysql_query('SELECT COUNT('.$co.') FROM '.$GLOBALS['db_pre'].$t.$x.';');
 $GLOBALS['sqlc']++;
 if($res) { $xc=mysql_fetch_row($res); } else { $xc=0; }
 @mysql_free_result($res);
 return $xc[0];
 unset($res,$xc);
}
#ID
function db_id()
{
 return mysql_insert_id();
}
#Zapis
function db_esc($co)
{
 return mysql_real_escape_string($co);
}
?>
