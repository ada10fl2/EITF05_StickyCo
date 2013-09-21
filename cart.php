<?php
	header('Content-Type: application/json');
	$script = "";
	
	$pid = isset($_GET['pid']) ? $_GET['pid'] : "";
	$action = isset($_GET['action']) ? $_GET['action'] : "";
	
	require_once('/classes/db.php');
	$db = new db();
	session_start();
	
	
	if($action === "add"){
		if(is_numeric($pid)) {
			$json = $db->cart_add($_SESSION['userid'], $pid);
			echo json_encode($json);
		} else {
			$json = $db->cart_get($_SESSION['userid'], $pid);
			echo json_encode($json);
		}
	}
	
	if($action === "clear"){
		echo json_encode($db->cart_clear($_SESSION['userid']));
	}
?>
