$(function(){
	
	$('.frontend.admin_module_view').parent().addClass('frontend relative');
	$('.frontend.admin_module_view p').click(function(){
		$(this).siblings('div').toggleClass('hidden');
	});
	$('.frontend.admin_module_view > div').click(function(){
		$(this).toggleClass('hidden');
	});
	
	$('.frontend .module.off').click(function(){
		$('.frontend.admin_module_view, .FRONTEND.ADMIN_MODULE_VIEW').each(function(){
			$(this).remove();
		});
		$('.frontend .module.off').addClass('hidden');
		$('.frontend .module.on').removeClass('hidden');
	});
	$('.frontend .module.on').click(function(){
		location.reload();
	});
	
});