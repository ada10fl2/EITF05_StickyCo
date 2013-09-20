<?php 
	require_once('/classes/db.php');
	$db = new db();
	$db->logout();
?>
<script>
	document.location = "index.php";
</script>