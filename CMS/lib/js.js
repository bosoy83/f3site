//To jest plik przeznaczony do edycji.
//Po zakoñczeniu zmian skompresuj go: http://dean.edwards.name/packer

//Otwórz okno na œrodku ekranu
function Okno(url,w,h)
{
	window.open(url,'','toolbar=yes,scrollbars=yes,personalbar=no,directories=no,width='+w+',height='+h+',top='+(screen.height-h)/2+',left='+(screen.width-w)/2)
}

//Szybki dostêp do elementów po ID
function d(id) { return document.getElementById(id) }

//Wstaw kod
function BBC(t,x,y,a2)
{
	var f=d(t);
	if(a2==undefined) var a2='';
	if((typeof f.selectionStart)!='undefined') {
		var s=f.selectionStart;
		var k=f.selectionEnd;
		var ost=f.scrollTop;
		var a1=(f.value).substring(0,s);
		if(a2=='') var a2=(f.value).substring(s,k);
		var a3=(f.value).substring(k,f.textLength);
		f.value=a1+x+a2+y+a3;
		f.selectionEnd=(a1+x+a2).length;
		f.scrollTop=ost;
		f.focus();
	}
	else { f.value+=x+a2+y; }
}

//Ustaw cookie
function setCookie(n,txt,c)
{
	var t=new Date();
	t.setTime(cz=(c*60*60*1000)+t.getTime());
	var cz=(t.toGMTString());
	document.cookie=n+'='+escape(txt)+'; expires='+cz;
}

//Poka¿ lub ukryj
function Show(c,h)
{
	with(d(c).style)
	{
		if(display=='none') display=''; else if(h==undefined) display='none'
	}
}

//Kursor
var cx,cy;
var toHide=new Array();
var IE=(document.all)?1:0;

//Mysz
function XY(e)
{
	if(IE)
	{
		cx=event.clientX + document.body.scrollLeft;
		cy=event.clientY + document.body.scrollTop
	}
	else
	{
		cx=e.pageX;
		cy=e.pageY
	}
	if(cx<0) cx=0;
	if(cy<0) cy=0
}
document.onmousedown=XY;
document.onclick=function()
{
	for(var i in toHide) { Hint(toHide[i],0,0,1) }
}

//Utwórz menu lub panel
function Panel(id,t,txt)
{
	//Istnieje?
	if(d(id)!=null) return id;

	//Utwórz
	var x=document.createElement('div');
	x.id=id;
	x.className='hint';
	x.style.padding='8px';
	
	//Tytu³
	if(t) x.innerHTML='<div class="title">'+this.title+'</div>';

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
function Hint(id,l,t,u)
{
	with(d(id).style)
	{
		if(display!='block')
		{
			//Na œrodku?
			if(l==undefined)
			{
				var l=(document.documentElement.clientWidth+document.documentElement.scrollLeft-width)/2;
				var t=(document.documentElement.clientHeight+document.documentElement.scrollTop-height)/2;
			}
			if(t!=0) { left=l+'px'; top=t+'px' }
			display='block';
			if(u==1) setTimeout('toHide.push("'+id+'")',10);
		}
		else
		{
			if(u==1) toHide.pop(id);
			display='none';
		}
	}
}

//HTTP
function Request(u,id,a)
{
	this.url=u;
	this.method='GET';
	//this.timeout=50000;
	this.reset();
	if(id!=undefined) this.setID(id,a);
}

//Auto
Request.prototype.setID=function(id,a)
{
	//Tryb cichy
	if(a==undefined)
	{
		this.Done=function(x) { d(id).innerHTML=x; document.body.style.cursor='' };
		this.Loading=function() { document.body.style.cursor='wait' };
		this.Failed=function() { window.status='Error...' };
	}
	//Hint
	else
	{
		var h='h'+id;
		this.st=0;
		Panel(h,'','<img src="img/icon/clock.png" alt="" /> '+a);
		this.Done=function(x) { d(id).innerHTML=x; Show(h) };
		this.Loading=function() { if(this.st==0) { Hint(h); this.st=1 } };
		this.Failed=function(){ Show(h); alert('Error...') };
	}
};

//Parametr
Request.prototype.add=function(a,b)
{
	this.p1.push(encodeURIComponent(a)+'='+encodeURIComponent(b)); this.method='POST';
};

//Reset
Request.prototype.reset=function() { this.p1=new Array() };

//Start
Request.prototype.run=function(x)
{
	this.http=false;
	
	//XMLHttpRequest
	if(window.XMLHttpRequest)
	{
		this.http=new XMLHttpRequest();
		if(this.http.overrideMimeType) this.http.overrideMimeType('text/xml');
	}
	//IE
	else if(window.ActiveXObject)
	{
		try
		{
			this.http=new ActiveXObject("Msxml2.XMLHTTP")
		}
		catch(e)
		{
			try
			{
				this.http=new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch(e)
			{
				if(this.errmsg) alert(this.errmsg);
			}
		}
	}
	if(!this.http) { this.Failed(); return false }

	//Odnoœnik do THIS
	var self=this;
	
	//Gdy zmienia siê status ¿¹dania...
	this.http.onreadystatechange=function()
	{
		switch(self.http.readyState) {
			case 4: self.Done(self.http.responseText); break;
			default: self.Loading();
		}
	};
	if(this.url!='')
	{
		//Parametry
		if(this.method!='POST')
		{
			this.p=null
		}
		else
		{
			this.p=this.p1.join('&')
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