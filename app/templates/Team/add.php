<h1><?=$title?></h1>

<form action="<?=$addHandler?>" method="post">
	<div class="form-group">
		<label for="name">Name</label>
		<input type="text" name="name" class="form-control" id="name" placeholder="Name of the team" required>
	</div>

	<div class="form-group">
		<label for="stadium">Stadium</label>
		<input type="text" name="stadium" class="form-control" id="stadium" placeholder="Team's home stadium">
	</div>

	<div class="form-group">
		<label for="league">League</label>
		<?php foreach ($leagues as $key => $league): ?>
			<div class="checkbox">
				<label for="league-<?=$league['id']?>">
					<input type="checkbox" name="leagues[]" value="<?=$league['id']?>" id="league-<?=$league['id']?>"> <?=$league['name']?>
				</label>
			</div>
		<?php endforeach; ?>
	</div>

	<button type="submit" class="btn btn-default">Save</button>
</form>