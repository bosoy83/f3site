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

		# Informacja?
		if($this->info)
		{
			$info  = &$this->info;
			$links = &$this->links;
			include VIEW_DIR.'info.html';
		}

		# Szablon modu�u?
		if($this->data || !$this->info)
		{
			# Utw�rz referencje, aby omin�� $this w szablonach
			foreach(array_keys($this->data) as $key)
			{
				$$key = &$this->data[$key];
			}
			if(!is_array($this->file)) $this->file = array($this->file);

			foreach($this->file as $_)
			{
				# Czy istnieje nowsza wersja �r�d�a?
				if(filemtime('./view/'.$_.'.html') > filemtime(VIEW_DIR.$_.'.html'))
				{
					if(!isset($tc))
					{
						include './lib/compiler.php';
						$tc = new Compiler;
					}
					$tc -> compile($_.'.html');
				}
				# Do��cz plik
				include VIEW_DIR.$_.'.html';
			}
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
		require VIEW_DIR.'message.html';
		exit;
	}
}