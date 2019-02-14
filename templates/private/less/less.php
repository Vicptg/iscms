<?php defined('isCMS') or die;
$lesspath = $template -> curr -> php . DIRECTORY_SEPARATOR . 'less' . DIRECTORY_SEPARATOR;
if ( file_exists($lesspath . 'style.less') && ( !file_exists($lesspath . 'style.old') || filesize($lesspath . 'style.less') != filesize($lesspath . 'style.old') ) ) {
	$less = new lessc;
	$less->checkedCompile($lesspath . 'style.less', $lesspath . 'style.css');
	copy($lesspath . 'style.less', $lesspath . 'style.old');
}
?>

<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> curr -> url; ?>/less/style.css" />