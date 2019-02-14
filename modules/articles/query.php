<?php defined('isCMS') or die;

/*
//echo '<br>' . '/' . $query -> data -> param . '/all/filter/type/' . $query -> data -> type . '/matherial/' . $query -> data -> matherial;
header('Location: /' . $query -> data -> param . '/all/filter/type/' . $query -> data -> type . '/matherial/' . $query -> data -> matherial);
exit;
*/

/*
//print_r($query);
print_r( (array) $query -> data );
exit;
*/

/*

!!!!!!!!!!!!!!!!!!!

нужно переделать отправку множественных данных в фильтре
так, например, если есть множественные данные - они сохраняются массивом с цифровыми индексами
затем значение проверяется, массив ли это
и если массив, то извлекается нулевой индекс, который показывает тип массива
затем идет обработка согласно правилам - и/или/диапазон

ок, это работает

теперь нужно допилить пагинацию - т.е. переключение по страницам
1. можно реализовать просто - при щелчке формирует перенаправление на ту же страницу, но с другим параметром пагинации: header("Location: \page=" . $page . "\" . $all-data)...
нет, не совсем так - нужно просто тупо прошивать адрес в ссылку
2. можно реализовать через опцию фильтров и подгружать по аяксу (может быть так будет проще в плане скорости и оптимизации - один раз загрузил все, а потом просто динамически подгружай куски...
хотя нет, ведь аякс все равно делает запрос на сайт)


print_r( $query -> data );

*/



if (!empty($query -> data -> ajax)) {
	
	unset($query -> data -> ajax);
	
	foreach ($query -> data as $key => &$item) {
		
		if (
			is_array($item) &&
			count($item)
		) {
			$t = $item['type'];
			unset($item['type']);
		}
		
		if (is_array($item) && count($item) > 1) {
			
			if (
				$t === 'numeric' ||
				$t === 'range' ||
				$t === 'range_bootstrap' ||
				$t === 'range_jqueryui'
			) {
				$query -> data -> $key = reset($item) . '-' . end($item);
			} else {
				$m = array_shift($item);
				if ($t === 'and') {
					$s = '+';
				} else {
					$s = ',';
				}
				foreach ($item as $i) {
					$m .= $s . $i;
				}
				$query -> data -> $key = $m;
			}
			
		} elseif (is_array($item) && reset($item)) {
			$query -> data -> $key = reset($item);
		} elseif (
			$item &&
			(
				is_string($item) ||
				is_numeric($item)
			)
		) {
			$query -> data -> $key = $item;
		} else {
			unset($query -> data -> $key);
		}
		
	}
	
	//print_r( $query -> data );
	module(['articles', $query -> status], $query -> data);
	exit;
	
} else {
	
	$q = '';
	
	foreach ($query -> data as $key => $item) {
		
		if (
			is_array($item) &&
			count($item)
		) {
			$t = $item['type'];
			unset($item['type']);
		}
		
		if (is_array($item) && count($item) > 1) {
			
			if (
				$t === 'numeric' ||
				$t === 'range' ||
				$t === 'range_bootstrap' ||
				$t === 'range_jqueryui'
			) {
				$q .= $key . '/' . reset($item) . '-' . end($item) . '/';
			} else {
				$q .= $key . '/' . array_shift($item);
				if ($t === 'and') {
					$s = '+';
				} else {
					$s = ',';
				}
				foreach ($item as $i) {
					$q .= $s . $i;
				}
				$q .= '/';
			}
			
		} elseif (is_array($item) && reset($item)) {
			$q .= $key . '/' . reset($item) . '/';
		} elseif (
			$item &&
			(
				is_string($item) ||
				is_numeric($item)
			)
		) {
			$q .= $key . '/' . $item . '/';
		}
		
	}
	
	header('Location: /' . $query -> status . '/all/filter/' . $q);
	//echo '[' . $t . '] ' . $query -> status . '/all/filter/' . $q;
	exit;
}

?>