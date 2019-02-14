<?php
defined('isQUERY') or die;

$status = lockProject('verify', $userID, $_POST['data']['pid']);

if ($status !== 'lock') {
	lockProject('lock', $userID, $_POST['data']['pid']);
} else {
	lockProject('unlock', $userID, $_POST['data']['pid']);
}

//echo 'old status: ' . $status;

exit;

?>