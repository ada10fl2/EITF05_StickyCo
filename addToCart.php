<?php
	$script = "";
	
	$pid = isset($_GET['pid']) ? $_GET['pid'] : "";

	require_once('/classes/db.php');
	$db = new db();
	session_start();
	if(!($pid === "")) {	
		$json = $db->addToCart($_SESSION['userid'], $pid);
		var_dump($json);
	}else{
		$json = $db->get_cart($_SESSION['userid'], $pid);
		var_dump($json);
	}
?>
