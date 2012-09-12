<?php
define("CLASS_BUILDING", 1);
define("CLASS_CHARACTER", 1);
define("CLASS_PARTY", 1);
define("CLASS_CHAT", 1);
define("REQUIRE_AUTH", 1);

require_once('common.php');

$char = $character->get_character_from_user($user->get_id());
if ((int)$char['id_cmodel'] == 0)
	header("Location: home.php");
$model = $character->get_character_model($char['id_cmodel']);
$lvl = get_level($char['xp']);

$sql = "SELECT c.*, i.imagename
	FROM map_cases c
		LEFT JOIN images i
			ON i.id = c.id_image
	WHERE x BETWEEN 0 AND " . CFG_MAP_SIZE . "
		AND y BETWEEN 0 AND " . CFG_MAP_SIZE;
$result = $db->query($sql);
$cases = array();
while ($row = $db->fetch_assoc($result))
{
	$cases[$row['x']][$row['y']] = $row;
}
$chars = $character->get_heroes_from_party($party->get_id());
$buildings = $building->get_buildings_from_party($char['id_party']);

$x_min = $char['x'] - CFG_GAME_MAPDISTANCE;
$x_max = $char['x'] + CFG_GAME_MAPDISTANCE;
$y_min = $char['y'] - CFG_GAME_MAPDISTANCE;
$y_max = $char['y'] + CFG_GAME_MAPDISTANCE;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="en" />
		<meta name="Copyright" content="GP1,2011" />
		<meta name="Author" content="GP1" />
		<meta name="description" content="Massively Web Online Battle Arena" />
		<meta name="keywords" content="GP1,isart,game,dota,league,legend" />
		<link rel="stylesheet" media="screen" type="text/css" title="Base" href="style/css/gameUI.css" />
		<link rel="stylesheet" media="screen" type="text/css" href="style/css/MooDialog.css" />
		<title>League of the Ancient GP1</title>
		<script type="text/javascript">
		const LIMIT_PLAYERS = <?php echo LIMIT_PLAYERS; ?>;
		const CFG_GAME_AGI_BY_CASE = <?php echo CFG_GAME_AGI_BY_CASE; ?>;
		const CFG_GAME_MAPDISTANCE = <?php echo CFG_GAME_MAPDISTANCE; ?>;

		var config = {
			id_party : <?php echo $party->get_id(); ?>,
			id_party_creator : <?php echo $party->get_id_creator(); ?>,
			id_character : <?php $char = $character->get_character_from_user($user->get_id());echo (int)$char['id'];?>,
			id_character_model : <?php echo (int)$char['id_cmodel'];?>,
			id_user : <?php echo $user->get_id(); ?>
		};

		var character = {
			agility : <?php echo ($model['agility'] + $model['agility_lvlup'] * $lvl); ?>,
			x : <?php echo $char['x']; ?>,
			y : <?php echo $char['y']; ?>
		};

		var chars = {
		<?php
		$i = 0;
		while (isset($chars[$i]))
		{
			echo $i . ':{id_user:' . $chars[$i]['id_user'] . ',x:' . $chars[$i]['x'] . ',y:' . $chars[$i]['y'] . ',id_image:' . $chars[$i]['id_image'] . '}';
			$i++;
			if (isset($chars[$i]))
				echo ',';
		}
		?>};

		var buildings = {
		<?php
		$i = 0;
		while (isset($buildings[$i]))
		{
			echo $i . ':{id:' . $buildings[$i]['id'] . ',x:' . $buildings[$i]['x'] . ',y:' . $buildings[$i]['y'] . ',id_image:' . $buildings[$i]['id_image'] . ',objects:{';
			$objects = $building->get_building_model_objects($buildings[$i]['id_bmodel']);
			$j = 0;
			while (isset($objects[$j]))
			{
				echo $j . ':{id:' . $objects[$j]['o_id'] . ',name:"' . $objects[$j]['name'] . '",description:"' . $objects[$j]['description'] . '",duration:' . $objects[$j]['duration'] . ',price:' . $objects[$j]['price'] . '}';
				$j++;
				if (isset($objects[$j]))
					echo ',';
			}
			echo '}}' . "\n";
			$i++;
			if (isset($buildings[$i]))
				echo ',';
		}
		?>};

		var images = {
		<?php
		$sql = "SELECT *
			FROM images";
		$result = $db->query($sql);
		while ($row = $db->fetch_assoc($result))
		{
			echo '' . $row['id'] . ':"' . $row['imagename'] . '",';
		}
		?>0:0};

		var map_cases = {
		<?php
		$y = 0;
		while ($y < CFG_MAP_SIZE)
		{
			echo $y . ':{';
			$x = 0;
			while ($x < CFG_MAP_SIZE)
			{
				echo $x . ':' . $cases[$x][$y]['id_image'] ;
				if ($x < CFG_MAP_SIZE)
					echo ',';
				$x++;
			}
			echo '}';
			if ($y < CFG_MAP_SIZE)
				echo ',';
			$y++;
		}
		?>
		};
		</script>
	</head>
	<body>
		<div id="response" style="position:fixed;bottom:0;left:0;z-index:10000;"></div>
		<div id="body">
			<div id="menu">
				<table>
					<tr>
					</tr>
				</table>
			</div>
			<div id="center">
				<div id="center_option">
				</div>
				<div id="center_game">
					<div id="shop"><span onClick="this.parentNode.fade('hide');">Close</span></div>
					<div id="mini_map">
						<?php
						if (is_file('style/images/mini_map.png'))
							echo '<img src="style/images/mini_map.png" alt="mini_map" title="" />';
						else
						{
						?>
						<table>
							<tr>
								<?php
								$y = CFG_MAP_SIZE - 1;
								while ($y >= 0)
								{
									$x = 0;
									while ($x < CFG_MAP_SIZE)
									{
										if (isset($cases[$x][$y]))
											echo '<td style="background-image:url(style/images/' . $cases[$x][$y]['imagename'] . ');"></td>';
										else
											echo '<td></td>';
										$x++;
									}
									echo '</tr><tr>';
									$y--;
								}
								?>
							</tr>
						</table>
						<?php
						}
						?>
					</div>
					<div id="character_summary">
						<?php
						echo '<table>
							<tr>
								<td colspan="2">' . $model['name'] . ' (<span id="char_x">' . $char['x'] . '</span>,<span id="char_y">' . $char['y'] . '</span>)</td>
							</tr>
							<tr>
								<td colspan="2">Lvl <span id="char_lvl">' . $lvl . '</span> (<span id="char_xp">' . $char['xp'] . '</span> XP)</td>
							</tr>
							<tr>
								<td>Kills <span id="char_kills">' . $char['kills'] . '</span></td>
								<td>Deaths <span id="char_deaths">' . $char['deaths'] . '</span></td>
							</tr>
							<tr>
								<td>HP</td>
								<td><span id="char_hp">' . $char['hp'] . '</span>/<span id="char_hpmax">' . ($model['hp'] + $model['hp_lvlup'] * $lvl) . '</span></td>
							</tr>
							<tr>
								<td>MP</td>
								<td><span id="char_mp">' . $char['mp'] . '</span>/<span id="char_mpmax">' . ($model['mp'] + $model['mp_lvlup'] * $lvl) . '</span></td>
							</tr>
							<tr>
								<td>PO</td>
								<td><span id="char_po">' . $char['po'] . '</span></td>
							</tr>
							<tr>
								<td>Atk Phy</td>
								<td><span id="char_atk_phy">' . ($model['atk_phy'] + $model['atk_phy_lvlup'] * $lvl) . '</span></td>
							</tr>
							<tr>
								<td>Atk Mag</td>
								<td><span id="char_atk_mag">' . ($model['atk_mag'] + $model['atk_mag_lvlup'] * $lvl) . '</span></td>
							</tr>
							<tr>
								<td>Def Phy</td>
								<td><span id="char_def_phy">' . ($model['def_phy'] + $model['def_phy_lvlup'] * $lvl) . '</span></td>
							</tr>
							<tr>
								<td>Def Mag</td>
								<td><span id="char_def_mag">' . ($model['def_mag'] + $model['def_mag_lvlup'] * $lvl) . '</span></td>
							</tr>
							<tr>
								<td>Agility</td>
								<td><span id="char_agility">' . ($model['agility'] + $model['agility_lvlup'] * $lvl) . '</span></td>
							</tr>
							<tr>
								<td>Luck</td>
								<td><span id="char_luck">' . ($model['luck'] + $model['luck_lvlup'] * $lvl) . '</span></td>
							</tr>
							<tr>
								<td>Move CD</td>
								<td id="countdown_move">N/A</td>
							</tr>
							<tr>
								<td>Attack CD</td>
								<td id="countdown_attack">N/A</td>
							</tr>
						</table>';
						?>
					</div>
					<div id="character_status">
						Status
					</div>
					<div id="allies_team">
						<table>
							<tr>
								<?php
								$chars = $character->get_heroes_from_party($party->get_id(), SORT_BY_TEAM);
								$max_per_team = ceil(LIMIT_PLAYERS / 2);
								$i = 0;
								while ($i < $max_per_team)
								{
									if (isset($chars[$char['team']][$i]))
									{
										echo '<td>' . $chars[$char['team']][$i]['name'] . '</td>';
									}
									$i++;
								}
								?>
							</tr>
						</table>
					</div>
					<div id="ennemies_team">
						<table>
							<tr>
								<?php
								$max_per_team = ceil(LIMIT_PLAYERS / 2);
								$team_ennemy = ($char['team'] + 1) % 2;
								$i = 0;
								while ($i < $max_per_team)
								{
									if (isset($chars[$team_ennemy][$i]))
									{
										echo '<td>' . $chars[$team_ennemy][$i]['name'] . '</td>';
									}
									$i++;
								}
								?>
							</tr>
						</table>
					</div>
					<div id="map">
						<table>
							<?php
							$buildings = $building->get_buildings_from_party($char['id_party'], SORT_BY_POS);
							$chars = $character->get_heroes_from_party($char['id_party'], SORT_BY_POS);
							$y = CFG_GAME_MAPDISTANCE;
							$y_pos = $y_max;
							while ($y_pos >= $y_min)
							{
								$x = -CFG_GAME_MAPDISTANCE;
								$x_pos = $x_min;
								echo '<tr><th id="map_y' . $y . '">' . $y_pos . '</th>';
								while ($x_pos <= $x_max)
								{
									echo '<td><div
										id="map_' . $x . '_' . $y . '"
										class="map_cell"';
									if (isset($cases[$x_pos][$y_pos]))
										echo 'style="background-image:url(style/images/' . $cases[$x_pos][$y_pos]['imagename'] . ');"';
									echo '>';
									if (isset($chars[$x_pos][$y_pos]))
										echo '<img src="style/images/' . $chars[$x_pos][$y_pos]['imagename'] . '" alt="" />';
									else if (isset($buildings[$x_pos][$y_pos]))
										echo '<img src="style/images/' . $buildings[$x_pos][$y_pos]['imagename'] . '" alt="" />';
									echo '</div></td>';
									$x++;
									$x_pos++;
								}
								echo "</tr>\n";
								$y--;
								$y_pos--;
							}
							$x_pos = $x_min;
							$x = -CFG_GAME_MAPDISTANCE;
							echo '<tr><th></th>';
							while ($x_pos <= $x_max)
							{
								echo '<th id="map_x' . $x . '">' . $x_pos . '</th>';
								$x++;
								$x_pos++;
							}
							echo '</tr>';
							?>
						</table>
					</div>
					<div id="chat">
						<table id="chat_table">
							<tr>
								<td>
									<select id="chat_target">
										<option value="<?php echo INGAME_CHAN?>">All</option>
										<option value="<?php echo INGAME_TEAM_CHAN?>">Team</option>
									</select>
								</td>
								<td class="chat_inputbox">
									<input type="text" id="chat_input" />
								</td>
								<td>
									<button id="chat_button" type="button">Chat!</button>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<div id="chat_textbox"></div>
								</td>
							</tr>
						</table>
					</div>
					<div id="character_inventory">
						<table>
							<tr>
								<?php
								$objects = $character->get_objects_model($char['id']);
								$i = 0;
								while ($i < CFG_GAME_MAXOBJECTS)
								{
									if (isset($objects[$i]))
									{
										echo '<td>' . $objects[$i]['name'] . '</td>';
									}
									else
									{
										echo '<td>Empty place</td>';
									}
									$i++;
									if (($i % (CFG_GAME_MAXOBJECTS / 2)) == 0)
										echo '</tr><tr>';
								}
								?>
							</tr>
						</table>
					</div>
					<div id="character_skills">
						skills
					</div>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="libs/js/mootools.js"></script>
	<script type="text/javascript" src="libs/js/common.js"></script>
	<script type="text/javascript" src="libs/js/chat.js"></script>
	<script type="text/javascript" src="libs/js/menu.js"></script>
	<script type="text/javascript" src="libs/js/gameUI.js"></script>
</html>