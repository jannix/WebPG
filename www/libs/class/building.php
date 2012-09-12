<?php
class	building
{
	protected	$buildings  = array();

	public function	get_buildings_from_party($id_party, $sort = NO_SORT)
	{
		global $db;

		if (empty($this->chars))
		{
			$sql = "SELECT b.*, m.id AS m_id, m.name, m.id_image, m.description, m.type, i.imagename
				FROM buildings b
					LEFT JOIN building_models m
						ON m.id = b.id_bmodel
					LEFT JOIN images i
						ON i.id = m.id_image
				WHERE b.id_party = " . (int)$id_party;
			$result = $db->query($sql);
			if ($db->num_rows($result))
			{
				$this->buildings = array();
				while ($row = $db->fetch_assoc($result))
				{
					$this->buildings[] = $row;
				}
			}
		}
		if ($sort == NO_SORT)
			return $this->buildings;
		else if ($sort == SORT_BY_POS)
		{
			$buildings = array();
			$i = 0;
			while (isset($this->buildings[$i]))
			{
				$buildings[$this->buildings[$i]['x']][$this->buildings[$i]['y']] = $this->buildings[$i];
				$i++;
			}
			return $buildings;
		}
		return false;
	}
	
	public function	get_building_model($id_building_model)
	{
		global $db;

		$sql = "SELECT m.*, i.imagename
			FROM building_models m
				LEFT JOIN images i
					ON i.id = m.id_image
			WHERE m.id = " . (int)$id_building_model;
		$result = $db->query($sql);
		if ($db->num_rows($result))
			return $row = $db->fetch_assoc($result);
		return false;
	}
	
	public function	get_building_models()
	{
		global $db;

		$sql = "SELECT m.*, i.imagename
			FROM building_models m
				LEFT JOIN images i
					ON i.id = m.id_image";
		$result = $db->query($sql);
		$chars = array();
		while ($row = $db->fetch_assoc($result))
		{
			$chars[] = $row;
		}
		return $chars;
	}

	public function	get_building_model_objects($id_building_model)
	{
		global $db;

		$sql = "SELECT m.*, o.id AS o_id, o.name, o.description, o.duration
			FROM building_model_objects m
				LEFT JOIN character_object_models o
					ON o.id = m.id_omodel
			WHERE m.id_bmodel = " . $id_building_model;
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