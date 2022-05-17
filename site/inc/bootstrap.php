<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$default_view = 'welcome';
//$dm_mode = 'mock';
$dm_mode = 'pdo';

// ---------

spl_autoload_register(function ($class) {
	$filename = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

	if (file_exists($filename)) {
		require_once ($filename);
	}
});


//session_start();
\Bookshop\SessionContext::create();
// ab hier ist $_SESSION verfügbar

$dm_class = match (mb_strtolower($dm_mode)) {
	'mysqli' => 'mysqli',
	'pdo' => 'mysqlpdo',
	default => 'mock'
};

/*
// Variante vor PHP 8
switch (mb_strtolower($dm_mode)) {
	case 'pdo':
		$dm_class = 'mysqlpdo';
		break;
	case 'mysqli':
		$dm_class = 'mysqli';
		break;
	default:
		$dm_class = 'mock';
		break;
}
*/

$dm_filename = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Data' .
               DIRECTORY_SEPARATOR . 'DataManager_' . $dm_class . '.php';

if (file_exists($dm_filename)) {
	require_once($dm_filename);
}
else {
	die('Error: DataManager could not be loaded.');
}