//Link
function InsLink(f,bbc)
{
 a=prompt(langadr,'http://');
 if(a && a!='http://') {
  b=prompt(langtxt,'');
	if(bbc==1) {
	 if(b) { c='[url='+a+']'+b+'[/url]' } else { c='[url]'+a+'[/url]' }
	}
	else {
	 d=confirm(langnw); c='<a href="'+a+'"'+((d)?' target="_blank"':'')+'>'+((b)?b:a)+'</a>'
	}
	BBC(f,c,'');
 }
}

//E-mail
function InsMail(f,bbc)
{
 a=prompt(langadr,'');
 if(a) {
  b=a.replace('@','&#64;');
  if(bbc==1) { c='[email]'+b+'[/email]' } else { c='<a href="mailto:'+b+'">'+b+'</a>' }
  BBC(f,c,'');
 }
}

//Buttons
var tags=['b','i','u','s','d','g','center','right','','quote','code'];
var specchr=['amp','reg','copy','trade','sect','deg','middot','bull','lt','gt','raquo'];

var color=['white','#c9c9c9','yellow','orange','red','#9de9f9','#7eebaa','teal','black','gray','olive','gold','brown','blue','green','navy'];

var html=['b','i','u','s','sub','sup','center','div align="right"','','div class="quote"','div class="code"><code'];
var ehtml=['b','i','u','s','sub','sup','center','div','','div','code></div'];

var EntID='';

function Format(id,i,bbc)
{
 switch(i)
 {
	case 13: EntID=id; Hint('chars',cx-10,cy,1); break;
	case 8: EntID=id; Hint('colors',cx-10,cy,1); break;
	case 11: InsLink(id,bbc); break;
	case 12: InsMail(id,bbc); break;
	case 14: if(bbc==1) BBC(id,'[img]','[/img]'); else BBC(id,'<img src="','" />'); break;
	default: if(bbc==1) BBC(id,'['+tags[i]+']','[/'+tags[i]+']'); else BBC(id,'<'+html[i]+'>','</'+ehtml[i]+'>')
 }
}

function TextTools(id,bbc)
{
 var i,y;
 document.write('<div class="tools"><img src="img/icon/tools.png" alt="Tools" usemap="#toolmap" /><map name="toolmap">');
 y=0;
 for(i=0;i<15;i++)
 {
  document.write('<area coords="'+y+',0,'+(y+20)+',19" href="javascript:Format(\''+id+'\','+i+','+bbc+')" />');
	if(i==8) y+=38; else y+=21;
 }
 document.write('</map></div>');
 
 if(EntID=='')
 {
	document.write('<div class="hint" id="chars" style="cursor: pointer"><table cellspacing="0" cellpadding="5"><tbody align="center">');
	y=1;
	for(i=0;i<11;i++)
	{
	 document.write(((y==1)?'<tr>':'')+'<td onclick="BBC(EntID,\'&amp;'+specchr[i]+';\',\'\')">&'+specchr[i]+';</td>'+((y==4)?'</tr>':''));
	 if(y==4) y=1; else y++;
	}
	document.write('<td onclick="BBC(\''+id+'\',\'&amp;bdquo;\',\'&amp;rdquo;\')">&bdquo;&rdquo;</td></tr></tbody></table></div>'
	
	+'<div class="hint" id="colors" style="cursor: pointer"><table style="width: 130px; height: 40px"><tbody>');
	y=1;
	for(i=0;i<16;i++)
	{
	 document.write(((y==1)?'<tr>':'')+'<td style="background-color: '+color[i]+'" onclick="BBC(EntID,\''+((bbc==1)?'[color='+color[i]+']\',\'[/color]':'&lt;span style=&quot;color: '+color[i]+'&quot;&gt;\',\'&lt;/span&gt;')+'\')"></td>'+((y==8)?'</tr>':''));
	 if(y==8) y=1; else y++;
	}
	document.write('</tbody></table></div>');
 }
}