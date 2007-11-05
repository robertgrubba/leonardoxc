<? 
/************************************************************************/
/* Leonardo: Gliding XC Server					                        */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2004-5 by Andreadakis Manolis                          */
/* http://sourceforge.net/projects/leonardoserver                       */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

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
	require_once $moduleRelPath."/templates/".$PREFS->themeName."/theme.php";
	setDEBUGfromGET();

	$clubID=makeSane($_GET['clubID'],1);
	if ( ! auth::isClubAdmin($userID,$clubID) && !auth::isAdmin($userID) ) { echo "go away"; return; }

	$op=makeSane($_GET['op']);	
	$flightID=makeSane($_GET['flightID'],1);
	$pilotID=makeSane($_GET['pilotID'],1);
	if ($op=='add'){	
		  $query="INSERT INTO $clubsFlightsTable (clubID,flightID) VALUES ($clubID,$flightID )";
		  $res= $db->sql_query($query);
		  if($res <= 0){   
			 echo("<H3> Error in inserting club flight ! $query</H3>\n");
		  } else echo "Flight added to league";
	} else if ($op=='remove'){	
		  $query="DELETE FROM $clubsFlightsTable WHERE clubID=$clubID AND flightID=$flightID";
		  $res= $db->sql_query($query);
		  if($res <= 0){   
			 echo("<H3> Error in deleting club flight ! $query</H3>\n");
		  } else echo "Flight removed from league";
	} 

?>