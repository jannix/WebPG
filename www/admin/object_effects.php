<?php
require_once("../common.php");
?>
<a href="character_object_models.php">Retour Object</a> - 
<a href="object_effects.php?object=<?php echo (int)$_GET['object']?>&create">Create Effect</a>
<?php
if (isset($_GET['create']))
{
	if (isset($_POST['submit']))
	{
		$field = $_POST['field'];
		$value = $_POST['value'];
		$type = $_POST['type'];
		$id_omodel = $_GET['object'];
		
		$sql = "INSERT INTO character_object_model_effects(id_omodel, field, value, type) VALUES
			('" . (int)$id_omodel . "', '" . $db->escape($field) . "', '" . (int)$value . "',
			'" . $db->escape($type) . "')";
		if ($db->query($sql))
			echo "On gere quand meme";
		else
			echo "Ca ne marche pas, as tu vu?";
	}
?>
<h1>CREATE EFFECT</h1>
<form method = "post" action = "object_effects.php?object=<?php echo
(int)$_GET['object']; ?>&create">
	<table border = 0>
		<tr>
			<td>Field</td>
			<td><input type="text" name="field" /></td>
		</tr>
		<tr>
			<td>Value</td>
			<td><input type="text" name="value" /></td>
		</tr>
		<tr>
			<td>Type</td>
			<td><input type="text" name="type" /></td>
		</tr>
		<tr>
			<td colspan = "2">
				<input type="submit" name="submit" value="Create" />
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
		$sql ="UPDATE character_object_model_effects
			SET field = '" . $db->escape($_POST['field']) . "',
				value = '" . (int)$_POST['value'] . "',
				type = '" . $db->escape($_POST['type']) . "'
			WHERE id = '" . (int)$_GET['update'] . "'";
		if ($db->query($sql))
			echo "Update succeeded";
	}
	$sql = "SELECT *
		FROM character_object_model_effects
		WHERE id = " . (int)$_GET['update'];
	$result = $db->query($sql);
	if (!$db->num_rows($result))
	{
		echo "<a>Unknowm item</a>";
	}
	else
	{
		$row = $db->fetch_assoc($result);
?>
<h1>Update Effect</h1>
<form method = "post" action = "object_effects.php?object=<?php echo (int)$_GET['object']?>&update=<?php echo
(int)$_GET['update']; ?>">
	<table border="0">
		<tr>
			<td>Field</td>
			<td><input type="text" name="field" value="<?php echo
			$row['field'] ; ?>" /></td>
		</tr>
		<tr>
			<td>Value</td>
			<td><input type="text" name="value" value="<?php echo
			$row['value'] ; ?>" /></td>
		</tr>
		<tr>
			<td>Type</td>
			<td><input type="text" name="type" value="<?php echo $row['type'] ; ?>" /></td>
		</tr>
		<tr>
			<td colspan = "2">
				<input type="submit" name="submit" value="Update" />
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
		$id = $_GET['delete'];
	
		$sql = "DELETE FROM character_object_model_effects
			WHERE id = " . (int)$id;
		if ($db->query($sql))
			echo "YEAH BABY! Success!!";
		else
			echo "Ca ne marche pas, as tu vu?";		
	}

	$sql = "SELECT *
		FROM character_object_model_effects
		WHERE id_omodel = '" . (int)$_GET['object'] . "'";

	$result = $db->query($sql);
	echo "<table><tr>
		<th>ID object model</th>
		<th>Field</th>
		<th>Value</th>
		<th>Type</th>
	</tr>";
	while ($row = $db->fetch_array($result))
	{
		echo "<tr><td>" . $row['id_omodel'] . "</td>";
		echo "<td>" . $row['field'] . "</td>";
		echo "<td>" . $row['value'] . "</td>";
		echo "<td>" . $row['type'] . "</td>";
		echo "<td><a href='object_effects.php?object=" . (int)$_GET['object'] . "delete=" . $row['id'] . "'>X</a></td>";
	}
	echo "</table>";
}
?>
