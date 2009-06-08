//Pobierz formularz
function comment(o)
{
	include('cache/emots.js', function()
	{
		include('lib/editor.js', function()
		{
			var http = new Request(o.href, id('com'));

			//Przejmij kontrolê nad formularzem
			http.done = function(x)
			{
				if(x.indexOf('<form') == -1)
				{
					id('comments').innerHTML = x;
				}
				else
				{
					id('com').innerHTML = x;
					var f = document.forms['comm'];
					new Editor(f.text, 1).emots();
					f.prev.onclick = function() { return http.sendForm(this) };
					f.save.onclick = function() { return http.sendForm(this) };
					this.scripts = 0;
				}
			};
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