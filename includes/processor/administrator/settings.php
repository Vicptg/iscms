<?php
defined('isQUERY') or die;

// settings

$target = $query -> data -> target;
$code = $query -> data -> code;
$data = json_encode($query -> data -> lang, JSON_UNESCAPED_UNICODE);

//print_r($data);
file_put_contents($code . '.lng', $data);
print_r($query -> data);

exit;
?>