<h1>
	<?=$title?>
	<a href="./league/add" class="pull-right">add a new league</a>
</h1>

<table class="table">
	<thead>
		<tr>
			<th>Name</th>
			<th>Season</th>
			<td></td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($leagues as $league): ?>
		<tr>
			<td><?=$league['name']?></td>
			<td><?=$league['season']?></td>
			<td>
				<a href="./league/edit?leagueId=<?=$league['id']?>">edit</a>
				<a href="./league/delete?leagueId=<?=$league['id']?>">remove</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>