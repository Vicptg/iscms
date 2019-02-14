<div class="flex block">
	<div class="flex column">
		<div class="item avatar">
			<span class="set_avatar"><?= $lang -> profile -> setavatar; ?></span>
			<img src="data:image/png;base64,<?= $userData['avatar'] ?>" />
		</div>
	</div>
	<div class="flex column">
		
		<div class="item profile">
			<div class="item_title">
				<div class="item_name">
					<?= $userData['name'] ?>
				</div>
				<div class="item_manage">
					<a href="/<?= $template -> name; ?>/editprofile">
						<i class="fas fa-pencil-alt" aria-hidden="true"></i>
					</a>
				</div>
			</div>
			
			<div class="item_body">
				
				<div class="item_row">
					<div class="item_row_label">
						<?= $lang -> profile -> birthday; ?>
					</div>
					<div class="item_row_value">
						<?php echo
							($userData['birthday'] != 0) ?
							date('j ', $userData['birthday']) . $lang -> datetime -> MM[date('n', $userData['birthday'])] . date(' Y', $userData['birthday'])
							: $lang -> profile -> noset;
						?>
					</div>
				</div>
				
				<?php if ($userData['country']) : ?>
					<div class="item_row">
						<div class="item_row_label">
							<?= $lang -> profile -> country; ?>
						</div>
						<div class="item_row_value">
							<?= $userData['country'] ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ($userData['city']) : ?>
					<div class="item_row">
						<div class="item_row_label">
							<?= $lang -> profile -> city; ?>
						</div>
						<div class="item_row_value">
							<?= $userData['city'] ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ($userData['address']) : ?>
					<div class="item_row">
						<div class="item_row_label">
							<?= $lang -> profile -> address; ?>
						</div>
						<div class="item_row_value">
							<?= $userData['address'] ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ($userData['email']) : ?>
					<div class="item_row">
						<div class="item_row_label">
							<?= $lang -> profile -> email; ?>
						</div>
						<div class="item_row_value">
							<?= $userData['email'] ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ($userData['phone']) : ?>
					<div class="item_row">
						<div class="item_row_label">
							<?= $lang -> profile -> phone; ?>
						</div>
						<div class="item_row_value">
							<?= $userData['phone'] ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ($userData['website']) : ?>
					<div class="item_row">
						<div class="item_row_label">
							<?= $lang -> profile -> website; ?>
						</div>
						<div class="item_row_value">
							<?= $userData['website'] ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ($userData['about']) : ?>
					<div class="item_row">
						<div class="item_row_label">
							<?= $lang -> profile -> about; ?>
						</div>
						<div class="item_row_value">
							<?= $userData['about'] ?>
						</div>
					</div>
				<?php endif; ?>
				
				<div class="item_row">
					<div class="item_row_label">
						<?= $lang -> profile -> lastenter; ?>
					</div>
					<div class="item_row_value">
						<?php echo
							($userData['lastenter'] != 0) ?
							$lang -> datetime -> WW[date('N', $userData['lastenter'])] .
							date(', j ', $userData['lastenter']) .
							$lang -> datetime -> MM[date('n', $userData['lastenter'])] .
							date(' Y, G:i', $userData['lastenter'])
							: $lang -> profile -> nodata;
						?>
					</div>
				</div>
				
			</div>
			
		</div>
		
		<div class="item plan">
			<div class="item_title">
				<div class="item_name">
					Тарифный план:
					Бесплатный
				</div>
				<div class="item_manage">
					<i class="fas fa-sync-alt" aria-hidden="true"></i>
				</div>
			</div>
			
			<div class="item_body">
				<div class="item_row">
					<div class="item_row_label">
						Возможности
					</div>
					<div class="item_row_value">
						<i class="far fa-eye" aria-hidden="true"></i>
						<i class="far fa-file" aria-hidden="true"></i>
						<i class="far fa-save" aria-hidden="true"></i>
						<i class="fas fa-upload" aria-hidden="true"></i>
						<i class="fas fa-print" aria-hidden="true"></i>
						<i class="fas fa-share-alt" aria-hidden="true"></i>
						<i class="fas fa-users" aria-hidden="true"></i>
					</div>
				</div>
				<div class="item_row">
					<div class="item_row_label">
						Проектов
					</div>
					<div class="item_row_value">
						0 из 1
					</div>
				</div>
			</div>
			
		</div>
		
		<div class="item communication">
			<div class="item_title">
				<div class="item_name">
					Написать в 
					службу поддержки
				</div>
				<div class="item_manage">
					<i class="far fa-plus-square" aria-hidden="true"></i>
				</div>
			</div>
			<div class="form">
				<form>
					<div>
						<input type="text" />
					</div>
					<div>
						<textarea>
						</textarea>
					</div>
					<div>
						<button class="button">
							Отправить
						</button>
					</div>
				</form>
			</div>
		</div>
		
		<div class="item plan">
			
			<?php print_r($user); ?>
			<?php print_r($userData); ?>
			
			<?php
				$je = json_encode($userData);
				$jd = json_decode($je, true);
				echo '<br><br>';
				echo $userData['lastenter'];
				echo '<br><br>';
				echo $je;
				echo '<br><br>';
				print_r ($jd);
			?>
		</div>
		
	</div>
</div>

<?php require_once 'setavatar.php'; ?> 