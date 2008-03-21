<?php /* Klasa szablon�w */
class Content
{
	public
		$file,
		$title,
		$head=null,
		$data=array();
	protected
		$info=null;

	# Wy�wietl g��wny szablon
	function display()
	{
		# Udost�pnij j�zyk
		$lang = &$GLOBALS['lang'];

		/* -- Na razie wy��czamy --
		# Czy istnieje nowsza wersja �r�d�a?
		if(filemtime(HTML_DIR.$this->file.'.html') > @filemtime(STYLE_DIR.$this->file.'.php'))
		{
			include('./lib/compiler.php');
			$tc = new Compiler;
			$tc -> examine();
		}
		-- */

		# Informacja?
		if($this->info)
		{
			$info  = &$this->info;
			$links = &$this->links;
			include STYLE_DIR.'info.php';
		}

		# Szablon modu�u?
		if($this->data || !$this->info)
		{
			# Utw�rz referencje, aby omin�� $this w szablonach
			foreach(array_keys($this->data) as $key)
			{
				$$key = &$this->data[$key];
			}

			# Do��cz plik
			include STYLE_DIR.$this->file.'.php';
		}
	}

	# Ustaw dane
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

	# Informacja
	function info($text, $links=null)
	{
		$this->info = $text;
		$this->links = $links;
	}

	# Dodaj plik CSS
	function addCSS($file)
	{
		$this->head .= '<link rel="stylesheet" type="text/css" href="'.$file.'" />';
	}

	# Dodaj skrypt
	function addScript($file, $type='text/javascript')
	{
		$this->head .= '<script type="'.$type.'" src="'.$file.'"></script>';
	}

	# 404
	function set404()
	{
		$this->file = '404';
	}

	# Komunikat lub b��d (gdy $notify = 1, powiadom admina)
	function message($info, $link=null, $notify=null)
	{
		if($info === (int)$info)
		{
			require LANG_DIR.'special.php';  $info =& $lang['s'.$info];
		}
		$lang =& $GLOBALS['lang'];
		require STYLE_DIR.'message.php';
		exit;
	}
}
?>