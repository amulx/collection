//姓名
function test_name(str_name)
{
    //名字为1到20个中英文([\u4e00-\u9fa5]+|([a-z]+\s?)+){1,12}
    var pattern = /^([\u4e00-\u9fa5]+|([a-zA-Z]+\s?)+)$/ ;
    if(pattern.test(str_name))
        return true;
    else
        return false;
}

//密码
function test_password(str_pwd)
{
    //6~12位英文字母、数字或字符的组合
    var pattern = /^\w{6,12}$/;
    if(pattern.test(str_pwd))
        return true;
    else
        return false;
}

//年龄
function test_age(str_age)
{
    //请输入数字
    return !isNaN(str_age)
}

//手机
function test_mobile(str_mobile)
{
    //手机号码为11位数字
    var pattern = /^[\d]{11}$/;
    if(pattern.test(str_mobile))
        return true;
    else
        return false;
}

//E-mail地址
function test_email(str_email)
{
    //E-mail地址格式不正确
    var pattern = /^[a-zA-Z0-9_.]+@([a-zA-Z0-9_]+.)+[a-zA-Z]{2,3}$/;
    if(pattern.test(str_email))
        return true;
    else
        return false;
}