<?
	define("SECTION_USER", 1);
	define("SECTION_ACCOUNT", 2);
	define("SECTION_LOCATION", 3);
	define("SECTION_EMAIL", 4);
	
	$section_id_array[SECTION_USER] = "".$lang['section_user']."";
	$section_id_array[SECTION_ACCOUNT] = "".$lang['section_account']."";
	$section_id_array[SECTION_LOCATION] = "".$lang['section_location']."";
	$section_id_array[SECTION_EMAIL] = "".$lang['section_email']."";
	
	$section_id_array_page[SECTION_USER] = '';		//'users.php?id={xref_id}';
	$section_id_array_page[SECTION_ACCOUNT] = '';	// 'account.php?id={xref_id}';
	$section_id_array_page[SECTION_LOCATION] = '';	// 'location.php?id={xref_id}';
	$section_id_array_page[SECTION_EMAIL] = '';		// 'email.php?id={xref_id}';
?>