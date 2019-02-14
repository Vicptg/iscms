<?php
defined('isQUERY') or die;

$status = lockProject('verify', $userID, $_POST['project']);

if ($status !== 'lock') {
	setcookie('PID', $_POST['project']);
	$_COOKIE['PID'] = $_POST['project'];
}

header("Location: /".NAME_PRIVATE."/");
exit;

?>