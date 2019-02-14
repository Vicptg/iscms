<?php
defined('isQUERY') or die;

$status = $_POST['query'];
$id = $_POST['data']['id'];
$projectID = $_COOKIE['PID'];

echo searchCard($status, $projectID, $id);

?>