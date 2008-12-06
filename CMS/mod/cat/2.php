<?php /* Lista plików */
if(iCMS!=1) exit;

#Odczyt
$res = $db->query('SELECT ID,name,date,dsc,file,size FROM '.PRE.'files WHERE '.$cats.'
	AND access=1 ORDER BY priority,'.CatSort($cat['sort']).' LIMIT '.$st.','.$cfg['np']);

$res->setFetchMode(3);

$total = 0;
$files = array();

#Lista
foreach($res as $file)
{
	$files[] = array(
		'title' => $file[1],
		'desc'  => $file[3],
		'size'  => $file[5],
		'num'   => ++$st,
		'date'  => genDate($file[2]),
		'url'   => '?co=file&amp;id='.$file[0],
		'file_url' => isset($cfg['fcdl']) ? 'go.php?file='.$file[0] : $file[4]
	);
	++$total;
}

#Strony
$pages = $cat['num']>$total ? Pages($page,$cat['num'],$cfg['np'],'?d='.$d) : null;

#Do szablonu
$content->file[] = 'cat_files';
$content->data += array(
	'files' => &$files,
	'pages' => &$pages,
	'add_url' => Admit($d,'CAT') ? '?co=edit&amp;act=file' : null,
	'cat_type'=> $lang['files']
);

unset($res,$total,$file);