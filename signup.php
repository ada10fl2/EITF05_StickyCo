<?php
	require_once('/classes/validate.php');

	$username = Validate::ifset($_POST['username']);
	$password = Validate::ifset($_POST['password']);
	$address = Validate::ifset($_POST['address']);
	$firstname = Validate::ifset($_POST['firstname']);
	$lastname = Validate::ifset($_POST['lastname']);
	
	$is_post = Validate::is_POST($_SERVER);
	
	$is_valid = 
		Validate::is_name($firstname) && Validate::is_name($lastname) &&
		Validate::is_address($address) && Validate::is_username($username) &&
		Validate::is_password($password);
	
	$success = FALSE;
	if($is_post && $is_valid) {
		require_once('/classes/db.php');
		$db = new db();
		if($db->create_user($username, $password, $firstname, $lastname, $address)) {
			$success = TRUE;
			?>
			<script>document.location = "/";</script>
			<?php
		}
	}
	
	$user_already_exist = ($is_post && $is_valid && !$success);
	
	$script = "var was_post = ".json_encode($is_post).";";
	$scriptfile = "/signup.js?v=1";
	include $_SERVER["DOCUMENT_ROOT"]."/include/header.php";
?>
<div class="row row-offcanvas row-offcanvas-right">
	<div class="col-xs-12 col-sm-9">
		<div class="jumbotron">
			<h1>Sign Up</h1>
			<p>
				Please fill in your information
			</p>
		</div>
		<?php if($user_already_exist) { //user aleady exist in db ?>
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

