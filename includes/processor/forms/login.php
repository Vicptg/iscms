<?php defined('isQUERY') or die;
	
$return = validate_form_login($attempts['ban'], $currlang);
$errorsLogin = $return['errors'];

if (count($errorsLogin)) {
	require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'templates.php';
} else {
	// Данные формы проверены, вывести сообщение для пользователя.
	// Вероятно, в реальном приложении будет выполнено
	// перенаправление или включен другой файл для отображения.
	$userData = $return['user'];
	if (count($userData)) {
		dbAttempts('delete', array($_SERVER['REMOTE_ADDR']));
		dbUser('update', array('id' => $userData['id'], 'parameter' => 'date_lastenter', 'value' => date('Y-m-d H:i:s')));
		session_regenerate_id();
		
		$cookie = $userData['id'] . md5($userData['id'] . session_id() . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
		$uD = json_decode($userData['userdata'], true);
		
		setcookie('UID', $cookie);
		setcookie('UN', $uD['name']);
		setcookie('LANG', $uD['language']);
		
		header("Location: /");
		exit;
	}
}

function validate_form_login($ban, $currlang) {
	
	$return = array();
	// Изначально ошибки отсутствуют
	
	$errors = array();
	
	// Имя обязательно для заполнения, должно содержать не менее 5 символов
	if (! (isset($_POST['datalogin']['login']) && (
		strlen($_POST['datalogin']['login']) > 5
	))) {
		$errors[] = 'loginlen';
	} else {
		$login = $_POST['datalogin']['login'];
	}
	
	// С паролем та же фигня
	if (! (isset($_POST['datalogin']['password']) && (
		strlen($_POST['datalogin']['password']) > 5
	))) {
		$errors[] = 'password';
	} else {
		$password = $_POST['datalogin']['password'];
	}
	
	// Проверяем, что это за имя такое - почта, телефон или логин
	if ( isset($login) && isset($password) ) {
		if ( preg_match('/.+@.+\..+/', $login) && strlen($login) < 61 ) {
			$index = 'email';
			$login = dataclear($login, 'nospaces code');
		} elseif ( preg_match('/^[-0-9_ ()\+\s]+$/', $login) && preg_match('/\d+/', $login) ) {
			$index = 'phone';
			$login = dataclear($login, 'nospaces phone');
			if (strlen($login) < 10 || strlen($login) > 12) {
				$errors[] = 'phonelen';
			}
			if ($currlang === 'ru') {
				$login = dataclear($login, 'phone_ru');
			}
		}
	}
	
	if (isset($index) && (
		$index == 'email' || $index == 'phone'
	)) {
		// А теперь соединяемся с базой, находим по имени пароль и сверяем его со введенным
		$findpassword = dbUser('search', array('field' => 'password', 'index' => $index, 'value' => $login));
		if (! (count($findpassword) > 0 && password_verify($password, $findpassword[0])) ) {
			$errors[] = 'verifylogin';
		} else {
			$userId = dbUser('search', array('field' => 'id', 'index' => $index, 'value' => $login));
			if ( count($userId) === 1 ) {
				$return['user'] = dbUser('load', array('value' => $userId[0]));
			} else {
				$errors[] = 'verify';
			}
		}
	} else {
		$errors[] = 'enter';
		// ошибка возникает даже при правильном логине, если не задан пароль, т.к. проверка идет только в связке логин+пароль
		// но это даже лучше, т.к. мы не подсказываем злоумышленнику, что именно введено неправильно
	}
	
	// Если есть бан, то проверяем и капчу заодно
	if ( isset($ban) && $ban > 0 ) {
		if (! (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['datalogin']['captcha']) ) {
			$errors[] = 'captcha';
		}
	}
	
	$return['errors'] = $errors;
	return $return;
}

?>