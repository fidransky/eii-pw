<h1><?=$title?></h1>

<h2>
	<?=$match['homeTeam']['name']?>
	<?=count($match['homeTeam']['goals'])?>:<?=count($match['visitingTeam']['goals'])?>
	<?=$match['visitingTeam']['name']?>
</h2>

<div class="row">
	<div class="col-6 box">
		<ul class="goal-list list-unstyled">
			<?php if (count($match['homeTeam']['goals']) === 0): ?>
				<p class="text-muted">There are no goals scored by the visiting team.</p>
			<?php else: ?>
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
			<?php endif; ?>
		</ul>
	</div>
	<div class="col-6 box">
		<ul class="goal-list list-unstyled">
			<?php if (count($match['visitingTeam']['goals']) === 0): ?>
				<p class="text-muted">There are no goals scored by the visiting team.</p>
			<?php else: ?>
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
			<?php endif; ?>
		</ul>
	</div>
</div>
