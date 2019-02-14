<style type="text/css" media="screen">
	.editor {
		position: relative;
		width: 100%;
		height: 600px;
	}
    #editor { 
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
</style>

<div class="editor">

<div id="editor">
<?php
	
	$dir = PATH_SITE . '/administrator';
	$file = 'settings.ini';
	$src = $dir . '/' . $file;
	$dest = $dir . '/' . $file . '.edit';
	
	$type = substr($file, strrpos($file, '.') + 1);
	
	$allowtypes = ['php', 'js', 'css', 'less', 'html', 'txt', 'xml', 'json', 'csv', 'ini'];
	
	if (!in_array($type, $allowtypes)) {
		$type = 'txt';
	}
	
	if ($type === 'js') { $type = 'javascript'; }
	if ($type === 'ini') { $type = 'json'; }
	
	copy($src, $dest);
	
	echo htmlentities( file_get_contents($dest) );
	
?>
</div>

</div>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.2/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/cobalt");
    editor.session.setMode("ace/mode/<?= $type; ?>");
</script>
