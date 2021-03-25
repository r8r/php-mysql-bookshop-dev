<?php

require_once('inc/bootstrap.php');

$view = $default_view;

// TODO: wert gegen injection absichern (regex)
if (isset($_REQUEST['view']) && file_exists(__DIR__ . '/views/' . $_REQUEST['view'] . '.php')) {
	$view = $_REQUEST['view'];
}

$postAction = $_REQUEST[Bookshop\Controller::ACTION] ?? null;
if ($postAction != null) {
	Bookshop\Controller::getInstance()->invokePostAction();
}

require_once('views/' . $view . '.php');
