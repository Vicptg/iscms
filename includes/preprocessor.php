<?php
defined('isCMS') or die;

define('isQUERY', 1);

$query = (object) array('name' => '', 'param' => '', 'status' => '', 'hash' => '', 'data' => '', 'open' => '', 'method' => strtolower($_SERVER['REQUEST_METHOD']), 'errors' => [], 'var' => []);

if ($query -> method === 'post') {
	$query -> name = ($_POST['query']) ? dataclear($_POST['query'], 'simpleurl') : '';
	$query -> param = ($_POST['param']) ? dataclear($_POST['param'], '') : '';
	$query -> status = ($_POST['status']) ? dataclear($_POST['status'], 'simpleurl') : '';
	$query -> hash = ($_POST['hash']) ? dataclear($_POST['hash'], '') : '';
	$query -> data = ($_POST['data']) ? $_POST['data'] : '';
} else {
	$query -> name = ($_GET['query']) ? dataclear($_GET['query'], 'simpleurl') : '';
	$query -> param = ($_GET['param']) ? dataclear($_GET['param'], '') : '';
	$query -> status = ($_GET['status']) ? dataclear($_GET['status'], 'simpleurl') : '';
	$query -> hash = ($_GET['hash']) ? dataclear($_GET['hash'], '') : '';
	$query -> data = ($_GET['data']) ? $_GET['data'] : '';	
}

if ($query -> data) {
	if (is_array($query -> data)) {
		$query -> data = (object) $query -> data;
	} elseif (!is_object($query -> data)) {
		$query -> data = (object) array('default' => $query -> data);
	}
}

// Проверка правильности запроса
// Проверка осуществляется по наличию хэша в запросе и соответствия его времени не больше 10 минут
// Либо должен быть специальный хэш - начинаться на = и состоять из 10 знаков
// В случае тестирования, данную проверку можно отключить, просто закомментировав код ниже

if (
	( strlen($query -> hash) === 11 && substr($query -> hash, 0, 1) === '=' && !is_numeric(substr($query -> hash, 1)) ) ||
	( ((int) datacrypt($query -> hash, 1) + TIME_MINUTE * 10) > time() )
) {
} else {
	error('400', $currlang);
}

// Проверка вызова обработчиков модулей

if (file_exists(PATH_MODULES . DIRECTORY_SEPARATOR . $query -> name . DIRECTORY_SEPARATOR . 'query.php')) {
	require_once PATH_MODULES . DIRECTORY_SEPARATOR . $query -> name . DIRECTORY_SEPARATOR . 'query.php';
}

// Поиск зарегистрированного обработчика процессов

else {
	
	//$data = dataloadjson(PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'processor.ini', true);
	//$data = dbUse(['name' => PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'processor.ini', 'json' => true], 'select');
	$data = dbSelect('settings', 'processor');
	//print_r($data);
	
	foreach ($data as $key => $array) {
		
		if (in_array($query -> name, $array) !== false) {
			$query -> open = PATH_INCLUDES . DIRECTORY_SEPARATOR . 'processor' . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR;
			$query -> open .= ($query -> status) ? $query -> status : $query -> name;
			$query -> open .= '.php';
			break;
		}
		
	}
	
	unset($data, $key, $array);
	
	if ($query -> open && file_exists($query -> open)) {
		require_once $query -> open;
		/*
		echo $query -> open;
		print_r($query);
		exit;
		*/
	} else {
		error('400', $currlang);
	}
	
}

unset($query -> var);

?>