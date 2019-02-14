<?php defined('isCMS') or die;

if (
	!isset($_SESSION['time']) ||
	$_SERVER['REQUEST_TIME'] > $_SESSION['time']
) {
	$_SESSION['time'] = $_SERVER['REQUEST_TIME'] + 3600; // задаем метку с запасом 1 час
	if (isset($_SESSION['token'])) {
		unset($_SESSION['token']); // очищаем токен
	}
}

/*
// задаем новый токен по истечении времени
if ( !isset($_SESSION['token']) ) {
	$token = '';
	for ($i = 0; $i < 16; $i++) {
		$token .= chr(mt_rand(32,126));
	}
	$_SESSION['token'] = $token; // задаем новый токен
}
*/

// задаем новый токен при каждом запросе
$token = '';
for ($i = 0; $i < 16; $i++) {
	$token .= chr(mt_rand(32,126));
}
$_SESSION['token'] = $token; // задаем новый токен
$_SESSION['oldtoken'] = $hash; // сохраняем старый токен

?>