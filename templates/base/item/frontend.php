<?php defined('isCMS') or die;

if (
	isset($administrator) &&
	$administrator -> settings -> frontend &&
	file_exists($administrator -> path -> base . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . $administrator -> path -> curr)
) {
	require_once $administrator -> path -> base . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . $administrator -> path -> curr;
}

?>