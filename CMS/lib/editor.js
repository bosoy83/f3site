/*Include CSS
var objC=document.createElement('link');
objC.setAttribute('href',eCSS);
objC.setAttribute('type','text/css');
objC.setAttribute('rel','stylesheet');
document.getElementsByTagName('head')[0].appendChild(objC); */

//Obrazki .png (pierwsze 5 - tekst, 0 = przerwa)
var eIMG=[
	'<b style="margin: 0 3px">B</b>',
	'<em style="margin: 0 4px"><b>I</b></em>',
	'<u style="margin: 0 3px">U</u>',
	'BIG',
	'<small>small</small>',
	'sub','sup','center','right','color',0,
	'char','img','www','mail','quote','code','php'];

//Kod (BBCode, HTML, zamkn. HTML)
var eTags=[
	['b','b','b'],
	['i','em','em'],
	['u','u','u'],
	['big','big','big'],
	['small','small','small'],
	['d','sub','sub'],
	['g','sup','sup'],
	['center','center','center'],
	['right','div align="right"','div'],
	0, '-', 0, 0, 0, 0,
	['quote','div class="quote"','div'],
	['code','div class="code"><code>','code></div'],
	0];

var eChar=['amp','reg','copy','trade','sect','deg','middot','bull','lt','gt','raquo'];

var eColor=['white','#c9c9c9','yellow','orange','red','#9de9f9','#7eebaa','teal','black','gray','olive','gold','brown','blue','green','navy'];

//Dla paneli (znaki + kolory) wystêpuj¹cych 1 raz
//Nie zmieniaj wartoœci tych zmiennych
var eCurID='';
var eCurBBC=0;
var eCharList=0;

//Konstruktor
function Editor(id,hid)
{
	this.on=1;
	this.bbcode=0;
	this.id=id;
	if(hid==undefined) var hid='';
	this.create(hid);
}

Editor.prototype.Format=function(i)
{
	if(this.on!=1) return 0;

	//Domyœlnie
	if(eTags[i]!=0)
	{
		if(this.bbcode==1) BBC(this.id,'['+eTags[i][0]+']','[/'+eTags[i][0]+']');
		else BBC(this.id,'<'+eTags[i][1]+'>','</'+eTags[i][2]+'>')
	}
	//Inne
	else
	{
		switch(i)
		{
			case 11:
				if(eCharList==0) this.Make('chars');
				eCurID=this.id;
				Hint('chars',cx-10,cy,1);
				break;
			case 9:
				if(eCurBBC==0) this.Make('colors');
				eCurID=this.id;
				eCurBBC=this.bbcode;
				Hint('colors',cx-10,cy,1);
				break;
			case 13:
				this.Ins('link');
				break;
			case 14:
				this.Ins('mail');
				break;
			case 12:
				if(this.bbcode==1) {
					var a=prompt(eLang2.img); if(a) BBC(this.id,'[img]','[/img]',a) }
				else
					BBC(this.id,'<img src="','" />');
				break;
		}
	}
}

//Utwórz edytor
Editor.prototype.create=function(hidden)
{
	var out=document.createElement('div');
	out.className='editor';
	var th=this;

	for(var i in eIMG)
	{
		if(hidden.indexOf(':'+eIMG[i])==-1)
		{
			if(eIMG[i]==0) var b=document.createTextNode(' ');
			else
			{
				if(i>4)
				{
					var b=document.createElement('img');
					b.src='./img/editor/'+eIMG[i]+'.png';
					b.alt=eIMG[i];
				}
				else
				{
					var b=document.createElement('span');
					b.innerHTML=eIMG[i];
					b.style.verticalAlign='middle';
				}
				b.item=parseInt(i);
				b.title=eLang[i];
				b.onclick=function() { th.Format(this.item); }
			}
			out.appendChild(b);
		}
	}
	d(this.id).parentNode.insertBefore(out,d(this.id));
}

//Emoty
Editor.prototype.Emots=function(x)
{
	//Wykryj
	var ex=d('elist'+this.id)?1:0;

	//Usuñ?
	if(x==0)
	{
		if(ex) d(this.id).parentNode.removeChild(d('elist'+this.id));
		return;
	}

	//Jeœli ju¿ istniej¹ - zakoñcz
	if(ex) return;

	//Wstaw
	var out=document.createElement('div');
	out.className='editor emots';
	out.id='elist'+this.id
	var th=this;

	for(var i in emots)
	{
		var b=document.createElement('img');
		b.src='./img/emo/'+emots[i][1];
		b.title=emots[i][0];
		b.alt=emots[i][2];
		b.onclick=function() { BBC(th.id,'','',this.alt); }
		out.appendChild(b);
	}
	d(this.id).parentNode.insertBefore(out,d(this.id).nextSibling)
}

//Zbuduj tabelê symboli
Editor.prototype.Make=function(co)
{
	var out=document.createElement('div');
	out.className='hint';
	out.id=co;

	var t=document.createElement('table');
	t.style.cursor='pointer';
	t.cellspacing=0;

	var tb=document.createElement('tbody');
	tb.align='center';

	var y=1;
	
	//Kolory
	if(co=='chars')
	{
		for(var i in eChar)
		{
			if(y==1) var tr=document.createElement('tr');

			var td=document.createElement('td');
			td.item=eChar[i];
			td.style.padding='5px';
			td.onclick=function() { BBC(eCurID,'&'+this.item+';','') }
			td.innerHTML='&'+eChar[i]+';';

			tr.appendChild(td);

			if(y==4) { tb.appendChild(tr); y=1 } else { ++y }
		}

		//Cudzys³owy
		var td=document.createElement('td');
		td.onclick=function() { BBC(eCurID,'&ldquo;','&rdquo;') }
		td.innerHTML='&bdquo; &rdquo;';
		tr.appendChild(td);
		tb.appendChild(tr);

		eCharList=1;
	}
	else
	{
		var ile=eColor.length;
		var i2=1;

		for(var i in eColor)
		{
			if(y==1) var tr=document.createElement('tr');

			var td=document.createElement('td');
			td.style.backgroundColor=eColor[i];
			td.style.padding='12px';//innerHTML='&nbsp;&nbsp;&nbsp;';
			td.item=eColor[i];
			td.onclick=function()
			{
				if(eCurBBC==1)
				{
					BBC(eCurID,'[color='+this.item+']','[/color]');
				}
				else
				{
					BBC(eCurID,'<span style="color: '+this.item+'">','</span>');
				}
			}
			tr.appendChild(td);

			if(y==8 || i2==ile) { tb.appendChild(tr); y=1 } else { ++y; } ++i2
		}
		eCurBBC=this.bbcode;
	}
	t.appendChild(tb);
	out.appendChild(t);
	document.body.appendChild(out);
};

Editor.prototype.Ins=function(co)
{
	if(co=='link')
	{
		var a=prompt(eLang2.adr,'http://');
		if(a && a!='http://')
		{
			var b=prompt(eLang2.adr2);
			if(b)
			{
				if(this.bbcode==1) BBC(this.id,'[url='+a+']'+b,'[/url]');
				else BBC(this.id,'<a href="'+a+'">'+b,'</a>')
			}
			else
			{
				if(this.bbcode==1) BBC(this.id,'[url]'+a,'[/url]');
				else BBC(this.id,'<a href="'+a+'">'+a,'</a>')
			}
		}
	}
	else
	{
		var a=prompt(eLang2.mail);
		if(a)
		{
			a.replace('@','&#64;');
			if(this.bbcode==1) BBC(this.id,'[mail]'+a,'[/mail]');
			else BBC(this.id,'<a href="m&#97;ilto:'+a+'">'+a,'</a>')
		}
	}
};

Editor.prototype.Rows=function(h)
{
	d(this.id).rows=h;
}