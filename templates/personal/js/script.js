$(function(){
/** start script **/

// hide/show email/phone fields from type value

var email_field = $('.main .editprofile form [name="profile[email]"]').parents('.item_row').first();
var email_type = $('.main .editprofile form [name="profile[email_type]"]');

if ( email_type.val() !== 'set' ) {
	email_field.hide();
}

email_type.change(function(){
	if ( $(this).val() === 'set' ) {
		email_field.show();
	} else {
		email_field.hide();
	}
});

var phone_field = $('.main .editprofile form [name="profile[phone]"]').parents('.item_row').first();
var phone_type = $('.main .editprofile form [name="profile[phone_type]"]');

if ( phone_type.val() !== 'set' ) {
	phone_field.hide();
}

phone_type.change(function(){
	if ( $(this).val() === 'set' ) {
		phone_field.show();
	} else {
		phone_field.hide();
	}
});

// show modal window for change avatar on click by avatar image

$('.set_avatar').click(function(){
	$('#setavatar').modal();
});

$('.newproject').click(function(){
	$('#setproject').modal();
	$('#setproject').find('[name="project[id]"]').val('');
	$('#setproject').find('[name="project[title]"]').val('');
	$('#setproject').find('[name="project[description]"]').val('');
	$('#setproject').find('[name="query"]').val('createproject');
});

$('.editproject').click(function(){
	parent = $(this).parents('.item.project').first();
	$('#setproject').modal();
	$('#setproject').find('[name="project[id]"]').val( $.trim( parent.data('id') ) );
	$('#setproject').find('[name="project[title]"]').val( $.trim( parent.find('.item_name').text() ) );
	$('#setproject').find('[name="project[description]"]').val( $.trim( parent.find('.item_description').text() ) );
	$('#setproject').find('[name="query"]').val('updateproject');	
});

$('.lockproject').click(function(){
	parent = $(this).parents('.item.project').first();
	$.post(
		'/',
		{
			'query': 'lockproject',
			'data': {
				'pid': parent.data('id'),
			},
		}
	); 
	$(this)
		.toggleClass('fa-unlock-alt')
		.toggleClass('fa-lock');
	parent.toggleClass('locked');
	parent.find('.buttons').toggleClass('hidden');
});

// show projects by two columns on large screen

if ($(window).width() > 1199) {
	$('.item.project').addClass('half');
}

$(window).resize(function(){
	if ($(window).width() > 1199) {
		$('.item.project').addClass('half');
	} else {
		$('.item.project').removeClass('half');
	}
});

/** end script **/
});