<?php

declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

$default_view = 'welcome';

// Classloader
spl_autoload_register(function($class) { // Bookshop\Category
	$filename = __DIR__ . '/../lib/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
	if (file_exists($filename)) {
		require_once($filename);
	}
});

Bookshop\SessionContext::create();

$mode = 'mock';

switch (mb_strtolower($mode)) {
	case 'pdo':
		$class = 'mysqlpdo';
		break;
	default:
		$class = 'mock';
		break;
}

require_once(__DIR__ . '/../lib/Data/DataManager_' . $class . '.php');