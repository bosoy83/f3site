<?php
#Generator plików menu
function RenderMenu()
{
	global $db;
	if(!is_writable('cache')) { echo 'CHMOD "CACHE" DIRECTORY TO 777!'; return false; }

	#Odczyt
	$res=$db->query('SELECT * FROM '.PRE.'menu WHERE disp!=2 ORDER BY seq');
	$m=$res->fetchAll(2); //ASSOC
	$res=null;
	$ile=count($m);

	#Zamieñ '
	function Ap($x) { return str_replace(array('\'','\\'),array('\\\'','\\\\'),$x); }

	#Sk³ad SQL
	$list=array();
	for($i=0;$i<$ile;++$i)
	{
		$list[]=(int)$m[$i]['ID'];
	}

	#Linki
	$res=$db->query('SELECT menu,text,url,nw FROM '.PRE.'mitems WHERE menu IN('.join(',',$list).') ORDER BY seq');
	$item=$res->fetchAll(3); //NUM
	$ile2=count($item);

	#Jêzyki
	$dh=opendir('./lang');
	while(($file = readdir($dh))!==false)
	{
		if(is_dir('./lang/'.$file) && strpos($file,'.')!==0)
		{
			$out=array('','','');
			for($i=0;$i<$ile;++$i)
			{
				if($m[$i]['disp']==1 || $m[$i]['disp']===$file)
				{
					#Nowy blok
					$page=$m[$i]['menu'];
					$out[$page].='mnew(\''.Ap($m[$i]['text']).'\',\''.((empty($m[$i]['img']))?'':' style="background: url('.Ap($m[$i]['img']).') no-repeat bottom right"').'\'); ';

					#Tekst
					if($m[$i]['type']==1) { $out[$page].='echo \''.Ap($m[$i]['value']).'\'; '; }
					#Plik
					elseif($m[$i]['type']==2) { $out[$page].='include \''.Ap($m[$i]['value']).'\'; '; }

					#Linki
					else {
						$out[$page].='?><ul>';
						for($y=0;$y<$ile2;++$y) {
							if($item[$y][0]==$m[$i]['ID'])
							{
								$out[$page].='<li><a href="'.Ap($item[$y][2]).'"'.(($item[$y][3]==1)?' target="_blank"':'').'>'.Ap($item[$y][1]).'</a></li>';
							}
						}
						$out[$page].='</ul><?php ';
					}
					$out[$page].='mend(); ';
				}
			}
			#Zapisz
			file_put_contents('./cache/menu'.$file.'.php','<?php function newnav($MenuID) { global $cfg,$lang,$db,$user; if($MenuID==1) { '.$out[1].' } else { '.$out[2].' } } ?>',2);
    }
	}
}
?>
