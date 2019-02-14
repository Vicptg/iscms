<?php

/* ФУНКЦИИ ПО ОБРАБОТКЕ ДАННЫХ */

function datareplacelang(&$arr) {
	
	/*
	*  Функция выбора языковых вариантов в массиве
	*  на входе нужно указать массив $arr
	*  
	*  функция примет массив и произведет поиск и замену языковых вариантов
	*  на единственный вариант, соответствующий текущему установленному языку
	*  по языковому коду
	*  
	*  например, массив { 'answer' : { 'ru' : 'привет', 'en' : 'hello' } } примет вид:
	*    если на сайте установлен английский язык { 'answer' : 'hello' }
	*    если на сайте установлен русский язык { 'answer' : 'привет' }
	*  
	*  на выходе ничего не отдает, т.к. работает напрямую с указанным массивом
	*/
	
	global $currlang;
	
	if (!isset($currlang)) {
		return;
	}
	
	foreach ($arr as &$item) {
		
		if (is_array($item) && isset($item[$currlang])) {
			$item = $item[$currlang];
		} elseif (is_object($item) && isset($item -> $currlang)) {
			$item = $item -> $currlang;
		}
		
		if (is_array($item) || is_object($item)) {
			datareplacelang($item);
		}
		
		unset($arr, $item);
		
	}
	
}


function dateformatcorrect($arr) {

	/*
	*  Функция корректировки формата даты
	*  на входе нужно указать массив $arr
	*  
	*  функция примет массив и произведет коррекцию формата даты
	*  на единственный вариант, соответствующий заданному стандарту
	*  
	*  например, дата 1.9.8 примет вид 01.09.2008
	*  
	*  параметры массива:
	*    sym - стандартный символ разделителя даты,
	*      например '.' для русского и '/' для английского языков
	*    convert - символ, который разделяет число и месяц,
	*      будет преобразован в стандартный, но число и месяц поменяются местами
	*    replace - другой символ, который разделяет дату,
	*      будет преобразован в стандартный без других изменений
	*    data - дата на входе
	*  
	*  на выходе отдает дату в исправленном формате
	*/
	
	$arr -> split = preg_split('/[\\' . $arr -> sym . '\\' . $arr -> convert . '\\' . $arr -> replace . ']/', $arr -> date, -1, PREG_SPLIT_OFFSET_CAPTURE);
	foreach ($arr -> split as &$split) {
		if (strlen($split[0]) === 1) {
			$split[0] = '0' . $split[0];
		}
	}
	
	$arr -> merge = $arr -> split[0][0];
	if (isset($arr -> split[1])) {
		$arr -> merge .= substr($arr -> date, $arr -> split[1][1] - 1, 1) . $arr -> split[1][0];
	}
	if (isset($arr -> split[2])) {
		$arr -> merge .= substr($arr -> date, $arr -> split[2][1] - 1, 1) . $arr -> split[2][0];
	}
	$arr -> date = $arr -> merge;
	
	if (strlen($arr -> date) === 5) {
		$arr -> date .= $arr -> sym . date('Y');
	} elseif (strlen($arr -> date) === 8) {
		$arr -> date = substr($arr -> date, 0, 6) . substr(date('Y'), 0, 2) . substr($arr -> date, 6, 2);
	}
	if (strpos($arr -> date, $arr -> replace)) {
		$arr -> date = str_replace($arr -> replace, $arr -> sym, $arr -> date);
	}
	if (strpos($arr -> date, $arr -> convert)) {
		$arr -> date = substr($arr -> date, 3, 2) . $arr -> sym . substr($arr -> date, 0, 2) . $arr -> sym . substr($arr -> date, 6, 4);
	}
	
	return $arr -> date;
	
}

function datavalidation($data, $type) {
	
	/*
	*  Функция валидации значения, т.е. проверки его на соответствие определенному типу
	*  на входе нужно указать значение $data и тип проверки $type
	*  
	*  параметры типов:
	*    alphanumeric - проверка на соответствие буквам, цифрам и нескольким знакам,
	*    numeric - проверка на соответствие только цифрам,
	*    datetime - проверка на соответствие формату даты,
	*    phone/phone_ru - проверка на соответствие телефонному номеру
	*    login - проверка на соответствие логину, английские буквы, цифры и несколько разрешенных знаков
	*    email - проверка на соответствие email,
	*    tags - проверка на присутствие тегов,
	*    notags - проверка на отсутствие тегов,
	*    text - проверка на соответствие тексту, буквы, цифры, пробелы и знаки пунктуации
	*    [0, 1] - если параметром типов указан массив из двух цифр, то проверяется соответствие на минимальное и максимальное значение
	*    [" ", "-", "_"] - если параметром типов указан массив из нескольких строк, то проверяется отсутствие всех доп.символов, кроме этих
	*  
	*  на выходе отдает
	*    true, если проверка прошла успешно
	*    и false, если значение проверку не прошло
	*/
	
	$regexp = '';
	$data = trim($data);
	$ext = in_array('mbstring', get_loaded_extensions()); // EDITED FOR NOT PHP MODULE
	
	if (
		!$data ||
		($ext && mb_strlen($data) < 6) ||
		(!$ext && strlen($data) < 6)
	) {
		return false;
	}
	
	switch ($type) {
		case 'alphanumeric':
			$regexp = '[^a-zA-Z0-9_\- ]';
		break;
		case 'numeric':
			$regexp = '^[0-9]+$';
		break;
		case 'datetime':
			$regexp = '^[0-9_\-.,:()\\\\\/ ]+$';
		break;
		case 'phone':
		case 'phone_ru':
			$data = str_replace(['(', ')', '-', '_', '+', ' '], '', $data);
			$regexp = '^\d{10,12}$';
		break;
		case 'login':
			$regexp = '^[a-zA-Z0-9\-_.@]+$';
		break;
		case 'email':
			$regexp = '^[^@=<> ]+@{1}[^@=<> ]+\.\w+$';
		break;
		case 'tags':
			$regexp = '<[^>]+?>';
		break;
		case 'notags':
			$regexp = '^[^<>]+$';
		break;
		case 'text':
			$regexp = '^[\w\d\s\-\'\".,!?():№*«»…—‒–]+$';
		break;
		case (is_array($type) && count($type) === 2 && is_numeric($type[0]) && is_numeric($type[1])):
			$regexp = '^.{' .$type[0] . ',' . $type[1] . '}$';
		break;
		case (is_array($type)):
			foreach ($type as $item) {
				$regexp .= $item;
			}
			$regexp = str_replace(['[', ']', '-', '\'', '"', '\"'], ['\\[', '\\]', '\\-', '\\\'', '\"', '\"'], $regexp);
			$regexp = '[^' . $regexp . ']';
		break;
		default:
			return false;
		break;
	}
	
	if (preg_match('/' . $regexp . '/ui', $data)) {
		return true;
	} else {
		return false;
	}
	
}

function dataclear($data, $type, $tagsarray = false) {

	/*
	*  Функция которая производит очистку данных по заданному параметру
	*  например, перед передачей ее для записи в базу данных
	*  на входе нужно указать значение $data и тип преобразования $type
	*  
	*  параметры типов (если нужно несколько, можно перечислять через пробел и/или запятую):
	*    alphanumeric - оставление в строке только (!) цифр, латинских букв и пробелов
	*    numeric - оставление в строке только (!) цифр
	*    datetime - оставление в строке только (!) цифр и знаков, встречающихся в формате даты и времени
	*    phone - приведение строки к телефонному номеру
	*    phone_ru - приведение строки к телефонному номеру россии (+7 заменяется на 8)
	*    login/email - приведение строки к формату логина/email
	*    url - приведение строки к формату url, включая спецсимволы
	*    simpleurl - приведение строки к формату url без спецсимволов, с обрезкой всех параметров
	*    urlencode - приведение строки к формату url, в котором символы кодируются % и hex-кодом
	*    urldecode - приведение строки из формата urlencode в обычный текстовый вид
	*    tospaces - приведение всех пробелов, табуляций и символов пробелов к одному пробелу
	*    nospaces - удаление всех пробелов
	*    codespaces - удаление незначащих для кода пробелов, сокращение кода
	*    onestring - приведение данных к однострочному виду
	*    code - htmlspecialchars
	*    entities - htmlentities
	*    notags - удаление всех тегов
	*    cleartags - очищение всех атрибутов внутри тегов
	*    tags - удаление всех тегов, кроме разрешенных
	*      чтобы этот параметр работал корректно, входящие данные должны быть кодированы 
	*      htmlspecialchars, в противном случае теги будут очищены
	*      на предварительном этапе обработки
	*  
	*  на выходе отдает преобразованное значение $data
	*/
	
	// выполняем предварительное очищение - от скриптов, программного кода
	$data = preg_replace('/<\?.+?\?>/','', $data);
	$data = preg_replace('/<script.+?\/script>/','', $data);
	
	// продолжаем предварительное очищение - от всех тегов, кроме разрешенных
	// задаем разрешенные теги
	$tags = [
		'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'pre', 'span', 'font', 'br', 'hr', 'img', // base elements
		'b', 'i', 's', 'u', 'blockquote', 'code', 'del', 'em', 'ins', 'small', 'strong', 'sub', 'sup', // base formatting
		'ul', 'ol', 'li', 'dl', 'dt', 'dd', 'details', 'summary', // list
		'table', 'thead', 'tbody', 'tfoot', 'th', 'tr', 'td', 'col', 'colgroup', 'caption', // table
		'abbr', 'bdi', 'bdo', 'cite', 'dfn', 'kbd', 'mark', 'q', 'rp', 'rt', 'rtc', 'ruby', 'samp', 'var', 'wbr' // additional
	];
	// подготавливаем список
	$striptags = '';
	foreach ($tags as $tag) {
		$striptags .= '<' . $tag . '>';
	}
	// завершаем
	unset($tags, $tag);
	// очищаем
	$data = strip_tags($data, $striptags);
	
	// продолжаем предварительное очищение - чистим текст от пробелов и отступов в начале и в конце
	$data = trim($data);
	$data = preg_replace('/^(&nbsp;)+/', '', $data);
	$data = preg_replace('/(&nbsp;)+$/', '', $data);
	
	// продолжаем предварительное очищение - чистим текст от двойных пробелов
	$data = preg_replace('/(\s|&nbsp;){2,}/', '$1', $data);
	
	if (!$type) { return $data; }
	
	// выполняем очищение согласно заданных типов
	
	$qs = preg_split('/\,{0,1}\s|\,/', $type);
	foreach ($qs as $q) {
		
		switch ($q) {
			case 'alphanumeric':
				$data = preg_replace('/[^a-zA-Z0-9_\- ]/', '', $data);
			break;
			case 'numeric':
				$data = preg_replace('/[^0-9]/', '', $data);
			break;
			case 'datetime':
				$data = preg_replace('/[^0-9_\-.,:()\\\\\/ ]/', '', $data);
			break;
			case 'phone':
				$data = preg_replace('/[^0-9]/', '', $data);
			break;
			case 'phone_ru':
				if (in_array('mbstring', get_loaded_extensions())) {
					$dataFirstSymbol = mb_substr($data,0,1);
				} else {
					$dataFirstSymbol = substr($data,0,1);
				}
				if (strlen($data) == 10) {
					$data = substr_replace($data, '7', 0, 0);
				} elseif ($dataFirstSymbol == 8) {
					$data = substr_replace($data, '7', 0, 1);
				}
			break;
			case 'login':
			case 'email':
				$data = preg_replace('/[^a-zA-Z0-9\-_.@]/', '', $data);
			break;
			case 'url':
				$data = preg_replace('/[^a-zA-Z0-9\-_.:\/?&\'\"=#+]/', '', $data);
				$data = rawurlencode($data);
			break;
			case 'simpleurl':
				$data = preg_replace('/[?&].*$/', '', $data);
				$data = preg_replace('/[^a-zA-Z0-9\-_.:\/]/', '', $data);
				$data = htmlspecialchars($data);
			break;
			case 'urlencode':
				$data = rawurlencode($data);
			break;
			case 'urldecode':
				$data = rawurldecode($data);
				$data = preg_replace('/[^a-zA-Z0-9\-_.:\/?&=#+]/', '', $data);
			break;
			case 'tospaces':
				$data = str_replace('&nbsp;', ' ', $data);
				$data = preg_replace('/\s+/', ' ', $data);
			break;
			case 'nospaces':
				$data = str_replace('&nbsp;', '', $data);
				$data = preg_replace('/\s/', '', $data);
			break;
			case 'codespaces':
				$data = str_replace('&nbsp;', ' ', $data);
				$data = preg_replace('/\s+/', ' ', $data);
				$data = preg_replace('/(.)\s(\W)/', '$1$2', $data);
				$data = preg_replace('/([^\w"])\s(\w)/', '$1$2', $data);
			break;
			case 'onestring':
				$data = preg_replace('/([^\s\t]|^)[\s\t]*(\r?\n){1,}[\s\t]*([^\s\t]|$)/', '$1 $3', $data);
			break;
			case 'code':
				$data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5);
			break;
			case 'entities':
				$data = htmlentities($data);
			break;
			case 'tags':
				$data = htmlspecialchars_decode($data);
				$data = strip_tags($data, $striptags);
			break;
			case 'notags':
				//$data = preg_replace('/([^\s\t]|^)[\s\t]*(\r?\n){1,}[\s\t]*([^\s\t]|$)/', '$1 $3', $data);
				$data = preg_replace('/(<\/\w+?>)|(<\w+?\s.+?>)/', '', $data);
			break;
			case 'cleartags':
				//$data = preg_replace('/([^\s\t]|^)[\s\t]*(\r?\n){1,}[\s\t]*([^\s\t]|$)/', '$1 $3', $data);
				$data = preg_replace('/<(\w+)?\s.+?>/', '<$1>', $data);
			break;
			case 'text':
				$data = preg_replace('/[^\w\d\s\-\'\".,!?():№*«»…—‒–]/', '', $data);
			break;
		}
		
	}
	
	return $data;
}

function datakeys($arrTarget) {

	/*
	*  Функция которая проверяет, ассоциативный массив или индексный
	*  на входе нужно указать проверяемый массив
	*  
	*  проверка идет по значениям ключей массива, и если все они числовые,
	*  то считается, что массив неассоциативный
	*  однако, массив вида [ 0 => 'a', 1 => 'b', 'key' => 'c' ]
	*  будет считаться уже ассоциативным
	*  
	*  на выходе отдает:
	*  true, если массив ассоциативный
	*  false, если массив индексный, т.е. неассоциативный
	*/
	
	if (count(array_filter(array_keys($arrTarget), 'is_string')) > 0) {
		return true;
	} else {
		return false;
	}
	
}

function datamerge($arrTarget, $arrFill, $convert = false) {

	/*
	*  Функция которая производит объединение данных в многомерных массивах или объектах
	*  на входе нужно указать:
	*    целевой массив или объект, которЫЙ будем заполнять - $arrTarget
	*    и массив или объект, которЫМ будем заполнять arrTarget - $arrFill
	*  
	*  чтобы просто добавить значение в объект или массив, использовать так:
	*  $obj = datamerge($obj, (object) ['field' => 'value']);
	*  $arr = datamerge($arr, ['field' => 'value']);
	*  
	*  на выходе отдает готовый массив $arrTarget
	*/
	
	if (
		!is_object($arrTarget) &&
		!is_array($arrTarget) &&
		!count($arrTarget) &&
		!is_object($arrFill) &&
		!is_array($arrFill) &&
		!count($arrFill)
	) {
		return false;
	}
	
	foreach ($arrFill as $k => $i) {
		
		if (is_array($i) || is_object($i)) {
			
			if (is_object($arrTarget)) {
				
				if (!isset($arrTarget -> $k)) {
					$arrTarget = (object) array_merge(
						(array) $arrTarget,
						array($k => $i)
					);
				} elseif (!$arrTarget -> $k) {
					$arrTarget -> $k = $i;
					
					if ($convert === 'array') {
						$arrTarget[$k] = (array) ($arrTarget[$k]);
					}
					
				} else {
					$arrTarget -> $k = datamerge($arrTarget -> $k, $i, $convert);
				}
				
			} elseif (is_array($arrTarget)) {
				
				if (empty($arrTarget[$k])) {
					$arrTarget[$k] = $i;
					
					if ($convert === 'object') {
						$arrTarget[$k] = (object) ($arrTarget[$k]);
					}
					
				} else {
					$arrTarget[$k] = datamerge($arrTarget[$k], $i, $convert);
				}
				
			}
			
		} else {
			
			if (is_object($arrTarget)) {
				
				if ($convert === 'replace') {
					unset($arrTarget -> $k);
				}
				
				if (!isset($arrTarget -> $k)) {
					$arrTarget = (object) array_merge(
						(array) $arrTarget,
						array($k => $i)
					);
				} elseif (!$arrTarget -> $k) {
					$arrTarget -> $k = $i;
				}
				
			} elseif (is_array($arrTarget)) {
				
				if ($convert === 'replace') {
					unset($arrTarget[$k]);
				}
				
				if (empty($arrTarget[$k])) {
					$arrTarget[$k] = $i;
				}
				
			}
			
		}
		
	}
	
	if ($convert && $convert !== 'replace') {
		$arrTarget = json_encode($arrTarget);
	}
	if ($convert === 'array') {
		$arrTarget = json_decode($arrTarget, true);
	} elseif ($convert === 'object') {
		$arrTarget = json_decode($arrTarget);
	}
	
	return $arrTarget;
	
}

function datasplit($target, $splitter = '\s,') {

	/*
	*  Функция, которая разбивает строку на подстроки по заданным разделителям
	*  на входе нужно указать:
	*    исходную строку
	*    строку с разделителями (если не указана, то по-умолчанию будет назначены пробел и запятая)
	*  
	*  Функция также чистит строку от квадратных и фигурных скобок
	*  
	*  на выходе отдает готовый массив
	*/
	
	/*
	if (
		strpos($item[$field_item], '[') !== false &&
		strpos($item[$field_item], ']') !== false
	) {
		$item[$field_item] = mb_substr($item[$field_item], 1, -1);
	}
	*/
	
	$target = str_replace(['[', ']', '{', '}'], '', $target);
	$target = preg_split('/[' . $splitter . ']/', $target, null, PREG_SPLIT_NO_EMPTY);
	
	return $target;

}

function dataobject($objTarget, $arrTarget, $output = false) {

	/*
	*  Функция, которая извлекает значение из объекта, воспринимая его как массив
	*  на входе нужно указать:
	*    исходный объект
	*    конечный элемент объекта в виде значения массива или объекта
	*    опция - если true, то возвращать в случае ошибки значение $arrTarget
	*  
	*  по-сути, это просто упрощение записи
	*  (((array)$object -> element)[$item -> name]) или $object -> element -> {$item -> name}
	*  но универсальная вне зависимости от версии PHP и чтобы не повторять ее в коде много раз
	*  
	*  также данная функция удобна тем, что ее можно вызывать без проверок
	*  на существование объекта, массива или их значений
	*  
	*  на выходе отдает значение элемента или пустое значение
	*/
	
	if (
		!$objTarget ||
		!$arrTarget
	) {
		
		if ($arrTarget && $output) {
			return $arrTarget;
		} else {
			return false;
		}
	} elseif (is_object($objTarget)) {
		return $objTarget -> $arrTarget;
	} else {
		return $objTarget[$arrTarget];
	}
	
}

function dataconvert($arrTarget, $convert = false) {

	/*
	*  Функция, которая преобразует массив в объект и объект в массив
	*  на входе нужно указать:
	*    исходный объект или массив
	*    конвертер:
	*      false - по-умолчанию, преобразует объект в массив и наоборот целиком
	*      true - преобразует объект в массив и наоборот только по внешнему уровню
	*      reset - сбрасывает все ключи массива
	*  
	*  на выходе отдает готовый массив или объект
	*/
	
	if (is_array($arrTarget) && !$convert) {
		$arrTarget = json_decode(json_encode($arrTarget));
	} elseif (is_object($arrTarget) && !$convert) {
		$arrTarget = json_decode(json_encode($arrTarget), true);
	} elseif (is_array($arrTarget) && $convert === 'reset') {
		$arrTarget = (object) array_values( (array) $arrTarget);
	} elseif (is_object($arrTarget) && $convert === 'reset') {
		$arrTarget = (array) array_values( (array) $arrTarget);
	} elseif (is_array($arrTarget)) {
		$arrTarget = (object) $arrTarget;
	} else {
		$arrTarget = (array) $arrTarget;
	}
	
	return $arrTarget;

}

function dataphone($target, $format = false) {
	
	/*
	*  Функция, которая форматирует номер телефона в нужном языковом формате
	*  на входе нужно указать:
	*    номер телефона в виде числа или строки
	*    формат
	*      false - по-умолчанию, берет формат из параметра $lang -> phone
	*      true - или если не задан $lang -> phone, просто преобразует в 11-значный набор чисел: 79001234567
	*  
	*  шаблон формата очень простой - все цифры нужно заменить на 'x'
	*  например, шаблон "+x (xxx) xxx-xx-xx" для номера "79001234567" выдаст "+7 (900) 123-45-67"
	*  а шаблон "+x xxx xxx xx xx" для номера "79001234567" выдаст "+7 900 123 45 67" и т.д.
	*  
	*  на выходе отдает готовую строку
	*/
	
	global $lang;
	
	$target = preg_replace('/[^0-9]/', '', $target);
	
	if (
		!$format &&
		isset($lang -> phone) &&
		!empty($lang -> phone -> format)
	) {
		$format = $lang -> phone -> format;
	} elseif (
		!$format ||
		$format === true
	) {
		$format = 'xxxxxxxxxx';
	}
	
	$format = preg_replace('/X/', 'x', $format);
	$format = preg_replace('/[^xX0-9 +—‒–().,_:;\-\[\]\{\}]/', '', $format);
	
	if (
		strlen($target) < 11 &&
		isset($lang -> phone) &&
		!empty($lang -> phone -> default)
	) {
		$i = 0;
			
		while (strlen($target) < 11) {
			$n = 11 - strlen($target) - 1;
			$target = $lang -> phone -> default[$n] . $target;
		}
		
		echo '['. $target . ']';
	}
	
	$i = 0;
	
	while (strpos($format, 'x') !== false) {
		
		$n = strpos($format, 'x');
		$format[$n] = $target[$i];
		$i++;
		
	}
	
	return $format;
	
}

function datalang($target, $current, $s = false, $convert = false) {

	/*
	*  Функция, которая выводит переменную в нужном языковом формате
	*  на входе нужно указать:
	*    переменную
	*    раздел языкового массива
	*    параметры морфера (падеж, число)
	*    параметры преобразования
	*      l - lowercase, все строчные буквы
	*      u - uppercase, все заглавные буквы
	*      c - case, первые буквы слов - заглавные
	*      по-умолчанию все буквы - как есть
	*  
	*  если вместо переменной будет указан массив, то функция переведет массив согласно
	*  языку и автоматически вызовет функцию dataarraytostring
	*  
	*  на выходе отдает готовую строку
	*/
	
	global $lang;
	$ext = in_array('mbstring', get_loaded_extensions());
	
	//echo $target . ' ';
	
	if (is_string($target)) {
		
		if (isset($lang -> $current -> $target)) {
			$target = $lang -> $current -> $target;
		}
		if ($s) {
			$target = datamorpher($target, $s);
		}
		
		if ($ext && $convert && $convert === 'l') {
			$target = mb_convert_case($target, MB_CASE_LOWER);
		} elseif ($ext && $convert && $convert === 'u') {
			$target = mb_convert_case($target, MB_CASE_UPPER);
		} elseif ($ext && $convert && $convert === 'c') {
			$target = mb_convert_case($target, MB_CASE_TITLE);
		} elseif ($ext && $convert && $convert === 't') {
			$target = mb_convert_case(mb_substr($target, 0, 1), MB_CASE_UPPER) . mb_convert_case(mb_substr($target, 1), MB_CASE_LOWER);
		}
		
	} else {
		
		$target = [$target, '', []];
		
		foreach ($target[0] as $target[1]) {
			
			$sample = $target[1];
			
			if (isset($lang -> $current -> $sample)) {
				$sample = $lang -> $current -> $sample;
			}
			if ($s) {
				$sample = datamorpher($sample, $s);
			}
			
			if ($ext && $convert && $convert === 'l') {
				$sample = mb_convert_case($sample, MB_CASE_LOWER);
			} elseif ($ext && $convert && $convert === 'u') {
				$sample = mb_convert_case($sample, MB_CASE_UPPER);
			} elseif ($ext && $convert && $convert === 'c') {
				$sample = mb_convert_case($sample, MB_CASE_TITLE);
			} elseif ($ext && $convert && $convert === 't') {
				$target = mb_convert_case(mb_substr($sample, 0, 1), MB_CASE_UPPER) . mb_convert_case(mb_substr($sample, 1), MB_CASE_LOWER);
			}
			
			$target[3][] = $sample;
			
		}
		
		$target = dataarraytostring($target[3], (isset($lang -> counter -> split)) ? $lang -> counter -> split : ' ');
		
	}
	
	return $target;
	
}

function datanum($target, $convert = false, $multiply = false) {

	/*
	*  Функция, которая выводит число в нужном языковом формате
	*  на входе нужно указать:
	*    число
	*    тип конверсии
	*      false - по-умолчанию, просто преобразует в число
	*      bits - разделяет разряды
	*      dec - ставит два знака после запятой
	*      bitsdec или decbits - разделяет разряды и ставит два знака после запятой
	*      add - добавляет после числа окончание
	*        могут быть параметры, указанные через двоеточие, согласно правилам морфинга,
	*        сами окончания задаются в $lang -> datetime -> add, где индекс соответствует числу,
	*        а 0 - для всех чисел, для которых индекс не задан или не найден,
	*        для самой цифры 0 индекс не применяется
	*      array - преобразует в массив, единицы, десятки, сотни и т.д.
	*      если указать массив значений, то преобразует в текстовое представление
	*        первое значение - падеж
	*        второе значение - трансформация
	*          l - lowercase, все строчные буквы
	*          u - uppercase, все заглавные буквы
	*          c - case, первые буквы заглавные
	*          по-умолчанию все буквы - как есть
	*        третье значение - форма слова, существительное/прилигательное
	*    множитель - на сколько число будет умножено
	*      
	*  
	*  если вместо переменной будет указан массив,
	*  то функция рекурсивно вызовет себя
	*  и вернет готовый массив чисел
	*  
	*  на выходе отдает готовое число
	*/
	
	if (is_array($target)) {
		$target = [$target, '', []];
		foreach ($target[0] as $target[1]) {
			$target[3][] = datanum($target[1], $convert, $multiply);
		}
		$target = $target[3];
		return $target;
	}
	
	if (
		strpos($target, ',') !== false ||
		strpos($target, '.') !== false
	) {
		$target = str_replace(',', '.', $target);
		$target = (float) $target;
	} else {
		$target = (int) $target;
	}
	
	if ($multiply) {
		$target = $target * $multiply;
	}
	
	if (
		$convert !== false &&
		$convert !== 'array'
	) {
		global $lang;
	}
	
	if ($convert === 'bits') {
		$target = number_format(
			$target,
			0,
			'',
			(isset($lang -> counter -> bit)) ? $lang -> counter -> bit : ' '
		);
	} elseif ($convert === 'dec') {
		$target = number_format(
			$target,
			2,
			(isset($lang -> counter -> dec)) ? $lang -> counter -> dec : ' ',
			''
		);
	} elseif ($convert === 'bitsdec' || $convert === 'decbits') {
		$target = number_format(
			$target,
			2,
			(isset($lang -> counter -> dec)) ? $lang -> counter -> dec : ' ',
			(isset($lang -> counter -> bit)) ? $lang -> counter -> bit : ' '
		);
	}
	
	if ($convert === 'array') {
		$target = str_replace($lang -> counter -> bit, '', $target);
		$target = (int) $target;
		$target = str_split($target);
	}
	
	if (is_string($convert) && substr($convert, 0, 3) === 'add') {
		
		$convert = [
			'morph' => substr($convert, 4),
			'string' => ''
		];
		
		if (strpos($convert['morph'], '::') !== false) {
			$convert['morph'] = str_replace(':::', ':0:0:', $convert['morph']);
			$convert['morph'] = str_replace('::', ':0:', $convert['morph']);
		}
		
		$convert['morph'] = datasplit($convert['morph'], ':');
		
		if (is_array($lang -> datetime -> add) && array_key_exists($target, $lang -> datetime -> add)) {
			$convert['string'] = $lang -> datetime -> add[$target];
		} elseif (is_array($lang -> datetime -> add)) {
			$convert['string'] = $lang -> datetime -> add[0];
		} else {
			$convert['string'] = $lang -> datetime -> add;
		}
		
		$target .= ($target) ? datamorpher(
			$convert['string'],
			$convert['morph']
		) : false;
		$convert = '';
		
	}
	
	if (is_array($convert)) {
		
		$target = str_replace($lang -> counter -> bit, '', $target);
		//$target = 1532201; // тестовое число
		$target = (int) $target;
		
		global $morph;
		
		//print_r($convert);
		//print_r($morph -> grammar);
		
		if ($target > 0 && isset($morph) && isset($morph -> grammar)) {
			
			$len = (strlen($target) % 3) ? 3 - strlen($target) % 3 : 0;
			$target = str_repeat(0, $len) . $target;
			$target = [ array_reverse(str_split($target, 3)), [], '', 0, [], [] ];
			// массив target:
			// [0] - массив числа, который мы разбираем на составляющие
			// [1] - массив готовых данных, куда мы записываем разбор
			// [2] - значение текущего числа
			// [3] - длина массива текущих данных
			// [4] - массив параметров морфинга
			// [5] - массив вторых параметров морфинга (если есть)
			// переменная $digit - значение текущего разряда: нет, тысячи, миллионы
			// переменная $type - значение выбранного формата: число/дата
			
			foreach ($target[0] as $target_key => $target_item) {
				
				$target_item = array_reverse(str_split($target_item));
				$target[3] = 0;
				
				// грамматика
				$target[4] = [$convert[0], $convert[1], $convert[2]];
				if ($target[4][2] === 'date') {
					$type = 'dates';
					$convert[1] = (!empty($convert[3])) ? $convert[3] : false;
				} else {
					$type = 'numbers';
				}
				
				foreach ($morph -> grammar -> $type -> base as $k => $i) {
					if ($i !== 'skip' && $k < 3) {
						$target[4][$k] = $i;
					}
				}
				
				if ($target_key === 1) {
					$digit = 'thousand';
				} elseif ($target_key === 2) {
					$digit = 'million';
				} else {
					$digit = 'first';
				}
				
				if ($target_item[1] === '1') {
					
					// это для чисел 10-19
					$target[2] = $target_item[1] . $target_item[0];
					$target = datanumgrammar(
						$target,
						$target[2],
						$type,
						$digit,
						$convert[1]
					);
					
				} else {
					
					if (
						($target_item[0] && $target_key === 0) ||
						($target_item[0] > 0 && $target_key > 0)
					) {
						// это для единиц (1-9)
						$target[2] = $target_item[0];
						$target = datanumgrammar(
							$target,
							$target[2],
							$type,
							$digit,
							$convert[1]
						);
					}
					
					if ($target_item[1]) {
						// это для десятков (20, 30, 40...)
						$target[2] = $target_item[1] . '0';
						$target = datanumgrammar(
							$target,
							$target[2],
							$type,
							$digit,
							$convert[1]
						);
					}
					
				}
				
				if ($target_item[2]) {
					// это для сотен (100, 200, 300, 400...)
					$target[2] = $target_item[2] . '00';
					$digit = 'hundred';
					$target = datanumgrammar(
						$target,
						$target_item[2],
						$type,
						$digit,
						$convert[1]
					);
				}
				
				if (
					$target_key === 1 ||
					$target_key === 2
				) {
					$target[1] = array_merge(
						array_slice($target[1], 0, $target[3]),
						[
							datalang(
								'1' . str_repeat('000', $target_key),
								'counter',
								$target[5],
								(!empty($convert[1])) ? $convert[1] : false
							)
						],
						array_slice($target[1], $target[3])
					);
				}
				
			}
			
			$target = dataarraytostring($target[1]);
			//print_r($target);
			
		}
		
	}
	
	return $target;
	
}

function datanumgrammar($target, $compare, $type, $digit, $convert = false) {

	/*
	*  Вспомогательная функция для функции datanum, определяет грамматику
	*  на входе нужно указать:
	*    массив (или объект, содержащий массив) с условиями
	*    массив настроек грамматики
	*  
	*  функция обрабатывает условия согласно заданным настройкам грамматики
	*  и возвращает готовый массив настроек грамматики
	*  
	*  правила грамматики target[4]:
	*  [0] - $convert[0] - падеж
	*  [1] - false - число, род
	*  [2] - $convert[2] - часть речи
	*  значения: false, skip, значение
	*  
	*  на выходе отдает массив настроек грамматики
	*/
	
	global $morph;
	
	if (!isset($morph -> grammar -> $type)) {
		$type = 'numbers';
	}
	
	if (!isset($morph -> grammar -> $type -> $digit)) {
		$digit = 'first';
	}
	
	if (
		(int) $compare == 1
	) {
		// это для значений 1
		$part = 'one';
	} elseif (
		(int) $compare >= $morph -> grammar -> $type -> $digit -> minmax[3] &&
		(int) $compare <= $morph -> grammar -> $type -> $digit -> minmax[4]
	) {
		// это для значений от min до max (2-4)
		$part = 'minmax';
	} else {
		// для всех остальных значений
		$part = 'all';
	}
	
	foreach ($morph -> grammar -> $type -> $digit -> $part as $k => $i) {
		
		if (strpos($i, ':') === false) {
			
			if ($i !== 'skip' && $k < 3) {
				$target[4][$k] = $i;
				$target[5][$k] = $i;
			}
			
		} else {
			
			$i = [
				substr($i, 0, strpos($i, ':')),
				substr($i, strpos($i, ':') + 1)
			];
			
			if ($i[0] !== 'skip' && $k < 3) {
				$target[4][$k] = $i[0];
			}
			
			if ($i[1] !== 'skip' && $k < 3) {
				$target[5][$k] = $i[1];
			}
			
		}
		
	}
	
	if (
		(
			$compare == 1 &&
			!empty($morph -> grammar -> $type -> $digit -> one[3])
		) || (int) $compare > 1
	) {
		array_unshift(
			$target[1],
			datalang(
				$target[2],
				'counter',
				$target[4],
				($convert) ? $convert : false
			)
		);
		$target[3]++;
	}
	
	return $target;
	
}

function datadatetime($target = false, $format = false, $convert = false) {

	/*
	*  Функция, которая выводит дату в нужном языковом формате
	*  на входе нужно указать:
	*    дату, по-умолчанию - текущая дата
	*    формат
	*      false - по-умолчанию, будет взята из языковых настроек или выведено число в формате UNIX
	*      true - без преобразования
	*      любой строковый формат форматирует дату согласно приведенным данным
	*    также дополнительно введены значения convert, которые также могут быть подставлены в format
	*    если convert не используется
	*      array - относительное время, сколько дней, часов, минут, секунд (например, 2 дня, 1 час)
	*      absolute - абсолютное время, общее целое число дней, общее целое число часов и т.д. (например, 2 дня, 49 часов)
	*  
	*  общий принцип таков:
	*    маленькая буква - номер,
	*    большая буква - название
	*    одна буква - короткий формат
	*    две буквы - полный формат
	*  например:
	*    m - номер месяца, 1
	*    mm - номер месяца с нулем, 01
	*    M - сокращенное название месяца, jan
	*    ММ - полное название месяца, january
	*    .a (латинская 'a' в конце) - добавление к номеру окончания
	*      (задается в $lang -> datetime -> add, где индекс соответствует числу,
	*      а 0 - для всех чисел, для которых индекс не задан или не найден,
	*      для самой цифры 0 индекс не применяется)
	*  переменные:
	*    y - год
	*    m - месяц
	*    d - день
	*    h - часы (+hour)
	*    i - минуты (+min)
	*    s - секунды (+sec)
	*  доп.переменные:
	*    w - номер дня недели
	*  
	*  если на входе была указана дата, то она разбирается по следующему принципу:
	*    число или числовой текст - дата в абсолютном формате UNIX
	*    любой иной строковый формат - дата форматируется согласно языковым настройкам
	*    в формате массива дата разбирается по порядку согласно приведенным данным
	*  
	*  переменные даты в строковом формате нужно указывать в квадратных скобках {M}, {D} и т.д.
	*  если нужно отформатировать строковое представление даты,
	*  после переменной через двоеточие указать параметры морфинга слова
	*  (падеж, число и род (второе - необязательно)), например: {M:r:e}
	*  
	*  datadatetime() / datadatetime('') / datadatetime('','') выдаст текущую дату в заданном формате
	*  datadatetime('',true) выдаст текущую дату в абсолютном формате
	*  datadatetime(1511110001) / datadatetime('1511110001') выдаст заданную дату в заданном формате
	*  datadatetime(1511110001,true) / datadatetime('1511110001',true) вернет абсолютную заданную дату
	*  datadatetime(1511110001,'...') вернет заданную дату в прописанном ... формате
	*  datadatetime(['2018','12','21']) разбирает массив год/месяц/день/час/мин/сек и возвращает в абсолютном формате
	*  datadatetime('21.12.1984') разберет строку по заданному формату и вернет в абсолютном формате
	*  
	*  в примерах значения '', '0', 0, false, null - дадут одинаковый результат
	*  значения true, '1', 1 - также дадут одинаковый между собой результат
	*  
	*  на выходе отдает готовую строку
	*/
	
	global $lang;
	
	$date = [
		'year' => '',
		'month' => '',
		'day' => '',
		'hour' => '',
		'minute' => '',
		'second' => '',
		'absolute' => '',
		'data' => '',
	];
	
	// $rules[0] - массив разрешенных входящих переменных
	// $rules[1] - массив соответствий в формате даты php
	// $rules[2] - массив текстовых переменных
	// $rules[3] - массив минимальных значений: по-умолчанию, длина, добавление
	// $rules[4] - формат, переданный в функцию
	$rules = [
		[
			'y', 'yy', 'Y', 'YY', 'ya', 'yya',
			'm', 'mm', 'M', 'MM', 'ma', 'mma',
			'd', 'dd', 'D', 'DD', 'da', 'dda',
			'h', 'hh', 'H', 'HH', 'hour',
			'i', 'ii', 'min',
			's', 'ss', 'sec',
			'w', 'ww', 'W', 'WW'
		],
		[
			'y', 'Y', 'y', 'Y', 'y', 'Y',
			'n', 'm', 'm', 'm', 'n', 'm',
			'j', 'd', 'j', 'j', 'j', 'd',
			'g a', 'h A', 'G', 'H', 'H',
			'i', 'i', 'i',
			's', 's', 's',
			(empty($lang -> datetime -> firstday) || $lang -> datetime -> firstday == '7') ? 'w' : 'N',
			(empty($lang -> datetime -> firstday) || $lang -> datetime -> firstday == '7') ? 'w' : 'N',
			(empty($lang -> datetime -> firstday) || $lang -> datetime -> firstday == '7') ? 'w' : 'N',
			(empty($lang -> datetime -> firstday) || $lang -> datetime -> firstday == '7') ? 'w' : 'N'
		],
		[
			'Y', 'YY', 'M', 'MM', 'D', 'DD', 'W', 'WW',
			'ya', 'yya', 'ma', 'mma', 'da', 'dda'
		],
		[
			'year' =>   ['1970', 4, 9999, '19'],
			'month' =>  ['01', 2, 12, '0'],
			'day' =>    ['01', 2, 31, '0'],
			'hour' =>   ['00', 2, 23, '0'],
			'minute' => ['00', 2, 59, '0'],
			'second' => ['00', 2, 59, '0']
		],
		$format
	];
	
	if (!$format && !$convert) { $convert = true; }
	
	$format = str_replace(
		array_map(
			function($i){
				return '{' . $i . '}';
			},
			$rules[0]
		),
		$rules[1],
		($format && $format !== true) ? $format : $lang -> datetime -> format
	);
	
	if (!$target || $target === true) {
		
		if (!$format) {
			return date('U');
		} elseif ($rules[4]) {
			$date['data'] = getdate();
			$date = [
				'year' => $date['data']['year'],
				'month' => $date['data']['mon'],
				'day' => $date['data']['mday'],
				'hour' => $date['data']['hours'],
				'minute' => $date['data']['minutes'],
				'second' => $date['data']['seconds'],
				'absolute' => $target,
				'data' => '',
			];
			$convert = $rules[4];
		} else {
			return date($format);
		}
		
	} elseif (is_numeric($target)) {
		
		if (!$convert) {
			
			$date['data'] = getdate($target);
			$date = [
				'year' => $date['data']['year'],
				'month' => $date['data']['mon'],
				'day' => $date['data']['mday'],
				'hour' => $date['data']['hours'],
				'minute' => $date['data']['minutes'],
				'second' => $date['data']['seconds'],
				'absolute' => $target,
				'data' => '',
			];
			$convert = $rules[4];
			//print_r($date);
			//return date('U', $target);
			
		} else {
			return date($format, $target);
		}
		
	} elseif (is_array($target)) {
		
		$date['data'] = array_keys($date);
		
		foreach ($target as $key => $item) {
			$date[ $date['data'][$key] ] = $item;
		}
		
		$date['data'] = '';
		
	} elseif (is_string($target)) {
		
		$date = array_intersect_key(date_parse_from_format($format, $target), $date);
		
	}
	
	// новые условия
	if ($convert === 'array') {
		
		// относительное время - сколько дней, часов, минут, секунд (например, 2 дня, 1 час)
		
		$date['data'] = (int) $date['absolute'];
		$date['year'] = floor($date['data'] / TIME_YEAR); $date['data'] = $date['data'] - $date['year'] * TIME_YEAR;
		$date['month'] = floor($date['data'] / TIME_MONTH); $date['data'] = $date['data'] - $date['month'] * TIME_MONTH;
		$date['day'] = floor($date['data'] / TIME_DAY); $date['data'] = $date['data'] - $date['day'] * TIME_DAY;
		$date['hour'] = floor($date['data'] / TIME_HOUR); $date['data'] = $date['data'] - $date['hour'] * TIME_HOUR;
		$date['minute'] = floor($date['data'] / TIME_MINUTE); $date['data'] = $date['data'] - $date['minute'] * TIME_MINUTE;
		$date['second'] = $date['data'];
		unset($date['data']);
		return $date;
	}
	if ($convert === 'absolute') {
		
		// абсолютное время - общее целое число дней, общее целое число часов и т.д. (например, 2 дня, 49 часов)
		
		/*
		1 min = 60 sec
		1 hour = 60 min = 3600 sec
		1 day = 24 hours = 1440 min = 86400 sec
		1 week = 7 days = 168 hours = 10080 min = 604800 sec
		1 month ~ 30 days ~ 720 hours ~ 43200 min ~ 2592000 sec
		1 year = 12 month ~ 365 days ~ 8760 hours ~ 525600 min ~ 31536000 sec
		1 year = 12 month = 365,25 days = 8766 hours = 525960 min = 31557600 sec
		*/
		
		//$date['data'] = 1271602;
		$date['data'] = (int) $date['absolute'];
		$date['year'] = floor($date['data'] / TIME_YEAR);
		$date['month'] = floor($date['data'] / TIME_MONTH);
		$date['day'] = floor($date['data'] / TIME_DAY);
		$date['hour'] = floor($date['data'] / TIME_HOUR);
		$date['minute'] = floor($date['data'] / TIME_MINUTE);
		$date['second'] = $date['data'];
		unset($date['data']);
		return $date;
	}
	// конец новых условий
	
	foreach($rules[3] as $k => $i) {
		
		if (
			!$date[$k] ||
			(int) $date[$k] < 0 ||
			(int) $date[$k] > $i[2] ||
			strlen($date[$k]) > $i[1]
		) {
			$date[$k] = $i[0];
		} elseif (strlen($date[$k]) < $i[1]) {
			$date[$k] = $i[3] . $date[$k];
		}
		
	}

	//print_r($date);
	
	$date['absolute'] = strtotime($date['year'] . ':' . $date['month'] . ':' . $date['day'] . ' ' . $date['hour'] . ':' . $date['minute'] . ':' . $date['second']);
	
	/*
	// а здесь - парсим временную зону и делаем смещение по времени, но это на самом деле не нужно
	$date['data'] = [
		(substr(date('P'), 0, 1) === '-') ? false : true,
		(int) substr(date('P'), 1, 2),
		(int) substr(date('P'), 4, 2)
	];
	
	if ($date['data'][0] === true) {
		$date['absolute'] = $date['absolute'] + ($date['data'][1] * 60 + $date['data'][2]) * 60;
	} else {
		$date['absolute'] = $date['absolute'] - ($date['data'][1] * 60 + $date['data'][2]) * 60;
	}
	*/
	
	if ($convert === true) {
		return (int) $date['absolute'];
	}
	
	// преобразование времени в формат $lang -> datetime -> format
	
	preg_match_all('/\{([\w\:]+)?\}/', $convert, $date['data']);
	
	$date['data'][0] = [
		'original' => '',
		'morph' => '',
		'string' => ''
	];
	
	foreach ($date['data'][1] as $i) {
		
		$date['data'][0]['original'] = $i;
		
		// доп.опция - замена пропущенных значений на пустые
		if (strpos($i, '::') !== false) {
			$i = str_replace(':::', ':0:0:', $i);
			$i = str_replace('::', ':0:', $i);
		}
		
		$date['data'][0]['morph'] = datasplit($i, ':');
		
		$i = array_shift($date['data'][0]['morph']);
		$k = array_search($i, $rules[0]);
		
		if (in_array($i, $rules[0])) {
			
			$date['data'][0]['string'] = date($rules[1][$k], $date['absolute']);
			
			if (in_array($i, $rules[2])) {
			//if (count($date['data'][0]['morph'])) {
			// это старый кусок кода, заточенный на то, чтобы выводить дату в текстовом виде
			// автоматом, когда указываются параметры морфинга
			// однако, на самом деле вывод даты в текстовом виде должен быть только при
			// определенных переменных: Y/YY для года, M/MM для месяца, D/DD для дня, W/WW для дня недели
				
				if (
					!isset($date['data'][0]['morph'][2]) ||
					!$date['data'][0]['morph'][2] ||
					!isset($date['data'][0]['morph'][3]) ||
					!$date['data'][0]['morph'][3]
				) {
					
					$date['data'][0]['morph'][3] = $date['data'][0]['morph'][2];
					
					// здесь условия вывода названий месяцев
					// i: yy/mm/d и т.д.
					// $date['data'][0]['string']: 1984/12/21 и т.д.
					if (array_key_exists($i, (array)$lang -> datetime)) {
						$target = $date['data'][0]['string'];
						$current = 'datetime';
						$date['data'][0]['string'] = $lang -> $current -> $i[$target];
						$date['data'][0]['morph'][2] = false;
					} elseif (substr($i, -1, 1) === 'a') {
						$date['data'][0]['morph'][2] = 'add';
					} else {
						$date['data'][0]['morph'][2] = 'date';
					}
					// конец кода
					
				}
				
				if ($date['data'][0]['morph'][2] === 'date') {
					$date['data'][0]['string'] = datanum(
						$date['data'][0]['string'],
						$date['data'][0]['morph']
					);
				} elseif ($date['data'][0]['morph'][2] === 'add') {
					unset(
						$date['data'][0]['morph'][2],
						$date['data'][0]['morph'][3]
					);
					$date['data'][0]['string'] = datanum(
						$date['data'][0]['string'],
						'add:' . dataarraytostring($date['data'][0]['morph'], ':')
					);
				} else {
					$date['data'][0]['string'] = datamorpher(
						$date['data'][0]['string'],
						$date['data'][0]['morph']
					);
				}
				
				if ($date['data'][0]['morph'][3]) {
					$date['data'][0]['string'] = datalang(
						$date['data'][0]['string'],
						'',
						'',
						$date['data'][0]['morph'][3]
					);
				}
				
			}
			
			$convert = str_replace(
				'{' . $date['data'][0]['original'] .  '}',
				$date['data'][0]['string'],
				$convert
			);
			
		}
		
		unset($k, $i);
		
	}
	
	/*
	// а здесь - просто проверка значений
	print_r($convert);
	echo '<br><hr>';
	print_r($date);
	echo '<hr><br>';
	*/
	
	$date = $convert;
	
	//return dataarraytostring($date);
	return $date;
	
}

function dataarraytostring($arrTarget, $splitter = ' ', $keys = false) {

	/*
	*  Функция, которая преобразует объект или массив в строку
	*  на входе нужно указать:
	*    исходный объект или массив
	*    строковый разделитель, по-умолчанию - пробел
	*      если указать массив, то его первое значение будет разделителем, а последнее будет последним разделителем
	*      например, если для массива ['раз', 'два', 'три'] указать [', ', ' и '], то результат будет: 'раз, два и три'
	*    флаг, который позволяет, рабирать ли ключи в массиве
	*      например, для массивов ['a' => 1, 'b' => 2] и ['a', 'b'] при значении true результаты будут 'a: 1, b: 2' и '0: a, 1: b'
	*      а для этих же массивов, но при значении false результаты будут '1, 2' и 'a, b'
	*  
	*  на выходе отдает готовую строку
	*/
	
	if (is_string($arrTarget)) {
		return false;
	} elseif (is_object($arrTarget)) {
		$arrTarget = dataconvert($arrTarget);
	}
	
	$arrTarget = [$arrTarget, '', '', '', ''];
	if ($keys) {
		reset($arrTarget[0]);
		$arrTarget[3] .= key($arrTarget[0]);
		$arrTarget[3] .= ((is_string($keys)) ? $keys : ': ');
	}
	$arrTarget[3] .= array_shift($arrTarget[0]);
	
	if (is_array($splitter) && count($arrTarget[0])) {
		$arrTarget[4] = array_pop($splitter);
		if ($keys) {
			end($arrTarget[0]);
			$arrTarget[4] .= key($arrTarget[0]);
			$arrTarget[4] .= ((is_string($keys)) ? $keys : ': ');
		}
		$arrTarget[4] .= array_pop($arrTarget[0]);
		$splitter = array_shift($splitter);
	}
	
	if (count($arrTarget[0])) {
		foreach ($arrTarget[0] as $arrTarget[2] => $arrTarget[1]) {
			$arrTarget[3] .= $splitter;
			if ($keys) {
				$arrTarget[3] .= $arrTarget[2];
				$arrTarget[3] .= ((is_string($keys)) ? $keys : ': ');
			}
			$arrTarget[3] .= $arrTarget[1];
		}
	}
	
	if ($arrTarget[4]) {
		$arrTarget[3] .= $arrTarget[4];
	}
	
	$arrTarget = $arrTarget[3];
	
	return $arrTarget;

}

function datamorpher($target, $arrOut = false) {

	/*
	*  Функция которая склоняет существительные
	*  на входе нужно указать:
	*    исходное слово
	*    выходные параметы
	*      падеж - i/r/d/v/t/p (им/род/дат/вен/твор/пред)
	*      число и род - e/m (ед.ч. муж. род / множ.ч.) + j/s (ед.ч. жен. род / ед.ч. сред. род), если есть
	*      часть речи - sush/pril (сущ. / прилаг.), если есть
	*      если параметров нет, считается, что это инфинитив:
	*      существительное единственного числа заданного рода
	*      (мужского, если родов несколько) в именительном падеже
	*  
	*  на выходе отдает готовое значение
	*/
	
	if (!$target) {
		return false;
	} elseif (in_array('mbstring', get_loaded_extensions())) {
		$target = mb_strtolower($target); // EDITED FOR NOT PHP MODULE
	}
	
	global $morph;
	
	if (!$morph -> enable) {
		return $target;
	}
	
	if (!$arrOut) {
		$arrOut = [
			false,
			false,
			false
		];
	} elseif (!is_array($arrOut)) {
		$arrOut = datasplit($arrOut, ':');
	}
	
	if (empty($arrOut[0])) { $arrOut[0] = $morph -> declension[0]; }
	
	if (empty($arrOut[1])) {
		$arrOut[1] = 0;
	} elseif (in_array($arrOut[1], $morph -> forms)) {
		$arrOut[1] = array_search($arrOut[1], $morph -> forms);
	} else {
		$arrOut[1] = 1;
	}
	
	if (empty($arrOut[2])) { $arrOut[2] = $morph -> parts[0]; }
	
	global $dictionary;
	
	//echo $target . ' ';
	//print_r($arrOut);
	//echo '<br>';
	
	if (
		array_key_exists($target, $dictionary) &&
		(
			isset($dictionary[$target][$arrOut[0]]) ||
			isset($dictionary[$target][$arrOut[2]])
		)
	) {
		
		if (isset($dictionary[$target][$arrOut[2]][$arrOut[0]])) {
			
			if (isset($dictionary[$target][$arrOut[2]][$arrOut[0]][$arrOut[1]])) {
				return $dictionary[$target][$arrOut[2]][$arrOut[0]][$arrOut[1]];
			} else {
				return $dictionary[$target][$arrOut[2]][$arrOut[0]][0];
			}
			
		} elseif (isset($dictionary[$target][$arrOut[0]][$arrOut[1]])) {
			
			// если искомое слово содержит $arrOut[1], то значит, у него нет форм
			return $dictionary[$target][$arrOut[0]][$arrOut[1]];
			
		} else {
			
			// иначе считаем, что форм слова нет и возвращаем инфинитив
			return $dictionary[$target][$arrOut[0]][0];
			
		}
		
	} else {
		return $target;
	}
	
}

function datacrypt($str, $do = false) {

	/*
	*  Функция которая шифрует данные
	*  на входе нужно указать:
	*    исходную строку
	*    параметы шифрования
	*      false и encode - кодировать
	*      true и decode - декодировать
	*      hash - спец.параметр, сделать хэш
	*  
	*  на выходе отдает готовую строку
	*/
	
	if (!$str) {
		return false;
	}
	
	if (!$do || $do === 'encode') {
		
		$str = [
			'string' => base64_encode($str),
			'params' => [
				0,
				0,
				0,
				substr(time(), -4),
				substr(strlen($str), 0, 1),
				0
			],
			'item' => '',
			'temp' => ''
		];
		
		$str['params'][0] = substr($str['string'], 0, 1);
		
		if (substr($str['string'], -3) === '===') {
			$str['params'][1] = 3;
			$str['string'] = substr($str['string'], 0, -2);
		} elseif (substr($str['string'], -2) === '==') {
			$str['params'][1] = 2;
			$str['string'] = substr($str['string'], 0, -2);
		} elseif (substr($str['string'], -1) === '=') {
			$str['params'][1] = 1;
			$str['string'] = substr($str['string'], 0, -1);
		}
		
		$str['string'] = substr($str['string'], 1);
		$str['string'] = bin2hex($str['string']);
		
		$str['temp'] = '';
		$str['item'] = (int)$str['params'][4] * 2;
		while($str['item'] > 0) {
			$str['temp'] .= base_convert(rand(33,126), 10, 16);
			$str['item'] = $str['item'] - 1;
		}
		$str['string'] =
			$str['temp'] .
			$str['string'] .
			base_convert(substr($str['params'][3], 0, 2), 10, 16) .
			base_convert(substr($str['params'][3], -2), 10, 16);
		
		if (strlen($str['string']) % 2 !== 0) {
			$str['string'] = substr($str['string'], 0, -1) . '0' . substr($str['string'], -1);
		}
		
		$str['temp'] = strlen($str['string']) / 2;
		$str['string'] = substr($str['string'], $str['temp']) . substr($str['string'], 0, $str['temp']);
		$str['temp'] = str_split($str['string'], 2);
		
		$str['string'] = '';
		foreach($str['temp'] as $str['item']) {
			$str['item'] = base_convert($str['item'], 16, 10);
			$str['item'] = (string) $str['item'];
			if (strlen($str['item']) < 2) {
				$str['item'] = '0' . $str['item'];
			}
			if (strlen($str['item']) < 3) {
				$str['item'] = '0' . $str['item'];
			}
			$str['string'] .= $str['item'];
		}
		
		if (strlen($str['string']) % 2) {
			$str['params'][2] = 1;
			$str['string'] = '0' . $str['string'];
		} else {
			$str['params'][2] = 0;
		}
		
		$str['temp'] = str_split($str['string'], 2);
		
		$str['string'] = '';
		foreach($str['temp'] as $str['item']) {
			$str['item'] = (int) $str['item'] + 33;
			$str['string'] .= chr($str['item']);
		}
		
		$str['string'] = strrev($str['string']);
		$str['string'] = base64_encode($str['string']);
		
		$str['item'] = $str['params'][1] . $str['params'][4];
		$str['item'] = chr(((int) $str['item']) + 33);
		
		$str = $str['params'][0] . (($str['params'][2] == 0) ? 'A' : 'z') . $str['item'] . $str['string'];
		
	} elseif ($do && $do !== 'hash') {
		
		$str = [
			'string' => strrev(base64_decode(substr($str, 3))),
			'params' => [
				substr($str, 0, 1),
				0,
				substr($str, 1, 1),
				((int) ord(substr($str, 2, 1))) - 33,
				0,
				strlen(ord(substr($str, 2, 1)))
			],
			'item' => '',
			'temp' => ''
		];
		
		$str['params'][4] = ($str['params'][5] > 0) ? substr($str['params'][3], -1, 1) : 0;
		$str['params'][1] = ($str['params'][5] > 1) ? substr($str['params'][3], -2, 1) : 0;
		$str['params'][2] = ($str['params'][2] === 'A') ? 0 : 1;
		
		$str['temp'] = str_split($str['string'], 1);
		$str['string'] = '';
		foreach ($str['temp'] as &$str['item']) {
			$str['item'] = ord($str['item']);
			$str['item'] = (int) $str['item'] - 33;
			$str['item'] = (string) $str['item'];
			if (strlen($str['item']) < 2) {
				$str['item'] = '0' . $str['item'];
			}
			$str['string'] .= $str['item'];	
		}
		
		if ($str['params'][2] == 1) {
			$str['string'] = substr($str['string'], 1);	
		}
		
		$str['temp'] = str_split($str['string'], 3);
		$str['string'] = '';
		foreach($str['temp'] as $str['item']) {
			$str['item'] = (int) $str['item'];
			$str['item'] = base_convert($str['item'], 10, 16);
			if (strlen($str['item']) < 1) {
				$str['item'] = '0' . $str['item'];
			}
			if (strlen($str['item']) < 2) {
				$str['item'] = '0' . $str['item'];
			}
			$str['string'] .= $str['item'];
		}
		
		$str['temp'] = strlen($str['string']) / 2;
		$str['string'] = substr($str['string'], $str['temp']) . substr($str['string'], 0, $str['temp']);
		$str['string'] = substr($str['string'], ((int)$str['params'][4] * 4), -4);
		if (strlen($str['string']) % 2 != 0) { return false; }
		$str['string'] = hex2bin($str['string']);
		
		if ($str['params'][1] == 3) {
			$str['string'] .= '===';
		} elseif ($str['params'][1] == 2) {
			$str['string'] .= '==';
		} elseif ($str['params'][1] == 1) {
			$str['string'] .= '=';
		}
		$str = base64_decode($str['params'][0] . $str['string']);
		
	} else {
		
		$str = [
			'string' => $str,
			'code' => base64_encode($str),
			'temp' => '',
			'len' => ''
		];
		
		$str['len'] = strlen($str['string']);
		$str['len'] = floor($str['len'] / 2);
		
		$str['temp'] = strlen($str['code']);
		$str['temp'] = floor($str['temp'] / 4);
		
		$str['code'] =
			strrev(substr($str['code'], $str['temp'] * 2, $str['temp'])) . 
			substr($str['string'], 0, $str['len']) . 
			substr($str['code'], $str['temp'], $str['temp']) . 
			substr($str['string'], $str['len']);
		
		$str = strlen($str['string']) . strrev(md5($str['code']));
		
	}
	
	return $str;
	
}
?>