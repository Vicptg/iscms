<?php defined('isCMS') or die; ?>

<!-- OPENING SCRIPTS -->

<?php if (in_array('baseset', $template -> options) && file_exists($template -> base -> php . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'script.js')) : ?>
	<script type="text/javascript" src="<?= $template -> base -> url; ?>/js/script.js"></script>
<?php endif; ?>

<?php if (isset($template -> js) && is_array($template -> js) && count($template -> js)) : ?>
	<?php foreach ($template -> js as $item) : ?>
		<?php if (file_exists($template -> curr -> php . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $item . '.js')) : ?>
			<script type="text/javascript" src="<?= $template -> curr -> url; ?>/js/<?= $item; ?>.js"></script>
		<?php endif; ?>
	<?php endforeach; ?>
<?php elseif (file_exists($template -> curr -> php . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'script.js')) : ?>
	<script type="text/javascript" src="<?= $template -> curr -> url; ?>/js/script.js"></script>
<?php endif; ?>

<?php if (file_exists($template -> curr -> php . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $template -> router -> page . '.js')) : ?>
	<script type="text/javascript" src="<?= $template -> curr -> url; ?>/js/<?= $template -> router -> page; ?>.js"></script>
<?php endif; ?>

<!-- OPENING LESS -->
	
<?php if (in_array('less', $template -> libraries) && file_exists(PATH_LIBRARIES . DIRECTORY_SEPARATOR . 'less')) require_once $template -> base -> item -> less; ?>

<!-- OPENING STYLES -->

<?php if (in_array('baseset', $template -> options) && file_exists($template -> base -> php . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'style.css')) : ?>
	<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> base -> url; ?>/css/style.css" />
<?php endif; ?>

<?php if ($template -> device -> type && $template -> device -> type !== 'desktop' && file_exists($template -> base -> php . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'mobile.css')) : ?>
	<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> base -> url; ?>/css/mobile.css" />
<?php endif; ?>

<?php if (isset($template -> css) && is_array($template -> css) && count($template -> css)) : ?>
	<?php foreach ($template -> css as $item) : ?>
		<?php if (file_exists($template -> curr -> php . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $item . '.css')) : ?>
			<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> curr -> url; ?>/css/<?= $item; ?>.css" />
		<?php endif; ?>
	<?php endforeach; ?>
<?php elseif (file_exists($template -> curr -> php . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'template.css')) : ?>
	<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> curr -> url; ?>/css/template.css" />
<?php endif; ?>

<?php if (file_exists($template -> curr -> php . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $template -> router -> page . '.css')) : ?>
	<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> curr -> url; ?>/css/<?= $template -> router -> page; ?>.css" />
<?php endif; ?>
