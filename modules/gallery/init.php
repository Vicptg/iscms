<?php

$path = '';

if (!isset($module -> settings)) {
	$module -> settings = (object) array();
}

if (isset($module -> settings -> list)) {
	$module -> data = $module -> settings -> list;
} else {
	
	if (!isset($module -> settings -> ext)) {
		$module -> settings -> ext = '..';
	}
	
	if ($module -> param === 'default') {
		$path = PATH_UPLOAD . DIRECTORY_SEPARATOR . $module -> name;
	} else {
		$path = PATH_UPLOAD . DIRECTORY_SEPARATOR . $module -> name . DIRECTORY_SEPARATOR . $module -> param;
	}
	
	$module -> data = fileconnect($path, $module -> settings -> ext);
}

if (!isset($module -> settings -> captions)) {
	
	$module -> settings -> captions = [];
	
	$captionslist = dataloadjson($path . DIRECTORY_SEPARATOR . 'captions.ini', true);
	
	foreach ($module -> data as $key => $item) {
		
		if (isset($captionslist[$item])) {
			$module -> settings -> captions[$key] = $captionslist[$item];
		} else {
			$module -> settings -> captions[$key] = dataloadjson($path . DIRECTORY_SEPARATOR . substr($item, 0, strripos($item, '.')) . '.ini', true);
		}
		
	}
	
}

datareplacelang($module -> settings -> captions);
foreach ($module -> settings -> captions as &$item) {
	if ($item) {
		$item = htmlentities($item);
		//echo $item . '<br>';
	}
}

unset($path);

?>