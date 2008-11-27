//To jest plik przeznaczony do edycji.
//Po zako�czeniu zmian skompresuj go: http://dean.edwards.name/packer

//Otw�rz okno na �rodku ekranu
function okno(url,w,h)
{
	return open(url, '', 'scrollbars=yes,width='+w+',height='+h+',top='+(screen.height-h)/2+',left='+(screen.width-w)/2)
}

//Zmie� CSS
function CSS(x)
{
	switch(typeof x)
	{
		case 'undefined':
			var box = dialog('Skin', '', {style:'width:250px;height:150px'}); hint(box);
			new Request('request.php?co=css', box.text).send();
			break;
		case 'number':
			var link = document.getElementsByTagName('link')[0];
			link.href = link.href.slice(0,-5) + x + '.css';
			break;
	}
}

//Do��cz plik JS
function include(file)
{
	var head = document.getElementsByTagName('head')[0];
	var js = document.createElement('script');
	js.type = 'text/javascript';
	js.src = file;
	head.appendChild(js);
	return false;
}

//Szybki dost�p do element�w po ID
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
	date.setTime(time = (expires*3600000) + date.getTime()); //expires = ilo�� godzin
	var time = date.toGMTString();
	document.cookie = name + '=' + escape(txt) + '; expires=' + time;
}

//Poka� lub ukryj
function show(o, once)
{
	if(typeof o == 'string') o = id(o);
	var x = o.style;
	if(x.display=='none') x.display='block'; else if(once==undefined) x.display='none'
}

//Kursor
var cx,cy;
var toHide = [];

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
	for(var i in toHide)
	{
		toHide[i].style.visibility = 'hidden';
		toHide.pop(toHide[i]);
	}
};

//Utw�rz menu lub warstw�
function dialog(title, txt, opt)
{
	//Utw�rz
	var x = document.createElement('div');
	x.className = 'hint';

	//Tytu�
	x.innerHTML = '<div class="title"><span onclick="hint(parentNode.parentNode)">X</span>'+title+'</div>';

	//Menu
	if(typeof txt=='object')
	{
		var v = '';
		for(var i=0; i<txt.length; i++)
		{
			v += '<li onclick="'+txt[i]+'">'+txt[++i]+'</li>';
		}
		x.innerHTML += '<ul class="menulist">'+v+'</ul>';
	}
	//Zawarto�� tekstowa
	else
	{
		x.text = x.appendChild(document.createElement('div'));
		x.text.className = 'win';
		x.text.innerHTML = txt;
	}
	
	//Opcje
	if(opt) for(var i in opt) x.text.setAttribute(i,opt[i]);

	//Zwr�� element
	return document.body.appendChild(x);
}

//Hint
function hint(o, left, top, autoHide)
{
	if(typeof o == 'string')
	{
		o = id(o);
	}
	if(o.style.visibility != 'visible')
	{
		//Na �rodku?
		if(left == undefined)
		{
			left = (document.documentElement.clientWidth - o.clientWidth) / 2;
			top  = (document.documentElement.clientHeight - o.clientHeight) / 2;
		}
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

//��danie AJAX - domy�lne warto�ci opcji
//alert(this instanceof arguments.callee ? 'object instance' : 'function call')
function Request(url, box, opt)
{
	opt = opt || {};
	this.o = box || id('main'); //Gdzie wstawi� odpowied�?
	this.url = url;
	this.post = opt.post || false; //Typ POST - domy�lnie false (typ GET)
	this.param = []; //Parametry POST
	//this.silent = opt.silent || true; //true = tryb cichy
	this.scripts = opt.scripts || false; //true = wykonaj skrypty JS
	//this.timeout = opt.timeout || 50000; //Czas oczekiwania na odpowied�
	this.loading = opt.loading || null; //Gdy odpowied� jest pobierana
	this.fail = opt.fail || function(x) { alert(x) }; //Gdy ��danie nie powiod�o si�
	this.done = opt.done || function(x) { this.o.innerHTML = x } //Gdy ��danie zako�czone sukcesem
}

//Wy�lij ��danie
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
					if(self.http.status == 200)
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