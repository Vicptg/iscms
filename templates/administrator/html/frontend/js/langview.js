$(function(){
	
	$('.frontend .lang.off').click(function(){
		$('.frontend.admin_lang_view, .FRONTEND.ADMIN_LANG_VIEW').each(function(){
			$(this).after($(this).html());
			$(this).remove();
		});
		$('.frontend .lang.off').addClass('hidden');
		$('.frontend .lang.on').removeClass('hidden');
		$('.frontend .lang.info').remove();
	});
	$('.frontend .lang.on').click(function(){
		location.reload();
	});
	
	$('.frontend.admin_lang_view, .FRONTEND.ADMIN_LANG_VIEW').hover(function(){
		$('.frontend .lang.info').html('lang:' + $(this).data('lang'));
	}, function(){
		$('.frontend .lang.info').html('lang:');
	});
	
});