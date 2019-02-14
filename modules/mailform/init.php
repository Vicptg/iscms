<?php defined('isCMS') or die; ?>
<?php

$module -> status = '';
$module -> data = [];

if (isset($_GET['formstatus'])) {
	
	$module -> status = $_GET['formstatus'];
	if (!empty($module -> settings -> id)) {
		$module -> data = $_GET[$module -> settings -> id];
	} else {
		foreach ($module -> settings -> form as $item) {
			$module -> data[$item -> name] = $_GET[$item -> name];
		}
	}
	
} elseif (isset($_POST['formstatus'])) {
	
	$module -> status = $_POST['formstatus'];
	if (!empty($module -> settings -> id)) {
		$module -> data = $_POST[$module -> settings -> id];
	} else {
		foreach ($module -> settings -> form as $item) {
			$module -> data[$item -> name] = $_POST[$item -> name];
		}
	}
	
}

?>