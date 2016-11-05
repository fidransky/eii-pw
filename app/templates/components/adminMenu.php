<?php if ($user->isLoggedIn() && $user->isInRole('admin')): ?>
<div>
	<ul class="list-unstyled">
		<li><a href="./league">Leagues</a></li>
		<li><a href="./team">Teams</a></li>
		<li><a href="./player">Players</a></li>
		<li><a href="./match">Matches</a></li>
	</ul>
</div>
<?php endif; ?>