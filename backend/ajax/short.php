<?php 
include_once '../help/config.php';
include_once '../help/shortlinker.php';

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$shortLinker = new ShortLinker($db);
	echo $shortLinker->createShortUrl($_POST['inputUrl'], $_POST['radioTime']);
}
else
{
	die('GO BACK');
}
