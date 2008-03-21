<?php
#Generator plików menu
function RenderMenu()
{
	global $db;
	if(!is_writable('cache')) { echo 'CHMOD "CACHE" DIRECTORY TO 777!'; return false; }

	#Odczyt
	$res = $db->query('SELECT * FROM '.PRE.'menu WHERE disp!=2 ORDER BY seq');
	$m = $res->fetchAll(2); //ASSOC
	$res = null;
	$ile = count($m);

	#Zamieñ '
	function Ap($x) { return str_replace(array('\'','\\'),array('\\\'','\\\\'),$x); }

	#Sk³ad SQL
	$list = array();
	foreach($m as $x)
	{
		$list[] = (int) $x['ID'];
	}

	#Linki
	$res=$db->query('SELECT menu,text,url,nw FROM '.PRE.'mitems WHERE menu IN('.join(',',$list).') ORDER BY seq');
	$item = $res->fetchAll(3); //NUM
	$ile2 = count($item);

	#Jêzyki
	foreach(scandir('./lang') as $dir)
	{
		if(is_dir('./lang/'.$dir) && $dir[0]!='.')
		{
			$out = array(null,null,null);
			foreach($m as &$x)
			{
				if($x['disp']==1 || $x['disp']==$dir)
				{
					#Nowy blok
					$page = $x['menu'];
					$out[$page].= '<div class="mh"'.(($x['img'])?' style="background: url('.$x['img'].') no-repeat bottom right"':'').'>'.$x['text'].'</div><div class="menu">';

					#Tekst
					if($x['type']==1) $out[$page].= $x['value'];

					#Plik
					elseif($x['type']==2) $out[$page].= '<?php include \''.Ap($x['value']).'\'?>';

					#Linki
					else {
						$links = '';
						foreach($item as &$y) {
							if($y[0] == $x['ID'])
							{
								$links.= '<li><a href="'.$y[2].'"'.(($y[3]===1)?' target="_blank"':'').'>'.$y[1].'</a></li>';
							}
						}
						if($links) $out[$page].= '<ul>'.$links.'</ul>';
					}
					$out[$page].='</div>';
				}
			}

			#Ca³oœæ
			$out = '<?php function newnav($MenuID) { global $cfg,$lang,$db,$user; if($MenuID==1) {?>'.$out[1].'<?php } else {?>'.$out[2].'<?php } } ?>';

			#Redukuj otwarcia PHP
			$out = str_replace('?><?php', '', $out);

			#Zapisz
			file_put_contents('./cache/menu'.$dir.'.php', $out, 2); //2 = LOCK_EX
    }
	}
}
?>
