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
/*
var favList=[];
var favVisible=0;
var favSelectInit=0;
 */
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

function timeToSeconds(time) {
	    time = time.split(/:/);
//	        return time[0] * 3600 + time[1] * 60 + time[2]*1;
	        return time[0] * 3600 + time[1] * 60 ;
}
function secondsToTime(time) {
	minutes = Math.floor(time / 60)%60;
	hours = Math.floor(time/60/60);
	if (hours<10) {
		hours = "0".concat(hours.toString());
	}
	if (minutes<10){
		minutes = "0".concat(minutes.toString());
	}
 
	return hours.toString().concat(":", minutes.toString());

}

function toogleIppi() {
	if (ippiVisible) {
		deactivateIppi();
		favVisible=0;
		ippiVisible=0;
	//	thermalVisible=0;
	//	dynamicVisible=0;
	} else {
		activateIppi();
		favVisible=0;
		ippiVisible=1;
	//	thermalVisible=1;
	//	dynamicVisible=1;
	}
	//toogleMenu('fav');
}

function activateIppi() {

	// $("#favFloatDiv").show("");
//		if (favSelectInit) {
		if (ippiSelectInit) {
		$(".indexCell .selectThermal").show();
		$(".indexCell .selectDynamic").show();
	//	$(".indexCell .selectTrack").show();
		$("#selectionSummary").show();
		$("#ippiDropDownID").show();
	} else {
		//$(".indexCell").attr('style', 'text-align: left');
		$(".indexCell").width('70px');
//		$(".indexCell").not('.SortHeader').empty();
		$(".indexCell").not('.SortHeader').append("<em class='selectThermal'>Termika: <input type='checkbox' value='1'></em><br><em class='selectDynamic'>Żagiel: <input type='checkbox' value='1'></em> ");
		favSelectInit=1;
		ippiSelectInit=1;
		$("#selectionSummary").show();
		$("#ippiDropDownID").show();
	//	thermalSelectInit=1;
	//	dynamicSelectInit=1;
	}
}

function deactivateIppi() {
	$(".selectThermal").hide();
	$(".selectDynamic").hide();
	$("#ippiDropDownID").hide();
	//$(".indexCell").not('.SortHeader').text().replace("Termika:","").replace("Żagiel:","");
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
	var total = timeToSeconds($("#timeOfThermalFlights").text());
	var time = timeToSeconds($("#row_"+flightID+" td:nth-child(4)").text()); 
	$("#timeOfThermalFlights").text(secondsToTime(total+time));
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
	var total = timeToSeconds($("#timeOfDynamicFlights").text());
	var time = timeToSeconds($("#row_"+flightID+" td:nth-child(4)").text()); 
	$("#timeOfDynamicFlights").text(secondsToTime(total+time));
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
	var total = timeToSeconds($("#timeOfThermalFlights").text());
	var time = timeToSeconds($("#row_"+flightID+" td:nth-child(4)").text()); 
	$("#timeOfThermalFlights").text(secondsToTime(total-time));
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
	var total = timeToSeconds($("#timeOfDynamicFlights").text());
	var time = timeToSeconds($("#row_"+flightID+" td:nth-child(4)").text()); 
	$("#timeOfDynamicFlights").text(secondsToTime(total-time));
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
	$("#numberOfThermalFlights").text('0');
	updateLinkIppi();
}
function clearDynamic(){
	$.cookie("dynamicList", null);
	$.post("<?=$moduleRelPath?>/EXT_ajax_functions.php?op=storeIppi", { ippiHtml: '' } );
	$("#favList tr").remove();
	dynamicList=[];
	$("#numberOfDynamicFlights").text('0');
	updateLinkIppi();
}
function clearAll(){
	clearDynamic();
	clearThermal();
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
		$("#numberOfThermalFlights").text(thermalListNum);
		$("#numberOfDynamicFlights").text(dynamicListNum);
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
		$(this).parent().nextAll().addBack().css("background-color","#ff9933");
		var row=$(this).parent().parent();
		var flightID=row.attr('id').substr(4);
//		alert(flightID);
		if ( $(this).children('input').is(':checked') ) {
			addThermal(flightID);
		} else {
			removeThermal(flightID);
		}
		//$("#dbg").html("id="+flightID+"@"+row.attr('id'));
		//row.css({background:"#ff0000",height:"100"});
	});

	$(".indexCell .selectDynamic").live('click',function() {
		$(this).parent().nextAll().addBack().css("background-color","#66ccff");
		var row=$(this).parent().parent();
		var flightID=row.attr('id').substr(4);

		if ( $(this).children('input').is(':checked') ) {
			addDynamic(flightID);
		} else {
			removeDynamic(flightID);
		}

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

	/*
	$("#ippiMenuID").live('click',function() {
		
	});	
 */
});


</script>

<div id="ippiDropDownID" class="secondMenuDropLayer"  >
	<div class='closeButton closeLayerButton'></div>        
	<div class='content' align="left">

		<div style='text-align:center;margin-top:10px;'>
			<span class='info' id='ippiText'>
			<h2>Wyciąg z księgi lotów na potrzeby IPPI </h2>
			<BR>
			<!-- Select Flights by clicking on the checkbox  -->	
			<p>W kolumnie z numerami lotów możesz zaznaczyć wybrane loty jako termiczne i żaglowe</p>	
			</span>
			
			<a id='compareFavoritesLink' class='greenButton' href=''><?php echo _Compare_Favorite_Tracks ?></a>
			
			<hr>
		</div>	 
		<div id='ippiListDiv'>
			<table id='selectionSummary' >
				<tr><th>Rodzaj lotów</th><th>Liczba lotów</th><th>Czas lotów</th></tr>
				<tr><th>Termika</th><td id='numberOfThermalFlights'></td>0<td id='timeOfThermalFlights'>00:00</td>
				<tr><th>Żagiel</th><td id='numberOfDynamicFlights'></td>0<td id='timeOfDynamicFlights'>00:00</td>
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
