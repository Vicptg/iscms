<?php defined('isCMS') or die; ?>

<div class="gallery gallery_<?= $module -> param; ?><?= ($module -> settings -> classes) ? ' ' . $module -> settings -> classes : ''; ?>">
	
	<?php foreach ($module -> data as $key => $item) : ?>
		
		<div
			class="gallery__item gallery_<?= $module -> param; ?>__item"
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
			<a
				data-fancybox="<?= $module -> param; ?>"
				data-caption="<?= $module -> settings -> captions[$key]; ?>"
				href="/<?= NAME_UPLOAD; ?>/<?= ($module -> param === 'default') ? $module -> name : $module -> name . '/' . $module -> param; ?>/<?= $item; ?>"
				class="gallery__link gallery_<?= $module -> param; ?>__link"
				
				<?php if (!empty($module -> settings -> background)) : ?>
					style="background-image: url('/<?= NAME_UPLOAD; ?>/<?= ($module -> param === 'default') ? $module -> name : $module -> name . '/' . $module -> param; ?>/<?= $item; ?>');"
				<?php endif; ?>
			>
				<?php if (empty($module -> settings -> background)) : ?>
					<img
						src="/<?= NAME_UPLOAD; ?>/<?= ($module -> param === 'default') ? $module -> name : $module -> name . '/' . $module -> param; ?>/<?= $item; ?>"
						class="gallery__image gallery_<?= $module -> param; ?>__image"
					>
				<?php endif; ?>
			</a>
			<?php if (isset($module -> settings -> captions[$key])) : ?><div class="gallery__caption gallery_<?= $module -> param; ?>__caption"><?= $module -> settings -> captions[$key]; ?></div><?php endif; ?>
		</div>
		
	<?php endforeach; ?>
	
</div>