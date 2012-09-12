<?php
require_once("../common.php");

if (isset($_POST['type'])
	AND isset($_POST['x'])
	AND isset($_POST['y'])
	AND isset($_POST['impassable']))
{
	if ($_POST['type'] == 'upd')
	{
		$sql = "UPDATE map_cases
			SET impassable = " . ($_POST['impassable'] == 'on' ? 1 : 0) . "
			WHERE x = " . (int)$_POST['x'] . "
				AND y = " . (int)$_POST['y'];
	}
	else
	{
		$sql = "DELETE FROM map_cases
			WHERE x = " . (int)$_POST['x'] . "
				AND y = " . (int)$_POST['y'];
	}
	echo $db->query($sql);
	exit();
}
else if (isset($_POST['position']) AND isset($_POST['image']))
{
	$position = explode(',', $_POST['position']);
	$sql = "SELECT x
		FROM map_cases
		WHERE x = " . (int)$position[0] . "
			AND y = " . (int)$position[1];
	$result = $db->query($sql);
	if ($db->num_rows($result))
	{
		$sql = "UPDATE map_cases
			SET id_image = " . (int)$_POST['image'] . "
			WHERE x = " . (int)$position[0] . "
				AND y = " . (int)$position[1];
	}
	else
	{
		$sql = "INSERT INTO map_cases (x, y, id_image) VALUES
			(" . (int)$position[0] . ",
			" . (int)$position[1] . ",
			" . (int)$_POST['image'] . ")";
	}
	echo $db->query($sql);
	exit();
}
?>
<html>
		<head>
			<style>
				* {
					margin: 0;
					padding: 0;
				}
				img {
					width: 20px;
					height: 20px;
				}
				.todrop {
					width: 20px;
					height: 20px;
					cursor: move;
					background-size: 100%;
				}
				.dropcell {
					width: 20px;
					height: 20px;
					cursor: move;
					background-size: 100%;
				}
				#images {
					position: fixed;
					top: 0;
					right: 0;
					overflow : auto;
				}
				
				table {
					border-collapse: collapse;
					border-spacing: 0;
				}
				tr {
					height: 20px;
				}
				td {
					border : 1px solid grey;
					width: 20px;
				}
			</style>
		</head>
	<body>
		<form method="post" action="map_cases.php">
			X : <input type="text" size="2" name="x" value="<?php echo isset($_POST['x']) ? $_POST['x'] : 0?>" /><br />
			Y : <input type="text" size="2" name="y" value="<?php echo isset($_POST['y']) ? $_POST['y'] : 0?>" /><br />
			<input type="submit" value="Let's go" />
		</form>
		<div id="cell_opt">
			<input type="hidden" size="2" id="x" />
			<input type="hidden" size="2" id="y" />
			Infranchissable: <input type="checkbox" id="impassable" /><br />
			<input type="submit" value="Update" onClick="cell_ajax('upd');" />
			<input type="submit" value="Delete" onClick="cell_ajax('del');" />
		</div>
		<?php
		if (isset($_POST['x']) AND isset($_POST['y']))
		{
			$x = (int)$_POST['x'];
			$y = (int)$_POST['y'];
			$distance = 10;
			
			$x_min = $x - $distance >= 0 ? $x - $distance : 0;
			$x_max = $x + $distance >= 1000 ? 1000 : $x + $distance;
			$y_min = $y - $distance >= 0 ? $y - $distance : 0;
			$y_max = $y + $distance >= 1000 ? 1000 : $y + $distance;

			$sql = "SELECT m.*, i.imagename AS image
				FROM map_cases m
					LEFT JOIN images i
						ON i.id = m.id_image
				WHERE m.x BETWEEN " . $x_min . " AND " . $x_max . "
					AND m.y BETWEEN " . $y_min . " AND " . $y_max;
			$result = $db->query($sql);
			$cases = array_fill($y_min, $y_max, array_fill($x_min, $x_max, array()));
			while ($row = $db->fetch_assoc($result))
			{
				$cases[$row['y']][$row['x']] = $row;
			}
			echo '<table id="map"><tr><th></th>';
			$x_pos = $x_min;
			while ($x_pos < $x_max)
			{
				echo '<th>' . $x_pos . '</th>';
				$x_pos++;
			}
			$y_pos = $y_max;
			echo '</tr>';
			while ($y_pos >= $y_min)
			{
				$x_pos = $x_min;
				echo '<tr><th>' . $y_pos . '</th>';
				while ($x_pos < $x_max)
				{
					if (isset($cases[$y_pos][$x_pos]['image']))
						echo '<td><div
							class="dropcell" title="' . $x_pos . ',' . $y_pos . '"
							style="background-image:url(../style/images/' . $cases[$y_pos][$x_pos]['image'] . ');"
							id="' . $cases[$y_pos][$x_pos]['id_image'] . '"
							onMouseUp="cell_opt(' . $x_pos . ',' . $y_pos . ', ' . $cases[$y_pos][$x_pos]['impassable'] . ');"
						>
						</div></td>';
					else
						echo '<td><div class="dropcell" title="' . $x_pos . ',' . $y_pos . '"></div></td>';
					$x_pos++;
				}
				echo '</tr>';
				$y_pos--;
			}
			echo '</table>';

			$sql = "SELECT *
				FROM images";
			if ($result = $db->query($sql))
			{
				if (!$db->num_rows($result))
					echo "There is no image records.";
				echo "<table id='images'>
					<tr>
						<th colspan='4'>Image Name</th>
					</tr>
					<tr>";
				$i = 0;
				while ($row = $db->fetch_array($result))
				{
					if ($i % 4 == 0)
						echo '</tr><tr>';
					echo "<td><div class='todrop' style='background-image:url(../style/images/" . $row['imagename'] . "' id='" . $row['id'] . "'></div></td>";
					$i++;
				}
				echo "</tr></table>";
			}
		}
		?>
	</body>
	<script type="text/javascript" src="../libs/js/mootools-core.js"></script>
	<script type="text/javascript" src="../libs/js/mootools-more.js"></script>
	<script type="text/javascript" src="../libs/js/MooDialog.js"></script>
	<script type="text/javascript" src="../libs/js/MooDialog.Alert.js"></script>
	<script type="text/javascript" src="../libs/js/MooDialog.Prompt.js"></script>
	<script type="text/javascript" src="../libs/js/MooDialog.Confirm.js"></script>
	<script type="text/javascript" src="../libs/js/MooDialog.Error.js"></script>
	<script type="text/javascript" src="../libs/js/MooDialog.Request.js"></script>
	<script type="text/javascript" src="../libs/js/Overlay.js"></script>
	<script type="text/javascript" src="../libs/js/MooDialog.Fx"></script>
	<script type="text/javascript">
	window.addEvent('domready', function(){
		$$('.todrop').addEvent('mousedown', function(event) {
			event.stop();

			var from = this;

			var clone = from.clone().setStyles(from.getCoordinates()).setStyles({
				opacity: 0.7,
				position: 'absolute'
			}).inject(document.body);

			var drag = new Drag.Move(clone, {

				droppables: $$('.dropcell'),

				onDrop: function(dragging, to) {
					dragging.destroy();
					if (to != null)
					{
						var request = new Request.JSON({
							url: 'map_cases.php',
							method: 'post',
							onComplete: function(responseJSON) {
								if (responseJSON == true)
									to.setStyle('background-image', from.getStyle('background-image'));
								else if (responseJSON == false)
									new MooDialog.Alert("Can't change the case.");
							},
							data: {
								image: from.get('id'),
								position: to.get('title')
							}
						}).send();
					}
				},
				onEnter: function(dragging, to){
					to.highlight('grey', 'red');
				},	
				onLeave: function(dragging, to){
					to.tween('background-color', 'white');
				},
				onCancel: function(dragging){
					dragging.destroy();
				}
			});
			drag.start(event);
		});
		$$('.dropcell').addEvent('mousedown', function(event) {
			event.stop();

			var from = this;

			var clone = from.clone().setStyles(from.getCoordinates()).setStyles({
				opacity: 0.7,
				position: 'absolute'
			}).inject(document.body);

			var drag = new Drag.Move(clone, {

				droppables: $$('#map div'),

				onDrop: function(dragging, to) {
					dragging.destroy();
					if (to != null)
					{
						var request = new Request.JSON({
							url: 'map_cases.php',
							method: 'post',
							onComplete: function(responseJSON) {
								if (responseJSON == true)
								{
									to.setStyle('background-image', from.getStyle('background-image'));
									to.set('id', from.get('id'));
								}
								else if (responseJSON == false)
									new MooDialog.Alert("Can't change the case.");
							},
							data: {
								image: from.get('id'),
								position: to.get('title')
							}
						}).send();
					}
				},
				onEnter: function(dragging, to){
					to.highlight('grey', 'red');
				},	
				onLeave: function(dragging, to){
					to.tween('background-color', 'white');
				},
				onCancel: function(dragging){
					dragging.destroy();
				}
			});
			drag.start(event);
		});
		$('cell_opt').fade('hide');
	});
	function	cell_opt(x, y, impassable)
	{
		$('x').set('value', x);
		$('y').set('value', y);
		if (impassable == 1)
			$('impassable').set('checked', true);
		else
			$('impassable').set('checked', false);
		$('cell_opt').fade('show');
	}
	function	cell_ajax(type)
	{
		var request = new Request.JSON({
			url: 'map_cases.php',
			method: 'post',
			onComplete: function(responseJSON) {
				if (responseJSON == true)
					$('cell_opt').fade('hide');
				else if (responseJSON == false)
					new MooDialog.Alert("Can't change the case.");
			},
			data: {
				type: type,
				x: $('x').get('value'),
				y: $('y').get('value'),
				impassable: $('impassable').get('value')
			}
		}).send();
	}
	</script>
</html>
