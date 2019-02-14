<?php defined('isCMS') or die;

class Lang_detect {
	var $language = null;
	
	public function __construct() {
		if ($list = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']) : null) {
			if (preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', $list, $list)) {
				$this->language = array_combine($list[1], $list[2]);
				foreach ($this->language as $n => $v)
					$this->language[$n] = $v ? $v : 1;
				arsort($this->language, SORT_NUMERIC);
			}
		} else $this->language = array();
	}
	
	public function getBestMatch($default, $langs) {
		$languages=array();
		foreach ($langs as $lang => $alias) {
			if (is_array($alias)) {
				foreach ($alias as $alias_lang) {
					$languages[strtolower($alias_lang)] = strtolower($lang);
				}
			} else $languages[strtolower($alias)]=strtolower($lang);
		}
		foreach ($this->language as $l => $v) {
			$s = strtok($l, '-'); // убираем то что идет после тире в языках вида "en-us, ru-ru"
			if (isset($languages[$s])) return $languages[$s];
		}
		return $default;
	}
}

$langs = ['ru' => ['ru'], 'en' => ['en']];

if (file_exists(PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'languages.ini')) {
	$langs = file_get_contents(PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'languages.ini');
	$langs = preg_replace('/\r\n/', '', $langs);
	$langs = json_decode($langs, true)['langs'];
}

$lang = new Lang_detect();
$currlang = $lang->getBestMatch(ROOT_LANG, $langs);

/*
if ($_GET['lang']) {
	$currlang = $_GET['lang'];
} elseif ($_POST['lang']) {
	$currlang = $_POST['lang'];
} elseif ($_COOKIE['LANG']) {
	$currlang = $_COOKIE['LANG'];
}
*/

if (!array_key_exists($currlang, $langs) && ROOT_LANG !== 'auto') {
	$currlang = ROOT_LANG;
}

unset($lang, $langs);

?>