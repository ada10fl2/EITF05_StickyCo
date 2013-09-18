<?php
	$script = "";
	$scriptfile = "/index.js";
	$title = "EITF05 Webshop";
	include $_SERVER["DOCUMENT_ROOT"]."/include/header.php";
?>

<div class="row row-offcanvas row-offcanvas-right">
	<div class="col-xs-12 col-sm-9">
	  <p class="pull-right visible-xs">
		<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
	  </p>
	  <div class="jumbotron">
		<h1>EITF05 Webshop</h1>
		<p>Checkout our awesome products</p>
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

	<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
		<div class="well sidebar-nav">
			<ul class="nav">
				<li>Shopingcart</li>
				<li><a href="#product0">1x Product 1</a></li>
				<li><a href="#product1">2x Product 2</a></li>
			</ul>
			<ul class="nav">
				<li><b>Sum: 0 SEK</b></li>
			</ul>
		</div><!--/.well -->
	</div><!--/span-->
</div><!--/row-->
<?php
	include $_SERVER["DOCUMENT_ROOT"].'/include/footer.php';
?>

