//Kolejność: Położenie ikony [px], BBCode, koniec BBCode, HTML, koniec HTML
var tags = [
	[0, '[b]', '[/b]', '<b>', '</b>'],
	[-22, '[i]', '[/i]', '<i>', '</i>'],
	[-44, '[u]', '[/u]', '<u>', '</u>'],
	[-66, '[big]', '[/big]', '<big>', '</big>'],
	[-88, '[small]', '[/small]', '<small>', '</small>'],
	[-110, '== ', ' ==', '<h3>', '</h3>'],
	[-132, '[sub]', '[/sub]', '<sub>', '</sub>'],
	[-154, '[sup]', '[/sup]', '<sup>', '</sup>'],
	[-176, '[center]', '[/center]', '<div style="text-align: center">', '</div>'],
	[-198, '[right]', '[/right]', '<div style="text-align: right">', '</div>'],
	[-220, '[quote]', '[/quote]', '<blockquote>', '</blockquote>'],
	[-242, '[code]', '[/code]', '<pre>', '</pre>'],
	[-264],
	[-286],
	[-308],
	[-330],
	[-352],
	[-374],
],

tagNum = 17, //Liczba wszystkich tagów

//Znaki specjalne
symbol = [
	'°', '§', '¤', '÷', '×', '&frac12;',
	'Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü',
	'®', '©', '™', '•', '&lt;', '&gt;',
	'«', '»', '&lArr;', '&rArr;', '·', 'µ',
	'&#945;', '&#946;', '&#947;', '&#949;', '&#916;', '&#937;',
	'€', '&hearts;', '‰', '&#9835;', '†'
],

//Kolory
color = [
	'white', '#c9c9c9', 'yellow', 'orange', 'red', '#9de9f9', '#7eebaa', 'teal',
	'black', 'gray', 'olive', 'gold', 'brown', 'blue', 'green', 'navy'
],

//Dla paneli znaki + kolory występujących 1 raz
eO, eCurBBC, eColors, eChars;

//Konstruktor
function Editor(o, usebbcode)
{
	this.o = o;
	this.bbcode = usebbcode;
	this.create();
}

//Utwórz edytor
Editor.prototype.create = function()
{
	var that = this, IE,
	out = document.createElement('div');
	out.className = 'editor';

	//Detect IE 6,7
	with(navigator.userAgent)
	{
		IE = (indexOf('MSIE') > 0 && charAt(indexOf('MSIE')+5) < 8)
	}

	for(var i=0; i<tagNum; i++)
	{
		if(this.bbcode && tags[i] && tags[i][1] === false) continue;
		var b = document.createElement('span');
		if(IE) { b.style.display = 'block'; b.style.styleFloat = 'left' }
		b.style.backgroundPosition = 'center ' + tags[i][0] + 'px';
		b.style.padding = '3px 12px';
		b.item = i;
		b.title = tips[i];
		b.onclick = function() { that.format(this.item); };
		out.appendChild(b)
	}
	this.o.parentNode.insertBefore(out,this.o);

	//Skróty klawiszowe
	this.o.onkeydown = function(e)
	{
		if(e == undefined) e = event;
		if(e.ctrlKey && !e.altKey)
		{
			switch(e.keyCode)
			{
				case 66: that.format(0); break; //B
				case 73: that.format(1); break; //I
				case 85: that.format(2); break; //U
				case 81: that.format(10); break; //Q
				case 76: that.format(14); break; //L
				case 72: that.format(5); break; //H
				case 13: BBC(this, document.documentElement.baseURI ? '<br />\n' : '<br>\n', ''); break; //BR
				case 80: if(!that.bbcode) BBC(this, '<p>', '</p>'); break; //P
				default: return true
			}
			return false
		}
	};
};

//Chroń przed wyjściem
Editor.prototype.protect = function(text)
{
	var self = this;
	onbeforeunload = function()
	{
		if(self.o.value != 0)
		{
			if(e) e.returnValue = text||lang.leave;
			return text||lang.leave;
		}
	};
	addEvent('submit', function() { onbeforeunload = null }, this.o.form)
};

//Wstaw znacznik
Editor.prototype.format = function(i)
{
	//Standardowe tagi
	if(tags[i][1])
	{
		if(this.bbcode)
			BBC(this.o, tags[i][1], tags[i][2]);
		else
			BBC(this.o, tags[i][3], tags[i][4]);
	}
	//Nieopisane w tablicy `tags`
	else switch(i)
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
			this.link();
			break;
		case 15:
			this.mail();
			break;
		case 16:
			var a = prompt(lang.img);
			if(a)
				if(this.bbcode)
					BBC(this.o, '[url]', '[/url]\n', a);
				else
					BBC(this.o,'<img src="','" />');
	}
};

//Emoty
Editor.prototype.emots = function(x)
{
	//Wykryj
	var exist = this.emo ? 1 : 0;

	//Usuń
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
		return
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
		img.src = 'img/emo/'+emots[i][1];
		img.alt = emots[i][2];
		img.width = 16;
		img.title = emots[i][0];
		img.onclick = function() { BBC(that, '', '', this.alt); };
		out.appendChild(img)
	}
	this.emo = this.o.parentNode.insertBefore(out, this.o.nextSibling)
};

//Zbuduj tabelę symboli
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
		var num = symbol.length;
		for(var i=0; i<num; ++i)
		{
			if(y==1) var tr = document.createElement('tr');

			var td = document.createElement('td');
			td.style.padding = '5px';
			td.onclick = function() { BBC(eO, this.innerHTML, '') };
			td.innerHTML = symbol[i];

			tr.appendChild(td);

			if(y==6) { tb.appendChild(tr); y=1 } else { ++y }
		}

		//Cudzysłowy
		var td = document.createElement('td');
		td.onclick = function() { BBC(eO, '„', '”') };
		td.innerHTML = '„ ”';
		tr.appendChild(td);
		tb.appendChild(tr);
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
					BBC(eO, '[color='+this.item+']', '[/color]')
				}
				else
				{
					BBC(eO, '<span style="color: '+this.item+'">', '</span>')
				}
			};
			tr.appendChild(td);

			if(y==8 || z==ile) { tb.appendChild(tr); y=1 } else { ++y; } ++z
		}
		eCurBBC = this.bbcode;
		eColors = out
	}
	t.appendChild(tb);
	out.appendChild(t);
	document.body.appendChild(out)
};

Editor.prototype.link = function()
{
	var url = prompt(lang.adr, 'http://');
	if(url && url != 'http://')
	{
		if(this.o.selectionStart != this.o.selectionEnd)
		{
			var title = ''
		}
		else
		{
			var input = prompt(lang.adr2), title = input ? input : url
		}
		if(this.bbcode)
		{
			BBC(this.o, (title == url) ? '[url]' : '[url='+url+']', '[/url]', title)
		}
		else
		{
			BBC(this.o, '<a href="'+encodeURI(url)+'">', '</a>', title)
		}
	}
	else this.o.focus()
};

Editor.prototype.mail = function()
{
	if(this.o.selectionStart != this.o.selectionEnd)
	{
		var input = undefined
	}
	else
	{
		var input = prompt(lang.mail)
	}
	if(input != '')
	{
		if(this.bbcode)
		{
			BBC(this.o, '[mail]', '[/mail]', input)
		}
		else
		{
			BBC(this.o, '<a href="mailto:'+input+'">', '</a>', input)
		}
	}
	else this.o.focus()
};

//Podgląd
Editor.prototype.preview = function(opt,where,text)
{
	//Tekst
	if(text == undefined) text = this.o.value;

	//DIV
	if(this.box == undefined && !where)
	{
		this.box = document.createElement('div');
		this.box.className = 'preview';
		this.o.form.parentNode.insertBefore(this.box, this.o.form)
	}

	//Brak opcji
	if(opt == undefined) opt = {NL:1};

	//Nowe linie
	this.box.style.whiteSpace = (opt.NL != undefined && opt.NL == false) ? '' : 'pre-wrap';

	//HTML
	if(this.bbcode)
	{
		text = text.replace(/&/g, '&amp;');
		text = text.replace(/</g, '&lt;');
		text = text.replace(/>/g, '&gt;')
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

	//Wyświetl
	this.o.focus();
	this.box.innerHTML = text;
	this.box.scrollIntoView()
};