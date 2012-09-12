<?php
require_once("../common.php");
?>
<a href="character_object_models.php">List Object</a> -
<a href="character_object_models.php?create">Create Object</a>
<?php
if (isset($_GET['create']))
{
	if (isset($_POST['submit']))
	{
		$name = $_POST['name'];
		$description = $_POST['description'];
		$duration = $_POST['duration'];

		$sql = "INSERT INTO character_object_models(name, description, duration) VALUES
			('" . $db->escape($name) . "', '" . $db->escape($description) . "', " . (int)$duration . ")";
		if ($db->query($sql))
			echo "Insert succeeded.";
		else
			echo "Insert failed.";
	}
?>
<h1>Create Object</h1>
<form method = "post" action = "character_object_models.php?create">
	<table border = 0>
		<tr>
			<td>Name</td>
			<td><input type="text" name="name" /></td>
		</tr>
				<tr>
			<td>Description</td>
			<td><input type="text" name="description" /></td>
		</tr>
		<tr>
			<td>Duration</td>
			<td><input type="text" name="duration" /></td>
		</tr>
		<tr>
			<td colspan = "2">
				<input type="submit" name="submit" value="create" />
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
		$sql ="UPDATE character_object_models
			SET name = '" . $db->escape($_POST['name']) . "',
				description = '" . $db->escape($_POST['description']) . "',
				duration = '" . (int)$_POST['duration'] . "'
			WHERE id = '" . (int)$_GET['update'] . "'";
		if ($db->query($sql))
			echo "Update succeeded";
	}
	$sql = "SELECT *
		FROM character_object_models
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
<h1>Update Object</h1>
<form method = "post" action = "character_object_models.php?update=<?php echo
(int)$_GET['update']; ?>">
	<table border="0">
		<tr>
			<td>Name</td>
			<td><input type="text" name="name" value="<?php echo
			$row['name'] ; ?>" /></td>
		</tr>
		<tr>
			<td>Description</td>
			<td><input type="text" name="description" value="<?php echo
			$row['description'] ; ?>" /></td>
		</tr>
		<tr>
			<td>Duration</td>
			<td><input type="text" name="duration" value="<?php echo $row['duration'] ; ?>" /></td>
		</tr>
		<tr>
			<td colspan = "2">
				<input type="submit" name="submit" value="update" />
				<hr />
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
		$sql ="DELETE FROM character_object_models
			WHERE id = " . (int)$_GET['delete'];
		if ($db->query($sql))
			echo "Deletion succeeded";
	}
	$sql = "SELECT *
		FROM character_object_models";
	if ($result = $db->query($sql))
	{
		if (!$db->num_rows($result))
			echo "There is no object records.";
		echo "<table>
			<tr>
				<th>Name</th>
				<th>Description</th>
				<th>Duration</th>
			</tr>";
		while ($row = $db->fetch_array($result))
		{
			echo "<tr>
				<td>
					" . $row['name'] . "
				</td>
				<td>
					" . $row['description'] . "
				</td>
				<td>
					" . $row['duration'] . "
				</td>
				<td>
					<a href='character_object_models.php?delete="
					. $row['id'] . "'>X</a>
				</td>
				<td>
					<a href='character_object_models.php?update="
					. $row['id'] . "'>Update</a>
				</td>
				<td>
					<a href='object_effects.php?object="
					. $row['id'] . "'>Object effect</a>
				</td>

			</tr>";
		}
		echo "</table>";
	}
}
?>
