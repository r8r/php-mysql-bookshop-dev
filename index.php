<?php
include_once("inc/bootstrap.php");

$default_view = "welcome";
$view = $default_view;
if (isset($_REQUEST['view']) && file_exists(__DIR__ . '/views/' . $_REQUEST['view'] . '.php')) {
	  $view = $_REQUEST['view'];
}

// auswerten von post-daten
$postAction = isset($_REQUEST[Bookshop\Controller::ACTION]) ? $_REQUEST[Bookshop\Controller::ACTION] : null;
if ($postAction) {
	
}

include("views/" . $view . ".php");
