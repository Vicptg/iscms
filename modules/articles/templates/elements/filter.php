<?php defined('isCMS') or die;

// код включения фильтрации и разбора фильтров

if ($template -> router -> parameters -> default === 'filter') {
	
	$module -> var['filter'] = $template -> router -> parameters;
	
	unset($module -> var['filter'] -> default);
	//if (isset($module -> var['filter'] -> page)) unset($module -> var['filter'] -> page);
	if (isset($module -> var['filter'] -> article)) unset($module -> var['filter'] -> article);
	
}



if (isset($module -> var['filter'] -> items)) {
	$module -> var['filter_items'] = $module -> var['filter'] -> items;
	unset($module -> var['filter'] -> items);
}

if (isset($module -> var['filter'] -> page)) {
	
	if (
		isset($module -> settings -> filter -> options) &&
		!empty($module -> settings -> filter -> options -> pages)
	) {
		$module -> var['filter_page_current'] = (int) $module -> var['filter'] -> page;
		$module -> var['filter_page'] = (int) $module -> var['filter'] -> page;
		
		if (
			isset($module -> var['filter_items']) &&
			$module -> var['filter_page'] > 0
		) {
			$module -> var['filter_page'] = ($module -> var['filter_page_current'] - 1) * $module -> var['filter_items'];
		} else {
			$module -> var['filter_page'] = 0;
		}
		
	} else {
		$module -> var['filter_page_current'] = '';
		$module -> var['filter_page'] = 0;
	}
	
	unset($module -> var['filter'] -> page);
}

//print_r($template -> router);
//print_r($module -> var['filter_page']);
//print_r($module -> var['filter']);

if (
	isset($module -> var['filter']) &&
	is_object($module -> var['filter']) &&
	count($module -> var['filter'])
) {
	foreach ($module -> data as $key => $item) {
		//print_r($item[$filter_key]);
		foreach ($module -> var['filter'] as $filter_key => $filter_item) {
			
			//$item[$filter_key] = (string) $item[$filter_key];
			
			if (!isset($item[$filter_key])) {
				
				// отсутствие нужного фильтра
				
				unset($module -> data[$key]);
				
			} elseif (strpos($filter_item, ',')) {
				
				// несовпадение любого из перечисленных фильтров
				
				$filter_item = datasplit($filter_item, ',');
				
				if (
					(
						is_string($item[$filter_key]) &&
						!in_array($item[$filter_key], $filter_item)
					) || (
						is_array($item[$filter_key]) &&
						!array_intersect($item[$filter_key], $filter_item)
					)
				) {
					unset($module -> data[$key]);
				}
				
			} elseif (strpos($filter_item, '+')) {
				
				// несовпадение всех перечисленных фильтров
				
				$filter_item = [datasplit($filter_item, '+'), ''];
				
				foreach ($filter_item[0] as $filter_item[1]) {
					if (
						(is_string($item[$filter_key]) && $item[$filter_key] !== $filter_item[1]) ||
						(is_array($item[$filter_key]) && !in_array($filter_item[1], $item[$filter_key]))
					) {
						unset($module -> data[$key]);
					}
				}
				
			} elseif (strpos($filter_item, '-') !== false) {
				
				// несовпадение диапазона чисел
				
				$filter_item = [datasplit($filter_item, '-'), '', strpos($filter_item, '-'), false];
				
				if (count($filter_item[0]) === 1) {
					
					if ($filter_item[2]) {
						$filter_item[0][1] = 0;
						$filter_item[0][0] = (float) $filter_item[0][0];
					} else {
						$filter_item[0][1] = (float) $filter_item[0][0];
						$filter_item[0][0] = false;
					}
					
				} else {
					$filter_item[0][1] = (float) $filter_item[0][1];
					$filter_item[0][0] = (float) $filter_item[0][0];
				}
				
				if ($filter_item[0][0] === false) {
					$filter_item[3] = -1;
				} elseif ($filter_item[0][0] < $filter_item[0][1]) {
					$filter_item[3] = 1;
				} else {
					$filter_item[3] = 0;
				}
				
				if (is_array($item[$filter_key])) {
					
					foreach ($item[$filter_key] as $filter_item[1]) {
						$filter_item[1] = (float) $filter_item[1];
						if (
							(
								( $filter_item[3] === 1 ) && ( $filter_item[1] < $filter_item[0][0] || $filter_item[1] > $filter_item[0][1] )
							) || (
								( $filter_item[3] === 0 ) && $filter_item[1] < $filter_item[0][0]
							) || (
								( $filter_item[3] === -1 ) && $filter_item[1] > $filter_item[0][1]
							)
						) {
							unset($module -> data[$key]);
						}
					}
					
				} else {
					
					$item[$filter_key] = (float) $item[$filter_key];
					
					if (
						(
							$filter_item[3] && ( $item[$filter_key] < $filter_item[0][0] || $item[$filter_key] > $filter_item[0][1] )
						) || (
							!$filter_item[3] && $item[$filter_key] < $filter_item[0][0]
						)
					) {
						unset($module -> data[$key]);
					}
					
				}
				
			} elseif (strpos($filter_item, '*') !== false) {
				
				// несовпадение фрагмента фильтра
				
				$filter_item = [substr($filter_item, 1), '', 0];
					
				if (is_array($item[$filter_key])) {
					
					$filter_item[2] = count($item[$filter_key]);
					
					foreach ($item[$filter_key] as $filter_item[1]) {
						if (
							is_string($filter_item[1]) &&
							strpos($filter_item[1], $filter_item[0]) === false
						) {
							$filter_item[2] = $filter_item[2] - 1;
						}
					}
					
					if ($filter_item[2] < 1) {
						unset($module -> data[$key]);
					}
					
				} elseif (
					is_string($item[$filter_key]) &&
					$filter_item[0] &&
					strpos($item[$filter_key], $filter_item[0]) === false
				) {
					unset($module -> data[$key]);
				}
				/*
				// сюда бы еще вставить поиск по значениям фильтра и подстановки тех ключей,
				// значения которых совпали с шаблоном поиска
				} else {
					//print_r($filter_item[0]);
				}
				*/
				
			//} else {
			// замена на пропуск пустого фильтра
			} elseif ($filter_item !== '') {
				
				// несовпадение с единственным фильтром
				
				if (
					(is_string($item[$filter_key]) && $item[$filter_key] !== $filter_item) ||
					(is_numeric($item[$filter_key]) && (string) $item[$filter_key] !== (string) $filter_item) ||
					(is_array($item[$filter_key]) && !in_array($filter_item, $item[$filter_key]))
				) {
					unset($module -> data[$key]);
				}
				
			}
			
		}
	}
}

$module -> var['filter_count'] = count($module -> data);

if (
	!empty($module -> var['filter_items']) &&
	is_numeric($module -> var['filter_items'])
) {
	$module -> data = array_slice($module -> data, $module -> var['filter_page'], (int) $module -> var['filter_items'], true);
}

//print_r($module -> data);

?>