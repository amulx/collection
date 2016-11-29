(function(){
	var $ = function(id){
		return document.getElementById(id);
	};

	$.init=function(){
		try{ return new XMLHttpRequest()}catch(e){}
		try{ return new ActiveXObject('Microsoft.XMLHTTP');}catch(e){}
		alert('error');
	};
	// 用于发送ajax的get请求
	$.get = function(url,data,callback,type){
		var xhr = $.init();
		if (data!=null) {
			url = url+'?'+data;
		}
		xhr.open('get',url);
		xhr.setRequestHeader("If-Modified-Since","0");
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4 && xhr.status==200){
				if (type = null) { type = 'text'; }
				if (type = 'text') { callback(xhr.responseText); }
				if (type = 'xml') { callback(xhr.responseXML); }
				if (type = 'json') {
					var str = eval('('+xhr.responseText+')');
					callback(str);
				}
			}
		};
		xhr.send(null);
	};
	// 用于发送ajax的post请求
	$.post = function(url,data,callback,type){
		var xhr = $.init();
		xhr.open('post',url);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xhr.onreadystatechange=function(){
			if (xhr.readyState==4 && xhr.status==200) {
				if (type == null) {
					type = 'text';
				}
				if (type=='text') {
					callback(xhr.responseText);
				}
				if(type == 'json'){
					var str = eval('('+xhr.responseText+')');
					callback(str);
				}
				if (type == 'xml') {
					callback(xhr.responseXML);
				}
			}
		};
		xhr.send(data);
	}

	window.$ = $;

})();
