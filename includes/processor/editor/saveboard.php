<?php
defined('isQUERY') or die;

$status = $_POST['query'];
$name = $_POST['data']['name'];
$data = $_POST['data']['data'];
$projectID = $_COOKIE['PID'];

saveBoard($status, $projectID, $name, $data);

?>