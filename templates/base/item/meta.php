<?php defined('isCMS') or die; ?>

<!-- META -->

<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php if (in_array('autoseo', $template -> options)) : ?>
	
	<?php
		if ($seo -> page && $seo -> original) {
			$title = $seo -> original . '. ' . $seo -> page . ' - ';
		} elseif ($seo -> page) {
			$title = $seo -> page . ' - ';
		} elseif ($seo -> original) {
			$title = $seo -> original . ' - ';
		}
		$title .= $seo -> site;
	?>
	
	<title><?= $title; ?></title>
	
	<meta name="DC.Title" content="<?= $title; ?>">
	<meta name="DC.Creator" content="<?= $seo -> site; ?>">
	<meta name="DC.Subject" content="<?= $seo -> keys; ?>">
	<meta name="DC.Description" content="<?= $seo -> desc; ?>">
	<meta name="DC.Publisher" content="<?= $seo -> author; ?>">
	<meta name="DC.Type" content="Text">
	<meta name="DC.Format" content="text/html">
	<meta name="DC.Identifier" content="<?= $seo -> link; ?>">
	<meta name="DC.Language" content="<?= $template -> lang . '_' . mb_strtoupper($template -> langcode); ?>">
	<meta name="DC.Coverage" content="World">
	<meta name="DC.Rights" content="<?= $seo -> rights; ?>">
	
	<meta property="og:title" content="<?= $title; ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:image" content="<?= $seo -> image; ?>" />
	<meta property="og:url" content="<?= $seo -> link; ?>" />
	<meta property="og:site_name" content="<?= $seo -> site; ?>" />
	<meta property="og:description" content="<?= $seo -> desc; ?>" />
	<meta property="og:locale" content="<?= $template -> lang . '_' . mb_strtoupper($template -> langcode); ?>" />
	<meta property="og:see_also" content="<?= $_SERVER['REQUEST_SCHEME'] . '://' . $seo -> url; ?>"/>
	<meta property="article:publisher" content="<?= ($lang -> facebook) ? $lang -> facebook : $seo -> author; ?>" />
	<meta property="article:author" content="<?= $seo -> author; ?>" />
	<meta property="article:published_time" content="2017-07-25T02:17:50+00:00" />
	<meta property="article:modified_time" content="2018-01-04T06:30:52+00:00" />
	
	<?php
		if ($seo -> keys) :
			$keywords = preg_split('/,/', $seo -> keys, -1, PREG_SPLIT_NO_EMPTY);
			foreach ($keywords as $item) :
	?>
		<meta property="article:tag" content="<?= trim($item); ?>" />
	<?php
			endforeach;
		endif;
	?>
	
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:description" content="<?= $seo -> desc; ?>" />
	<meta name="twitter:title" content="<?= $title; ?>" />
	<meta name="twitter:site" content="<?= $seo -> site; ?>" />
	<meta name="twitter:image" content="<?= $seo -> image; ?>" />
	<meta name="twitter:creator" content="<?= $seo -> author; ?>" />
	
	<meta property="business:contact_data:street_address" content="<?= $lang -> information -> address; ?>"/>
	<meta property="business:contact_data:locality" content="<?= $lang -> information -> city; ?>"/>
	<meta property="business:contact_data:postal_code" content="<?= $lang -> information -> postcode; ?>"/>
	<meta property="business:contact_data:country_name" content="<?= $lang -> information -> country; ?>"/>
	<meta property="business:contact_data:email" content="<?= $lang -> information -> email[0]; ?>"/>
	<meta property="business:contact_data:phone_number" content="<?= $lang -> information -> phone[0]; ?>"/>
	<meta property="business:contact_data:website" content="<?= $_SERVER['REQUEST_SCHEME'] . '://' . $seo -> url; ?>"/>
	
	<meta itemprop="name" content="<?= $seo -> site; ?>"/>
	<meta itemprop="description" content="<?= $seo -> desc; ?>"/>
	<meta itemprop="image" content="<?= $seo -> image; ?>"/>
	
	<meta name="author" content="<?= $seo -> author; ?>"/>
	<meta name="description" content="<?= $seo -> desc; ?>"/>
	<meta name="keywords" content="<?= $seo -> keys; ?>"/>
	<meta name="copyright" content="<?= $seo -> rights; ?>"/>
	
	<?php if (isset($template -> device -> type) && $template -> device -> type !== 'desktop') : ?>
		<meta name="application-name" content="<?= $seo -> site; ?>">
	<?php endif; ?>
	
<?php else : ?>
	
	<title><?= ($template -> seo -> title) ? $template -> seo -> title . ' - ' : ''; ?><?= $lang -> title; ?></title>
	
	<?php
		$arr = ['author', 'description', 'keywords', 'robots', 'generator', 'copyright'];
		foreach ($arr as $item) :
			if ($template -> seo -> $item) :
	?>
	<meta name="<?= $item; ?>" content="<?= $template -> seo -> $item; ?>"/>
	<?php
			endif;
		endforeach;
	?>
	
<?php endif; ?>

<?php
	if (isset($template -> seo -> additional)) :
		foreach ((array)$template -> seo -> additional as $key => $item) :
?>
	<meta name="<?= $key; ?>" content="<?= $item; ?>"/>
<?php
		endforeach;
	endif;
?>

<?php if (count($template -> router -> parameters) > 1 || strpos($_SERVER['REQUEST_URI'], '.php') !== false || strpos($_SERVER['REQUEST_URI'], '?') !== false) : ?>
	<meta name="referrer" content="origin">
	<link rel="canonical" href="<?php
		echo $template -> url;
		echo ($template -> lang !== ROOT_LANG) ? '/' . $template -> lang : '';
		echo $template -> curr -> path;
	?>/">
<?php endif; ?>

<?php
	if (count($template -> langs)) :
		foreach ($template -> langs as $item) :
			if ($item !== $template -> lang) :
?>
	<link rel="alternate" href="<?php
		echo $template -> url;
		echo ($item !== ROOT_LANG) ? '/' . $item : '';
		echo $template -> curr -> path;
		if (count($template -> router -> parameters) && strpos($_SERVER['REQUEST_URI'], '?') === false) {
			foreach ($template -> router -> parameters as $k => $i) {
				echo ($k) ? '/' . $k : '';
				echo ($i) ? '/' . $i : '';
			}
			unset($i);
		}
	?>/" hreflang="<?= $item; ?>">
<?php
			endif;
		endforeach;
	endif;
?>
