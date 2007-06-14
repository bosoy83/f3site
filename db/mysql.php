<?php
if(iCMS!=1) exit;

#B³±d
function db_err() { exit('DB ERROR: '.mysql_error()); }

#£±czenie
mysql_connect($db_h,$db_u,$db_p) or db_err();
mysql_select_db($db_d) or db_err();
$sqlc+=2;

#Odczyt
function db_read($co,$table,$var,$type,$o='')
{
 $res=mysql_query('SELECT '.$co.' FROM '.PRE.$table.$o.';') or db_err();
 ++$GLOBALS['sqlc'];

 #Do tablicy
 switch($type)
 {
  case 'oa': $GLOBALS[$var]=mysql_fetch_assoc($res); break;
  case 'on': $GLOBALS[$var]=mysql_fetch_row($res); break;
  case 'ta': $i=0; $n=mysql_num_rows($res); while($i<$n&&$GLOBALS[$var][$i]=mysql_fetch_assoc($res))$i++; break;
  case 'tn': $i=0; $n=mysql_num_rows($res); while($i<$n&&$GLOBALS[$var][$i]=mysql_fetch_row($res))$i++; break;
	case 'get': return (($var==1)?@mysql_result($res,0,0):mysql_fetch_row($res)); break;
  default: $GLOBALS[$var][$type]=mysql_fetch_assoc($res);
 }
 mysql_free_result($res); return $n;
}

#Inne zapytanie
function db_q($z,$r=0)
{
 ++$GLOBALS['sqlc'];
 if($r==1)
 {
	$res=mysql_query(str_replace('{pre}',PRE,$z).';') or db_err();
  return $res;
 }
 else
 {
  $res=mysql_unbuffered_query(str_replace('{pre}',PRE,$z).';') or db_err();
 }
}

#Liczenie
function db_count($co,$table,$o='')
{
 $res=mysql_query('SELECT COUNT('.$co.') FROM '.PRE.$table.$o.';');
 ++$GLOBALS['sqlc'];
 if($res) { $xc=mysql_fetch_row($res); mysql_free_result($res); } else { $xc=0; }
 return $xc[0];
 unset($res,$xc);
}

#ID
function db_id()
{
 return mysql_insert_id();
}

#Zabezpiecz dane
function db_esc($co)
{
 return mysql_real_escape_string($co);
}

#Transakcje
function db_begin() {}
function db_finish() {}
?>
