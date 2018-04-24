<?php

$i = 0; 
$url = "http://apache-wp-1/category/leonardoxc/feed/"; // url of news feed
$rss = simplexml_load_file($url); // XML parser

echo '<?php';
echo '$CONF[\'news\'][\'items\']=array(';

foreach($rss->channel->item as $item) {
if ($i < 5) { // parse only 5 items
    $dto = DateTime::createFromFormat(DateTime::RSS, $item->pubDate);
    $pubDate = $dto->format('Y/m/d');
    echo ($i+1).'=>array(';    	
    echo '\'active\'=>1,';
    echo '\'date\'=>\''.$pubDate.'\',';
    echo '\'link\'=>\''.$item->link.'\',';
    echo '\'text\'=>\''.$item->title.'\',';
    echo '\'target\'=>\'_blank\'';
    echo '),';
}

$i++;
}
echo ');';
?>
