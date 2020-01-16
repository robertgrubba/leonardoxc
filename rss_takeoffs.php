<?
//************************************************************************
// Leonardo XC Server, http://leonardo.pgxc.pl
//
// Copyright (c) 2020- by Robert Grubba
//
// This program is free software. You can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License.
//
// $Id: rss_takeoffs.php,v 0.1 2020/01/16 12:46:40 Robert G                                                                 
//
//************************************************************************
	if (!defined("IN_RSS") ) exit;

		$flightID = $_GET['flightID']+0;
		if ($flightID) $flightWhereClause=" AND $commentsTable.flightID=$flightID ";
		else $flightWhereClause='';
		
		$query="SELECT * FROM $waypointsTable
				WHERE LENGTH(description)>100
				ORDER BY $waypointsTable.modifyDate DESC LIMIT $count";
		$res= $db->sql_query($query);
		if ( $_GET['debug'] ) exit($query);
		if($res <= 0){
			echo("<H3> Error in query! </H3>\n");
			exit();
		}
		
$encoding="utf-8";

$RSS_str="<?xml version=\"1.0\" encoding=\"$encoding\" ?>
<rss version=\"0.92\">
<channel>
	<docs>http://leonardo.pgxc.pl</docs>
	<title>Leonardo at ".$_SERVER['SERVER_NAME']." :: Latest takeoffs</title>
	<link>".str_replace("&","&amp;",getLeonardoLink(array('op'=>'list_takeoffs')))."</link>
	<language>pl</language>
	<description>Leonardo at ".$_SERVER['SERVER_NAME']." :: Latest takeoffs</description>
	<managingEditor>".$CONF_admin_email."</managingEditor>
	<webMaster>".$CONF_admin_email."</webMaster>
	<lastBuildDate>". gmdate('D, d M Y H:i:s', time()) . " GMT</lastBuildDate>
<!-- BEGIN post_item -->
";
	$pilotNames=array();

	while ($row = mysql_fetch_assoc($res)) { 
		$link=htmlspecialchars(getLeonardoLink(array('op'=>'show_waypoint','waypointIDview'=>$row['ID'])) );
		$RSS_str.="<item>
<title><![CDATA[$name".$row['intName']."]]></title>
<guid isPermaLink=\"false\">".$row['ID']."</guid>
<pubDate>". gmdate('D, d M Y H:i:s', strtotime($row['modifyDate']) ) . " GMT</pubDate>
<link>$link</link>
<description><![CDATA[". $row['description']."]]></description>
</item>
";
	
	}
	
	
		$RSS_str.="<!-- END post_item -->
		</channel>
		</rss>
		";

	if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
	{
		header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
	}
	else
	{
		header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
	}
	header ('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
	header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header ('Content-Type: text/xml');
	echo $RSS_str;




?>
