function createxhr(){
	try{
		return new XMLHttpRequest();
	}catch(e){

	}

	try{return new ActiveXObject('Microsoft.XMLHTTP');}catch(e){}

	alert("请更换浏览器");
};

function $(id){
	return document.getElementById(id);
}