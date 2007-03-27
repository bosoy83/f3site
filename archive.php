<?php
if(iCMS!='E123') exit;
#Lista nowo¶ci
$id=$_GET['id'];
if($id)
{
 if(!defined('SEARCH'))
 {
  $xdate=0;
  $xdate=explode('.',$id);
  db_read('ID,name,date','news','news','tn',' WHERE MONTH(date)='.$xdate[0].' && YEAR(date)='.$xdate[1].' && (access=1 || access="'.db_esc($nlang).'") ORDER BY ID DESC');
 }
 $ile=count($news);
 if($ile>0)
 {
  cTable($lang['arch'],2);
  echo '<tr><th>'.$lang['title'].'</th><th>'.$lang['added'].'</th></tr>';
  for($i=0;$i<$ile;$i++)
  {
   echo '<tr><td>'.(($cfg['num']==1)?($i+1).'. ':'').'<a href="?co=news&amp;id='.$news[$i][0].'">'.$news[$i][1].'</a></td><td align="center">'.genDate($news[$i][2]).'</td></tr>';
  }
  eTable();
 }
 else
 {
  Info($lang['nonews']);
 }
}
#Lista mies.
else {
 $news[0]='';
 db_read('date','news','news','on',' LIMIT 1');
 if($news[0]!='')
 {
  cTable($lang['arch'],1);
  echo '<tr><td align="center" style="line-height: 80%">';
  $dnews=explode('-',$news[0]);
  if($dnews[1]!=10) { $dnews[1]=str_replace('0','',$dnews[1]); }
  $m=strftime('%m');
  $y=strftime('%Y');
  for($i=0;$i<50;$i++)
  {
   $tempx[$i]='<br /><a href="?co=arch&amp;id='.$dnews[1].'.'.$dnews[0].'">'.$mlang[$dnews[1]].' - '.$dnews[0].'</a><br />';
   if($dnews[1]==$m && $dnews[0]==$y) { break; }
   $dnews[1]++;
   if($dnews[1]==13) { $dnews[1]=1; $dnews[0]++; }
  }
  $ile=count($tempx);
  for($i=$ile;$i>=0;$i--)
  {
   echo $tempx[$i];
  }
  echo '<br /></td></tr>';
  eTable();
  unset($tempx,$dnews);
 }
 else
 {
  Info($lang['nonews']);
 }
}
?>
