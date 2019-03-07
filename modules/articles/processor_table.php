<?php

if (
	isset($module -> settings -> keys) &&
	(
		is_array($module -> settings -> keys) ||
		is_bool($module -> settings -> keys)
	)
) {
	$module -> var['keys'] = $module -> settings -> keys;
} else {
	$module -> var['keys'] = true;
}

$module -> var['table'] = dataloadcsv(
	PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> param,
	(object) array(
		'keys' => $module -> var['keys'],
		'alias' => (isset($module -> settings -> alias) && $module -> settings -> alias !== false) ? $module -> settings -> alias : ''
	)
);

$module -> var['item_count'] = count($module -> var['table'] -> data);

$module -> var['counter'] = 0;

foreach($module -> var['table'] -> data as $key => $item) {
	
	// добавляем дефолтные поля, такие как дата, имя и id-шник
	
	if (!isset($item['date'])) {
		$module -> var['table'] -> data[$key]['date'] = date(filemtime( PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> param . '.csv' ));
	}
	if (!isset($item['name'])) {
		$module -> var['table'] -> data[$key]['name'] = $key;
		$item['name'] = $key;
	}
	if (!isset($item['id'])) {
		$module -> var['table'] -> data[$key]['id'] = $module -> var['counter'];
	}
	
	// разбираем поля, заданные в настройках, по типу
	
	if (
		!empty($module -> settings -> fields) &&
		is_object($module -> settings -> fields)
	) {
		foreach ($module -> settings -> fields as $field_key => $field) {
			if (
				is_array($field) &&
				count($field)
			) {
				foreach ($field as $field_item) {
					
					// здесь содержатся различные условия обработки полей
					// типы заданы через $field_key,
					// название полей задаются в $field_item
					// искомое поле будет задано как $item[$field_item]
					// оригинальная ссылка будет $module -> var['table'] -> data[$key][$field_item]
					
					// условия для полей с типом "массив" (array)
					// в этих полях мы превращаем строку или объект в массив значений
					if ($field_key === 'array') {
						if (is_string($item[$field_item])) {
							$item[$field_item] = datasplit($item[$field_item]);
						} elseif (is_object($item[$field_item])) {
							$item[$field_item] = dataconvert($item[$field_item], 'reset');
						}
						$module -> var['table'] -> data[$key][$field_item] = $item[$field_item];
					}
					
					// условия для полей с типом "число" (numeric)
					// в этих полях мы превращаем строку в число
					if ($field_key === 'numeric') {
						$item[$field_item] = datanum($item[$field_item]);
						$module -> var['table'] -> data[$key][$field_item] = $item[$field_item];
					}
					
					// условия для полей с типом "дата" (date)
					// в этих полях мы распознаем дату и переводим ее в общий формат
					if ($field_key === 'date') {
						if (!empty($module -> settings -> dateformat)) {
							$module -> var['dateformat'] = $module -> settings -> dateformat;
						} else {
							$module -> var['dateformat'] = $lang -> datetime -> format;
						}
						$module -> var['table'] -> data[$key][$field_item] = datadatetime($item[$field_item], $module -> var['dateformat'], true);
					}
					
					// условия для полей с типом "папка" (folder)
					// в эти поля мы записываем значение $key
					if ($field_key === 'folder') {
						$module -> var['table'] -> data[$key][$field_item] = $key;
					}
					
					// условия для полей с типом "галерея" (gallery)
					// в этих полях перечисляются изображения, как в массиве
					if (
						$field_key === 'gallery' &&
						is_string($item[$field_item]) &&
						(
							strpos($item[$field_item], '/') !== false ||
							strpos($item[$field_item], '\\') !== false ||
							$item[$field_item] === ''
						)
					) {
						
						if (
							substr($item[$field_item], 0, 1) === '/' ||
							substr($item[$field_item], 0, 1) === '\\'
						) {
							$item[$field_item] = substr($item[$field_item], 1);
						}
						
						if (
							substr($item[$field_item], -1) === '/' ||
							substr($item[$field_item], -1) === '\\'
						) {
							$item[$field_item] = substr($item[$field_item], 0, -1);
						}
						
						if ($item[$field_item] === '') {
							$module -> var['gallery_name'] = $module -> param . DIRECTORY_SEPARATOR . $item['name'];
						}
						
						$module -> var['imagepath'] = PATH_ARTICLES . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $module -> var['gallery_name']) . DIRECTORY_SEPARATOR;
						$module -> var['imageurl'] = $template -> url . '/' . NAME_ARTICLES . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $module -> var['gallery_name']) . '/';
						
						if (
							file_exists($module -> var['imagepath'])
						) {
							$module -> var['table'] -> data[$key][$field_item] = fileconnect($module -> var['imagepath'], ['jpg', 'JPG', 'png', 'PNG']);
							
							foreach ($module -> var['table'] -> data[$key][$field_item] as &$i) {
								$i = $module -> var['imageurl'] . $i;
							}
							unset ($i);
							
							$module -> var['gallery_image'] = reset($module -> var['table'] -> data[$key][$field_item]);
						}
						
						//echo $module -> var['gallery_image'];
						//print_r($module -> var['table'] -> data[$key][$field_item]);
						
					} elseif ($field_key === 'gallery') {
						
						if (is_string($item[$field_item])) {
							$item[$field_item] = datasplit($item[$field_item]);
						} elseif (is_object($item[$field_item])) {
							$item[$field_item] = dataconvert($item[$field_item], 'reset');
						}
						
						$module -> var['imagepaths'] = [
							$module -> param . '_gallery' . DIRECTORY_SEPARATOR . $item['name'],
							$module -> param . DIRECTORY_SEPARATOR . $item['name'] . '_gallery',
							$module -> param . DIRECTORY_SEPARATOR . 'gallery',
							$module -> param . '_gallery',
							$item['name'] . '_gallery',
							'gallery',
							$module -> param . '_images' . DIRECTORY_SEPARATOR . $item['name'],
							$module -> param . DIRECTORY_SEPARATOR . $item['name'] . '_images',
							$module -> param . DIRECTORY_SEPARATOR . $item['name'],
							$module -> param . DIRECTORY_SEPARATOR . 'images',
							$item['name'] . '_images',
							$item['name'],
							'images',
						];
						
						foreach ($module -> var['imagepaths'] as $module -> var['imagepath']) {
							
							if (
								file_exists(PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> var['imagepath'])
							) {
								
								foreach ($item[$field_item] as $module -> var['gallery_key'] => $module -> var['gallery_item']) {
									
									if (
										strpos($module -> var['gallery_item'], '.') !== false &&
										file_exists(PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> var['imagepath'] . DIRECTORY_SEPARATOR . $module -> var['gallery_item'])
									) {
										$module -> var['table'] -> data[$key][$field_item][$module -> var['gallery_key']] = $template -> url . '/' . NAME_ARTICLES . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $module -> var['imagepath']) . '/' . $module -> var['gallery_item'];
									} elseif (
										file_exists(PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> var['imagepath'] . DIRECTORY_SEPARATOR . $module -> var['gallery_item'] . '.jpg')
									) {
										$module -> var['table'] -> data[$key][$field_item][$module -> var['gallery_key']] = $template -> url . '/' . NAME_ARTICLES . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $module -> var['imagepath']) . '/' . $module -> var['gallery_item'] . '.jpg';
									} elseif (file_exists(PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> var['imagepath'] . DIRECTORY_SEPARATOR . $module -> var['gallery_item'] . '.png')) {
										$module -> var['table'] -> data[$key][$field_item][$module -> var['gallery_key']] = $template -> url . '/' . NAME_ARTICLES . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $module -> var['imagepath']) . '/' . $module -> var['gallery_item'] . '.png';
									} else {
										$module -> var['table'] -> data[$key][$field_item][$module -> var['gallery_key']] = '';
									}
									
								}
								
								break;
								$module -> var['gallery_image'] = reset($item[$field_item]);
								
							}
							
						}
						
						//по-идее, функция array_diff вернет массив, содержащий разницу исходного массива и пустого массива, а значит исходный массив без пустых элементов
						//$module -> var['table'] -> data[$key][$field_item] = array_diff($module -> var['table'] -> data[$key][$field_item], ['']);
						
					}
					
					// условия для полей с типом "изображение" (image)
					if ($field_key === 'image') {
						
						if (
							!isset($item[$field_item]) ||
							!$item[$field_item]
						) {
							$module -> var['image'] = $item['name'];
						} else {
							$module -> var['image'] = $item[$field_item];
						}
						
						$module -> var['imagepaths'] = [
							$module -> param,
							$module -> param . '_images',
							$module -> param . '_images' . DIRECTORY_SEPARATOR . $item['name'],
							$module -> param . '_gallery',
							$module -> param . '_gallery' . DIRECTORY_SEPARATOR . $item['name'],
							$module -> param . DIRECTORY_SEPARATOR . $item['name'],
							$module -> param . DIRECTORY_SEPARATOR . $item['name'] . '_images',
							$module -> param . DIRECTORY_SEPARATOR . $item['name'] . '_gallery',
							$module -> param . DIRECTORY_SEPARATOR . 'images',
							$module -> param . DIRECTORY_SEPARATOR . 'gallery',
							$item['name'],
							$item['name'] . '_images',
							$item['name'] . '_gallery',
							'images',
							'gallery'
						];
						
						foreach ($module -> var['imagepaths'] as $module -> var['imagepath']) {
							
							//echo PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> var['imagepath'] . '<br>';
							
							if (
								file_exists(PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> var['imagepath'])
							) {
								if (
									strpos($module -> var['image'], '.') !== false &&
									file_exists(PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> var['imagepath'] . DIRECTORY_SEPARATOR . $module -> var['image'])
								) {
									$module -> var['table'] -> data[$key][$field_item] = $template -> url . '/' . NAME_ARTICLES . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $module -> var['imagepath']) . '/' . $module -> var['image'];
									break;
								} elseif (
									file_exists(PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> var['imagepath'] . DIRECTORY_SEPARATOR . $module -> var['image'] . '.jpg')
								) {
									$module -> var['table'] -> data[$key][$field_item] = $template -> url . '/' . NAME_ARTICLES . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $module -> var['imagepath']) . '/' . $module -> var['image'] . '.jpg';
									break;
								} elseif (file_exists(PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> var['imagepath'] . DIRECTORY_SEPARATOR . $module -> var['image'] . '.png')) {
									$module -> var['table'] -> data[$key][$field_item] = $template -> url . '/' . NAME_ARTICLES . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $module -> var['imagepath']) . '/' . $module -> var['image'] . '.png';
									break;
								} else {
									$module -> var['table'] -> data[$key][$field_item] = '';
								}
							}
							
						}
						
						if (
							$module -> var['table'] -> data[$key][$field_item] === '' &&
							!empty($module -> var['gallery_image'])
						) {
							$module -> var['table'] -> data[$key][$field_item] = $module -> var['gallery_image'];
							unset($module -> var['gallery_image']);
						}
						
						if (
							$module -> var['table'] -> data[$key][$field_item] === '' &&
							!empty($module -> settings -> defaultimage) &&
							file_exists(PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> var['imagepath'] . DIRECTORY_SEPARATOR . $module -> settings -> defaultimage)
						) {
							$module -> var['table'] -> data[$key][$field_item] = $template -> url . '/' . NAME_ARTICLES . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $module -> var['imagepath']) . '/' . $module -> settings -> defaultimage;
						}
						
					}
					
					// условия для полей с типом "фильтр" (filter)
					// в этих полях мы формируем фильтр для последующей сортировки материалов
					// условие старое, сейчас перерабатывается
					/*
					if (
						$field_key === 'filter' &&
						$module -> settings -> filter
					) {
						
						if (is_array($item[$field_item])) {
							
							foreach ($item[$field_item] as $i) {
								$module -> var['fields'][$field_item][] = $i;
							}
							unset($i);
							
						} else {
							$module -> var['fields'][$field_item][] = $item[$field_item];
						}
						
					}
					*/
					
					
					// место для других условий
					// ...
					
				}
			}
		}
	}
	
	// убираем лишние материалы, если указано их число articles и число пропускаемых skip
	
	if (is_numeric($key)) {
		$module -> var['int'] = (int) $key;
	} else {
		$module -> var['int'] = (int) $module -> var['counter'];
	}
	
	if (
		$module -> settings -> page -> articles &&
		$module -> settings -> page -> skip
	) {
		$module -> var['ver'] = $module -> settings -> page -> articles + $module -> settings -> page -> skip;
	} else {
		$module -> var['ver'] = $module -> settings -> page -> articles;
	}
	
	if (
		(
			$module -> settings -> page -> articles &&
			(
				$module -> var['int'] < (int) $module -> settings -> page -> skip ||
				$module -> var['int'] >= (int) $module -> var['ver']
			)
		) || (
			!$module -> settings -> page -> articles &&
			$module -> settings -> page -> skip &&
			$module -> var['int'] < (int) $module -> settings -> page -> skip
		)
	) {
		unset($module -> var['table'] -> data[$key]);
	}	
	
	// новое условие для "фильтра" (filter)
	// в этих полях мы формируем фильтр для последующей сортировки материалов
	
	if (
		!empty($module -> settings -> filter) &&
		!empty($module -> settings -> filter -> fields)
	) {
		foreach ($module -> settings -> filter -> fields as $filter_key => $filter_type) {
			if (array_key_exists($filter_key, $item)) {
				if (is_array($item[$filter_key])) {
					foreach ($item[$filter_key] as $i) {
						$module -> var['filter_fields'][$filter_key][] = $i;
					}
					unset($i);
				} else {
					$module -> var['filter_fields'][$filter_key][] = $item[$filter_key];
				}
			}
		}
		//print_r($module -> var['filter_fields']);
	}
	
	$module -> var['counter']++;
	
}

if (
	is_string($module -> this) &&
	$module -> this !== 'all'
) {
	$module -> data[$module -> this] = $module -> var['table'] -> data[$module -> this];
} else {
	$module -> data = $module -> var['table'] -> data;
}

// обработка фильтра
// здесь мы удаляем все одинаковые значения
// а затем сортируем получившийся массив

if (
	isset($module -> var['filter_fields']) &&
	count($module -> var['filter_fields'])
) {
	foreach ($module -> var['filter_fields'] as &$i) {
		$i = array_unique($i);
		sort($i);
	}
	$template -> var['filter_fields'] = $module -> var['filter_fields'];
	//print_r($module -> var['filter_fields']);
}

$template -> var['item_count'] = $module -> var['item_count'];

unset($module -> var);

if (
	isset($template -> var['filter_fields']) &&
	count($template -> var['filter_fields'])
) {
	$module -> var['filter_fields'] = $template -> var['filter_fields'];
	unset($template -> var['filter_fields']);
}

if (isset($module -> settings -> sort)) {
	moduleArticleSort($module -> data, $module -> settings -> sort, $module -> param);
}

$module -> var['item_count'] = $template -> var['item_count'];

//print_r($module);
//exit;

?>