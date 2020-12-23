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
// $Id: GUI_list_areas.php,v 1.6 2010/03/14 20:56:11 manolis Exp $                                                                 
//
//************************************************************************

require_once dirname(__FILE__).'/CL_area.php';
  
$areaID=makeSane($_GET['areaID'],1);

openMain(_Flying_Areas,0,'icon_takeoff.gif'); 
  
?>


  <div class=main_text width="100%"  border="0" style="display:flex;" >
    <div id="areaslist" class="areaslist">
    <h2><?=_Name_of_Area?></h2>
	<ul>
<? 
	$query="SELECT * FROM $areasTable WHERE areaType=0 ORDER BY name";
	// $query="SELECT * FROM $areasTable ORDER BY name";
	// echo $query;
	$res= $db->sql_query($query);		
	if($res <= 0){
		echo "No areas found <BR>";
	}
	
	while ($row = $db->sql_fetchrow($res)) { 
	  echo "<li id=".$row['ID']."><a href='".	getLeonardoLink(array('op'=>'area_show','areaID'=>$row['ID']))."'>".$row['name']."</a></li>\n";	
	}
	echo "</ul>";
     echo "</div>";
     echo "<div class=\"mapka\" id=\"mapka\" style='margin-left: 2em; '></div>";
   echo "</div>";

 echo '<script> arh = $(areaslist).height(); $("#areaslist").on("hover","a",function(){ idr=$(this).parent().attr("id"); $( mapka ).html(\'<img height="\'+arh+\'" src="'.$CONF['links']['baseURL'].'/data/cache/map_thumbs/users/rejon_\'+idr+\'.jpg">\'); });</script>';
	closeMain();  
	return;

?>
