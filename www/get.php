<?php
$url = $_GET['url'];
$path_parts = pathinfo($url);
$ext = $path_parts['extension'];
$ext = explode("?", $ext);
$ext = $ext[0];
//var_dump($path_parts);
//die;
if (isset($_POST['url']))
{
	header("Location: get.php?url=" . urlencode($_POST['url']));
	exit(0);
}
else if (empty($url))
{
?>
<form action="get.php">
	<input name="url" type="text" size="100" value="http://" />
	<input type="submit" value="Go" />
</form>
<?php
	exit(0);
}

if (!empty($_POST))
{
/*	echo "<pre>";
	var_dump($_GET);
	var_dump($_POST);
	echo "</pre>";
*/	$post = array();
	foreach ($_POST as $key => $value)
	{
		$post[] = $key . "=" . urlencode($value) ."\r\n";
	}
	$post = implode("&", $post);
	$message  = "POST " . $_GET['url'] . " HTTP/1.0\r\n";
	$message .= "Content-type: application/x-www-form-urlencoded\r\n";
	$message .= "Content-length: " . strlen($post) . "\r\n";
	$message .= $post . "\r\n";

	$action = pathinfo($_GET['action']);
//	var_dump($action);
//	echo basename($action['dirname']);
	// monserveur correspond au serveur qui doit recevoir la requete
	$fd = fsockopen(basename($action['dirname']), 80);
	fputs($fd,$message);
    while (!feof($fd)) {
        echo fgets($fd, 128);
    }
	fclose($fd);
	exit(0);
}
$buffer = file($_GET['url']);
$lines = array();
foreach ($buffer as $line_num => $line)
{
	$lines[] = $line;
}
$lines = implode("", $lines);

if (empty($ext) ||
    "php" == $ext ||
    "html" == $ext)
	$content_type = "text/html";
else if ("png" == $ext)
	$content_type = "image/png";
if (strstr("ISO-8859-1", $lines))
	$charset = "ISO-8859-1";
else
	$charset = "UTF-8";
header("Content-Type: " . $content_type . "; charset=" . $charset);
$lines = str_replace("action=\"", "action=\"http://fr.lintury.tk/get.php?url=" . urlencode($url) . "&action=", $lines);
echo $lines;
?>
