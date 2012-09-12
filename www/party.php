<?php
define("CLASS_PARTY", 1);
require_once("common.php");

if (!isset($_POST['mode']))
	exit;
$response = '';
if ($_POST['mode'] == 'get_parties')
{
	$response = $party->get_parties(1);
}
else if ($_POST['mode'] == 'get_players')
{
	$response = $party->get_players($_POST['id_party']);
}
else if ($_POST['mode'] == 'is_started')
{
	$response = $party->is_started($_POST['id_party']);
}
else if ($_POST['mode'] == 'add_player')
{
	$response = $party->add_player($_POST['id_party'], $user->get_id());
}
else if ($_POST['mode'] == 'add_party')
{
	$response = $party->add_party($_POST['title'], $user->get_id());
}
else if ($_POST['mode'] == 'leave_party')
{
	$response = $party->leave_party($user->get_id());
}
else if ($_POST['mode'] == 'start_party')
{
	$response = $party->start_party($_POST['id_party']);
}
else if ($_POST['mode'] == 'swap_position')
{
	$response = $party->swap_position($_POST['id_party'], $user->get_id(), $_POST['team'], $_POST['position']);
}
echo json_encode($response);
$user->update(UPD_LAST_TIME);
?>