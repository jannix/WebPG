<?php
define("CLASS_PARTY", 1);
require_once("common.php");

if ($_SESSION['connected'] == 0)
{
	$log = (isset($_POST['login'])) ? $_POST['login'] : '';
	$pass = (isset($_POST['password'])) ? $_POST['password'] : '';

	$sql = "SELECT id, login
		FROM users
		WHERE login = '" . $db->escape($log) . "'
			AND password = '" . md5($pass) . "'";
	if ($result = $db->query($sql))
	{
		if (!$db->num_rows($result))
		{
			$_SESSION['connected'] = 0;
			echo "You are not registered<br />
			<a href='index.php'>Retour<a/>";
		}
		else
		{
			$row = $db->fetch_array($result);
			$sql = "SELECT id
				FROM chat_messages
				ORDER BY id DESC
				LIMIT 0, 1";
			$result = $db->query($sql);
			$message = $db->fetch_assoc($result);
			$sql = 'UPDATE users
				SET last_time = ' . time() . ', last_messageid = ' . (int)$message['id'] . '
				WHERE id = ' . $row['id'];
			$db->query($sql);
			$_SESSION['id_user'] = $row['id'];
			$_SESSION['connected'] = 1;
			header("Location: home.php");
		}
	}
}
else
{
	$party->leave_party($user->get_id());
	session_destroy();
	header("Location: index.php");
}
?>
