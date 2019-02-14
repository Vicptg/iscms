<?php 
defined('isCMS') or die;

define('ROOT_USER', 'admin');
define('ROOT_PASS', '1234');
define('ROOT_EMAIL', 'admin@themarkofthewar.ru');
define('ROOT_LANG', 'ru');

define('DB_TYPE', 'nodb');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'themarkofthewar_db');
define('DB_PREFIX', 'jFZNp');

$paths = explode(DIRECTORY_SEPARATOR, PATH_BASE);
define('PATH_SITE',          implode(DIRECTORY_SEPARATOR, $paths));
define('NAME_ADMINISTRATOR', 'administrator');
define('PATH_ADMINISTRATOR', PATH_SITE . DIRECTORY_SEPARATOR . 'administrator');
define('NAME_ARTICLES', 'articles');
define('PATH_ARTICLES', PATH_SITE . DIRECTORY_SEPARATOR . 'articles');
define('NAME_CACHE', 'cache');
define('PATH_CACHE', PATH_SITE . DIRECTORY_SEPARATOR . 'cache');
define('NAME_INCLUDES', 'includes');
define('PATH_INCLUDES', PATH_SITE . DIRECTORY_SEPARATOR . 'includes');
define('NAME_LIBRARIES', 'libraries');
define('PATH_LIBRARIES', PATH_SITE . DIRECTORY_SEPARATOR . 'libraries');
define('NAME_MODULES', 'modules');
define('PATH_MODULES', PATH_SITE . DIRECTORY_SEPARATOR . 'modules');
define('NAME_TEMPLATES', 'templates');
define('PATH_TEMPLATES', PATH_SITE . DIRECTORY_SEPARATOR . 'templates');
define('NAME_UPLOAD', 'media');
define('PATH_UPLOAD', PATH_SITE . DIRECTORY_SEPARATOR . 'media');

define('NAME_PERSONAL', 'personal');
define('NAME_PRIVATE', 'private');
?>