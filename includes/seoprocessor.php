<?php

/* ПРОЦЕССОР ПО ФОРМИРОВАНИЮ ДАННЫХ, НЕОБХОДИМЫХ ДЛЯ SEO */

$seo = (object) array(
	'page' => '',
	'original' => '',
	'lang' => '',
	'langcode' => '',
	'author' => '',
	'rights' => '',
	'site' => '',
	'url' => '',
	'link' => '',
	'name' => '',
	'data' => '',
	'desc' => '',
	'keys' => '',
	'image' => '',
	'icon' => ''
);

// название страницы (из меню)
$var = $template -> router -> page;
if (isset($lang -> menu -> $var)) {
	$seo -> page = $lang -> menu -> $var;
}
unset($var);

// название сайта (из тайтла)
if (isset($lang -> title)) {
	$seo -> site = $lang -> title;
}

// оригинальное название страницы (из данных seo)
if (!empty($template -> seo -> title)) {
	$seo -> original = $template -> seo -> title;
}

// урл сайта (из системы)
$seo -> url = $_SERVER['SERVER_NAME'];

// адрес страницы (из системы)
$seo -> link = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['PATH_INFO'];

// данные (из запроса)
if (isset($_GET['metaname'])) {
	$seo -> name = htmlentities($_GET['metaname']);
}
if (isset($_POST['meta']['name'])) {
	$seo -> name = htmlentities($_POST['meta']['name']);
}
if (isset($_GET['metadata'])) {
	$seo -> data = htmlentities($_GET['metadata']);
}
if (isset($_POST['meta']['data'])) {
	$seo -> data = htmlentities($_POST['meta']['data']);
}

// оригинальное название страницы (из данных seo)
if (isset($_GET['metadesc'])) {
	$seo -> desc = htmlentities($_GET['metadesc']);
} elseif (isset($_POST['meta']['desc'])) {
	$seo -> desc = htmlentities($_POST['meta']['desc']);
} elseif (!empty($template -> seo -> description)) {
	$seo -> desc = $template -> seo -> description;
}

// ключи из запроса

if (!empty($template -> seo -> keywords)) {
	$seo -> keys = $template -> seo -> keywords;
} elseif ($seo -> desc) {
	$keywords = '';
	$keys = preg_split('/ /', $seo -> desc, -1, PREG_SPLIT_NO_EMPTY);
	sort($keys);
	$wrons = ['.', ',', '-', ':', '_', '<', '>'];
	foreach ($keys as $key => &$item) {
		$item = mb_strtolower($item, 'UTF-8');
		$item = str_replace($wrons, '', $item);
		$item = trim($item);
		if (mb_strlen($item) > 3) {
			$keywords .= ', ';
			$keywords .= trim($item);
		}
	}
	$seo -> keys = mb_substr($keywords, 2);
	unset($keywords, $keys, $wrongs, $key, $item);
}

// изображение

if (!empty($template -> seo -> image)) {
	$seo -> image = $template -> seo -> image;
} elseif (!empty($_GET['metaimage'])) {
	$str = $_GET['metaimage'];
	if (strpos($str, '/')) {
		$str = substr($str, 0, strpos($str, '/'));
	}
	$seo -> image = htmlentities($str);
} elseif (!empty($_POST['meta']['image'])) {
	$str = $_POST['meta']['image'];
	if (strpos($str, '/')) {
		$str = substr($str, 0, strpos($str, '/'));
	}
	$seo -> image = htmlentities($str);
} elseif (isset($_SERVER['PATH_INFO'])) {
	$seo -> image = $_SERVER['PATH_INFO'];
} else {
	$seo -> image = 'image';
}

$seo -> image = str_replace('.', '/', $seo -> image);
if ( mb_substr($seo -> image, 0, 1) === DIRECTORY_SEPARATOR || mb_substr($seo -> image, 0, 1) === '/' ) {
	$seo -> image = mb_substr($seo -> image, 1);
}
if ( mb_substr($seo -> image, -1, 1) === DIRECTORY_SEPARATOR || mb_substr($seo -> image, -1, 1) === '/' ) {
	$seo -> image = mb_substr($seo -> image, 0, -1);
}

if (file_exists(PATH_UPLOAD . DIRECTORY_SEPARATOR . $seo -> image . '.png')) {
	$seo -> image = $template -> url . '/' . NAME_UPLOAD . '/' . $seo -> image . '.png';
} elseif (file_exists(PATH_UPLOAD . DIRECTORY_SEPARATOR . $seo -> image . '.jpg')) {
	$seo -> image = $template -> url . '/' . NAME_UPLOAD . '/' . $seo -> image . '.jpg';
} elseif (file_exists(PATH_UPLOAD . DIRECTORY_SEPARATOR . 'image' . '.png')) {
	$seo -> image = $template -> url . '/' . NAME_UPLOAD . '/' . 'image' . '.png';
} elseif (file_exists(PATH_UPLOAD . DIRECTORY_SEPARATOR . 'image' . '.jpg')) {
	$seo -> image = $template -> url . '/' . NAME_UPLOAD . '/' . 'image' . '.jpg';
} else {
	$seo -> image = '';
}

if (!empty($template -> seo -> author)) {
	$seo -> author = $template -> seo -> author;
} elseif (!empty($lang -> title)) {
	$seo -> author = $lang -> title;
} else {
	$seo -> author = $_SERVER['SERVER_NAME'];
}

if (!empty($template -> seo -> copyright)) {
	$seo -> rights = $template -> seo -> rights;
} else {
	$seo -> rights = '(C) Copyright ' . date('Y');
}

?>