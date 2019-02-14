<?php defined('isQUERY') or die;
	
	define('isORIGIN', 1);
	
	require_once PATH_INCLUDES . DIRECTORY_SEPARATOR . 'verify' . DIRECTORY_SEPARATOR . 'ban.php';
	
	$result = dbUser('activation', $_GET);
	
	if ($result && $result == 1) {
		
		$userId = dbUser('search', array('field' => 'id', 'index' => 'email', 'value' => $_GET['email']))[0];
		
		if ($userId) {
			createProjectDB('create', $userId);
			dbUser('delregistration', $_GET);
		}
		header("Location: /index.php?error=201&lang=" . $currlang);
	} else {
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 FORBIDDEN', true, 400);
		header("Location: /index.php?error=400&lang=" . $currlang);
	}
	
	exit;
	
?>