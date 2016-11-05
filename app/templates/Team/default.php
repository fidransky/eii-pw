<h1>
	<?=$title?>
	<a href="./team/add" class="pull-right">add a new team</a>
</h1>

<table class="table">
	<thead>
		<tr>
			<th>Name</th>
			<th>Stadium</th>
			<td></td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($teams as $team): ?>
		<tr>
			<td><?=$team['name']?></td>
			<td><?=$team['stadium']?></td>
			<td>
				<a href="./team/edit?teamId=<?=$team['id']?>">edit</a>
				<a href="./team/delete?teamId=<?=$team['id']?>">remove</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>