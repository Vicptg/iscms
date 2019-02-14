<section class="frontend">
	<div class="panel">
		<?php require_once 'html/panel.php'; ?>
	</div>
	<div class="objects">
		<?php
			if (
				isset($administrator -> settings -> monitor) &&
				is_array($administrator -> settings -> monitor) &&
				count($administrator -> settings -> monitor)
			) {
				require_once 'html/monitor.php';
			}
			
			require_once 'html/global.php';
		?>
	</div>	
</section>

<script>
<?php
	
	require_once 'js/frontend.js';
	
	if (!$administrator -> in && $administrator -> settings -> langview) {
		
		function langAllMarks($arr, $pkey = false) {
			foreach ($arr as $key => $item) {
				if (is_array($item) || is_object($item)) {
					$item = langAllMarks($item, $key);
					if (is_array($arr)) {
						$arr[$key] = $item;
					} elseif (is_object($arr)) {
						$arr -> $key = $item;
					}
				} else {
					$item = '<span class="frontend admin_lang_view" data-lang="' . (($pkey) ? $pkey . ':' : '') . $key . '">' . $item . '</span>';
					if (is_array($arr)) {
						$arr[$key] = $item;
					} elseif (is_object($arr)) {
						$arr -> $key = $item;
					}
				}
			}
			return $arr;
		}
		
		$lang = langAllMarks($lang);
		
		require_once 'js/langview.js';
		
	}
	
?>
</script>

<link rel="stylesheet/less" type="text/css" href="<?= $template -> url; ?>/templates/administrator/html/frontend/less/frontend.less" />
<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.min.js"></script>