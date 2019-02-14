<?php defined('isCMS') or die; ?>
<?php if (in_array('baseset', $template -> param)) : ?>
<?php
	$baseless = [];
	$lesspath = $template -> base -> php . DIRECTORY_SEPARATOR . 'less' . DIRECTORY_SEPARATOR;
	if (!file_exists($lesspath . 'style.less')) file_put_contents($lesspath . 'style.less', '');
	if (!file_exists($lesspath . 'style.old') || filesize($lesspath . 'style.less') != filesize($lesspath . 'style.old')) {
		$baseless['style'] = new lessc;
		$baseless['style']->checkedCompile($lesspath . 'style.less', $lesspath . 'style.css');
		copy($lesspath . 'style.less', $lesspath . 'style.old');
	}
	
	if ( $template -> device -> type && $template -> device -> type !== 'desktop' && !file_exists($lesspath . 'mobile.less') ) file_put_contents($lesspath . 'mobile.less', '');
	if ( $template -> device -> type && $template -> device -> type !== 'desktop' && ( !file_exists($lesspath . 'mobile.old') || filesize($lesspath . 'mobile.less') != filesize($lesspath . 'mobile.old') ) ) {
		$baseless['mobile'] = new lessc;
		$baseless['mobile']->checkedCompile($lesspath . 'mobile.less', $lesspath . 'mobile.css');
		copy($lesspath . 'mobile.less', $lesspath . 'mobile.old');
	}
	
	unset($baseless, $lesspath);
?>

<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> base -> url; ?>/less/style.css" />
<?php if ($template -> device -> type && $template -> device -> type !== 'desktop') : ?>
<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> base -> url; ?>/less/mobile.css" />
<?php endif; ?>

<?php endif; ?>

<?php if (isset($template -> less) && is_array($template -> less) && count($template -> less)) : ?>
	
	<?php
		$baseless = [];
		$lesspath = $template -> curr -> php . DIRECTORY_SEPARATOR . 'less' . DIRECTORY_SEPARATOR;
	?>
	<?php foreach ($template -> less as $item) : ?>
		<?php if (!file_exists($lesspath . $item . '.less')) file_put_contents($lesspath . $item . '.less', ''); ?>
		<?php
			if (!file_exists($lesspath . $item . '.old') || filesize($lesspath . $item . '.less') != filesize($lesspath . $item . '.old')) {
				$baseless[$item] = new lessc;
				$baseless[$item]->checkedCompile($lesspath . $item . '.less', $lesspath . $item . '.css');
				copy($lesspath . $item . '.less', $lesspath . $item . '.old');
			}
		?>
		<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> curr -> url; ?>/less/<?= $item; ?>.css" />
	<?php endforeach; ?>
	<?php unset($baseless, $lesspath); ?>
	
<?php else : ?>
	
	<?php
		$baseless = [];
		$lesspath = $template -> curr -> php . DIRECTORY_SEPARATOR . 'less' . DIRECTORY_SEPARATOR;
		if (!file_exists($lesspath . 'template.less')) file_put_contents($lesspath . 'template.less', '');
		if (!file_exists($lesspath . 'template.old') || filesize($lesspath . 'template.less') != filesize($lesspath . 'template.old')) {
			$baseless['template'] = new lessc;
			$baseless['template']->checkedCompile($lesspath . 'template.less', $lesspath . 'template.css');
			copy($lesspath . 'template.less', $lesspath . 'template.old');
		}
	?>
	<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> curr -> url; ?>/less/template.css" />
	<?php unset($baseless, $lesspath); ?>
	
<?php endif; ?>

<?php
	$baseless = '';
	$lesspath = $template -> curr -> php . DIRECTORY_SEPARATOR . 'less' . DIRECTORY_SEPARATOR;
	if (file_exists($lesspath . $template -> router -> page . '.less')) :
		if (!file_exists($lesspath . $$template -> router -> page . '.old') || filesize($lesspath . $template -> router -> page . '.less') != filesize($lesspath . $template -> router -> page . '.old')) {
			$baseless = new lessc;
			$baseless->checkedCompile($lesspath . $template -> router -> page . '.less', $lesspath . $template -> router -> page . '.css');
			copy($lesspath . $template -> router -> page . '.less', $lesspath . $template -> router -> page . '.old');
		}
?>
	<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $template -> curr -> url; ?>/less/<?= $template -> router -> page; ?>.css" />
<?php
	endif;
	unset($baseless, $lesspath);
?>
