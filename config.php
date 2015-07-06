<?
	//setup database links
	$db_server = 'p:localhost';	//s15.sherrodcomputers.com
	$db_username = 'cce_user1';
	$db_password = 'as867bhs87asFS';
	$db_name = 'cce';
	define("DEFAULT_LANG", english);
	
	$page_name = strtolower(substr($_SERVER['SCRIPT_NAME'], - (strlen($_SERVER['SCRIPT_NAME']) - strrpos($_SERVER['SCRIPT_NAME'],"/") - 1)));
	$session_hours=1;
	ini_set('session.gc_maxlifetime', (3600 * $session_hours));		// server should keep session data for AT LEAST $session_hours hour(s)  ... 3600(1 hour) * $session_hours
	session_set_cookie_params((3600 * $session_hours));			// each client should remember their session id for EXACTLY 1 hour(s)     ... 3600(1 hour) * $session_hours
	
	$use_tiny_mce_editor=0;
	
	$datasource = mysqli_connect($db_server, $db_username, $db_password, $db_name) or die("Could not connect to database server");
	
	$user_thumb_width=100;
	$user_thumb_height=100;
?>