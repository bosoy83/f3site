<?php //Wy¶lij obraz na serwer - zanim wywo³asz funkcjê, do³±cz konfig i jêzyk
function Avatar(&$error=array(), $id=UID)
{
	global $lang,$cfg,$db;
	$ok   = 1;
	$file = &$_FILES['photo'];
	$ext  = strtolower(strrchr($file['name'],'.'));
	$size = getimagesize($file['tmp_name']);

	if($ext!='.png' && $ext!='.jpg' && $ext!='.gif' && $ext!='.bmp')
	{
		$error[] = $lang['photoEx'];
		$ok = 0;
	}
	if(!$size OR $size[0]==0 OR $size[0]>$cfg['maxDim1'] OR $size[1]>$cfg['maxDim2'])
	{
		$error[] = $lang['photoBig']; //Gdy rozmiar = 0, plik nie jest obrazem
		$ok = 0;
	}
	try
	{
		#Pobierz aktualny URL
		$old = $db->query('SELECT photo FROM '.PRE.'users WHERE ID='.$id) -> fetchColumn();

		#Przenie¶ plik
		if($ok && move_uploaded_file($file['tmp_name'], 'img/user/'.$id.$ext))
		{
			#Usuñ stary
			if($old != 'img/user/'.$id.$ext && !strpos($old,':'))
			{
				@unlink($old);
			}

			#Aktualizuj w bazie
			$db->exec('UPDATE '.PRE.'users SET photo="img/user/'.$id.$ext.'" WHERE ID='.$id);

			#Zwróæ URL
			return 'img/user/'.$id.$ext;
		}
		else
		{
			$error[] = $lang['photoErr'];
			return $old;
		}
	}
	catch(PDOException $e)
	{
		$error[] = $e;
		return $old;
	}
}

function RemoveAvatar(&$error, $id=UID)
{
	global $db;
	try
	{
		#Pobierz aktualny URL
		$old = $db->query('SELECT photo FROM '.PRE.'users WHERE ID='.$id) -> fetchColumn();

		#Aktualizuj w bazie
		$db->exec('UPDATE '.PRE.'users SET photo="" WHERE ID='.$id);
	}
	catch(PDOException $e)
	{
		$error[] = $e;
		return $old;
	}
	if(strpos($old,':') === false) @unlink($old);
	return '';
}