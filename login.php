<?php
	$user = isset($_POST['username']) ? $_POST['username'] : "";
	$pass = isset($_POST['password']) ? $_POST['password'] : "";
	if(isset($user) && isset($pass)) {
		
		require_once('/classes/db.php');
		$db = new db();
		
		
		if($db->verify_user($user, $pass) === TRUE){
			$_SESSION['user'] = $user;
			$_SESSION['last_logon'] = date('y-M-d');
			?>
			<script>
				document.location = "index.php";
			</script>
			<?php
		}
	}
	$script = "";
	$scriptfile = "/login.js";
	$title = "EITF05 Webshop";
	include $_SERVER["DOCUMENT_ROOT"]."/include/header.php";
?>
<div class="row row-offcanvas row-offcanvas-right">
	<div class="col-xs-12 col-sm-9">
		<p class="pull-right visible-xs">
			<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
		</p>
		<div class="jumbotron">
			<div class="login-form well">
				<h2>Login</h2>
				<form action="" method="POST">
					<fieldset>
						<label for="username">Username</label>
						<div class="clearfix">
							<input type="text" name="username" id="username">
						</div>
						
						<label for="password">Password</label>
						<div class="clearfix">
							<input type="password" name="password" id="password">
						</div>
						<br/>
						<button class="btn btn-default btn-lg" type="submit">Log in</button><br/><br/>
						Not registered yet? <a href="signup.php">Sign up here</a>
					</fieldset>
				</form>
			</div>
		</div>
	</div><!--/span-->
</div><!--/row-->

<?php
	include $_SERVER["DOCUMENT_ROOT"].'/include/footer.php';
?>

