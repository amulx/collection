var reg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
$(document).ready(function(){
    //$(".ui_timepicker").datepicker();
    $(".ui_timepicker").datetimepicker({
        showSecond: true,
        timeFormat: 'hh:mm:ss',
        stepHour: 1,
        stepMinute: 1,
        stepSecond: 1
    });

    //删除直播管理员操作
    $(".remove_live_admin").live("click", function(){
        var _email = $(this).prev('code').html();
        var index = addMembers.indexOf(_email);
        if (index != -1){
            addMembers.splice(index, 1);
        }

        $(this).parent().remove();
    });

    //添加直播管理员
    $('#add_live_admin_icon').click(function(){
        var _email = $('#add_live_admin_email').val();
        if(_email != ""){
            if(!reg.test(_email)){
                alert('邮箱格式不正确，请重新输入');
                return false;
            }else{
                if(addMembers.length >= 3){
                    alert('最多只能添加3个注册邮箱。');
                    return false;
                }
                if($.inArray(_email, addMembers) == -1){
                    //验证注册邮箱
                    $.ajax({
                        type: "POST",
                        url: course_Live_admin,
                        data: { email: _email},
                        success: function(result){
                            if(result.code == 200){
                                addMembers.push(_email);
                                var add_con = '<span><code>'+_email+'</code><a href="javascript:void(0);" class="remove_live_admin"' +
                                    ' style="color: red;text-decoration: none;font-weight: bold">X</a><br></span>';
                                $("#show_admin_email").append(add_con);
                                $('#add_live_admin_email').val("");
                            }else{
                                alert(result.msg)
                                return false;
                            }
                        }
                    });
                }else{
                    alert('该用户用户邮箱已被添加。');
                    return false;
                }
            }
        }else{
            alert('请输入邮箱地址');
            return false;
        }
    });

    $('#live_title').blur(function(){
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

    //取消按钮
    $('#cancel_create_btn').click(function(){
        location.href = course_live_index;
    });

    //保存按钮
    $('#live_submit_btn').click(function(){
        $('#live_status').val(2);
        $('#add_live_admin').val(addMembers);
        checkLiveForm();
    });

    //保存并发布按钮
    $('#live_create_btn').click(function(){
        $('#live_status').val(4);
        $('#add_live_admin').val(addMembers);
        checkLiveForm();
    });

    function checkLiveForm(){
        if($('#live_title').val() == ""){
            alert('请输入直播标题。');
            return false;
        }else{
            var is_length = fnGetLength($('#live_title').val());
            if(!is_length){
                return false;
            }
        }
        if($('#live_brief').val() == ""){
            alert('请输入直播简介。');
            return false;
        }else if($('#live_start_time').val() == ""){
            alert('请输入直播开始时间。');
            return false;
        }else if($('#live_instructor').val() == ""){
            alert('请输选择主讲老师。');
            return false;
        }else if($('#duration').val() == ""){
            alert('请输入直播时长。');
            return false;
        }else{
            $('#add_live_form').submit();
        }
    }

    //老师头像切换
    $("#live_instructor").change(function(){
        var teacher_id = $(this).val();
        if(teacher_id != ""){
            $.post(course_ajax_category,{instructor_id: teacher_id},
                function(result){
                    if(result.code == 200){
                        $('#instructor_name').val(result.name);
                        $('#instructor_avatar').val(result.avatar);
                        $('#live_instructor_avatar').attr('src', result.avatar);
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

    //添加点播课程tab切换
    $('.tab_menu .td_bg a').click(function(){
        $(".closeWindow").parent().hide();//隐藏层
        $(this).parent().addClass("td_bg_current").siblings().removeClass("td_bg_current");
        var  target_table = $(this).attr('target-table');
        if(target_table == "live_base_info"){
            $('.white_content2').show();
            $('#live_base_info').show();
            $('#live_detail_info').hide();
            $('#live_video').hide();
        }else if(target_table == "live_detail_info"){
            $('.white_content2').hide();
            $('#live_detail_info').show();
            $('#live_base_info').hide();
            $('#live_video').hide();
        }else{
            $('.white_content2').hide();
            $('#live_video').show();
            $('#live_base_info').hide();
            $('#live_detail_info').hide();
        }
    });

    var ue = UE.getEditor('container');
})