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
// $Id: GUI_EXT_waypoint_details.php,v 1.16 2010/03/14 20:56:10 manolis Exp $                                                                 
//
//************************************************************************

 	require_once dirname(__FILE__)."/EXT_config_pre.php";
	require_once dirname(__FILE__)."/config.php";
 	require_once dirname(__FILE__)."/EXT_config.php";
	require_once dirname(__FILE__)."/CL_flightData.php";
	require_once dirname(__FILE__)."/FN_functions.php";	
	require_once dirname(__FILE__)."/FN_UTM.php";
	require_once dirname(__FILE__)."/FN_waypoint.php";	
	require_once dirname(__FILE__)."/FN_output.php";
	require_once dirname(__FILE__)."/FN_pilot.php";
	require_once dirname(__FILE__)."/FN_flight.php";
	require_once dirname(__FILE__)."/templates/".$PREFS->themeName."/theme.php";
	setDEBUGfromGET();
	require_once dirname(__FILE__)."/language/".CONF_LANG_ENCODING_TYPE."/lang-".$currentlang.".php";
	require_once dirname(__FILE__)."/language/".CONF_LANG_ENCODING_TYPE."/countries-".$currentlang.".php";
	
	if (! L_auth::isAdmin($userID)) {
		// return;
    }
?><head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?=$CONF_ENCODING?>">
  <style type="text/css">
  body, p, table,tr,td {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;}
  body {margin:0px}
  </style>
<script language="javascript" src="<?=$moduleRelPath?>/js/DHTML_functions.js"></script>
</head>
<?
	$waypointIDview=makeSane($_GET['wID'],1);

  $wpInfo =new waypoint($waypointIDview );
  $wpInfo->getFromDB();
  
//  $wpName= getWaypointName($waypointIDview);

  $wpName= selectWaypointName($wpInfo->name,$wpInfo->intName,$wpInfo->countryCode);
  $wpLocation = selectWaypointLocation($wpInfo->location,$wpInfo->intLocation,$wpInfo->countryCode);

  if ( L_auth::isAdmin($userID)  ) $opString="<a href='".
   	getLeonardoLink(array('op'=>'edit_waypoint','waypointIDedit'=>$waypointIDview))
	."'><img src='".$moduleRelPath."/img/change_icon.png' border=0 align=bottom></a>"; 
  $titleString=_Waypoint_Name." : ".$wpName." (".$countries[$wpInfo->countryCode].") &nbsp;";

//$opString="<a href='#' onclick=\"toggleVisible('takeoffID','takeoffPos',14,-20,0,0);return false;\">
//<img src='".$moduleRelPath."/templates/".$PREFS->themeName."/img/exit.png' border=0></a>";

  open_inner_table("<table class=main_text width=100% cellpadding=0 cellspacing=0><tr><td>".$titleString."</td><td align=right width=50><div align=right>".$opString."</div></td></tr></table>",605,"icon_pin.png");
  open_tr();
  echo "<td>";	
?>

<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>
 
<table width="100%" border="0" bgcolor="#EFEFEF" class=main_text>

  <tr>
    <td valign="middle">
      <table class="Box" width="100%"  align="center">
        <tr align="center" bgcolor="#D0E2CD">
          <td width="34%"><strong><? echo "<a href='".getDownloadLink(array('type'=>'kml_wpt','wptID'=>$waypointIDview))."'>"._Navigate_with_Google_Earth."</a>"; ?>
            </strong>
          <div align="center"></div></td>
          <td width="33%"><strong><? echo "<a rel='nofollow' href='http://maps.google.com/maps?q=".$wpName."&ll=". $wpInfo->lat.",".-$wpInfo->lon."&spn=1.535440,2.885834&t=h&hl=en' target='_blank'>"._See_it_in_Google_Maps."</a>"; ?>
            </strong>
          <div align="center"></div></td>
          <td width="33%"><strong><? echo "<a rel='nofollow' href='http://www.mapquest.com/maps/map.adp?searchtype=address&formtype=address&latlongtype=decimal&latitude=".$wpInfo->lat."&longitude=".-$wpInfo->lon."' target='_blank'>"._See_it_in_MapQuest."</a>"; ?>
            </strong>
          <div align="center"></div></td>
        </tr>
      </table>
<? if ($wpLocation || $wpInfo->description || $wpInfo->link) { ?>
	 <br>
      <table class="Box"  align="center" width="100%">
        <tr bgcolor="#49766D">
          <td colspan="3">             
              <div align="center" class="titleWhite"><strong><? echo _SITE_INFO ?></strong></div></td>
        </tr>
		<? if ($wpLocation) { ?>
        <tr bgcolor="#F2ECDB">
          <td width="10%"><? echo _SITE_REGION ?></td>
          <td valign="top" colspan="2"><? echo $wpLocation ?>&nbsp;</td>
        </tr>
		<? } ?>
<!-- extended description begins here -->
<?
$intNameUrl = $wpInfo->intName;
$intNameUrl = str_replace(" ","%20",$intNameUrl);
$json = file_get_contents($CONF['weatherapi'].'/spot/'.$intNameUrl);
$obj = json_decode($json);
$weatherResponse= $obj->status;
if ($weatherResponse==200){
                        $windForSpotGraphicsURL=$CONF['links']['baseURL'].$CONF['images']['directionsRel']."/kierunki_".str_replace(" ","",$wpInfo->intName).".png";?>
                        <td rowspan=7 width="150"  bgcolor="#F2ECDB"><img height="150" src="<? echo $windForSpotGraphicsURL ?>"> </td>
<? } ?>
        </tr>
                <? if ($wpInfo->link) { ?>
        <tr  bgcolor="#F2ECDB">
          <td width=180 class="col3_in"><? echo _SITE_LINK ?></td>
          <td valign="top"><a rel="nofollow" href='<? echo formatURL($wpInfo->link) ?>' target="_blank"><? echo formatURL($wpInfo->link) ?></a>&nbsp;</td>
        </tr>
                <? } ?>

<? if ($weatherResponse==200) {
        $windForSpotGraphics=$CONF['images']['directions']."/kierunki_".str_replace(" ","",$wpInfo->intName).".png";

        if (!is_file($windForSpotGraphics.".txt")) {
                $response = file_get_contents($CONF['weatherapi']."/windrose/".$intNameUrl);
                file_put_contents($windForSpotGraphics,$response);
                file_put_contents($windForSpotGraphics.".txt","ok");
        }

        ?>
  <? if(!is_array($obj->dirMin)) { ?>
        <tr  bgcolor="#F2ECDB">
          <td width=180 class="col3_in"><? echo _SI_winddir ?></td>
          <td valign="top" ><? echo $obj->dirMin."&deg - ".$obj->dirMax."&deg;" ?>&nbsp;</td>
        </tr>
  <? } else { ?>
        <tr bgcolor="white">
          <td width=180 class="col3_in"><? echo _SI_winddir ?></td>
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
        <tr bgcolor="#F2ECDB">
          <td width=180 class="col3_in"><? echo _SI_windspd ?></td>
          <td valign="top" ><? echo (($obj->spdMin)/2)." - ".(($obj->spdMax)/2)."m/s" ?>&nbsp;</td>
        </tr>
        <tr  bgcolor="#F2ECDB">
          <td width=180 class="col3_in"><? echo _SI_forecasts  ?></td>
          <td valign="top" ><? echo "<a target='_blank' href='https://www.windguru.cz/".$obj->windguruID."'>Windguru</a> <a target='_blank' href='https://www.windy.com/".$obj->lat."/".$obj->lon."'>Windy</a>" ?>&nbsp;</td>
        </tr>
        <tr bgcolor="#F2ECDB">
          <td width=180 class="col3_in"><? echo _SI_flyable_today ?></td>
          <td valign="top" ><? print_r(file_get_contents($CONF['weatherapi']."/isflyabletoday/".$intNameUrl)) ?>&nbsp;</td>
        </tr>
        <tr  bgcolor="#F2ECDB">
          <td width=180 class="col3_in"><? echo _SI_next_flyable_days ?></td>
          <td valign="top" ><? print_r(file_get_contents($CONF['weatherapi']."/flyabledays/".$intNameUrl)) ?>&nbsp;</td>
        </tr>
        <? if ($obj->links!="None"){ ?>
                <tr bgcolor="#F2ECDB">
                  <td  width=200 class="col3_in"><? echo _SI_useful_links ?></td>
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
   <? if ($weatherResponse==200) { ?>
	<tr bgcolor="#F2ECDB">
		  <td></td>
                  <td  width=200 class="col3_in"><? echo _FLYABLE_ESTIMATIONS ?></td>
		  <td colspan=2 valign="top">
			 <a target="_blank" href="<? echo getLeonardoLink(array('op'=>'list_detailed_forecasts','takeoff'=>$waypointIDview))?>"><button style="width:120px;height:32px"><? echo _SHORT_TERM ?></button></a>
	        <a target="_blank" href="<? echo getLeonardoLink(array('op'=>'list_forecasts','takeoff'=>$waypointIDview))?>"><button style="width:120px;height:32px"><? echo _LONG_TERM ?></button></a>
		  </td>

	</tr>
   <? } ?>
<? } ?>


<!-- extended description ends here-->

		<? if ($wpInfo->description) { ?>
        <tr bgcolor="#F2ECDB">
          <td width=200><? echo _SITE_DESCR ?></td>
          <td valign="top" colspan="2"><? echo $wpInfo->description ?>&nbsp;</td>
        </tr>
		<? } ?>
      </table>    
	  <? } ?>  
      <br>
      <table class="Box" width="558"  align="center">
        <tr >
          <td width="271" rowspan="4" valign="top"><img src="<? echo $moduleRelPath ?>/EXT_showWaypointGlobe.php?type=big&zoomFactor=2&lat=<? echo $wpInfo->lat ?>&lon=<? echo $wpInfo->lon ?>" ></td>
          <td bgcolor="#49766D"><span class="titleWhite"><b><? echo _FLIGHTS ?></b></span></td>
        </tr>
        <tr bgcolor="#EBE1C5">
          <td bgcolor="#EBE1C5" valign="top"><b><? echo _SITE_RECORD ?></b>:
              <?
	 $query="SELECT  MAX(MAX_LINEAR_DISTANCE) as record_km, ID FROM $flightsTable  WHERE takeoffID =".$waypointIDview." GROUP BY ID ORDER BY record_km DESC ";

	 $flightNum=0;
     $res= $db->sql_query($query);
     if($res > 0){
		$flightNum=mysql_num_rows($res);
		$row = mysql_fetch_assoc($res);

		echo "<a target='_blank' href='".getLeonardoLink(array('op'=>'show_flight','flightID'=>$row["ID"]))."'>".formatDistance($row['record_km'],1)."</a>";
	 } 


?>
		          <p> <strong><? echo "<a target='_blank'  href='".getLeonardoLink(array('op'=>'list_flights','takeoffID'=>$waypointIDview,'year'=>'0','month'=>'0','season'=>'0','pilotID'=>'0','country'=>'0','cat'=>'0'))."'>"._See_flights_near_this_point." [ ".$flightNum." ]</a>"; ?></strong></td>
        </tr>
        <tr bgcolor="#49766D">
          <td><div align="left" ></div>
              <div align="left" class="titleWhite"><b><? echo _COORDINATES ?></b></div></td>
        </tr>
        <tr bgcolor="#EBE1C5">
          <td width="271" bgcolor="#EBE1C5"><p><strong>lat/lon (WGS84):</strong><br>
		  <? 	echo $wpInfo->lat." , ".-$wpInfo->lon ;
				echo "<br>".$wpInfo->getLatMinDec()." , ".$wpInfo->getLonMinDec();
				echo "<br>".$wpInfo->getLatDMS()." , ".$wpInfo->getLonDMS();
				echo "<p>";
				list($UTM_X,$UTM_Y,$UTMzone,$UTMlatZone)=$wpInfo->getUTM();
				echo "<b>UTM:</b> $UTMzone$UTMlatZone X: ".floor($UTM_X)." Y: ".floor($UTM_Y);
		 ?>
		  </td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
  </tr>

</table>
<?
  echo "</td></tr>";
  close_inner_table();
?>
