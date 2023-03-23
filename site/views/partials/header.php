<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title>SCM4 Book Shop</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
	<link href="assets/main.css" rel="stylesheet">

</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">

		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">SCM 4 Bookshop</a>
		</div>

		<div class="navbar-collapse collapse" id="bs-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li  <?php if ($view === 'welcome') { ?>class="active"<?php } ?>><a href="index.php">Home</a></li>
        <li <?php if ($view === 'list') { ?>class="active"<?php } ?>><a href="index.php?view=list">List</a></li>
        <li  <?php if ($view === 'search') { ?>class="active"<?php } ?>><a href="index.php?view=search">Search</a></li>
        <li  <?php if ($view === 'checkout') { ?>class="active"<?php } ?>><a href="index.php?view=checkout">Checkout</a></li>
      </ul>
			<ul class="nav navbar-nav navbar-right login">
				<li>
					<a href="index.php?view=checkout">
						<span class="badge"><?php print \Bookshop\ShoppingCart::size(); ?></span> <span class="glyphicon
						glyphicon-shopping-cart"
						aria-hidden="true"></span></a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Not logged in!
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li>
							<a href="index.php?view=login">Login now</a>
						</li>
					</ul>
				</li>
			</ul> <!-- /. login -->
		</div><!--/.navbar-collapse -->

	</div>
</div>

<div class="container">