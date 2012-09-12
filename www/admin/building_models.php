<?php
require_once("../common.php");

$type = array(
	BLDG_TYPE_RESPAWN => "Respawn",
	BLDG_TYPE_SHOP => "Shop"
);
?>
<a href="building_models.php">List</a> -
<a href="building_models.php?create">Create</a>
<?php
if (isset($_GET['create']))
{
	if (isset($_POST['submit']))
	{
		$name = $_POST['name'];
		$id_image = $_POST['id_image'];
		$description = $_POST['description'];

		$sql = "INSERT INTO building_models(name, id_image, description, type) VALUES
			('" . $db->escape($name) . "', " . (int)$id_image . ", '" . $db->escape($description) . "', " . (int)$_POST['type'] . ")";
		if ($db->query($sql))
			echo "Building model inserted.";
		else
			echo "Error insertion.";
	}
?>
<h1>Create building model</h1>
<form method="post" action="building_models.php?create">
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
		$sql ="UPDATE building_models
			SET name = '" . $db->escape($_POST['name']) . "',
				id_image = " . (int)$_POST['id_image'] . ",
				description = '" . $db->escape($_POST['description']) . "',
				type = " . (int)$_POST['type'] . "
			WHERE id = '" . (int)$_GET['update'] . "'";
		if ($db->query($sql))
			echo "Update succeeded";
		else
			echo "Update failed";
	}
	$sql = "SELECT *
		FROM building_models
		WHERE id = " . (int)$_GET['update'];
	$result = $db->query($sql);
	if (!$db->num_rows($result))
	{
		echo "<a href='building_object_models.php'>Unknowm item</a>";
	}
	else
	{
		$row = $db->fetch_array($result);
?>
<h1>Update building model</h1>
<form method="post" action="building_models.php?update=<?php echo (int)$_GET['update']?>">
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

		$sql = "DELETE FROM building_models
			WHERE id = " . (int)$id;
		if ($db->query($sql))
			echo "Building deleted";
		else
			echo "Delete failed";
	}

	$sql = "SELECT *
		FROM building_models";
	$result = $db->query($sql);
	echo "<table style='text-align:center;'><tr>
		<th>Name</th>
		<th>ID image</th>
		<th>Type</th>
	</tr>";
	while ($row = $db->fetch_array($result))
	{
		echo "<tr><td>" . $row['name'] . "</td>";
		echo "<td>" . $row['id_image'] . "</td>";
		echo "<td>" . $type[$row['type']] . "</td><td><a href='building_models.php?delete=" . $row['id'] . "'>X</a> - <a href='building_models.php?update=" . $row['id'] . "'>Upd</a></td></tr>";
		echo "<tr><td colspan='4'>" . $row['description'] . "</td><br />";
	}
	echo "</table>";
}
?>