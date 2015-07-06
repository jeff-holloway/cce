<?php
include_once('application.php');

if (!empty($_FILES)) 
{	
	$new_folder = $defaultsarray['base_path']."uploads/";
	$def_access = $defaultsarray['default_attachment_access_level'];
	
	if(!file_exists($new_folder)) mkdir($new_folder);	

	$file_ext = get_file_ext($_FILES['Filedata']['name']);
	
	$new_filename = get_unique_filename($new_folder,$_FILES['Filedata']['name']);
	$curdate=0;
	
	if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $new_folder.$new_filename)) {
		$curdate=mrr_pull_image_created_date($new_folder.$new_filename);
		$rslt = 1;
	} else {
		$rslt = 0;
	}
	
	$user_id=0;
	$store_id=0;
	$merchant_id=0;
	
	if(isset($_SESSION['user_id']))		$user_id=$_SESSION['user_id'];
	if(isset($_SESSION['store_id']))		$store_id=$_SESSION['store_id'];
	if(isset($_SESSION['merchant_id']))	$merchant_id=$_SESSION['merchant_id'];
	
	//log that file was uploaded...
	$sql = "
		insert into attached_files
			(linedate_added,
			linedate_created,
			filename,
			filesize,
			section_id,
			xref_id,
			deleted,
			access_level,
			uuid,
			merchant_id,
			store_id,
			user_id)
			
		values (now(),
			'".sql_friendly($curdate)."',
			'".sql_friendly($new_filename)."',
			'".sql_friendly($_FILES['Filedata']['size'])."',
			'".sql_friendly($_POST['section_id'])."',
			'".sql_friendly($_POST['xref_id'])."',
			0,
			'".sql_friendly($def_access)."',
			'".createuuid()."',
			'".sql_friendly($merchant_id)."',
			'".sql_friendly($store_id)."',
			'".sql_friendly($user_id)."')
	";
	simple_query($sql);
	
	//$iid=mysql_insert_id();	
}	
?>1