<?php #Sidebars generator
function RenderMenu(PDO $db = null)
{
	if(!$db) global $db;
	if(!is_writable('cache')) throw new Exception('ERROR: You must chmod /cache/ directory to 777');

	#Odczyt bloków - ASSOC
	$block = $db->query('SELECT * FROM '.PRE.'menu WHERE disp!=2 ORDER BY seq')->fetchAll(2);

	#Odczyt linków menu - NUM
	$items = $db->query('SELECT menu,text,type,url,nw FROM '.PRE.'mitems ORDER BY seq')->fetchAll(3);

	#Jêzyki
	foreach(scandir('lang') as $dir)
	{
		if($dir[0]=='.' || !is_dir('lang/'.$dir)) continue;
		$out = array(null,'','');

		foreach($block as &$b)
		{
			$page = $b['menu'];
			if($b['disp']=='3')
			{
				$out[$page] .= '<?php if(IS_ADMIN){?>';
			}
			elseif($b['disp']!='1' && $b['disp']!=$dir) continue;
			$out[$page] .= '<div class="mh"'.($b['img'] ? ' style="background: url('.$b['img'].') no-repeat bottom right"':'').'>'.$b['text'].'</div><div class="menu">';

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
						switch($i[2])
						{
							case 1: $url = '.'; break;
							case 3: $url = $i[3]; break;
							case 4: $url = strpos($i[3], 'www.')===0 ? 'http://'.$i[3] : $i[3]; break;
							case 6: $url = url('page/'.$i[3]); break;
							default: $url = url($i[3]);
						}
						$links .= '<li><a href="'.$url.'"'.($i[4]?' target="_blank"':'').'>'.$i[1].'</a></li>';
					}
				}
				if($links) $out[$page].= '<ul>'.$links.'</ul>';
			}
			$out[$page].='</div>' . ($b['disp']=='3' ? '<?php } ?>' : '');
		}

		#Ca³oœæ
		$out = '<?php function newnav($MID) { global $cfg,$lang,$db,$user; if($MID==1) {?>'.$out[1].'<?php } else {?>'.$out[2].'<?php } } ?>';

		#Redukuj otwarcia PHP
		$out = str_replace('?><?php', '', $out);

		#Zapisz
		file_put_contents('./cache/menu'.$dir.'.php', $out, 2); //2 = LOCK_EX
	}
}