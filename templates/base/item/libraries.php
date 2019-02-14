<?php defined('isCMS') or die;

//$libraries = dataloadjson(PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'libraries.ini');
//$libraries = dbUse(['name' => PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'libraries.ini', 'json' => true], 'select');
$libraries = dbSelect('settings', 'libraries');
if (!$libraries) {
	exit;
}
?>

<!-- LIBRARIES -->

<?php foreach ($libraries as $key => $item) : ?>

	<?php if (in_array('all', $template -> libraries) || in_array($key, $template -> libraries)) : ?>
		
		<?php if (isset($loadingLog)) { $loadingLog .= $key . ' library '; } ?>
		
		<!-- load <?= $key; ?> -->
		
		<?php if (!empty($item -> css)) : ?>
		<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?php
			if (!empty($item -> css_cdn) && in_array('cdn', $template -> libraries)) {
				echo $item -> css_cdn;
				if (isset($loadingLog)) { $loadingLog .= 'from cdn '; }
			} else {
				echo $template -> url . '/' . NAME_LIBRARIES . '/' . $key . '/' . $item -> css;
			}
		?>" />
			
			<?php if (!empty($item -> css_cdn_lang) && in_array('cdn', $template -> libraries)) : ?>
				<?php if (isset($loadingLog)) { $loadingLog .= 'and language pack '; } ?>
				<link rel="stylesheet" rev="stylesheet" type="text/css" charset="UTF-8" href="<?php
					$item -> css_cdn_lang = str_replace('{lang}', $template -> lang, $item -> css_cdn_lang);
					$item -> css_cdn_lang = str_replace('{langcode}', $template -> langcode, $item -> css_cdn_lang);
					echo $item -> css_cdn_lang;
					if (isset($loadingLog)) { $loadingLog .= 'from cdn '; }
				?>" />
			<?php elseif (!empty($item -> css_lang)) : ?>
				<link rel="stylesheet" rev="stylesheet" type="text/css" charset="UTF-8" href="<?php
					$item -> css_lang = str_replace('{lang}', $template -> lang, $item -> css_lang);
					$item -> css_lang = str_replace('{langcode}', $template -> langcode, $item -> css_lang);
					echo $template -> url . '/' . NAME_LIBRARIES . '/' . $key . '/' . $item -> css_lang;
				?>" />
			<?php endif; ?>
			
		<?php endif; ?>
		
		<?php if (!empty($item -> js)) : ?>
			<?php if (isset($loadingLog)) { $loadingLog .= 'with scripts '; } ?>
			<script type="text/javascript" src="<?php
				if (!empty($item -> js_cdn) && in_array('cdn', $template -> libraries)) {
					echo $item -> js_cdn;
				} else {
					echo $template -> url . '/' . NAME_LIBRARIES . '/' . $key . '/' . $item -> js;
				}
			?>"></script>
			<?php if (!empty($item -> js_cdn_lang) && in_array('cdn', $template -> libraries)) : ?>
				<?php if (isset($loadingLog)) { $loadingLog .= 'and scripts language pack '; } ?>
				<script type="text/javascript" charset="UTF-8" src="<?php
					$item -> js_cdn_lang = str_replace('{lang}', $template -> lang, $item -> js_cdn_lang);
					$item -> js_cdn_lang = str_replace('{langcode}', $template -> langcode, $item -> js_cdn_lang);
					echo $item -> js_cdn_lang;
					if (isset($loadingLog)) { $loadingLog .= 'from cdn '; }
				?>"></script>
			<?php elseif (!empty($item -> js_lang)) : ?>
				<script type="text/javascript" charset="UTF-8" src="<?php
					$item -> js_lang = str_replace('{lang}', $template -> lang, $item -> js_lang);
					$item -> js_lang = str_replace('{langcode}', $template -> langcode, $item -> js_lang);
					echo $template -> url . '/' . NAME_LIBRARIES . '/' . $key . '/' . $item -> js_lang;
				?>"></script>
			<?php endif; ?>
		<?php endif; ?>
		
		<?php if (!empty($item -> code)) :
			if (isset($loadingLog)) { $loadingLog .= 'with codes '; }
			$path = PATH_LIBRARIES . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . $item -> code;
			if (file_exists($path)) {
				require_once $path;
				echo '<!-- ' . $key . ' library loading is complete -->';
				if (isset($loadingLog)) { $loadingLog .= 'complete '; }
			} else {
				echo '<!-- error: ' . $key . ' library loading is wrong -->';
				if (isset($loadingLog)) { $loadingLog .= 'wrong '; }
			}
			unset($path);
		endif; ?>
		
		<?php
		/*
		echo '<pre>';
		print_r($item);
		echo '</pre>-----';
		*/
		if (isset($loadingLog)) { $loadingLog .= 'was be loading\n'; }
		?>
		
	<?php endif; ?>
	
<?php
	endforeach;
	unset($libraries);
	if (isset($loadingLog)) { $loadingLog .= '\n'; }
?>

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->