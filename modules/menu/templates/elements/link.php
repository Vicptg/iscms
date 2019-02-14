<?php defined('isCMS') or die;

//$type = 'item';

if ($type === 'item') {
	//$target = $item;
	$target = moduleMenuClear($item)['clear'];
} elseif ($type === 'subitem') {
	$target = (is_array($subitem)) ? $subkey : $subitem;
} elseif ($type === 'submenu') {
	$target = $key;
}

require 'link_open.php';

?>

<a href="<?php
	
	if ($type === 'item') {
		$path = moduleMenuPath($key, $item, ($module -> settings -> onepage) ? $module -> settings -> onepage : '');
		$key = $path['key'];
		$item = $path['item'];
		unset($path);
		$target = $item;
	} elseif ($type === 'subitem') {
		$path = moduleMenuPath($subkey, $subitem, ($module -> settings -> onepage) ? $module -> settings -> onepage : '');
		$subkey = $path['key'];
		$subitem = $path['item'];
		unset($path);
		$target = (is_array($subitem)) ? $subkey : $subitem;
	} elseif ($type === 'submenu') {
		$path = moduleMenuPath($key, $item, ($module -> settings -> onepage) ? $module -> settings -> onepage : '');
		$key = $path['key'];
		$item = $path['item'];
		unset($path);
		$target = $key;
	}
	
?>" class="<?php
	
	echo $module -> param . '_link ' . $module -> param . '_link__' . $type . ' ';
	
	if (
		$target === $template -> router -> page ||
		(!empty($template -> router -> parameters -> article) && $target === $template -> router -> parameters -> article)
	) {
		echo $module -> param . '_link__active' . ' ';
	}
	
	if ($module -> settings -> bootstrap) {
		if ($type === 'item') {
			echo 'nav-link' . ' ';
		} elseif ($type === 'subitem') {
			echo 'dropdown-item' . ' ';
		} elseif ($type === 'submenu') {
			echo 'nav-link dropdown-toggle' . ' ';
		}
	}
	
	if ($module -> settings -> classes -> link && is_string($module -> settings -> classes -> link) && !isset($module -> settings -> classes -> link -> $type)) {
		echo $module -> settings -> classes -> link . ' ';
	}
	if (!empty($module -> settings -> classes -> link -> $type) && is_string($module -> settings -> classes -> link -> $type)) {
		echo $module -> settings -> classes -> link -> $type . ' ';
	}
	
	// установка класса для ссылки на главную страницу
	/*
	if ($module -> settings -> classes -> link && is_string($module -> settings -> classes -> link) && !isset($module -> settings -> classes -> link -> $type)) {
		echo $module -> settings -> classes -> link . ' ';
	}
	*/
	
	// перенос классов с элемента li
	if (!isset($module -> var[$type]['li'])) {
		echo $module -> param . '_item item_' . $target . ' ';
		
		if ($module -> settings -> bootstrap && ($type === 'item' || $type === 'submenu')) {
			echo 'nav-item' . ' ';
		}
		if ($module -> settings -> bootstrap && $type === 'submenu') {
			echo 'dropdown' . ' ';
		}
		if ($type === 'submenu' && $module -> settings -> classes -> submenu) {
			echo $module -> settings -> classes -> submenu . ' ';
		}
		
		if ($module -> settings -> classes -> li && is_string($module -> settings -> classes -> li) && !isset($module -> settings -> classes -> li -> $type)) {
			echo $module -> settings -> classes -> li . ' ';
		}
		if (!empty($module -> settings -> classes -> li -> $type) && is_string($module -> settings -> classes -> li -> $type)) {
			echo $module -> settings -> classes -> li -> $type . ' ';
		}
		if (
			$target === $template -> router -> page ||
			(!empty($template -> router -> parameters -> article) && $target === $template -> router -> parameters -> article) ||
			($target === 'home' && !$template -> router -> page && $module -> settings -> elements -> homeactive)
		) {
			echo $module -> param . '_item__active' . ' ';
			if ($module -> settings -> bootstrap) { echo 'active' . ' '; }
			if ($module -> settings -> classes -> active) { echo $module -> settings -> classes -> active . ' '; }
		}
		if (
			$target === 'home' &&
			$module -> settings -> classes -> home
		) {
			echo $module -> settings -> classes -> home . ' ';
		}
		
	}
	
?>"<?php
	
	if (is_array($module -> settings -> modal) && in_array($target, $module -> settings -> modal)) {
		
		echo 'data-toggle="modal"' . ' ';
		echo 'data-target="#' . $target . '"' . ' ';
		
	}
	if ($type === 'submenu' && $module -> settings -> bootstrap) {
		
		echo 'data-toggle="dropdown"' . ' ';
		echo 'role="button"' . ' ';
		echo 'aria-haspopup="true"' . ' ';
		echo 'aria-expanded="false"' . ' ';
		
	}
	
?>>

<?php

require 'link_inside.php';

if ($type === 'submenu') {
	require 'submenu.php';
}

require 'link_close.php';

?>