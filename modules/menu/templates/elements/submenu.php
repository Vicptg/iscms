<?php defined('isCMS') or die;

	// UL/DIV OPEN
	
	if (
		$module -> settings -> elements -> ul === 'disable' ||
		$module -> settings -> elements -> ul -> submenu === 'disable'
	) {
	} else {
		
		$module -> var['submenu']['ul'] = [
			'name' => '',
			'class' => []
		];
		
		// name
		if (
			$module -> settings -> bootstrap ||
			$module -> settings -> elements -> ul === 'div' ||
			$module -> settings -> elements -> ul -> submenu === 'div'
		) {
			$module -> var['submenu']['ul']['name'] = 'div';
		} else {
			$module -> var['submenu']['ul']['name'] = 'ul';
		}
		
		// class
		if ($module -> settings -> bootstrap) {
			$module -> var['submenu']['ul']['class'][] = 'dropdown-menu';
		}
		if ($module -> settings -> classes -> ul && is_string($module -> settings -> classes -> ul) && !isset($module -> settings -> classes -> ul -> submenu)) {
			$module -> var['submenu']['ul']['class'][] = $module -> settings -> classes -> ul;
		}
		if (!empty($module -> settings -> classes -> ul -> submenu) && is_string($module -> settings -> classes -> ul -> submenu)) {
			$module -> var['submenu']['ul']['class'][] = $module -> settings -> classes -> ul -> submenu;
		}
		
		$module -> var['submenu']['ul'] = new htmlElement($module -> var['submenu']['ul']['name'], $module -> var['submenu']['ul']['class']);
		
	}
	
	// SUBITEM
	
	foreach ($item as $subkey => $subitem) {
		$type = 'subitem';
		require 'link.php';
		$type = 'submenu';
		$target = $key;
	}
	
	// UL/DIV CLOSE
	
	if (isset($module -> var['submenu']['ul'])) {
		$module -> var['submenu']['ul'] -> close();
	}

?>