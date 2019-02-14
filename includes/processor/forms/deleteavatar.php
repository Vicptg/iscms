<?php defined('isQUERY') or die;

dbUser('update', array('id' => $userID, 'parameter' => 'avatar', 'value' => ''));

header("Location: /".NAME_PERSONAL."/profile");
exit;

?>