function createxhr(){
	
	try{return new XMLHttpRequest();}catch(e){}
	
	
	try {return new ActiveXObject('Microsoft.XMLHTTP');} catch (e) {}
	
	alert("qing hua liuranqi");};

function $(id){
	return document.getElementById(id);
	// document.getElementsByTagName('')
}