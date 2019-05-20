<html>
<head>
<title>Latest leonardo comments</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="<?=$moduleRelPath?>/templates/pgxc/style_top_menu.css" type="text/css">

<base target="_parent">
</head>
<body>
<?php
	$rss = new DOMDocument();
	$rss->load("http://localhost/rss.php?op=comments");
	$feed = array();
	foreach ($rss->getElementsByTagName('item') as $node) {
		$item = array ( 
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
			);
		array_push($feed, $item);
		echo "$description";
	}
	$limit = 5;
	for($x=0;$x<$limit;$x++) {
		$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
		$link = $feed[$x]['link'];
		$description = $feed[$x]['desc'];
		$date = date('d.m.Y, H:i:s', strtotime($feed[$x]['date']));
		echo '<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="main_text indexTable">';
		echo '<tr>';
		echo '<td width="20%" valign="bottom" bgcolor="#CED8E1" class="catHeader">';
		echo '<a style="font-size: 11px;font-family: Verdana, Arial, Helvetica, sans-serif; line-height: 110%;" href="'.$link.'" title="'.$title.'">'.$title.':</a>';
		echo '<ul style="font-size: 10px;font-family: Verdana, Arial, Helvetica, sans-serif; line-height: 110%;font-style: italic;" >'.$description.'</ul>';
		echo '<div align="right" style="font-size: 10px;font-family: Verdana, Arial, Helvetica, sans-serif; line-height: 110%;font-style: italic;" >('.$date.') </div>';
		echo '<hr>';
		echo '</td></tr></table>';
	}
?>
</body></html>
