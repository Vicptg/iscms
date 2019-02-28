<?php

global $query;

/*
условие ниже не имеет смысла, т.к. оно задано при инициализации формы
в то время как оно должно быть задано при обработке данных с этой формы
однако при обработке данных не реализована возможность проверять, какой протокол стоял в настройках модуля
зато можно эту проверку сделать так: вынести в отдельную настройку свойств админки/пользователя и проверять при авторизации/регистрации

if (
	(!empty($module -> settings -> get) && $query -> method !== 'get') ||
	(empty($module -> settings -> get) && $query -> method !== 'post')
) {
	error('400', $template -> lang);
}
*/

$module -> data = $query -> data;

if ($query -> status !== 'complete') {
	$query -> errors['fail'] = true;
	// если сделать $query -> errors[] = 'fail';, то после следующего условия 'fail' запишется почему-то два раза
}

if (!empty($query -> errors)) {
	$module -> data = datamerge($module -> data, ['errors' => $query -> errors]);
}

$module -> var['name'] = $query -> name;

unset($query);

$module -> var['verification'] = '';

?>