<?php
	session_start();
	// Constants

	define('SERVER_DIR', dirname ( __FILE__ ).'/server');
	define('PUBLIC_DIR', dirname ( __FILE__ ).'/client');
	require_once SERVER_DIR.'/services/DotEnv.class.php';
	
	// Load Environment variables
	
	(new DotEnv(__DIR__ .'/.env'))->load();
	
	if (getenv('APP_ENV') == "dev") {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}
	
	require_once SERVER_DIR.'/services/Router.class.php';
	
	
	$router = (new Router());
	
	// Load Main Page
	require_once PUBLIC_DIR.'/index.php';
?>