<h1>Log in</h1>

<form action="<?=$logInHandler?>" method="post">
	<div class="form-group">
		<label for="mail">E-mail address</label>
		<input type="email" name="mail" class="form-control" id="mail" placeholder="E-mail">
	</div>

	<div class="form-group">
		<label for="password">Password</label>
		<input type="password" name="password" class="form-control" id="password" placeholder="Password">
	</div>

	<button type="submit" class="btn btn-default">Log me in</button>
</form>