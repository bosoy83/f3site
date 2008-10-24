<?php /* Klasa szablonów */
class Content
{
	public
		$file,
		$title,
		$head,
		$data = array(),
		$check = true,
		$cache = VIEW_DIR,
		$dir = SKIN_DIR;

	#Wy¶wietl szablony
	function display()
	{
		#Udostêpnij jêzyk
		global $lang;

		#Informacja
		if(isset($this->info))
		{
			$info  = $this->info;
			$links = $this->links;
			include $this->path('info', 1);
			if(!$this->data) return;
		}

		#Utwórz referencje, aby omin±æ $this w szablonach
		foreach(array_keys($this->data) as $key)
		{
			$$key = &$this->data[$key];
		}

		#Kompiluj i wy¶wietl
		if(is_array($this->file))
		{
			foreach($this->file as $f)
			{
				include $this->path($f);
			}
		}
		else
		{
			include $this->path($this->file);
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
		$this->title = $GLOBALS['lang']['404'];
		$this->file = '404';
	}

	#Komunikat lub b³±d
	function message($info, $link=null)
	{
		if($info === (int)$info)
		{
			require LANG_DIR.'special.php';
			$info = $lang[$info];
		}
		global $lang;
		require $this->path('message', 1);
		exit;
	}

	#Kompiluj szablon
	function path($file, $sys=null)
	{
		if(!$sys && file_exists($this->dir . $file . '.html'))
		{
			$path  = $this->dir;
			$cache = $this->cache;
		}
		elseif(file_exists(SKIN_DIR . $file . '.html'))
		{
			$path = SKIN_DIR;
			$cache = VIEW_DIR;
		}
		else
		{
			exit('Cannot find template: '.$file.'.html');
		}

		#Sprawd¼ datê modyfikacji
		if($this->check && filemtime($path . $file . '.html') > @filemtime($cache. $file . '.html'))
		{
			static $compiler;
			if(!isset($compiler))
			{
				include_once './lib/compiler.php';
				$compiler = new Compiler;
			}
			$compiler -> compile($file, $path, $cache);
		}

		return $cache . $file . '.html';
	}
}