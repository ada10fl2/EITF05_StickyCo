<?php

	function filter_name($v){    return preg_match('/^[\w ]{3,45}$/i',$v);}
	function filter_address($v){ return preg_match('/^[\w ]{3,200}$/i',$v);}
	function filter_username($v){ return preg_match('/^[\w]{4,}$/i',$v);}
	function filter_password($v){ return preg_match('/^[\w]{6,}$/i',$v);}

	function has_error($str){
		return (preg_match('/^[_a-zA-Z0-9- ]+$/', $str) || $_SERVER['REQUEST_METHOD'] !== "POST" ? "" : "has-error");
	}
	function has_error_pass($str){
		return (preg_match('/^.{8,}$/', $str) || $_SERVER['REQUEST_METHOD'] !== "POST" ? "" : "has-error");
	}
	
	$username = isset($_POST['username']) ? $_POST['username'] : "";
	$password = isset($_POST['password']) ? $_POST['password'] : "";
	$address = isset($_POST['address']) ? $_POST['address'] : "";
	$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : "";
	$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
	
	$is_post = ($_SERVER['REQUEST_METHOD'] === "POST");
	$is_valid = 
		filter_name($firstname) &&
		filter_name($lastname) &&
		filter_address($address) &&
		filter_username($username) &&
		filter_password($password);
	
	$success = FALSE;
	if($is_post && $is_valid) {
		require_once('/classes/db.php');
		$db = new db();
		if($db->create_user($username, $password, $firstname, $lastname, $address)) {
			$success = TRUE;
			?>
			<script>
				document.location = "index.php";
			</script>
			<?php
		}
	}
	$script = "var was_post = ".json_encode($is_post).";";
	$scriptfile = "/signup.js?v=1";
	include $_SERVER["DOCUMENT_ROOT"]."/include/header.php";
?>
<div class="row row-offcanvas row-offcanvas-right">
	<div class="col-xs-12 col-sm-9">
		<p class="pull-right visible-xs">
			<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
		</p>
		<div class="jumbotron">
			<h1>Sign Up</h1>
			<p>
				Please fill in your information
			</p>
		</div>
		<?php if($is_post && $is_valid && !$success) { //user aleady exist in db ?>
		<div class="well">	
			<ul>
			<li class="text-danger">
				User '<i><?= $username ?></i>' already exists...
			</li>
			<ul>
		</div>
		<?php } ?>
		<div class="well">
			<form action="" method="POST" role="form" id="signup">
				<div class="form-group">
					<label class="control-label" for="firstname">Firstname</label>
					<input class="form-control validate" type="text" name="firstname" id="firstname" value='<?= $firstname ?>'>
					<span class="help-block">The very first part of your full name</span>
				</div>
				<div class="form-group">
					<label class="control-label" for="lastname">Lastname</label>
					<input class="form-control validate" type="text" name="lastname" id="lastname" value='<?= $lastname ?>'>
					<span class="help-block">The last part of your full name</span>
				</div>
				<div class="form-group">
					<label class="control-label" for="username">Username</label>
					<input class="form-control validate" type="text" name="username" id="username" value='<?= $username ?>'>
					<span class="help-block">The desired username</span>
				</div>
				<div class="form-group">
					<label class="control-label" for="password">Password</label>
					<input class="form-control" type="password" name="password" id="password" value='<?= $password ?>'>
					<span class="help-block">The desired password</span>
				</div>
				<div class="form-group">
					<label class="control-label" for="address">Home Address</label>
					<input class="form-control validate" type="text" name="address" id="address" value='<?= $address ?>'>	
					<span class="help-block">Your home address</span>
				</div> 
				<button class="btn btn-default btn-lg" type="submit">Register</button>
			</form>
		</div>
	</div><!--/span-->
</div><!--/row-->

<?php
	include $_SERVER["DOCUMENT_ROOT"].'/include/footer.php';
?>

