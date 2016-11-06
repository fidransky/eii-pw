<h1><?=$title?></h1>

<form action="<?=$editHandler?>" method="post">
	<div class="form-group">
		<label for="name">Name</label>
		<input type="text" name="name" value="<?=$team['name']?>" class="form-control" id="name" placeholder="Name of the team">
	</div>

	<div class="form-group">
		<label for="stadium">Stadium</label>
		<input type="text" name="stadium" value="<?=$team['stadium']?>" class="form-control" id="stadium" placeholder="Team's home stadium">
	</div>

	<div class="form-group">
		<label for="league">League</label>
		<select name="league" class="form-control" id="league">
			<?php foreach ($leagues as $key => $league): ?>
				<option value="<?=$league['id']?>" <?=($league['id'] === $team['league']['id']) ? 'selected' : ''?>><?=$league['name']?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<button type="submit" class="btn btn-default">Save</button>
</form>