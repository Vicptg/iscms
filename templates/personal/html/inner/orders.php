<div class="flex block">
	<div class="flex column">
		<div class="item avatar">
			<img src="data:image/png;base64,<?= $userData['avatar'] ?>" />
		</div>
	</div>
	<div class="flex column">
		
		<div class="item profile">
			
			<div class="item_title">
				<div class="item_name">
					<?= $lang -> orders -> title; ?>
				</div>
				<div class="item_manage">
					<a href="<?= $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['PATH_INFO']; /* REQUEST_URI */ ?>">
						<i class="fas fa-sync-alt" aria-hidden="true"></i>
					</a>
				</div>
			</div>
			
			<div class="item_body">
				
				<div class="item_row">
					<div class="item_row_label">
						<?= $lang -> orders -> balance; ?>
					</div>
					<div class="item_row_value">
						<?= $userData['birthday'] ?>
					</div>
				</div>
				
				<div class="item_row">
					<div class="item_row_label">
						<?= $lang -> orders -> currency; ?>
					</div>
					<div class="item_row_value">
						<?= $userData['birthday'] ?>
					</div>
				</div>
				
				<div class="item_row">
					<div class="item_row_label">
						<?= $lang -> orders -> lastenter; ?>
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
				
				<div class="item_row">
					<div class="item_row_label">
						<?= $lang -> orders -> lastpay; ?>
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
				
				<div class="item_row separate">
				</div>
				
				<button class="button set_pay">
					Пополнить баланс
				</button>
				
				<button class="button">
					Запросить вывод средств
				</button>
				
				<button class="button">
					Запросить возврат
				</button>
				
			</div>
			
		</div>
		
		<div class="item plan">
			
			<div class="item_title">
				<div class="item_name">
					Заказы
				</div>
			</div>
			
			<div class="item_body">
				
				<div class="item_row">
					<div class="item_row_label">
						<?= $lang -> orders -> plan; ?>
					</div>
					<div class="item_row_value">
						<?= $userData['birthday'] ?>
					</div>
				</div>
				
				<div class="item_row">
					<div class="item_row_label">
						<?= $lang -> orders -> datastart; ?>
					</div>
					<div class="item_row_value">
						<?= $userData['birthday'] ?>
					</div>
				</div>
				
				<div class="item_row">
					<div class="item_row_label">
						<?= $lang -> orders -> dataend; ?>
					</div>
					<div class="item_row_value">
						<?= $userData['birthday'] ?>
					</div>
				</div>
				
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
				
				<div class="item_row">
					<div class="item_row_label">
						Осталось дней
					</div>
					<div class="item_row_value">
						2 из 30
					</div>
				</div>
				
				<div class="item_row separate">
				</div>
				
				<button class="button">
					Продлить
				</button>
				
				<button class="button">
					Сменить
				</button>
				
			</div>
			
		</div>
		
	</div>
</div>

<?php require_once 'setpay.php'; ?> 