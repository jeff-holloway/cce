<?
	if(!function_exists("money_format")) 
	{
		function money_format($var) 
		{
			$var=str_replace("$","",$var);
			$var=str_replace(",","",$var);
			$var=(float)$var;
			return "$".number_format($var, 2, '.', ',');
		}
	}

	function d($use_string) 
	{
		echo "<pre>";
		print_r($use_string);
		echo "</pre>";
		die;
	}
	
	function get_internal_address() 
	{
		// function to return the site internal address
		// this is needed for items like the PDF creator that references the internal address		
		global $defaultsarray;
		
		$use_address = $_SERVER['HTTP_HOST'];		

		if($defaultsarray['internal_address'] != '') 	$use_address = $defaultsarray['internal_address'];
		
		if(substr($use_address,-1) != '/') 			return $use_address.'/';
		
		return $use_address;
	}	
	
	function duplicate_row($table, $id) 
	{		
		// function to duplicate a row from a table		
		$sql = "
			show columns 
			from $table
		";
		$data_fields = simple_query($sql);
		$row_fields = mysql_fetch_array($data_fields);
		$field_array = array();
		while($row_fields = mysql_fetch_array($data_fields)) 
		{
			//echo $row_fields['Field']."<br>";
			if($row_fields['Field'] != 'id') $field_array[] = $row_fields['Field'];
		}
		$field_list = implode(",", $field_array);
		$sql = "
			insert into $table ($field_list)
			select $field_list from $table i where i.id = '".sql_friendly($id)."'
		";		
		simple_query($sql);				
		return mysql_insert_id();
	}

	function check_access($action_name) 
	{
		// funciton to make sure this user has access to the requested area		
		if(!isset($_SESSION['access_level'])) return false;
		
		$sql = "
			select access_level
			
			from security
			where action = '".sql_friendly($action_name)."'
		";
		$data = simple_query($sql);
		
		if(!mysql_num_rows($data)) return false;
		
		$row = mysql_fetch_array($data);
		if($row['access_level'] <= $_SESSION['access_level']) 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}
	
	function get_option_text_by_id($option_id,$mode=0) 
	{
		$sql = "
			select *
			
			from option_values
			where id = '".sql_friendly($option_id)."'
		";
		$data = simple_query($sql);
		$row = mysql_fetch_array($data);
		$res=$row['fname'];
		if($mode==1)	$res=$row['fvalue'];
		return $res;
	}		
	function build_option_box($option_cat_name, $selected_value = "", $field_name, $show_name = false, $show_blank_text = true) 
	{
		$data = get_options($option_cat_name);
		
		echo "<select name='$field_name' id='$field_name'>";
		$row = mysql_fetch_array($data);
		if($row['blank_text'] != '') 
		{
			echo "<option value='0'>".($show_blank_text ? $row['blank_text'] : "")."</option>";
		}
		mysql_data_seek($data, 0);
		while($row = mysql_fetch_array($data)) 
		{
			$use_selected = "";
			if($row['id'] == $selected_value) $use_selected = "selected";
			$disp_name = "";
			if($show_name) $disp_name = "($row[fname]) ";
			echo "<option $use_selected value='$row[id]'>$disp_name $row[fvalue]</option>";
		}
		echo "</select>";
	} 
	function mrr_build_option_box($option_cat_name, $selected_value = "", $field_name, $show_name = false, $show_blank_text = true, $class="") 
	{
		$data = get_options($option_cat_name);
		$selbx="";
		
		$selbx.="<select name='$field_name' id='$field_name'".$class.">";
		$row = mysql_fetch_array($data);
		if($row['blank_text'] != '') 
		{
			$selbx.="<option value='0'>".($show_blank_text ? $row['blank_text'] : "")."</option>";
		}
		mysql_data_seek($data, 0);
		while($row = mysql_fetch_array($data)) 
		{
			$use_selected = "";
			if($row['id'] == $selected_value) $use_selected = "selected";
			
			$disp_name = "$row[fvalue]";
			if(trim($disp_name)=="" || trim($disp_name)=="New Entry")		$disp_name = "$row[fname]";
			//if($show_name) $disp_name = "($row[fname]) ";
			
			$selbx.="<option $use_selected value='$row[id]'>$disp_name</option>";
		}
		$selbx.="</select>";		
		return $selbx;
	}

	function get_options($option_cat_name,$sort="") 
	{
		$sql = "
			select option_values.*,
				option_cat.blank_text
			
			from option_values, option_cat
			where option_values.cat_id = '".sql_friendly(get_option_cat_id($option_cat_name))."'
				and option_values.deleted = 0
				and option_values.cat_id = option_cat.id

			order by ".$sort."option_values.zorder, option_values.fvalue
		";
		$data = simple_query($sql);
		
		return $data;
	}	
	function get_options_special($option_cat_name,$sort="",$builder_id=0,$cust_id=0) 
	{
		$sql = "
     			select option_values.*,
     				option_cat.blank_text
     			
     			from option_values
     				left join option_cat on option_cat.id = option_values.cat_id
     			where option_values.cat_id = '".sql_friendly(get_option_cat_id($option_cat_name))."'
     				and option_values.deleted = 0
     				
     			order by ".$sort."option_values.zorder, option_values.fvalue
     	";
		
		$data = simple_query($sql);		
		return $data;
	}

		
	function get_option_cat_id($cat_name) 
	{
		$sql = "
			select id
			
			from option_cat
			where cat_name = '".sql_friendly($cat_name)."'
				and deleted = 0
		";
		$data = simple_query($sql);
		$row = mysql_fetch_array($data);
		return $row['id'];
	}

	function checkbox_value($field_name) 
	{		
		// takes the name of an input checkbox and returns 1 if it exists, or 0 if it doesn't		
		if(isset($_POST[$field_name])) 		return "1";
		
		return "0";
	}

	function log_alert($user_id, $msg) 
	{
		$sql = "
			insert into user_alerts
				(user_id,
				linedate_added,
				alert_msg)
				
			values ('".sql_friendly($user_id)."',
				now(),
				'".sql_friendly($msg)."')
		";
		simple_query($sql);
	}
	
	function simple_query($sql) 
	{
		global $datasource;
		global $debug_mode;
		
		if($debug_mode) 
		{
			$data = mysql_query($sql,$datasource) or die("database query failed! <br>". mysql_error() . "<pre>". $sql ."</pre>");
		} 
		else 
		{
			$data = mysql_query($sql,$datasource) or die("General Error Occurred...");
		}		
		return $data;
	}
	
	function sql_friendly($istring) 
	{		
		if(get_magic_quotes_gpc()) 
		{
			$hold = stripslashes($istring);
		} 
		else 
		{
			$hold = $istring;
		}		
		return mysql_real_escape_string($hold);
	}
	
	function query_string_remove($query_string,$removestring) 
	{
		$uQueryString = explode("&",$query_string);
		$query_string = '';
	
		foreach($uQueryString as $uVar)
		{
			if(preg_match("[^". $removestring ."]",$uVar) == 0) 		$query_string .= "&".$uVar;				
		}	
		return substr($query_string,1);	
	}
	
     function sort_fields($default_sort_field = "id", $force_update = false) 
     {
     	//this function will create the session variable for the page that holds how we are sorting, and which direction (i.e. ascending, or descending) 
     	global $SCRIPT_NAME;
     	global $page_name;
     
     	$page_name = substr($SCRIPT_NAME,strpos($SCRIPT_NAME,"/") + 1);
     	$page_name = substr($page_name,0,strpos($page_name,"."));
     
     	if(!isset($_SESSION['sort_field_'.$page_name]) || $force_update) 
     	{
     		$_SESSION['sort_field_'.$page_name] = $default_sort_field;
     		$_SESSION['sort_field_direction_'.$page_name] = "desc";
     	}
     	
     	if(isset($_GET['sort_field'])) 
     	{
     		if($_SESSION['sort_field_'.$page_name] == $_GET['sort_field']) 
     		{
     			// switch sort direction 
     			if($_SESSION['sort_field_direction_'.$page_name] == "asc") 
     			{
     				$_SESSION['sort_field_direction_'.$page_name] = "desc";
     			} 
     			else 
     			{
     				$_SESSION['sort_field_direction_'.$page_name] = "asc";
     			}     
     		} 
     		else 
     		{     			
     			$_SESSION['sort_field_direction_'.$page_name] = "asc";
     			$_SESSION['sort_field_'.$page_name] = $_GET['sort_field'];
     		}
     	}
     }

	function data_nav_bar($data_list, $record_start = 0,$records_per_page = 20) 
	{
		//function to display the [previous] [next], page range, and search box on our data view pages	
		global $query_string;
		
		if(!isset($_SESSION['results_per_page'])) 
		{
			$_SESSION['results_per_page'] = $records_per_page;
		}
		
		$use_query_string = query_string_remove($query_string, "eid");
		$use_query_string = query_string_remove($use_query_string, "id");
		$use_query_string = query_string_remove($use_query_string, "search");
		$use_query_string = query_string_remove($use_query_string, "results_per_page");
			
		if(isset($_POST['sbox']) && $_POST['sbox'] != "") 
		{
			$extra_search = "&search=$_POST[sbox]";
		} 
		else 
		{
			$extra_search = "";
		}
		
		/* calculate our top record for this set */
		$top_record = $record_start + $_SESSION['results_per_page'];
		if($top_record > mysql_num_rows($data_list)) $top_record = mysql_num_rows($data_list);
	?>
	<table class='admin_menu' width='100%'>
		<tr>
			<form action='?<?=$use_query_string?>' method='post'>
			<td width='1%'>
				&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				<b>&nbsp;&nbsp;&nbsp;<?=mysql_num_rows($data_list)?> record(s) |  viewing records <?=$record_start + 1?> - <?=$top_record?></b><br>
				<?
				if($record_start > 0) 
				{
					$prev_no = $record_start - $_SESSION['results_per_page'];
					if($prev_no < 0) $prev_no = 0;
					echo "<a href='?$use_query_string&results_per_page=$_SESSION[results_per_page]&start_row=".$prev_no."$extra_search'>[Previous]</a>";
				} 
				else 
				{
					echo "[Previous]";
				}
				echo "&nbsp;&nbsp;&nbsp;";
				if(($record_start + $_SESSION['results_per_page']) < mysql_num_rows($data_list)) 
				{
					echo "<a href='?$use_query_string&results_per_page=$_SESSION[results_per_page]&start_row=".($record_start + $_SESSION['results_per_page'])."$extra_search'>[Next]</a>";
				} 
				else 
				{
					echo "[Next]";
				}
				?>				
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<b>Page:</b> 
				<?
					// only show a max of 15 page links, first 5, mid 5, last 5
					$max_links = 15;
					$links_per_section = 5;
					$page_count = mysql_num_rows($data_list) / $_SESSION['results_per_page'];
					$page_middle = $page_count / 2;
					$max_dots = 5;
					$page_start = $record_start / $_SESSION['results_per_page'];
					
					for($i=0;$i < $page_count;$i++) 
					{
						if($i < $links_per_section 
							|| ($i >= $page_middle && $i < ($page_middle + $links_per_section)) 
							|| $i > ($page_count - $links_per_section)
							|| ($i >= ($page_start - $links_per_section) && $i < ($page_start + $links_per_section))
							) 
						{
							echo "<a href='?$use_query_string&results_per_page=$_SESSION[results_per_page]&start_row=".($i*$_SESSION['results_per_page'])."$extra_search'>[".($i + 1)."]</a> ";
							$dot_counter = 0;
						} 
						else 
						{
							if($dot_counter < $max_dots) 
							{
								$dot_counter ++;
								echo " . ";
							}
						}
					}
				?>
				<br>
				<b>Search:</b>
				<input name="sbox" class='standard12'>
				<input type='submit' value='Go'>
				<?
					if(isset($_POST['sbox']) && $_POST['sbox'] != "") 
					{
						echo "<br><b>Current Search Term: '$_POST[sbox]'";
					}
				?>
			</td>
			</form>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan='5'>
				<b>Records per page: </b>
				<a href='?results_per_page=20&<?=$use_query_string?><?=$extra_search?>'>[20]</a>
				<a href='?results_per_page=50&<?=$use_query_string?><?=$extra_search?>'>[50]</a>
				<a href='?results_per_page=100&<?=$use_query_string?><?=$extra_search?>'>[100]</a>
				<a href='?results_per_page=200&<?=$use_query_string?><?=$extra_search?>'>[200]</a>
				<a href='?results_per_page=99999&<?=$use_query_string?><?=$extra_search?>'>[all]</a>
			</td>
		</tr>
		</table>
<? 	}
	
	function confirm_secure_page() 
	{
		/* this function makes sure that the user is on a secure page (i.e. https://)   */
		global $SCRIPT_NAME;
		global $query_string;
		global $defaultsarray;
		
		if(stripos($_SERVER['SERVER_NAME'], "backup.") !== false) 
		{
			// if we're in the 'backup' domain portion of the site, then we don't have an SSL for it, so bypass the SSL check
			return;
		}		
		
		if($_SERVER['SERVER_PORT'] != '443' && substr(strtolower($defaultsarray['secure_site']),0,5) == 'https') 
		{
			$use_qstring = "";
			if(strlen($query_string) > 0) 					$use_qstring = "?$query_string";
			$use_sstring = "";
			if($SCRIPT_NAME != "/" && $SCRIPT_NAME != "./") 		$use_sstring = substr($SCRIPT_NAME, strrpos($SCRIPT_NAME, "/"));
			if(substr($use_sstring, 0, 1) == "/") 				$use_sstring = substr($use_sstring,1);
			
			javascript_redirect($defaultsarray['secure_site'].$use_sstring.$use_qstring);			
		}
	}	
	function javascript_redirect($use_url) 
	{
		?>
		<script language="javascript">
			window.location = "<?=$use_url?>";
		</script>
		<?
	}	
	function display_xml_response($use_response) 
	{		
		//removed the XML declaration from first line...was causing error in LINUX?
		$return_var = "<response>
				$use_response
			</response>
		";
		header('Content-Type: text/xml');		
		echo $return_var;
	}
	
	//files
	function createuuid() 
	{
		return md5(uniqid(rand(), true));
	}	
	function get_unique_filename($dir, $file) 
	{
		// check to see if the file exists, if so, loop through until we get a unique filename		
		$file_ext = get_file_ext($file);
		$file_base = str_replace(".$file_ext","",$file);
		
		if(!file_exists($dir.$file)) 
		{
			$new_filename = $file;
		} 
		else 
		{
			for($i=1;$i<99999;$i++) 
			{
				$new_filename = $file_base."_".$i.".$file_ext";
				if(!file_exists($dir.$new_filename)) break;
			}
		}		
		return $new_filename;
	}
	function get_filename($full_file) 
	{
		$full_file = str_replace("\\","/",$full_file);
		return substr($full_file, -(strlen($full_file) - strrpos($full_file, "/") - 1));
	}	
	function get_file_ext($file) 
	{
		$file_ext = '';		
		if(strpos($file, ".") !== false) 
		{
			$file_parts = explode(".", $file);
			$file_ext = $file_parts[count($file_parts)-1];			
		}		
		return $file_ext;
	}
	
	//users
	function mrr_get_user_email_address()
	{
		$email="";
		$sql = "
			select email			
			from users
			where id = '".sql_friendly($_SESSION['user_id'])."'
		";	
		$data = simple_query($sql);
		while($row = mysql_fetch_array($data))
		{
			$email=trim($row['email']);
		}
		return $email;	
	}
	function mrr_get_user_email_names($id=0,$cd=0)
	{
		$fullname="";
		$sql = "
			select name_first,name_last			
			from users
			where id = '".sql_friendly($_SESSION['user_id'])."'
		";
		
		if($id>0 || $cd>0)
		{
			$sql = "
				select name_first,name_last			
				from users
				where id = '".sql_friendly($id)."'
			";		
		}
			
		$data = simple_query($sql);
		while($row = mysql_fetch_array($data))
		{
			$fullname=trim($row['name_first'])." ".trim($row['name_last']);
		}
		return $fullname;	
	}
	
	function mrr_get_default_trusted_email()
	{
		global $defaultsarray;		
		return $defaultsarray['emails_from'];	
	}
	function sendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles,$faxcode=0,$replyName='',$replyAddr='') 
	{
		/* mail using PHPMailer */
		global $defaultsarray;
		
		if($defaultsarray['disable_email'] == 'TRUE') 
		{
			// e-mails turned off by global settings
			return;
		}

		$email_notes = "";
		$email_xref_id = 0;
		$email_section_id = 0;
		$email_attachment_id = 0;
		if(isset($_POST['email_notes'])) 			$email_notes = $_POST['email_notes'];
		if(isset($_POST['email_xref_id'])) 		$email_xref_id = $_POST['email_xref_id'];
		if(isset($_POST['email_section_id'])) 		$email_section_id = $_POST['email_section_id'];
		if(isset($_POST['email_attachment_id'])) 	$email_attachment_id = $_POST['email_attachment_id'];
		
		$mail_uuid = createuuid();
		
		if($email_notes != '') 
		{
			$Html .= "<br><bb>Notes: $email_notes";
		}
				
		$mail = new PHPMailer();
		
		$mail->IsSMTP(); 							// telling the class to use SMTP
		if($defaultsarray['smtp_host'] != '') 
		{
			$mail->Host = $defaultsarray['smtp_host']; 	// SMTP server
			if($defaultsarray['smtp_username'] != '') 			
			{
				$mail->SMTPAuth = true;
				$mail->Username = $defaultsarray['smtp_username'];
				$mail->Password = $defaultsarray['smtp_password'];
			}
		}
		
		if($faxcode==1)
		{
			$From=mrr_get_default_trusted_email();
			$FromName=$From;
			$mail->AddReplyTo($From, $FromName);	
		}
		else
		{
			$mrr_email=$replyAddr;
			$mrr_names=$replyName;
			if(trim($mrr_names)=="")		$mrr_names=$FromName;
			if(trim($mrr_email)=="")		$mrr_email=$From;
			$mail->AddReplyTo($mrr_email, $mrr_names);	
		}
		
		$mail->From = $From;
		$mail->FromName = $FromName;		
		
		// add support to process multiple e-mail addresses CSV
		$to_array = explode(",",$To);
		
		foreach($to_array as $value) 
		{
			$mail->AddAddress($value);
		}
		
		if($AttmFiles != '') 
		{
			// rather than send the actual attachment, we're now sending a link to the attachment so we can
			// track when it's been opened
			
			$Html .= "
				<br><br><a href='$defaultsarray[default_sent_link_url]/view_attachment.php?id=$mail_uuid'>Click here</a> to view your attachment.
				<br><br>
				Or copy and paste this link into your browser:<br><br>
				$defaultsarray[default_sent_link_url]/view_attachment.php?id=$mail_uuid
			";
			$Text .= "
			
			You can view your attachment at the following address: 
			
			$defaultsarray[default_sent_link_url]/view_attachment.php?id=$mail_uuid
			
			";
			//$mail->AddAttachment($AttmFiles);
		}
		
		$mail->AltBody = $Text;		
		$mail->Subject = $Subject;
		$mail->Body = $Html;
		$mail->WordWrap = 50;
		
		if(!$mail->Send())
		{
		   echo 'Message was not sent.';
		   echo 'Mailer error: ' . $mail->ErrorInfo;
		   
		   $mail_error = $mail->ErrorInfo;		   
		}
		else
		{
			$mail_error = "";
		   //echo 'Message has been sent.';
		}
		
		$sql = "
			insert into log_email
				(email_from,
				email_to,
				subject,
				attachment,
				linedate_sent,
				sent_by_user_id,
				mail_error,
				uuid,
				email_notes,
				xref_id,
				section_id,
				attachment_id,
				location_id)
				
			values ('".sql_friendly($From)."',
				'".sql_friendly($To)."',
				'".sql_friendly($Subject)."',
				'".sql_friendly($AttmFiles)."',
				now(),
				'".sql_friendly($_SESSION['user_id'])."',
				'".sql_friendly($mail_error)."',
				'".sql_friendly($mail_uuid)."',
				'".sql_friendly($email_notes)."',
				'".sql_friendly($email_xref_id)."',
				'".sql_friendly($email_section_id)."',
				'".sql_friendly($email_attachment_id)."',
				'".sql_friendly($_SESSION['location_id'])."')
		";
		simple_query($sql);		
	}
	
	
		
	function get_user_guid($user_id) 
	{
		$sql = "
			select guid			
			from users
			where deleted = 0
				and id = '".sql_friendly($user_id)."'
		";
		$data = simple_query($sql);
		
		if(!mysql_num_rows($data)) 
		{
			return false;
		} 
		else 
		{
			$row = mysql_fetch_array($data);
			return $row['guid'];
		}
	}	
	function get_user_id_by_name($username) 
	{
		$sql = "
			select id			
			from users
			where username = '".sql_friendly($username)."'
				and deleted = 0
		";
		$data = simple_query($sql);		
		if(!mysql_num_rows($data)) 
		{
			return 0;
		} 
		else 
		{
			$row = mysql_fetch_array($data);
			return $row['id'];
		}
	}	
	
	function print_contents($use_filename = '', $html = '', $display_mode = 0, $header = "", $footer = "", $show_page_numbers = true) 
	{
		// takes a couple POST parameters, writes them to a PDF file, then displays the PDF
		// display_mode: 0 = portrait, 1 = landscape
				
	     //generate pdf content and write to html file to conver to pdf
          
          if($html == '' && isset($_POST['use_html'])) 	$html = $_POST['use_html'];
          
          if($use_filename == '') 		$use_filename = $_POST['use_filename'];
          
		return print_contents_include($use_filename, $html, $display_mode, $header, $footer, $show_page_numbers);		
	}
	
	function print_contents_include($use_filename, $html, $display_mode, $header = "", $footer = "", $show_page_numbers = true) 
	{
		//new version of PDF generator...		
		$html_blob="".$header."".$html."".$footer."";
		
		$res=generate_pdf_remotely($use_filename,$html_blob,1,"","",$show_page_numbers);		//0= do not download... 1= download.
		
	   	return "./temp/".$use_filename . ".pdf";
	}
	

	function money_strip($amount) 
	{
		// function to strip "$" and "," from currency fields
		$temp_money = str_replace("%","",str_replace(",","",str_replace("$", "", $amount)));
		if($temp_money == '') $temp_money = 0;
		return $temp_money;
	}	
	
	/*
	function get_order_prefix() 
	{
		// return the prefix for the order (i.e. "TSO1001-", etc...)
		
		$sql = "
			select order_prefix
			
			from locations
			where id = '".sql_friendly($_SESSION['location_id'])."'
		";
		$data_prefix = simple_query($sql);
		$row_prefix = mysql_fetch_array($data_prefix);	
		
		return $row_prefix['order_prefix'];
	}
	
	function mrr_get_name_and_code($cv_type='',$cv_id=0)
	{
		$res['code']='';	
		$res['name']='';
		return $res;
	}
	*/
	
	function handle_quick_dates($use_all=0) 
	{
		global $quick_date_range_array;
		
		if(!isset($_POST['date_from'])) 					$_POST['date_from'] = '';
		if(!isset($_POST['date_to'])) 					$_POST['date_to'] = '';
		if(!isset($_POST['limit_records_to'])) 				$_POST['limit_records_to'] = 2000;
		if(!isset($_POST['report_filter_customer_name'])) 	$_POST['report_filter_customer_name'] = "";					
		if(!isset($_POST['report_filter_user'])) 			$_POST['report_filter_user'] = "";
		if(!isset($_POST['report_section_id'])) 			$_POST['report_section_id'] = "";
		if(!isset($_POST['report_generic_string'])) 			$_POST['report_generic_string'] = "";
		if(!isset($_POST['email_log_unopened']))			$_POST['email_log_unopened']=0;				
		if(!isset($_POST['report_font_size']))				$_POST['report_font_size']=0;		
			
		if($use_all==1)
		{
			if(!isset($_POST['quick_date_range'])) 
			{
     			$_POST['quick_date_range'] = 'all';
     			$_POST['quick_date'] = 1;
     		}	
		}
		else
		{
     		if(!isset($_POST['quick_date_range'])) 
     		{
     			$_POST['quick_date_range'] = 'today';
     			$_POST['quick_date'] = 1;
     		}	
		}
				
		$quick_date_range_array = array('all'=>'All',
								'today'=>'Today',
								'yesterday'=>'Yesterday',
								'tomorrow'=>'Tomorrow',
								'thisweek'=>'This Week',
								'thismonth'=>'This Month',
								'thisyear'=>'This Year',
								'lastweek'=>'Last Week',
								'lastmonth'=>'Last Month',
								'lastyear'=>'Last Year');

		
		if(isset($_POST['quick_date']) && !isset($_GET['use_date'])) 
		{
			if($_POST['quick_date_range'] == 'all') {
				$_POST['date_from'] = date("01/01/2000", time());
				$_POST['date_to'] = date("n/j/Y", time());
			} elseif($_POST['quick_date_range'] == 'today') {
				$_POST['date_from'] = date("n/j/Y", time());
				$_POST['date_to'] = date("n/j/Y", time());
			} elseif($_POST['quick_date_range'] == 'thisweek') {
				$_POST['date_from'] = date("n/j/Y", strtotime("-".(date('w',time()))." day", time()));
				$_POST['date_to'] = date("n/j/Y", time());
			} elseif($_POST['quick_date_range'] == 'thismonth') {
				$_POST['date_from'] = date("n/j/Y", strtotime("-".(date('j',time()) - 1)." day", time()));
				$_POST['date_to'] = date("n/j/Y", time());
			} elseif($_POST['quick_date_range'] == 'thisyear') {
				$_POST['date_from'] = date("n/j/Y", strtotime("-".(date('z',time()))." day", time()));
				$_POST['date_to'] = date("n/j/Y", time());
			} elseif($_POST['quick_date_range'] == 'yesterday') {
				$_POST['date_from'] = date("n/j/Y", strtotime("-1 day", time()));
				$_POST['date_to'] = date("n/j/Y", strtotime("-1 day", time()));
			} elseif($_POST['quick_date_range'] == 'tomorrow') {
				$_POST['date_from'] = date("n/j/Y", strtotime("+1 day", time()));
				$_POST['date_to'] = date("n/j/Y", strtotime("+1 day", time()));
			} elseif($_POST['quick_date_range'] == 'lastweek') {
				$last_week_base = strtotime("-7 day", time());
				$_POST['date_from'] = date("n/j/Y",  strtotime("-".(date('w',$last_week_base))." day", $last_week_base));
				$_POST['date_to'] = date("n/j/Y", strtotime("+".(7 - date('w',$last_week_base) - 1)." day", $last_week_base));
			} elseif($_POST['quick_date_range'] == 'lastmonth') {
				$last_week_base = strtotime("-1 month", time());
				$_POST['date_from'] = date("n/j/Y",  strtotime("-".(date('j',$last_week_base)-1)." day", $last_week_base));
				$_POST['date_to'] = date("n/j/Y", strtotime("+".(date("t", $last_week_base) - date('j',$last_week_base))." day", $last_week_base));
			} elseif($_POST['quick_date_range'] == 'lastyear') {
				$last_week_base = strtotime("-7 day", time());
				$_POST['date_from'] = date("n/j/Y",  strtotime("-".(date('w',$last_week_base))." day", $last_week_base));
				$_POST['date_to'] = date("n/j/Y", strtotime("+".(7 - date('w',$last_week_base) - 1)." day", $last_week_base));
			}						
		}
	}
	
	
	class report_filter {
				
		var $show_cust_filter 		= false;
		var $show_user_filter		= false;
		var $show_date_range 		= true;
		var $show_generic_string 	= false;
		var $show_row_limit 		= false;
		var $show_print			= false;
		var $leave_form_open 		= false;
		var $show_sections			= false;
		var $email_log_unopened		= false;
		var $show_font_size			= false;
		
		function show_filter() 
		{			
			global $use_title;
			global $quick_date_range_array;
			global $section_id_array;
			global $defaultsarray;
											
			?>
			<form action='' method='post' onsubmit="if(!CheckSubmitFilter()) return false">
			<input type='hidden' name='build_report' value='1'>
			<table class='input_area mrr_no_print'>
			<tr>
				<td colspan='5' nowrap><div class='heading'><?=$use_title ?></div></td>
			</tr>
			<? if($this->show_date_range) { ?>
				<tr>
					<td><span class='highlight'>Quick Date Range</span></td>
					<td>
						<select name='quick_date_range'>
							<? 
							foreach($quick_date_range_array as $value => $key) {
								echo "<option value='$value' ".($value == $_POST['quick_date_range'] ? 'selected' : '').">$key</option>";
							}
							?>
						</select>
					</td>
					<td colspan='2'></td>
					<td><input type='submit' name='quick_date' value='Build Quick Date Range'></td>
				</tr>
				<tr>
					<td colspan='2'><span class='highlight'>Custom Date Range</span></td>
				</tr>
				<tr>
					<td style='width:120px'>Date From</td>
					<td style='width:100px'><input name='date_from' id='date_from' value='<?=$_POST['date_from']?>' class='veryshort'></td>
				
					<td align='right'>Date To</td>
					<td><input name='date_to' id='date_to' value='<?=$_POST['date_to']?>' class='veryshort'></td>
					<td><input type='submit' name='custom_date' value='Build Custom Date Range'></td>
				</tr>
				<tr>
					<td colspan='8'>
						<hr>
					</td>
				</tr>
			<? } ?>			
			<? if($this->show_user_filter) { ?>
				<tr>
					<td>User</td>
					<td colspan='4'><input name='report_filter_user' id='report_filter_user' value="<?=$_POST['report_filter_user']?>"></td>
				</tr>
			<? } ?>				
			<? if($this->show_cust_filter) { ?>				
				<tr>
					<td>Customer</td>
					<td colspan='4'><input name='report_filter_customer_name' id='report_filter_customer_name' value="<?=$_POST['report_filter_customer_name']?>"></td>
				</tr>
			<? } ?>	
			<? if($this->show_generic_string) { ?>
				<tr>
					<td>Search</td>
					<td colspan='4'><input name='report_generic_string' id='report_generic_string' value="<?=$_POST['report_generic_string']?>" style='width:250px'></td>
				</tr>
			<? } ?>
			<? if($this->show_row_limit) { ?>
				<?
					$limit_array = array(10,50,100,250,500,750,1000,2000,3000,4000,5000,6000,7000,8000,9000,10000);
				?>
				<tr>
					<td>Limit results to</td>
					<td colspan='4'>
						<select name='limit_records_to' id='limit_records_to'>
							<? foreach($limit_array as $value) { 
								echo "<option value='$value' ".($value == $_POST['limit_records_to'] ? "selected" : "").">$value</option>";
							}?>
						</select>
					</td>
				</tr>
			<? } ?>			
			<? if($this->show_sections) { ?>
				<tr>
					<td>Section</td>
					<td colspan='4'>
						<select name='report_section_id' id='report_section_id'>
							<option value='0'>All Sections</option>
							<? foreach($section_id_array as $key => $value) { 
								echo "<option value='$key' ".($key == $_POST['report_section_id'] ? "selected" : "").">$value</option>";
							}?>
						</select>
					</td>
				</tr>
			<? } ?>			
			<? if($this->show_print && isset($_POST['build_report'])) { ?>
				<tr>
					<td>
						<input type='button' value='Print Report' id='report_filter_print' onclick='print_report()'>
						<img src='images/loader.gif' id='report_filter_loader' style='display:none'>
					</td>
				</tr>
			<? } ?>			
			<? if($this->email_log_unopened) { ?>
				<tr>
					<td><label for='email_log_unopened' id='mrr_report_email_log_unopened'>Show Unopened Only</label></td>
					<td><input type='checkbox' name='email_log_unopened' id='email_log_unopened' value='1' <?=((isset($_POST['email_log_unopened']) && $_POST['email_log_unopened'] > 0 ) ? 'checked' : '')?>></td>
				</tr>
			<? } ?>			
			<? if($this->show_font_size) { ?> 
				<tr>
					<td>Display Font Size</td>
					<td colspan='4'>
						<select name='report_font_size' id='report_font_size'>
							<?
							if($_POST['report_font_size']==0)				$_POST['report_font_size']=$defaultsarray['default_report_font_size'];
							for($x=10; $x < 19; $x++)
							{
								$sel="";
								if($_POST['report_font_size'] == $x)		$sel=" selected";
								
								echo "<option value='".$x."'".$sel.">".$x."</option>";
							}
							?>	
						</select>
					</td>
				</tr>
			<? } ?>						
			<? if(!$this->show_date_range) { ?>
				<tr>
					<td></td>
					<td><input type='submit' value='Submit'></td>
				</tr>
			<? } ?>
			</table>
			<? if(!$this->leave_form_open) { ?>
				</form>
			<? } ?>			
			<script type='text/javascript'>
				$().ready(function() {
					$('#report_filter_customer_name').autocomplete('ajax.php?cmd=search_customers',{formatItem:formatItem});
					$('#date_from').datepicker();
					$('#date_to').datepicker();
				});				
			</script>
			<?
		}
	}
	
	function show_report_filter($show_cust_filter = false,$show_date_range = true) 
	{
		global $quick_date_range_array;
		global $use_title;
		
		if(!isset($_POST['report_filter_customer_name'])) 	$_POST['report_filter_customer_name'] = "";
		?>
		<form action='' method='post'>
		<input type='hidden' name='build_report' value='1'>
		<table class='input_area2'>
		<tr>
			<td colspan='5'><div class='heading'><?=$use_title ?></div></td>
		</tr>
		<? if($show_date_range) { ?>
			<tr>
				<td><span class='highlight'>Quick Date Range</span></td>
				<td>
					<select name='quick_date_range'>
						<? 
						foreach($quick_date_range_array as $value => $key) {
							echo "<option value='$value' ".($value == $_POST['quick_date_range'] ? 'selected' : '').">$key</option>";
						}
						?>
					</select>
				</td>
				<td colspan='2'></td>
				<td><input type='submit' name='quick_date' value='Build Quick Date Range'></td>
			</tr>
			<tr>
				<td colspan='2'><span class='highlight'>Custom Date Range</span></td>
			</tr>
			<tr>
				<td style='width:120px'>Date From</td>
				<td style='width:100px'><input name='date_from' id='date_from' value='<?=$_POST['date_from']?>' class='veryshort'></td>
			
				<td align='right'>Date To</td>
				<td><input name='date_to' id='date_to' value='<?=$_POST['date_to']?>' class='veryshort'></td>
				<td><input type='submit' name='custom_date' value='Build Custom Date Range'></td>
			</tr>
			<tr>
				<td colspan='8'>
					<hr>
				</td>
			</tr>
		<? } ?>
		
		<? if($show_cust_filter) { ?>
			<tr>
				<td>Customer Fitler</td>
				<td colspan='4'><input name='report_filter_customer_name' id='report_filter_customer_name' value="<?=$_POST['report_filter_customer_name']?>"></td>
			</tr>
		<? } ?>
		</table>
		</form>
		
		<script type='text/javascript'>
			$().ready(function() {
				$('#report_filter_customer_name').autocomplete('ajax.php?cmd=search_customers',{formatItem:formatItem});
				$('#date_from').datepicker();
				$('#date_to').datepicker();
			});
		</script>
	<? }
		
	
	function get_type_of_item_by_name($type_name) 
	{		
		// gets the item type ID from the item name
		// example, request the item type ID for 'inventory'		
		$sql = "
			select id
			
			from option_values
			where cat_id = '".get_option_cat_id('item_type')."'
				and fname = '".sql_friendly($type_name)."'
		";
		$data = simple_query($sql);
		$row = mysql_fetch_array($data);
		return $row['id'];
	}
	
	function log_page_load() 
	{
		if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) 
		{
			$sql = "
				select linedate_last_pageload
				
				from users
				where id = '".sql_friendly($_SESSION['user_id'])."'
			";
			$data_last = simple_query($sql);
			$row_last = mysql_fetch_array($data_last);
			
			$date_last_pageload = date("Y-m-d H:i:s", strtotime($row_last['linedate_last_pageload']));
		} 
		else 
		{
			$date_last_pageload = '0000-00-00';
		}
		
		// log this page load
		$sql = "
			insert into log_page_loads
				(linedate_added,
				user_id,
				page_name,
				query_string,
				remote_ip,
				linedate_prior_page_load)
				
			values (now(),
				'".(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "0")."',
				'".sql_friendly($_SERVER['SCRIPT_NAME'])."',
				'".sql_friendly($_SERVER['QUERY_STRING'])."',
				'".sql_friendly($_SERVER['REMOTE_ADDR'])."',
				'$date_last_pageload')
		";
		simple_query($sql);
	}
	
	
	function verify_index($table_name, $index_name) 
	{
		// function to make sure an index exists on a table 
		// returns true if it does, false if it doesn't		
		$sql = "show index from $table_name";
		$data = simple_query($sql);
		
		while($row = mysql_fetch_array($data)) 
		{
			if(strtolower($row['Key_name']) == strtolower($index_name)) return true;
		}
		return false;
	}
	
	function get_menu_id($menu_name) 
	{
		$id=0;
		$sql = "
			select id
			
			from menu
			where menu_name = '".sql_friendly($menu_name)."'
		";
		$data = simple_query($sql);
		if($row = mysql_fetch_array($data))
		{
			$id=$row['id'];	
		}
		
		return $id;
	}
	
	function mysql_user_function_exists($function_name) 
	{
		// check to see if a user defined function in mysql already exists		
		global $db_name;
		
		$sql = "SELECT * FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE='function' AND routine_name = '".sql_friendly($function_name)."' AND ROUTINE_SCHEMA='".sql_friendly($db_name)."'";
		$data = simple_query($sql);
		
		if(mysql_num_rows($data)) {
			return true;
		} else {
			return false;
		}
	}
	
	//binary switch form element
	function mrr_select_binary($field, $pre=0)
	{
		$selbx="<select name='".$field."' id='".$field."'>";
		
		$sel="";		if($pre==0)		$sel=" selected";
		$selbx.="<option value='0'".$sel.">No</option>";
		
		$sel="";		if($pre==1)		$sel=" selected";
		$selbx.="<option value='1'".$sel.">Yes</option>";
		
     	$selbx.="<select>";
     	return $selbx;
	}
	function mrr_select_toggle_3way($field, $pre=0)
	{
		$selbx="<select name='".$field."' id='".$field."'>";
		
		$sel="";		if($pre==0)		$sel=" selected";
		$selbx.="<option value='0'".$sel.">No</option>";
		
		$sel="";		if($pre==1)		$sel=" selected";
		$selbx.="<option value='1'".$sel.">Yes</option>";
		
		$sel="";		if($pre==2)		$sel=" selected";
		$selbx.="<option value='2'".$sel.">N/A</option>";
		
     	$selbx.="<select>";
     	return $selbx;
	}
	
	
	/*
	function download_scanned_files() {
		// since the scanner can't upload to SFTP, we have the scanner uploading to a holding FTP server (that uses plain FTP)
		// this routine checks that server for new files on a schedule, downloads them, and removes them from the holding FTP site
		global $defaultsarray;
	
		
		$curl = curl_init();
		
		$ftp_server = $defaultsarray['scanner_ftp_site'];
		$ftp_user_name = $defaultsarray['scanner_ftp_username'];
		$ftp_user_pass = $defaultsarray['scanner_ftp_password'];
		
		curl_setopt($curl, CURLOPT_URL, $ftp_server);
		curl_setopt($curl, CURLOPT_FTPLISTONLY, 1);
		curl_setopt($curl, CURLOPT_USERPWD, "$ftp_user_name:$ftp_user_pass");
		//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		//curl_setopt($curl, CURLOPT_FTP_SSL, CURLFTPSSL_ALL);
		//curl_setopt($curl, CURLOPT_FTPSSLAUTH, CURLFTPAUTH_TLS);
		//curl_setopt($curl, CURLOPT_SSLVERSION, 3);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		
		$result = curl_exec($curl);
		
		$error_no = curl_errno($curl);
		
		
		if ($error_no == 0) {
		     echo 'Connection made<br>';
		     
		     $result = str_replace(chr(13),"",$result);
		     $file_array = explode(chr(10),$result);
			
			$use_path = $defaultsarray['base_path']."html/scanned/";
		     
		     //echo "File(s) found: " . count($file_array)."<br>";
		     
		     foreach($file_array as $file) {
		     	// only download the 'outgoing' files (which means "outgoing" from comdata, so they're coming to us)
		     	if(strlen($file) > 0) {
		     		
		     		echo "File: $file (".strlen($file).")";
		     		
		     		$new_file = str_replace("//", "/", "$use_path/$file");
		     		
					$dest_file = fopen($new_file, "w");
					curl_setopt($curl, CURLOPT_FTPLISTONLY, 0);
					curl_setopt($curl, CURLOPT_URL, $ftp_server."/$file");
					curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
					curl_setopt($curl, CURLOPT_FILE, $dest_file);
					$result = curl_exec($curl);
					
					if($result) {
						echo "success";
						curl_setopt($curl, CURLOPT_URL, $ftp_server);
						curl_setopt($curl, CURLOPT_QUOTE, array('DELE /' . $file));
						$result = curl_exec($curl);
					} else {
						echo "error";
					}
					
					fclose($dest_file);
					
					echo "<br>";
										
					//echo "($new_file)";
					
		     	} else {
		     		"Invalid file: ($file)<br>";
		     	}
		     	
		     }
		} else {
			echo curl_error($curl);
		     echo 'Connection failed: ' . $error_no;
		}
		
		// list our files
		
		//echo "<br><br>";
		//var_dump($result);
		//echo "<hr>";
		curl_close($curl);		
	}
	
	function mrr_process_scanned_files_by_code($report_mode=0)
	{
		global $defaultsarray;
		$dir = $defaultsarray['base_path']."html/scanned";
		$dir = str_replace("//", "/", $dir);
		
		$new_folder = $defaultsarray['base_path']."files";
		$def_access = $defaultsarray['default_attachment_access_level'];
		
		$files=0;
		
		$dh  = opendir($dir);
		while (false !== ($filename = readdir($dh))) 
		{
    			
    			if(trim($filename)!='..' && trim($filename)!=".")
    			{
    				$marker="DIR:";
    				if(is_file("".$dir."/".$filename.""))
    				{
    					$marker="----";
    					
    					$pos1=strrpos($filename,".");
    					if($pos1>0)
    					{	//file should have some type of extension or skip it...
    						$file=substr($filename,0,$pos1);
    						$type=substr($filename,$pos1);
    						
    						$mod=substr($file,0,1);
    						$idval="";
    						$desc="";	
    						
    						//$len=strlen($file);
    						
    						
    						$pos2=strpos($file,"_");
    						if($pos2>0)
    						{
    							$desc=substr($file,$pos2);   							
    							$idval=substr($file,1,($pos2-1));
    						}
    						else
    						{
    							$idval=substr($file,1);	
    						}
    						if(!is_numeric($mod) && is_numeric($idval))
    						{
    							$files++;    							
    							
    							$xref_id=(int)$idval;
    							$section_id=0;
    							if(strtolower($mod)=="p")		$section_id=SECTION_PR0JECT;		//
    							elseif(strtolower($mod)=="e")		$section_id=SECTION_BID;			//e=estimate....a.k.a. Bid.
    							elseif(strtolower($mod)=="t")		$section_id=SECTION_ORDER;		//
    							elseif(strtolower($mod)=="s")		$section_id=SECTION_SERVICE;		//	
    							elseif(strtolower($mod)=="b")		$section_id=SECTION_BUILDER;		//
    							elseif(strtolower($mod)=="d")		$section_id=SECTION_BUILDER;		//d=developer...old version
    							elseif(strtolower($mod)=="c")		$section_id=SECTION_CUSTOMER;		//	
    							elseif(strtolower($mod)=="n")		$section_id=SECTION_SUBDIVISION;	//n=neighborhood (subdivision)
    							elseif(strtolower($mod)=="l")		$section_id=SECTION_LOT;			//	
    							
    							//if(trim($desc)=="")		$desc="_".time()."";
    							
    							$new_filename="".$mod."".$idval."".$desc."_".time()."".$type."";
    							
    							if($report_mode > 0)	echo "<br>".$files.". ".$mod."".$idval."".$desc."".$type.": (".$new_filename."): '".$dir."/".$filename."' to '".$new_folder."/".$new_filename."'";		// ".$file.".  Mod=
    							
    							$size=0;
    							$curdate=0;
    							    							
    							if($section_id > 0 &&  $xref_id > 0)
    							{    							
          						if (rename("".$dir."/".$filename."" , $new_folder."/".$new_filename)) {
                                        	$curdate=mrr_pull_image_created_date_v2($new_folder."/".$new_filename);
                                        	$rslt = 1;
                                        	$size=filesize($new_folder."/".$new_filename);
                                        	//unlink("".$dir."/".$filename."");
                                        	
                                        	$sql = "
                                        		insert into attached_files
                                        			(linedate_added,
                                        			filename,
                                        			filesize,
                                        			section_id,
                                        			xref_id,
                                        			deleted,
                                        			access_level,
                                        			uuid)
                                        			
                                        		values (now(),
                                        			'".sql_friendly($new_filename)."',
                                        			'".sql_friendly($size)."',
                                        			'".sql_friendly($section_id)."',
                                        			'".sql_friendly($xref_id)."',
                                        			0,
                                        			'".sql_friendly($def_access)."',
                                        			'".createuuid()."')
                                        	";
                                        	simple_query($sql);
                                        	
                                        	$iid=mysql_insert_id();
                                        	
                                        	$curdater=trim(substr($curdate,0,10));
                                        	$curtimer=trim(substr($curdate,10));
                                        	$curdater=str_replace(":","-",$curdater);
                                        	$strtime="".$curdater." ".$curtimer."";
                                        	
                                        	$sql = "
                                        		update attached_files set 
                                        			linedate_eta='".sql_friendly($strtime)."'
                                        		where id='".sql_friendly($iid)."'
                                        	";
                                        	simple_query($sql);
                                        	
                                        	if($report_mode > 0)	echo " ...<b>Moved</b> file ".$new_filename.".";
                                             
                                        	
                                        } else {
                                        	$rslt = 0;
                                        	if($report_mode > 0)	echo " ...<b>Unable to move file ".$new_filename."</b>.";
                                        }
                                        

                              	}    							
    						}
    					}
    				}
    				
    				
			}
		}
		
		if($report_mode > 0 && $files==0)	echo "<br><b>No files to move at this time.</b><br>";
	}
	
	
	function mrr_pull_image_created_date_v2($imagePath)
 	{
 		$camDate="";
 		if(trim($imagePath)!="" && file_exists($imagePath) && substr_count(strtolower($imagePath),".pdf") ==0)
 		{
 			$exif_ifd0 = read_exif_data($imagePath ,'IFD0' ,0);
			$camDate = $exif_ifd0['DateTime'];	
		}
		return $camDate;
 	}
 	*/
	
	function mrr_cookie_login_for_session($user_id=0)
	{
		global $defaultsarray;
		$secs=(int) $defaultsarray['session_timeout'];		
		$mrr_cookie_bake= time() +  $secs;				  		/* expires in SECS */
			
		if($user_id>0)
		{
			$sql = "select users.*					
				from users				
				where users.id = '" . sql_friendly($user_id) . "'
					and users.active = 1 
					and users.deleted = 0
			";			
			$data = simple_query($sql);
			$row = mysql_fetch_array($data);	
			
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['username'] = $row['username'];
			$_SESSION['access_level'] = $row['access_level'];
						
			$invalid_password = '';
			$use_userid = $row['id'];
			
			$mrr_cookie=createuuid();						
			
			unset($_COOKIE['user']);
			setcookie("user", $_SESSION['user_id'], $mrr_cookie_bake, "/");	//reset the cookie with new expiration date	
			
			unset($_COOKIE['uuid']);
			setcookie("uuid", $mrr_cookie, $mrr_cookie_bake, "/");			//reset the cookie with new expiration date	
			
			return 1;
		}
		return 0;
	}	
	function mrr_auto_login($user,$pass="")
	{
		global $mrr_cookie_bake;
		global $defaultsarray;
		
		$logged_in=0;
		
		if($user!="")
		{     		
     		$sql = "
     			select users.*     				
     			from users     			
     			where username = '" . sql_friendly($user) . "'     				
     				and users.active = 1 and users.deleted = 0
     		";		//and password = '" . sql_friendly($pass) . "'     			
     		$data = simple_query($sql);
     		if($row = mysql_fetch_array($data))
     		{
     			$mrr_location=$row['location_id'];
     			// be sure to put any additional SESSION variables on the order_review "successful login" section
				$_SESSION['user_id'] = $row['id'];				
				$_SESSION['username'] = $row['username'];
				$_SESSION['access_level'] = $row['access_level'];
				
				$invalid_password = '';
				$use_userid = $row['id'];				
				if(!isset($mrr_cookie))		$mrr_cookie=createuuid();
				
				setcookie("user", $_SESSION['user_id'], $mrr_cookie_bake);	//reset the cookie with new expiration date	
				setcookie("uuid", $mrr_cookie, $mrr_cookie_bake);			//reset the cookie with new expiration date		
				
				$logged_in=1;
     		}
		}
		return $logged_in;
	}
	
	
	function get_state_select_box($field,$pre="",$cd=0,$prompt="",$classy="")
     {		
     	//if($pre=="")		$pre="TN";
     	     	
     	if($classy=="")	$classy="";	// style='width:200px;'
     	
     	$selbox="<select name='".$field."' id='".$field."'".$classy.">";
     	
     	if($pre=="" || $pre==0)		$sel=" selected";		else	$sel="";
     	$selbox.="<option value=''".$sel." style='text-align:left;'>".$prompt."</option>";	
     			
     	$mrr_adder="";
     	if($cd==1)	$mrr_adder="where order_by_code>1 and order_by_code<54";
     	
     	$sql = "
     		select * 
     		from states			
     		".$mrr_adder."
     		order by order_by_code asc
     	";
     	$data=simple_query($sql);
     	while($row=mysql_fetch_array($data))
     	{
     		if($pre==$row['state_code'])		$sel=" selected";		else	$sel="";
     		$selbox.="<option value='".$row['state_code']."'".$sel." style='text-align:left;'>".$row['state_description']."</option>";	
     	}     	
     	
     	$selbox.="</select>";
     	return $selbox;			
     }
     
     function mrr_uniform_space($str,$min)
	{
		$str=trim($str);
		if($min <= 0 || $str=="")	return $str;
		
		if(strlen($str) > $min)
		{
			$str=substr($str,0,($min-3))."...";
		}
		elseif(strlen($str) < $min)
		{
			for($x=0; $x < ($min - strlen($str)) ; $x++)
			{
				$str.=" ";	
			}
		}
		elseif(strlen($str) ==$min)
		{
			//nothing at the moment....
		}
		return $str;
	}
	function mrr_letter_case_management($str)
	{
		$temp=trim($str);
		
		$temp=strtolower($temp);		//removes all caps.
		
		$first_char=substr($temp,0,1);
		$first_char=strtoupper($first_char);
		$temp=$first_char.substr($temp,1);
		
		//special cases
		$temp=str_replace(" nw"," NW",$temp);
		$temp=str_replace(" sw"," SW",$temp);	
		$temp=str_replace(" mt"," MT",$temp);
		//$temp=str_replace(" st"," ST",$temp);	
		//$temp=str_replace(" "," ",$temp);
				
		//normal first letter in word... (spaces separate words, so include space in search).
		$temp=str_replace(" a"," A",$temp);
		$temp=str_replace(" b"," B",$temp);
		$temp=str_replace(" c"," C",$temp);
		$temp=str_replace(" d"," D",$temp);
		$temp=str_replace(" e"," E",$temp);
		$temp=str_replace(" f"," F",$temp);
		$temp=str_replace(" g"," G",$temp);
		$temp=str_replace(" h"," H",$temp);
		$temp=str_replace(" i"," I",$temp);
		$temp=str_replace(" j"," J",$temp);
		$temp=str_replace(" k"," K",$temp);
		$temp=str_replace(" l"," L",$temp);
		$temp=str_replace(" m"," M",$temp);
		$temp=str_replace(" n"," N",$temp);
		$temp=str_replace(" o"," O",$temp);
		$temp=str_replace(" p"," P",$temp);
		$temp=str_replace(" q"," Q",$temp);
		$temp=str_replace(" r"," R",$temp);
		$temp=str_replace(" s"," S",$temp);
		$temp=str_replace(" t"," T",$temp);
		$temp=str_replace(" u"," U",$temp);
		$temp=str_replace(" v"," V",$temp);
		$temp=str_replace(" w"," W",$temp);
		$temp=str_replace(" x"," X",$temp);
		$temp=str_replace(" y"," Y",$temp);
		$temp=str_replace(" z"," Z",$temp);
				
		return $temp;	
	}	
?>