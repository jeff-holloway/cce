<?
	session_start();

	if(isset($_GET['out'])) 
	{
		//echo "destryoing session";
		//session_destroy();		
		unset($_COOKIE['uuid']);
		unset($_COOKIE['user']);
		unset($_SESSION);
		session_destroy();	
	}
	
	include_once('config.php'); 			// database settings
	
	include_once('language_pack.php'); 	// language packs
	include_once('debug_settings.php');	// sets default debug settings
	include_once('defaults.php'); 		// program defaults
	include_once('functions.php'); 		// core functions

	include_once('session_handler.php'); 	// take a guess

	include_once('includes/thumbnail.inc.php');	// thumbnail generator code (obviously)
	include_once('class/class.smtp.php');
	include_once('class/class.phpmailer.php');
	include_once("includes/fusioncharts/FC_Colors.php");
	include_once("includes/fusioncharts/FusionCharts.php");	
?>