<ul class="monitor">
	<?php foreach ($administrator -> settings -> monitor as $item) : ?>
	
	<?php if ($item && strpos($item, ':')) : ?>
	
	<?php $item = explode(':', $item); ?>
	<li>
		<p><?php foreach ($item as $k => $i) { echo ($k == 0) ? $i : ' > ' . $i; } ?></p>
		<p><?php foreach ($item as $k => $i) { ($k == 0) ? $p = $template -> $i : ((is_object($p)) ? $p = $p -> $i : $p = $p[$i]); } print_r($p); unset($p); ?>
	</li>
	
	<?php elseif ($item) : ?>
	
	<li>
		<p><?= $item; ?></p>
		<p><?php print_r($template -> $item); ?></p>
	</li>
	
	<?php endif; ?>
	<?php endforeach; ?>
</ul>