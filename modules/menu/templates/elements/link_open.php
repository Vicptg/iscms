<?php defined('isCMS') or die;

	// LI/A OPEN
	
	if (
		$module -> settings -> elements -> li === 'disable' ||
		$module -> settings -> elements -> li -> $type === 'disable' ||
		($module -> settings -> bootstrap && $type === 'subitem')
	) {
	} else {
		
		$module -> var[$type]['li'] = [
			'name' => '',
			'class' => []
		];
		
		// name
		if (
			$module -> settings -> elements -> li === 'div' ||
			$module -> settings -> elements -> li -> $type === 'div'
		) {
			$module -> var[$type]['li']['name'] = 'div';
		} else {
			$module -> var[$type]['li']['name'] = 'li';
		}
		
		// class
		$module -> var[$type]['li']['class'][] = $module -> param . '_item item_' . $target;
		if ($module -> settings -> bootstrap && ($type === 'item' || $type === 'submenu')) {
			$module -> var[$type]['li']['class'][] = 'nav-item';
		}
		if ($module -> settings -> bootstrap && $type === 'submenu') {
			$module -> var['submenu']['li']['class'][] = 'dropdown';
		}
		if ($type === 'submenu' && $module -> settings -> classes -> submenu) {
			$module -> var['submenu']['li']['class'][] = $module -> settings -> classes -> submenu;
		}
		if ($module -> settings -> classes -> li && is_string($module -> settings -> classes -> li) && !isset($module -> settings -> classes -> li -> $type)) {
			$module -> var[$type]['li']['class'][] = $module -> settings -> classes -> li;
		}
		if (!empty($module -> settings -> classes -> li -> $type) && is_string($module -> settings -> classes -> li -> $type)) {
			$module -> var[$type]['li']['class'][] = $module -> settings -> classes -> li -> $type;
		}
		if (
			$target === $template -> router -> page ||
			(!empty($template -> router -> parameters -> article) && $target === $template -> router -> parameters -> article) ||
			($target === 'home' && !$template -> router -> page && $module -> settings -> elements -> homeactive)
		) {
			$module -> var[$type]['li']['class'][] = $module -> param . '_item__active';
			if ($module -> settings -> bootstrap) { $module -> var[$type]['li']['class'][] = 'active'; }
			if ($module -> settings -> classes -> active) { $module -> var[$type]['li']['class'][] = $module -> settings -> classes -> active; }
		}
		if (
			$target === 'home' &&
			$module -> settings -> classes -> home
		) {
			$module -> var[$type]['li']['class'][] = $module -> settings -> classes -> home;
		}
		
		$module -> var[$type]['li'] = new htmlElement($module -> var[$type]['li']['name'], $module -> var[$type]['li']['class']);
		
	}
	
	// WRAPPER OPEN
	
	if ($module -> settings -> elements -> wrapper) {
		
		$module -> var[$type]['wrapper'] = [];
		
		$module -> var[$type]['wrapper'][] = $module -> param . '_wrapper';
		if ($module -> settings -> classes -> wrapper) { $module -> var[$type]['wrapper'][] = $module -> settings -> classes -> wrapper; }
		
		$module -> var[$type]['wrapper'] = new htmlElement('div', $module -> var[$type]['wrapper']);
		
	}
	
?>