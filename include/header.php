<?php 
	require_once '/classes/validate.php'; 
	Validate::is_logged_in(isset($requireLogin) ? $requireLogin : FALSE); 	
?>
<!doctype html>
<html>
	<head>
		<title><?= (!isset($title) || !empty($title)) ? "StickyCo" : $title ?></title>
		<meta charset="utf-8">
		<meta name="author" content="ada10fl2,ada10jbe" />
		<meta name="description" content="StickyCo Webshop" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
		<link href="/css/styles.css" rel="stylesheet">
		
		<script src="/js/jquery.v1.10.2.min.js"></script>
		<script src="/js/jsrender.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/bootstrap-tooltip.js"></script>
		<?php
			if(isset($scriptfile) && !empty($scriptfile)){
				echo "<script src='$scriptfile'></script>";
			}
		?>
		<script>
			$(document).ready(function(){
				var parts = document.URL.split("/");
				var firstFolder = parts[3];
				$(".nav a[href='/" + firstFolder + "']").parent().attr("class", "active");
			});
			<?= $script ?>
		</script>
	</head>

	<body>
	<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">StickyCo.se</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li>
						<a href="/index.php">Products</a>
					</li>
					<li class="visible-xs">
						<a href="/checkout.php">Checkout your cart</a>
					</li>
					<li>
						<a href="/about.php">About</a>
					</li>
				</ul>
				<ul class="nav navbar-nav pull-right">
					<?php
						if(isset($_SESSION['user'])){
							?>
							<li class="disabled">
							<a href="#">
								<b><span class="glyphicon glyphicon-user"></span> <?= $_SESSION['user'] ?></b>
							</a>
							</li>
							<li>
							<a href="/logout.php">
								<b>Logout</b>
							</a>
							</li>
							<?php
						} else { 
							?>
							<li>
							<a href="/login.php">
								<b><span class="glyphicon glyphicon-home"></span> Log in</b>
							</a>
							</li>
							<?php
						}
					?>
				</ul>
			</div><!-- /.nav-collapse -->
		</div><!-- /.container -->
	</div><!-- /.navbar -->
	<div class="container">
	