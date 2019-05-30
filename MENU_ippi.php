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
		$("#selectionSummary").show();
	} else {
		//$(".indexCell").attr('style', 'text-align: left');
		$(".indexCell").width('70px');
//		$(".indexCell").not('.SortHeader').empty();
		$(".indexCell").not('.SortHeader').append("Termika: <input class='selectThermal' type='checkbox' value='1'><br>Żagiel: <input class='selectDynamic' type='checkbox' value='1'> ");
		favSelectInit=1;
		ippiSelectInit=1;
		$("#selectionSummary").show();
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

function addThermal(flightID ){
	//if ( $.inArray(flightID, thermalList)  >=0 ) { return; };
	var newrow=$("#row_"+flightID).clone().attr('id', 'thermal_'+(flightID)  ).appendTo("#thermalListIppi > tbody:last");
	$("#thermal_"+flightID+" *").removeAttr('id').removeAttr("href");
	$("#thermal_"+flightID+" a").contents().unwrap();
	$("#thermal_"+flightID+" .dateHidden").removeClass('dateHidden');
	$("#thermal_"+flightID+" .indexCell").remove();
	$("#thermal_"+flightID+" .indexCell").remove();
	$("#thermal_"+flightID+" .smallInfo").html("<div class='thermal_remove' id='thermal_remove_"+flightID+"'>"+
				"<?php echo leoHtml::img("icon_fav_remove.png",0,0,'absmiddle',_Remove_From_Favorites,'icons1','',0)?></div>");
	thermalList.push(flightID);
	updateLinkIppi();
	updateCookieIppi();
	//$.getJSON('EXT_flight.php?op=list_flights_json&lat='+flights[i].data.firstLat+'&lon='+flights[i].data.firstLon+'&distance='+radiusKm+queryString,null,addFlightToFav);	
}

function addDynamic(flightID ){
	//if ( $.inArray(flightID, dynamicList)  >=0 ) { return; }
	var newrow=$("#row_"+flightID).clone().attr('id', 'dynamic_'+(flightID)  ).appendTo("#dynamicListIppi > tbody:last");
	$("#dynamic_"+flightID+" *").removeAttr('id').removeAttr("href");
	$("#dynamic_"+flightID+" a").contents().unwrap();
	$("#dynamic_"+flightID+" .dateHidden").removeClass('dateHidden');
	$("#dynamic_"+flightID+" .indexCell").remove();
	$("#dynamic_"+flightID+" .indexCell").remove();
	$("#dynamic_"+flightID+" .smallInfo").html("<div class='dynamic_remove' id='dynamic_remove_"+flightID+"'>"+
				"<?php echo leoHtml::img("icon_fav_remove.png",0,0,'absmiddle',_Remove_From_Favorites,'icons1','',0)?></div>");
	dynamicList.push(flightID);
	updateLinkIppi();
	updateCookieIppi();
	//$.getJSON('EXT_flight.php?op=list_flights_json&lat='+flights[i].data.firstLat+'&lon='+flights[i].data.firstLon+'&distance='+radiusKm+queryString,null,addFlightToFav);	
}


function removeThermal(flightID){
	if ( $.inArray(flightID, thermalList)  < 0  ) { return; }
	$("#thermal_"+flightID).fadeOut(300,function() {
		$(this).remove();
		//remove from list
		thermalList = jQuery.grep(thermalList, function(value) {
			  return value != flightID;
		});
		updateLinkIppi();
		updateCookieIppi();
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
		updateLinkIppi();
		updateCookieIppi();
	});
}


function updateCookieIppi(){
	//return;
	var strThermal='';
	var thermalListNum=0;
	for(var i in thermalList) {
		if (thermalListNum>0) strThermal+=',';
		strThermal+=thermalList[i];
		thermalListNum++;
	}
	$.cookie("thermalList", strThermal );
	var dynamicListNum=0;
	var strDynamic='';
	str='';
	for(var i in dynamicList) {
		if (dynamicListNum>0) strDynamic+=',';
		strDynamic+=dynamicList[i];
		dynamicListNum++;
	}
	$.cookie("dynamicList", strDynamic );
	$.post("<?=$moduleRelPath?>/EXT_ajax_functions.php?op=storeIppi", { ippiHtml: $("#favListDiv").html() } );
	//$.post("<?=$moduleRelPath?>/EXT_ajax_functions.php?op=storeFavs", { favHtml: $("#selectionSummary").html() } );
	
}


function clearThermal(){
	$.cookie("thermalList", null);
	$.post("<?=$moduleRelPath?>/EXT_ajax_functions.php?op=storeIppi", { ippiHtml: '' } );
	$("#favList tr").remove();
	thermalList=[];
	updateLinkIppi();
}
function clearDynamic(){
	$.cookie("dynamicList", null);
	$.post("<?=$moduleRelPath?>/EXT_ajax_functions.php?op=storeIppi", { ippiHtml: '' } );
	$("#favList tr").remove();
	dynamicList=[];
	updateLinkIppi();
}

function updateLinkIppi() {
	var strThermal='';
	var strDynamic='';
	var  thermalListNum=0;
	var  dynamicListNum=0;
	for(var i in thermalList) {
		if (thermalListNum>0) strThermal+=',';
		strThermal+=thermalList[i];
		thermalListNum++;
	}
	for(var i in dynamicList) {
		if (dynamicListNum>0) strDynamic+=',';
		strDynamic+=dynamicList[i];
		dynamicListNum++;
	}

	if (thermalListNum>0 || dynamicListNum>0 ) {
		$("#ippiDropDownID").removeClass('secondMenuDropLayer');
		$("#compareFavoritesLink").show();
		$("#compareFavoritesText").hide();
		$("#selectionSummary").show();
		$("#ippiListDiv").show();
//		console.log("thermals: "+ thermalListNum + " dynamic: " +dynamicListNum);
		
		compareUrl=compareUrlBase.replace("%FLIGHTS%",strThermal+','+strDynamic);
		$("#compareFavoritesLink").attr('href',compareUrl);
	} else {
		$("#ippiDropDownID").addClass('secondMenuDropLayer');
		$("#compareFavoritesLink").hide();
		$("#compareFavoritesText").show();
		$("#selectionSummary").hide();
	}

	
}

$(document).ready(function(){

 	var thermalListCookie=$.cookie("thermalList");
	if (thermalListCookie){
		thermalList=thermalListCookie.split(',');
		updateLinkIppi();
	}
	var dynamicListCookie=$.cookie("dynamicList");
	if (dynamicListCookie){
		dynamicList=dynamicListCookie.split(',');
		updateLinkIppi();
	}
	$(".indexCell .selectThermal").live('click',function() {
		var row=$(this).parent().parent();
		var flightID=row.attr('id').substr(4);
//		alert(flightID);
		if ( $(this).is(':checked') ) {
			addThermal(flightID);
		} else {
			removeThermal(flightID);
		}
		$(this).parent().nextAll().addBack().css("background-color","#ff9933");
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

		$(this).parent().nextAll().addBack().css("background-color","#66ccff");
		//$("#dbg").html("id="+flightID+"@"+row.attr('id'));
		//row.css({background:"#ff0000",height:"100"});
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

<div id="ippiDropDownID" class="secondMenuDropLayer"  >
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
	<div id='ippiListDiv'>
		<table id='selectionSummary' >
			<tr><td>Rodzaj lotów</td><td>Liczba lotów</td><td>Czas lotów</td></tr>
			<tr><td>Termika</td><td id='numberOfThermalFlights'></td><td id='timeOfThermalFlights'></td>
			<tr><td>Żagiel</td><td id='numberOfDynamicFlights'></td><td id='timeOfDynamicFlights'></td>
		</table>
		<table id='thermalListIppi'>
			<tbody></tbody>
		</table>
		<table id='dynamicListIppi'>
			<tbody></tbody>
		</table>
	</div>
</div>
</div>
