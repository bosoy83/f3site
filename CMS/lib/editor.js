//Przyciski - pierwsze 6 s¹ tekstem, zaœ pozosta³e grafik¹ .png
var button = [
	'<b style="margin: 0 3px">B</b>',
	'<i style="margin: 0 4px">I</i>', //1
	'<u style="margin: 0 3px">U</u>',
	'BIG',
	'<small>small</small>', //4
  ' H ',
  'sub',
	'sup',
	'center', //8
	'right',
	'quote',
	'code',
	'color', //12
  'char',
	'www',
	'mail',
	'img', //16
],

//Podwójne tablice powoduj¹ niewielki spadek wydajnoœci w stosunku do pojedynczych,
//ale ich przemierzanie w pêtli FOR jest znacznie szybsze od przemierzania obiektów {},
//gdy¿ nie trzeba u¿ywaæ konstrukcji `var i in tags`
//Podwójne tablice zosta³y u¿yte w celu ³atwiejszej edycji znaczników

//Kolejnoœæ: BBCode, pocz¹tek HTML, koniec HTML
tags = [
	['b', '<b>', '</b>'],
	['i', '<i>', '</i>'],
	['u', '<u>', '</u>'],
	['big', '<big>', '</big>'],
	['small', '<small>', '</small>'], //4
	['', '<h3>', '</h3>'],
	['sub', '<sub>', '</sub>'],
	['sup', '<sup>', '</sup>'],
	['center', '<div style="text-align: center">', '</div>'], //8
	['right', '<div style="text-align: right">', '</div>'],
	['quote', '<blockquote>', '</blockquote>'],
	['code', '<pre>', '</pre>'] //11
],

tagNum = 17, //Liczba wszystkich tagów - tak¿e spoza tablicy `tags`
iso = 12, //Liczba znaków specjalnych w kodowaniu ISO - dozwolone w BBCode

//Symbole: BBCode, HTML, po œrodku / po lewej, po prawej
symbol = [
	'°', '§', '¤', '÷', '×', 'ß',
	'Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü',
	'&reg;', '&copy;', '&trade;', '&bull;', '&lt;', '&gt;',
	'&frac12;', '&raquo;', '&lArr;', '&rArr;', '&middot;'
],

//Kolory
color = [
	'white', '#c9c9c9', 'yellow', 'orange', 'red', '#9de9f9', '#7eebaa', 'teal',
	'black', 'gray', 'olive', 'gold', 'brown', 'blue', 'green', 'navy'
],

//Dla paneli znaki + kolory wystêpuj¹cych 1 raz
eO, eCurBBC, eColors, eChars;

//Konstruktor
function Editor(o, usebbcode)
{
	this.o = o;
	this.bbcode = usebbcode;
	this.create();
}

//Wstaw znacznik
Editor.prototype.format = function(i)
{
	//Standardowe tagi
	if(tags[i])
	{
		if(this.bbcode)
			BBC(this.o, '['+tags[i][0]+']', '[/'+tags[i][0]+']');
		else
			BBC(this.o, tags[i][1], tags[i][2])
	}
	//Nieopisane w tablicy `tags`
	else
	{
		switch(i)
		{
			case 13:
				if(!eChars) this.make(1);
				eO = this.o;
				hint(eChars, cx-60, cy, 1);
				break;
			case 12:
				if(!eColors) this.make();
				eO = this.o;
				eCurBBC = this.bbcode;
				hint(eColors, cx-90, cy, 1);
				break;
			case 14:
				this.ins('link');
				break;
			case 15:
				this.ins('mail');
				break;
			case 16:
				var a = prompt(lang.img);
				if(a)
					if(this.bbcode)
						BBC(this.o, '[url]', '[/url]\n', a);
					else
						BBC(this.o,'<img src="','" />');
		}
	}
};

//Utwórz edytor
Editor.prototype.create = function()
{
	var that = this,
	out = document.createElement('div');
	out.className = 'editor';

	for(var i=0; i<tagNum; i++)
	{
		if(this.bbcode && tags[i] && !tags[i][0]) continue;
		if(i > 5)
		{
			var b = document.createElement('img');
			b.src = 'img/editor/'+button[i]+'.png';
		}
		else
		{
			var b = document.createElement('span');
			b.innerHTML = button[i];
			b.style.verticalAlign = 'middle';
		}
		b.item = i;
		b.title = tips[i];
		b.width = 16;
		b.onclick = function() { that.format(this.item); };
		out.appendChild(b);
	}
	this.o.parentNode.insertBefore(out,this.o);

	//Skróty klawiszowe
	this.o.onkeydown = function(e)
	{
		if(e == undefined) e = event;
		if(e.ctrlKey)
		{
			switch(e.keyCode)
			{
				case 66: that.format(0); break; //B
				case 73: that.format(1); break; //I
				case 85: that.format(2); break; //U
				case 81: that.format(10); break; //Q
				case 87: that.format(14); break; //W
				case 72: if(!that.bbcode) BBC(this, '<h3>', '</h3>'); break; //H
				case 80: if(!that.bbcode) BBC(this, '<p>', '</p>'); break; //P
				default: return true;
			}
			return false;
		}
	};
};

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
	else if(x && x==0) return;

	//Wstaw
	var that = this.o,
	num = emots.length,
	out = document.createElement('div');
	out.className = 'editor emots';

	for(var i=0; i<num; ++i)
	{
		var img = document.createElement('img');
		img.src = './img/emo/'+emots[i][1];
		img.alt = emots[i][2];
		img.width = 16;
		img.title = emots[i][0];
		img.onclick = function() { BBC(that, '', '', this.alt); };
		out.appendChild(img);
	}
	this.emo = this.o.parentNode.insertBefore(out, this.o.nextSibling)
};

//Zbuduj tabelê symboli
Editor.prototype.make = function(buildChars)
{
	var out = document.createElement('div');
	out.className = 'hint';

	var t = document.createElement('table');
	t.style.cursor = 'pointer';
	t.cellspacing = 0;

	var y = 1,
	tb = document.createElement('tbody');
	tb.align = 'center';

	//Kolory
	if(buildChars)
	{
		var num = (this.bbcode) ? iso : symbol.length;
		for(var i=0; i<num; ++i)
		{
			if(y==1) var tr = document.createElement('tr');

			var td = document.createElement('td');
			td.item = symbol[i];
			td.style.padding = '5px';
			td.onclick = function() { BBC(eO, this.item, '') };
			td.innerHTML = symbol[i];

			tr.appendChild(td);

			if(y==6) { tb.appendChild(tr); y=1 } else { ++y }
		}

		//Cudzys³owy
		if(!this.bbcode)
		{
			var td = document.createElement('td');
			td.onclick = function() { BBC(eO, '<q>', '</q>') };
			td.innerHTML = '&bdquo; &rdquo;';
			tr.appendChild(td);
			tb.appendChild(tr);
		}
		eChars = out;
	}
	else
	{
		var ile = color.length,
		z = 1;

		for(var i=0; i<ile; ++i)
		{
			if(y==1) var tr = document.createElement('tr');

			var td = document.createElement('td');
			td.style.backgroundColor = color[i];
			td.width = td.height = 20;
			td.item = color[i];
			td.onclick = function()
			{
				if(eCurBBC)
				{
					BBC(eO, '[color='+this.item+']', '[/color]');
				}
				else
				{
					BBC(eO, '<span style="color: '+this.item+'">', '</span>');
				}
			};
			tr.appendChild(td);

			if(y==8 || z==ile) { tb.appendChild(tr); y=1 } else { ++y; } ++z
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
	if(co == 'link')
	{
		var a = prompt(lang.adr, 'http://');
		if(a && a != 'http://')
		{
			var b = prompt(lang.adr2);
			if(b)
			{
				if(this.bbcode) BBC(this.o, '[url='+a+']'+b, '[/url]');
				else BBC(this.o, '<a href="'+a+'">'+b, '</a>')
			}
			else
			{
				if(this.bbcode) BBC(this.o, '[url]'+a, '[/url]');
				else BBC(this.o, '<a href="'+a+'">'+a, '</a>')
			}
		}
	}
	else
	{
		var a = prompt(lang.mail);
		if(a)
		{
			if(this.bbcode) BBC(this.o, '[mail]'+a, '[/mail]');
			else BBC(this.o, '<a href="mailto:'+a+'">'+a, '</a>')
		}
	}
};

//Podgl¹d
Editor.prototype.preview = function(opt,where,text)
{
	//Tekst
	if(text == undefined) text = this.o.value;

	//DIV
	if(this.box == undefined)
	{
		if(where)
		{
			this.box = where.getElementsByTagName('div')[0];
		}
		else
		{
			this.box = document.createElement('div');
			this.box.className = 'box';
			this.o.form.parentNode.insertBefore(this.box, this.o.form);
		}
	}

	//Brak opcji?
	if(opt == undefined) opt = {NL:1};

	//Nowe linie
	this.box.style.whiteSpace = (opt.NL != undefined && opt.NL == false) ? '' : 'pre-wrap';

	//HTML
	if(this.bbcode)
	{
		text = text.replace(/&/g, '&amp;');
		text = text.replace(/</g, '&lt;');
		text = text.replace(/>/g, '&gt;');
	}

	//Emotikony
	if(opt.EMOTS)
	{
		for(var i in emots)
		{
			while(text.indexOf(emots[i][2]) !== -1 && emots[i][2] != '')
			{
				text = text.replace(emots[i][2],'<img src="img/emo/'+emots[i][1]+'" />');
			}
		}
	}

	//Wyœwietl
	this.box.innerHTML = text;
	this.box.scrollIntoView()
};