<h1>Log in</h1>

<form action="<?=$logInHandler?>" method="post">
	<div class="form-group">
		<label for="exampleInputEmail1">Email address</label>
		<input type="email" name="username" class="form-control" id="exampleInputEmail1" placeholder="Email">
	</div>

	<div class="form-group">
		<label for="exampleInputPassword1">Password</label>
		<input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
	</div>

	<button type="submit" class="btn btn-default">Log me in</button>
</form>