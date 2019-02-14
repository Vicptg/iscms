<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
<script type="text/javascript" src="/libs/ckeditor/ckeditor.js"></script>

<style>
.content {
	position: relative;
}
.content:hover {
	border: 1px solid #ccc;
	margin: -1px;
}
#editor-buttons {
	display: none;
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0.5;
    z-index: 10;
}
#editor-buttons.active {
	display: block;
}
#editor-buttons a {
	padding: 5px;
    text-decoration: none;
    font-size: 12px;
    color: #fff;
	cursor: pointer;
}
#editor-buttons a.ok {
	background: green;
}
#editor-buttons a.no {
	background: red;
}
#editor-ckeditor-inline:focus {
	border: 0;
	outline: none;
}
</style>

<div class="content">
<div id="editor-content">
</div>
<div id="editor-buttons">
<a href="#" class="ok">OK</a>
<a href="#" class="no">X</a>
</div>
</div>

<script>
$(document).ready(function(){
	function load_data(){
		$.ajax({
			url: "/includes/load.php",
			type: "POST",
			success: function(data){
				$('#editor-content').html(data);
			},
		});
	}
	load_data();
	function save_data(txt, id){
		$.ajax({
			url: "/includes/save.php",
			type: "POST",
			data: {txt:txt, id:id},
			success: function(){
				$('#editor-buttons').removeClass("active");
			}
		});
	}
	$(document).on('click', '#editor-content', function(){
		$('#editor-buttons').addClass("active");
	});
	$(document).on('click', '#editor-buttons .ok', function(){
		var id = $('#editor-ckeditor-inline').data("id");
		var txt = $('#editor-ckeditor-inline').html();
		save_data(txt, id);
	});
	$(document).on('click', '#editor-buttons .no', function(){
		load_data();
		$('#editor-buttons').removeClass("active");
	});
});
</script>
