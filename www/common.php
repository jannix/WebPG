<?php
session_start();
error_reporting(E_ALL);

$_SESSION['connected'] = isset($_SESSION['connected']) ? $_SESSION['connected'] : 0;
if (defined("REQUIRE_AUTH") AND $_SESSION['connected'] == 0)
{
	header("Location: index.php");
}

require_once('config.inc.php');
require_once('libs/constantes.php');
require_once('libs/functions.php');

require_once('libs/class/sql.php');
$db = new sql($config['host'], $config['user'], $config['pass'], $config['dbmain']);
$db->connect();
$db->set_charset('utf8');
unset($config);

$_SESSION['id_user'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
require_once('libs/class/user.php');
$user = new user($_SESSION['id_user']);

if (defined("CLASS_BUILDING"))
{
	require_once('libs/class/building.php');
	$building = new building();
}
if (defined("CLASS_CHARACTER"))
{
	require_once('libs/class/character.php');
	$character = new character();
}
if (defined("CLASS_PARTY"))
{
	require_once('libs/class/party.php');
	$party = new party($user->get_id());
}
if (defined("CLASS_CHAT"))
{
	require_once('libs/class/chat.php');
	$chat = new chat();
}
?>