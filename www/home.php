<?php
define("CLASS_CHARACTER", 1);
define("CLASS_PARTY", 1);
define("CLASS_CHAT", 1);
define("REQUIRE_AUTH", 1);

require_once('common.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="en" />
		<meta name="Copyright" content="GP1,2011" />
		<meta name="Author" content="GP1" />
		<meta name="description" content="Massively Web Online Battle Arena" />
		<meta name="keywords" content="GP1,isart,game,dota,league,legend" />
		<link rel="stylesheet" media="screen" type="text/css" title="Base" href="style/css/home.css" />
		<link rel="stylesheet" media="screen" type="text/css" href="style/css/MooDialog.css" />
		<title>League of the Ancient GP1</title>
		<script type="text/javascript">
		var config = new Array();
		const LIMIT_PLAYERS = <?php echo LIMIT_PLAYERS; ?>;
		config['id_party'] = <?php echo $party->get_id(); ?>;
		config['id_party_creator'] = <?php echo $party->get_id_creator(); ?>;
		config['id_character'] = <?php $char = $character->get_character_from_user($user->get_id());echo (int)$char['id'];?>;
		config['id_character_model'] = <?php echo (int)$char['id_cmodel'];?>;
		config['id_user'] = <?php echo $user->get_id(); ?>;
		config['party_is_started'] = <?php echo $party->is_started($party->get_id()); ?>;
		</script>
	</head>
	<body>
		<div id="body">
			<div id="characters_selection">
				<table>
					<tr>
				<?php
				$chars = $character->get_hero_models();
				$i = 0;
				while (isset($chars[$i]))
				{
					echo '<td>
						<table>
							<tr>
								<td colspan="4"><img src="style/images/' . $chars[$i]['imagename'] . '" alt="" title="" style="float:left;"/>' . $chars[$i]['name'] . '<br />
								' . $chars[$i]['description'] . '</td>
							</tr>
							<tr>
								<td>HP:</td><td>' . $chars[$i]['hp'] . ' (+' . $chars[$i]['hp_lvlup'] . ')</td>
								<td>MP:</td><td>' . $chars[$i]['mp'] . ' (+' . $chars[$i]['mp_lvlup'] . ')</td>
							</tr>
							<tr>
								<td>Atk Phy:</td><td>' . $chars[$i]['atk_phy'] . ' (+' . $chars[$i]['atk_phy_lvlup'] . ')</td>
								<td>Atk Mag:</td><td>' . $chars[$i]['atk_mag'] . ' (+' . $chars[$i]['atk_mag_lvlup'] . ')</td>
							</tr>
							<tr>
								<td>Def Phy:</td><td>' . $chars[$i]['def_phy'] . ' (+' . $chars[$i]['def_phy_lvlup'] . ')</td>
								<td>Def Mag:</td><td>' . $chars[$i]['def_mag'] . ' (+' . $chars[$i]['def_mag_lvlup'] . ')</td>
							</tr>
							<tr>
								<td>Agility:</td><td>' . $chars[$i]['agility'] . ' (+' . $chars[$i]['agility_lvlup'] . ')</td>
								<td>Luck:</td><td>' . $chars[$i]['luck'] . ' (+	' . $chars[$i]['luck_lvlup'] . ')</td>
							</tr>
							<tr>
								<td colspan="2"><button class="char_pick_up" id="' . $chars[$i]['id'] . '">Pick up!</button></td>
							</tr>
						</table>
						</td>';
					$i++;
				}
				?>
					</tr>
				</table>
			</div>
			<div id="prelaunch_party">
				<button type="button" id="button_start_party">Start!</button>
				<button type="button" id="button_choose_character">Characters</button>
				<button type="button" id="button_redirect_game">Go into the Game</button>
				<span id="party_countdown"></span>
				<h2 id="party_title"><?php echo $party->get_title();?></h2>
				<table id="players">
					<tr>
						<th>Umamy team</th>
						<th>Sugra team</th>
					</tr>
					<?php
					$players = $party->get_players($party->get_id());
					$i = 0;
					$max_per_team = ceil(LIMIT_PLAYERS / 2);
					$teams = array(
						array_fill(0, $max_per_team, array('name' => '', 'id_user' => 0)),
						array_fill(0, $max_per_team, array('name' => '', 'id_user' => 0))
					);
					while (isset($players[$i]))
					{
						$teams[$players[$i]['team']][$players[$i]['position']] = $players[$i];
						$i++;
					}
					$i = 0;
					while ($i < $max_per_team)
					{
						echo '
						<tr>
							<td><div id=\'{"t":0,"p":' . $i . '}\' ' . (($teams[0][$i]['id_user'] == $user->get_id()) ? 'class="my_char"' : '') . '>' . $teams[0][$i]['name'] . '</div></td>
							<td><div id=\'{"t":1,"p":' . $i . '}\' ' . ($teams[1][$i]['id_user'] == $user->get_id() ? 'class="my_char"' : '') . '>' . $teams[1][$i]['name'] . '</div></td>
						</tr>';
						$i++;
					}
					?>
				</table>
				<button type="button" id="button_leave_party">Leave the party</button>
			</div>
			<div id="div_parties">
				<button type="button" id="button_create_party">Create a party</button>
				<table id="parties">
				<?php
				$parties = $party->get_parties(1);
				$i = 0;
				while (isset($parties[$i]))
				{
					echo '<tr title="' . date('D j, H:m', $parties[$i]['time_gamecreate']) . ' ou ' . $parties[$i]['time_gamecreate'] . ' pour les droïdes.">
						<td>' . $parties[$i]['count_players'] . '</td>
						<td>' . $parties[$i]['title'] . '</td>
						<td><button onClick="party.rejoin(' . $parties[$i]['id'] . ', \'' . $parties[$i]['title'] . '\');">Rejoin</button></td>
					</tr>';
					$i++;
				}
				?>
				</table>
			</div>
			<div id="menu">
				<table>
					<tr>
					</tr>
				</table>
			</div>
			<div id="center">
				<div id="center_home">
				<?php
				// NEWS?
				?>
				</div>
				<div id="center_option">
				<?php
				// Changer mdp, login?
				?>
				Option
				</div>
				<div id="center_main_room">
					<table>
						<tr>
							<td class="chat_inputbox">
								<input type="text" id="main_room_input" />
							</td>
							<td>
								<button id="main_room_button" type="button">Chat!</button>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<div id="main_room_textbox">
								<?php
								$messages = $chat->get_messages(MAIN_ROOM_CHAN, 0, $user->get_last_messageid());
								$i = 0;
								while (isset($messages[$i]))
								{
									echo $messages[$i]['name'] . ' : ' . $messages[$i]['text'] . '<br />';
									$i++;
								}
								?>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="center_game_room">
					<table>
						<tr>
							<td class="chat_inputbox">
								<input type="text" id="game_room_input" />
							</td>
							<td>
								<button id="game_room_button" type="button">Chat!</button>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<div id="game_room_textbox">
								<?php
								$messages = $chat->get_messages(GAME_ROOM_CHAN, 0, $user->get_last_messageid());
								$i = 0;
								while (isset($messages[$i]))
								{
									echo $messages[$i]['name'] . ' : ' . $messages[$i]['text'] . '<br />';
									$i++;
								}
								?>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="libs/js/mootools.js"></script>

	<script type="text/javascript" src="libs/js/common.js"></script>
	<script type="text/javascript" src="libs/js/chat.js"></script>
	<script type="text/javascript" src="libs/js/menu.js"></script>
	<script type="text/javascript" src="libs/js/home.js"></script>
</html>
<?php
$user->update(UPD_ALL);
?>