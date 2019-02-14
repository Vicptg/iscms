<?php defined('isCMS') or die;

// отменяем админку, если нет папки шаблона админки

if (!file_exists(PATH_TEMPLATES . DIRECTORY_SEPARATOR . NAME_ADMINISTRATOR)) {
	error('404', $template -> lang);
}

// проверяем наличие файла index в заданной папке админки
// а также перед тем как вывести авторизацию, проверяем, что был вызван именно он
// и что его размер и содержимое не было изменено

if (!cookie('AID', true)) {
	
	//echo 'not AID';
	
	if (!defined('isADMIN')) {
		header("Location: /" . NAME_ADMINISTRATOR . "/");
		exit;
	} elseif (
		file_exists(PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'index.php') &&
		file_exists(PATH_TEMPLATES . DIRECTORY_SEPARATOR . NAME_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'authorise' . DIRECTORY_SEPARATOR . 'template.php') &&
		str_replace(['\\', '/'], '', $_SERVER['REQUEST_URI']) === NAME_ADMINISTRATOR &&
		md5(filesize(PATH_SITE . str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $_SERVER['PHP_SELF']))) === '3ef815416f775098fe977004015c6193' &&
		md5(file_get_contents(PATH_SITE . str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $_SERVER['PHP_SELF']))) === '8eaf46990c0751b952c46b6870beb682'
	) {
		require_once PATH_TEMPLATES . DIRECTORY_SEPARATOR . NAME_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'authorise' . DIRECTORY_SEPARATOR . 'template.php';
		exit;
	} else {
		error('403', $template -> lang);
	}
	
}

//echo 'AID!';

// проверяем AID на определенное соответствие
// пока - этой проверки нет

// задаем базовые параметры админки

$administrator = (object) array(
	'path' => (object) array(
		'base' => PATH_TEMPLATES . DIRECTORY_SEPARATOR . NAME_ADMINISTRATOR,
		'url' => $template -> url . '/' . NAME_ADMINISTRATOR,
		'curr' => 'template.php'
	),
	'in' => (preg_split('/[\/\\\]/', $_SERVER['REQUEST_URI'], null, PREG_SPLIT_NO_EMPTY)[0] === NAME_ADMINISTRATOR) ? true : false,
	//'settings' => dataloadjson(PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'settings.ini')
	//'settings' => dbUse(['name' => PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'settings.ini', 'json' => true], 'select')
	'settings' => dbSelect('settings', 'settings')
);

$template -> administrator = true;

//print_r($administrator);

// добавляем структуру админки в структуру сайта

$template -> structure = array_merge(
	$template -> structure,
	//[NAME_ADMINISTRATOR => dataloadjson(PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'administrator.ini', 'structure')]
	//[NAME_ADMINISTRATOR => dbUse(['name' => PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'administrator.ini', 'json' => true, 'asarray' => 'structure'], 'select')]
	[NAME_ADMINISTRATOR => dbSelect('structures', 'administrator')]
);

// если включена настройка отображать фронтэнд, то загружаем его шаблон
// на самом деле здесь мы дополняем путь
// да, это на самом деле работает, и работает так:
// на момент вызова этого файла, объект $template уже задан с базовыми параметрами, в частности путь - пустой
// путь является относительным, используется в ссылках, формируется после роутера
// а здесь - к пути добавляется название админки
// единственное, что требует проверки - это слеши, по идее, после этого представления путь будет таким: "/administrator//page"

if ($administrator -> in) {
	$template -> curr -> path = '/' . NAME_ADMINISTRATOR . '/' . $template -> curr -> path;
}

/*
echo '<pre>';
print_r($path);
echo '</pre>';
*/
//exit;

?>