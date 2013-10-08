<?php
	require_once('/classes/db.php');
	require_once('/classes/validate.php');
	
	$cc_nr = Validate::ifset($_POST['cc_nr']);
	$cc_mm = Validate::ifset($_POST['cc_mm']);
	$cc_yy = Validate::ifset($_POST['cc_yy']);
	$cc_cv = Validate::ifset($_POST['cc_cv']);
	$firstname = Validate::ifset($_POST['firstname']);
	$lastname = Validate::ifset($_POST['lastname']);
	$address = Validate::ifset($_POST['address']);
	
	$db = new db();
	$is_post = Validate::is_POST($_SERVER);
	
	Validate::is_logged_in(TRUE); //send unauth away;
	
	$is_valid = 
		Validate::is_name($firstname) &&
		Validate::is_name($lastname) &&
		Validate::is_address($address) && 
		Validate::is_creditcard($cc_nr,$cc_cv,$cc_mm,$cc_yy);

	$uid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
	$user = isset($_SESSION['userid']) ? $db->get_user($uid) : array();
	$cart = isset($_SESSION['userid']) ? json_encode($db->cart_get($uid)) : json_encode(array());
	
	if($is_post && $is_valid) {	
		$res = $db->create_order($uid, $cart, $firstname, $lastname, $address);
		if($res === TRUE) {
			$db->cart_clear($uid);
			exit("<script>document.location='success';</script>");
		} else {
			exit("Creation failed, should never happen a normal user");
		}
	} else {
		$post = $is_post ? "true" : "false";
		$valid = $is_valid ? "true" : "false";
		$script = "var cart = $cart; var wasPosted = $post; var wasValid = $valid;";
		$scriptfile = "/checkout.js";
		$requireLogin = TRUE;
		include $_SERVER["DOCUMENT_ROOT"]."/include/header.php";
	}
?>
<div class="row row-offcanvas row-offcanvas-right">
	<div class="col-xs-12 col-sm-9">
		<div class="jumbotron">
			<h1>Checkout</h1>
			<p>
				Please fill in your information
			</p>
		</div>

		<div class="well">	
			<p>
				<legend>Shopingcart <span class="badge pull-right" id="cart_size">0</span></legend>
			</p>
			<table  id="cart" width="100%"></table>
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
			<form method="POST" role="form" id="order" class="form-horizontal">
				<legend>Payment</legend>
				
				<div class="control-group">
					<label class="control-label" for="firstname">Firstname</label>
					<input class="form-control" type="text" name="firstname" id="firstname" value="<?= $user["FirstName"] ?>" maxlength="45">
					<span class="help-block">The very first part of your full name</span>
				</div>
			
				<div class="control-group">
					<label class="control-label" for="lastname">Lastname</label>
					<input class="form-control" type="text" name="lastname" id="lastname" value="<?= $user["LastName"] ?>" maxlength="45">
					<span class="help-block">The last part of your full name</span>
				</div>
			
				<div class="control-group">
					<label class="control-label" for="address">Home Address</label>
					<input class="form-control" type="text" name="address" id="address" value="<?= $user["Address"] ?>" maxlength="200">	
					<span class="help-block">Your home address</span>
				</div>
			
				<div class="control-group">
					<label class="control-label" for="address">Credit card number</label>
					<input class="form-control" type="text" name="cc_nr" id="cc_nr" placeholder="•••• •••• •••• ••••" value="<?= $cc_nr ?>" maxlength="19">
					<span class="help-block cardimg"></span>
					<span class="help-block cardinfo">Your credit card number </span>
				</div>
			
				<div class="control-group">
					<label for="cc_mm">Month</label> / <label for="cc_yy">Year</label><br>
					<input class="form-control pull-left digi2" type="text" name="cc_mm" id="cc_mm" placeholder="<?= date('m'); ?>" value="<?= $cc_mm ?>" maxlength="2">
					<span class="pull-left sldiv">/</span> 
					<input class="form-control pull-left digi2" type="text" name="cc_yy" id="cc_yy" placeholder="<?= date('y'); ?>" value="<?= $cc_yy ?>" maxlength="2">
					<div class="clearfix"></div>
					<span class="help-block">'Valid until' of your card</span>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="cc_cv">Credit card verification</label>
					<input class="form-control digi3" type="text" name="cc_cv" id="cc_cv" placeholder="•••" value="<?= $cc_cv ?>" maxlength="3">
					<span class="help-block cardinfo">Your CVV2, three digits on the back</span>
				</div>
				
				<button class="btn btn-default btn-lg" type="submit">Order</button>
			</form>
		</div>
	</div><!--/span-->
</div><!--/row-->

<?php
	include $_SERVER["DOCUMENT_ROOT"].'/include/footer.php';
?>

