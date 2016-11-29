$(document).ready(function(){
    // 滚动加载课程
    $(window).scroll(function(){
        // 当滚动到最底部以上100像素时， 加载新内容
        if($(document).height() - $(this).scrollTop() - $(this).height() < 100){
            loadLive();
        }
    });

    //倒计时
    $(".countdown").countdown({
        tmpl : '<li class="hour"><p>%{h}</p></li><li class="colon fl">:</li><li class="hour"><p>%{m}</p>' +
            '</li><li class="colon fl">:</li><li class="hour"><p>%{s}</p></li>'
    });
});

//加载更多录播课程
function loadLive(){
    if(loadInfo.hasMore && !loadInfo.isLoading){
        loadInfo.isLoading = true;

        //加载直播课程
        $.post(course_live_ajaxLoad, {level:loadInfo.level, rType: loadInfo.rType, page:loadInfo.page},
            function(result){
                loadInfo.isLoading = false;
                if(result.code == 200){
                    loadInfo.page++;
                    loadInfo.hasMore = (result.course_count >= loadInfo.limit) ? true : false;
                    $.each(result.data , function(key , value){
                        appendLive(key, value);
                    });

                    //执行倒计时
                    $(".countdown").countdown({
                        tmpl : '<li class="hour"><p>%{h}</p></li><li class="colon fl">:</li><li class="hour"><p>%{m}</p>' +
                            '</li><li class="colon fl">:</li><li class="hour"><p>%{s}</p></li>'
                    });
                }else{
                    loadInfo.hasMore = false;
                }
            }
        );
    }
}

//ajax添加直播课程内容
function appendLive(key, liveCourse){
    var d          = new Date();
    var currentD = d.getDate();
    if(currentD<10){
        currentD = '0'+currentD;
    }
    var str        = d.getFullYear()+""+(d.getMonth()+1)+""+currentD;
    var s          = key;
    var month      = s.substring(4, 6);
    var day        = s.substring(6, 8);
    var today_date = s.substring(0, 8);
    var parent_key = key;
    if($('#day_box_'+key).length > 0){
        var time_box = '';
    }else{
        var time_box = '<div style="padding-top: 10px;" class="blank">'+
            '<div class="day_box" id="day_box_'+key+'">'+month+'月'+day+'日</div>'+
            '</div>';
    }

    $.each(liveCourse , function(key2 , live){
        var schedule_time = live.schedule_time;
        if(loadInfo.rType == 'new'){
            var content_h = '';
            var line_b = '';
            var countdown ='';
            //alert(str);
            if(str == today_date){
                if(live.time_left == 0){
                    var countdown =
                        '<ul>'+
                        '<li style="color: #ed7101;font-size: 18px;height: 70px;line-height: 70px;">正在直播。。。'+
                        '</li>'+
                        '</ul>';
                }else{
                    var countdown =
                    '<ul style="height: 70px;" class="countdown" data-diff="'+live.time_left+'">'+
                    '<li class="hour">'+
                    '<p>'+schedule_time.substring(11, 13)+'</p>'+
                    '</li>'+
                    '<li class="colon fl">:</li>'+
                    '<li class="hour">'+
                    '<p id="p_minute_1">'+schedule_time.substring(14, 16)+'</p>'+
                    '</li>'+
                    '<li class="colon fl">:</li>'+
                    '<li class="hour">'+
                    '<p>'+schedule_time.substring(17, 19)+'</p>'+
                    '</li>'+
                    '</ul>';
                }
            }else{
                var countdown = '';
                var line_b = 'style="height: 206px;"';
                var content_h = 'style="padding-top: 0px;"';
            }
        }else{
            var countdown = '';
            var line_b = 'style="height: 206px;"';
            var content_h = 'style="padding-top: 0px;"';
        }
        var brief;
        if(live.brief.length>105){
            brief = live.brief.substring(0,102)+"…";
        }else{
            brief = live.brief;
        }
        var is_free = (live.current_price == '0.00') ? '免费' : live.current_price;
        var tpl = time_box+'<div class="appointment_list">'+
            '<div>'+
            '<div class="div1"></div>'+
            '<div class="fl" style="">'+
            '<div class="time fl">'+schedule_time.substring(11,16)+'</div>'+
            '<div class="circle"></div>'+
            '<div class="line_b" '+line_b+'></div>'+
            '</div>'+
            '<div class="app_r">'+countdown+
            '<div style="padding-top: 15px;" class="content" '+content_h+'>'+
            '<div class="relative">'+
            '<div class="div_pic fl">'+
            '<a href="'+course_live_detail+'/'+live.id+'" target="_blank"><img width="280" height="192" src="'+live.img_url+'" style="border:1px solid #CCCCCC;"></a>'+
            '</div>'+
            '<div class="word">'+
            '<div class="h_right_box" style="height: 190px;">'+
            '<div class="div_top">'+
            '<p class="title">'+
            '<a href="'+course_live_detail+'/'+live.id+'" target="_blank">'+live.title+'</a>'+
            '</p>'+
            '<p class="w_c">'+brief+'</p>'+
            '</div>'+
            '<div class="div_bottom" style="left: 320px">'+
            '<p style="padding-bottom: 20px;" class="div_bottom_text" style="font-size: 12px;">时间：'+live.time_length+'</p>'+
            '<img src="/assets/img/icon/list/person_black.png">'+
            '<span> '+live.reserve_count+'</span>'+
            '<span style="margin-left: 28px;"><img src="/assets/img/icon/list/money_black.png"> '+is_free+'</span>'+
            '<span style="margin-left: 28px;">'+
            '<img src="/assets/img/icon/list/teacher_level.png"> '+live.course_level+
            '</span>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div><div class="clear"></div>';

        $(".allzhibo .my_appointment").append(tpl);

        if($('#day_box_'+parent_key).length > 0){
            time_box = '';
        }
    });
}