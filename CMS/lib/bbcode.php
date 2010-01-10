<?php #Zamieñ BBCode na HTML
function BBCode($x, $exc=false)
{
	global $lang,$cfg;
	static $bbc;
	if(!isset($cfg['bbcode'])) return $x;
	if(!$bbc)
	{
		$bbc[0] = array(
			'[b]','[i]','[u]','[sup]','[sub]',
			'[code]','[quote]',
			'[big]','[small]',
			'[center]','[right]'
		);
		$bbc[1] = array(
			'[/b]','[/i]','[/u]','[/sup]','[/sub]',
			'[/code]','[/quote]',
			'[/big]','[/small]',
			'[/center]','[/right]'
		);
		$bbc[2] = array(
			'<b>', '<i>', '<u>', '<sup>', '<sub>',
			'<pre>',
			'<blockquote>',
			'<big>',
			'<small>',
			'<center>',
			'<div align="right">'
		);
		$bbc[3] = array(
			'</b>', '</i>', '</u>', '</sup>', '</sub>',
			'</pre>', '</blockquote>',
			'</big>', '</small>',
			'</center>', '</div>'
		);
	}

	#Proste znaczniki
	$t = str_replace($bbc[0], $bbc[2], $x, $c1);
	$t = str_replace($bbc[1], $bbc[3], $t, $c2);

	#Znaczniki niedomkniête?
	if($c1 != $c2)
	{
		if($exc) throw new Exception(); else return $x;
	}

	#Kolor, e-mail
	$t = preg_replace(
		array(
			'@\[color=([A-Za-z0-9#].*?)\](.*?)\[/color\]@si',
			'#\[email\]([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#i',
			'#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i',
			'#==(.*?)==([\s]*)#i',
		), array(
			'<span style="color:\\1">\\2</span>',
			'<a href="mailto:\\1@\\2">\\1@\\2</a>',
			'\\1<a href="mailto:\\2@\\3">\\2@\\3</a>',
			'<h3>\\1</h3>'
		), $t);

	#Linki
	$t = preg_replace_callback(
		array(
			'#\[url]([^\]].*?)\[/url\]#i',
			'#\[url=([^\]]+)\](.*?)\[/url\]#i',
			'#[\n ]+(www\.[a-z0-9\-]+\.[a-z0-9\-.\~,\?!%\*_\#:;~\\&$@\/=\+]*)#i',
			'#[\n ]+(http+s?://[a-z0-9\-]+\.[a-z0-9\-.\~,\?!%\*_\#:;~\\&$@\/=\+]*)#i',
		), 'bburl', $t);

	#Usuñ JS i zwróæ gotowy tekst
	return preg_replace_callback('#\<a(.*?)\>#si', 'bbcode_js', $t);
}

#Zabezpiecz URL i skróæ link
function bburl($t)
{
	$link = trim(str_replace(' ', '%20', $t[1]), '.,');
	if(strpos($link, '"') !== false) return '';
	if(strpos($link, 'www.') === 0) $link = 'http://' . $link;
	if(isset($t[2]))
	{
		return '<a href="'.$link.'">'.$t[2].'</a>';
	}
	else
	{
		$text = isset($t[1][31]) ? substr($t[1], 0, 21) . '...' . substr($t[1], -8) : $t[1];
		return ' <a href="'.$link.'">'.$text.'</a>';
	}
}

#Anty JS
function bbcode_js($t)
{
	return str_ireplace( array('javascript:','vbscript:'), array('java_script','vb_script'), $t[0]);
}