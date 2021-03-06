<h1><?=$title?></h1>

<form action="<?=$editHandler?>" method="post">
	<div class="form-group">
		<label for="date">Date and time</label>
		<input type="datetime-local" name="date" value="<?=$match['date']->format(HTML5_DATETIME_FORMAT)?>" class="form-control" required>
	</div>

	<div class="form-group">
		<label for="homeTeamId">Home team</label>
		<select class="form-control" id="homeTeamId" disabled>
			<?php foreach ($teams as $key => $team): ?>
				<option value="<?=$team['id']?>" <?=($team['id'] === $match['home_team_id']) ? 'selected' : ''?>><?=$team['name']?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="form-group">
		<label>Players of the home team</label>
		<ol id="homeTeamPlayers">
			<?php foreach ($match['homeTeamPlayers'] as $player): ?>
				<li><?=$player['name']?></li>
			<?php endforeach; ?>
		</ol>
	</div>

	<div class="form-group">
		<label for="visitingTeamId">Visiting team</label>
		<select class="form-control" id="visitingTeamId" disabled>
			<?php foreach ($teams as $key => $team): ?>
				<option value="<?=$team['id']?>" <?=($team['id'] === $match['visiting_team_id']) ? 'selected' : ''?>><?=$team['name']?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="form-group">
		<label>Players of the visiting team</label>
		<ol id="visitingTeamPlayers">
			<?php foreach ($match['visitingTeamPlayers'] as $player): ?>
				<li><?=$player['name']?></li>
			<?php endforeach; ?>
		</ol>
	</div>

	<input type="hidden" name="homeTeamId" value="<?=$match['home_team_id']?>">
	<input type="hidden" name="visitingTeamId" value="<?=$match['visiting_team_id']?>">
	<input type="hidden" name="state" value="<?=$match['state__raw']?>">

	<button type="submit" class="btn btn-default">Save</button>
</form>