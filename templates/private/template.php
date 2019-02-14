<?php defined('isCMS') or die; ?>

<header class="header">

	<section class="menu">
		<div class="container">
			<div class="row">
				<?php include_once 'html/menu.php'; ?>
			</div>
		</div>
	</section>

	<!--
	<div class="container">
		<div class="row">
			<?php include_once 'html/header.php'; ?>
		</div>
	</div>
	-->

</header>

<section class="main">
	<div class="container">
		<div class="row">
			<?php include_once ($template -> router -> page) ? $template -> curr -> inner . DIRECTORY_SEPARATOR . $template -> router -> page . '.php' : 'html/main.php'; ?>
		</div>
	</div>
</section>
