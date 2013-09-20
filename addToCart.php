<?php
	$script = "";
	
	$pid = isset($_POST['pid']) ? $_POST['pid'] : "";

	if(!empty($pid)) {
		
		require_once('/classes/db.php');
		$db = new db();
		$db->addToCart($_SESSION['userid'], $pid);
	}
?>
