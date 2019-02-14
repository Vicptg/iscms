<?php
$projects = loadProjects('load', $userID);
if (!$projects) {
	createProjectDB('create', $userID);
	$projects = array();
}
?>

<?php foreach($projects as $item) : ?>
	
	<div class="item project<?= $item['image'] ? '' : ' noimg'; ?><?= $item['status'] !== 'lock' ? '' : ' locked'; ?>" data-id="<?= $item['id'] ?>">
	<form action="/" method="post">
		
		<input type="hidden" name="project" value="<?= $item['id'] ?>">
		<input type="hidden" name="query" value="openproject">
		
		<?php if ($item['image']) : ?>
		<div class="item_image">
			<img src="data:image/png;base64,<?= $item['image'] ?>" />
		</div>
		<?php endif; ?>
		
		<div class="item_title">
			<div class="item_name">
				<?= $item['title'] ?>
			</div>
			<div class="item_manage">
				<i class="fas fa-pencil-alt editproject" aria-hidden="true"></i>
				<i class="fas fa-arrows-alt" aria-hidden="true"></i>
				<i class="fas <?= $item['status'] !== 'lock' ? 'fa-unlock' : 'fa-lock'; ?> lockproject" aria-hidden="true"></i>
			</div>
		</div>
		
		<div class="item_body">
			<div class="item_row">
				<pre class="item_description"><?= $item['description'] ?></pre>
			</div>
			<div class="item_row flex buttons<?= $item['status'] !== 'lock' ? '' : ' hidden'; ?>">
				<div class="item_row_left">
					<button class="button open" type="submit">
						<i class="fas fa-pencil-alt" aria-hidden="true"></i>
						<?= $lang -> action -> open; ?>
					</button>
					<button class="button download" onclick="DownloadProject(this);">
						<i class="fas fa-save" aria-hidden="true"></i>
						<?= $lang -> action -> download; ?>
					</button>
				</div>
				<div class="item_row_right">
					<button class="button delete" onclick="DeleteProject(this);">
						<i class="fas fa-times" aria-hidden="true"></i>
						<?= $lang -> action -> delete; ?>
					</button>
				</div>
			</div>
		</div>
		
	</form>
	</div>
	
<?php endforeach; ?>

<div class="item addproject">
	
	<button class="button newproject">
		<i class="far fa-file" aria-hidden="true"></i>
		<?= $lang -> action -> create; ?>
	</button>
	
	<button class="button">
		<i class="fas fa-upload" aria-hidden="true"></i>
		<?= $lang -> action -> upload; ?>
	</button>
	
</div>

<?php require_once 'setproject.php'; ?> 

<script>
</script>

<script>

function DeleteProject(t) {
	parent = $(t).parents('.item.project').first();
	if (confirm('<?= $lang -> modals -> project -> deleteconfirm; ?>')) {
		$.post(
			'/',
			{
				query: 'deleteproject',
				project: $.trim( parent.data('id') ),
			},
			function(){
				location.reload();
			}
		);
	}
}



/*
$(function(){
	
	$('.button.delete').click(function(){
		parent = $(this).parents('.item.project').first();
		if (confirm('<?= $lang -> modals -> project -> deleteconfirm; ?>')) {
			$.post(
				'/',
				{
					query: 'deleteproject',
					project: $.trim( parent.data('id') ),
				},
				function(){
					location.reload();
				}
			);
		}
	});
	
});
*/
</script>