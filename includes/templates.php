<?php defined('isCMS') or die;
	
	// базовые установки для всех шаблонов
	
	//echo '<!-- memory for initialisation is ' , round(memory_get_peak_usage() / 1024, 2) , ' kb -->';
	
	$template = (object) array(
		'name' => 'default',
		'lang' => $currlang,
		'langcode' => '',
		'langs' => array(),
		'url' => (((isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https') || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')) ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . (($_SERVER['SERVER_PORT'] != 80) ? ':' . $_SERVER['SERVER_PORT'] : ''),
		'site' => $_SERVER['SERVER_NAME'],
		'structure' => array(),
		'router' => (object) array(),
		'curr' => (object) array(
			'url' => '',
			'php' => '',
			'html' => '',
			'inner' => '',
			'page' => '',
			'path' => '',
		),
		'base' => (object) array(
			'url' => '',
			'php' => '',
			'item' => (object) array()
		),
		'administrator' => false,
		'libraries' => array(),
		'param' => array(),
		'css' => array(),
		'js' => array(),
		'less' => array(),
		'special' => array(),
		'seo' => (object) array(),
		'device' => (object) array(
			'type' => '',
			'os' => '',
		),
		'var' => array()
	);
	
	// настраиваем базовые параметры
	
	$template -> structure = dbSelect('structures', 'structure');
	
	// настраиваем языковые параметры
	
	$currlang = dbSelect('settings', 'languages');
	
	$template -> langcode = substr(dataobject($currlang -> codes, $template -> lang), 3, 2);
	$template -> langs = array_keys((array)$currlang -> langs);
	
	if (strlen($template -> langcode) !== 2) {
		$template -> langcode = mb_strtoupper($template -> lang);
	}
	
	unset($currlang);
	
	//print_r($template -> structure);
	//exit;
	
	// останавливаем загрузку сайта, если нет каких-либо важных параметров
	
	if (
		!count($template -> structure) ||
		!count($template -> langs) ||
		!$template -> name ||
		!$template -> lang ||
		!$template -> langcode ||
		!$template -> url
	) {
		error('500', $template -> lang);
	}
	
	// здесь мы задаем условия для редиректов, фактически, пишем свой роутер
	
	require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'router.php';
	
	// разбираем шаблон
	
	if ($template -> router -> template) {
		$template -> name = $template -> router -> template;
	}
	if ($template -> name !== NAME_ADMINISTRATOR && isset($template -> structure[NAME_ADMINISTRATOR])) {
		unset($template -> structure[NAME_ADMINISTRATOR]);
	}
	if ($template -> name === NAME_ADMINISTRATOR || defined('isADMIN') || $_COOKIE['AID']) {
		require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'administrator.php';
	}
	if (
		$template -> name === NAME_PERSONAL ||
		$template -> name === NAME_PRIVATE ||
		$_COOKIE['UID'] ||
		$_COOKIE['PID']
	) {
		require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'user.php';
	}
	unset($template -> router -> template);
	
	// обрабатываем полученные пути
	
	$template -> curr -> inner = PATH_TEMPLATES . DIRECTORY_SEPARATOR . $template -> name . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'inner';
	$template -> curr -> page = $template -> curr -> inner;
	if (count($template -> router -> folders)) {
		foreach ($template -> router -> folders as $item) {
			$template -> curr -> page .= DIRECTORY_SEPARATOR . $item;
			$template -> curr -> path .= '/' . $item;
		}
	}
	if ($template -> router -> page) {
		$template -> curr -> page .= DIRECTORY_SEPARATOR . $template -> router -> page . '.php';
		$template -> curr -> path .= '/' . $template -> router -> page;
	} else {
		$template -> curr -> page .= DIRECTORY_SEPARATOR . 'home' . '.php';
	}
	
	//print_r($template);
	//exit;
	
	// здесь мы проверяем, есть ли запрошенная страница или статья
	if (
		($template -> router -> page && !file_exists($template -> curr -> page) && !isset($template -> router -> parameters -> article)) ||
		(
			isset($template -> router -> parameters -> article) &&
			!glob(PATH_ARTICLES . str_replace('/', DIRECTORY_SEPARATOR, $template -> curr -> path) . DIRECTORY_SEPARATOR . $template -> router -> parameters -> article . '.*') &&
			!file_exists(PATH_ARTICLES . DIRECTORY_SEPARATOR . $template -> router -> page . '.csv')
		)
	) {
		error('404', $template -> lang);
	}
	
	// здесь мы определяем язык
	
	if ($template -> router -> lang) {
		
		if ($template -> router -> lang === ROOT_LANG && ROOT_LANG !== 'auto') {
			header("Location: " . $template -> curr -> path);
			exit;
		} else {
			$template -> lang = $template -> router -> lang;
			cookie('LANG', $template -> router -> lang);
			/*
			if (isset($_COOKIE['LANG'])) {
				$_COOKIE['LANG'] = $template -> router -> lang;
			} else {
				setcookie('LANG', $template -> router -> lang);
			}
			*/
		}
		
	}
	
	// здесь мы задаем базовые настройки для шаблонов
	
	$template -> curr -> url = $template -> url . DIRECTORY_SEPARATOR . NAME_TEMPLATES . DIRECTORY_SEPARATOR . $template -> name;
	$template -> curr -> php = PATH_TEMPLATES . DIRECTORY_SEPARATOR . $template -> name;
	
	$template -> base -> url = $template -> url . DIRECTORY_SEPARATOR . NAME_TEMPLATES . DIRECTORY_SEPARATOR . 'base';
	$template -> base -> php = PATH_TEMPLATES . DIRECTORY_SEPARATOR . 'base';
	
	// здесь мы создаем массив базовых элементов шаблона
	
	$files = fileconnect($template -> base -> php . DIRECTORY_SEPARATOR . 'item', 'php');
	$keys = str_replace('.php', '', $files);
	$items = [];
	foreach ($files as $key => $item) {
		$items[$keys[$key]] = $template -> base -> php . DIRECTORY_SEPARATOR . 'item' . DIRECTORY_SEPARATOR . $item;
	}
	$template -> base -> item = json_decode(json_encode($items),false);
	unset($files, $keys, $items, $key, $item);
	
	$template -> curr -> html = PATH_TEMPLATES . DIRECTORY_SEPARATOR . $template -> name . DIRECTORY_SEPARATOR . 'html';
	
	// здесь мы читаем файл настроек шаблона
	
	$settings = dbSelect('templates', $template -> name);
	
	$template -> libraries = (array) $settings -> libraries;
	$template -> param = (array) $settings -> param;
	$template -> css = (array) $settings -> css;
	$template -> js = (array) $settings -> js;
	$template -> less = (array) $settings -> less;
	
	unset($settings);
	
	// здесь у нас условия, по которым подгружаются шаблоны
	// много кода, зато потом внутри шаблона очень удобно
	
	// здесь загружаем информацию о компьютере пользователя, если в параметрах шаблона есть свойство 'mobiledetect'
	
	if (in_array('mobiledetect', $template -> param)) {
		
		require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'mobiledetect' . DIRECTORY_SEPARATOR . 'Mobile_Detect.php';
		$mobiledetect = new Mobile_Detect;
		
		$template -> device -> type = ($mobiledetect->isMobile() ? ($mobiledetect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
		
		if ( $mobiledetect->isWindowsPhoneOS() ) {
			$template -> device -> os = 'windowsphone';
		} elseif ( $mobiledetect->isiOS() ) {
			$template -> device -> os = 'ios';
		} elseif ( $mobiledetect->isAndroidOS() ) {
			$template -> device -> os = 'android';
		}
		
		unset($mobiledetect);
		
	}
	
	// загрузка языковых файлов
	
	$lang = (object) array();
	$currlang = $template -> lang;
	
	if (
		in_array('baseset', $template -> param)
	) {
		$lang = (object) array_merge(
			(array) dbUse('languages', 'select', 'lang', ['name' => $template -> lang, 'template' => 'base'], ['limit' => 1, 'json' => true, 'format' => false]),
			(array) dbUse('languages', 'select', 'common', ['name' => $template -> lang, 'template' => 'base'], ['limit' => 1, 'json' => true, 'format' => false]),
			(array) dbUse('languages', 'select', 'lang', ['name' => $template -> lang, 'template' => $template -> name], ['limit' => 1, 'json' => true, 'format' => false])
		);
	} else {
		$lang = dbUse('languages', 'select', 'lang', ['name' => $template -> lang, 'template' => $template -> name], ['limit' => 1, 'json' => true, 'format' => false]);
	}
	
	$morph = (object) array(
		'enable' => false
	);
	
	if (
		in_array('dictionary', $template -> param) &&
		isset($lang -> morph)
	) {
		$morph = (object) array_merge(
			(array) $lang -> morph,
			array(
				'enable' => true
			)
		);
		unset($lang -> morph);
	}
	
	$dictionary = [[],[]];
	
	if (
		in_array('baseset', $template -> param) &&
		in_array('dictionary', $template -> param)
	) {
		$dictionary = array_merge(
			(array) dbUse('languages', 'select', 'dictionary', ['name' => $template -> lang, 'template' => 'base'], ['limit' => 1, 'json' => true, 'format' => true]),
			(array) dbUse('languages', 'select', 'dictionary', ['name' => $template -> lang, 'template' => $template -> name], ['limit' => 1, 'json' => true, 'format' => true])
		);
	} elseif (in_array('dictionary', $template -> param)) {
		$dictionary = dbUse('languages', 'select', 'dictionary', ['name' => $template -> lang, 'template' => $template -> name], ['limit' => 1, 'json' => true, 'format' => true]);
	}
	
	// здесь формируем seo
	
	$settings = array_merge(
		(array) dataloadjson($template -> curr -> php . DIRECTORY_SEPARATOR . 'seo.ini', true),
		(array) dataloadjson($template -> base -> php . DIRECTORY_SEPARATOR . 'seo.ini', true)
	);
	
	if (isset($settings) && count($settings)) {
		datareplacelang($settings);
		
		if (isset($settings[$template -> router -> page]) && is_array($settings[$template -> router -> page])) {
			foreach ($settings[$template -> router -> page] as $key => $item) {
				if ($item) {
					$template -> seo -> $key = $item;
				} elseif ($settings['default'][$key]) {
					$template -> seo -> $key = $settings['default'][$key];
				}
			}
		} elseif (isset($settings['default']) && is_array($settings['default'])) {
			$template -> seo = (object) $settings['default'];
		}
	}
	unset($settings);
	
	if (in_array('autoseo', $template -> param)) {
		require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'seoprocessor.php';
	}
	
	// здесь загружаем специальную защищенную страницу
	
	if (
		file_exists(PATH_TEMPLATES . DIRECTORY_SEPARATOR . $template -> name . DIRECTORY_SEPARATOR . 'secure.php') &&
		!empty($user) &&
		(
			($template -> name === NAME_PERSONAL && !empty($user -> id) && cookie('UID', true) && $user -> id === $_COOKIE['UID']) ||
			($template -> name === NAME_PRIVATE && !empty($user -> private) && cookie('PID', true) && $user -> private === $_COOKIE['PID'])
		)
	) {
		require_once PATH_TEMPLATES . DIRECTORY_SEPARATOR . $template -> name . DIRECTORY_SEPARATOR . 'secure.php';
	}
	
	// загрузка страниц
	
	//echo '<!-- memory before load template is ' , round(memory_get_peak_usage() / 1024, 2) , ' kb -->';
	
	if (
		in_array('baseset', $template -> param) &&
		!in_array('develop', $template -> param)
	) {
		require_once $template -> base -> item -> opening;
		require_once PATH_TEMPLATES . DIRECTORY_SEPARATOR . $template -> name . DIRECTORY_SEPARATOR . 'template.php';
		require_once $template -> base -> item -> ending;
	} else {
		require_once PATH_TEMPLATES . DIRECTORY_SEPARATOR . $template -> name . DIRECTORY_SEPARATOR . 'template.php';
	}
	
	//echo '<!-- memory after load template is ' , round(memory_get_peak_usage() / 1024, 2) , ' kb -->';
	//unset($template, $lang, $morph, $dictionary, $revslider, $entrycat, $entrymod, $entryserv);
	
	/*
	echo '<!-- ';
	print_r(get_defined_vars());
	//print_r($_SERVER);
	//print_r($GLOBALS);
	//print_r($template);
	echo ' -->';
	*/
	
	/*
	echo '<!-- sess:';
	print_r($_SESSION);
	echo 'cook:';
	print_r($_COOKIE);
	echo '-->';
	*/
	
	unset($template, $lang, $morph, $dictionary);
	
	//echo '<!-- memory after load template is ' , round(memory_get_usage() / 1024, 2) , ' kb -->';
	
?>