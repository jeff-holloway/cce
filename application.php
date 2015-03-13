<?
	$page_name = strtolower(substr($_SERVER['SCRIPT_NAME'], - (strlen($_SERVER['SCRIPT_NAME']) - strrpos($_SERVER['SCRIPT_NAME'],"/") - 1)));
	$session_hours=1;
	ini_set('session.gc_maxlifetime', (3600 * $session_hours));		// server should keep session data for AT LEAST $session_hours hour(s)  ... 3600(1 hour) * $session_hours
	session_set_cookie_params((3600 * $session_hours));			// each client should remember their session id for EXACTLY 2 hours     ... 3600(1 hour) * $session_hours
	session_start();

	$debug_mode = true;	

	//if(!isset($_SESSION['location_id'])) $_SESSION['location_id'] = 0;
	
	// capture errors
	if($debug_mode) {
		error_reporting(E_ALL ^ E_DEPRECATED);
	} else {
		error_reporting(0);
	}

	if($_SERVER['REMOTE_ADDR'] == '50.76.161.186') 
	{
		error_reporting(E_ALL ^ E_DEPRECATED);
		ini_set('display_errors', '1');
	}

	include('config.php');
	
	//Language Settings...use the selected one below.
	$language_selected=0;	
	//language file array....
	$languages[0]="language_english.php";
	$languages[1]="language_spanish.php";
	include_once("".$languages[ 0 ]."");					//Pull default language always.
	if($language_selected!=0)
	{
		include_once("".$languages[ $language_selected ]."");	//Pull selected language if not default.
	}
	//................................................
	
	include('defaults.php');

	$datasource = mysql_pconnect($db_server, $db_username, $db_password) or die("Could not connect to database server");
	mysql_select_db($db_name);

	/* load any default vars specified in the database */
	$sql = "
		select xname,
			xvalue		
		from defaults
	";
	$data_defaults = mysql_query($sql,$datasource);
	while($row_defaults = mysql_fetch_array($data_defaults)) 
	{
		$defaultsarray[$row_defaults['xname']] = $row_defaults['xvalue'];
	}	
	
	include_once('functions.php');
	
	$secs=(int) $defaultsarray['session_timeout'];		
	$mrr_cookie_bake= time() +  $secs;				  		/* expires in SECS */
	
	if((!isset($_SESSION['access_level']) || $_SESSION['access_level'] == 0) 
				&& $page_name != 'login.php'
				&& $page_name != 'uploadify.php'
				&& $page_name != 'update.php'
				&& $page_name != 'view_attachment.php') 
	{
		$mrrid=0;	
				
		if(isset($_COOKIE['user']))
		{
			$mrrid=mrr_cookie_login_for_session($_COOKIE['user']);
		}
		
		if(isset($_COOKIE['uuid']) && $mrrid==0)
		{
			$mrrid=1;			
						
			setcookie("uuid", $_COOKIE['uuid'], $mrr_cookie_bake, "/");		//reset the cookie with 60 seconds
		}
		if($mrrid==0)
		{
			unset($_COOKIE['uuid']);
			setcookie("uuid", 'novalue', $mrr_cookie_bake , "/");			//reset the cookie with 60 seconds
			
			header("Location: login.php");
			die;
		}	
	}
		
	if(isset($_COOKIE['uuid']))
	{
		$secs=(int) $defaultsarray['session_timeout'];		
		$mrr_cookie_bake= time() +  $secs;				  				/* expires in SECS */
					
		setcookie("uuid", $_COOKIE['uuid'], $mrr_cookie_bake);				//reset the cookie with 60 seconds
	}	

	//set our query_string and http_referer to local variables in case they are blank we can still use them.
	if(isset($_SERVER['HTTP_REFERER'])) $http_referer = $_SERVER['HTTP_REFERER']; else $http_referer = "";
	if(isset($_SERVER['QUERY_STRING'])) $query_string = $_SERVER['QUERY_STRING']; else $query_string = "";
	// because some pages modify the query_string, we'll set a second one that will never be modified
	$query_string_original = $query_string;
	
	if(!isset($SCRIPT_NAME)) $SCRIPT_NAME = $_SERVER['PHP_SELF'];
	
	//include_once('class/class.phpmailer.php');
	//include_once('class/xml_reader.php');
	//include_once("includes/fusioncharts/FC_Colors.php");
	//include_once("includes/fusioncharts/FusionCharts.php");
	
	
	if(!isset($_SESSION['user_id'])) 
	{
		$mrrid=0;
		if(isset($_COOKIE['uuid']))
		{
			$mrrid=1;
			
			$secs=(int) $defaultsarray['session_timeout'];		
			$mrr_cookie_bake= time() +  $secs;				  		//expires in SECS
						
			setcookie("uuid", $_COOKIE['uuid'], $mrr_cookie_bake);		//reset the cookie with 60 seconds
			
			session_destroy();
			session_start($_COOKIE['uuid']);
		}
		if($mrrid==0)
		{
			header("Location: login.php");
			die();
		}
	}
	
	$use_tiny_mce_editor=0;
?>