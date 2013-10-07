<?php
	$success = FALSE;
	$user = isset($_POST['username']) ? $_POST['username'] : "";
	$pass = isset($_POST['password']) ? $_POST['password'] : "";
	$is_login = ($_SERVER['REQUEST_METHOD'] === "POST");
	if($is_login === TRUE) {
		require_once('/classes/db.php');
		$db = new db();
		if($db->verify_user($user, $pass) === TRUE){
			$success = TRUE;
			if(isset($_SESSION['cart']) && isset($_SESSION['userid'])){
				$cart = $_SESSION['cart'];
				$uid = $_SESSION['userid'];
				if($cart['count'] !== 0) {
					$db->cart_clear($uid);
					foreach($cart['content'] as $p){
						for ($i = 0; $i < $p['count']; $i++){
							$db->cart_add($uid, $p['ID']);
						}
					}
				}
			}
			exit("<script>document.location='index.php';</script>");
		}
	}
	$script = "";
	$scriptfile = "/login.js";
	include $_SERVER["DOCUMENT_ROOT"]."/include/header.php";
?>
<div class="row row-offcanvas row-offcanvas-right">
	<div class="col-xs-12 col-sm-9">
		<div class="jumbotron">			
			<h1>Log in</h1>
			<form action="" method="POST" role="form" id="login">
				
				<div class="form-group <?= ($is_login && $success===FALSE) ? "has-error" : ""?>">
					<label class="control-label" for="username">Username</label>
					<input type="text" class="form-control input-lg" name="username" id="username" autofocus="autofocus" value="<?= $user ?>">
				</div>
				<div class="form-group <?= ($is_login && $success===FALSE) ? "has-error" : ""?>">	
					<label class="control-label" for="password">Password</label>
					<input type="password" class="form-control input-lg" name="password" id="password" value="<?= $pass ?>">
				</div>
				<div id="login_error" class="text-danger">
					<?php if($is_login && $success===FALSE) echo "<span class='glyphicon glyphicon-warning-sign'> </span> Invalid credentials, please try again..." ?>
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

