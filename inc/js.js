function Okno(o,w,h,l,t)
{
 window.open(o,'','toolbar=yes,location=no,scrollbars=yes,personalbar=no,directories=no,width='+w+',height='+h+',top='+t+',left='+l)
}
function nw(c,i){Okno('?om=1&co='+c+'&id='+i,450,300,150,200)}

function BBC(t,x,y) {
 f=document.getElementById(t);
 if((typeof f.selectionStart)!='undefined') {
  s=f.selectionStart;
  k=f.selectionEnd;
  ost=f.scrollTop;
  a1=(f.value).substring(0,s);
  a2=(f.value).substring(s,k);
  a3=(f.value).substring(k,f.textLength);
  f.value=a1+x+a2+y+a3;
  f.selectionEnd=(a1+x+a2).length;
  f.scrollTop=ost;
  f.focus(); }
 else { f.value+=x+y; }
}
function setCookie(n,txt,c)
{
 t=new Date();
 t.setTime(cz=(c*60*60*1000)+t.getTime());
 cz=(t.toGMTString());
 document.cookie=n+'='+escape(txt)+'; expires='+cz;
}
function d(c){return document.getElementById(c)}
function Show(c){if(d(c).style.display=='none') d(c).style.display='block'; else d(c).style.display='none'}