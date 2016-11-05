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

	<button type="submit" class="btn btn-default">Save</button>
</form>