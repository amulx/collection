$(document).ready(function(){
    // 滚动加载课程
    $(window).scroll(function(){
        // 当滚动到最底部以上100像素时， 加载新内容
        if($(document).height() - $(this).scrollTop() - $(this).height() < 100){
            loadMemberVideo();
        }
    });
});

//加载更多录播课程
function loadMemberVideo(){
    if(loadInfo.hasMore && !loadInfo.isLoading){
        loadInfo.isLoading = true;

        //加载直播课程
        $.post(course_live_ajaxLoad, {rType: loadInfo.rType, page:loadInfo.page},
            function(result){
                loadInfo.isLoading = false;
                if(result.code == 200){
                    loadInfo.page++;
                    loadInfo.hasMore = result.data.length >= 9;
                    if(result.data != ""){
                        $.each(result.data , function(key , value){
                            appendCourse(key, value);
                        });
                    }
                }else{
                    loadInfo.hasMore = false;
                }
            }
        );
    }
}

/**参数说明：
 * 根据长度截取先使用字符串，超长部分追加…
 * str 对象字符串
 * len 目标字节长度
 * 返回值： 处理结果字符串
 */
function cutString(str, len) {
    //length属性读出来的汉字长度为1
    if(str.length*2 <= len) {
        return str;
    }
    var strlen = 0;
    var s = "";
    for(var i = 0;i < str.length; i++) {
        s = s + str.charAt(i);
        if (str.charCodeAt(i) > 128) {
            strlen = strlen + 2;
            if(strlen >= len){
                return s.substring(0,s.length-1) + "...";
            }
        } else {
            strlen = strlen + 1;
            if(strlen >= len){
                return s.substring(0,s.length-2) + "...";
            }
        }
    }
    return s;
}

//ajax添加用户录播课程内容
function appendCourse(key, course){
    var _class = ((key+1)%3 == 0) ? "class='last'" : "";
    var tpl = '<li '+_class+'>'+
        '<div class="myvedio_list_d2">'+
        '<a href="'+course_detail_url+'/'+course.id+'" target="_blank"><img width="200" height="138" src="'+course.img_url+'"></a>'+
        '<div class="content">'+
        '<div class="name"><a href="'+course_detail_url+'/'+course.id+'">'+cutString(course.title,30)+'</a></div>'+
        '<div>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</li>';

    $(".myvedio_list ul").append(tpl);
}