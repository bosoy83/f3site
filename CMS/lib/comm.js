//Pobierz formularz
function comment(o)
{
	include('cache/emots.js', function()
	{
		include('lib/editor.js', function()
		{
			var http = new Request(o.href, $('com'));

			//Przejmij kontrole nad formularzem
			http.done = function(x)
			{
				if(x.indexOf('<form') == -1)
				{
					http.scripts = 0;
					onbeforeunload = null;
					$('comments').innerHTML = x;
					if(window.prettyPrint) prettyPrint();
				}
				else
				{
					$('com').innerHTML = x;
					var f = document.forms['comm'];
					f.prev.onclick = function() { return http.sendForm(this) };
					f.save.onclick = function() { return http.sendForm(this) };
					if(f.name) f.name.focus(); else f.text.focus();
					http.scripts = 1;
				}
			};
			http.send();
		})
	});

	//Zastap link tekstem: czekaj
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
		).post({act:act});
	}
	return false;
}

//Wczytaj plik jêzyka
include('lang/' + document.documentElement.lang + '/edit.js');