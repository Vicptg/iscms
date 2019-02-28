<?php defined('isCMS') or die; ?>

<?php if (!cookie('AGREE', true)) : ?>

<div id="cookies" class="cookies">
	<div class="cookies_content">
		<?= $lang -> common -> cookie; ?>
	</div>
	<div class="cookies_buttons">
		<a href="/?query=cookiesagree&hash=<?= datacrypt(time()); ?>" class="cookies_button cookies_buttons__argee">
			<?= $lang -> action -> agree; ?>
		</a>
		<a href="/cookie" class="cookies_button cookies_buttons__readmore">
			<?= $lang -> action -> readmore; ?>
		</a>
	</div>
</div>

<style>
#cookies {
	position: absolute;
	top: auto;
	bottom: 0;
	left: 0;
	right: 0;
	padding: 2em;
	background: white;
	z-index: 9999;
}
#cookies .cookies_content {
	padding-bottom: 1em;
	font-size: 1.25em;
	text-align: center;
}
#cookies .cookies_buttons {
	text-align: center;
}
#cookies .cookies_button {
	margin: 0.5em;
	padding: 0.5em;
	text-transform: uppercase;
}
</style>

<?php endif; ?>