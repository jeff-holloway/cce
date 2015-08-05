<? include_once("application.php") ?>
<? include_once("functions.php") ?>
<?
if(!isset($_SESSION['user_id']))
{	//session is dead, so return XML warning so that the user knows the timeout occurred.
	$return_var = "<response><rslt>-1</rslt><mrrMsg><![CDATA[ Your session has timed out. Please Sign In. ]]></mrrMsg></response>";
	header('Content-Type: text/xml');		
	echo $return_var;
}
?>
<?
	if(!isset($_GET['cmd'])) {
		die("You have reached this page incorrectly");
	}
	
	if(isset($_GET['q'])) {
		// fix the search string for ampersands
		foreach($_GET as $key => $value) {
			if($key != 'q' && $key != 'cmd') {
				$_GET['q'] .= "&".$key.$value;
			}
		}
	}
	
	if(!isset($_SESSION['user_id']))				$_SESSION['user_id']=0;
	if(!isset($_SESSION['access_level']))			$_SESSION['access_level']=0;
	if(!isset($_SESSION['view_access_level']))		$_SESSION['view_access_level']=0;
	
	if(!isset($_SESSION['merchant_id']))			$_SESSION['merchant_id']=0;
	if(!isset($_SESSION['store_id']))				$_SESSION['store_id']=0;
	
				
	switch ($_GET['cmd']) {
						
		case 'set_email_view_log':
			set_email_view_log();
			break;	
		case 'get_email_view_log':
			get_email_view_log();
			break;
		case 'view_error_file':
			view_error_file();
			break;		
			
		case 'display_attachments':
			display_attachments();
			break;
		case 'delete_attachment':
			delete_attachment();
			break;
		case 'rename_attachment':
			rename_attachment();
			break;	
		case 'fetch_file_info':
			fetch_file_info();
			break;	
		case 'rename_document':
			rename_document();
			break;	
		case 'view_attached_file':
			view_attached_file();
			break;		
		case 'send_attachment_email':
			send_attachment_email();
			break;		
		case 'load_waiting_files':
			load_waiting_files();
			break;
		case 'mrr_update_file_details':
			mrr_update_file_details();
			break;
		
			
		case 'get_file_uploader':
			get_file_uploader();
			break;
		
		
		case 'load_file_types':
			load_file_types();
			break;	
		case 'save_file_type':
			save_file_type();
			break;
		case 'delete_file_type':
			delete_file_type();
			break;
			
		case 'save_level':
			save_level();
			break;
		case 'save_level_option':
			save_level_option();
			break;
		case 'display_user_access_options':
			display_user_access_options();
			break;
		
		case 'load_template_items':
			load_template_items();
			break;
		case 'save_template':
			save_template();
			break;
		case 'save_template_item':
			save_template_item();
			break;
		case 'delete_template':
			delete_template();
			break;
		case 'delete_template_item':
			delete_template_item();
			break;
		case 'copy_template_items':
			copy_template_items();
			break;
		
		case 'load_cce_tagline':
			load_cce_tagline();
			break;
		case 'load_cce_messages':
			load_cce_messages();
			break;			
		case 'mrr_update_cce_message':
			mrr_update_cce_message();
			break;
			
		case 'mrr_reload_quick_links':
			mrr_reload_quick_links();
			break;
		case 'mrr_update_quick_links':
			mrr_update_quick_links();
			break;
		case 'mrr_update_quick_links_new':
			mrr_update_quick_links_new();
			break;
			
		case 'get_financial_inst_details':
			get_financial_inst_details();
			break;
		
		case 'display_merchant_program':
			display_merchant_program();
			break;			
		case 'load_merchants':
			load_merchants();
			break;
		case 'archive_merchant':
			archive_merchant();
			break;
		case 'delete_merchant':
			delete_merchant();
			break;
		case 'get_merchant_details':
			get_merchant_details();
			break;	
		case 'update_merchant':
			update_merchant();
			break;
		
		case 'load_dynamic_sidebar':
			load_dynamic_sidebar();
			break;
			
		case 'load_dynamic_user_select':
			load_dynamic_user_select();
			break;
		case 'load_dynamic_user_customer_select':
			load_dynamic_user_customer_select();
			break;
		case 'load_dynamic_user_store_select':
			load_dynamic_user_store_select();
			break;
		
		case 'load_co_slot_info':
			load_co_slot_info();
			break;
		case 'load_stores':
			load_stores();
			break;
		case 'archive_store_location':
			archive_store_location();
			break;
		case 'delete_store_location':
			delete_store_location();
			break;
		case 'get_store_location_details':
			get_store_location_details();
			break;	
		case 'update_store_location':
			update_store_location();
			break;
					
		case 'load_important_dates':
			load_important_dates();
			break;
		case 'get_important_date_details':
			get_important_date_details();
			break;
		case 'update_important_date':
			update_important_date();
			break;
		case 'archive_important_date':
			archive_important_date();
			break;
		case 'delete_important_date':
			delete_important_date();
			break;
			
		case 'archive_user':
			archive_user();
			break;
		
		case 'timeout_check':
			timeout_check();
			break;				
			
		case 'search_users':
			search_users();
			break;	
		case 'display_user_settings_form':
			display_user_settings_form();
			break;
		
		case 'load_merchant_archive':
			load_merchant_archive();
			break;
				
		case 'search_docs_filter':
			search_docs_filter();
			break;
		case 'search_custs_filter':
			search_custs_filter();
			break;
		case 'search_custs_filter_v2':
			search_custs_filter_v2();
			break;
		case 'search_stores_filter':
			search_stores_filter();
			break;
		
		case 'list_users_selected':
			list_users_selected();
			break;
		case 'search_users_filter':
			search_users_filter();
			break;	
		case 'new_user':
			new_user();
			break;
		case 'save_user':
			save_user();
			break;
		case 'save_user_pass':
			save_user_pass();
			break;
		case 'delete_user':
			delete_user();
			break;
		case 'get_user_details':
			get_user_details();
			break;
		
		case 'get_user_image':
			get_user_image();
			break;
		case 'get_user_cert':
			get_user_cert();
			break;
		case 'get_logo_image':
			get_logo_image();
			break;
		case 'get_store_image':
			get_store_image();
			break;
						
		case 'user_password_reset':
			user_password_reset();
			break;		
		case 'verify_access_level':
			verify_access_level();
			break;	
		
		case 'refresh_store_selector':
			refresh_store_selector();
			break;
		case 'refresh_sub_item_selector':
			refresh_sub_item_selector();
			break;
		case 'search_template_items':
			search_template_items();
			break;
		
		case 'update_auditor2_assignment':
			update_auditor2_assignment();
			break;
		case 'refresh_auditor2_assignment':
			refresh_auditor2_assignment();
			break;
		case 'refresh_auditor2_files':
			refresh_auditor2_files();
			break;
		
		case 'pick_selected_item':
			pick_selected_item();
			break;
			
		case 'update_bread_crumb_trail':
			update_bread_crumb_trail();
			break;
		case 'debread_crumb_trail':
			debread_crumb_trail();
			break;
		case 'zip_download_files':
			zip_download_files();
			break;
		case 'load_training_certs':
			load_training_certs();
			break;
		case 'save_sortable':
			save_sortable();
			break;
		
		case 'remove_logo_list':
			remove_logo_list();
			break;
			
		case 'mrr_heart_beat':
			mrr_heart_beat();
			break;
		default:
			case_default();
			break;		
	}
		
	//logs
	function set_email_view_log() 
	{
		$email_id=$_POST['email_id'];
		$ip_address=$_SERVER['REMOTE_ADDR'];
		$file_id=$_POST['file_id'];
		$user_id=$_SESSION['user_id'];
		
		$sql = "
			insert into log_email_views
				(id,
				email_id,
				linedate_viewed,
				ip_address,
				file_id,
				user_id)
			values
				(NULL,
				'".sql_friendly($email_id)."',
				NOW(),
				'".sql_friendly($ip_address)."',
				'".sql_friendly($file_id)."',
				'".sql_friendly($user_id)."')
		";
		simple_query($sql);
		
		display_xml_response("<rslt>1</rslt>");
	}
	function get_email_view_log() 
	{
		$sql = "
			select * 
			
			from log_email_views
			where email_id = '".sql_friendly($_POST['email_log_id'])."'
			order by linedate_viewed
			limit 50
		";
		$data = simple_query($sql);
		
		$html = "
			<table width='100%'>
			<tr>
				<td colspan='2'>Detailed E-Mail log<hr></td>
			</tr>
			<tr>
				<td><b>Date / Time</b></td>
				<td align='right'><b>IP Address</b></td>
		";
		
		while($row = mysqli_fetch_array($data)) {
			$html .= "
				<tr style='font-weight:normal'>
					<td>".date("M j, Y - h:i:s a", strtotime($row['linedate_viewed']))."</td>
					<td align='right'>$row[ip_address]</td>
				</tr>
			";
		}
		$html .= "</table>";
		
		display_xml_response("<rslt>1</rslt><html><![CDATA[$html]]></html>");
	}
	function view_error_file() {
		global $defaultsarray;
		
		$sql = "
			select *
			
			from log_scan
			where id = '".sql_friendly($_POST['file_id'])."'
				and linedate_reviewed = 0
		";
		$data = simple_query($sql);
		
		if(!mysqli_num_rows($data)) {
			display_xml_response("<rslt>0</rslt><rsltmsg>Could not locate file</rsltmsg>");
		} else {
			$row = mysqli_fetch_array($data);
			
			$uuid = createuuid();
			
			// copy the file to a temp location to view
			$tmp_filename = $uuid.$row['filename'];
			copy(getcwd().'/scanned/working/'.$row['filename'], $defaultsarray['base_path'].'/www/temp/'.$tmp_filename);
			
			display_xml_response("<rslt>1</rslt><filename><![CDATA[temp/$tmp_filename]]></filename>");
		}
	}
	
	//files and attachments	
	function display_attachments() {
		global $defaultsarray;
		
		$use_xml=0;
		if(isset($_POST['xref_id']))		$use_xml=1;
		
		$tab="";
		
		$sql = "
			select *,
				(select count(*) from log_email where log_email.attachment_id = attached_files.id) as send_count
			
			from attached_files
			where deleted = 0
				and section_id = '".sql_friendly($_POST['section_id'])."'
				and xref_id = '".sql_friendly($_POST['xref_id'])."'
				and filesize > 0
				and access_level <= '".$_SESSION['access_level']."'
			order by linedate_added desc
		";
		$data = simple_query($sql);	
		
		$tab.="
			<table width='100%'>
			<tr>
				<td><b>Filename</b></td>
				<td align='right'><b>Date Uploaded</b></td>
				<td align='right'><b>Times E-Mailed</b></td>
			</tr>
		";
		$mrr_cntr=0;
		while($row = mysqli_fetch_array($data)) {
			
			$tab.="
				<tr id='attachment_row_$row[id]'>
					<td><input type='hidden' id='mrr_attachment_file_".$mrr_cntr."' name='mrr_attachment_file_".$mrr_cntr."' value='".$row['id']."'>
						<a id='mrr_attachment_link_".$mrr_cntr."' href=\"javascript:view_attached_file($row[section_id], $row[xref_id], $row[id])\">$row[filename]</a></td>
					<td align='right'>".date("m/d/Y", strtotime($row['linedate_added']))."</td>
					<td align='right'>
						<a href='report_email_log.php?attachment_id=$row[id]' target='report_email_log_attachment_$row[id]'>".$row['send_count']."</a>
						&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:email_attachment($row[id])'><img id='email_attachment_icon_$row[id]' src='images/blank_message_20.png' alt='E-Mail Attachment' title='E-Mail Attachment' border='0' style='height:13px'></a>
						&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:delete_attachment($row[id])'><img src='images/delete_small.png' alt='Delete Attachment' title='Delete Attachment' border='0'></a>
					</td>
				</tr>
			";
			$mrr_cntr++;
		}
		$tab.="</table><input type='hidden' id='mrr_attachment_files' name='mrr_attachment_files' value='".$mrr_cntr."'>";
		
		if($use_xml==0)
		{
			echo $tab;		//uses the HTML mode in AJAX request.  DEFAULT for this function.
		}
		else
		{
			display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	//uses the XML response
		}
	}
	function delete_attachment() 
	{
		$sql = "
			update attached_files
			set deleted = 1
			where id = '".sql_friendly($_POST['id'])."'
			limit 1
		";
		simple_query($sql);
	}	
	function view_attached_file() 
	{
		global $defaultsarray;
		
		$sql = "
			select *
			
			from attached_files
			where id = '".sql_friendly($_POST['file_id'])."'
				and section_id = '".sql_friendly($_POST['section_id'])."'
				and xref_id = '".sql_friendly($_POST['xref_id'])."'
				
				and deleted = 0
		";		//and access_level <= '".$_SESSION['access_level']."'
		$data = simple_query($sql);	
		
		if(!mysqli_num_rows($data)) {
			display_xml_response("<rslt>0</rslt><rsltmsg>Could not locate file</rsltmsg>");
		} else {
			$row = mysqli_fetch_array($data);
			
			
			if($row['public_flag']==1)
			{
				$tmp_filename="/documents/".$row['filename']."";
			}
			else
			{
				$uuid = createuuid();
     						
     			// copy the file to a temp location to view
     			$tmp_filename = $uuid.$row['filename'];
     			copy($defaultsarray['base_path'].'/uploads/'.$row['filename'], $defaultsarray['base_path'].'public_html/temp/'.$tmp_filename);
     			
     			
     			$tmp_filename="/temp/".str_replace("#",'%23',$tmp_filename);			//File name is truncated if the '#' letter is found.  'test_file#1234.jpg' becomes 'test_file'.  File won't be found.	
			}
						
			display_xml_response("<rslt>1</rslt><filename><![CDATA[$tmp_filename]]></filename>");
		}
	}	
	function fetch_file_info()
	{
		$doc_id=0;
		$doc_name="";
		
		$doc_type=0;
		$sub_type=0;
		$doc_type_label="";
		$doc_sub_label="";
		
		$doc_date="";
		
		$doc_cust_id=0;
		$doc_store_id=0;
		$doc_cust_lock=0;	
		$doc_store_lock=0;	
		$doc_cust_name="";
		$doc_store_name="";
				
		$sql = "
			select attached_files.*,
				(select store_locations.store_name from store_locations where store_locations.id=attached_files.store_id) as store_label,
				(select merchants.merchant from merchants where merchants.id=attached_files.merchant_id) as cust_label,
				(select template_items.sub_group_id from template_items where template_items.id=attached_files.template_item_id) as temp_sub_grp
			
			from attached_files
			where attached_files.id = '".sql_friendly($_POST['file_id'])."'
		";
		$data = simple_query($sql);
		if($row = mysqli_fetch_array($data))
		{
			$doc_id=$row['id'];
			
			if($row['public_name']=="")		$row['public_name']=get_filename_without_unique($row['filename']);
			
			$doc_name=$row['public_name'];
			
			$doc_type=$row['template_item_id'];
			$sub_type=$row['template_item_id_sub'];
			if($row['temp_sub_grp'] > 0)
			{
				$doc_type=$row['temp_sub_grp'];
				$sub_type=$row['template_item_id'];
			}
			
			$doc_type_label=get_template_item_label_from_item($doc_type);
			$doc_sub_label=get_template_item_label_from_item($sub_type);	
			
			if(trim($doc_type_label)=="")		$doc_type_label="None Selected";	
			if(trim($doc_sub_label)=="")		$doc_sub_label="None Selected";
			
			$doc_date=date("m/d/Y",strtotime($row['linedate_display_start']));
			$doc_cust_id=$row['merchant_id'];
			$doc_store_id=$row['store_id'];
			
			$doc_cust_name=trim($row['cust_label']);
			$doc_store_name=trim($row['store_label']);	
			
			if(trim($doc_cust_name)=="")		$doc_cust_name="All Customers";
			if(trim($doc_store_name)=="")		$doc_store_name="All Store Locations";
					
			
			if($_SESSION['merchant_id'] > 0)		$doc_cust_lock=1;	
			if($_SESSION['store_id'] > 0)			$doc_store_lock=1;			
		}
		
		$doc_type_list=get_template_item_listing('temp_sub_sel');
		$doc_cust_list=get_cust_store_listing('cust_store_sel');
		
		$rval="
			<rslt>1</rslt>
			<DocID><![CDATA[$doc_id]]></DocID>
			<DocName><![CDATA[$doc_name]]></DocName>
			<DocType><![CDATA[$doc_type]]></DocType>
			<DocSub><![CDATA[$sub_type]]></DocSub>
			<DocTypeName><![CDATA[$doc_type_label]]></DocTypeName>
			<DocSubName><![CDATA[$doc_sub_label]]></DocSubName>
			<DocTypeList><![CDATA[$doc_type_list]]></DocTypeList>
			<DocCustList><![CDATA[$doc_cust_list]]></DocCustList>
			<DocDate><![CDATA[$doc_date]]></DocDate>
			<DocCust><![CDATA[$doc_cust_id]]></DocCust>
			<DocStore><![CDATA[$doc_store_id]]></DocStore>
			<DocCustName><![CDATA[$doc_cust_name]]></DocCustName>
			<DocStoreName><![CDATA[$doc_store_name]]></DocStoreName>
			<DocCustLock><![CDATA[$doc_cust_lock]]></DocCustLock>
			<DocStoreLock><![CDATA[$doc_store_lock]]></DocStoreLock>
		";		
		display_xml_response($rval);
	}
	function rename_document() 
	{		
		$adder="";
		
		if(isset($_POST['date']))		$adder.="linedate_display_start='".date("Y-m-d",strtotime($_POST['date']))."',";
		if(isset($_POST['custid']))		$adder.="merchant_id='".sql_friendly($_POST['custid'])."',";
		if(isset($_POST['storeid']))		$adder.="store_id='".sql_friendly($_POST['storeid'])."',";
				
		if(isset($_POST['typeid']))	
		{
			$adder.="template_item_id='".sql_friendly($_POST['typeid'])."',";
			
			if(!isset($_POST['subid']))		$_POST['subid']=0;
						
     		if($_POST['subid']==0 && $_POST['typeid'] > 0)
     		{	//if no subtype was picked, pick it for them (first sub-type in doc-type group).  If none, ignore.
     			$sql="
     				select id 
     				from template_items
     				where deleted=0
     					and archived=0
     					and sub_group_id='".sql_friendly($_POST['typeid'])."'
     				order by zorder asc,item_label asc, id asc
     			";	
     			$data=simple_query($sql);
     			if($row = mysqli_fetch_array($data)) 
     			{
     				$_POST['subid']=$row['id'];	//use the first one only.
     			}
     		}
     		
     		$adder.="template_item_id_sub='".sql_friendly($_POST['subid'])."',";
		}
		
		$sql = "
			update attached_files set
				".$adder."
				public_name = '".sql_friendly($_POST['new_name'])."'
			where id = '".sql_friendly($_POST['file_id'])."'
		";
		simple_query($sql);
		
		display_xml_response("<rslt>1</rslt>");		
	}
	function rename_attachment() 
	{
		global $defaultsarray;
		
		// get the old filename
		$sql = "
			select *
			
			from attached_files
			where id = '".sql_friendly($_POST['id'])."'
		";
		$data = simple_query($sql);
		$row = mysqli_fetch_array($data);
		
		$new_file_full = $defaultsarray['base_path']."/uploads/".$_POST['new_filename'];
		
		$return_filename = $row['filename']; // or
		$rslt = 0;
		
		if(!file_exists($new_file_full)) {
			
			if(rename($defaultsarray['base_path']."/uploads/$row[filename]", $new_file_full)) {
				$sql = "
					update attached_files
					set filename = '".sql_friendly($_POST['new_filename'])."'
					where id = '".sql_friendly($_POST['id'])."'
				";
				simple_query($sql);
				
				$return_filename = $_POST['new_filename'];
				$rslt = 1;
				
			}
		}
		display_xml_response("<rslt>$rslt</rslt><FileName><![CDATA[$return_filename]]></FileName>");		
	}
	function send_attachment_email() 
	{
		global $defaultsarray;
		global $section_id_array;
		
		$sql = "
			select *
			
			from attached_files
			where id = '".sql_friendly($_POST['attachment_id'])."'
		";
		$data = simple_query($sql);
		$row = mysqli_fetch_array($data);
		
		$from = $defaultsarray['emails_from'];
		$fromname = $from;
		$to = $_POST['email_to'];
		$toname = $to;
		
		$temp_sub=$_POST['email_subject'];
		if(trim($temp_sub)!="")
			$subject=$temp_sub;
		else
			$subject = $section_id_array[$row['section_id']]." attachment: $row[filename] - from $defaultsarray[company_name]";
		
		$text = $section_id_array[$row['section_id']]." attachment: $row[filename] is attached to this E-Mail as a PDF";
		$html = $text;
		
		$attm = $defaultsarray['base_path'].'/uploads/'.$row['filename'];
		
		$_POST['email_section_id'] = $row['section_id'];
		$_POST['email_xref_id'] = $row['xref_id'];
		$_POST['email_attachment_id'] = $row['id'];
		
		ob_start();
		sendMail($from,$fromname,$to,$toname,$subject,$text,$html,$attm);
		$email_result_text = ob_get_contents(); 
		ob_end_clean();
		
		if($email_result_text == '') {
			$email_result = 1;
		} else {
			$email_result = 0;
		}
		
		$return_var = "
			<EmailResult>$email_result</EmailResult>
			<EmailResultText>$email_result_text</EmailResultText>
		";
		
		display_xml_response($return_var);
	}
	
	
	function load_waiting_files() 
	{			
		$mrr_adder="";
		$allow_all=0;
		
		//find merchant template first...acts as a default.
     	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$mrr_adder.=" and attached_files.merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'";
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$mrr_adder.=" and attached_files.merchant_id='".sql_friendly($_SESSION['merchant_id'])."'";
     	}
     	
     	if($_SESSION['access_level']>=80)		$allow_all=1;
     	
     	//find store template next...override merchant if set.
     	if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
     	{
     		$mrr_adder.=" and attached_files.store_id='".sql_friendly($_SESSION['selected_store_id'])."'";
     	}     	
     	elseif($_SESSION['store_id'] > 0)
     	{
     		$mrr_adder.=" and attached_files.store_id='".sql_friendly($_SESSION['store_id'])."'";
     	}
		
		
		$rval="";
		$tab="";
		
		$tab.="
		<div class='mrr_sector_container'>
			<table cellpadding='0' cellspacing='0' border='0' style='width:100%'>
			
			<tbody>
		";			//<th valign='top'><b>Expire</b></th>
		/* 	width='100%'
			
			<thead>
			<tr>
					<th valign='top'><b>File<b></th>
					<th valign='top'><b>Processing</b></th>
					<th valign='top'><b>Display Date</b></th>					
					<th valign='top'><b>Public Name</b></th>
					<th valign='top'><b>Access Level</b></th>
					<th valign='top'><b>For Customer</b></th>
					<th valign='top'><b>For Store</b></th>
					<th valign='top'><b>For User</b></th>
					<th valign='top'><b>&nbsp;</b></td>
			</tr>
			</thead>
		*/
		
		$sql = "
			select *			
			from attached_files
			where deleted = 0
				and section_id = '".sql_friendly(SECTION_WAITING)."'				
				and access_level<='".sql_friendly($_SESSION['access_level'])."'
				".($_SESSION['access_level'] < 60 ? " and xref_id = '".sql_friendly($_SESSION['user_id'])."'" : "")."
				".$mrr_adder."
				and processed_flag=0
			order by id asc
		";
		$data = simple_query($sql);
		$cntr=0;	
		
		$closer="";
		//if(mysqli_num_rows($data) == 1)		$closer="  dialog.dialog(\"close\");";
		
		while($row = mysqli_fetch_array($data)) 
		{
			
			$rval .= "
				<FileEntry>
					<File><![CDATA[".$row['filename']."]]></File>
					<DateAdded><![CDATA[".date("m/d/Y h:i a", strtotime($row['linedate_added']))."]]></DateAdded>
				</FileEntry>
			";
			
			
			$date2="";		if($row['linedate_display_start'] !="0000-00-00 00:00:00")		$date2="".date("m/d/Y", strtotime($row['linedate_display_start']))."";
			//$date3="";		if($row['linedate_display_end'] !="0000-00-00 00:00:00")		$date3="".date("m/d/Y", strtotime($row['linedate_display_end']))."";
			
			//if($date2=="")		$date2=date("m/d/Y",time());
					
			$selbx1=get_access_select_box($_SESSION['access_level'],  "file_".$row['id']."_access_level",  $row['access_level'], 0, "", "");
			$selbx2=get_user_select_box("file_".$row['id']."_user_id",  $row['xref_id'], 0, "", "");
			$selbx3=get_merchant_select_box("file_".$row['id']."_merchant_id",  $row['merchant_id'], 0, "All", " file_id='$row[id]' class='file_merchant_selector'",$allow_all);
			$selbx4=get_store_select_box("file_".$row['id']."_store_id",  $row['store_id'], $row['merchant_id'], 0, "All", " file_id='$row[id]' class='file_store_selector'",$row['merchant_id']);
			
			
			$prime_item=$row['template_item_id'];			
			$sub_item=$row['template_item_id_sub'];
			$group_id=$prime_item;
			
			if($sub_item==0 && $prime_item > 0)
			{
				$group_id=get_template_item_sub_id_from_item($prime_item);
				if($group_id > 0)	
				{
					$prime_item=$group_id;
					$sub_item=$row['template_item_id'];
					
					//update the system to store the group id and the sub id separately....					
					$sqlu = "
						update attached_files set 
							template_item_id='".sql_friendly($group_id)."',
							template_item_id_sub='".sql_friendly($sub_item)."'
						where id='".sql_friendly($row['id'])."'
					";
					simple_query($sqlu);
				}
			}
						
			$selbx5=get_template_item_select_box("file_".$row['id']."_template_id", $prime_item,$row['store_id'], $row['merchant_id'], 0, "", " file_id='$row[id]' class='file_template_selector'",0,0);
			$selbx6=get_template_item_select_box("file_".$row['id']."_sub_id", $sub_item, $row['store_id'], $row['merchant_id'], 0, "", " file_id='$row[id]' class='file_template_sub_selector'",$group_id,1);	// onChange='update_mrr_selectors(4);'
					
			
			$test_file=strtolower($row['filename']);
			
			$preview="".$row['filename']."";
			
			if($row['public_flag'] > 0) 
			{
				$preview="<a href='/documents/".$row['filename']."' target='_blank' >".$row['filename']."</a>";
			}
			else
			{
				$preview="<a href='javascript:void(0);' onClick='view_attached_file(".$row['section_id'].",".$row['xref_id'].",".$row['id'].");'>".$row['filename']."</a>";
			}
					
			
			if(trim($row['public_name'])=="")		$row['public_name']="".trim(get_filename_without_unique($row['filename']))."";
					
			$tab.="
				<tr class='".($cntr%2==0 ? "even" : "odd")." waiting_list' id='attachment_row_".$row['id']."'>					
					<td valign='top' style='width:410px;'>
						<div class='mrr_v_spacer'>
							<div class='mrr_v_spacer_label1'>Filename:</div>
							".$preview."
						</div>
						<div class='mrr_v_spacer'>
							<div class='mrr_v_spacer_label1'>Upload Date:</div>
							".date("n/j/Y h:i a", strtotime($row['linedate_added']))."
						</div>

						<div class='mrr_v_spacer'>
							<div class='mrr_v_spacer_label1'>Document Date:</div>
							<input name='file_".$row['id']."_display_date' id='file_".$row['id']."_display_date' value='".$date2."' class='linedate short100' onChange='mrr_update_waiting_file(".$row['id'].",0);'>
						</div>
						<div class='mrr_v_spacer'>
							<div class='mrr_v_spacer_label1'>Document Name:</div>
							<input name='file_".$row['id']."_public_name' id='file_".$row['id']."_public_name' value=\"".$row['public_name']."\" class='tooltipx large' onChange='mrr_update_waiting_file(".$row['id'].",0);' title='This is a friendly name like \"My TXT Document\" or \"Compliance Letter\"'>
						</div>
					</td>
					<td valign='top' style='width:300px;' nowrap>
						
						<div class='mrr_v_spacer'><div class='mrr_v_spacer_label'>Customer:</div> ".$selbx3."</div>
						<div class='mrr_v_spacer'><div class='mrr_v_spacer_label'>Store:</div> ".$selbx4."</div>				
						<div class='mrr_v_spacer'><div class='mrr_v_spacer_label'>Document Type:</div> ".$selbx5."</div>
						<div class='mrr_v_spacer'><div class='mrr_v_spacer_label'>Doc. Sub-Type:</div> ".$selbx6."</div>
						<div class='mrr_v_spacer' style='text-align:right;'>
							<label for='file_".$row['id']."_processed' class='tooltip' title='To Remove File from waiting list, check the box.  This will not delete the file.'></label>
							<input type='button' class='buttonize btn btn-default add_new_btn' value='Delete' onClick='mrr_delete_waiting_file(".$row['id'].",1);".$closer."'>
							<input type='button' class='buttonize btn btn-default add_new_btn' value='Save' onClick='mrr_update_waiting_file(".$row['id'].",1);".$closer."'>
						</div>
					</td>
				</tr>

			";		
			$cntr++;
		}
		$tab.="</tbody>
		</table>
		<div id='dialog_delete_file' title='Remove this File?' style='display:none;'>
          	<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>This File will be permanently removed and cannot be recovered without reloading the file. Are you sure you want to delete it?</p>
          </div>
                   
		</div>";		
		
		if($cntr==0)		$tab="<b>No files are waiting to be processed at this time. Please check back later.</b>";
		
		$tab.="<input type='hidden' name='tot_files_waiting' id='tot_files_waiting' value='".$cntr."'><input type='hidden' name='tot_files_processed' id='tot_files_processed' value='0'>";
		
		display_xml_response("<rslt>1</rslt>$rval<mrrTab><![CDATA[".$tab."]]></mrrTab>");
	}	
	function mrr_update_file_details()
	{
		$id=$_POST['id'];
		$file_date=$_POST['display_date'];				
		$file_name=$_POST['public_name'];
		//$file_access=$_POST['access_level'];
		//$file_user=$_POST['user_id'];
		$file_merchant=$_POST['merchant_id'];
		$file_store=$_POST['store_id'];
		$file_template=$_POST['template_id'];
		$file_sub=$_POST['template_sub'];	
		
		$processed=0;
		if($_POST['processed_flag'] > 0)	
		{
			if($file_date=="")	{	$file_date="".date("m/d/Y")."";		$_POST['display_date']=$file_date;	}
			$processed=$_SESSION['user_id'];
		}
		
		if($file_date=="")	
		{
			$file_date="0000-00-00 00:00:00";	
			$processed=0;
		}
		else
		{
			$file_date="".date("Y-m-d", strtotime($_POST['display_date']))."";
		}
		
		if($file_sub==0 && $file_template > 0)
		{	//if no subtype was picked, pick it for them (first sub-type in doc-type group).  If none, ignore.
			$sql="
				select id 
				from template_items
				where deleted=0
					and archived=0
					and sub_group_id='".sql_friendly($file_template)."'
				order by zorder asc,item_label asc, id asc
			";	
			$data=simple_query($sql);
			if($row = mysqli_fetch_array($data)) 
			{
				$file_sub=$row['id'];	//use the first one only.
			}
		}
		
		
		$sql="";
		$res=0;
		
		if($id > 0 && $processed > 0)
		{
			$sql="
				update attached_files set
					processed_flag='".sql_friendly($processed)."',
					linedate_display_start='".sql_friendly($file_date)."'			
				where id='".sql_friendly($id)."'
			";	
			simple_query($sql);
			$res=1;	
		}
		elseif($id > 0)
		{
			$sql="
				update attached_files set
					merchant_id='".sql_friendly($file_merchant)."',
					store_id='".sql_friendly($file_store)."',
					template_item_id='".sql_friendly($file_template)."',
					template_item_id_sub='".sql_friendly($file_sub)."',
					linedate_display_start='".sql_friendly($file_date)."',
					public_name='".sql_friendly($file_name)."'
					
					
				where id='".sql_friendly($id)."'
			";	
				//xref_id='".sql_friendly($file_user)."',
				//access_level='".sql_friendly($file_access)."',
					//user_id='".sql_friendly($file_user)."',
					//linedate_display_end='".sql_friendly($file_date)."',
			simple_query($sql);
			$res=1;	
		}
		display_xml_response("<rslt>".$res."</rslt><removeList>".$processed."</removeList>");
	}
	
	
	//custom file uploader
	function get_file_uploader()
	{
		$field_name=$_POST['field_name'];
		$label=$_POST['label'];
		$section=$_POST['section_id'];
		$xref_id=$_POST['xref_id'];		
		$call_back=$_POST['call_back'];
		
		create_uploader_section($field_name,$label,$section,$xref_id,$call_back);	
	}
	
	
	//file types
	function load_file_types()
	{
		$tab=get_file_types_form();
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	function save_file_type()
	{	
		$id=$_POST['id'];	
		$name=$_POST['name'];
		$ext=$_POST['ext'];
		$min=$_POST['min'];
		$max=$_POST['max'];
		$arch=$_POST['archived'];
		
		$res=0;
		if($id == 0)
		{
			$sql="
				insert into template_file_types
					(id,
					file_type_name,
					file_type_ext,
					file_size_max,
					file_size_min,
					linedate_added,
					user_id,
					archived,
					deleted)
				values
					(NULL,
					'".sql_friendly($name)."',
					'".sql_friendly($ext)."',
					'".sql_friendly($max)."',
					'".sql_friendly($min)."',
					NOW(),
					'".sql_friendly($_SESSION['user_id'])."',
					'".sql_friendly($arch)."',
					0)
			";	
			simple_query($sql);
			$id=get_mysql_insert_id();
			if($id > 0)	$res=1;	
		}
		elseif($id > 0)
		{
			$sql="
				update template_file_types set
					archived='".sql_friendly($arch)."',					
					file_type_ext='".sql_friendly($ext)."',
					file_size_max='".sql_friendly($max)."',
					file_size_min='".sql_friendly($min)."',					
					file_type_name='".sql_friendly($name)."'					
					
				where id='".sql_friendly($id)."'
			";	
			simple_query($sql);
			$res=1;	
		}
		
		display_xml_response("<rslt>".$res."</rslt>");
	}
	function delete_file_type()
	{
		$id=$_POST['id'];
		
		$sql = "
			update template_file_types set
				deleted = 1
			where id='".sql_friendly($id)."'
		";
		simple_query($sql);	
		
		display_xml_response("<rslt>1</rslt>");		
	}
	
	//access levels...customizable...
	function save_level()
	{
		$id=$_POST['id'];	
		$level=$_POST['level'];
		$arch=$_POST['archived'];
		
		$res=0;
		if($id == 0)
		{
			//do not allow additions....for now.
		}
		elseif($id > 0)
		{
			$sql="
				update user_levels set
					archived='".sql_friendly($arch)."',
					level_name='".sql_friendly($level)."'					
					
				where id='".sql_friendly($id)."'
			";	
			simple_query($sql);
			$res=$id;	
		}
		
		display_xml_response("<rslt>".$res."</rslt>");
	}
	function save_level_option()
	{
		$level=$_POST['level_id'];	
		$user=$_POST['user_id'];	
		$template=$_POST['template_id'];	
		
		$action=$_POST['name'];	
		$state=$_POST['state'];
		$val=(int) $_POST['value'];
		
		if($state==false || $state=="false")		$val=0;		
		
		update_access_value($level,$user,$template,$action,$val);
		
		display_xml_response("<rslt>1</rslt>");	
	}
	function display_user_access_options()
	{
		$tab="Sorry, you do not have authorization to grant access.";
		$id=$_POST['user_id'];
		
		$standard_ops[]="Add Customers";
		$standard_ops[]="Edit Customers";
		$standard_ops[]="Delete Customers";
		$standard_ops[]="Add Stores";
		$standard_ops[]="Edit Stores";
		$standard_ops[]="Delete Stores";
		
		$standard_ops[]="Add Users";
		$standard_ops[]="Edit Users";
		$standard_ops[]="Delete Users";
		$standard_ops[]="Add Important Dates";
		$standard_ops[]="Edit Important Dates";
		$standard_ops[]="Delete Important Dates";
		
		$standard_ops[]="Add Documents";
		$standard_ops[]="Edit Documents";
		$standard_ops[]="Delete Documents";
		$standard_ops[]="Add Templates";
		$standard_ops[]="Edit Templates";
		$standard_ops[]="Delete Templates";
		
		
		if($_SESSION['access_level'] >=90 && $id > 0)
		{
			$my_base_id=0;
			$sql = "
     			select id 
     			from user_levels 
     			where access_level='".sql_friendly($_SESSION['access_level'])."'
     		";
     		$data=simple_query($sql);	
     		if($row = mysqli_fetch_array($data))
     		{
     			$my_base_id=$row['id'];
     		}	
					
			
			$show_name="";
			$base_access=0;
			$base_id=0;
			$merch_id=0;			//specific merchant for user.
			$merch_temp_id=0;		//template this merchant uses for all users...
			
			$sql = "
     			select first_name,
     				last_name,
     				access_level,
     				(select user_levels.id from user_levels where user_levels.access_level=users.access_level and user_levels.deleted=0 limit 1) as access_id,
     				merchant_id,
     				(select merchants.template_id from merchants where merchants.id=users.merchant_id) as merch_temp_id
     			from users
     			where deleted=0 and id='".sql_friendly($id)."'
     		";
     		$data=simple_query($sql);	
     		if($row = mysqli_fetch_array($data))
     		{
     			$show_name=$row['first_name']." ".$row['last_name'];
     			$base_access=$row['access_level'];
     			$base_id=$row['access_id'];
     			$merch_id=$row['merchant_id'];
     			$merch_temp_id=$row['merch_temp_id'];
     		}	
			
			$tab="Great, ".$show_name." (".$base_access.")[".$base_id."]--(".$merch_id.")[".$merch_temp_id."].";	
			
			$tab="<input type='button' class='buttonize btn btn-default add_new_btn' onclick='toggle_user_perms();' value='+ / -'><br>&nbsp;<br>";
			
			
			if($base_access > 0 && trim($show_name)!="")
			{
				
				$perm_list="<table cellpadding='0' cellspacing='0' border='0' style='width:95%' id='user_perms_table'>";
				
				//standard operations...     			
     			for($i=0; $i < count($standard_ops); $i++)
     			{     				     				
     				$action=trim(strtolower($standard_ops[ $i ]));
     				$action=str_replace(" ","_",$action);
     				
     				$get_valid1=get_access_value($base_id,0,0,$action);	//level,user,template-item,action
     				$get_valid2=get_access_value($base_id,$id,0,$action);	//level,user,template-item,action
     				
     				$use_val=$get_valid1;			//no setting for this user...has default by access level.	
     				if($get_valid2 >= 0)
     				{     					
     					$use_val=$get_valid2;		//use the specific value for this user instead of the access level default.
     				}
     				
     				
     				$inact=" class='access_editor_user_inactive'";
     				$bx="".($use_val > 0 ? "Yes" : "No")."";
     				     				
     				$uvalid1=get_access_value($my_base_id,0,0,$action);					//level,user,template-item,action
     				$uvalid2=get_access_value($my_base_id,$_SESSION['user_id'],0,$action);	//level,user,template-item,action
     				$grant_access=$uvalid1;
     				if($uvalid2 >=0)
     				{
     					$grant_access=$uvalid2;	
     				}
     				if($grant_access > 0)
     				{
     					$inact="";	//(".$action.") 
     					$bx="&nbsp; <input type='checkbox' name='".$action."' id='".$action."' value='1'".($use_val > 0 ? " checked" : "")." onClick='save_user_access_items(".$base_id.",".$id.",0,\"#".$action."\",\"".$action."\",1);'>";	
     				}   	
     							
     				     				     				
          			$perm_list.="
          			<tr class='access_editor_user'>
          				<td valign='top'".$inact."><label for='".$action."'>".$standard_ops[ $i ]."</label></td>
          				<td valign='top'".$inact." width='60' align='right'>".$bx."</td>
          			</tr>
          			";	
     			}
     			
				
				//template items...
				$perm_list.="
          			<tr>
          				<td valign='top' colspan='2' align='center'><b>Document Access</b></td>
          			</tr>
          			";	
				
				$icntr=0;
     			$last_template="";
     			$sqlt = "
          			select template_items.*,
          				(select level_name from user_levels where user_levels.access_level=template_items.min_access_level limit 1) as min_access,
          				templates.template_name
          			from template_items
          				left join templates on templates.id=template_items.template_id
          			where template_items.deleted=0
          				and template_items.sub_group_id = 0
          				 and templates.deleted=0
          				 ".($merch_temp_id > 0 ? " and template_items.template_id='".sql_friendly($merch_temp_id)."'" : " and template_items.template_id=1")."
          			order by template_items.template_id asc,
          				template_items.zorder asc,
          				template_items.item_label asc
          				
          		";
          		$datat=simple_query($sqlt);
          		while($rowt=mysqli_fetch_array($datat))	
     			{
     				$get_valid1=get_access_value($base_id,0,$rowt['id'],'view_template_item');		//level,user,template-item,action
     				$get_valid2=get_access_value($base_id,$id,$rowt['id'],'view_template_item');	//level,user,template-item,action
     				
     				$use_val=$get_valid1;				//use the access level as default...for the template item.
     				if($get_valid2 >= 0)
     				{
     					$use_val=$get_valid2;			//if present, use the user access for this template item fo the user...
     				}
     				
     				
     				$inact=" class='access_editor_user_inactive'";
     				$bx="".($use_val > 0 ? "Yes" : "No")."";
     				     				
     				$uvalid1=get_access_value($my_base_id,0,$rowt['id'],'view_template_item');					//level,user,template-item,action
     				$uvalid2=get_access_value($my_base_id,$_SESSION['user_id'],$rowt['id'],'view_template_item');	//level,user,template-item,action
     				$grant_access=$uvalid1;
     				if($uvalid2 >=0)
     				{
     					$grant_access=$uvalid2;	
     				}
     				if($grant_access > 0)
     				{
     					$inact="";
     					$bx="&nbsp; <input type='checkbox' name='view_template_item_".$icntr."' id='view_template_item_".$icntr."' value='1'".($use_val > 0 ? " checked" : "")." onClick='save_user_access_items(".$base_id.",".$id.",".$rowt['id'].",\"#view_template_item_".$icntr."\",\"view_template_item\",1);'>";
     				}   	
     				
     				$perm_list.="
          			<tr class='access_editor_user'>
          				<td valign='top'".$inact."><label for='view_template_item_".$icntr."'>".trim($rowt['item_label'])."</label></td>
          				<td valign='top'".$inact." width='60' align='right'>".$bx."</td>
          			</tr>
          			";	
     				
     				$icntr++;
     			}
     			
				$perm_list.="</table>";	
				
				$tab.=$perm_list;
			}
			
		}	
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	
	
	//templates
	function load_template_items()
	{
		$id=$_POST['id'];	
			
		$tab="
		<div id='template_item_preview'>
			<h3>Template Preview:</h3>
		";
		//$tab.=get_all_template_items_for_template($id,0,0);		//,$access=0,$editor=0
		$tab.=get_all_template_items_for_template_preview($id);
		
		$tab.="
		</div>
		<div id='template_item_settings'>
			<h3>Template Item Settings:</h3>";
		$tab.=get_template_items_form($id);
		$tab.="</div>";
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	function save_template()
	{
		$id=$_POST['id'];	
		$template=$_POST['template'];
		$arch=$_POST['archived'];
		
		$res=0;
		if($id == 0)
		{
			$sql="
				insert into templates
					(id,
					linedate_added,
					user_id,
					archived,
					template_name,
					deleted)
				values
					(NULL,
					NOW(),
					'".sql_friendly($_SESSION['user_id'])."',
					'".sql_friendly($arch)."',
					'".sql_friendly($template)."',
					0)
			";	
			simple_query($sql);
			$id=get_mysql_insert_id();
			$res=$id;	
		}
		elseif($id > 0)
		{
			$sql="
				update templates set
					archived='".sql_friendly($arch)."',
					template_name='".sql_friendly($template)."'
					
					
				where id='".sql_friendly($id)."'
			";	
			simple_query($sql);
			$res=$id;	
		}
		
		display_xml_response("<rslt>".$res."</rslt>");
	}
	
	
	function save_template_item()
	{
		$temp_id=$_POST['template_id'];	
		$id=$_POST['id'];	
		$name=trim($_POST['name']);
		$type=$_POST['type'];
		$group=$_POST['group'];
		$min=$_POST['min'];
		$max=$_POST['max'];
		$zorder=$_POST['zorder'];
		$arch=$_POST['archived'];
		$title_text=trim($_POST['title_text']);
				
		$res=0;
		
		//first check if match for existing template item...
		if($group==0 && $id==0)
		{
     		$sql = "
     			select *
     			from template_items
     			where deleted=0
     				and (
     					item_label='".sql_friendly($name)."'
     					or
     					item_label='".sql_friendly(strtolower($name))."'
     					or
     					item_label='".sql_friendly(strtoupper($name))."'
     					)
     				and id!='".sql_friendly($id)."'
     			order by template_id asc,archived asc
     			limit 1
     		";
     		$data=simple_query($sql);	
     		if($row = mysqli_fetch_array($data))
     		{
     			$res=copy_all_template_item_subs( $row['id'] , $temp_id );
     		}
		}
		
		if($res==0)
		{
     		if($id == 0)
     		{
     			$sql="
     				insert into template_items
     					(id,
     					template_id,
     					item_label,
     					file_type_id,
     					file_size_max,
     					file_size_min,
     					linedate_added,
     					user_id,
     					archived,
     					sub_group_id,
     					zorder,
     					title_text,
     					deleted)
     				values
     					(NULL,
     					'".sql_friendly($temp_id)."',
     					'".sql_friendly($name)."',
     					'".sql_friendly($type)."',
     					'".sql_friendly($max)."',
     					'".sql_friendly($min)."',
     					NOW(),
     					'".sql_friendly($_SESSION['user_id'])."',
     					'".sql_friendly($arch)."',
     					'".sql_friendly($group)."',
     					'".sql_friendly($zorder)."',
     					'".sql_friendly($title_text)."',
     					0)
     			";	
     			simple_query($sql);
     			$id=get_mysql_insert_id();
     			$res=$id;	
     		}
     		elseif($id > 0)
     		{
     			$sql="
     				update template_items set
     					archived='".sql_friendly($arch)."',					
     					file_type_id='".sql_friendly($type)."',
     					file_size_max='".sql_friendly($max)."',
     					file_size_min='".sql_friendly($min)."',
     					sub_group_id='".sql_friendly($group)."',
     					zorder='".sql_friendly($zorder)."',	
     					title_text='".sql_friendly($title_text)."',		
     					item_label='".sql_friendly($name)."'					
     					
     				where id='".sql_friendly($id)."'
     			";	
     			simple_query($sql);
     			$res=$id;	
     		}
		}
		
		display_xml_response("<rslt>".$res."</rslt>");
	}
	function delete_template()
	{
		$id=$_POST['id'];
		
		$sql = "
			update templates set
				deleted = 1
			where id='".sql_friendly($id)."'
		";
		simple_query($sql);	
		
		$sql = "
			update template_items set
				deleted = 1
			where template_id='".sql_friendly($id)."'
		";
		simple_query($sql);	
		
		display_xml_response("<rslt>1</rslt>");		
	}
	function delete_template_item()
	{
		$id=$_POST['id'];
		
		$sql = "
			update template_items set
				deleted = 1
			where id='".sql_friendly($id)."'
		";
		simple_query($sql);	
		
		//delete and sub group items from this as well...
		$sql = "
			update template_items set
				deleted = 1
			where sub_group_id='".sql_friendly($id)."'
		";
		simple_query($sql);	
		
		display_xml_response("<rslt>1</rslt>");	
	}
	
	function copy_template_items()
	{
		$id=$_POST['id'];
		$master_template_id=1;
		
		copy_all_template_items($master_template_id, $id);
		
		display_xml_response("<rslt>1</rslt>");		
	}
	
	
	
	//cce messages
	function load_cce_tagline()
	{
		$tab=get_tagline_trail(0);	
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");
	}
	function load_cce_messages()
	{
		$tab=cce_system_messages($_SESSION['merchant_id'],$_SESSION['store_id']);	
		//$tab2=mrr_display_quick_links_user();
		//$tab3=mrr_display_quick_links_edit();
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");		//<mrrQuickLinks><![CDATA[".$tab2."]]></mrrQuickLinks><mrrEditLinks><![CDATA[".$tab3."]]></mrrEditLinks>
	}
	function mrr_update_cce_message()
	{
		$id=$_POST['id'];
		$sub=str_replace("'","&apos;",$_POST['sub']);				
		$body=str_replace("'","&apos;",$_POST['body']);
		
		$res=0;
		if($id > 0)
		{
			$sql="
				update cce_messages set
					subject='".sql_friendly(trim($sub))."',
					message='".sql_friendly(trim($body))."'
					
				where id='".sql_friendly($id)."'
			";	
			simple_query($sql);
			$res=1;	
		}
		
		display_xml_response("<rslt>".$res."</rslt>");
	}
	
	//quick links...
	function mrr_reload_quick_links()
	{
		$tab2=mrr_display_quick_links_user();
		$tab3=mrr_display_quick_links_edit();
		
		display_xml_response("<rslt>1</rslt><mrrQuickLinks><![CDATA[".$tab2."]]></mrrQuickLinks><mrrEditLinks><![CDATA[".$tab3."]]></mrrEditLinks>");
	}
	function mrr_update_quick_links_new()
	{
		$id=$_POST['id'];
		$name=trim($_POST['name']);
		$url=trim($_POST['url']);
		$private=$_POST['private_link'];
		$merchant=$_POST['merchant_id'];
		$store=$_POST['store_id'];
		$m_list=trim($_POST['m_list']);
		$s_list=trim($_POST['s_list']);
		$rownum=$_POST['row_num'];
		$colnum=$_POST['col_num'];
		$poser=$_POST['position_id'];
		
		if(trim($name)!="") 
		{		
			$res=mrr_update_quick_links_main($id,$name,$url,$private,$merchant,$store,$m_list,$s_list,$rownum,$colnum,$poser);	
			display_xml_response("<rslt>".$res."</rslt>");
		}
		else
		{
			display_xml_response("<rslt>1</rslt>");
		}
	}
	function mrr_update_quick_links()
	{
		$id=$_POST['id'];
		$field=trim($_POST['field']);			//database column name
		$value=trim($_POST['value']);			//any value it has...including integer values.
		
		$field=str_replace("'","",$field);
		if(substr_count($value,"'") % 2 !=0)	$value="'".str_replace("'","",$value)."'";		//repackage value in string quotes...
		
		$res=0;
		if($id > 0 && $field!="")
		{
			$sql="
				update quick_links set
					".$field."=".$value."
					
				where id='".sql_friendly($id)."'
			";	
			simple_query($sql);
			$res=1;	
		}
		
		display_xml_response("<rslt>".$res."</rslt>");
	}
	
	//Financial Institutions
	function get_financial_inst_details()
	{
		$rval="";
		$id=$_SESSION['selected_merchant_id'];
		if($id==0)	$id=$_SESSION['merchant_id'];
		
		$sid=$_SESSION['selected_store_id'];
		if($sid==0)	$sid=$_SESSION['store_id'];
				
		$sql = "
			select merchants.*,
				contact_title as fi_name,
				contact_first_name as fi_address1,
				'' as fi_address2,
				'' as fi_city,
				'' as fi_state,
				'' as fi_zip,
				contact_phone2 as fi_phone,
				contact_phone1 as fi_cell,
				contact_email as fi_email,
				contact_last_name as fi_relation,
				
				msb_auditor as fi_auditor,
          		msb_phone as fi_aud_phone,
          		msb_cell as fi_aud_cell,
          		msb_email as fi_aud_email,
          		msb_ref_number as fi_aud_refer,
          		msb_address as fi_aud_addr,
          		
          		irs_agent as irs_name,
          		irs_employee_id as irs_id,
          		irs_cell as irs_cell,
          		irs_phone as irs_phone,
          		irs_email as irs_email,
          		irs_address as irs_addr,
          		irs_case_number as irs_case,
				
				(select attached_files.filename from attached_files where attached_files.deleted=0 and attached_files.xref_id=merchants.id and attached_files.section_id=9 order by id desc limit 1) as company_logo
			from merchants
			where merchants.id='".sql_friendly($id)."'
		";
		$data=simple_query($sql);	
		if($row = mysqli_fetch_array($data))
		{						
			if($sid > 0)
			{
				//override with store settings if selected
				$sql2 = "
          			select contact_title as fi_name,
          				contact_first_name as fi_address1,
          				'' as fi_address2,
          				'' as fi_city,
          				'' as fi_state,
          				'' as fi_zip,
          				contact_phone2 as fi_phone,
          				contact_phone1 as fi_cell,
          				contact_email as fi_email,
          				contact_last_name as fi_relation,
          				
          				msb_auditor as fi_auditor,
          				msb_phone as fi_aud_phone,
          				msb_cell as fi_aud_cell,
          				msb_email as fi_aud_email,
          				msb_ref_number as fi_aud_refer,
          				msb_address as fi_aud_addr,
                    		
                    		irs_agent as irs_name,
                    		irs_employee_id as irs_id,
                    		irs_cell as irs_cell,
                    		irs_phone as irs_phone,
                    		irs_email as irs_email,
                    		irs_address as irs_addr,
                    		irs_case_number as irs_case
          				
          			from store_locations
          			where store_locations.id='".sql_friendly($sid)."'
          		";
          		$data2=simple_query($sql2);	
          		if($row2 = mysqli_fetch_array($data2))	
          		{
          			if($row['fi_name']!=$row2['fi_name'] || $row['fi_relation']!=$row2['fi_relation'])
          			{
          				$row['fi_name']=$row2['fi_name'];	
          				$row['fi_address1']=$row2['fi_address1'];
          				$row['fi_relation']=$row2['fi_relation'];
          				$row['fi_phone']=$row2['fi_phone'];
          				$row['fi_cell']=$row2['fi_cell'];
          				$row['fi_email']=$row2['fi_email'];
          			}
          			if($row['fi_auditor']!=$row2['fi_auditor'] || $row['fi_aud_refer']!=$row2['fi_aud_refer'])
          			{
          				$row['fi_auditor']=$row2['fi_auditor'];
          				$row['fi_aud_phone']=$row2['fi_aud_phone'];
          				$row['fi_aud_cell']=$row2['fi_aud_cell'];
          				$row['fi_aud_email']=$row2['fi_aud_email'];          			
          				$row['fi_aud_refer']=$row2['fi_aud_refer'];
          				$row['fi_aud_addr']=$row2['fi_aud_addr'];
          			}
          			if($row['irs_name']!=$row2['irs_name'] || $row['irs_id']!=$row2['irs_id'])
          			{
          				$row['irs_name']=$row2['irs_name'];
          				$row['irs_phone']=$row2['irs_phone'];
          				$row['irs_cell']=$row2['irs_cell'];
          				$row['irs_email']=$row2['irs_email'];          			
          				$row['irs_id']=$row2['irs_id'];
          				$row['irs_addr']=$row2['irs_addr'];
          				$row['irs_case']=$row2['irs_case'];
          			}
          		}
			}			
			
			$rval.="<FIName><![CDATA[".$row['fi_name']."]]></FIName>";
               $rval.="<FIAddr><![CDATA[".$row['fi_address1']."]]></FIAddr>";	// ".$row['fi_address2']." ".$row['fi_city'].", ".$row['fi_state']." ".$row['fi_zip']."
               $rval.="<FIRelation><![CDATA[".$row['fi_relation']."]]></FIRelation>";
               $rval.="<FIPhone><![CDATA[".$row['fi_phone']."]]></FIPhone>";
               $rval.="<FICell><![CDATA[".$row['fi_cell']."]]></FICell>";
               $rval.="<FIEmail><![CDATA[".$row['fi_email']."]]></FIEmail>";
                    	
               $rval.="<FIAuditor><![CDATA[".$row['fi_auditor']."]]></FIAuditor>";
               $rval.="<FIAudPhone><![CDATA[".$row['fi_aud_phone']."]]></FIAudPhone>";
               $rval.="<FIAudCell><![CDATA[".$row['fi_aud_cell']."]]></FIAudCell>";
               $rval.="<FIAudEmail><![CDATA[".$row['fi_aud_email']."]]></FIAudEmail>";
               $rval.="<FIAudAddr><![CDATA[".$row['fi_aud_addr']."]]></FIAudAddr>";    	
               $rval.="<FIAudRefer><![CDATA[".$row['fi_aud_refer']."]]></FIAudRefer>";	
                    	
               $rval.="<IRSname><![CDATA[".$row['irs_name']."]]></IRSname>";
               $rval.="<IRSPhone><![CDATA[".$row['irs_phone']."]]></IRSPhone>";
               $rval.="<IRSCell><![CDATA[".$row['irs_cell']."]]></IRSCell>";
               $rval.="<IRSEmail><![CDATA[".$row['irs_email']."]]></IRSEmail>";
               $rval.="<IRSAddr><![CDATA[".$row['irs_addr']."]]></IRSAddr>";    	
               $rval.="<IRSempid><![CDATA[".$row['irs_id']."]]></IRSempid>";    
               $rval.="<IRScase><![CDATA[".$row['irs_case']."]]></IRScase>";           		
		}
		else
		{
			$rval.="<FIName><![CDATA[N/A]]></FIName>";
               $rval.="<FIAddr><![CDATA[]]></FIAddr>";
               $rval.="<FIRelation><![CDATA[]]></FIRelation>";
               $rval.="<FIPhone><![CDATA[]]></FIPhone>";
               $rval.="<FICell><![CDATA[]]></FICell>";
               $rval.="<FIEmail><![CDATA[]]></FIEmail>";
                    	
               $rval.="<FIAuditor><![CDATA[N/A]]></FIAuditor>";
               $rval.="<FIAudPhone><![CDATA[]]></FIAudPhone>";
               $rval.="<FIAudCell><![CDATA[]]></FIAudCell>";
               $rval.="<FIAudEmail><![CDATA[]]></FIAudEmail>";
               $rval.="<FIAudAddr><![CDATA[]]></FIAudAddr>";   
               $rval.="<FIAudRefer><![CDATA[]]></FIAudRefer>";	
               
               $rval.="<IRSname><![CDATA[]]></IRSname>";
               $rval.="<IRSPhone><![CDATA[]]></IRSPhone>";
               $rval.="<IRSCell><![CDATA[]]></IRSCell>";
               $rval.="<IRSEmail><![CDATA[]]></IRSEmail>";
               $rval.="<IRSAddr><![CDATA[]]></IRSAddr>";    	
               $rval.="<IRSempid><![CDATA[]]></IRSempid>"; 
               $rval.="<IRScase><![CDATA[]]></IRScase>";  
		}
		
		display_xml_response("<rslt>1</rslt>".$rval."");	
	}
	
	
	//merchants
	function display_merchant_program()
	{
		$merchant_id=0;
		if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
		{
			$merchant_id=$_SESSION['selected_merchant_id'];
		}
		elseif($_SESSION['merchant_id'] > 0)	
		{
			$merchant_id=$_SESSION['merchant_id'];
		}
		$tab=mrr_get_merchant_program_title($merchant_id);
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");		
	}
	function load_merchants()
	{
		//$tab=mrr_show_merchants();
		$tab=mrr_search_merchant_locs("",0);
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	function delete_merchant()
	{
		$id=$_POST['id'];
		
		$sql = "update merchants set deleted='1' where id='".sql_friendly($id)."'";
		simple_query($sql);	
		
		//remove stores from this merchant or free store from merchant
		$sql = "update store_locations set	deleted_merchant_id='".sql_friendly($id)."',merchant_id='0',deleted='1' where merchant_id='".sql_friendly($id)."'";
		simple_query($sql);	
		
		//remove users from this merchant or free user from merchant
		$sql = "
			update users set	
				deleted_customer_id='".sql_friendly($id)."',
				deleted_store_id=store_id,
				merchant_id='0'
			where merchant_id='".sql_friendly($id)."'";
		simple_query($sql);
		$sql = "
			update users set	
				store_id='0'
			where merchant_id='".sql_friendly($id)."'";
		simple_query($sql);
				
		mrr_find_user_roles($id,0,0);					
		//$sql = "update users set	deleted='1' where merchant_id='".sql_friendly($id)."'";
		//simple_query($sql);	
		
		$_SESSION['selected_merchant_id']=0;
		
		display_xml_response("<rslt>1</rslt>");	
	}
	function archive_merchant()
	{
		$id=$_POST['id'];
		$val=1;
		if(isset($_POST['value']))		$val=$_POST['value'];
		
		$sql = "
			update merchants set
				archived = '".sql_friendly($val)."',
				deleted = '".sql_friendly($val)."'
			where id='".sql_friendly($id)."'
		";
		simple_query($sql);	
		
		if($val==0)
		{
			//unflag and reset users
			$sql = "
			update users set	
				merchant_id=deleted_customer_id,
				store_id=deleted_store_id,
				deleted='0',
				archived='0'
			where deleted_customer_id='".sql_friendly($id)."'";
			simple_query($sql);	
			
			//mark user deleted store back to zero.
			$sql = "
			update users set	
				deleted_customer_id=0,
				deleted_store_id=0
			where deleted_customer_id='".sql_friendly($id)."'";
			simple_query($sql);	
		}
		
		
		$_SESSION['selected_merchant_id']=0;
		
		display_xml_response("<rslt>1</rslt>");	
	}
	function get_merchant_details()
	{
		$rval="";
		$id=$_POST['id'];
		
		$sql = "
			select merchants.*,
				templates.template_name,
				(select attached_files.filename from attached_files where attached_files.deleted=0 and attached_files.xref_id=merchants.co_user_id and attached_files.section_id=8 order by id desc limit 1) as co_image,
				(select users.contact_phone1 from users where users.id=merchants.co_user_id) as co_user_cell,
				(select users.contact_phone2 from users where users.id=merchants.co_user_id) as co_user_phone,
				(select attached_files.filename from attached_files where attached_files.deleted=0 and attached_files.xref_id=merchants.group_user_id and attached_files.section_id=8 order by id desc limit 1) as grp_image,
				(select attached_files.filename from attached_files where attached_files.deleted=0 and attached_files.xref_id=merchants.id and attached_files.section_id=9 order by id desc limit 1) as company_logo
			from merchants
				left join templates on templates.id= merchants.template_id
			where merchants.id='".sql_friendly($id)."'
		";
		$data=simple_query($sql);	
		if($row = mysqli_fetch_array($data))
		{						
			$rval.="<arch><![CDATA[".$row['archived']."]]></arch>";
			$rval.="<id><![CDATA[".$row['id']."]]></id>";
			$rval.="<user><![CDATA[".$row['user_id']."]]></user>";
			$rval.="<added><![CDATA[".date("m/d/Y H:i",strtotime($row['linedate_added']))."]]></added>";
			$rval.="<deleted><![CDATA[".$row['deleted']."]]></deleted>";
			
			$rval.="<ProgramTitle><![CDATA[".$row['program_title']."]]></ProgramTitle>";
			$rval.="<ProgramSubtitle><![CDATA[".$row['program_subtitle']."]]></ProgramSubtitle>";
									
			$rval.="<Merchant><![CDATA[".$row['merchant']."]]></Merchant>";
			$rval.="<Addr1><![CDATA[".$row['address1']."]]></Addr1>";
			$rval.="<Addr2><![CDATA[".$row['address2']."]]></Addr2>";
			$rval.="<City><![CDATA[".$row['city']."]]></City>";
			$rval.="<State><![CDATA[".$row['state']."]]></State>";
			$rval.="<Zip><![CDATA[".$row['zip']."]]></Zip>";
			$rval.="<Title><![CDATA[".$row['contact_title']."]]></Title>";
			$rval.="<First><![CDATA[".$row['contact_first_name']."]]></First>";
			$rval.="<Last><![CDATA[".$row['contact_last_name']."]]></Last>";
			$rval.="<Phone1><![CDATA[".$row['contact_phone1']."]]></Phone1>";
			$rval.="<Phone2><![CDATA[".$row['contact_phone2']."]]></Phone2>";
			$rval.="<Phone3><![CDATA[".$row['contact_phone3']."]]></Phone3>";
			$rval.="<Phone4><![CDATA[".$row['contact_phone4']."]]></Phone4>";
			$rval.="<Email><![CDATA[".$row['contact_email']."]]></Email>";
			$rval.="<Logo><![CDATA[".create_thumbnail("documents/".$row['company_logo'], 200)."]]></Logo>";
			$rval.="<Template><![CDATA[".$row['template_id']."]]></Template>";
			$rval.="<ParentID><![CDATA[".$row['parent_company_id']."]]></ParentID>";
			
			//fields below are for display only...
			$rval.="<TemplateName><![CDATA[".$row['template_name']."]]></TemplateName>";
			
			$rval.="<COuser><![CDATA[".$row['co_user_id']."]]></COuser>";
			$rval.="<COuserCell><![CDATA[".$row['co_user_cell']."]]></COuserCell>";
			$rval.="<COuserPhone><![CDATA[".$row['co_user_phone']."]]></COuserPhone>";
			$rval.="<COuserImage><![CDATA[". create_thumbnail("documents/".$row['co_image'], 100)."]]></COuserImage>";
			$rval.="<COuserImage2><![CDATA[documents/".$row['co_image']."]]></COuserImage2>";
			$rval.="<COuserEmail><![CDATA[".mrr_get_user_email_address($row['co_user_id'],1)."]]></COuserEmail>";
			$rval.="<COuserName><![CDATA[".mrr_get_user_email_names($row['co_user_id'],1)."]]></COuserName>";
						
			$rval.="<Groupuser><![CDATA[".$row['group_user_id']."]]></Groupuser>";			
			$rval.="<GroupuserImage><![CDATA[".create_thumbnail("documents/".$row['grp_image'], 100)."]]></GroupuserImage>";
			$rval.="<GroupuserEmail><![CDATA[".mrr_get_user_email_address($row['group_user_id'],1)."]]></GroupuserEmail>";	
			$rval.="<GroupuserName><![CDATA[".mrr_get_user_email_names($row['group_user_id'],1)."]]></GroupuserName>";				
			
			$rval.="<MSBname><![CDATA[".$row['msb_auditor']."]]></MSBname>";
			$rval.="<MSBref><![CDATA[".$row['msb_ref_number']."]]></MSBref>";
			$rval.="<MSBcell><![CDATA[".$row['msb_cell']."]]></MSBcell>";
			$rval.="<MSBphone><![CDATA[".$row['msb_phone']."]]></MSBphone>";
			$rval.="<MSBemail><![CDATA[".$row['msb_email']."]]></MSBemail>";
			$rval.="<MSBaddress><![CDATA[".$row['msb_address']."]]></MSBaddress>";
			
			$rval.="<IRSname><![CDATA[".$row['irs_agent']."]]></IRSname>";
			$rval.="<IRSref><![CDATA[".$row['irs_employee_id']."]]></IRSref>";
			$rval.="<IRScell><![CDATA[".$row['irs_cell']."]]></IRScell>";
			$rval.="<IRSphone><![CDATA[".$row['irs_phone']."]]></IRSphone>";
			$rval.="<IRSemail><![CDATA[".$row['irs_email']."]]></IRSemail>";
			$rval.="<IRSaddress><![CDATA[".$row['irs_address']."]]></IRSaddress>";
			$rval.="<IRScase><![CDATA[".$row['irs_case_number']."]]></IRScase>";
						
			//$rval.="<SQL><![CDATA[".$sql."]]></SQL>";
			
			$edit_button="";
			$valid_user=check_user_edit_access('merchants',$row['id'],$_SESSION['user_id']);
			if($valid_user > 1)	
			{
				$allow_editor1="<i class='fa fa-pencil' style='color:#e19918;' title='Click to edit this merchant' onClick='edit_merchant(".$row['id'].",1);'></i>";
				$allow_editor2="<i class='fa fa-trash' style='color:#e19918;' title='Click to remove this merchant' onClick='edit_merchant(".$row['id'].",2);'></i>";
				$allow_editor3="<i class='fa fa-chevron-circle-down' style='color:#e19918;' title='Click to check it off (archive).' onClick='edit_merchant(".$row['id'].",3);'></i>";	
				
				$edit_button="".$allow_editor1."&nbsp;&nbsp;".$allow_editor2."&nbsp;&nbsp;";	//".$allow_editor3."
			}
			$rval.="<EditButton><![CDATA[".$edit_button."]]></EditButton>";
			
          	$_SESSION['selected_merchant_id']=$id;
		}
		
		display_xml_response("<rslt>1</rslt>".$rval."");	
	}
	function update_merchant()
	{
		$id=$_POST['id'];
		$name=$_POST['merchant'];
		$addr1=$_POST['address1'];
		$addr2=$_POST['address2'];
		$city=$_POST['city'];
		$state=$_POST['state'];
		$zip=$_POST['zip'];
		$program=$_POST['program'];
		$subtitle=$_POST['subtitle'];
		$title=$_POST['title'];
		$first=$_POST['first'];
		$last=$_POST['last'];
		$phone1=$_POST['phone1'];
		$phone2=$_POST['phone2'];
		$phone3=$_POST['phone3'];
		$phone4=$_POST['phone4'];
		$email=$_POST['email'];
		$template_id=$_POST['template_id'];
		$parent_id=$_POST['parent_id'];
		$logo="";
		
		$msb_aud=$_POST['msb_auditor'];
		$msb_ref=$_POST['msb_ref_number'];
		$msb_cell=$_POST['msb_cell'];
		$msb_phone=$_POST['msb_phone'];
		$msb_email=$_POST['msb_email'];		
		$msb_addr=trim($_POST['msb_addr']);		
		
		$irs_addr=trim($_POST['irs_addr']);
		$irs_agent=trim($_POST['irs_agent']);
		$irs_empid=trim($_POST['irs_empid']);
		$irs_email=trim($_POST['irs_email']);
		$irs_phone=trim($_POST['irs_phone']);
		$irs_cell=trim($_POST['irs_cell']);
		$irs_case=trim($_POST['irs_case']);
			
		$co_id=$_POST['co_user_id'];
		$grp_id=$_POST['grp_user_id'];
		
		if(isset($_POST['logo']))	$logo=$_POST['logo'];
		
		$new_merchant=0;
		
		if($id==0)
		{
			$sql="
				insert into merchants
					(id,
					user_id,
					linedate_added,
					merchant,					
					deleted,
					archived)
				values
					(NULL,
					'".sql_friendly($_SESSION['user_id'])."',
					NOW(),
					'',
					0,
					0)
			";
			simple_query($sql);	
			$id=get_mysql_insert_id();
			
			$sql="
				insert into cce_messages
					(id,
					section,
					linedate_added,
					deleted,
					subject,
					message,
					merchant_id,
					store_id)
				values
					(NULL,
					'merchant',
					NOW(),
					0,
					'',
					'',
					'".sql_friendly($id)."',
					0)
			";
			simple_query($sql);	
			
			$new_merchant=1;
		}
				
		$sql = "
			update merchants set
				program_title='".sql_friendly($program)."',
				program_subtitle='".sql_friendly($subtitle)."',
				address1='".sql_friendly($addr1)."',
				address2='".sql_friendly($addr2)."',
				city='".sql_friendly($city)."',
				state='".sql_friendly($state)."',
				zip='".sql_friendly($zip)."',
				
				contact_title='".sql_friendly($title)."',
				contact_first_name='".sql_friendly($first)."',
				contact_last_name='".sql_friendly($last)."',
				contact_phone1='".sql_friendly($phone1)."',
				contact_phone2='".sql_friendly($phone2)."',
				contact_email='".sql_friendly($email)."',
				
				msb_auditor='".sql_friendly($msb_aud)."',
    				msb_ref_number='".sql_friendly($msb_ref)."',
    				msb_cell='".sql_friendly($msb_cell)."',
    				msb_phone='".sql_friendly($msb_phone)."',
    				msb_email='".sql_friendly($msb_email)."',
    				msb_address='".sql_friendly($msb_addr)."',
    				
    				irs_address='".sql_friendly($irs_addr)."',
    				irs_cell='".sql_friendly($irs_cell)."',
    				irs_phone='".sql_friendly($irs_phone)."',
    				irs_email='".sql_friendly($irs_email)."',
    				irs_agent='".sql_friendly($irs_agent)."',
    				irs_employee_id='".sql_friendly($irs_empid)."',
    				irs_case_number='".sql_friendly($irs_case)."',
				
				contact_phone3='".sql_friendly($phone3)."',
				contact_phone4='".sql_friendly($phone4)."',
				
				co_user_id='".sql_friendly($co_id)."',
				group_user_id='".sql_friendly($grp_id)."',				
				
				template_id='".sql_friendly($template_id)."',
				parent_company_id='".sql_friendly($parent_id)."',
				logo='".sql_friendly($logo)."',
				merchant='".sql_friendly($name)."'
					
			where id='".sql_friendly($id)."'
		";	
		simple_query($sql);			
		
		$_SESSION['selected_merchant_id']=$id;
		
		//Assigning user to merchant no longer to limit the user to this merchant
		/*		
		if($co_id > 0 && $grp_id > 0  && $co_id==$grp_id)		$grp_id=0;		//can't be the same...
		
		if($co_id > 0)
		{	//if selected update the user as this merchant only.
			$sql = "
     			update users set     			
     				merchant_id='".sql_friendly($id)."'     					
     			where id='".sql_friendly($co_id)."'
     		";	
     		simple_query($sql);		
		}
		
		if($grp_id > 0)
		{	//if selected update the user as this merchant group only.
			$sql = "
     			update users set     			
     				merchant_id='".sql_friendly($id)."'     					
     			where id='".sql_friendly($grp_id)."'
     		";	
     		simple_query($sql);		
		}
		*/
		
		//if(trim($first)=="" && trim($last)=="" && $new_merchant==1)		copy_contact_info_to_merchant($_SESSION['user_id'],$id);
		
		display_xml_response("<rslt>".$id."</rslt>");		
	}
	
	function load_dynamic_sidebar()
	{
		$tab=generate_sidebar_documents();
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	
	function load_dynamic_user_select()
	{
		$field_name=trim($_POST['field_name']);
		$pre=$_POST['id'];
		$cd=$_POST['cd'];
		$prompt=trim($_POST['prompt']);
		$classy=$_POST['class_text'];
				
		//get preselected user id...for merchant and store selected
		if($field_name=="ms_co_user_id" || $field_name=="ms_grp_user_id")
		{
			$sql = "
     			select co_user_id,group_user_id
     			from merchants
     			where id='".sql_friendly($_SESSION['selected_merchant_id'])."'
     		";
     		$data=simple_query($sql);	
     		if($row = mysqli_fetch_array($data))
     		{
     			if($field_name=="ms_co_user_id")		$pre=$row['co_user_id'];	
     			if($field_name=="ms_grp_user_id")		$pre=$row['group_user_id'];	
     		}
		}
		elseif($field_name=="mst_cm_user_id")
		{
			$sql = "
     			select cm_user_id
     			from store_locations
     			where id='".sql_friendly($_SESSION['selected_store_id'])."'
     		";
     		$data=simple_query($sql);	
     		if($row = mysqli_fetch_array($data))
     		{
     			$pre=$row['cm_user_id'];	
     		}
		}
		
		if($_POST['id'] < 0)		$pre=0;
		
		if($_SESSION['selected_user_id'] > 0)		$pre=$_SESSION['selected_user_id'];	//always pick the selected one if dynamic user box...
				
		$tab=get_user_select_box($field_name,$pre,$cd,$prompt,$classy);
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");		
	}
	
	function load_dynamic_user_customer_select()
	{
		$field_name=trim($_POST['field_name']);
		$pre=$_POST['id'];
		$cd=$_POST['cd'];
		$prompt=trim($_POST['prompt']);
		$classy=$_POST['class_text'];
		
		if($_SESSION['selected_merchant_id'] > 0)		$pre=$_SESSION['selected_merchant_id'];
				
		if($_POST['id'] < 0)		$pre=0;
				
		$tab=get_merchant_select_box($field_name,$pre,$cd,$prompt,$classy);
		
		display_xml_response("<rslt>1</rslt><pre>$pre</pre><mrrTab><![CDATA[".$tab."]]></mrrTab>");		
	}	
	function load_dynamic_user_store_select()
	{
		$field_name=trim($_POST['field_name']);
		$pre=$_POST['id'];
		$cd=$_POST['cd'];
		$prompt=trim($_POST['prompt']);
		$classy=$_POST['class_text'];
		
		$bypass_session=0;
		$merchant=0;
		if($_SESSION['selected_merchant_id'] > 0)		$merchant=$_SESSION['selected_merchant_id'];
		if($_SESSION['selected_store_id'] > 0)			$pre=$_SESSION['selected_store_id'];
		
		if($_POST['id'] < 0)		$pre=0;	
				
		$tab=get_store_select_box($field_name,$pre,$merchant,$cd,$prompt,$classy,$bypass_session);
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");		
	}	
	
	
	//stores
	function load_co_slot_info()
	{
		$tab=cm_document_slot_filler();
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");		
	}
	function load_stores()
	{
		$tab=mrr_show_store_locations();
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	function delete_store_location()
	{
		$id=$_POST['id'];
		
		$sql = "update store_locations set deleted='1' where id='".sql_friendly($id)."'";
		simple_query($sql);	
		
		//remove users from this store or free user from store
		$sql = "
			update users set	
				deleted_store_id='".sql_friendly($id)."',
				store_id='0'
			where store_id='".sql_friendly($id)."'";
		simple_query($sql);
		
		mrr_find_user_roles(0,$id,0);			
		//$sql = "update users set	deleted='1' where store_id='".sql_friendly($id)."'";
		//simple_query($sql);		
		
		$_SESSION['selected_store_id']=0;
		
		display_xml_response("<rslt>1</rslt>");	
	}
	function archive_store_location()
	{
		$id=$_POST['id'];
		$val=1;
		if(isset($_POST['value']))		$val=$_POST['value'];
				
		$sql = "
			update store_locations set
				archived = '".sql_friendly($val)."',
				deleted = '".sql_friendly($val)."'
			where id='".sql_friendly($id)."'
		";
		simple_query($sql);	
		
		if($val==0)
		{
			//unflag and reset users
			$sql = "
			update users set	
				store_id=deleted_store_id,
				deleted='0',
				archived='0'
			where deleted_store_id='".sql_friendly($id)."'";
			simple_query($sql);	
			
			//mark user deleted store back to zero.
			$sql = "
			update users set	
				deleted_store_id=0
			where deleted_store_id='".sql_friendly($id)."'";
			simple_query($sql);	
		}
		
		$_SESSION['selected_store_id']=0;
		
		display_xml_response("<rslt>1</rslt>");	
	}
	function get_store_location_details()
	{		
		$rval="";
		$id=$_POST['id'];
		
		$sql = "
			select store_locations.*,
				(select attached_files.filename from attached_files where attached_files.deleted=0 and attached_files.xref_id=store_locations.id and attached_files.section_id=10 order by id desc limit 1) as store_image,
				(select users.contact_phone1 from users where users.id=store_locations.cm_user_id) as cm_user_cell,
				(select users.contact_phone2 from users where users.id=store_locations.cm_user_id) as cm_user_phone,
				(select attached_files.filename from attached_files where attached_files.deleted=0 and attached_files.xref_id=store_locations.cm_user_id and attached_files.section_id=8 order by id desc limit 1) as cm_image
			from store_locations
			where store_locations.id='".sql_friendly($id)."'
		";
		$data=simple_query($sql);	
		if($row = mysqli_fetch_array($data))
		{						
			$rval.="<arch><![CDATA[".$row['archived']."]]></arch>";
			$rval.="<id><![CDATA[".$row['id']."]]></id>";
			$rval.="<user><![CDATA[".$row['user_id']."]]></user>";
			$rval.="<added><![CDATA[".date("m/d/Y H:i",strtotime($row['linedate_added']))."]]></added>";
			$rval.="<deleted><![CDATA[".$row['deleted']."]]></deleted>";
			
			$edit_user="";
			$edit_user2="";
			$edit_mode="";
			$valid_user=check_user_edit_access('users',$row['cm_user_id'],$_SESSION['user_id']);
			if($valid_user > 0)	
			{
				if($valid_user==1)	$edit_mode="readonly";	
				$edit_user="javascript: void(0);";
				$edit_user2="select_user_id(".$row['id'].",\"".$edit_mode."\");";
			}
			
			$rval.="<CMuser><![CDATA[".$row['cm_user_id']."]]></CMuser>";
			
			$rval.="<CMuserCell><![CDATA[".$row['cm_user_cell']."]]></CMuserCell>";
			$rval.="<CMuserPhone><![CDATA[".$row['cm_user_phone']."]]></CMuserPhone>";
			
			$rval.="<CMuserImage><![CDATA[".create_thumbnail("documents/".$row['cm_image'], 100)."]]></CMuserImage>";
			$rval.="<CMuserName><![CDATA[".mrr_get_user_email_names($row['cm_user_id'],0)."]]></CMuserName>";
			$rval.="<CMuserEmail><![CDATA[".mrr_get_user_email_address($row['cm_user_id'],0)."]]></CMuserEmail>";
			$rval.="<CMuserEdit><![CDATA[".$edit_user."]]></CMuserEdit>";
			$rval.="<CMuserEdit2><![CDATA[".$edit_user2."]]></CMuserEdit2>";
			
			
			$edit_button="";
			$valid_user=check_user_edit_access('store_locations',$row['id'],$_SESSION['user_id']);
			if($valid_user > 1)	
			{
				$allow_editor1="<i class='fa fa-pencil' style='color:#e19918;' title='Click to edit this store' onClick='edit_store_location(".$row['id'].",1);'></i>";
				$allow_editor2="<i class='fa fa-trash' style='color:#e19918;' title='Click to remove this store' onClick='edit_store_location(".$row['id'].",2);'></i>";
				$allow_editor3="<i class='fa fa-chevron-circle-down' style='color:#e19918;' title='Click to check it off (archive).' onClick='edit_store_location(".$row['id'].",3);'></i>";	
				
				$edit_button="".$allow_editor1."&nbsp;&nbsp;".$allow_editor2."&nbsp;&nbsp;";	//".$allow_editor3."
			}
			
			$rval.="<EditButton><![CDATA[".$edit_button."]]></EditButton>";
			
			
			$rval.="<StoreImage><![CDATA[/documents/".$row['store_image']."]]></StoreImage>";
						
			$rval.="<StoreName><![CDATA[".$row['store_name']."]]></StoreName>";
			$rval.="<StoreNumber><![CDATA[".$row['store_number']."]]></StoreNumber>";
			$rval.="<Addr1><![CDATA[".$row['address1']."]]></Addr1>";
			$rval.="<Addr2><![CDATA[".$row['address2']."]]></Addr2>";
			$rval.="<City><![CDATA[".$row['city']."]]></City>";
			$rval.="<State><![CDATA[".$row['state']."]]></State>";
			$rval.="<Zip><![CDATA[".$row['zip']."]]></Zip>";
			$rval.="<Title><![CDATA[".$row['contact_title']."]]></Title>";
			$rval.="<First><![CDATA[".$row['contact_first_name']."]]></First>";
			$rval.="<Last><![CDATA[".$row['contact_last_name']."]]></Last>";
			$rval.="<Phone1><![CDATA[".$row['contact_phone1']."]]></Phone1>";
			$rval.="<Phone2><![CDATA[".$row['contact_phone2']."]]></Phone2>";
			$rval.="<Phone3><![CDATA[".$row['contact_phone3']."]]></Phone3>";
			$rval.="<Phone4><![CDATA[".$row['contact_phone4']."]]></Phone4>";
			$rval.="<Email><![CDATA[".$row['contact_email']."]]></Email>";
			$rval.="<Template><![CDATA[".$row['template_id']."]]></Template>";
			$rval.="<Merchant><![CDATA[".$row['merchant_id']."]]></Merchant>";
			
			$rval.="<MSBname><![CDATA[".$row['msb_auditor']."]]></MSBname>";
			$rval.="<MSBref><![CDATA[".$row['msb_ref_number']."]]></MSBref>";
			$rval.="<MSBcell><![CDATA[".$row['msb_cell']."]]></MSBcell>";
			$rval.="<MSBphone><![CDATA[".$row['msb_phone']."]]></MSBphone>";
			$rval.="<MSBemail><![CDATA[".$row['msb_email']."]]></MSBemail>";
			$rval.="<MSBaddress><![CDATA[".$row['msb_address']."]]></MSBaddress>";
			
			$rval.="<IRSname><![CDATA[".$row['irs_agent']."]]></IRSname>";
			$rval.="<IRSref><![CDATA[".$row['irs_employee_id']."]]></IRSref>";
			$rval.="<IRScell><![CDATA[".$row['irs_cell']."]]></IRScell>";
			$rval.="<IRSphone><![CDATA[".$row['irs_phone']."]]></IRSphone>";
			$rval.="<IRSemail><![CDATA[".$row['irs_email']."]]></IRSemail>";
			$rval.="<IRSaddress><![CDATA[".$row['irs_address']."]]></IRSaddress>";
			$rval.="<IRScase><![CDATA[".$row['irs_case_number']."]]></IRScase>";
						
         	 	$_SESSION['selected_merchant_id']=$row['merchant_id'];
			$_SESSION['selected_store_id']=$id;
		}
		
		display_xml_response("<rslt>1</rslt>".$rval."");	
	}
	function update_store_location()
	{
		$id=$_POST['id'];
		$name=$_POST['store_name'];
		$num=$_POST['store_number'];
		$addr1=$_POST['address1'];
		$addr2=$_POST['address2'];
		$city=$_POST['city'];
		$state=$_POST['state'];
		$zip=$_POST['zip'];
		$title=$_POST['title'];
		$first=$_POST['first'];
		$last=$_POST['last'];
		$phone1=$_POST['phone1'];
		$phone2=$_POST['phone2'];
		$phone3=$_POST['phone3'];
		$phone4=$_POST['phone4'];
		$email=$_POST['email'];
		$template_id=$_POST['template_id'];
		$merchant_id=$_POST['merchant_id'];
		$cm_id=$_POST['cm_user_id'];
		
		$msb_aud=$_POST['msb_auditor'];
		$msb_ref=$_POST['msb_ref_number'];
		$msb_cell=$_POST['msb_cell'];
		$msb_phone=$_POST['msb_phone'];
		$msb_email=$_POST['msb_email'];
		$msb_addr=trim($_POST['msb_addr']);		
		
		$irs_addr=trim($_POST['irs_addr']);
		$irs_agent=trim($_POST['irs_agent']);
		$irs_empid=trim($_POST['irs_empid']);
		$irs_email=trim($_POST['irs_email']);
		$irs_phone=trim($_POST['irs_phone']);
		$irs_cell=trim($_POST['irs_cell']);
		$irs_case=trim($_POST['irs_case']);
		
		if($merchant_id==0)	$merchant_id=$_SESSION['selected_merchant_id'];
		
		$new_store=0;
		
				
		if($id==0)
		{
			$sql="
				insert into store_locations
					(id,
					user_id,
					linedate_added,
					merchant_id,
					store_name,
					store_number,					
					deleted,
					archived)
				values
					(NULL,
					'".sql_friendly($_SESSION['user_id'])."',
					NOW(),
					0,
					'',
					'',
					0,
					0)
			";
			simple_query($sql);	
			$id=get_mysql_insert_id();
			
			$sql="
				insert into cce_messages
					(id,
					section,
					linedate_added,
					deleted,
					subject,
					message,
					merchant_id,
					store_id)
				values
					(NULL,
					'store',
					NOW(),
					0,
					'',
					'',
					'".sql_friendly($merchant_id)."',
					'".sql_friendly($id)."')
			";
			simple_query($sql);	
			
			$new_store=1;
		}
				
		$sql = "
			update store_locations set			
				address1='".sql_friendly($addr1)."',
				address2='".sql_friendly($addr2)."',
				city='".sql_friendly($city)."',
				state='".sql_friendly($state)."',
				zip='".sql_friendly($zip)."',
				contact_title='".sql_friendly($title)."',
				contact_first_name='".sql_friendly($first)."',
				contact_last_name='".sql_friendly($last)."',
				contact_phone1='".sql_friendly($phone1)."',
				contact_phone2='".sql_friendly($phone2)."',
				contact_email='".sql_friendly($email)."',
				contact_phone3='".sql_friendly($phone3)."',
				contact_phone4='".sql_friendly($phone4)."',
				msb_auditor='".sql_friendly($msb_aud)."',
     			msb_ref_number='".sql_friendly($msb_ref)."',
     			msb_cell='".sql_friendly($msb_cell)."',
     			msb_phone='".sql_friendly($msb_phone)."',
     			msb_email='".sql_friendly($msb_email)."',
     			msb_address='".sql_friendly($msb_addr)."',
     			irs_address='".sql_friendly($irs_addr)."',
    				irs_cell='".sql_friendly($irs_cell)."',
    				irs_phone='".sql_friendly($irs_phone)."',
    				irs_email='".sql_friendly($irs_email)."',
    				irs_agent='".sql_friendly($irs_agent)."',
    				irs_employee_id='".sql_friendly($irs_empid)."',
				irs_case_number='".sql_friendly($irs_case)."',
				cm_user_id='".sql_friendly($cm_id)."',
				template_id='".sql_friendly($template_id)."',
				merchant_id='".sql_friendly($merchant_id)."',
				store_number='".sql_friendly($num)."',
				store_name='".sql_friendly($name)."'
			where id='".sql_friendly($id)."'
		";	
		$temp_sql=$sql;
		simple_query($sql);	
		
		$_SESSION['selected_merchant_id']=$merchant_id;
		$_SESSION['selected_store_id']=$id;
		
		//Assigning user to merchant no longer to limit the user to this merchant
		/*
		if($cm_id > 0)
		{	//if selected update the user as this store only.
			$sql = "
     			update users set     			
     				store_id='".sql_friendly($id)."'     					
     			where id='".sql_friendly($cm_id)."'
     		";	
     		simple_query($sql);		
		}	
		*/	
		
		//if($merchant_id> 0 && $cm_id == 0 && $new_store==1)		copy_merchant_info_to_store($merchant_id,$id);
		
		display_xml_response("<rslt>".$id."</rslt><mrrSQL><![CDATA[".$temp_sql."]]></mrrSQL>");		
	}
	
	//important dates
	function load_important_dates()
	{
		$tab=mrr_show_important_dates();
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	function get_important_date_details()
	{
		$rval="";
		$id=$_POST['id'];
		
		
		$sql = "
			select * 
			from important_dates
			where id='".sql_friendly($id)."'
		";
		$data=simple_query($sql);	
		if($row = mysqli_fetch_array($data))
		{
			$rval.="<Type><![CDATA[".$row['date_type']."]]></Type>";
			$rval.="<Date><![CDATA[".date("m/d/Y",strtotime($row['linedate']))."]]></Date>";
			$rval.="<Sub><![CDATA[".$row['date_description']."]]></Sub>";
			$rval.="<Desc><![CDATA[".$row['date_message']."]]></Desc>";
			
			$rval.="<Rem1Date><![CDATA[".date("m/d/Y",strtotime($row['linedate_reminder1']))."]]></Rem1Date>";
			$rval.="<Rem1Email><![CDATA[".$row['email_reminder1']."]]></Rem1Email>";
			$rval.="<Rem1Msg><![CDATA[".$row['msg_reminder1']."]]></Rem1Msg>";
			
			$rval.="<Rem2Date><![CDATA[".date("m/d/Y",strtotime($row['linedate_reminder2']))."]]></Rem2Date>";
			$rval.="<Rem2Email><![CDATA[".$row['email_reminder2']."]]></Rem2Email>";
			$rval.="<Rem2Msg><![CDATA[".$row['msg_reminder2']."]]></Rem2Msg>";
			
			$rval.="<arch><![CDATA[".$row['archived']."]]></arch>";
			$rval.="<id><![CDATA[".$row['id']."]]></id>";
			$rval.="<user><![CDATA[".$row['user_id']."]]></user>";
			$rval.="<added><![CDATA[".date("m/d/Y H:i",strtotime($row['linedate_added']))."]]></added>";
			$rval.="<deleted><![CDATA[".$row['deleted']."]]></deleted>";
		}
		
		display_xml_response("<rslt>1</rslt>".$rval."");	
	}
	function delete_important_date()
	{
		$id=$_POST['id'];
		
		$sql = "
			update important_dates set
				deleted = 1
			where id='".sql_friendly($id)."'
		";
		simple_query($sql);	
		
		display_xml_response("<rslt>1</rslt>");	
	}
	function archive_important_date()
	{
		$id=$_POST['id'];
		$val=1;
		if(isset($_POST['value']))		$val=$_POST['value'];
		
		$sql = "
			update important_dates set
				archived = '".sql_friendly($val)."',
				deleted = '".sql_friendly($val)."'
			where id='".sql_friendly($id)."'
		";
		simple_query($sql);	
		
		display_xml_response("<rslt>1</rslt>");	
	}
	function update_important_date()
	{
		$id=$_POST['id'];
		$date0=$_POST['date'];
		$sub=$_POST['title'];
		$msg0=$_POST['msg'];
		$type=$_POST['type'];
		
		$date1=$_POST['date_remind1'];
		$eml1=$_POST['email_remind1'];
		$msg1=$_POST['msg_remind1'];
		
		$date2=$_POST['date_remind2'];
		$eml2=$_POST['email_remind2'];
		$msg2=$_POST['msg_remind2'];
		
		
		$use_merchant=0;
		//find merchant template first...acts as a default.
     	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$use_merchant=$_SESSION['selected_merchant_id'];
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$use_merchant=$_SESSION['merchant_id'];
     	}
     	
     	$use_store=0;
     	//find store template next...override merchant if set.
     	if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
     	{
     		$use_store=$_SESSION['selected_store_id'];
     	}     	
     	elseif($_SESSION['store_id'] > 0)
     	{
     		$use_store=$_SESSION['store_id'];
     	}
		
		
		if($id==0)
		{
			$sql="
				insert into important_dates
					(id,
					user_id,
					linedate_added,
					date_description,
					date_message,
					msg_reminder1,
					msg_reminder2,
					date_type,
					deleted,
					merchant_id,
					store_id,
					archived,
					sent_reminder1,
					sent_reminder2)
				values
					(NULL,
					'".sql_friendly($_SESSION['user_id'])."',
					NOW(),
					'',
					'',
					'',
					'',
					0,
					0,
					'".sql_friendly($use_merchant)."',
					'".sql_friendly($use_store)."',
					0,
					0,
					0)
			";
			simple_query($sql);	
			$id=get_mysql_insert_id();
		}
				
		$sql = "
			update important_dates set
					date_description='".sql_friendly($sub)."',
					date_message='".sql_friendly($msg0)."',
					date_type='".sql_friendly($type)."',
					linedate='".date("Y-m-d", strtotime($date0))."',
					
					".( strtotime($date1) > time() ? "sent_reminder1=0," : "")."
					linedate_reminder1='".($date1!="" ? date("Y-m-d", strtotime($date1)) : "")."',
					email_reminder1='".sql_friendly($eml1)."',
					msg_reminder1='".sql_friendly($msg1)."',
					
					".( strtotime($date2) > time() ? "sent_reminder2=0," : "")."
					linedate_reminder2='".($date2!="" ? date("Y-m-d", strtotime($date2)) : "")."',
					email_reminder2='".sql_friendly($eml2)."',
					msg_reminder2='".sql_friendly($msg2)."'
			where id='".sql_friendly($id)."'
		";
		simple_query($sql);	
		
		display_xml_response("<rslt>".$id."</rslt>");		
	}
	
	//users
	function archive_user()
	{
		$id=$_POST['id'];
		$val=1;
		if(isset($_POST['value']))		$val=$_POST['value'];
		
		$sql = "
			update users set
				archived = '".sql_friendly($val)."',
				deleted = '".sql_friendly($val)."'
			where id='".sql_friendly($id)."'
		";
		simple_query($sql);	
		
		display_xml_response("<rslt>1</rslt>");	
	}
	function search_users() 
	{
		//search user table, but only find those in access restrictions	
		$merchant=0;
		$store=0;
		
		$access_level=$_SESSION['access_level'];
		$view_level=$_SESSION['view_access_level'];
		if(isset($_SESSION['merchant_id']))		$merchant=$_SESSION['merchant_id'];
		if(isset($_SESSION['store_id']))			$store=$_SESSION['store_id'];
		
		$filter="";
		if($store > 0 && $access_level <= 40)		$filter.=" and store_id='".sql_friendly($store)."'";
		if($merchant > 0 && $access_level <= 80)	$filter.=" and merchant_id='".sql_friendly($merchant)."'";
		if($view_level < 100)					$filter.=" and access_level < '".sql_friendly($view_level)."'";
				
		$sql = "
			select *
			
			from users
			where (username like '%".sql_friendly($_GET['q'])."%')
				and deleted = 0
				".$filter."
			order by username
			limit 100
		";
		$data = simple_query($sql);
		
		while($row = mysqli_fetch_array($data)) {
			echo "$row[username]|$row[first_name] $row[last_name]\n";
		}
	}	
	
	function display_user_settings_form()
	{
		$id=$_POST['user_id'];
		$edit_mode=$_POST['edit_mode'];
		$view_user_access=$_SESSION['view_access_level'];	
		
		echo mrr_user_settings_form($id,$edit_mode,$view_user_access=0);
	}
	
	function load_merchant_archive()
	{
		$tab=mrr_merchant_archive();
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");		
	}
	
	function search_docs_filter()
	{
		$filter=$_POST['search_doc'];
		$view_user_access=$_SESSION['view_access_level'];
		
		$tab=mrr_search_docs(trim($filter),$view_user_access);
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	function search_custs_filter()
	{
		$filter=$_POST['search_cust'];
		$view_user_access=$_SESSION['view_access_level'];
		
		//$tab=mrr_search_custs(trim($filter),$view_user_access);
		$tab=mrr_search_merchant_locs(trim($filter),$view_user_access,0);
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	function search_custs_filter_v2()
	{
		$filter=$_POST['search_cust'];
		$view_user_access=$_SESSION['view_access_level'];
		
		//$tab=mrr_search_custs(trim($filter),$view_user_access);
		$tab=mrr_search_merchant_locs(trim($filter),$view_user_access,1);
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	function search_stores_filter()
	{
		$filter=$_POST['search_store'];
		$view_user_access=$_SESSION['view_access_level'];
		
		$tab=mrr_search_stores(trim($filter),$view_user_access);
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	function list_users_selected()
	{		
		$tab=mrr_list_users();
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	function search_users_filter()
	{
		$filter=$_POST['search_universal'];
		$view_user_access=$_SESSION['view_access_level'];
		
		$tab=mrr_search_users(trim($filter),$view_user_access);
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	function save_user() 
	{
		mrr_update_account($_POST['id'],$_POST['user_access_level'],$_POST['archived'],$_POST['user_email'],
						$_POST['user_first'],$_POST['user_last'],$_POST['merchant_id'],$_POST['store_id'],$_POST['user_title'],$_POST['logs'],$_POST['phone1'],$_POST['phone2']);
		
		display_xml_response("<rslt>1</rslt>");
	}
	function save_user_pass() 
	{
		$rslt=mrr_update_account_pass($_POST['id'],$_POST['user'],$_POST['pass'],$_POST['confirm']);
		
		display_xml_response("<rslt>".$rslt."</rslt>");
	}
	function new_user() 
	{	//create new account with low access level...for free tools section.
		global $lang;
		
		$e_addr=trim($_POST['new_email']);
		$user=trim($_POST['new_username']);
		$pass=trim($_POST['new_pword']);
		$utype=$_POST['new_acct_type'];
		
		$res=mrr_create_account($user,$pass,$e_addr,$utype);		
		$error_new=$res['msg'];		
		$newid=$res['newid'];
		$editing=$res['auto_edit'];
		
		display_xml_response("<rslt>1</rslt><newid>".$newid."</newid><msg><![CDATA[".$error_new."]]></msg><AutoEdit><![CDATA[".$editing."]]></AutoEdit><AutoSelect><![CDATA[".$utype."]]></AutoSelect>");
	}
	function delete_user() 
	{		
		mrr_remove_account($_POST['id']);
		
		display_xml_response("<rslt>1</rslt>");
	}
	function get_user_details()
	{		
		$rval="";
		$id=$_POST['id'];
		
		$sql = "
			select users.*,
				(select user_levels.level_name from user_levels where user_levels.deleted=0 and user_levels.access_level=users.access_level) as user_level_name,
				(select attached_files.filename from attached_files where attached_files.deleted=0 and attached_files.xref_id=users.id and attached_files.section_id=8 order by id desc limit 1) as user_image,
				(select store_name from store_locations where store_locations.id=users.store_id and store_locations.deleted=0) as user_store
			from users
			where users.id='".sql_friendly($id)."'
		";
		$data=simple_query($sql);	
		if($row = mysqli_fetch_array($data))
		{						
			$rval.="<arch><![CDATA[".$row['archived']."]]></arch>";
			$rval.="<id><![CDATA[".$row['id']."]]></id>";
			$rval.="<added><![CDATA[".date("m/d/Y H:i",strtotime($row['linedate_added']))."]]></added>";
			$rval.="<deleted><![CDATA[".$row['deleted']."]]></deleted>";
			
			$edit_user="";
			$edit_user2="";
			$edit_mode="";
			$valid_user=check_user_edit_access('users',$row['id'],$_SESSION['user_id']);
			if($valid_user==1)	
			{
				$edit_mode="readonly";	
				$edit_user="javascript: void(0);";
				$edit_user2="select_user_id(".$row['id'].",\"".$edit_mode."\");";
			}
			
			$logs="No";
			if($row['monitor_logs'] > 0)	$logs="Yes";
			
			if($row['store_id']==0)		$row['user_store']="All";
			
			$rval.="<UserName><![CDATA[".$row['username']."]]></UserName>";
			$rval.="<UserTitle><![CDATA[".$row['title']."]]></UserTitle>";
			$rval.="<UserImage><![CDATA[/documents/".$row['user_image']."]]></UserImage>";
			$rval.="<UserFirst><![CDATA[".$row['first_name']."]]></UserFirst>";
			$rval.="<UserLast><![CDATA[".$row['last_name']."]]></UserLast>";
			$rval.="<UserEmail><![CDATA[".$row['email']."]]></UserEmail>";
			$rval.="<UserEdit><![CDATA[".$edit_user."]]></UserEdit>";
			$rval.="<UserEdit2><![CDATA[".$edit_user2."]]></UserEdit2>";
			$rval.="<UserCell><![CDATA[".$row['contact_phone1']."]]></UserCell>";
			$rval.="<UserPhone><![CDATA[".$row['contact_phone2']."]]></UserPhone>";
			$rval.="<UserPhone3><![CDATA[".$row['contact_phone3']."]]></UserPhone3>";
			$rval.="<UserPhone4><![CDATA[".$row['contact_phone4']."]]></UserPhone4>";
			$rval.="<StoreName><![CDATA[".$row['user_store']."]]></StoreName>";
			$rval.="<LevelName><![CDATA[".$row['user_level_name']."]]></LevelName>";
			$rval.="<Logs><![CDATA[".$logs."]]></Logs>";
			
			
			//allow group managers to pick another user...by skipping the assignment for them
			$_SESSION['selected_user_id']=$id;
			if($row['access_level']==70)					$_SESSION['selected_user_id']=0;
						
			//attempt to set, but do not override if the selected item is different.
         	 	if($_SESSION['selected_merchant_id']==0)		$_SESSION['selected_merchant_id']=$row['merchant_id'];
			if($_SESSION['selected_store_id']==0)			$_SESSION['selected_store_id']=$row['store_id'];
		}
		
		display_xml_response("<rslt>1</rslt>".$rval."");	
	}
	function get_user_image() 
	{
		global $defaultsarray;
		
		$sql = "
			select *
			
			from attached_files
			where xref_id = '".sql_friendly($_POST['user_id'])."'
				and section_id = '".sql_friendly(SECTION_AVATAR)."'
				and deleted = 0
			order by id desc
		";
		$data = simple_query($sql);
		
		$msg = "";
		
		if(!mysqli_num_rows($data)) 
		{
			$file = '';
			$file2 = '';
			$rslt = 0;
			$msg = "Could not locate file";
		} 
		else 
		{
			$row = mysqli_fetch_array($data);
			if($row['filename']!="")
			{
     			$file = "documents/".$row['filename'];
     			if($row['public_flag']==0)
     			{
     				$uuid = createuuid();	
     				// copy the file to a temp location to view
     				$tmp_filename = "temp/".$uuid.$row['filename'];
     				copy($defaultsarray['base_path'].'/uploads/'.$row['filename'], $defaultsarray['base_path'].'public_html/'.$tmp_filename);			
     				$tmp_filename=str_replace("#",'%23',$tmp_filename);			//File name is truncated if the '#' letter is found.  'test_file#1234.jpg' becomes 'test_file'.  File won't be found.
     				$file=$tmp_filename;
     			}
			}			
			$rslt = 1;
		}
		display_xml_response("<rslt>$rslt</rslt><msg><![CDATA[".$msg."]]></msg><File><![CDATA[".$file."]]></File>");	
	}
	function get_user_cert() 
	{
		global $defaultsarray;
		
		$sql = "
			select *
			
			from attached_files
			where xref_id = '".sql_friendly($_POST['user_id'])."'
				and section_id = '".sql_friendly(SECTION_CERTIFICATES)."'
				and deleted = 0
			order by id desc
		";
		$data = simple_query($sql);
		
		$msg = "";
		
		if(!mysqli_num_rows($data)) 
		{
			$file = '';
			$file2 = '';
			$rslt = 0;
			$msg = "Could not locate certificate for user ".$_POST['user_id'].".";
		} 
		else 
		{
			$row = mysqli_fetch_array($data);
			if($row['filename']!="")
			{
     			$file = "documents/".$row['filename'];
     			if($row['public_flag']==0)
     			{
     				$uuid = createuuid();	
     				// copy the file to a temp location to view
     				$tmp_filename = "temp/".$uuid.$row['filename'];
     				copy($defaultsarray['base_path'].'/uploads/'.$row['filename'], $defaultsarray['base_path'].'public_html/'.$tmp_filename);			
     				$tmp_filename=str_replace("#",'%23',$tmp_filename);			//File name is truncated if the '#' letter is found.  'test_file#1234.jpg' becomes 'test_file'.  File won't be found.
     				$file=$tmp_filename;
     			}
			}			
			$rslt = 1;
		}
		display_xml_response("<rslt>$rslt</rslt><msg><![CDATA[".$msg."]]></msg><File><![CDATA[".$file."]]></File>");	
	}
	function verify_access_level() {
		$sql = "
			select id,
				access_level
			
			from users
			where username = '".sql_friendly($_POST['username'])."'
				and password = '".sql_friendly($_POST['password'])."'
				and deleted = 0
				and active = 1
		";
		$data = simple_query($sql);
		
		$rslt = 0;
		$rsltmsg = "";
		$user_id = 0;
		
		if(!mysqli_num_rows($data)) {
			// user not found
			$rsltmsg = "Invalid username or password entered";
		} else {
			$row = mysqli_fetch_array($data);
			
			if($row['access_level'] >= $_POST['check_access_level']) {
				// good to go
				$rslt = 1;
				$user_id = $row['id'];
			} else {
				// username that was entered doesn't have the access level requested
				$rsltmsg = "Username entered does not have the access level requested";
			}
		}
		
		display_xml_response("<rslt>$rslt</rslt><rsltmsg><![CDATA[$rsltmsg]]></rsltmsg><userid>$user_id</userid>");
	}
	function user_password_reset() 
	{
		$user_name=$_POST['user_name'];
		$e_addr=$_POST['user_email'];
		$user_id=$_POST['user_id'];
		
		$newpass=mrr_encryptor("reset_password",$user_name);
		
		$sql = "
			update users set
				reset_password=1,
				linedate_failed='0000-00-00 00:00:00',
				failed_logins=0,
				password='".sql_friendly($newpass)."'
			where id = '".sql_friendly($user_id)."'
		";
		simple_query($sql);
		
		mrr_send_login_email($e_addr,$user_name,$newpass,$user_id);
		
		display_xml_response("<rslt>1</rslt>");
	}
	
	function timeout_check() {
		// since the user could have many pages open, we want to see whichever one was the last one opened, then use that for the timeout check
		
		global $defaultsarray;
		$sql = "
			select now() as currentdt,
				linedate_last_pageload
			
			from users
			where id = '".sql_friendly($_SESSION['user_id'])."'
		";
		$data = simple_query($sql);
		$row = mysqli_fetch_array($data);
		
		$seconds_since_pageload = (strtotime($row['currentdt']) - strtotime($row['linedate_last_pageload']));
		
		$timeout_left = $defaultsarray['session_timeout'] - $seconds_since_pageload;
		
		if($timeout_left <= 0) {
			$timed_out = '1';
		} else {
			$timed_out = '0';
		}
		
		$sql = "
			insert into log_timeout_check
				(user_id,
				linedate_added,
				timeout_limit,
				timed_out_flag,
				linedate_last_pageload,
				seconds_remaining)
				
			values ('".sql_friendly($_SESSION['user_id'])."',
				now(),
				'".sql_friendly($defaultsarray['session_timeout'])."',
				'$timed_out',
				'".date("Y-m-d H:i:s", strtotime($row['linedate_last_pageload']))."',
				'$timeout_left')
		";
		$new_id = simple_query($sql);
		
		if($new_id % 100 == 1) 
		{
			$sql = "
				delete from log_timeout_check
				where linedate_added < '".date("Y-m-d", strtotime("-14 day", time()))."'
			";
			simple_query($sql);
		}
		
		display_xml_response("<rslt>1</rslt><TimeoutBool>$timed_out</TimeoutBool><TimeoutLeft>$timeout_left</TimeoutLeft>");
	}
	
	
	function get_logo_image() 
	{
		global $defaultsarray;
		
		$sql = "
			select *
			
			from attached_files
			where (xref_id = '".sql_friendly($_POST['merchant_id'])."' or merchant_id= '".sql_friendly($_POST['merchant_id'])."')
				and section_id = '".sql_friendly(SECTION_LOGO_CUST)."'
				and deleted = 0
			order by merchant_id desc,id desc
		";
		$data = simple_query($sql);
		
		$msg = "";
		
		if(!mysqli_num_rows($data)) 
		{
			$file = '';
			$file2 = '';
			$rslt = 0;
			$msg = "Could not locate file";
		} 
		else 
		{
			$row = mysqli_fetch_array($data);
			if($row['filename']!="")
			{
     			$file = "documents/".$row['filename'];
     			
			}
			
			$rslt = 1;
		}
		display_xml_response("<rslt>$rslt</rslt><msg><![CDATA[".$msg."]]></msg><File><![CDATA[".$file."]]></File>");	
	}
	function get_store_image() 
	{
		global $defaultsarray;
		
		$sql = "
			select *
			
			from attached_files
			where (xref_id = '".sql_friendly($_POST['store_id'])."' or store_id= '".sql_friendly($_POST['store_id'])."')
				and section_id = '".sql_friendly(SECTION_LOGO_STORE)."'
				and deleted = 0
			order by store_id desc,id desc
		";
		$data = simple_query($sql);
		
		$msg = "";
		
		if(!mysqli_num_rows($data)) 
		{
			$file = '';
			$file2 = '';
			$rslt = 0;
			$msg = "Could not locate file";
		} 
		else 
		{
			$row = mysqli_fetch_array($data);
			if($row['filename']!="")
			{
     			$file = "documents/".$row['filename'];
     			
			}
			
			$rslt = 1;
		}
		display_xml_response("<rslt>$rslt</rslt><msg><![CDATA[".$msg."]]></msg><File><![CDATA[".$file."]]></File>");	
	}
	
	function refresh_store_selector() 
	{
		$rval = "";
		
		$sql = "
			select *			
			from store_locations
			where deleted=0
				and merchant_id = '".sql_friendly($_POST['merchant_id'])."'	
			order by store_number asc,store_name asc		
		";
		$data = simple_query($sql);		
		while($row = mysqli_fetch_array($data)) 
		{
			$rval .= "
				<StoreEntry>
					<StoreID>$row[id]</StoreID>
					<StoreName><![CDATA[ $row[store_name] UID: $row[store_number]]]></StoreName>
				</StoreEntry>
			";			
		}		
		display_xml_response("<rslt>1</rslt>$rval");	
	}
	function refresh_sub_item_selector() 
	{
		$rval = "";
						
          if(isset($_POST['item_grp']) && isset($_POST['my_item']))
          {
          	$sql = "
				select *			
				from template_items
				where deleted=0
					and sub_group_id > 0
					and sub_group_id = '".sql_friendly($_POST['item_grp'])."'	
				order by item_label asc		
			";
			$data = simple_query($sql);	
          }
		else 
		{
			$sql = "
				select *			
				from template_items
				where deleted=0
					and sub_group_id > 0
					and sub_group_id = '".sql_friendly($_POST['item_id'])."'	
				order by item_label asc		
			";
			$data = simple_query($sql);		
		}
		while($row = mysqli_fetch_array($data)) 
		{
			$rval .= "
				<ItemEntry>
					<ItemID>$row[id]</ItemID>
					<ItemName><![CDATA[ $row[item_label]]]></ItemName>
				</ItemEntry>
			";			
		}	
		display_xml_response("<rslt>1</rslt>$rval");	
	}
	function search_template_items() 
	{			
		$sql = "
			select *
			
			from template_items
			where (item_label like '".sql_friendly($_GET['q'])."%')
				and deleted = 0
				and archived = 0
				and sub_group_id = 0
			order by item_label asc,template_id asc
			limit 100
		";
		$data = simple_query($sql);
		
		while($row = mysqli_fetch_array($data)) 
		{
			echo "$row[item_label]|$row[title_text]\n";
		}	
	}	
	
	function update_auditor2_assignment() 
	{
		$id=$_POST['file_id'];
		$viewable=$_POST['viewable'];
				
		$sql = "
			update attached_files set
				auditor2_viewable='".sql_friendly($viewable)."'
			where id = '".sql_friendly($id)."'
		";
		simple_query($sql);
		
		display_xml_response("<rslt>1</rslt>");
	}
	function refresh_auditor2_assignment()
	{
		$tab=display_auditor2_file_controls(1);		//default to master template
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");		
	}
	function refresh_auditor2_files()
	{
		$tab=display_auditor2_files(0);
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");		
	}
	
	function pick_selected_item()
	{
		$user_id=$_POST['user_id'];
		$merchant_id=$_POST['merchant_id'];
		$store_id=$_POST['store_id'];
		
		$_SESSION['selected_user_id']=$user_id;
		$_SESSION['selected_merchant_id']=$merchant_id;
		$_SESSION['selected_store_id']=$store_id;
		
		$_SESSION['special_merchant_id']=$merchant_id;
		
		$tab=show_selected_route_info($merchant_id,$store_id,$user_id,1);
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab>");	
	}
	
	function update_bread_crumb_trail()
	{
		$merchant_id=$_SESSION['selected_merchant_id'];
		$store_id=$_SESSION['selected_store_id'];
		$user_id=$_SESSION['selected_user_id'];
		
		$only=0;
		if(isset($_POST['only']))		$only=1;
		
		$tab=show_selected_route_info($merchant_id,$store_id,$user_id,1);
		
		$display="Customer ".$merchant_id.", Store ".$store_id.", User ".$user_id."";
		
		display_xml_response("<rslt>1</rslt><mrrTab><![CDATA[".$tab."]]></mrrTab><mrrDisplay><![CDATA[".$display."]]></mrrDisplay>");		
	}
	function debread_crumb_trail()
	{
		if($_POST['mode']==1)		$_SESSION['selected_merchant_id']=0;
		elseif($_POST['mode']==2)	$_SESSION['selected_store_id']=0;
		elseif($_POST['mode']==3)	$_SESSION['selected_user_id']=0;
		elseif($_POST['mode']==0)
		{	//clear all			
			$_SESSION['selected_merchant_id']=0;
			$_SESSION['selected_store_id']=0;
			$_SESSION['selected_user_id']=0;
		}
		
		$_SESSION['special_merchant_id']=$_SESSION['selected_merchant_id'];
		
		display_xml_response("<rslt>1</rslt>");		
	}
	
	
	function mrr_heart_beat()
	{
		//function attempts to keep session alive if page is up...
		$result=0;
		if(isset($_SESSION['user_id']))
		{
			if($_SESSION['user_id'] > 0)		$result=1;
		}
		display_xml_response("<rslt>".$result."</rslt>");
	}
	
	function case_default()
	{
		display_xml_response("<rslt>1</rslt>");	
	}
	
	function zip_download_files() 
	{
		global $defaultsarray;
		$msg="";
				
		$zip = new ZipArchive();
		
		$public_filename = "temp/".uniqid('', true).".zip";
		$filename = getcwd()."/".$public_filename;
		
		if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) 
		{
		    display_xml_response("<rslt>0</rslt><rsltmsg>Could not create Zip File.</rsltmsg>");	
		}
				
		foreach($_POST['file_array'] as $attachment_id) 
		{
			$sql="
          		select *
             		from attached_files
             		where deleted=0
             			and auditor2_viewable > 0            			
             			and linedate_display_start <= NOW()
             			and id='".sql_friendly($attachment_id)."'
          	";
          	$data = simple_query($sql);          
          	if($row = mysqli_fetch_array($data))
			{
				$file="".$defaultsarray['base_path']."uploads/".$row['filename'];	
				
				if($row['public_flag']==1)		$file="".$defaultsarray['base_path']."public_html/documents/".$row['filename']."";
				
				//copy /hidden/filename --- accessible/filename
				//$file = accessible/filename
				//$fname = database/filename // this will be the new filename (in the zip file)				
						
				//copy($file, $defaultsarray['base_path'].'public_html/temp/'.$row['filename']);
								
				$fname = basename($file);
     			// fname is the filename that will be displayed in the zip file when the user opens it, so it should be as human readable as possible
     			if(!$zip->addFile($file, $fname)) 
     			{	//getcwd().$file
     				$msg.="Error adding file ".$fname." to Zip File.  ";
     			}				
			}
			else
			{
				display_xml_response("<rslt>0</rslt><rsltmsg><![CDATA[You have selected an invalid file; zipping file(s) operation has been canceled.]]></rsltmsg>");	
			}				
		}
		$zip->close();
		
		display_xml_response("<rslt>1</rslt><file><![CDATA[$public_filename]]></file><rsltmsg><![CDATA[".$msg."]]></rsltmsg>");	
	}
	
	function load_training_certs() {
		$sql = "
			select *
			
			from attached_files
			where section_id = '".sql_friendly(SECTION_CERTIFICATES)."'
				and deleted = 0
				and xref_id = '".sql_friendly($_POST['user_id'])."'
		";
		$data = simple_query($sql);
		
		$rval = "";
		while($row = mysqli_fetch_array($data)) {
			$rval .= "
				<Entry>
					<Filename><![CDATA[$row[filename]]]></Filename>
					<Date><![CDATA[".date("m/d/Y", strtotime($row['linedate_added']))."]]></Filename>
					<PublicName><![CDATA[$row[public_name]]]></Filename>
				</Entry>
			";
		}
		
		display_xml_response("<rslt>1</rslt>$rval");
	}
	
	function save_sortable() {
		
		if(strtolower($_POST['from_page']) == 'documents.php') {
			if($_POST['column'] == 'move_box_left') {
				$use_column = 'doc_column_left';
			} else {
				$use_column = 'doc_column_right';
			}
		} else {
			if($_POST['column'] == 'move_box_left') {
				$use_column = 'home_column_left';
			} else {
				$use_column = 'home_column_right';
			}
		}
		
		$sql = "
			update users
			set $use_column = '".sql_friendly($_POST['sort_data'])."'
			where id = '".sql_friendly($_SESSION['user_id'])."'
		";
		simple_query($sql);
		
		display_xml_response("<rslt>1</rslt>");
	}
	function remove_logo_list()
	{
		if($_POST['cust_id'] > 0)
		{
			$sql = "
			update attached_files set
				deleted='1' 
			where xref_id='".sql_friendly($_SESSION['selected_merchant_id'])."' 
				and section_id='".SECTION_LOGO_CUST."'
			";
			simple_query($sql);
		}	
		elseif($_POST['store_id'] > 0)
		{
			$sql = "
			update attached_files set
				deleted='1' 
			where xref_id='".sql_friendly($_SESSION['selected_store_id'])."' 
				and section_id='".SECTION_LOGO_STORE."'
			";
			simple_query($sql);
		}
		elseif($_POST['user_id'] > 0)
		{
			$sql = "
			update attached_files set
				deleted='1' 
			where xref_id='".sql_friendly($_SESSION['selected_user_id'])."' 
				and section_id='".SECTION_AVATAR."'
			";
			simple_query($sql);
		}
		display_xml_response("<rslt>1</rslt>");
	}
	
?>