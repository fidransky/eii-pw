<h1><?=$title?></h1>

<form action="<?=$editHandler?>" method="post">
	<div class="form-group">
		<label for="name">Name</label>
		<input type="text" name="name" value="<?=$player['name']?>" class="form-control" id="name" placeholder="Name of the player">
	</div>

	<div class="form-group">
		<label for="number">Number</label>
		<input type="number" name="number" value="<?=$player['number']?>" class="form-control" id="number" placeholder="Player's number">
	</div>

	<div class="form-group">
		<label for="post">Post</label>
		<select name="post" class="form-control" id="post">
			<?php foreach ($posts as $key => $post): ?>
				<option value="<?=$key?>" <?=($key === $player['post__raw']) ? 'selected' : ''?>><?=$post?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="form-group">
		<label for="team">Team</label>
		<?php foreach ($teams as $key => $team): ?>
			<div class="checkbox">
				<label for="team-<?=$team['id']?>">
					<input type="checkbox" name="teams[]" value="<?=$team['id']?>" id="team-<?=$team['id']?>" <?=in_array($team['id'], $player['teams']) ? 'checked' : ''?>> <?=$team['name']?>
				</label>
			</div>
		<?php endforeach; ?>
	</div>

	<button type="submit" class="btn btn-default">Save</button>
</form>