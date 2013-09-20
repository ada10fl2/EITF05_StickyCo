<?php
	$script = "";
	
	$user = isset($_POST['username']) ? $_POST['username'] : "";
	$pass = isset($_POST['password']) ? $_POST['password'] : "";
	if(!empty($user) && !empty($pass)) {
		require_once('/classes/db.php');
		$db = new db();
		if($db->verify_user($user, $pass) === TRUE){
			?>
			<script>document.location = "index.php";</script>
			<?php
			$script = "var failed = false;";
		} else {
			$script = "var failed = true;";
		}
	}
	$scriptfile = "/login.js";
	include $_SERVER["DOCUMENT_ROOT"]."/include/header.php";
?>
<div class="row row-offcanvas row-offcanvas-right">
	<div class="col-xs-12 col-sm-9">
		<p class="pull-right visible-xs">
			<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
		</p>
		<div class="jumbotron">			
			<h1>Login</h1>
			<form action="" method="POST" role="form" id="login">
				<div class="form-group">
					<label class="control-label" for="username">Username</label>
					<input type="text" class="form-control" name="username" id="username">
				</div>
				<div class="form-group">	
					<label class="control-label" for="password">Password</label>
					<input type="password" class="form-control" name="password" id="password">
				</div>
				
				<button class="btn btn-default btn-lg" type="submit">Log in</button>
				<br/><br/>
				Not registered yet? <a href="signup.php">Sign up here</a>
			</form>
		
		</div>
	</div><!--/span-->
</div><!--/row-->
<?php
	include $_SERVER["DOCUMENT_ROOT"].'/include/footer.php';
?>

