<h1>Sign in</h1>

<form action="<?=$signInHandler?>" method="post">
	<div class="form-group">
		<label for="name">Your name</label>
		<input type="text" name="name" class="form-control" id="name" placeholder="Name and surname">
	</div>

	<div class="form-group">
		<label for="mail">E-mail address</label>
		<input type="email" name="mail" class="form-control" id="mail" placeholder="E-mail">
	</div>

	<div class="form-group">
		<label for="password">Password</label>
		<input type="password" name="password" class="form-control" id="password" placeholder="Password">
	</div>

	<div class="form-group">
		<label for="password_check">Password one more time</label>
		<input type="password" name="password_check" class="form-control" id="password_check" placeholder="Password">
	</div>

	<button type="submit" class="btn btn-default">Sign me in</button>
</form>