<?php defined('isCMS') or die;

/*
test strings:
http://0gl.ru/
http://0gl.ru/documents
http://0gl.ru/documents/about
http://0gl.ru/en/documents/about?page=1&sort="id"
http://0gl.ru/documents/about/page/1/sort/id
http://0gl.ru/documents/submenu/about
http://0gl.ru/essbgl/documents
http://0gl.ru/essbgl/documents/submenu/about

http://0gl.ru/en/documents/submenu/aertertweyrt/c?page=1&sort=%22id%22

http://0gl.ru/en/documents/submenu/aertertweyrt/?page=1&sort=%22id%22

http://0gl.ru/en/documents/submenu/aertertweyrt/1
http://0gl.ru/en/documents/submenu/aertertweyrt/?1
http://0gl.ru/en/documents/submenu/aertertweyrt/?page=1
http://0gl.ru/en/documents/submenu/aertertweyrt/?page&sort
http://0gl.ru/en/documents/submenu/aertertweyrt/all
http://0gl.ru/en/documents/submenu/aertertweyrt/c/1


http://0gl.ru/en/documents/submenu/aaertertweyrt/no/page/1/sort/id

http://0gl.ru/en/submenu/aertertweyrt/c/1

http://0gl.ru/en/documents/submenu/aertertweyrt/page/1/sort/id
http://0gl.ru/en/documents/submenu/aertertweyrt/page/1/sort/id


http://0gl.ru/en/documents/submenu/aertertweyrt/?page=1
http://0gl.ru/en/documents/submenu/aaertertweyrt/?page=1
http://0gl.ru/en/documents/submenu/aertertweyrt/?page=1&sort=%22id%22
http://0gl.ru/en/documents/submenu/aaertertweyrt/?page=1&sort=%22id%22
http://0gl.ru/en/documents/submenu/aertertweyrt/page/1/sort/id
http://0gl.ru/en/documents/submenu/aaertertweyrt/page/1/sort/id

http://0gl.ru/en/documents/submenu/aertertweyrt/?page&sort
http://0gl.ru/en/documents/submenu/aertertweyrt/?page
http://0gl.ru/en/documents/submenu/aertertweyrt/&sort
http://0gl.ru/en/documents/submenu/aertertweyrt/1/id
http://0gl.ru/en/documents/submenu/aertertweyrt/1
http://0gl.ru/en/documents/submenu/aertertweyrt/id

http://0gl.ru/en/documents/submenu/aertertweyrt/rtt/1/id/
http://0gl.ru/en/documents/submenu/aertertweyrt/rtt/1/id/0

http://0gl.ru/en/documents/submenu/aaertertweyrt/?page&sort
http://0gl.ru/en/documents/submenu/aaertertweyrt/?page
http://0gl.ru/en/documents/submenu/aaertertweyrt/&sort
http://0gl.ru/en/documents/submenu/aaertertweyrt/1/id
http://0gl.ru/en/documents/submenu/aaertertweyrt/1
http://0gl.ru/en/documents/submenu/aaertertweyrt/id

page по-умолчанию задается default или index или main
если этого page нет в папке, то выводится nopage из base

*/

$path = [];
$path = (object) array(
	'split' => '',
	'templates' => dirconnect(PATH_TEMPLATES, ['base', 'default', 'errors', 'restore']),
	'structure' => array(),
	'counter' => '',
	'current' => (object) array(
		'item' => '',
		'structure' => array(),
		'split' => array(),
		'folders' => array(),
		'params' => '',
		'article' => '',
	),
	'lang' => '',
	'template' => '',
	'types' => array(),
	'folders' => array(),
	'page' => '',
	'parameters' => array(),
);

// Этап подготовки значений массива

$path -> split = $_SERVER['REQUEST_URI'];
if (strpos($path -> split, '.php')) {
	$path -> split = str_replace('.php', '', $path -> split);
}
$path -> counter = strpos($path -> split, '?');
if ($path -> counter !== false) {
	$path -> parameters = substr($path -> split, $path -> counter + 1);
	$path -> split = substr($path -> split, 0, $path -> counter);
} else {
	$path -> counter = strpos($path -> split, '&');
	if ($path -> counter !== false) {
		$path -> parameters = substr($path -> split, $path -> counter + 1);
		$path -> split = substr($path -> split, 0, $path -> counter);
	}
}
$path -> split = preg_split('/[\/\\\]/', $path -> split, null, PREG_SPLIT_NO_EMPTY);
$path -> counter = '';

// 1й этап проверки - отделяем язык и шаблон

// старая версия, без учета админки:
/*
foreach ($path -> split as $key => $item) {
	
	// 1я проверка
	// является ли первая часть языком
	// проверка идет только по первому ключу
	if ($key == 0 && in_array($item, $template -> langs)) {
		$path -> lang = $item;
		unset($path -> split[$key]);
	}
	
	// 2я проверка
	// является ли первая или вторая часть названием шаблона
	// проверка идет только по первым двум ключам и если название шаблона еще не занято
	elseif (($key == 0 || $key == 1) && in_array($item, $path -> templates) && !$path -> template) {
		$path -> template = $item;
		unset($path -> split[$key]);
		break;
	}
	
	else {
		break;
	}
	
}
*/

// новая версия, с учетом админки:

// 1я проверка
// является ли первая часть языком
// проверка идет только первому ключу, если вторым ключом не задан шаблон админки
// и только по второму ключу, если первым ключом задан шаблон админки
if (in_array($path -> split[0], $template -> langs) && $path -> split[1] !== NAME_ADMINISTRATOR) {
	$path -> lang = $path -> split[0];
	unset($path -> split[0]);
} elseif (in_array($path -> split[1], $template -> langs) && $path -> split[0] === NAME_ADMINISTRATOR) {
	$path -> lang = $path -> split[1];
	unset($path -> split[1]);
}

// 2я проверка
// является ли первая или вторая часть названием шаблона
// проверка идет только по первым двум ключам и если название шаблона еще не занято
foreach ($path -> split as $key => $item) {
	if (($key === 0 || $key === 1) && in_array($item, $path -> templates) && !$path -> template) {
		$path -> template = $item;
		unset($path -> split[$key]);
		break;
	}
}

// 2й этап проверки - отделяем папки и файлы

// создаем две функции:
// - очищения элементов структуры от типов
// - рекурсию на пробег по структуре

if (!function_exists('routerStructureClear')) {
	function routerStructureClear($target) {
		
		// Маленькая функция
		// так как у нас структура меню может содержать типы, указываемые через знак '|',
		// то делаем проверку на содержание типа в строке и очищаем его
		
		$clear = [];
		
		if (strpos($target, '|') !== false) {
			$clear = [
				'clear' => substr($target, 0, strpos($target, '|')),
				'type' => substr($target, strpos($target, '|') + 1)
			];
		} else {
			$clear = [
				'clear' => $target,
				'type' => ''
			];
		}
		
		unset($target);
		return $clear;
		
	}
}

if (!function_exists('routerStructureRemap')) {
	function routerStructureRemap($target, $parent = '') {
		
		// Рекурсивная функция
		// пробегает по заданному массиву и разделяет его на папки и элементы
		$arr = [];
		
		// пробегаем по заданной части структуры
		foreach ($target as $key => $item) {
			
			// чистим ключ
			$key = routerStructureClear($key);
			
			// делаем проверку, что ключ не является внешней ссылкой
			if ($key['type'] !== 'url') {
				
				// если элемент - массив (т.е. папка)
				if (is_array($item)) {
					
					// если этот элемент - группа
					// или он имеет название дома,
					// то запускаем рекурсию на вложенные элементы
					if (
						$key['type'] === 'group' ||
						$key['clear'] === 'home'
					) {
						//$arr = array_merge($arr, routerStructureRemap($item));
						$arr = array_merge(
							$arr,
							routerStructureRemap($item, $key['clear'] . '|' . $key['type'])
						);
						//$arr[] = routerStructureRemap($item);
					// в других случаях этот элемент - папка
					// важно - если были даны другие значения ключей,
					// они не просто так были даны папке, а не файлу
					// значит, было нужно сделать, чтобы структура имела этот уровень вложенности
					} else {
						$arr[$key['clear']] = ['folder', (($parent) ? $parent . ':' : '') . $key['clear'] . (($key['type']) ? '|' . $key['type'] : '')];
					}
					
				// если элемент - файл
				} else {
					
					// чистим элемент
					$item = routerStructureClear($item);
					
					// а вот здесь, в отличие от папки,
					// запускаем проверку на те типы, которые не дают отдельного файла
					if (
						$item['type'] !== 'none' &&
						$item['type'] !== 'hash' &&
						$item['type'] !== 'action' &&
						$item['type'] !== 'home'
					) {
						$arr[$item['clear']] = ['file', $item['type']];
					}
					
				}
			}
			
		}
		
		unset($target, $key, $item);
		return $arr;
		
	}
}

$path -> current -> structure = $template -> structure;

foreach ($path -> split as $key => $item) {
	
	if (!empty($path -> folders)) {
		
		// если в массиве папок уже что-то есть,
		// то мы берем последнее значение,
		// узнаем по нему оригинальный пункт структуры
		// и делаем заполнение уже по этому уровню структуры
		// 
		// !!! здесь нужно пояснение - из-за параметров group и home в структуре
		// вложенные пункты могут реально оказаться наверху
		// чтобы убрать эту путаницу, мы вносим спецсимвол двоеточие (:),
		// разделяющий уровни вложения
		// но его нужно обнаружить и разобрать
		
		// вот здесь мы узнаем оригинальный пункт структуры
		$path -> current -> item = $path -> structure[
			$path -> folders[
				count($path -> folders) - 1
			]
		][1];
		
		// вот здесь мы разбираем его, если в нем есть двоеточие
		// и разбиваем на отдельные элементы, записывая в массив оригинальных папок
		if (strpos($path -> current -> item, ':') !== false) {
			$path -> current -> split = explode(':', $path -> current -> item);
			//$c = count($path -> current -> folders);
			
			foreach ($path -> current -> split as $k => $i) {
				if ($i) {
					//$path -> current -> folders[$k - $c] = $i;
					$path -> current -> folders[] = $i;
				}
			}
		
		// а если двоеточия нет, но есть просто значение, то
		// добавляем это значение в массив оригинальных папок
		} elseif ($path -> current -> item) {
			$path -> current -> folders[] = $path -> current -> item;
		}
		
		// теперь мы, имея последовательность оригинальных названий папок структуры,
		// добираемся до этой структуры
		foreach ($path -> current -> folders as $i) {
			if (array_key_exists($i, $path -> current -> structure)) {
				$path -> current -> structure = $path -> current -> structure[$i];
			}
		}
		unset($i);
	}
	
	$path -> structure = routerStructureRemap($path -> current -> structure);
	
	// 3я проверка
	// является ли эта часть папкой
	
	if (array_key_exists($item, $path -> structure) && $path -> structure[$item][0] === 'folder' ) {
		
		// проверка, есть ли в массиве части структуры данный элемент
		// и является ли он папкой
		
		// !!! старая строка была такой:
		// $path -> folders[] = $item;
		// сейчас мы ее закомментировали, чтобы перенести вниз
		
		// добавляем тип папки в массив типов $path -> types[name] = type
		// почему добавляем только здесь? потому что если элемента нет в структуре, то и тип ему не назначен!
		// очевидно, есть какая-то ошибка или это требует цикл, но тип для папок почему-то сохраняется вместе с названием !!!
		// поэтому мы его здесь чистим, а в дальнейшем нужно разобраться с этим беспределом
		// возможно, это происходит оттого, что в самом начале не задана структура $path -> structure !!!!!!!!!!!!!!!!!!
		//$path -> types[$item] = $path -> structure[$item][1];
		$path -> types[$item] = substr($path -> structure[$item][1], strrpos($path -> structure[$item][1], '|') + 1);
		
		unset($path -> split[$key]);
		
		if (strpos($path -> structure[$item][1], '|params') !== false) {
			$path -> current -> params = true;
		} elseif (strpos($path -> structure[$item][1], '|article') !== false) {
			$path -> current -> params = true;
			$path -> current -> article = true;
		} else {
			$path -> current -> params = false;
		}

		// !!! здесь новая строка с новыми условиями:
		if ($path -> current -> article === true) {
			$path -> page = $item;
			break;
		} else {
			$path -> folders[] = $item;
		}
		
	} elseif (array_key_exists($item, $path -> structure) && $path -> structure[$item][0] === 'file' ) {
		
		// проверка, есть ли в массиве части структуры данный элемент
		// и является ли он файлом
		
		$path -> page = $item;
		
		// добавляем тип файла в массив типов $path -> types[name] = type
		// почему добавляем только здесь? потому что если элемента нет в структуре, то и тип ему не назначен!
		$path -> types[$item] = $path -> structure[$item][1];
		
		unset($path -> split[$key]);
		if ($path -> structure[$item][1] === 'params') {
			$path -> current -> params = true;
		} elseif ($path -> structure[$item][1] === 'article') {
			$path -> current -> params = true;
			$path -> current -> article = true;
		} elseif (!empty($path -> split)) {
			error('404', $template -> lang);
		} else {
			$path -> current -> params = false;
		}
		break;
	
	} elseif (empty($path -> current -> params)) {
		
		// проверка, если в массиве части структуры данного элемента нет
		// если параметры для предыдущего элемента запрещены...
		
		if (count($path -> split) > 1) {
			// и если это не последний элемент, то это папка
			$path -> folders[] = $item;
			unset($path -> split[$key], $count);
		} else {
			// иначе, это файл
			$path -> page = $item;
			unset($path -> split[$key], $count);
			break;
		}
		
	} elseif (count($path -> split) % 2) {
		$path -> page = $item;
		unset($path -> split[$key]);
		break;
	} else {
		$path -> page = '';
		break;
	}
	
}

// 4я проверка
// назначение страницы

// Этот режим роутера проверяет, если название страницы пустое,
// то в качестве названия страницы задается имя последней папки,
// а имя последней папки, в свою очередь, при этом стирается.
// Это нужно, потому что в дальнейшем вся архитектура сайта
// требует не пустое название страницы

if (!$path -> page && !empty($path -> folders)) {
	$path -> page = array_pop($path -> folders);
} elseif ($path -> page === 'index') {
	$path -> page = array_pop($path -> folders);
}

// 3й этап проверки - параметры

// 5я проверка
// разбор параметров

if (!is_array($path -> parameters) && $path -> parameters) {
	
	if (strpos($path -> parameters, '&') !== false) {
		$path -> structure = explode('&', $path -> parameters);
	} else {
		$path -> structure = [$path -> parameters];
	}
	$path -> parameters = [];
	
	foreach ($path -> structure as $item) {
		if (strpos($item, '=') !== false) {
			$path -> counter = strpos($item, '=');
			$path -> parameters[dataclear(substr($item, 0, $path -> counter), 'login')] = dataclear(substr($item, $path -> counter + 1), 'urldecode');
			$path -> counter = '';
		}
	}
	
	$path -> structure = '';
	
} elseif (!$path -> parameters && $path -> current -> article) {
	$path -> parameters['article'] = '';
}

// 6я проверка
// вычленение названия статьи

if ($path -> current -> article) {
	
	$path -> current -> item = dataclear(array_shift($path -> split), 'urldecode');
	
	if ($path -> current -> item === 'all') {
		$path -> parameters['page'] = $path -> current -> item;
	} elseif ($path -> current -> item !== 'index') {
		$path -> parameters['article'] = $path -> current -> item;
	}
	
}

// 7я проверка
// формирование массива параметров

if (!empty($path -> split) && $path -> current -> params === true) {
	
	if (count($path -> split) % 2) {
		$path -> current -> item = dataclear(array_shift($path -> split), 'urldecode');
		$path -> parameters['default'] = $path -> current -> item;
	}
	
	$path -> split = array_values($path -> split);
	
	foreach ($path -> split as $key => $item) {
		$item = dataclear($item, 'urldecode');
		
		if (!($key % 2)) {
			//echo $item . ' : ' . $path -> split[$key + 1] . '<br>';
			$path -> parameters[$item] = $path -> split[$key + 1];
		}
		unset($path -> split[$key]);
		
	}
}

$path -> parameters = json_decode(json_encode($path -> parameters));

unset($path -> split, $path -> templates, $path -> counter, $path -> structure, $path -> current);

$template -> router = $path;

unset($path);

/*
echo '<pre>';
print_r($path);
echo '</pre>';
*/
//exit;

?>