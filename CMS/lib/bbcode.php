<?php
$bbc[0] = array(
	'[b]','[i]','[u]','[g]','[d]',
	'[code]','[quote]',
	'[big]','[small]',
	'[center]','[right]'
);
$bbc[1] = array(
	'[/b]','[/i]','[/u]','[/g]','[/d]',
	'[/code]','[/quote]',
	'[/big]','[/small]',
	'[/center]','[/right]'
);
$bbc[2] = array(
	'<b>', '<i>', '<u>', '<sup>', '<sub>',
	'<div style="padding: 5px"><b>'.$lang['code'].':</b><div class="code"><code>',
	'<div style="padding: 5px"><b>'.$lang['quote'].':</b><div class="quote">',
	'<big>',
	'<small>',
	'<center>',
	'<div align="right">'
);
$bbc[3] = array(
	'</b>', '</i>', '</u>', '</sup>', '</sub>',
	'</code></div></div>', '</div></div>',
	'</big>', '</small>',
	'</center>', '</div>'
);

#Zamieñ BBCode na HTML
function BBCode($x, $exc=false)
{
	global $lang,$bbc,$cfg;
	if(!isset($cfg['bbcode'])) return $x;

	#Proste znaczniki
	$t = str_replace($bbc[0], $bbc[2], $x, $c1);
	$t = str_replace($bbc[1], $bbc[3], $t, $c2);

	#Znaczniki niedomkniête?
	if($c1 != $c2)
	{
		if($exc) throw new Exception($lang['notClosed']); else return $x;
	}

	#Pomoc: Generator BBCode - bbcode.strefaphp.net
	$t = preg_replace(
		array(
			'#\[color=(http://)?(.*?)\](.*?)\[/color\]#si',
			'#\[url\](.*?)?(.*?)\[/url\]#si',
			'#\[url=(.*?)?(.*?)\](.*?)\[/url\]#si',
			'#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]*)?)#i',
			'#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i',
			'#\[email\]([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#i',
		), array(
			'<span style="color:\\2">\\3</span>',
			'<a href="\\1\\2">\\1\\2</a>',
			'<a href="\\2">\\3</a>',
			'<a href="http://www.\\2.\\3\\4" target="_blank">www.\\2.\\3\\4</a>',
			'\\1<a href="mailto:\\2&#64;\\3">\\2&#64;\\3</a>',
			'<a href="mailto:\\1&#64;\\2">\\1&#64;\\2</a>'
		), $t);

	#Automatyczne tworzenie linków + JS
	$t=preg_replace_callback('#([\n ])([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si', 'autolink', $t);
	$t=preg_replace_callback('#\<a(.*?)\>#si', 'bbcode_js', $t);

	return $t;
}

function autolink($t)
{
	$link = $t[3];
	if(isset($link[31]))
	{
		$l = substr($link, 0, 3) == 'www' ? 9 : 5;
		$link = substr($link, 0, $l) . '(...)' . substr($link, strlen($link)-8);
	}
	return '<a href="'.$t[2].'://'.$t[3].'" target="_blank">'.$t[2].'://'.$link.'</a>';
}

#Anty JS
function bbcode_js($t)
{
	return str_replace( array('javascript','vbscript'), array('java_script','vb_script'), $t[0]);
}