<?php

class htmlElement {

	/*
	*  $a = new htmlElement('div', ['style1', 's2', 's3'], 'new', $data);
	*  создаем экземпляр класса и сразу же при этом выводится объект (тег)
	*  аргументы по-порядку:
	*    элемент (обязательно, остальные аргуметы - нет)
	*    стили (строкой или массивом)
	*    id
	*    данные (массивом в стиле 'название' => 'значение', тогда формируется data-название="значение")
	*    area (по аналогии с данными)
	*
	*  $a -> close();
	*  закрывает объект (тег)
	*
	*  пример вызова:
	*  $data = ['id' => '1', 'name' => 'my', 'target' => '#mod'];
	*  $a = new htmlElement('div', ['style1', 's2', 's3'], 'new', $data);
	*    $b = new htmlElement('p', 'basta');
	*      echo '45635062450598yer!!!';
	*    $b -> close();
	*  $a -> close();
	*  unset($a, $b);
	*  вывод:
	*  <div class="style1 s2 s3" id="new" data-id="1" data-name="my" data-target="#mod"><p class="basta">45635062450598yer!!!</p></div>
	*
	*  Вот такой вот маленький и очень полезный класс!!!
	*/
	
	function add($type = false, $s = false) {
		
		//if (!$type || !$s || !count($s)) {
		if (empty($type) || empty($s)) {
			return false;
		}
		
		echo ' ' . $type . '="';
		
		if (!is_array($s)) {
			echo $s;
		} elseif (count($s) == 1) {
			echo $s[0];
		} else {
			echo array_shift($s);
			foreach ($s as $i) {
				echo ' ' . $i;
			}
		}
		
		echo '"';
		
	}
	
	function data($type = false, $data = false) {
		
		if (!$type || !$data || !is_array($data) || !count($data)) {
			return false;
		}
		
		foreach ($data as $k => $i) {
			echo ' ' . $type . '-' . $k . '="' . $i . '"';
		}
		
	}
	
	// это - конструктор класса, т.е. функция, которая запускается при создании экземпляра класса
	// как раз то, что нам нужно, чтобы создавать элементы
	function __construct($tag = false, $class = false, $id = false, $data = false, $area = false) {
		
		$allowtags = ['div', 'p', 'span', 'nav', 'ul', 'li', 'i'];
		
		if (!$tag || !in_array($tag, $allowtags)) {
			return false;
		}
		
		$this -> tag = $tag;
		echo '<' . $tag;
		self::add('class', $class);
		self::add('id', $id);
		self::data('data', $data);
		self::data('area', $area);
		echo '>';
		
	}
	
	function close() {
		
		if (!$this -> tag) {
			return false;
		}
		
		echo '</' . $this -> tag . '>';
		$this -> tag = null;
	}
		
}

?>