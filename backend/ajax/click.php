<?php 
include_once '../help/config.php';
include_once '../help/shortclicker.php';

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$shortClicker = new ShortClicker($db);
	echo $shortClicker->getOrigUrl($_POST['linkId']);
}
else
{
	die('GO BACK');
}
