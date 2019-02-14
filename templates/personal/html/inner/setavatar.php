<?php defined('isCMS') or die; ?>

<!-- Modal -->
<div id="setavatar" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="item modal-content">
			
			<div class="item_title">
				<div class="item_name">
					<?= $lang -> modals -> avatar -> title; ?>
				</div>
				<div class="item_manage">
					<a href="#" data-dismiss="modal">
						<i class="fas fa-times" aria-hidden="true"></i>
					</a>
				</div>
			</div>
			
			<div class="item_body">
				<div class="item_row">
					<?= $lang -> modals -> avatar -> description; ?>
				</div>
				<div class="item_row">
					<form method="post" enctype="multipart/form-data" action="/">
						<input type="file" name="fileload" onChange="CheckFile(this);">
						<button type="submit" name="query" value="changeavatar" class="button">
							<?= $lang -> action -> ok; ?>
						</button>
						<button class="button" data-dismiss="modal">
							<?= $lang -> action -> cancel; ?>
						</button>
						<button type="submit" name="query" value="deleteavatar" class="button">
							<?= $lang -> action -> delete; ?>
						</button>
					</form>
				</div>
			</div>
			
		</div>

	</div>
</div>

<script>

$(function(){
	$('button[value="deleteavatar"]').click(function(e){
		if (!confirm('<?= $lang -> modals -> avatar -> deleteconfirm; ?>')) {
			e.preventDefault();
		}
	});
});

function CheckFile(file) {
    // Устанавливаем настройки
    // Флаг для валидации расширения файла
    var good_ext = false;
    // Флаг для валидации размера файла
    var good_size = false;
    // Разрешенные расширения файла
    var ext_arr = ['jpg','jpeg','png','gif']; 
    // Максимальный размер 4MB
    var maxsize = 1024*1024*4;
	
    // Для хранения размера загружаемого файла
    var iSize = 0;
	
    // Если браузер IE
    if ($.browser && $.browser.msie) {
        var objFSO = new ActiveXObject("Scripting.FileSystemObject");
        var sPath = $(file)[0].value;
        var objFile = objFSO.getFile(sPath);
        iSize = objFile.size;
    } else {
        // В других браузерах
        iSize = $(file)[0].files[0].size;
    }
	
    // Делаем проверку что файл не превышает допустимого размера
    if (maxsize > iSize) {
        // Если файл допустимого размера - выставляем флаг
        good_size = true;
    }
    // Пробегаемся по нашему массиву разрешенных типов файлов
    for (i in ext_arr) {
        // Если совпадения найдены
        if('image/'+ext_arr[i] == $(file)[0].files[0].type) {
            // Выставляем флаг - что расширение файла допустимо
            good_ext = true; 
        }
    }
    // Для хранения ошибки
    var error = '';
    // Если расширение не совпадает с фильтром
    if (!good_ext) {
        error += '<?= $lang -> modals -> avatar -> errorext; ?>';
    }
    // Если у нас уже есть ошибка - ставим переход на новую строку
    if (error != '') {
        error += "\r\n";
    }
    // Если не прошли валидацию по размеру файла
    if (!good_size) {
        error += '<?= $lang -> modals -> avatar -> errorsize; ?>';
    }
    // Если есть ошибки
    if (error != '') {
        // очищаем значение input file
        $(file).val('');
        // И выводим алертом сообщение об ошибке
        alert(error);
    }
	return false;
}
</script>
