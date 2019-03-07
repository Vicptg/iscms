<?php defined('isCMS') or die; ?>

<div class="buttons">

<?php
	
	if (
		!empty($module -> settings -> filter -> options) &&
		!empty($module -> settings -> filter -> options -> pages) &&
		!empty($module -> var['filter']) &&
		isset($module -> var['filter_items']) &&
		is_numeric($module -> var['filter_items']) &&
		$module -> var['filter_items']
	) :
		$s = $template -> url . '/' . $module -> param . '/all/filter/';
		foreach ($module -> var['filter'] as $module -> var['key'] => $module -> var['item']) {
			$s .= $module -> var['key'] . '/' . $module -> var['item'] . '/';
		}
		$s .= 'items/' . $module -> var['filter_items'] . '/';
		$n = ceil($module -> var['filter_count'] / $module -> var['filter_items']);
		
?>
	<div class="buttons_pagination">
		<?php for ($i = 1; $i <= $n; $i++) : ?>
			<?php if ($i == $module -> var['filter_page_current']) : ?>
				<span class="button button_page button_page__current">
					<?= $i; ?>
				</span>
			<?php else : ?>
				<a
					href="<?= $s . 'page/' . $i . '/#articles_' . $module -> param; ?>"
					class="button button_page"
				>
					<?= $i; ?>
				</a>
			<?php endif; ?>
		<?php endfor; ?>
	</div>
<?php endif; ?>

<?php
	
	if (
		$template -> router -> parameters -> page !== 'all' &&
		$module -> settings -> page -> articles !== 0 &&
		isset($module -> settings -> buttons) &&
		!empty($module -> settings -> buttons -> all) &&
		(
			$module -> settings -> buttons -> all === $module -> var['buttons'] ||
			$module -> settings -> buttons -> all === 'both'
		)
	) :
	
?>
	<a href="all/" class="button button_openall">
		<?= $lang -> action -> open; ?> <?= $lang -> action -> all; ?>
	</a>
<?php
	
	elseif (
		isset($template -> router -> parameters -> page) &&
		isset($module -> settings -> buttons) &&
		!empty($module -> settings -> buttons -> back) &&
		(
			$module -> settings -> buttons -> back === $module -> var['buttons'] ||
			$module -> settings -> buttons -> back === 'both'
		)
		
	) :
	
?>
	<a href="/<?= $module -> param; ?>/" class="button button_back">
		<?= $lang -> action -> back; ?>
	</a>
<?php endif; ?>

</div>