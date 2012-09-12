<?php
class	character
{
	protected	$chars = array();

	public function	get_character_from_user($id_user)
	{
		global $db;
		
		$sql = "SELECT c.*
			FROM characters c
				LEFT JOIN parties p
					ON p.id = c.id_party
						AND p.time_gameend = 0
			WHERE c.id_user = " . (int)$id_user;
		$result = $db->query($sql);
		return $db->fetch_assoc($result);
	}

	public function	get_objects_model($id_character)
	{
		global $db;
		
		$sql = "SELECT o.*, m.*
			FROM character_objects o
				LEFT JOIN character_object_models m
					ON m.id = o.id_omodel
			WHERE o.id_character = " . (int)$id_character;
		$result = $db->query($sql);
		if (!$db->num_rows($result))
			return false;
		$objects = array();
		while ($row = $db->fetch_assoc($result))
		{
			$objects[$row['position']] = $row;
		}
		return $objects;
	}

	public function	get_heroes_from_party($id_party, $sort = NO_SORT)
	{
		global $db;

		if (empty($this->chars))
		{
			$sql = "SELECT c.*,
				m.id AS m_id,
				m.id_image,
				m.name,
				m.description,
				m.hp AS m_hp,
				m.mp AS m_mp,
				m.atk_phy,
				m.atk_mag,
				m.def_phy,
				m.def_mag,
				m.luck,
				m.agility,
				m.hp_lvlup,
				m.mp_lvlup,
				m.atk_phy_lvlup,
				m.atk_mag_lvlup,
				m.def_phy_lvlup,
				m.def_mag_lvlup,
				m.luck_lvlup,
				m.agility_lvlup,
				i.imagename
				FROM characters c
					LEFT JOIN character_models m
						ON m.id = c.id_cmodel
					LEFT JOIN images i
						ON i.id = m.id_image
				WHERE c.id_party = " . (int)$id_party . "
					AND m.type = " . CHAR_TYPE_HERO;
			$result = $db->query($sql);
			if ($db->num_rows($result))
			{
				$this->chars = array();
				while ($row = $db->fetch_assoc($result))
				{
					$this->chars[] = $row;
				}
			}
		}
		$chars = array();
		if ($sort == NO_SORT)
			return $this->chars;
		else if ($sort == SORT_BY_TEAM)
		{
			$i = 0;
			while (isset($this->chars[$i]))
			{
				$chars[$this->chars[$i]['team']][$this->chars[$i]['position']] = $this->chars[$i];
				$i++;
			}
			return $chars;
		}
		else if ($sort == SORT_BY_POS)
		{
			$i = 0;
			while (isset($this->chars[$i]))
			{
				$chars[$this->chars[$i]['x']][$this->chars[$i]['y']] = $this->chars[$i];
				$i++;
			}
			return $chars;
		}
		return false;
	}
	
	public function	get_character_model($id_character_model)
	{
		global $db;

		$sql = "SELECT m.*, i.imagename
			FROM character_models m
				LEFT JOIN images i
					ON i.id = m.id_image
			WHERE m.id = " . (int)$id_character_model;
		$result = $db->query($sql);
		if ($db->num_rows($result))
			return $row = $db->fetch_assoc($result);
		return false;
	}
	
	public function	get_hero_models()
	{
		global $db;

		$sql = "SELECT m.*, i.imagename
			FROM character_models m
				LEFT JOIN images i
					ON i.id = m.id_image
			WHERE m.type = " . CHAR_TYPE_HERO;
		$result = $db->query($sql);
		$chars = array();
		while ($row = $db->fetch_assoc($result))
		{
			$chars[] = $row;
		}
		return $chars;
	}
	
	public function	select_char($id_character, $id_character_model)
	{
		global $db;

		$id_character = (int)$id_character;
		$id_character_model = (int)$id_character_model;
		$sql = "SELECT c.id_cmodel, c.id_party, cm.hp, cm.hp_lvlup, cm.mp, cm.mp_lvlup
			FROM characters c
				LEFT JOIN parties p
					ON p.id = c.id_party
						AND p.time_gameend = 0
						AND p.time_gamestart >= " . time() . "
				LEFT JOIN character_models cm
					ON cm.id = " . $id_character_model . "
			WHERE c.id = " . $id_character . "
				AND c.id_cmodel = 0";
		$result = $db->query($sql);
		if ($db->num_rows($result))
		{
			$row = $db->fetch_assoc($result);
			$sql = "UPDATE characters
				SET id_cmodel = " . $id_character_model . ",
					hp = " . ($row['hp'] + $row['hp_lvlup']) . ",
					mp = " . ($row['mp'] + $row['mp_lvlup']) . "
				WHERE id = " . $id_character;
			return $db->query($sql);
		}
		return false;
	}

	public function	get_character_objects($id_character)
	{
		global $db;
		
		$sql = "SELECT *
			FROM character_objects
			WHERE id_character = " . (int)$id_character;
		$result = $db->query($sql);
		$objects = array();
		while ($row = $db->fetch_assoc($result))
		{
			$objects[] = $row;
		}
		return $objects;
	}
}
?>