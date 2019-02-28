<?php defined('isCMS') or die; ?>

<?php require_once $template -> base -> item -> frontend; ?> 

<?php
	if (in_array('cookiesagree', $template -> options)) {
		require_once $template -> base -> item -> cookies;
	}
?>

<?php
	if (in_array('inspect', $template -> options)) {
		require_once $template -> base -> item -> inspectend;
	}
?>

</body>
</html>