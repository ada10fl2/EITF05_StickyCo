<?php
	$address = isset($_POST['address']) ? $_POST['address'] : "";
	$first = isset($_POST['firstname']) ? $_POST['firstname'] : "";
	$last = isset($_POST['lastname']) ? $_POST['lastname'] : "";
		require_once('/classes/db.php');
			session_start();
	$db = new db();
	$json = json_encode($db->get_products());
	$user = $db->get_user($_SESSION['userid']);
	$cart = json_encode(isset($_SESSION['userid']) ? $db->cart_get($_SESSION['userid']) : array());

	if(!empty($address) && !empty($first) && !empty($last)) {
		$res = $db->create_order($_SESSION['userid'], $cart, $first, $last, $address);
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
		$script = "var products = $json; var cart = $cart";
		$scriptfile = "/checkout.js";
		$json = json_encode($db->get_products());
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
						<label class="control-label" for="password">Credit card number</label>
						<input class="form-control" type="text" name="creditcard" id="creditcard">
						<span class="help-block">Your credit card number</span>
					</div>

					
					<button class="btn btn-default btn-lg" type="submit">Order</button>
				</form>
			</div>
		
	</div><!--/span-->
</div><!--/row-->

<?php
	include $_SERVER["DOCUMENT_ROOT"].'/include/footer.php';
?>

