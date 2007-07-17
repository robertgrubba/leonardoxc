<? 
/* 
transactionID  autoincrement

actionTime   the tm of the action
userID  - the user that commits the action
effectiveUserID  - the user for whom the action is taken
ItemType - on an item that is of type :

1 => flight
2 => pilot
4 => waypoint
8 => NAC / club  / group
16=> League / event 
32=> Area ( group of waypoints ) 

ItemID - the item 
ServerItemID - the server of the item -> those 2 define an item in the distributed DB network

ActionID  - what the user does 
1  => add
2  => edit
4  => delete
8  => Score  (flight only)
16 => Create charts (flight only)
32 => Create Map (flight only)
64 => 
128=>
256=>
512=>

ActionXML - XML that describes the action so it
			 can be reproduced later/in another server

Modifier
0=> nothing special
1=> Club ( ie   user adds pilot to club  )
2=> League / event  ( ie user adds flight to event ) 
4=> Area  ( ie   user adds waypoint to area )


ModifierID - ie clubId of LeagueID
ServerModifierID - the server on which the the extra item resides

Result 
0=> Problem  (initial)
1=> OK
2=> Pending

Result Description  - if any furthe info needs to logged (ie in cae of error)



*/
require_once dirname(__FILE__).'/FN_functions.php';
require_once dirname(__FILE__).'/FN_flight.php';
require_once dirname(__FILE__).'/CL_pilot.php';

class logReplicator { 

	function logReplicator() {
	}



	function checkPilot($serverID,$pilotArray){
		/*  [pilot] => Array
			(
				[userID] => 347
				[userName] => 
				[pilotFirstName] => �������
				[pilotLastName] => ����������
				[pilotCountry] => gr
				[pilotBirthdate] => 
				[pilotSex] => 
			)
		*/
		$update=1;
		$pilot=new pilot($serverID,$pilotArray['userID']) ;
		$pilot->createDirs();

		if ( ! $pilot->pilotExists() ) {			
			$update=0;
		}

		$pilot->pilotID =$pilotArray['userID'];
		//$pilot->FirstName=$pilotArray['userName'];
		$pilot->FirstName=$pilotArray['pilotFirstName'];
		$pilot->LastName=$pilotArray['pilotLastName'];
		$pilot->countryCode =$pilotArray['pilotCountry'];
		$pilot->Birthdate=$pilotArray['pilotBirthdate'];
		$pilot->Sex=$pilotArray['pilotSex'];

		$pilot->putToDB($update);
	}
	
	function findFlight($serverID,$flightIDoriginal) {
	  global $db,$flightsTable;
	  
	  $query="SELECT * FROM $flightsTable  WHERE original_ID=$flightIDoriginal AND serverID=$serverID";

	  $res= $db->sql_query($query);	
	  # Error checking
	  if($res <= 0){
		 echo("<H3> Error in findFlight query! $query</H3>\n");
		 exit();
	  }
		
	  if (! $row = $db->sql_fetchrow($res) ) {
		  return 0;	  
	  } else {
	  	return $row['ID'];
	  }
	  
	}
	
	function processEntry($serverID,$e) {
		global $flightsAbsPath;
		echo "<PRE>";
		print_r($e);
		echo "</PRE>";
		if ($e['type']=='1') { // flight

			//	check 'alien' pilot  and insert him or update him anyway
			$userServerID=$e['ActionXML']['flight']['serverID'];
			if ($userServerID==0)  $userServerID=$serverID;	
			$userID=$userServerID.'_'.$e['ActionXML']['flight']['pilot']['userID'];
			logReplicator::checkPilot($userServerID,$e['ActionXML']['flight']['pilot']);
	
			if ($e['action']==1) {	// add
				$igcFilename=$e['ActionXML']['flight']['filename'];
				$igcFileURL	=$e['ActionXML']['flight']['linkIGC'];
				$tempFilename=$flightsAbsPath.'/'.$igcFilename;
	

				$is_private	=$e['ActionXML']['flight']['info']['private'];
				$gliderCat	=$e['ActionXML']['flight']['info']['gliderCat'];
				$linkURL	=$e['ActionXML']['flight']['info']['linkURL'];
				$comments	=$e['ActionXML']['flight']['info']['comments'];
				$glider		=$e['ActionXML']['flight']['info']['glider'];
				$category	=$e['ActionXML']['flight']['info']['cat'];
				if (!$igcFileStr=fetchURL($igcFileURL,20) ) {
					echo "logReplicator::processEntry() : Cannot Fetch $igcFileURL<BR>";
					return 0;
				}
				$argArray=array("dateAdded"		=>$e['ActionXML']['flight']['dateAdded'],
								"originalURL"	=>$e['ActionXML']['flight']['linkDisplay'],
								"original_ID"	=>$e['ActionXML']['flight']['id'],
								"serverID"		=>$e['ActionXML']['flight']['serverID'],
								"userServerID"	=>$e['ActionXML']['flight']['serverID'],
								"originalUserID"=>$e['ActionXML']['flight']['pilot']['userID'],
				);
	
				writeFile($tempFilename,$igcFileStr);
				list( $res,$flightID)=addFlightFromFile($tempFilename,0,$userID,
								$is_private,$gliderCat,$linkURL,$comments,$glider, $category,$argArray);
				if ($res!=1) { 
					echo "Problem: ".getAddFlightErrMsg($res,$flightID)."<BR>";
				} else { 
					echo "flight pulled OK with local ID $flightID<BR>";
				}
			} else if ($e['action']==2) {	// edit / update
				$flightIDlocal=logReplicator::findFlight($e['ActionXML']['flight']['serverID'],$e['ActionXML']['flight']['id']);
				if (!$flightIDlocal) {
					echo "logReplicator::processEntry : Flight with serverID ".$e['ActionXML']['flight']['serverID']." an original ID : ".
							$e['ActionXML']['flight']['id']." is not found in the local DB -> Wont update<BR>";
					return;
				}
				echo "Will update flight $flightIDlocal<BR>";
				
				$extFlight=new flight();
				$extFlight->getFlightFromDB($flightIDlocal);
				
				$extFlight->glider	=$e['ActionXML']['flight']['info']['glider'];
				$extFlight->cat		=$e['ActionXML']['flight']['info']['gliderCat'];
				$extFlight->category=$e['ActionXML']['flight']['info']['cat'];
				$extFlight->linkURL =$e['ActionXML']['flight']['info']['linkURL'];
				$extFlight->private	=$e['ActionXML']['flight']['info']['private'];
				$extFlight->comments=$e['ActionXML']['flight']['info']['comments'];
				
				$extFlight->DATE =$e['ActionXML']['flight']['time']['date'];
				$extFlight->timezone =$e['ActionXML']['flight']['time']['Timezone'];
				$extFlight->START_TIME =$e['ActionXML']['flight']['time']['StartTime'];
				$extFlight->DURATION =$e['ActionXML']['flight']['time']['Duration'];
				$extFlight->END_TIME=$extFlight->START_TIME+$extFlight->DURATION;
				
				/*
				if (0) {
					$extFlight-> =$e['ActionXML']['flight']['location']['takeoffID'];
					$extFlight-> =$e['ActionXML']['flight']['location']['serverID'];
					$extFlight-> =$e['ActionXML']['flight']['location']['takeoffVinicity'];
					$extFlight-> =$e['ActionXML']['flight']['location']['takeoffName'];
					$extFlight-> =$e['ActionXML']['flight']['location']['takeoffNameInt'];
					$extFlight-> =$e['ActionXML']['flight']['location']['takeoffCountry'];
				}
				*/
				
				if ($getValidationData) {
					$extFlight->validated =$e['ActionXML']['flight']['validation']['validated'];
					$extFlight->grecord =$e['ActionXML']['flight']['validation']['grecord'];
					$extFlight->validationMessage =$e['ActionXML']['flight']['validation']['validationMessage'];
					$extFlight->airspaceCheck =$e['ActionXML']['flight']['validation']['airspaceCheck'];
					$extFlight->airspaceCheckFinal =$e['ActionXML']['flight']['validation']['airspaceCheckFinal'];
					$extFlight->airspaceCheckMsg =$e['ActionXML']['flight']['validation']['airspaceCheckMsg'];
				}
				
				if ( $getScoreData ) {
					$extFlight->BEST_FLIGHT_TYPE=$e['ActionXML']['flight']['stats']['FlightType'];
					$extFlight->LINEAR_DISTANCE	=$e['ActionXML']['flight']['stats']['StraightDistance'];
					$extFlight->FLIGHT_KM	=$e['ActionXML']['flight']['stats']['XCdistance'];
					$extFlight->FLIGHT_POINTS=$e['ActionXML']['flight']['stats']['XCscore'];
					$extFlight->MAX_SPEED	=$e['ActionXML']['flight']['stats']['MaxSpeed'];
					$extFlight->MAX_VARIO	=$e['ActionXML']['flight']['stats']['MaxVario'];
					$extFlight->MIN_VARIO	=$e['ActionXML']['flight']['stats']['MinVario'];
					$extFlight->MAX_ALT		=$e['ActionXML']['flight']['stats']['MaxAltASL'];
					$extFlight->MIN_ALT		=$e['ActionXML']['flight']['stats']['MinAltASL'];
					$extFlight->TAKEOFF_ALT	=$e['ActionXML']['flight']['stats']['TakeoffAlt'];
				}
				
				$extFlight->putFlightToDB(1);
				
			} else if ($e['action']==4) {	// edit / update
				$flightIDlocal=logReplicator::findFlight($e['ActionXML']['flight']['serverID'],$e['ActionXML']['flight']['id']);
				if (!$flightIDlocal) {
					echo "logReplicator::processEntry : Flight with serverID ".$e['ActionXML']['flight']['serverID']." an original ID : ".
							$e['ActionXML']['flight']['id']." is not found in the local DB -> Wont delete it<BR>";
					return;
				}
				echo "Will delete flight $flightIDlocal<BR>";
				
				$extFlight=new flight();			
				$extFlight->deleteFlight();			
			}
		}		
	}


} // end of class

?>