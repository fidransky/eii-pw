<h1>
	<?=$title?>
	<a href="./player/add" class="pull-right">add a new player</a>
</h1>

<table class="table">
	<thead>
		<tr>
			<th>Name</th>
			<td></td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($players as $player): ?>
		<tr>
			<td><?=$player['name']?></td>
			<td>
				<a href="./player/edit?playerId=<?=$player['id']?>">edit</a>
				<a href="./player/delete?playerId=<?=$player['id']?>">remove</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>