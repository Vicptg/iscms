<?php defined('isCMS') or die; ?>

<form
	id="<?= $module -> param; ?>"
	class="
		form
		form_<?= $module -> param; ?>
		<?php if (!empty($module -> status)) : ?>
			<?= ($module -> status === 'complete') ? ' form__complete' : ' form__fail'; ?>
		<?php endif; ?>
		<?= (!empty($module -> settings -> classes)) ? ' ' . $module -> settings -> classes : ''; ?>
	"
	method="<?= (!empty($module -> settings -> method)) ? $module -> settings -> method : 'get'; ?>"
>
	
	<?php if (!empty($module -> status)) : ?>
		<p class="form_status">
			<?= ($module -> status === 'complete') ? $lang -> form -> complete : $lang -> form -> fail; ?>
			
		</p>
	<?php endif; ?>
	
	<input type="hidden" name="query" value="mailform">
	<input type="hidden" name="name" value="<?= $module -> param; ?>">
	<input type="text" name="check" value="" style="display:none!important;">
	
	<?php foreach ($module -> settings -> form as $item) : ?>
		<?php if ($item -> type === 'textarea') : ?>
			<textarea
				class="
					form_item
					form_item__<?= $item -> type; ?>
					<?= ($item -> name !== $item -> type) ? ' form_item__' . $item -> name : '' ; ?>
					form_<?= $module -> param; ?>__<?= $item -> name; ?>
					<?= ($item -> name === str_replace('fail_', '', $module -> status)) ? ' form_item__fail' : ''; ?>
				"
				name="<?= $module -> param; ?>[<?= $item -> name; ?>]"
				<?= (!empty($item -> text)) ? ' placeholder="' . $item -> text . '"' : ''; ?>
				<?= ($module -> status === 'complete') ? ' disabled' : ''; ?>
			><?= ($module -> status !== 'complete') ? $module -> data[$item -> name] : ''; ?></textarea>
		<?php else : ?>
			<input
				class="
					form_item
					form_item__<?= $item -> type; ?>
					<?= ($item -> name !== $item -> type) ? ' form_item__' . $item -> name : '' ; ?>
					form_<?= $module -> param; ?>__<?= $item -> name; ?>
					<?= ($item -> name === str_replace('fail_', '', $module -> status)) ? ' form_item__fail' : ''; ?>
				"
				type="<?= $item -> type; ?>"
				name="<?= $module -> param; ?>[<?= $item -> name; ?>]"
				<?= (!empty($item -> text)) ? ' placeholder="' . $item -> text . '"' : ''; ?>
				<?= ($module -> status === 'complete') ? ' disabled' : ' value="' . $module -> data[$item -> name] . '"'; ?>
			>
		<?php endif; ?>		
	<?php endforeach; ?>
	
	<button
		type="submit"
		class="form_button form_<?= $module -> param; ?>__submit"
		<?= ($module -> status === 'complete') ? ' disabled' : ''; ?>
	>
		<?= $lang -> action -> send; ?>
	</button>
	
</form>