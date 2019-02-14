<?php defined('isCMS') or die; ?>

<ul class="breadcrumbs">
	<li class="
		breadcrumbs_item
		breadcrumbs_item__home
		breadcrumbs_item__link
	"><a href="/">Главная</a></li>
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
				breadcrumbs_item__folder
				breadcrumbs_item__link
			">
				<a data-set="<?= $item; ?>" href="<?= $path; ?>"><?= $lang -> menu -> $item; ?></a>
			<?php else : ?>
			<li class="
				breadcrumbs_item
				breadcrumbs_item__folder
				breadcrumbs_item__nolink
			">
				<span><?= $lang -> menu -> $item; ?></span>
			<?php endif; ?>
			</li>
		<?php endforeach; ?>
		<?php unset($path, $item); ?>
	<?php endif; ?>
	<?php if ($template -> router -> page) : ?>
		<?php $path = $template -> router -> page; ?>
			<li class="
				breadcrumbs_item
				breadcrumbs_item__page
				breadcrumbs_item__nolink
			">
				<span><?= $lang -> menu -> $path; ?></span>
			</li>
		<?php unset($path); ?>
	<?php endif; ?>
</ul>
