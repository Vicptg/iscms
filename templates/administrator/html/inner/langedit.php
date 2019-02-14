<?php

function inputsimple($key, $item, $parent = false) {
	return '<input type="text" class="form-control" value="' . $item . '" aria-describedby="basic-addon-' . $key . '" name="data[lang]' . $parent . '">';
}

function inputlang($arr, $parent = false) {
	
	foreach ($arr as $key => $item) {
		
		$p = false;
		
		if (!$parent) {
			if (is_object($item)) {
				$parent = $key;
			}
		} else {
			
			if (substr($parent, 0, 1) === '[' && substr($parent, -1) === ']') {
				$parent = substr($parent, 1, -1);
			}
			
			$p = $parent;
			$parent = '[' . $parent . '][' . $key . ']';
		}
		
		if (is_object($item)) {
			
			echo '<div class="wrapper-group">';
			echo '<div class="label">' . $key . '</div>';
			echo '<div class="sub-group ' . $key . '">';
			inputlang($item, $parent);
			echo '</div>';
			echo '</div>';
			
		} elseif (is_array($item)) {
			
			echo '<div class="input-group array">';
			echo '<div class="input-group-prepend">';
			echo '<span class="input-group-text" id="basic-addon-' . $key . '">' . $key . '</span>';
			echo '</div>';
			echo '<div class="input-group-array">';
			
			foreach ($item as $k => $i) {
				//echo inputsimple($k, $i, $parent . '[array][' . $k . ']');
				echo inputsimple($k, $i, $parent . '[' . $k . ']');
			}
			
			echo '</div>';
			echo '</div>';
			
		} else {
			
			echo '<div class="input-group">';
			echo '<div class="input-group-prepend">';
			echo '<span class="input-group-text" id="basic-addon-' . $key . '">' . $key . '</span>';
			echo '</div>';
			echo inputsimple($key, $item, ($parent) ? $parent : '[' . $key . ']');
			echo '</div>';
			
		}
		
		$parent = $p;
	}
	
}

?>

<form method="post">
	
	<input type="hidden" name="query" value="settings">
	<input type="hidden" name="data[code]" value="ru">
	<input type="hidden" name="data[target]" value="lang">
	
	<div class="main-group">
	<?php inputlang($lang); ?>
	</div>
	
	<button type="submit">button name</button>
</form>

<style>
.main-group {
	margin: 20px;
	padding: 20px;
	border: 1px solid #ccc;	
}
.wrapper-group {
	margin: 40px 0px 0px 0px;
	padding: 0px 20px 20px 20px;
	border: 1px solid #ccc;	
}
.wrapper-group + .input-group {
	margin-top: 20px;
}
.main-group .label {
	display: inline-block;
    top: 0px;
    position: relative;
    transform: translateY(-50%);
    background: #fff;
    padding: 5px 10px;
    border: 1px solid #ccc;
}

.input-group.array {
	display: flex;
    flex-direction: row;	
}
.input-group.array .input-group-prepend {
	display: flex;
	flex-direction: row;
}
.input-group.array .input-group-array {
	display: flex;
    flex-direction: row;
}

.input-group .input-group-prepend {
	width: 25%;
}
.input-group .input-group-prepend .input-group-text {
	width: 100%;
}

.sub-group.datetime {
	display: flex;
	flex-direction: row;
}
.sub-group.datetime > .input-group {
	display: flex;
	flex-direction: column;
}
.sub-group.datetime > .input-group:first-child {
	flex-direction: row;
    flex-wrap: nowrap;
    align-items: start;
}
.sub-group.datetime > .input-group > .input-group-prepend {
	display: flex;
	flex-direction: column;
}
.sub-group.datetime > .input-group > .form-control {
	width: 100%;
}

.sub-group.datetime .input-group.array {
	display: flex;
    flex-direction: column;	
}
.sub-group.datetime .input-group.array .input-group-prepend {
	display: flex;
	flex-direction: column;
}
.sub-group.datetime .input-group.array .input-group-array {
	display: flex;
    flex-direction: column;
}

.sub-group.datetime .input-group .input-group-prepend {
    width: 100%;
}

.sub-group.datetime .input-group-array .form-control:first-child {
	display: none;
}

</style>
