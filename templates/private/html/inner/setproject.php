<?php defined('isCMS') or die; ?>

<!-- Modal -->
<div id="setproject" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="item modal-content">
			
			<div class="item_title">
				<div class="item_name">
					<?= $lang -> modals -> project -> title; ?>
				</div>
				<div class="item_manage">
					<a href="#" data-dismiss="modal" onClick="RestoreFile();">
						<i class="fas fa-times" aria-hidden="true"></i>
					</a>
				</div>
			</div>
			
			<div class="item_body">
				<div class="item_row">
					<?= $lang -> modals -> project -> description; ?>
				</div>
				<div class="item_row">
					<form method="post" enctype="multipart/form-data" action="/">
						
						<input type="hidden" name="project[id]">
						
						<div class="item_row">
							<div class="item_row_label">
								<?= $lang -> modals -> project -> input_title; ?>
							</div>
							<div class="item_row_value">
								<input type="text" name="project[title]">
							</div>
						</div>
						
						<div class="item_row">
							<div class="item_row_label">
								<?= $lang -> modals -> project -> input_description; ?>
							</div>
							<div class="item_row_value">
								<textarea name="project[description]"></textarea>
							</div>
						</div>
						
						<div class="item_row separate">
						</div>
						
						<div class="item_row">
							<div class="item_row_label">
								<?= $lang -> modals -> project -> input_image; ?>
							</div>
							<div class="item_row_value">
								<input type="file" name="fileload" onChange="CheckFile(this);">
								<input type="hidden" name="filedelete" value="">
								<button type="button" class="button" name="resetimage" onClick="ResetFile();">
									<?= $lang -> modals -> project -> resetimage; ?>
								</button>
							</div>
						</div>
						
						<div class="item_row separate">
						</div>
						
						<div class="item_row">
							<button type="submit" name="query" value="createproject" class="button">
								<?= $lang -> action -> ok; ?>
							</button>
							<button class="button" data-dismiss="modal" onClick="RestoreFile();">
								<?= $lang -> action -> cancel; ?>
							</button>
						</div>
						
					</form>
				</div>
			</div>
			
		</div>

	</div>
</div>

<script>
function ResetFile() {
	$('#setproject form [name="fileload"]').val('');
	$('#setproject form [name="filedelete"]').val('1');
	$('#setproject form [name="resetimage"]').html('<?= $lang -> modals -> project -> resetimagecomplete; ?>');
}

function RestoreFile() {
	$('#setproject form [name="filedelete"]').val('');
	$('#setproject form [name="resetimage"]').html('<?= $lang -> modals -> project -> resetimage; ?>');
}

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
	
	RestoreFile();
	
	return false;
}
</script>