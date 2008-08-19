/* Rozszerzenie klasy Request */

HTMLFormElement.prototype.send = function(box, o)
{
	//ID
	if(box == undefined) box = id('main');

	//Obiekt Request i zdarzenia
	var o = new Request(this.action, box);
	o.failed = function() { lock(this, true) };
	o.loading = function() { lock(this) };
	o.done = function(x) { box.innerHTML = x; box.scrollIntoView() };
	return o.sendForm(this);
}

function lock(form, restore)
{
	var submits = form.elements('input');
	for(var i=0; i<submits.length; ++i)
	{
		if(submits[i].type == 'submit')
		{
			submits[i].disabled = (restore === undefined)
		}
	}
}

Request.prototype.sendForm = function(form)
{
	//POST / GET
	this.method = form.method;

	//Elementy
	var elem = form.elements;
	var num = elem.length;

	//Dodaj
	for(var i=0; i<num; ++i)
	{
		if(elem.type == 'undefined') continue;
		switch(elem.type)
		{
			case 'radio':
				if(elem.checked) this.add(elem.name, elem.value); //Radio
				break;
			case 'checkbox':
				if(elem.checked) this.add(elem.name, elem.value||1); //Checkbox
				break;
			case 'text':
			case 'textarea':
			case 'hidden':
			case 'password':
				this.add(elem.name, elem.value); //Pola tekstowe
				break;
			case 'select':
			case 'select-one':
			case 'select-multiple':
				for(var i in elem.options)
				{
					if(elem.options[i].selected) this.add(elem.name, elem.options[i].value) //Pole wyboru
				}
				break;
		}
	}
	alert('tak!'); return false;
	//Wy¶lij
	this.run(1);
}

//Fragment formularza do powielania
function Fragment(box,opt)
{
	var box  = id(box);
	var self = this;
	this.box = box;
	this.num = box.getElementsByTagName(opt.tag).length;
	this.limit = opt.limit || 30;
	this.mode = opt.mode || null;

	//G³êboko¶æ przycisków wzglêdem powtarzanego elementu lub nazwa znacznika
	if(opt.tag == undefined)
		this.depth = opt.depth || 1;
	else
		this.tag = opt.tag;

	//Zdefiniowano kod HTML do powielania?
	if(opt.html != undefined) this.html = opt.html;

	//Zdarzenie onclick dla obszaru powtarzanych elementów
	box.onclick = function(e)
	{
		e = e || event;
		var o = e.srcElement || e.target;
		if(!o.alt) return false;

		//Znajd¼ najbli¿szy znacznik self.tag
		var node = o.parentNode;
		if(self.depth != undefined)
		{
			if(self.depth>1) while(++self.depth>1) { node = node.parentNode }
		}
		else
		{
			while(node.tagName != self.tag && node.parentNode) { node = node.parentNode }
		}

		//Wykryj, który przycisk wci¶niêto
		switch(o.alt)
		{
			case '+':
				if(self.num > self.limit) return false;
				if(self.html)
				{
					var newNode = self.html.cloneNode(true); newNode.style.display = 'block'
				}
				else
				{
					var newNode = node.cloneNode(true);
					if(self.mode == 'clean')
					{
						var list = newNode.getElementsByTagName('input');
						for(var i=0; i<list.length; i++) list[i].value = '';
					}
				}
				node.parentNode.insertBefore(newNode, node);
				self.num++;

				//Aktywuj pierwsze pole INPUT
				var list = newNode.getElementsByTagName('input');
				if(list.length>0) list[0].focus();
				
			break;

			case '-':
				node.parentNode.removeChild(node);
				self.num--;
			break;

			case '^': case 'UP':
				if(node.previousSibling) node.parentNode.insertBefore(node, node.previousSibling);
			break;

			case 'v': case 'DOWN':
				if(node.nextSibling) node.parentNode.insertBefore(node.nextSibling, node);
			break;
		}
		return false; //Nie wysy³aj formularza
	};
};

//Nowy element
Fragment.prototype.addItem = function()
{
	if(this.num > this.limit || this.html == undefined) return false;
	with(this.box.appendChild(this.html.cloneNode(true)))
	{
		style.display = 'block';
		var list = getElementsByTagName('input'); if(list.length>0) list[0].focus();
	}
	this.num++;
}