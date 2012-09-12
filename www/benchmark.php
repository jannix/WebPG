<?php
echo json_encode(array('t'=> 1, 'p'=> 1));
$beginTime = microtime(true);
$i = 0;
while ($i)
{
	$string = "42";
	$id_user = (int)$string;
	if ($id_user == 12)
	{}
	else if ($id_user == 42)
	{}
	$i++;
}
echo round(microtime(true) - $beginTime, 15) . " secondes<br />";

$beginTime = microtime(true);
$i = 0;
while ($i)
{
	$string = "42";
	$id_user = $string;
	if ($id_user == 12)
	{}
	else if ($id_user == 42)
	{}
	$i++;
}
echo round(microtime(true) - $beginTime, 15) . " secondesa<br />";
?>