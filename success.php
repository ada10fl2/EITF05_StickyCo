<?php
	require_once('/classes/db.php');
	require_once('/classes/validate.php');
	$scriptfile="";
	include $_SERVER["DOCUMENT_ROOT"]."/include/header.php";
?>


<div class="row row-offcanvas row-offcanvas-right">
	<div class="col-xs-12 col-sm-9">
		<p class="pull-right visible-xs">
			<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
		</p>
		<div class="jumbotron">
			<h1>Success!</h1>
			<img src="/img/green-tick.png" class="greentick">
			<p>
				<a href="index.php">
					<button type="button" class="btn btn-default btn-lg">Return To Products</button>
				</a>
				Your purchase was successful!
			</p>
			
		</div>
	</div><!--/span-->
</div><!--/row-->



<?php
	include $_SERVER["DOCUMENT_ROOT"].'/include/footer.php';
?>
