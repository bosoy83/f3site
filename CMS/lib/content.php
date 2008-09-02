<?php /* Klasa szablonów */
class Content
{
	public
		$file,
		$title,
		$head,
		$data = array(),
		$check = true,
		$cacheDir = VIEW_DIR,
		$dir = SKIN_DIR;

	#Wy¶wietl g³ówny szablon
	function display()
	{
		#Udostêpnij jêzyk
		$lang = &$GLOBALS['lang'];

		#Informacja
		if(isset($this->info))
		{
			$info  = $this->info;
			$links = $this->links;
			include VIEW_DIR.'info.html';
			if(!$this->data) return;
		}

		#Utwórz referencje, aby omin±æ $this w szablonach
		foreach(array_keys($this->data) as $key)
		{
			$$key = &$this->data[$key];
		}
		if(!is_array($this->file)) $this->file = array($this->file);

		foreach($this->file as $F)
		{
			#Czy istnieje nowsza wersja ¼ród³a?
			$this->check && $this->compile($F.'.html');

			#Do³±cz plik
			include $this->cacheDir.$F.'.html';
		}
	}

	#Ustaw dane
	function set($key, &$array=null)
	{
		if($array !== null)
		{
			$this->data[$key] =& $array;
		}
		elseif(is_array($key))
		{
			$this->data += $key;
		}
	}

	#Informacja
	function info($text, $links=null)
	{
		$this->info = $text;
		$this->links = $links;
	}

	#Dodaj plik CSS
	function addCSS($file)
	{
		$this->head .= '<link rel="stylesheet" type="text/css" href="'.$file.'" />';
	}

	#Dodaj skrypt
	function addScript($file, $type='text/javascript')
	{
		$this->head .= '<script type="'.$type.'" src="'.$file.'"></script>';
	}

	#404
	function set404()
	{
		$this->file = '404';
	}

	#Komunikat lub b³±d (gdy $notify = 1, powiadom admina)
	function message($info, $link=null, $notify=null)
	{
		if($info === (int)$info)
		{
			require LANG_DIR.'special.php';  $info = $lang['s'.$info];
		}
		$lang =& $GLOBALS['lang'];
		require VIEW_DIR.'message.html';
		exit;
	}

	#Kompiluj szablon
	function compile($x)
	{
		static $compiler;

		if(!isset($compiler))
		{
			include './lib/compiler.php';
			$compiler = new Compiler;
		}
		if(filemtime($this->dir.$x) > @filemtime($this->cacheDir.$x))
		{
			$compiler -> compile($x, $this->dir, $this->cacheDir);
		}
	}
}