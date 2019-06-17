<?php
require_once dirname(__FILE__)."/EXT_config_pre.php";
require_once dirname(__FILE__)."/config.php";
require_once dirname(__FILE__)."/EXT_config.php";
require_once dirname(__FILE__).'/FN_functions.php';
require_once dirname(__FILE__).'/FN_waypoint.php';
require_once dirname(__FILE__).'/FN_flight.php';
require_once dirname(__FILE__).'/CL_pilot.php';
require_once dirname(__FILE__).'/CL_mail.php';
require_once dirname(__FILE__)."/CL_pdf.php";
require_once dirname(__FILE__)."/CL_flightData.php";
require_once dirname(__FILE__)."/CL_user.php";

echo "OK";
$thermal = explode(',',$_POST['thermal']);
$dynamic = explode(',',$_POST['dynamic']);
$flights = array_merge($thermal,$dynamic);//explode(',',$_POST['flights']);

if(sizeof($flights)<2){
	error_log("problem with body and flights array".print_r($body,true).'  '.print_r($flights,true));
	http_response_code(400);
	die();
}

$url="";
$now = getdate();
$flight = new flight();
foreach ($flights as $key => $val){
	if(!isset($uid)){
		$flight->getFlightFromDB($val+0);
		if(isset($flight->userID)){
			$uid=$flight->userID;
		}else{
				error_log(print_r($body,true));
				error_log(print_r($flights,true));
				http_response_code(400);
				die();
		}	
		
	}else{
		$flight->getFlightFromDB($val+0);
		if(isset($flight->userID)){
			$nuid=$flight->userID; 
		
			if ($uid != $nuid){
				error_log(print_r($body,true));
				error_log(print_r($flights,true));
				http_response_code(400);
				die();
			}else{	
//				echo "flightid: $val userid: $uid";
			}
		}else{
			error_log(print_r($body,true));
			error_log(print_r($flights,true));
			http_response_code(400);
			die();
		}
		
	}
		
}
$body='<head><meta charset="UTF-8"><style type="text/css">th, td{ text-align: center;} table{ width:100%;} p{ font-size: 12px;}</style></head>';
$body.='<body><p>Wyciąg z księgi lotów użytkownika <a href="https://leonardo.pgxc.pl">Polskiego Serwera LeonardoXC</a>.</p>';
$body.='<center><h4>Loty termiczne</h4></center>';
$body.='<table style="font-size: 10px; "><tr><th>lp</th><th>Pilot</th><th>Data</th><th>Startowisko</th><th>Czas lotu</th><th>XC km</th><th>G-Record</th><th>link</th></tr>';
$totalTime=0;
$totalThermal=0;
foreach ($thermal as $key => $val){
	$num = (int)($key+1);
	$flight->getFlightFromDB($val+0);
	$distance = formatDistance($flight->FLIGHT_KM,1);
	$duration = sec2Time($flight->DURATION);
	$totalTime+=$flight->DURATION;
	$totalThermal+=$flight->DURATION;
	$pilot=getPilotRealName($flight->userID,0);
	$takeoff=getWaypointName($flight->takeoffID);
	if ($flight->validated == 0) $grecord="&#10007;";
	if ($flight->validated == 1) $grecord="&#10004;";
	if ($flight->validated != 1 && $flight->grecord!=0) $grecord="&quest;";
	$body.="<tr><td>$num</td><td>$pilot</td><td>$flight->DATE</td><td>$takeoff</td><td>$duration</td><td>$distance</td><td>$grecord</td><td><a href='https://leonardo.pgxc.pl/lot/$val'>https://leonardo.pgxc.pl/lot/$val</a></td></tr>";
}
$duration = sec2Time($totalThermal);
	$body.="<tr ><td ></td><td ></td><td></td><td ></td><td ><b>$duration</b></td><td ></td><td></td><td></td></tr>";
$body.='</table>';
$totalNum = $num;
$body.='<center><h4>Loty żaglowe</h4></center>';
$body.='<table style="font-size: 10px; "><tr><th>lp</th><th>Pilot</th><th>Data</th><th>Startowisko</th><th>Czas lotu</th><th>XC km</th><th>G-Record</th><th>link</th></tr>';
$totalDynamic=0;
foreach ($dynamic as $key => $val){
	$num = (int)($key+1);
	$flight->getFlightFromDB($val+0);
	$distance = formatDistance($flight->FLIGHT_KM,1);
	$duration = sec2Time($flight->DURATION);
	$totalTime+=$flight->DURATION;
	$totalDynamic+=$flight->DURATION;
	$pilot=getPilotRealName($flight->userID,0);
	$takeoff=getWaypointName($flight->takeoffID);
	if ($flight->validated == 0) $grecord="&#10007;";
	if ($flight->validated == 1) $grecord="&#10004;";
	if ($flight->validated != 1 && $flight->grecord!=0) $grecord="&quest;";
	$body.="<tr><td>$num</td><td>$pilot</td><td>$flight->DATE</td><td>$takeoff</td><td>$duration</td><td>$distance</td><td>$grecord</td><td><a href='https://leonardo.pgxc.pl/lot/$val'>https://leonardo.pgxc.pl/lot/$val</a></td></tr>";
}
$duration = sec2Time($totalDynamic);
	$body.="<tr ><td ></td><td ></td><td></td><td ></td><td ><b>$duration</b></td><td ></td><td></td><td></td></tr>";
$body.='</table>';
$totalNum=sizeof($flights);
$totalAirtime=sec2Time($totalTime);
$body.="<p>W powyższej tabeli zgłoszono $totalNum lotów, o łącznym nalocie $totalAirtime.</p>";
$body.="</body></html>";
file_put_contents($CONF['pdf']['tmpPath']."/".$now[0],$body);
$url = $CONF['links']['baseURL'].'/'.$CONF['pdf']['tmpPathRel'].'/'.$now[0];
$url = 'http://leonardo.pgxc.pl/'.$CONF['pdf']['tmpPathRel'].'/'.$now[0];
$email = LeoUser::getEmail($uid);
$result = LeoPdf::createPDF($url);
unlink($CONF['pdf']['tmpPath']."/".$now[0]);
$hashkey=md5($url);
$link = $CONF['links']['baseURL'].'/'.$CONF['pdf']['tmpPathRel'].'/'.$hashkey.'.pdf';
$message= <<<EOT
Wygenerowane zestawienie lotów na potrzeby karty IPPI (https://www.aeroklub-polski.pl/licencje/karty-ippi/) znajdziesz pod adresem: $link.

Pobierz plik już teraz, ponieważ o północy zostanie on usunięty na zawsze.

Pozdrawiamy,
Zespół Polskiego Serwera Leonardo

EOT;
LeonardoMail::sendMail('Zestawienie lotow IPPI',$message, $email,$fromMail='no-reply@pgxc.pl',$fromName='PGXC.pl',$isHtml=false);
http_response_code(200);




