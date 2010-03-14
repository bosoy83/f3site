var oTag  = $('tags'), oWin, oHttp, inputs = [], allTags = {},

//Add floating DIV element
oEdit = document.createElement('div');
oEdit.className = 'tags';
oEdit.style.cssFloat = 'right';
oEdit.style.cursor = 'pointer';
oEdit.innerHTML = oTag.innerHTML == 0 ? lang.add : lang.edit;

//Show dialog window if user clicks DIV
oEdit.onclick = function()
{
	if(!oWin)
	{
		oWin = new Dialog(lang.tags, '', 420, 380);
		oHttp = new Request(URLS[0], oWin.body, {done: function(tags)
		{
			//Parse JSON code
			tags = getJSON(tags);

			//Change AJAX behavior
			oHttp.done = function(x)
			{
				x = getJSON(x);
				oTag.innerHTML = '';
				oTag.insertBefore(oEdit, oTag.firstChild);

				//Build tags from scratch
				for(var i in x)
				{
					var a = document.createElement('a');
					a.href = URLS[1] + x[i];
					a.className = 'tags';
					a.innerHTML = x[i] + ' ['+(allTags[x[i]] ? allTags[x[i]] : '1')+'] ';
					oTag.appendChild(a)
				}
			};

			//Build checkboxes
			for(var i=0, len=tags.length; i<len; i++)
			{
				allTags[tags[i][0]] = tags[i][2];
				createTag(tags[i][0], tags[i][1], tags[i][2], 1)
			}

			//New tag
			var newTag = createTag(lang.add + '...', 0);
			newTag.onclick = function()
			{
				var txt = prompt(lang.add);
				if(txt) createTag(txt, 1, 1, 1, this.parentNode.parentNode);
				this.checked = 0
			};

			//Build ADD button
			input = document.createElement('button');
			input.style.display = 'block';
			input.style.margin = '15px auto';
			input.innerHTML = '<b>OK</b>';
			input.onclick = function()
			{
				var i, post = [];
				for(var i=0, len=inputs.length; i<len; i++)
				{
					if(inputs[i].checked) post.push(inputs[i].name)
				}
				oWin.hide();
				oHttp.post(post)
			};
			oWin.body.appendChild(input);
		}});
		oHttp.get()
	}
	oWin.show()
};

//Insert in the beginning of parent DIV
oTag.insertBefore(oEdit, oTag.firstChild);

//Add tag
function createTag(name, checked, num, toIndex, before)
{
	var div = document.createElement('div'),
	label = document.createElement('label'),
	input = document.createElement('input');
	input.type = 'checkbox';
	input.checked = checked;
	input.name = name;
	label.appendChild(input);
	label.appendChild(document.createTextNode(num ? name+' ('+num+')' : name));
	div.style.display = 'inline-block';
	div.style.width = '50%';
	div.appendChild(label);

	if(toIndex) inputs.push(input);
	if(before) oWin.body.insertBefore(div, before); else oWin.body.appendChild(div);
	return input;
}