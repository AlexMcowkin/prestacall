<?php 
include_once 'backend/help/config.php';

include_once 'backend/help/shortlinker.php';
include_once 'backend/help/shortclicker.php';

$shortLinker = new ShortLinker($db);

if(!empty($_GET["url"]))
{
	$shortUrl = filter_var($_GET["url"], FILTER_SANITIZE_STRING);

	if($shortLinker->verifyShortUrlDb($shortUrl))
	{
	    $shortClicker = new ShortClicker($db);
	    $url = $shortClicker->getOrigUrlByShortUrl($shortUrl);

	    header("HTTP/1.1 301 Moved Permanently");
	    header("Location: " . $url);
	}
} 

require_once 'views/header.php';
require_once 'views/content.php';

// get data for urls table
$list = $shortLinker->getUrlToTable();

require_once 'views/table.php';
require_once 'views/footer.php';
