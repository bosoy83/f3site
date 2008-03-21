<?php
/*
# Wymaga kompilacji?
		if(!file_exists('./cache/tpl/'.$this->file) || filemtime($this->dir.$this->file) > filemtime('./cache/tpl/'.$this->file))
		{
			require './lib/compiler.php';
			$com = new Compiler;
			try
			{
				$com -> compile($this->file);
			}
			catch(Exception $e)
			{
				return 'Template error: '.$e->getMessage();
			}
		}*/
class Compiler
{
	protected
		$data,
		$replace1,
		$replace2;
	public
		$removePHP = false, //Zmieñ na TRUE, je¶li chcesz u¿ywaæ kodu PHP w szablonach
		$src = HTML_DIR,
		$cache = STYLE_DIR,
		$debug = false,
		$byteCode = false;

	#F: Zbadaj pliki skórki i skompiluj zmodyfikowane
	function examine()
	{
		if(!$f = opendir($this->src))
		{
			throw new Exception('Cannot open style: '.strrstr($this->src,'/'));
		}
		while(false !== ($x = readdir($f)))
		{
			if(strpos($x,'.html') && $y = substr_replace($x,'php',-4))
			{
				if(filemtime($this->src.$x) > @filemtime($this->cache.$y))
				{
					$this->compile($x); //Kompiluj
				}
			}
		}
	}

	#F: Callback do <!-- ATTACH plik -->
	function attach($file)
	{
		return file_get_contents($this->src.$file[1].'.html');
	}

	#F: Kompiluj
	function compile($file)
	{
		#Debug
		if($this->debug) { echo 'Compiling file: '.$file.'... '; }

		#Istnieje?
		if(file_exists($this->src.$file))
		{
			$this->data = file_get_contents($this->src.$file);
		}
		else
		{
			throw new Exception('Template does not exist.');
		}

		#.html => .php
		$file = str_ireplace('.html', '.php', $file);

		/* Chyba nie potrzeba tej komendy
		#Attach - do³±czenie kodu innych szablonów
		$this->data = preg_replace_callback('/\<!-- ATTACH ([A-Za-z1-9_.]*) --\>/', array($this,'attach'), $this->data); */

		#Wyrzuæ PHP, je¶li w³±czono (code taken from PhpBB 3 - GPL v2 forum)
		if($this->removePHP)
		{
			$this->data = preg_replace( array(
				'#<([\?%])=?.*?\1>#s',
				'#<script\s+language\s*=\s*(["\']?)php\1\s*>.*?</script\s*>#s',
				'#<\?php(?:\r\n?|[ \n\t]).*?\?>#s'), '', $this->data);
		}

		#Sta³e predefiniowane i niepotrzebne znaki
		$in  = array(
			'{CONTENT}', '{LEFT MENU}', '{RIGHT MENU}', '{LANG}', '{ADMIN}', '{MENU}',
			'{MAIN TITLE}', '{PAGE TITLE}', '{HEAD TAGS}', "\t", "\n\n");

		$out = array(
			'<?php $content->display(); ?>',
			'<?php newnav(1); ?>',
			'<?php newnav(2); ?>',
			'<?= $nlang; ?>',
			'<?php include MOD?>',
			'<?= $menu ?>',
			'<?= $cfg[\'title\']; ?>',
			'<?= $content->title; ?>',
			'<?= $cfg[\'dkh\'].$content->head; ?>',
			'', ''
		);
		$this->data = str_replace($in, $out, $this->data);

		#Pêtle
		if($pos = stripos($this->data,'<!-- START')) $this->checkLoop($pos);

		#Do zamiany
		$in = array(
			'/\{BANNER ([1-9]+)\}/',
			'/\{([A-Za-z1-9_]*)\.([A-Za-z1-9:_ ]*)\}/', //Tablice
			'/\{(nl2br|Clean|htmlspecialchars|Autor|genDate): ([A-Za-z1-9_]*)\.([A-Za-z1-9:_ ]*)\}/',
			'/\{([A-Za-z1-9_]*)\-\>([A-Za-z1-9_]*)\}/', //Obiekty
			'/\{([A-Z1-9_]*)\}/', //Sta³e
			'/\{([A-Za-z1-9_]*)\}/',
			'/\{(nl2br|Clean|htmlspecialchars|Autor|genDate): ([A-Za-z1-9_]*)\}/',
			'/<!-- INCLUDE ([A-Za-z1-9_.]*) -->/');

		$out = array(
			'<?=Banners(\\1);?>',
			'<?=$\\1[\'\\2\'];?>',
			'<?=\\1($\\2[\'\\3\']);?>',
			'<?=$\\1->\\2;?>',
			'<?=\\1;?>',
			'<?=$\\1;?>',
			'<?=\\1($\\2);?>',
			'<?php include STYLE_DIR.\'\\1.php\';?>');

		$this->data = preg_replace($in, $out, $this->data);

		#Zbadaj IF
		$pos = 0;
		if(($pos = strpos($this->data,'<!-- IF')) !== false) $this->checkIF($pos);

		#Zamieñ IF i ELSE
		$this->data = str_replace($this->replace1, $this->replace2, $this->data, $c1);
		$this->data = str_replace('<!-- END -->', '<?php } ?>', $this->data, $c2);
		$this->data = str_replace('<!-- ELSE -->', '<?php }else{?>', $this->data, $c3);

		#Tyle samo IF, END i ELSE?
		if($c1 != $c2 OR $c3 > $c1) { throw new Exception('IF condition is not closed.'); }

		#Optymalizacja otwaræ PHP
		$this->data = str_replace( array('?><?php', '?><?=', "?>\n<?php"), ' ', $this->data);

		#Wyrzuæ komentarze HTML
		$this->data = preg_replace('#\<!--*--\>#', '', $this->data);

		#Zapisz
		if($this->byteCode && extension_loaded('bcompiler'))
		{
			$tmp = tmpfile();
			if(file_put_contents($this->cache.'.temp.php', $this->data))
			{
				$f = fopen($this->cache.$file, 'w');
				bcompiler_write_header($f);
				bcompiler_write_file($f, $this->cache.'.temp.php');
				bcompiler_write_footer($f);
				fclose($f);
				unlink($this->cache.'.temp.php');

				if($this->debug) echo 'Done.<br />';
				return true;
			}
		}
		else
		{
			if(file_put_contents($this->cache.$file, $this->data))
			{
				if($this->debug) echo 'Done.<br />';
				return true;
			}
		}
		throw new Exception('Cannot save template: '.$file);
	}

	#F: Pêtle
	protected function checkLoop($pos)
	{
		#Poziom zag³êbienia
		static $lv = 0;  ++$lv;

		#Dalsze pêtle?
		if($pos2 = strpos($this->data,'<!-- START',$pos+1)) $this->checkLoop($pos2);

		#Aktualna pozycja
		$pos = strpos($this->data, '<!-- START');

		#Zmienna dla pêtli FOREACH (foreach $zmienna as $item)
		$frag = substr($this->data, $pos, strpos($this->data, '<!-- STOP -->')-$pos+13);
		$len  = strlen($frag);
		$end  = strpos($frag, ' -->');
		$var  = substr($frag, 11, $end-11);

		#Poprawno¶æ zmiennej
		if(!ctype_alpha($var[0]) || !ctype_alnum($var))
		{
			throw new Exception('Wrong variable name in START '.$lv.' definition!');
		}

		#Klucz?
		if(stripos($frag, '{KEY}'))
		{
			$frag = str_ireplace('{KEY}', '<?=$key;?>', $frag);
			$key  = '$key=>&$i';
		}
		else
		{
			$key = '&$i';
		}
		$frag = str_ireplace('{ITEM}', '<?=$i'.$lv.';?>', $frag);

		#Zamieñ definicjê pêtli
		$frag = str_replace('<!-- START '.$var.' -->', '<?php foreach($'.$var.' as '.$key.$lv.'){?>', $frag);

		#Koniec pêtli
		$frag = substr_replace($frag, '<?php } ?>', -13);

		#Zmienne i IF
		$frag = preg_replace(
			array(
				'/\{([A-Za-z1-9_]*)\}/',
				'/\{(nl2br|Clean|htmlspecialchars|Autor|genDate): ([A-Za-z1-9_]*)\}/',
				'/\<!-- IF ([A-Za-z1-9_])(.*) --\>/'),
			array(
				'<?=$i'.$lv.'[\'\\1\'];?>',
				'<?=\\1($i'.$lv.'[\'\\2\']);?>',
				'<!-- IF i'.$lv.'.\\1\\2 -->'),
			$frag);

		#Zaktualizuj zmiany
		$this->data = substr_replace($this->data, $frag, $pos, $len);

		#Zmniejsz poziom zag³êbienia
		-- $lv;
	}

	#F: Instrukcje warunkowe
	protected function checkIF($pos)
	{
		#Fragment
		$frag = substr($this->data, $pos, strpos($this->data, ' -->', $pos)-$pos+4);
		$cond = substr($frag, 8, -4);

		/* Wersja bardziej nara¿ona na luki, ale elastyczna: */
		$cond = str_replace( array(')', '(', '`', '@', '/', '\\'), '', $cond);
		$part = explode(' ', $cond);
		$cond = array();
		$str = 0;

		foreach($part as $x)
		{
			if(!ctype_alpha($x[0]) || strtoupper($x) === $x)
			{
				$cond[] = $x;
				continue;
			}

			elseif(strpos($x, '.'))
			{
				$tmp  = explode('.', $x);
				$cond[] = '$'.$tmp[0].'['. ((is_numeric($tmp[1])) ? $tmp[1] : '\''.$tmp[1].'\'').']';
			}
			else
			{
				$cond[] = '$'.$x;
			}
		}

		/* Analiza warunku IF - zrezygnowa³em z niej ze wzglêdu na du¿± ilo¶æ mo¿liwych kombinacji:
		
		#Zmienna
		$var = explode(' ', $cond);

		#Poprawna?
		if(!preg_match('/[A-Za-z1-9._]+/', $var[0]) || !ctype_alpha($var[0][0]))
		{
			throw new Exception('Wrong variable name in IF definition.');
		}

		#Tablica?
		if(strpos($var[0], '.'))
		{
			$tmp = explode('.', $var[0]);  $var[0] = '$'.$tmp[0].'[\''.$tmp[1].'\']';
		}

		#Sta³a czy zmienna?
		elseif(strtoupper($var[0]) !== $var[0]) $var[0] = '$'.$var[0];

		#Operator
		if(isset($var[1]) && isset($var[2]))
		{
			switch($var[1])
			{
				case '!='; case 'not'; case '<>'; case '~': $var[1] = '!='; break;
				case 'OR'; case '||': $var[1] = 'OR'; break;
				case 'AND'; case '&&': $var[1] = '&&'; break;
				case '>': $var[1] = '>'; break;
				case '<': $var[1] = '<'; break;
				default: $var[1] = '===';
			}
			if(!is_numeric($var[2]) && ctype_alnum($var[2]))
			{
				$var[2] = '\''.$var[2].'\'';
			}
			else
			{
				$var[2] = (int)$var[2];
			}
			$var = join('', $var);
		}
		else
		{
			$var = $var[0];
		}
		*/

		#Dopisz do zamiany
		$this->replace1[] = $frag;
		$this->replace2[] = '<?php if('.join(' ', $cond).'){ ?>';

		#Inne IF?
		if($pos = strpos($this->data, '<!-- IF', $pos+1)) $this->checkIF($pos);
	}
}
?>