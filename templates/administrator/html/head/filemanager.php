<!-- Section CSS -->
<!-- jQuery UI (REQUIRED) -->
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

<!-- elFinder CSS (REQUIRED) -->
<link rel="stylesheet" type="text/css" href="/<?= NAME_LIBRARIES; ?>/elfinder/css/elfinder.min.css">
<link rel="stylesheet" type="text/css" href="/<?= NAME_LIBRARIES; ?>/elfinder/css/theme.css">

<!-- Section JavaScript -->
<!-- jQuery and jQuery UI (REQUIRED) -->
<!--[if lt IE 9]>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!--<![endif]-->
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- elFinder JS (REQUIRED) -->
<script src="/<?= NAME_LIBRARIES; ?>/elfinder/js/elfinder.min.js"></script>

<!-- Extra contents editors (OPTIONAL) -->
<script src="/<?= NAME_LIBRARIES; ?>/elfinder/js/extras/editors.default.min.js"></script>

<!-- GoogleDocs Quicklook plugin for GoogleDrive Volume (OPTIONAL) -->
<!--<script src="js/extras/quicklook.googledocs.js"></script>-->

<!-- elFinder initialization (REQUIRED) -->
<script type="text/javascript" charset="utf-8">
	// Documentation for client options:
	// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
	$(document).ready(function() {
		$('#elfinder').elfinder(
			// 1st Arg - options
			{
				cssAutoLoad : false,               // Disable CSS auto loading
				baseUrl : '/<?= NAME_LIBRARIES; ?>/elfinder/',                    // Base URL to css/*, js/*
				url : '/<?= NAME_LIBRARIES; ?>/elfinder/php/connector.minimal.php.init',  // connector URL (REQUIRED)
				lang: '<?= $template -> lang; ?>',                    // language (OPTIONAL)
				height: '600'
			},
			// 2nd Arg - before boot up function
			function(fm, extraObj) {
				// `init` event callback function
				fm.bind('init', function() {
					// Optional for Japanese decoder "encoding-japanese.js"
					if (fm.lang === 'ja') {
						fm.loadScript(
							[ '//cdn.rawgit.com/polygonplanet/encoding.js/1.0.26/encoding.min.js' ],
							function() {
								if (window.Encoding && Encoding.convert) {
									fm.registRawStringDecoder(function(s) {
										return Encoding.convert(s, {to:'UNICODE',type:'string'});
									});
								}
							},
							{ loadType: 'tag' }
						);
					}
				});
				// Optional for set document.title dynamically.
				var title = document.title;
				fm.bind('open', function() {
					var path = '',
						cwd  = fm.cwd();
					if (cwd) {
						path = fm.path(cwd.hash) || null;
					}
					document.title = path? path + ':' + title : title;
				}).bind('destroy', function() {
					document.title = title;
				});
			}
		);
	});
</script>