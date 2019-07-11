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
// $Id: GUI_waypoint_show.php,v 1.15 2010/03/14 20:56:11 manolis Exp $                                                                 
//
//************************************************************************

  $wpInfo =new waypoint($waypointIDview );
  $wpInfo->getFromDB();

  $pageURL=$CONF['protocol']."://".$_SERVER['SERVER_NAME']."/startowisko/".$waypointIDview;
//  $wpName= getWaypointName($waypointIDview);

  $wpName= selectWaypointName($wpInfo->name,$wpInfo->intName,$wpInfo->countryCode);
  $wpLocation = selectWaypointLocation($wpInfo->location,$wpInfo->intLocation,$wpInfo->countryCode);

  if ( L_auth::isAdmin($userID)  ) $opString="<a href='".
  		getLeonardoLink(array('op'=>'edit_waypoint','waypointIDedit'=>$waypointIDview))
		."'><img src='".$moduleRelPath.	"/img/change_icon.png' border=0 align=bottom></a>"; 
  $titleString=_Waypoint_Name." : ".$wpName." (".$countries[$wpInfo->countryCode].") &nbsp;";
?>

<style type="text/css">
<!--
.titleText {font-weight: bold}
.col1 { background-color:#9FBC7F; }
.col2 { background-color:#BE8C80; }
.col3 { background-color:#7F91BF; }

.col1_in { background-color:#EEF3E7; }
.col2_in { background-color:#F8F3F2; }
.col3_in { background-color:#DAE5F0; }
-->
</style>

<?
 // open_inner_table("<table class=main_text width=100% cellpadding=0 cellspacing=0><tr><td>".$titleString."</td><td align=right width=50><div align=right>".$opString."</div></td></tr></table>",760,"icon_pin.png");

 $imgStr="<img src='$moduleRelPath/img/icon_pin.png' align='absmiddle'> ";

 openMain("<div style='width:90%;font-size:12px;clear:none;display:block;float:left'>$imgStr$titleString</div><div align='right' style='width:10%; text-align:right;clear:none;display:block;float:right' bgcolor='#eeeeee'>$opString</div>",0,'');


?> 
<table width="100%" border="0" bgcolor="#EFEFEF" class=main_text>

  <tr>
    <td valign="middle">
      <table width="98%"  align="center" class="Box">
        <tr align="center" bgcolor="#D0E2CD">
          <td bgcolor="#49766D" class="col1"><div align="center" class="titleWhite titleText"><? echo _FLIGHTS ?></div></td>
          <td bgcolor="#49766D" class="col2"><div align="center" class="titleWhite titleText"><? echo _COORDINATES ?></div></td>
          <td width="140" class="col3"><div align="center" class="titleWhite titleText">Navigate</div></td>
          <td width="40" class="col4" rowspan="4">
		<div align="center" class="titleWhite titleText">
  		 <a href="https://www.facebook.com/sharer.php?u=<?php echo $pageURL ?>" target="_blank"><img class="shareButtonSmall" src="https://leonardo.pgxc.pl/img/social/facebook.png" alt="Facebook" /></a>
                 <a href="https://twitter.com/share?url=<?php echo $pageURL ?>&amp;text=Ale%20miejscówka%20do%20latania!&amp;hashtags=pgxc,paralotnie,<?php echo $wpName;  ?>" target="_blank"><img class="shareButtonSmall" src="https://leonardo.pgxc.pl/img/social/twitter.png" alt="Twitter" /></a>
                 <a href="mailto:?Subject=Zobacz%20jaka%20miejscówa&amp;Body=<?php echo $wpName ?>%20-%20musimy%20się%20tam%20niebawem%20wybrać!%20 <?php echo $pageURL ?>"><img class="shareButtonSmall" src="https://leonardo.pgxc.pl/img/social/email.png" alt="Email" /></a>

		</div>
	  </td>
        </tr>
        <tr align="center" bgcolor="#D0E2CD">
          <td rowspan="3" class="col1_in">
<!-- beginning of stats section -->
	<table align="left" width="98%">
<tr><td>
<b><? echo _SITE_RECORD." (OLC)" ?></b></td><td>
	<?
	 $query="SELECT  MAX(MAX_LINEAR_DISTANCE) as record_km, ID FROM $flightsTable  WHERE takeoffID =".$waypointIDview." GROUP BY ID ORDER BY record_km DESC ";

	 $flightNum=0;
     $res= $db->sql_query($query);
     if($res > 0){
		$flightNum=mysql_num_rows($res);
		$row = mysql_fetch_assoc($res);
		echo "<a href='".getLeonardoLink(array('op'=>'pilot_profile_stats','pilotID'=>'0_'.$og_siteChampionID, 'year'=>'0','month'=>'0','takeoffID'=>'0','country'=>'0','cat'=>'0'))."'>".$og_siteChampion." </a></td><td><a href='".getLeonardoLink(array('op'=>'show_flight','flightID'=>$og_siteChampionFlightID))."'>".$og_siteRecord."</a></td></tr>";
	 } 

?>
<tr><td>
<strong><? echo _Altitude_gain_Record." </td><td> <a href='".getLeonardoLink(array('op'=>'pilot_profile_stats','pilotID'=>'0_'.$og_siteMaxGainPilotID, 'year'=>'0','month'=>'0','takeoffID'=>'0','country'=>'0','cat'=>'0'))."'> ".getPilotRealName($og_siteMaxGainPilotID,$serverIDview)." </a></td><td><a href='".getLeonardoLink(array('op'=>'show_flight','flightID'=>$og_siteMaxGainFlightID))."' >".$og_siteMaxGain."</a></td></tr>"; ?></strong>
<tr><td>
<strong><? echo _Maximum_Total_Airtime."</td><td> <a href='".getLeonardoLink(array('op'=>'pilot_profile_stats','pilotID'=>'0_'.$og_siteMaxTotalAirtimePilotID, 'year'=>'0','month'=>'0','takeoffID'=>'0','country'=>'0','cat'=>'0'))."'> ".getPilotRealName($og_siteMaxTotalAirtimePilotID,$serverIDview)." </a></td><td> ".$og_siteMaxTotalAirtime." </td></tr>"; ?></strong>
<tr><td>
<strong><? echo _Maximum_Number_Launches."</td><td> <a href='".getLeonardoLink(array('op'=>'pilot_profile_stats','pilotID'=>'0_'.$og_siteMaxLaunchesPilotID, 'year'=>'0','month'=>'0','takeoffID'=>'0','country'=>'0','cat'=>'0'))."'> ".getPilotRealName($og_siteMaxLaunchesPilotID,$serverIDview)." </a></td><td> <a href='".getLeonardoLink(array('op'=>'list_flights','takeoffID'=>$waypointIDview,'year'=>0,'month'=>'0','season'=>'0','pilotID'=>'0_'.$og_siteMaxLaunchesPilotID,'country'=>'0','cat'=>'0'))."'>".$og_siteMaxLaunches."</a></td></tr>"; ?></strong>
<tr><td>
<strong><? echo _All_flights_near_this_point."</td><td></td><td><a href='".getLeonardoLink(array('op'=>'list_flights','takeoffID'=>$waypointIDview, 'year'=>'0','month'=>'0','country'=>'0','cat'=>'0'))."'>[ ".$flightNum." ]</a></td></tr>"; ?></strong>
<tr><td>
<strong><? echo _Total_Site_Airtime."</strong></td><td></td><td> ".$og_siteTotalAirtime; ?></td></tr></table>
<!-- end of stats section -->

			</td>
          <td rowspan="3" class="col2_in"><p><strong>lat/lon (WGS84):</strong><br>
		  <? 	echo $wpInfo->lat." , ".-$wpInfo->lon ;
				echo "<br>".$wpInfo->getLatMinDec()." , ".$wpInfo->getLonMinDec();
				echo "<br>".$wpInfo->getLatDMS()." , ".$wpInfo->getLonDMS();
				echo "<p>";
				list($UTM_X,$UTM_Y,$UTMzone,$UTMlatZone)=$wpInfo->getUTM();
				echo "<b>UTM:</b> $UTMzone$UTMlatZone X: ".floor($UTM_X)." Y: ".floor($UTM_Y);
		 ?></td>
          <td class="col3_in"><div align="center"><strong><? echo "<a href='".getDownloadLink(array('type'=>'kml_wpt','wptID'=>$waypointIDview))."'>"._Navigate_with_Google_Earth."</a>"; ?></strong></div></td>
        </tr>
        <tr align="center" class="col3_in">
          <td><strong><? echo "<a href='https://maps.google.com/maps?q=".$wpName."&ll=". $wpInfo->lat.",".-$wpInfo->lon."&spn=1.535440,2.885834&t=h&hl=en' target='_blank'>"._See_it_in_Google_Maps."</a>"; ?></strong></td>
        </tr>
        <tr align="center" class="col3_in">
          <td><strong><? echo "<a href='https://www.mapquest.com/maps/map.adp?searchtype=address&formtype=address&latlongtype=decimal&latitude=".$wpInfo->lat."&longitude=".-$wpInfo->lon."' target='_blank'>"._See_it_in_MapQuest."</a>"; ?></strong></td>
        </tr>
      </table>
<? if ($wpLocation || $wpInfo->description || $wpInfo->link) { ?>
	 <br>
      <table class="Box"  align="center" width="98%">
        <tr >
          <td colspan="3" class="col3">             
          <div align="center" class="titleWhite titleText"><? echo _SITE_INFO ?></div></td>
        </tr>
		<? if ($wpLocation) { ?>
        <tr bgcolor="white">
          <td width=180 class="col3_in"> <? echo _SITE_REGION ?></td>
	  <td valign="top" ><? echo $wpLocation ?>&nbsp;</td>

<!-- data from weather raport -->
<?
$json = file_get_contents($CONF['weatherapi'].'/spot/'.$wpInfo->intName);
$obj = json_decode($json);
$weatherResponse= $obj->status;
if ($weatherResponse==200){
			$windForSpotGraphicsURL=$CONF['links']['baseURL'].$CONF['images']['directionsRel']."/kierunki_".str_replace(" ","",$wpInfo->intName).".png";?>
			<td rowspan=7 width="150"><img height="150" src="<? echo $windForSpotGraphicsURL ?>"> </td>
<? } ?>
        </tr>
		<? } ?>
		<? if ($wpInfo->link) { ?>
        <tr bgcolor="white">
          <td width=180 class="col3_in"><? echo _SITE_LINK ?></td>
          <td valign="top"><a href='<? echo formatURL($wpInfo->link) ?>' target="_blank"><? echo formatURL($wpInfo->link) ?></a>&nbsp;</td>
        </tr>
		<? } ?>

<? if ($weatherResponse==200) {
	$windForSpotGraphics=$CONF['images']['directions']."/kierunki_".str_replace(" ","",$wpInfo->intName).".png";
	
	if (!is_file($windForSpotGraphics.".txt")) {
		$response = file_get_contents($CONF['weatherapi']."/windrose/".$wpInfo->intName);
		file_put_contents($windForSpotGraphics,$response);
		file_put_contents($windForSpotGraphics.".txt","ok");
	}

	?>
  <? if(!is_array($obj->dirMin)) { ?>
        <tr bgcolor="white">
          <td width=180 class="col3_in">Użyteczne kierunki wiatru</td>
          <td valign="top" ><? echo $obj->dirMin."&deg - ".$obj->dirMax."&deg;" ?>&nbsp;</td>
	</tr>
  <? } else { ?>
        <tr bgcolor="white">
          <td width=180 class="col3_in">Użyteczne kierunki wiatru</td>
	  <td valign="top" ><? 
		$numberOfRanges=sizeOf($obj->dirMin);	
		for($x=0; $x<$numberOfRanges; $x++){
			print $obj->dirMin[$x]."&deg - ".$obj->dirMax[$x]."&deg;" ;
			if ($x!=$numberOfRanges-1) print ", ";
		 } 
		?>&nbsp;
	   </td>
	</tr>
  <? } ?>	
        <tr bgcolor="white">
          <td width=180 class="col3_in">Użyteczna siła wiatru</td>
          <td valign="top" ><? echo (($obj->spdMin)/2)." - ".(($obj->spdMax)/2)."m/s" ?>&nbsp;</td>
        </tr>
        <tr bgcolor="white">
          <td width=180 class="col3_in">Prognozy pogody</td>
          <td valign="top" ><? echo "<a target='_blank' href='https://www.windguru.cz/".$obj->windguruID."'>Windguru</a> <a target='_blank' href='https://www.windy.com/".$obj->lat."/".$obj->lon."'>Windy</a>" ?>&nbsp;</td>
        </tr>
        <tr bgcolor="white">
          <td width=180 class="col3_in">Czy dziś jest szansa na warun?</td>
          <td valign="top" ><? print_r(file_get_contents($CONF['weatherapi']."/isflyabletoday/".$wpInfo->intName)) ?>&nbsp;</td>
        </tr>
        <tr bgcolor="white">
          <td width=180 class="col3_in">Najbliższe lotne terminy</td>
          <td valign="top" ><a href="http://pgxc.pl/weather_forecast.pdf" target="_blank"><? print_r(file_get_contents($CONF['weatherapi']."/flyabledays/".$wpInfo->intName)) ?></a>&nbsp;</td>
        </tr>
	<? if ($obj->links!="None"){ ?>
		<tr bgcolor="white">
		  <td  width=200 class="col3_in">Przydatne linki</td>
		  <td colspan=2 valign="top" ><? 
			$links=$obj->links; 
			foreach($links as $key=>$value){
				$protocols = array();
				$protocols[0]='/http:\/\//';
				$protocols[1]='/https:\/\//';
				$domain = preg_replace($protocols,'',$value);
				$domain = preg_replace("/\/.*$/",'',$domain);
				$thumbnail = preg_replace("/\./",'',$domain);
				$pageURL=$CONF['protocol']."://".$_SERVER['SERVER_NAME']."/startowisko/".$waypointIDview;
				if ($domain!=$_SERVER['SERVER_NAME'] and $domain!=""){
					print "<a href='$value' target='_blank'><img alt='Informacje o $wpName na $domain' width='32' height='32' src='".$CONF['protocol']."://".$_SERVER['SERVER_NAME']."/img/ext/$thumbnail.png'></a> ";
				}
			}  ?>&nbsp;</td>
		</tr>
	<? } ?>
<? } ?>
		<? if ($wpInfo->description) { ?>
        <tr bgcolor="#49766D">
          <td colspan=3 class="col3"><div align="center" class="titleWhite  titleText"><? echo _SITE_DESCR ?>
          </div></td>
        </tr>
        <tr>
          <td colspan=3 valign="top"><? echo $wpInfo->description ?>&nbsp;</td>
        </tr>
		<? } ?>
      </table>    
	  <? } ?>  
      </td>
  </tr>
  <tr> 
    <td colspan="1">
	<div align="center">
		<?  list($browser_agent,$browser_version)=getBrowser();
			if ( $CONF_google_maps_api_key  ) { ?> 
		<iframe align="center"
		  SRC="<? echo getRelMainDir()."EXT_google_maps.php?wpID=".$wpInfo->waypointID."&wpName=".$wpInfo->intName."&lat=".$wpInfo->lat."&lon=".-$wpInfo->lon; ?>"
		  TITLE="Google Map" width="98%" height="400px"
		  scrolling="no" frameborder="0">
		Sorry. If you're seeing this, your browser doesn't support IFRAMEs.
		You should upgrade to a more current browser.
		</iframe>
		<? } else { ?>	
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><img src="<? echo $moduleRelPath ?>/EXT_showWaypointGlobe.php?type=small&lat=<? echo $wpInfo->lat?>&lon=<? echo $wpInfo->lon ?>" ></td>
            <td valign="top"><img src="<? echo $moduleRelPath ?>/EXT_showWaypointGlobe.php?type=big&zoomFactor=2&lat=<? echo $wpInfo->lat ?>&lon=<? echo $wpInfo->lon ?>" ></td>
          </tr>
        </table>      
		<? } ?> 
	</div>
	</td>
  </tr>
</table>
<?
  
  closeMain();
?>
