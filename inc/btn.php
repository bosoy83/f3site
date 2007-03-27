<?php if(iCMS!='E123') { exit; } ?>
<script type="text/javascript">
<!--
function InsLink(f,t)
{
 a=prompt('<?= $lang['adr'] ?>:','http://');
 if(a) {
  b=prompt('<?= $lang['text'] ?>:','');
  if(b) {
   if(t==1) { d=confirm('<?= $lang['ap_nw'] ?>'); c='<a href="'+a+'"'+((d)?' target="_blank"':'')+'>'+b+'</a>' } else { c='[url='+a+']'+b+'[/url]' }
   BBC(f,c,'');
  }
 }
}
function InsMail(f,t)
{
 a=prompt('<?= $lang['adr'] ?>:','');
 if(a) {
  b=a.replace('@','&#64;');
  if(t==1) { c='<a href="mailto:'+b+'">'+b+'</a>' } else { c='[email]'+b+'[/email]' }
  BBC(f,c,'');
 }
}
-->
</script>
<?php
function Btns($t,$o,$f)
{
global $lang;
echo '<input type="button" value="B" onclick="BBC(\''.$f.'\','.(($t==1)?'\'&lt;b&gt;\',\'&lt;/b&gt;\'':'\'[b]\',\'[/b]\'').')" style="font-weight: bold; width: 25px" />
<input type="button" value="I" onclick="BBC(\''.$f.'\','.(($t==1)?'\'&lt;i&gt;\',\'&lt;/i&gt;\'':'\'[i]\',\'[/i]\'').')" style="font-style: italic; width: 25px" />
<input type="button" value="U" onclick="BBC(\''.$f.'\','.(($t==1)?'\'&lt;u&gt;\',\'&lt;/u&gt;\'':'\'[u]\',\'[/u]\'').')" style="text-decoration: underline; width: 25px" />
<input type="button" value="link" onclick="InsLink(\''.$f.'\','.$t.')" />
<input type="button" value="E-mail" onclick="InsMail(\''.$f.'\','.$t.')" />
<input type="button" value="'.$lang['quote'].'" onclick="BBC(\''.$f.'\','.(($t==1)?'\'&lt;div class=&quot;quote&quot;&gt;\',\'&lt;/div&gt;\'':'\'[quote]\',\'[/quote]\'').')" />
<input type="button" value="'.$lang['code'].'" onclick="BBC(\''.$f.'\','.(($t==1)?'\'&lt;div class=&quot;code&quot;&gt;&lt;code&gt;\',\'&lt;/code&gt;&lt;/div&gt;\'':'\'[code]\',\'[/code]\'').')" />
'.(($o==1)?'<input type="button" value="'.$lang['img'].'" onclick="BBC(\''.$f.'\','.(($t==1)?'\'&lt;img src=&quot;\',\'&quot; /&gt;\'':'\'[img]\',\'[/img]\'').')" />':'');
}
function SpecChr($f)
{
global $lang;
echo '
<div style="padding: 3px; cursor: pointer">'.$lang['spchr'].':
<a onclick="BBC(\''.$f.'\',\'&amp;bdquo;\',\'\')">&bdquo; </a>
<a onclick="BBC(\''.$f.'\',\'&amp;rdquo;\',\'\')">&nbsp;&rdquo; </a>
<a onclick="BBC(\''.$f.'\',\'&amp;lt;\',\'\')">&nbsp;&lt; </a>
<a onclick="BBC(\''.$f.'\',\'&amp;gt;\',\'\')">&nbsp;&gt; </a>
<a onclick="BBC(\''.$f.'\',\'&amp;middot;\',\'\')">&nbsp;&middot; </a>
<a onclick="BBC(\''.$f.'\',\'&amp;bull;\',\'\')">&nbsp;&bull; </a>
<a onclick="BBC(\''.$f.'\',\'&amp;deg;\',\'\')">&nbsp;&deg; </a>
<a onclick="BBC(\''.$f.'\',\'&amp;amp;\',\'\')">&nbsp;&amp; </a>
<a onclick="BBC(\''.$f.'\',\'&amp;sect;\',\'\')">&nbsp;&sect; </a>
<a onclick="BBC(\''.$f.'\',\'&amp;copy;\',\'\')">&nbsp;&copy; </a>
<a onclick="BBC(\''.$f.'\',\'&amp;reg;\',\'\')">&nbsp;&reg; </a>
<a onclick="BBC(\''.$f.'\',\'&amp;raquo;\',\'\')">&nbsp;&raquo;</a>
</div>
';
}
function Colors($f,$t)
{
 global $lang;
 echo ' <select onchange="so=this.options[this.selectedIndex].innerHTML; if(this.selectedIndex!=0) BBC(\''.$f.'\','.(($t==1)?'\'&lt;span style=&quot;color: \'+so+\'&quot;&gt;\',\'&lt;/span&gt;\'':'\'[color=\'+so+\']\',\'[/color]\'').')"><option value="black">'.$lang['color'].'</option><option style="color: orange">orange</option><option style="color: red">red</option><option style="color: purple">purple</option><option style="color: brown">brown</option><option style="color: blue">blue</option><option style="color: teal">teal</option><option style="color: green">green</option></select> ';
}
function FontBtn($f,$t)
{
 echo ' <input type="button" value="center" onclick="BBC(\''.$f.'\',\''.(($t==1)?'&lt;center&gt;\',\'&lt;/center&gt;':'[center]\',\'[/center]').'\')" />
 <input type="button" value="&rarr;" onclick="BBC(\''.$f.'\',\''.(($t==1)?'&lt;div align=&quot;right&quot;&gt;\',\'&lt;/div&gt;':'[right]\',\'[/right]').'\')" />
 <input type="button" value="big" onclick="BBC(\''.$f.'\',\''.(($t==1)?'&lt;big&gt;\',\'&lt;/big&gt;':'[big]\',\'[/big]').'\')" />
 <input type="button" value="small" style="width: 50px" onclick="BBC(\''.$f.'\',\''.(($t==1)?'&lt;span class=&quot;txtm&quot;&gt;\',\'&lt;/span&gt;':'[small]\',\'[/small]').'\')" /> ';
}
?>
