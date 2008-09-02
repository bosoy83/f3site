function put(o)
{
	opener.focus();
	opener.FM.focus();
	opener.FM.value = document.getElementsByTagName('tt')[0].innerHTML + o.parentNode.parentNode.getElementsByTagName('a')[0].text;
}