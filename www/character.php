<?php
define("CLASS_CHARACTER", 1);
require_once("common.php");

if (!isset($_POST['mode']))
	exit;
$response = '';
if ($_POST['mode'] == 'select_char')
{
	$response = $character->select_char($_POST['id_character'], $_POST['id_character_model']);
}
echo json_encode($response);
$user->update(UPD_LAST_TIME);
?>