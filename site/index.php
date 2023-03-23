<?php

require_once('inc/bootstrap.php');

$view = $default_view;

if (isset($_REQUEST['view']) && file_exists(__DIR__ . '/views/' . $_REQUEST['view'] . '.php')) {
  $view = $_REQUEST['view'];
}

$postAction = $_REQUEST[Bookshop\Controller::ACTION] ?? null;
if ($postAction != null) {
	// an den controller übergeben
	Bookshop\Controller::getInstance()->invokePostAction();
}

require_once('views/' . $view . '.php');