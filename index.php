<?
//************************************************************************
// Leonardo XC Server, http://www.leonardoxc.net
//
// Copyright (c) 2004-2010 by Andreadakis Manolis
//
// This program is free software. You can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License.
//
// $Id: index.php,v 1.127 2012/10/17 09:45:24 manolis Exp $
//
//************************************************************************

$userAgent=$_SERVER['HTTP_USER_AGENT'];

if ( $_SERVER['REQUEST_URI']=='/' && (strpos($userAgent, 'curl') !== false)){
	echo "OK ";
	die();
}

if ( file_exists(dirname(__FILE__)."/install.php") ) {
	echo "Please remove install.php from dir modules/leonardo in order to run Leonardo<br>";
	exit;
}


//------------------------------------------------------------
// we need to init joomla first thing!
require_once dirname(__FILE__)."/site/config_op_mode.php";
// if ($opMode==5 && $CONF_use_own_template==1 ) { // Joomla
if ($opMode==5 ) { // Joomla
	define( '_JEXEC', 1 );
	define( 'DS', DIRECTORY_SEPARATOR );
		
	require_once JPATH_BASE.DS.'includes'.DS.'defines.php';
	require_once JPATH_BASE.DS.'includes'.DS.'framework.php';
	$mainframe =& JFactory::getApplication('site');
	$user   =& JFactory::getUser();
//echo "<hr>user<hr>"; print_r($user);
//$session =& JFactory::getSession();

//  echo "<hr>2<hr>";
//  print_r($_SESSION);
// echo "<hr>3<hr>";
//print_r($session); 
}
//------------------------------------------------------------

function leo_getmicrotime() {
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

$pageStart=leo_getmicrotime();


@session_start();

$module_name = basename(dirname(__FILE__));

//$moduleAbsPath=dirname(__FILE__);
// $moduleRelPath="modules/$module_name";

// ugly joomla 1.5 hack
@include dirname(__FILE__)."/site/predefined/5/globals_include.php";

require_once dirname(__FILE__)."/config.php";

// ugly joomla 1.5 hack
@include dirname(__FILE__)."/site/predefined/5/globals_include.php";


setVarFromRequest("lng",$PREFS->language);
if ( strlen($lng)==2) {
	$lng=array_search($lng,$lang2iso);
	if (!$lng) $lng=$PREFS->language;
}
$currentlang=$lng;

if ( !eregi($CONF_mainfile, $_SERVER['PHP_SELF']) ) {
    die ("You can't access this file directly...");
}

if ($CONF_use_utf) define('CONF_LANG_ENCODING_TYPE','utf8');
else  define('CONF_LANG_ENCODING_TYPE','iso');


require_once dirname(__FILE__)."/language/".CONF_LANG_ENCODING_TYPE."/lang-".$currentlang.".php";
require_once dirname(__FILE__)."/language/".CONF_LANG_ENCODING_TYPE."/countries-".$currentlang.".php";
require_once dirname(__FILE__)."/FN_UTM.php";
require_once dirname(__FILE__)."/FN_functions.php";
require_once dirname(__FILE__)."/FN_waypoint.php";
require_once dirname(__FILE__)."/FN_brands.php";

require_once dirname(__FILE__)."/FN_pilot.php";
require_once dirname(__FILE__)."/FN_flight.php";
require_once dirname(__FILE__)."/FN_output.php";
require_once dirname(__FILE__)."/CL_flightData.php";
require_once dirname(__FILE__)."/CL_dates.php";
require_once dirname(__FILE__)."/CL_brands.php";
require_once dirname(__FILE__)."/CL_statsLogger.php";
require_once dirname(__FILE__)."/CL_filter.php";
require_once dirname(__FILE__)."/templates/".$PREFS->themeName."/theme.php";


$CLIENT['browser']=findBrowserOS();
// $agent,$version,$os,$aol

	
 // if we use utf
if ($CONF_use_utf) {
		$db->sql_query("SET NAMES utf8");
}

$pagetitle = _PAGE_TITLE;


if ($opMode==1 ) { // phpnuke
	$user = $_REQUEST['user'];
	if ( is_user($user)) {
		cookiedecode($user);
		$userID=$cookie[0];
		$userName=$cookie[1];
	}
} else if ($opMode==2 ) { // phpBB
	$userID=$userdata['user_id'];
	$userName=$userdata['username'];
} else if ($opMode==3 ) { // standalone
	$userID=$userdata['user_id'];
	$userName=$userdata['username'];
} else if ($opMode==4 ) { // discuz
	$userID=$userdata['user_id'];
	$userName=$userdata['username'];
} else if ($opMode==5 ) { // joomla
	$userID=$userdata['user_id'];
	$userName=$userdata['username'];
} else if ($opMode==6 ) { // phpbb3

	if ($user->data['user_type'] == 2) {
		$userID=0;
		$userName='guest';
	} else {
		$userID=$user->data['user_id'];
		$userName=$user->data['username'];
	}
}

$_SESSION['userID']=$userID;


if ($_GET['remote']) {
	$RUN['remote']='remote';	
}

if (substr($_SERVER['REQUEST_URI'],-5)=='print' || $_GET['print'] ) {
	global $RUN;	
	$RUN['view']='print';
	$CONF_compItemsPerPage=10000;
	$PREFS->itemsPerPage=$CONF['pdf']['maxflightsPerPrint'];	
}
if (substr($_SERVER['REQUEST_URI'],-6)=='print0' || $_GET['print0'] ) {
	global $RUN;	
	$RUN['view']='print';
	$RUN['view0']='print0';
	$CONF_compItemsPerPage=10000;
	$PREFS->itemsPerPage=$CONF['pdf']['maxflightsPerPrint'];	
}
	
//$RUN['view']='';
//$RUN['view0']='';
	
if ($_GET['leoSeo']) {
	// inject some $_GET values
	$seoParamsOrg=split(',',$_GET['leoSeo']);
	foreach($seoParamsOrg as $seoParam) {
		$t1=split(':',$seoParam);
		$seoParams[$t1[0]]=$t1[1];
	}

	if (isset($seoParams['cat'])) {
		$_REQUEST['cat']=$seoParams['cat'];
	}

	if (isset($seoParams['brand'])) {
		$_REQUEST['brandID']=$seoParams['brand'];
		if ($_REQUEST['brandID']=='all') $_REQUEST['brandID']=0;
	}

	if (isset($seoParams['takeoff'])) {
		$_REQUEST['takeoffID']=$seoParams['takeoff'];
		if ($_REQUEST['takeoffID']=='all') $_REQUEST['takeoffID']=0;
	}

	if (isset($seoParams['class'])) {
		$_REQUEST['class']=$seoParams['class'];
		if ($_REQUEST['class']=='all') $_REQUEST['class']=0;
	}

	if (isset($seoParams['xctype'])) {
		$_REQUEST['xctype']=$seoParams['xctype'];
		if ($_REQUEST['xctype']=='all') $_REQUEST['xctype']=0;
	}

	if (isset($seoParams['pilot'])) {
		$_REQUEST['pilotID']=$seoParams['pilot'];
		if ($_REQUEST['pilotID']=='all') $_REQUEST['pilotID']=0;
	}

	if (isset($seoParams['club'])) {
		if (strpos($seoParams['club'],'.')) {
			$tmpNac=split('\.',$seoParams['club']);
			$_REQUEST['nacid']=$tmpNac[0]+0;
			if ($_REQUEST['nacid']) {
				$_REQUEST['nacclubid']=$tmpNac[1]+0;
				$_REQUEST['clubID']=0;
			} else {
				$_REQUEST['nacclubid']=0;
				$_REQUEST['clubID']=$tmpNac[1]+0;
			}
		} else {
			$_REQUEST['clubID']=$seoParams['club'];

			if ($_REQUEST['clubID']=='all') {
				$_REQUEST['clubID']=0;
				$_REQUEST['nacid']=0;
				$_REQUEST['nacclubid']=0;
			}
		}
	}
}

// DEBUG
setVarFromRequest("DBGcat","");
setVarFromRequest("DBGlvl",0,1);

// new filter code
setVarFromRequest("fltr",0);
if ($fltr) {
	$filter=new LeonardoFilter();
	$filter->parseFilterString($fltr);
	// echo "<PRE>";	print_r($filter->filterArray);	echo "</PRE>";	
	$_SESSION['filter_clause']=$filter->makeClause();
}

if ($_REQUEST['setFilter']==1) { // form submitted
	if (!$filter) $filter=new LeonardoFilter();
	//echo "<pre>"; print_r($_REQUEST); echo "</pre>";
	
	$filter->filterImport('_REQUEST');
	//echo "<PRE>";	print_r($filter->filterArray);	echo "</PRE>";	
	if ($_REQUEST['clearFilter']==1) {
		$_SESSION["filter_clause"]='';
		$_SESSION["fltr"]='';
	} else {
		$_SESSION["filter_clause"]=$filter->makeClause();	
		$_SESSION["fltr"]=$filter->makeFilterString();
	}
}

// echo "<PRE>";	print_r($_SESSION['filter_clause']);	echo "</PRE>";			

setVarFromRequest("includeMask",0);

setVarFromRequest("waypointIDview",0,1);
setVarFromRequest("flightID",0,1);
setVarFromRequest("pilotIDview",0,0);
setVarFromRequest("year",date("Y"),1);
setVarFromRequest("month",0,1); // date("m") for current month
setVarFromRequest("day",0,1); // only used for flights_list

// SEASON MOD
setVarFromRequest("season",0,1); // only used for flights_list
setVarFromRequest("subseason",0,0); // can be text
if ($season) {
	setVar("year",0);
	setVar("month",0);
	setVar("day",0);
}

setVarFromRequest("l_date",-1);
if ($l_date=='alltimes'){
	setVar("year",0);
	setVar("month",0);
	setVar("day",0);
	setVar("season",0);	
}else if ($l_date>=0) {
	if ( preg_match('/^(\d{4})\.(\d{2})\.(\d{2})$/',$l_date,$matches) ) {
		setVar("year",$matches[1]);
		setVar("month",$matches[2]);
		setVar("day",$matches[3]);
		setVar("season",0);
	} else if ( preg_match('/^(\d{4})\.(\d{2})$/',$l_date,$matches) ) {
		setVar("year",$matches[1]);
		setVar("month",$matches[2]);
		setVar("day",0);
		setVar("season",0);
	}else if ( preg_match('/^(\d{4})$/',$l_date,$matches) ) {
		setVar("year",$matches[1]);
		setVar("month",0);
		setVar("day",0);
		setVar("season",0);
	} else if ( preg_match('/^season(\d{4})$/',$l_date,$matches) ) {
		setVar("year",0);
		setVar("month",0);
		setVar("day",0);
		setVar("season",$matches[1]);
	}



}

/*
echo 'REQUEST_URI:'.$_SERVER['REQUEST_URI'];
echo 'QUERY_STRING:'.$_SERVER['QUERY_STRING'];
print_r($_REQUEST);
exit;
*/
// BRANDS MOD
setVarFromRequest("brandID",0,1); // numeric
if (! brands::isValidBrandForFilter($brandID) ) setVar("brandID",0);
setVarFromRequest("pilotID",0,0);
setVarFromRequest("takeoffID",0,1);
setVarFromRequest("country",$PREFS->viewCountry);
if ($country=='world') setVar('country',0);

setVarFromRequest("op",$CONF_main_page);
setVarFromRequest("cat",$PREFS->viewCat,1);
setVarFromRequest("class",0,1);
setVarFromRequest("xctype",0,1);
setVarFromRequest("subcat","pg");
setVarFromRequest("comp",0,1);
setVarFromRequest("rank",0,1);
setVarFromRequest("subrank",0,1);
setVarFromRequest("clubID",0,1);
/// Martin Jursa 17.05.2007 : values for nacclub filtering added
setVarFromRequest("nacclubid", 0, 1);
setVarFromRequest("nacid", 0, 1);

// The filter for displaying only flights with photos
setVarFromRequest("filter01", 0, 1);


$serverID=0;
$serverIDview=0;
if ( count($pilotPartsArray=split('_',$pilotIDview)) > 1 ) {
	$serverIDview=$pilotPartsArray[0];
	$serverID=$serverIDview;
	$pilotIDview=$pilotPartsArray[1];
	// echo "@@ $serverID $pilotIDview@@";
}

// echo "# $pilotID # ";
if (  count($pilotPartsArray=split('_',$pilotID)) >1 ) {
	$serverID=$pilotPartsArray[0];
	$pilotID=$pilotPartsArray[1];
	// echo "@@ $serverID $pilotID@@";
}


if ($op=="main"){
    $op=$CONF_main_page;
}


if ($op=="sites") $page_title = 'przewodnik po startowiskach paralotniowych';
if ($op=="list_pilots") $page_title = 'lista pilotów paralotni portalu';
if ($op=="pilot_search") $page_title = 'wyszukiwarka pilotów paralotni portalu';
if ($op=="list_areas") $page_title = 'lista rejonów do latania paralotnią';
if ($op=="list_takeoffs"){
	 $page_title = 'Lista startowisk paralotniowych';
	 $page_keywords = "paralotnie, paragliding, flights, logs, track, igc, parapente, loty, startowiska, opisy, przewodnik";
	 $page_description = "Tabela z listą miejsc z których zgłoszono loty z podziałem na kraje i startowiska, liczbę lotów i rekordowe wyniki.";

         $board_config['meta_keywords']=$page_keywords;
         $board_config['meta_description']=$page_description; 
	 $board_config['meta_author']='https://leonardo.pgxc.pl';

	 $board_config['meta_ogTitle'] =  $page_title;
	 $board_config['meta_ogDescription'] = $page_description;
	 $board_config['meta_ogUrl'] = 'https://leonardo.pgxc.pl/startowiska/world/alltimes/brand:all,cat:0,club:all,pilot:0_0';
//	 $board_config['meta_ogUpdatedTime'] = $og_flightDate;
//	 $board_config['meta_ogLatitude'] = $flight->firstLat;
//	 $board_config['meta_ogLongtitude'] = $flight->firstLon;
	 $board_config['meta_ogType'] = 'sport';
//	 $board_config['meta_ogPublished'] = $og_flightDate;
//	 $board_config['meta_ogModified'] = $og_flightSubmission;
	 $board_config['meta_ogSiteName'] = 'Polski Serwer Leonardo';
	 $board_config['meta_ogImage'] = 'https://leonardo.pgxc.pl/templates/pgxc/tpl/leonardo_logo.gif';
         $board_config['meta_ogImageType'] = 'image/gif';
}
if ($op=="list_forecasts"){
	 $page_title = 'Prognozy dla startowisk paralotniowych';
	 $page_keywords = "paralotnie, paragliding, flights, logs, track, igc, parapente, loty, startowiska, opisy, przewodnik";
	 $page_description = "Strona zawierajaca informację o lotnych dniach w kolejnych pięciu dniach z podziałem na kraje i rejony.";

         $board_config['meta_keywords']=$page_keywords;
         $board_config['meta_description']=$page_description; 
	 $board_config['meta_author']='https://leonardo.pgxc.pl';

	 $board_config['meta_ogTitle'] =  $page_title;
	 $board_config['meta_ogDescription'] = $page_description;
	 $board_config['meta_ogUrl'] = 'https://leonardo.pgxc.pl/prognozy/world/alltimes/brand:all,cat:0,club:all,pilot:0_0';
//	 $board_config['meta_ogUpdatedTime'] = $og_flightDate;
//	 $board_config['meta_ogLatitude'] = $flight->firstLat;
//	 $board_config['meta_ogLongtitude'] = $flight->firstLon;
	 $board_config['meta_ogType'] = 'sport';
//	 $board_config['meta_ogPublished'] = $og_flightDate;
//	 $board_config['meta_ogModified'] = $og_flightSubmission;
	 $board_config['meta_ogSiteName'] = 'Polski Serwer Leonardo';
	 $board_config['meta_ogImage'] = 'https://leonardo.pgxc.pl/templates/pgxc/tpl/leonardo_logo.gif';
         $board_config['meta_ogImageType'] = 'image/gif';
}
if ($op=="list_flights") $page_title = 'lista zgłoszonych lotów';
if ($op=="pilot_profile_stats"){
// user total airtime
	$query="SELECT sum(DURATION) as total_time FROM $flightsTable  WHERE userID =".$pilotID;
	$res= $db->sql_query($query);
 	if($res > 0){
		$row = mysql_fetch_assoc($res);
		$og_userTotalAirtime=sec2Time($row['total_time'],1);
	}

// number of flights
	$query="SELECT count(*) as number_of_flights FROM $flightsTable  WHERE userID =".$pilotID;
	$res= $db->sql_query($query);
 	if($res > 0){
		$row = mysql_fetch_assoc($res);
		$og_userTotalLaunches=$row['number_of_flights'];
	}

// best flight
	$query="SELECT FLIGHT_KM as record_km, takeoffID FROM $flightsTable  WHERE userID =".$pilotID." ORDER by FLIGHT_KM DESC limit 1";
	$res= $db->sql_query($query);
 	if($res > 0){
		$row = mysql_fetch_assoc($res);
		$og_userRecord=formatDistance($row['record_km'],1);
	 	$wpInfo =new waypoint($row['takeoffID'],1);
	 	$wpInfo->getFromDB();
	 	$og_userRecordTakeoff = selectWaypointName($wpInfo->name,$wpInfo->intName,$wpInfo->countryCode);
	}


//	 $realName = getPilotRealName($pilotIDview,$serverIDview);
	 $realName = getPilotRealName($pilotID,$serverIDview);
	 $page_title = $realName.' - statystyki lotów' ;
         $page_keywords = "paralotnie, loty, statystyki";
         $page_description = "Strona ze statystykami lotów pilota paralotni: ".$realName;
         $board_config['meta_keywords']=$page_keywords;
         $board_config['meta_description']=$page_description;
         $board_config['meta_author']='https://leonardo.pgxc.pl';

         $board_config['meta_ogTitle'] =  $page_title;
	 $board_config['meta_ogDescription']= $realName." &#8759; &#8721; ".$og_userTotalLaunches." (".$og_userTotalAirtime.") &#8759; &#9812 ".$og_userRecord.' - '.$og_userRecordTakeoff;
         $board_config['meta_ogUrl'] = getLeonardoLink(array('op'=>'pilot_profile_stats','pilotIDview'=>$serverIDview.'_'.$pilotIDview)).'&pilotIDview=0_'.$pilotID;
//       $board_config['meta_ogUpdatedTime'] = $og_flightDate;
//       $board_config['meta_ogLatitude'] = $flight->firstLat;
//       $board_config['meta_ogLongtitude'] = $flight->firstLon;
         $board_config['meta_ogType'] = 'sport';
//       $board_config['meta_ogPublished'] = $og_flightDate;
//       $board_config['meta_ogModified'] = $og_flightSubmission;
         $board_config['meta_ogSiteName'] = 'Polski Serwer Leonardo';
	$dstUserMap=$CONF['mapUsersDir'].'/'.$pilotID.'.jpg';
        $board_config['meta_ogImage'] = 'https://files.leonardo.pgxc.pl/'.$dstUserMap;
        $board_config['meta_ogImageType'] = 'image/jpeg';
}

if ($op=="show_waypoint"){
	 $wpInfo =new waypoint($waypointIDview );
	 $wpInfo->getFromDB();
		 $query="SELECT MAX(FLIGHT_KM) as record_km, ID, userID FROM $flightsTable  WHERE takeoffID =".$waypointIDview." GROUP BY ID ORDER BY record_km DESC";
		 $res= $db->sql_query($query);
 		 if($res > 0){
                	$og_flightNum=mysql_num_rows($res);
			$row = mysql_fetch_assoc($res);
			$og_siteRecord=formatDistance($row['record_km'],1);
			
			$flightID=$row['ID']; 
			if ($flightID != ''){
				$flight=new flight();
  				if ( ! $flight->getFlightFromDB($flightID) ) {
        		//		echo "<br><div align='center'>No such flight exists</div><br><BR>";
        				return;
				}else{
					$og_siteChampion=$flight->userName;
					$og_siteChampionID=$flight->userID;
					$og_siteChampionFlightID=$flight->flightID;
				}
			}
		 }

	$query="SELECT sum(DURATION) as total_time FROM $flightsTable  WHERE takeoffID =".$waypointIDview;
	$res= $db->sql_query($query);
 	if($res > 0){
		$row = mysql_fetch_assoc($res);
		$og_siteTotalAirtime=sec2Time($row['total_time'],1);
	}
	$query="SELECT userID, count(*) as number_of_flights FROM $flightsTable  WHERE takeoffID =".$waypointIDview." group by userID order by number_of_flights DESC";
	$res= $db->sql_query($query);
 	if($res > 0){
		$row = mysql_fetch_assoc($res);
		$og_siteMaxLaunches=$row['number_of_flights'];
		$og_siteMaxLaunchesPilotID=$row['userID'];
	}
//highest total airtime

	$query="SELECT userID, sum(DURATION) as total_airtime FROM $flightsTable  WHERE takeoffID =".$waypointIDview." group by userID order by total_airtime DESC";
        $res= $db->sql_query($query);
        if($res > 0){
                $row = mysql_fetch_assoc($res);
                $og_siteMaxTotalAirtime=sec2Time($row['total_airtime']);
                $og_siteMaxTotalAirtimePilotID=$row['userID'];
        }

//highest altitude from takeoff
	$query="SELECT DISTINCT userID, (MAX_ALT-TAKEOFF_ALT) as maximum_gain, ID  FROM $flightsTable  WHERE takeoffID =".$waypointIDview." order by maximum_gain DESC limit 1";
        $res= $db->sql_query($query);
        if($res > 0){
                $row = mysql_fetch_assoc($res);
                $og_siteMaxGain=formatAltitude($row['maximum_gain']);
                $og_siteMaxGainPilotID=$row['userID'];
                $og_siteMaxGainFlightID=$row['ID'];
        }


	 $og_takeoffName = selectWaypointName($wpInfo->name,$wpInfo->intName,$wpInfo->countryCode);
         $page_title = $og_takeoffName.' - opis startowiska ';
	 $page_keywords = "paralotnie, paragliding, flights, logs, track, igc, parapente, loty, opis, przewodnik, ".$og_takeoffName.", guide";
	 $page_description = "Informacje na temat startowiska paralotniowego ".$og_takeoffName.": koordynaty GPS, ".$og_flightNum." zgłoszonych lotów i mapa ułatwiająca odnalezienie miejsca.";
	 $board_config['meta_keywords']=$page_keywords;
	 $board_config['meta_description']=$page_description;
 	 $board_config['meta_geo']=$wpInfo->lat.", ".(-1*$wpInfo->lon);
	 $board_config['meta_date_revision']=str_replace('-','',$wpInfo->modifyDate);
 	 $board_config['meta_author']='https://leonardo.pgxc.pl';

// map icons for social media
	$dst=$CONF['mapIconsDir'].'/'.$waypointIDview.'.jpg';
	$src='https://maps.googleapis.com/maps/api/staticmap?center='.$wpInfo->lat.",".(-1*$wpInfo->lon).'&zoom=11&size=800x600&markers=icon:http://bit.ly/PGICON32%7C|'.$wpInfo->lat.",".(-1*$wpInfo->lon).'&key='.$CONF_google_maps_api_key;
// if not exists then create one
	if (!file_exists($dst.".txt")){
		$result=file_put_contents($dst, file_get_contents($src));
		if ($result !== FALSE){
			file_put_contents($dst.".txt", "1");
		}
	}

	$board_config['meta_ogTitle'] = $page_title;
	$board_config['meta_ogDescription']=  "&#9872; ".$og_takeoffName." &#8759; &#8721; ".$og_flightNum." (".$og_siteTotalAirtime.") &#8759; &#9812 ".$og_siteChampion." - ".$og_siteRecord;
 	$board_config['meta_ogUrl'] = getLeonardoLink(array('op'=>'show_waypoint','waypointIDview'=>$waypointIDview));
 	$board_config['meta_ogUpdatedTime'] = $wpInfo->modifyDate;
 	$board_config['meta_ogLatitude'] = $wpInfo->lat (-1*$wpInfo->lon);
 	$board_config['meta_ogLongtitude'] =  (-1*$wpInfo->lon);
 	$board_config['meta_ogType'] = 'sport';
 	$board_config['meta_ogPublished'] = $wpInfo->modifyDate;
	$board_config['meta_ogModified'] = $wpInfo->modifyDate;
 	$board_config['meta_ogSiteName'] = 'Polski Serwer Leonardo';
        $board_config['meta_ogImage'] = 'https://files.leonardo.pgxc.pl/'.$dst;
        $board_config['meta_ogImageType'] = 'image/jpeg';
}
if ($op=="pilot_profile"){
	$page_title = 'profil pilota  - '.getPilotRealName($pilotIDview,0,0);
}
if ($op=="stats") $page_title = 'statystyki lotów zgłoszonych do portalu';
if ($op=="show_flight"){
 $flightID=makeSane($_REQUEST['flightID'],1);
 $flight=new flight();
  if ( ! $flight->getFlightFromDB($flightID) ) {
        echo "<br><div align='center'>No such flight exists</div><br><BR>";
        return;
  }
 $og_pilotName = $flight->userName;
 $og_takeoffName = getWaypointName($flight->takeoffID);
 $og_flightDate = $flight->DATE;
 $og_flightSubmission = $flight->dateAdded;
 $og_flightDistance = round(($flight->FLIGHT_KM)/1000,1);
 $og_flightDuration = gmdate("H:i:s",$flight->DURATION);
 $og_flightMeanSpeed = $flight->MEAN_SPEED;
 $og_flightMaxHeight = $flight->MAX_ALT;
 $page_title = 'lot paralotnią ze startowiska '.$og_takeoffName; //.' do '.getWaypointName($flight->landingID);
 $page_description = $gliderCatList[$flight->cat].". Dnia ".$og_flightDate." ".$og_pilotName." w czasie ".$og_flightDuration." wykonał lot na dystansie ".$og_flightDistance."KM (OLC) ze startowiska ".$og_takeoffName." - strona zawiera statystyki i wizualizację tego lotu.";
 $page_keywords = $gliderCatList[$flight->cat].", paragliding, flight, log, track, igc, parapente, ".$og_takeoffName.", ".$og_pilotName;

 $board_config['meta_description']=$page_description;
 $board_config['meta_keywords']=$page_keywords;
 $board_config['meta_author']=$og_pilotName;
 $board_config['meta_date_creation']=str_replace('-','',$og_flightDate); 
 $board_config['meta_date_revision']=substr(str_replace('-','',$og_flightSubmission),0,8); 
 $board_config['meta_geo']=$flight->firstLat.", ".$flight->firstLon;

 $board_config['meta_ogTitle'] = $og_pilotName." &#8226; ".$og_flightDate." &#8226; ".$og_flightDistance." km";
 $board_config['meta_ogDescription'] = $gliderCatList[$flight->cat]."  &#9971; ".$og_takeoffName." &#8759; &#8987; ".$og_flightDuration." &#8759; &#248; ".$og_flightMeanSpeed." km/h &#8759; &#8613; ".$og_flightMaxHeight." m n.p.m";
 $board_config['meta_ogUrl'] = getLeonardoLink(array('op'=>'show_flight','flightID'=>$flightID));
 $board_config['meta_ogUpdatedTime'] = $og_flightDate;
 $board_config['meta_ogLatitude'] = $flight->firstLat;
 $board_config['meta_ogLongtitude'] = $flight->firstLon;
 $board_config['meta_ogType'] = 'sport';
 $board_config['meta_ogPublished'] = $og_flightDate;
 $board_config['meta_ogModified'] = $og_flightSubmission;
 $board_config['meta_ogSiteName'] = 'Polski Serwer Leonardo';


$dst=$CONF['flightMapThumbsDir'].'/'.$flightID.'.jpg';

if (!file_exists($dst.".txt")){
        $jsondata = file_get_contents('.'.$flight->getJsonRelPath());
        $obj = json_decode(str_replace('var flightArray=','',$jsondata),true);
        $size=count($obj['lat']);
        $x=0;
        $path="weight:2%7Ccolor:0xff0000ff%7C";
	$step=1;
	if($size>200){
		$step=2;
	}
	if($size>400){
        $step=4;
    }
	if($size>800){
		$step=8;
	}

        while ($x<$size){
            $path.=round($obj['lat'][$x],4).','.round($obj['lon'][$x],4);
                $x=$x+$step;
                if ($x<$size){
                        $path.='%7C';
                }
        }

        $launch="".round($obj['lat'][1],4).','.round($obj['lon'][1],4);
        $landing="".round($obj['lat'][$size-1],4).','.round($obj['lon'][$size-1],4);
        $markers='icon:http://bit.ly/2uZ79WQ%7C'.$launch.'&markers=icon:http://bit.ly/2LJNOju%7C'.$landing;

        $src='https://maps.googleapis.com/maps/api/staticmap?size=800x600&markers='.$markers.'&path='.$path.'&key='.$CONF_google_maps_api_key;

        $result=file_put_contents($dst, file_get_contents($src));
        if ($result !== FALSE && $size>0){
            file_put_contents($dst.".txt","$size"." $src ".$flight->getJsonRelPath());
        }
}

	$board_config['meta_ogImage'] = $CONF['cdnURL'].'/'.$CONF['flightMapThumbsDir'].'/'.$flightID.'.jpg';
	$board_config['meta_ogImageType'] = 'image/jpeg';

/* $OG_PHOTOS=""; */
/*  if ($flight->hasPhotos) { */
/*         require_once dirname(__FILE__)."/CL_flightPhotos.php"; */

/*         $flightPhotos=new flightPhotos($flight->flightID); */
/*         $flightPhotos->getFromDB(); */

/*         // get geoinfo */
/*         //$flightPhotos->computeGeoInfo(); */

/* 	foreach ( $flightPhotos->photos as $photoNum=>$photoInfo) { */
/* 		$imgIconRel=$CONF['cdnURL'].$flightPhotos->getPhotoRelPath($photoNum).".icon.jpg"; */
/* //currently open graph accepts only one image that is why I'll leave it commented */
/* //		$OG_PHOTOS.=' <meta property="og:image" content="'.$imgIconRel.'" >'; */
/* 	} */
/* 		$board_config['meta_ogImage'] = ' <meta property="og:image" content="'.$imgIconRel.'" >'; */
/*  } */
}

if ($op=="area_show"){
    $areaID=makeSane($_GET['areaID'],0);
    require_once dirname(__FILE__)."/CL_area.php";
    $area=new area($areaID);
    $area->getFromDB();

    // number of takeoffs
    $query="SELECT count(*) as number_of_takeoffs FROM leonardo_areas_takeoffs where areaID=".$areaID;
    $res= $db->sql_query($query);
    if($res > 0){
        $row = mysql_fetch_assoc($res);
        $numberOfTakeoffs=$row['number_of_takeoffs'];
    }
    $areaName = $area->name;
    $page_title = 'Startowiska paralotniowe w rejonie: '.$areaName ;
    $page_keywords = "księga lotów, paralotnie, loty, statystyki";
    $page_description = $areaName." - ".$numberOfTakeoffs. " startowisk wraz z informacjami o dzisiejszym warunie";
    $board_config['meta_keywords']=$page_keywords;
    $board_config['meta_description']=$page_description;
    $board_config['meta_author']='https://leonardo.pgxc.pl';
    $board_config['meta_ogTitle'] =  $page_title;
    $board_config['meta_ogDescription']= $areaName." - ".$numberOfTakeoffs. " startowisk wraz z informacjami o dzisiejszym warunie";
    $board_config['meta_ogUrl'] = 'https://leonardo.pgxc.pl/rejon/'.$areaID;
    $board_config['meta_ogType'] = 'sport';
    $board_config['meta_ogSiteName'] = 'Polski Serwer Leonardo';
    $dstUserMap=$CONF['mapUsersDir'].'/rejon_'.$areaID.'.jpg';
    $board_config['meta_ogImage'] = 'https://leonardo.pgxc.pl/'.$dstUserMap;
    $board_config['meta_ogImageType'] = 'image/jpeg';

    $dst=$CONF['mapUsersDir'].'/rejon_'.$areaID.'.jpg';
    // if not exists then create one
    if (!file_exists($dst)){
        $query="select lat,lon from leonardo_waypoints where ID in (select takeoffID from leonardo_areas_takeoffs where areaID=".$areaID.")  group by ID";
        $res= $db->sql_query($query);
        $places="";
        if($res > 0){
            while ($row=mysql_fetch_assoc($res)){
                $places.=$row['lat'].",".(floatval($row['lon'])*-1)."|";
            }
        }

        $src='https://maps.googleapis.com/maps/api/staticmap?size=600x400&markers=color:red%7Csize:tiny|'.$places.'&key='.$CONF_google_maps_api_key;
        //      echo $src;
        $result=file_put_contents($dst, file_get_contents($src));
    }

}

if ($op=="index_full"){
    // users total airtime
    $query="SELECT sum(DURATION) as total_time FROM $flightsTable";
    $res= $db->sql_query($query);
    if($res > 0){
        $row = mysql_fetch_assoc($res);
        $og_userTotalAirtime=sec2Time($row['total_time'],1);
    }

    // number of flights
    $query="SELECT count(*) as number_of_flights FROM $flightsTable ";
    $res= $db->sql_query($query);
    if($res > 0){
        $row = mysql_fetch_assoc($res);
        $og_userTotalLaunches=$row['number_of_flights'];
    }

    // best flight
    $query="SELECT FLIGHT_KM as record_km, takeoffID, userID FROM $flightsTable  ORDER by FLIGHT_KM DESC limit 1";
    $res= $db->sql_query($query);
    if($res > 0){
        $row = mysql_fetch_assoc($res);
        $og_userRecord=formatDistance($row['record_km'],1);
        $og_userRecordID=$row['userID'];
        $wpInfo =new waypoint($row['takeoffID'],1);
        $wpInfo->getFromDB();
        $og_userRecordTakeoff = selectWaypointName($wpInfo->name,$wpInfo->intName,$wpInfo->countryCode);
    }


    //   $realName = getPilotRealName($pilotIDview,$serverIDview);
    $userRecordName = getPilotRealName($og_userRecordID,$serverIDview);
    $page_title = 'Polski Serwer Leonardo' ;
    $page_keywords = "księga lotów, paralotnie, loty, statystyki";
    $page_description = "Książka lotów online";
    $board_config['meta_keywords']=$page_keywords;
    $board_config['meta_description']=$page_description;
    $board_config['meta_author']='https://leonardo.pgxc.pl';

    $board_config['meta_ogTitle'] =  $page_title;
    $board_config['meta_ogDescription']= "Małe podsumowanie: &#8759; &#8721; ".$og_userTotalLaunches." zgłoszonych lotów &#8759; &#10710; (".$og_userTotalAirtime.") (h:m) &#8759; &#9812 ".$userRecordName.' - '.$og_userRecordTakeoff;
    $board_config['meta_ogUrl'] = 'https://leonardo.pgxc.pl/';
    $board_config['meta_ogType'] = 'sport';
    $board_config['meta_ogSiteName'] = 'Polski Serwer Leonardo';
    $dstUserMap=$CONF['mapUsersDir'].'/all.jpg';
    $board_config['meta_ogImage'] = 'https://files.leonardo.pgxc.pl/'.$dstUserMap;
    $board_config['meta_ogImageType'] = 'image/jpeg';

    $dst=$CONF['mapUsersDir'].'/all.jpg';
    // if not exists then create one
    if (!file_exists($dst)){
        $query="select ROUND(firstLat,2) as lat,ROUND(firstLon,2) as lon from leonardo_flights group by takeoffID";
        $res= $db->sql_query($query);
        $places="";
        if($res > 0){
            while ($row=mysql_fetch_assoc($res)){
                $places.=$row['lat'].",".$row['lon']."|";
            }
        }

        $src='https://maps.googleapis.com/maps/api/staticmap?size=800x600&markers=color:red%7Csize:tiny|'.$places.'&key='.$CONF_google_maps_api_key;
        //      echo $src;
        $result=file_put_contents($dst, file_get_contents($src));
    }

}

if ($op=="competition") $page_title = 'zestawienie lotów pilotów portalu';
if ($op=="show_flight" && $flightID==0) $op=$CONF_main_page;

if ($op=="login") {  // do some output buffering so that cookies can be set later on
	ob_start();
}

if ($op=="show_flight" ) {  // get the flight info now since we need to create meta tags
  $flightID+=0;
  $flight=0;
  if ($flightID>0) {
	  $flight=new flight();
	  if ( ! $flight->getFlightFromDB($flightID) ) {
		echo "<br><div align='center'>No such flight exists</div><br><BR>";
		return;  
	  }
  }	  
}

if ($opMode==3 || $opMode==4 || $opMode==6  || ($opMode==5 &&  $CONF_use_own_template ) )  { // stand alone , we use phpbb3 as standalone too	
	require_once dirname(__FILE__)."/GUI_header.php";
}
// phpnuke
if ($opMode==1) include("header.php");

?>

<link href="<?=$moduleRelPath."/templates/".$PREFS->themeName."/style.css"; ?>" rel="stylesheet" type="text/css">
<link href="<?=$moduleRelPath."/templates/".$PREFS->themeName."/width.css"; ?>" rel="stylesheet" type="text/css">
<style type="text/css">
<?php  if ( $print ||  $RUN['view']=='print'  ) {?>


.mainBodyTable {  
	border:0;
	margin-bottom:0px;
}
.bodyline , body {
   border:none;
   background:none;
}

.main_text a:link, a:active,
 a:visited, a:hover , .listTable a, .listTable a:visited {
text-decoration: none;
}

<?php  } else { ?>
.mainBodyTable {  
	border:0;
	border-left-color:#000000; border-left-style:solid; border-left-width:2px; 
	border-right-color:#000000; border-right-style:solid; border-right-width:2px; 
	margin-bottom:0px;
}

<?php } ?>
</style>

<?
if ($opMode==1) OpenTable();

$Theme =new Theme();
require_once dirname(__FILE__)."/BLOCKS_start.php";



$noClubDisplay=1;
if ($clubID) {
   require_once  dirname(__FILE__)."/CL_club.php";
   //$currentClub=new club($clubID);
   //$clubName=$currentClub->getAttribute("name");
   $clubName=$clubsList[$clubID]['desc'];
   $noClubDisplay=0;
} else {
	$clubName=_No_Club;
}

require_once dirname(__FILE__)."/MENU_menu.php";


//---------------------------------------------
// MAIN SWITCH
//---------------------------------------------
$LeoCodeBase=dirname(__FILE__);

if ( $RUN['view']=='print' && $RUN['view0']!='print0'  ) {
	if ($op=="competition" || $op=="comp" || $op=="stat_flights" || $op=='pilot_profile_stats') {
		
		if ($RUN['remote']=='remote') {
			$url="http://".$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"].'0';
			echo "START PDF\n";
			echo "PDF URL:$url\n";	
			echo "END PDF\n";
			//echo "Will make pdf out of $url<BR>";			
		} else {
			require_once dirname(__FILE__)."/CL_pdf.php";
			$url="http://".$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"].'0';
			echo "Wil make pdf out of $url<BR>";
			
			$pdfFile=leoPdf::createPDF($url,md5($url));
			if ($pdfFile) {				
				echo "<a href='".$moduleRelPath.'/'.$CONF['pdf']['tmpPathRel'].'/'.$pdfFile."' target='_blank'>PDF is ready</a>";
				
			//	echo "\n\n".$moduleRelPath.'/'.$CONF['pdf']['tmpPathRel'].'/'.$pdfFile;
			} else {				
				echo "ERROR: PDF creation failed";
			}
		}					
		
		exit;
	}
}
	
if ($op=="index_full") {
   	require $LeoCodeBase."/GUI_index_full.php";
}else if ($op=="index_help") {
	require $LeoCodeBase."/GUI_index_help.php";
} else if ($op=="index_news") {
	require $LeoCodeBase."/GUI_index_news.php";
// Clubs - areas admin
//--------------------------
} else if ($op=="club_admin") {
	require $LeoCodeBase."/GUI_club_admin.php";
} else if ($op=="area_admin") {
	require $LeoCodeBase."/GUI_area_admin.php";
} else if ($op=="admin_sites") {
	require $LeoCodeBase."/GUI_admin_sites.php";
// Listing output
//--------------------------
} else if ($op=="list_clubs") {
	require $LeoCodeBase."/GUI_list_clubs.php";
} else if ($op=="list_flights") {
	require $LeoCodeBase."/GUI_list_flights.php";
} else if ($op=="stat_flights") {
	require $LeoCodeBase."/GUI_stat_flights.php";
} else if ($op=="export_flights") {  // only for admin
	require $LeoCodeBase."/GUI_flights_export.php";
} else if ($op=="list_pilots" ) {
	require $LeoCodeBase."/GUI_list_pilots.php";
} else if ($op=="competition") {
    require $LeoCodeBase."/GUI_list_comp.php";
} else if ($op=="comp") {
    require $LeoCodeBase."/GUI_comp.php";
} else if ($op=="list_takeoffs") {
	require $LeoCodeBase."/GUI_list_takeoffs.php";
} else if ($op=="list_forecasts") {
	if ($userID>0 ) require $LeoCodeBase."/GUI_list_forecasts.php";
	else echo "<center><br><BR><span class='note'>"._You_are_not_login."</span><BR><BR></center>";
} else if ($op=="sites") {
	require $LeoCodeBase."/GUI_sites.php";
} else if ($op=="list_areas") {
	require $LeoCodeBase."/GUI_list_areas.php";
} else if ($op=="area_show") {
	require $LeoCodeBase."/GUI_area_show.php";
} else if ($op=="browser") {
	require $LeoCodeBase."/GUI_browser.php";

//--------------------------
// "Flight" related actions
//--------------------------
} else if ($op=="show_flight" ) {
	if ($RUN['view']=='print' ) {
		require $LeoCodeBase."/GUI_flight_show_print.php";	
	} else {
		require $LeoCodeBase."/GUI_flight_show.php";
	}
} else if ($op=="compare" ) { 	
	require $LeoCodeBase."/GUI_flight_compare.php";
} else if ($op=="compare3d" ) {
	$force3D=1;
	require $LeoCodeBase."/GUI_flight_compare.php";       
} else if ($op=="add_flight") {
	// add by Durval Henke www.xcbrasil.org 19/12/2008
    if($CONF_force_civlid==1 && !$civlID && 0)
		require $LeoCodeBase."/GUI_user_civl_search.php";
    else
		require $LeoCodeBase."/GUI_flight_add.php";

} else if ($op=="add_from_zip") {
	require $LeoCodeBase."/GUI_flight_add_from_zip.php";
} else if ($op=="delete_flight") {
	require $LeoCodeBase."/GUI_flight_delete.php";
} else if ($op=="edit_flight") {
	require $LeoCodeBase."/GUI_flight_edit.php";
}  else if ($op=="addTestFlightFromURL") {
	addTestFlightFromURL(urldecode ($_REQUEST[flightURL]) );
//--------------------------
// "Waypoints" related actions
//--------------------------
} else if ($op=="show_waypoint") {
	require $LeoCodeBase."/GUI_waypoint_show.php";
} else if ($op=="add_waypoint") {
    require $LeoCodeBase."/GUI_waypoint_add.php";
} else if ($op=="edit_waypoint") {
    require $LeoCodeBase."/GUI_waypoint_edit.php";
//--------------------------
// "User " related actions ( mostly used in standalone op ($op=3)
//--------------------------
} else if ($op=="users") {
	if ($opMode==3 ) require $LeoCodeBase."/GUI_admin_users_list.php";
} else if ($op=="login") { // for phpbb2, standalone, discuz, phpbb3
	$noFooterMenu=1;
	if ($opMode==2 || $opMode==3 || $opMode==4 || $opMode==6 ) require $LeoCodeBase."/GUI_login.php";
} else if ($op=="register") {
	if ($opMode==3) require $LeoCodeBase."/GUI_user_register.php";
	else echo "<BR><BR>Parameter Not used !!<BR>";
} else if ($op=="send_password") {
	// add by Durval Henke www.xcbrasil.org 19/12/2008
	require $LeoCodeBase."/GUI_user_send_password.php";

/*
} else if ($op=="change_password"){
  if ($userID>0)  require $LeoCodeBase."/GUI_user_change_password.php";
  // add by Durval Henke www.xcbrasil.org 19/12/2008
  else echo _You_are_not_login;
} else if ($op=="change_email") {
  if ($userID>0 || isset($_GET['rkey']) ) require $LeoCodeBase."/GUI_user_change_email.php";
  // add by Durval Henke www.xcbrasil.org 19/12/2008
  else echo _You_are_not_login;
  */
} else if ($op=="need_civlid") {
  if ($userID>0)  require $LeoCodeBase."/GUI_user_civl_search.php";
  // add by Durval Henke www.xcbrasil.org 19/12/2008
  else echo _You_are_not_login;
//--------------------------
// "Pilots" related actions
//--------------------------
} else if ($op=="pilot_search") {
	// require $LeoCodeBase."/GUI_pilot_search.php";
	// new code!
	require $LeoCodeBase."/GUI_pilot_find.php";
} else if ($op=="pilot_find") {
	require $LeoCodeBase."/GUI_pilot_find.php";
} else if ($op=="pilot_profile") {
	if ($userID>0 || $CONF_showProfilesToGuests ) require $LeoCodeBase."/GUI_pilot_profile.php";
	else echo "<center><br><BR><span class='note'>"._You_are_not_login."</span><BR><BR></center>";
} else if ($op=="pilot_profile_edit") {
	require $LeoCodeBase."/GUI_pilot_profile_edit.php";
} else if ($op=="pilot_olc_profile_edit") {
	require $LeoCodeBase."/GUI_pilot_olc_profile_edit.php";
} else if ($op=="pilot_profile_stats") {
	
	require $LeoCodeBase."/GUI_pilot_profile_stats.php";
} else if ($op=="pilot_flights") {
	require $LeoCodeBase."/GUI_pilot_flights.php";
} else if ($op=="user_prefs") {
	require $LeoCodeBase."/GUI_user_prefs.php";
//--------------------------
// Admin related actions
//--------------------------
} else if ($op=="admin") {
	require $LeoCodeBase."/GUI_admin.php";
} else if ($op=="admin_languages") {
	require $LeoCodeBase."/GUI_admin_update_languages.php";
} else if ($op=="admin_brands") {
	require $LeoCodeBase."/GUI_admin_brands.php";
} else if ($op=="admin_airspace") {
	require $LeoCodeBase."/GUI_admin_airspace.php";
} else if ($op=="admin_test") {
	require $LeoCodeBase."/GUI_admin_test.php";
} else if ($op=="admin_logs") {
	require $LeoCodeBase."/GUI_admin_logs.php";
} else if ($op=="admin_stats") {
	require $LeoCodeBase."/GUI_admin_stats.php";
} else if ($op=="admin_takeoffs") {
	require $LeoCodeBase."/GUI_admin_takeoffs.php";
} else if ($op=="admin_areas") {
	require $LeoCodeBase."/GUI_area_admin.php";
} else if ($op=="admin_takeoff_resolve") {
	require $LeoCodeBase."/GUI_admin_takeoff_resolve.php";
} else if ($op=="admin_duplicates") {
	require $LeoCodeBase."/GUI_admin_duplicates.php";
} else if ($op=="admin_pilot_map") {
	require $LeoCodeBase."/GUI_admin_pilot_map.php";
} else if ($op=="validation_review") {
	require $LeoCodeBase."/GUI_validation_review.php";
} else if ($op=="servers_manage") {
	require $LeoCodeBase."/GUI_servers_manage.php";
} else if ($op=="conf_htaccess") {
	require $LeoCodeBase."/GUI_conf_htaccess.php";
//--------------------------
//--------------------------
// Misc related actions
//--------------------------
} else if ($op=="filter") {
	require $LeoCodeBase."/GUI_filter.php";
} else if ($op=="rss_conf") {
	$noFooterMenu=1;
	require $LeoCodeBase."/GUI_rss_conf.php";
} else if ($op=="stats") {
	require $LeoCodeBase."/GUI_stats.php";
} else if ($op=="program_info") {
	require $LeoCodeBase."/GUI_program_info.php";
}

exitPage(0);

// END OF OUTPUT to the browser

function exitPage($exitNow=1){
   global $opMode,$noFooterMenu,$moduleRelPath,$PREFS,$CONF_use_own_template,$CONF;
   global $sqlQueriesTime ,$sqlQueriesNum,$sqlFetchTime,$sqlFetchNum;
   global $pageStart,$DBGlvl;
   global $RUN;


   echo "<br>";
   if (!$noFooterMenu ) {
   	 if ($RUN['view']!='print') {
		if(date("Y")==2018){
			$dateto="";
		}else{
			$dateto=date("Y")." - ";
		}
	 	echo "<br><center><font size='1'>Copyright © 2018 - ".$dateto." leonardo.pgxc.pl - <a target='_blank' href='https://leonardo.pgxc.pl/regulamin.pdf'>regulamin</a></font></center>";
   	 }
   }
   echo "</div>";

   $pageEnd=leo_getmicrotime();
   $pageTime=$pageEnd-$pageStart;
   DEBUG("MAIN",1,"PAGE CREATION: $pageTime secs<BR>");
   DEBUG_END();


   require_once dirname(__FILE__)."/BLOCKS_end.php";
	
   if ($opMode==1) {
		CloseTable();
		include("footer.php");
   } else if (0 && ($opMode==3 || $opMode==4 || $opMode==6 || ($opMode==5 &&  $CONF_use_own_template ))  ) {
		require_once dirname(__FILE__)."/GUI_footer.php";
   }  else if ($opMode==3 || $opMode==4 || $opMode==6 || ($opMode==5 &&  $CONF_use_own_template ) ) {
	    global $Ltemplate;
		$Ltemplate->set_filenames(array('overall_footer' => 'tpl/overall_footer.html'));
		$Ltemplate->assign_vars(array(
			'SIDE_BLOCKS_HTML' => $side_blocks_html,
			'CUSTOM_FOOTER_CODE'=> 	$CONF['footer']['custom_code'],
		) );
		$Ltemplate->pparse('overall_footer');
		
		// Close our DB connection.
		//$db->sql_close();

   } 
	if ($DBGlvl) {
		// db execution time

		echo "<div class='debugBoxTop'>";
		printf("DB query: <b>%.4f</b> secs in %d queries <hr>",$sqlQueriesTime ,$sqlQueriesNum);

		printf("DB fetch: <b>%.4f</b> secs in %d fetches <hr>",$sqlFetchTime,$sqlFetchNum);
		printf("DB Total: <b>%.5f</b> secs<hr>",($sqlQueriesTime + $sqlFetchTime )) ;
		printf("Page Total: <b>%.5f</b> secs<hr>",$pageTime) ;
		echo "</div>";
		// end db
	}

   statsLogger::Log($pageTime);

   if ($exitNow) exit;
}

?>
