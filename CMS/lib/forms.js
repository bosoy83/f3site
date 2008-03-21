/* Rozszerzenie klasy Request() */

//Przejmij w³adzê nad onSubmit()
Request.prototype.setForm=function(name,rules)
{
	var self=this;	
	document.forms[name].onsubmit=function()
	{
		if(self.sendForm(name,rules)) return true; else return false;
	};
}

//Wy¶lij formularz
Request.prototype.sendForm=function(name,rules)
{
	var form=document.forms[name];
	var elem=form.elements;

	//Warunki
	if(typeof rules!='undefined')
	{
		for(var i in rules)
		{
			if(this.r[i]=='NUM')
			{
				if(typeof this.e[this.r.name]!='number')
				{
					if(this.rt[i]!='') alert(this.rt[i]); //Alert
					this.e[this.r.name].focus();
					return false;
				}
			}
			/* INNE? */
		}
		return true;
	}

	//Zapytanie HTTP
	this.request.method=this.f.method;

	//Dodaj
	for(var i in elem)
	{
		if(elem.type)
		{
			switch(elem.type)
			{
				case 'radio':
					if(elem.checked) this.add(elem.name,elem.value); //Radio
					break;
				case 'checkbox':
					if(elem.checked) this.add(elem.name,elem.value||1); //Checkbox
					break;
				case 'text':
				case 'textarea':
				case 'hidden':
				case 'password':
					this.add(elem.name,elem.value); //Pola tekstowe
					break;
				case 'select':
				case 'select-one':
				case 'select-multiple':
					for(var i in elem.options)
					{
						if(elem.options[i].selected) this.add(elem.name,elem.options[i].value) //Pole wyboru
					}
					break;
			}
		}
	}

	//Wy¶lij
	this.run(1);
}