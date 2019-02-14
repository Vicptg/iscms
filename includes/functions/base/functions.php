<?php

/* ФУНКЦИЯ РЕГИСТРАЦИИ/УДАЛЕНИЯ КУКИ */

function cookie($name, $set = false){
	
	if ($set === true) {
		if (empty($_COOKIE[$name])) {
			return false;
		} else {
			return true;
		}
	} elseif ($set) {
		setcookie($name, $set, 0, '/');
		$_COOKIE[$name] = $set;
	} elseif (is_array($name)) {
		foreach ($name as $item) {
			setcookie($item, '', time() - 3600, '/');
			unset($_COOKIE[$item]);
		}
		unset($item);
	} else {
		setcookie($name, '', time() - 3600, '/');
		unset($_COOKIE[$name]);
	}
	
}

/* ФУНКЦИЯ ВЫЗОВА ОШИБКИ */

function error($code, $l = false){
	
	global $template;
	
	if (!$l && isset($template -> lang)) {
		$l = $template -> lang;
	}
	
	header($_SERVER['SERVER_PROTOCOL'] . ' ' . $code . ' FORBIDDEN', true, $code);
	header("Location: /index.php?error=" . $code . "&lang=" . $l);
	exit;
}

/* ФУНКЦИЯ ВЫЗОВА МОДУЛЯ */

function module($arr, $this = false){
	
	global $lang;
	global $template;
	
	if (!is_array($arr)) { $arr = [$arr]; }
	if (empty($arr[1])) { $arr[1] = 'default'; }
	
	if (empty($arr[2])) {
		if (
			$arr[1] !== 'default' &&
			file_exists(PATH_MODULES . DIRECTORY_SEPARATOR . $arr[0] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $arr[1] . '.php')
		) {
			$arr[2] = $arr[1];
		} else {
			$arr[2] = 'default';
		}
	}
	
	if (empty($arr[3])) {
		$arr[3] = false;
	}
	
	$module = (object) array(
		'name' => $arr[0],
		'param' => $arr[1],
		'template' => $arr[2],
		'path' => PATH_MODULES . DIRECTORY_SEPARATOR . $arr[0],
		'this' => $this,
		'settings' => (object) array(),
		'var' => array()
	);
	
	if (
		is_string($module -> this) ||
		is_numeric($module -> this)
	) {
		$module -> this = dataclear($module -> this, 'alphanumeric');
	} elseif (
		!is_object($module -> this) &&
		!is_array($module -> this) &&
		!is_bool($module -> this)
	) {
		$module -> this = false;
	}
	
	$module -> settings = moduleSettings($module -> name, $module -> param, $arr[3]);
	unset ($arr);
	
	if (in_array('inspect', $template -> param)) {
		global $loadingLog;
		$loadingLog .= 'module ' . $module -> name . ' as ' . $module -> param . ' with ' . $module -> template . ' template ';
	}
	
	if (!$module -> settings) {
		if (isset($loadingLog)) { $loadingLog .= 'was not opening\n'; }
		unset($module);
		return false;
	}
	
	require $module -> path . DIRECTORY_SEPARATOR . 'init.php';
	require $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> template . '.php';
	
	if ($template -> administrator) {
		global $administrator;
		if (file_exists($administrator -> path -> base . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'modules.php')) {
			require $administrator -> path -> base . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'modules.php';
		}
	}
	
	if (isset($loadingLog)) { $loadingLog .= 'was opening complete\n'; }
	
	unset($module);
	
}

/* ФУНКЦИЯ ЧТЕНИЯ НАСТРОЕК МОДУЛЯ */

function moduleSettings($name, $param, $custom){
	
	$settings = (object) [
		'base' => dbUse('modules', 'select', 'data', ['name' => $name, 'param' => null], ['limit' => 1, 'json' => true, 'format' => false]),
		'this' => dbUse('modules', 'select', 'data', ['name' => $name, 'param' => $param], ['limit' => 1, 'json' => true, 'format' => false]),
		'custom' => ($custom) ? json_decode($custom) : $custom,
		'default' => (object) [],
		'result' => (object) []
	];
	
	if ($settings -> base) {
		
		if (isset($settings -> base -> default)) {
			$settings -> default = $settings -> base -> default;
			unset($settings -> base -> default);
		}
		
		if (isset($settings -> base -> $param)) {
			$settings -> base = $settings -> base -> $param;
		}
		
		if (!isset($settings -> base -> default) && !isset($settings -> base -> $param)) {
			unset($settings -> base);
		}
		
	}
	
	if ($settings -> default && count($settings -> default)) {
		$settings -> result = datamerge($settings -> result, $settings -> default);
		unset($settings -> default);
	}
	if ($settings -> base && count($settings -> base)) {
		$settings -> result = datamerge($settings -> result, $settings -> base, 'replace');
		unset($settings -> base);
	}
	if ($settings -> this && count($settings -> this)) {
		$settings -> result = datamerge($settings -> result, $settings -> this, 'replace');
		unset($settings -> this);
	}
	if ($settings -> custom && count($settings -> custom)) {
		$settings -> result = datamerge($settings -> result, $settings -> custom, 'replace');
		unset($settings -> custom);
	}
	
	return $settings -> result;
	
}

/* ФУНКЦИИ ОТПРАВКИ СООБЩЕНИЙ */

function message($arr, $subject, $settings, $message, $errors) {
	
	/*
	*  Функция подготовки и вызова отправки сообщения
	*  на входе нужно указать:
	*    arr - массив данных (напр. "type" : "email", "param" : "", "id" : "mail@mail.com", "key" : "")
	*    subject - тема сообщения
	*    settings - массив данных (подмассив "label" - название данных, "value" - значение данных)
	*    message - текстовое сообщение
	*    errors - путь и название файла с логом ошибок, если письма не были доставлены
	*  
	*  функция примет данные и вызовет messageSend с теми же параметрами
	*/
	
	if ( isset($arr) && is_array($arr) ) {
		
		foreach ($arr as $arritem) {
			if (is_array($arritem -> id)) {
				$ids = $arritem -> id;
				foreach ($ids as $id) {
					$arritem -> id = $id;
					if (messageSend($arritem, $subject, $settings, $message, $errors) === false) {
						return false;
					}
				}
				unset($ids, $id);
			} else {
				if (messageSend($arritem, $subject, $settings, $message, $errors) === false) {
					return false;
				}
			}
		}
		unset($arritem);
		
	} elseif (isset($arr)) {
		
		if (is_array($arr -> id)) {
			$ids = $arr -> id;
			foreach ($ids as $id) {
				$arr -> id = $id;
				if (messageSend($arr, $subject, $settings, $message, $errors) === false) {
					return false;
				}
			}
			unset($ids, $id);
		} else {
			if (messageSend($arr, $subject, $settings, $message, $errors) === false) {
				return false;
			}
		}
		
	}
	
	unset($arr, $message);
	return true;
	
}

function messageSend($arr, $subject, $settings, $message, $errors) {
	
	/*
	*  Функция непосредственно отправки сообщения
	*  на входе принимает данные из message
	*  
	*  функция принимает данные и отправляет сообщения
	*  на данный момент реализована отправка email, vk, whatsapp, sms
	*  обработки и проверки данных пока нет
	*/
	
	$message = htmlspecialchars($message);
	
	// отправка сообщений для вконтакте
	if ($arr -> type === 'vk' || $arr -> type === 'vkontakte') {
		
		$message = $message . "\r\n\r\n";
		
		foreach ($settings -> label as $key => $item) {
			
			if (isset($settings -> value -> $key)) {
				$message .= $item . ': ' . $settings -> value -> $key . "\r\n";
			}
		}
		
		$result = file_get_contents('https://api.vk.com/method/messages.send', false, stream_context_create(array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query(
					array(
						$arr -> param => $arr -> id,
						'message' => $message,
						'access_token' => $arr -> key,
						'v' => '5.37'
					)
				)
			)
		)));
	}
	
	// отправка сообщений по электронной почте
	elseif ($arr -> type === 'email' || $arr -> type === 'phpmail') {
		
		$site = $_SERVER['SERVER_NAME'];
		
		$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
		$headers .= "From: no-reply@" . $site . "\r\n"; 
		$headers .= "Reply-To: no-reply@" . $site . "\r\n"; 
		$headers .= "X-Mailer: PHP/" . phpversion();
		
		$message = '<p>' . $message . '</p><p></p>';
			
		foreach ($settings -> label as $key => $item) {
			if (isset($settings -> value -> $key)) {
				$message .= '<p>' . $item . ': ' . $settings -> value -> $key . '</p>';
			}
		}
		
		if ( (isset($_GET['check']) || isset($_POST['check'])) && !$_GET['check'] && !$_POST['check'] ) {
			$result = mail($arr -> id, $subject, $message, $headers);
		}
	}
	
	// отправка спец.сообщения или уведомления по электронной почте
	elseif ($arr -> type === 'sendmail' || $arr -> type === 'notification') {
		
		$site = $_SERVER['SERVER_NAME'];
		
		$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
		$headers .= "From: no-reply@" . $site . "\r\n"; 
		$headers .= "Reply-To: no-reply@" . $site . "\r\n"; 
		$headers .= "X-Mailer: PHP/" . phpversion();
		
		$message = $message;
		
		if ( (isset($_GET['check']) || isset($_POST['check'])) && !$_GET['check'] && !$_POST['check'] ) {
			$result = mail($arr -> id, $subject, $message, $headers);
		}
	}
	
	// отправка сообщений по СМС
	elseif ($arr -> type === 'sms') {
		
		$message = '[' . $subject . '] ';
		
		foreach ($settings -> label as $key => $item) {
			if (isset($settings -> value -> $key)) {
				$message .= $item . ': ' . $settings -> value -> $key . ', ';
			}
		}
		
		if (in_array('mbstring', get_loaded_extensions())) {
			$message = mb_substr($message, 0, -2);
		} else {
			$message = substr($message, 0, -2); // EDITED FOR NOT PHP MODULE
		}
		
		$message = htmlspecialchars($message);
		
		$newarr = (object) array(
			'key' => json_decode('{"id":"' . $arr -> id . '","message":"' . $message . '",' . ((in_array('mbstring', get_loaded_extensions())) ? mb_substr(json_encode($arr -> key), 1) : substr(json_encode($arr -> key), 1)), true), // EDITED FOR NOT PHP MODULE
			'param' => $arr -> param
		);
		
		foreach ($newarr -> key as $k => $i) {
			$newarr -> param = str_replace( '{' . $k . '}', $i, $newarr -> param);
		}
		
		$result = file_get_contents($arr -> param);
		
	}
	
	// отправка сообщений по WhatsApp
	elseif ($arr -> type === 'whatsapp' || $arr -> type === 'whatsappget') {
		
		$message = $message . "\r\n";
			
		foreach ($settings -> label as $key => $item) {
			if (isset($settings -> value -> $key)) {
				$message .= $item . ': ' . $settings -> value -> $key . "\r\n";
			}
		}
		
		$message = htmlspecialchars($message);
		
		if ($arr -> type === 'whatsappget') {
			
			$content = $arr -> param .
				'?' . $arr -> key -> token . '=' . $arr -> key -> key .
				'&' . $arr -> key -> id . '=' . $arr -> id .
				'&' . $arr -> key -> message . '=' . urlencode($message);
			$result = file_get_contents($content);
			
		} else {
			
			$result = file_get_contents($arr -> param . '?' . $arr -> key -> token . '=' . $arr -> key -> key, false, stream_context_create([
				'http' => [
					'method'  => 'POST',
					'header'  => 'Content-type: application/json',
					'content' => json_encode([
						$arr -> key -> id => $arr -> id,
						$arr -> key -> message => $message,
					])
				]
			]));
			
		}
	}
	
	// обработка результатов и формирование отчета в случае ошибки
	
	if (!empty($result)) {
		$result = json_decode($result, true);
	} else {
		$result = array(
			'error' => array(
				'error_status' => 'none result',
				'error_type' => $arr -> type
			)
		);
	}
	
	if (isset($result['error'])) {
		$arr = dataloadcsv($errors);
		
		if (!$arr -> data) {
			$arr -> data[] = [
				'date',
				'error status',
				'ip',
				'browser',
				'message'
			];
		}
		
		$arr -> data[] = [
			date('d.m.Y H:i:s (P') . ' GMT)',
			json_encode($result['error']),
			$_SERVER['REMOTE_ADDR'],
			$_SERVER['HTTP_USER_AGENT'],
			$message . ':' . $subject . ':' . json_encode($settings)
		];
		
		datasavecsv($arr -> data, $errors);
		
		return false;
	}
	
	return true;
	
}

?>