<?php
// Leonardo XC Server, http://www.leonardoxc.net
//
// Copyright (c) 2004-2010 by Andreadakis Manolis
//
// This program is free software. You can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License.
//
// $Id: GUI_club_admin.php,v 1.11 2010/03/14 20:56:11 manolis Exp $                                                                 
//
//************************************************************************
$season=makeSane($_GET['season']);
$rankID=makeSane($_GET['rank_to_arbiter_id']);
if ( ! L_auth::isRankArbiter($userID,$rankID) && !L_auth::isAdmin($userID) ) { echo "go away"; return; }

$pilotsList=array();
$pilotsID=array();

//list($takeoffs,$takeoffsID)=getTakeoffList();
//list($countriesCodes,$countriesNames)=getCountriesList();

if ($_POST['formPosted']) {
//	$add_pilot_id=$_POST['add_pilot_id'];
	list($add_pilot_server_id,$add_pilot_id)=splitServerPilotStr(makeSane($_POST['add_pilot_id']));

	$action=$_POST['AdminAction'];
	
	if ($add_pilot_id) $action="add_pilot";

	if ( $action=="add_pilot" ) {		
		$pilotName=getPilotRealName($add_pilot_id,$add_pilot_server_id);
		$resText="Pilot Name: $pilotName -> ";
		if ($pilotName) {
			$query="INSERT INTO $bannedPilotsTable (rank,pilotID,arbiterID,season,cause) VALUES (".makeSane($_POST['rank']).",".$add_pilot_id.",".makeSane($_POST['arbiterID']).",".makeSane($_POST['season']).",\"".$_POST['cause'] ."\")";
			$res= $db->sql_query($query);
			if($res <= 0){   
				$resText.="Ten pilot już jest wykluczony ";
				$resText.=$query;
			} $resText.="Pilot wykluczony ";
		} else {
			$resText.="Nie ma takiego ID pilota ";
		}
	} else if ( $action=="remove_pilot" ) {
		// $pilotToRemove=$_POST['pilotToRemove'];
		list($pilotToRemoveServerID,$pilotToRemove)=splitServerPilotStr(makeSane($_POST['pilotToRemove']));
		$query="DELETE FROM $bannedPilotsTable WHERE rank=".makeSane($_POST['rank'])." AND pilotID=".$pilotToRemove." AND season=".$season." ";
		$res= $db->sql_query($query);
		if($res <= 0){   
			$resText.="<H3> Błąd przy usuwania pilota z listy! $query</H3>\n";
		} else $resText.="Pilot usunięty z wykluczeń ";
	}


	if ( $action=="add_flight" ) {		
//		$pilotName=getPilotRealName($add_pilot_id,$add_pilot_server_id);
		$flightID=makeSane($_POST['flightID']);
		$resText="Lot: $flightID -> ";
		if ($flightID) {
			$query="INSERT INTO $bannedFlightsTable (rank,flightID,arbiterID,cause) VALUES (".makeSane($_POST['rank']).",".$flightID.",".makeSane($_POST['arbiterID']).",\"".$_POST['cause']."\")";
			$res= $db->sql_query($query);
			if($res <= 0){   
				$resText.="Ten lot już jest wykluczony ";
				$resText.=$query;
			} $resText.="Lot wykluczony ";
		} else {
			$resText.="Nie podano ID lotu ";
		}
	} else if ( $action=="remove_flight" ) {
		// $pilotToRemove=$_POST['pilotToRemove'];
		$query="DELETE FROM $bannedFlightsTable WHERE rank=".makeSane($_POST['rank'])." AND flightID=".makeSane($_POST['flightToRemove'])." ";
		$res= $db->sql_query($query);
		if($res <= 0){   
			$resText.="<H3> Błąd przy usuwania lotu z listy! $query</H3>\n";
		} else $resText.="Lot usunięty z wykluczeń ";
	}
  if(L_auth::isAdmin($userID)){
	if ( $action=="add_takeoff" ) {		
		$takeoffID=makeSane($_POST['takeoffID']);
		$resText="Startowisko: $takeoffID -> ";
		if ($takeoffID) {
			$query="INSERT INTO $rankTakeoffsTable (rankID,takeoffID) VALUES (".makeSane($_POST['rank']).",".$takeoffID.")";
			$res= $db->sql_query($query);
			if($res <= 0){   
				$resText.="Ten to startowiso jest już dodane ";
				$resText.=$query;
			} $resText.="Startowisko dodane ";
		} else {
			$resText.="Nie podano ID Startowiska ";
		}
	} else if ( $action=="remove_takeoff" ) {
		// $pilotToRemove=$_POST['pilotToRemove'];
		$takeoffID=makeSane($_POST['takeoffToRemove']);
		$rankID=makeSane($_POST['rankID']);
		$query="DELETE FROM $rankTakeoffsTable WHERE rankID=$rankID AND takeoffID=$takeoffID ";
		$res= $db->sql_query($query);
		if($res <= 0){   
			$resText.="<H3> Błąd przy usuwania startowiska z listy! $query</H3>\n";
		} else $resText.="Startowisko usunięte z rankingu ";
	}
  }



}

?>
<script language="javascript">


function addClubPilot() {
	// clubID,pilotID;
	document.clubAdmin.AdminAction.value="add_pilot";
	document.clubAdmin.submit();
}

function removeClubPilot(pilotID) {
	document.clubAdmin.pilotToRemove.value=pilotID;
	document.clubAdmin.AdminAction.value="remove_pilot";
	document.clubAdmin.submit();
}
function addBannedFlight() {
	// clubID,pilotID;
	document.flightAdmin.AdminAction.value="add_flight";
	document.flightAdmin.submit();
}

function removeBannedFlight(flightID) {
	document.flightAdmin.flightToRemove.value=flightID;
	document.flightAdmin.AdminAction.value="remove_flight";
	document.flightAdmin.submit();
}
function addTakeoff() {
	// clubID,pilotID;
	document.takeoffAdmin.AdminAction.value="add_takeoff";
	document.takeoffAdmin.submit();
}

function removeTakeoff(takeoffID) {
	document.takeoffAdmin.takeoffToRemove.value=takeoffID;
	document.takeoffAdmin.AdminAction.value="remove_takeoff";
	document.takeoffAdmin.submit();
}
</script>

<?
	$legend="Panel sędziego rankingu $rank w sezonie $season";
	echo  "<div class='tableTitle'>
	<div class='titleDiv'>$legend</div>
	<div class='pagesDivSimple'>$legendRight</div>
	</div>" ;
	if ($resText) {
		echo "<div id='updateDiv' style='display:block; background-color:#EBE6DA;padding:5px; font-weight:bold;'>$resText</div>";
	}
?>
<div style="display: flex">
<? if(L_auth::isAdmin($userID)){
?>
	<div>
		<!-- formularz zarzadania startowiskami -->
		<form name="takeoffAdmin" method="post" action="">
		<table width="100%" border="0" cellpadding="3" class="main_text" style="width: 250px">
		  <tr>
		    <td><p>
		      <label> ID startowiska do dodania
			<input name="takeoffID" type="text" id="takeoffID" required/>
		      </label>
		      </p>
		      <p>
			<label>
			<input name="Add takeoff" type="button" id="Add takeoff" value="Dodaj startowisko" onclick="javascript:addTakeoff();"/>
			</label>
		      </p>
		      <p><strong>Startowiska w rankingu</strong></p>
		      <?
		  
				$query="SELECT rankID,takeoffID FROM $rankTakeoffsTable WHERE rankID=".$rank." ";
				$res= $db->sql_query($query);
				while($row = mysql_fetch_assoc($res)){
				echo "<div id='tk_$takeoffID'>".$row['takeoffID']." <a target='_blank' href='".getLeonardoLink(array('op'=>'show_waypoint','waypointIDview'=>$row['takeoffID']))."'> ".getWaypointName($row['takeoffID'])."</a> <a href='javascript:removeTakeoff(\"".$row['takeoffID']."\");'>[x]</a></div>"; 
}
		?></td>
		    <td><p>
		      <label></label>
		    </p>      </td>
		  </tr>
		</table>



		<input name="formPosted" type="hidden" value="1" />
		<input name="rankID" type="hidden" value="<?=$rank?>"/> 	
		<input name="AdminAction" type="hidden" value="0" />
		<input name="takeoffToRemove" type="hidden" value="0" />

		</form>
	</div>
<? } ?>
	<div>
		<!-- formularz wykluczania pilotow -->
		<form name="clubAdmin" method="post" action="">
		<table width="100%" border="0" cellpadding="3" class="main_text" style="width: 250px">
		  <tr>
		    <td><p>
		      <label> ID pilota do wykluczenia
			<input name="add_pilot_id" type="text" id="add_pilot_id" required/>
		      </label>
		      </p>
		      <p>
		      <label> Przyczyna wykluczenia
			<input name="cause" type="text" id="cause" required />
			</label>
			</p>
		      <p>
			<label>
			<input name="Add pilot" type="button" id="Add pilot" value="Wyklucz pilota" onclick="javascript:addClubPilot();"/>
			</label>
		      </p>
		      <p><strong>Wykluczeni Piloci</strong></p>
		      <?
		  
			//echo "<BR>";
			//open_inner_table("Administer CLub/League",730,"icon_home.gif"); echo "<tr><td>";
			list($pilots,$pilotsID)=getBannedPilotList($rankID,$season);
			$i=0;
			foreach ($pilots as $pilotName ){
				$pilotID=$pilotsID[$i++];
				list($bannedUserServerID,$pilotID)=splitServerPilotStr($pilotID);
				$query="SELECT arbiterID,cause,created FROM $bannedPilotsTable WHERE pilotID=".$pilotID." AND season=".$season." AND rank=".$rank." ";
				$res= $db->sql_query($query);
				$row = mysql_fetch_assoc($res);
				echo "<div id='pl_$pilotID'>".$row['created']." <a target='_blank' href='".getLeonardoLink(array('op'=>'pilot_profile_stats','pilotID'=>'0_'.$pilotID, 'year'=>'0','month'=>'0','takeoffID'=>'0','country'=>'0','cat'=>'0','season'=>'0'))."'> $pilotName </a> ($pilotID) : ".$row['cause']." (przez ".getPilotRealName($row['arbiterID'],0,0,2).") <a href='javascript:removeClubPilot(\"$pilotID\");'>[x]</a></div>"; 
			}
		?></td>
		    <td><p>
		      <label></label>
		    </p>      </td>
		  </tr>
		</table>



		<input name="formPosted" type="hidden" value="1" />
		<input name="season" type="hidden" value="<?=$season?>" />
		<input name="arbiterID" type="hidden" value="<?=$userID?>"/> 	
		<input name="rank" type="hidden" value="<?=$rank?>"/> 	
		<input name="AdminAction" type="hidden" value="0" />
		<input name="pilotToRemove" type="hidden" value="0" />

		</form>
	</div>
	<div>
	<!-- formularz usuwania lotow -->
		<form name="flightAdmin" method="post" action="">
		<table width="100%" border="0" cellpadding="3" class="main_text" style="width: 250px">
		  <tr>
		    <td><p>
		      <label>ID lotu do wykluczenia
			<input name="flightID" type="text" id="flightID" required />
		      </label>
		      </p>
		      <p>
		      <label>Przyczyna wykluczenia
			<input name="cause" type="text" id="cause" required />
		      </label>
		      </p>
		      <p>
			<label>
			<input name="Add flight" type="button" id="Add flight" value="Wyklucz lot" onclick="javascript:addBannedFlight();"/>
			</label>
		      </p>
		      <p><strong>Loty wykluczone z rankingu </strong></p>
		      <?
		  
				$query="SELECT flightID,arbiterID,cause,created FROM $bannedFlightsTable WHERE rank=".$rank." ";
				$res= $db->sql_query($query);
				while($row = mysql_fetch_assoc($res)){
					$flightID=$row['flightID'];
					echo "<div id='fl_$flightID'>".$row['created']." <a target='_blank' href='".getLeonardoLink(array('op'=>'show_flight','flightID'=>$flightID))."'>Lot nr $flightID</a> ".$row['cause']." (przez ".getPilotrealName($row['arbiterID'],0,0,2).") <a href='javascript:removeBannedFlight(\"$flightID\");'>[x]</a></div>"; 
				}
		?></td>
		    <td><p>
		      <label></label>
		    </p>      </td>
		  </tr>
		</table>

		<input name="formPosted" type="hidden" value="1" />
		<input name="AdminAction" type="hidden" value="0" />
		<input name="flightToRemove" type="hidden" value="0" />
		<input name="arbiterID" type="hidden" value="<?=$userID?>"/> 	
		<input name="rank" type="hidden" value="<?=$rank?>"/> 	
		</form>
	</div>
	<!-- Podejrzenie naruszenia strefy -->
		<table width="100%" border="0" cellpadding="3" class="main_text" >
		  <tr>
		    <td><p>
		      <p><strong>Podejrzenie naruszenia stref</strong></p>
		  <?
		 		$SEASON_START=$ranksList["$rank"]['seasons']['seasons'][$season]['start'];
		 		$SEASON_END=$ranksList["$rank"]['seasons']['seasons'][$season]['end'];
				$query="SELECT ID,takeoffID,userID,DATE FROM $flightsTable WHERE 1=1 "
					."AND takeoffID IN (select takeoffID from $rankTakeoffsTable where rankID=".$rank.") "
					."AND airspaceCheck=-1 "
					."AND DATE <= STR_TO_DATE('".$SEASON_END."','%Y-%m-%d') "
					."AND DATE >= STR_TO_DATE('".$SEASON_START."','%Y-%m-%d') "
					."ORDER BY ID DESC ";
				$res= $db->sql_query($query);
				while($row = mysql_fetch_assoc($res)){
					$flightID=$row['ID'];
					$takeoffID=$row['takeoffID'];
					echo "<div id='fl_$flightID'>".$row['DATE']." <a target='_blank' href='".getLeonardoLink(array('op'=>'show_flight','flightID'=>$flightID))."&rank=$rank'>Lot nr $flightID</a> ".$row['cause']." z <a target='_blank' href='".getLeonardoLink(array('op'=>'show_waypoint','waypointIDview'=>$takeoffID))."'>".getWaypointName($takeoffID)."</a> (przez ".getPilotrealName($row['userID'],0,0,2).") </div>"; 
				}
		?></p></td>
		    <td><p>
		      <label></label>
		    </p>      </td>
		  </tr>
		</table>
	</div>
</div>
</div>
