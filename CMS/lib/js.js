//Plik przeznaczony do edycji
//Skompresuj go: http://dean.edwards.name/packe0

//Otw�rz okno na �rodku ekranu
function okno(url, width, height)
{
	return open(url, '', 'scrollbars=yes,width='+width+',height='+height+',top='+(screen.height-height)/2+',left='+(screen.width-width)/2)
}

//Zmie� CSS
function CSS(x)
{
	if(x)
	{
		var link = document.getElementsByTagName('link')[0];
		link.href = link.href.slice(0,-5) + x + '.css';
	}
	else
	{
		if(!CSS.d)
		{
			CSS.d = new Dialog('SKIN','',250,200);
			CSS.d.load('request.php?co=css');
		}
		else
		{
			CSS.d.show();
		}
	}
}

//Do��cz plik JS - loaded opcjonalny
function include(file, loaded)
{
	var js = document.createElement('script');
	js.type = 'text/javascript';
	js.src = file;

	//Wywo�aj funkcj�, gdy plik zostanie za�adowany
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
function addEvent(type, f, o)
{
	if(window.addEventListener)
	{
		(o||window).addEventListener(type, f, false)
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

//Szybki dost�p do element�w po ID
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
function setCookie(name, txt, expires)
{
	var date = new Date();
	date.setTime(time = (expires*3600000) + date.getTime()); //expires = ilo�� godzin
	var time = date.toGMTString();
	document.cookie = name + '=' + escape(txt) + '; expires=' + time;
}

//Poka� lub ukryj
function show(o, once)
{
	if(typeof o == 'string') o = $(o);
	var x = o.style;
	if(x.display=='none') x.display='block'; else if(once==undefined) x.display='none'
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

//
// *** AJAX REQUESTS ***
//
function Request(url, box, opt)
{
	//Opcje
	opt = opt || {};

	//Gdzie wstawi� odpowied�?
	this.o = box || $('main');

	//Adres docelowy
	this.url = url;

	//Metoda POST - domy�lnie GET
	this.post = opt.post || false;

	//Parametry POST
	this.param = [];

	//Wykonaj skrypty JS - domy�lnie NIE
	this.scripts = opt.scripts || false;

	//Gdy odpowied� jest pobierana
	this.loading = opt.loading || null;

	//Gdy ��danie nie powiedzie si�
	this.fail = opt.fail || function(x) { alert(x) };

	//Gdy ��danie zako�czone sukcesem
	this.done = opt.done || function(x) { this.o.innerHTML = x }

	//Czas oczekiwania na odpowied�
	this.timeout = opt.timeout || 50000;
}

//Wy�lij ��danie
Request.prototype.send = function(list)
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
					this.fail('AJAX is not supported!');
					return false;
				}
			}
		}
		if(!this.http) { this.fail('Cannot create AJAX object!'); return false }

		//Odno�nik do THIS
		var self = this;

		//Gdy odpowied� jest pobierana
		if(self.loading) self.loading();

		//Kursor
		document.body.style.cursor = 'progress';

		//Gdy zmienia si� status ��dania...
		this.http.onreadystatechange = function()
		{
			if(self.http.readyState == 4)
			{
				try
				{
					if(self.http.status == 200 || self.http.status == 0)
					{
						//Wstaw odpowied�
						self.done(self.http.responseText);

						//Wykonaj znaczniki <script>
						if(self.scripts)
						{
							var script = self.o.getElementsByTagName('script');
							for(var i=0; i<script.length; ++i)
							{
								eval(script[i].innerHTML);
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

	//Otw�rz po��czenie
	this.http.open((this.post || list) ? 'POST' : 'GET', this.url, true);

	//Typ POST
	if(this.post || list)
	{
		if(typeof list == 'object')
		{
			for(var name in list) this.add(name,list[name]); //Dodaj parametry
		}
		var p = this.param.join('&');
		this.http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	}
	else
	{
		var p = null;
	}
	this.http.setRequestHeader('X-Requested-With','XMLHttpRequest');
	this.http.send(p);

	//Usu� zmienne POST
	if(list) this.reset();
};

//Dodaj parametr POST
Request.prototype.add = function(key, val)
{
	this.param.push(encodeURIComponent(key)+'='+encodeURIComponent(val));
	this.post = true;
};

//Reset
Request.prototype.reset = function() { this.param = [] };

//
// *** WY�LIJ FORMULARZ ZA POMOC� AJAX ***
//

//Utw�rz tymczasowy obiekt Request i wy�lij formularz
function send(o,id,opt)
{
	new Request(o.form.action || o.form.baseURI, id, opt).sendForm(o);
	return false;
}

//Wy�lij formularz za pomoc� istniej�cego obiektu Request
//Argumentem jest pole SUBMIT - w zdarzeniu onclick: [obiektRequest].sendForm(this)
Request.prototype.sendForm = function(o)
{
	var el = o.form.elements, x;
	for(var i=0; i<el.length; ++i)
	{
		x = el[i];
		switch(x.type || '')
		{
			case 'radio':
			case 'checkbox':
				if(x.checked) this.add(x.name, x.value || 1); //Radio + Checkbox
				break;
			case 'text':
			case 'textarea':
			case 'hidden':
			case 'password':
				this.add(x.name, x.value); //Text
				break;
			case 'select':
			case 'select-one':
			case 'select-multiple':
				for(var y=0; y<x.options.length; ++y)
				{
					if(x.options[y].selected) this.add(x.name, x.options[y].value) //Select
				}
				break;
		}
	}
	if(o.name) this.add(o.name, o.value);
	this.send();
	this.reset();
	o.disabled = 1;
	return false;
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
	this.title = this.o.appendChild(document.createElement('h3')); //Dialog's title
	this.title.ref = this;
	this.title.innerHTML = '<div class="exit" onclick="parentNode.ref.hide()">x</div>' + title;
	this.body = this.o.appendChild(document.createElement('div')); //Dialog's body

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
Dialog.prototype.load = function(url, post)
{
	new Request(url, this.body, {scripts:1}).send(post);
	this.show();
}

//this instanceof arguments.callee ? 'object instance' : 'function call'