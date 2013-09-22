<?php
	
	$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : "";
	$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
	$address = isset($_POST['address']) ? $_POST['address'] : "";
	$creditcard = isset($_POST['creditcard']) ? $_POST['creditcard'] : "";
	
	require_once('/classes/db.php');
	$db = new db();
	$is_post = ($_SERVER['REQUEST_METHOD'] === "POST");
	
	
	session_start();
	if(!isset($_SESSION['userid'])){ //User not logged in
		exit("<script>document.location='login.php'; //User not logged in</script>");
	}
	
	$uid = $_SESSION['userid'];
	$user = $db->get_user($uid);
	if($is_post) {
		$res = $db->create_order($uid, $cart, $firstname, $lastname, $address, $creditcard);
		if($res === TRUE) {
			$db->cart_clear($uid);
			exit("<script>document.location='index.php';</script>");
		} else {
			exit("Creation failed, should never happen a normal user");
		}
	} else {
		$cart = json_encode(isset($_SESSION['userid']) ? $db->cart_get($uid) : array());
		$script = "var cart = $cart";
		$scriptfile = "/checkout.js";
		include $_SERVER["DOCUMENT_ROOT"]."/include/header.php";
	}
?>
<div class="row row-offcanvas row-offcanvas-right">
	<div class="col-xs-12 col-sm-9">
		<p class="pull-right visible-xs">
			<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
		</p>
		<div class="jumbotron">
			<h1>Checkout</h1>
			<p>
				Please fill in your information
			</p>
		
		</div>

		<div class="well">	
			<p>
				<h3>Shopingcart <span class="badge pull-right" id="cart_size">0</span></h3>
			</p>
				<table  id="cart" width="100%">
				</table>
				<ul class="nav" id="cart_footer">
				<li><h4>Sum: <span id="cart_price">0</span> SEK</h4></li>
				<li>
					<button type="button" class="btn btn-default btn-sm" id="cart_clear">
						<span class="glyphicon glyphicon-trash"></span> Clear cart
					</button>
				</li>
			</ul>
			</div>
				<div class="well">	
				<form action="" method="POST" role="form" id="order">
					<div class="form-group">
						<label class="control-label" for="firstname">Firstname</label>
						<input class="form-control" type="text" name="firstname" id="firstname" value="<?= $user["FirstName"] ?>">
						<span class="help-block">The very first part of your full name</span>
					</div>
					<div class="form-group">
						<label class="control-label" for="lastname">Lastname</label>
						<input class="form-control" type="text" name="lastname" id="lastname" value="<?= $user["LastName"] ?>">
						<span class="help-block">The last part of your full name</span>
					</div>
					<div class="form-group">
						<label class="control-label" for="address">Home Address</label>
						<input class="form-control" type="text" name="address" id="address" value="<?= $user["Address"] ?>">	
						<span class="help-block">Your home address</span>
					</div>
					<div class="form-group">
						<label class="control-label" for="creditcard">Credit card number</label>
						<input class="form-control" type="text" name="creditcard" id="creditcard">
						<span class="help-block cardimg"></span>
						<span class="help-block cardinfo">Your credit card number </span>
					</div>

					
					<button class="btn btn-default btn-lg" type="submit">Order</button>
				</form>
			</div>
		
	</div><!--/span-->
</div><!--/row-->

<?php
	include $_SERVER["DOCUMENT_ROOT"].'/include/footer.php';
?>

