<!doctype html>

<html lang="<?=LANG?>">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<base href="<?=$basePath?>">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<link href="<?=$basePath?>css/style.css" rel="stylesheet" media="all">

		<title>
			<?=$appName?>
			<?php if (!empty($title)): ?>
				> <?=$title?>
		 	<?php endif; ?>
	 	</title>
	</head>

	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="./"><?=$appName?></a>
				</div>

				<div id="navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="./">Home</a></li>
						<?php if ($user->isLoggedIn()): ?>
							<li><a href="./league">Leagues</a></li>
							<li><a href="./team">Teams</a></li>
							<li><a href="./player">Players</a></li>
							<li><a href="./match">Matches</a></li>
						<?php else: ?>
							<li><a href="./log/in">Log in</a></li>
							<li><a href="./sign/in">Sign in</a></li>
						<?php endif; ?>
					</ul>
					<ul class="nav navbar-nav pull-right">
						<?php if ($user->isLoggedIn()): ?>
							<li><a href="./user"><?=$user->name?></a></li>
							<li><a href="./log/out">Log out</a></li>
						<?php endif; ?>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>

		<div class="container">
			<div class="starter-template">
				<?php foreach ($flashMessages as $key => $message): ?>
					<p class="alert <?='alert-' . $message['type']?>">
						<?=$message['text']?>
					</p>
				<?php endforeach; ?>

				<?php include($template); ?>
			</div>
		</div><!-- /.container -->
	</body>
</html>