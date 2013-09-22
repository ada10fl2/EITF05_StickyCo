<?php
$imgs = scandir($_SERVER['DOCUMENT_ROOT']."/img/small");
 	require_once('/classes/db.php');
	$db = new db();
 foreach($imgs as $l){
 	  if(preg_match("/.*\.png/", $l)){
  	$p = str_replace ( ".png" , "" , $l);
  	$p = str_replace ( "_" , " " , $p);
  	$price = rand ( 1 , 20 );
  	$text = json_decode(file_get_contents("http://baconipsum.com/api/?type=all-meat&paras=1&start-with-lorem=0&sentences=3"))[0];
   	$db->create_prod($l,ucwords($p), $price, $text);
   }
 }

 ?>