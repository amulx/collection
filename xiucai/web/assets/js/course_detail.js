$(document).ready(function(){
    $('.temp').mouseover(function(){
        if($('#play_video').is(":hidden")){
            $('#play_video').show();
        }
    });
    $('.temp').mouseleave(function(){
        if(!$('#play_video').is(":hidden")){
            $('#play_video').hide();
        }
    });

    /*if(window.location.search == '?type=chapter'){
        var auto_chapter_id = $('.introduce2_list').find("li:first").attr('data-chapter');
        $.post(course_ajax_chapter,{chapter_id: auto_chapter_id, course_id: commentParam.course_id},
            function(result){
                if(result.code == 200){
                    playVideo(result.data);
                    $('#play_video_btn').hide();
                }else{
                    $('.login_layer #callback').val(window.location.href);
                    $("#login_div").show();
                    return false;
                }
            }
        );
    }*/



    //倒计时
    $(".countdown").countdown({
        tmpl : '<dl><dd class="fl"><div class="hour"><span>%{h}</span></div></dd><dd class="fl">' +
            '<div class="t_s"><span class="time_zone_span">:</span></div>' +
            '</dd><dd class="fl"><div class="hour"><span>%{m}</span></div></dd><dd class="fl">' +
            '<div class="t_s"><span class="time_zone_span">:</span></div>' +
            '</dd><dd class="fl"><div class="hour"><span>%{s}</span></div></dd>' +
            '</dl>'
    });

    //直播进入房间
    $('.play_live_course').live('click', function(){
        if($(this).hasClass('btn_sub_gray')){
            return false;
        }

        //登录提示
        if(commentParam.u_id == 0 || commentParam.u_id == "" || commentParam.u_id == '0'){
            $("#login_div").show();
            $(".layer_bg").show();
            $('.login_layer #callback').val(live_detail_url);
            return;
        }

        //直播状态处理
        $.post(live_status_url,{course_id: commentParam.course_id},
            function(result){
                if(result.code == 200){
                    var con = '<a href="'+enter_live_room+'" target="_blank" style="font-size: 14px;color: #FFFFFF;font-weight: bold">'+
                        '<div style="width:190px;height: 40px;padding:0;line-height: 40px;text-align: center" class="btn_sub">进入教室'+
                        '</div></a>';
                    $('#live_btn_con').html(con);
                }else if(result.code == 501){
                    layer.alert('报名成功');
                    var con = '<a href="'+enter_live_room+'" target="_blank" style="font-size: 14px;color: #FFFFFF;font-weight: bold">'+
                        '<div style="width:190px;height: 40px;padding:0;line-height: 40px;text-align: center" class="btn_sub">进入教室'+
                        '</div></a>';
                    $('#live_btn_con').html(con);
                }else if(result.code == 404){
                    $("#login_div").show();
                    $(".layer_bg").show();
                    $('.login_layer #callback').val(live_detail_url);
                }else if(result.code == 500){
                    layer.alert(result.msg);
                    $('.play_live_course').html('直播尚未开始');
                    $('.play_live_course').addClass('btn_sub_gray');
                    $('.play_live_course').removeClass('btn_sub play');
                    $('.play_live_course').removeClass('play_live_course');
                    return false;
                }else if(result.code == 301){
                    //跳转到支付页面
                    addToCart(commentParam.course_id, 2);
                }else{
                    layer.alert(result.msg);
                    return false;
                }
            }
        );
    });

    //录播课程按钮判断
    $('.play_video_course').click(function(){
        //登录提示
        if(commentParam.u_id == 0 || commentParam.u_id == "" || commentParam.u_id == '0'){
            $("#login_div").show();
            $(".layer_bg").show();
            $('.login_layer #callback').val(course_detail_url);
            return;
        }

        //录播状态处理
        $.post(course_status_url,{course_id: commentParam.course_id},
            function(result){
                if(result.code == 200){
                    location.href = course_detail_url+'?type=chapter';
                    return;
                }else if(result.code == 404){
                    $("#login_div").show();
                    $(".layer_bg").show();
                    $('.login_layer #callback').val(live_detail_url);
                }else if(result.code == 500){
                    layer.alert(result.msg);
                    $('.play_video_course').html('观看课程');
                    return false;
                }else if(result.code == 301){
                    //跳转到支付页面
                    addToCart(commentParam.course_id, 1);
                }else{
                    layer.alert(result.msg);
                    return false;
                }
            }
        );
    });

    //老师点赞
    $('.right_content .btn_zan').live('click', function(){
        //登录提示
        if(commentParam.u_id == 0 || commentParam.u_id == "" || commentParam.u_id == '0'){
            $("#login_div").show();
            $(".layer_bg").show();
            $('.login_layer #callback').val(course_detail_url);
            return false;
        }

        var _this = $(this);
        var teacher_id = $(this).attr('teach-id');
        if(teacher_id != ''){
            $.post(course_favorite_teach,{teacher_id: teacher_id},
                function(result){
                    if(result.code == 200){
                        _this.attr('class','already_zan');
                        _this.html('已赞同');
                        $('.right_mid_content .zan_num').html(result.favorite_count);
                    }else{
                        layer.alert(result.msg);
                        return false;
                    }
                }
            );
        }
    });

    //点击加载更多评论
    $('.load_more_comment').live('click', function(){
        var _this = $(this);
        var post_id = $(this).find('a').attr('post-id');
        var page = $(this).find('a').attr('comment_page');
        page = parseInt(page);
        commentParam.page = page;
        commentParam.c_id = post_id;
        $.post(comment_load_more,{params: commentParam},
            function(result){
                if(result.code == 200){
                    var del_comment = "";
                    var comment_content = "";
                    $.each(result.data , function(index , value){
                        if(value.member_id == commentParam.u_id){
                            del_comment = '<span class="reply_reply del_com"><a comment-id="'+value.id+'" post-id="'+post_id+'" href="javascript:;" class="del_commment">删除</a></span>'
                        }else{
                            del_comment = "";
                        }
                        comment_content += '<div class="discuss_pic fl mf">'+
                            '<img alt="" src="'+value.member_logo+'">'+
                            '</div>'+
                            '<div class="discuss_content fl wd">'+
                            '<div class="dis_t">'+
                            '<span class="reply_name">'+value.comment_title+'</span>'+
                            '<span class="reply_time">'+value.create_time+'</span>'+
                            '<span class="reply_reply"><a class="review" href="javascript:;"' +
                            ' post-id="'+post_id+'" comment-id="'+value.id+'">回复</a></span>'+del_comment+
                            '</div>'+
                            '<p>'+value.content+'</p>'+
                            '<div class="huifu_pinglun"></div>'+
                            '</div>' +
                            '<div class="clear"></div>';
                    });

                    $(comment_content).insertBefore(_this);
                    if(result.comment_count < 5 ){
                        _this.remove();
                    }else{
                        page++;
                        _this.find('a').attr('comment_page',page);
                    }
                }else if(result.code == 404){
                    _this.remove();
                }else{
                    layer.alert(result.msg);
                    return false;
                }
            }
        );
    });

    //点击加载更多讨论
    $('.load_more_post').click(function(){
        var page = $(this).find('a').attr('post_page');
        page = parseInt(page);
        var c_type = commentParam.c_type;
        var content_id = commentParam.course_id;
        $.post(course_ajax_loadPost,{content_id: content_id, content_type: c_type, page: page},
            function(result){
                if(result.code == 200){
                    var post_content = '';
                    $.each(result.data , function(index , value){
                        var comment_num = (value.comment_num == 0) ? ' 添加评论' : ' <code>'+value.comment_num+'</code>条评论';
                        post_content += '<div class="discuss_area">'+
                            '<div class="discuss_pic fl">'+
                            '<img src="'+value.member_logo+'" alt="">'+
                            '</div>'+
                            '<div class="discuss_content fl">'+
                            '<div class="dis_t">'+
                            '<span class="dis_name">'+value.member_name+'</span>'+
                            '<span>'+value.create_time+'</span>'+
                            '</div>'+
                            '<p>'+value.content+'</p>'+
                            '</div>'+
                            '<div class="clear discuss_add">'+
                            '<img class="img_f" src="/assets/img/discuss_xuxian.jpg">'+
                            '<img src="/assets/img/discuss_speech.jpg">'+
                            '<span>'+
                            '<a is-load="false" post-id="'+value.id+'" class="load_comment load_comment_'+value.id+'" href="javascript:;">'+comment_num+'</a>'+
                            '</span>'+
                            '<img src="/assets/img/discuss_xuxian.jpg" alt="">'+
                            '</div>'+
                            '<div class="discuss_reply discuss_reply_'+value.id+'">'+
                            '</div>'+
                            '</div><div class="discuss_height"></div>';
                    });

                    $(post_content).insertBefore('.load_more_post');
                    if(result.total_count < 5){
                        $('.load_more_post').remove();
                    }else{
                        page++;
                        $('.load_more_post a').attr('post_page',page);
                    }
                }else if(result.code == 404){
                    $('.load_more_post').remove();
                }else{
                    layer.alert(result.msg);
                    return false;
                }
            }
        );
    });

    //课程评论区点击事件
    $(".load_comment").live("click",function(){
        //$(this).parent().parent().next('.discuss_reply').toggle();
        var _this = $(this);
        var is_load = $(this).attr('is-load');
        var post_id = $(this).attr('post-id');
        $('.discuss_reply_'+post_id).toggle();
        /*if($('.discuss_reply_'+post_id).is(":hidden")){
            $('.discuss_reply_'+post_id).show();
        }else{
            $('.discuss_reply_'+post_id).hide();
        }*/

        if(is_load == "false"){
            commentParam.page = 1;
            commentParam.c_id = post_id;
            $.post(comment_load_more,{params: commentParam},
                function(result){
                    if(result.code == 200){
                        var load_more = "";
                        var del_comment = "";
                        var comment_content = "";
                        _this.attr('is-load','true');
                        $.each(result.data , function(index , value){
                            if(value.member_id == commentParam.u_id){
                                del_comment = '<span class="reply_reply del_com"><a comment-id="'+value.id+'" post-id="'+post_id+'" href="javascript:;" class="del_commment">删除</a></span>'
                            }else{
                                del_comment = "";
                            }
                            comment_content += '<div class="discuss_pic fl mf">'+
                                '<img alt="" src="'+value.member_logo+'">'+
                                '</div>'+
                                '<div class="discuss_content fl wd">'+
                                '<div class="dis_t">'+
                                '<span class="reply_name">'+value.comment_title+'</span>'+
                                '<span class="reply_time">'+value.create_time+'</span>'+
                                '<span class="reply_reply"><a class="review" href="javascript:;"' +
                                ' post-id="'+post_id+'" comment-id="'+value.id+'">回复</a></span>'+del_comment+
                                '</div>'+
                                '<p>'+value.content+'</p>'+
                                '<div class="huifu_pinglun"></div>'+
                                '</div>' +
                                '<div class="clear"></div>';
                        });
                        if(result.total_count > 5){
                            if(result.comment_count >= 5 ){
                                load_more = '<div class="click_load_more load_more_comment">'+
                                    '<a class="blue" href="javascript:;" comment_page="2" post-id="'+post_id+'">点击加载更多评论</a>'+
                                    '</div>';
                            }
                        }
                        comment_content = comment_content+load_more+
                            '<div class="discuss_comment" style="display: block">' +
                            '<div>'+
                            '<textarea placeholder="写下你的评论…" class="dis_testarea dis_testarea_'+commentParam.c_id+'"></textarea>' +
                            '</div>' +
                            '<div class="dis_bt">' +
                            '<span style="margin-right: 18px;"><a href="javascript:;" class="cancel_comment">取消</a></span>' +
                            '<button class="btn_sub send_comment_btn" content-id="'+post_id+'" comment-id="">评论</button>' +
                            '</div>' +
                            '</div>';

                        $('.discuss_reply_'+commentParam.c_id).append(comment_content);
                    }else if(result.code == 404){
                        _this.attr('is-load','true');

                        //添加评论框
                        var add_comment = '<div class="discuss_comment" style="display: block">' +
                            '<div>' +
                            '<textarea placeholder="写下你的评论…" class="dis_testarea dis_testarea_'+commentParam.c_id+'"></textarea>' +
                            '</div>' +
                            '<div class="dis_bt">' +
                            '<span style="margin-right: 18px;"><a href="javascript:;" class="cancel_comment">取消</a></span>' +
                            '<button class="btn_sub send_comment_btn" content-id="'+post_id+'" comment-id="">评论</button>' +
                            '</div>' +
                            '</div>';

                        $('.discuss_reply_'+commentParam.c_id).append(add_comment);
                        $('.dis_testarea_'+commentParam.c_id).focus();
                    }else{
                        layer.alert(result.msg);
                        return false;
                    }
                }
            );
        }
    });

    //录播课程发布讨论
    $(".discuss_issue_area .course_post_btn").click(function(){
        var content = $(".discuss_issue_area .course_post_content").val();
        if(content == ""){
            layer.alert('请输入讨论内容。');
            return false;
        }

        //发布讨论
        $.post(course_ajax_post,{post_content: content,params: commentParam},
            function(result){
                if(result.code == 200){
                    var con_str = '<div class="discuss_area">'+
                        '<div class="discuss_pic fl">'+
                        '<img alt="" src="'+commentParam.u_logo+'">'+
                        '</div>'+
                        '<div class="discuss_content fl">'+
                        '<div class="dis_t">'+
                        '<span class="dis_name">'+commentParam.u_name+'</span><span>'+result.date+'</span>'+
                        '</div>'+
                        '<p>'+content+'</p>'+
                        '</div>'+
                        '<div class="clear discuss_add">'+
                        '<img alt="" src="/assets/img/discuss_xuxian.jpg" class="img_f">'+
                        '<img alt="" src="/assets/img/discuss_speech.jpg">'+
                        '<span><a href="javascript:;" class="load_comment" post-id="'+result.id+'" is-load="false"> 添加评论</a></span>'+
                        '<img alt="" src="/assets/img/discuss_xuxian.jpg">'+
                        '</div>' +
                        '<div class="discuss_reply discuss_reply_'+result.id+'"></div>'+
                        '</div><div class="discuss_height"></div>';
                    $(con_str).insertAfter("#course_post_content_insert");
                    $(".discuss_issue_area .course_post_content").val("");

                    //页面滚动到头部可以查看自己回复评论信息
                    $('body').animate({scrollTop:0},500);
                    $('html').animate({scrollTop:0},500);
                }else{
                    layer.alert(result.msg);
                    return false;
                }
            }
        );
    });

    $(".introduce_mid .mid_right .introduce2_list ul li").click(function(){
        //$(this).addClass("col_a").removeClass("col_b").siblings().removeClass("col_a").addClass("col_b");
        var chapter_id = $(this).attr('data-chapter');
        if(chapter_id == ""){
            return false;
        }
        var chapterUrl = course_detail_url+'?type=chapter&vid='+chapter_id;
        if(commentParam.u_id == 0 || commentParam.u_id == "" || commentParam.u_id == '0'){
            $("#login_div").show();
            $(".layer_bg").show();
            $('.login_layer #callback').val(chapterUrl);
            return false;
        }
        location.href = chapterUrl;
        /*$.post(course_ajax_chapter,{chapter_id: chapter_id, course_id: commentParam.course_id},
            function(result){
                if(result.code == 200){
                    playVideo(result.data);
                    $('#play_video_btn').hide();
                }else{
                    $('.login_layer #callback').val(window.location.href);
                    $("#login_div").show();
                    return false;
                }
            }
        );*/
    });

    $("#chapterTab").click(function(){
        var chapterUrl = course_detail_url+'?type=chapter';
        if(commentParam.u_id == 0 || commentParam.u_id == "" || commentParam.u_id == '0'){
            $("#login_div").show();
            $(".layer_bg").show();
            $('.login_layer #callback').val(chapterUrl);
            return false;
        }
        window.location.href = chapterUrl;
        /*var type = $(this).attr('wtype');
         if(type == 'about'){
         var chapter_id = $('#courseChapterFirst').val();
         }else if(type == 'chapter'){
         var chapter_id = $('.introduce2_list').find("li:first").attr('data-chapter');
         }
         $.post(course_ajax_chapter,{chapter_id: chapter_id, course_id: commentParam.course_id},
         function(result){
         if(result.code == 200){
         playVideo(result.data);
         $('#play_video_btn').hide();
         }else{
         $('.login_layer #callback').val(window.location.href);
         $("#login_div").show();
         return false;
         }
         }
         );*/
    });

    $("#play_video_btn").click(function(){
        var chapterUrl = course_detail_url+'?type=chapter';
        if(commentParam.u_id == 0 || commentParam.u_id == "" || commentParam.u_id == '0'){
            $("#login_div").show();
            $(".layer_bg").show();
            $('.login_layer #callback').val(chapterUrl);
            return false;
        }
        window.location.href = chapterUrl;
        /*var type = $(this).attr('wtype');
        if(type == 'about'){
            var chapter_id = $('#courseChapterFirst').val();
        }else if(type == 'chapter'){
            var chapter_id = $('.introduce2_list').find("li:first").attr('data-chapter');
        }
        $.post(course_ajax_chapter,{chapter_id: chapter_id, course_id: commentParam.course_id},
            function(result){
                if(result.code == 200){
                    playVideo(result.data);
                    $('#play_video_btn').hide();
                }else{
                    $('.login_layer #callback').val(window.location.href);
                    $("#login_div").show();
                    return false;
                }
            }
        );*/
    });

    /**
     * 播放小节
     * @param script
     */
    function playVideo(script){
        var url = $(script).attr("src");
        var object=document.createElement("script");
        object.src= url
        object.type="text/javascript";
        object.charset="utf-8";
        var bottom = document.getElementById("play_video_img");
        $('#play_video_div').html("");
        document.getElementById("play_video_div").appendChild(object);
        $('#play_video_div').show();
        $('#play_video_img').hide();
    }

    /**
     * 放入选课单
     */
    function addToCart(content_id, content_type) {
        $.ajax({
            type: "POST",
            url: billing_ajax_cart,
            data: {type : 1 , content_type : content_type , content_id : content_id},
            success: function(result){
                if (result.code == 1){
                    location.href = billing_cart_url;
                }else if(result.code == 2){
                    $('.login_layer #callback').val(window.location.href);
                    $("#login_div").show();
                }
                else{
                    layer.alert("加入选课单失败");
                }
            }
        });
    }
});