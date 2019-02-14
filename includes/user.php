<?php defined('isCMS') or die;

if (
	($template -> name === NAME_PERSONAL && !cookie('UID', true)) ||
	($template -> name === NAME_PRIVATE && !cookie('PID', true))
) {
	error('401', $template -> lang);
}

if (cookie('UID', true)) {
	
	// выдаем ошибку, если нет папки соответствующего шаблона
	if (!file_exists(PATH_TEMPLATES . DIRECTORY_SEPARATOR . NAME_PERSONAL)) {
		error('404', $template -> lang);
	}
	
	// добавляем структуру пользовательских блоков в структуру сайта
	if (
		file_exists(PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . NAME_PERSONAL . '.ini')
	) {
		$template -> structure = array_merge(
			$template -> structure,
			//[NAME_PERSONAL => dataloadjson(PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . NAME_PERSONAL . '.ini', 'structure')]
			//[NAME_PERSONAL => dbUse(['name' => PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . NAME_PERSONAL . '.ini', 'json' => true, 'asarray' => 'structure'], 'select')]
			[NAME_PERSONAL => dbSelect('structures', NAME_PERSONAL)]
		);
	}
	
	// задаем базовые параметры пользователя
	
	$user = (object) array(
		'id' => $_COOKIE['UID'],
		'in' => preg_split('/[\/\\\]/', $_SERVER['REQUEST_URI'], null, PREG_SPLIT_NO_EMPTY)[0],
		'settings' => dbSelect('settings', 'userstable'),
		'data' => (object) array(),
		'db' => (object) array(),
		'var' => array()
	);
	
	foreach ($user -> settings as $key => $item) {
		if (!empty($item -> secure)) {
			unset($user -> settings -> $key);
		}
	}
	$user -> settings = array_keys((array)$user -> settings);
	
	$user -> db = dbUse('users', 'select', $user -> settings, ['id' => $user -> id], ['first' => true]);
	$user -> data = json_decode($user -> db['data']);
	
	unset($user -> db['data'], $user -> settings);
	
	//print_r($user);
	//exit;
	
}

if (cookie('PID', true)) {
	
	// выдаем ошибку, если нет папки соответствующего шаблона
	if (!file_exists(PATH_TEMPLATES . DIRECTORY_SEPARATOR . NAME_PRIVATE)) {
		error('404', $template -> lang);
	}
	
	// добавляем структуру пользовательских блоков в структуру сайта
	if (
		file_exists(PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . NAME_PRIVATE . '.ini')
	) {
		$template -> structure = array_merge(
			$template -> structure,
			//[NAME_PRIVATE => dataloadjson(PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . NAME_PRIVATE . '.ini', 'structure')]
			//[NAME_PRIVATE => dbUse(['name' => PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . NAME_PRIVATE . '.ini', 'json' => true, 'asarray' => 'structure'], 'select')]
			[NAME_PRIVATE => dbSelect('structures', NAME_PRIVATE)]
		);
	}
	
}

// проверяем UID и PID на определенное соответствие
// пока - этой проверки нет

/*
// дополняем путь
// по аналогии с админкой
if ($user -> in === NAME_PRIVATE) {
	$template -> curr -> path = '/' . NAME_PRIVATE . '/' . $template -> curr -> path;
} elseif ($user -> in === NAME_PERSONAL) {
	$template -> curr -> path = '/' . NAME_PERSONAL . '/' . $template -> curr -> path;
}
*/

/*
echo '<pre>';
print_r($path);
echo '</pre>';
*/
//exit;

?>