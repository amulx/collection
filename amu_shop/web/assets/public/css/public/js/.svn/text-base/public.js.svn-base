/**
 * Author:姜西
 * Design：王立伟
 * Description：公共js整站调用
 * Time:2014/12/22
 */

/***************浏览器低版本升级Begin********************/

$(function(){
		var cookie,
			ua,
			match;
		ua=window.navigator.userAgent;
		match=/;\s*MSIE (\d+).*?;/.exec(ua);
		if(match&&+match[1]<9){
			
			cookie=document.cookie.match(/(?:^|;)\s*ic=(\d)/);
			if(cookie&&cookie[1]){
				return ;
			}
		
			$("body").prepend([
				"<div id='js-compatible' class='compatible-contianer'>",
					"<p class='cpt-ct'><i></i>您的浏览器版本过低。为保证最佳浏览效果，<a href='http://ie.microsoft.com/' target='_blank'>请点此更新高版本浏览器</a></p>",
					"<div class='cpt-handle'><a href='javascript:;' class='cpt-agin'>以后再说</a><a href='javascript:;' class='cpt-close'><i></i></a>",
				"</div>"
			].join(""));
			$("#js-compatible .cpt-agin").click(function(){
				var d=new Date();
				d.setTime(d.getTime()+30*24*3600*1000);
				//d.setTime(d.getTime()+60*1000);
				document.cookie="ic=1; expires="+d.toGMTString()+"; path=/";
				$("#js-compatible").remove();
			});
			$("#js-compatible .cpt-close").click(function(){
				$("#js-compatible").remove();
			});
		}
	});
/***************浏览器低版本升级End********************/



/***************鼠标经过导航切换Begin******************/
	$(function(){
		
		$(".menu").hover(function(){
				$(this).addClass("menuHover");
				$(this).removeClass("menu");
			},function(){
				$(this).addClass("menu");
				$(this).removeClass("menuHover");
				})
		});
/***************鼠标经过导航切换End******************/

/***********鼠标经过微信公共号 Begin*************/
	$(function(){
		$(".smallWeixin").hover(function(e) {
            $(".bigWeixin").toggle();
        });
		})
/***********鼠标经过微信公共号End*************/

/*************事件冒泡Begin****************/
	function estop(e){
		var e=arguments.callee.caller.arguments[0]||event;
		if (e && e.stopPropagation){
		//因此它支持W3C的stopPropagation()方法
		   e.stopPropagation();
		}else{
		//否则，我们需要使用IE的方式来取消事件冒泡
		   window.event.cancelBubble = true;
		   return false;
		}
	}
/*************事件冒泡End****************/

/*************二级导航效果 Begin***************/
	$(function(){
	/*	$(".userInfo").hover(function(e) {
			$(".userInfoTop").css({"border-left-color":"#E5E5E3","border-right-color":"#E5E5E3"});
			$(".userInfo .userInfoTop p").removeClass("noneInfo").addClass("blockInfo");
			$(".userInfoList").slideDown();

        	},function(e){
				$(".userInfoList").slideUp();
				
				$(".userInfoTop").css({"border-left-color":"#fff","border-right-color":"#fff"});
				$(".userInfo .userInfoTop p").removeClass("blockInfo").addClass("noneInfo");
				});
				
		$(".serInfo").hover(function(e) {
			$(".Isserver").css({"border-left-color":"#E5E5E3","border-right-color":"#E5E5E3"});
			$(".serverInfoList").slideDown();

        	},function(e){
				$(".Isserver").css({"border-left-color":"#fff","border-right-color":"#fff"});
				$(".serverInfoList").slideUp();
				});
			*/
		
	
                $('.userInfoList').hide();
                $('.userInfo')
		        .mouseenter(function(e) {
			        var $elm = $(this);
			        $elm.data('mouseover', true); 
			        setTimeout(function(){if($elm.data('mouseover'))$elm.find('.userInfoList:hidden').slideDown();},100); 
					$(".userInfoTop").css({"border-left-color":"#E5E5E3","border-right-color":"#E5E5E3"});
					$(".userInfo .userInfoTop p").removeClass("noneInfo").addClass("blockInfo");
					})
		        .mouseleave(function(e) {
			        var $elm = $(this);
			        $elm.data('mouseover', false);
			        $elm.children('.userInfoList:visible').slideUp();
					$(".userInfoTop").css({"border-left-color":"#fff","border-right-color":"#fff"});
					$(".userInfo .userInfoTop p").removeClass("blockInfo").addClass("noneInfo");
		        });
				
				$('.serverInfoList').hide();
                $('.serInfo')
		        .mouseenter(function(e) {
			        var $elm = $(this);
			        $elm.data('mouseover', true); 
			        setTimeout(function(){if($elm.data('mouseover'))$elm.find('.serverInfoList:hidden').slideDown();},100); 
					$(".Isserver").css({"border-left-color":"#E5E5E3","border-right-color":"#E5E5E3"});
					})
		        .mouseleave(function(e) {
			        var $elm = $(this);
			        $elm.data('mouseover', false);
			        $elm.children('.serverInfoList:visible').slideUp();
					$(".Isserver").css({"border-left-color":"#fff","border-right-color":"#fff"});
		        })
            });
/*************二级导航效果 End***************/



/**************弹出框 Begin*******************/
	//显示黑色 jQuery 遮罩层 
	function platBt(popCon) {
		$("#BgMask").remove();
		$("body").prepend("<div id='BgMask'></div> ");
		var bh = $("body").height(); 
		var bw = $("body").width();
		var Wbh = $(window).height();
		var Wbw = $(window).width(); 
		var Wleft =(Wbw-$("#"+popCon).width())/2;
		if(Wbh>bh){
			bh=Wbh;
			}
		$("#BgMask").css({ 
			height:bh, 
			width:bw
		});
		
		$("#"+popCon).css({
			left:Wleft
		});
		$("#BgMask").fadeIn(1000); 
		$("#"+popCon).fadeIn(500);  
	} 
	//关闭黑色 jQuery 遮罩 
	function plateClose(popCon) { 
		$("#"+popCon).fadeOut(500); 
		$("#BgMask").fadeOut(500,function(){
			$("#BgMask").remove();
			});
	} ;

/**************弹出框 END*******************/


/************登陆验证Begin*************/
	$(function(){
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; //验证邮箱的正则表达式
		$(".email").focus(function(){
			$(this).css({"border-color":"#7A9ECE"});
			$(this).siblings("h6.msgError").remove();
		});
		$(".email").blur(function(){
			if($(this).val()==""||$(this).val()==null){
				if(!$(this).siblings().hasClass("msgError")){
					$(this).css({"border-color":"#F3696E"});
					$(this).after("<h6 class='msgError'>请输入真实有效的邮箱</h6>");
					$(this).parent("li").attr("class","none");
				}
			}
			else if(!reg.test($(this).val())){
				if(!$(this).siblings().hasClass("msgError")){
					$(this).css({"border-color":"#F3696E"});
					$(this).after("<h6 class='msgError'>请输入真实有效的邮箱</h6>");
					$(this).parent("li").attr("class","none");
					}
				}else{
					$(this).css({"border-color":"#E1E1E1"});
					$(this).parent("li").addClass("emailLi")
					}
		});
		$(".email").keyup(function(){
			this.value=this.value.replace(/\s/g,'');
			});
		$(".password").focus(function(){
			$(this).css({"border-color":"#7A9ECE"});
			$(this).siblings("h6.msgError").remove();
		});
		$(".password").blur(function(){
			if($(this).val()==""||$(this).val()==null){
				if(!$(this).siblings().hasClass("msgError")){
					$(this).css({"border-color":"#F3696E"});
					$(this).after("<h6 class='msgError'>请输入密码</h6>");
					$(this).parent("li").attr("class","none");
				}
			}
			else{
				$(this).css({"border-color":"#E1E1E1"});
				}
		});
		
		$(".password").keyup(function(){
			this.value=this.value.replace(/\s/g,'');
			});
	})
	
	function subMyLogin(){
            var flag = "yes";
			var email,password,globalVar;
			email = $(".email").val();
			password = $(".password").val();
			if(email==null||email==""){
				if(!$(".email").siblings().hasClass("msgError")){
					$(".email").css({"border-color":"#F3696E"});
					$(".email").after("<h6 class='msgError'>请输入真实有效的邮箱</h6>");
                    var flag = "no";
				}
			}
			
			if(password==null||password==""){
				if(!$(".password").siblings().hasClass("msgError")){
					$(".password").css({"border-color":"#F3696E"});
					$(".password").after("<h6 class='msgError'>密码错误</h6>");
                    var flag = "no";
				}
			}

            if(flag=='yes'){
                $.ajax({
                    type: "POST",
                    url: user_login,
                    data: { email : email,  password : password },
                    success: function(result){
                        if(result.code == 200){
                            alert("login_backFunction is success");
                        }
                    }
                });
            }


			
		}
/************登陆验证End*************/

//过滤非法字符  
function stripscript(s){   
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！%`@#￥……&*（）——|''‘’‘：”“'、？]");    
	var rs = "";   
	for (var i = 0; i < s.length; i++) {   
		rs = rs+s.substr(i, 1).replace(pattern, '');   
	}   
	return rs; 
}



/* hover 动作	---淡入淡出*/
/*personal	------包含所有对象的父节点
  perChild	------触发动作的对象
  findObj	------淡入淡出的对象
*/
function hoverOpacityFun(personal,perChild,findObj){
	var $childs = $(personal).find(perChild);
	
	$childs.each(function(index, element) {
		$(this).hover(
				function(){
					$(this).addClass("hover");
						},
				function(){
					$(this).removeClass("hover");
					
				});
    });

	$childs.each(function(index, element) {
		
		
		
		$(this).hover(
				function(){
					setTimeout(function(){ 
						if ($(element).hasClass('hover')){
							$(element).find(findObj).stop(true,true).show().animate({opacity:'1'},200);
							} 
						else{
							return;}
					},100);
							},
				function(){
					setTimeout(function(){ 
						if (!$(element).hasClass('hover')){
							$(element).find(findObj).stop(true,true).animate({opacity:'0'},200);
							} 
						else{
							return;}
					},100);
					
				});
				
				
				
				
				
				
				
    });
	
}
/* hover 动作	---淡入淡出*/


//点击回到顶部	
function getpos(e){
	var toTop = $(e).offset().top;
	$("html,body").animate({"scrollTop":(toTop-90)+"px"},300);
}

function p_confirm(msg) {   
	var msg = msg;   
	if (confirm(msg)==true){   
	return true;   
	}else{   
	return false;   
	}   
} 
//详情页选项卡
$(function(){
	$(".tabStyle dd").click(function(e) {
        $(this).addClass("tabHover").siblings().removeClass("tabHover");
    });
	});

//添加iframe
function addIframe(addDivID,ifSrc,noclose,fun){
    var $haveLib = $("#"+addDivID);

    if($haveLib.length <= 0){
        //创建DIV DOM
        var choose = document.createElement("div");
        choose.setAttribute("id",addDivID);
        //创建iframe DOM
        var iframe = document.createElement("iframe");
        iframe.setAttribute("src",ifSrc);
        iframe.setAttribute("name",addDivID+"Iframe");
        iframe.setAttribute("id",addDivID+"Iframe");

        choose.appendChild(iframe);
        if(noclose!='noclose'){
            //创建关闭按钮 DOM
            var closeObj = document.createElement("div");
            closeObj.setAttribute("class","close-pop");
            if(fun == '' || fun == null){
                closeObj.setAttribute("onclick","cloose('"+addDivID+"');");
            }
            else{
                closeObj.setAttribute("onclick",fun+"()");
            }
            choose.appendChild(closeObj);
        }

        document.body.appendChild(choose);
    }
    else{
        popUpBox(addDivID);
    }



    //加载完成后执行
    var oFrm = document.getElementById(addDivID+"Iframe");
    oFrm.onload = oFrm.onreadystatechange = function() {
        if (this.readyState && this.readyState != 'complete') return;
        else {
            //iframe宽高自适应
            var iframeObj = window.frames[addDivID+"Iframe"].document;
            var height = $(iframeObj).find("#"+addDivID).height();
            var width = $(iframeObj).find("#"+addDivID).width();

            $("#"+addDivID).css({"width":width,"height":height,"border":0,"position":"relative","display":"none"})
                .find("iframe").css({"width":width,"height":height,"border":0,"position":"relative"});

            popUpBox(addDivID);

        }
    }

}

function popUpBox(divPopUpBox){
    var htmlWidth = document.body.clientWidth;
    var htmlHeight = document.body.clientHeight;
    var borwerViewWidth =  document.documentElement.clientWidth;
    var borwerViewHeight =  document.documentElement.clientHeight;

    var scrollTop = getScrollTop();

    var getw =$("#"+divPopUpBox).width();
    var geth = $("#"+divPopUpBox).height();

    if(getheight == '' || getheight == null){
        getheight = 0;
    }

    var getwidth = parseInt(getw);
    var getheight = parseInt(geth);

    var topWidth = (borwerViewWidth - getwidth)/2;
    var topHeight = (borwerViewHeight - getheight)/ 2;

    $("#greybackground").remove();
    $("body").prepend('<div id="greybackground"></div>');
    //$("html,body").animate({ scrollTop:0},0);
    //$("html,body").css({ "overflow": "hidden"});




    $("#"+divPopUpBox).css({
        "position": "absolute",
        "top": scrollTop+50 + "px" ,
        "left": topWidth+"px",
        "z-index":"10001"})
        .fadeIn(300);

    $("#greybackground").css({
        "position":"absolute",
        "top":"0px",
        "left":"0px",
        "z-index":"10000",
        "height": htmlHeight+"px",
        "width": htmlWidth+"px",
        "background":"black",
        "opacity": "0.8",
        "display":"none"})
        .fadeIn(300);



}



function cloosePopBox(divPopUpBox){
    $("#greybackground").fadeOut(300);
    $("#"+divPopUpBox).fadeOut(300);
}
function cloose(divPopUpBox){
    $("#greybackground").remove();
    $("#"+divPopUpBox).remove();
}

//要获取当前页面的滚动条纵坐标位置
function getScrollTop(){
    if(document.documentElement.scrollTop)
    {return document.documentElement.scrollTop;}
    else
    {return document.body.scrollTop}
}
//列表最后一行加底边
$(function(){
    $(".orderList ul:last").css("border-bottom","1px solid #fbf9fa");

})