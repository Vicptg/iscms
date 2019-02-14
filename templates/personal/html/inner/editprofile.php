<div class="item editprofile">
	<div class="item_title">
		<div class="item_name">
			<?= $lang -> profile -> editprofile; ?>
		</div>
	</div>
	
	<? print_r($lang -> title); ?>
	
	<div class="item_body">
		
		<form method="post" action="\">
		
		<input type="hidden" name="query" value="editprofile">
		
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> name; ?>
			</div>
			<div class="item_row_value">
				<input
					type="text"
					name="profile[name]"
					placeholder="<?= $lang -> profile -> noset; ?>"
					value="<?= $userData['name'] ?>"
				/>
			</div>
		</div>
		
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> birthday; ?>
			</div>
			<div class="item_row_value">
				<input
					type="text"
					name="profile[birthday]"
					placeholder="<?= $lang -> profile -> noset; ?>"
					value="<?= ($userData['birthday'] != 0) ? date('Y-m-d', $userData['birthday']) : '' ?>"
				/>
			</div>
		</div>
		<div class="item_row area">
			<div class="item_row_label">
				<?= $lang -> profile -> about; ?>
			</div>
			<div class="item_row_value">
				<textarea
					type="text"
					name="profile[about]"
					placeholder="<?= $lang -> profile -> nodata; ?>"
				><?= $userData['about'] ?></textarea>
			</div>
		</div>
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> language; ?>
			</div>
			<div class="item_row_value">
				<select
					name="profile[language]"
				>
					<option value="">
						<?= $lang -> profile -> default; ?>
					</option>
					<?php foreach ((array)$lang -> langs as $key => $item) : ?>
						<option
							value="<?= $key; ?>"
							<?= ($key === $userData['language']) ? 'selected' : '' ?>
						>
							<?= $item; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		
		<div class="item_row separate">
		</div>
		
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> country; ?>
			</div>
			<div class="item_row_value">
				<input
					type="text"
					name="profile[country]"
					placeholder="<?= $lang -> profile -> noset; ?>"
					value="<?= $userData['country'] ?>"
				/>
			</div>
		</div>
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> city; ?>
			</div>
			<div class="item_row_value">
				<input
					type="text"
					name="profile[city]"
					placeholder="<?= $lang -> profile -> noset; ?>"
					value="<?= $userData['city'] ?>"
				/>
			</div>
		</div>
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> address; ?>
			</div>
			<div class="item_row_value">
				<input
					type="text"
					name="profile[address]"
					placeholder="<?= $lang -> profile -> noset; ?>"
					value="<?= $userData['address'] ?>"
				/>
			</div>
		</div>
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> website; ?>
			</div>
			<div class="item_row_value">
				<input
					type="text"
					name="profile[website]"
					placeholder="http://"
					value="<?= $userData['website'] ?>"
				/>
			</div>
		</div>
		
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> email; ?>
			</div>
			<div class="item_row_value">
				<select
					name="profile[email_type]"
				>
					<?php foreach ($types as $type) : ?>
						<option
							value="<?= htmlentities($type) ?>"
							<?= ($type === $userData['email_type']) ? 'selected' : '' ?>
						>
							<?= htmlentities($lang -> name_types -> $type); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="item_row">
			<div class="item_row_label">
			</div>
			<div class="item_row_value">
				<input
					type="text"
					name="profile[email]"
					value="<?= $userData['email'] ?>"
				/>
			</div>
		</div>
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> phone; ?>
			</div>
			<div class="item_row_value">
				<select
					name="profile[phone_type]"
				>
					<?php foreach ($types as $type) : ?>
						<option
							value="<?= htmlentities($type) ?>"
							<?= ($type === $userData['phone_type']) ? 'selected' : '' ?>
						>
							<?= htmlentities($lang -> name_types -> $type); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="item_row">
			<div class="item_row_label">
			</div>
			<div class="item_row_value">
				<input
					type="text"
					name="profile[phone]"
					value="<?= $userData['phone'] ?>"
				/>
			</div>
		</div>
		
		<div class="item_row separate">
		</div>
		
		<div class="item_row social">
			<div class="item_row_label">
				<?= $lang -> profile -> social -> facebook; ?>
			</div>
			<div class="item_row_value">
				<span>
					www.facebook.com/
				</span>
				<input
					type="text"
					name="profile[social][facebook]"
					value="<?= $userData['social']['facebook'] ?>"
				/>
			</div>
		</div>
		<div class="item_row social">
			<div class="item_row_label">
				<?= $lang -> profile -> social -> instagram; ?>
			</div>
			<div class="item_row_value">
				<span>
					www.instagram.com/
				</span>
				<input
					type="text"
					name="profile[social][instagram]"
					value="<?= $userData['social']['instagram'] ?>"
				/>
			</div>
		</div>
		<div class="item_row social">
			<div class="item_row_label">
				<?= $lang -> profile -> social -> vk; ?>
			</div>
			<div class="item_row_value">
				<span>
					vk.com/
				</span>
				<input
					type="text"
					name="profile[social][vk]"
					value="<?= $userData['social']['vk'] ?>"
				/>
			</div>
		</div>
		<div class="item_row social">
			<div class="item_row_label">
				<?= $lang -> profile -> social -> twitter; ?>
			</div>
			<div class="item_row_value">
				<span>
					twitter.com/
				</span>
				<input
					type="text"
					name="profile[social][twitter]"
					value="<?= $userData['social']['twitter'] ?>"
				/>
			</div>
		</div>
		<div class="item_row social">
			<div class="item_row_label">
				<?= $lang -> profile -> social -> skype; ?>
			</div>
			<div class="item_row_value">
				<input
					type="text"
					name="profile[social][skype]"
					value="<?= $userData['social']['skype'] ?>"
				/>
			</div>
		</div>
		
		<div class="item_row separate">
		</div>
		
		<div class="item_row">
			<div class="item_row_label">
			</div>
			<div class="item_row_value">
				<button class="button" type="submit">
					<?= $lang -> action -> ok; ?>
				</button>
				<button class="button" type="reset" onclick="history.back();">
					<?= $lang -> action -> cancel; ?>
				</button>
			</div>
		</div>
		
		</form>
		
	</div>
	
</div>

<div class="item verification">
	<div class="item_title">
		<div class="item_name">
			<?= $lang -> profile -> change -> verification; ?>
		</div>
	</div>
	
	<div class="item_body">
		
		<form>
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> change -> email; ?>
			</div>
			<div class="item_row_value">
				<input type="text"
					value="<?= $userData['email'] ?>"
				/>
				<button class="button">
					<?= $lang -> action -> edit; ?>
				</button>
			</div>
		</div>
		</form>
		
		<form>
		<div class="item_row">
			<div class="item_row_label">
			</div>
			<div class="item_row_value">
				<input type="text"
					placeholder="Код подтверждения"
					value=""
				/>
				<button class="button">
					<?= $lang -> action -> confirm; ?>
				</button>
			</div>
		</div>
		</form>
		
		<form>
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> change -> phone; ?>
			</div>
			<div class="item_row_value">
				<input type="text"
					value="<?= $userData['email'] ?>"
				/>
				<button class="button">
					<?= $lang -> action -> edit; ?>
				</button>
			</div>
		</div>
		</form>
		
		<form>
		<div class="item_row">
			<div class="item_row_label">
			</div>
			<div class="item_row_value">
				<input type="text"
					placeholder="Код подтверждения"
					value=""
				/>
				<button class="button">
					<?= $lang -> action -> confirm; ?>
				</button>
			</div>
		</div>
		</form>
		
		<form>
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> change -> password; ?>
			</div>
			<div class="item_row_value">
				<input type="text"
					value="<?= $userData['email'] ?>"
				/>
				<button class="button">
					<?= $lang -> action -> edit; ?>
				</button>
			</div>
		</div>
		</form>
		
		<form>
		<div class="item_row">
			<div class="item_row_label">
			</div>
			<div class="item_row_value">
				<input type="text"
					placeholder="Код подтверждения"
					value=""
				/>
				<button class="button">
					<?= $lang -> action -> confirm; ?>
				</button>
			</div>
		</div>
		</form>
		
		<form>
		<div class="item_row">
			<div class="item_row_label">
				<?= $lang -> profile -> change -> delete; ?>
			</div>
			<div class="item_row_value">
				<input type="text"
					value="<?= $userData['email'] ?>"
				/>
				<button class="button">
					<?= $lang -> action -> delete; ?>
				</button>
			</div>
		</div>
		</form>
		
		<form>
		<div class="item_row">
			<div class="item_row_label">
			</div>
			<div class="item_row_value">
				<input type="text"
					placeholder="Код подтверждения"
					value=""
				/>
				<button class="button">
					<?= $lang -> action -> confirm; ?>
				</button>
			</div>
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

<script>
$(function(){
	
	// datepicker
	
	var year = new Date().getFullYear();
	var yearmin = year-100;
	var yearmax = year-16;
	
	$('.main .editprofile form [name="profile[birthday]"]').datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: yearmin + ':' + yearmax,
		dateFormat: 'yy-mm-dd',
		firstDay: <?= $lang -> datetime -> firstday; ?>,
		dayNamesMin: [ <?php
			echo
			'"' . $lang -> datetime -> W[7] . '",' .
			'"' . $lang -> datetime -> W[1] . '",' .
			'"' . $lang -> datetime -> W[2] . '",' .
			'"' . $lang -> datetime -> W[3] . '",' .
			'"' . $lang -> datetime -> W[4] . '",' .
			'"' . $lang -> datetime -> W[5] . '",' .
			'"' . $lang -> datetime -> W[6] . '"';
		?> ],
		monthNamesShort: [ <?php
			echo
			'"' . $lang -> datetime -> M[1] . '",' .
			'"' . $lang -> datetime -> M[2] . '",' .
			'"' . $lang -> datetime -> M[3] . '",' .
			'"' . $lang -> datetime -> M[4] . '",' .
			'"' . $lang -> datetime -> M[5] . '",' .
			'"' . $lang -> datetime -> M[6] . '",' .
			'"' . $lang -> datetime -> M[7] . '",' .
			'"' . $lang -> datetime -> M[8] . '",' .
			'"' . $lang -> datetime -> M[9] . '",' .
			'"' . $lang -> datetime -> M[10] . '",' .
			'"' . $lang -> datetime -> M[11] . '",' .
			'"' . $lang -> datetime -> M[12] . '"';
		?> ],
		hideIfNoPrevNext: true, duration: '',
	});
	
	// store original so we can call it inside our overriding method
	$.datepicker._generateMonthYearHeader_original = $.datepicker._generateMonthYearHeader;
	
	$.datepicker._generateMonthYearHeader = function(inst, dm, dy, mnd, mxd, s, mn, mns) {
		var header = $($.datepicker._generateMonthYearHeader_original(inst, dm, dy, mnd, mxd, s, mn, mns)),
			years = header.find('.ui-datepicker-year');
			
		// reverse the years
		years.html(Array.prototype.reverse.apply(years.children()));
		
		// return our new html
		return $('<div />').append(header).html();
	}
	
});
</script>

