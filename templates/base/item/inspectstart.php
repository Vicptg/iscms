<?php defined('isCMS') or die;

if (in_array('inspect', $template -> options)) {
	
	$loadingLog = '';
	
	if (isset($query)) {
		
		$loadingLog .= 'this page was loaded with valid query!\n';
		$loadingLog .= 'query name is ' . ((!empty($query -> name)) ? '\"' . htmlentities($query -> name) . '\"' : 'none') . '\n';
		$loadingLog .= 'query status is ' . ((!empty($query -> status)) ? '\"' . htmlentities($query -> status) . '\"' : 'none') . '\n';
		$loadingLog .= 'query method is ' . ((!empty($query -> method)) ? '\"' . htmlentities($query -> method) . '\"' : 'none') . '\n';
		
		if (isset($query -> data) && (is_array($query -> data) || is_object($query -> data))) {
			$loadingLog .= 'query data is \"' . htmlentities(dataarraytostring($query -> data, ', ', true)) . '\"\n';
		}
		
		if (isset($query -> errors) && (is_array($query -> errors) || is_object($query -> errors))) {
			$loadingLog .= 'query errors is \"' . htmlentities(dataarraytostring($query -> errors, ', ', true)) . '\"\n\n';
		}
		
	}
	
}

?>

<script>
	// временные метки, когда началось выполнение скриптов (в js и unix формате)
	var loadingStart = performance.now();
	<?php $loadingStart = microtime(true); ?>
</script>
