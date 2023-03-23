<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$default_view = 'welcome';

spl_autoload_register(function($class) {
	$file = __DIR__ . '/../lib/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

	if (file_exists($file)) {
		include($file);
	}
});

\Bookshop\SessionContext::create();

$mode = 'mock';
switch (strtolower($mode)) {
	case 'pdo':
	case 'mysqli':
	case 'mock':
		require_once('lib/Data/Datamanager_' . $mode . '.php');
		break;
	default:
		break;
}

