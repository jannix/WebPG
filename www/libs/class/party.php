<?php
class	party
{
	protected	$data;

	public function	party($id_user)
	{
		global $db;

		$this->data = $this->get_party_from_user($id_user);
		if ($this->data == false)
			$this->data = array("id" => 0);
	}

	public function	get_players($id_party)
	{
		global	$db;

		if ($id_party == 0)
			return false;
		$sql = "SELECT c.*, u.login AS name
			FROM characters c
				LEFT JOIN users u
					ON c.id_user = u.id
			WHERE id_party = " . (int)$id_party;
		$players = array();
		$result = $db->query($sql);
		while ($row = $db->fetch_assoc($result))
		{
			$players[] = $row;
		}
		return $players;
	}

	public function	add_player($id_party, $id_user)
	{
		global	$db;

		$id_user = (int)$id_user;
		$id_party = (int)$id_party;
		$players = $this->get_players($id_party);
		if (!$this->get_party_from_user($id_user) AND count($players) <= LIMIT_PLAYERS)
		{
			$max_per_team = ceil(LIMIT_PLAYERS / 2);
			$teams = array(
				array_fill(0, $max_per_team, 0),
				array_fill(0, $max_per_team, 0)
			);
			$i = 0;
			$count_team = array(0, 0);
			while (isset($players[$i]))
			{
				$teams[$players[$i]['team']][$players[$i]['position']] = $players[$i];
				$count_team[$players[$i]['team']]++;
				$i++;
			}
			$team = ($count_team[0] == $max_per_team) ? 1 : 0;
			$i = 0;
			while ($i < $max_per_team)
			{
				if ($teams[$team][$i] == 0)
					break;
				$i++;
			}
			$sql = "INSERT INTO characters (id_user, id_party, team, position) VALUES
				(" . $id_user . ", " . $id_party . ", " . $team . ", " . $i . ")";
			$db->query($sql);
			return $db->lastid;
		}
		return false;
	}

	public function	leave_party($id_user)
	{
		global	$db;

		$id_user = (int)$id_user;
		$party = $this->get_party_from_user($id_user);
		if ($party === false)
			return false;
		if ($party['id_creator'] == $id_user)
			return $this->del_party($party['id']);
		else if ($party['time_gamestart'] == 0)
		{
			$sql = "DELETE FROM characters
				WHERE id_user = " . (int)$id_user . "
					AND id_party = " . $party['id'];
			return $db->query($sql);
		}
		return false;
	}

	public function	get_parties($count_players = 0)
	{
		global $db;
		
		if ($count_players == 1)
			$sql = "SELECT count(c.id) as count_players, p.*
				FROM parties p
					LEFT JOIN characters c
						ON c.id_party = p.id
							AND c.position != -1
				GROUP BY p.id";
		else
			$sql = "SELECT *
				FROM parties";
		$result = $db->query($sql);
		$parties = array();
		while ($row = $db->fetch_assoc($result))
		{
			$parties[] = $row;
		}
		return $parties;
	}

	public function	get_party_from_title($title)
	{
		global	$db;

		$sql = "SELECT *
			FROM parties
			WHERE title = '" . $db->escape($title) . "'";
		$result = $db->query($sql);
		if ($db->num_rows($result))
			return $db->fetch_assoc($result);
		return false;
	}

	public function	get_party_from_user($id_user)
	{
		global $db;

		$sql = "SELECT p.*
			FROM parties p
				LEFT JOIN characters c
					ON c.id_party = p.id
			WHERE p.time_gameend = 0
				AND c.id_user = " . (int)$id_user;
		$result = $db->query($sql);
		if ($db->num_rows($result))
			return $db->fetch_assoc($result);
		return false;
	}

	public function	add_party($title, $id_user)
	{
		global $db;

		$time_gamecreate = time();
		$id_user = (int)$id_user;
		if ($this->get_party_from_title($title) OR $this->get_party_from_user($id_user))
			return false;
		$sql = "INSERT INTO parties (title, id_creator, time_gamecreate) VALUES
			('" . $db->escape($title) . "', " . $id_user . ", " . $time_gamecreate . ")";
		if ($db->query($sql))
		{
			$this->data = array(
				'id' => $db->lastid,
				'title' => $title,
				'id_creator' => $id_user,
				'time_gamecreate' => $time_gamecreate,
				'time_gamestart' => 0,
				'time_gameend' => 0
			);
			$id_party = $db->lastid;
			$id_character = $this->add_player($id_party, $id_user);
			if ($id_party != 0 AND $id_character != 0)
				return array($id_party, $id_character);
			else
				$this->del_party($title);
		}
		return false;
	}

	public function	start_party($id_party)
	{
		global $db;

		$id_party = (int)$id_party;
		$sql = "SELECT time_gamestart
			FROM parties
			WHERE id = ". $id_party;
		$result = $db->query($sql);
		if (!$db->num_rows($result))
			return false;
		$row = $db->fetch_assoc($result);
		if ($row['time_gamestart'] != 0)
			return false;
		$players = $this->get_players($id_party);
		$i = 0;
		$count_team = array(0, 0);
		while (isset($players[$i]))
		{
			$count_team[$players[$i]['team']]++;
			$i++;
		}
		if ($count_team[0] >= 1
		    AND $count_team[1] >= 1
		    AND count($players) <= LIMIT_PLAYERS
		    AND count($players) >= 2)
		{
			// Add Hearts
			$sql = "SELECT id, hp
				FROM character_models
				WHERE type = " . CHAR_TYPE_HEART . "
				LIMIT 0, 2";
			$result = $db->query($sql);
			if ($row1 = $db->fetch_assoc($result))
			{
				$row2 = $db->fetch_assoc($result);
			}
			else
				return false;
			$sql = "INSERT INTO characters (x, y, id_cmodel, id_party, team, position, hp) VALUES
				(4, 4, " . $row1['id'] . ", " . $id_party . ", 0, -1, " . $row1['hp'] . "),
				(" . (CFG_MAP_SIZE - 5) . ", " . (CFG_MAP_SIZE - 5) . ", " . $row2['id'] . ", " . $id_party . ", 1, -1, " . $row2['hp'] . ")";
			$db->query($sql);

			// Add shop building and respawn building
			$sql = "SELECT id
				FROM building_models
				WHERE type = " . BLDG_TYPE_SHOP;
			$result = $db->query($sql);
			if (!($row_shop = $db->fetch_assoc($result)))
				return false;
			$sql = "SELECT id
				FROM building_models
				WHERE type = " . BLDG_TYPE_RESPAWN;
			$result = $db->query($sql);
			if (!($row_respawn = $db->fetch_assoc($result)))
				return false;

			$sql = "INSERT INTO buildings (x, y, id_bmodel, id_party) VALUES
				(2, 2, " . $row_shop['id'] . ", " . $id_party . "),
				(" . (CFG_MAP_SIZE - 3) . ", " . (CFG_MAP_SIZE - 3) . ", " . $row_shop['id'] . ", " . $id_party . "),
				(1, 1, " . $row_respawn['id'] . ", " . $id_party . "),
				(" . (CFG_MAP_SIZE - 2) . ", " . (CFG_MAP_SIZE - 2) . ", " . $row_respawn['id'] . ", " . $id_party . ")";
			$db->query($sql);

			$sql = "UPDATE characters
				SET x = 1, y = 1
				WHERE id_party = " . $id_party . "
					AND team = 0";
			$db->query($sql);
			$sql = "UPDATE characters
				SET x = " . (CFG_MAP_SIZE - 2) . ", y = " . (CFG_MAP_SIZE - 2) . "
				WHERE id_party = " . $id_party . "
					AND team = 1";
			$db->query($sql);
			$sql = "UPDATE parties
				SET time_gamestart = " . (microtime(true) * 1000 + CFG_GAME_TIMEPREPARE * 1000) . "
				WHERE id = " . $id_party;
			return $db->query($sql);
		}
		return false;
	}
	
	public function	is_started($id_party)
	{
		global $db;
		
		$id_party = (int)$id_party;
		if ($id_party == 0)
			return 0;
		$sql = "SELECT time_gamestart
			FROM parties
			WHERE id = " . $id_party;
		$result = $db->query($sql);
		if (!$db->num_rows($result))
			return 0;
		$row = $db->fetch_assoc($result);
		if ($row['time_gamestart'] != 0)
			return $row['time_gamestart'];
		return 0;
	}

	public function	del_party($id_party)
	{
		global	$db;

		$id_party = (int)$id_party;
		$sql = "DELETE FROM characters
			WHERE id_party = " . $id_party;
		if ($db->query($sql))
		{
			$sql = "DELETE FROM parties
				WHERE id = " . $id_party;
			return $db->query($sql);
		}
		return false;
	}

	public function	swap_position($id_party, $id_user, $team, $position)
	{
		global $db;
		
		$id_party = (int)$id_party;
		$team = (int)$team;
		$position = (int)$position;
		$sql = "SELECT id
			FROM characters
			WHERE id_party = " . $id_party . "
				AND team = " . $team . "
				AND position = " . $position;
		$result = $db->query($sql);
		if (!$db->num_rows($result))
		{
			$sql = "UPDATE characters
				SET team = " . $team . ", position = " . $position . "
				WHERE id_party = " . $id_party . "
					AND id_user = " . (int)$id_user;
			return $db->query($sql);
		}
		return false;
	}
	
	function	get_id()
	{
		return $this->data['id'];
	}

	function	get_title()
	{
		return isset($this->data['title']) ? $this->data['title'] : '';
	}

	function	get_id_creator()
	{
		return isset($this->data['id_creator']) ? $this->data['id_creator'] : 0;
	}
}
?>