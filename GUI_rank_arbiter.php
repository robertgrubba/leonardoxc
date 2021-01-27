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
	list($add_pilot_server_id,$add_pilot_id)=splitServerPilotStr($_POST['add_pilot_id']);

	$action=$_POST['AdminAction'];
	
	if ($add_pilot_id) $action="add_pilot";

	if ( $action=="add_pilot" ) {		
		$pilotName=getPilotRealName($add_pilot_id,$add_pilot_server_id);
		$resText="Pilot Name: $pilotName -> ";
		if ($pilotName) {
			$query="INSERT INTO $bannedPilotsTable (rank,pilotID,arbiterID,season,cause) VALUES (".$_POST['rank'].",".$add_pilot_id.",".$_POST['arbiterID'].",".$_POST['season'].",\"".$_POST['cause'] ."\")";
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
		list($pilotToRemoveServerID,$pilotToRemove)=splitServerPilotStr($_POST['pilotToRemove']);
		$query="DELETE FROM $bannedPilotsTable WHERE rank=".$_POST['rank']." AND pilotID=".$pilotToRemove." AND season=".$season." ";
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
			$query="INSERT INTO $bannedFlightsTable (rank,flightID,arbiterID,cause) VALUES (".$_POST['rank'].",".$flightID.",".$_POST['arbiterID'].",\"".$_POST['cause'] ."\")";
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
		$query="DELETE FROM $bannedFlightsTable WHERE rank=".$_POST['rank']." AND flightID=".$_POST['flightToRemove']." ";
		$res= $db->sql_query($query);
		if($res <= 0){   
			$resText.="<H3> Błąd przy usuwania lotu z listy! $query</H3>\n";
		} else $resText.="Lot usunięty z wykluczeń ";
	}

}

?>
<script language="javascript">


function addClubPilot() {
	// clubID,pilotID;
	document.clubAdmin.AdminAction.value="add_pilot";
	document.clubAdmin.submit();
/*
	url='/<?=$moduleRelPath?>/EXT_club_functions.php';
	pars='op=add_pilot&clubID='+clubID+'&flightID='+flightID;
	
	var myAjax = new Ajax.Updater('updateDiv', url, {method:'get',parameters:pars});

	newHTML="<a href=\"#\" onclick=\"removeClubFlight("+clubID+","+flightID+");return false;\"><img src='<?=$moduleRelPath?>/img/icon_club_remove.gif' width=16 height=16 border=0 align=bottom></a>";
	div=MWJ_findObj('fl_'+flightID);
	div.innerHTML=newHTML;
	
	//toggleVisible(divID,divPos);
*/
}

function removeClubPilot(pilotID) {
	document.clubAdmin.pilotToRemove.value=pilotID;
	document.clubAdmin.AdminAction.value="remove_pilot";
	document.clubAdmin.submit();
	/*
	url='/<?=$moduleRelPath?>/EXT_club_functions.php';
	pars='op=remove_pilot&clubID='+clubID+'&pilotID='+pilotID;
	
//	var myAjax = new Ajax.Updater('updateDiv', url, {method:'get',parameters:pars});

//	newHTML="<a href=\"#\" onclick=\"addClubFlight("+clubID+","+flightID+");return false;\"><img src='<?=$moduleRelPath?>/img/icon_club_add.gif' width=16 height=16 border=0 align=bottom></a>";
//	div=MWJ_findObj('pl_'+pilotID);
//	toggleVisible(div,divPos);
	MWJ_changeDisplay('pl_'+pilotID,"none");
//	div.innerHTML=newHTML;
	//toggleVisible(divID,divPos);
*/
}
function addBannedFlight() {
	// clubID,pilotID;
	document.flightAdmin.AdminAction.value="add_flight";
	document.flightAdmin.submit();
/*
	url='/<?=$moduleRelPath?>/EXT_club_functions.php';
	pars='op=add_pilot&clubID='+clubID+'&flightID='+flightID;
	
	var myAjax = new Ajax.Updater('updateDiv', url, {method:'get',parameters:pars});

	newHTML="<a href=\"#\" onclick=\"removeClubFlight("+clubID+","+flightID+");return false;\"><img src='<?=$moduleRelPath?>/img/icon_club_remove.gif' width=16 height=16 border=0 align=bottom></a>";
	div=MWJ_findObj('fl_'+flightID);
	div.innerHTML=newHTML;
	
	//toggleVisible(divID,divPos);
*/
}

function removeBannedFlight(flightID) {
	document.flightAdmin.flightToRemove.value=flightID;
	document.flightAdmin.AdminAction.value="remove_flight";
	document.flightAdmin.submit();
	/*
	url='/<?=$moduleRelPath?>/EXT_club_functions.php';
	pars='op=remove_pilot&clubID='+clubID+'&pilotID='+pilotID;
	
//	var myAjax = new Ajax.Updater('updateDiv', url, {method:'get',parameters:pars});

//	newHTML="<a href=\"#\" onclick=\"addClubFlight("+clubID+","+flightID+");return false;\"><img src='<?=$moduleRelPath?>/img/icon_club_add.gif' width=16 height=16 border=0 align=bottom></a>";
//	div=MWJ_findObj('pl_'+pilotID);
//	toggleVisible(div,divPos);
	MWJ_changeDisplay('pl_'+pilotID,"none");
//	div.innerHTML=newHTML;
	//toggleVisible(divID,divPos);
*/
}
</script>

<?
	$legend="Panel sędziego rankingu";
	echo  "<div class='tableTitle'>
	<div class='titleDiv'>$legend</div>
	<div class='pagesDivSimple'>$legendRight</div>
	</div>" ;
	if ($resText) {
		echo "<div id='updateDiv' style='display:block; background-color:#EBE6DA;padding:5px; font-weight:bold;'>$resText</div>";
	}
?>
<div style="display: flex">
	<div>
		<!-- formularz wykluczania pilotow -->
		<form name="clubAdmin" method="post" action="">
		<table width="100%" border="0" cellpadding="3" class="main_text">
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
				echo "<div id='pl_$pilotID'>".$row['created']." $pilotName ($pilotID) : ".$row['cause']." (przez ".getPilotRealName($row['arbiterID'],0,0,2).") <a href='javascript:removeClubPilot(\"$pilotID\");'>Remove pilot</a></div>"; 
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
		<table width="100%" border="0" cellpadding="3" class="main_text">
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
					echo "<div id='fl_$flightID'>$flightID : <a href='javascript:removeBannedFlight(\"$flightID\");'>Remove flight</a></div>"; 
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
</div>
</div>