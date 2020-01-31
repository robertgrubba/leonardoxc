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

//print_r(array_keys(get_defined_vars()));
//print_r($season);

//  $y=$year ? $year : date('Y');
  $nextY = $y+1;
  $y=$season;
  $nextY=$season+1;
//  error_log("YEAR: "+$y);

	
//	$where_clause.=" AND category=2 AND takeoffID in (17005, 17009, 17006, 12477, 12478, 17010, 17011, 17015) AND DATE<'".$nextY."'-01-01' AND DATE >'".$y."'-01-01' ";
	$where_clause.=" AND category in (1,2,3)  AND takeoffID in ('17005', '17009', '17006', '12477', '12478', '17010', '17011', '17015') AND DATE<'".$nextY."'-01-01' AND DATE >'".$y."'-01-01' AND takeoffID>12476 AND takeoffID<17016 ";
	require_once dirname(__FILE__)."/common_pre.php";

	$sub_query = "SELECT $flightsTable.ID, userID, takeoffID , userServerID,
  				 gliderBrandID, $flightsTable.glider as glider, cat,
  				 MAX(FLIGHT_POINTS) as FLIGHT_POINTS, FLIGHT_KM, BEST_FLIGHT_TYPE  "
  		. " FROM $flightsTable,$pilotsTable "
        . " WHERE (userID!=0 AND  private=0) AND $flightsTable.userID=$pilotsTable.pilotID "
//			AND $flightsTable.userServerID=$pilotsTable.serverID
		." $where_clause ";





// do tabeli trafiaja loty wgrane nie pozniej niz tydzien po zakonczeniu sezonu
if($season>=2019){
        $flight_deadline ="AND STR_TO_DATE(dateAdded, '%Y-%m-%d') <= DATE_ADD(STR_TO_DATE('".$nextY."-01-01','%Y-%m-%d'), INTERVAL 1 WEEK) ";
}

// do tabeli trafiaja loty wgrane nie pozniej niz tydzien po wykonaniu lotu
if($season>2019){
        $flight_deadline.="AND STR_TO_DATE(dateAdded, '%Y-%m-%d') <= DATE_ADD(STR_TO_DATE(DATE,'%Y-%m-%d'), INTERVAL 1 WEEK) ";
}

//print_r($flight_deadline);
	$query = 'SELECT ID, userID, takeoffID, userServerID, gliderBrandID, glider, cat, FLIGHT_POINTS, MAX(FLIGHT_KM) as FLIGHT_KM, BEST_FLIGHT_TYPE '
                 .'FROM leonardo_flights WHERE 1=1 '
		 .'AND  takeoffID IN (17005, 17009, 17006, 12477, 12478, 17010, 17011, 17015) '
		 .'AND DATE<\''.$nextY.'-01-01\' '
		 .'AND DATE >\''.$y.'-01-01\' '
		 .$flight_deadline.' '
		 .'AND FLIGHT_KM in ('
			 .'SELECT MAX(FLIGHT_KM) '
			 .'FROM leonardo_flights '
			 .'WHERE 1=1 '
				 .'AND validated=1 '
				 .'AND cat=1 '
				 .'AND private=0 '
				 .'AND DATE<\''.$nextY.'-01-01\' '
				 .'AND DATE >\''.$y.'-01-01\' '
				 .'AND  takeoffID IN (17005, 17009, 17006, 12477, 12478, 17010, 17011, 17015) '
				 .$flight_deadline.' '
			 .'GROUP BY userID,takeoffID '
			.') '
                 .'AND takeoffID>12476 '
                 .'AND takeoffID<17016 '
		 .'AND takeoffID NOT IN (9093,9716,9133,13478) '
                 .'GROUP BY userID,takeoffID '
                 .'ORDER BY takeoffID;';


require_once dirname(__FILE__)."/common.php";


?>
