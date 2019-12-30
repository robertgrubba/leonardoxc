<?
/************************************************************************/
/* Leonardo: Gliding XC Server					                                */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2004-5 by Andreadakis Manolis                          */
/* http://sourceforge.net/projects/leonardoserver                       */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

//-----------------------------------------------------------------------
//-----------------------  custom league --------------------------------
//-----------------------------------------------------------------------
	// 	open class ,   category=2";
	// Some config
	$cat=1; // pg
	$dontShowDatesSelection=1;
	$dontShowSecondMenu=1;
	
	$where_clause.=" AND category=2 AND takeoffID in (17005, 17009, 17006, 12477, 12478, 17010, 17011, 17015) AND takeoffID>12476 AND takeoffID<17016 ";
	require_once dirname(__FILE__)."/common_pre.php";

	$sub_query = "SELECT $flightsTable.ID, userID, takeoffID , userServerID,
  				 gliderBrandID, $flightsTable.glider as glider, cat,
  				 MAX(FLIGHT_POINTS) as FLIGHT_POINTS, FLIGHT_KM, BEST_FLIGHT_TYPE  "
  		. " FROM $flightsTable,$pilotsTable "
        . " WHERE (userID!=0 AND  private=0) AND $flightsTable.userID=$pilotsTable.pilotID "
//			AND $flightsTable.userServerID=$pilotsTable.serverID
		." $where_clause ";


	$query = 'select ID, userID, takeoffID, userServerID, gliderBrandID, glider, cat, FLIGHT_POINTS, FLIGHT_KM, BEST_FLIGHT_TYPE from '.$flightsTable.' where FLIGHT_KM in (select max(FLIGHT_KM) as userRecord from (SELECT '.$flightsTable.'.ID, userID, takeoffID , userServerID, gliderBrandID, '.$flightsTable.'.glider as glider, cat, FLIGHT_POINTS, FLIGHT_KM, BEST_FLIGHT_TYPE FROM '.$flightsTable.','.$pilotsTable.' WHERE (userID!=0 AND private=0) AND takeoffID in (17005, 17009, 17006, 12477, 12478, 17010, 17011, 17015) AND '.$flightsTable.'.userID='.$pilotsTable.'.pilotID  AND (cat=1) AND validated=1) z group by userID, takeoffID) and takeoffID not in (9716,9193) group by userID,takeoffID order by takeoffID ';

$query = 'select ID, userID, takeoffID, userServerID, gliderBrandID, glider, cat, FLIGHT_POINTS, MAX(FLIGHT_KM) as FLIGHT_KM, BEST_FLIGHT_TYPE from '.$flightsTable.' where validated=1 and cat=1 and private=0 and takeoffID in (17005, 17009, 17006, 12477, 12478, 17010, 17011, 17015) group by userID,takeoffID order by takeoffID';

$query = 'select ID, userID, takeoffID, userServerID, gliderBrandID, glider, cat, FLIGHT_POINTS, MAX(FLIGHT_KM)as FLIGHT_KM, BEST_FLIGHT_TYPE from leonardo_flights where FLIGHT_KM in (select MAX(FLIGHT_KM) as FLIGHT_KM from leonardo_flights where validated=1 and cat=1 and private=0 and  takeoffID in (17005, 17009, 17006, 12477, 12478, 17010, 17011, 17015) AND takeoffID>12476 AND takeoffID<17016  group by userID,takeoffID) and takeoffID not in (9133,13478,9093,9193)  group by userID,takeoffID order by takeoffID;';


	$query = 'SELECT ID, userID, takeoffID, userServerID, gliderBrandID, glider, cat, FLIGHT_POINTS, MAX(FLIGHT_KM) as FLIGHT_KM, BEST_FLIGHT_TYPE '
                 .'FROM leonardo_flights WHERE 1=1 '
		 .'AND FLIGHT_KM in ('
			 .'SELECT MAX(FLIGHT_KM) '
			 .'FROM leonardo_flights '
			 .'WHERE 1=1 '
				 .'AND validated=1 '
				 .'AND cat=1 '
				 .'AND private=0 '
				 .'AND  takeoffID IN (17005, 17009, 17006, 12477, 12478, 17010, 17011, 17015) '
			 .'GROUP BY userID,takeoffID '
			.') '
                 .'AND takeoffID>12476 '
                 .'AND takeoffID<17016 '
		 .'AND takeoffID NOT IN (9093,9716,9133,13478) '
                 .'GROUP BY userID, takeoffID '
                 .'ORDER BY FLIGHT_KM ';


//var_dump($query);


require_once dirname(__FILE__)."/common.php";


?>
