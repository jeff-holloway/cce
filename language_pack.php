<?
	
	
	if(!isset($_SESSION['user_lang']) && !isset($_SESSION['current_lang'])) {
		$_SESSION['user_lang'] = DEFAULT_LANG;
  	$_SESSION['current_lang'] = "English";
  }
  
  
	if(isset($_GET['lang'])) {
	  $_SESSION['lang'] = $_GET['lang'];
  }

	
  if(isset($_SESSION['lang'])) {
  	
		if($_SESSION['lang'] == "English" || $_SESSION['lang'] == "Inglés" ) {
			$_SESSION['user_lang'] = "en";
			$_SESSION['current_lang'] = "English";
		}
		if($_SESSION['lang'] == "Spanish" || $_SESSION['lang'] == "Español" ) {
			$_SESSION['user_lang'] = "sp";
			$_SESSION['current_lang'] = "Spanish";
		}
		
	}
	
	$lang_array = array();
	
	include_once('lang.' . DEFAULT_LANG . '.php');
	include_once('lang.' . $_SESSION['user_lang'] . '.php');

	$lang_array = array_merge($lang_array_en, $lang_array);
	
?>