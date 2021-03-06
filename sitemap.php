<?php
error_reporting(E_ERROR);
        require_once dirname(__FILE__)."/EXT_config_pre.php";
        require_once "config.php";
        require_once "EXT_config.php";

        require_once "CL_flightData.php";
        require_once "FN_functions.php";
        require_once "FN_UTM.php";
        require_once "FN_waypoint.php";
        require_once "FN_output.php";
        require_once "FN_pilot.php";
        require_once "FN_flight.php";
        require_once dirname(__FILE__)."/templates/".$PREFS->themeName."/theme.php";

 
//for takeoffs

$query="select takeoffID, LENGTH(w.description) as opis, count(*) as number, MAX(dateAdded) as date from ".$flightsTable.", ".$waypointsTable." w where w.id=takeoffID group by takeoffID order by number,opis";
$result=mysql_query($query);
$querymax="select MAX(opis) as opis, MAX(number) as number from (select takeoffID, LENGTH(w.description) as opis, count(*) as number from ".$flightsTable.", ".$waypointsTable." w where w.id=takeoffID group by takeoffID order by number,opis) as new;";
$resultmax=mysql_query($querymax);
while ($row = mysql_fetch_assoc($resultmax)){
	$maxflights=intval($row['number']);
	$maxdesc=intval($row['opis']);
}
mysql_free_result($resultmax);
while ($row = mysql_fetch_assoc($result)) {
    $takeoffID= $row['takeoffID'];
    $flights= intval($row['number']);
    $description= intval($row['opis']);
    $date=str_replace(' ','T',$row['date'])."+00:00";

    $priority = round(($flights/$maxflights)*0.75 + ($description/$maxdesc)*0.25, 2);
    if ($priority < 0.1) $priority=0.1;

    $url="https://leonardo.pgxc.pl/startowisko/".$takeoffID;
    if(filter_var($url,FILTER_VALIDATE_URL)){
	    echo "<url> \n";
	    echo "	<loc>".$url."</loc>\n";
	    echo "	<lastmod>".$date."</lastmod>\n";
	    echo "	<changefreq>monthly</changefreq>\n";
	    echo "	<priority>$priority</priority>\n";
	    echo "</url>\n";
    }
}
mysql_free_result($result);

//for flights

$query="select id,takeoffID, dateAdded as date,FLIGHT_POINTS from ".$flightsTable." where private!=1 and validated=1";
$result=mysql_query($query);
$querymax="select MAX(FLIGHT_POINTS) as maxpoints from ".$flightsTable." where private!=1 and validated=1";
$resultmax=mysql_query($querymax);

while ($row = mysql_fetch_assoc($resultmax)){
	$maxpoints=intval($row['maxpoints']);
}
mysql_free_result($resultmax);


while ($row = mysql_fetch_assoc($result)) {
    $flightID=$row['id'];
    $takeoffID= $row['takeoffID'];
    $points= intval($row['FLIGHT_POINTS']);
    $date=str_replace(' ','T',$row['date'])."+00:00";

    $querysite="select MAX(FLIGHT_POINTS) as sitemax from ".$flightsTable." where private=!1 and validated=1 and takeoffID=".$takeoffID;
    $resultsite=mysql_query($querysite);
    while ($row = mysql_fetch_assoc($resultsite)){
	$sitemax = intval($row['sitemax']);
    }
    mysql_free_result($resultsite);

    $priority = round(($points/$maxpoints)*0.5 + ($points/$sitemax)*0.5, 2);
    if ($priority < 0.1) $priority=0.1;
    if ($priority >1) $priority=1.0;
   
    $url = "https://leonardo.pgxc.pl/lot/".$flightID;
    if(filter_var($url,FILTER_VALIDATE_URL)){
	    echo "<url> \n";
	    echo "	<loc>".$url."</loc>\n";
	    echo "	<lastmod>".$date."</lastmod>\n";
	    echo "	<changefreq>never</changefreq>\n";
	    echo "	<priority>$priority</priority>\n";
	    echo "</url>\n";
    }
}
mysql_free_result($result);


//for areas

$query="select areaID as id from ".$areasTakeoffsTable." group by id";
$result=mysql_query($query);
while ($row = mysql_fetch_assoc($result)) {
    $id= $row['id'];

    $priority = 0.5;

    $url="https://leonardo.pgxc.pl/rejon/".$id;
    if(filter_var($url,FILTER_VALIDATE_URL)){
	    echo "<url> \n";
	    echo "	<loc>".$url."</loc>\n";
	    echo "	<changefreq>monthly</changefreq>\n";
	    echo "	<priority>$priority</priority>\n";
	    echo "</url>\n";
    }
}
mysql_free_result($result);

