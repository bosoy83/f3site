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
			r.run();
			break;
		case 'number':
			var link = document.getElementsByTagName('link')[0];
			link.href = link.href.slice(0,-5) + x + '.css';
			break;
		default: alert('def');
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
	js.onreadystatechange = js.onload = function()
	{
		if(js.readyState == 'complete') callback();
	}
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
}

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
	//HTML lub tekst
	else
	{
		x.innerHTML+=txt;
	}
	document.body.appendChild(x);
	return x; //Zwróæ element
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

//HTTP
function Request(url, o, showBox)
{
	//Obiekt = ID?
	if(typeof o === 'string') o = id(o);

	//Domyœlne zmienne
	this.o = o;
	this.url = url;
	this.method = 'GET';
	this.eval = false;
	this.timeout = 50000;
	this.reset();

	//Tryb cichy
	if(showBox == undefined)
	{
		this.done = function(x) { o.innerHTML = x };
		this.loading = function() {};
		return;
	}

	//Domyœlnie wyœwietlaj warstwê z obrazkiem podczas ³adowania
	var box = createBox('', '<img src="img/icon/clock.png" alt="" />');

	//Status, zdarzenia: powodzenie, ³adowanie, pora¿ka
	this.st = 0;
	this.done = function(x)
	{
		o.innerHTML = x;
		box.style.visibility = 'hidden';
	};
	this.loading = function()
	{
		if(this.st==0) { hint(box); this.st=1 }
	};
	this.failed = function()
	{
		box.style.visibility = 'hidden'; alert('Error...')
	};
};

//Autostart
function get(url, o, eval)
{
	var obj = new Request(url, o);  if(eval != undefined) obj.eval = true;
	obj.run();
}

//Dodaj parametr POST
Request.prototype.add = function(key, val)
{
	this.p1.push(encodeURIComponent(key)+'='+encodeURIComponent(val)); this.method='POST';
};

//Reset
Request.prototype.reset = function() { this.p1 = new Array() };

//Start
Request.prototype.run = function(x)
{
	this.http=false;
	
	//XMLHttpRequest
	if(window.XMLHttpRequest)
	{
		this.http = new XMLHttpRequest();
		if(this.http.overrideMimeType) this.http.overrideMimeType('text/xml');
	}
	//IE
	else if(window.ActiveXObject)
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
				if(this.errmsg) alert(this.errmsg);
			}
		}
	}
	if(!this.http) { this.failed(); return false }

	//Odnoœnik do THIS
	var self = this;

	//Gdy zmienia siê status ¿¹dania...
	this.http.onreadystatechange = function()
	{
		switch(self.http.readyState)
		{
			case 4:
				self.done(self.http.responseText);

				//Wykonaj znaczniki <script>
				if(self.eval)
				{
					var scripts = self.o.getElementsByTagName('script');
					for(var i=0; i<scripts.length; ++i)
					{
						eval(scripts[i].innerHTML);
					}		 
				}
				break;

			default: self.loading();
		}
	};
	if(this.url!='')
	{
		//Parametry
		if(this.method!='POST')
		{
			this.p = null
		}
		else
		{
			this.p = this.p1.join('&')
		}
		
		//Otwórz po³¹czenie
		this.http.open(this.method,this.url,true);
		
		//POST?
		if(this.method=='POST')
			this.http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		//Wyœlij zapytanie
		this.http.send(this.p);
		
		//Reset
		if(x!=undefined) this.reset();
	}
};