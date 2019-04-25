<?php

/* ФУНКЦИИ ДЛЯ РАБОТЫ С ФАЙЛОВОЙ СИСТЕМОЙ */

function fileconnect($dir, $ext = false){
	
	/*
	*  Функция получения списка файлов с определенным расширением
	*  на входе нужно указать путь к папке $dir и расширение $ext (или массив расширений)
	*  (false или не указывать - для получения списка всех файлов)
	*  функция вернет массив с перечнем нужных файлов
	*/
	
	if (!file_exists($dir)) {
		return;
	}
	
	$datascan = scandir($dir);
	
	foreach ($datascan as $key => $item) {
		
		if ($item === '.' || $item === '..') {
			unset($datascan[$key]);
		}
		
		$item = substr($item, strrpos($item, '.') + 1);
		
		if (is_array($ext)) {
			if (!in_array($item, $ext)) {
				unset($datascan[$key]);
			}
		} elseif ($ext !== false && $ext !== '*' && $item !== $ext) {
			unset($datascan[$key]);
		}
		
		unset($key, $item);
		
	}
	
	sort($datascan);
	
	unset($dir, $ext);
	
	return $datascan;
	
}

function dirconnect($dir, $ext){
	
	/*
	*  Функция получения списка папок
	*  на входе нужно указать путь к папке $dir
	*  и массив $ext из названий папок, которые нужно пропустить
	*  функция вернет массив с перечнем папок
	*/
	
	if (!file_exists($dir) || !is_dir($dir)) {
		return;
	}
	
	$datascan = scandir($dir);
	
	foreach ($datascan as $key => $item) {
		
		if (!is_dir($dir . DIRECTORY_SEPARATOR . $item) || $item === '.' || $item === '..') {
			unset($datascan[$key]);
		} elseif (is_array($ext)) {
			foreach ($ext as $i) {
				if ($item === $i) {
					unset($datascan[$key]);
				}
			}
			unset($i);
		} elseif ($item === $ext) {
			unset($datascan[$key]);
		}
		
		unset($key, $item);
		
	}
	
	sort($datascan);
	
	unset($dir, $ext);
	
	return $datascan;
	
}

/* ФУНКЦИИ ДЛЯ РАБОТЫ С НАСТРОЙКАМИ ЛОКАЛЬНЫХ ФАЙЛОВ */

function dataloadjson($data, $true = false){
	
	/*
	*  Функция обработки файла в формате json
	*  на входе нужно указать путь к файлу $data (с расширением!)
	*  
	*  функция примет файл и
	*    переведет его в массив, если второй параметр $true задан true
	*    переведет его в объект, если второй параметр $true задан false
	*/
	
	if (!file_exists($data)) {
		return false;
	}
	
	$data = file_get_contents($data);
	
	if ($true === 'structure') {
		$data = str_replace(['[', ']'], ['{','}'], $data);
		$data = preg_replace('/(["\}])(\s+\")/', '$1,$2', $data);
		$data = preg_replace_callback(
			'/(\"[\w|]+\"[^ :],?)/', 
			function ($matches, $i=0) {
				static $i;
				return '"' . ++$i . '" : ' . $matches[1];
			},
			$data
		);
	} elseif ($true === 'article') {
		$data = preg_replace('/([\"\'])\s{2,}/', '$1 ', $data);
		$data = preg_replace('/\s{2,}([\"\'])/', ' $1', $data);
		$data = htmlspecialchars($data, ENT_NOQUOTES);
		$data = dataclear($data, 'tags tospaces');
	}
	
	$data = preg_replace('/\s*\/\*.*?\*\/.*/', '', $data);
	$data = preg_replace('/([^\:\"\'])\s*?\/\/.*?([$\r\n])/', '$1$2', $data);
	$data = preg_replace('/\t/', '', $data);
	$data = preg_replace('/\r\n|\r|\n/', '', $data);
	
	$data = dataclear($data, '');
	
	if ($true) {
		$data = json_decode($data, true);
	} else {
		$data = json_decode($data);
	}
	
	unset($true);
	
	return $data;
	
}

function dataloadcsv($datafile, $datasettings = false){
	
	/*
	*  Функция обработки файла в формате csv
	*  на входе нужно указать путь к файлу $datafile (с названием самого файла, но без расширения!)
	*  Внимание! Файл csv должен быть в кодировке unicode / utf-8
	*  
	*  второе необязательное значение - массив настроек в формате json
	*  
	*  функция примет файл и переведет его в объект:
	*  $result ->
	*    data - массив, где хранятся данные
	*    settings - объект, где хранятся настройки
	*  
	*  настройки подключаются из файла ini с тем же названием или создаются автоматически
	*    row - число колонок
	*      если не указано, будет вычислено самостоятельно
	*    header - число строк, которые являются заголовком таблицы
	*      если не указано, то считается первая строка
	*    titlerow - колонка, которая содержит названия подразделов
	*      если не указано, то считается вторая колонка
	*    name - название или идентификатор таблицы, используется для создания класса
	*      если не указано, то ставится 'default'
	*    keys - в виде массива ["", "" ...], содержащего названия колонок
	*      если не задан, то идет циферная нумерация, начиная с нуля
	*/
	
	if (!file_exists($datafile . '.csv') || filesize($datafile . '.csv') === 0) {
		return false;
	}
	
	$data = (object) array(
		'data' => array(),
		'settings' => (object) array()
	);
	
	if (!$datasettings) {
		$data -> settings = dataloadjson($datafile . '.ini');
	} elseif (is_object($datasettings)) {
		$data -> settings = $datasettings;
	} elseif (is_string($datasettings)) {
		$data -> settings = json_decode($datasettings);
	}
	
	if (!empty($data -> settings)) {
		if (isset($data -> settings -> header)) {
			$data -> settings -> header --;
		}
		if (isset($data -> settings -> row)) {
			$data -> settings -> row --;
		}
		if (isset($data -> settings -> titlerow)) {
			$data -> settings -> titlerow --;
		}
	}
	
	if ($handle = fopen($datafile . '.csv', "r")) {
		$row = 0;
		
		while ($file = fgetcsv($handle)) {
			
			if ($row === 0) {
				$keys = $file;
			}
			
			if (
				!empty($data -> settings -> keys) &&
				(
					(
						!empty($data -> settings -> titlerow) &&
						$row == $data -> settings -> titlerow
					) || (
						empty($data -> settings -> titlerow) &&
						$row === 0
					)
				)
			) {
				unset($file);
			}
			
			if (isset($data -> settings -> alias) && is_string($data -> settings -> alias)) {
				$row = $file[ array_search($data -> settings -> alias, $keys) ];
			}
			
			$num = count($file);
			
			for ($c = 0; $c < $num; $c++) {
				if (isset($data -> settings -> keys) && is_array($data -> settings -> keys)) {
					$data -> data[$row][$data -> settings -> keys[$c]] = $file[$c];
				} elseif (isset($data -> settings -> keys) && $data -> settings -> keys === true && $row !== 0) {
					$data -> data[$row][$keys[$c]] = $file[$c];
				} else {
					$data -> data[$row][] = $file[$c];
				}
			}
			
			$row++;
		}
		fclose($handle);
	}
	
	$tsettings_default = array(
		'cols' => count($data -> data) - 1,
		'name' => 'default',
		'header' => 0,
		'row' => count($data -> data[0]),
		'titlerow' => 1
	);
	
	foreach ($tsettings_default as $key => $item) {
		if (!isset($data -> settings -> $key)) {
			//$data -> settings -> $key = $item;
			$data -> settings = (object) array_merge( (array) $data -> settings, array(
				$key => $item
			));
		}
	}
	
	unset($row, $handle, $file, $num, $c, $tsettings_default, $key, $item, $datasettings, $datafile);
	
	return $data;
	
}

function datasavecsv($arr, $datafile){
	
	/*
	*  Функция обработки массива и записи в файл в формате csv
	*  на входе нужно указать путь к файлу $datafile (с названием самого файла, но без расширения!)
	*  и массив
	*
	*  Внимание! Файл csv будет в кодировке unicode / utf-8
	*  
	*  Функция примет массив, построчно подготовит его и запишет в файл
	*/
	
	if ($handle = fopen($datafile . '.csv', "w")) {
		if (is_array($arr[0])) {
			foreach ($arr as $item) {
				fputcsv($handle, $item);
			}
			unset($item);
		} else {
			fputcsv($handle, $arr);
		}
		fclose($handle);
	} else {
		return false;
	}
	
	unset($handle, $datafile, $arr);
	
	return true;
	
}

/* БАЗОВЫЕ ФУНКЦИИ ДЛЯ ЧТЕНИЯ ЛОКАЛЬНЫХ ФАЙЛОВ */

function localOpenFile($target, $array = false) {
	
	/*
	*  Функция открывает файл $target и читает его построчно
	*  
	*  на входе нужно указать полный путь к файлу с названием и расширением
	*  второй параметр - разрешен ли вывод в массив
	*  по-умолчанию запрещен, т.е. вывод идет в строку
	*  
	*  вывод через функцию file_get_contents по сравнению с fopen+fgets+fclose
	*  оказывается быстрее при том же потреблении памяти, т.к. использует memory mapping
	*  
	*  функция вернет массив или строку
	*/
	
	if (!$array) {
		return file_get_contents($target);
	}
	
	$lines = [];
	
	$handle = fopen($target, "r");
	while(!feof($handle)) {
		$lines[] = fgets($handle);
	}
	fclose($handle);
	
	return $lines;
}

function localReadFile($target) {
	
	/*
	*  Функция открывает файл $target и читает его построчно
	*  на входе нужно указать полный путь к файлу с названием и расширением
	*  
	*  отличие от предыдущей функции в том, что эта действует через генератор
	*  и потребляет меньше оперативной памяти - размером ровно на одну строку
	*  
	*  функция возвращает текущую строку итерации
	*/
	
	$handle = fopen($target, "r");
	while(!feof($handle)) {
		yield fgets($handle);
	}
	fclose($handle);
}

function localFile($filename, $funcname = false, $values = false) {
	
	/*
	*  Универсальная функция, объединяющая две предыдущие
	*  Базовая для работы с локальными данными
	*  
	*  на входе нужно указать полный путь к файлу с названием и расширением
	*  вторым параметром - название функции-обработчика
	*  третьим параметром - дополнительные значения
	*  
	*  функция предназначена для построчной обработки больших файлов
	*  
	*  если указан второй параметр, то функция работает через генератор
	*  если второй параметр не указан, то функция работает через поток
	*  
	*  функция-обработчик должна включать одну переменную - передаваемую в функцию строку генератора
	*  если вы хотите использовать другие значения, используйте второй параметр в качестве массива
	*  
	*  пример функции-обработчика без доп.параметров и ее вызов:
	*  
	*  function test($str) {
	*    echo '<p>' . $str . </p>;
	*  }
	*  localFile($filename, 'test');
	*  
	*  пример функции-обработчика c одним доп.параметром и ее вызов:
	*  
	*  function test($str, $trim) {
	*    if ($trim) {
	*      $srt = trim($srt);
	*    }
	*    echo '<p>' . $str . </p>;
	*  }
	*  localFile($filename, 'test', 1);
	*  
	*  пример функции-обработчика c доп.параметрами в виде массива и ее вызов:
	*  
	*  function test($str, $params) {
	*    if ($params['trim']) {
	*      $srt = trim($srt);
	*    }
	*    if ($params['tags']) {
	*      $srt = '<p>' . $str . </p>;
	*    }
	*    if ($params['end']) {
	*      $srt = $srt . PHP_EOL;
	*    }
	*    echo $str;
	*  }
	*  localFile($filename, 'test', ['trim', 'tags']);
	*  
	*  примеры использования с файлом объемом 2.36 mb
	*  в скобках указана память после выполнения операции и потребляемая на операцию память
	*  
	*  $str = localFile($filename);
	*    echo '<pre>' . $str . '</pre>'; // [2.37 mb - 6.88 mb]
	*    echo '<pre>', $str, '</pre>'; //  [2.37 mb - 2.38 mb]
	*  $str = '';
	*  localFile($filename, 'ad');
	*    echo '<pre>' . $str . '</pre>'; // [2.37 mb - 6.88 mb]
	*    echo '<pre>, $str, '</pre>'; // [2.37 mb - 2.38 mb]
	*  echo '<pre>, localFile($filename, 'ec'), '</pre>'; // [120.95 kb - 133.39 kb]
	*  unset($str); // [121.17 kb]
	*  
	*  функции, использованные в примере:
	*  function ec($a) { echo $a; }
	*  function ad($a) { global $str; $str .= $a; }
	*  
	*  Обратите внимание, что использование генератора для построчной обработки
	*  существенно экономит оперативную память системы!
	*/
	
	if (!$funcname) {
		return localOpenFile($filename, $values);
	}
	
	$iterator = localReadFile($filename);
	
	foreach ($iterator as $iteration) {
		if ($values) {
			$funcname($iteration, $values);
		} else {
			$funcname($iteration);
		}
	}
	
}

?>