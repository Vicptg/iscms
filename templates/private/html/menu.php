<?php defined('isCMS') or die;
$salt = dechex(time());
$hash = $salt . substr(MD5($_SESSION['token'] . $salt), 0, 30);
?>

<nav>
	<ul class="flex parts"<?php if ($template -> router -> page) { echo 'data-current="' . $template -> router -> page . '"'; } ?>>
		<div class="flex left part">
			<li class="item logo">
				<a href="/<?= $template -> name; ?>" class="link">
					<i class="fas fa-user-circle" aria-hidden="true"></i>
					<?= $lang -> menu -> welcome; ?><?= $userData['name'] ?>
				</a>
			</li>
			<li class="item">
				<a href="/<?= $template -> name; ?>/profile" class="link">
					<?= $lang -> menu -> profile; ?>
				</a>
			</li>
			<li class="item">
				<a href="/<?= $template -> name; ?>/myprojects" class="link">
					<?= $lang -> menu -> myprojects; ?>
				</a>
			</li>
			<? /*
			<li class="item">
				<a href="orders" class="link">
					<?= $lang -> menu -> orders; ?>
				</a>
			</li>
			*/ ?>
			<li class="item">
				<a href="/<?= $template -> name; ?>/help" class="link">
					<?= $lang -> menu -> help; ?>
				</a>
			</li>
		</div>
		<div class="flex right part">
			<li class="item message">
				<i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
				<?= $lang -> menu -> message; ?>
			</li>
			<li class="item logout">
				<a href="/">
					<i class="fas fa-home" aria-hidden="true"></i>
					<?= $lang -> menu -> mainpage; ?>
				</a>
			</li>
			<li class="item logout">
				<form action="\" method="post">
					<input type="hidden" name="query" value="logout">
					<input type="hidden" name="hash" value="<?= $hash; ?>">
					<button class="link logout" type="submit">
						<i class="fas fa-sign-out-alt" aria-hidden="true"></i>
						<?= $lang -> menu -> exit; ?>
					</button>
				</form>
			</li>
		</div>
	</ul>
</nav>
