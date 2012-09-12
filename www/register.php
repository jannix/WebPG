<?php
require_once("common.php");

$log = $_POST['login'];
$pass = $_POST['password'];
$pass2 = $_POST['password_confirm'];

$sql = "SELECT login
	FROM users
	WHERE login = '" . $db->escape($log) . "'";
if ($pass != $pass2)
	echo "Password different.";
else if (empty($pass) OR empty($log))
	echo "Something is empty.";
else if ($result = $db->query($sql))
{
	if ($db->num_rows($result))
	{
		echo "You suck man! Your motherfucking login or
			password exist <br />
			Try again!<br />
			<a href='index.php'>Retour<a/>";
	}
	else
	{
		echo "Congratulation!<br />";
		$sql = "INSERT INTO users (login, password) VALUES
			('" . $db->escape($log) . "', '" . md5($pass) . "')";
		if ($db->query($sql))
			echo "Now, you can play.<br />
			<a href='index.php'>Retour<a/>";
		else
			echo "Error request: Can't register your account.<br />
			<a href='index.php'>Retour<a/>";
	}
}

?>
