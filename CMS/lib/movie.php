<?php
if(iCMS!=1) exit;

#IE?
define('IE', strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')!==false || strpos($_SERVER['HTTP_USER_AGENT'],'Microsoft')!==false);

#Obiekt Flash
function Flash($url,$x,$y)
{
	if(IE)
	{
		return '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,24,0" width="'.$x.'" height="'.$y.'"><param name="movie" value="'.$url.'" /><param name="quality" value="high" /></object>'; 
	}
	else
	{
		return '<object data="'.$url.'" type="application/x-shockwave-flash" width="'.$x.'" height="'.$y.'"><param name="pluginurl" value="http://www.macromedia.com/go/getflashplayer" /></object>';
	}
}

#Obiekt QuickTime
function Movie($url,$x,$y)
{
	if(IE)
	{
		return '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="'.$x.'" height="'.$y.'" codebase="http://www.apple.com/qtactivex/qtplugin.cab"><param name="SRC" value="'.$url.'" /></object>';
	}
	else
	{
		return '<embed src="'.$url.'" width="'.$x.'" height="'.$y.'" autoplay="true" controller="true" pluginspace="http://www.apple.com/quicktime/download/"></embed>';
	}
}