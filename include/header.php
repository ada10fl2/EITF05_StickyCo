<!doctype html>
<html>
	<head>
		<title><?= (trim($title) == false) ? "Default Title" : $title ?></title>
		<meta charset="utf-8">
		<meta name="author" content="ada10fl2,ada10jbe" />
		<meta name="description" content="Webshop" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
		
		<link href="/css/bootstrap-theme.min.css" rel="stylesheet">
		<link href="/css/bootstrap-lightbox.min.css" rel="stylesheet">
		<link href="/css/styles.css" rel="stylesheet">
		
		<script src="/js/jquery.v1.10.2.min.js"></script>
		<script src="/js/jsrender.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/bootstrap-lightbox.min.js"></script>
		<script src="/js/bootstrap-tooltip.js"></script>
		<script src="/js/bootstrap-thumb-lightbox-w-tooltip.js"></script>
		
		<script><?= $script ?></script>
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
				<a class="navbar-brand" href="#">Project name</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">Home</a></li>
					<li><a href="#about">About</a></li>
					<li><a href="#contact">Contact</a></li>
				</ul>
			</div><!-- /.nav-collapse -->
		</div><!-- /.container -->
	</div><!-- /.navbar -->
	<div class="container">
	