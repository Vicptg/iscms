<?php
defined('isQUERY') or die;

$status = $_POST['query'];
$id = $_POST['data']['id'];
$parameter = $_POST['data']['parameter'];
$value = $_POST['data']['value'];
$projectID = $_COOKIE['PID'];

refreshCard($status, $projectID, $id, $parameter, $value);

?>