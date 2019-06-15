<?php
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

$body = $_POST['body'];
$flights = explode(',',$_POST['flights']);
if($body=="" or sizeof($flights)<2){
	die();
}
$url="";
$now = getdate();

file_put_contents('.'.$CONF['pdf']['tmpPath']."/".$now[0],$body." ".$flights);
$url = $CONF['links']['baseURL'].'/'.$CONF['pdf']['tmpPathRel'].'/'.$now[0];
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
$email = LeoUser::getEmail($uid);
//echo "url: $url";
$result = LeoPdf::createPDF($url);
unlink('.'.$CONF['pdf']['tmpPath']."/".$now[0]);
//echo "Result: $result ";
$hashkey=md5($url);
//echo "hashkey: $hashkey";
$link = $CONF['links']['baseURL'].'/'.$CONF['pdf']['tmpPathRel'].'/'.$hashkey.'.pdf';
LeonardoMail::sendMail('Zestawienie lotow IPPI','Zestawienie lotów na potrzeby karty IPPI (https://www.aeroklub-polski.pl/licencje/karty-ippi/) znajdziesz pod adresem: '.$link.'\n\nPobierz plik ponieważ w ciągu 24 godzin od utworzenia zostanie on usunięty na zawsze. \n\nPozdrawiamy,\nZespół Polskiego Serwera Leonardo', $email,$fromMail='no-reply@pgxc.pl',$fromName='PGXC.pl',$isHtml=false);
http_response_code(200);




