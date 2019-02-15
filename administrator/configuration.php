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
define('NAME_ARTICLES', 'articles');
define('NAME_CACHE', 'cache');
define('NAME_INCLUDES', 'includes');
define('NAME_LIBRARIES', 'libraries');
define('NAME_MODULES', 'modules');
define('NAME_TEMPLATES', 'templates');
define('NAME_UPLOAD', 'media');

define('PATH_ADMINISTRATOR', PATH_SITE . DIRECTORY_SEPARATOR . NAME_ADMINISTRATOR);
define('PATH_ARTICLES', PATH_SITE . DIRECTORY_SEPARATOR . NAME_ARTICLES);
define('PATH_CACHE', PATH_SITE . DIRECTORY_SEPARATOR . NAME_CACHE);
define('PATH_INCLUDES', PATH_SITE . DIRECTORY_SEPARATOR . NAME_INCLUDES);
define('PATH_LIBRARIES', PATH_SITE . DIRECTORY_SEPARATOR . NAME_LIBRARIES);
define('PATH_MODULES', PATH_SITE . DIRECTORY_SEPARATOR . NAME_MODULES);
define('PATH_TEMPLATES', PATH_SITE . DIRECTORY_SEPARATOR . NAME_TEMPLATES);
define('PATH_UPLOAD', PATH_SITE . DIRECTORY_SEPARATOR . NAME_UPLOAD);

define('NAME_PERSONAL', 'personal');
define('NAME_PRIVATE', 'private');
?>