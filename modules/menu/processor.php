<?php

if (!function_exists('moduleMenuClear')) {
	function moduleMenuClear($target) {
		
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
		
		return $clear;
		
	}
}

if (!function_exists('moduleMenuExtract')) {
	function moduleMenuExtract($arrTarget) {
		
		//
		// Маленькая рекурсивная функция
		// распаковывает все элементы меню $arrTarget
		// и возвращает готовый раскрытый массив $arrCreate
		//
		
		$arrCreate = [];
		
		if (!is_array($arrTarget)) {
			return [];
		};
		
		foreach ($arrTarget as $key => $item) {
			if (is_array($item)) {
				$arrSecond = moduleMenuExtract($item);
				$arrCreate = array_merge($arrCreate, $arrSecond);
				unset($arrSecond);
			} else {
				$arrCreate[$key] = $item;
			}
		}
		unset($key, $item);
		
		return $arrCreate;
		
	}
}

if (!function_exists('moduleMenuDisable')) {
	function moduleMenuDisable($arrTarget, $arrDisable) {
		
		// 
		// Рекурсивная функция, перебирает массив любой глубины
		// при создании проверяется, была ли такая же функция уже зарегистрирована - это очень важно!
		// 
		// Задача функции - очистить структуру меню от заблокированных пунктов
		// 
		// функция перебирает значения и ключи внутри массива arrTarget
		// сравивает их с содержимым массива arrDisable
		// в случае успеха - удаляет значение из массива arrTarget
		// иначе - продолжает поиск
		// 
		// в результате возвращает готовый очищенный массив arrTarget
		// 
		
		foreach ($arrTarget as $key => $item) {
			
			// так как у нас структура меню может содержать типы, указываемые через знак '|',
			// то создаем копии названий ключей или элементов и проверку делаем по ним
			
			//$compare = (is_array($item)) ? moduleMenuClear($key) : moduleMenuClear($item);
			
			if (!is_array($arrDisable)) {
				return $arrTarget;
			}
			
			if (is_array($item)) {
				$compare = moduleMenuClear($key)['clear'];
			} else {
				$compare = moduleMenuClear($item)['clear'];
			}
			
			if (in_array($compare, $arrDisable) !== false) {
				unset($arrTarget[$key]);
			} elseif (is_array($item)) {
				$arrTarget[$key] = moduleMenuDisable($item, $arrDisable);
			}
			
		}
		unset($compare, $key, $item);
		return $arrTarget;
	}
}

if (!function_exists('moduleMenuRemap')) {
	function moduleMenuRemap($arrTarget, $parent) {
		
		// 
		// Рекурсивная функция, перебирает массив любой глубины
		// при создании проверяется, была ли такая же функция уже зарегистрирована - это очень важно!
		// 
		// Задача функции - переименовать ключи структуры вида '0' в вид 'parent:0'
		// 
		// функция перебирает значения и ключи внутри массива arrTarget
		// к каждому ключу, если он не является элементом первого уровня, добавляется его родитель
		// если ключ является числовым значением, то к нему добавляется сравивает их с содержимым массива
		// 
		// в результате возвращает готовый переработанный массив arrTarget
		// 
		
		foreach ($arrTarget as $key => $item) {
			
			// так как у нас структура меню может содержать типы, указываемые через знак '|',
			// то создаем копии названий ключей и запись подпунктов делаем по ним
			
			$clearkey = moduleMenuClear($key);
			$clearkey = ($clearkey['type'] !== 'group') ? $clearkey['clear'] : '';
			
			if (is_array($item)) {
				if ($parent) {
					$arrTarget[$parent . ':' . $key] = moduleMenuRemap($item, $parent . ':' . $clearkey);
					unset($arrTarget[$key]);
				} else {
					$arrTarget[$key] = moduleMenuRemap($item, $clearkey);
				}
			} else {
				
				if ($parent) {
					$arrTarget[$parent . ':' . $key] = $item;
					unset($arrTarget[$key]);
				}
			}
			
		}
		unset($clearkey, $type, $key, $item);
		return $arrTarget;
	}
}

if (!function_exists('moduleMenuCustom')) {
	function moduleMenuCustom($arrTarget, $arrCustom) {
		
		// 
		// Рекурсивная функция, перебирает массив любой глубины
		// при создании проверяется, была ли такая же функция уже зарегистрирована - это очень важно!
		// 
		// Задача функции - создать структуру меню согласно заданным параметрам
		// 
		// функция перебирает значения и ключи внутри массива arrTarget
		// сравивает их с содержимым массива arrCustom
		// в случае успеха - добавляет значение в новый массив arrCreate
		// и продолжает поиск
		// 
		// в результате возвращает готовый массив arrCreate
		// 
		
		$arrCreate = [];
		$arrSearch = [];
		
		// формируем массив поиска и сравнения значения выражений
		
		foreach ($arrTarget as $key => $item) {
			
			// так как у нас структура меню может содержать типы, указываемые через знак '|',
			// то создаем копии названий ключей или элементов и проверку делаем по ним
			
			if (is_array($item)) {
				$compare = moduleMenuClear((strpos($key, ':') !== false) ? substr($key, strrpos($key, ':') + 1) : $key)['clear'];
			} elseif (strpos($key, '|url') !== false) {
				$compare = moduleMenuClear($key)['clear'];
			} else {
				$compare = moduleMenuClear($item)['clear'];
			}
			$arrSearch[$compare] = $key;
			//$arrSearch[$key] = $compare;
		}
		unset($compare, $key, $item);
		
		//print_r($arrSearch);
		
		// пробегаем по всем элементам структуры
		foreach ($arrCustom as $key => $item) {
			
			// очищаем элемент поиска и формируем спецсимвол
			$spec = '';
			if (substr($item, -1) === ':' && substr($item, -2, 1) !== ':') {
				$spec = ':';
				$item = substr($item, 0, -1);
			} elseif (substr($item, -1) === ':' && substr($item, -2, 1) === ':') {
				$spec = '::';
				$item = substr($item, 0, -2);
			} elseif (substr($item, -1) === '^') {
				$spec = '^';
				$item = substr($item, 0, -1);
			}
			
			// начинаем условия проверки
			
			if (array_key_exists($item, $arrSearch)) {
				
				// если этот элемент из кастом-списка есть в структуре, является подменю, но содержит спецсимвол ^,
				// то выводим только ссылку на него, не отображая подменю
				// сохраняя при этом связь с родительским пунктом в виде 'родитель:элемент'
				// например: [ main, sub:item, sub:item, sub:sub ]
				// важно, что глубоко вложенные подменю переносятся как подменю и не раскрываются
				if ($spec === '^') {
					$arrSecond = (strpos($arrSearch[$item], ':') !== false) ? substr($arrSearch[$item], 0, strrpos($arrSearch[$item], ':')) . ':' : 0;
					$arrCreate[$arrSecond . rand()] = $item;
					unset($arrSecond);
				}
				
				// если этот элемент из кастом-списка есть в структуре, является подменю, но содержит спецсимвол двоеточие,
				// то последовательно разворачиваем все входящие в него элементы и добавляем их в подменю
				// сохраняя при этом связь с родительским пунктом в виде 'родитель:элемент'
				// например: [ main [ sub [ item, item, subsub:item ] ] ]
				// важно, что глубоко вложенные подменю переносятся как подменю и не раскрываются
				elseif ($spec === ':') {
					$arrCreate[$arrSearch[$item]] = [];
					$arrCreate[$arrSearch[$item]] = moduleMenuExtract($arrTarget[$arrSearch[$item]]);
				}
				
				// если этот элемент из кастом-списка есть в структуре, является подменю, но содержит два спецсимвола двоеточия,
				// то последовательно разворачиваем все входящие в него элементы и добавляем их на текущий уровень меню
				// сохраняя при этом связь с родительским пунктом в виде 'родитель:элемент'
				// например: [ main, sub:item, sub:item, sub:sub ]
				// важно, что глубоко вложенные подменю переносятся как подменю и не раскрываются
				elseif ($spec === '::') {
					foreach ($arrTarget[$arrSearch[$item]] as $subkey => $subitem) {
						$arrCreate[$subkey] = $subitem;
					}
					unset($subkey, $subitem);
				}
				
				// если этот элемент из кастом-списка есть в структуре и он является обычным пунктом, то добавляем его в новое меню
				// если этот элемент из кастом-списка есть в структуре, но он является подменю, то целиком переносим его в новое меню
				else {
					$arrCreate[$arrSearch[$item]] = $arrTarget[$arrSearch[$item]];
				}
				
			} else {
				
				// если этот элемент из кастом-списка не нашелся в структуре, то вызываем рекурсию
				// разворачиваем эту структуру и проверяем по-очереди каждый вложенный массив
				foreach ($arrTarget as $item) {
					if (is_array($item)) {
						$arrSecond = moduleMenuCustom($item, [$arrCustom[$key]]);
						$arrCreate = array_merge($arrCreate, $arrSecond);
						unset($arrSecond);
					}
				}
				
			}
			
		}
		
		unset($spec, $key, $item);
		return $arrCreate;
	}
}

if (!function_exists('moduleMenuPath')) {
	function moduleMenuPath($key, $item, $onepage, $print = true) {
		
		// 
		// Функция формирует пути (ссылки)
		// при создании проверяется, была ли такая же функция уже зарегистрирована - это очень важно!
		// 
		// Задача функции - создать ссылку меню согласно заданным параметрам
		// 
		// функция сравивает переданные ей значения и ключи $key, $item
		// с внутренними условиями и запускает обработку согласно им
		// 
		// в результате возвращает массив [key, item, path]
		// 
		
		global $template;
		
		$path = [];
		$path['base'] = $template -> url . '/';
		if ($template -> lang !== ROOT_LANG) {
			$path['base'] .= $template -> lang . '/';
		}
		
		if (is_array($item)) {
			$key = moduleMenuClear($key);
			$path['type'] = $key['type'];
			$key = $key['clear'];
		} elseif (strpos($key, '|url') !== false) {
			$path['url'] = dataclear($item, 'simpleurl');
			$path['type'] = 'url';
			$item = moduleMenuClear($key)['clear'];
		} else {
			$item = moduleMenuClear($item);
			$path['type'] = $item['type'];
			$item = $item['clear'];
		}
		
		$path['menu'] = '';
		
		if ($path['type'] !== 'url' && strpos($key, ':') !== false) {
			$path['explode'] = explode(':', $key);
			foreach($path['explode'] as $pathitem) {
				if ($pathitem && $pathitem !== 'home' && !is_numeric($pathitem)) {
					$path['menu'] .= htmlentities($pathitem) . '/';
				}
			}
			$key = substr($key, strrpos($key, ':') + 1);
		} elseif ($key !== 'home' && !is_numeric($key)) {
			$path['menu'] .= htmlentities($key) . '/';
		}
		
		if (!is_array($item)) {
			$path['menu'] .= htmlentities($item) . '/';
		}
		
		// условия закончились - выводим ссылку (!)
		// ну а фига там еще кучу лишних действий в шаблоне повторять,
		// тем более, что эта функция один хрен вызывается в 'a href='
		
		if ($path['type'] === 'nolink' || $path['type'] === 'group') {
			$path['link'] = 'javascript:undefined;';
			//'javascript:void(0);';
		} elseif ($path['type'] === 'none') {
			$path['link'] = '#';
			//'#" onclick="return false'; 
		} elseif ($path['type'] === 'hash') {
			$path['menu'] = substr($path['menu'], 0, strrpos(substr($path['menu'], 0, -1), '/'));
			if ($path['menu'] && substr($path['menu'], 0, -1) !== '/') { $path['menu'] .= '/'; }
			$path['link'] = $path['base'] . $path['menu'] . '#';
			$path['link'] .= (is_array($item)) ? $key : $item;
		} elseif ($path['type'] === 'action') {
			$path['link'] = '#';
			$path['link'] .= (is_array($item)) ? $key : $item;
		} elseif ($path['type'] === 'home' || $key === 'home' || $item === 'home') {
			$path['link'] = $path['base'];
		} elseif ($path['type'] === NAME_ADMINISTRATOR || $key === NAME_ADMINISTRATOR || $item === NAME_ADMINISTRATOR) {
			$path['link'] = $path['base'] . NAME_ADMINISTRATOR;
		} elseif ($path['type'] === 'url') {
			$path['link'] = $path['url'] . '" rel="nofollow';
		} elseif (
			is_array($item) &&
			(
				$onepage === true || 
				(is_array($onepage) && in_array($key, $onepage))
			)
		) {
			$path['link'] = '#' . $key;
		} elseif (
			!is_array($item) &&
			(
				$onepage === true || 
				(is_array($onepage) && in_array($item, $onepage))
			)
		) {
			$path['link'] = '#' . $item;
		} else {
			$path['link'] = $path['base'] . $path['menu'];
		}
		
		if ($print) {
			echo $path['link'];
		}
		
		return array('key' => $key, 'item' => $item, 'type' => $path['type'], 'link' => $path['link']);
		
	}
}

if (!function_exists('moduleMenuElements')) {
	function moduleMenuElements($key, $item, $nosubmenu = false) {
		
		// 
		// Функция определения типа элемента и его выборки
		// при создании проверяется, была ли такая же функция уже зарегистрирована - это очень важно!
		// 
		// Задача функции - вернуть тип элемента
		// Если элемент является пунктом меню, то возвращается значение item,
		// Если элемент является подменю, то возвращается значение submenu,
		// Если элемент является подменю и содержит тип группы, то возвращается значение item и вложения элемента
		// 
		// функция возвращает тип элемента и, в случае с группой, его вложения
		// 
		
		$element = (object) array(
			'type' => '',
			'key' => '',
			'item' => '',
			'data' => array()
		);
		
		if (is_array($item)) {
			
			$clear = moduleMenuClear((strpos($key, ':') !== false) ? substr($key, strrpos($key, ':') + 1) : $key);
			
			if ($clear['type'] === 'group') {
				// перезапуск цикла
				$i = $item;
				foreach ($i as $key => $item) {
					$clear = moduleMenuClear((strpos($key, ':') !== false) ? substr($key, strrpos($key, ':') + 1) : $key);
					$element -> data[] = moduleMenuElements($clear['clear'], $item, $nosubmenu);
				}
				$item = $i;
			}
			
			/*
			if ($nosubmenu === true) {
				$element -> type = 'item';
				$element -> key = ':';
				$element -> item = $clear['clear'];
			} elseif (is_array($nosubmenu) && in_array($clear['clear'], $nosubmenu)) {
				$element -> type = 'item';
				$element -> key = (strpos($key, ':') !== false) ? substr($key, 0, strrpos($key, ':')) . ':00' . rand() : $key;
				$element -> item = $clear['clear'];
			*/
			if (
				$nosubmenu === true ||
				(
					is_array($nosubmenu) &&
					in_array($clear['clear'], $nosubmenu)
				)
			) {
				$element -> type = 'item';
				$element -> key = ':';
				$element -> item = $clear['clear'];
			} else {
				$element -> type = 'submenu';
				$element -> key = $key;
				$element -> item = $item;
			}
			
			unset($clear);
			
		} else {
			$element -> type = 'item';
			$element -> key = $key;
			$element -> item = $item;
		}
		
		if (empty($element -> data)) {
			$element -> data = '';
		}
		
		return $element;
		
	}
}

?>