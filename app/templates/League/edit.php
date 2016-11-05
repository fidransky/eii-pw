<h1><?=$title?></h1>

<form action="<?=$editHandler?>" method="post">
	<div class="form-group">
		<label for="name">Name</label>
		<input type="text" name="name" value="<?=$league['name']?>" class="form-control" id="name" placeholder="Name of the league">
	</div>

	<div class="form-group">
		<label for="season">Season</label>
		<select name="season" id="season">
			<?php foreach ($seasons as $key => $season): ?>
			<option value="<?=$season?>" <?=($season === $league['season']) ? 'default' : ''?>><?=$season?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<button type="submit" class="btn btn-default">Save</button>
</form>