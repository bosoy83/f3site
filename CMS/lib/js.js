//Plik przeznaczony do edycji
//Skompresuj go: http://dean.edwards.name/packe0

//Otwórz okno na œrodku ekranu
function okno(url, width, height)
{
	return open(url, '', 'scrollbars=yes,width='+width+',height='+height+',top='+(screen.height-height)/2+',left='+(screen.width-width)/2)
}

//Zmieñ CSS
function CSS(x)
{
	if(x)
	{
		var link = document.getElementsByTagName('link')[0];
		link.href = link.href.slice(0,-5) + x + '.css';
		setCookie('CSS', x, 3600)
	}
}

//Do³¹cz plik JS - loaded opcjonalny
function include(file, loaded)
{
	if(file.indexOf('.css') > 0)
	{
		var js = document.createElement('link');
		js.type = 'text/css';
		js.rel = 'stylesheet';
		js.href = file;
	}
	else
	{
		var js = document.createElement('script');
		js.type = 'text/javascript';
		js.src = file;
	}

	//Wywo³aj funkcjê, gdy plik zostanie za³adowany
	if(loaded)
	{
		if(js.readyState)
		{
			js.onreadystatechange = function()
			{
				if(js.readyState == 'complete' || js.readyState == 'loaded')
				{
					loaded(); js.onreadystatechange = null
				}
			}
		}
		else js.onload = loaded;
	}
	document.getElementsByTagName('head')[0].appendChild(js);
}

//Dodaj zdarzenie - IE i W3
function addEvent(type, f, o, capture)
{
	if(window.addEventListener)
	{
		(o||window).addEventListener(type, f, capture||false)
	}
	else if(window.attachEvent)
	{
		(o||window).attachEvent('on'+type, f)
	}
	else if(!o['on'+type])
	{
		(o||window)[type] = f
	}
}

//Szybki dostêp do elementów po ID
function $(x) { return document.getElementById(x) }

//Wstaw kod
function BBC(o, left, right, inside)
{
	if(o.selectionStart != undefined)
	{
		var start  = o.selectionStart;
		var end    = o.selectionEnd;
		var scroll = o.scrollTop;
		var before = o.value.substring(0, start);
		var after  = o.value.substring(end, o.textLength);
		var inside = (inside) ? inside : o.value.substring(start, end);

		o.value = before + left + inside + right + after;
		o.selectionEnd = o.selectionStart = before.length + left.length + inside.length;
		o.scrollTop = scroll;
		o.focus();
	}
	else if(document.selection)
	{
		o.focus();
		var sel  = document.selection.createRange(),
		inside   = (inside) ? inside : sel.text;
		sel.text = left + inside + right;
	}
}

//Ustaw cookie
function setCookie(name, txt, expires, path)
{
	var date = new Date();
	date.setTime(time = (expires*3600000) + date.getTime()); //expires = iloœæ godzin
	if(path == undefined)
	{
		path = document.getElementsByTagName('base')[0].href;
		path = path.substr(path.indexOf('/', 8));
	}
	document.cookie = name + '=' + escape(txt) + ';path=' + path + ';expires=' + date.toGMTString();
}

//Poka¿ lub ukryj
function show(o, once)
{
	if(typeof o == 'string') o = $(o);
	var x = o.style;
	if(x.display=='none') x.display=''; else if(once==undefined) x.display='none'
}

//Kursor
var cx,cy,toHide = [];

//Mysz
document.onmousedown = function(e)
{
	if(e)
	{
		cx = e.pageX;
		cy = e.pageY
	}
	else
	{
		cx = event.clientX + document.documentElement.scrollLeft;
		cy = event.clientY + document.documentElement.scrollTop
	}
	if(cx<0) cx=0;
	if(cy<0) cy=0
};
document.onclick = function()
{
	for(var i in toHide)
	{
		toHide[i].style.visibility = 'hidden';
		toHide.pop(toHide[i]);
	}
};

//Hint
function hint(o, left, top, autoHide)
{
	if(typeof o == 'string')
	{
		o = $(o);
	}
	if(o.style.visibility != 'visible')
	{
		if(top != 0)
		{
			o.style.left = left + 'px';
			o.style.top  = top  + 'px'
		}
		o.style.visibility = 'visible';
		if(autoHide == 1) setTimeout(function() { toHide.push(o) }, 10);
	}
	else o.style.visibility = 'hidden';
}

//JSON
function getJSON(x)
{
	if(window.JSON)
	{
		return JSON.parse(x);
	}
	else
	{
		return !(/[^,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/.test(x.replace(/"(\\.|[^"\\])*"/g, ''))) && eval('('+x+')');
	}
}

//
// *** AJAX REQUESTS ***
//
function Request(url, box, opt)
{
	//Opcje
	opt = opt || {};

	//Gdzie wstawiæ odpowiedŸ?
	this.o = box || $('main');

	//Adres docelowy
	this.url = url;

	//Wykonaj skrypty JS - domyœlnie NIE
	this.scripts = opt.scripts || false;

	//Gdy odpowiedŸ jest pobierana
	this.loading = opt.loading || null;

	//Gdy ¿¹danie nie powiedzie siê
	this.fail = opt.fail || function(x) { alert(x) };

	//Gdy ¿¹danie zakoñczone sukcesem
	this.done = opt.done || function(x) { this.o.innerHTML = x }

	//Czas oczekiwania na odpowiedŸ
	this.timeout = opt.timeout || 50000;
}

//Wyœlij ¿¹danie metod¹ GET
Request.prototype.get = function(list)
{
	this.send(list, false);
}

//Wyœlij ¿¹danie metod¹ POST
Request.prototype.post = function(list)
{
	this.send(list, true);
}

//Wyœlij ¿¹danie
Request.prototype.send = function(list, post)
{
	if(!this.http)
	{
		if(window.XMLHttpRequest) //XMLHttpRequest
		{
			this.http = new XMLHttpRequest();
			if(this.http.overrideMimeType) this.http.overrideMimeType('text/html');
		}
		else if(window.ActiveXObject) //IE
		{
			try
			{
				this.http = new ActiveXObject("Msxml2.XMLHTTP")
			}
			catch(e)
			{
				try
				{
					this.http = new ActiveXObject("Microsoft.XMLHTTP")
				}
				catch(e)
				{
					throw new Exception('AJAX is not supported!')
				}
			}
		}
		if(!this.http) throw new Exception('Cannot create AJAX object!');

		//Odnoœnik do THIS
		var self = this;

		//Gdy zmienia siê status ¿¹dania...
		this.http.onreadystatechange = function()
		{
			if(self.http.readyState == 4)
			{
				try
				{
					if(self.http.status == 200 || self.http.status == 0)
					{
						//Wstaw odpowiedŸ
						self.done(self.http.responseText);

						//Wykonaj znaczniki <script>
						if(self.scripts)
						{
							var script = self.o.getElementsByTagName('script');
							for(var i=0; i<script.length; ++i)
							{
								if(script[i].src)
								{
									var d = document.createElement('script');
									d.src = script[i].src;
									script[i].appendChild(d)
								}
								else eval(script[i].innerHTML);
							}
						}
					}
					else
					{
						self.fail('Server is busy.');
					}
				}
				catch(e)
				{
					self.fail(e);
				}
				document.body.style.cursor = '';
			}
		};
	}
	//Gdy nie zdefiniowano URL
	if(this.url == '') return;

	//Gdy odpowiedŸ jest pobierana
	if(this.loading) this.loading();

	//Kursor
	document.body.style.cursor = 'progress';

	//Otwórz po³¹czenie
	this.http.open(post ? 'POST' : 'GET', this.url, true);

	//Typ POST
	if(post)
	{
		this.http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	}
	this.http.setRequestHeader('X-Requested-With','XMLHttpRequest');

	//Parametry
	if(typeof list == 'object')
	{
		var name,param = [];
		for(name in list) param.push(encodeURIComponent(name)+'='+encodeURIComponent(list[name]));
		this.http.send(param.join('&'))
	}
	else
	{
		this.http.send();
	}
};

//
// *** WYŒLIJ FORMULARZ ZA POMOC¥ AJAX ***
//

//Utwórz tymczasowy obiekt Request i wyœlij formularz
function send(o,id,opt)
{
	new Request(o.form.action || o.form.baseURI, id, opt).sendForm(o);
	return false;
}

//Przechwyæ kontrolê nad gwiazdkami
function rate(o)
{
	for(var i=0; i<o.v.length; i++)
	{
		o.v[i].onclick = function()
		{
			send(this, '', {done: function(x) { alert(x) }});
			return false
		}
	}
}

//Wyœlij formularz za pomoc¹ istniej¹cego obiektu Request
//Argumentem jest pole SUBMIT - w zdarzeniu onclick: [obiektRequest].sendForm(this)
Request.prototype.sendForm = function(o)
{
	var el = o.form.elements, x, param = {};
	for(var i=0; i<el.length; ++i)
	{
		x = el[i];
		switch(x.type || '')
		{
			case 'radio':
			case 'checkbox':
				if(x.checked) param[x.name] = x.value || 1; //Radio + Checkbox
				break;
			case 'text':
			case 'textarea':
			case 'hidden':
			case 'password':
				param[x.name] = x.value; //Text
				break;
			case 'select':
			case 'select-one':
			case 'select-multiple':
				for(var y=0; y<x.options.length; ++y)
				{
					if(x.options[y].selected) param[x.name] = x.options[y].value //Select
				}
				break;
		}
	}
	if(o.name) param[o.name] = o.value;
	this.post(param);
	o.disabled = 1;
	return false
};

//
// *** DIALOG WINDOWS WITH AJAX SUPPORT ***
//
function Dialog(title, txt, width, height)
{
	this.o = document.createElement('div');
	this.o.className = 'dialog';
	this.bg = document.createElement('div'); //Overlay
	this.bg.className = 'overlay';
	this.title = this.o.appendChild(document.createElement('h3'));
	this.title.ref = this;
	this.title.innerHTML = '<div class="exit" onclick="parentNode.ref.hide()">x</div>' + title;
	this.body = this.o.appendChild(document.createElement('div'));
	this.body.style.height = height - 20 + 'px'
	this.body.style.overflow = 'auto';

	//Width and height
	if(width) this.o.style.width = width + 'px';
	if(height) this.o.style.height = height + 'px';

	//Content
	if(txt) this.body.innerHTML = txt;
}
Dialog.prototype.show = function()
{
	if(this.o.parentNode != document.body)
	{
		document.body.appendChild(this.bg);
		document.body.appendChild(this.o);
		this.o.style.left = (document.documentElement.clientWidth - this.o.clientWidth) / 2 + 'px';
		this.o.style.top = (document.documentElement.clientHeight - this.o.clientHeight) / 2 + 'px';
	}
	curDialog = this;
};
Dialog.prototype.hide = function()
{
	if(this.o.parentNode == document.body)
	{
		document.body.removeChild(this.bg);
		document.body.removeChild(this.o);
	}
};
Dialog.prototype.load = function(url, data, post)
{
	new Request(url, this.body, {scripts:1}).send(data,post);
	this.show();
}

//this instanceof arguments.callee ? 'object instance' : 'function call'