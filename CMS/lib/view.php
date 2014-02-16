<?php /* Template system */
class View
{
	public
		$title,
		$desc,
		$nav,
		$check = true,
		$cache = VIEW_DIR,
		$dir = SKIN_DIR;
	private
		$head,
		$file,
		$data = array();

	#Add template and assign variables
	function add($file, $data=array())
	{
		$this->file[] = $file;
		$this->data += $data;
	}
	
	#Display templates
	function display()
	{
		#Share language
		global $lang;

		#Info texts
		if(isset($this->info))
		{
			$info = $this->info;
			include $this->path('info', 1);
			if(!$this->data) return;
		}

		#Create references to omit $this in templates
		foreach(array_keys($this->data) as $key)
		{
			$$key = &$this->data[$key];
		}

		#Compile and output
		if($this->file)
		{
			foreach($this->file as $f)
			{
				include $this->path($f);
			}
		}
		else
		{
			include $this->path('404');
		}
	}

	#Display main template
	function front($file = 'body', $data = null)
	{
		global $lang,$cfg,$user;

		#Additional data
		if($data) extract($data);

		#Set 410 Gone header if page does not exist
		if(empty($this->file))
		{
			$this->title = $lang['404'];
			header('Gone', true, 410);
		}
		include $this->path($file,1);
	}

	#Add information
	function info($text, $links=array(), $class='info')
	{
		$this->info[] = array('text' => $text, 'links' => $links, 'class' => $class);
	}

	#Add stylesheet
	function css($file)
	{
		if(!strpos($file, '/'))
		{
			$file = SKIN_DIR.$file;
		}
		$this->head .= '<link rel="stylesheet" type="text/css" href="'.$file.'">';
	}

	#Add JavaScript file
	function script($file)
	{
		$this->head .= '<script src="'.$file.'"></script>';
	}

	#Add RSS channel
	function rss($file, $title=null)
	{
		if(!is_array($file))
		{
			$file = array($file => $title);
		}
		foreach($file as $f=>$t)
		{
			$this->head .= '<link rel="alternate" type="application/rss+xml" href="'.$f.'" title="'.$t.'">';
		}
	}

	#Show message or error
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

	#Compile template
	function path($file, $sys=null)
	{
		if(!$sys && file_exists($this->dir . $file . '.html'))
		{
			$path  = $this->dir;
			$cache = $this->cache;
		}
		elseif(file_exists(SKIN_DIR . $file . '.html'))
		{
			$path  = SKIN_DIR;
			$cache = VIEW_DIR;
		}
		elseif(file_exists('style/system/' . $file . '.html'))
		{
			$path  = './style/system/';
			$cache = './cache/system/';
		}
		else
		{
			exit('Cannot find template: '.$file.'.html');
		}

		#Check modification date
		if($this->check && filemtime($path . $file . '.html') > @filemtime($cache. $file . '.html'))
		{
			static $compiler;
			if(!isset($compiler))
			{
				include_once './lib/compiler.php';
				$compiler = new Compiler;
			}
			try
			{
				$compiler->compile($file, $path, $cache);
			}
			catch(Exception $e)
			{
				exit($e->getMessage());
			}
		}
		return $cache . $file . '.html';
	}
}