<?php defined('isCMS') or die; ?>

<!-- FAVICONS -->

<?php
$icons = dataloadjson(PATH_UPLOAD . DIRECTORY_SEPARATOR . 'icons' . DIRECTORY_SEPARATOR . 'icons.ini', true);

if ($icons) {
	foreach ($icons as $icon) {
		foreach ($icon['sizes'] as $item) {
			if (file_exists(PATH_UPLOAD . DIRECTORY_SEPARATOR . 'icons' . DIRECTORY_SEPARATOR . $icon['name'] . '-' . $item . 'x' . $item . '.png')) {
				if ($icon['type'] === 'icon') {
					echo '<link rel="' . $icon['type'] . '" type="image/png" sizes="' . $item . 'x' . $item . '" href="' . $template -> url . '/' . NAME_UPLOAD . '/icons/' . $icon['name'] . '-' . $item . 'x' . $item . '.png">';
				}
				if ($icon['type'] === 'apple-touch-icon') {
					echo '<link rel="' . $icon['type'] . '" sizes="' . $item . 'x' . $item . '" href="' . $template -> url . '/' . NAME_UPLOAD . '/icons/' . $icon['name'] . '-' . $item . 'x' . $item . '.png">';
				}
				if ($icon['type'] === 'apple-touch-icon-precomposed') {
					echo '<link rel="' . $icon['type'] . '" sizes="' . $item . 'x' . $item . '" href="' . $template -> url . '/' . NAME_UPLOAD . '/icons/' . $icon['name'] . '-' . $item . 'x' . $item . '.png">';
				}
			}
		}
	}
	unset($icon, $item);
}
unset($icons);
?>
<?php if (file_exists(PATH_SITE . DIRECTORY_SEPARATOR . 'browserconfig.xml')) : ?>
	<meta name="msapplication-config" content="browserconfig.xml">
<?php endif; ?>