<?php

if (
	(
		isset($module -> this) ||
		(
			isset($template -> router -> parameters -> page) &&
			$template -> router -> parameters -> page === 'all'
		)
	) &&
	isset($module -> settings -> all) &&
	is_object($module -> settings -> all)
) {
	foreach ($module -> settings -> all as $key => $item) {
		if (!isset($module -> settings -> page -> $key)) {
			$module -> settings -> page = (object)$key;
		}
		$module -> settings -> page -> $key = $item;
	}
	unset($key, $item);
}
unset($module -> settings -> all);

if (strpos($module -> settings -> page -> topdate, ':')) {
	$module -> var['topdate'] = datasplit($module -> settings -> page -> topdate, ':');
} else {
	$module -> var['topdate'] = [
		$module -> settings -> page -> topdate,
		1
	];
}

switch ($module -> var['topdate'][0]) {
	case 'month':
		$module -> var['topdate'][0] = TIME_MONTH;
		break;
	case 'week':
		$module -> var['topdate'][0] = TIME_WEEK;
		break;
	case 'day':
		$module -> var['topdate'][0] = TIME_DAY;
		break;
	case 'hour':
		$module -> var['topdate'][0] = TIME_HOUR;
		break;
}

$module -> settings -> page -> topdate = (int) $module -> var['topdate'][0] * (int) $module -> var['topdate'][1];


if (
	$module -> this === true &&
	$template -> router -> page
) {
	$module -> this = $template -> router -> page;
} elseif (
	$module -> this === false &&
	(
		$template -> router -> parameters -> article ||
		$template -> router -> parameters -> article === 0 ||
		$template -> router -> parameters -> article === '0'
	)
) {
	$module -> this = $template -> router -> parameters -> article;

} elseif (
	is_object($module -> this) ||
	is_array($module -> this)
) {
	$template -> var['filter'] = (object) $module -> this;
	$module -> this = 'all';
}



require 'processor_functions.php';

if (isset($module -> settings -> type) && $module -> settings -> type === 'table') {
	require 'processor_table.php';
} else {
	require 'processor_articles.php';
}

$module -> var['filter'] = $template -> var['filter'];
unset($template -> var['filter']);

?>