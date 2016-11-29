$(function() {
	//首页轮播图1
	$("#avatarUpload").uploadify({
        'auto'				: true,
        'multi'			: false,
        'uploadLimit'		: 999,
        'formData'			: {'uid':'18'},
        'buttonText'		: '选择图片',
        'buttonClass' : 'btn_sub',
        'height'			: 40,
        'width'			: 150,
        'color':'#fff',
        'removeCompleted'	: false,
        'swf'				: '/assets/uploadify_img/uploadify/uploadify.swf',
        'uploader'			: uploadUrl,
        'fileTypeExts'		: '*.gif; *.jpg; *.jpeg; *.png;',
        'fileSizeLimit'		: '5120KB',
		 //'debug'				: true,
		'onUploadSuccess' : function(file, data, response) {
			var msg = $.parseJSON(data);
            if( msg.result_code == 1 ){
                if(msg.max != ""){
                    var img_path = msg.result_small + "?preventCatch="+Math.floor(Math.random()*10000);
                    $("#img_url").val( msg.result_small );
                    $(".img_preview").attr("src",img_path);

                    var img_path = msg.result_des + "?preventCatch="+Math.floor(Math.random()*10000);
                    $("#course_banner").val( msg.result_des );
                    $(".banner_preview").attr("src",img_path);
                }else{
                    var img_path = msg.result_des + "?preventCatch="+Math.floor(Math.random()*10000);
                    var oriPath = msg.result_des;
                    var random = Math.floor(Math.random()*10000);
                    msg.result_des += "?preventCatch=" + random;
                    //清除图片信息
                    // $("div.jcrop-tracker").remove();
                    // $(".shadow").hide();
                    $("#img").val( oriPath );
                    $("#target").attr("src", msg.result_des );
                    $(".preview").attr("src", msg.result_des );
                }
            } else {
                alert('上传失败。');
            }
		},
		'onClearQueue' : function(queueItemCount) {
            alert( $('#img1') );
        },
		'onCancel' : function(file) {
            alert('The file ' + file.name + ' was cancelled.');
        }
    });
});

