
$(document).ready(function()
{
	// pay2 Start
	$('.cart_list h6 .check').click(function()
	{
        if($(this).hasClass("open")){
            $(this).toggleClass('open');
            $("#needInvoice").val(0);
            $(".information_edit").hide();
            $(".information_list").hide();
        }else{
            $(this).toggleClass('open');
            $("#needInvoice").val(1);
            $('.check_bg .information_edit').toggle();
        }
	})
	
	$('.modify').click(function()
	{	
		$('.cart_list h6 .check').addClass('open');
		$('.check_bg .information_edit').show();
		$('.check_bg .information_list').hide();		
	})
	//pay2 End
	
	//pay3 Start
	$('.tab_menu ul li').each(function(index)
	{	
		$(this).click(function()
		{
			$(this).addClass('current');
			$(this).siblings().removeClass('current');
			$('.tab_content .tab_content'+(index+1)).show();
			$('.tab_content .tab_content'+(index+1)).siblings().hide();
		})
	})
	
	$('.tab_content1 ul li').click(function()
	{	
		$('.tab_content1 ul li').removeClass('current');	
		$(this).addClass('current');	
	})
	
	$('.choose').click(function()
	{
		$('.tab_content1 .more').toggle();
		if($(this).html('选择其它'))
		{
			$(this).html('收起');
		}
		else
		{
			$(this).html('选择其它');
		}
	})
	//pay3 End
})