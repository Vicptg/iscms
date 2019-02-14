<?php defined('isCMS') or die;

global $template;
$structure = $template -> structure;
$module -> data = [];

if ($module -> this) {
	if ($template -> router -> page) {
		$module -> settings -> custom = $template -> router -> page;
	} elseif (count($template -> router -> folders)) {
		$module -> settings -> custom = end($template -> router -> folders);
	}
}

require_once 'processor.php';

// 1. сначала облегчаем структуру - чистим от заблокированных элементов, если они есть

if (is_array($module -> settings -> disable)) {
	$structure = moduleMenuDisable($structure, $module -> settings -> disable);
}

// 2. теперь пробегаем по структуре и переименовываем ключи

$module -> data = moduleMenuRemap($structure, '');
unset($structure);

// 3. последним этапом обновляем структуру, если задано кастомизирование

if (is_array($module -> settings -> custom)) {
	$module -> data = moduleMenuCustom($module -> data, $module -> settings -> custom);
} elseif ($module -> settings -> custom) {
	$module -> data = moduleMenuCustom($module -> data, [$module -> settings -> custom . '::']);
}

//print_r($module -> data);
/*
echo '<pre>';
print_r($module -> data);
echo '</pre>';
*/
?>