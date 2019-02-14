<?php

// создание констант времени

define('TIME_MINUTE', 60);
define('TIME_HOUR', 3600);
define('TIME_DAY', 86400);
define('TIME_WEEK', 604800);
define('TIME_MONTH', 2629800);
define('TIME_YEAR', 31556926);

// инициализация работы с локальными данными

require_once 'classes' . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'htmlelement.php';

require_once 'functions' . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'files.php';
require_once 'functions' . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'data.php';
require_once 'functions' . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'math.php';
require_once 'functions' . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'functions.php';
require_once 'functions' . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'custom.php';

// инициализация работы с базами данных

if (DB_TYPE && DB_TYPE !== 'nodb') {
	
	// языковой пакет ошибок
	
	$dberrors = (object) array(
		'block' => '<p style="text-align: center; display: block; margin: 10px auto; padding: 10px; background: #F44336; color: white; width: 50%;">',
		'unsupport' => (object) array(
			'ru' => 'Ошибка: Неподдерживаемый тип базы данных!',
			'en' => 'Error: Unsupported database type!',
		),
		'connect' => (object) array(
			'ru' => 'Ошибка: Невозможно подключиться к базе данных!',
			'en' => 'Error: Unable to database connect!',
		),
		'charset' => (object) array(
			'ru' => 'Ошибка: Не установлена кодировка соединения!',
			'en' => 'Error: Charset database not set!',
		),
		'query' => (object) array(
			'ru' => 'Ошибка: Ошибка в запросе в базу данных!',
			'en' => 'Error: Invalid database query!',
		),
	);
	
	if (DB_TYPE === 'mysqli' || DB_TYPE === 'mysqli-test') {
		
		// инициализация работы с базой данных через библиотеку mysqli [OLD!]
		//require_once 'functions' . DIRECTORY_SEPARATOR . 'mysqli_data.php';
		//require_once 'functions' . DIRECTORY_SEPARATOR . 'mysqli_old.php';
		//$connect = dbConnect();
		
		// инициализация работы с базой данных через класс над библиотекой mysqli [NEW]
		// что позволило сократить и оптимизировать код, а также использовать стандартные настройки
		// теперь не нужно переписывать весь (!) config, а изменить лишь DB_TYPE с 'mysqli' на 'mysqli-test'
		
		require_once 'classes' . DIRECTORY_SEPARATOR . 'safemysql' . DIRECTORY_SEPARATOR . 'safemysql.php';
		
		$dbset = array('db' => DB_NAME);
		
		if (DB_TYPE === 'mysqli') {
			$dbset = array(
				'host' => DB_HOST,
				'user' => DB_USER,
				'pass' => DB_PASS,
				'db' => DB_NAME,
				// optional: 'port' => DB_PORT,
				'charset' => 'utf8'
			);
		}
		
		$db = new SafeMysql($dbset);
		
		require_once 'functions' . DIRECTORY_SEPARATOR . 'mysqli' . DIRECTORY_SEPARATOR . 'functions.php';
		
	} else {
		
		// инициализация работы с базой данных через библиотеку pdo
		
		require_once 'functions' . DIRECTORY_SEPARATOR . 'pdo' . DIRECTORY_SEPARATOR . 'functions.php';	
		
		$pdo = PDO::getAvailableDrivers();
		if (!in_array(DB_TYPE, $pdo)) {
			die( $dberrors -> block . $dberrors -> unsupport -> $currlang );
		}
		
		$connect = dbPDOConnect();
		
	}
	
} else {
	
	// инициализация работы с базой данных в виде локальных файлов
	
	require_once 'functions' . DIRECTORY_SEPARATOR . 'local' . DIRECTORY_SEPARATOR . 'functions.php';	
	
}

?>