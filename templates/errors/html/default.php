<?php defined('isCMS') or die; ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title><?= $error -> name . ' ' . $error -> code; ?></title> 
	<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $error -> link; ?>/css/style.css" />
</head>
<body>

<div>
	
	<p align="center">
		<strong>
		<?= $error -> name . ' ' . $error -> code . ': ' . $error -> description; ?>
		</strong>
	</p>
	
	<?= $error -> message; ?>
	
	<p>
		<?//= echo $error -> support; ?>
	</p>
	
</div>

<script type="text/javascript" src="<?= $error -> link; ?>/js/script.js"></script>

</body>
</html>