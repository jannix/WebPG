<?php
require_once("../common.php");
?>
<html>
		<head>
			<style>
				img
				{
					max-width : 50px;
					max-height : 50px;
				}
			</style>
		</head>
	<body>
<a href="images.php">List</a> -
<a href="images.php?create">Create</a>
<?php
if (isset($_GET['create']))
{
	if (isset($_POST['submit']))
	{
		$imagename = $_POST['imagename'];
		$sql = "INSERT INTO images(imagename) VALUES
			('" . $db->escape($imagename) . "')";
		if ($db->query($sql))
			echo "Insert succeeded.";
		else
			echo "Insert failed.";
	}
?>
<h1>Create Image</h1>
<form method = "post" action = "images.php?create">
	<table border = 0>
		<tr>
			<td>Image Name</td>
			<td><input type="text" name="imagename" /></td>
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
		$sql ="UPDATE images
			SET imagename = '" . $db->escape($_POST['imagename']) . "'
			WHERE id = '" . (int)$_GET['update'] . "'";
		if ($db->query($sql))
			echo "Update succeeded";
	}
	$sql = "SELECT *
		FROM images
		WHERE id = " . (int)$_GET['update'];
	$result = $db->query($sql);
	if (!$db->num_rows($result))
	{
		echo "<a href='images.php'>Unknowm image</a>";
	}
	else
	{
		$row = $db->fetch_assoc($result);
?>
<h1>Update Image</h1>
<form method = "post" action = "images.php?update=<?php echo
(int)$_GET['update']; ?>">
	<table border="0">
		<tr>
			<td>Image Name</td>
			<td><input type="text" name="imagename" value="<?php echo
			$row['imagename'] ; ?>" /></td>
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
		$sql ="DELETE FROM images
			WHERE id = " . (int)$_GET['delete'];
		if ($db->query($sql))
			echo "Deletion succeeded";
	}
	$sql = "SELECT *
		FROM images";
	if ($result = $db->query($sql))
	{
		if (!$db->num_rows($result))
			echo "There is no image records.";
		echo "<table>
			<tr>
				<th>Image Name</th>
			</tr>";
		while ($row = $db->fetch_assoc($result))
		{
			echo "<tr>
				<td>
					<img src='../style/images/" . $row['imagename'] . "' alt='unknown' />
					" . $row['imagename'] . "
				</td>
				<td>
					<a href='images.php?delete=" . $row['id'] . "'>X</a>
				</td>
				<td>
					<a href='images.php?update=" . $row['id'] . "'>Update</a>
				</td>
			</tr>";
		}
		echo "</table>";
	}
}
?>
	</body>
</html>
