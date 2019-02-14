<?php defined('isQUERY') or die;

$subject = 'Активация учетной записи на сайте';
$message = '
<p>
	Здравствуйте!
</p>
<p>
	Это письмо пришло вам потому, что Вы, либо кто-то другой указал данный адрес при регистрации на сайте
</p>
<p>
	<a href="http://' . $_SERVER['SERVER_NAME'] . '">
		http://' . $_SERVER['SERVER_NAME'] . '
	</a>
</p>
<p>
	Для активации аккаунта перейдите по этой ссылке:
</p>
<p>
	<a href="http://' . $_SERVER['SERVER_NAME'] . '/?query=activation&email=' . $registration['email'] . '&verify=' . $registration['verify'] . '">
		http://' . $_SERVER['SERVER_NAME'] . '/?query=activation&email=' . $registration['email'] . '&verify=' . $registration['verify'] . '
	</a>
</p>
<p>
	Либо скопируйте ее и вставьте в окно браузера.
</p>
<p>
	Если это письмо пришло к Вам по ошибке, просто проигнорируйте его. Неподтвержденные данные удалятся с сервера в течение 24 часов.
</p>
';

?>