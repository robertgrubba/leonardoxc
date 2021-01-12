<?php
require_once dirname(__FILE__)."/EXT_config_pre.php";
require_once dirname(__FILE__)."/config.php";
require_once dirname(__FILE__)."/EXT_config.php";
require_once dirname(__FILE__).'/FN_functions.php';
require_once dirname(__FILE__).'/FN_waypoint.php';
require_once dirname(__FILE__).'/FN_flight.php';
require_once dirname(__FILE__).'/CL_pilot.php';
require_once dirname(__FILE__).'/CL_mail.php';
require_once dirname(__FILE__)."/CL_pdf.php";
require_once dirname(__FILE__)."/CL_flightData.php";
require_once dirname(__FILE__)."/CL_user.php";

$pilotIDview=makeSane($_GET['userid'],1);

   $query = 'SELECT DISTINCT userID, max( LINEAR_DISTANCE ) AS bestDistance,
                        min(DATE) as firstFlightDate,max(DATE) as lastFlightDate,
                        (TO_DAYS(max(DATE)) - TO_DAYS(MIN(DATE))) as flyingPeriod,
                        max( DURATION ) AS maxDuration,
                        max( MAX_ALT ) AS maxAlt,
                        max( MAX_ALT - TAKEOFF_ALT ) AS maxAltGain,

                   '
                . ' count( * ) AS totalFlights,
                        sum( LINEAR_DISTANCE ) AS totalDistance,
                        sum( DURATION ) AS totalDuration, '
                . ' sum( LINEAR_DISTANCE )/count( * ) as mean_distance, '
                . ' sum( DURATION )/count( * ) as mean_duration, '
                . ' sum( FLIGHT_KM ) as totalOlcKm, '
                . ' sum( FLIGHT_POINTS ) as totalOlcScore, '
                . ' max( FLIGHT_POINTS ) as bestOlcScore, '
		. ' count(DISTINCT(takeoffID)) as takeoffsNumber '
        . ' FROM '.$flightsTable.$extra_table_str
        . ' WHERE '.$flightsTable.'.userID = '.$pilotIDview
        . ' GROUP BY userID'
        . ' ';
        $res= $db->sql_query($query);
    if($res <= 0){
      echo("<H3> Error in pilot stats query  </H3>\n");
      return;
    }
    $row = mysql_fetch_assoc($res);

$pierwszyLot = _ACCORDING_TO_LOGS_FLY_SINCE.": ".$row['firstFlightDate'];
$podsumowanie = _AIRTIME.": ".str_replace(':','h',sec2Time($row['totalDuration'],1))."m "._IN." ".$row['totalFlights']." "._FLIGHTS_FROM." ".$row['takeoffsNumber']." "._SINGLE_TAKEOFF.", "._PERSONAL_BEST.": ".str_replace('&nbsp;','',formatDistance($row['bestDistance'],1));

function generateForumFooter($r,$g,$b,$podsumowanie,$pierwszyLot, $color_name,$userid){
	//$my_img = imagecreate( 430, 40 );                             //width & height
	$my_img = imagecreate( 1300, 120 );                             //width & height
	$background  = imagecolorallocatealpha($my_img,0,0,0,127);
	$font= './templates/pgxc/tpl/Roboto-Regular.ttf';
	$logo = imagecreatefromgif('./templates/pgxc/tpl/leonardo_logo.gif');
	$color = imagecolorallocate($my_img,$r,$g,$b);
	$red = imagecolorallocate( $my_img, 255,0,0);
	imagettftext( $my_img, 30,0, 140, 31, $color,$font, $pierwszyLot);
	imagettftext( $my_img, 30,0, 140, 88, $color,$font, $podsumowanie);
	imagecopymerge($my_img, $logo, 0, 0, 0, 0, 120, 100, 100);
	imagettftext( $my_img, 20,0, 17, 110, $red, $font, '.pgxc.pl');
	$dest = './data/pilots/'.$userid.'/stopka_'.$color_name.'.png';
//	header( "Content-type: image/png" );
	imagepng( $my_img, $dest, 0 );
	imagecolordeallocate( $color );
	imagecolordeallocate( $red );
	imagecolordeallocate( $background );
	imagedestroy( $my_img );
	shell_exec("mogrify -resize 30% ".$dest);
}
generateForumFooter(16,82,137,$podsumowanie,$pierwszyLot,_BLUE,$pilotIDview);
generateForumFooter(255,255,0,$podsumowanie,$pierwszyLot,_YELLOW,$pilotIDview);
generateForumFooter(128,255,0,$podsumowanie,$pierwszyLot,_GREEN,$pilotIDview);

?> 
