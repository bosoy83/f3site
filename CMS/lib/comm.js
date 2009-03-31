//Pobierz formularz
function comment(o)
{
	include('/cache/emots.js', function()
	{
		include('/lib/editor.js', function()
		{
			var http = new Request(o.href, id('com'), {scripts: 1});

			/* Przejmij kontrolê nad formularzem - dopiero po wprowadzniu XML lub JSON
			http.done = function(x)
			{
				id('com').innerHTML = x;
			}
			var f = document.forms['comm'];
			var func = function() { http.sendForm(this) };
			f.prev.onclick = func;
			f.save.onclick = func;*/

			http.send();
		})
	});

	//Zast±p link tekstem: czekaj
	o.parentNode.innerHTML = lang.wait;
	return false;
}

//Akcja komentarza
function coma(url,act,o)
{
	if(act == 'ok' || confirm(lang.del))
	{
		var x;
		if(!x) x = new Request('request.php'+url, null, {post:1,done:function(x){alert(x)}});
		x.url = url;
		x.send({act:act});
		o.parentNode.removeChild(o)
	}
}

//Wczytaj plik jêzyka
if(!window.lang) include('lang/' + document.documentElement.lang + '/edit.js');