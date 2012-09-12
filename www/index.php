<h1>Connexion</h1>
<form method = "post" action = "login.php">
	<table border = 0>
		<tr>
			<td>Login</td>
			<td><input type="text" name="login" /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="password" /></td>
		</tr>
		<tr>
			<td colspan = "2">
				<input type="submit" name="submit" value="Connection" />
				<hr />
			</td>
		</tr>
	</table>
</form>

<h1>Inscription</h1>
<form method = "post" action = "register.php">
	<table border = 0>
		<tr>
			<td>Login</td>
			<td><input type = "text" name = "login" /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="password" /></td>
		</tr>
		<tr>
			<td>Are you sure? (password)</td>
			<td><input type="password" name="password_confirm" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" name="submit" value="Register" />
				<hr />
			</td>
		</tr>
	</table>
</form>
