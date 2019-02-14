<?php
defined('isQUERY') or die;

$array = array();

foreach ($_POST['project'] as $key => $item) {
	$array[$key] = $item;
}

if($_POST['filedelete']) {
	$array['image'] = ' ';
} elseif (
	isset($_FILES['fileload']) &&
	$_FILES['fileload']['error'] == UPLOAD_ERR_OK &&
	$_FILES['fileload']['size'] < 4194304 &&
	(
		$_FILES['fileload']['type'] == 'image/jpeg' ||
		$_FILES['fileload']['type'] == 'image/gif' ||
		$_FILES['fileload']['type'] == 'image/png'
	)
) {
	$file = $_FILES['fileload']['tmp_name'];
	
	require PATH_INCLUDES . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'simpleimage' . DIRECTORY_SEPARATOR . 'simpleimage.php';
	
	$image = new SimpleImage();
	$image->load($file);
	$image->resizeToWidth(300);
	$image->save($file);
	
	$file_new = base64_encode(file_get_contents($file));
	$array['image'] = $file_new;
	
	unset($file);
	unset($image);
	unset($file_new);
}

if ($_POST['query'] == 'createproject') {
	$uniqid = editProject('create', $userID, $array);
	createContentDB('create', $uniqid);
	unset($uniqid);
} elseif ($_POST['query'] == 'updateproject') {
	editProject('update', $userID, $array);
}

unset($array);

header("Location: /".NAME_PERSONAL."/myprojects");
exit;

?>