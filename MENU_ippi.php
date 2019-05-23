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
// $Id: MENU_ippi.php,v 1.2 2012/09/17 22:33:49 manolis Exp $                                                                 
//
//************************************************************************
 

?>
<div id="dbg" style="position:absolute;top:0;left:0;width:100px;height:20px;display:none;"></div>

<script type="text/javascript">

var favList=[];
var favVisible=0;
var favSelectInit=0;

var thermalList=[];
var thermalVisible=0;
var thermalSelectInit=0;

var dynamicList=[];
var dynamicVisible=0;
var dynamicSelectInit=0;

var ippiVisible=0;
var ippiSelectInit=0;

var compareUrlBase='<?php echo getLeonardoLink(array('op'=>'compare','flightID'=>'%FLIGHTS%'));?>';
var compareUrl='';

function toogleIppi() {
	if (favVisible) {
		deactivateIppi();
		favVisible=0;
		ippiVisible=0;
	//	thermalVisible=0;
	//	dynamicVisible=0;
	} else {
		activateIppi();
		favVisible=1;
		ippiVisible=1;
	//	thermalVisible=1;
	//	dynamicVisible=1;
	}
	toogleMenu('fav');
}

function activateIppi() {

	// $("#favFloatDiv").show("");
//		if (favSelectInit) {
		if (ippiSelectInit) {
		$(".indexCell .selectThermal").show();
		$(".indexCell .selectDynamic").show();
	//	$(".indexCell .selectTrack").show();
	} else {
		//$(".indexCell").attr('style', 'text-align: left');
		$(".indexCell").width('70px');
//		$(".indexCell").not('.SortHeader').empty();
		$(".indexCell").not('.SortHeader').append("Termika: <input class='selectThermal' type='checkbox' value='1'><br>Żagiel: <input class='selectDynamic' type='checkbox' value='1'> ");
		favSelectInit=1;
		ippiSelectInit=1;
	//	thermalSelectInit=1;
	//	dynamicSelectInit=1;
	}
}

function deactivateIppi() {
	$(".indexCell .selectThermal").hide();
	$(".indexCell .selectDynamic").hide();
}

function loadFavs() {
	for( var flightID in favList) {
	//	addFavFav(flightID );
	}	
}

function addFav(flightID ){
	if ( $.inArray(flightID, favList)  >=0 ) { return; }
	var newrow=$("#row_"+flightID).clone().attr('id', 'fav_'+(flightID)  ).appendTo("#favList > tbody:last");
	$("#fav_"+flightID+" *").removeAttr('id').removeAttr("href");
	$("#fav_"+flightID+" a").contents().unwrap();
	$("#fav_"+flightID+" .dateHidden").removeClass('dateHidden');
	$("#fav_"+flightID+" .indexCell").remove();
	$("#fav_"+flightID+" .indexCell").remove();
	$("#fav_"+flightID+" .smallInfo").html("<div class='fav_remove' id='fav_remove_"+flightID+"'>"+
				"<?php echo leoHtml::img("icon_fav_remove.png",0,0,'absmiddle',_Remove_From_Favorites,'icons1','',0)?></div>");
	favList.push(flightID);
	updateLink();
	updateCookie();
	//$.getJSON('EXT_flight.php?op=list_flights_json&lat='+flights[i].data.firstLat+'&lon='+flights[i].data.firstLon+'&distance='+radiusKm+queryString,null,addFlightToFav);	
}

function addThermal(flightID ){
	if ( $.inArray(flightID, thermalList)  >=0 ) { return; }
	var newrow=$("#row_"+flightID).clone().attr('id', 'thermal_'+(flightID)  ).appendTo("#thermalList > tbody:last");
	$("#thermal_"+flightID+" *").removeAttr('id').removeAttr("href");
	$("#thermal_"+flightID+" a").contents().unwrap();
	$("#thermal_"+flightID+" .dateHidden").removeClass('dateHidden');
	$("#thermal_"+flightID+" .indexCell").remove();
	$("#thermal_"+flightID+" .indexCell").remove();
	$("#thermal_"+flightID+" .smallInfo").html("<div class='thermal_remove' id='thermal_remove_"+flightID+"'>"+
				"<?php echo leoHtml::img("icon_fav_remove.png",0,0,'absmiddle',_Remove_From_Favorites,'icons1','',0)?></div>");
	thermalList.push(flightID);
	updateLink();
	updateCookie();
	//$.getJSON('EXT_flight.php?op=list_flights_json&lat='+flights[i].data.firstLat+'&lon='+flights[i].data.firstLon+'&distance='+radiusKm+queryString,null,addFlightToFav);	
}

function addDynamic(flightID ){
	if ( $.inArray(flightID, dynamicList)  >=0 ) { return; }
	var newrow=$("#row_"+flightID).clone().attr('id', 'dynamic_'+(flightID)  ).appendTo("#dynamicList > tbody:last");
	$("#dynamic_"+flightID+" *").removeAttr('id').removeAttr("href");
	$("#dynamic_"+flightID+" a").contents().unwrap();
	$("#dynamic_"+flightID+" .dateHidden").removeClass('dateHidden');
	$("#dynamic_"+flightID+" .indexCell").remove();
	$("#dynamic_"+flightID+" .indexCell").remove();
	$("#dynamic_"+flightID+" .smallInfo").html("<div class='dynamic_remove' id='dynamic_remove_"+flightID+"'>"+
				"<?php echo leoHtml::img("icon_fav_remove.png",0,0,'absmiddle',_Remove_From_Favorites,'icons1','',0)?></div>");
	dynamicList.push(flightID);
	updateLink();
	updateCookie();
	//$.getJSON('EXT_flight.php?op=list_flights_json&lat='+flights[i].data.firstLat+'&lon='+flights[i].data.firstLon+'&distance='+radiusKm+queryString,null,addFlightToFav);	
}


function removeFav(flightID){
	if ( $.inArray(flightID, favList)  < 0  ) { return; }
	$("#fav_"+flightID).fadeOut(300,function() {
		$(this).remove();
		//remove from list
		favList = jQuery.grep(favList, function(value) {
			  return value != flightID;
		});
		updateLink();
		updateCookie();
	});
}

function removeThermal(flightID){
	if ( $.inArray(flightID, thermalList)  < 0  ) { return; }
	$("#thermal_"+flightID).fadeOut(300,function() {
		$(this).remove();
		//remove from list
		thermalList = jQuery.grep(thermalList, function(value) {
			  return value != flightID;
		});
		updateLink();
		updateCookie();
	});
}


function removeDynamic(flightID){
	if ( $.inArray(flightID, dynamicList)  < 0  ) { return; }
	$("#dynamic_"+flightID).fadeOut(300,function() {
		$(this).remove();
		//remove from list
		dynamicList = jQuery.grep(dynamicList, function(value) {
			  return value != flightID;
		});
		updateLink();
		updateCookie();
	});
}


function updateCookie(){
	//return;
	var str='';
	var thermalListNum=0;
	for(var i in thermalList) {
		if (thermalListNum>0) str+=',';
		str+=thermalList[i];
		thermalListNum++;
	}
	
	$.cookie("thermalList", str );
	$.post("<?=$moduleRelPath?>/EXT_ajax_functions.php?op=storeFavs", { favHtml: $("#favListDiv").html() } );
	
}

function clearFavs(){
	$.cookie("favList", null );
	$.post("<?=$moduleRelPath?>/EXT_ajax_functions.php?op=storeFavs", { favHtml: '' } );
	$("#favList tr").remove();
	favList=[];
	updateLink();
}

function clearThermal(){
	$.cookie("thermalList", null);
	$.post("<?=$moduleRelPath?>/EXT_ajax_functions.php?op=storeFavs", { favHtml: '' } );
	$("#favList tr").remove();
	thermalList=[];
	updateLink();
}
function clearDynamic(){
	$.cookie("dynamicList", null);
	$.post("<?=$moduleRelPath?>/EXT_ajax_functions.php?op=storeFavs", { favHtml: '' } );
	$("#favList tr").remove();
	dynamicList=[];
	updateLink();
}

function updateLink() {
	var str='';
	var  favListNum=0;
	for(var i in favList) {
		if (favListNum>0) str+=',';
		str+=favList[i];
		favListNum++;
	}
	if (favListNum>0) {
		$("#compareFavoritesLink").show();
		$("#compareFavoritesText").hide();
		
		compareUrl=compareUrlBase.replace("%FLIGHTS%",str);
		$("#compareFavoritesLink").attr('href',compareUrl);
	} else {
		$("#compareFavoritesLink").hide();
		$("#compareFavoritesText").show();
	}

	
}

$(document).ready(function(){

	var favListCookie=$.cookie("favList");
	if (favListCookie) {
		favList=favListCookie.split(',');		
		updateLink();		
	}
	var thermalListCookie=$.cookie("thermalList");
	if (thermalListCookie){
		thermalList=thermalListCookie.split(',');
		updateLink();
	}
	var dynamicListCookie=$.cookie("dynamicList");
	if (dynamicListCookie){
		dynamicList=dynamicListCookie.split(',');
		updateLink();
	}
	
	$(".indexCell .selectTrack").live('click',function() {
		var row=$(this).parent().parent();
		var flightID=row.attr('id').substr(4);
//		alert(flightID);
		if ( $(this).is(':checked') ) {
			addFav(flightID);
		} else {
			removeFav(flightID);
		}
		//$("#dbg").html("id="+flightID+"@"+row.attr('id'));
		//row.css({background:"#ff0000",height:"100"});
	});

	$(".indexCell .selectThermal").live('click',function() {
		var row=$(this).parent().parent();
		var flightID=row.attr('id').substr(4);
//		alert(flightID);
		if ( $(this).is(':checked') ) {
			addThermal(flightID);
		} else {
			removeThermal(flightID);
		}
		//$("#dbg").html("id="+flightID+"@"+row.attr('id'));
		//row.css({background:"#ff0000",height:"100"});
	});

	$(".indexCell .selectDynamic").live('click',function() {
		var row=$(this).parent().parent();
		var flightID=row.attr('id').substr(4);

		if ( $(this).is(':checked') ) {
			addDynamic(flightID);
		} else {
			removeDynamic(flightID);
		}
		//$("#dbg").html("id="+flightID+"@"+row.attr('id'));
		//row.css({background:"#ff0000",height:"100"});
	});
 
	$(".fav_remove").live('click',function() {
		var flightID=$(this).attr('id').substr(11);
		//$("#row_"+flightID+" .selectThermal").attr('checked', false);
		//$("#row_"+flightID+" .selectDynamic").attr('checked', false);
		removeFav(flightID);
	});

	$(".thermal_remove").live('click',function() {
		var flightID=$(this).attr('id').substr(11);
		$("#row_"+flightID+" .selectThermal").attr('checked', false);
		removeThermal(flightID);
	});

	$(".dynamic_remove").live('click',function() {
		var flightID=$(this).attr('id').substr(11);
		$("#row_"+flightID+" .selectDynamic").attr('checked', false);
		removeDynamic(flightID);
	});

	
});


</script>

<div id="favDropDownID" class="secondMenuDropLayer"  >
<div class='closeButton closeLayerButton'></div>        
<div class='content' align="left">

	<div style='text-align:center;margin-top:10px;'>
		<span class='info' id='compareFavoritesText'>
		<h2><?php echo _Favorites ?></h2>
		<?php echo _Compare_flights_line_1 ?>
		<BR>
		<!-- Select Flights by clicking on the checkbox  -->	
		<?php echo _Compare_flights_line_2 ?>
			<!-- You can then compare all your selected flights in google maps -->	
			<br><BR>	
		</span>
		
		<a id='compareFavoritesLink' class='greenButton' href=''><?php echo _Compare_Favorite_Tracks ?></a>
		
		<a class='redButton smallButton' href='javascript:clearFavs()'><?php echo _Remove_all_favorites?></a>
		
		<hr>
	</div>	 
	<div id='favListDiv'>
		<?php  if (strlen($_SESSION['favHtml'])>30) { 
			echo $_SESSION['favHtml'];
		} else { ?>
		<table id='favList'>
			<tbody></tbody>
		</table>
		<table id='thermalList'>
			<tbody></tbody>
		</table>
		<table id='dynamicList'>
			<tbody></tbody>
		</table>
		<?php  } ?>
	</div>
</div>
</div>
