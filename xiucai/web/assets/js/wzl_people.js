var reg_phone = /^0{0,1}(13[0-9]|15[0-9]|153|156|18[0-9])[0-9]{8}$/;
var reg_email = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
$(document).ready(function(){

    //昵称验证
    $('.people_message .nickname').blur(function(){
        var _username = $(this).val();
        if(_username != ""){
            //用户昵称唯一
            $.ajax({
                type: "POST",
                url: register_check_url,
                data: { username: _username},
                success: function(result){
                    if(result.code == 100){
                        layer.alert(result.msg);
                        return false;
                    }
                }
            });
        }
    });

    //邮箱验证
    $('.people_message .email').blur(function(){
        var _email = $(this).val();
        if(_email != ""){
            if(!reg_email.test(_email)){
                layer.alert('邮箱格式不正确，请重新输入');
                return false;
            }else{
                //验证邮箱唯一
                $.ajax({
                    type: "POST",
                    url: register_check_url,
                    data: { email: _email },
                    success: function(result){
                        if(result.code == 100){
                            layer.alert(result.msg);
                            return false;
                        }
                    }
                });
            }
        }
    });

    //手机号码验证
    $('.people_message .cellphone').blur(function(){
        var _cellphone = $(this).val();
        if(_cellphone != ""){
            if(!reg_phone.test(_cellphone)){
                layer.alert('手机格式不正确，请重新输入');
                return false;
            }else{
                //验证手机唯一
                $.ajax({
                    type: "POST",
                    url: register_check_url,
                    data: { cellphone: _cellphone },
                    success: function(result){
                        if(result.code == 100){
                            layer.alert(result.msg);
                            return false;
                        }
                    }
                });
            }
        }
    });

    //获取城市信息
    $('#people_message .province').change(function(){
        var province_id = $(this).val();
        if(province_id == ""){
            $('#people_message .city').html('<option value="">-请选择城市-</option>');
        }else{
            $.ajax({
                type: "POST",
                url: get_user_city,
                data: { province_id: province_id},
                success: function(result){
                    if(result.code == 200){
                        var city_str = "";
                        $.each(result.city , function(k , v){
                            city_str += '<option value="'+v.city_code+'">'+v.city_name+'</option>';
                        });
                        $('#people_message .city').html(city_str);
                    }
                }
            });
        }
    });

    //保存信息按钮事件
    /*$('.people_bto .btn_sub').click(function(){
        //昵称非空验证
        var nickname = $('.people_message .nickname').val();
        if(nickname == ''){
            layer.alert('请填写昵称。');
            return false;
        }

        //邮箱非空验证
        var email = $('.people_message .email').val();
        if(email != ""){
            if(!reg_email.test(email)){
                layer.alert('邮箱格式不正确，请重新输入。');
                return false;
            }
        }else{
            layer.alert('请填写邮箱地址。');
            return false;
        }
        document.member_editor_form.submit();
        //$('#member_editor_form').submit();
    });*/

    //取消按钮事件
    $('.people_bto .cancel_btn').click(function(){
        location.reload(); //重载页面
    });
})

function update_col(sex){
    if(sex == 1){
        $('#gender').val(1);
        document.getElementById('nan').style.backgroundColor="#cccccc";
        document.getElementById('nv').style.backgroundColor="#ffffff";
    }else{
        $('#gender').val(0);
        document.getElementById('nan').style.backgroundColor="#ffffff";
        document.getElementById('nv').style.backgroundColor="#cccccc";
    }
}
function messageShow(){
    var ms = document.getElementById("people_message").style.display;
    if( ms == "block"){
        document.getElementById("zoom_pic").src = "/assets/img/vedio_arrow_black.png";
        document.getElementById("people_message").style.display = "none";

    }else{
        document.getElementById("zoom_pic").src = "/assets/img/vedio_arrow_black_d.png";
        document.getElementById("people_message").style.display = "block";

    }
}