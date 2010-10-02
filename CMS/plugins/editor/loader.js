function Editor(O, bbcode)
{
	var that = this;
	if(!Editor.loaded)
	{
		include('plugins/editor/tiny_mce.js');
		Editor.loaded = true;
	}
	var init = function()
	{
		if(!O.id) O.id = O.name;
		that.o = tinymce.add(new tinymce.Editor(O.id, {

			//Global settings
			dialog_type: 'modal',
			gecko_spellcheck: true,
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

			//Plugins
			plugins: 'inlinepopups,safari,table,fullscreen,media,emotions,contextmenu,searchreplace,autoresize',

			//Buttons
			theme_advanced_buttons1: 'formatselect,fontselect,fontsizeselect,table,image,media,charmap,emotions,|,replace,|,fullscreen,code,help,|,undo,redo',

			theme_advanced_buttons2: 'cut,copy,paste,removeformat,|,bold,italic,underline,strikethrough,|,sub,sup,|,justifyleft,justifycenter,justifyright,justifyfull,|,numlist,bullist,|,blockquote,link,|,forecolor,backcolor',

			theme_advanced_buttons3: ''
		}));
		that.o.render();
	};

	//If DOM ready, load editor, else add event
	if(Editor.DOM) setTimeout(init,500);
	else addEvent('load', function() { init(); Editor.DOM = true })
}

Editor.loaded = Editor.DOM = false;
Editor.prototype.emots = function() {};
Editor.prototype.protect = function() {};
Editor.prototype.preview = function(opt,where,text) {
	if(this.box == undefined && !where)
	{
		this.box = document.createElement('div');
		this.box.className = 'preview';
		this.o.getElement().form.parentNode.insertBefore(this.box, this.o.getElement().form)
	}
	this.box.innerHTML = this.o.getContent();
	this.box.scrollIntoView()
};