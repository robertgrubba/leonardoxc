<html lang="PL">
<head>
<title>Latest leonardo comments</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="<?=$moduleRelPath?>/templates/pgxc/style_top_menu.css" type="text/css">

<base target="_parent">
</head>
<body>
<?php
	$obj = json_decode(file_get_contents('http://weather:8080/lastposts/'));
	//print_r($obj);

	
	foreach($obj as $post){
		$content = $post->{'post'};
		$replys = $post->{'replys'};
		$date = $post->{'updated_time'};
		echo '<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="main_text indexTable">';
                echo '<tr>';
                echo '<td width="20%" valign="bottom" bgcolor="#D7EDD3" class="catHeader">';
                //echo ''.$date.'<em style="font-size: 11px;font-family: Verdana, Arial, Helvetica, sans-serif; line-height: 110%;font-style:regular;">:</em>';
                echo '<ul style="font-size: 10px;font-family: Verdana, Arial, Helvetica, sans-serif; line-height: 110%;font-style: italic;" ><strong>'.$date.'</strong>: '.$content.'</ul>';
                echo '<div align="right" style="font-size: 10px;font-family: Verdana, Arial, Helvetica, sans-serif; line-height: 110%;font-style: italic;" ><a href="https://www.facebook.com/groups/1478007302478465/" target="_blank">(liczba komentarzy: '.$replys.')</a> </div>';
                echo '<hr>';
                echo '</td></tr></table>';

	}
?>
</body></html>
