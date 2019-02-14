<?php defined('isQUERY') or die;

$site = $_SERVER['SERVER_NAME'];

$to = $registration['email'];

$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
$headers .= "From: no-reply@" . $site . "\r\n"; 
$headers .= "Reply-To: no-reply@" . $site . "\r\n"; 
$headers .= "X-Mailer: PHP/" . phpversion();

//$headers .= "List-Unsubscribe: <mailto:rm-0bxg2hykazs74dxau3ua1cq2cxdkebs@e.victoriassecret.com>"

$subject = $site;
$message = '
	<a href="http://' . $site . '/?query=activation&email=' . $registration['email'] . '&verify=' . $registration['verify'] . '">
		http://' . $site . '/?query=activation&email=' . $registration['email'] . '&verify=' . $registration['verify'] . '
	</a>
';

require 'sendmail' . DIRECTORY_SEPARATOR . $currlang . '.php';

$result = mail($to, $subject, $message, $headers);

if($result) {
	header("Location: /index.php?error=mail&lang=" . $currlang);
	exit;
} else {
	header("Location: /index.php?error=500&lang=" . $currlang);
	exit;
}

?>