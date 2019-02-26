<?php defined('isCMS') or die;

// создаем базовый объект
$error = (object) [
	'code' => htmlspecialchars($_GET['error']),
	'lang' => htmlspecialchars($_GET['lang']),
	'description' => '',
	'name' => '',
	'message' => '',
	'support' => '',
	'path' => __DIR__,
	'link' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/' . NAME_TEMPLATES . '/' . 'errors',
	'data' => (object) array(),
	'options' => (object) array()	
];

// читаем настройки и тексты для кодов ошибок
$error -> options = (object) dataprepare($error -> path . DIRECTORY_SEPARATOR . 'settings.ini');
$error -> data = (object) dataprepare($error -> path . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . 'lang.lng');

// проверяем адрес почты и если пустой, то устанавливаем дефолтный
if (!$error -> options -> mail) {
	$error -> options -> mail = 'support@' . $_SERVER['SERVER_NAME'];
}

// проверяем язык и устанавливаем первый их набора в качестве дефолтного
if (!in_array((string) $error -> lang, $error -> options -> langs)) {
	$error -> lang = $error -> options -> langs[0];
}

// проверяем и устанавливаем статус
if (in_array((string) $error -> code, $error -> options -> statuses)) {
	$error -> name = $error -> options -> status[$error -> lang];
} elseif (in_array((string) $error -> code, $error -> options -> errors)) {
	$error -> name = $error -> options -> error[$error -> lang];
}

// задаем базовые значения объектов для использования в шаблоне

$error -> description = (array)$error -> data;
$error -> description = $error -> description[(string) $error -> code][$error -> lang]['description'];
$error -> description = trim($error -> description);
$error -> message = (array)$error -> data;
$error -> message = $error -> message[(string) $error -> code][$error -> lang]['message'];
$error -> message = trim($error -> message);

$error -> support = $error -> options -> support[$error -> lang] . '<a href="mailto: ' . $error -> options -> mail . '?subject=' . $error -> name . '_' . $error -> code . '">' . $error -> options -> mail . '</a>';

// производим замену содержимого текста
if ($error -> message) {
	$error -> message = str_replace(
		[
			'{timer}',
			'{restore}',
			'{host}',
			'{block}',
			'{support}',
			'{phpversion}'
		],
		[
			'<span id="timer">' . $error -> options -> timer . '</span>',
			'<a href="/?query=restore">' . $error -> options -> restore[$error -> lang] . '</a>',
			'<a href="/">http://' . $_SERVER['SERVER_NAME'] . '</a>',
			$error -> options -> block[$error -> lang],
			'<a href="mailto: ' . $error -> options -> mail . '?subject=' . $error -> name . ' ' . $error -> code . ' (' . $_SERVER['SERVER_NAME'] . ')">' . $error -> options -> mail . '</a>',
			CMS_MINIMUM_PHP
		],
		$error -> message
	);
}

unset($error -> data);

/*
echo '<pre>';
print_r($error);
echo '</pre>';
exit;
*/

// загружаем шаблон
require_once $error -> path . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'default.php';

// функция dataprepare, объединяющая dataloadjson и dataclear
// с урезанными возможности только для данного конкретного случая

function dataprepare($data){
	
	if (!file_exists($data)) {
		return false;
	}
	
	$data = file_get_contents($data);
	
	// выполняем предварительное очищение - от переносов и табуляций
	$data = preg_replace('/\r\n/', '', $data);
	$data = preg_replace('/\n/', '', $data);
	$data = preg_replace('/\t/', ' ', $data);
	
	// выполняем предварительное очищение - от скриптов, программного кода
	$data = preg_replace('/<\?.+?\?>/','', $data);
	$data = preg_replace('/<script.+?\/script>/','', $data);
	
	// продолжаем предварительное очищение - от всех тегов, кроме разрешенных
	// задаем разрешенные теги
	$tags = ['br', 'p', 'span', 'b', 'i', 's', 'u', 'strong', 'em', 'del', 'small', 'sub', 'sup', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'ol', 'li', 'pre', 'hr'];
	// подготавливаем список
	$striptags = '';
	foreach ($tags as $tag) {
		$striptags .= '<' . $tag . '>';
	}
	// очищаем
	$data = strip_tags($data, $striptags);
	// завершаем
	unset($tags, $tag, $striptags);
	
	// продолжаем предварительное очищение - чистим текст от пробелов и отступов в начале и в конце
	$data = trim($data);
	$data = preg_replace('/^(&nbsp;)+/', '', $data);
	$data = preg_replace('/(&nbsp;)+$/', '', $data);
	
	// продолжаем предварительное очищение - чистим текст от двойных пробелов
	$data = preg_replace('/(\s|&nbsp;){2,}/', '$1', $data);
	
	$data = json_decode($data, true);
	
	return $data;
	
}

?>