<?
//************************************************************************
// Leonardo XC Server, http://www.leonardoxc.net
//
// Copyright (c) 2020 by Robert Grubba
//
// This program is free software. You can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License.
//
// $Id: EXT_google_maps_v3.php,v 1.7 2020/11/18 09:45:24 rgrubba Exp $                                                                 
//
//************************************************************************

 	require_once dirname(__FILE__)."/EXT_config_pre.php";
	require_once dirname(__FILE__)."/config.php";
	$CONF_use_utf=1;
 	require_once dirname(__FILE__)."/EXT_config.php";

	require_once dirname(__FILE__)."/CL_flightData.php";
	require_once dirname(__FILE__)."/FN_functions.php";	
	require_once dirname(__FILE__)."/FN_waypoint.php";	
	require_once dirname(__FILE__)."/FN_output.php";
	require_once dirname(__FILE__)."/FN_pilot.php";
	//	setDEBUGfromGET();

	$wpLon=makeSane($_GET['lon'],1);
	$wpLat=makeSane($_GET['lat'],1);
	$wpName=makeSane($_GET['wpName']);
	$wpID=makeSane($_GET['wpID'],1);
	
	$takeoffID=$wpID;
	$flightID='310';
	$trackPossibleColors=array( "FF0000","00FF00","0000FF","FFFF00","FF00FF","00FFFF","EF8435","34A7F0","33F1A3","9EF133","808080");
	$isAdmin=L_auth::isAdmin($userID) ;


	
	$flightIDstr=$flightID;
	$flightsListTmp=explode(",",$flightIDstr);
	$flightsList=array();
	$i=1;
	$flightsList[]=$flightID;
/*	foreach($flightsListTmp as $flightID) {
		$flightID+=0;
		if ($flightID) $flightsList[]=$flightID;
		$i++;
		if ($i>20) break;
	} 
*/	$flightsNum=count($flightsList);
	sort($flightsList);
	
	if ( $flightsNum==1) {
		$flight=new flight();
		$flight->getFlightFromDB($flightsList[0],0);
		

		$isAdmin=L_auth::isAdmin($userID) || $flight->belongsToUser($userID);		
		$trackPossibleColors=array( "AB7224", "3388BE", "FF0000","00FF00","0000FF","FFFF00","FF00FF","00FFFF","EF8435","34A7F0","33F1A3","9EF133","808080");
	} else {
		
		$title1=_Altitude.' ('.(($PREFS->metricSystem==1)?_M:_FT).')';
		$trackPossibleColors=array( "FF0000","00FF00","0000FF","FFFF00","FF00FF","00FFFF","EF8435","34A7F0","33F1A3","9EF133","808080");
		$isAdmin=L_auth::isAdmin($userID) ;
	}

	$colotStr='';
	foreach ($trackPossibleColors as $color) {
		if ($colotStr) $colotStr.=',';
		$colotStr.=" '#$color'";		
	}
	
	$flightListStr='';
	foreach ($flightsList as $f) {
		if ($flightListStr) $flightListStr.=',';
		$flightListStr.="$f";		
	}
	

	/*
    ROADMAP displays the normal, default 2D tiles of Google Maps.
    SATELLITE displays photographic tiles.
    HYBRID displays a mix of photographic tiles and a tile layer for prominent features (roads, city names).
    TERRAIN displays physical relief tiles for displaying elevation and water features (mountains, rivers, etc.).
	*/	
	# martin jursa 22.06.2008: enable configuration of map type
	$GMapType='HYBRID';
/*	if ( in_array( $CONF['google_maps']['default_maptype'],
			 array('ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN','G_SATELLITE_3D_MAP'))) {
		$GMapType= $CONF['google_maps']['default_maptype'];
	}
*/
	if ( $CONF_airspaceChecks && $isAdmin) { 
		$airspaceCheck=1;	
	} else {
		$airspaceCheck=0;
	}
	
	// $isAdmin=1;
	// $airspaceCheck=1;
	// $CONF['thermals']['enable'] =1;
	// $CONF['airspace']['enable'] =1;
	
	// use the google earth plugin directly, not the hack
	$useGE=1;	 
	$is3D=$_GET['3d']+0;
			
	if ($CONF_google_maps_api_key) {
		$googleApiKeyStr="&key=$CONF_google_maps_api_key";
	} else {
		$googleApiKeyStr='';
	}
	
	if ($is3D) {
		 $CONF['thermals']['enable'] =0;
		 $airspaceCheck=0;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Takeoff Area</title>
<link rel='stylesheet' type='text/css' href='<?=$themeRelPath?>/css/google_maps_v3.css' />

<!-- sprites-->
<style type="text/css">
<!--
img.brands { background: url(<?=$moduleRelPath?>/img/sprite_brands.png) no-repeat left top; }
img.fl {   background: url(<?=$moduleRelPath?>/img/sprite_flags.png) no-repeat left top ; }
img.icons1 {   background: url(<?=$moduleRelPath?>/img/sprite_icons1.png) no-repeat left  top ; }

#control2d {
	position:absolute;
	top:5px;
	right:10px;
	dislay:block;
}

.control2dBig {
	position:absolute;
	top:5px;
	right:10px;
	dislay:block;
	font-size:25px;
	font-weight:bold;
	padding:10px;
}
#distanceDiv {
	position:absolute;
	top:5px;
	right:100px;
	dislay:block;
}

.solidBackground , #placeDetails.solidBackground , #trackDetails.solidBackground {
 background:#565656; 
 border-radius: 0;  
 -moz-border-radius: 0;  
 -webkit-border-radius: 0;  
}

#placeDetails.solidBackground {
	bottom:95px;
}
#trackDetails.solidBackground {
	bottom:65px;
}

#overlay {
	position:fixed; 
	top:0;
	left:0;
	width:100%;
	height:100%;
	background:#000;
	opacity:0.5;
	filter:alpha(opacity=50);
}

#modal {
	position:absolute;
	background:url(tint20.png) 0 0 repeat;
	background:rgba(0,0,0,0.2);
	border-radius:14px;
	padding:8px;
}

-->
</style>
<link rel="stylesheet" type="text/css" href="<?=$moduleRelPath?>/templates/<?=$PREFS->themeName?>/sprites.css">
 
<? if ( $is3D ) { ?> 
<? } else { ?>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=geometry<?php echo $googleApiKeyStr ?>" type="text/JavaScript"></script>
<? } ?>

<script src="<?=$moduleRelPath?>/js/google_maps/jquery.js" type="text/javascript"></script>

<script src="<?=$moduleRelPath?>/js/google_maps/gmaps_v3.js" type="text/javascript"></script>
<script src="<?=$moduleRelPath?>/js/google_maps/chart_v3.js" type="text/javascript"></script>
<script src="<?=$moduleRelPath?>/js/google_maps/polyline_v3.js" type="text/javascript"></script>

<? if ( $CONF['thermals']['enable']  ) { 
	// http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclustererplus/docs/reference.html
?>
<script src="<?=$moduleRelPath?>/js/google_maps/markerclusterer_v3.js" type="text/javascript"></script>
<script src="<?=$moduleRelPath?>/js/google_maps/thermals_v3.js" type="text/javascript"></script>
<? } ?>
<? if ($CONF['airspace']['enable']) { ?>
<script src="<?=$moduleRelPath?>/js/google_maps/airspace_v3.js" type="text/javascript"></script>
<? } ?>
 <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?=$moduleRelPath?>/js/flot/excanvas.min.js"></script><![endif]-->
<script src="<?=$moduleRelPath?>/js/flot/jquery.flot.js"></script>
<script src="<?=$moduleRelPath?>/js/flot/jquery.flot.resize.js"></script>
<script src="<?=$moduleRelPath?>/js/flot/jquery.flot.crosshair.js"></script>

</head>
<body>

<div id='chartDiv' style="display:none">
	<div id="chart" class="chart"></div>  			 
</div>

<div style='display:none;'>
	<div id='control3d' class='controlButton'>
		<div class='controlButtonInner'>3D View</div>
	</div>
	<div id='controlSkyways' class='controlButton skywaysButton'>
		<div class='controlButtonInner'>Skyways</div>
	</div>
</div>
	

<div id='control2d' class='controlButton' style='display:none;'>
	<div class='controlButtonInner'>2D View</div>
</div>
	
<div id='distanceDiv' class='controlButton' style='display:none;'>
<strong>Distance: </strong><span id="distance">N/A</span>
</div>


<div id='mapDiv' style="height:100%">  


	<div id='map'></div>  
	
	<div id='trackDetails' style="display:none">
	  
		<div id='trackCompareFinder'>
			<div id="trackCompareFinderHeader">
			<?php  echo _Find_and_Compare_Flights?>  
			</div> 
			<div id="trackCompareFinderHeaderExpand" > 				
				<div id="trackCompareFinderHeaderClose">
				<?php  echo _Close ?>
				</div>			
				<div id="trackCompareFinderHeaderAct">
				<?php  echo _Compare_Selected_Flights ?>
				</div>  
			</div>
			<div id="trackCompareFinderList">
				<div id="trackFinderTplMulti" class='trackInfoDisplay'>
				 	<div class="trackDisplayItem">
				 		<div class='trackListStr date'></div>
				 		<div class='trackListStr score'>-</div>	 			 	
						<div class='trackListStr info'>-</div>
						<div class='trackListStr glider'>-</div>
						<div class='trackListStr name'>-</div>	 	
						<div class='trackListStr tick'></div>	 		
				 	</div>
				</div>
			</div>
			<div id="trackCompareFinderHeaderMore">
				<?php echo _More ?>...
			</div>
		</div>
		
		<div id="trackInfoTplMulti" class='trackInfoDisplay'>
		 	<div class="trackDisplayItem">
		 		<div class='trackStr color'></div>
		 		<div class='trackStr name'>-</div>	 			 	
				<div class='trackStr alt'>-</div>
				<div class='trackStr speed'>-</div>
		 		<div class='trackStr vario'>-</div>
		 	</div>
		</div>
	</div>


	<div id='placeDetails'>  
	<div style="display:block; display:none">
		<div style="position:relative; float:right; clear:both; margin-top:8px">
			<a href='javascript:toogleFullScreen();'><img src='img/icon_maximize.gif' border=0></a>
		</div>
		<br>
 		<fieldset id="trackInfoList" class="legendBox">
 		
 		<div id="trackInfoTpl" class='infoDisplay'>
	 		<div class="infoDisplayItem"><div class='infoDisplayText'><?=_Time_Short?></div><div class='infoString time'>-</div></div>
	 		<div class="infoDisplayItem"><div class='infoDisplayText'><?=_Speed?></div><div class='infoString speed'>-</div></div>
	 		<div class="infoDisplayItem"><div class='infoDisplayText'><?=_Altitude_Short?></div><div class='infoString alt'>-</div></div>
	 		<? if ($isAdmin) { ?>
	 		<div class="infoDisplayItem"><div class='infoDisplayText'><?=_Altitude_Short?> (Baro)</div><div class='infoString altV'>-</div></div>	                
	        <? } ?>
	 		<div class="infoDisplayItem"><div class='infoDisplayText'><?=_Vario_Short?></div><div class='infoString vario'>-</div></div>
		</div>
		</fieldset>

		<fieldset class="legendBox">	
			<a href='javascript:zoomToFlight()'><?=_Zoom_to_flight?></a>
			<div id="side_bar"></div> 
						
			<?php  if ( $flightsNum>1) { ?>
			<input type="checkbox" value="1" id='syncStarts' onClick="toggleSyncStarts(this)">
			<label for='syncStarts'><?=_Sync_Start_Times?></label><br>
			
				<?php  if ( 0) { ?>
				<input type="checkbox" value="1" id='syncStartsTz' onClick="toggleSyncStartsTimezone(this)">
				<label for='syncStartsTz'><?=_Sync_Start_Timezones?></label><br>
				<?php  } ?>
			<?php  } ?>
			<input type="checkbox" value="1" id='followGlider' name='followGlider' onClick="toggleFollow(this)">
			<label for='followGlider'><?=_Follow_Glider?></label><br>
			<input type="checkbox" value="1" <?php echo (($flightsNum==1)?'checked':'') ?> id='showTask' name='showTask'  onClick="toggleTask(this)">
			<label for='showTask'><?=_Show_Task?></label><br>
			<? if ($CONF_airspaceChecks && $isAdmin ) { ?>
				<input type="checkbox" value="1"  checked="checked" name='airspaceShow' id='airspaceShow' onClick="toggleAirspace(true)">
				<label for='airspaceShow'><?=_Show_Airspace?></label>		
			<?  } ?>
		</fieldset>
	     
	    <fieldset class="legendBox">	  
	    <div id='animControl'>
		   <a href="javascript:;" onclick='ToggleTimer();'><img src='<?=$moduleRelPath?>/img/icon_anim_play.gif' border="0" title="<?=_Normal_Speed?>"></a>
		   <a href="javascript:;" onclick='resetTimer()'><img src='<?=$moduleRelPath?>/img/icon_anim_stop.gif' border="0" title="<?=_Stop?>"></a>
		   <button class="navbutt" onclick="TimeStep/=1.5;" title="<?=_Slower?>">-</button>
		   <button class="navbutt" onclick="TimeStep*=1.5;" title="<?=_Faster?>">+</button>
		</div>
 		</fieldset>
	  
	  
		<? if ( $CONF['thermals']['enable']  ) { ?>
		<fieldset id='themalBox' class="legendBox"><legend><?=_Thermals?></legend>
	         <div id='thermalLoad'><a href='javascript:loadThermals("<?=_Loading_thermals?><BR>")'><?=_Load_Thermals?></a></div>
	         <div id='thermalClose' style="display:none"><a href='javascript:toggleThermals()'><?=_Close?></a></div>
	         <div id='thermalOpen' style="display:none"><a href='javascript:toggleThermals()'><?=_Load_Thermals?></a></div>
	         <div id='thermalLoading' style="display:none"></div>
	         <div id='thermalControls' style="display:none">
	      		<input type="checkbox" id="1_box" onClick="boxclick(this,'1')" /><label for='1_box'> A Class<img src='img/thermals/class_1.png'></label><BR>
	          	<input type="checkbox" id="2_box" onClick="boxclick(this,'2')" /><label for='2_box'> B Class<img src='img/thermals/class_2.png'></label><BR>
	          	<input type="checkbox" id="3_box" onClick="boxclick(this,'3')" /><label for='3_box'> C Class<img src='img/thermals/class_3.png'></label><BR>
	          	<input type="checkbox" id="4_box" onClick="boxclick(this,'4')" /><label for='4_box'> D Class<img src='img/thermals/class_4.png'></label><BR>
	          	<input type="checkbox" id="5_box" onClick="boxclick(this,'5')" /><label for='5_box'> E Class<img src='img/thermals/class_5.png'></label><BR>
	         </div>
		</fieldset>
	    <? } ?>
        
		
        
		</div>
	</div>  
</div>  
    


<div id='msg'>DEBUG</div>
<div id='kk7Copyright' style='padding:3px;'>Skyways Layer &copy; <a href='https://thermal.kk7.ch' target='_blank'>thermal.kk7.ch</a></div>

<div id="photoDiv" style="position:absolute;display:none;z-index:110;"></div>

<script type="text/javascript">

var is3D=<?php  echo $is3D ?>;
var useGE=<?php  echo $useGE ?>; 
var trackColors= [ <?php  echo $colotStr; ?>] ;
var relpath="<?php echo $moduleRelPath?>";
var SERVER_NAME = '<?php  echo $_SERVER['SERVER_NAME'] ?>';
var posMarker=[];
var posMarker2=[];
var altMarker=[];
var varioMarker=[];
var speedMarker=[];

var tracksNum=0;

var followGlider=0;
var airspaceShow=1;
var showTask=1;
var taskLayer=[];
var infowindow ;

var mapType;

var metricSystem=<?=$PREFS->metricSystem?>;
var multMetric=1;
if (metricSystem==2) {
	multMetric=3.28;
}

var takeoffString="<? echo _TAKEOFF_LOCATION ?>";
var landingString="<? echo _LANDING_LOCATION ?>";

var AltitudeStr="<? echo _Altitude_Short ?>";
var AltitudeStrBaro="<? echo _Altitude_Short." (Baro)" ?>";

var altUnits="<? echo ' '.(($PREFS->metricSystem==1)?_M:_FT) ; ?>";
var speedUnits="<? echo ' '.(($PREFS->metricSystem==1)?_KM_PER_HR:_MPH) ; ?>";
var varioUnits="<? echo ' '.(($PREFS->metricSystem==1)?_M_PER_SEC:_FPM) ; ?>";

var flightList=[ <?php echo $flightListStr;?> ];
var flightsTotNum=<?php  echo $flightsNum ?>;
var takeoffID=<?php echo $takeoffID?>;
var flightID=<?php echo $flightID?>;
var flightIDstr='<?php echo $flightIDstr?>';
var compareUrlBase='<?php echo getLeonardoLink(array('op'=>'compare'.($is3D?'3d':''),'flightID'=>$flightListStr));?>';
var TimeStep = 10000; //  in millisecs
var CurrTime=null;

var airspaceCheck=<?php echo $airspaceCheck; ?>;
<? if ( $isAdmin ) { ?>
	var baroGraph=true;
	userAccessPriv=true;
<? } else { ?>
	var baroGraph=false;
	var userAccessPriv=false;
<?  } ?>

var skywaysVisible=0;
var map;
var googleEarth;   
var ge;
var compareUrl=null;
var radiusKm=10
var queryString='';
var gex;


<?php  if ($is3D ) { ?>
// google.load("maps", "3", {other_params: "sensor=false"});
<?php  }  else { ?>

$(document).ready(function(){
	initialize();
});

//google.setOnLoadCallback(initialize);
//google.load("maps", "3", {other_params: "sensor=false"});
<?php  } ?>

function initialize0() {
	$.getScript("<?=$moduleRelPath?>/js/google_maps/googleearth_org.js").done(function(script, textStatus) {
		initialize();
	});
}

function initializeGE(){
}

function initCallback(object) {
	ge = object;
	ge.getWindow().setVisibility(true);
	ge.getNavigationControl().setVisibility(ge.VISIBILITY_SHOW);
	ge.getNavigationControl().getScreenXY().setXUnits(ge.UNITS_PIXELS);
	ge.getNavigationControl().getScreenXY().setYUnits(ge.UNITS_INSET_PIXELS);
	ge.getNavigationControl().setVisibility(ge.VISIBILITY_AUTO);

	ge.getLayerRoot().enableLayerById(ge.LAYER_TERRAIN, true);
	ge.getLayerRoot().enableLayerById(ge.LAYER_BORDERS, true);
    ge.getLayerRoot().enableLayerById(ge.LAYER_ROADS, true);

    ge.getOptions().setScaleLegendVisibility(true); 	//Displays the current scale of the map.
    ge.getOptions().setStatusBarVisibility(true); 	//Displays a status bar at the bottom of the Earth window, containing geographic coordinates and altitude of the terrain below the current cursor position, as well as the range from which the user is viewing the Earth.
    //ge.getOptions().setOverviewMapVisibility(true); 	//Displays an inset map of the entire world in the bottom right corner. The current viewport is displayed on the inset map as a red rectangle.
    //ge.getOptions().setGridVisibility(true); 		//Displays the lines of latitude and longitude on the globe.
    //ge.getOptions().setAtmosphereVisibility(true); 	//Displays scattered light in the Earth's atmosphere.
    //ge.getSun().setVisibility(true);
        
//    gex = new GEarthExtensions(ge);
    
	addOverlays();
	// addGEruler();
}

function failureCallback(object) {
}
  		
function initialize() {

	mapType=google.maps.MapTypeId.<?php echo $GMapType ?>;
	var reliefTypeOptions = {
	  getTileUrl: function(a,b) {
	    	return "https://maps-for-free.com/layer/relief/z" + b + "/row" + a.y + "/" + b + "_" + a.x + "-" + a.y + ".jpg";
	    },
	  maxZoom: 20,
	  minZoom: 0,
	  tileSize: new google.maps.Size(256, 256),
	  name: "Relief"
	};
	var reliefMapType = new google.maps.ImageMapType(reliefTypeOptions);
		
    var mapOptions= {
            zoom: 2,
//            mapTypeControl: true,
      //      scaleControl: true,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_TOP
            },
            streetViewControl: false,
//            streetViewControlOptions: {
//              position: google.maps.ControlPosition.LEFT_TOP
//            },
//            mapTypeControlOptions: {
//                mapTypeIds: [
//					"Relief",
//					google.maps.MapTypeId.ROADMAP,
//					google.maps.MapTypeId.SATELLITE,
//					google.maps.MapTypeId.HYBRID,
//					google.maps.MapTypeId.TERRAIN,
//                   ],
//                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
//           },
//            mapTypeIds: [
//                google.maps.MapTypeId.ROADMAP,
//                google.maps.MapTypeId.SATELLITE,
//                google.maps.MapTypeId.HYBRID,
//                google.maps.MapTypeId.TERRAIN,
//             
//               // 'Earth'
//            ],
            mapTypeId: mapType
          };

    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    //map.mapTypes.set('Relief', reliefMapType);
    // map.setMapTypeId('Relief');
    skywaysOverlay= new google.maps.ImageMapType({
	    getTileUrl: function(tile, zoom) {
	    	var y= (1<<zoom)-tile.y-1;
    		return "https://thermal.kk7.ch/php/tile.php?typ=skyways&t=all&z="+zoom+"&x="+tile.x+"&y="+y+"&src="+SERVER_NAME; 
	    },
	    tileSize: new google.maps.Size(256, 256),
	    opacity:0.60,
	    isPng: true
	});

    map.overlayMapTypes.push(null); // create empty overlay entry 0 -> skyways

    
    var kk7Copyright=$("#kk7Copyright").get(0);
    kk7Copyright.index = 0; // used for ordering
    map.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(kk7Copyright);

       

    var controlButton=$("#controlSkyways").get(0);
    google.maps.event.addDomListener(controlButton , 'click', function() {
        if (skywaysVisible) {
        	map.overlayMapTypes.setAt("0",null); 
        	skywaysVisible=0;
        	$("#controlSkyways").removeClass('skywaysButtonPressed');
        } else {
    		map.overlayMapTypes.setAt("0",skywaysOverlay); 
    		skywaysVisible=1;
    		$("#controlSkyways").addClass('skywaysButtonPressed');
        }
    });
    controlButton.index = 5;
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(controlButton);

    
    <?php  if ($is3D) { ?>
    <?php  } else { ?>
    addOverlays();    
    <?php  } ?>

 //infowindow - po kliknieciu w element wyswietla info  
    infowindow = new google.maps.InfoWindow();
    google.maps.event.addListener(map, 'click', function() {
        infowindow.close();
    });	
	map.setCenter (new google.maps.LatLng(0,0) );
	

}

function addGEruler() {
		
}
function addOverlays(){
	loadTakeoff(takeoffID);
}

var takeoffs=[];
var MIN_START=86000;
var MAX_END=0;


function loadTakeoff(takeoffID) {

	$.getJSON('EXT_takeoff.php?op=get_takeoff_coordinates&wpID='+takeoffID, function(data) {
		takeoffs[takeoffID]=[];
		
		takeoffs[takeoffID]['data']=data;


		if (is3D) {
		} else {		
			var max_lat=data.lat+0.05;
			var min_lat=data.lat-0.05;
			var max_lon=data.lon+0.05;
			var min_lon=data.lon-0.05;	
			var newbounds = new google.maps.LatLngBounds(new google.maps.LatLng(max_lat,min_lon),new google.maps.LatLng(min_lat,max_lon) );
			if (bounds ==null ) {
				bounds=newbounds;
			} else {
				bounds.union(newbounds);
			}
			map.fitBounds(bounds);
	
			if (tracksNum==(flightsTotNum-1) ) {			
				if (airspaceCheck) {	
					toggleAirspace(false);
				}			
				
				$.get('EXT_takeoff.php?op=get_nearest&lat='+data.lat+'&lon='+data.lon, function(data) {
					drawTakeoffs(data);
				});	
	
			}
		}

	});
	
}
	
	
</script>

</body>
