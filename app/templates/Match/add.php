<h1><?=$title?></h1>

<form action="<?=$addHandler?>" method="post">
	<div class="form-group">
		<label for="date">Date and time</label>
		<input type="datetime-local" name="date" class="form-control" required>
	</div>

	<div class="form-group">
		<label for="homeTeamId">Home team</label>
		<select name="homeTeamId" class="form-control" id="homeTeamId">
			<?php foreach ($teams as $key => $team): ?>
				<option value="<?=$team['id']?>"><?=$team['name']?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="form-group">
		<label>Players of the home team</label>
		<ol id="homeTeamPlayers">
			<li>
				<select name="homeTeamPlayers[]" class="form-control">
					<option value="" selected>select player</option>
					<?php foreach ($players as $player): ?>
						<option value="<?=$player['id']?>"><?=$player['name']?></option>
					<?php endforeach; ?>
				</select>
			</li>
		</ol>
	</div>

	<div class="form-group">
		<label for="visitingTeamId">Visiting team</label>
		<select name="visitingTeamId" class="form-control" id="visitingTeamId">
			<?php foreach ($teams as $key => $team): ?>
				<option value="<?=$team['id']?>"><?=$team['name']?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="form-group">
		<label>Players of the visiting team</label>
		<ol id="visitingTeamPlayers">
			<li>
				<select name="visitingTeamPlayers[]" class="form-control">
					<option value="" selected>select player</option>
					<?php foreach ($players as $player): ?>
						<option value="<?=$player['id']?>"><?=$player['name']?></option>
					<?php endforeach; ?>
				</select>
			</li>
		</ol>
	</div>

	<div class="form-group">
		<label for="state">State</label>
		<select class="form-control" id="state" disabled>
			<?php foreach ($states as $key => $state): ?>
				<option value="<?=$key?>"><?=$state?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<input type="hidden" name="state" value="0">

	<button type="submit" class="btn btn-default">Save</button>
</form>

<script>
var homeTeamPlayersList = document.getElementById('homeTeamPlayers');

// get players of the selected team
var selectors = [{
	id: document.getElementById('homeTeamId'),
	playersList: document.getElementById('homeTeamPlayers'),
	playersItems: document.querySelectorAll('#homeTeamPlayers select'),
	playersListName: 'homeTeamPlayers[]',
}, {
	id: document.getElementById('visitingTeamId'),
	playersList: document.getElementById('visitingTeamPlayers'),
	playersItems: document.querySelectorAll('#visitingTeamPlayers select'),
	playersListName: 'visitingTeamPlayers[]',
}];

selectors.forEach(function(selector) {
	var selectCallback = function(e) {
		e.preventDefault();

		var listItem = document.createElement('li');

		var select = this.cloneNode(true);
		select.addEventListener('focus', selectCallback);

		listItem.appendChild(select);

		selector.playersList.appendChild(listItem);
	};

	// update players' list on team change
	selector.id.addEventListener('change', function(e) {
		getAjax('<?=$teamChangedHandler?>?teamId=' + this.value, function(data) {
			var players = JSON.parse(data);

			// clear the list of select fields
			selector.playersList.innerHTML = '';

			// create new list item with select field
			var listItem = document.createElement('li');
			
			var select = document.createElement('select');
			select.setAttribute('name', selector.playersListName);
			select.classList.add('form-control');
			select.addEventListener('focus', selectCallback);

			listItem.appendChild(select);

			selector.playersList.appendChild(listItem);

			// fill in the select options
			var option = document.createElement('option');
			option.setAttribute('value', '');
			option.setAttribute('selected', 'selected');

			var label = document.createTextNode('select player');
			option.appendChild(label);

			select.appendChild(option);

			// players list
			players.forEach(function(player) {
				var option = document.createElement('option');
				option.setAttribute('value', player.id);

				var label = document.createTextNode(player.name);
				option.appendChild(label);

				select.appendChild(option);
			});
		});
	});

	// add more select fields on focus
	selector.playersItems.forEach(function(item) {
		item.addEventListener('focus', selectCallback);
	});
});

function getAjax(url, success) {
	var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.open('GET', url);
	xhr.onreadystatechange = function() {
		if (xhr.readyState > 3 && xhr.status === 200) success(xhr.responseText);
	};
	xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	xhr.send();

	return xhr;
}
</script>