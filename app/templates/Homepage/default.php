<h1>Hello world</h1>

<div class="row">
	<div class="col-2">
		<?php include(__DIR__ . '/../components/adminMenu.php'); ?>
	</div>
	<div class="col-10">
		<ul class="list-unstyled">
			<?php foreach ($matches as $match): ?>
			<li><a href="match"><?=$match['id']?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-2 box">col-2</div>
	<div class="col-4 box">col-4</div>
	<div class="col-6 box">col-6</div>
</div>