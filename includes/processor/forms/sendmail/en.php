<?php defined('isQUERY') or die;

$subject = 'Activating account on the site';
$message = '
<p>
	Hello!
</p>
<p>
	This letter has been send to you because you, or someone else, indicated this address when registering on the site
</p>
<p>
	<a href="http://' . $_SERVER['SERVER_NAME'] . '">
		http://' . $_SERVER['SERVER_NAME'] . '
	</a>
</p>
<p>
	To activate your account, please click here:
</p>
<p>
	<a href="http://' . $_SERVER['SERVER_NAME'] . '/?query=activation&email=' . $registration['email'] . '&verify=' . $registration['verify'] . '">
		http://' . $_SERVER['SERVER_NAME'] . '/?query=activation&email=' . $registration['email'] . '&verify=' . $registration['verify'] . '
	</a>
</p>
<p>
	or copy it and paste into the browser.
</p>
<p>
	If this email send to you by mistake, just ignore it. Unconfirmed data will be deleted from the server within 24 hours.
</p>
';

?>