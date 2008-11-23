//To jest plik przeznaczony do edycji.
//Po zakoñczeniu zmian skompresuj go: http://dean.edwards.name/packer

//Otwórz okno na œrodku ekranu
function okno(url,w,h)
{
	return open(url, '', 'scrollbars=yes,width='+w+',height='+h+',top='+(screen.height-h)/2+',left='+(screen.width-w)/2)
}

//Zmieñ CSS
function CSS(x)
{
	switch(typeof x)
	{
		case 'undefined':
			var r = new Request('request.php?co=css');
			r.done = function(x) { var box = createBox('', x); hint(box) };
			r.send();
			break;
		case 'number':
			var link = document.getElementsByTagName('link')[0];
			link.href = link.href.slice(0,-5) + x + '.css';
			break;
	}
}

//Do³¹cz plik JS
function include(file, callback)
{
	var head = document.getElementsByTagName('head')[0];
	var js = document.createElement('script');
	js.setAttribute('type', 'text/javascript');
	js.setAttribute('src', file);
	head.appendChild(js);

	//Gotowy do u¿ycia?
	if(callback == undefined) return true;
	js.onreadystatechange = function()
	{
		if(js.readyState == 'complete') callback();
	};
	js.onload = callback;
	return false;
}

//Szybki dostêp do elementów po ID
function id(x) { return document.getElementById(x) }

//Wstaw kod
function BBC(o, left, right, inside)
{
	if(typeof o.selectionStart === 'number')
	{
		var start  = o.selectionStart;
		var end    = o.selectionEnd;
		var scroll = o.scrollTop;
		var before = o.value.substring(0, start);
		var after  = o.value.substring(end, o.textLength);
		var inside = (inside) ? inside : o.value.substring(start, end);

		o.value = before + left + inside + right + after;
		o.selectionEnd = before.length + left.length + inside.length;
		o.scrollTop = scroll;
		o.focus();
	}
	else { o.value += left + (inside||'') + right; }
}

//Ustaw cookie
function setCookie(name, txt, expires)
{
	var date = new Date();
	date.setTime(time = (expires*3600000) + date.getTime()); //expires = iloœæ godzin
	var time = date.toGMTString();
	document.cookie = name + '=' + escape(txt) + '; expires=' + time;
}

//Poka¿ lub ukryj
function show(o, once)
{
	if(typeof o == 'string') o = id(o);
	var x = o.style;
	if(x.display=='none') x.display='block'; else if(once==undefined) x.display='none'
}

//Kursor
var cx,cy;
var toHide = new Array();

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
		cx = event.clientX + document.body.scrollLeft;
		cy = event.clientY + document.body.scrollTop
	}
	if(cx<0) cx=0;
	if(cy<0) cy=0
};
document.onclick = function()
{
	for(var i in toHide) { hint(toHide[i],0,0,1) }
};

//Utwórz menu lub warstwê
function createBox(title, txt)
{
	//Utwórz
	var x=document.createElement('div');
	x.className='hint';
	x.style.padding='8px';
	
	//Tytu³
	if(title) x.innerHTML='<div class="title">'+title+'</div>';

	//Menu
	if(typeof txt=='array')
	{
		var v;
		for(var i in txt)
		{
			v+='<li onclick="'+txt[i][1]+'">'+txt[i][0]+'</li>';
		}
		x.innerHTML+='<ul class="menulist">'+v+'</ul>';
	}
	else
	{
		x.innerHTML+=txt; //HTML lub tekst
	}
	return document.body.appendChild(x); //Zwróæ element
}

//Hint
function hint(o, l, t, autoHide)
{
	if(typeof o == 'string')
	{
		o = id(o);
	}
	var style = o.style;
	if(style.visibility != 'visible')
	{
		//Na œrodku?
		if(l == undefined)
		{
			l = (document.documentElement.clientWidth - o.clientWidth) / 2;
			t = (document.documentElement.clientHeight - o.clientHeight) / 2;
		}
		if(t!=0)
		{
			style.left = l + 'px';
			style.top  = t + 'px'
		}
		style.visibility = 'visible';
		if(autoHide == 1) setTimeout(function() { toHide.push(o) }, 10);
	}
	else if(autoHide == 1)
	{
		toHide.pop(o);
		style.visibility = 'hidden';
	}
}

//¯¹danie AJAX - domyœlne wartoœci opcji
//alert(this instanceof arguments.callee ? 'object instance' : 'function call')
function Request(url, box, opt)
{
	opt = opt || {};
	this.o = box || id('main'); //Gdzie wstawiæ odpowiedŸ?
	this.url = url;
	this.post = opt.post || false; //Typ POST - domyœlnie false (typ GET)
	this.param = []; //Parametry POST
	this.silent = opt.silent || true; //true = tryb cichy
	this.scripts = opt.scripts || false; //true = wykonaj skrypty JS
	this.timeout = opt.timeout || 50000; //Czas oczekiwania na odpowiedŸ
	this.loading = opt.loading || null; //Gdy odpowiedŸ jest pobierana
	this.fail = opt.fail || function(x) { alert(x) }; //Gdy ¿¹danie nie powiod³o siê
	this.done = opt.done || function(x) { this.o.innerHTML = x } //Gdy ¿¹danie zakoñczone sukcesem
}

//Wyœlij ¿¹danie
Request.prototype.send = function(list)
{
	if(!this.http)
	{
		if(window.XMLHttpRequest) //XMLHttpRequest
		{
			this.http = new XMLHttpRequest();
			if(this.http.overrideMimeType) this.http.overrideMimeType('text/xml');
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
		if(!this.silent)
		{
			//Domyœlnie wyœwietlaj warstwê z obrazkiem podczas ³adowania
			var box = createBox('', '<img src="img/icon/clock.png" alt="" />');
		}

		//Odnoœnik do THIS
		var self = this;

		//Gdy odpowiedŸ jest pobierana
		if(self.loading) self.loading();

		//Kursor
		document.body.style.cursor = 'progress';

		//Gdy zmienia siê status ¿¹dania...
		this.http.onreadystatechange = function()
		{
			if(self.http.readyState == 4)
			{
				try
				{
					if(self.http.status == 200)
					{
						//Wstaw odpowiedŸ
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

	//Otwórz po³¹czenie
	this.http.open(this.post ? 'POST' : 'GET', this.url, true);

	//Typ POST
	if(this.post)
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
	this.http.send(p);

	//Usuñ zmienne POST
	if(list) this.reset();
}

//Dodaj parametr POST
Request.prototype.add = function(key, val)
{
	this.param.push(encodeURIComponent(key)+'='+encodeURIComponent(val));
	this.post = true;
};

//Reset
Request.prototype.reset = function() { this.param = [] };