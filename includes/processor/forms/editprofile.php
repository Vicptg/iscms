<?php defined('isQUERY') or die;

$types = array('encrypt', 'hide', 'show', 'set');
$profile = $_POST['profile'];

$errorsEditprofile = validate_form_editprofile($currlang);

if (count($errorsEditprofile)) {
	header("Location: /".NAME_PERSONAL."/editprofile");
	exit;
} else {
	// Данные формы проверены, вывести сообщение для пользователя.
	dbUser('update', array('id' => $userID, 'parameter' => 'date_birthday', 'value' => $profile['birthday']));
	
	unset($profile['birthday']);
	unset($profile['lastenter']);
	
	setcookie('UN', $profile['name']);
	setcookie('LANG', $profile['language']);
	
	$profile = json_encode($profile);
	//print_r($profile);
	
	dbUser('update', array('id' => $userID, 'parameter' => 'userdata', 'value' => $profile));
	
	header("Location: /".NAME_PERSONAL."/profile");
	exit;
}

function validate_form_editprofile($currlang) {
	
	global $profile;
	global $types;
	global $langs;
	
	// Изначально ошибки отсутствуют
	$errors = array();
	
	// Имя должно содержать не более 62 допустимых символов
	if ( $profile['name'] && (! (
		!preg_match('/[\"\:~`!@$%\?\=\*()<>\[\]\|\/\№]+/', $profile['name']) &&
		strlen($profile['name']) < 61
	))) {
		$errors['name'] = 'Имя содержит недопустимые символы. ';
	}
	
	// Аватар должен быть закодирован base64
	if ( $profile['avatar'] && (! (
		preg_match('/(([A-Za-z0-9+/]{4})*([A-Za-z0-9+/]{3}=|[A-Za-z0-9+/]{2}==)?){1}/', $profile['avatar'])
	))) {
		$errors['avatar'] = 'Имя содержит недопустимые символы. ';
	}
	
	// Дата рождения должна содержать не более 32 допустимых символов
	if ( $profile['birthday'] && (! (
		preg_match('/^[0-9-\.\:\,]+$/', $profile['birthday']) &&
		strlen($profile['birthday']) < 31
	))) {
		$errors['birthday'] = 'Дата рождения содержит недопустимые символы. ';
	}
	
	// О себе должно содержать не более 256 допустимых символов
	if ( $profile['about'] && (! (
		!preg_match('/[~`@$%\=<>\{\}\[\]\\\|\/]+/', $profile['about']) &&
		strlen($profile['about']) < 255
	))) {
		$errors['about'] = 'Информация о себе содержит недопустимые символы. ';
	}
	
	// Выбор языка не обязателен, но если значение отправлено, оно должно присутствовать в массиве $langs
	if ($profile['language']) {
		$cl = 0;
		foreach ($langs as $item) {
			if (in_array($profile['language'], $item)) {
				$cl++;
			}
		}
		if ($cl < 1) {
			$errors['language'] = 'Указанный язык не найден в системе ';
		}
	}
	
	// Страна должна содержать не более 62 допустимых символов
	if ( $profile['country'] && (! (
		!preg_match('/[\"\:~`!@$%\?\=\*()<>\[\]\|\/\№]+/', $profile['country']) &&
		strlen($profile['country']) < 61
	))) {
		$errors['country'] = 'Поле "страна" содержит недопустимые символы. ';
	}
	
	// Город должен содержать не более 62 допустимых символов
	if ( $profile['city'] && (! (
		!preg_match('/[\"\:~`!@$%\?\=\*()<>\[\]\|\/\№]+/', $profile['city']) &&
		strlen($profile['city']) < 61
	))) {
		$errors['city'] = 'Поле "город" содержит недопустимые символы. ';
	}
	
	// Адрес должен содержать не более 62 допустимых символов
	if ( $profile['address'] && (! (
		!preg_match('/[\"\:~`!@$%\?\=\*()<>\[\]\|\/\№]+/', $profile['address']) &&
		strlen($profile['address']) < 61
	))) {
		$errors['address'] = 'Поле "адрес" содержит недопустимые символы. ';
	}
	
	// Тип почты должен присутствовать в массиве $types
	if ( $profile['email_type'] && (!in_array($profile['email_type'], $types)) ) {
		$errors['email_type'] = 'Тип отображения электронной почты задан неверно. ';
	}
	
	// Адрес почты должен содержать не более 62 допустимых символов
	if ( $profile['email'] && (! (
		preg_match('/.+@.+\..+/', $profile['email']) &&
		strlen($profile['email']) < 61
	))) {
		$errors['email'] = 'E-mail содержит недопустимые символы. ';
	}
	
	// Тип телефона должен присутствовать в массиве $types
	if ( $profile['phone_type'] && (!in_array($profile['phone_type'], $types)) ) {
		$errors['phone_type'] = 'Тип отображения электронной почты задан неверно. ';
	}
	
	// Номер телефона должен содержать не более 32 допустимых символов
	if ( $profile['phone'] && (! (
		preg_match('/^[-0-9_ ()\+\s\*]+$/', $profile['phone']) &&
		strlen($profile['phone']) < 31
	))) {
		$errors['phone'] = 'Телефон содержит недопустимые символы. ';
	}
	
	foreach ($profile['social'] as $key => $item) {
		if ( $profile['social'][$key] && (! (
			!preg_match('/[\"\:~`!@$%\?\=\*()<>\[\]\|\/\№]+/', $profile['social'][$key])
		))) {
			$errors['social'][$key] = 'Поле содержит недопустимые символы. ';
		}
	}
	
	return $errors;
}

?>