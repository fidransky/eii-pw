<h1><?=$pageTitle?></h1>

<ul class="match-list list-unstyled" id="matches">
	<?php foreach ($ongoingMatches as $match): ?>
	<li id="match-<?=$match['id']?>" class="match link-list-item" data-id="<?=$match['id']?>" data-started="<?=$match['started'] ? $match['started']->getTimestamp() : ''?>" data-part="<?=$match['part']?>">
		<a href="./homepage/match?id=<?=$match['id']?>">
			<strong>
				<?=$match['homeTeam']['name']?>
				<?=count($match['homeTeam']['goals'])?>:<?=count($match['visitingTeam']['goals'])?>
				<?=$match['visitingTeam']['name']?>
			</strong>
			<span class="match-state"><span id="matchPart">part <?=$match['part']?></span>, <span id="matchTime"><?=$match['time'] ? (($match['time']->format('%i') + 1) . '′') : 'paused'?></span></span>
			<span class="pull-right"><?=$match['date']->format(DATETIME_FORMAT)?></span>
		</a>
	</li>
	<?php endforeach; ?>
</ul>

<?php if (count($ongoingMatches) > 0): ?>
	<hr>
<?php endif; ?>

<ul class="match-list list-unstyled">
	<?php foreach ($finishedMatches as $match): ?>
	<li class="match link-list-item">
		<a href="./homepage/match?id=<?=$match['id']?>">
			<strong>
				<?=$match['homeTeam']['name']?>
				<?=count($match['homeTeam']['goals'])?>:<?=count($match['visitingTeam']['goals'])?>
				<?=$match['visitingTeam']['name']?>
			</strong>
			<span class="match-state">finished</span>
			<span class="pull-right"><?=$match['date']->format(DATETIME_FORMAT)?></span>
		</a>
	</li>
	<?php endforeach; ?>
</ul>

<script src="<?=$basePath?>js/ajax.js"></script>
<script>
var matches = [];

var matchItems = document.querySelectorAll('#matches li');
matchItems.forEach(function(listItem) {
	var id = +listItem.getAttribute('data-id');
	var timestamp = listItem.getAttribute('data-started');
	var part = +listItem.getAttribute('data-part');

	matches.push({
		id: id,
		started: timestamp ? new Date(+timestamp * 1000) : null,
		part: part,
	});
});

// updates matches' time progress every second
setInterval(function() {
	var now = new Date();

	matches.forEach(function(match) {
		var text;
		if (match.started) {
			var minutes = (now - match.started) / 1000 / 60;
			text = Math.ceil(minutes) + '′';
		} else {
			text = 'paused';
		}

		document.querySelector('#match-' + match.id + ' #matchTime').textContent = text;
		document.querySelector('#match-' + match.id + ' #matchPart').textContent = 'part ' + match.part;
	});
}, 1 * 1000);

// updates matches' started datetime every 10 seconds
setInterval(function() {
	matches.forEach(function(match) {
		ajax.get('<?=$matchStateHandler?>?matchId=' + match.id, function(data) {
			data = JSON.parse(data);

			match.part = data.part;
			match.started = data.started ? new Date(+data.started * 1000) : null;
		});
	});
}, 10 * 1000);
</script>