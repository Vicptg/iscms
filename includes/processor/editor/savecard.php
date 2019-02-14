<?php
defined('isQUERY') or die;

$status = $_POST['query'];
$data = $_POST['data'];

$projectID = $_COOKIE['PID'];

saveCard($status, $projectID, $data);

?>