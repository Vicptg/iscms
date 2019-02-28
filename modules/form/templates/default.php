<?php defined('isCMS') or die; ?>

<form
	id="<?= $module -> param; ?>"
	class="
		form
		form_<?= $module -> param; ?>
	"
	method="<?= (!empty($module -> settings -> get)) ? 'get' : 'post'; ?>"
>
	
	<input type="hidden" name="query" value="<?= $module -> settings -> type; ?>">
	<input type="hidden" name="param" value="<?= $module -> param; ?>">
	<input type="hidden" name="hash" value="<?= datacrypt(time()); ?>">
	<?php if ($module -> settings -> type === 'authorisation') : ?><input type="hidden" name="data[onlyuser]" value="1"><?php endif; ?>
	<?php if ($module -> settings -> type === 'form') : ?><input type="text" name="check" value="" style="display:none!important;"><?php endif; ?>
	
	<?php foreach ($module -> settings -> form as $item) : ?>
		
		<?php $module -> var['verification'] .= '"' . $item -> name . '":"' . $item -> verify . '",'; ?>
		
		<div class="
			form_<?= $module -> param; ?>_group
			<?= (!empty($module -> settings -> bootstrap)) ? 'form-group' : ''; ?>
			<?= (!empty($item -> required) || !empty($module -> settings -> required)) ? 'form_' . $module -> param . '_group__required' : ''; ?>
		">
			<?php if (!empty($item -> label) || !empty($module -> settings -> bootstrap)) : ?>
			<label
				for="<?= 'form_' . $module -> param . '_field__' . $item -> name; ?>"
				class="form_<?= $module -> param; ?>_label"
			>
				<?= (!empty($item -> label)) ? datalang($item -> label, 'names') : datalang($item -> name, 'form'); ?>
			</label>
			<?php endif; ?>
			
			<?php
				
				if ($item -> type === 'submit') {
					echo '<button ';
				} elseif ($item -> type === 'textarea') {
					echo '<textarea ';
				} else {
					echo '<input ';
				}
				
				echo 'type="' . $item -> type . '" ';
				echo 'name="data[' . $item -> name . ']" ';
				
				if (!empty($item -> label) || !empty($module -> settings -> bootstrap)) {
					echo 'id="form_' . $module -> param . '_field__' . $item -> name . '" ';
				}
				
				echo 'class="' .
					'form_' . $module -> param . '_field ' .
					'form_' . $module -> param . '_field__' . $item -> name;
				
				if (!empty($item -> required) || !empty($module -> settings -> required)) {
					echo ' form_' . $module -> param . '_field__required';
				}
				if (!empty($module -> settings -> bootstrap)) {
					echo ' form-control';
				}
				if (!empty($item -> class)) {
					echo ' ' . $item -> class;
				}
				
				echo '" ';
				
				if ($item -> type === 'submit') {
					echo 'value=true' ,
						'>' ,
						(!empty($item -> text)) ? datalang($item -> text, 'action') : datalang($item -> name, 'form') ,
						'</button>';
				} elseif ($item -> type === 'textarea') {
					echo 'placeholder="' , (!empty($item -> text)) ? datalang($item -> text, 'form') : datalang($item -> name, 'form') , '"' ,
						'>' ,
						($module -> settings -> type === $module -> var['name']) ? htmlentities(dataobject($module -> data, $item -> name)) : '' ,
						'</textarea>';
				} else {
					echo 'value="' , ($module -> settings -> type === $module -> var['name']) ? htmlentities(dataobject($module -> data, $item -> name)) : '' , '" ' ,
						'placeholder="' , (!empty($item -> text)) ? datalang($item -> text, 'form') : datalang($item -> name, 'form') , '"' ,
						(!empty($item -> required) || !empty($module -> settings -> required)) ? ' required' : '' ,
						'/>';
				}
				
			?>
			
			<?php if (!empty($item -> description)) : ?>
				<span class="
					form_<?= $module -> param; ?>_description
					<?= (!empty($module -> settings -> bootstrap)) ? 'form-text text-muted' : ''; ?>
				">
					<?= datalang($item -> description, 'names'); ?>
				</span>
			<?php endif; ?>
			
		</div>
		
	<?php endforeach; ?>
	
	<input type="hidden" name="data[verification]" value="<?= htmlentities('{' . substr($module -> var['verification'], 0, -1) . '}'); ?>">
	
	<?php if (!empty($module -> settings -> errors) && !empty($module -> data['errors'])) : ?>
		<div class="form_<?= $module -> param; ?>_error">
			<?php
				foreach ($module -> data['errors'] as $key => $item) {
					echo '<span>' , (!empty($lang -> errors -> $key)) ? $lang -> errors -> $key : $item , '</span>';
				}
			?>
		</div>
	<?php endif; ?>
	
	<?php if (( isset($attempts['ban']) && $attempts['ban'] > 0 ) || ( isset($result['ban']) && $result['ban'] > 0 )) : ?>
		<p><img class="captcha" src="/<?= NAME_LIBRARIES; ?>/kcaptcha/?<?= session_name(); ?>=<?= session_id(); ?>"></p>
		<input type="text" name="data[captcha]">
		<span class="error"><?= htmlentities($lang -> errors -> captcha) . ' '; ?></span>
	<?php endif; ?>
	
	<?php if (!empty($module -> settings -> submit)) : ?>
		<button type="submit" class="form_<?= $module -> param; ?>_submit"><?= datalang((is_string($module -> settings -> submit) ? $module -> settings -> submit : 'submit'), 'action'); ?></button>
	<?php endif; ?>
	
</form>

<?php //print_r($module -> data); ?>
