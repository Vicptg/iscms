<?php defined('isCMS') or die; ?>

<?php require_once $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'opening.php'; ?>
<?php require_once $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'hidden.php'; ?>

<label>Ваше имя</label>
<input type="text" name="user" value="<?= $order -> user; ?>">

<label>Email</label>
<input type="text" name="email" value="<?= $order -> email; ?>">

<label>Телефонный номер</label>
<input type="text" name="phone" value="<?= $order -> phone; ?>">

<label>Номер</label>
<?php require_once $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'place.php'; ?>

<label>Дата</label>
<?php require_once $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'date.php'; ?>

<label>Время</label>
<?php require_once $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'time.php'; ?>

<button type="submit">Забронировать</button>

<?php require_once $module -> path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'ending.php'; ?>
