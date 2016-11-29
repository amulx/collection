$(function(){
  price = [];
  title = [];
  $(".gl-item").each(function(){
       price.push($(this).find('.J_price').text());//获取所有价格
       title.push($(this).find('.p-name em').text());//获取所有标题
  });
  console.log(price);
  console.log(title);
  console.log(window.location.href);

function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}

console.log(GetQueryString("page"));
  //异步提交数据
 //  	$.ajax({
	// 	url:"http://www.3dclose.com/index.php",
	// 	data:{
	// 	    	g:'CloseShow',
	// 	    	m:'index',
	// 	    	a:'getdata',
	// 	    	jobs:jobs
	// 	},
	// 	type:"get",
	// 	dataType:'jsonp',
	// 	success:function(data){
	// 		console.log(JSON.stringify(data));
	// 	},
	// 	error:function(er){
	// 		console.log(JSON.stringify(er));
	// 	}
	// });
});
