<?php

// первым делом собираем данные из запроса

$module = (object) array(
	'base' => (object) array(
		'path' => PATH_MODULES . DIRECTORY_SEPARATOR . $query -> name . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $query -> param,
		'lang' => $currlang,
		'currdate' => date('d.m.Y H:i (P') . ' GMT)',
		'ip' => $_SERVER['REMOTE_ADDR'],
		'browser' => $_SERVER['HTTP_USER_AGENT'],
	),
	'settings' => (object) array(),
	'label' => array(),
	'value' => array(),
);

// вторым номером читаем настройки и объединяем их с предыдущими данными

$module -> settings = dataloadjson($module -> base -> path . '.ini');

if (empty($module -> settings)) {
	exit;
}

// теперь начинаем эти данные песочить
// проверка на валидность

$query -> data = (array) $query -> data;

foreach($module -> settings -> form as $item) {
	
	$query -> data[$item -> name] = trim($query -> data[$item -> name]);
	
	if (!datavalidation($query -> data[$item -> name], $item -> verify)) {
		$query -> errors[] = $item -> name;
	} else {
		/*
		$module -> label = (object) array_merge( (array) $module -> label, array( $item -> name => $item -> text ) );
		$module -> value = (object) array_merge( (array) $module -> value, array( $item -> name => htmlentities($query -> data[$item -> name]) ) );
		*/
		$module -> label[$item -> name] = $item -> text;
		$module -> value[$item -> name] = htmlentities($query -> data[$item -> name]);
	}
	
}

if (empty($query -> errors)) {
	$query -> status = 'ready';
} else {
	$query -> status = 'fail';
}

/*
print_r($module -> data);
echo '<br><br>';
print_r($module -> settings);
echo '<br><br>';
print_r($_GET);
*/

// если все ок, то запускаем функцию отправки оповещения админу (не пользователю!!!)

if (
	$query -> status === 'ready' &&
	!empty($module -> settings -> send) &&
	message(
		$module -> settings -> send,
		$module -> settings -> message -> subject,
		(object) array(
			'label' => (object) $module -> label,
			'value' => (object) $module -> value
		),
		$module -> settings -> message -> text,
		$module -> base -> path . '_error'
	)
) {
	$query -> status = 'complete';
}

// если в настройках включен лог, то записываем логи о пришедших сообщениях

if (!empty($module -> settings -> log)) {
	
	$arr = dataloadcsv($module -> base -> path . '_log');
	
	if (empty($arr) || empty($arr -> data)) {
		$arr = (object) array(
			'data' => [
				[
					'date',
					'status',
					'ip',
					'browser',
					'message'
				]
			]
		);
	}
	
	$arr -> data[] = [
		datadatetime('','{dd}.{mm}.{yy} {hour}:{min}:{sec}'),
		//date('d.m.Y H:i:s (P') . ' GMT)',
		$query -> status,
		$_SERVER['REMOTE_ADDR'],
		$_SERVER['HTTP_USER_AGENT'],
		$module -> settings -> message -> text . ':' . $module -> settings -> message -> subject . ':' . json_encode($module -> settings)
	];
	
	datasavecsv($arr -> data, $module -> base -> path . '_log');
	
}

// если отправка не удалась, то мы подготавливаем редирект, а именно добавляем после статуса все введенные данные
// таким образом, при повторной загрузке, форма получит старые данные, а пользователю не придется вводить их заново
// можно было бы принять данные и когда все хорошо, но тогда их можно будет перехватить - это раз, и ссылка получится огромной - это два

if ($query -> status === 'complete') {
	header("Location: " . $_SERVER['REDIRECT_URL']);
	exit;
}


?>