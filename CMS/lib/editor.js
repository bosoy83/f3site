/*Include CSS
var objC=document.createElement('link');
objC.setAttribute('href',eCSS);
objC.setAttribute('type','text/css');
objC.setAttribute('rel','stylesheet');
document.getElementsByTagName('head')[0].appendChild(objC); */

//Obrazki .png (pierwsze 5 - tekst, 0 = przerwa)
var eIMG = [
	'<b style="margin: 0 3px">B</b>',
	'<em style="margin: 0 4px"><b>I</b></em>',
	'<u style="margin: 0 3px">U</u>',
	'BIG',
	'<small>small</small>',
	'sub','sup','center','right','color',
	'char','img','www','mail','quote','code'];

//Kod (BBCode, HTML, zamkn. HTML)
var eTags = [
	['b','b','b'],
	['i','em','em'],
	['u','u','u'],
	['big','big','big'],
	['small','small','small'],
	['d','sub','sub'],
	['g','sup','sup'],
	['center','center','center'],
	['right','div align="right"','div'],
	0, 0, 0, 0, 0,
	['quote','blockquote','blockquote'],
	['code','pre','pre']
];

var eChar = ['amp','reg','copy','trade','sect','deg','middot','bull','lt','gt','raquo'];

var eColor = ['white','#c9c9c9','yellow','orange','red','#9de9f9','#7eebaa','teal','black','gray','olive','gold','brown','blue','green','navy'];

//Dla paneli (znaki + kolory) wystêpuj¹cych 1 raz
//Nie zmieniaj wartoœci tych zmiennych
var eO = '';
var eCurBBC = 0;
var eColors = 0;
var eCharList = 0;

//Konstruktor
function Editor(o, hiddenTags)
{
	this.on = 1;
	this.bbcode = 0;
	this.o = o;
	this.create( (hiddenTags==undefined) ? '' : hiddenTags );
}

Editor.prototype.format = function(i)
{
	if(this.on!=1) return 0;

	//Domyœlnie
	if(eTags[i]!=0)
	{
		if(this.bbcode==1)
			BBC(this.o, '['+eTags[i][0]+']', '[/'+eTags[i][0]+']');
		else
			BBC(this.o, '<'+eTags[i][1]+'>', '</'+eTags[i][2]+'>')
	}
	//Inne
	else
	{
		switch(i)
		{
			case 10:
				if(eCharList==0) this.make('chars');
				eO = this.o;
				hint(eCharList, cx-10, cy, 1);
				break;
			case 9:
				if(eColors==0) this.make('colors');
				eO = this.o;
				eCurBBC = this.bbcode;
				hint(eColors, cx-10, cy, 1);
				break;
			case 12:
				this.ins('link');
				break;
			case 13:
				this.ins('mail');
				break;
			case 11:
				if(this.bbcode==1) {
					var a = prompt(eLang2.img); if(a) BBC(this.o, '[img]', '[/img]', a) }
				else
					BBC(this.o,'<img src="','" />');
				break;
		}
	}
}

//Utwórz edytor
Editor.prototype.create = function(hidden)
{
	var out = document.createElement('div');
	out.className = 'editor';
	var that = this;

	for(var i in eIMG)
	{
		if(typeof hidden[eIMG[i]] == 'string') continue;
		if(i > 4)
		{
			var b = document.createElement('img');
			b.src = './img/editor/'+eIMG[i]+'.png';
			b.alt = eIMG[i];
		}
		else
		{
			var b = document.createElement('span');
			b.innerHTML = eIMG[i];
			b.style.verticalAlign = 'middle';
		}
		b.item  = parseInt(i);
		b.title = eLang[i];
		b.width = 16;
		b.onclick = function() { that.format(this.item); }
		out.appendChild(b);
	}
	this.o.parentNode.insertBefore(out,this.o);

	//Skróty klawiszowe
	this.o.onkeypress = function(e)
	{
		if(e == undefined) e = event;
		if(e.ctrlKey)
		{
			switch(e.charCode)
			{
				case 98: that.format(0); break; //B
				case 105: that.format(1); break; //I
				case 117: that.format(2); break; //U
				case 113: that.format(14); break; //Q
				case 119: that.format(12); break; //W
				case 104: if(!that.bbcode) BBC(this,'<h3>','</h3>'); break; //H
				case 112: if(!that.bbcode) BBC(this,'<p>','</p>'); break; //P
				default: return true;
			}
			return false;
		}
	}
}

//Emoty
Editor.prototype.emots = function(x)
{
	//Wykryj
	var exist = this.emo ? 1 : 0;

	//Usuñ
	if(this.emo)
	{
		if(x == false)
		{
			this.emo.style.display = 'none'
		}
		else
		{
			this.emo.style.display = 'block'
		}
		return;
	}
	else if(!x) return;

	//Wstaw
	var out = document.createElement('div');
	out.className = 'editor emots';
	var that = this.o;

	for(var i in emots)
	{
		var img = document.createElement('img');
		img.src = './img/emo/'+emots[i][1];
		img.alt = emots[i][2];
		img.width = 16;
		img.title = emots[i][0];
		img.onclick = function() { BBC(that,'','',this.alt); }
		out.appendChild(img);
	}
	this.emo = this.o.parentNode.insertBefore(out, this.o.nextSibling)
}

//Zbuduj tabelê symboli
Editor.prototype.make = function(co)
{
	var out = document.createElement('div');
	out.className = 'hint';
	out.id = co;

	var t = document.createElement('table');
	t.style.cursor = 'pointer';
	t.cellspacing = 0;

	var tb = document.createElement('tbody');
	tb.align = 'center';

	var y = 1;
	
	//Kolory
	if(co=='chars')
	{
		for(var i in eChar)
		{
			if(y==1) var tr = document.createElement('tr');

			var td = document.createElement('td');
			td.item = eChar[i];
			td.style.padding = '5px';
			td.onclick = function() { BBC(eO,'&'+this.item+';','') }
			td.innerHTML = '&'+eChar[i]+';';

			tr.appendChild(td);

			if(y==4) { tb.appendChild(tr); y=1 } else { ++y }
		}

		//Cudzys³owy
		var td = document.createElement('td');
		td.onclick = function() { BBC(eO,'&ldquo;','&rdquo;') }
		td.innerHTML = '&bdquo; &rdquo;';
		tr.appendChild(td);
		tb.appendChild(tr);

		eCharList = out;
	}
	else
	{
		var ile = eColor.length;
		var i2 = 1;

		for(var i in eColor)
		{
			if(y==1) var tr = document.createElement('tr');

			var td = document.createElement('td');
			td.style.backgroundColor = eColor[i];
			td.style.padding = '12px';
			td.item = eColor[i];
			td.onclick = function()
			{
				if(eCurBBC==1)
				{
					BBC(eO,'[color='+this.item+']','[/color]');
				}
				else
				{
					BBC(eO,'<span style="color: '+this.item+'">','</span>');
				}
			}
			tr.appendChild(td);

			if(y==8 || i2==ile) { tb.appendChild(tr); y=1 } else { ++y; } ++i2
		}
		eCurBBC = this.bbcode;
		eColors = out;
	}
	t.appendChild(tb);
	out.appendChild(t);
	document.body.appendChild(out);
};

Editor.prototype.ins = function(co)
{
	if(co=='link')
	{
		var a = prompt(eLang2.adr, 'http://');
		if(a && a!='http://')
		{
			var b = prompt(eLang2.adr2);
			if(b)
			{
				if(this.bbcode==1) BBC(this.o, '[url='+a+']'+b, '[/url]');
				else BBC(this.o, '<a href="'+a+'">'+b, '</a>')
			}
			else
			{
				if(this.bbcode==1) BBC(this.o, '[url]'+a, '[/url]');
				else BBC(this.o, '<a href="'+a+'">'+a, '</a>')
			}
		}
	}
	else
	{
		var a = prompt(eLang2.mail);
		if(a)
		{
			a.replace('@','&#64;');
			if(this.bbcode==1) BBC(this.o, '[mail]'+a, '[/mail]');
			else BBC(this.o, '<a href="m&#97;ilto:'+a+'">'+a, '</a>')
		}
	}
};