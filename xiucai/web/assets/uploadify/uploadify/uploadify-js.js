$(document).ready(function(){
	var jcrop_api, boundx, boundy;

	//首页轮播图1
	$("#avatarUpload").uploadify({
        'auto'				: true,
        'multi'			: false,
        'uploadLimit'		: 999,
        'formData'			: {'uid':'18'},
        'buttonText'		: '选择头像',
        'buttonClass' : 'btn_sub',
        'height'			: 40,
        'width'			: 150,
        'color':'#fff',
        'removeCompleted'	: false,
        'swf'				: '/assets/uploadify_img/uploadify/uploadify.swf',
        'uploader'			: uploadUrl,
        'fileTypeExts'		: '*.gif; *.jpg; *.jpeg; *.png;',
        'fileSizeLimit'		: '2048KB',
		// 'debug'				: true,
		'onUploadSuccess' : function(file, data, response) {
			var msg = $.parseJSON(data);
			if( msg.result_code == 1 ){
				var oriPath = msg.result_des;
				var random = Math.floor(Math.random()*10000);
				msg.result_des += "?preventCatch=" + random;
				//清除图片信息
				// $("div.jcrop-tracker").remove();
				// $(".shadow").hide();
				$("#img").val( oriPath );
				$("#target").attr("src", msg.result_des );
				$(".preview").attr("src", msg.result_des );
				$(".jcrop-holder img").attr("src" , $("#target").attr("src"));


				$('#target').Jcrop({
					minSize: [50,50],
					setSelect: [0,0,200,200],
					onChange: updatePreview,
					onSelect: updatePreview,
					onSelect: updateCoords,
					aspectRatio: 1
				},
				function(){
					// Use the API to get the real image size
					var bounds = this.getBounds();
					boundx = bounds[0];
					boundy = bounds[1];
					// Store the API in the jcrop_api variable
					jcrop_api = this;
					updateCoords(jcrop_api.tellSelect());
				});
				// $(".imgchoose").show(1000);
				//$("#avatar_submit").show(1000);
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

	//头像裁剪
	function updateCoords(c)
	{
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};
	function checkCoords()
	{
		if (parseInt($('#w').val())) return true;
		alert('请选择图片上合适的区域');
		return false;

	};

	function updatePreview(c){
		if (parseInt(c.w) > 0){
			var rx = 112 / c.w;
			var ry = 112 / c.h;
			$('#preview1').css({
				width: Math.round(rx * boundx) + 'px',
				height: Math.round(ry * boundy) + 'px',
				marginLeft: '-' + Math.round(rx * c.x) + 'px',
				marginTop: '-' + Math.round(ry * c.y) + 'px'
			});
		}
		{
			var rx = 130 / c.w;
			var ry = 130 / c.h;
			$('#preview2').css({
				width: Math.round(rx * boundx) + 'px',
				height: Math.round(ry * boundy) + 'px',
				marginLeft: '-' + Math.round(rx * c.x) + 'px',
				marginTop: '-' + Math.round(ry * c.y) + 'px'
			});
		}
		{
			var rx = 200 / c.w;
			var ry = 200 / c.h;
			$('#preview3').css({
				width: Math.round(rx * boundx) + 'px',
				height: Math.round(ry * boundy) + 'px',
				marginLeft: '-' + Math.round(rx * c.x) + 'px',
				marginTop: '-' + Math.round(ry * c.y) + 'px'
			});
		}
	};
	//document.domain='xiucai.com';
	$("#avatar_submit").click(function(){
		var img = $("#img").val();
		var x = $("#x").val();
		var y = $("#y").val();
		var w = $("#w").val();
		var h = $("#h").val();


		var member_id = $("#member_id").val();
		if( checkCoords() ){
			$.ajax({
				type: "GET",
				url: "/assets/uploadify/resize.php",
				data: {"img":img,"x":x,"y":y,"w":w,"h":h,"member_id": member_id},
				dataType: "json",
				success: function(msg){
                    //msg = $.parseJSON(msg);
                    if( msg.result_code == 1 ){
                    	$('#avatar').attr('src', msg.result_des.big + "?preventCatch="+Math.floor(Math.random()*10000));
                    	$('#head_pic').val(msg.result_des.small);
                        $('#head_pic_large').val(msg.result_des.big);
                    	jcrop_api.destroy();
                    	/*$('html,body').animate({scrollTop:$('#avatar').offset().top-150},1000,'swing',function(){
                    	});*/
                    } else {
                    	alert("上传失败。");
                    }
                },
                error: function () { }
            });
		}

		//$(".jcrop-holder").css("width","0");
		//$(".jcrop-holder").css("height","0");
		$("#pop_bg").hide();
        $("#layer_bg").hide();
		
		var tw=$("#target").attr("width");
		var th=$("#target").attr("height");
		$(".jcrop-tracker img").css("width" ,tw );
		$(".jcrop-tracker img").css("height" , th);
	});

    $(".pop_bg .res").click(function(event){
        event.preventDefault();
        $("#pop_bg").hide();
        $("#layer_bg").hide();
        jcrop_api.destroy();
    });

});
