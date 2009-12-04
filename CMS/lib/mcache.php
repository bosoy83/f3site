<?php #Generator plików menu
function RenderMenu()
{
	global $db;
	if(!is_writable('cache')) throw new Exception('Chmod CACHE directory to 777');

	#Odczyt bloków - ASSOC
	$block = $db->query('SELECT * FROM '.PRE.'menu WHERE disp!=2 ORDER BY seq')->fetchAll(2);

	#Odczyt linków menu - NUM
	$items = $db->query('SELECT menu,text,url,nw FROM '.PRE.'mitems ORDER BY seq')->fetchAll(3);

	#Jêzyki
	foreach(scandir('./lang') as $dir)
	{
		if($dir[0]=='.' || !is_dir('./lang/'.$dir)) continue;
		$out = array(null,'','');

		foreach($block as &$b)
		{
			if($b['disp']!=1 && $b['disp']!=$dir) continue;
			$page = $b['menu'];
			$out[$page] .= '<div class="mh"'.(($b['img'])?' style="background: url('.$b['img'].') no-repeat bottom right"':'').'>'.$b['text'].'</div><div class="menu">';

			#Tekst, plik, linki
			switch($b['type'])
			{
				case 1: $out[$page] .= $b['value']; break;
				case 2: $out[$page] .= '<?php include \''.str_replace(array('\'','\\'),array('\\\'','\\\\'),$b['value']).'\'?>'; break;
				case 4: $got = file_get_contents($b['value']);
					if(substr_count($got,'<?') > substr_count($got,'?>')) $got .= ' ?>';
					$out[$page] .= $got;
					break;
				default: 

				$links = '';
				foreach($items as &$i)
				{
					if($i[0] == $b['ID'])
					{
						$links .= '<li><a href="'.$i[2].'"'.(($i[3])?' target="_blank"':'').'>'.$i[1].'</a></li>';
					}
				}
				if($links) $out[$page].= '<ul>'.$links.'</ul>';
			}
			$out[$page].='</div>';
		}

		#Ca³oœæ
		$out = '<?php function newnav($MID) { global $cfg,$lang,$db,$user; if($MID==1) {?>'.$out[1].'<?php } else {?>'.$out[2].'<?php } } ?>';

		#Redukuj otwarcia PHP
		$out = str_replace('?><?php', '', $out);

		#Zapisz
		file_put_contents('./cache/menu'.$dir.'.php', $out, 2); //2 = LOCK_EX
	}
}