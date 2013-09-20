<?php
	$script = "";
	
	$pid = isset($_GET['pid']) ? $_GET['pid'] : "";

	if(!empty($pid)) {
		
		require_once('/classes/db.php');
		$db = new db();
		session_start();
		$json = $db->addToCart($_SESSION['userid'], $pid);
		var_dump($json);
	}
?>
