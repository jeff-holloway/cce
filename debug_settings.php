<?
	$debug_mode = false;	
	if($_SERVER['REMOTE_ADDR'] == '50.76.161.186' || $_SERVER['REMOTE_ADDR'] == '173.10.208.206') $debug_mode = true;

	// capture errors
	if($debug_mode) {
		error_reporting(E_ALL ^ E_DEPRECATED);
		ini_set('display_errors', '1');
	} else {
		error_reporting(0);
	}

?>