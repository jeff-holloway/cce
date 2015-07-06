<?
	define("SECTION_USER", 1);		//logged in users...
	define("SECTION_ACCOUNT", 2);		//Company, Customer, or Merchant. (interchangeable at the moment).
	define("SECTION_LOCATION", 3);	//store locations for a given merchant/customer/company.
	define("SECTION_EMAIL", 4);
	define("SECTION_WAITING", 5);
	define("SECTION_TEMPLATE", 6);
	define("SECTION_REPORT", 7);
	define("SECTION_AVATAR", 8);
	define("SECTION_LOGO_CUST", 9);
	define("SECTION_LOGO_STORE", 10);
	define("SECTION_CERTIFICATES", 11);
	
	$section_id_array[SECTION_USER] = "User";
	$section_id_array[SECTION_ACCOUNT] = "Customer";
	$section_id_array[SECTION_LOCATION] = "Location";
	$section_id_array[SECTION_EMAIL] = "EMail";
	$section_id_array[SECTION_WAITING] = "Waiting";
	$section_id_array[SECTION_TEMPLATE] = "Template";
	$section_id_array[SECTION_REPORT] = "Report";
	$section_id_array[SECTION_AVATAR] = "Avatar";
	$section_id_array[SECTION_LOGO_CUST] = "Company Logo";
	$section_id_array[SECTION_REPORT] = "Store Image";
	$section_id_array[SECTION_CERTIFICATES] = "Certificates";
	
	$section_id_array_page[SECTION_USER] = '';		// 'users.php?id={xref_id}';
	$section_id_array_page[SECTION_ACCOUNT] = '';	// 'customers.php?id={xref_id}';
	$section_id_array_page[SECTION_LOCATION] = '';	// 'stores.php?id={xref_id}';
	$section_id_array_page[SECTION_EMAIL] = '';		// 'email.php?id={xref_id}';
	$section_id_array_page[SECTION_WAITING] = '';	// 
	$section_id_array_page[SECTION_TEMPLATE] = '';	// 
	$section_id_array_page[SECTION_REPORT] = '';		// 
	$section_id_array_page[SECTION_AVATAR] = '';		// user avatars to separate from other documents
	$section_id_array_page[SECTION_LOGO_CUST] = '';	// company logo to separate from other documents
	$section_id_array_page[SECTION_LOGO_STORE] = '';	// store image to separate from other documents
	$section_id_array_page[SECTION_CERTIFICATES] = '';		// user avatars to separate from other documents
	
	/* load any default vars specified in the database */
	$sql = "
		select xname,
			xvalue		
		from defaults
	";
	$data_defaults = mysqli_query($datasource,$sql);
	while($row_defaults = mysqli_fetch_array($data_defaults)) 
	{
		$defaultsarray[$row_defaults['xname']] = $row_defaults['xvalue'];
	}	
	
	$date_display="".$defaultsarray['date_format_string']."";
	
	//set our query_string and http_referer to local variables in case they are blank we can still use them.
	if(isset($_SERVER['HTTP_REFERER'])) $http_referer = $_SERVER['HTTP_REFERER']; else $http_referer = "";
	if(isset($_SERVER['QUERY_STRING'])) $query_string = $_SERVER['QUERY_STRING']; else $query_string = "";
	// because some pages modify the query_string, we'll set a second one that will never be modified
	$query_string_original = $query_string;
	
	if(!isset($SCRIPT_NAME)) $SCRIPT_NAME = $_SERVER['PHP_SELF'];
	
	if(!isset($use_title)) $use_title = $defaultsarray['company_name'];
?>