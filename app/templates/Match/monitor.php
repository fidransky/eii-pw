<h1><?=$title?></h1>

<h2>
	<?=$match['homeTeam']['name']?>
	<?=count($match['homeTeam']['goals'])?>:<?=count($match['visitingTeam']['goals'])?>
	<?=$match['visitingTeam']['name']?>,
	<span class="match-state">part <?=$match['part']?>, <span id="matchTime"><?=$match['time'] ? ($match['time']->format('%i') . '′ ' . $match['time']->format('%s') . '″') : 'paused'?></span></span>
</h2>

<div class="row">
	<div class="col-6 box">
		<strong><?=$match['homeTeam']['name']?></strong>

		<form action="<?=$scoreGoalHandler?>" method="post">
			<select name="teamPlayerId">
				<option value="" selected>select player</option>
				<?php foreach ($match['homeTeamPlayers'] as $player): ?>
					<option value="<?=$player['team_player_id']?>"><?=$player['name']?></option>
				<?php endforeach; ?>
			</select>

			<select name="type">
				<?php foreach ($goalTypes as $key => $type): ?>
					<option value="<?=$key?>"><?=$type?></option>
				<?php endforeach; ?>
			</select>

			<input type="hidden" name="matchId" value="<?=$match['id']?>">
			<input type="hidden" name="teamId" value="<?=$match['home_team_id']?>">
			<input type="hidden" name="part" value="<?=$match['part']?>">

			<input type="submit" value="Score" <?=$match['started'] === null ? 'disabled' : ''?>>
		</form>
	</div>
	<div class="col-6 box">
		<strong><?=$match['visitingTeam']['name']?></strong>

		<form action="<?=$scoreGoalHandler?>" method="post">
			<select name="teamPlayerId">
				<option value="" selected>select player</option>
				<?php foreach ($match['visitingTeamPlayers'] as $player): ?>
					<option value="<?=$player['team_player_id']?>"><?=$player['name']?></option>
				<?php endforeach; ?>
			</select>

			<select name="type">
				<?php foreach ($goalTypes as $key => $type): ?>
					<option value="<?=$key?>"><?=$type?></option>
				<?php endforeach; ?>
			</select>

			<input type="hidden" name="matchId" value="<?=$match['id']?>">
			<input type="hidden" name="teamId" value="<?=$match['visiting_team_id']?>">
			<input type="hidden" name="part" value="<?=$match['part']?>">

			<input type="submit" value="Score" <?=$match['started'] === null ? 'disabled' : ''?>>
		</form>
	</div>
</div>

<?php if ($match['started'] !== null): ?>
	<a href="./match/pause?matchId=<?=$match['id']?>" class="btn btn-default" id="pauseButton">pause</a>
<?php else: ?>
	<a href="./match/resume?matchId=<?=$match['id']?>" class="btn btn-default">resume</a>
<?php endif; ?>
<a href="./match/end?matchId=<?=$match['id']?>" class="btn btn-default">end</a>

<script>
var timestamp = '<?=$match["started"]->getTimestamp()?>';
var started = timestamp ? new Date(+timestamp * 1000) : null;

var pauseButton = document.getElementById('pauseButton');

// update time progress every second
setInterval(function() {
	if (started) {
		var now = new Date();
		var seconds = (now - started) / 1000;
		var minutes = seconds / 60;
		text = Math.floor(minutes) + '′ ' + (Math.ceil(seconds % 60) - 1) + '″';

		// add CSS class to pause button after 45 minutes played
		if (minutes > 45) {
			pauseButton.classList.remove('btn-default');
			pauseButton.classList.add('btn-primary');
		}

	} else {
		text = 'paused';
	}

	document.getElementById('matchTime').textContent = text;
}, 1 * 1000);
</script>