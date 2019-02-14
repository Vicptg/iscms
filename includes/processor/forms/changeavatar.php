<?php defined('isQUERY') or die;

if (
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
	
	dbUser('update', array('id' => $userID, 'parameter' => 'avatar', 'value' => $file_new));
	
}

header("Location: /".NAME_PERSONAL."/profile");
exit;

?>