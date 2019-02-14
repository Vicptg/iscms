<?php defined('isCMS') or die;
	
	// NAV OPEN
	
	if (
		$module -> settings -> bootstrap ||
		$module -> settings -> elements -> nav
	) {
		
		$module -> var['menu']['nav'] = [];
		
		$module -> var['menu']['nav'][] = $module -> param;
		if ($module -> settings -> bootstrap) {
			$module -> var['menu']['nav'][] = 'navbar';
		}
		if ($module -> settings -> classes -> nav) {
			$module -> var['menu']['nav'][] = $module -> settings -> classes -> nav;
		}
		
		$module -> var['menu']['nav'] = new htmlElement('nav', $module -> var['menu']['nav'], $module -> param);
		
	}
	
	require 'elements.php';
	
	// DIV BODY OPEN
	
	if ($module -> settings -> elements -> body) {
		
		$module -> var['menu']['divbody'] = [
			'class' => [],
			'id' => ''
		];
		
		$module -> var['menu']['divbody']['class'] = [];
		
		$module -> var['menu']['divbody']['class'][] = $module -> param . '_body';
		if (
			$module -> settings -> collapse &&
			$module -> settings -> bootstrap
		) {
			$module -> var['menu']['divbody']['class'][] = 'collapse navbar-collapse';
		}
		if ($module -> settings -> classes -> body) {
			$module -> var['menu']['divbody']['class'][] = $module -> settings -> classes -> body;
		}
		
		if ($module -> settings -> bootstrap) {
			$module -> var['menu']['divbody']['id'] = 'navbar_';
		} elseif (!isset($module -> var['menu']['nav'])) {
			$module -> var['menu']['divbody']['id'] .= $module -> param;
		}
		
		$module -> var['menu']['divbody'] = new htmlElement('div', $module -> var['menu']['divbody']['class'], $module -> var['menu']['divbody']['id']);
		
	}
	
	// BEFORE
	
	if ($module -> settings -> elements -> before) {
		require $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> settings -> elements -> before . '.php';
	}
	
	// UL/DIV OPEN
	
	if (
		$module -> settings -> elements -> ul === 'disable' ||
		$module -> settings -> elements -> ul -> menu === 'disable'
	) {
	} else {
		
		$module -> var['menu']['ul'] = [
			'name' => '',
			'class' => [],
			'id' => '',
		];
		
		// name
		if (
			$module -> settings -> elements -> ul === 'div' ||
			$module -> settings -> elements -> ul -> menu === 'div'
		) {
			$module -> var['menu']['ul']['name'] = 'div';
		} else {
			$module -> var['menu']['ul']['name'] = 'ul';
		}
		
		// class
		if (!$module -> settings -> elements -> nav) {
			$module -> var['menu']['ul']['class'][] = $module -> param;
		}
		if ($module -> settings -> bootstrap) {
			$module -> var['menu']['ul']['class'][] = 'navbar-nav';
		}
		if (
			$module -> settings -> collapse &&
			$module -> settings -> bootstrap &&
			!$module -> settings -> elements -> body
		) {
			$module -> var['menu']['ul']['class'][] = 'collapse navbar-collapse';
		}
		
		if ($module -> settings -> classes -> ul && is_string($module -> settings -> classes -> ul)) {
			$module -> var['menu']['ul']['class'][] = $module -> settings -> classes -> ul;
		}
		if (!empty($module -> settings -> classes -> ul -> menu)) {
			$module -> var['menu']['ul']['class'][] = $module -> settings -> classes -> ul -> menu;
		}
		
		//id
		if ($module -> settings -> bootstrap) {
			$module -> var['menu']['ul']['id'] .= 'navbar_';
		} elseif (!isset($module -> var['menu']['nav']) && !isset($module -> var['menu']['divbody'])) {
			$module -> var['menu']['ul']['id'] .= $module -> param;
		}
		
		$module -> var['menu']['ul'] = new htmlElement($module -> var['menu']['ul']['name'], $module -> var['menu']['ul']['class'], $module -> var['menu']['ul']['id']);
		
	}
	
	// BEFOREITEMS
	
	if ($module -> settings -> elements -> beforeitems) {
		require $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> settings -> elements -> beforeitems . '.php';
	}
	
	// ITEM/SUBMENU
	
	foreach ($module -> data as $key => $item) {
		$element = moduleMenuElements($key, $item, $module -> settings -> nosubmenu);
		
		if (is_array($element -> data)) {
			foreach ($element -> data as $e) {
				$key = $e -> key; $item = $e -> item; $type = $e -> type;
				require 'link.php';
			}
			$key = $element -> key; $item = $element -> item; $type = $element -> type;
		} else {
			$key = $element -> key; $item = $element -> item; $type = $element -> type;
			require 'link.php';
		}
		
	}
	
	// AFTERITEMS
	
	if ($module -> settings -> elements -> afteritems) {
		require $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> settings -> elements -> afteritems . '.php';
	}
	
	// UL/DIV CLOSE
	
	if (isset($module -> var['menu']['ul'])) {
		$module -> var['menu']['ul'] -> close();
	}
	
	// AFTER
	
	if ($module -> settings -> elements -> after) {
		require $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> settings -> elements -> after . '.php';
	}
	
	// DIV BODY CLOSE
	
	if (isset($module -> var['menu']['divbody'])) {
		$module -> var['menu']['divbody'] -> close();
	}
	
	// NAV CLOSE
	
	if (isset($module -> var['menu']['nav'])) {
		$module -> var['menu']['nav'] -> close();
	}
	
	// подгрузка модальных окон
	
	if (
		$module -> settings -> bootstrap &&
		is_array($module -> settings -> modal) &&
		count($module -> settings -> modal)
	) :
		foreach ($module -> settings -> modal as $item) :
			
?>
	
	<!-- Modal -->
	<div id="<?= $item; ?>" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				
				<?php require_once $template -> curr -> inner . DIRECTORY_SEPARATOR . $item . '.php'; ?>
				
			</div>
		</div>
	</div>
	
<?php
			
		endforeach;
	endif;
	
?>