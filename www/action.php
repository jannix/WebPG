<?php
define("CLASS_CHARACTER", 1);
define("CLASS_CHAT", 1);
define("REQUIRE_AUTH", 1);

require_once('common.php');

$char = $character->get_character_from_user($user->get_id());
if ((int)$char['id_cmodel'] == 0)
	header("Location: home.php");
$model = $character->get_character_model($char['id_cmodel']);
$chars = $character->get_heroes_from_party($char['id_party']);
$lvl = get_level($char['xp']);

$response = array(
	'user'	=> array(
		'lvl'	=> $lvl,
		'xp'	=> $char['xp'],
		'x'	=> $char['x'],
		'y'	=> $char['y'],
		'hp'	=> $char['hp'],
		'mp'	=> $char['mp'],
		'hpmax'	=> $model['hp'] + $model['hp_lvlup'] * $lvl,
		'mpmax'	=> $model['mp'] + $model['mp_lvlup'] * $lvl,
		'kills'	=> $char['kills'],
		'deaths'=> $char['deaths'],
	//	'assists'=> $char['assists'],
		'po'	=> $char['po'],
		'atk_phy'	=> ($model['atk_phy'] + $model['atk_phy_lvlup'] * $lvl),
		'atk_mag'	=> ($model['atk_mag'] + $model['atk_mag_lvlup'] * $lvl),
		'def_phy'	=> ($model['def_phy'] + $model['def_phy_lvlup'] * $lvl),
		'def_mag'	=> ($model['def_mag'] + $model['def_mag_lvlup'] * $lvl),
		'agility'	=> ($model['agility'] + $model['agility_lvlup'] * $lvl),
		'luck'		=> ($model['luck'] + $model['luck_lvlup'] * $lvl),
		'timenext_respawn'=> $char['timenext_respawn']
	)
);

// Update characters
$i = 0;
while (isset($chars[$i]))
{
	$response['chars'][] = array(
		'id_user'=> $chars[$i]['id_user'],
		'x'	=> $chars[$i]['x'],
		'y'	=> $chars[$i]['y'],
	);
	$i++;
}

// Refresh chat
$response['chat'] = $chat->get_messages(array(INGAME_CHAN, INGAME_TEAM_CHAN),
					$char['id_party'],
					$user->get_last_messageid(),
					$char['team']);
if (empty($response['chat']))
	unset($response['chat']);
else
	$user->update(UPD_ALL);

if (isset($_POST['id']))
{
	$position = explode('_', $_POST['id']);
	$x = (int)$position[1];
	$y = (int)$position[2];
	$x = $char['x'] + $x;
	$y = $char['y'] + $y;
}
else
	exit(json_encode($response));

$distance = max(abs($x - $char['x']), abs($y - $char['y']));
$microtime = microtime(true) * 1000;

if ($char['timenext_respawn'] > $microtime)
	exit(json_encode($response));

// 1st step: check character
if ($char['timenext_hit'] <= $microtime AND
    $distance <= 1)
{
	$sql = "SELECT c.id,
			c.team,
			m.def_phy,
			m.agility,
			c.hp,
			c.xp,
			m.hp_lvlup,
			m.def_phy_lvlup,
			m.agility_lvlup
		FROM characters c
			LEFT JOIN character_models m
				ON m.id = c.id_cmodel
		WHERE c.x = " . $x . "
			AND c.y = " . $y . "
			AND c.id_party = " . $char['id_party'];
	$result = $db->query($sql);
	if ($db->num_rows($result))
	{
		$target = $db->fetch_assoc($result);
		if ($target['team'] != $char['team'])
		{
			$tlvl = get_level($char['xp']);
			$atk_phy = ($model['atk_phy'] + $model['atk_phy_lvlup'] * $lvl);
			if (mt_rand(0, 99) <= ($model['luck'] + $model['luck_lvlup'] * $lvl))
			{
				$atk_phy = $atk_phy + $atk_phy;
			}
			else if (mt_rand(0, 99) <= max(1, (($target['agility'] + $target['agility_lvlup'] * $tlvl) -
							   ($model['agility'] + $model['agility_lvlup'] * $lvl)) / 2))
			{
				$atk_phy = 0;
			}
			$tdef_phy = ($target['def_phy'] + $target['def_phy_lvlup'] * $tlvl);
			$damage = $atk_phy - $tdef_phy;

			$new_xp = ($tlvl * CFG_GAME_HIT_BONUS_PTS_XP_BY_LVL) +
				      ($target['xp'] * CFG_GAME_HIT_BONUS_PTS_XP_BY_LVL / 100);
			$timenext_hit = $microtime + (1000 - ($model['agility'] + $model['agility_lvlup'] * $lvl) / CFG_GAME_AGI_BY_HIT * 50);
			if ($target['hp'] - $damage <= 0)
			{
				$timenext_respawn = $microtime + (CFG_GAME_TIME_RESPAWN_BASE + $lvl * CFG_GAME_TIME_RESPAWN_LVL);
				$hpmax = ($target['hp'] + $target['hp_lvlup'] * $tlvl);
				$mpmax = ($target['mp'] + $target['mp_lvlup'] * $tlvl);
				
				$sql = "UPDATE characters
					SET hp = " . $hpmax . ",
						mp = " . $mpmax . ",
						deaths = deaths + 1,
						timenext_respawn = " . $timenext_respawn . "
					WHERE id = " . $target['id'];
				$db->query($sql);
				
				$new_xp = $new_xp + $new_xp;
				$new_po = $char['po'] + CFG_GAME_KILL_BONUS_PO;
				$sql = "UPDATE characters
					SET xp = " . $new_xp . ",
						po = " . $new_po . ",
						kills = kills + 1,
						timenext_hit = " . $timenext_hit . "
					WHERE id = " . $char['id'];
				$db->query($sql);
				$response['user']['kills'] = $char['kills'] + 1;
			}
			else
			{
				$sql = "UPDATE characters
					SET hp = " . ($target['hp'] - $damage) . "
					WHERE id = " . $target['id'];
				$db->query($sql);
				
				$new_po = $char['po'] + CFG_GAME_HIT_BONUS_PO;
				$sql = "UPDATE characters
					SET xp = " . $new_xp . ",
						po = " . $new_po . ",
						timenext_hit = " . $timenext_hit . "
					WHERE id = " . $char['id'];
				$db->query($sql);
			}
			$response['user']['xp'] = $new_xp;
			$response['user']['po'] = $new_po;
			$response['attack'] = $timenext_hit;
		}
		exit(json_encode($response));
	}
}

// 2nd step: check building
if ($distance <= 1)
{
	$sql = "SELECT id
		FROM buildings
		WHERE x = " . $x . "
			AND y = " . $y . "
			AND id_party = " . $char['id_party'];
	$result = $db->query($sql);
	if ($db->num_rows($result))
	{
		$row = $db->fetch_assoc($result);
		$response['building'] = $row['id'];
		exit(json_encode($response));
	}
}

// 3rd step: check move
//echo $char['timenext_move'] . '<br />';
//echo microtime(true) * 1000 . '<br />';
if ($char['timenext_move'] <= $microtime
    AND $distance <= ($model['agility'] + $model['agility_lvlup'] * $lvl) / CFG_GAME_AGI_BY_CASE)
{
	$sql = "SELECT x
		FROM map_cases
		WHERE x = " . $x . "
			AND y = " . $y . "
			AND impassable = 0";
	$result = $db->query($sql);
	if ($db->num_rows($result))
	{
		$timenext_move = $microtime + (1000 -
					       ($model['agility'] + $model['agility_lvlup'] * $lvl) /
					       CFG_GAME_AGI_BY_CASE * 50);
		//echo $nexttime;
		$sql = "UPDATE characters
			SET x = " . $x . ", y = " . $y . ", timenext_move = " . $timenext_move . "
			WHERE id = " . $char['id'];
		if ($db->query($sql))
		{
			$response['user']['x'] = $x;
			$response['user']['y'] = $y;
			$response['move'] = $timenext_move;
		}
		else
			$response['move'] = false;
	}
}
exit(json_encode($response));
?>