<?
//************************************************************************
// Leonardo XC Server, http://www.leonardoxc.net
//
// Copyright (c) 2019- Robert Grubba rgrubba@gmail.com
//
// This program is free software. You can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License.
//
// $Id: GUI_list_detailed_forecasts.php,v 1.0 2020/01/23 20:56:11 rgrubba $                                                                 
//
//************************************************************************
require_once dirname(__FILE__)."/config.php";
require_once dirname(__FILE__)."/CL_brands.php";
require_once dirname(__FILE__)."/CL_html.php";
require_once dirname(__FILE__)."/CL_user.php";
require_once dirname(__FILE__)."/FN_functions.php";
require_once dirname(__FILE__)."/FN_waypoint.php";

$dontShowDatesSelection=1;
$dontShowClubsSelection=1;
$dontShowManufacturers=1;
$dontShowCatSelection=1;
 
if(!isset($_SESSION[userID])){
	echo "<br><br><br><center><H2>Dostęp tylko dla zalogowanych użytkowników</h2></center>";
	echo "<br><br><br><center><h3>Strona z długoterminowymi informacjami o warunie dostępna jest jedynie dla zalogowanych użytkowników, proszę zalogować się i spróbować ponownie.</h3></center>";
	die();
}

  $sortOrder=makeSane($_REQUEST["sortOrder"]);
  if ( $sortOrder=="")  $sortOrder="CountryCode";

  $page_num=$_REQUEST["page_num"]+0;
  if ($page_num==0)  $page_num=1;

  if ($cat==0) $where_clause="";
  else $where_clause=" AND cat=$cat ";

  $area=makeSane($_REQUEST["area"]);
  if ( $sortOrder=="")  $sortOrder="0";

  $takeoff=makeSane($_REQUEST["takeoff"]);

  $queryExtraArray=array();
  $legend=_MENU_TAKEOFFS;
  
	// take care of exluding flights
	// 1-> first bit -> means flight will not be counted anywhere!!!
	$bitMask=1 & ~( $includeMask & 0x01 );
  if ($country) {
		$where_clause_country.=" AND  ".$waypointsTable.".countryCode='".$country."' ";
		//$legend.=" (".$countries[$country].") | ";
  }    

  if ($area) {
		$where_clause_country.=" AND ".$waypointsTable.".ID IN (select takeoffID from leonardo_areas_takeoffs where areaID = ".$area.") ";
  }
  
  if ($takeoff) {
		$where_clause_country.=" AND ".$waypointsTable.".ID = ".$takeoff." ";
		$queryGPS = "SELECT lat,lon FROM $waypointsTable WHERE ID=$takeoff";
		$resGPS = $db->sql_query($queryGPS);		
		$GPS = $db->sql_fetchrow($resGPS);
		$windy='<iframe width="100%" height="450" src="https://embed.windy.com/embed2.html?lat='.$GPS["lat"].'&lon='.-$GPS["lon"].'&zoom=10&level=surface&overlay=wind&menu=&message=&marker=&calendar=now&pressure=true&type=map&location=coordinates&detail=true&detailLat='.$GPS["lat"].'&detailLon='.-$GPS["lon"].'&metricWind=m%2Fs&metricTemp=%C2%B0C&radarRange=-1" frameborder="0"></iframe>';

   		$db->sql_freeresult($resGPS);
  }
  $sortDescArray=array("countryCode"=>_DATE_SORT, "FlightsNum"=>_NUMBER_OF_FLIGHTS, "max_distance"=>_SITE_RECORD_OPEN_DISTANCE  );
 
  $sortDesc=$sortDescArray[ $sortOrder];
  $ord="DESC";
  if ($sortOrder =='CountryCode' || $sortOrder =='intName' )   $ord="ASC";

  $sortOrderFinal=$sortOrder;
  
  	//----------------------------------------------------------
	// Now the filter
	//----------------------------------------------------------		
	$filter_clause=$_SESSION["filter_clause"];
	// echo $filter_clause;
	if ( strpos($filter_clause,$pilotsTable) )  $pilotsTableQuery=1;
	if ( strpos($filter_clause,$waypointsTable) )  $countryCodeQuery=1;		
	$where_clause.=$filter_clause;
	//----------------------------------------------------------	

	if ($pilotsTableQuery  && !$pilotsTableQueryIncluded  ){
		$where_clause.="  AND $flightsTable.userID=$pilotsTable.pilotID AND $flightsTable.serverID=$pilotsTable.serverID  ";	
		$extra_table_str.=",$pilotsTable ";
	}	

 $where_clause.=$where_clause_country;
//-----------------------------------------------------------------------------------------------------------

	$query="SELECT DISTINCT ID, ID as takeoffID, name, intName, countryCode, lon as FlightsNum, lat as max_distance 
  			FROM $waypointsTable $extra_table_str
  			WHERE countryCode in ('PL','DK','NL','PT','DE','CZ','FR','ES','SL','IT','HR','SK') ".$where_clause." 
			GROUP BY intName ORDER BY $sortOrderFinal ".$ord.",max_distance DESC";	
//    echo $query;
	$res= $db->sql_query($query);		
    if($res <= 0){
		echo "no takeoffs found<br>";
		return ;
    }
	$legendRight="";
   if (0) echo  "<div class='tableTitle shadowBox'>
   <div class='titleDiv'>$legend</div>
   <div class='pagesDiv'>$legendRight</div>
   </div>" ;
	require_once dirname(__FILE__)."/MENU_second_menu.php";

//	echo "<center><div class='loader'></div></center>";
   echo "<div class='list_header'>
				<div class='list_header_r'></div>
				<div class='list_header_l'></div>
				<h1>$legend</h1>
				<div class='pagesDiv'>$legendRight</div>
			</div>";
			
  listTakeoffs($res,$legend,$queryExtraArray,$sortOrder,$takeoff,$windy);

?>
<script type="text/javascript" src="<?=$moduleRelPath ?>/js/tipster.js"></script>
<? echo makeTakeoffPopup(); ?>
<?
function printHeaderTakeoffs($width,$sortOrder,$fieldName,$fieldDesc,$queryExtraArray,$colspan,$rowspan) {
  global $moduleRelPath ,$Theme;
  $alignClass="";

  if ($width==0) $widthStr="";
  else  $widthStr="width='".$width."'";

  if ($fieldName=="intName") $alignClass="alLeft";
  if ($colspan>0){
		$col="colspan='".$colspan."'";
	}else{
		$row="rowspan='2'";
	}
  if ($rowspan>0){
	$row = "rowspan='".$rowspan."'";
  }
  if ($fieldName=="CountryCode") $alignClass="takeoffCountry";
  if ($fieldName=="FlightsNum") $alignClass="takeoffFlights";
  if ($fieldName=="max_distance") $alignClass="takeoffMaxDistance";
  if ($sortOrder==$fieldName) { 
   echo "<td $widthStr $col $row $align class='SortHeader activeSortHeader $alignClass'>
			$fieldDesc<img src='$moduleRelPath/img/icon_arrow_down.png' border=0  width=10 height=10></div>
		</td>";
  } else {  
	if ($sortOrder=="none"){
	   echo "<td $widthStr $col $row $align  class='SortHeader $alignClass'>$fieldDesc</td>";
	}else{
	   echo "<td $widthStr $col $row $align class='SortHeader $alignClass'><a href='".
	   		getLeonardoLink(array('op'=>'list_detailed_forecasts','sortOrder'=>$fieldName)+$queryExtraArray)
			."'>$fieldDesc</td>";
	}
   } 
}

function listTakeoffs($res,$legend, $queryExtraArray=array(),$sortOrder="CountryCode",$takeoff,$windy) {
   global $db,$Theme, $takeoffRadious, $userID, $moduleRelPath;
   global $PREFS;
   global $page_num,$pagesNum,$startNum,$itemsNum;
   global $currentlang,$nativeLanguage, $countries;
	 
   
   $headerSelectedBgColor="#F2BC66";

  ?>
  <table class='listTable' width="100%" cellpadding="2" cellspacing="0">
  <tr>
  	<td rowspan="2" width="25" class='SortHeader hideOnExtraSmall'><? echo _NUM ?></td>
 	<?
		printHeaderTakeoffs(0,$sortOrder,"CountryCode",_COUNTRY,$queryExtraArray,1,2) ;
		printHeaderTakeoffs(0,$sortOrder,"intName",_TAKEOFF,$queryExtraArray,1,2) ;
		$currentHour=date('H');
		if ($currentHour<17){
				printHeaderTakeoffs(0,"none","today",date('d/m'),$queryExtraArray,3,1) ;
				printHeaderTakeoffs(0,"none","tomorrow",date('d/m',strtotime(' +1 day')),$queryExtraArray,3,1) ;
				printHeaderTakeoffs(0,"none","+2 days",date('d/m',strtotime(' +2 day')),$queryExtraArray,3,1) ;
		}else{	
				printHeaderTakeoffs(0,"none","tomorrow",date('d/m',strtotime(' +1 day')),$queryExtraArray,3,1) ;
				printHeaderTakeoffs(0,"none","+2 days",date('d/m',strtotime(' +2 day')),$queryExtraArray,3,1) ;
				printHeaderTakeoffs(0,"none","+3 days",date('d/m',strtotime(' +3 day')),$queryExtraArray,3,1) ;
		}
		printHeaderTakeoffs(0,"none","forecast links","Prognozy",$queryExtraArray,1,2) ;
	?>
	</tr>
	<tr>
	<?
		printHeaderTakeoffs(0,"none","","<11",$queryExtraArray,0) ;
		printHeaderTakeoffs(0,"none","","11-15",$queryExtraArray,0) ;
		printHeaderTakeoffs(0,"none","",">15",$queryExtraArray,0) ;
		printHeaderTakeoffs(0,"none","","<11",$queryExtraArray,0) ;
		printHeaderTakeoffs(0,"none","","11-15",$queryExtraArray,0) ;
		printHeaderTakeoffs(0,"none","",">15",$queryExtraArray,0) ;
		printHeaderTakeoffs(0,"none","","<11",$queryExtraArray,0) ;
		printHeaderTakeoffs(0,"none","","11-15",$queryExtraArray,0) ;
		printHeaderTakeoffs(0,"none","",">15",$queryExtraArray,0) ;

	?>
	</tr>
<tr></tr>
<?
   	$currCountry="";
   	$i=1;
	while ($row = $db->sql_fetchrow($res)) {  
		$takeoffName=selectWaypointName($row["name"],$row["intName"],$row["countryCode"]);	
		$sortRowClass="l_row1";
		$i++;
		$intNameUrl = $row["intName"];
		$intNameUrl = str_replace(" ","%20",$intNameUrl);
		$CONF["weatherapi"]='http://weather:8080';
		$existsInWeatherRaport = file_get_contents($CONF["weatherapi"].'/isdefined/'.$intNameUrl);
		if ($existsInWeatherRaport!="") {
if ( $countries[$row["countryCode"]] != $currCountry || $sortOrder!='CountryCode' ) {
                        $currCountry=$countries[$row["countryCode"]] ;
                        $country_str= "<a href='".getLeonardoLink(
                                        array('op'=>'list_flights','country'=>$row["countryCode"],'takeoffID'=>'0') )
                                        ."'>".$currCountry."</a>";

                        if ($sortOrder=='CountryCode') $sortRowClass="l_row2";
                        else $sortRowClass=($i%2)?"l_row1":"l_row2";
                } else {
                        $country_str="&nbsp;";
                }
			echo "<TR class='$sortRowClass'>";	
			echo "<TD class='hideOnExtraSmall'>".($i-1+$startNum)."</TD>";
			echo "<TD class='takeoffCountry'>$country_str</TD>";
			$takeoffNameSafe=str_replace("'","\'",$takeoffName);
			$takeoffNameSafe=str_replace('"','\"',$takeoffNameSafe);
			$takeoffNameSafe=htmlspecialchars($takeoffName); 

			$takeoffName=showWaypointDesciptionIcon($row["takeoffID"])." ".$takeoffName;

			echo "<TD class='alLeft'><div align=left id='t_$i'>";
			echo "<a href=\"javascript:takeoffTip.newTip('inline', 0, 13, 't_$i', 250, '".$row["takeoffID"]."','".str_replace("'","\'",$takeoffNameSafe)."')\"  onmouseout=\"takeoffTip.hide()\">$takeoffName</a>";
			
			echo "</div></TD>";
			
			echo file_get_contents($CONF["weatherapi"].'/forecastforthreedays/'.$intNameUrl);
			echo "<TD>".file_get_contents($CONF["weatherapi"].'/forecastlinks/'.$intNameUrl)."</TD>";
			echo "</TR>";
	}

  if ($takeoff){
	echo "<tr><th colspan=13>Prognoza z windy.com</th></tr>";
	echo "<tr><td colspan=13>$windy</td></tr>";
  }
   }     

	echo "</table>";
?>
           <div style="text-align: right"><?
		print_r(displayWeatherLegend());
?>
</div>
<?
   $db->sql_freeresult($res);
}

?>
