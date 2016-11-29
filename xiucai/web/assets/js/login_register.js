var reg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var registerParam = { email: "", username: "", password: "", code: ""};
$(document).ready(function()
{
    //注册弹层—登录按钮点击事件
    $('#reg_div .login1_bom').click(function(){
        $('#reg_div').hide();
        $('#login_div').show();
        $('.layer_bg').show();
        $('.register_layer .login1_hint').html('');
        $('.register_layer .login1_hint').hide();
    });

    //登录弹层—注册按钮点击事件
    $('#login_div .login1_bom').click(function(){
        $('#login_div').hide();
        $('.layer_bg').show();
        $('#reg_div').show();
        $('.register_layer .login1_hint').html('');
        $('.register_layer .login1_hint').hide();
    });

    //登录
    $('.login1_bt a').live('click', login_form_check);

    //注册
    $('.regist1_bt a').live('click', register_form_check);

    //邮箱唯一验证
    $('.register_layer .email').blur(function(){
        var _email = $(this).val();
        if(_email != ""){
            if(!reg.test(_email)){
                $('.register_layer .login1_hint').html('邮箱格式不正确，请重新输入');
                $('.register_layer .login1_hint').show();
                return false;
            }else{
                //验证邮箱唯一
                $.ajax({
                    type: "POST",
                    url: register_check_url,
                    data: { email: _email},
                    success: function(result){
                        if(result.code == 100){
                            $('.register_layer .login1_hint').html(result.msg);
                            $('.register_layer .login1_hint').show();
                            return false;
                        }else{
                            $('.register_layer .login1_hint').html('');
                            $('.register_layer .login1_hint').hide();
                        }
                    }
                });
            }
        }
    });

    //用户名字输入限制
    $('.register_layer .username').blur(function(){
        var _username = $(this).val();
        _username = trimSpaces(_username);
        fnGetLength(_username);
    });

    function trimSpaces(Str) {
        var ResultStr = "";
        var Temp = Str.split(/\s/);
        for(var i = 0; i < Temp.length; i++) {
            ResultStr += Temp[i];
        }
        return ResultStr;
    }

    function fnCheckChineseChar(obj)
    {
        var reg = /^[\u0391-\uFFE5]+$/;
        return reg.test(obj);
    }

    //用户昵称唯一验证
    /*$('.register_layer .username').blur(function(){
        var _username = $(this).val();
        if(_username != ""){
            //用户昵称唯一
            $.ajax({
                type: "POST",
                url: register_check_url,
                data: { username: _username},
                success: function(result){
                    if(result.code == 100){
                        $('.register_layer .login1_hint').html(result.msg);
                        $('.register_layer .login1_hint').show();
                        return false;
                    }else{
                        $('.register_layer .login1_hint').html('');
                        $('.register_layer .login1_hint').hide();
                    }
                }
            });
        }
    });*/

    //找回密码
    $("#find_pass_div .login2_bt .btn_sub").live("click", function(){
        var register_email = $("#find_pass_div .register_email").val();
        if(register_email == ""){
            $("#find_pass_div .login2_bt_in").show();
            return false;
        }else{
            if(!reg.test(register_email)){
                $("#find_pass_div .login2_bt_in").show();
                return false;
            }else{
                $("#find_pass_div .login2_bt_in").hide();
                $('#find_pass_div .login2_bt .btn_sub').hide();
                $('#find_pass_div .login2_bt').find('img').show();
                $.ajax({
                    type: "POST",
                    url: user_findPass,
                    data: { email: register_email},
                    success: function(result){
                        if(result.code == 200){
                            $("#find_pass_div").hide();
                            $("#find_back_div").show();

                            //弹层倒计时
                            var i = 10;
                            setInterval(function(){
                                if(i == 0){
                                    $("#find_back_div").fadeOut(800);
                                    $('.layer_bg').hide();
                                }
                                document.getElementById('endTime2').innerHTML = i--;
                            },1000);
                        }else{
                            $('#find_pass_div .login2_bt .btn_sub').show();
                            $('#find_pass_div .login2_bt').find('img').hide();
                            $('#find_pass_div .msb_in').show();
                            $('#find_pass_div .msb_in').html(result.msg);
                        }
                    }
                });
            }
        }
    });

    $("#find_pass_div .register_email").focus(function(){
        if($(this).val() == ""){
            $("#find_pass_div .login2_bt_in").hide();
        }
    })
});

function fnGetLength(_username)
{
    var c = /\w/g;
    var name_length = _username.length;
    if(c.test(_username) ){
        if(name_length > 10){
            $('.register_layer .login1_hint').html('昵称英文字符最长为10个');
            $('.register_layer .login1_hint').show();
            return false;
        }else{
            $('.register_layer .login1_hint').html('');
            $('.register_layer .login1_hint').hide();
            return true;
        }
    }else{
        if(name_length > 10){
            $('.register_layer .login1_hint').html('昵称中文字符最长为10个');
            $('.register_layer .login1_hint').show();
            return false;
        }else{
            $('.register_layer .login1_hint').html('');
            $('.register_layer .login1_hint').hide();
            return true;
        }
    }
}

//登录表单信息验证
function login_form_check(){
    //邮箱账号验证
    var _email = $('.login_layer .email').val();
    if(_email == ''){
        $('.login_layer .login1_hint').html('请输入邮箱地址');
        $('.login_layer .login1_hint').show();
        return false;
    }else{
        if(!reg.test(_email)){
            $('.login_layer .login1_hint').html('邮箱格式不正确，请重新输入');
            $('.login_layer .login1_hint').show();
            return false;
        }
    }

    //密码非空验证
    var password = $('.login_layer .password').val();
    if(password == ''){
        $('.login_layer .login1_hint').html('请输入密码');
        $('.login_layer .login1_hint').show();
        return false;
    }else{
        $('.login_layer .login1_hint').html('');
        $('.login_layer .login1_hint').hide();
    }

    var is_remember = $('.login_layer #remember').is(':checked');
    is_remember = (is_remember) ? 'remember' : 'no'; //是否记住密码
    var callback = $('.login_layer #callback').val();
    if(_email != "" && password != ""){
        //隐藏提交按钮
        $('#login_div .login1_bt .btn_sub').hide();
        $('#login_div .login1_bt a').find('img').show();
        //ajax登录
        $.ajax({
            type: "POST",
            url: user_login,
            data: { email: _email, password: password, remember: is_remember},
            success: function(result){
                if(result.code == 200){
                    $('.layer_bg').hide();
                    $("#login_div").hide();
                    if(callback){
                        location.href = callback;
                    }else{
                        location.href = user_index;
                    }
                    //location.reload(); //重载页面
                }else{
                    $('.login_layer .login1_hint').html(result.msg);
                    $('.login_layer .login1_hint').show();
                    $('#login_div .login1_bt .btn_sub').show();
                    $('#login_div .login1_bt a').find('img').hide();
                }
            }
        });
    }
}

//注册表单信息验证
function register_form_check(){
    //获取验证错误信息是否为空
    /*var register_check_info = $('.register_layer .login1_hint').html();
    if($.trim(register_check_info) != ''){
        return false;
    }*/

    registerParam.email = $('.register_layer .email').val();
    if($.trim(registerParam.email) == ''){
        $('.register_layer .login1_hint').html('请输入邮箱地址');
        $('.register_layer .login1_hint').show();
        return false;
    }else{
        if(!reg.test(registerParam.email)){
            $('.register_layer .login1_hint').html('邮箱格式不正确，请重新输入');
            $('.register_layer .login1_hint').show();
            return false;
        }
    }

    registerParam.password = $('.register_layer .pass').val();
    registerParam.username = $('.register_layer .username').val();
    if(registerParam.username == ''){
        $('.register_layer .login1_hint').html('请输入您的昵称');
        $('.register_layer .login1_hint').show();
        return false;
    }else{
        var is_length = fnGetLength(registerParam.username);
        if(!is_length){
            return false;
        }
    }

    if(registerParam.password == ''){
        $('.register_layer .login1_hint').html('请输入密码');
        $('.register_layer .login1_hint').show();
        return false;
    }else if(registerParam.password.length < 6){
        $('.register_layer .login1_hint').html('密码长度不低于6位');
        $('.register_layer .login1_hint').show();
        return false;
    }else if($('.register_layer .review_pass').val() == ''){
        $('.register_layer .login1_hint').html('请输入重复密码');
        $('.register_layer .login1_hint').show();
        return false;
    }else if($('.register_layer .review_pass').val() != registerParam.password){
        $('.register_layer .login1_hint').html('两次密码输入不一致');
        $('.register_layer .login1_hint').show();
        return false;
    }else{
        $('.register_layer .login1_hint').html('');
        $('.register_layer .login1_hint').hide();
    }

    /*if($(this).parent().prev().hasClass('yzm')){
        registerParam.code = $('.register_layer .validateCode').val();
        if(registerParam.code == ""){
            $('.register_layer .login1_hint').html('请输入验证码');
            $('.register_layer .login1_hint').show();
            return false;
        }
    }*/

    if(registerParam.email != "" && registerParam.username != "" && registerParam.password != ""){
        //隐藏提交按钮
        $('#reg_div .regist1_bt .btn_sub').hide();
        $('#reg_div .regist1_bt a').find('img').show();
        $.ajax({
            type: "POST",
            url: user_register,
            data: { params: registerParam},
            success: function(result){
                if(result.code == 200){
                    $("#reg_div").hide();
                    $("#feed_back_div").show();

                    //弹层倒计时
                    var i = 10;
                    setInterval(function(){
                        if(i == 0){
                            $("#feed_back_div").fadeOut(800);
                            //location.reload(); //重载页面
                            location.href = user_index;
                        }
                        document.getElementById('endTime').innerHTML = i--;
                    },1000);
                }else if(result.code == 100){
                    location.reload(); //重载页面
                }else{
                    $('.register_layer .login1_hint').html(result.msg);
                    $('.register_layer .login1_hint').show();
                    $('#reg_div .regist1_bt .btn_sub').show();
                    $('#reg_div .regist1_bt a').find('img').hide();
                }
            }
        });
    }
    /*var verify_code = $('.code').val(); //验证码
    $.ajax({
        type: "POST",
        url: register_check_url,
        data: { verify_code: verify_code},
        success: function(result){
            if(result.code == 100){
                alert(result.msg);
                return false;
            }else{
                $(".register_form").submit();
            }
        }
    });*/
}