var IE=document.all?true:false;
var cx,cy;
var toHide=new Array();

//Mysz
function XY(e)
{
 if(IE){cx=event.clientX+document.body.scrollLeft;cy=event.clientY+document.body.scrollTop}
 else{cx=e.pageX;cy=e.pageY}
 if(cx<0){cx=0}
 if(cy<0){cy=0}
}

document.onmousedown=XY;
document.onclick=function()
{
 var l=toHide.length;
 if(l>0){for(var i=0;i<l;i++){Hint(toHide[i],0,0,1)}}
}

//HINTY
function Hint(i,l,t,u)
{
 with(d(i).style){
	if(display!='block'){
   if(t!=0){left=l+'px';top=t+'px'}
	 display='block';
	 if(u==1)setTimeout('toHide.push("'+i+'")',10);
	}
	else{
	 if(u==1) toHide.pop(i);
	 display='none';
	}
 }
}

//HTTP
function Request()
{
 //Parametr
 this.add=function(a,b)
 {
  this.p1.push(encodeURIComponent(a)+'='+encodeURIComponent(b));
 }
 //Reset
 this.reset=function()
 {
  this.http=false;
	this.url='';
  this.method='GET';
	this.p1=new Array();
 }
 this.reset();
}

//Start
Request.prototype.run=function()
{
 if(window.XMLHttpRequest)
 {
	this.http=new XMLHttpRequest();
	if(this.http.overrideMimeType) this.http.overrideMimeType('text/xml');
 }
 //IE
 else if(window.ActiveXObject)
 {
	try { this.http=new ActiveXObject("Msxml2.XMLHTTP"); } catch(e) { try { this.http=new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) { if(this.errmsg) alert(this.errmsg); } }
 }
 if(!this.http) return false;
 
 var self=this;
 this.http.onreadystatechange=function()
 {
	switch(self.http.readyState) {
	 case 4: self.Done(self.http.responseText); break;
	 default: self.Loading();
	}
 }
 if(this.url!='')
 {
	//Parametry
	if(this.method!='POST')
	{
	 this.p=null;
	}
	else
	{
	 this.p=this.p1.join('&');
	}
	this.http.open(this.method,this.url,true);
	if(this.method=='POST') this.http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	this.http.send(this.p);
 }
}