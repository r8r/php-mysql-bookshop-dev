<?php

// force strict type declarations

declare(strict_types=1);

// set error reporting

error_reporting(E_ALL);
ini_set('display_errors', 'On');

// autoload Classes

spl_autoload_register(function ($class) {
	$filename =  __DIR__ . '/../lib/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
	if (file_exists($filename)) {
		include($filename);
	}
});

// create session

Bookshop\SessionContext::create();

// include DataManager

$mode = 'pdo';
switch (mb_strtolower($mode)) {
	case 'pdo':
		$class = 'mysqlpdo';
		break;
	default:
		$class = 'mock';
		break;
}
require_once(__DIR__ . '/../lib/Data/DataManager_' . $class . '.php');
