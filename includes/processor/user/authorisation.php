<?php defined('isQUERY') or die;
	
	//print_r($query);
	
	if ($query -> data -> exit) {
		
		cookie(['AID', 'UID', 'PID']);
		
		header("Location: /");
		exit;
		
	} elseif (
		$query -> data -> login &&
		$query -> data -> password
	) {
		
		$user = dbUse('users', 'select', ['id', 'password', 'status'], ['login' => $query -> data -> login], ['first' => true]);
		
		if (empty($user)) {
			$user = dbUse('users', 'select', ['id', 'password', 'status'], ['login' => $query -> data -> login], ['first' => true, 'crypt' => true]);
		}
		
		if (
			is_array($user) &&
			!empty($user) &&
			password_verify($query -> data -> password, $user['password'])
		) {
			
			if (
				($user['status'] < 100 && !empty($query -> data -> onlyadmin)) ||
				($user['status'] > 99 && !empty($query -> data -> onlyuser))
			) {
				
				$query -> status = 'fail';
				$query -> errors['authorisation'] = true;
				
			} elseif ($user['status'] > 99) {
				
				cookie(['UID', 'PID']);
				cookie('AID', $user['id']);
				
				unset($user);
				
				header("Location: /" . NAME_ADMINISTRATOR . "/");
				exit;
				
			} elseif ($user['status'] > 1) {
				
				cookie('AID');
				cookie('UID', $user['id']);
				cookie('PID', $user['id']);
				
			} else {
				
				cookie('AID');
				cookie('UID', $user['id']);
				
			}
			
			unset($user);
			
		} else {
			$query -> status = 'fail';
			$query -> errors['authorisation'] = true;
		}
		
	}
	
	if (
		$query -> status === 'fail' &&
		!empty($query -> data -> onlyadmin)
	) {
		header("Location: /" . NAME_ADMINISTRATOR . "/");
		exit;
	}
	
	//print_r($query);
	//exit;
	
?>