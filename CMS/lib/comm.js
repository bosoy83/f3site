//Pobierz formularz
function comment(o)
{
	include('cache/emots.js', function()
	{
		include('lib/editor.js', function()
		{
			var http = new Request(o.href, $('com'));

			//Przejmij kontrolê nad formularzem
			http.done = function(x)
			{
				if(x.indexOf('<form') == -1)
				{
					$('comments').innerHTML = x;
				}
				else
				{
					$('com').innerHTML = x;
					var f = document.forms['comm'];
					new Editor(f.text, 1).emots();
					f.prev.onclick = function() { return http.sendForm(this) };
					f.save.onclick = function() { return http.sendForm(this) };
					f.name.focus();
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
function coma(act,o)
{
	if(act == 'ok' || confirm(lang.del))
	{
		new Request(o.href, null, {post: 1, done: function(x)
		{
			if(x == 'OK')
			{
				if(act == 'del') o.parentNode.parentNode.parentNode.removeChild(o.parentNode.parentNode);
				else o.parentNode.removeChild(o);
			}
			else alert(x);
		} }
		).send({act:act});
	}
	return false;
}

//Wczytaj plik jêzyka
if(!window.lang) include('lang/' + document.documentElement.lang + '/edit.js');