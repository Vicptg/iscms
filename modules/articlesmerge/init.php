<?php

$module -> datas = [];

foreach ($module -> param as $key => $item) {
	
	$param = $item -> param;
	
	switch ($item -> type) {
		case 'file':
			$name = 'articles';
			break;
		case 'csv':
			$name = 'buildtable';
			break;
		case 'db':
			$name = '';
			break;
	}
	
	if ($key === 0) {
		$module -> name = $name;
	}
	
	if ($name) {
		$module -> path = PATH_MODULES . DIRECTORY_SEPARATOR . $name;
		
		$module -> settings = moduleSettings($module -> path, $param);
		
		require_once $module -> path . DIRECTORY_SEPARATOR . 'init.php';
		
		$module -> datas[] = $module -> data;
		unset($module -> data, $param, $name);
	} else {
		exit;
	}
	
}

$module -> data = $module -> datas[0];
unset($module -> datas[0]);

foreach ($module -> datas as $i => $datas) {
	foreach ($module -> data as $key => &$item) {
		if ($datas[$key][$module -> param[$i] -> merge] === $item[$module -> param[0] -> merge]) {
			$item = array_merge($item, $datas[$key]);
			unset($item[$module -> param[$i] -> merge]);
		}
	}
}

unset($module -> datas);

$module -> path = PATH_MODULES . DIRECTORY_SEPARATOR . $module -> name;

?>