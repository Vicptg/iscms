<?php
defined('isQUERY') or die;

setcookie('PID', '', time()-1000);
unset($_COOKIE['PID']);

header("Location: /".NAME_PERSONAL."/myprojects");
exit;
?>