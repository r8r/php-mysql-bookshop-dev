<?php
declare(strict_types=1);
ini_set('display_errors', "1");
error_reporting(E_ALL);
setlocale(LC_ALL, 'de_AT');

spl_autoload_register(function ($class) {
	$filename = __DIR__ . '/../lib/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
	if (file_exists($filename)) {
		include($filename);
	}
});

/**
 *
 * DataManager
 * change to switch between different implementations … 'mock' | 'pdo'
 */
$mode = 'mock';
switch (mb_strtolower($mode)) {
	case 'mysqli':
		$class = 'mysqli';
		break;
	case 'pdo':
		$class = 'mysqlpdo';
		break;
	default:
		$class = 'mock';
		break;
}
require_once(__DIR__ . '/../lib/Data/DataManager_' . $class . '.php');