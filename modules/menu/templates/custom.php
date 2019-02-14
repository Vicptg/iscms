<?php defined('isCMS') or die; ?>

<?php // прерываем вывод сразу же, если меню не кастомное ?>
<?php if (!$module -> settings -> custom) return; ?>

<?php // main menu <ul> / <div> ; ?>
<?php if (!$module -> settings -> menuchange) : ?>
<ul
<?php else : ?>
<div
<?php endif; ?>
	class="<?= $module -> param; ?>"
	id="<?= $module -> param; ?>"
>
	
	<?php foreach ($module -> data as $key => $item) : ?>

		<?php // main elements <li> / <a> only; ?>
		<?php if (!$module -> settings -> menuchange) : ?>
		<?= ( $key && $key !== key($module -> data) && $module -> settings -> separator ) ? htmlspecialchars($module -> settings -> separator) : ''; ?>
		<li
			class="
				<?= ($module -> settings -> classes -> li) ? $module -> settings -> classes -> li : ''; ?>
				<?php if ($item === $template -> router -> page || ($item === 'home' && !$template -> router -> page)) : ?>
					<?= ($module -> settings -> classes -> active) ? $module -> settings -> classes -> active : ''; ?>
				<?php endif; ?>
			"
		>
		<?php endif; ?>
			
			<?= ( $key && $key !== key($module -> data) && $module -> settings -> menuchange && $module -> settings -> separator ) ? htmlspecialchars($module -> settings -> separator) : ''; ?>
			
			<a
				href="<?php
					if (
						$module -> settings -> onepage === true ||
						(
							is_array($module -> settings -> onepage) &&
							in_array($item, $module -> settings -> onepage)
						)
					) {
						echo '#' . $item;
					} elseif (
						is_array($module -> settings -> modal) &&
						in_array($item, $module -> settings -> modal) &&
						$module -> settings -> bootstrap
					) {
						echo '#';
					} elseif (
						is_array($module -> settings -> modal) &&
						in_array($item, $module -> settings -> modal)
					) {
						echo '#' . $item;
					} else {
						$path = $template -> url . '/';
						if ($template -> lang !== ROOT_LANG) {
							$path .= $template -> lang . '/';
						}
						if ($item !== 'home') {
							if ( strpos($key, ':') && strpos( substr( $key, strpos($key, ':') + 1 ), ':' ) ) {
								$path .= substr( $key, 0, strpos($key, ':') ) . '/';
								$path .= substr( substr( $key, strpos($key, ':') + 1 ), 0, strpos( substr( $key, strpos($key, ':') + 1 ), ':' ) ) . '/';
								$path .= $item . '/';
							} elseif ( strpos($key, ':') ) {
								$path .= substr( $key, 0, strpos($key, ':') ) . '/';
								$path .= $item . '/';
							} else {
								$path .= $item . '/';
							}
						}
						echo $path;
						unset($path);
					}
				?>"
				class="
					<?php // классы элемента li переносятся только когда смена включена, но при этом бутстрап выключен; ?>
					<?php if ($module -> settings -> menuchange) : ?>
						<?php if ($item === $template -> router -> page || ($item === 'home' && !$template -> router -> page)) : ?>
							<?= ($module -> settings -> classes -> active) ? $module -> settings -> classes -> active : ''; ?>
						<?php endif; ?>
					<?php endif; ?>
				"
				<?php
					if (
						is_array($module -> settings -> modal) &&
						in_array($item, $module -> settings -> modal) &&
						$module -> settings -> bootstrap
					) :
				?>
					data-toggle="modal"
					data-target="#<?= $item; ?>"
				<?php endif; ?>
			>
				<?php if ($module -> settings -> icons -> $item) : ?>
					<i class="<?= $module -> settings -> icons -> $item; ?>"></i>
				<?php endif; ?>
			<?= ($lang -> menu -> $item) ? $lang -> menu -> $item : $item; ?></a>
			
		<?php // main elements <li> / <a> only; ?>
		<?php if (!$module -> settings -> menuchange) : ?>
		</li>
		<?php endif; ?>
			
	<?php endforeach; ?>
	
<?php // main menu </ul> / </div> ; ?>
<?php if (!$module -> settings -> menuchange) : ?>
</ul>
<?php else : ?>
</div>
<?php endif; ?>
