<?php

require_once('inc/bootstrap.php');

/* if we have a form post, invoke the controller */
$postAction = $_REQUEST[Bookshop\Controller::ACTION] ?? null;
if ($postAction != null) {
	Bookshop\Controller::getInstance()->invokePostAction();
}


$view = $default_view;

if (isset($_REQUEST['view']) && $_REQUEST['view'] && file_exists(__DIR__ . '/views/' . $_REQUEST['view'] . '.php')
) {
	// TODO: injection check
	$view = $_REQUEST['view'];
}

require_once('views/' . $view . '.php');

