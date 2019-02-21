<?php defined('isCMS') or die; ?>
<!DOCTYPE html>
<html lang="<?= $template -> lang; ?>"<?= (in_array('autoseo', $template -> options)) ? ' prefix="og: http://ogp.me/ns#"' : ''; ?>>
<head>
	
	<?php
		if (in_array('inspect', $template -> options)) {
			require_once $template -> base -> item -> inspectstart;
		}
	?>
	
	<?php
		
		if (!isset($administrator) || !$administrator -> in) {
			require_once $template -> base -> item -> meta;
		}
		
		require_once $template -> base -> item -> favicon;
		require_once $template -> base -> item -> libraries;
		require_once $template -> base -> item -> cssjs;
		
		if (file_exists($template -> curr -> php . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'head' . DIRECTORY_SEPARATOR . 'home.php')) {
			require_once $template -> curr -> php . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'head' . DIRECTORY_SEPARATOR . 'home.php';
		}
		if ($template -> router -> page && file_exists($template -> curr -> php . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'head' . DIRECTORY_SEPARATOR . $template -> router -> page . '.php')) {
			require_once $template -> curr -> php . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'head' . DIRECTORY_SEPARATOR . $template -> router -> page . '.php';
		}
		
	?>
	
</head>

<body class="
	<?= ($template -> router -> page) ? $template -> router -> page : 'home'; ?>
	<?= ($template -> administrator) ? 'administrator' : ''; ?>
	<?= ($template -> device -> type) ? ' is_' . $template -> device -> type : ''; ?>
	<?= ($template -> device -> os) ? ' is_' . $template -> device -> os : ''; ?>
">