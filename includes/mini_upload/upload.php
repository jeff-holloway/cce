<?php
include_once('../../application.php');
function return_result($rslt) {
	echo json_encode($rslt);
	exit;
}

if(!is_logged_in()) {
	$rslt['status_code'] = 0;
	$rslt['msg'] = 'You must be logged in to upload files.';
	return_result($rslt);
}

$upcounter = $_GET['uuid'];

// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif','zip','xls','xlsx','pdf','doc','docx');

if(isset($_FILES['upl_'.$upcounter]) && $_FILES['upl_'.$upcounter]['error'] == 0){

	$extension = pathinfo($_FILES['upl_'.$upcounter]['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		$rslt['status_code'] = 0;
		$rslt['msg'] = 'Invalid File Extension';
		return_result($rslt);
	}
		
	$finfo = pathinfo($_FILES['upl_'.$upcounter]['name']);
	$new_filename = str_replace("'","_",$finfo['filename']."-".uniqid().".".$finfo['extension']);
	
	$public=0;
	if($_SESSION['upload_params'][$upcounter]['section_id']==SECTION_AVATAR || $_SESSION['upload_params'][$upcounter]['section_id']==SECTION_LOGO_CUST || 
		$_SESSION['upload_params'][$upcounter]['section_id']==SECTION_LOGO_STORE || $_SESSION['upload_params'][$upcounter]['section_id']==SECTION_CERTIFICATES)		$public=1;
	
	//$move_destination="uploads/".$new_filename;
	$move_destination="".$defaultsarray['base_path']."/uploads/".$new_filename;		//documents for private access...or masked temp filename
	if($public==1)		$move_destination="../../documents/".$new_filename;			//public files like images adn avatars.
	
	//link to current merchant
	$use_merchant_id=0;	
	if($_SESSION['merchant_id']==0 && $_SESSION['selected_merchant_id'] > 0)
	{
		$use_merchant_id=$_SESSION['selected_merchant_id'];
	}
	elseif($_SESSION['merchant_id'] > 0)
	{
		$use_merchant_id=$_SESSION['merchant_id'];	
	}
	
	//link to current store
	$use_store_id=0;
	if($_SESSION['store_id']==0 && $_SESSION['selected_store_id'] > 0)
	{
		$use_store_id=$_SESSION['selected_store_id'];
	}
	elseif($_SESSION['store_id'] > 0)
	{
		$use_store_id=$_SESSION['store_id'];	
	}
		
	if(move_uploaded_file($_FILES['upl_'.$upcounter]['tmp_name'], $move_destination))
	{		
		$sql = "
			insert into attached_files
				(xref_id,
				filename,
				filesize,
				linedate_added,
				section_id,
				access_level,
				deleted,
				user_id,
				merchant_id,
				store_id,
				public_name,
				public_flag)
				
			values ('".sql_friendly($_SESSION['upload_params'][$upcounter]['xref_id'])."',
				'".sql_friendly($new_filename)."',
				0,
				now(),
				'".sql_friendly($_SESSION['upload_params'][$upcounter]['section_id'])."',
				'".sql_friendly($_SESSION['access_level'])."',
				0,
				'".sql_friendly($_SESSION['user_id'])."',
				'".sql_friendly($use_merchant_id)."',
				'".sql_friendly($use_store_id)."',
				'".sql_friendly(get_filename_without_unique($new_filename))."',
				'".sql_friendly($public)."')
		";
		simple_query($sql);
		
		$rslt['status_code'] = 1;
		$rslt['msg'] = 'success!';
		$rslt['filename_new'] = $new_filename;
		$rslt['filename_original'] = $_FILES['upl_'.$upcounter]['name'];
		$rslt['extra_params'] = $_SESSION['upload_params'][$upcounter]['extra_params'];
		return_result($rslt);
	}
}

$rslt['status_code'] = 0;
$rslt['msg'] = 'No files found.';
return_result($rslt);