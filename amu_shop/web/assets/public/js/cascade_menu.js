$(document).ready(function(){
    //获取城市信息
    $("#province").change(function(){
        var province_id = $(this).val();
        if(province_id == ""){
            $('#city').html('<option value="">-请选择城市-</option>');
        }else{
            $.ajax({
                type: "POST",
                url: get_user_city_ajax,
                data: { province_id: province_id},
                success: function(result){
                    if(result.code == 200){
                        var city_str = "";
                        $.each(result.city , function(k , v){
                            city_str += '<option value="'+v.city_code+'">'+v.city_name+'</option>';
                        });
                        $('#city').html(city_str);
                    }
                }
            });
        }
    });

});
