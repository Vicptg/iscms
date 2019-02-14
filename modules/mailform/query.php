<?php

// первым делом собираем данные GET или POST - смотря, какая форма к нам пришла

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	
	$module = (object) array(
		'form' => $_GET[$_GET['name']],
		'base' => (object) array(
			'name' => $_GET['name'],
			'path' => PATH_MODULES . DIRECTORY_SEPARATOR . $_GET['query'] . DIRECTORY_SEPARATOR . 'data',
			'lang' => $currlang,
			'currdate' => date('d.m.Y H:i (P') . ' GMT)',
			'ip' => $_SERVER['REMOTE_ADDR'],
			'browser' => $_SERVER['HTTP_USER_AGENT'],
		),
		'data' => (object) array(),
		'settings' => (object) array(
			'label' => (object) array(),
			'value' => (object) array(),
		),
		'status' => ''
	);
	
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	$module = (object) array(
		'form' => $_POST[$_POST['name']],
		'base' => (object) array(
			'name' => $_POST['name'],
			'path' => PATH_MODULES . DIRECTORY_SEPARATOR . $_POST['query'] . DIRECTORY_SEPARATOR . 'data',
			'lang' => $currlang,
			'currdate' => date('d.m.Y H:i (P') . ' GMT)',
			'ip' => $_SERVER['REMOTE_ADDR'],
			'browser' => $_SERVER['HTTP_USER_AGENT'],
		),
		'data' => (object) array(),
		'settings' => (object) array(
			'label' => (object) array(),
			'value' => (object) array(),
		),
		'status' => ''
	);
	
}

// вторым номером читаем настройки и объединяем их с предыдущими данными

$module -> data = dataloadjson($module -> base -> path . DIRECTORY_SEPARATOR . $module -> base -> name . '.ini');

if (!$module -> data) {
	exit;
}

// теперь начинаем эти данные песочить
// проверка на валидность

foreach($module -> data -> form as $item) {
	
	$module -> form[$item -> name] = trim($module -> form[$item -> name]);
	
	if (
		($item -> type === 'email' || $item -> name === 'email' || $item -> name === 'mail') &&
		!datavalidation($module -> form[$item -> name], 'email')
	) {
		$module -> status = 'fail_' . $item -> name;
	} elseif (
		($item -> type === 'tel' || $item -> name === 'tel' || $item -> name === 'phone') &&
		!datavalidation($module -> form[$item -> name], 'phone')
	) {
		$module -> status = 'fail_' . $item -> name;
	} elseif (
		($item -> type === 'text' || $item -> type === 'textarea') &&
		!datavalidation($module -> form[$item -> name], 'notags')
	) {
		$module -> status = 'fail_' . $item -> name;
	} else {
		$module -> status = 'ready';
		$module -> settings -> label = (object) array_merge( (array)$module -> settings -> label, array( $item -> name => $item -> text ) );
		$module -> settings -> value = (object) array_merge( (array)$module -> settings -> value, array( $item -> name => htmlentities($module -> form[$item -> name]) ) );
	}
	
}

/*
print_r($module -> data);
echo '<br><br>';
print_r($module -> settings);
echo '<br><br>';
print_r($_GET);
*/

// если все ок, то запускаем функцию отправки оповещения админу (не пользователю!!!)

if ($module -> status === 'ready' && message($module -> data -> send, $module -> data -> message -> subject, $module -> settings, $module -> data -> message -> text, $module -> base -> path . DIRECTORY_SEPARATOR . $module -> base -> name . '_error') ) {
	$module -> status = 'complete';
}

// если в настройках включен лог, то записываем логи о пришедших сообщениях

if (!empty($module -> data -> log)) {
	$arr = dataloadcsv($module -> base -> path . DIRECTORY_SEPARATOR . $module -> base -> name . '_log');
	
	if (!$arr -> data) {
		$arr -> data[] = [
			'date',
			'status',
			'ip',
			'browser',
			'message'
		];
	}
	
	$arr -> data[] = [
		date('d.m.Y H:i:s (P') . ' GMT)',
		$module -> status,
		$_SERVER['REMOTE_ADDR'],
		$_SERVER['HTTP_USER_AGENT'],
		$module -> data -> message -> text . ':' . $module -> data -> message -> subject . ':' . json_encode($module -> settings)
	];
	
	datasavecsv($arr -> data, $module -> base -> path . DIRECTORY_SEPARATOR . $module -> base -> name . '_log');
}

// если отправка не удалась, то мы подготавливаем редирект, а именно добавляем после статуса все введенные данные
// таким образом, при повторной загрузке, форма получит старые данные, а пользователю не придется вводить их заново
// можно было бы принять данные и когда все хорошо, но тогда их можно будет перехватить - это раз, и ссылка получится огромной - это два

if ($module -> status !== 'complete') {
	$module -> status .= '&' . http_build_query($module -> form);
}

header("Location: " . $_SERVER['REDIRECT_URL'] . "?formstatus=" . $module -> status);
exit;

?>