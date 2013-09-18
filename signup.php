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
	$scriptfile = "/signup.js";
	include $_SERVER["DOCUMENT_ROOT"]."/include/header.php";
?>
<div class="row row-offcanvas row-offcanvas-right">
	<div class="col-xs-12 col-sm-9">
		<p class="pull-right visible-xs">
			<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
		</p>
		<div class="jumbotron">
			<div class="login-form well">
				<h2>Sign Up</h2>
				<form action="" method="POST">
					<fieldset>
						<label for="firstname">Firstname</label>
						<div class="clearfix">
							<input type="text" name="firstname" id="firstname">
						</div>
						
						<label for="lastname">Lastname</label>
						<div class="clearfix">
							<input type="text" name="lastname" id="lastname">
						</div>
						
						<label for="username">Username</label>
						<div class="clearfix">
							<input type="text" name="username" id="username">
						</div>
						
						<label for="password">Password</label>
						<div class="clearfix">
							<input type="password" name="password" id="password">
						</div>
						
						<label for="address">Home Address</label>
						<div class="clearfix">
							<input type="text" name="address" id="address">
						</div>
						<br/>
						<button class="btn btn-default btn-lg" type="submit">Register</button>
						<br/>
						<br/>
					</fieldset>
				</form>
			</div>
		</div>
	</div><!--/span-->
</div><!--/row-->

<?php
	include $_SERVER["DOCUMENT_ROOT"].'/include/footer.php';
?>

