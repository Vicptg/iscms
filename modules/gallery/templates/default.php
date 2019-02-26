<?php defined('isCMS') or die; ?>

<div
	class="
		gallery
		gallery_<?= $module -> param; ?>
		<?php
			if (!empty($module -> settings -> classes)) {
				if (is_string($module -> settings -> classes)) {
					echo $module -> settings -> classes;
				} elseif (!empty($module -> settings -> classes -> gallery) && is_string($module -> settings -> classes -> gallery)) {
					echo $module -> settings -> classes -> gallery;
				}
			}
		?>
	"
	id="<?= $module -> param; ?>"
	<?= (!empty($module -> settings -> frames)) ? 'data-frames="' . $module -> settings -> frames . '"' : ''; ?>
>
	
	<?php foreach ($module -> data as $key => $item) : ?>
		
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
			<?php if (!empty($module -> settings -> nolink)) : ?>
			<div
			<?php else : ?>
			<a
				data-fancybox="<?= $module -> param; ?>"
				data-caption="<?= $module -> settings -> captions[$key]; ?>"
				href="/<?= NAME_UPLOAD; ?>/<?= ($module -> param === 'default') ? $module -> name : $module -> name . '/' . $module -> param; ?>/<?= $item; ?>"
			<?php endif; ?>
				class="
					gallery__link
					gallery_<?= $module -> param; ?>__link
					<?php
						if (!empty($module -> settings -> classes) && !empty($module -> settings -> classes -> link) && is_string($module -> settings -> classes -> link)) {
							echo $module -> settings -> classes -> link;
						}
						if (!empty($module -> settings -> background) && !empty($module -> settings -> classes) && !empty($module -> settings -> classes -> image) && is_string($module -> settings -> classes -> image)) {
							echo $module -> settings -> classes -> image;
						}
					?>
				"
				<?php if (!empty($module -> settings -> background)) : ?>
					style="background-image: url('/<?= NAME_UPLOAD; ?>/<?= ($module -> param === 'default') ? $module -> name : $module -> name . '/' . $module -> param; ?>/<?= $item; ?>');"
				<?php endif; ?>
			>
				<?php if (empty($module -> settings -> background)) : ?>
					<img
						src="/<?= NAME_UPLOAD; ?>/<?= ($module -> param === 'default') ? $module -> name : $module -> name . '/' . $module -> param; ?>/<?= $item; ?>"
						class="
							gallery__image
							gallery_<?= $module -> param; ?>__image
							<?php
								if (!empty($module -> settings -> classes) && !empty($module -> settings -> classes -> image) && is_string($module -> settings -> classes -> image)) {
									echo $module -> settings -> classes -> image;
								}
							?>
						"
					>
				<?php endif; ?>
			<?php if (!empty($module -> settings -> nolink)) : ?>
			</div>
			<?php else : ?>
			</a>
			<?php endif; ?>
			
			<?php if (isset($module -> settings -> captions[$key])) : ?>
				<div class="
					gallery__caption
					gallery_<?= $module -> param; ?>__caption
					<?php
						if (!empty($module -> settings -> classes) && !empty($module -> settings -> classes -> caption) && is_string($module -> settings -> classes -> caption)) {
							echo $module -> settings -> classes -> caption;
						}
					?>
				">
					<?= $module -> settings -> captions[$key]; ?>
				</div>
			<?php endif; ?>
		</div>
		
	<?php endforeach; ?>
	
</div>