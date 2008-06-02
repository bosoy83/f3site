<?php
$bbc[0]=Array('b','i','u','g','d','code','quote','big','small','center','right');
$bbc[1]=Array('b','i','u','sup','sub','div style="padding: 5px"><b>'.$lang['code'].':</b><div class="code"><code','div style="padding: 5px"><b>'.$lang['quote'].':</b><div class="quote"','big','small','center','div align="right"');
$bbc[2]=Array('b','i','u','sup','sub','code></div></div','div></div','big','small','center','div');
#Pomoc: Generator BBCode - bbcode.strefaphp.net
function ParseBBC($t) {
global $lang,$bbc,$cfg;
if($cfg['bbcode']!=1) return $t;
#Proste znaczniki
for($i=0;$i<11;$i++)
{
 #Liczenie
 $cnt1=substr_count($t,'['.$bbc[0][$i].']');
 $cnt2=substr_count($t,'[/'.$bbc[0][$i].']');
 #Zamiana
 if($cnt1==$cnt2)
 {
  $t=str_replace('['.$bbc[0][$i].']','<'.$bbc[1][$i].'>',$t);
  $t=str_replace('[/'.$bbc[0][$i].']','</'.$bbc[2][$i].'>',$t);
 }
}
#Kolor
$t=preg_replace('#\[color=(http://)?(.*?)\](.*?)\[/color\]#si','<span style="color:\\2">\\3</span>',$t);
#Odno¶nik, otwieranie w nowym oknie
$t=preg_replace('#\[url\](.*?)?(.*?)\[/url\]#si','<a href="\\1\\2" target="_blank">\\1\\2</a>',$t);
$t=preg_replace('#\[url=(.*?)?(.*?)\](.*?)\[/url\]#si','<a href="\\2" target="_blank">\\3</a>',$t);
#Automatyczne tworzenie linków
$t=preg_replace_callback('#([\n ])([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si', 'bbcode_autolink', $t);
$t=preg_replace('#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]*)?)#i', ' <a href="http://www.\\2.\\3\\4" target="_blank">www.\\2.\\3\\4</a>', $t);
$t=preg_replace('#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i','\\1<a href="mailto:\\2&#64;\\3">\\2&#64;\\3</a>', $t);
#Odno¶nik e-mail
$t=preg_replace('#\[email\]([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#i', '<a href="mailto:\\1&#64;\\2">\\1&#64;\\2</a>', $t);
#js
$t=preg_replace_callback('#\<a(.*?)\>#si', 'bbcode_js', $t);
#wynik
return $t; }

function bbcode_autolink($t){
$lnk=$t[3];
if(strlen($lnk)>30){
if(substr($lnk,0,3)=='www'){$l=9;}else{$l=5;}
$lnk=substr($lnk,0,$l).'(...)'.substr($lnk,strlen($lnk)-8);}
return '<a href="'.$t[2].'://'.$t[3].'" target="_blank">'.$t[2].'://'.$lnk.'</a>';}

#anti js
Function bbcode_js($t) {
return str_replace('javascript','java_script',str_replace('vbscript','vb_script',$t[0])); }
?>
