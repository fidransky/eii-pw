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
		<select name="league" class="form-control" id="league">
			<?php foreach ($leagues as $key => $league): ?>
				<option value="<?=$league['id']?>"><?=$league['name']?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<button type="submit" class="btn btn-default">Save</button>
</form>