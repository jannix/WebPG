<?php
define("REQUIRE_AUTH", 1);
define("CLASS_CHARACTER", 1);
define("CLASS_PARTY", 1);
require_once("common.php");

$char = $character->get_character_from_user($user->get_id());
if ((int)$char['id_cmodel'] == 0)
	header("Location: home.php");

if (isset($_POST['id']))
{
	$ids = explode('_', $_POST['id']);
	$id_building = $ids[0];
	$id_object_model = $ids[1];

	$sql = "SELECT id_bmodel
		FROM buildings
		WHERE id = " . $id_building . "
			AND id_party = " . $party->get_id() . "
			AND x BETWEEN " . ($char['x'] - 1) . " AND " . ($char['x'] + 1) . "
			AND y BETWEEN " . ($char['y'] - 1) . " AND " . ($char['y'] + 1);
	$result = $db->query($sql);
	if ($db->num_rows($result))
	{
		$row = $db->fetch_assoc($result);
		$sql = "SELECT price
			FROM building_model_objects
			WHERE id_bmodel = " . $row['id_bmodel'] . "
				AND id_omodel = " . $id_object_model;
		$result = $db->query($sql);
		$row = $db->fetch_assoc($result);
		if ($char['po'] >= $row['price'])
		{
			$objects = $character->get_character_objects($char['id']);
			$i = 0;
			$position_objects = array_fill(0, CFG_GAME_MAXOBJECTS, 0);
			while (isset($objects[$i]))
			{
				$position_objects[$objects[$i]['position']] = 1;
				$i++;
			}
			$i = 0;
			while ($i < CFG_GAME_MAXOBJECTS)
			{
				if ($position_objects[$i] == 0)
					break;
				$i++;
			}
			
			if ($i < CFG_GAME_MAXOBJECTS)
			{
				$sql = "UPDATE characters
					SET po = " . ($char['po'] - $row['price']) . "
					WHERE id = " . $char['id'];
				if ($db->query($sql))
				{
					$sql = "INSERT INTO character_objects (id_omodel, id_character, position) VALUES
						(" . $id_object_model . ", " . $char['id'] . ", " . $i . ")";
					exit(json_encode($db->query($sql)));
				}
			}
		}
	}
}
exit(json_encode(false));
?>