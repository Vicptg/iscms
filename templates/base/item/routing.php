<?php defined('isCMS') or die;

$template -> var['wrapper'] = $template -> curr -> html . DIRECTORY_SEPARATOR . 'wrapper';

if (
	empty($template -> router -> folder) &&
	(
		empty($template -> router -> page) ||
		$template -> router -> page === 'home'
	)
) {
	$template -> var['wrapper'] .= DIRECTORY_SEPARATOR . 'home_';
} else {
	$template -> var['wrapper'] .= DIRECTORY_SEPARATOR . 'inner_';
}

if (file_exists($template -> var['wrapper'] . 'before.php')) {
	include_once $template -> var['wrapper'] . 'before.php';
}

if (
	file_exists($template -> curr -> page) &&
	empty($template -> router -> parameters -> article)
) {
	include_once $template -> curr -> page;
} elseif ($template -> router -> page && isset($template -> router -> parameters -> article)) {
	module(['articles', $template -> router -> page]);
} else {
	include_once $template -> base -> item -> nopage;
}

if (file_exists($template -> var['wrapper'] . 'after.php')) {
	include_once $template -> var['wrapper'] . 'after.php';
}

unset($template -> var['wrapper']);

?>