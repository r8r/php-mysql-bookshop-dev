<?php
include_once("inc/bootstrap.php");
include("views/partials/header.php");

$default_view = "welcome";
$view = $default_view;
if (isset($_REQUEST['view']) && file_exists(__DIR__ . '/views/' . $_REQUEST['view'] . '.php')) {
	  $view = $_REQUEST['view'];
}
include("views/" . $view . ".php");

include("views/partials/footer.php");
