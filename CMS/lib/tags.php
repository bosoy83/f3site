<?php
function tags($id, $type, $mayTag=false)
{
	global $db,$cfg,$content;

	$may = admit('TAG');
	$url = url('tags/');
	$tag = array();

	$res = $db->prepare('SELECT tag,num FROM '.PRE.'tags WHERE ID=? AND TYPE=? GROUP BY tag ORDER BY tag');
	$res -> execute(array($id, $type));

	foreach($res as $x)
	{
		$tag[] = array(
			'tag' => $x['tag'],
			'url' => $url.$x['tag'],
			'num' => $x['num']
		);
	}

	if($tag || $may):

	$content->file[] = 'tag';
	$content->data['tag'] = $tag;
	$content->data['tags'] = url('tags');
	$content->data['editTags'] = $may ? 'request.php?go=tags&type='.$type.'&id='.$id : false;

	endif;
}