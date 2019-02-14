<?php defined('isCMS') or die; ?>

<form method="get" action="/index.php">
	
	<input name="query" value="authorisation" type="hidden">
	<input type="hidden" name="hash" value="<?= datacrypt(time()); ?>">
	<input name="data[onlyadmin]" value="1" type="hidden">
	
	<input name="data[login]">
	<input name="data[password]">
	<input type="submit">
	
</form>