<?php
class	chat
{
	function	add_message($message, $chan, $id_user, $id_party)
	{
		global $db;
		
		if (empty($message) OR ($chan == GAME_ROOM_CHAN AND $id_party == 0))
			return false;
		$id_party = (int)$id_party;
		$id_user = (int)$id_user;
		$team = 0;
		if ($chan == INGAME_TEAM_CHAN)
		{
			$sql = "SELECT team
				FROM characters
				WHERE id_party = " . $id_party . "
					AND id_user = " . $id_user;
			$result = $db->query($sql);
			$row = $db->fetch_assoc($result);
			$team = $row['team'];
		}
		$sql = "INSERT INTO chat_messages (id_user, id_party, timepost, text, chan, team) VALUES
			(" . $id_user . ", " . $id_party . ", " . time() . ", '" . $db->escape($message) . "', " . (int)$chan . ", " . $team . ")";
		return $db->query($sql);
	}

	function	get_messages($chan, $id_party, $lastid, $team = -1)
	{
		global $db;

		$sql = "SELECT m.*, u.login AS name
				FROM chat_messages m
					LEFT JOIN users u
						ON m.id_user = u.id
				WHERE m.id > " . (int)$lastid . "
					AND m.id_party = " . (int)$id_party;
		if (is_array($chan))
		{
			$sql .= " AND ((m.chan = " . INGAME_CHAN .") OR
					(m.chan = " . INGAME_TEAM_CHAN . " AND
					 m.team = " . $team . "))";
		}
		else
			$sql .= "= " . (int)$chan;
		$result = $db->query($sql);
		$messages = array();
		while ($row = $db->fetch_assoc($result))
		{
			$messages[] = $row;
		}
		return $messages;
	}
}
?>