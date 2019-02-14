<?php
$module -> table = dataloadcsv($module -> path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $module -> param);
$module -> data = $module -> table -> data;
$module -> settings = $module -> table -> settings;
unset($module -> table);
?>