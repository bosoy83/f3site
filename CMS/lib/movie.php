<?php
if(iCMS!=1) exit;
#IE
if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')!==false || strpos($_SERVER['HTTP_USER_AGENT'],'Microsoft')!==false)
{
	#Flash
	if($img['type']==2)
	{
		$ximg='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,24,0" width="'.$xs[0].'" height="'.$xs[1].'">
	<param name="movie" value="'.$img['file'].'" />
	<param name="quality" value="high" /></object>';
	}
	#QuickTime
	else
	{
		$ximg='<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="'.$xs[0].'" height="'.$xs[1].'" codebase="http://www.apple.com/qtactivex/qtplugin.cab">
	<param name="SRC" value="'.$img['file'].'" /></object>';
	}
}
#Inne
else
{
	#Flash
	if($img['type']==2)
	{
		$ximg='<object data="'.$img['file'].'" type="application/x-shockwave-flash" width="'.$xs[0].'" height="'.$xs[1].'">
	<param name="pluginurl" value="http://www.macromedia.com/go/getflashplayer" /></object>';
	}
	else
	{
		$ximg='<embed src="'.$img['file'].'" width="'.$xc[0].'" height="'.$xc[1].'" autoplay="true" controller="true" pluginspace="http://www.apple.com/quicktime/download/"></embed>';
	}
}
?>
