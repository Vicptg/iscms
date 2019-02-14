<?php defined('isQUERY') or die;

session_start();

$attempts = dbAttempts('verify', array($_SERVER['REMOTE_ADDR']));

if ( !isset($attempts['ban']) || $attempts['ban'] < 2 ) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 400 FORBIDDEN', true, 400);
	header("Location: /index.php?error=400&lang=" . $currlang);
	exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	// Запрос относится к типу GET, вывести форму
	$errorsRestore = array();
	$salt = dechex(time());
	$restorehash = $salt . substr(MD5($_SESSION['token'] . $salt), 0, 30);
	$_SESSION['template'] = 'restore';
	require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'templates.php';
} else {
	// Запрос относится к типу POST, проверить данные на форме
	unset($_SESSION['template']);
	$return = validate_form_restore($attempts['ban'], $currlang);
	$errorsRestore = $return['errors'];
	
	if (count($errorsRestore) || defined('isHACK')) {
		// Если обнаружены ошибки, выбросить
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 FORBIDDEN', true, 400);
		header("Location: /index.php?error=400&lang=" . $currlang);
		exit;
	} else {
		// Данные формы проверены, вывести сообщение для пользователя.
		// Вероятно, в реальном приложении будет выполнено
		// перенаправление или включен другой файл для отображения.
		dbAttempts('delete', array($attempts['ip']));
		header("Location: /index.php?error=201&lang=" . $currlang);
		exit;
	}
	
}

function validate_form_restore($ban, $currlang) {
	
	$return = array();
	// Изначально ошибки отсутствуют
	
	$errors = array();
	
	// Почта обязательна для заполнения
	if (! (isset($_POST['datarestore']['email']) &&
		strlen($_POST['datarestore']['email']) > 5 &&
		preg_match('/.+@.+\..+/', $_POST['datarestore']['email']) &&
		strlen($_POST['datarestore']['email']) < 61
	)) {
		$errors['email'] = 1;
	}
	
	// С паролем та же фигня
	if (! (isset($_POST['datarestore']['password']) && (
		strlen($_POST['datarestore']['password']) > 5
	))) {
		$errors['password'] = 1;
	} else {
		$password = $_POST['datarestore']['password'];
	}
	
	// Если есть бан, то проверяем и капчу заодно
	if (! (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['datarestore']['captcha']) ) {
		$errors['captcha'] = 'Данные капчи указаны неверно. ';
	}
	
	if(!count($errors)) {
		$email = $_POST['datarestore']['email'];
		$email = dataclear($email, 'nospaces code');
		
		// А теперь соединяемся с базой, находим по имени пароль и сверяем его со введенным
		$findpassword = dbUser('search', array('field' => 'password', 'index' => 'email', 'value' => $email));
		
		if (! (count($findpassword) > 0 && password_verify($password, $findpassword[0])) ) {
			$errors['verify'] = $errors['verify'] + 1;
		}
	}
	
	$return['errors'] = $errors;
	return $return;
}

?>