<? include_once("application.php") ?>
<? include_once("functions.php") ?>
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
				
	switch ($_GET['cmd']) {
		
		case 'load_customer':
			load_customer();
			break;
		
		case 'search_users':
			search_users();
			break;		
		case 'search_results':
			search_results();
			break;
		case 'search_results_id':
			search_results_id();
			break;
		
		case 'verify_access_level':
			verify_access_level();
			break;			
		case 'timeout_check':
			timeout_check();
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
		case 'view_attached_file':
			view_attached_file();
			break;
		case 'view_error_file':
			view_error_file();
			break;		
		case 'get_email_view_log':
			get_email_view_log();
			break;
		case 'send_attachment_email':
			send_attachment_email();
			break;			
			
				
		default:
			case_default();
			break;
		
	}
	
	function load_customer()
	{		
		$cust_id = get_cust_id_by_name($_POST['customer_name']);
		
		$sql = "
			select customers.*			
			from customers
			where customers.id = '".sql_friendly($cust_id)."'
		";
		$data_cust = simple_query($sql);
		if(!mysql_num_rows($data_cust)) {
			display_xml_response("<rslt>0</rslt><rsltmsg>Could not locate customer</rsltmsg>");
			return;
		}
		$row_cust = mysql_fetch_array($data_cust);
		
		$return_var = '<rslt>1</rslt>';
		$return_var .= "
			<CompanySearch><![CDATA[$_POST[customer_name]]]></CompanySearch>
			<CompanyName><![CDATA[$row_cust[name_company]]]></CompanyName>
			<CustomerID><![CDATA[$row_cust[id]]]></CustomerID>
		";
		
		display_xml_response($return_var);		
	}
	
	
	function search_users() {
		$sql = "
			select *
			
			from users
			where (name_last like '%".sql_friendly($_GET['q'])."%'
				 or name_first like '%".sql_friendly($_GET['q'])."%')
				and deleted = 0
			order by name_last, name_first
			limit 100
		";
		$data = simple_query($sql);
		
		while($row = mysql_fetch_array($data)) {
			echo "$row[username]|$row[name_first] $row[name_last]\n";
		}
	}
	
	
	
	
	
	function search_results() {
		
		$use_table = sql_friendly($_POST['search_type']);

		if(!isset($_POST['search_limit']) || $_POST['search_limit'] == 0) $_POST['search_limit'] = 100;
				
		$html_msg="<div>Term is ".trim($_POST['customer_name']).".</div>";
		$html="";
		
		$blanker="";			// target='_blank'
		
		if(1==1)
		{						
			/*
			$sql = "
     			select $use_table.*
     			
     			from $use_table, customers
     			where $use_table.customer_id = customers.id
     				".($_POST['customer_name'] != '' ? "and customers.customer_name = '".sql_friendly($_POST['customer_name'])."'" : "")."
     				and customers.deleted = 0
     				and $use_table.deleted = 0
     				
     			order by linedate_added desc
     			limit ".sql_friendly($_POST['search_limit'])."
     		";	
     		$data = simple_query($sql);
     
     		$html = "<table width='90%'>
     			<tr>
     				<td width='15%'><b>$_POST[search_display] Number</b></td>
     				<td width='10%'><b>Local</b></td>
     				<td width='15%'><b>$_POST[search_display] Code</b></td>
     				<td width='30%'><b>$_POST[search_display] Name</b></td>
     				<td width='15%'><b>$_POST[search_display] Date</b></td>     				
     				<td width='15%' align='right'><b>Total</b></td>
     			</tr>
     		";
     		while($row = mysql_fetch_array($data)) {
     			
     			
     			$cv_type='customers';
     			$cv_id=$row['customer_id'];
     			if($use_table =='purchase_order' || $use_table =='inventory_entries')		
     			{	
     				$cv_type='vendors';
     				$cv_id=$row['vendor_id'];	
     			}
     						
				$extra_suffix="";
				if($use_table=='purchase_order')
				{
					$extra_items=mrr_test_if_exta_items_on_po($row['id']);
					if($extra_items > 0)	$extra_suffix="-A";
				}			
     			     			
     			$mrr_names="";		//mrr_get_name_and_code($cv_type,$cv_id);
     			$loc_display="";	//mrr_get_location_display($row['mrr_location_id'],1);
     			
     			$html .= "
     				<tr>
     					<td><a href='$use_table.php?".$use_table."_id=$row[id]'".$blanker.">".$row[$use_table."_number"]."".$extra_suffix."</a></td>
     					<td>TEST</td>
     					<td>TEST</td>
     					<td>TEST</td>
     					<td>".date("m-d-Y", strtotime($row['linedate']))."</td>
     					<td align='right'>".money_format($row['total'])."</td>
     				</tr>
     			";
     		}
     		$html .= "</table>";
     		*/	
		}
		
		$first_result = '';
		if(mysql_num_rows($data)) 
		{
			mysql_data_seek($data, 0);
			$row = mysql_fetch_array($data);
			$first_result = $row['id'];
		}
		
		$return_var = "
			<rslt>1</rslt>
			<rsltmsg></rsltmsg>
			<SearchResultHTML><![CDATA[$html]]></SearchResultHTML>
			<ResultCount>".mysql_num_rows($data)."</ResultCount>
			<FirstResult>$first_result</FirstResult>
		";	
		display_xml_response($return_var);		
	}
	
	function search_results_id() {
		
		$use_table = sql_friendly($_POST['search_type']);
		
		$html_msg="";		
		
		$html_msg="<div><b>".strtolower(trim($use_table))."</b></div>";
		$html="";
		$halt=0;
		$blanker="";		// target='_blank'
		
		if(1==1)
		{			
     		/*
     		$sql = "
     			select $use_table.*,
     				customers.customer_name,
     				customers.name_company
     				".($use_table == 'quote' ? ", invoice_id " : "")."
     			
     			from $use_table
     				left join customers on $use_table.customer_id = customers.id and customers.deleted = 0
     			where $use_table.".$use_table."_number='".sql_friendly($_POST['search_id'])."'
     				and $use_table.deleted = 0
     			order by linedate_added desc
     			limit 400
     		";
		
     		$data = simple_query($sql);
     		
     		$html = "<table width='90%'>
     			<tr>
     				<td><b>$_POST[search_display] Number</b></td>
     				<td><b>Customer ID</b></td>
     				<td><b>Customer Name</b></td>
     				<td><b>$_POST[search_display] Date</b></td>
     				".($use_table == 'quote' ? "<td><b>Converted</b></td>" : "")."
     				<td align='right'><b>Total</b></td>
     			</tr>
     		";
     		$counter = 0;
     		while($row = mysql_fetch_array($data)) 
     		{
     			$counter++;
     			
     			
     			
     			$html .= "
     				<tr class='".($counter % 2 == 1 ? 'even1' : 'odd1')."'>
     					<td><a href='$use_table.php?".$use_table."_id=$row[id]'".$blanker.">".$row[$use_table."_number"]."".$extra_suffix."</a></td>
     					<td>$row[customer_name]</td>
     					<td>$row[name_company]</td>
     					<td>".date("m-d-Y", strtotime($row['linedate']))."</td>
     					".($use_table == 'quote' ? "<td><a href='invoice.php?invoice_id=$row[invoice_id]'".$blanker.">$row[invoice_id]</a></td>" : "")."
     					<td align='right'>".money_format($row['total'])."</td>
     				</tr>
     			";
     		}
     		$html .= "</table>";
     		*/
		}
		
		$first_result = '';
		if(mysql_num_rows($data)) 
		{
			mysql_data_seek($data, 0);
			$row = mysql_fetch_array($data);
			$first_result = $row['id'];
		}
		
		$return_var = "
			<rslt>1</rslt>
			<rsltmsg></rsltmsg>
			<SearchResultHTML><![CDATA[$html]]></SearchResultHTML>
			<ResultCount>".mysql_num_rows($data)."</ResultCount>
			<FirstResult>$first_result</FirstResult>
		";	
		display_xml_response($return_var);		
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
		
		if(!mysql_num_rows($data)) {
			// user not found
			$rsltmsg = "Invalid username or password entered";
		} else {
			$row = mysql_fetch_array($data);
			
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
	
	
	function timeout_check() {
		// since the user could have many pages open, we want to see whichever one was the last one opened, then use that for the timeout check
		
		global $defaultsarray;
		//if($_SERVER['REMOTE_ADDR'] == '69.137.72.167') $defaultsarray['session_timeout'] = 15;
		
		$sql = "
			select now() as currentdt,
				linedate_last_pageload
			
			from users
			where id = '".sql_friendly($_SESSION['user_id'])."'
		";
		$data = simple_query($sql);
		$row = mysql_fetch_array($data);
		
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
		
		if($new_id % 100 == 1) {
			$sql = "
				delete from log_timeout_check
				where linedate_added < '".date("Y-m-d", strtotime("-14 day", time()))."'
			";
			simple_query($sql);
		}
		
		display_xml_response("<rslt>1</rslt><TimeoutBool>$timed_out</TimeoutBool><TimeoutLeft>$timeout_left</TimeoutLeft>");
	}
			
	function display_attachments() {
		global $defaultsarray;
		
		$sql = "
			select *,
				(select count(*) from log_email where log_email.attachment_id = attached_files.id) as send_count
			
			from attached_files
			where deleted = 0
				and section_id = '".sql_friendly($_POST['section_id'])."'
				and xref_id = '".sql_friendly($_POST['xref_id'])."'
				and filesize > 0
				and access_level <= '".$_SESSION['access_level']."'
				and (value2 = '' or value2 is null)
			order by linedate_added desc
		";
		$data = simple_query($sql);		//$defaultsarray['default_attachment_access_level']
		
		echo "
			<table width='100%'>
			<tr>
				<td><b>Filename</b></td>
				<td align='right'><b>Date Uploaded</b></td>
				<td align='right'><b>Times E-Mailed</b></td>
			</tr>
		";
		$mrr_cntr=0;
		while($row = mysql_fetch_array($data)) {
			
			echo "
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
		echo "</table><input type='hidden' id='mrr_attachment_files' name='mrr_attachment_files' value='".$mrr_cntr."'>";
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
				and access_level <= '".$_SESSION['access_level']."'
				and deleted = 0
		";
		$data = simple_query($sql);		//$defaultsarray['default_attachment_access_level']
		
		if(!mysql_num_rows($data)) {
			display_xml_response("<rslt>0</rslt><rsltmsg>Could not locate file</rsltmsg>");
		} else {
			$row = mysql_fetch_array($data);
			
			$uuid = createuuid();
			
			
			
			
			// copy the file to a temp location to view
			$tmp_filename = $uuid.$row['filename'];
			copy($defaultsarray['base_path'].'/files/'.$row['filename'], $defaultsarray['base_path'].'/html/temp/'.$tmp_filename);
			
			
			$tmp_filename=str_replace("#",'%23',$tmp_filename);			//File name is truncated if the '#' letter is found.  'test_file#1234.jpg' becomes 'test_file'.  File won't be found.
			//$tmp_filename=urlencode($tmp_filename);			//File name is truncated if the '#' letter is found.  'test_file#1234.jpg' becomes 'test_file'.  File won't be found by browser.
			
			display_xml_response("<rslt>1</rslt><filename><![CDATA[/temp/$tmp_filename]]></filename>");
		}
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
		
		if(!mysql_num_rows($data)) {
			display_xml_response("<rslt>0</rslt><rsltmsg>Could not locate file</rsltmsg>");
		} else {
			$row = mysql_fetch_array($data);
			
			$uuid = createuuid();
			
			// copy the file to a temp location to view
			$tmp_filename = $uuid.$row['filename'];
			copy(getcwd().'/scanned/working/'.$row['filename'], $defaultsarray['base_path'].'/www/temp/'.$tmp_filename);
			
			display_xml_response("<rslt>1</rslt><filename><![CDATA[temp/$tmp_filename]]></filename>");
		}
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
		$row = mysql_fetch_array($data);
		
		$new_file_full = $defaultsarray['base_path']."/files/".$_POST['new_filename'];
		
		$return_filename = $row['filename']; // or
		$rslt = 0;
		
		if(!file_exists($new_file_full)) {
			
			if(rename($defaultsarray['base_path']."/files/$row[filename]", $new_file_full)) {
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
		
		while($row = mysql_fetch_array($data)) {
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
		$row = mysql_fetch_array($data);
		
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
		
		$attm = $defaultsarray['base_path'].'/files/'.$row['filename'];
		
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
	
		
	
	function case_default()
	{
		display_xml_response("<rslt>1</rslt>");	
	}		
?>