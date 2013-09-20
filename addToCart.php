<?php
	header('Content-Type: application/json');
	$script = "";
	$pid = isset($_GET['pid']) ? $_GET['pid'] : "";
	require_once('/classes/db.php');
	$db = new db();
	session_start();
	if(!empty($pid)) {
		$json = $db->addToCart($_SESSION['userid'], $pid);
		echo json_encode($json);
	}else{
		$json = $db->get_cart($_SESSION['userid'], $pid);
		echo json_encode($json);
	}
?>
