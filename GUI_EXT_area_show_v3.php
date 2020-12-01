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
// $Id: GUI_EXT_area_show_v3.php,v 0.1 2020/11/27 20:56:10 rgrubba Exp $                                                                 
//
//************************************************************************
	
	require_once dirname(__FILE__)."/EXT_config_pre.php";
	require_once dirname(__FILE__)."/config.php";
	$CONF_use_utf=1;
 	require_once dirname(__FILE__)."/EXT_config.php";
 	require_once dirname(__FILE__)."/CL_area.php";

	$areaID=makeSane($_GET['areaID'],0);
	$area=new area($areaID);
	$area->getFromDB();
	$GMapType='HYBRID';
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <title>Google Maps</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="<?=$moduleRelPath?>/js/google_maps/jquery.js" type="text/javascript"></script>
<script src="<?=$moduleRelPath?>/js/google_maps/gmaps_v3.js" type="text/javascript"></script>
<script src="<?=$moduleRelPath?>/js/google_maps/chart_v3.js" type="text/javascript"></script>
<script src="<?=$moduleRelPath?>/js/google_maps/polyline_v3.js" type="text/javascript"></script>
<script src="<?=$moduleRelPath?>/js/google_maps/markerclusterer_v3.js" type="text/javascript"></script>
<script src="<?=$moduleRelPath?>/js/google_maps/thermals_v3.js" type="text/javascript"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?=$CONF_google_maps_api_key ?>&libraries=&v=weekly" type="text/javascript"></script>
	<script src="<?=$moduleRelPath?>/js/AJAX_functions.js" type="text/javascript"></script>
	
	
	<script src="<?=$moduleRelPath?>/js/jquery.js" type="text/javascript"></script>
	<script src="<?=$moduleRelPath?>/js/facebox/facebox.js" type="text/javascript"></script>
	<link href="js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>

	<style type="text/css">
	<!--
		body{margin:0}
		
		table,td,tr { font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; }
		
div#floatDiv , div#takeoffInfoDiv
{

  width: 20%;
  float: right;
  border: 1px solid #8cacbb;
  margin: 0.5em;
  background-color: #dee7ec;
  padding-bottom: 5px;
 
   position :fixed;
   right    :0.5em;
   top      :13em;
   width    :15em;

   display:none;
}

div#takeoffInfoDiv {
	float :left;
	left : 0.5em;
}

div#floatDiv h3, div#takeoffInfoDiv h3
{
	margin:0;
	font-size:12px;
	background-color:#336699;
	color:#FFFFFF;
}

#sidebar {
  cursor: pointer;
  text-decoration:underline;
  color: #4444ff;
  padding-right:0;
}

#sidebar a, #sidebar a:visited,  #sidebar div { 
  color: #4444ff;
  margin-bottom:5px;
  padding:4px;
  padding-right:0;
  line-height:15px;
}

#takeoffHeader {
width:100%;
background-color:#FF9933;
font-size:12px;
margin-bottom:5px;
color:#FFFFFF;
padding:5px;
padding-right:0;
}

#takeoffHeader a { 
color:#FFFFFF;
padding:5px;
}
	-->
	
	</style>
  </head>
<body>

<div id ='floatDiv'>
	<h3>Results</h3>
	<div id ='resDiv'><BR /><BR /></div>
</div>

<table border="0" cellpadding="0" cellspacing="0" width="99%" style="margin-left:auto; margin-right:auto"> 
<tr>
  <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="100"><div id='takeoffHeader'><? echo _AREA ?></div></td>
      <td width="150"><div id='takeoffHeader'><b><?=$area->name?></b></td>
    </tr>
    <tr>
      <td ><?=$area->desc?></td>
	<td align="right" valign="bottom">
	<? require_once "FN_functions.php";?>
        <a target="_parent" href="<? echo getLeonardoLink(array('op'=>'list_forecasts','area'=>$areaID))?>"><button style="width:150px"><? echo _LONG_TERM_FORECASTS ?></button></a>
        <a target="_parent" href="<? echo getLeonardoLink(array('op'=>'list_detailed_forecasts','area'=>$areaID))?>"><button style="width:150px"><? echo _SHORT_TERM_FORECASTS ?></button></a>
</td>

      </tr>
	 <tr>
      <td colspan=2>&nbsp;</td>
    </tr>
  </table></td>
  </tr>
<tr>
	<td><div name="map" id="map" style="width:100%; height: 450px"></div></td>
		<td width = 150 valign="top" bgcolor="#F4F4EA" >
			<div id='takeoffHeader'><? echo _TAKEOFFS ?></div>
			<div id="sidebar"></div>		</td>
</tr>
<tr>
	<td  colspan=2>
		
		<div id="siteInfo"></div>	</td>
</tr>
    <tr><td colspan=2>
	<? require_once "FN_functions.php";?>
        <a target="_parent" href="<? echo getLeonardoLink(array('op'=>'list_areas')) ?>"> <-<? echo _MENU_AREA_GUIDE ?></a>
    </td></tr>
</table>
        <div style="text-align: right; font-size:12px;"><?
 	require_once dirname(__FILE__)."/FN_functions.php";
print_r(displayWeatherLegend());
?>
</div>

<script type="text/javascript">
//var id = <?php echo $areaID?>;
var currMarker;
var wpID=0;	
var site_list_html = "";
var takeoffPoint= new google.maps.LatLng(40, 22) ;
var iconUrl		= "https://maps.google.com/mapfiles/kml/pal2/icon13.png";
var shadowUrl	= "https://maps.google.com/mapfiles/kml/pal2/icon13s.png";		
var waypointid;	
var id;
var setTitle;
var takeoffMarkers=[];

function show_waypoint(id) {
                 jQuery.facebox(
                        function() {
                          jQuery.get("GUI_EXT_waypoint_info.php?wID="+id, function(data) {
                                  jQuery.facebox (data )
                                        }
                                )
                })
}

function openMarkerInfoWindow(jsonString) {
	var results= eval("(" + jsonString + ")");			
	var i=results.takeoffID;
	var html=results.html;
	var position;
	infowindow.setContent(html); 
	$.getJSON('EXT_takeoff.php?op=get_takeoff_coordinates&wpID='+i, function(data) {
		position = new google.maps.LatLng(data.lat, data.lon) ;
		infowindow.setPosition(position);
	});	
    	infowindow.open(map,currMarker);
}

	
function openSite(id)	{
		getAjax('EXT_takeoff.php?op=get_info&inPageLink=1&wpID='+id,null,openMarkerInfoWindow);
}
	
function initialize() {
    mapType=google.maps.MapTypeId.<?php echo $GMapType ?>;
		
    const uluru = { lat: -25.344, lng: 131.036 };

    var mapOptions= {
            zoom: 7,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_TOP
            },
            streetViewControl: false,
            mapTypeId: mapType,
	    center: uluru,
          };

    map = new google.maps.Map(document.getElementById('map'), mapOptions);


 //infowindow - po kliknieciu w element wyswietla info  
    infowindow = new google.maps.InfoWindow();
    google.maps.event.addListener(map, 'click', function() {
        infowindow.close();
    });	
    map.setCenter (new google.maps.LatLng(0,0) );

    addOverlays();    

}

function getZoomByBounds( map, bounds ){
  var MAX_ZOOM = map.mapTypes.get( map.getMapTypeId() ).maxZoom || 21 ;
  var MIN_ZOOM = map.mapTypes.get( map.getMapTypeId() ).minZoom || 0 ;

  var ne= map.getProjection().fromLatLngToPoint( bounds.getNorthEast() );
  var sw= map.getProjection().fromLatLngToPoint( bounds.getSouthWest() ); 

  var worldCoordWidth = Math.abs(ne.x-sw.x);
  var worldCoordHeight = Math.abs(ne.y-sw.y);

  //Fit padding in pixels 
  var FIT_PAD = 40;

  for( var zoom = MAX_ZOOM; zoom >= MIN_ZOOM; --zoom ){ 
      if( worldCoordWidth*(1<<zoom)+2*FIT_PAD < $(map.getDiv()).width() && 
          worldCoordHeight*(1<<zoom)+2*FIT_PAD < $(map.getDiv()).height() )
          return zoom;
  }
  return 0;
}

function createMarker(point,name,html,iconName) {    
	var iconUrl='';
	if (iconName=='start') {
		iconUrl= "https://maps.google.com/mapfiles/kml/pal4/icon61.png";
	} else {
		iconUrl		= "https://maps.google.com/mapfiles/kml/pal2/icon13.png";
	}			
	var marker = new google.maps.Marker({
		position: point,       
		map: map,
		icon: iconUrl,
		title: "",
	});

	gmarkers[markers_num] = marker;
	htmls[markers_num] = html;

	google.maps.event.addListener(marker, 'click', function(){
		wpID=name;
	  	getAjax('EXT_takeoff.php?op=get_info&inPageLink=1&wpID='+name,null,openMarkerInfoWindow);
	});
	  
	markers_num++;
	
	return marker;
}

function drawTakeoffs(jsonString){
	var results= eval("(" + jsonString + ")");		
	//$("#resDiv").html(results.waypoints.length);
	var bounds = new google.maps.LatLngBounds();
	site_list_html = '';
	description='';
	for(i=0;i<results.waypoints.length;i++) {	
		var takeoffPoint= new google.maps.LatLng(results.waypoints[i].lat, results.waypoints[i].lon) ;
		var iconUrl		= "https://maps.google.com/mapfiles/kml/pal2/icon13.png";
		var shadowUrl	= "https://maps.google.com/mapfiles/kml/pal2/icon13s.png";		
		waypointid = results.waypoints[i].id;	
		var takeoffMarker= createMarker(takeoffPoint,results.waypoints[i].id, results.waypoints[i].name,iconUrl);
		takeoffMarkers[takeoffPoint,results.waypoints[i].id] = takeoffMarker;		
		takeoffMarker.setMap(map);
		bounds.extend(takeoffPoint);
		site_list_html += '<a href="javascript:openSite(' + results.waypoints[i].id + ')">' + results.waypoints[i].name + '</a><br>';
	}	
	map.setZoom(getZoomByBounds(map,bounds));
	map.setCenter(bounds.getCenter());
	$("#sidebar").html(site_list_html);
}
function addOverlays(){
		getAjax('EXT_takeoff.php?op=getTakeoffsForArea&areaID=<?=$areaID?>',null,drawTakeoffs);
}
$(document).ready(function(){
	initialize();
});

</script>
</body>
</html>
