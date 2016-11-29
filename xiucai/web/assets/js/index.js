$(document).ready(function(){
    $("#reg_a").live("click", function(){
        $("#reg_div").show();
        $('.layer_bg').show();
    });
    $("#login_a").live("click", function(){
        $("#login_div").show();
        $('.layer_bg').show();
    });
    $("#index_banner_reg").live("click", function(){
        $("#reg_div").show();
        $('.layer_bg').show();
    });
    $("#close_reg").live("click", function(){
        $("#reg_div").hide();
        $('.layer_bg').hide();
    });
    $("#close_login").live("click", function(){
        $("#login_div").hide();
        $('.layer_bg').hide();
    });
    $("#close_feed_back").live("click", function(){
        $("#feed_back_div").hide();
        $('.layer_bg').hide();
        location.reload();
    });
    $("#close_find_back").live("click", function(){
        $("#find_back_div").hide();
        $('.layer_bg').hide();
        location.reload();
    });
    $("#close_find_pass").live("click", function(){
        $("#find_pass_div").hide();
        $('.layer_bg').hide();
        location.reload();
    });
    $("#login_div .login1_box .find_pass_pop").live("click", function(){
        $("#login_div").hide();
        $("#reg_div").hide();
        $("#feed_back_div").hide();
        $("#find_back_div").hide();
        $('#find_pass_div').show();
        $('.layer_bg').show();
    });
    $("#find_pass_div .back_login_pop").live("click", function(){
        $('#find_pass_div').hide();
        $("#login_div").show();
    });


    //alert(333);
    //alert(document.body.clientWidth);
    $("#banner").attr("width",document.body.clientWidth);
    //$(".top_div").attr("width",document.body.clientWidth);
});
/*function _fresh()
{
    var endtime=new Date("December 20, 2014 20:00:00");//这里设置的时间为2011年，您可以修改为其它时间。
    var nowtime = new Date();
    var leftsecond=parseInt((endtime.getTime()-nowtime.getTime())/1000);
    if(leftsecond<0){leftsecond=0;}
    __d=parseInt(leftsecond/3600/24);
    __h=__d*24;
    __h+=parseInt((leftsecond/3600)%24);
    __m=parseInt((leftsecond/60)%60);
    __s=parseInt(leftsecond%60);
    $("#dj").html(__h+":"+__m+":"+__s);
}
_fresh();
setInterval(_fresh,1000);
function _fresh_1()
{
    var endtime=new Date("December 11, 2014 20:00:00");//这里设置的时间为2011年，您可以修改为其它时间。
    var nowtime = new Date();
    var leftsecond=parseInt((endtime.getTime()-nowtime.getTime())/1000);
    if(leftsecond<0){leftsecond=0;}
    __d=parseInt(leftsecond/3600/24);
    __h=__d*24;
    __h+=parseInt((leftsecond/3600)%24);
    __m=parseInt((leftsecond/60)%60);
    __s=parseInt(leftsecond%60);
    $("#dj_1").html(__h+":"+__m+":"+__s);
}
_fresh_1();
setInterval(_fresh_1,1000);*/