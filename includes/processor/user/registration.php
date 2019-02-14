<?php defined('isQUERY') or die;

/*
Не хватает функции перезаписи одного значения в таблице
В локальном случае должна идти запись в потоке
Метод пайпинга подробно изложен по ссылке: https://habr.com/ru/post/345024/
*/

$query -> var['usertable'] = dbSelect('settings', 'userstable');

//print_r($query); echo '<br><br>';
//print_r($query -> var['usertable']);

// в дальнейшем, возможно, избавимся от поля id, а поле login, возможно, заменим на name (как в других таблицах)
// убрать поле id хочется хотя бы по той причине, что login и так должен быть уникальным
// и даже не зная id, в стандартном случае, когда id создаются по порядку, можно попробовать прочитать любой id наугад
// хотя с другой стороны, это не несет в себе смысла, т.к. можно и просто прочитать все данные таблицы пользователей
// но еще может быть id не нужен, если в других локальных таблицах он не используется, а в базе данных создается по-умолчанию, но роли не играет

if (count(array_intersect_key( (array) $query -> var['usertable'], array_flip(['id', 'login', 'password', 'status', 'data']) )) < 5) {
	// ошибка базы данных: в настройках базы данных не хватает обязательных полей
	$query -> errors['usertable'] = 'filedsmissing';
	//echo '<br>ERROR!!! THIS USER TABLE IS NOT VALID - ONE OR ALL FIELDS MISSING : [id, login, password, status or data] !!!<br>';
}

if (
	empty($query -> var['usertable'] -> login) ||
	empty($query -> var['usertable'] -> login -> required) ||
	empty($query -> var['usertable'] -> login -> unique)
) {
	// ошибка базы данных: логин не указан в настройках как обязательное и уникальное поле
	$query -> errors['usertable'] = 'loginrequiredunique';
	//echo '<br>ERROR!!! THIS USER TABLE IS NOT VALID - LOGIN FIELD MUST BE REQUIRED AND UNIQUE!!!<br>';
}

foreach ($query -> var['usertable'] as $key => $item) {
	
	//echo 'before: [' . $query -> data -> $key . ']<br>';
	$query -> data -> $key = dataclear($query -> data -> $key, $item -> validation);
	//echo 'after: [' . $query -> data -> $key . ']<br>';
	
	if (
		!empty($query -> data -> $key) &&
		array_key_exists($key . '_confirm', (array) $query -> data) &&
		$query -> data -> $key !== dataobject($query -> data, $key . '_confirm')
	) {
		// здесь нужно записывать ошибку, чтобы потом вернуть ее в форму
		// ошибка: поле имеет дублирующееся поле для подтверждения введенного значения (с ключом key_confirmed) и их значения не совпадают!
		$query -> errors[$key . '_confirm'] = 'notconfirm';
		//echo '<br>ERROR!!! THIS VALUE IS NOT CONFIRM : [' . $key . '] !!!<br>';
	}
	
	if (
		empty($query -> data -> $key) &&
		!empty($item -> required)
	) {
		// здесь нужно записывать ошибку, чтобы потом вернуть ее в форму
		// ошибка: поле имеет статус обязательного, но оно не заполнено
		$query -> errors[$key] = 'required';
		//echo '<br>ERROR!!! THIS VALUE IS REQUIRED : [' . $key . '] !!!<br>';
	}
		
	if (
		!empty($query -> data -> $key) &&
		!empty($item -> validation) &&
		!datavalidation($query -> data -> $key, $item -> validation)
	) {
		// здесь нужно записывать ошибку, чтобы потом вернуть ее в форму
		// ошибка: поле имеет проверку, но значение этого поля данную проверку не прошло
		$query -> errors[$key] = 'notvalid';
		//echo '<br>ERROR!!! THIS VALUE IS NOT VALID : [' . $key . '] !!!<br>';
	}
	
	if (!empty($item -> crypt)) {
		if ($item -> crypt === 'password') {
			$query -> data -> $key = password_hash($query -> data -> password, PASSWORD_DEFAULT);
		} elseif ($item -> crypt === 'hash') {
			$query -> data -> $key = datacrypt($query -> data -> $key, 'hash');
		} else {
			$query -> data -> $key = datacrypt($query -> data -> $key);
		}
		//echo 'crypt: [' . $query -> data -> $key . ']<br>';
	}
	
	if (!empty($item -> unique)) {
		
		// вычисляем совпадения, если текущий объект должен быть уникальным
		if (!empty($query -> data -> $key)) {
			
			// простой алгоритм сравнения
			// проверка через встроенную возможность проверки таблицы с криптой,
			// но данный алгоритм занимает слишком много времени и памяти из-за того, что
			// для сравнения приходится раскриптовывать каждое значение
			//if (dbUse('users', 'verify', $key, $query -> data -> $key, ['crypt' => (!empty($item -> crypt)) ? $item -> crypt : false])) {
			//	echo '<br>ERROR!!! THIS VALUE IS THIS : [' . $key . '][' . $query -> data -> $key . '] !!!<br>';
			//}
			
			// другой алгоритм при крипте проверяет наличие в базе поля с тем же именем, но с добавлением хэша
			// если такое поле есть, то делает выборку и проверку по хэшу
			// если поля нет, то проверяет по простому алгоритму сравнения
			// если же крипты нет вообще, то идет обычная проверка
			
			if (
				!empty($item -> crypt) &&
				array_key_exists($key . '_hash', $query -> var['usertable'])
			) {
				$query -> data -> compare = dbUse('users', 'verify', $key . '_hash', datacrypt(datacrypt($query -> data -> $key, 1), 'hash'));
			} elseif (!empty($item -> crypt)) {
				$query -> data -> compare = dbUse('users', 'verify', $key, $query -> data -> $key, ['crypt' => true]);
			} else {
				$query -> data -> compare = dbUse('users', 'verify', $key, $query -> data -> $key);
			}
			
			if (!empty($query -> data -> compare)) {
				// ошибка: данное поле должно иметь уникальное значение, но введенное значение уже есть в базе данных
				$query -> errors[$key] = 'notunique';
				//echo '<br>ERROR!!! THIS VALUE IS THIS : [' . $key . '][' . $query -> data -> $key . '] !!!<br>';
			}
			
		}
		
	}
}

if (!count($query -> errors)) {
	
	// save to db
	
	$query -> data -> id = time() . '00' . rand(1000, 9999);
	$query -> var['writetable'] = array_intersect_key((array) $query -> data, (array) $query -> var['usertable']);
	//print_r($query -> var['writetable']);
	dbUse('users', 'insert', $query -> var['writetable']);
	
	// not errors
	$query -> status = 'success';
	$query -> data = [];
	$query -> var = [];
	
	// print message about confirm registration via email
	// and final redirect to main page or continue loading with new datas to get protocol
	
} else {
	
	// errors
	//echo 'E-R-R-O-R-S-!-!-!';
	$query -> status = 'fail';
	// attention! decrypt all encrypted values
	foreach ($query -> var['usertable'] as $key => $item) {
		if (!empty($item -> crypt)) {
			$query -> data -> $key = datacrypt($query -> data -> $key, 1);
			//echo 'crypt: [' . $query -> data -> $key . ']<br>';
		}
	}
	
	// next loading form page with errors
	
}

/*
//$a = dbSelect('settings', 'userstable');
//$a = dbUse('users', 'insert', ['1', '22', '333', '444']);
//$a = dbUse('users', 'insert', ['login' => '0-0-0-0-0-0-0', 'password' => 'qwerty']);
//$a = dbUse('users', 'insert', ['login' => '0-0-0-0-0-0-0', 'password' => 'qwerty', 'email' => $query -> data -> email]);
//$a = dbUse('users', 'select');
//$a = dbUse('users', 'select', 'id', '', '');
//$a = dbUse('users', 'select', 'id', ['login' => 'aaa'], '');
//$a = dbUse('users', 'select', '', ['login' => 'aaa'], '');
//$a = dbUse('users', 'select', '', '', ['limit' => true]);
//$a = dbUse('users', 'select', '', ['lastvisit' => ''], '');
//$a = dbUse('users', 'select', '', ['lastvisit' => ''], ['limit' => true]);
//$a = dbUse('users', 'select', '', '', ['limit' => true]);

echo '<pre>';
print_r($query -> data);
echo '</pre>';

exit;
*/
?>