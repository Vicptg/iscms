<?php defined('isCMS') or die; ?>

<div class="
	breadcrumbs
	breadcrumbs_<?= $module -> param; ?>
	<?= (!empty($module -> settings -> classes -> body)) ? $module -> settings -> classes -> body : ''; ?>
">

<?php if (!empty($module -> settings -> title)) : ?>
	<span class="
		breadcrumbs_title
		breadcrumbs_<?= $module -> param; ?>_title
		<?= (!empty($module -> settings -> classes -> title)) ? $module -> settings -> classes -> title : ''; ?>
	"><?php
		if ($module -> settings -> title === true && isset($lang -> title)) {
			if (is_array($lang -> title)) {
				foreach ($lang -> title as $i) {
					echo $i . ' ';
				}
			} else {
				echo $lang -> title;
			}
		} else {
			echo $module -> settings -> title;
		}
	?></span>
<?php endif; ?>

<ul class="
	breadcrumbs_wrapper
	breadcrumbs_<?= $module -> param; ?>_wrapper
	<?= (!empty($module -> settings -> classes -> ul)) ? $module -> settings -> classes -> ul : ''; ?>
">
	
	<?php if (!empty($module -> settings -> home)) : ?>
		<li class="
			breadcrumbs_item
			breadcrumbs_<?= $module -> param; ?>_item
			<?= (!empty($module -> settings -> classes -> li)) ? $module -> settings -> classes -> li : ''; ?>
			breadcrumbs_item__home
			breadcrumbs_<?= $module -> param; ?>_item__home
			<?= (!empty($module -> settings -> classes -> home)) ? $module -> settings -> classes -> home : ''; ?>
			breadcrumbs_item__link
			breadcrumbs_<?= $module -> param; ?>_item__link
			<?= (!empty($module -> settings -> classes -> link)) ? $module -> settings -> classes -> link : ''; ?>
		">
			<a href="/"><?= ($module -> settings -> home === true && isset($lang -> menu -> home)) ? $lang -> menu -> home : $module -> settings -> home; ?></a>
			<?php if (!empty($module -> settings -> separator)) echo $module -> settings -> separator; ?>
		</li>
	<?php endif; ?>
	
	<?php if (count($template -> router -> folders)) : ?>
		<?php $path = ''; ?>
		<?php foreach ($template -> router -> folders as $item) : ?>
			<?php $path .= '/' . $item; ?>
			<?php
				if (
					!array_key_exists($item, $template -> router -> types) ||
					$template -> router -> types[$item] == '' ||
					$template -> router -> types[$item] == 'params' ||
					$template -> router -> types[$item] == 'article'
				) :
			?>
			<li class="
				breadcrumbs_item
				breadcrumbs_<?= $module -> param; ?>_item
				<?= (!empty($module -> settings -> classes -> li)) ? $module -> settings -> classes -> li : ''; ?>
				breadcrumbs_item__folder
				breadcrumbs_<?= $module -> param; ?>_item__folder
				<?= (!empty($module -> settings -> classes -> folder)) ? $module -> settings -> classes -> folder : ''; ?>
				breadcrumbs_item__link
				breadcrumbs_<?= $module -> param; ?>_item__link
				<?= (!empty($module -> settings -> classes -> link)) ? $module -> settings -> classes -> link : ''; ?>
			">
				<a data-set="<?= $item; ?>" href="<?= $path; ?>"><?= $lang -> menu -> $item; ?></a>
				<?php if (!empty($module -> settings -> separator)) echo $module -> settings -> separator; ?>
			<?php else : ?>
			<li class="
				breadcrumbs_item
				breadcrumbs_<?= $module -> param; ?>_item
				<?= (!empty($module -> settings -> classes -> li)) ? $module -> settings -> classes -> li : ''; ?>
				breadcrumbs_item__folder
				breadcrumbs_<?= $module -> param; ?>_item__folder
				<?= (!empty($module -> settings -> classes -> folder)) ? $module -> settings -> classes -> folder : ''; ?>
				breadcrumbs_item__nolink
				breadcrumbs_<?= $module -> param; ?>_item__nolink
				<?= (!empty($module -> settings -> classes -> nolink)) ? $module -> settings -> classes -> nolink : ''; ?>
			">
				<span><?= $lang -> menu -> $item; ?></span>
				<?php if ($template -> router -> page && !empty($module -> settings -> separator)) echo $module -> settings -> separator; ?>
			<?php endif; ?>
			</li>
		<?php endforeach; ?>
		<?php unset($path, $item); ?>
	<?php endif; ?>
	
	<?php
		if (
			$template -> router -> page &&
			!empty($module -> settings -> page)
		) :
	?>
		<?php $path = $template -> router -> page; ?>
			<li class="
				breadcrumbs_item
				breadcrumbs_<?= $module -> param; ?>_item
				<?= (!empty($module -> settings -> classes -> li)) ? $module -> settings -> classes -> li : ''; ?>
				breadcrumbs_item__page
				breadcrumbs_<?= $module -> param; ?>_item__page
				<?= (!empty($module -> settings -> classes -> page)) ? $module -> settings -> classes -> page : ''; ?>
				breadcrumbs_item__nolink
				breadcrumbs_<?= $module -> param; ?>_item__nolink
				<?= (!empty($module -> settings -> classes -> nolink)) ? $module -> settings -> classes -> nolink : ''; ?>
			">
				<span><?= $lang -> menu -> $path; ?></span>
			</li>
		<?php unset($path); ?>
	<?php endif; ?>
	
</ul>

</div>
