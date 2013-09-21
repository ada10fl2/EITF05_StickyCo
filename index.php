<?php
require_once('/classes/db.php');
session_start();

$db = new db();
$json = json_encode($db->get_products());

if(isset($_SESSION['userid'])){
	$cart = json_encode($db->cart_get($_SESSION['userid']));
} else {
	if(!isset($_SESSION['cart'])){
		$_SESSION['cart'] = $db->cart_get(null);
	}
	$cart = json_encode($_SESSION['cart']);
}
$script = "var products = $json; var cart = $cart";
$scriptfile = "/index.js";
include $_SERVER["DOCUMENT_ROOT"] . "/include/header.php";
?>
<div class="row row-offcanvas row-offcanvas-right">
	<div class="col-xs-12 col-sm-9">
		<p class="pull-right visible-xs">
			<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
		</p>
		<div class="jumbotron">
			<p><img src="/img/logo.png" alt="StickyCo.se"></p>
			<p>Checkout our awesome stickers!</p>
		</div>
		<div class="row" id="products">
			<!--
			<div class="col-6 col-sm-6 col-lg-4">
			  <h2>Heading</h2>
			  <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
			  <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
			</div>
			-->
		</div><!--/row-->
	</div><!--/span-->
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">Skate
		<div class="modal-dialog">
		  <div class="modal-content">
			Katt
		  </div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
		<div class="well sidebar-nav">
			<ul class="nav" id="cart_header">
				<li>
					<h3>Shopingcart <span class="badge pull-right" id="cart_size">0</span></h3>
				</li>
			</ul>
			<table class="nav" id="cart" width="100%">
			</table>
			<ul class="nav" id="cart_footer">
				<li><h4>Sum: <span id="cart_price">0</span> SEK</h4></li>
				<li>
					<button type="button" class="btn btn-default btn-sm" id="cart_clear">
						<span class="glyphicon glyphicon-trash"></span> Clear cart
					</button>
					<button type="button" class="btn btn-default btn-sm" id="checkout">
						<span class="glyphicon glyphicon-credit-card"></span> Checkout
					</button>
				</li>
			</ul>
		</div><!--/.well -->
	</div><!--/span-->
</div><!--/row-->

<?php
include $_SERVER["DOCUMENT_ROOT"] . '/include/footer.php';
