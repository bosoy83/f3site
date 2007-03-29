<?php
require('plugins/bugs/lang/'.$nlang.'.php');
require('cfg/plug_bugs.php');
$b=&$lang['reqerr'];
if($cfg['bugs_on']!=1 && !$_GET['id']) { exit($b); } else { $id=$_GET['id']; }

switch($_GET['t'])
{
	#Usuñ
	case 'del':
		if(!ChPrv('BUGS')) exit($b);
		$bug=array();
		$bugdate=array();
		db_read('cat','bugs','bug','on',' WHERE ID='.$id);
		if($bug[0])
		{
			db_q('DELETE FROM {pre}bugs WHERE ID='.$id);
			db_read('date','bugs','bugdate','on',' WHERE cat='.$bug[0].' ORDER BY ID DESC LIMIT 1');
			db_q('UPDATE {pre}bugcats SET num=num-1
			'.(($bugdate[0])?', last="'.$bugdate[0].'"':'').' WHERE ID='.$bug[0]);
			exit('OK.');
		}

	#G³os
	case 'rate':
		if(LOGD!=1 && $cfg['bugs_v']!=1) exit;
		$r=$_GET['r'];
		$bug=array();
		@db_read('c.ID,c.trate,b.pos,b.neg','bugcats c INNER JOIN {pre}bugs b ON c.ID=b.cat','bug','on',' WHERE c.see<>2 AND b.ID='.$id);

		if($bug[0]!='')
		{
			#User
			$u=(LOGD==1)?UID:'"'.$_SERVER['REMOTE_ADDR'].'"';

			#Test
			$rate=array();
			db_read('wh','bugrate','rate','on',' WHERE BID='.$id.' AND UID='.$u);
	 
			#G³osowa³
			if($rate[0])
			{
				exit($lang['voted'].(($bug[1]==1)?'<big>'.(($rate[0]==2)?' &#8711;':' &#916;').'</big>':$rate[0]));
			}
			else
			{
				#Typ
				switch($bug[1])
				{
					#Za/przeciw
					case 1:
						if($r==1) { $bug[2]++; } elseif($r==2) { $bug[3]++; } else { exit($b); }
						$out='<img src="plugins/bugs/thup.png" alt="UP" /> '.$bug[2].'
						<img src="plugins/bugs/thd.png" alt="DOWN" /> '.$bug[3];
					break;

					#Ocena
					case 2:
						if($r>0 && $r<6) { $bug[2]+=$r; $bug[3]++; } else { exit($b); }
						$out=Rating($bug[2].'|'.$bug[3],1);
					break;

					default: exit($b);
				}
				#SQL
				db_q('UPDATE {pre}bugs SET pos='.$bug[2].', neg='.$bug[3].' WHERE ID='.$id);
				db_q('INSERT INTO {pre}bugrate VALUES ('.$u.','.$id.','.$r.',NOW())');

				#OK
				exit('OK. '.$out);
			}
		}
		else
		{
		echo $b;
	}
	break;

	#Status
	case 'status':
		if(ChPrv('BUGS'))
		{
			if(is_numeric($_GET['s']))
			{
				@db_q('UPDATE {pre}bugs SET status='.$_GET['s'].' WHERE ID='.$id);
				echo $lang['bugs_'.$_GET['s']];
			}
		}
	break;
	default: exit($b);
}
?>