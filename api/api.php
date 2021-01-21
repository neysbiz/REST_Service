<?php
header("Content-Type:application/json");
require("../PDF_Pusher/pusher.php");

if(!empty($_GET['doc']))
	{
	$doc=$_GET['doc'];

	$pusher = new Pusher();
	$pusher->call_doc('kessel3', $doc);
	}
?>
