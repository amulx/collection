$(document).ready(function(){
    //新密码输入验证
    $(".list .new_pass").blur(function(){
        if($(this).val() != ""){
            if($(this).val().length < 6 || $(this).val().length > 18){
                $('.list .check_msg').html('请输入6~18个字符区分大小写');
            }else{
                $('.list .check_msg').html('');
            }
        }
    });

    //重复密码输入验证
    $(".list .repeat_pass").blur(function(){
        if($(this).val() != ""){
            if($(this).val().length < 6 || $(this).val().length > 18){
                $('.list .check_msg').html('请输入6~18个字符区分大小写');
            }else if($(this).val() != $(".list .new_pass").val()){
                $('.list .check_msg').html('俩次输入的密码不一致');
            }else{
                $('.list .check_msg').html('');
            }
        }
    });


    $("#changepwd").click(function(){
        //原密码非空验证
        if($('.list .current_pass').val() == ""){
            $('.list .check_msg').html('请输入当前密码');
            return false;
        }
        var pass_ori = $('.list .current_pass').val();
        password_from_check(pass_ori, 'set');
    });
    $("#rest_pass_btn").click(function(){
        password_from_check('', 'reset');
    });
});

//表单提交验证
function password_from_check(pass_ori, type){
    if(type == 'set'){
        //原密码非空验证
        if($('.list .current_pass').val() == ""){
            $('.list .check_msg').html('请输入当前密码');
            return false;
        }
    }
    //新密码验证
    if($('.list .new_pass').val() == ""){
        $('.list .check_msg').html('请输入新的密码');
        return false;
    }else if($('.list .new_pass').val().length < 6 || $('.list .new_pass').val().length > 18){
        $('.list .check_msg').html('请输入6~18个字符区分大小写');
        return false;
    }

    //重复密码验证
    if($('.list .repeat_pass').val() == ""){
        $('.list .check_msg').html('请输重复密码');
        return false;
    }else if($('.list .repeat_pass').val().length < 6 || $('.list .repeat_pass').val().length > 18){
        $('.list .check_msg').html('请输入6~18个字符区分大小写');
        return false;
    }

    //密码重复验证
    if($('.list .new_pass').val() != $('.list .repeat_pass').val()){
        $('.list .check_msg').html('俩次输入的密码不一致');
        return false;
    }else{
        var current_pass = pass_ori;
        var find_code = (type == 'reset') ? $('#findCode').val() : "";
        var new_pass = $('.list .new_pass').val();
        $.ajax({
            type: "POST",
            url: user_ajaxSetPass,
            data: { new_password: new_pass, current_password: current_pass, find_code: find_code, type: type},
            success: function(result){
                if(result.code == 200){
                    location.href = user_index;
                }else{
                    $('.list .check_msg').html(result.msg);
                    return false;
                }
            }
        });
    }
}