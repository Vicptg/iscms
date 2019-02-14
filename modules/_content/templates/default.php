<?php defined('isCMS') or die; ?>

<?php
	if (
		isset($template -> router -> parameters -> page) &&
		$template -> router -> parameters -> page === 'all' &&
		$module -> settings -> page -> firstnews
	) :
?>
	<a href="/<?= $module -> param; ?>/" class="news_button">
		<?= $lang -> action -> back; ?>
	</a>
<?php endif; ?>

<div id="news">
	
	<?php if (!count($module -> data) || isset($template -> router -> parameters -> item)) : ?>
		
		<div id="news-content">
			<p align="center">Такой новости не существует!</p>
			<a href="/<?= $module -> param; ?>/" class="news_button">
				Назад к списку
			</a>
		</div>
		
	<?php else : ?>
	
	<?php foreach ($module -> data as $key => $item) : ?>
	
	<?php if ($key >= $module -> settings -> page -> firstnews) : ?>
	
	<div id="news-content">
		<div id="news-pic">
			<img src="/<?= NAME_ARTICLES . '/' . module -> param . '-pic/' . $item['image']; ?>" alt="<?= ($item['imagename']) ? $item['imagename'] : $item['title']; ?>" title="<?= ($item['imagename']) ? $item['imagename'] : $item['title']; ?>" />
		</div>
		<div id="news-inner">
			<div id="news-data">
				<?= $item['date']; ?>
			</div>
			<div id="news-titul">
				<?php if ( time() - strtotime($item['date']) < $module -> settings -> page -> oldtime || $key < $module -> settings -> page -> oldnews ) : ?>
					<span class="new">NEW!</span>
				<?php endif; ?>
				<?= $item['title']; ?>
			</div>
			<div id="news-text">
				<?= $item['text']; ?>
			</div>
		</div>
	</div>
	
	<?php endif; ?>
	
	<?php endforeach; ?>
	
	<?php endif; ?>
	
</div>

<?php
	if (
		$template -> router -> parameters -> page !== 'all' &&
		!isset($template -> router -> parameters -> item)
	) :
?>
<a href="?page=all" class="news_button">
	<?= $lang -> action -> open; ?> <?= $lang -> action -> all; ?>
</a>
<?php endif; ?>

<?php
	if (
		isset($template -> router -> parameters -> page) &&
		$template -> router -> parameters -> page === 'all' &&
		$module -> settings -> page -> firstnews
	) :
?>
	<a href="/<?= $module -> param; ?>/" class="news_button">
		<?= $lang -> action -> back; ?>
	</a>
<?php endif; ?>
