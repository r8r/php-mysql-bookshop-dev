<?php
require_once('inc' . DIRECTORY_SEPARATOR . 'bootstrap.php');

$view = $default_view;

if (
	isset($_REQUEST['view']) &&
	file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $_REQUEST['view'] . '.php')
) {
	$view = $_REQUEST['view'];
}

$postAction = $_REQUEST[\Bookshop\Controller::ACTION] ?? null;
if ($postAction != null) {
	\Bookshop\Controller::getInstance()->invokePostAction();
}


require_once('views' . DIRECTORY_SEPARATOR . $view . '.php');