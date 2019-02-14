<?php defined('isCMS') or die; ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> curr -> url; ?>/style.css" />
	<title><?= $lang -> title; ?></title>
</head>

<body>

<form id="restore" class="form" action="/" method="post">
	<input type="hidden" name="query" value="restore">
	<input type="hidden" name="hash" value="<?= $restorehash; ?>">
	<input
		type="text"
		name="datarestore[email]"
		data-name="email"
		class="field"
		value=""
		placeholder="<?= $lang -> restore -> email; ?>"
	/>
	<input
		type="text"
		name="datarestore[password]"
		data-name="password"
		class="field"
		value=""
		placeholder="<?= $lang -> restore -> password; ?>"
	/>
	
	<p class="captcha">
		<img class="captcha" src="/<?= NAME_LIBRARIES; ?>/kcaptcha/?<?= session_name()?>=<?= session_id()?>">
	</p>
	<input
		type="text"
		name="datarestore[captcha]"
		class="field"
		placeholder="<?= $lang -> restore -> captcha; ?>"
	/>
	
	<button type="submit" class="submit"><?= $lang -> restore -> button; ?></button>
	<p><?= $lang -> restore -> description; ?></p>
</form>

</body>
</html>