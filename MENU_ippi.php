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
<div id="infobox" style="position:fixed;top:0;right:0;background-color:rgba(0,255,0,.7);display:none;">

	<table id='selectionSummaryInfobox' >
					<tr><th align="left">Rodzaj lotów</th><th align="center">Liczba lotów</th><th align="center">Czas lotów</th></tr>
					<tr><th align="left">Termika</th><td align="center"  id='numberOfThermalFlightsInfobox'>0</td><td align="center"  id='timeOfThermalFlightsInfobox'>00:00</td>
					<tr ><th align="left">Żagiel</th><td align="center" id='numberOfDynamicFlightsInfobox'>0</td><td align="center" id='timeOfDynamicFlightsInfobox'>00:00</td>
					<tr><th align="left">Suma</th><td align="center" id='totalNumberOfFlightsInfobox'>0</td><td align="center" id='totalTimeOfFlightsInfobox'>00:00</td>
	</table>
</div>

<script type="text/javascript">
var thermalList=[];
var thermalVisible=0;
var thermalSelectInit=0;

var dynamicList=[];
var dynamicVisible=0;
var dynamicSelectInit=0;

var ippiVisible=0;
var ippiSelectInit=0;

var ippiUrlBase='<?php echo getLeonardoLink(array('op'=>'compare','flightID'=>'%FLIGHTS%'));?>';
var ippiUrl='';
function timeToSeconds(time) {
	    time = time.split(/:/);
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
		favSelectInit=0;
	} else {
		activateIppi();
		favVisible=0;
		ippiVisible=1;
		favSelectInit=0;
	}
}

function activateIppi() {

	if (ippiSelectInit) {
		$(".indexCell .selectThermal").show();
		$(".indexCell .selectDynamic").show();
		$("#selectionSummary").show();
		$("#ippiDropDownID").show();
		$("#infobox").show();
		$("#favMenuID").hide();
		$("#favDropDownID").hide();
		$("#ippiListDiv").hide();
		$("[src='/img/icon_private.gif']").parent().parent().hide();
	} else {
		$(".indexCell").width('70px');
		$(".indexCell").not('.SortHeader').append("<em class='selectThermal'>Termika: <input type='checkbox' value='1'></em><br><em class='selectDynamic'>Żagiel: <input type='checkbox' value='1'></em> ");
		favSelectInit=1;
		ippiSelectInit=1;
		$("#selectionSummary").show();
		$("#ippiDropDownID").show();
		$("#infobox").show();
		$("#favMenuID").hide();
		$("#favDropDownID").hide();
		$("#ippiListDiv").hide();
		$("[src='/img/icon_private.gif']").parent().parent().hide();
	}
}

function deactivateIppi() {
	$(".selectThermal").hide();
	$(".selectDynamic").hide();
	$("#ippiDropDownID").hide();
	$("#infobox").hide();
	$("#favMenuID").show();
	$("#ippiListDiv").hide();
	$("[src='/img/icon_private.gif']").parent().parent().show();
}

function loadFavs() {
	for( var flightID in favList) {
	//	addFavFav(flightID );
	}	
}

function addThermal(flightID ){
	var newrow=$("#row_"+flightID).clone().attr('id', 'thermal_'+(flightID)  ).appendTo("#thermalListIppi > tbody:last");
	$("#thermal_"+flightID+" *").removeAttr('id').removeAttr("href");
	$("#thermal_"+flightID+" a").contents().unwrap();
	$("#thermal_"+flightID+" .dateHidden").removeClass('dateHidden');
	$("#thermal_"+flightID+" .indexCell").remove();
	$("#thermal_"+flightID+" .indexCell").remove();
	$("#thermal_"+flightID+" .catInfo").remove();
	$("#thermal_"+flightID+" td:nth-child(4)").remove();
	$("#thermal_"+flightID+" td:has(img.sprite-icon_valid_nok)").html("&#10007;");
	$("#thermal_"+flightID+" td:has(img.sprite-icon_valid_ok)").html("&#10004;");
	if($("#thermal_"+flightID+" td:nth-child(5)").text().length>1){
		$("#thermal_"+flightID+" td:nth-child(5)").html("&quest;");
	}
	$("#thermal_"+flightID+" td:nth-child(6)").html("<a href='https://leonardo.pgxc.pl/lot/"+flightID+"/'>https://leonardo.pgxc.pl/lot/"+flightID+"</a>");
	var model = $("#thermal_"+flightID+" img.brands").attr('alt');
	$("#thermal_"+flightID+" td:has(img.brands)").html(model);
	$("#thermal_"+flightID+" td:nth-child(8)").remove();
	$("#thermal_"+flightID+" td:nth-child(2)").after("<td>"+model+"</td>");
	$("#thermal_"+flightID+" td:nth-child(8)").remove();
	$("#thermal_"+flightID+" *").removeAttr('style');
	$("#thermal_"+flightID+" *").removeAttr('class','dateString');
	$("#thermal_"+flightID+" *").removeAttr('valign','top');
	$("#thermal_"+flightID+" *").attr('style','text-align:center');
	$("#thermal_"+flightID+" *").removeClass('l_row2').addClass('l_row1');
	thermalList.push(flightID);
	var total = timeToSeconds($("#timeOfThermalFlights").text());
	var time = timeToSeconds($("#row_"+flightID+" td:nth-child(4)").text()); 
	$("#timeOfThermalFlights").text(secondsToTime(total+time));
	$("#timeOfThermalFlightsInfobox").text(secondsToTime(total+time));
	var old = $("#numberOfThermalFlights").text()*1;
	$("#numberOfThermalFlights").text(old+1);
	$("#numberOfThermalFlightsInfobox").text(old+1);
	updateLinkIppi();
	updateLinkIppi();
	updateCookieIppi();
	$("#row_"+flightID+" em.selectDynamic").hide();
	var total = $("#totalNumberOfFlights").text()*1;
	$("#totalNumberOfFlights").text(total+1);
	$("#totalNumberOfFlightsInfobox").text(total+1);
	var totalTime = timeToSeconds($("#totalTimeOfFlights").text());
	$("#totalTimeOfFlights").text(secondsToTime(totalTime + time));
	$("#totalTimeOfFlightsInfobox").text(secondsToTime(totalTime + time));
}

function addDynamic(flightID ){
	var newrow=$("#row_"+flightID).clone().attr('id', 'dynamic_'+(flightID)  ).appendTo("#dynamicListIppi > tbody:last");
	$("#dynamic_"+flightID+" *").removeAttr('id').removeAttr("href");
	$("#dynamic_"+flightID+" a").contents().unwrap();
	$("#dynamic_"+flightID+" .dateHidden").removeClass('dateHidden');
	$("#dynamic_"+flightID+" .indexCell").remove();
	$("#dynamic_"+flightID+" .indexCell").remove();
	$("#dynamic_"+flightID+" td:nth-child(4)").remove();
	$("#dynamic_"+flightID+" td:has(img.sprite-icon_valid_nok)").html("&#10007;");
	$("#dynamic_"+flightID+" td:has(img.sprite-icon_valid_ok)").html("&#10004;");
	if($("#dynamic_"+flightID+" td:nth-child(5)").text().length>1){
		$("#dynamic_"+flightID+" td:nth-child(5)").html("&quest;");
	}
	$("#dynamic_"+flightID+" td:nth-child(6)").html("<a href='https://leonardo.pgxc.pl/lot/"+flightID+"/'>https://leonardo.pgxc.pl/lot/"+flightID+"</a>");
	var model = $("#dynamic_"+flightID+" img.brands").attr('alt');
	$("#dynamic_"+flightID+" td:has(img.brands)").html(model);
	$("#dynamic_"+flightID+" td:nth-child(8)").remove();
	$("#dynamic_"+flightID+" td:nth-child(2)").after("<td>"+model+"</td>");
	$("#dynamic_"+flightID+" td:nth-child(8)").remove();
	$("#dynamic_"+flightID+" *").removeAttr('style');
	$("#dynamic_"+flightID+" *").removeAttr('class','dateString');
	$("#dynamic_"+flightID+" *").removeAttr('valign','top');
	$("#dynamic_"+flightID+" *").attr('style','text-align:center');
	$("#dynamic_"+flightID+" *").removeClass('l_row2').addClass('l_row1');
	dynamicList.push(flightID);
	var total = timeToSeconds($("#timeOfDynamicFlights").text());
	var time = timeToSeconds($("#row_"+flightID+" td:nth-child(4)").text()); 
	$("#timeOfDynamicFlights").text(secondsToTime(total+time));
	$("#timeOfDynamicFlightsInfobox").text(secondsToTime(total+time));
	var old = $("#numberOfDynamicFlights").text()*1;
	$("#numberOfDynamicFlights").text(old+1);
	$("#numberOfDynamicFlightsInfobox").text(old+1);
	updateLinkIppi();
	updateCookieIppi();
	$("#row_"+flightID+" em.selectThermal").hide();
	var total = $("#totalNumberOfFlights").text()*1;
	$("#totalNumberOfFlights").text(total+1);
	$("#totalNumberOfFlightsInfobox").text(total+1);
	var totalTime = timeToSeconds($("#totalTimeOfFlights").text());
	$("#totalTimeOfFlights").text(secondsToTime(totalTime + time));
	$("#totalTimeOfFlightsInfobox").text(secondsToTime(totalTime + time));
}


function removeThermal(flightID){
	if ( $.inArray(flightID, thermalList)  < 0  ) { return; }
	$("#thermal_"+flightID).fadeOut(300,function() {
		$(this).remove();
		thermalList = jQuery.grep(thermalList, function(value) {
			  return value != flightID;
		});
	var total = timeToSeconds($("#timeOfThermalFlights").text());
	var time = timeToSeconds($("#row_"+flightID+" td:nth-child(4)").text()); 
	$("#timeOfThermalFlights").text(secondsToTime(total-time));
	$("#timeOfThermalFlightsInfobox").text(secondsToTime(total-time));
	var totalTime = timeToSeconds($("#totalTimeOfFlights").text());
	$("#totalTimeOfFlights").text(secondsToTime(totalTime - time));
	$("#totalTimeOfFlightsInfobox").text(secondsToTime(totalTime - time));
		updateLinkIppi();
		updateCookieIppi();
	});
	$("#row_"+flightID+" em.selectDynamic").show();
	var old = $("#numberOfThermalFlights").text()*1;
	$("#numberOfThermalFlights").text(old-1);
	$("#numberOfThermalFlightsInfobox").text(old-1);
	var total = $("#totalNumberOfFlights").text()*1;
	$("#totalNumberOfFlights").text(total-1);
	$("#totalNumberOfFlightsInfobox").text(total-1);
	updateLinkIppi();
}


function removeDynamic(flightID){
	if ( $.inArray(flightID, dynamicList)  < 0  ) { return; }
	$("#dynamic_"+flightID).fadeOut(300,function() {
		$(this).remove();
		dynamicList = jQuery.grep(dynamicList, function(value) {
			  return value != flightID;
		});
	var total = timeToSeconds($("#timeOfDynamicFlights").text());
	var time = timeToSeconds($("#row_"+flightID+" td:nth-child(4)").text()); 
	$("#timeOfDynamicFlights").text(secondsToTime(total-time));
	$("#timeOfDynamicFlightsInfobox").text(secondsToTime(total-time));
	var totalTime = timeToSeconds($("#totalTimeOfFlights").text());
	$("#totalTimeOfFlights").text(secondsToTime(totalTime-time));
	$("#totalTimeOfFlightsInfobox").text(secondsToTime(totalTime-time));
		updateLinkIppi();
		updateCookieIppi();
	});
	$("#row_"+flightID+" em.selectThermal").show();
	var old = $("#numberOfDynamicFlights").text()*1;
	$("#numberOfDynamicFlights").text(old-1);
	$("#numberOfDynamicFlightsInfobox").text(old-1);
	var total = $("#totalNumberOfFlights").text()*1;
	$("#totalNumberOfFlights").text(total-1);
	$("#totalNumberOfFlightsInfobox").text(total-1);
}


function updateCookieIppi(){
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
		$("#compareIppiLink").show();
		$("#compareFavoritesText").hide();
		$("#selectionSummary").show();
		
		ippiUrl=ippiUrlBase.replace("%FLIGHTS%",strThermal+','+strDynamic);
	} else {
		$("#ippiDropDownID").addClass('secondMenuDropLayer');
		$("#compareIppiLink").hide();
		$("#compareFavoritesText").show();
		$("#selectionSummary").hide();
	}

	
}

$(document).ready(function(){
$("#ippiDropDownID").hide();
$("#favDropDownID").hide();
$("#infobox").hide();
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
	$("#compareIppiLink").live('click',function(){
		$.post("<?=$moduleRelPath?>/EXT_generate_ippi.php", { body: $("#ippiListDiv").html(), flights: $.merge($.cookie("thermalList"), $.cookie("dynamicList")) });
	});

	$(".indexCell .selectThermal").live('click',function() {
		$(this).parent().nextAll().addBack().css("background-color","#ff9933");
		var row=$(this).parent().parent();
		var flightID=row.attr('id').substr(4);
		if ( $(this).children('input').is(':checked') ) {
			addThermal(flightID);
		} else {
			removeThermal(flightID);
			$(this).parent().nextAll().addBack().css("background-color","#bfbfbf");
		}
	});

	$(".indexCell .selectDynamic").live('click',function() {
		$(this).parent().nextAll().addBack().css("background-color","#66ccff");
		var row=$(this).parent().parent();
		var flightID=row.attr('id').substr(4);

		if ( $(this).children('input').is(':checked') ) {
			addDynamic(flightID);
		} else {
			removeDynamic(flightID);
			$(this).parent().nextAll().addBack().css("background-color","#bfbfbf");
		}

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
			<span class='info' id='ippiText'>
			<h2>Wyciąg z księgi lotów na potrzeby IPPI </h2>
			<BR>
			<!-- Select Flights by clicking on the checkbox  -->	
			<p>Funkcjonalność pozwala na wygenerowanie zestawienia lotów potrzebnego w procesie wydania poświadczeń IPPI (Pozycja 8.c z <a href="https://www.aeroklub-polski.pl/wp-content/uploads/2019/02/181214-Regulamin-kart-IPPI_2018.pdf" target="_blank">regulaminu wydawania kart IPPI</a>). Pozostałe formularze jakie trzeba wysłać do AP - <a href="https://www.aeroklub-polski.pl/wp-content/uploads/2019/02/181214-Wniosek-kart-IPPI-_2018.pdf" target="_blank">link</a>.</p><p>W kolumnie z numerami lotów możesz zaznaczyć wybrane loty jako termiczne i żaglowe</p>	
			</span>
			
			<a id='compareIppiLink' class='greenButton' href=''>Wygeneruj zestawienie lotów</a>
			
			<hr>
		</div>	 
		<div id='ippiListDiv'>
			<table id='selectionSummary' >
<?php 
$pilotName = getPilotRealName($pilotIDview,$serverIDview,0);
?>

				<tr><th align="left">Rodzaj lotów</th><th align="center">Liczba lotów</th><th align="center">Czas lotów</th></tr>
				<tr><th align="left">Termika</th><td align="center"  id='numberOfThermalFlights'>0</td><td align="center"  id='timeOfThermalFlights'>00:00</td>
				<tr><th align="left">Żagiel</th><td align="center" id='numberOfDynamicFlights'>0</td><td align="center" id='timeOfDynamicFlights'>00:00</td>
				<tr><th align="left">Suma</th><td align="center" id='totalNumberOfFlights'>0</td><td align="center" id='totalTimeOfFlights'>00:00</td>
			</table>
				<center><h2>Loty termiczne</h2></center>
			<table id='thermalListIppi' width="100%" >
				<tbody>
					<th>Data</th><th>Pilot / Startowisko</th><th></th><th>Skrzydło</th><th>Czas lotu</th><th>Dystans</th><th>G-Record</th><th>Link do lotu</th>
				</tbody>
			</table>
				<center><h2>Loty żaglowe</h2></center>
			<table id='dynamicListIppi' width="100%">
				<tbody>
					<th>Data</th><th>Pilot / Startowisko</th><th></th><th>Skrzydło</th><th>Czas lotu</th><th>Dystans</th><th>G-Record</th><th>Link do lotu</th>
				</tbody>
			</table>
	<p>Zestawienie lotów przgotowane przez <?php echo "$pilotName"; ?>, na bazie lotów zgłoszonych do Polskiego Serwera Leonardo dostępnego pod adresem <a href="https://leonardo.pgxc.pl">https://leonardo.pgxc.pl</a>.</p>
		</div>
	</div>
</div>
