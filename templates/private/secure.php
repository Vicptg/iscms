<?php

$types = array('encrypt', 'hide', 'show', 'set');

$user = dbUser('load', array('value' => $userID));
$userData = json_decode($user['userdata'], true);

if ($userData['language']) {
	$template -> lang = $userData['language'];
}

if (!$userData['name']) {
	$userData['name'] = $user['email'];
}

if ($user['avatar']) {
	$userData['avatar'] = $user['avatar'];
} else {
	$userData['avatar'] = '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';
}

if (!$userData['email_type']) {
	$userData['email_type'] = 'encrypt';
}
if (!$userData['phone_type']) {
	$userData['phone_type'] = 'encrypt';
}

if ($user['email']) {
	if ($userData['email_type'] === 'show') {
		$userData['email'] = $user['email'];
	} elseif ($userData['email_type'] === 'encrypt') {
		$email = preg_split('/@/', $user['email']);
		$email[2] = substr($email[1], strrpos($email[1], '.'));
		$email[1] = substr($email[1], 0, 2) . '***';
		$email[0] = substr($email[0], 0, 2) . '***';
		$userData['email'] = $email[0] . '@' .  $email[1] . $email[2];
		unset($email);
	} elseif ($userData['email_type'] === 'hide') {
		$userData['email'] = '';
	}
}

if ($user['phone']) {
	if ($userData['phone_type'] === 'show') {
		$userData['phone'] = '+' . $user['phone'];
	} elseif ($userData['phone_type'] === 'encrypt') {
		$userData['phone'] = '+' . substr($user['phone'], 0, 4) . '*****' . substr($user['phone'], -2);
	} elseif ($userData['phone_type'] === 'hide') {
		$userData['phone'] = '';
	}
}

$userData['birthday'] = strtotime($user['date_birthday']);
$userData['lastenter'] = strtotime($user['date_lastenter']);

unset($user);

?>
