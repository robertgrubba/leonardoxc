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
// $Id: MENU_areas.php,v 1.8 2010/03/14 20:56:11 manolis Exp $                                                                 
//
//************************************************************************
?>

<div id="areaDropDownID" class="secondMenuDropLayer"  >
<div class='closeButton closeLayerButton'></div>        
<div class='content' align="left">

<?
if ( $areasNum > 30 &&0 ) {
	$tblWidth="400";

?>
<table  width="<?=$tblWidth?>" cellpadding="3" cellspacing="0">
<tr>
	<td  height=25 class="main_text" bgcolor="#40798C">
		<div align="center" class="style1">
			<strong><?=_SELECT_AREA?> <?=_OR?></strong>
			<a href='<?=getLeonardoLink(array('op'=>'useCurrent','area'=>'0'))?>'><?=_Display_ALL?></a>
		</div>	
	</td>
</tr>
<tr>
<td align="center" height=180 valign="top">
<div align="center" style="display:block; z-index:1000; height:180px; ">
<?
	echo "<select name='selectCountry' id='selectCountry' onchange='changeCountry(this)'>";
		echo "<option value=0>---- "._SELECT_AREA." ----</option>";
//		echo "	<option value=0>"._Display_ALL."</option>";
	foreach($areasCodes as $i=>$cCode) {
		$areaName=$areasNames[$i];
		if ($cCode==$area) $sel='selected';
		else $sel='';
		echo "<option $sel value='".$cCode."'>$areaName</option>";
	}
	echo "</select>\n";
	
?></div>
</td></tr>
<tr>
	<td  height=8 class="main_text" ></td>
</tr>
</TABLE>
<?
} else {
			
	$num_of_cols=ceil(($areasNum+6)/17);
	$num_of_cols=1;
	// $num_of_cols=5;
	$num_of_rows=ceil(($areasNum)/$num_of_cols);
	$num_of_rows=$areasNum;

	$areasDivWidth=100;
	$areasDivWidthTot=$areasDivWidth*$num_of_cols;
	// echo "#$areasNum#$num_of_cols#$num_of_rows#";
	$tblWidth=$num_of_cols*110;
	$tblWidth=740;

?>

<?
	require_once dirname(__FILE__)."/FN_areas.php";
	$i=0;
	foreach($areasNames as $areaName) {	
//		$continentNum=$areas2continent[$areasCodes[1]];	
//		$continentNum=$areas2continent[0];	
//		$continentNum=$areas2continent[0];	
//		$continentArray[$continentNum][]=0;
		$i++;
	}		
	
?>
<div style='height:4px;'></div>
<div align='left'>
<table  cellpadding="1" cellspacing="0"  >
<tr>
	<td height=5 colspan=<?=$num_of_cols ?> class="main_text">
		<strong><?=_SELECT_AREA?> <?=_OR?>
		</strong>
		<div class="buttonLink">
			<a  href='<?=getLeonardoLink(array('op'=>'useCurrent','area'=>'0'))?>'><?=_Display_ALL?></a>
		</div>
	</td>
</tr>
<tr>
	<td colspan=<?=$num_of_cols ?> >

	</td>
</tr>


<? 
	/*wyswietlanie tabeli z rejonami w dodatkowym menu */
if ($areasNum) {
	$percent=floor(100/$num_of_cols);
	$sortRowClass=($ii%2)?"l_row1":"l_row2"; 	
	$ii=0; 
//	echo "\n\n<tr><td class='areaContinent areaContinent1'  valign='top' width='$percent%'>";
	
	//for($c=1;$c<=6;$c++) {
	//
		echo "<div class='datesColumnHeader ContinentHeader ContinentHeader1'><strong>"._MENU_AREA_GUIDE."</strong></div>";
	for($iii=0; $iii<=$areasNum;$iii++){
				$areaName=$areasNames[$iii];
				$areaName=trimText($areaName,20);
				$linkTmp=getLeonardoLink(array('op'=>'useCurrent','area'=>$areasCodes[$iii]));
						
				echo "<div class='areaContinentLink ContinentHeader1'><a class='areaContinent1' href='$linkTmp'>$areaName</a></div>\n";
	}
	echo "</tr>";
}	


$ii=0;
if ($areasNum && 0) {



	$percent=floor(100/$num_of_cols);

	for( $r=0;$r<$num_of_rows;$r++) {
		$sortRowClass=($ii%2)?"l_row1":"l_row2"; 	
		$ii++; 
		echo "\n\n<tr class='$sortRowClass'>";
		for( $c=0;$c<$num_of_cols;$c++) {
			//	echo "<td style='width:".$areasDivWidth."px'>";
			echo "<td class='areaList' width='$percent%'>";

			//compute which to show
			//echo "c=$c r=$r i=$i<br>";
			$i= $c * $num_of_rows +( $r % $num_of_rows);
			if ($i<$areasNum) {
				$areaName=$areasNames[$i];
				$areaName=trimText($areaName,20);
				$linkTmp=getLeonardoLink(array('op'=>'useCurrent','area'=>$areasCodes[$i]));
				
				echo "<a href='$linkTmp'>$areaName</a>\n";
				/*
				if ($currentlang=='hebrew')
					echo "<a href='$linkTmp'>(".$areasFlightsNum[$i].") $areaName</a>\n";
				else
					echo "<a href='$linkTmp'>$areaName (".$areasFlightsNum[$i].")</a>\n";
				*/
			}	 
			else echo "&nbsp;";

			echo "</td>";
		}
		echo '</tr>';
	}
} 

//echo "</ul></div>";
//echo "</td></tr>";
?>
<tr>
	<td colspan=<? echo $num_of_cols ; ?> height=8 class="main_text" ></td>
</tr>
</TABLE>
</div>


</div>
</div>
<style type="text/css">
<!--
.areaList a:link {
	
	display:inline;
	text-align:left;
	float:none;
	width:auto;
	white-space:normal;	
}
.areaList {
	white-space:normal;	
	text-align:left;
}
-->
</style>
<? } ?>
