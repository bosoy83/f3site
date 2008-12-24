<?php #Generator plików menu
function RenderMenu()
{
	global $db;
	if(!is_writable('cache')) { echo 'CHMOD "CACHE" DIRECTORY TO 777!'; return false; }

	#Odczyt bloków - ASSOC
	$block = $db->query('SELECT * FROM '.PRE.'menu WHERE disp!=2 ORDER BY seq')->fetchAll(2);

	#Odczyt linków menu - NUM
	$items = $db->query('SELECT menu,text,url,nw FROM '.PRE.'mitems ORDER BY seq')->fetchAll(3);

	#Zamieñ '
	function Ap($x) { return str_replace(array('\'','\\'),array('\\\'','\\\\'),$x); }

	#Jêzyki
	foreach(scandir('./lang') as $dir)
	{
		if($dir[0]=='.' || !is_dir('./lang/'.$dir)) continue;
		$out = array('','','');

		foreach($block as &$x)
		{
			if($x['disp']!=1 && $x['disp']!=$dir) continue;

			#Nowy blok
			$page = $x['menu'];
			$out[$page].= '<div class="mh"'.(($x['img'])?' style="background: url('.$x['img'].') no-repeat bottom right"':'').'>'.$x['text'].'</div><div class="menu">';

			#Tekst, plik, linki
			switch($x['type'])
			{
				case 1: $out[$page] .= $x['value']; break;
				case 2: $out[$page] .= '<?php include \''.Ap($x['value']).'\'?>'; break;
				case 4: $got = file_get_contents($x['value']);
					if(substr_count($got,'<?') > substr_count($got,'?>')) $got .= ' ?>';
					$out[$page] .= $got;
					break;
				default: 

				$links = '';
				foreach($items as &$y)
				{
					if($y[0] == $x['ID'])
					{
						$links.= '<li><a href="'.$y[2].'"'.(($y[3])?' target="_blank"':'').'>'.$y[1].'</a></li>';
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