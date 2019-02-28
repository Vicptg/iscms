<?php defined('isQUERY') or die;

if ($query -> name === 'cookiesagree') {
	cookie('AGREE', 1);
	header("Location: /");
}

?>