<?php
require_once('/classes/db.php');
session_start();

$db = new db();
$json = json_encode($db->get_products());
$cart = json_encode(isset($_SESSION['userid']) ? $db->cart_get($_SESSION['userid']) : array());
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
			<h1>StickyCo.se</h1>
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
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
			<ul class="nav" id="cart">
				<li><br /></li>
			</ul>
			<ul class="nav" id="cart_footer">
				<li><h4>Sum: <span id="cart_price">0</span> SEK</h4></li>
				<li>
					<button type="button" class="btn btn-default btn-sm" id="cart_clear">
						<span class="glyphicon glyphicon-trash"></span> Clear cart
					</button>
				</li>
			</ul>
		</div><!--/.well -->
	</div><!--/span-->
</div><!--/row-->

<?php
include $_SERVER["DOCUMENT_ROOT"] . '/include/footer.php';
