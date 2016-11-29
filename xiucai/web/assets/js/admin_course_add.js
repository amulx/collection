$(document).ready(function(){
    //取消按钮
    $('#cancel_create_btn').click(function(){
        location.href = course_video_index;
    });

    $('#course_title').blur(function(){
        var _title = $(this).val();
        _title = trimSpaces(_title);
        fnGetLength(_title);
    });

    function trimSpaces(Str) {
        var ResultStr = "";
        var Temp = Str.split(/\s/);
        for(var i = 0; i < Temp.length; i++) {
            ResultStr += Temp[i];
        }
        return ResultStr;
    }

    function fnGetLength(_username)
    {
        var c = /\w/g;
        var name_length = _username.length;
        if(c.test(_username) ){
            if(name_length > 60){
                alert('课程英文字符最长为60个');
                return false;
            }else{
                return true;
            }
        }else{
            if(name_length > 30){
                alert('课程中文字符最长为30个');
                return false;
            }else{
                return true;
            }
        }
    }

    //保存按钮
    $('#course_submit_btn').click(function(){
        $('#course_status').val(2);
        if($('#course_title').val() == ""){
            alert('请输入课程标题');
            return false;
        }else{
            var is_length = fnGetLength($('#course_title').val());
            if(!is_length){
                return false;
            }

            $('#add_course_form').submit();
        }
    });

    //保存并发布按钮
    $('#course_create_btn').click(function(){
        $('#course_status').val(4);
        if($('#course_title').val() == ""){
            alert('请输入课程标题');
            return false;
        }else{
            var is_length = fnGetLength($('#course_title').val());
            if(!is_length){
                return false;
            }

            $('#add_course_form').submit();
        }
    });

    //点播视频向上排序
    $('.up_sort').live('click', function(){
        if($(this).parent().parent().hasClass("first")){
            return false;
        }

        var _this = $(this);
        sort_chapter(_this, 'up_sort');
    });

    //点播视频向下排序
    $('.down_sort').live('click', function(){
        if($(this).parent().parent().hasClass("last")){
            return false;
        }
        var _this = $(this);
        sort_chapter(_this, 'down_sort');
    });

    //排序处理
    function sort_chapter(_this, sort_type){
        var content_id = $('#course_id').val();
        var zIndex = _this.parent().attr('zIndex');
        var video_id = _this.parent().attr('video-id');
        if(content_id == "" || zIndex == ""){
            return false;
        }

        $.post(course_ajax_sortVideo,{content_id: content_id, video_id:video_id, zIndex: zIndex, sort_type: sort_type},
            function(result){
                if(result.code == 200){
                    _this.parent().attr('zindex', result.new_zIndex);
                    if(sort_type == 'up_sort'){
                        _this.parent().parent().prev().find('.video_sort').attr('zindex',result.old_zIndex);

                        var _selector = _this.parent().parent().prev();
                        var this_tr = _this.parent().parent().html();
                        if(_this.parent().parent().prev().hasClass('first')){
                            _this.parent().parent().prev().removeClass('first');
                            _this.parent().parent().addClass('first');
                            this_tr = '<tr class="first">'+this_tr+'</tr>';
                        }else{
                            this_tr = '<tr>'+this_tr+'</tr>';
                        }
                        $(this_tr).insertBefore(_selector);
                        _this.parent().parent().remove();
                    }else if(sort_type == 'down_sort'){
                        _this.parent().parent().next().find('.video_sort').attr('zindex',result.old_zIndex);
                        var _selector = _this.parent().parent().next();
                        var this_tr = _this.parent().parent().html();
                        if(_this.parent().parent().next().hasClass('last')){
                            _this.parent().parent().next().removeClass('last');
                            _this.parent().parent().addClass('last');
                            this_tr = '<tr class="last">'+this_tr+'</tr>';
                        }else{
                            this_tr = '<tr>'+this_tr+'</tr>';
                        }
                        $(this_tr).insertAfter(_selector);
                        _this.parent().parent().remove();
                    }
                }else if(result.code == 201){
                    return;
                }else{
                    alert(result.msg);
                    return false;
                }
            }
        );
    }

    //添加或编辑点播视频弹层按钮
    $('#add_chapter_btn').live('click', function(){
        courseChapter.video_id = $('#video_id').val();
        courseChapter.course_id = $('#course_id').val();
        courseChapter.tags = $('#tags').val();
        courseChapter.length = $('#length').val();
        courseChapter.video_path = $('#video_path').val();
        courseChapter.chapter_title = $('#chapter_title').val();
        courseChapter.third_party_id = $('#third_party_id').val();
        courseChapter.is_free = $('input:radio[name="is_free"]:checked').val();
        if(courseChapter.chapter_title == ""){
            alert('请输入视频标题');
            return false;
        }else if(courseChapter.third_party_id == ""){
            alert('请输入第三方视频ID');
            return false;
        }else if(courseChapter.length == ""){
            alert('请输入视频时长');
            return false;
        }else if(courseChapter.video_path == ""){
            alert('请输入视频路径');
            return false;
        }

        //添加或编辑课程视频内容
        $.post(course_ajax_video,{params: courseChapter},
            function(result){
                if(result.code == 200){
                    var is_free = (courseChapter.is_free == 1) ? '是' : '否';
                    var add_chapter = '<td align="center" >'+result.id+'</td>'+
                        '<td align="center">'+courseChapter.chapter_title+'</td>'+
                        '<td align="center">'+courseChapter.length+'</td>'+
                        '<td align="center">'+is_free+'</td>'+
                        '<td align="center" video-id="'+result.id+'">'+
                        '<a href="javascript:;" class="editor_chapter">编辑</a> | '+
                        '<a href="javascript:;" class="del_chapter">删除</a>'+
                        '</td>'+
                        '<td align="center" class="video_sort" zindex="'+result.zIndex+'"  video-id="'+result.id+'">'+
                        '<a href="javascript:;" class="up_sort">向上</a> | '+
                        '<a href="javascript:;" class="down_sort">向下</a>'+
                        '</td>';
                    if($('#video_id').val() == ""){
                        add_chapter = '<tr id="chapter'+result.id+'">'+add_chapter+'</tr>';
                        $('#course_video_table').append(add_chapter);
                    }else{
                        $('#chapter'+courseChapter.video_id).html(add_chapter);
                    }
                    $('.white_content').hide();
                }else{
                    alert(result.msg);
                    return false;
                }
            }
        );
    });

    //编辑视频弹层赋值处理
    $('.editor_chapter').live('click', function(){
        var video_id = $(this).parent().attr('video-id');
        $.post(course_ajax_category,{video_id: video_id},
            function(result){
                if(result.code == 200){
                    $('#tags').val(result.tags);
                    $('#length').val(result.length);
                    $('#video_path').val(result.url);
                    $('#chapter_title').val(result.title);
                    $('#video_id').val(result.id);
                    $('#third_party_id').val(result.third_party_id);
                    $('.white_content').show();
                    if(result.is_free == 1){
                        $("#isFree").attr("checked", true);
                    }else{
                        $("#noFree").attr("checked", true);
                    }
                    //courseChapter.is_free = $('input:radio[name="is_free"]:checked').val();
                }else{
                    alert(result.msg);
                    return false;
                }
            }
        );
    });

    //删除点播视频
    $('.del_chapter').live('click', function(){
        var _this = $(this);
        var video_id = $(this).parent().attr('video-id');
        if(video_id == ""){
            alert('删除点播视频失败。');
            return false;
        }else{
            if (confirm("确定删除该点播视频信息？")){
                $.post(course_ajax_video,{video_id: video_id},
                    function(result){
                        if(result.code == 200){
                            _this.parent().parent().remove();
                        }else{
                            alert(result.msg);
                            return false;
                        }
                    }
                );
            }
        }
    });

    //老师头像切换
    $("#course_instructor").change(function(){
        var teacher_id = $(this).val();
        if(teacher_id != ""){
            $.post(course_ajax_category,{instructor_id: teacher_id},
                function(result){
                    if(result.code == 200){
                        $('#course_instructor_avatar').attr('src', result.avatar);
                    }else{
                        alert(result.msg);
                        return false;
                    }
                }
            );
        }
    });

    //ajax获取二级分类
    $('#parent_course_kind').change(function(){
        var category_id = $(this).val();
        if(category_id != ""){
            $.post(course_ajax_category,{id: category_id},
                function(result){
                    if(result.code == 200){
                        var option_str = '<option value="">--请选择类别--</option>';
                        $.each(result.data , function(index , value){
                            option_str += '<option value="'+value.category_id+'">'+value.name+'</option>';
                        });

                        $('#course_kind').html(option_str);
                    }else{
                        alert(result.msg);
                        return false;
                    }
                }
            );
        }else{
            $('#course_kind').html('<option value="">--请选择类别--</option>');
        }
    });

    //弹层关闭按钮
    $(".closeWindow").live('click', function(){
        $(this).parent().hide();
    });

    //弹层显示按钮
    $("#add_chapter_course").click(function(){
        $("#isFree").attr("checked", true);
        $('#video_id').val("");
        $('#tags').val("");
        $('#length').val("");
        $('#video_path').val("");
        $('#chapter_title').val("");
        $('#third_party_id').val("");
        $('.white_content').show();
    })

    $('.upload_btn').click(function(){
        var target_btn = $(this).attr('target-btn');
        var content = '<div class="up_pic" style="height: 40px;margin-bottom: 8px;">'+
            '<input type="text" id="avatarUpload" value="" />'+
            '</div>';
        if(target_btn == "img_url"){
            var uploadUrl = "/assets/uploadify_img/upload.php?member_id="+Math.random()+'&w_max=256&h_max=186&class=img_url';
        }else{
            var uploadUrl = "/assets/uploadify_img/upload.php?member_id="+Math.random()+'&w_max=698&h_max=343&class=banner_url';
        }
        var _id = "up_pic_"+target_btn;
        var object = document.createElement("input");
        object.id = "avatarUpload";
        object.type = "text";
        document.getElementById("up_pic_img_url").appendChild(object);
    });

    //添加点播课程tab切换
    $('.tab_menu .td_bg a').click(function(){
        $(".closeWindow").parent().hide();//隐藏层
        $(this).parent().addClass("td_bg_current").siblings().removeClass("td_bg_current");
        var  target_table = $(this).attr('target-table');
        if(target_table == "course_base_info"){
            $('.white_content2').show();
            $('#course_base_info').show();
            $('#course_detail_info').hide();
            $('#course_video').hide();
        }else if(target_table == "course_detail_info"){
            $('.white_content2').hide();
            $('#course_detail_info').show();
            $('#course_base_info').hide();
            $('#course_video').hide();
        }else{
            $('.white_content2').hide();
            $('#course_video').show();
            $('#course_base_info').hide();
            $('#course_detail_info').hide();
        }
    });

    var ue = UE.getEditor('container');
})