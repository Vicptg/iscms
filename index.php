<?php
define('isCMS', '0.4');
define('CMS_MINIMUM_PHP', '5.6.0');
define('PATH_BASE', __DIR__);
//define('PATH_BASE', $_SERVER['DOCUMENT_ROOT']);

// Подключаем файл конфигурации

require_once PATH_BASE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'configuration.php';

// Проверяем на ошибки

if (isset($_GET['error'])) {
	require_once PATH_TEMPLATES . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . 'template.php';
	exit;
}
if (version_compare(PHP_VERSION, CMS_MINIMUM_PHP, '<')) {
	header("Location: /index.php?error=php");
	exit;
}

// Подключаем обработчики процессов

require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'verification.php';
require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'languages.php';
require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'functions.php';

// Если был инициирован запрос query, запускаем препроцессор

if (isset($_POST['query']) || isset($_GET['query'])) {
	require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'preprocessor.php';
}

// Загружаем шаблонизатор и запускаем сайт

require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'templates.php';

?>