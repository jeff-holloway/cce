<? include_once("application.php") ?>
<? 
     function field_exists($use_table, $use_field) 
     {
     	global $datasource;	
     	
     	$fieldname = $use_field;
     	$table = $use_table;
     	$fieldexists = false;
     	$result = mysql_query("SHOW FIELDS FROM `$table`",$datasource) or die("Error in Query");
     	while ($record = mysql_fetch_array($result)) 
     	{
     		if (strtolower($record['Field']) == $fieldname) 
     		{
     			$fieldexists = true;
     			break;
     		}
     	}     
     	return $fieldexists;
     }
     
     function table_exists($tablename) 
     {
     	global $datasource;
     	
          $exists = mysql_query("SELECT 1 FROM $tablename LIMIT 0", $datasource);
          if ($exists) return true;
          return false;
     }
     
     function update_version($version_no) 
     {
     	// writes the new version number to the database
     	global $datasource;
     	
     	$sql = "
     		update defaults
     		set xvalue = '$version_no'
     		where xname = 'site_version'
     	";
     	$data_version_update = simple_query($sql);
     }
     
     function default_exists($xname) 
     {
     	// checks to see if the specified default entry exists in the tbldefaults table
     	
     	$sql = "
     		select id
     		
     		from defaults
     		where xname = '".sql_friendly($xname)."'
     	";
     	$data = simple_query($sql);
     	
     	if(mysql_num_rows($data)) {
     		return true;
     	} else {
     		return false;
     	}
     }
     
     if(isset($_GET['auto_update']) && !isset($_GET['force_update'])) 
     {
     
     } 
     else 
     {
     	//if this isn't an auto_update, then the user needs to have admin privlidges to run this
     	if(!isset($_SESSION['access_level'])) header("Location: index.php");
     	include_once("header.php");
     	echo "<font class='standard12'>";
     }

	$current_version = $defaultsarray['site_version'];

	if($current_version == "" && !isset($_GET['override_check'])) 
	{
		die("Unable to determine the current version, please check manually");
	}
	
	if(isset($_GET['force_update'])) 
	{
		$current_version = $_GET['force_update'];
	}
?>
<br>
<table width="95%" align='center' class='standard12'>
<tr>
	<td>
     <b>Current Version: <?=$defaultsarray['site_version']?> 
     <? if(isset($_GET['force_update'])) echo "<br><font color='red'>Force Update - From version $_GET[force_update]</font>"; ?></b><p>
     <?
     	if($current_version == 1.0) 
     	{
     		/*
     		if(!field_exists('customers','customer_restricted')) 
     		{
     			$sql = "
     				alter table customers add column customer_restricted int default 0,
     					add column customer_restricted_notes text
     			";
     			simple_query($sql);
     		}
     		
     		if(!field_exists('log_email','uuid')) 
     		{
     			$sql = "
     				alter table log_email add column uuid varchar(200),
     					add column email_notes text
     			";
     			simple_query($sql);
     		}
     		
     		$sql = "
     			select id
     			
     			from menu
     			where menu_name = 'Update System'
     				and toplevel = 1
     		";
     		$data_check = simple_query($sql);
     		if(!mysql_num_rows($data_check)) 
     		{
     			$sql = "
     				insert into menu
     					(toplevel,
     					menu_name,
     					deleted,
     					access_level,
     					link,
     					zorder)
     					
     				values (1,
     					'Update System',
     					'0',
     					'90',
     					'update.php',
     					'4000')
     			";
     			simple_query($sql);
     		}
     		
     		if(!field_exists('log_email','xref_id')) 
     		{
     			$sql = "
     				alter table log_email add column xref_id int default 0,
     					add column section_id int default 0
     			";
     			simple_query($sql);
     		}
     		
     		if(!table_exists("log_email_views")) 
     		{
     			$sql = "
     				CREATE TABLE  `log_email_views` (
     				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
     				  `email_id` int DEFAULT 0,
     				  `linedate_viewed` datetime DEFAULT '0000-00-00',
     				  `ip_address` varchar(50),
     				  PRIMARY KEY (`id`)
     				) 
     			";
     			simple_query($sql);
     		}
     		
     		$sql = "
     			update option_values
     			set dummy_val = ''
     			where cat_id = '".sql_friendly(get_option_cat_id("import_fields"))."'
     				and fname = 'cost'
     		";
     		simple_query($sql);
     		
     		$sql = "
     			update option_values
     			set dummy_val = '',
     				fvalue = 'Vendor ID'
     			where cat_id = '".sql_friendly(get_option_cat_id("import_fields"))."'
     				and fname = 'default_vendor_id'
     		";
     		simple_query($sql);
     		*/
     		$current_version = 1.01;
     		update_version($current_version);
     	}
     	
     	if($current_version == 1.01) 
     	{	
     		
     		$current_version = 1.02;
     		update_version($current_version);
     	}	
     	
     	echo "<b>New Version: $current_version</b>";
     ?>
	<br><br>
	<a href="index.php">Click here</a> to return to the home page
	</td>
</tr>
</table>
</font>
<? if(isset($_GET['auto_update']) && !isset($_GET['force_update'])) {
	javascript_redirect('index.php');
} else {
	include("footer.php");
} ?>