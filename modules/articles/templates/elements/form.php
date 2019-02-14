<?php defined('isCMS') or die; ?>

<form class="articles_filter" method="post" action="/index.php">
	
	<input type="hidden" name="query" value="<?= $module -> name; ?>">
	<input type="hidden" name="hash" value="<?= datacrypt(time()); ?>">
	<input type="hidden" name="status" value="<?= $module -> param; ?>">
	
	<?php
		if (
			isset($module -> settings -> filter -> options) &&
			!empty($module -> settings -> filter -> options -> ajax)
		) :
	?>
		<input type="hidden" name="data[ajax]" value="<?= $module -> settings -> filter -> options -> ajax; ?>">
	<?php endif; ?>
	
	<?php
		
		if (file_exists($module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> param . '_filter.php')) :
			require $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $module -> param . '_filter.php';
		else :
			
	?>
		
		<?php foreach ($module -> var['filter_fields'] as $module -> var['name'] => $module -> var['current']) : ?>
			
			<?php
				if (
					isset($module -> settings -> filter -> options) &&
					!empty($module -> settings -> filter -> options -> wrapper)
				) :
			?>
				<div class="articles_filter__wrapper">
			<?php endif; ?>
			
			<?php
				if (
					isset($module -> settings -> filter -> options) &&
					!empty($module -> settings -> filter -> options -> label)
				) :
			?>
				<label for="articles_filtername__<?= $module -> var['name']; ?>">
					<?= dataobject($lang -> filter, $module -> var['name'], true); ?>
				</label>
			<?php endif; ?>
			
			
			
			
			<?php
				$module -> var['filter_type'] = dataobject($module -> settings -> filter -> fields, $module -> var['name']);
				if (!$module -> var['filter_type']) $module -> var['filter_type'] = 'select';
				//print_r($module -> var['filter_type']);
			?>
			
			<input
				name="data[<?= $module -> var['name']; ?>][type]"
				value="<?= $module -> var['filter_type']; ?>"
				type="hidden"
			>
			
			<?php
				if (
					array_key_exists($module -> var['name'], (array) $module -> var['filter'])
				) {
					$module -> var['filter_values'] = datasplit( dataobject($module -> var['filter'], $module -> var['name']), '\s,+\-');
				} else {
					$module -> var['filter_values'] = [];
				}
				//print_r($module -> var['filter_values']);
			?>
			
			
			
			<?php
				if (
					$module -> var['filter_type'] === 'radio' ||
					$module -> var['filter_type'] === 'and' ||
					$module -> var['filter_type'] === 'or'
				) :
			?>
				
				<?php
					if ($module -> var['filter_type'] === 'radio') :
						$module -> var['input_type'] = 'radio';
				?>
					
					<div class="
						articles_filtername__<?= $module -> var['name']; ?>
						articles_filter__<?= $module -> var['filter_type']; ?>
					">
						<input
							id="articles_filtername__<?= $module -> var['name']; ?>"
							name="data[<?= $module -> var['name']; ?>]"
							value=""
							type="<?= $module -> var['input_type']; ?>"
						>
						<?= $lang -> action -> all; ?>
					</div>
					
				<?php
					else :
						$module -> var['input_type'] = 'checkbox';
					endif;
				?>
				
				<?php foreach ($module -> var['current'] as $module -> var['key'] => $module -> var['item']) : ?>
					
					<div class="
						articles_filtername__<?= $module -> var['name']; ?>
						articles_filter__<?= $module -> var['filter_type']; ?>
					">
						
						<input
							id="articles_filtername__<?= $module -> var['name']; ?>"
							name="data[<?= $module -> var['name']; ?>][<?= $module -> var['key']; ?>]"
							value="<?= $module -> var['item']; ?>"
							type="<?= $module -> var['input_type']; ?>"
							<?php
								if (
									count($module -> var['filter_values']) &&
									in_array($module -> var['item'], $module -> var['filter_values'])
								) {
									echo 'checked';
								}
							?>
						>
						<?//= dataobject($lang -> filter, $module -> var['item'], true); ?>
						<?= (dataobject($lang -> filter, $module -> var['item'], true)) ? dataobject($lang -> filter, $module -> var['item'], true) : $module -> var['item']; ?>
					</div>
					
				<?php endforeach; ?>
				
			<?php
				elseif ($module -> var['filter_type'] === 'search') :
			?>
				
				<div class="
					articles_filtername__<?= $module -> var['name']; ?>
					articles_filter__<?= $module -> var['filter_type']; ?>
				">
					<input
						name="data[<?= $module -> var['name']; ?>]"
						list="articles_filtername__<?= $module -> var['name']; ?>_list"
					>
					<datalist id="articles_filtername__<?= $module -> var['name']; ?>_list">
						
						<?php foreach ($module -> var['current'] as $module -> var['item']) : ?>
							<option value="<?= $module -> var['item']; ?>">
								<?= dataobject($lang -> filter, $module -> var['item'], true); ?>
							</option>
						<?php endforeach; ?>
						
					</datalist>
				</div>
				
			<?php
				elseif ($module -> var['filter_type'] === 'numeric') :
			?>
				
				<div class="
					articles_filtername__<?= $module -> var['name']; ?>
					articles_filter__<?= $module -> var['filter_type']; ?>
				">
					<input
						name="data[<?= $module -> var['name']; ?>][0]"
						value="<?= (count($module -> var['filter_values'])) ? $module -> var['filter_values'][0] : ''; ?>"
					>
					-
					<input
						name="data[<?= $module -> var['name']; ?>][1]"
						value="<?= (count($module -> var['filter_values'])) ? $module -> var['filter_values'][1] : ''; ?>"
					>
				</div>
				
			<?php
				elseif (
					$module -> var['filter_type'] === 'range' ||
					$module -> var['filter_type'] === 'range_bootstrap' ||
					$module -> var['filter_type'] === 'range_jqueryui'
				) :
					
					sort($module -> var['filter_fields'][$module -> var['name']], SORT_NUMERIC);
					if ($module -> var['filter_fields'][$module -> var['name']][0] > 0) {
						$module -> var['filter_fields'][$module -> var['name']][0] = 0;
					}
					//print_r($module -> var['filter_fields'][$module -> var['name']]);
					
					$module -> var['filter_range_min'] = reset($module -> var['filter_fields'][$module -> var['name']]);
					$module -> var['filter_range_max'] = end($module -> var['filter_fields'][$module -> var['name']]);
					
					//print_r($template -> libraries);
			?>
				
				<div class="
					articles_filtername__<?= $module -> var['name']; ?>
					articles_filter__<?= $module -> var['filter_type']; ?>
				">
					
					<?php
						if (
							$module -> var['filter_type'] === 'range_bootstrap' &&
							in_array('bootstrapslider', $template -> libraries)
						) :
					?>
						
						<script>
							$(function() {
								$("#articles_filtername__<?= $module -> var['name']; ?>_amount")
									.bootstrapSlider({
										min: <?= $module -> var['filter_range_min']; ?>,
										max: <?= $module -> var['filter_range_max']; ?>,
										value: [<?= $module -> var['filter_range_min'] . ',' . $module -> var['filter_range_max']; ?>]
									})
									.on("slide", function(slideEvt) {
										$("#articles_filtername__<?= $module -> var['name']; ?>_min").text(slideEvt.value[0]);
										$("#articles_filtername__<?= $module -> var['name']; ?>_max").text(slideEvt.value[1]);
									})
									.on("slideStop", function(slideEvt) {
										$("#articles_filtername__<?= $module -> var['name']; ?>_amount").val(slideEvt.value[0] + "-" + slideEvt.value[1]);
									});
							});
						</script>
						
						<span id="articles_filtername__<?= $module -> var['name']; ?>_min"><?= $module -> var['filter_range_min']; ?></span>
						<input
							id="articles_filtername__<?= $module -> var['name']; ?>_amount"
							type="text"
							name="data[<?= $module -> var['name']; ?>]"
							value=""
						>
						<span id="articles_filtername__<?= $module -> var['name']; ?>_max"><?= $module -> var['filter_range_max']; ?></span>
						
					<?php
						elseif (
							$module -> var['filter_type'] === 'range_jqueryui' &&
							in_array('jqueryui', $template -> libraries)
						) :
					?>
						
						<script>
							$(function() {
								$("#articles_filtername__<?= $module -> var['name']; ?>_range").slider({
									range: true,
									min: <?= $module -> var['filter_range_min']; ?>,
									max: <?= $module -> var['filter_range_max']; ?>,
									values: [<?= $module -> var['filter_range_min'] . ', ' . $module -> var['filter_range_max']; ?>],
									slide: function(event, ui) {
										$("#articles_filtername__<?= $module -> var['name']; ?>_amount").val(ui.values[0] + " - " + ui.values[1]);
									}
								});
								$("#articles_filtername__<?= $module -> var['name']; ?>_amount").val(
									$("#articles_filtername__<?= $module -> var['name']; ?>_range").slider("values", 0) + " - " + $("#articles_filtername__<?= $module -> var['name']; ?>_range").slider("values", 1)
								);
							});
						</script>
						
						<label for="articles_filtername__<?= $module -> var['name']; ?>_amount"></label>
						<input
							type="text"
							id="articles_filtername__<?= $module -> var['name']; ?>_amount"
							name="data[<?= $module -> var['name']; ?>]"
							readonly
						>
						<div id="articles_filtername__<?= $module -> var['name']; ?>_range"></div>
						
					<?php else : ?>
						
						<div id="articles_filtername__<?= $module -> var['name']; ?>_range">
							<span id="articles_filtername__<?= $module -> var['name']; ?>_min"><?= (count($module -> var['filter_values'])) ? $module -> var['filter_values'][0] : $module -> var['filter_range_min']; ?></span>
							-
							<span id="articles_filtername__<?= $module -> var['name']; ?>_max"><?= (count($module -> var['filter_values'])) ? $module -> var['filter_values'][1] : $module -> var['filter_range_max']; ?></span>
						</div>
						
						<div id="articles_filtername__<?= $module -> var['name']; ?>_amount">
							<input
								name="data[<?= $module -> var['name']; ?>][0]"
								value="<?= (count($module -> var['filter_values'])) ? $module -> var['filter_values'][0] : $module -> var['filter_range_min']; ?>"
								type="range"
								min="<?= $module -> var['filter_range_min']; ?>"
								max="<?= $module -> var['filter_range_max']; ?>"
								onchange="document.getElementById('articles_filtername__<?= $module -> var['name']; ?>_min').innerHTML = this.value;"
							>
							<input
								name="data[<?= $module -> var['name']; ?>][1]"
								value="<?= (count($module -> var['filter_values'])) ? $module -> var['filter_values'][1] : $module -> var['filter_range_max']; ?>"
								type="range"
								min="<?= $module -> var['filter_range_min']; ?>"
								max="<?= $module -> var['filter_range_max']; ?>"
								onchange="document.getElementById('articles_filtername__<?= $module -> var['name']; ?>_max').innerHTML = this.value;"
							>
						</div>
						
					<?php endif; ?>
					
				</div>
				
			<?php else : ?>
				
				<div class="
					articles_filtername__<?= $module -> var['name']; ?>
					articles_filter__<?= $module -> var['filter_type']; ?>
				">
					
					<select
						id="articles_filtername__<?= $module -> var['name']; ?>"
						name="data[<?= $module -> var['name']; ?>]"
					>
						
						<option value=""></option>
						
						<?php foreach ($module -> var['current'] as $module -> var['item']) : ?>
							<option
								value="<?= $module -> var['item']; ?>"
								<?php
									if (
										count($module -> var['filter_values']) &&
										in_array($module -> var['item'], $module -> var['filter_values'])
									) {
										echo 'selected';
									}
								?>
							>
								<?= dataobject($lang -> filter, $module -> var['item'], true); ?>
							</option>
						<?php endforeach; ?>
						
					</select>
					
				</div>
				
			<?php endif; ?>
			
			
			
			<?php
				if (
					isset($module -> settings -> filter -> options) &&
					!empty($module -> settings -> filter -> options -> wrapper)
				) :
			?>
				</div>
			<?php endif; ?>
			
		<?php endforeach; ?>
		
		<?php
			
			if (
				isset($module -> settings -> filter -> items) &&
				!empty($module -> settings -> filter -> items -> allow) &&
				!empty($module -> settings -> filter -> items -> min) &&
				!empty($module -> settings -> filter -> items -> max)
			) :
			
		?>
			<select name="data[items]">
				<option value=""></option>
				<?php
					for ($i = $module -> settings -> filter -> items -> min; $i <= $module -> settings -> filter -> items -> max; $i++) :
					if (
						isset($module -> settings -> filter -> items -> multiply) &&
						is_numeric($module -> settings -> filter -> items -> multiply)
					) {
						$a = $i * (int) $module -> settings -> filter -> items -> multiply;
					} else {
						$a = $i;
					}
				?>
					<option
						value="<?= $a; ?>"
						<?php
							if ($a == $module -> var['filter_items']) {
								echo 'selected';
							}
						?>
					>
						<?= $a; ?>
					</option>
				<?php endfor; ?>
			</select>
		<?php endif; ?>
	
	<?php endif; ?>
	
	<?php
		if (
			isset($module -> settings -> filter -> options) &&
			!empty($module -> settings -> filter -> options -> reset)
		) :
	?>
		<button type="reset">
			<?= $lang -> action -> cancel; ?>
		</button>
	<?php endif; ?>
	
	<button type="submit">
		<?= $lang -> action -> send; ?>
	</button>
	
</form>