//Create new CHATROOM
function Chat(formID, out)
{
	//Form onsubmit action
	var self = this;
	this.form = document.forms[formID];
	this.form.onsubmit = function() { self.post(); return false };

	//Input field
	this.input = this.form.txt;

	//Output box - may be table or div
	this.output = out || $('chatout');

	//Topic box
	//this.topicBox = $('chatTopic');

	//Scrolled chat box - div recommended
	this.chatBox = this.output.parentNode;

	//Chatroom name
	this.room = 'main';

	//Disallow HTML
	this.HTML = false;

	//Your nickname
	this.nick = 'testNICK';

	//Default nickname - if not specified
	this.anonym = '';

	//Last date
	this.lastTime = '';

	//Default tags
	this.oneTag = 'tr';
	this.timeTag = this.nickTag = this.msgTag = 'td';

	//Default classes
	this.timeClass = 'chatTime';
	this.nickClass = 'chatNick';
	this.msgClass  = 'chatMsg';

	//AJAX request object
	this.http = new Request('request.php?co=chat');

	//Response handler
	this.http.done = function(x)
	{
		//TODO: native JSON parser in js.js
		if(window.JSON) x = JSON.parse(x); else x = eval(x);
		for(var i in x)
		{
			self.insert(x[i]);
		}
	};

	//If failed
	this.http.fail = function(x) { alert(x) };

	//Input ENTER
	this.input.onkeydown = function(e)
	{
		if(e == undefined) e = event;
		if(e.keyCode == 13 && !e.shiftKey) { this.form.onsubmit(); return false }
	};

	//Autofocus input
	this.input.focus();

	//Interval in seconds
	this.interval = 5;
}

//Post a message
Chat.prototype.post = function()
{
	if(this.input.value == '') return false;
	this.http.send({msg: this.input.value});
	this.insert({msg: this.purify(this.input.value), nick: this.nick})
};

//Change nickname
Chat.prototype.setNick = function(x)
{
	this.nick = x
};

//Add message onto the board
Chat.prototype.insert = function(x)
{
	var one = document.createElement(this.oneTag),
	msg = document.createElement(this.msgTag),
	time = document.createElement(this.timeTag),
	nick = document.createElement(this.nickTag);

	//Message
	msg.className = this.msgClass;
	msg.innerHTML = x.msg || '';

	//Date object
	var date = x.date ? new Date(x.date) : new Date;
	var date = date.toLocaleTimeString().substr(0,5);

	//Do not show the same date
	if(this.lastTime == date) date = ''; else this.lastTime = date;

	//Post time
	time.className = this.timeClass;
	time.innerHTML = date;

	//Nickname
	nick.className = this.nickClass;
	nick.innerHTML = x.uid>0 ? this.profile(x.nick) : (x.nick || x.anonym);

	//Check if output box should be scrolled down
	var height = this.chatBox.offsetHeight + this.chatBox.scrollTop;
	var scroll = this.chatBox.scrollHeight < height;
	
	//Place the message
	one.appendChild(time);
	one.appendChild(nick);
	one.appendChild(msg);
	this.output.appendChild(one);
	this.input.value = '';

	//Scroll down the output box
	if(scroll) this.chatBox.scrollTop = this.chatBox.scrollHeight;
};

//Purify text - no HTML
Chat.prototype.purify = function(x)
{
	x = x.replace(/&/g, '&amp;');
	x = x.replace(/</g, '&lt;');
	return x.replace(/>/g, '&gt;');
};

//Generate link to profile
Chat.prototype.profile = function(nick, uid)
{
	return '<a href="user/' + encodeURI(nick) + '">' + nick + '</a>'
};