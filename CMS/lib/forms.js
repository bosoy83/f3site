/* Mened�er plik�w */

var FM = null;
var FMWin = null;

//Otw�rz mened�er plik�w
function fileman(folder, input)
{
	if(FMWin && !FMWin.closed)

		FMWin.focus();

	else if(input)
	{
		FMWin = okno('fileman.php?dir='+folder, 580, 400, 150, 150);
		window.onunload = function() { FMWin.close() }
	}
	FM = input;
}

//
// *** WY�LIJ FORMULARZ ZA POMOC� AJAX ***
//

//Utw�rz tymczasowy obiekt Request i wy�lij formularz
function send(o,id,opt)
{
	new Request(o.form.action, id, opt).sendForm(o);
	return false;
}

//Wy�lij formularz za pomoc� istniej�cego obiektu Request
//Argumentem jest pole SUBMIT - w zdarzeniu onclick: [obiektRequest].sendForm(this)
Request.prototype.sendForm = function(o)
{
	var param = {}, el = o.form.elements, x;
	for(var i=0; i<el.length; ++i)
	{
		x = el[i];
		switch(x.type || '')
		{
			case 'radio':
			case 'checkbox':
				if(x.checked) this.add(x.name, x.value || 1); //Radio
				break;
			case 'text':
			case 'textarea':
			case 'hidden':
			case 'password':
				this.add(x.name, x.value); //Text field
				break;
			case 'select':
			case 'select-one':
			case 'select-multiple':
				for(var y=0; y<x.options.length; ++y)
				{
					if(x.options[y].selected) this.add(x.name, x.options[y].value) //Select field
				}
				break;
		}
	}
	if(o.name) this.add(o.name, o.value);
	this.send();
	o.disabled = 1;
	return false;
};

//
// *** Fragment formularza do powielania ***
//
function Fragment(box,opt)
{
	var box  = id(box);
	var self = this;

	//Element, w kt�rym znajduj� si� fragmenty
	this.box = box;

	//Lista fragment�w
	this.nodes = box.getElementsByTagName(opt.tag || 'div');
	
	//Aktualna ilo�� fragment�w
	this.num = this.nodes.length;

	//Maksymalna ilo�� fragment�w
	this.limit = opt.limit || 30;

	//Znacznik aktywowany po wykonaniu akcji
	this.focus = opt.focus || 'input'; 

	//Tryby: clean - czy�ci pola po skopiowaniu fragmentu
	this.mode = opt.mode || null;

	//Kod HTML, kt�ry nale�y wstawi� po klikni�ciu "Dodaj fragment"
	if(opt.html != undefined) this.html = opt.html;

	//Przesuwanie fragment�w za pomoc� klawiatury - CTRL + ...
	box.onkeydown = function(e)
	{
		e = e || event;
		var o = e.srcElement || e.target;
		if(!e.ctrlKey) return true;

		//Rodzicem przycisk�w jest element fragmentu
		var node = o.parentNode;

		switch(e.keyCode)
		{
			case 38: self.up(node); break;
			case 40: self.down(node); break;
			default: return true;
		}
		return false;
	};

	//Zdarzenie onclick dla obszaru powtarzanych element�w
	box.onclick = function(e)
	{
		e = e || event;
		var o = e.srcElement || e.target;
		if(!o.alt) return false;

		//Rodzicem przycisk�w jest element fragmentu
		var node = o.parentNode;

		//Wykryj, kt�ry przycisk wci�ni�to
		switch(o.alt)
		{
			case '+': self.copy(node); break;
			case '-': self.del(node);  break;
			case '^': case 'UP': self.up(node); break;
			case 'v': case 'DOWN': self.down(node); break;
			default: return true;
		}
		return false; //Nie wysy�aj formularza
	};
};

Fragment.prototype = {

	//Kopiuj fragment lub dodaj nowy
	copy: function(node)
	{
		if(this.num > this.limit) return false;
		if(this.html)
		{
			var newNode = this.html.cloneNode(true);
			newNode.style.display = 'block'
		}
		else
		{
			var newNode = node.cloneNode(true);
			if(this.mode == 'clean')
			{
				var list = newNode.getElementsByTagName('input');
				for(var i=0; i<list.length; i++) list[i].value = '';
			}
		}
		node.parentNode.insertBefore(newNode, node);
		this.num++;

		//Aktywuj pierwsze pole INPUT
		this.act(newNode);
	},

	//Usu� fragment i uaktywnij s�siada
	del: function(node)
	{
		if(node.nextSibling)
		{
			this.act(node.nextSibling);
		}
		else if(node.previousSibling)
		{
			this.act(node.previousSibling);
		}
		node.parentNode.removeChild(node);
		this.num--;
	},

	//Przesu� w g�r�
	up: function(node)
	{
		if(node.previousSibling)
		{
			node.parentNode.insertBefore(node, node.previousSibling);
		}
	},

	//Przesu� w d�
	down: function(node)
	{
		if(node.nextSibling)
		{
			node.parentNode.insertBefore(node.nextSibling, node);
		}
	},

	//Nowy element
	addItem: function()
	{
		if(this.num > this.limit || this.html == undefined) return false;
		var o = this.box.appendChild(this.html.cloneNode(true));
		o.style.display = 'block';
		this.act(o);
		this.num++;
	},

	//Uaktywnij pierwszy element we fragmencie
	act: function(node)
	{
		var list = node.getElementsByTagName(this.focus);
		if(list.length>0) list[0].focus();
	}

}