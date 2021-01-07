<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">

<meta http-equiv="content-language" content="PL"/>
<title>leonardo.pgxc.pl - Tabela Rankingowa na Polskim Serwerze LeonardoXC</title>
<meta name="keywords" content="tabela, rankingowa, leoanrdoXC"/>
<meta name="description" content="Tabela Rankingowa LeonardoXC"/>
<meta name="author" content=""/>
<meta name="identifier-url" content=""/>
<meta name="reply-to" content=""/>
<meta name="revisit-after" content="14 days"/>
<meta name="category" content="Sports"/>
<meta name="copyright" content="https://leonardo.pgxc.pl"/>
<meta name="generator" content=""/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="robots" content=""/>
<meta name="distribution" content="global"/>
<meta name="date-creation-yyyymmdd" content=""/>
<meta name="date-revision-yyyymmdd" content=""/>
<meta name="geo.position" content=""/>
<meta name="ICBM" content=""/>
<base target="_parent">



<link rel="stylesheet" href="/templates/pgxc/tpl/style.css" type="text/css">
<style>
<!--
body {
	background-color:#a1a1a1;
	background-image:url(/templates/pgxc/tpl/pagebg.gif);
	background-repeat:repeat-x;
	margin:0;
}
-->
</style>
<!--[if IE]>
<style type="text/css">
/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
@import url("/templates/pgxc/tpl/formIE.css");
</style>
<![endif]-->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-111088592-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-111088592-1');
</script>

<link rel="stylesheet" type="text/css" href="https://leonardo.pgxc.pl/js/cookieconsent.min.css" />
<script src="https://leonardo.pgxc.pl/js/cookieconsent.min.js"></script>
<script>
window.addEventListener("load", function(){
window.cookieconsent.initialise({
  "palette": {
    "popup": {
      "background": "#efefef",
      "text": "#404040"
    },
    "button": {
      "background": "#8ec760",
      "text": "#ffffff"
    }
  },
  "position": "bottom-right",
  "content": {
    "message": "Portal wykorzystuje pliki cookies, potrzebne są one także do poprawnego działania powiązanych usług Google. Korzystając ze strony wyrażasz zgodę na wykorzystywanie plików cookies.\n",
    "dismiss": "OK!",
    "link": "dowiedz się więcej",
    "href": "http://pgxc.pl/cookie-policy/"
  }
})});
</script>
</head>
<body topmargin="0"  bottommargin="0" bgcolor="#a1a1a1" text="#" link="#" vlink="#">
<a name="top"></a>
<!-- jako tabela
<table id="mainBodyTable" class='mainBodyTable' >
	<tr>
		<td class="bodyline" align="left" valign="top">
-->
<link href="/templates/pgxc/style.css" rel="stylesheet" type="text/css">
<link href="/templates/pgxc/width.css" rel="stylesheet" type="text/css">
<style>
.mainBodyTable {  
	border:0;
	border-left-color:#000000; border-left-style:solid; border-left-width:2px; 
	border-right-color:#000000; border-right-style:solid; border-right-width:2px; 
	margin-bottom:0px;
}

</style>

<script src="/js/google_maps/jquery.js"></script>
<script src="/js/jquery.cookie.js"></script>

<script src="/js/jqModal.js"></script>
<script src="/js/DHTML_functions.js"></script>

<link rel="stylesheet" type="text/css" href="/templates/pgxc/style_second_menu.css">
<link rel="stylesheet" type="text/css" href="/templates/pgxc/style_top_menu.css">
<link rel="stylesheet" type="text/css" href="/js/jqModal.css">

<!-- sprites-->
<style>
<!--
img.brands { background: url(/img/sprite_brands.png) no-repeat left top; }
img.fl {   background: url(/img/sprite_flags.png) no-repeat left top ; }
img.icons1 {   background: url(/img/sprite_icons1.png) no-repeat left  top ; }
-->
</style>
<link rel="stylesheet" type="text/css" href="/templates/pgxc/sprites.css">

	
<!--[if IE ]>
<style type = "text/css">
	#vnav li, #nav li , #dropMenu li , table.listTable tr {
	   behavior: url('/hover.htc');
	}
</style>
<![endif]-->

<script ><!--//--><![CDATA[//><!--

$('#dialogWindow').jqm({modal:true,toTop: true});

function showClubDetails(clubID) {
	$('#dialogWindow').jqm({ajax: '/GUI_EXT_club_info.php?clubid='+clubID,
		ajaxText: '<img src=\'/img/ajax-loader.gif\'>'  });
	$('#dialogWindow').jqmShow();
}

function showNewsItem(itemID) {
	$('#dialogWindow').jqm({ajax: '/data/news/'+itemID+'/index.html',
		ajaxText: '<img src=\'/img/ajax-loader.gif\'>'  });
	$('#dialogWindow').jqmShow();
}

function showNewsSettings() {
	$('#dialogWindow').jqm({ajax: '/GUI_EXT_settings.php',
		ajaxText: '<img src=\'/img/ajax-loader.gif\'>' });
	$('#dialogWindow').jqmShow(); 	
}

function showFilter() {
	$('#dialogWindow').jqm({ajax: '/GUI_EXT_filter.php',
		ajaxText: '<img src=\'/img/ajax-loader.gif\'>' });

	$(".jqmWindow").css({top:'20px', 'margin-left':'-400px',width:'800px'});
	
	$('#dialogWindow').jqmShow(); 	
}


function showUserSettings() {
	$('#dialogWindow').jqm({ajax: '/GUI_EXT_settings.php',
		ajaxText: '<img src=\'/img/ajax-loader.gif\'>' });
	$('#dialogWindow').jqmShow(); 	
}


function submitUserPrefs() {
  var inputs = [];
  $("#userPrefs :input").each(function() {
	inputs.push(this.name + '=' + escape(this.value));
  })
  jQuery.ajax({
	data: inputs.join('&'),
	url: '/GUI_EXT_settings.php',
	timeout: 4000,
	error: function() {
	 $("#userPrefsMsg").html("<span class='alert'>Error in updating user settings</span>");
	},
	success: function(r) { 
	 $("#userPrefsMsg").html(r);
	}
  }) 
  return false;
}
//--><!]]></script>


<?
$userAgent=$_SERVER['HTTP_USER_AGENT'];

//------------------------------------------------------------
// we need to init joomla first thing!
require_once dirname(__FILE__)."/site/config_op_mode.php";
// if ($opMode==5 && $CONF_use_own_template==1 ) { // Joomla
if ($opMode==5 ) { // Joomla
	define( '_JEXEC', 1 );
	define( 'DS', DIRECTORY_SEPARATOR );
		
	require_once JPATH_BASE.DS.'includes'.DS.'defines.php';
	require_once JPATH_BASE.DS.'includes'.DS.'framework.php';
	$mainframe =& JFactory::getApplication('site');
	$user   =& JFactory::getUser();
//echo "<hr>user<hr>"; print_r($user);
//$session =& JFactory::getSession();

//  echo "<hr>2<hr>";
//  print_r($_SESSION);
// echo "<hr>3<hr>";
//print_r($session); 
}
//------------------------------------------------------------

function leo_getmicrotime() {
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

$pageStart=leo_getmicrotime();


@session_start();

$module_name = basename(dirname(__FILE__));

//$moduleAbsPath=dirname(__FILE__);
// $moduleRelPath="modules/$module_name";

// ugly joomla 1.5 hack
@include dirname(__FILE__)."/site/predefined/5/globals_include.php";

require_once dirname(__FILE__)."/config.php";

// ugly joomla 1.5 hack
@include dirname(__FILE__)."/site/predefined/5/globals_include.php";


setVarFromRequest("lng",$PREFS->language);
if ( strlen($lng)==2) {
	$lng=array_search($lng,$lang2iso);
	if (!$lng) $lng=$PREFS->language;
}
$currentlang=$lng;

//if ( !eregi($CONF_mainfile, $_SERVER['PHP_SELF']) ) {
//    die ("You can't access this file directly...");
//}

if ($CONF_use_utf) define('CONF_LANG_ENCODING_TYPE','utf8');
else  define('CONF_LANG_ENCODING_TYPE','iso');


require_once dirname(__FILE__)."/language/".CONF_LANG_ENCODING_TYPE."/lang-".$currentlang.".php";
require_once dirname(__FILE__)."/language/".CONF_LANG_ENCODING_TYPE."/countries-".$currentlang.".php";
require_once dirname(__FILE__)."/FN_UTM.php";
require_once dirname(__FILE__)."/FN_functions.php";
require_once dirname(__FILE__)."/FN_waypoint.php";
require_once dirname(__FILE__)."/FN_brands.php";

require_once dirname(__FILE__)."/FN_pilot.php";
require_once dirname(__FILE__)."/FN_flight.php";
require_once dirname(__FILE__)."/FN_output.php";
require_once dirname(__FILE__)."/CL_flightData.php";
require_once dirname(__FILE__)."/CL_dates.php";
require_once dirname(__FILE__)."/CL_brands.php";
require_once dirname(__FILE__)."/CL_statsLogger.php";
require_once dirname(__FILE__)."/CL_filter.php";
require_once dirname(__FILE__)."/templates/".$PREFS->themeName."/theme.php";


$CLIENT['browser']=findBrowserOS();
// $agent,$version,$os,$aol

	
 // if we use utf
if ($CONF_use_utf) {
		$db->sql_query("SET NAMES utf8");
}

  $rank=makeSane($_GET['rank'],1);
  $subrank=makeSane($_GET['subrank'],1);
  $season=makeSane($_GET['season'],1);
  $CONF_compItemsPerPage=100;

  if($rank==203 and $season==0){
	$season=2019;
  }
  if (!$subrank)  $subrank=1;

  if ($lng==$ranksList[$rank]['localLanguage'])
	  $legend=$ranksList[$rank]['localName'];
  else 
  	  $legend=$ranksList[$rank]['name'];

  
  // SEASON MOD
  // we do a little trick here!
  // if the rank has custom seasons we just replace the global $CONF['seasons'] array 
  // since both have the same structure
  if ( $ranksList[$rank]['useCustomSeasons'] ) { 
	  $CONF['seasons']=$ranksList[$rank]['seasons'];
	  
	  if (is_array( $ranksList[$rank]['subranks'][$subrank]['seasons'] ) ) {
		  $CONF['seasons']=$ranksList[$rank]['subranks'][$subrank]['seasons'];
	  }
  }
  if ( $ranksList[$rank]['useCustomYears'] ) { 
	  $CONF['years']=$ranksList[$rank]['years'];
	  if (is_array( $ranksList[$rank]['subranks'][$subrank]['years'] ) ) {
		  $CONF['years']=$ranksList[$rank]['subranks'][$subrank]['years'];
	  }
  }
  $where_clause='';
  $where_clause.= dates::makeWhereClause(0,$season,$year,$month,0 );
  // BRANDS MOD  
  $where_clause.= brands::makeWhereClause($brandID);

	// take care of exluding flights
	// 1-> first bit -> means flight will not be counted anywhere!!!
	$bitMask=2 & ~( $includeMask & 0x03 );
	$where_clause.= " AND ( excludeFrom & $bitMask ) = 0 ";

  $dontShowCatSelection =$ranksList[$rank]['dontShowCatSelection'];
  $dontShowCountriesSelection=$ranksList[$rank]['dontShowCountriesSelection'];
  $dontShowManufacturers=$ranksList[$rank]['dontShowManufacturers']; //P.Wild 16.7.08
  $dontShowSecondMenu=$ranksList[$rank]['dontShowSecondMenu']; //R.Grubba
  $dontShowDatesSelection=$ranksList[$rank]['dontShowDatesSelection']; //R.Grubba
  
  
  	# Martin Jursa 22.05.2007 option NACclub Selection
	$dontShowNacClubSelection=1;
	$forceNacId=0;
	if (!empty($CONF_use_NAC) && empty($ranksList[$rank]['dontShowNacClubSelection'])) {
		require_once(dirname(__FILE__)."/CL_NACclub.php");
		$dontShowNacClubSelection=0;
		# to override any REQUEST/SESSION nacid value inside the nacclub second_menu
		$forceNacId=$ranksList[$rank]['forceNacId'];
		if ($forceNacId) $nacid=$forceNacId;
	}
  
  
  	//----------------------------------------------------------
	// Now the filter
	//----------------------------------------------------------		
	$filter_clause=$_SESSION["filter_clause"];
	// echo $filter_clause;
	if ( strpos($filter_clause,$pilotsTable) )  $pilotsTableQuery=1;
	if ( strpos($filter_clause,$waypointsTable) )  $countryCodeQuery=1;		
	$where_clause.=$filter_clause;
	//----------------------------------------------------------

	if ($countryCodeQuery)   {
		 $countryCodeQuery_clause.=" AND $flightsTable.takeoffID=$waypointsTable.ID ";
		 $extra_table_str1.=",$waypointsTable";
	} 
	if ($pilotsTableQuery ){
		$pilotsTableQuery_clause.="  AND $flightsTable.userID=$pilotsTable.pilotID AND $flightsTable.userServerID=$pilotsTable.serverID  ";	 
		$extra_table_str2.=",$pilotsTable  ";			
	}

  
  // show the current subranking
  require dirname(__FILE__)."/data/ranks/$rank/GUI_rank_cat_$subrank.php";
  
  
  if ($ranksList[$rank]['entity']=='club') $listClubs=1;
  else $listClubs=0;

  $clubID=0;
  
  /*
  $page_num=makeSane($_REQUEST["page_num"],1);
  if ($page_num==0)  $page_num=1;
  $startNum=($page_num-1)*$CONF_compItemsPerPage;
  $pagesNum=ceil ($itemsNum/$CONF_compItemsPerPage);
  */
  $page_num=$_REQUEST["page_num"]+0;
  if ($page_num==0)  $page_num=1;
  
   $itemsNum=count($pilots);   

  $startNum=($page_num-1)*$CONF_compItemsPerPage;
  $pagesNum=ceil ($itemsNum/$CONF_compItemsPerPage);
  $endNum=$startNum+$CONF_compItemsPerPage;
	
  $legendRight=generate_flights_pagination(
  			getLeonardoLink(array('op'=>'comp','rank'=>$rank,'subrank'=>$subrank)), 
			$itemsNum,$CONF_compItemsPerPage,$page_num*$CONF_compItemsPerPage-1, TRUE); 
			

	if ($endNum>$itemsNum) $endNum=$itemsNum;
	$legendRight.=" [&nbsp;".($startNum+1)."-".$endNum."&nbsp;"._From."&nbsp;".$itemsNum ."&nbsp;]";
	if ($itemsNum==0) $legendRight="[ 0 ]";
//tytul nad tabela
// echo  "<div class='tableTitle shadowBox'><div class='titleDiv'>$legend</div>";
 // if (!$listClubs) echo "<div class='pagesDivSimple'>$legendRight</div>";
  
//  echo "</div>" ;



  //echo  "<div class='tableTitle shadowBox'><div class='titleDiv'>$legend</div></div>" ;  
//  require_once dirname(__FILE__)."/MENU_second_menu.php";

// show the general discription of the ranking

// show the different subrankings/categories menu
foreach($ranksList[$rank]['subranks'] as $subrankID=>$subrankArray) {
  if ($lng==$ranksList[$rank]['localLanguage'])
  		$subrankTitle=$subrankArray['localName'];
  else
  		$subrankTitle=$subrankArray['name'];
		
	if ($subrankID==$subrank) $style ="style='background-color:#E1E6F3;' ";
	else $style="";
//wylaczam mozliwosc wybrania subranku
//	echo " <div class='menu1' $style ><a href='".getLeonardoLink(array('op'=>'comp','rank'=>$rank,'subrank'=>$subrankID))."'>$subrankTitle</a></div>";	
}
//echo "<BR><BR>";


# Martin Jursa, 02.03.2009: Hook for custom menu generated somewhere in the ranks code
if (!empty($custom_ranks_menu)) {
	echo $custom_ranks_menu;
}


	// was _KILOMETERS -> bug
	// and _TOTAL_KM
	if ($PREFS->metricSystem==1) {
		$FAI_TRIANGLE_str=_KM;
		$MENU_OPEN_DISTANCE_str=_TOTAL_DISTANCE." "._KM;
	} else  {
		$FAI_TRIANGLE_str=_MI;
		$MENU_OPEN_DISTANCE_str=_TOTAL_DISTANCE." "._MI;
	}

?>
<link rel="stylesheet" href="<?=$moduleRelPath ?>/js/bettertip/jquery.bettertip.css" type="text/css" />


<script src="<?=$moduleRelPath ?>/js/bettertip/jquery.bettertip.js"></script>
<script src="<?=$moduleRelPath ?>/js/tipster.js"></script>

<script >
var BT_base_urls=[];
BT_base_urls[0]='<?=$moduleRelPath?>/GUI_EXT_pilot_info.php?op=info_short&pilotID=';
BT_base_urls[1]='<?=$moduleRelPath?>/GUI_EXT_pilot_info.php?op=info_nac&pilotID=';
BT_base_urls[2]='<?=$moduleRelPath?>/GUI_EXT_flight_info.php?op=info_short&flightID=';

var BT_displayOnSide=[];
BT_displayOnSide[0]='auto';
BT_displayOnSide[1]='auto';
BT_displayOnSide[2]='auto';

var BT_widths=[];
BT_widths[0]=500;
BT_widths[1]=300;
BT_widths[2]=300;

var BT_default_width=500;
</script>

<? echo makePilotPopup();  ?>

<div class="tabber" id="compTabber">
<?
	# Martin Jursa 02.03.2009: Enable custom Title of display via specific ranks code (setting $customSubrankTitle)
	if (!empty($customSubrankTitle)) {
		$subrankTitle=$customSubrankTitle;
	}else {
	if ($lng==$ranksList[$rank]['localLanguage'])
		$subrankTitle=$ranksList[$rank]['subranks'][$subrank]['localName'];
	else
		$subrankTitle=$ranksList[$rank]['subranks'][$subrank]['name'];
	}
	
	if ($customFormatFunction) 
		$formatFunction=$customFormatFunction;
	else 
		$formatFunction="formatOLCScore";	
	
	if ($customRankHeader) 
		$rankHeader=$customRankHeader;
	else 
		$rankHeader=_OLC_TOTAL_SCORE;
			
	if ($listClubs) {			
		listClubs($subrankTitle, $rankHeader,"score","score",$formatFunction);
	} else {
		if ($season!=1){
			listCategory($legend.' '.$season.' # '.$subrankTitle, $rankHeader,"score","score",$formatFunction);
		}else{
			listCategory($legend.' Best of The Best # '.$subrankTitle, $rankHeader,"score","score",$formatFunction);
		}
	}
	
	?>
</div>
<?	
function listCategory($legend,$header, $category, $key, $formatFunction="") {
   global $pilots;
   global $Theme,$countries;
   global $moduleRelPath;
   global $CONF_compItemsPerPage;
   global $page_num,$pagesNum,$startNum,$itemsNum;
   global $op,$cat;
   global $countHowMany;
   global $tabID;
   global $sort_funcs_pilots;
   global $CONF;
   global $dateLegend;
   global $takeoffsOrder;

   uasort($pilots,$sort_funcs_pilots[$category]);
   $legendRight=""; // show all pilots up to  $CONF_compItemsPerPage
   if ($tabID ==  makeSane($_GET['comp'],1)  ) $defaultTabStr=" tabbertabdefault";
   else  $defaultTabStr="";
   
   $tabID++;
   echo "<div class='tabbertab $defaultTabStr' title='$legend'>";
   
   if ($countHowMany>0)  {
	   $legend.=" (".$countHowMany." "._N_BEST_FLIGHTS.")";
   } else {   
       $legend.=" (".$dateLegend.")";
   }

//adding link to ranking counting rules if is defined in constant RULESURL

   if (defined('RULESURL')){
	$rules='<a href="'.RULESURL.'" target="_blank">'._PROJECT_RULES.'</a>';
   }
   echo "<table class='listTable listTableTabber' cellpadding='2' cellspacing='0'>
   			<tr><th class='tableTitleExtra' colspan='".($countHowMany+4)."'>$legend  $rules</th></tr>";
   
   ?>
   <tr>
   <td class="SortHeader hideOnExtraSmall class_flightNo"><? echo _NUM ?></td>
   <td class="SortHeader"><div align=left><? echo _PILOT ?></div></td>
   <td class="SortHeader" width="70"><? echo $header ?></td>
   <? for ($ii=1;$ii<=$countHowMany;$ii++) { 
// in case of defined takeoffs display names instead of flight number
		if (!isset($takeoffsOrder)){
			$headerName="#".$ii;
		}else{
			//$headerName="##".$ii;
			$headerName=getWaypointName($takeoffsOrder[$ii-1]);
			$pattern = '/ - \w+/i';
			$headerName=preg_replace($pattern,'',$headerName);
			$headerName="<a  href='".getLeonardoLink(array('op'=>'show_waypoint','waypointIDview'=>$takeoffsOrder[$ii-1]))."'>".$headerName."</a>";
			
		}
	?>
   <td class="SortHeader" width="55"><? echo $headerName?></td>
   <? } ?>
   <td class="SortHeader hideOnSmall hideOnExtraSmall class_gliderBrand">&nbsp;</td>
   </tr>
   <? 

	  $i=1;
//	print_r($pilots); something wrong with that array with rank 201 20180228
   	  foreach ($pilots as $pilotID=>$pilot) {
	  	 if ($i< ( $startNum + 1) ) { $i++ ;  continue; }
		 if ($i>( $startNum  + $CONF_compItemsPerPage) ) break;
  		 // if ($i>$CONF_compItemsPerPage) break;
		 if (!$pilot[$category]['sum'] || ! count($pilot[$category]['flights'])) continue;

		 $sortRowClass=($i%2)?"l_row1":"l_row2"; 
 		 if ($i==1) $bg=" class='compFirstPlace'";
 		 else if ($i==2) $bg=" class='compSecondPlace'";
 		 else if ($i==3) $bg=" class='compThirdPlace'";
		 else $bg=" class='$sortRowClass'";
		 	 	     
	     $pilotIDinfo=str_replace("_","u",$pilotID);
		 echo "<TR $bg>";
		 echo "<TD class='hideOnExtraSmall class_flightNo' >".($i)."</TD>"; 	
	     echo "<TD nowrap ><div align=left id='$arrayName"."_$i' class='pilotLink'>";

	     	 echo "<a id='tpa0_$pilotIDinfo' href='".
	     	 getLeonardoLink(array('op'=>'pilot_profile_stats','pilotID'=>$pilotID,'season'=>0,'takeoffID'=>0)).
	     	  "'  >".$pilot['name']."</a>";
	     			
		if ($pilot['NACid'] && $pilot['NACmemberID'] && $pilot['NACclubID'] &&
				 $CONF['NAC']['display_club_details_on_pilot_list']
		) {	
			echo "&nbsp;<a class='betterTip' id='tpa1_$pilotIDinfo' href=\"javascript:nop();\"><img src='$moduleRelPath/img/icon_nac_member.gif' align='absmiddle' border=0></a>";
		}	
		 echo "</div></TD>";
		//jesli trafi cos z poza zdefiniowanej listy trzeba przekalkulowac wynik koncowy
		if (isset($takeoffsOrder)){
			$pilot[$category]["sum"]=0;
			 foreach ($pilot[$category]['flights'] as $flightID){
				if (in_array($pilot['flights'][$flightID]['takeoff'],$takeoffsOrder)){
					$pilot[$category]["sum"]=$pilot[$category]["sum"]+$pilot['flights'][$flightID]['score'];
				}
			}
		}
		 if ($formatFunction) $outVal=$formatFunction($pilot[$category]["sum"]);
		 else $outVal=$pilot[$category]["sum"];
   	     echo "<TD>".$outVal."</TD>"; 	 
		 
		$i++;

		$k=0;

		unset($pilotBrands);
		$pilotBrands=array();

		if (!isset($takeoffsOrder)){
//unordered table
			if ($countHowMany>0) {
				foreach ($pilot[$category]['flights'] as $flightID) {
					$val=$pilot['flights'][$flightID][$key];
//					print_r($category.' '.$key.' '.$flightID.' '.$val.' ');
		
					$glider=$pilot['flights'][$flightID]['glider'];
					$country=$countries[$pilot['flights'][$flightID]['country']];
		
					$thisFlightBrandID=$pilot['flights'][$flightID]['brandID'];
					if ($thisFlightBrandID) $pilotBrands[$thisFlightBrandID]++;
		
					$flightComment=$pilot['flights'][$flightID]['comment'];
					
					if (!$val)  $outVal="-";
					else if ($formatFunction) $outVal=$formatFunction($val);
					else $outVal=$val;
					// $descr=_GLIDER.": $glider, "._COUNTRY.": $country";
					$thisFlightTakeoff=$pilot['flights'][$flightID]['takeoff'];
					
					if ($val) {
						if ($flightComment) $flightCommentStr="<br>($flightComment)";
						else $flightCommentStr='';
						echo "<TD><a id='tpa2_$flightID' href='".getLeonardoLink(array('op'=>'show_flight','flightID'=>$flightID))."'>".$outVal.$flightCommentStr."</a>";
						echo "</TD>"; 	 		  
					} else echo "<TD>".$outVal."</TD>"; 	 		  
					$k++;
					if ($k>=$countHowMany) break;
				}
		
				if ($k!=$countHowMany) {
					for($j=$k;$j<$countHowMany;$j++) {
						echo "<TD>-</TD>"; 	 		  
					}
				}
			} else {
				//only detect most used glider brand
				foreach ($pilot[$category]['flights'] as $flightID) {				
					$thisFlightBrandID=$pilot['flights'][$flightID]['brandID'];
					if ($thisFlightBrandID) $pilotBrands[$thisFlightBrandID]++;
				}	
			
			}
			
			arsort($pilotBrands);
			$flightBrandID=array_shift(array_keys($pilotBrands));

			$gliderBrandImg=brands::getBrandImg($flightBrandID,'',$cat);	

			echo "<td align='center' class='hideOnSmall hideOnExtraSmall class_gliderBrand'>$gliderBrandImg</td>";
		}else{
//ordered table with defined takeoffs list
			foreach($takeoffsOrder as $takeoff){
				if (!in_array($takeoff, $pilot['score']['takeoffs'])){
					$pilot['flights'][$takeoff]['takeoff']=$takeoff;
					$pilot['flights'][$takeoff]['score']="-";
					$pilot['flights'][$takeoff]['cat']=1;
					$pilot['flights'][$takeoff]['glider']="";
					$pilot['flights'][$takeoff]['brandID']="";
					$pilot['flights'][$takeoff]['type']="";
					$pilot['flights'][$takeoff]['country']="PL";
					$pilot['flights'][$takeoff]['continent']="1";
					array_push($pilot[$category]['flights'],$takeoff);
					array_push($pilot['score']['takeoffs'],$takeoff);
				} 
		       }
//find position of each flight
			foreach ($pilot[$category]['flights'] as $flightID) {
				if(in_array($pilot['flights'][$flightID]['takeoff'],$takeoffsOrder)){	
					$pos = array_search($pilot['flights'][$flightID]['takeoff'],$takeoffsOrder);
					$pilot['score']['tempFlights'][$pos]=$flightID;
				}else{
				//remove if somehow from different takeoff
			        //	$pilot['score']['sum']=$pilot['score']['sum']-$pilot['flights'][$flightID]['score'];
					unset($pilot['flights'][$flightID]);
				}
		       }

// recreate flights array
		$numberOfTakeoffs=sizeof($takeoffsOrder);
		for($it=0; $it<$numberOfTakeoffs;$it++){
			$pilot['score']['flights'][$it]=$pilot['score']['tempFlights'][$it];
		}
//remove extra flights if from takeoffs out of the list
		$numberOfFlights=sizeof($pilot['score']['flights']);
		while($numberOfTakeoffs<$numberOfFlights){
			unset($pilot['score']['flights'][$numberOfFlights-1]);
			unset($pilot['score']['takeoffs'][$numberOfFlights-1]);
			$numberOfFlights--;
		}

				foreach ($pilot[$category]['flights'] as $flightID) {
					$val=$pilot['flights'][$flightID][$key];
			//		print_r($category.' '.$key.' '.$flightID.' '.$key.' - '.$val.'<br> ');
		
					$glider=$pilot['flights'][$flightID]['glider'];
					$country=$countries[$pilot['flights'][$flightID]['country']];
		
					$thisFlightBrandID=$pilot['flights'][$flightID]['brandID'];
					if ($thisFlightBrandID) $pilotBrands[$thisFlightBrandID]++;
		
					$flightComment=$pilot['flights'][$flightID]['comment'];
					
					if ($val=="-")  $outVal="-";
					else if ($formatFunction) $outVal=$formatFunction($val);
					else $outVal=$val;
					// $descr=_GLIDER.": $glider, "._COUNTRY.": $country";
					
					if ($val!="-" and $key!=$pilot['flights'][$flightID]['takeoff']) {
						if ($flightComment) $flightCommentStr="<br>($flightComment)";
						else $flightCommentStr='';
						echo "<TD><a id='tpa2_$flightID' href='".getLeonardoLink(array('op'=>'show_flight','flightID'=>$flightID))."'>".$outVal.$flightCommentStr."</a>";
						
						//echo " <a class='betterTip' id='tpa2_$flightID' href='".$moduleRelPath."/GUI_EXT_flight_info.php?op=info_short&flightID=".$flightID."' title='$descr'>?</a>";
						echo "</TD>"; 	 		  
					} else echo "<TD>".$outVal."</TD>"; 	 		  
				}
		
				foreach ($pilot[$category]['flights'] as $flightID) {				
					$thisFlightBrandID=$pilot['flights'][$flightID]['brandID'];
					if ($thisFlightBrandID) $pilotBrands[$thisFlightBrandID]++;
				}	


			arsort($pilotBrands);
			$flightBrandID=array_shift(array_keys($pilotBrands));

			$gliderBrandImg=brands::getBrandImg($flightBrandID,'',$cat);	

			echo "<td align='center' class='hideOnSmall hideOnExtraSmall class_gliderBrand'>$gliderBrandImg</td>";
//end of ordered table		
		}	
	
		// next pilot
	}
	echo "</table>"; 
	echo '</div>';
} //end function

function listClubs($legend,$header, $category, $key, $formatFunction="") {
   global $clubs,$NACid;
   global $Theme,$countries;
   global $moduleRelPath;
   global $CONF_compItemsPerPage;
   global $page_num,$pagesNum,$startNum,$itemsNum;
   global $op,$cat;
   global $countHowManyFlights,$pilotsMax,$pilotsMin;
   global $tabID;
   global $sort_funcs_pilots;

   require_once dirname(__FILE__)."/CL_NACclub.php";
   $clubNamesList=NACclub::getClubs($NACid);

   $legendRight=""; // show all pilots up to  $CONF_compItemsPerPage
    
  // $legend.=" (".$countHowMany." "._N_BEST_FLIGHTS.")";
   echo "<table class='listTable listTableTabber listTable2' cellpadding='2' cellspacing='0'>
   			<tr><td class='tableTitleExtra' colspan='".($pilotsMax+3)."'>$legend</td></tr>";
   
   ?>
   <tr>
   <td class="SortHeader hideOnExtraSmall class_flightNo"><? echo _NUM ?></td>
   <td class="SortHeader"><div align=left><? echo _Club ?></div></td>
   <? for ($ii=1;$ii<=$pilotsMax;$ii++) { ?>
   <td class="SortHeader" width="55"><div align=left><? echo _PILOT." #$ii" ;?></div></td>
   <? } ?>
   <td class="SortHeader" width="70"><? echo $header ?></td>
   </tr>
   <? 

	  $i=1;
   	  foreach ($clubs as $clubID=>$club) {
  		 // if ($i>$CONF_compItemsPerPage) break;
		 if (!$club['sum'] ) continue;

		 $sortRowClass=($i%2)?"l_row1":"l_row2"; 
 		 if ($i==1) $bg=" class='compFirstPlace'";
 		 else if ($i==2) $bg=" class='compSecondPlace'";
 		 else if ($i==3) $bg=" class='compThirdPlace'";
		 else $bg=" class='$sortRowClass'";
		 	 	     
	     $i++;
		 echo "<TR $bg>";
		 echo "<TD>".($i-1+$startNum)."</TD>"; 	
	     echo "<TD width='25%'><div align=left >".$clubNamesList[$clubID].
				"</div></TD>";
		 


		unset($pilotBrands);
		$pilotBrands=array();

		$j=0;
		foreach ($club as $pilotID=>$pilot) {
			$k=0;
			$pilotIDparts=split('_',$pilotID,2);
			if (!is_numeric( $pilotIDparts[1]) ) continue;
			// echo "#".$pilotID."#";

			$pilotName=getPilotRealName($pilotIDparts[1],$pilotIDparts[0],1); 


			$pilotName=prepare_for_js($pilotName);
			$pilotIDinfo=str_replace("_","u",$pilotID);
			
			echo "<TD width='20%'>";
			echo "<table width='100%' cellpadding='0' cellspacing='0' class='listTable3'><TR><TD colspan=3 id='$arrayName"."_$pilotID' class='pilotLink'>".
				"<a class='clubPilot betterTip' id='tpa0_$pilotIDinfo' href=\"javascript:pilotTip.newTip('inline', 0, 13, '$arrayName"."_$pilotID', 200, '".$pilotID."','".
					addslashes($pilotName)."' )\"  onmouseout=\"pilotTip.hide()\">".$pilotName."</a>".
			"</td></tr><tr>";
			foreach($pilot['flights_sel'] as $flightID) {
				$val=$pilot['flights'][$flightID]['score'];
	
				$glider=$pilot['flights'][$flightID]['glider'];
				$country=$countries[$pilot['flights'][$flightID]['country']];
	
				$thisFlightBrandID=$pilot['flights'][$flightID]['brandID'];
				if ($thisFlightBrandID) $pilotBrands[$thisFlightBrandID]++;
	
				if (!$val)  $outVal="&nbsp;";
				else if ($formatFunction) $outVal=$formatFunction($val);
				else $outVal=$val;
				
				
				// $descr=_PILOT.": $pilotName, "._GLIDER.": $glider, "._COUNTRY.": $country";
				$descr=_GLIDER.": $glider, "._COUNTRY.": $country";
				$descr='';
				
				if ($val) {
					echo "<TD width='33%'><a class='betterTip' id='tpa2_$flightID' href='".getLeonardoLink(array('op'=>'show_flight','flightID'=>$flightID))."' alt='$descr'  title='$descr'>".$outVal."</a></TD>"; 	 		  
				} else { 
					echo "<TD width='33%'>".$outVal."</TD>"; 	 		  
				}	
				$k++;
				if ($k>=$countHowManyFlights) break;
			}
			if ($k!=$countHowManyFlights) {
				for($kk=$k;$kk<$countHowManyFlights;$kk++) {
					echo "<TD >&nbsp;</TD>"; 	 		  
				}
			}
			echo "</tr></table></td>";
			$j++;
			if ($j>=$pilotsMax) break;
		}

		if ($j!=$pilotsMax) {
			for($jj=$j;$jj<$pilotsMax;$jj++) {
				echo "<TD width='20%'>-</TD>"; 
//				for($jjj=0;$jjj<$countHowManyFlights;$jjj++) {
//					echo "<TD>-</TD>"; 	 		  
//				}
			}
		}

		 if ($formatFunction) $outVal=$formatFunction($club["sum"]);
		 else $outVal=$club["sum"];
   	     echo "<TD>".$outVal."</TD>"; 	 


		echo "</tr>";
   	}	// next club

	echo "</table>"; 
//	echo '</div>';

} //end function

?>
