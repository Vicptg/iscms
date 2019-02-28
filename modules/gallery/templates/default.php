<?php defined('isCMS') or die;

$module -> var['gallery'] = [
	'tag' => 'div',
	'class' => [],
	'id' => $module -> param,
	'data' => [],
];

$module -> var['gallery']['class'][] = 'gallery';
$module -> var['gallery']['class'][] = 'gallery_' . $module -> param;

if (!empty($module -> settings -> classes)) {
	if (is_string($module -> settings -> classes)) {
		$module -> var['gallery']['class'][] = $module -> settings -> classes;
	} elseif (!empty($module -> settings -> classes -> gallery) && is_string($module -> settings -> classes -> gallery)) {
		$module -> var['gallery']['class'][] = $module -> settings -> classes -> gallery;
	}
}

$module -> var['gallery'] = new htmlElement(
	$module -> var['gallery']['tag'],
	$module -> var['gallery']['class'],
	$module -> var['gallery']['id']
);

foreach ($module -> data as $key => $item) :

?>
	
	<div
		class="
			gallery__item
			gallery_<?= $module -> param; ?>__item
			<?php
				if (!empty($module -> settings -> classes) && !empty($module -> settings -> classes -> item) && is_string($module -> settings -> classes -> item)) {
					echo $module -> settings -> classes -> item;
				}
			?>
		"
		<?php if (isset($module -> settings -> random)) : ?>
		style="
				<?php if (isset($module -> settings -> random -> rotate)) : ?>
				transform: rotate(<?= rand($module -> settings -> random -> rotate[0], $module -> settings -> random -> rotate[1]); ?>deg);
				<?php endif; ?>
				
				<?php if (isset($module -> settings -> random -> color)) : ?>
				background-color: rgb(
					<?= rand($module -> settings -> random -> color[0], $module -> settings -> random -> color[1]) * 32; ?>,
					<?= rand($module -> settings -> random -> color[0], $module -> settings -> random -> color[1]) * 32; ?>,
					<?= rand($module -> settings -> random -> color[0], $module -> settings -> random -> color[1]) * 32; ?>
				);
				<?php endif; ?>
		"
		<?php endif; ?>
	>
		
		<?php
			
			$module -> var['link'] = [
				'tag' => false,
				'class' => [],
				'id' => false,
				'data' => [],
				'area' => false,
				'styles' => [],
				'link' => false
			];
			
			if (!empty($module -> settings -> nolink)) {
				$module -> var['link']['tag'] = 'div';
			} else {
				$module -> var['link']['tag'] = 'a';
				$module -> var['link']['data'] = [
					'fancybox' => $module -> param,
					'caption' => $module -> settings -> captions[$key]
				];
				$module -> var['link']['link'] = '/' . NAME_UPLOAD . '/' . (($module -> param === 'default') ? $module -> name : $module -> name . '/' . $module -> param) . '/' . $item;
			}
			
			$module -> var['link']['class'][] = 'gallery__link';
			$module -> var['link']['class'][] = 'gallery_' . $module -> param . '__link';
			
			if (!empty($module -> settings -> classes) && !empty($module -> settings -> classes -> link) && is_string($module -> settings -> classes -> link)) {
				$module -> var['link']['class'][] = $module -> settings -> classes -> link;
			}
			if (!empty($module -> settings -> background) && !empty($module -> settings -> classes) && !empty($module -> settings -> classes -> image) && is_string($module -> settings -> classes -> image)) {
				$module -> var['link']['class'][] = $module -> settings -> classes -> image;
			}
			
			if (!empty($module -> settings -> background)) {
				$module -> var['link']['styles']['background-image'] = 'url(/' . NAME_UPLOAD . '/' . (($module -> param === 'default') ? $module -> name : $module -> name . '/' . $module -> param) . '/' . $item . ')';
			}
			
			$module -> var['link'] = new htmlElement(
				$module -> var['link']['tag'],
				$module -> var['link']['class'],
				$module -> var['link']['id'],
				$module -> var['link']['data'],
				$module -> var['link']['area'],
				$module -> var['link']['styles'],
				$module -> var['link']['link']
			);
			
			if (empty($module -> settings -> background)) {
				
				$module -> var['image'] = [
					'class' => [],
					'link' => false
				];
				
				$module -> var['image']['link'] = '/' . NAME_UPLOAD . '/' . (($module -> param === 'default') ? $module -> name : $module -> name . '/' . $module -> param ) . '/' . $item;
				
				$module -> var['image']['class'][] = 'gallery__image';
				$module -> var['image']['class'][] = 'gallery_' . $module -> param . '__image';
				
				if (!empty($module -> settings -> classes) && !empty($module -> settings -> classes -> image) && is_string($module -> settings -> classes -> image)) {
					$module -> var['image']['class'][] = $module -> settings -> classes -> image;
				}
				
				$module -> var['image'] = new htmlElement(
					'img',
					$module -> var['image']['class'],
					false,
					false,
					false,
					false,
					$module -> var['image']['link']
				);
				
				$module -> var['image'] -> close();
				
			}
			
			if (isset($module -> var['link'])) {
				$module -> var['link'] -> close();
			}
			
			if (isset($module -> settings -> captions[$key])) {
				
				$module -> var['caption'] = [
					'tag' => 'div',
					'class' => [],
				];
				
				$module -> var['caption']['class'][] = 'gallery__caption';
				$module -> var['caption']['class'][] = 'gallery_' . $module -> param . '__caption';
				
				if (!empty($module -> settings -> classes) && !empty($module -> settings -> classes -> caption) && is_string($module -> settings -> classes -> caption)) {
					$module -> var['caption']['class'][] = $module -> settings -> classes -> caption;
				}
				
				$module -> var['caption'] = new htmlElement(
					$module -> var['caption']['tag'],
					$module -> var['caption']['class']
				);
				
				echo $module -> settings -> captions[$key];
				
				$module -> var['caption'] -> close();
				
			}
			
		?>
		
	</div>
	
<?php
	
	endforeach;
	
	if (isset($module -> var['gallery'])) {
		$module -> var['gallery'] -> close();
	}
	
?>