<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Chisinau');

define('DB_HOST', 'localhost');
define('DB_NAME', 'prestacall_db');
define('DB_USER', 'root');
define('DB_PWD', 'root');

define('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');

try
{
	$db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PWD); 
	$db->exec('SET NAMES utf8');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
	die('Connection to DB failed');
}