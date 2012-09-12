<?php
require_once("../common.php");

$type = array(
	CHAR_TYPE_HERO => "Hero",
	CHAR_TYPE_TOWER_DEFENSE => "Tower Defense",
	CHAR_TYPE_HEART => "Heart"
);
?>
<a href="character_models.php">List</a> - 
<a href="character_models.php?create">Create</a>
<?php
if (isset($_GET['create']))
{
	if (isset($_POST['submit']))
	{
		$name = $_POST['name'];
		$id_image = $_POST['id_image'];
		$description = $_POST['description'];
		$hp = $_POST['hp'];
		$mp = $_POST['mp'];
		$atk_phy = $_POST['atk_phy'];
		$atk_mag = $_POST['atk_mag'];
		$def_phy = $_POST['def_phy'];
		$def_mag = $_POST['def_mag'];
		$luck = $_POST['luck'];
		$agility = $_POST['agility'];
		$hp_lvlup = $_POST['hp_lvlup'];
		$mp_lvlup = $_POST['mp_lvlup'];
		$atk_phy_lvlup = $_POST['atk_phy_lvlup'];
		$atk_mag_lvlup = $_POST['atk_mag_lvlup'];
		$def_phy_lvlup = $_POST['def_phy_lvlup'];
		$def_mag_lvlup = $_POST['def_mag_lvlup'];
		$luck_lvlup = $_POST['luck_lvlup'];
		$agility_lvlup = $_POST['agility_lvlup'];
		
		$sql = "INSERT INTO character_models(name, id_image, description, type,
						     hp, mp,
						     atk_phy, atk_mag, def_phy, def_mag,
						     luck, agility,
						     hp_lvlup, mp_lvlup,
						     atk_phy_lvlup, atk_mag_lvlup,
						     def_phy_lvlup, def_mag_lvlup,
						     luck_lvlup, agility_lvlup) VALUES
			('" . $db->escape($name) . "', " . (int)$id_image . ", '" . $db->escape($description) . "', " . (int)$_POST['type'] . ",
			'" . (int)$hp . "','" . (int)$mp . "',
			'" . (int)$atk_phy . "', '" . (int)$atk_mag . "', '" . (int)$def_phy . "', '" . (int)$def_mag . "',
			'" . (int)$luck . "', '" . (int)$agility . "',
			'" . (int)$hp_lvlup . "', '" . (int)$mp_lvlup . "',
			'" . (int)$atk_phy_lvlup . "', '" . (int)$atk_mag_lvlup . "',
			'" . (int)$def_phy_lvlup . "', '" . (int)$def_mag_lvlup . "',
			'" . (int)$luck_lvlup . "', '" . (int)$agility_lvlup . "')";
		if ($db->query($sql))
			echo "Character model inserted.";
		else
			echo "Ca ne marche pas, as tu vu?";
	}
?>
<h1>Create character model</h1>
<form method="post" action="character_models.php?create">
	<table border="0">
		<tr>
			<td>Name</td>
			<td><input type="text" name="name" /></td>
		</tr>
		<tr>
			<td>Id Image</td>
			<td><input type="text" name="id_image" /></td>
		</tr>
		<tr>
			<td>Description</td>
			<td><input type="text" maxlength="255" name="description" /></td>
		</tr>
			<td>Type</td>
			<td>
				<select name="type">
					<?php
					foreach ($type as $key => $value)
					{
						echo '<option value="' . $key . '">' . $value . '</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Hp</td>
			<td><input type="text" name="hp" /></td>
		</tr>
		<tr>
			<td>Mp</td>
			<td><input type="text" name="mp" /></td>
		</tr>
		<tr>
			<td>Atk_phy</td>
			<td><input type="text" name="atk_phy" /></td>
		</tr>
		<tr>
			<td>Atk_mag</td>
			<td><input type="text" name="atk_mag" /></td>
		</tr>
		<tr>
			<td>Def_phy</td>
			<td><input type="text" name="def_phy" /></td>
		</tr>
		<tr>
			<td>Def_mag</td>
			<td><input type="text" name="def_mag" /></td>
		</tr>
		<tr>
			<td>Luck</td>
			<td><input type="text" name="luck" /></td>
		</tr>
		<tr>
			<td>Agility</td>
			<td><input type="text" name="agility" /></td>
		</tr>
		<tr>
			<td>Hp_lvlup</td>
			<td><input type="text" name="hp_lvlup" /></td>
		</tr>
		<tr>
			<td>Pm_lvlup</td>
			<td><input type="text" name="mp_lvlup" /></td>
		</tr>
		<tr>
			<td>Atk_phy_lvlup</td>
			<td><input type="text" name="atk_phy_lvlup" /></td>
		</tr>
		<tr>
			<td>Atk_mag_lvlup</td>
			<td><input type="text" name="atk_mag_lvlup" /></td>
		</tr>
		<tr>
			<td>Def_phy_lvlup</td>
			<td><input type="text" name="def_phy_lvlup" /></td>
		</tr>
		<tr>
			<td>Def_mag_lvlup</td>
			<td><input type="text" name="def_mag_lvlup" /></td>
		</tr>
		<tr>
			<td>Luck_lvlup</td>
			<td><input type="text" name="luck_lvlup" /></td>
		</tr>
		<tr>
			<td>Agility_lvlup</td>
			<td><input type="text" name="agility_lvlup" /></td>
		</tr>
		<tr>
			<td colspan = "2">
				<input type="submit" name="submit" value="Creation!" />
				<hr />
			</td>
		</tr>
	</table>
</form>
<?php
}
else if (isset($_GET['update']))
{
	if (isset($_POST['submit']))
	{
		$sql ="UPDATE character_models
			SET name = '" . $db->escape($_POST['name']) . "',
				id_image = " . (int)$_POST['id_image'] . ",
				description = '" . $db->escape($_POST['description']) . "',
				type = " . (int)$_POST['type'] . ",
				atk_phy = " . (int)$_POST['atk_phy'] . ", atk_mag = " . (int)$_POST['atk_mag'] . ",
				hp = " . (int)$_POST['hp'] . ", mp = " . (int)$_POST['mp'] . ",
				def_phy = " . (int)$_POST['def_phy'] . ", def_mag = " . (int)$_POST['def_mag'] . ",
				luck = " . (int)$_POST['luck'] . ", agility = " . (int)$_POST['agility'] . ",
				hp_lvlup = " . (int)$_POST['hp_lvlup'] . ", mp_lvlup = " . (int)$_POST['mp_lvlup'] . ",
				atk_phy_lvlup = " . (int)$_POST['atk_phy_lvlup'] . ", atk_mag_lvlup = " . (int)$_POST['atk_mag_lvlup'] . ",
				def_phy_lvlup = " . (int)$_POST['def_phy_lvlup'] . ", def_mag_lvlup = " . (int)$_POST['def_mag_lvlup'] . ",
				luck_lvlup = " . (int)$_POST['luck_lvlup'] . ", agility_lvlup = " . (int)$_POST['agility_lvlup'] . "
			WHERE id = '" . (int)$_GET['update'] . "'";
		if ($db->query($sql))
			echo "Update succeeded";
		else
			echo "Update failed";
	}
	$sql = "SELECT *
		FROM character_models
		WHERE id = " . (int)$_GET['update'];
	$result = $db->query($sql);
	if (!$db->num_rows($result))
	{
		echo "<a href='character_object_models.php'>Unknowm item</a>";
	}
	else
	{
		$row = $db->fetch_array($result);
?>
<h1>Update character model</h1>
<form method="post" action="character_models.php?update=<?php echo (int)$_GET['update']?>">
	<table border="0">
		<tr>
			<td>Name</td>
			<td><input type="text" name="name" value="<?php echo $row['name'];?>" /></td>
		</tr>
		<tr>
			<td>Id Image</td>
			<td><input type="text" name="id_image" value="<?php echo $row['id_image'];?>" /></td>
		</tr>
		<tr>
			<td>Description</td>
			<td><input type="text" maxlength="255" name="description" value="<?php echo $row['description'];?>" /></td>
		</tr>
			<td>Type</td>
			<td>
				<select name="type">
					<?php
					foreach ($type as $key => $value)
					{
						echo '<option value="' . $key . '" ' . ($row['type'] == $key ? 'selected="selected"' : '') . '>' . $value . '</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>HP</td>
			<td><input type="text" name="hp" value="<?php echo $row['hp'];?>" /></td>
		</tr>
		<tr>
			<td>MP</td>
			<td><input type="text" name="mp" value="<?php echo $row['mp'];?>" /></td>
		</tr>
		<tr>
			<td>Atk_phy</td>
			<td><input type="text" name="atk_phy" value="<?php echo $row['atk_phy'];?>" /></td>
		</tr>
		<tr>
			<td>Atk_mag</td>
			<td><input type="text" name="atk_mag" value="<?php echo $row['atk_mag'];?>" /></td>
		</tr>
		<tr>
			<td>Def_phy</td>
			<td><input type="text" name="def_phy" value="<?php echo $row['def_phy'];?>" /></td>
		</tr>
		<tr>
			<td>Def_mag</td>
			<td><input type="text" name="def_mag" value="<?php echo $row['def_mag'];?>" /></td>
		</tr>
		<tr>
			<td>Luck</td>
			<td><input type="text" name="luck" value="<?php echo $row['luck'];?>" /></td>
		</tr>
		<tr>
			<td>Agility</td>
			<td><input type="text" name="agility" value="<?php echo $row['agility'];?>" /></td>
		</tr>
		<tr>
			<td>HP lvlup</td>
			<td><input type="text" name="hp_lvlup" value="<?php echo $row['hp_lvlup'];?>" /></td>
		</tr>
		<tr>
			<td>MP lvlup</td>
			<td><input type="text" name="mp_lvlup" value="<?php echo $row['mp_lvlup'];?>" /></td>
		</tr>
		<tr>
			<td>Atk_phy_lvlup</td>
			<td><input type="text" name="atk_phy_lvlup" value="<?php echo $row['atk_phy_lvlup'];?>" /></td>
		</tr>
		<tr>
			<td>Atk_mag_lvlup</td>
			<td><input type="text" name="atk_mag_lvlup" value="<?php echo $row['atk_mag_lvlup'];?>" /></td>
		</tr>
		<tr>
			<td>Def_phy_lvlup</td>
			<td><input type="text" name="def_phy_lvlup" value="<?php echo $row['def_phy_lvlup'];?>" /></td>
		</tr>
		<tr>
			<td>Def_mag_lvlup</td>
			<td><input type="text" name="def_mag_lvlup" value="<?php echo $row['def_mag_lvlup'];?>" /></td>
		</tr>
		<tr>
			<td>Luck_lvlup</td>
			<td><input type="text" name="luck_lvlup" value="<?php echo $row['luck_lvlup'];?>" /></td>
		</tr>
		<tr>
			<td>Agility_lvlup</td>
			<td><input type="text" name="agility_lvlup" value="<?php echo $row['agility_lvlup'];?>" /></td>
		</tr>
		<tr>
			<td colspan = "2">
				<input type="submit" name="submit" value="Update" />
			</td>
		</tr>
	</table>
</form>
<?php
	}
}
else
{
	if (isset($_GET['delete']))
	{
		$id = $_GET['delete'];
	
		$sql = "DELETE FROM character_models
			WHERE id = " . (int)$id;
		if ($db->query($sql))
			echo "YEAH BABY! Success!!";
		else
			echo "Ca ne marche pas, as tu vu?";		
	}

	$sql = "SELECT c.*, i.imagename
		FROM character_models c
			LEFT JOIN images i
				ON i.id = c.id_image";
	$result = $db->query($sql);
	echo "<table style='text-align:center;'><tr>
		<th>Name</th>
		<th>ID image</th>
		<th>HP</th>
		<th>MP</th>
		<th>Atk_phy</th>
		<th>Atk_mag</th>
		<th>Def_phy</th>
		<th>Def_mag</th>
		<th>Luck</th>
		<th>Agility</th>
	</tr>
	<tr>
		<th>Type</th>
		<th></th>
		<th>HP_lvlup</th>
		<th>MP_lvlup</th>
		<th>Atk_phy_lvlup</th>
		<th>Atk_mag_lvlup</th>
		<th>Def_phy_lvlup</th>
		<th>Def_mag_lvlup</th>
		<th>Luck_lvlup</th>
		<th>Agility_lvlup</th>
	</tr>";
	while ($row = $db->fetch_array($result))
	{
		echo "<tr><td>" . $row['name'] . "</td>";
		echo "<td>" . $row['id_image'] . "</td>";
		echo "<td>" . $row['hp'] . "</td>";
		echo "<td>" . $row['mp'] . "</td>";
		echo "<td>" . $row['atk_phy'] . "</td>";
		echo "<td>" . $row['atk_mag'] . "</td>";
		echo "<td>" . $row['def_phy'] . "</td>";
		echo "<td>" . $row['def_mag'] . "</td>";
		echo "<td>" . $row['luck'] . "</td>";
		echo "<td>" . $row['agility'] . "</td></tr>";
		echo "<tr><td>" . $type[$row['type']] . "</td>
			<td><img src='../style/images/" . $row['imagename'] . "' alt='' title='' /></td><td>" . $row['hp_lvlup'] . "</td>";
		echo "<td>" . $row['mp_lvlup'] . "</td>";
		echo "<td>" . $row['atk_phy_lvlup'] . "</td>";
		echo "<td>" . $row['atk_mag_lvlup'] . "</td>";
		echo "<td>" . $row['def_phy_lvlup'] . "</td>";
		echo "<td>" . $row['def_mag_lvlup'] . "</td>";
		echo "<td>" . $row['luck_lvlup'] . "</td>";
		echo "<td>" . $row['agility_lvlup'] . "</td></tr>";
		echo "<tr><td colspan='10'><a href='character_models.php?delete=" . $row['id'] . "'>X</a> - <a href='character_models.php?update=" . $row['id'] . "'>Upd</a> - " . $row['description'] . "</td><br />";
	}
	echo "</table>";
}
?>