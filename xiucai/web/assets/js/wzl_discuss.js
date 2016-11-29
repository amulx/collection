$(document).ready(function(){
    //取消发送评论
    $('.cancel_comment').live('click', function(){
        //$(this).parent().parent().parent().remove();
        $(this).parent().parent().prev().val("");
        $(this).parent().next().attr('comment-id',"");
        $(this).parent().parent().prev().attr('placeholder',"");
    });

    //删除评论
    $('.del_commment').live('click',function(){
        var self = $(this);
        var comment_id = $(this).attr('comment-id');
        var post_id = $(this).attr('post-id');
        if(comment_id == ""){
            layer.alert('删除失败。');
            return false;
        }else{
            commentParam.comment_id = comment_id;
        }
        //删除评论处理
        if (confirm("确定要删除该评论吗？")){
            $.post(comment_delete_url,{params: commentParam, post_id: post_id},
                function(result){
                    if (result.code == 200){
                        if(result.comment_num > 0){
                            var comment_num_str = ' <code>'+result.comment_num+'</code>条评论 '
                        }else{
                            var comment_num_str = ' 添加评论 ';
                        }
                        $('.load_comment_'+post_id).html(comment_num_str);
                        self.parent().parent().parent().prev().remove();
                        self.parent().parent().parent().remove();
                    }else{
                        layer.alert(result.msg);
                    }
                }
            );
        }
    });

    //发布评论功能
   $('.send_comment_btn').live('click', function(){
       var _this = $(this);
       commentParam.c_id = $(this).attr('content-id');
       commentParam.comment_id = $(this).attr('comment-id'); //不为空则是回复评论
       var comment_content = $(this).parent().prev().find('.dis_testarea').val();
       if(comment_content == ""){
           layer.alert('请输入评论内容。');
           return false;
       }else{
           $.post(comment_send_url,{comment_content: comment_content, params: commentParam},
               function(result){
                   if(result.code == 200){
                       var comment_str = '<div class="discuss_pic fl mf">'+
                           '<img alt="" src="'+result.data.member_logo+'">'+
                           '</div><div class="discuss_content fl wd">'+
                           '<div class="dis_t">'+
                           '<span class="reply_name">'+result.data.comment_title+'</span>'+
                           '<span class="reply_time">'+result.data.create_time+'</span>'+
                           '<span class="reply_reply"><a class="review" href="javascript:;" post-id="'+commentParam.c_id+'" comment-id="'+result.data.id+'">' +
                           '回复</a></span><span class="reply_reply del_com"><a comment-id="'+result.data.id+'" post-id="'+commentParam.c_id+'"' +
                           ' href="javascript:;" class="del_commment">删除</a></span>'+
                           '</div>'+
                           '<p>'+comment_content+'</p>'+
                           '<div class="huifu_pinglun"></div>'+
                           '</div><div class="clear"></div>';

                       $('.discuss_reply_comment').remove();
                       var selector = $('.discuss_reply_'+commentParam.c_id).children().first();
                       if(selector){
                           $(comment_str).insertBefore(selector);
                       }else{
                           $('.discuss_comment').insertBefore();
                       }

                       //更新评论数量
                       $('.load_comment_'+commentParam.c_id).html('<code>'+result.data.comment_count+'</code>条评论');
                       _this.parent().prev().find('.dis_testarea').val("");
                   }else{
                       layer.alert(result.msg);
                       return false;
                   }
               }
           );
       }
   });

    //回复评论
    $('.review').live('click', function(){
        var post_id = $(this).attr('post-id');
        var comment_id = $(this).attr('comment-id');
        commentParam.c_id = post_id;
        commentParam.comment_id = comment_id;

        var username = $(this).parent().prev().prev().find('code').html();
        var review_user = '回复'+username+'：';
        $('.dis_testarea_'+commentParam.c_id).attr('placeholder',review_user);
        $('.dis_testarea_'+commentParam.c_id).next().find('button').attr('comment-id', comment_id);

        /*var discuss_comment = $('.discuss_reply_'+commentParam.c_id).find('.discuss_reply_comment');
        if(discuss_comment){
            discuss_comment.remove();
        }

        //添加评论框
        var username = $(this).parent().prev().prev().html();
        var add_comment = '<div class="discuss_comment discuss_reply_comment" style="display: block">' +
            '<textarea class="dis_testarea discuss_reply_textarea" placeholder="回复'+username+'："></textarea>' +
            '<div class="dis_bt">' +
            '<span style="margin-right: 18px;"><a href="javascript:;" class="cancel_comment">取消</a></span>' +
            '<button class="btn_sub send_comment_btn">评论</button>' +
            '</div>' +
            '</div>';
        var selector = $(this).parent().parent().parent();
        $(add_comment).insertAfter(selector);*/
    });
});

/*function discuss_hf(){
    var dis = document.getElementById("area_id").style.display;
    if( dis == "block"){
        document.getElementById("area_id").style.display = "none";
    }else{
        document.getElementById("area_id").style.display = "block";
    }
}
function discuss_add(id){
    var dis_add =document.getElementById("comment_id"+id).style.display;
    if( dis_add == "block"){
        document.getElementById("comment_id"+id).style.display = "none";
    }else{
        document.getElementById("comment_id"+id).style.display = "block";
    }
}*/
$(".discuss_content").live("mouseover", function(){
    $(this).find('.reply_reply').show();
});

//鼠标离开时隐藏回复，删除，编辑tab
$(".discuss_content").live("mouseleave",function(){
    $(this).find('.reply_reply').hide();
})

/*$(document).ready(function(){
    $('.review').click(function(){
        var html = '<div  class="discuss_comment" style="margin-left:0;display: block">';
        html += '<textarea class="dis_testarea">';
        html += '</textarea>';
        html += '<div class="dis_bt">';
        html += '<span style="margin-right: 18px;"><a href="#">取消</a></span>';
        html += '<button class="btn_sub" >评论</button>';
        html += '</div>';
        html += '</div>';
        var _selector = $(this).parent().parent().parent().find('.huifu_pinglun').html();
        if($.trim(_selector) == ''){
            $(this).parent().parent().parent().find('.huifu_pinglun').html(html);
        }else{
            $(this).parent().parent().parent().find('.huifu_pinglun').html("");
        }
    });
})*/
