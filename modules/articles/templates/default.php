<?php defined('isCMS') or die;

//print_r($template -> router);
//print_r($module -> settings -> filter);
require 'elements' . DIRECTORY_SEPARATOR . 'type.php';

$module -> var['buttons'] = 'before';
require 'elements' . DIRECTORY_SEPARATOR . 'buttons.php';

if (
	(isset($module -> var['filter']) && is_object($module -> var['filter'])) ||
	!empty($module -> settings -> filter)
) {
	require 'elements' . DIRECTORY_SEPARATOR . 'filter.php';
}

if (
	$template -> router -> page !== $module -> param &&
	file_exists($module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> param . '_before.php')
) {
	require $module -> param . '_before.php';
}

if (
	!empty($module -> settings -> filter) &&
	$template -> router -> page === $module -> param &&
	$module -> var['type'] !== 'alone'
) {
	
	require 'elements' . DIRECTORY_SEPARATOR . 'form.php';
	
	if (
		isset($module -> settings -> filter -> options) &&
		!empty($module -> settings -> filter -> options -> ajax)
	) {
		require 'elements' . DIRECTORY_SEPARATOR . 'ajax.php';
	}
	
}

?>

<div class="articles <?= 'articles_' . $module -> param . ' ' . $module -> var['type']; ?>" id="<?= 'articles_' . $module -> param; ?>">
	
	<?php
		
		if (!count($module -> data)) :
			
			require 'elements' . DIRECTORY_SEPARATOR . 'noarticle.php';
			
		else :
			
			$module -> var['link'] = str_replace(
				$template -> router -> page,
				$module -> param,
				$template -> curr -> path
			);
			
			foreach ($module -> data as $key => $item) :
				
				require 'elements' . DIRECTORY_SEPARATOR . 'defaults.php';
				
	?>
	
	<div class="articles__item">
		
		<?php
			if (
				$template -> router -> page !== $module -> param &&
				file_exists($module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> param . '_inner.php')
			) {
				require $module -> param . '_inner.php';
			} elseif (
				$module -> var['type'] === 'alone' &&
				file_exists($module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> param . '_alone.php')
			) {
				require $module -> param . '_alone.php';
			} elseif (
				file_exists($module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> param . '_all.php')
			) {
				require $module -> param . '_all.php';
			} elseif (
				file_exists($module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> param . '_default.php')
			) {
				require $module -> param . '_default.php';
			} else {
				require 'elements' . DIRECTORY_SEPARATOR . 'noarticle.php';
			}
		?>
		
	</div>
	
	<?php
			endforeach;
		endif;
	?>
	
</div>

<?php

$module -> var['buttons'] = 'after';
require 'elements' . DIRECTORY_SEPARATOR . 'buttons.php';

if (
	$template -> router -> page !== $module -> param &&
	file_exists($module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> param . '_after.php')
) {
	require $module -> param . '_after.php';
}

?>
