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
// $Id: EXT_google_maps.php,v 1.16 2010/03/15 20:57:40 manolis Exp $                                                                 
//
//************************************************************************

	//$module_name = basename(dirname(__FILE__));		
	//	$moduleAbsPath=dirname(__FILE__);
	//	$moduleRelPath=".";
	// require "config.php";
	
	require_once dirname(__FILE__)."/EXT_config_pre.php";
	require_once dirname(__FILE__)."/config.php";
	$CONF_use_utf=1;
 	require_once dirname(__FILE__)."/EXT_config.php";
        require_once dirname(__FILE__)."/CL_flightPhotos.php";
        require_once dirname(__FILE__)."/FN_functions.php";
	


?>
<html>
  <head>
    <title>Latest leonardo photographs</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="<?=$moduleRelPath?>/js/bootstrap/css/bootstrap.css" type="text/css">
	<script src="<?=$moduleRelPath?>/js/bootstrap/css/bootstrap.css" type="text/css"></script>
	<script src="<?=$moduleRelPath?>/js/bootstrap/js/bootstrap.min.js" type="text/javascript"/></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
  </head>
<body>
<div id="photo" class="carousel slide" data-ride="carousel">

<?php

   $query='select p.flightID as flightID, p.id as id ,p.path as path,p.name as name,f.active,f.userID, f.private from leonardo_photos p, leonardo_flights f where p.flightID=f.id AND f.private=0 AND f.active=1  order by f.id desc limit 10';
   $res= $db->sql_query($query);
   if($res <= 0){
      echo("<H3> "._THERE_ARE_NO_PHOTOS_TO_DISPLAY."</H3>\n");
      exit();
   }

$i=1;
$photos=array(); // new method -> one multi array

while ($row = $db->sql_fetchrow($res)) {
         $photoID=$row['id'];
	 list($pilotID,$fname,$year)=split("/",$row['path']);
	 $photos[$i]['url']='https://files.leonardo.pgxc.pl/data/flights/'.$fname.'/'.$year.'/'.$pilotID.'/'.$row['name'];
	 $photos[$i]['icon']=$photos[$i]['url'].'.icon.jpg';
	 $photos[$i]['flight']=getLeonardoLink(array('op'=>'show_flight','flightID'=>$row['flightID']));
//	 print_r($row);
//	 print_r($photoID);
     $i++;
}

// https://getbootstrap.com/docs/4.1/components/carousel/
?>

<!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#photo" data-slide-to="0" class="active"></li>
    <li data-target="#photo" data-slide-to="1"></li>
    <li data-target="#photo" data-slide-to="2"></li>
    <li data-target="#photo" data-slide-to="3"></li>
    <li data-target="#photo" data-slide-to="4"></li>
    <li data-target="#photo" data-slide-to="5"></li>
    <li data-target="#photo" data-slide-to="6"></li>
    <li data-target="#photo" data-slide-to="7"></li>
    <li data-target="#photo" data-slide-to="8"></li>
    <li data-target="#photo" data-slide-to="9"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="<?php echo $photos[1]['url'] ?>" alt="1">
      <div class="carousel-caption d-none d-md-block">
       <h5>1</h5>
       <p>1</p>
      </div>
    </div>
 <?
	$i = 2;
	while ($i<11){
	
?>
    <div class="carousel-item">
      <img class="d-block w-100" src="<?php echo $photos[$i]['url'] ?>" alt="<? echo $i ?>">
      <div class="carousel-caption d-none d-md-block">
       <h5><? echo $i ?></h5>
       <p><? echo $i ?></p>
      </div>
    </div>
<? $i++;} ?> 
  </div>


  <a class="carousel-control-prev" href="#photo" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#photo" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>

</div>
</body>
</html>
