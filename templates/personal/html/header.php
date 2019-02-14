<?php defined('isCMS') or die; ?>

<div class="menu">
	<nav>
		<ul class="flex parts">
			<div class="flex left part">
				<li class="item logo">
					<a href="#" class="link">
					</a>
				</li>
				<li class="item">
					<a href="#" class="link">
						Home
					</a>
				</li>
				<li class="item">
					<a href="#info" class="link">
						What's New
					</a>
				</li>
				<li class="item">
					<a href="#news" class="link">
						Get Started
					</a>
				</li>
			</div>
			<div class="flex right part">
				<li class="item login">
					<a href="#" class="link" data-toggle="modal" data-target="#modal">
						<i class="fas fa-user-circle" aria-hidden="true"></i>
						Registered/Login
					</a>
				</li>
				<li class="item enter hidden">
					<form id="enter" class="form" action="\" method="post">
						<input type="hidden" name="template" value="user">
						<button type="submit" class="submit">Enter</button>
					</form>
				</li>
			</div>
		</ul>
	</nav>
</div>

<div class="box flex">
	<div class="offer">
		<h1>
			Title of Site
		</h1>
		<h2>
			Second slogan of Site
		</h2>
		<button data-toggle="modal" data-target="#modal">
			Button
		</button>
	</div>
	<div class="video">
		<iframe width="100%" height="100%" src="https://www.youtube.com/embed/De7PC-fImEA" frameborder="0" allowfullscreen></iframe>
	</div>
</div>
