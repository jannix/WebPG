<?php
function espaceur($my_num) {
	return number_format($my_num,0,","," ");
}

function calcul_rate_exp($max)
{
	$level = 1;
	$exp_total = 0;
	$step_require = 150;
	$add_step = 70;
	$evolution = 1.102792;

	echo '<table style="text-align:center;"><tr>
	<th style="width:100px;">Level</th>
	<th style="width:100px;">Exp</th>
	<th style="width:100px;">Requis</th>
	<th style="width:100px;">Palier</th></tr>';

	while ($level <= $max)
	{
		$add_step = round($add_step * $evolution, 5);
		
		echo "<tr>
			<td>" . espaceur($level) . "</td>
			<td>" . espaceur($exp_total) . "</td>
			<td>" . espaceur($step_require) . "</td>
			<td>" . espaceur($add_step) . "</td>
		</tr>";
		$exp_total += $step_require;
		$step_require += $add_step;
		$level++;
	}
	echo '</table>';
	return $exp_total;
}

function get_level($xp)
{
	$level = 1;
	$exp_total = 0;
	$step_require = 150;
	$add_step = 70;
	$evolution = 1.102792;

	while ($exp_total <= $xp)
	{
		$add_step = round($add_step * $evolution, 5);
		$exp_total += $step_require;
		$step_require += $add_step;
		$level++;
	}
	return $level - 1;
}?>