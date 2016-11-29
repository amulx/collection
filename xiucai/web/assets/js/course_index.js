$(document).ready(function(){
    // 滚动加载课程
    $(window).scroll(function(){
        // 当滚动到最底部以上100像素时， 加载新内容
        if($(document).height() - $(this).scrollTop() - $(this).height() < 100){
            loadCourse();
        }
    });
});

//加载更多录播课程
function loadCourse(){
    if(loadInfo.hasMore && !loadInfo.isLoading){
        loadInfo.isLoading = true;

        //加载录播课程
        $.post(course_ajax_load_url, {level:loadInfo.level, page:loadInfo.page},
            function(result){
                loadInfo.isLoading = false;
                if(result.code == 200){
                    if(result.data.length > 0){
                        loadInfo.page++;
                        loadInfo.hasMore = (result.course_count >= loadInfo.limit) ? true : false;
                        $.each(result.data , function(index , value){
                            appendCourse(value);
                        });
                    } else {
                        loadInfo.hasMore = false;
                    }
                }else{
                    loadInfo.hasMore = false;
                }
            }
        );
    }
}

//ajax添加录播课程内容
function appendCourse(course){
    var brief;
    if(course.brief.length>105){
        brief = course.brief.substring(0,102)+"…";
    }else{
        brief = course.brief;
    }
    var is_free = (course.current_price == '0.00') ? '免费' : course.current_price;
    //var course_num = (is_free == "免费") ? course.video_num : course.reserve_num;
    var tpl = '<div class="content_list  blank">'+
        '<div class="relative">'+
        '<div class="div_pic fl">'+
        '<a href="'+course_detail_url+'/'+course.id+'" target="_blank"><img width="280" height="192" src="'+course.img_url+'" style="border:1px solid #CCCCCC;"></a>'+
        '</div>'+
        '<div class="word">'+
        '<div class="h_right_box" style="height: 190px;">'+
        '<div class="div_top">'+
        '<p class="title"><a href="'+course_detail_url+'/'+course.id+'" target="_blank" class="title">'+course.title+'</a>'+
        '</p>'+
        '<p class="w_c">'+brief+'</p>'+
        '</div>'+
        '<div class="div_bottom" style="left: 320px;">'+
        '<p class="div_bottom_text">更新于'+course.modify_time+'</p>'+
        '<img src="/assets/img/icon/list/person_black.png"> '+
        '<span>'+course.play_count+'</span>'+
        '<span style="margin-left: 28px;"><img src="/assets/img/icon/list/money_black.png"> '+is_free+'</span>'+
        '<span style="margin-left: 28px;">'+
        '<img src="/assets/img/icon/list/teacher_level.png"> '+course.course_level+'</div>'+
        '</span>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</div>';

    $(".allzhibo .all_recording").append(tpl);
}