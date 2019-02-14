<?php
// создаем переменную TXT и присваиваем ей значение результата функции, т.е. массив
// (тот же вопрос, что и в index.php: а что делать, когда текстов будет дофига?! - придется делать выборку по конкретным номерам строк)
$txt = selectTxt(); 

// при нажатии кнопки 'submit' (т.е. при post-запросе, содержащим соответствующие параметры)
if($_POST['submit']){
	// вызываем функцию записи и передаем в нее значения TXT и ID (из параметров name тегов формы)
	update($_POST['txt'], $_POST['id']);
	// это выход с редиректом - обновление странички (функция PHP_SELF вызывает текущий php файл относительно корня сайта, например /file.php или /dir/file.php или /dir/subdir/file.php)
	// выход обязателен, чтобы сбросить все post-данные
	exit("<meta http-equiv='refresh' content='0; url= $_SERVER[PHP_SELF]'>");
}

// !!! вот по такой же аналогии нужно получать выборку конкретного номера строки !!!
/* echo 'Привет ' . htmlspecialchars($_GET["name"]) . '!';
Если пользователь ввел в браузере адрес http://example.com/?name=Hannes
Результатом выполнения данного примера будет: Привет Hannes!
*/
?>

<?php
if ($сonfigEditor == 'ckeditor') {
	echo '<script type="text/javascript" src="/libs/ckeditor/ckeditor.js"></script>';
} elseif ($сonfigEditor == 'tinymce') {
	echo "
	<script src=\"/libs/tinymce/tinymce.min.js\"></script>
	<script>
		tinymce.init({
			selector:'textarea#editor',
			skin: 'custom',
			language: 'ru',
			plugins : 'advlist autolink charmap fullscreen image insertdatetime link lists media nonbreaking paste preview print searchreplace table visualchars',
			file_browser_callback: function(field, url, type, win) {
				tinyMCE.activeEditor.windowManager.open({
					file: '/libs/kcfinder/browse.php?opener=tinymce4&field=' + field + '&type=' + type,
					title: 'KCFinder',
					width: 700,
					height: 500,
					inline: true,
					close_previous: false
				}, {
					window: win,
					input: field
				});
				return false;
			}
		});
	</script>
	";
} elseif ($сonfigEditor == 'ace') {
	echo "
	<style>
	.ace_editor {
		height: 200px;
	}
	</style>
	";
}
?>

<div class="content" style="height: 200px!important;">
	<h1><?php echo $сonfigEditor; ?></h1>
	<form method="post">
<textarea id="editor" name="txt" cols="100" rows="20" <?php if($сonfigEditor=='reformator'){echo'class="HTML"';}?>>
<?php echo $txt[0]['text'] // здесь выводим из массива TXT, из первой строки только значение ячейки TEXT ?>
</textarea>
		
		<?php
		if ($сonfigEditor == 'ckeditor') {
			echo "
				<script type=\"text/javascript\">
					var ckeditor1 = CKEDITOR.replace( 'editor', {
						language: 'ru',
						skin: 'moono-lisa',
						toolbarCanCollapse: true,
						filebrowserBrowseUrl: '/libs/kcfinder/browse.php?opener=ckeditor&type=file',
						filebrowserImageBrowseUrl: '/libs/kcfinder/browse.php?opener=ckeditor&type=image',
						filebrowserFlashBrowseUrl: '/libs/kcfinder/browse.php?opener=ckeditor&type=media',
						filebrowserUploadUrl: '/libs/kcfinder/upload.php?opener=ckeditor&type=file',
						filebrowserImageUploadUrl: '/libs/kcfinder/upload.php?opener=ckeditor&type=image',
						filebrowserFlashUploadUrl: '/libs/kcfinder/upload.php?opener=ckeditor&type=media'
					} );
				</script>
			";
		} elseif ($сonfigEditor == 'reformator') {
			echo "
				<script type=\"text/javascript\" charset=\"utf-8\"
			";
			if ($сonfigLibs == 'cdn') {
				echo "src=\"http://web.artlebedev.ru/tools/reformator/reformator.js\"";
			} else {
				echo "src=\"/libs/reformator/reformator.js\"";
			}
			echo "
				></script>
				<script type=\"text/javascript\">
					reformator.auto({bar: true, bar_path: '";
			if($сonfigLibs=='cdn'){echo"http://web.artlebedev.ru/tools/reformator/sidebar.html";}else{echo"/libs/reformator/sidebar.html";}
			echo "', focus: true});
					reformator.children[0].set_maximize(true);
				</script>	
			";
		} elseif ($сonfigEditor == 'ace') {
			echo "
				<script src=\"/libs/ace/ace.js\" type=\"text/javascript\" charset=\"utf-8\"></script>
				<script>
					var editor = ace.edit(\"editor\");
					editor.setTheme(\"ace/theme/chrome\");
					editor.session.setMode(\"ace/mode/php\");
					editor.resize();
					editor.commands.addCommand({
						name: 'changehighlightmode_html',
						bindKey: {win: 'Alt-H', mac: \"Command-Option-H\"},
						exec: function(editor) {
							editor.session.setMode(\"ace/mode/html\");
						},
						readOnly: true
					});
					editor.commands.addCommand({
						name: 'changehighlightmode_javascript',
						bindKey: {win: 'Alt-J', mac: \"Command-Option-J\"},
						exec: function(editor) {
							editor.session.setMode(\"ace/mode/javascript\");
						},
						readOnly: true
					});
					editor.commands.addCommand({
						name: 'changehighlightmode_php',
						bindKey: {win: 'Alt-P', mac: \"Command-Option-P\"},
						exec: function(editor) {
							editor.session.setMode(\"ace/mode/php\");
						},
						readOnly: true
					});
					editor.commands.addCommand({
						name: 'changehighlightmode_css',
						bindKey: {win: 'Alt-C', mac: \"Command-Option-C\"},
						exec: function(editor) {
							editor.session.setMode(\"ace/mode/css\");
						},
						readOnly: true
					});
					editor.commands.addCommand({
						name: 'showmenu',
						bindKey: {win: 'Ctrl-M', mac: \"Command-M\"},
						exec: function(editor) {
							editor.showSettingsMenu();
						},
						readOnly: true
					});	
				</script>
			";
		}
		?>
		
		<br />
		<input type="hidden" name="id" value="<?php echo $txt[0]['id'] // здесь в скрытое поле выводим из массива TXT, из первой строки только значение ячейки ID ?>" />
		<input type="submit" name="submit" value="Обновить" />
	</form>

</div>