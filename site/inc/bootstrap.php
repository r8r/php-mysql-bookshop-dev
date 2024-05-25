<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 'On');

spl_autoload_register(
	function($class) {
		$filename = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . str_replace('\\',
				DIRECTORY_SEPARATOR, $class) . '.php';
		if (file_exists($filename)) {
			include($filename);
		}
	}
);

\Bookshop\SessionContext::create();

$dm = 'mock';
//$dm = 'mysqlpdo';
require_once(__DIR__ . '/../lib/Data/DataManager_' . $dm . '.php');

$default_view = 'welcome';