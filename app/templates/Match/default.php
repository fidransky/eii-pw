<h1>
	<?=$title?>
	<a href="./match/add" class="pull-right">add a new match</a>
</h1>

<table class="table">
	<thead>
		<tr>
			<th>Name</th>
			<td></td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($matches as $match): ?>
		<tr>
			<td><?=$match['name']?></td>
			<td>
				<a href="./match/edit?matchId=<?=$match['id']?>">edit</a>
				<a href="./match/delete?matchId=<?=$match['id']?>">remove</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>