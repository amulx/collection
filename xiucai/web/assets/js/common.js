function checkMobile(v){
    var reg=/((15)\d{9})|((13)\d{9})|((18)\d{9})|(0[1-9]{2,3}\-?[1-9]{6,7})/i;
    if(reg.test(v)){
        return true;
    }else{
        return false;
    }
}

function checkTellphone(v)
{
    var result=v.match(/\d{3}-\d{8}|\d{4}-\d{7}/);
    if(result==null) return false;
    return true;
}

/*function b(){
    h = $(window).height();
    t = $(document).scrollTop();
    if(t > h){
        $('.gtop').show();
    }else{
        $('.gtop').hide();
    }
}
$(document).ready(function(e) {
    b();
});
$(window).scroll(function(e){
    b();
});

(function(){
    function xc (){
        //页面滚动
        this.GoTop=function(v) {
            if(!v)v=0;
            if ( jQuery.browser.safari ){
                $('body').animate({scrollTop:v},'slow');return false;
            } else {
                $('html').animate({scrollTop:v},'slow');return false;
            }

        };
        this.scrollToTop = function (e) {
            $(e).hide().removeAttr("href");
            if ($(window).scrollTop() != "0") {
                $(e).fadeIn("slow")
            }
            var scrollDiv = $(e);
            $(window).scroll(function () {
                if ($(window).scrollTop() == "0") {
                    $(scrollDiv).fadeOut("slow")
                } else {
                    $(scrollDiv).fadeIn("slow")
                }
            });
            $(e).click(function () {
                $("html, body").animate({
                    scrollTop: 0
                }, "slow")
            })
        }
    }
    var _xc=new xc();
    window.xc=_xc;
})();*/
/*$(function () {
    div = $('<div></div>');
    div.css('background-color', '#f2dede');
    div.css('width', '480px');
    div.css('position', 'fixed');
    div.css('left', '0px');
    div.css('right', '0px');
    div.css('margin-left', 'auto');
    div.css('margin-right', 'auto');
    div.css('top', '30px');
    div.css('z-index', '10000');
    div.css('padding-bottom', '14px');
    div.css('padding-top', '14px');
    div.css('border-color', '#eed3d7');
    div.css('color', '#b94a48');
    div.css('border-radius', '4px')
    div.css('border-width', '2px');
    div.css('border-style', 'solid');
    div.css('margin-bottom', '20px');
    div.css('text-shadow', '0 1px 0 rgba(255, 255, 255, 0.5)');
    div.css('text-align', 'right');


    var btn = $('<button>×</button>');
    btn.css('line-height', '20px');
    btn.css('position', 'relative');
    btn.css('right', '15px');
    btn.css('top', '-10px');
    btn.css('background', 'none repeat scroll 0 0 transparent');
    btn.css('border', '0 none');
    btn.css('cursor', 'pointer');
    btn.css('padding', '0');
    btn.css('color', '#000');
    btn.css('float', 'right');
    btn.css('font-size', '20px');
    btn.css('font-weight', 'bold');
    btn.css('line-height', '20px');
    btn.css('opacity', '0.2');
    btn.css('text-shadow', '0 1px 0 #fff');


    var span = $('<span><p align="center" id="alert_content"></p></span>');

    div.append(btn);
    div.append(span);
    div.hide();

    $("body").append(div);

    btn.click(function () {
        div.fadeOut('slow');
    });

    window.alert = function () {
        var str = ' '
        for (var i = 0; i < arguments.length; i++) {
            str += arguments[i] + ' ';
        }
        $("#alert_content").text(str);
        div.fadeIn('slow', function () {
            setTimeout(function () {
                div.fadeOut('slow');
            }, 2000);
        });

    }
})*/


$(document).ready(function(){
    $('#login_div input,#reg_div input,#find_pass_div input').placeholder({isUseSpan:true,onInput:false});
    document.onkeydown = function (e) {
        var theEvent = window.event || e;
        var code = theEvent.keyCode || theEvent.which;
        if (code == 13) {
            $(".btn_sub").click();
        }
    }
});