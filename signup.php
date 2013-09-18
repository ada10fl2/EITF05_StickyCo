<?php
	$user = isset($_POST['username']) ? $_POST['username'] : "";
	$pass = isset($_POST['password']) ? $_POST['password'] : "";
	$address = isset($_POST['address']) ? $_POST['address'] : "";
	$first = isset($_POST['firstname']) ? $_POST['firstname'] : "";
	$last = isset($_POST['lastname']) ? $_POST['lastname'] : "";
	
	if(!empty($user) && !empty($pass) && !empty($address) && !empty($first) && !empty($last)) {
		require_once('/classes/db.php');
		$db = new db();
		$res = $db->create_user($user, $pass, $first, $last, $address);
		if($res === TRUE) {
			?>
			<script>
				document.location = "index.php";
			</script>
			<?php
		} else {
			exit("Creation failed");
		}
	} else {
	}
	$script = "";
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
		<div class="well">	
				<form action="" method="POST" role="form" id="signup">
					<div class="form-group">
						<label class="control-label" for="firstname">Firstname</label>
						<input class="form-control" type="text" name="firstname" id="firstname">
						<span class="help-block">The very first part of your full name</span>
					</div>
					<div class="form-group">
						<label class="control-label" for="lastname">Lastname</label>
						<input class="form-control" type="text" name="lastname" id="lastname">
						<span class="help-block">The last part of your full name</span>
					</div>
					<div class="form-group">
						<label class="control-label" for="username">Username</label>
						<input class="form-control" type="text" name="username" id="username">
						<span class="help-block">The desired username</span>
					</div>
					<div class="form-group">
						<label class="control-label" for="password">Password</label>
						<input class="form-control" type="password" name="password" id="password">
						<span class="help-block">The desired password</span>
					</div>
					<div class="form-group">
						<label class="control-label" for="address">Home Address</label>
						<input class="form-control" type="text" name="address" id="address">	
					</div>
					<span class="help-block">Your home address</span>
					
					<button class="btn btn-default btn-lg" type="submit">Register</button>
				</form>
			</div>
		
	</div><!--/span-->
</div><!--/row-->

<?php
	include $_SERVER["DOCUMENT_ROOT"].'/include/footer.php';
?>

