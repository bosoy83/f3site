function Editor(O, bbcode)
{
	try
	{
		if(!O.id) O.id = 'tiny' + Editor.num++;
		if(window.tinyMCE)
		{
			tinyMCE.execCommand('mceAddControl', false, O.id)
		}
		else
		{
			Editor.list.push(O.id);
			Editor.got || this.load()
		}
		this.id = O.id
	}
	catch(e)
	{
		if(confirm('Cannot load TinyMCE editor. Show error?')) alert(e)
	}
}

//ID list and unused functions
Editor.got = false;
Editor.num = 1;
Editor.list = [];
Editor.prototype.emots = function() {};
Editor.prototype.protect = function() {};

//Init TinyMCE
Editor.prototype.load = function()
{
	Editor.got = true;
	include('plugins/editor/tiny_mce.js', function() { var C = document.styleSheets[0].href; tinyMCE.init({

		//Global settings
		mode: 'exact',
		elements: Editor.list.join(),
		dialog_type: 'modal',
		language: document.documentElement.lang || 'en',
		gecko_spellcheck: true,
		strict_loading_mode: true,
		content_css: C.substring(0,C.lastIndexOf('/')) + '/tiny.css',
		document_base_url: document.baseURI,

		//Entities - UTF-8 needs only critical characters
		entities: '160,nbsp,38,amp,60,lt,62,gt',

		//Theme
		theme: 'advanced',
		theme_advanced_resizing: true,
		theme_advanced_toolbar_location: 'top',
		theme_advanced_toolbar_align: 'left',
		theme_advanced_statusbar_location: 'bottom',

		//Formats
		theme_advanced_blockformats: 'p,div,h3,h4,blockquote,dt,dd,code,samp',

		//No horizontal resizing
		theme_advanced_resize_horizontal : false,

		//Plugins
		plugins: 'inlinepopups,safari,table,media,emotions,contextmenu',

		//Buttons
		theme_advanced_buttons1: 'formatselect,fontselect,fontsizeselect,table,image,media,charmap,emotions,code,help,undo,redo',

		theme_advanced_buttons2: 'cut,copy,paste,removeformat,bold,italic,underline,strikethrough,sub,sup,justifyleft,justifycenter,justifyright,justifyfull,numlist,bullist,blockquote,link,forecolor,backcolor',

		theme_advanced_buttons3: ''
	})});
}

//Custom preview method
Editor.prototype.preview = function(opt,where,text) {
	var e = tinyMCE.editors[this.id];
	if(this.box == undefined && !where)
	{
		this.box = document.createElement('div');
		this.box.className = 'preview';
		e.getElement().form.parentNode.insertBefore(this.box, e.getElement().form)
	}
	this.box.innerHTML = e.getContent();
	this.box.scrollIntoView()
};