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
	$dontShowDatesSelection=0;
	$dontShowSecondMenu=0;

	$dates_where_clause= dates::makeWhereClause(0,$season,$year,$month,0 );
	
	$where_clause.=" AND category=2 AND takeoffID in (SELECT ID FROM leonardo_waypoints where location='Kujawsko-Pomorskie') ";
	require_once dirname(__FILE__)."/common_pre.php";

	$sub_query = "SELECT $flightsTable.ID, userID, takeoffID , userServerID,
  				 gliderBrandID, $flightsTable.glider as glider, cat,
  				 MAX(FLIGHT_POINTS) as FLIGHT_POINTS, FLIGHT_KM, BEST_FLIGHT_TYPE  "
  		. " FROM $flightsTable,$pilotsTable "
        . " WHERE (userID!=0 AND  private=0) AND $flightsTable.userID=$pilotsTable.pilotID "
//			AND $flightsTable.userServerID=$pilotsTable.serverID
		." $where_clause ";





/*
	$query = 'SELECT ID, userID, takeoffID, userServerID, gliderBrandID, glider, cat, FLIGHT_POINTS, MAX(FLIGHT_KM) as FLIGHT_KM, BEST_FLIGHT_TYPE '
                 .'FROM leonardo_flights WHERE 1=1 '
		 .'AND takeoffID IN (SELECT ID FROM leonardo_waypoints where location="Kujawsko-Pomorskie") '
		 .'AND FLIGHT_KM in ('
			 .'SELECT MAX(FLIGHT_KM) '
			 .'FROM leonardo_flights '
			 .'WHERE 1=1 '
		 		 ."$dates_where_clause "
				 .'AND cat=1 '
				 .'AND validated=1 '
				 .'AND startType=1 '
				 .'AND private=0 '
				 .'AND takeoffID!=17030 '
				 .'AND takeoffID IN (SELECT ID FROM leonardo_waypoints where location="Kujawsko-Pomorskie") '
			 .'GROUP BY userID,takeoffID '
			.') '
                 .'AND takeoffID>17003 '
                 .'GROUP BY userID, takeoffID '
                 .'ORDER BY FLIGHT_KM DESC';
*/
	$query = 'SELECT DISTINCT ID, userID, takeoffID, userServerID, gliderBrandID, glider, cat, FLIGHT_POINTS, MAX(FLIGHT_KM) as FLIGHT_KM, BEST_FLIGHT_TYPE '
                 .'FROM leonardo_flights WHERE 1=1 '
		 .'AND takeoffID IN (SELECT takeoffID FROM '.$rankTakeoffsTable.' WHERE rankID='.$rank.' ) '
		 .'AND ID NOT IN (SELECT flightID FROM '.$bannedFlightsTable.' WHERE rank='.$rank.') '
		 .'AND userID NOT IN (SELECT pilotID FROM '.$bannedPilotsTable.' WHERE season='.$season.') '
		 .'AND FLIGHT_KM in ('
			 .'SELECT MAX(FLIGHT_KM) '
			 .'FROM leonardo_flights '
			 .'WHERE 1=1 '
		 		 ."$dates_where_clause "
				 .'AND cat=1 '
				 .'AND validated=1 '
				 .'AND startType=1 '
				 .'AND private=0 '
		 		.'AND takeoffID IN (SELECT takeoffID FROM '.$rankTakeoffsTable.' WHERE rankID='.$rank.' ) '
		 		.'AND ID NOT IN (SELECT flightID FROM '.$bannedFlightsTable.' WHERE rank='.$rank.') '
		 		.'AND userID NOT IN (SELECT pilotID FROM '.$bannedPilotsTable.' WHERE season='.$season.') '
			 .'GROUP BY userID,takeoffID '
			.') '
                 .'GROUP BY userID, takeoffID '
                 .'ORDER BY FLIGHT_KM DESC';


//var_dump($query);


require_once dirname(__FILE__)."/common.php";


?>
