<?php if(iCMS!=1) exit;
require './lib/categories.php';
require LANG_DIR.'content.php';

#Template
$content->title = $lang['batch'];
$content->dir = './plugins/upload/';
$content->cache = './cache/upload/';

#Supported archives
$zip = extension_loaded('zip');
$rar = extension_loaded('rar');

#Supported miniatures
if(!extension_loaded('gd'))
{
	$content->info($lang['nogd'], null, 'error');
	return;
}

#Stage 1: show form
if(isset($_POST['stage1']))
{
	$data = array();
	$cat = is_numeric($_POST['cat']) ? $_POST['cat'] : 0;
	$title = empty($_POST['title']) ? $lang['noname'] : clean($_POST['title']); //todo:translate
	$autor = clean($_POST['author']);
	$dsc = $_POST['dsc'];
	$w = 120;
	$h = 100;

	#Method: load from server
	if(empty($_POST['up']))
	{
		$i = 0;
		$dir = new DirectoryIterator($_POST['folder']);
		
		#Warn that we cannot create miniatures in non-writable folder
		if(!is_writable($_POST['folder']))
		{
			$content->info(sprintf($lang['noup'], clean($_POST['folder'])), null, 'error');
		}
		
		#Scan all files inside
		foreach($dir as $x)
		{
			#If dir or thumbnail then omit
			if(!$x->isFile() || strpos($x,'.th.')) continue;
			
			#Get full path with filename
			$path = str_replace('\\','/',$x->getPathname());
			
			#Get directory path
			$dir = str_replace('//','/',$x->getPath().'/');
			
			#Get base name without extension
			$base = pathinfo($path, PATHINFO_FILENAME);
			
			#Get width, height, image type
			list($W,$H,$type) = getimagesize($path);
			
			#Get thumbnail name and load full image
			switch($type)
			{
				case IMAGETYPE_JPEG:
					$th = $dir.$base.'.th.jpg';
					$GD = imagecreatefromjpeg($path);
					break;
				case IMAGETYPE_PNG:
					$th = $dir.$base.'.th.png';
					$GD = imagecreatefrompng($path);
					break;
				case IMAGETYPE_GIF:
					$th = $dir.$base.'.th.gif';
					$GD = imagecreatefromgif($path);
					break;
				default: continue;
			}
			
			#If loading full image failed then omit
			if(!$GD) continue;
			
			#Create true color thumbnail
			$gd = imagecreatetruecolor($w,$h);
			
			#Compute width/height ratio for full image and thumbnail
			$RATIO = $W/$H;
			$ratio = $w/$h;
			
			#If full image is wider than thumbnail then crop it
			if($RATIO > $ratio)
			{
				$U = ceil($w*$H/$h);
				$X = floor(($W-$U)/2);
				$V = $H;
				$Y  = 0;
			}
			else
			{
				$U = $W;
				$X  = 0;
				$V = ceil($W*$h/$w);
				$Y  = floor(($H-$V)/2);
			}
			
			#Create thumbnail by resampling cropped full image
			imagecopyresampled($gd, $GD, 0, 0, $X, $Y, $w, $h, $U, $V);
			
			#Save thumbnail on disk
			switch($type)
			{
				case IMAGETYPE_JPEG: imagejpeg($gd, $th, 100); break;
				case IMAGETYPE_PNG: imagepng($gd, $th, 9); break;
				case IMAGETYPE_GIF: imagegif($gd, $th); break;
			}
			
			#Prepare data for template
			$data[] = array(
				'name' => sprintf("%s %d", $title, ++$i),
				'th'   => $th,
				'dsc'  => $dsc,
				'file' => $path,
				'author' => $autor
			);
		}
	}/* TODO: To be continued
	elseif($_FILES)
	{
		if(is_dir('img/'.$cat) && is_writable('img/'.$cat) || mkdir('img/'.$cat))
		{
			$dir = 'img/'.$cat.'/';
		}
		elseif(is_writable('img'))
		{
			$dir = 'img/';
		}
		else
		{
			$dir = null;
		}
		if($dir)
		{
			foreach($_FILES['file']['name'] as $i=>$name)
			{
				switch(pathinfo($name, PATHINFO_EXTENSION))
				{
					case 'zip':
						if($zip)
					{
						$a = new ZipArchive;
						if($a->open($zip) === TRUE)
						{
							$a->extractTo(sys_get_temp_dir());
						}
					}
					case 'jpg':
					case 'png':
					case 'gif':
						if(!getimagesize($name)) continue;
						
					break;

				}
			}
	}*/
	$content->add('upload', array(
		'stage1' => false,
		'stage2' => true,
		'cat'    => $cat,
		'image'  => $data
	));
}

elseif(isset($_POST['stage2']))
{
	$cat = (int)$_POST['cat'];
	if(!admit($cat)) $content->info($lang['nor'], null, 'error');
	$db->beginTransaction();
	$q = $db->prepare('INSERT INTO '.PRE.'imgs (cat,access,name,dsc,type,date,priority,author,th,file)
		VALUES (:cat,1,:name,:dsc,:type,:date,2,:author,:th,:file)');
	foreach($_POST['file'] as $i=>$file)
	{
		$q->execute(array(
			'cat'  => (int)$_POST['cat'],
			'name' => clean($_POST['name'][$i]),
			'dsc'  => clean($_POST['dsc'][$i]),
			'type' => 1,
			'date' => gmdate('Y-m-d H:i:s'),
			'author' => clean($_POST['author'][$i]), //TODO:funkcja z saverclass
			'th'   => clean($_POST['th']),
			'file' => clean($file)
		));
	}
	$db->commit();
	header('Location: '.URL.url('cat/'.$cat));
	return 1;
}

else
{
	$content->add('upload', array(
		'cat'    => Slaves(3, 0),
		'stage1' => true,
		'stage2' => false
	));
}