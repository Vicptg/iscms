<?php defined('isCMS') or die;
	
	if (
		is_string($module -> this) &&
		$module -> this !== 'all'
	) {
		$module -> var['type'] = 'alone';
	} elseif (
		isset($template -> router -> parameters -> page) &&
		$template -> router -> parameters -> page === 'all'
	) {
		$module -> var['type'] = 'all';
	} else {
		$module -> var['type'] = 'list';
	}
	
?>