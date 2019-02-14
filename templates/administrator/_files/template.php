<?php
defined('isCMS') or die;
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">

	<?php require_once 'select.php'; ?>
	<?php require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'libs.php'; ?>

	<?php
		if ($сonfigEditor == 'reformator') {
			echo '<link rel="stylesheet" rev="stylesheet" type="text/css" href="';
			if ($сonfigLibs == 'cdn') {
				echo 'http://web.artlebedev.ru/tools/reformator/reformator.css';
			} else {
				echo '/libs/reformator/reformator.css';
			}
			echo '" />';
		}
	?>

	<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?php echo $url . DIRECTORY_SEPARATOR . NAME_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'style.css'; ?>" />
	<script src="<?php echo $url . DIRECTORY_SEPARATOR . NAME_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'script.js'; ?>"></script>

	<?php $currentmodulepath = DIRECTORY_SEPARATOR . NAME_ADMINISTRATOR . DIRECTORY_SEPARATOR . NAME_MODULES . DIRECTORY_SEPARATOR . $currentmodule . DIRECTORY_SEPARATOR; ?>
	<?php if(file_exists(PATH_SITE . $currentmodulepath . $currentmoduletheme . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'style.css')) : ?>
	<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?php echo $url . $currentmodulepath . $currentmoduletheme . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'style.css'; ?>" />
	<?php endif; ?>
	<?php if(file_exists(PATH_SITE . $currentmodulepath . $currentmoduletheme . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'script.js')) : ?>
	<script src="<?php echo $url . $currentmodulepath . $currentmoduletheme . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'script.js'; ?>"></script>
	<?php endif; ?>

	<title>Административная часть</title>
</head>

<body>

<header class="panel">
<?php require_once 'panel.php'; // подключаем файл с админской панелькой ?>
</header>

<section class="inside">
<?php
if ($_GET['do'] == $currentmodule && file_exists(PATH_SITE . $currentmodulepath . 'index.php')) {
	require PATH_SITE . $currentmodulepath . 'index.php';
} elseif ($_GET['do'] == 'frontend') {
	require 'frontend/index.php';
} else {
	require 'backend/index.php';	
}
?>
</section>

</body>
</html>