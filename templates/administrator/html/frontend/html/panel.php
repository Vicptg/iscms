<ul<?= ($administrator -> in) ? ' class="right"' : '';?>>
	
	<?php if (!$administrator -> in && $administrator -> settings -> moduleview) : ?>
	<li>
		<p class="module off">module off</p>
		<p class="module on hidden">module on</p>
	</li>
	<?php endif; ?>
	
	<?php if (!$administrator -> in && $administrator -> settings -> langview) : ?>
	<li>
		<p class="lang off">lang off</p>
		<p class="lang on hidden">lang on</p>
	</li>
	<li class="big">
		<p class="lang info">lang:</p>
	</li>
	<?php endif; ?>
		
	<li class="right">
		<?php if ($administrator -> in) : ?>
			<a class="link" href="<?= $template -> url; ?>/">Перейти на сайт</a>
		<?php else : ?>
			<a class="link" href="<?= $administrator -> path -> url; ?>/">Перейти в админку</a>
		<?php endif; ?>
	</li>
	<li class="right">
		<form method="get" action="/index.php">
			<input name="query" value="authorisation" type="hidden">
			<input type="hidden" name="hash" value="<?= datacrypt(time()); ?>">
			<input class="link" type="submit" name="data[exit]" value="Выйти из админки">
		</form>
	</li>
	
</ul>
