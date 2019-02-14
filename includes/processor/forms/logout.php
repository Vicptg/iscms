<?php defined('isQUERY') or die;

// удаляем все переменные сессии

session_start();
foreach ($_SESSION as $key => $session) {
	unset($_SESSION[$key]);
}
session_destroy();

// удаляем все куки

foreach ($_COOKIE as $key => $session) {
	unset($_COOKIE[$key]);
	setcookie($key, '', time() - 3600);
}

// очищаем попытки из базы данных, иначе при последовательном выходе 10 пользователей с одного ip, адрес будет заблокирован

dbAttempts('delete', array($_SERVER['REMOTE_ADDR']));

// переходим на главную

header("Location: /");
exit;

?>