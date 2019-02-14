<?php defined('isCMS') or die;

if (!DB_TYPE || DB_TYPE === 'nodb') {
	
	header($_SERVER['SERVER_PROTOCOL'] . ' 502 FORBIDDEN', true, 502);
	header("Location: /index.php?error=502&lang=" . $currlang);
	exit;
	
}

$attempt = array(
	'ip' => $_SERVER['REMOTE_ADDR'],
	'session' => session_id(),
	'ban' => 0,
	'counts' => 0,
	'data' => $_SERVER['REQUEST_TIME'],
);

if (!defined('isORIGIN') || defined('isHACK')) {
	$attempt['ban'] = 2;
	$attempt['counts'] = 100;
}

$result = dbAttempts('verify', array($attempt['ip']));

if (!count($result) || count($result) == 0) {
	dbAttempts('save', $attempt);
	$result = dbAttempts('verify', array($attempt['ip']));
}

if (count($result)) {
	if (defined('isORIGIN') && ($attempt['data'] - $result['data']) > 3600) {
		dbAttempts('delete', array($attempt['ip']));
	} else {
		$attempt['counts'] = $result['counts'] + 1;
		if ($result['ban'] == 2 || $attempt['ban'] == 2) {
			$attempt['ban'] = 2;
			dbAttempts('update', $attempt);
			header($_SERVER['SERVER_PROTOCOL'] . ' 403 FORBIDDEN', true, 403);
			header("Location: /index.php?error=403&lang=" . $currlang);
			exit;
		} elseif ($result['ban'] == 1) {
			if ($result['counts'] > 7) {
				$attempt['ban'] = 2;
			} else {
				$attempt['ban'] = $result['ban'];
			}
		} elseif ($result['ban'] == 0) {
			if ($result['counts'] >= 2) {
				$attempt['ban'] = 1;
			} else {
				$attempt['ban'] = $result['ban'];
			}
		}
		dbAttempts('update', $attempt);
	}
}

?>