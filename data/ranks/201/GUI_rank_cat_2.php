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


$query = 'SELECT ID, userID, takeoffID, userServerID, gliderBrandID, glider, cat, FLIGHT_POINTS, MAX(FLIGHT_KM)as FLIGHT_KM, BEST_FLIGHT_TYPE
	FROM leonardo_flights WHERE 
		FLIGHT_KM IN (select MAX(FLIGHT_KM) AS FLIGHT_KM 
				FROM leonardo_flights WHERE validated=1 
					AND cat=1 
					AND private=0 
					and takeoffID in (17005, 17009, 17006, 12477, 12478, 17010, 17011, 17015) 
					AND takeoffID>12476 
					AND takeoffID<17016  
				GROUP BY userID,takeoffID) 
		AND takeoffID NOT IN (9133,13478,9093,9193,9191) 
		GROUP BY userID,takeoffID 
	ORDER BY takeoffID;';


require_once dirname(__FILE__)."/common.php";


?>
