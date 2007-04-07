<?php
if(iCMS!='E123') exit;
extract($_POST);
$p=$db_pre;
$p1='CREATE TABLE '.$p;
$p2='INSERT INTO '.$p;
$lt=$lang['cnt'].' '.$p;
function I() { echo '<div style="color: green"><b>OK</b></div>'; }

require('../db/'.$db_db.'.php');
if($uadmh!=$uadmh2 || empty($uadmh)) exit($lang['pass_err']);

if(!is_writable('../cfg/db.php')) exit($lang['chmod_err']);
?>
<table align="center" style="width: 500px">
<tbody>
<tr>
 <th><b><?= $lang['op'] ?></b></th>
</tr>
<tr>
 <td style="width: 300px">
<?php
#£±czenie
echo $lang['conn'].'...<br />';
db_c($db_h,$db_u,$db_p,$db_d);
I();
#Usuwanie
db_q('DROP TABLE IF EXISTS '.$p.'admmenu, '.$p.'answers, '.$p.'arts, '.$p.'artstxt, '.$p.'banners, '.$p.'cats, '.$p.'comms, '.$p.'confmenu, '.$p.'emots, '.$p.'files, '.$p.'fnews, '.$p.'groups, '.$p.'imgs, '.$p.'links, '.$p.'log, '.$p.'menu, '.$p.'mitems, '.$p.'news, '.$p.'online, '.$p.'pages, '.$p.'plugins, '.$p.'pms, '.$p.'polls, '.$p.'users');
#Tabele
echo $lt.'admmenu...<br />';
db_q($p1.'admmenu (ID varchar(100) NOT NULL PRIMARY KEY, text varchar(200), file varchar(200), hid tinyint)');
I();
echo $lt.'answers...<br />';
db_q($p1.'answers (IDP int unsigned NOT NULL, seq int, a varchar(200), num int)');
I();
echo $lt.'arts...<br />';
db_q($p1.'arts (ID int NOT NULL auto_increment PRIMARY KEY, cat int NOT NULL, name varchar(255), dsc mediumtext, date date, author varchar(200), rates varchar(50), access tinyint, priotity tinyint, ent int)');
I();
echo $lt.'artstxt...<br />';
db_q($p1.'artstxt (ID int NOT NULL auto_increment PRIMARY KEY, cat int NOT NULL, text text, emo tinyint, br tinyint, php tinyint)');
I();
echo $lt.'banners...<br />';
db_q($p1.'banners (ID int NOT NULL auto_increment PRIMARY KEY, gen varchar(255) NOT NULL, name varchar(100), ison tinyint, code mediumtext)');
I();
echo $lt.'cats...<br />';
db_q($p1.'cats (ID int NOT NULL auto_increment PRIMARY KEY, name varchar(255), dsc varchar(255), access varchar(10), type tinyint, sc varchar(50), sort tinyint, text mediumtext, num int unsigned NOT NULL, nums int unsigned NOT NULL, opt varchar(5) NOT NULL)');
I();
echo $lt.'comms...<br />';
db_q($p1.'comms (ID int NOT NULL auto_increment PRIMARY KEY, th varchar(20), name varchar(200), author varchar(200), at tinyint, ip varchar(20), date datetime, text mediumtext)');
I();
echo $lt.'confmenu...<br />';
db_q($p1.'confmenu (ID varchar(50) NOT NULL PRIMARY KEY, name varchar(50), lang varchar(5) NOT NULL, img varchar(230))');
I();
echo $lt.'files...<br />';
db_q($p1.'files (ID int NOT NULL auto_increment PRIMARY KEY, cat int NOT NULL, name varchar(255), author varchar(100), date date, dsc mediumtext, file varchar(200), dls int, access tinyint, size varchar(50), priotity tinyint, rates varchar(50), fulld text)');
I();
echo $lt.'fnews...<br />';
db_q($p1.'fnews (ID int NOT NULL PRIMARY KEY, cat int NOT NULL, text text)');
I();
echo $lt.'groups...<br />';
db_q($p1.'groups (ID int NOT NULL auto_increment PRIMARY KEY, name varchar(80), dsc text, access int, opened tinyint)');
I();
echo $lt.'imgs...<br />';
db_q($p1.'imgs (ID int NOT NULL auto_increment PRIMARY KEY, cat int(11) NOT NULL, name varchar(255), dsc mediumtext, type int(11), date date NOT NULL, priotity tinyint(4) NOT NULL, access tinyint(4) NOT NULL, rates varchar(100), author varchar(100), filem varchar(255), file varchar(255), size varchar(100))');
I();
echo $lt.'links...<br />';
db_q($p1.'links (ID int NOT NULL auto_increment PRIMARY KEY, cat int NOT NULL, name varchar(255), dsc mediumtext, access tinyint, adr varchar(255), priotity tinyint, count int, nw int)');
I();
echo $lt.'log...<br />';
db_q($p1.'log (ID int NOT NULL auto_increment PRIMARY KEY, name varchar(50), date datetime, ip varchar(70), user int)');
I();
echo $lt.'menu...<br />';
db_q($p1.'menu (ID int NOT NULL auto_increment PRIMARY KEY, seq int NOT NULL, text varchar(200), disp varchar(10), menu int NOT NULL, type int NOT NULL, img varchar(255), value mediumtext)');
I();
echo $lt.'mitems...<br />';
db_q($p1.'mitems (seq int NOT NULL, menu varchar(11), type int NOT NULL, text varchar(100), url varchar(255), nw tinyint)');
I();
echo $lt.'news...<br />';
db_q($p1.'news (ID int NOT NULL auto_increment PRIMARY KEY, cat int NOT NULL, name varchar(255), txt text, date datetime, author varchar(50), fn tinyint, img varchar(255), emo tinyint, comm int, br tinyint, access varchar(10))');
I();
echo $lt.'online...<br />';
db_q($p1.'online (IP varchar(70), user int, time timestamp, site varchar(50), PRIMARY KEY(IP))');
I();
echo $lt.'pages...<br />';
db_q($p1.'pages (ID int NOT NULL auto_increment PRIMARY KEY, name varchar(255), access varchar(5), tab tinyint, br tinyint, emo tinyint, comm tinyint, php tinyint, text text)');
I();
echo $lt.'plugins...<br />';
db_q($p1.'plugins (ID varchar(100) NOT NULL, name varchar(200))');
I();
echo $lt.'pms...<br />';
db_q($p1.'pms (ID int NOT NULL auto_increment PRIMARY KEY, topic varchar(80), usr int unsigned NOT NULL, owner int unsigned NOT NULL, st tinyint, date datetime, bbc tinyint, txt text)');
I();
echo $lt.'polls...<br />';
db_q($p1.'polls (ID int NOT NULL auto_increment PRIMARY KEY, name varchar(100), q varchar(255), ison tinyint NOT NULL, type varchar(10), num int, access varchar(10) NOT NULL, date date)');
I();
echo $lt.'users...<br />';
db_q($p1.'users (ID int NOT NULL auto_increment PRIMARY KEY, login varchar(100) UNIQUE NOT NULL, pass varchar(255) NOT NULL, mail varchar(100), mvis tinyint, gid int NOT NULL, lv tinyint, adm mediumtext, regt date, lvis datetime, pms int unsigned NOT NULL, about mediumtext, mails tinyint, www varchar(200), city varchar(100), icq varchar(15), skype varchar(50), tlen varchar(50), gg int)');
I();
#Dodawanie zawarto¶ci
echo '<br />'.$lang['a'].$p.'cats...<br />';
db_q($p2.'cats VALUES (1,"'.$lang['mainpage'].'","",1,5,"P",2,"",0,0,"S")');
I();
echo $lang['a'].$p.'groups...<br />';
db_q($p2.'groups VALUES (1,"'.$lang['users'].'","",1,1)');
db_q($p2.'groups VALUES (2,"'.$lang['adms'].'","",1,2)');
I();
echo $lang['a'].$p.'menu...<br />';
db_q($p2.'menu VALUES (1,1,"Menu",1,1,3,0,"")');
db_q($p2.'menu VALUES (2,2,"'.$lang['user'].'",1,2,2,0,"inc/m/mlog.php")');
db_q($p2.'menu VALUES (3,3,"'.$lang['poll'].'",1,2,2,0,"inc/m/poll.php")');
db_q($p2.'menu VALUES (4,4,"'.$lang['opts'].'",1,1,2,0,"inc/m/msets.php")');
db_q($p2.'menu VALUES (5,5,"'.$lang['stats'].'",1,2,2,0,"inc/m/online.php")');
db_q($p2.'menu VALUES (6,6,"'.$lang['narts'].'",2,2,2,0,"inc/m/new.php")');
I();
echo $lang['a'].$p.'mitems...<br />';
db_q($p2.'mitems VALUES (1,1,1,"'.$lang['mainpage'].'","index.php",2)');
db_q($p2.'mitems VALUES (2,1,1,"'.$lang['narch'].'","?co=arch",2)');
db_q($p2.'mitems VALUES (3,1,1,"'.$lang['links'].'","?co=cats&amp;id=4",2)');
db_q($p2.'mitems VALUES (4,1,1,"'.$lang['imgs'].'","?co=cats&amp;id=3",2)');
db_q($p2.'mitems VALUES (5,1,1,"'.$lang['search'].'","?co=s&amp;new=1",2)');
db_q($p2.'mitems VALUES (6,1,1,"'.$lang['users'].'","?co=users",2)');
db_q($p2.'mitems VALUES (7,1,1,"'.$lang['groups'].'","?co=groups",2)');
I();
echo $lang['a'].$p.'users...<br />';
$newp=md5($uadmh);
db_q($p2.'users VALUES (1,"'.str_replace('\\','',htmlspecialchars(strip_tags($uadml),ENT_QUOTES)).'","'.$newp.'","",2,2,3,"","'.strftime('%Y-%m-%d').'","",0,"",1,"","","","","","")');
I();
echo '<br />'.$lang['crdbf'].'<br />';
$f=fopen('../cfg/db.php','w');
fwrite($f,'<?php if(iCMS!=\'E123\') { exit; } $cms_lang=\''.$lng.'\'; $db_db=\''.$db_db.'.php\'; $db_d=\''.$db_d.'\'; $db_pre=\''.$p.'\'; $db_u=\''.$db_u.'\'; $db_p=\''.$db_p.'\'; $db_h=\''.$db_h.'\'; ?>');
fclose($f);
I();
echo '<br /><br />'.$lang['delit'];
?>
<br /><br />
<center><input type="button" value="&raquo; &raquo;" onclick="location='../index.php'" /></center>
 </td>
</tr>
</tbody>
</table>
