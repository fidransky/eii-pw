<h1><?=$title?></h1>

<h2>
	<?=$match['homeTeam']['name']?>
	<?=count($match['homeTeam']['goals'])?>:<?=count($match['visitingTeam']['goals'])?>
	<?=$match['visitingTeam']['name']?>,
	<span class="match-state">
	<?php if ($match['state'] === 'finished'): ?>
		finished
	<?php else: ?>
		part <?=$match['part']?>, <span id="matchTime"><?=$match['time'] ? ($match['time']->format('%i') . '′ ' . $match['time']->format('%s') . '″') : 'paused'?></span>
	<?php endif; ?>
	</span>
</h2>

<h3>Lineup</h3>
<div class="row">
	<div class="col-6 box">
		<?php if (count($match['homeTeam']['players']) === 0): ?>
			<p class="text-muted">There are no players of the home team.</p>
		<?php else: ?>
			<ol class="player-list">
				<?php foreach ($match['homeTeam']['players'] as $player): ?>
				<li>
					<?=$player['name']?>
				</li>
				<?php endforeach; ?>			
			</ol>
		<?php endif; ?>
	</div>
	<div class="col-6 box">
		<?php if (count($match['visitingTeam']['players']) === 0): ?>
			<p class="text-muted">There are no players of the visiting team.</p>
		<?php else: ?>
			<ol class="player-list">
				<?php foreach ($match['visitingTeam']['players'] as $player): ?>
				<li>
					<?=$player['name']?>
				</li>
				<?php endforeach; ?>			
			</ol>
		<?php endif; ?>
	</div>
</div>

<h3>Goals</h3>
<div class="row">
	<div class="col-6 box">
		<?php if (count($match['homeTeam']['goals']) === 0): ?>
			<p class="text-muted">There are no goals scored by the home team.</p>
		<?php else: ?>
			<ul class="goal-list list-unstyled">
					<?php foreach ($match['homeTeam']['goals'] as $goal): ?>
					<?php if (isset($part) && $part != $goal['part']): ?>
						<li class="divider"></li>
					<?php endif; ?>
					<?php $part = $goal['part']; ?>

					<li>
						<strong><?=$goal['player']['name']?></strong>
						part <?=$goal['part']?>, <?=$goal['time']->format('%i')?>′ <?=$goal['time']->format('%s')?>″
						(<?=$goal['type']?>)
					</li>
					<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>

	<?php $part = 1; ?>

	<div class="col-6 box">
		<?php if (count($match['visitingTeam']['goals']) === 0): ?>
			<p class="text-muted">There are no goals scored by the visiting team.</p>
		<?php else: ?>
			<ul class="goal-list list-unstyled">
				<?php foreach ($match['visitingTeam']['goals'] as $goal): ?>
				<?php if (isset($part) && $part != $goal['part']): ?>
					<li class="divider"></li>
				<?php endif; ?>
				<?php $part = $goal['part']; ?>

				<li>
					part <?=$goal['part']?>, <?=$goal['time']->format('%m')?>′<?=$goal['time']->format('%s')?>″
					<strong><?=$goal['player']['name']?></strong>
					<?=$goal['type']?>
				</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>

<script>
var timestamp = '<?=$match["started"]->getTimestamp()?>';
var started = timestamp ? new Date(+timestamp * 1000) : null;

// update time progress every second
setInterval(function() {
	if (started) {
		var now = new Date();
		var seconds = (now - started) / 1000;
		var minutes = seconds / 60;
		text = Math.floor(minutes) + '′ ' + (Math.ceil(seconds % 60) - 1) + '″';

	} else {
		text = 'paused';
	}

	document.getElementById('matchTime').textContent = text;
}, 1 * 1000);
</script>