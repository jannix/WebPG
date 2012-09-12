<?php
define("CLASS_CHAT", 1);
define("CLASS_PARTY", 1);
require_once("common.php");

if (!isset($_POST['mode']))
	exit;
$response = '';
if ($_POST['mode'] == 'get_messages')
{
	if ($_POST['chan'] == OUT_GAME)
	{
		$response[MAIN_ROOM_CHAN] = $chat->get_messages(MAIN_ROOM_CHAN, 0, $user->get_last_messageid());
		$response[GAME_ROOM_CHAN] = $chat->get_messages(GAME_ROOM_CHAN, 0, $user->get_last_messageid());
	}
	else if ($_POST['chan'] == IN_GAME)
	{
		$response = $chat->get_messages(array(INGAME_CHAN, INGAME_TEAM_CHAN),
						$party->get_id(),
						$user->get_last_messageid());
	}
	$user->update(UPD_ALL);
}
else if ($_POST['mode'] == 'add_message')
{
	$response = $chat->add_message($_POST['message'], $_POST['chan'], $user->get_id(), $party->get_id());
	$user->update(UPD_LAST_TIME);
}
echo json_encode($response);
?>
