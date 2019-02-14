<?php

if (!function_exists('moduleArticleGet')) {
	function moduleArticleGet($data, $param, $settings) {
		
		//$file = dataloadjson($param . DIRECTORY_SEPARATOR . $data, 'article');
		//$file = dataloadjson(PATH_ARTICLES . DIRECTORY_SEPARATOR . $param . DIRECTORY_SEPARATOR . $data . '.' . $settings -> ext, 'article');
		$file = dbUse('articles_' . $param, 'select', false, $data, ['limit' => 1, 'json' => true, 'format' => 'article', 'add' => ['ext' => $settings -> ext, 'folder' => $param]]);
		
		if (!$file) {
			return;
		}
		
		foreach ($settings -> fields as $item) {
			if (!empty($item) && is_array($item)) {
				foreach ($item as $i) {
					if (!isset($file[$i])) {
						$file[$i] = '';
					}
				}
			}
		}
		unset($item, $i);
		
		if (!isset($file['name'])) {
			$file['name'] = $data;
		}
		
		if (
			!empty($file['date']) &&
			!is_numeric($file['date']) &&
			isset($settings -> dateformat)
		) {
			$file['date'] = date_parse_from_format($settings -> dateformat, $file['date']);
			$file['date'] = mktime(
				(($file['date']['hour']) ? $file['date']['hour'] : 0),
				(($file['date']['minute']) ? $file['date']['minute'] : 0),
				(($file['date']['second']) ? $file['date']['second'] : 0),
				(($file['date']['month']) ? $file['date']['month'] : 0),
				(($file['date']['day']) ? $file['date']['day'] : 0),
				(($file['date']['year']) ? ($file['date']['year']) : 0)
			);
		}
		
		unset($data, $param, $settings);
		return $file;
	}
}

if (!function_exists('moduleArticleSort')) {
	function moduleArticleSort(&$data, $sort, $param) {
		
		if (
			!isset($sort -> type) ||
			!$sort -> type ||
			$sort -> type === 'alphabet'
		) {
			
			if (
				isset($sort -> order) &&
				$sort -> order === 'desc'
			) {
				rsort($data);
			} else {
				sort($data);
			}
			
		} elseif ($sort -> type === 'numeric') {
			
			if (
				isset($sort -> order) &&
				$sort -> order === 'desc'
			) {
				rsort($data, SORT_NUMERIC);
			} else {
				sort($data, SORT_NUMERIC);
			}
			
		} elseif (
			$sort -> type === 'date' ||
			$sort -> type === 'modify'
		) {
			
			$datafromdate = [];
			
			if ($sort -> type === 'modify') {
				foreach ($data as $key => $item) {
					$datafromdate[date(filemtime( PATH_ARTICLES . DIRECTORY_SEPARATOR . $param . DIRECTORY_SEPARATOR . $item )) . $key] = $item;
				}
			} else {
				foreach ($data as $key => $item) {
					$datafromdate[date(filectime( PATH_ARTICLES . DIRECTORY_SEPARATOR . $param . DIRECTORY_SEPARATOR . $item )) . $key] = $item;
				}
			}
			
			if (
				isset($sort -> order) &&
				$sort -> order === 'desc'
			) {
				krsort($datafromdate, SORT_NUMERIC);
			} else {
				ksort($datafromdate, SORT_NUMERIC);
			}
			
			$data = array_values($datafromdate);
			unset($datafromdate);
			
		}
		
	}
}

?>