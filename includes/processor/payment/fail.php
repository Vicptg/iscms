<?php defined('isCMS') or die;

$error_number = $_GET['error'];
$error_lang = $_GET['lang'];
$error_description = '';

if ($error_lang = 'ru') { $error_name = 'Ошибка'; }
else { $error_name = 'Error'; }

switch ($error_number) {
	case 'mail':
		if ($error_lang = 'ru') {
			$error_name = 'Статус';
			$error_description = 'Успешно';
			$error_message = '
				<p>
					Форма отправлена! Для активации учетной записи ее необходимо подтвердить. Подтверждение направлено на указанный почтовый ящик.
				</p>
			';
		}
		else {
			$error_name = 'Status';
			$error_description = 'Complete';
			$error_message = '
				<p>
					The form is submitted! To activate your account, you must confirm it. Confirmation is sent to the specified e-mail.
				</p>
			';
		}
	break;
	case 201:
		if ($error_lang = 'ru') {
			$error_name = 'Статус';
			$error_description = 'Успешно';
			$error_message = '
				<p>
					Ваши данные были успешно приняты. Для доступа к сайту, перейдите на главную страницу: <a href="/">http://' . $_SERVER['SERVER_NAME'] . '</a>.
				</p>
			';
		}
		else {
			$error_name = 'Status';
			$error_description = 'Created';
			$error_message = '
				<p>
					Your data has been successfully accepted. To access the site, go to the main page: <a href="/">http://' . $_SERVER['SERVER_NAME'] . '</a>.
				</p>
			';
		}
	break;
	case 400:
		if ($error_lang = 'ru') {
			$error_description = 'Неверный запрос';
			$error_message = '
				<p>
					Вами был отправлен неверный запрос. Для того, чтобы продолжить работу, перейдите на главную страницу: <a href="/">http://' . $_SERVER['SERVER_NAME'] . '</a>.
				</p>
			';
		}
		else {
			$error_description = 'Bad Request';
			$error_message = '
				<p>
					You have been sent an invalid request. In order to continue working, go to the main page: <a href="/">http://' . $_SERVER['SERVER_NAME'] . '</a>.
				</p>
			';
		}
	break;
	case 401:
		if ($error_lang = 'ru') { $error_description = 'Нет права на доступ'; }
		else { $error_description = 'Unauthorized'; }
	break;
	case 403:
		if ($error_lang = 'ru') {
			$error_description = 'Доступ запрещен';
			$error_message = '
				<p>
					На сайте установлена защита от взлома и атак. С вашего адреса поступило слишком много неверных запросов. Доступ к сайту для вас будет восстановлен через 1 час.
				</p>
				<p>
					Вы можете также попробовать восстановить доступ самостоятельно, перейдя по этой ссылке: <a href="/?query=restore">Форма восстановления доступа</a>
				</p>
				<p>
					Если блокировка произошла по ошибке, вы также можете обратиться в службу поддержки.
				</p>
			';
		}
		else {
			$error_description = 'Forbidden';
			$error_message = '
				<p>
					The site is protected against hacking and attacks. You have exceeded the maximum connection limit. Access to the site for you will be restored after 1 hour.
				</p>
				<p>
					You can also try to restore access yourself by clicking on this link: <a href="/?query=restore">Access restore form</a>
				</p>
				<p>
					You can also contact to the support team.
				</p>
			';
		}
	break;
	case 404:
		if ($error_lang = 'ru') { $error_description = 'Не найдено'; }
		else { $error_description = 'Not Found'; }
	break;
	case 500:
		if ($error_lang = 'ru') { $error_description = 'Внутренняя ошибка сервера'; }
		else { $error_description = 'Internal Server Error'; }
	break;
	case 502:
		if ($error_lang = 'ru') {
			$error_description = 'Ошибка подключения';
			$error_message = '
				<p>
					На сайте установлена защита от взлома и атак. С вашего адреса поступил опасный запрос. Для обеспечения защиты ваших данных, доступ к сайту с вашего адреса заблокирован на 1 час.
				</p>
				<p>
					Если Вы хотите ускорить процесс разблокировки, напишите в службу поддержки.
				</p>
			';
		}
		else {
			$error_description = 'Bad Gateway';
			$error_message = '
				<p>
					The site is protected against hacking and attacks. A dangerous request came from your address. To ensure the protection of your data, access to the site from your address is blocked for 1 hour.
				</p>
				<p>
					If you want to speed up the unlock process, write to the support team.
				</p>
			';
		}
	break;
	case 504:
		if ($error_lang = 'ru') { $error_description = 'Превышено время ожидания'; }
		else { $error_description = 'Gateway Timeout'; }
	break;
}
?>

<style>
@import url('https://fonts.googleapis.com/css?family=PT+Sans|PT+Serif');
body {
	margin: 0;
	padding: 0;
}
div {
	display: block;
	padding: 20px 0;
	margin: 25% auto 0 auto;
	background: #eee;
}
div p {
	width: 50%;
	font: 400 24px 'PT Serif', serif;
	margin: auto;
	padding: 10px 0;
}
@media (min-width: 1200px) { div { transform: translateY(-50%); } }
@media (max-width: 1199px) { div p { width: 75%; font-size: 18px; } }
@media (max-width: 767px) { div p { width: 90%; font-size: 12px; } }
</style>

<div>
	<p align="center">
		<strong>
		<?php echo $error_name . ' ' . $error_number . ': ' . $error_description; ?>
		</strong>
	</p>
	
	<?php echo $error_message; ?>

	<p>
		<?php if ($error_lang = 'ru') : ?>
			Обратиться в службу поддержки: 
		<?php else : ?>
			Contact to the support team:
		<?php endif; ?>
		<a href="mailto: mail@mail.com?subject=<?php echo $error_name . '_' . $error_number; ?>">mail@mail.com</a>
	</p>
</div>

<?php exit; ?>