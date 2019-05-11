<?php
declare(strict_types=1);
ini_set('display_errors', "1");
error_reporting(E_ALL);

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