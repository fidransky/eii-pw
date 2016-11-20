<h1>
	<?=$title?>
	<a href="./match/add" class="pull-right">add a new match</a>
</h1>

<table class="table">
	<?php if (count($matches) === 0): ?>
		<caption>There are no matches yet.</caption>
	<?php else: ?>
		<thead>
			<tr>
				<th>Home team</th>
				<th>Visiting team</th>
				<th>Date</th>
				<th>State</th>
				<td></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($matches as $match): ?>
			<tr>
				<td><?=$match['homeTeam']['name']?></td>
				<td><?=$match['visitingTeam']['name']?></td>
				<td><?=$match['date']->format(DATE_RFC2822)?></td>
				<td><?=$match['state']?></td>
				<td>
					<a href="./match/edit?matchId=<?=$match['id']?>">edit</a>
					<a href="./match/delete?matchId=<?=$match['id']?>">remove</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	<?php endif; ?>
</table>