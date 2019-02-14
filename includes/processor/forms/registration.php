<?php defined('isQUERY') or die;

function validate_form_registration($ban) {
	
	// Изначально ошибки отсутствуют
	$errors = array();
	
	// Пароль обязателен для заполнения и должен содержать не менее 6 допустимых символов
	if (! (isset($query -> data -> password) && (
		strlen($query -> data -> password) > 5 && strlen($query -> data -> password) < 31
	))) {
		$errors[] = 'password';
	}
	if (! (isset($query -> data -> passwordconfirm) && (
		$query -> data -> passwordconfirm === $query -> data -> password
	))) {
		$errors[] = 'passwordconfirm';
	}
	
	// E-mail обязателен для заполнения и должен отвечать регулярке
	if (! (isset($query -> data -> email) && (
		preg_match('/.+@.+\..+/', $query -> data -> email) && strlen($query -> data -> email) < 61
	))) {
		$errors[] = 'email';
	}
	
	// Телефон не обязателен, но если задан, то должен отвечать регулярке
	if ( $query -> data -> phone && (! (
		preg_match('/^[-0-9_ ()\+\s]+$/', $query -> data -> phone) && preg_match('/\d+/', $query -> data -> phone) && strlen($query -> data -> phone) > 9 && strlen($query -> data -> phone) < 31
	))) {
		$errors[] = 'phone';
	}
	
	// Проверка на существование подобных
	$indexArray = array('email','phone');
	foreach ($indexArray as $index) {
		$login = $query -> data -> $index;
		
		$loginq = 'spaces';
		if ($index == 'phone') {
			$loginq .= ' phone';
		} else {
			$loginq .= ' code';
		}
		$login = dataclear($login, $loginq);
		unset($loginq);
		
		if ($login !== '') {
			$userId = dbUser('search', array('field' => 'id', 'index' => $index, 'value' => $login));
		}
		if (count($userId)) {
			$errors[] = 'verifyreg';
		}
	}
	
	// Если есть бан, то проверяем и капчу заодно
	if ( isset($ban) && $ban > 0 ) {
		if (! (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $query -> data -> captcha) ) {
			$errors[] = 'captcha';
		}
	}
	
	return $errors;
}

?>