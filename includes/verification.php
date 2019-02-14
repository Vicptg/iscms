<?php defined('isCMS') or die;

// код ниже должен исключать проверки по хэшу и бан, если у пользователя есть куки и куки верные

if (DB_TYPE && DB_TYPE !== 'nodb') {
	
	if (isset($_COOKIE['UID'])) {
		
		session_start();
		
		$userID = substr($_COOKIE['UID'], 0, 13);
		$cookie = $userID . md5($userID . session_id() . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
		
		if ($_COOKIE['UID'] === $cookie) {
			define('isORIGIN', 1);
		}
		
	} else {
		
		if (!defined('isORIGIN')) {
			require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'verify' . DIRECTORY_SEPARATOR . 'ban.php';
			exit;
		}
		
		session_start();
		
		$hash = $_POST['hash'];
		$salt = substr($hash, 0, 8);
		
		if ($hash === $_SESSION['oldtoken']) {
			header("Location: /");
			exit;
		}
		
		if (
			// !$hash || // - это правило заставляет считать все запросы, отправленные без хэша, хаком ! Перед активацией нужно проверить запросы редактора через ajax
			( (time() - hexdec($salt)) < 0 || (time() - hexdec($salt)) > 600 ) &&
			($salt . substr(MD5($_SESSION['token'] . $salt), 0, 30)) !== $hash
		) {
			define('isHACK', 1);
		}
		
		if ($_POST['query'] !== 'restore') {
			
			// Первый этап проверки - записи в сессиях: время последнего запроса и токен
			require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'verify' . DIRECTORY_SEPARATOR . 'session.php';
			
			// Второй этап проверки - записи в базе данных: бан, число попыток и время последней попытки
			require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'verify' . DIRECTORY_SEPARATOR . 'ban.php';
			
		}
		
	}
	
} else {
	
	// Проверяем источник запроса
	
	session_start();
	$_SESSION['INIT'] = 1; // это сделано для защиты от запросов с других сайтов и источников
	
	if (
		!isset($_SESSION['INIT']) ||
		!isset($_SERVER['HTTP_USER_AGENT']) ||
		(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '//' . $_SERVER['SERVER_NAME'] . '/') === false)
	) {
		// this request is identified by us as an attempt to hack the site
		unset($_SESSION, $_COOKIE, $_SERVER['HTTP_COOKIE']);
		session_write_close();
		header($_SERVER['SERVER_PROTOCOL'] . ' 502 FORBIDDEN', true, 502);
		header("Location: /index.php?error=502");
		exit;
	} else {
		unset($_SESSION['INIT']);
		//define('isORIGIN', 1);
	}
	
}

?>