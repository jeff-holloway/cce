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
	function d($use_string, $die_here = true) 
	{
		
		echo "<br><br><br><br><br><br>";
		echo "<pre>";
		print_r($use_string);
		echo "</pre>";
		
		if($die_here) die;
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
		$row_fields = mysqli_fetch_array($data_fields);
		$field_array = array();
		while($row_fields = mysqli_fetch_array($data_fields)) 
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
		return mysqli_insert_id();
	}	
	function get_option_text_by_id($option_id,$mode=0) 
	{
		$sql = "
			select *
			
			from option_values
			where id = '".sql_friendly($option_id)."'
		";
		$data = simple_query($sql);
		$row = mysqli_fetch_array($data);
		$res=$row['fname'];
		if($mode==1)	$res=$row['fvalue'];
		return $res;
	}		
	function build_option_box($option_cat_name, $selected_value = "", $field_name, $show_name = false, $show_blank_text = true) 
	{
		$data = get_options($option_cat_name);
		
		echo "<select name='$field_name' id='$field_name'>";
		$row = mysqli_fetch_array($data);
		if($row['blank_text'] != '') 
		{
			echo "<option value='0'>".($show_blank_text ? $row['blank_text'] : "")."</option>";
		}
		mysqli_data_seek($data, 0);
		while($row = mysqli_fetch_array($data)) 
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
		$row = mysqli_fetch_array($data);
		if($row['blank_text'] != '') 
		{
			$selbx.="<option value='0'>".($show_blank_text ? $row['blank_text'] : "")."</option>";
		}
		mysqli_data_seek($data, 0);
		while($row = mysqli_fetch_array($data)) 
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
		$row = mysqli_fetch_array($data);
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
		
	//database
	function simple_query($sql) 
	{
		global $datasource;
		global $debug_mode;
		
		if($debug_mode) 
		{
			$data = mysqli_query($datasource,$sql) or die("database query failed! <br>". mysqli_error($datasource) . "<pre>". $sql ."</pre>");
		} 
		else 
		{
			$data = mysqli_query($datasource,$sql) or die("General Error Occurred...");
		}		
		return $data;
	}
	
	function sql_friendly($istring) 
	{		
		global $datasource;
		if(get_magic_quotes_gpc()) 
		{
			$hold = stripslashes($istring);
		} 
		else 
		{
			$hold = $istring;
		}		
		return mysqli_real_escape_string($datasource,$hold);
	}
	function mysql_user_function_exists($function_name) 
	{
		// check to see if a user defined function in mysql already exists		
		global $db_name;
		
		$sql = "SELECT * FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE='function' AND routine_name = '".sql_friendly($function_name)."' AND ROUTINE_SCHEMA='".sql_friendly($db_name)."'";
		$data = simple_query($sql);
		
		if(mysqli_num_rows($data)) {
			return true;
		} else {
			return false;
		}
	}
	function get_mysql_insert_id()
	{
		global $datasource;	
		return mysqli_insert_id($datasource);
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
		if($top_record > mysqli_num_rows($data_list)) $top_record = mysqli_num_rows($data_list);
	?>
	<table class='admin_menu' width='100%'>
		<tr>
			<form action='?<?=$use_query_string?>' method='post'>
			<td width='1%'>
				&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				<b>&nbsp;&nbsp;&nbsp;<?=mysqli_num_rows($data_list)?> record(s) |  viewing records <?=$record_start + 1?> - <?=$top_record?></b><br>
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
				if(($record_start + $_SESSION['results_per_page']) < mysqli_num_rows($data_list)) 
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
					$page_count = mysqli_num_rows($data_list) / $_SESSION['results_per_page'];
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
	
	//users
	function get_welcome_by_id($id,$cd=0) 
	{
		if($id==0)		return "";
		
		$sql = "
			select *			
			from users
			where id = '".sql_friendly($id)."'
		";
		$data = simple_query($sql);
		$row = mysqli_fetch_array($data);
		
		if(trim($row['first_name']." ".$row['last_name'])!="" && $cd==0)
		{
			$disp_name = "<h2>Welcome, <span>".$row['first_name']." ".$row['last_name']."</span>";			
			if(trim($row['title'])!="")	$disp_name.=", ".trim($row['title'])."";			
			$disp_name.="!</h2>";				
		}
		else
		{
			if($cd==1)	return $row['username'];
			
			$disp_name = "<h2>Welcome, <span>".$row['username']."</span>";	
		}
		$tab=$disp_name;
		
		return $tab;
	}
	function get_tagline_trail($id=0) 
	{
		$new_tab="";
		
		//get tagline text.
		$tagline="";
		$sql = "
     		select * 
     		from cce_messages			
     		where deleted=0
     			and section='tagline'     		
     		order by linedate_added desc
     		limit 1
     	";
     	$data=simple_query($sql);
     	if($row=mysqli_fetch_array($data))
     	{     
     		//see if user can edit this...
     		$res_tab=mrr_display_cce_message_pad($row['id']);
     		$editor="";
     		if(trim($res_tab)!="")
     		{
     			$editor="<i class='fa fa-pencil' style='color:#e19918;' title='Click to edit the tagline' onClick='allow_cce_message_edit2(".$row['id'].");'></i> &nbsp; &nbsp;";
     			//$editor="<input type='button' class='buttonize btn btn-default add_new_btn' onClick='allow_cce_message_edit2(".$row['id'].");' value='Edit Tagline'>";
     		}     		
     		$tagline="<span id='cce_sub_".$row['id']."'>".$row['subject']."</span> ".$editor."".$res_tab."";
     	}  
     	//...
     	$new_tab.="<div class='mrr_tagline'>".$tagline."</div><span id='bread_crumb_trail'></span>";
     	
		return $new_tab;
	}
	
	function mrr_show_important_dates($dater="")
	{
		$min_cal_list="";
		
		if($dater=="")		$dater=date("Y-m-d", time());
		
		
		$mrr_adder="";
		
		//find merchant dates first...acts as a default.
     	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$mrr_adder.=" and important_dates.merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'";
     		
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$mrr_adder.=" and important_dates.merchant_id='".sql_friendly($_SESSION['merchant_id'])."'";
     	}
     	
     	//find store dates next...override merchant if set.
     	if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
     	{
     		$mrr_adder.=" and (important_dates.store_id='".sql_friendly($_SESSION['selected_store_id'])."' or important_dates.store_id=0)";
     	}     	
     	elseif($_SESSION['store_id'] > 0)
     	{
     		$mrr_adder.=" and (important_dates.store_id='".sql_friendly($_SESSION['store_id'])."' or important_dates.store_id=0)";
     	}
		
		
		$sql = "
			select *			
			from important_dates
			where linedate >= '".date("Y-m-d", strtotime($dater))." 00:00:00'
				and deleted=0
				and archived=0
				".$mrr_adder."
			order by linedate asc
			limit 5
		";
		$data = simple_query($sql);
		while($row = mysqli_fetch_array($data))
		{
			$allow_editor1="&nbsp;";
			$allow_editor2="&nbsp;";
			$allow_editor3="&nbsp;";	//<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
			
			$edit_mode="";
               $valid_user=check_user_edit_access('important_dates',$row['id'],$_SESSION['user_id']);
               if($valid_user > 1)
               {	
               	$allow_editor1="<i class='fa fa-pencil' title='Click to edit this date' onClick='edit_important_date(".$row['id'].",1);'></i>";
				$allow_editor2="<i class='fa fa-trash' title='Click to remove this date' onClick='edit_important_date(".$row['id'].",2);'></i>";
				//$allow_editor3="<i class='fa fa-chevron-circle-down' title='Click to check it off (archive).' onClick='edit_important_date(".$row['id'].",3);'></i>";	
               }					
			
			$min_cal_list.="				
				<li class='important_date_id_".$row['id']."'>
					<ul class='edit_icons'>
						<li><a href='javascript: void(0);'>".$allow_editor1."</a></li>
						<li><a href='javascript: void(0);'>".$allow_editor2."</a></li>
						<li><a href='javascript: void(0);'>".$allow_editor3."</a></li>
					</ul>
					<span><small>".date("M Y", strtotime($row['linedate']))."</small><strong>".date("d", strtotime($row['linedate']))."</strong></span>
					<p><span>".trim($row['date_description'])."</span>".trim($row['date_message'])."</p>					
				</li>				
			";
		}	
		
		//Important Dates  <input type='button' value='Add Important Date' class='buttonize'>
		$tab="
			<div class='mrr_button_left_margin'>
				<button class='btn btn-default add_new_btn' name='create-new-date' id='create-new-date' type='button' onClick='edit_important_date(0,1);'>ADD NEW DATE</button>
			</div>
			<ul class='imp_date_list'>
				".$min_cal_list."
			</ul>
						
			<div id='dialog_delete_date' title='Remove this Important date?' style='display:none;'>
          		<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>This Date will be permanently removed and cannot be recovered. Are you sure you want to delete it?</p>
          	</div>	
		";
		
		return $tab;	
	}
	
	
	function get_user_info_section_by_id($id) 
	{
		if($id==0)		return "";
		
		$sql = "
			select *			
			from users
			where id = '".sql_friendly($id)."'
		";
		$data = simple_query($sql);
		$row = mysqli_fetch_array($data);
		$store_display="All";
		
		if($row['store_id'] > 0)
		{
			$sqlx = "
     			select *			
     			from store_locations
     			where id = '".sql_friendly($row['store_id'])."'
     		";
     		$datax = simple_query($sqlx);
     		$rowx = mysqli_fetch_array($datax);
			
			$store_display="".$rowx['store_number'].": ".$rowx['store_name']."";	
		}
		
		
		$edit_link="";
		$edit_mode="";
		$valid_user=check_user_edit_access('users',$row['id'],$_SESSION['user_id']);
		if($valid_user==1)		$edit_mode="readonly";	
		if($valid_user==2)
		{
			$edit_link="
				<div style='float:right; margin-right:25px;'>
     				<input type='button' class='buttonize' value='Edit' onClick='select_user_id(".$row['id'].",\"".$edit_mode."\");'>
     			</div>
			";				
		}	
		//Account Information
		$tab="
			<div id='user_info_section'>
				<div class='user_info_title' style='height:35px;'>
					".$edit_link."
					&nbsp;
				</div>
				<div class='user_info_body'>
					<div class='user_info_row'>
						<div class='user_info_first'>
							<div class='user_info_label'>First Name</div>
							<div class='user_info_data'>".$row['first_name']."</div>
						</div>
						<div class='user_info_last'>
							<div class='user_info_label'>Last Name</div>
							<div class='user_info_data'>".$row['last_name']."</div>
						</div>
						<div class='user_info_stores'>
							<div class='user_info_label'>Store Location</div>
							<div class='user_info_data'>".$store_display."</div>
						</div>
					</div>
					<div class='user_info_row'>						
						<div class='user_info_email'>
							<div class='user_info_label'>Username</div>
							<div class='user_info_data'>".$row['username']."</div>
						</div>
						<div class='user_info_pass'>
							<div class='user_info_label'>Password</div>
							<div class='user_info_data'>********</div>
						</div>
					</div>
				</div>
			</div>		
		";	
		
		$tab="
			<table class='table table-striped'>
				  <tbody>
					<tr>
					  	<td>FIRST NAME<br><span>".$row['first_name']."</span></td>
					  	<td>LAST NAME<br><span>".$row['last_name']."</span></td>
					  	<td>STORE LOCATION<br><span>".$store_display."</span></td>
					</tr>
					<tr>
						<td>USERNAME<br><span>".$row['username']."</span></td>
					  	<td>PASSWORD<br><span>************</span></td>
					  	<td>E-MAIL<br><span>".$row['email']."</span></td>					  
					</tr>
				  </tbody>
			</table>
		";
				
		return $tab;
	}
	function mrr_add_login_attempt($use_userid,$user,$invalid_password)
	{
		$sql = "
			insert into log_login
				(user_id,
				username,
				ip_address,
				linedate_added,
				invalid_password)
				
			values ('".sql_friendly($use_userid)."',
				'".sql_friendly($user)."',
				'".sql_friendly($_SERVER['REMOTE_ADDR'])."',
				now(),
				'".sql_friendly($invalid_password)."')
		";
		simple_query($sql);	
	}
	function mrr_reset_acct_pass($try_id,$try_user)
	{
		$error="";
		
		if($try_id > 0 && $try_user!="")
		{
			$sql = "select users.*					
     			from users				
     			where id = '".sql_friendly($try_id)."' 
     				and username='".sql_friendly($try_user)."'
     		";			
     		$data = simple_query($sql);	
     		if($row = mysqli_fetch_array($data))
     		{
     			$use_pswd=mrr_encryptor("reset_password",$try_user);
     			
     			$sql="
					update users set 
						password='".sql_friendly($use_pswd)."',
						reset_password=1,
						linedate_failed='0000-00-00 00:00:00',
						failed_logins=0						
						
					where id='".sql_friendly($try_id)."' 
						and username='".sql_friendly($try_user)."'
				";
				simple_query($sql);
				
				$error = 'Password reset, please check your E-mail log in again.';
     			     			
     			//header("Location: login.php");
				//exit;
     		}   
     		else
     		{
     			$error = 'Account not found.';
     		}  		
		}
		return $error;	
	}
	function mrr_reset_acct_pass_email($e_addr)
	{
		$error_email="";
		global $datasource;
		
		if($e_addr!="")
		{
			$sql = "select users.*					
     			from users				
     			where email = '" . sql_friendly($e_addr) . "'
     				and deleted=0
     				and archived=0
     		";			
     		$data = simple_query($sql);
     		$row = mysqli_fetch_array($data);						
     		if(is_array($row))
     		{
     			$uuid = createuuid()."-".createuuid()."-".uniqid(true);
     			$sql = "
     				insert into users_reset_pass
     					(user_id,
     					linedate_added,
     					deleted,
     					uuid,
     					email_to)
     					
     				values ('".sql_friendly($row['id'])."',
     					now(),
     					0,
     					'".sql_friendly($uuid)."',
     					'".sql_friendly($e_addr)."')
     			";
     			$data = simple_query($sql);
     			$reset_id = mysqli_insert_id($datasource);
     			$id=$row['id'];
     			$us=trim($row['username']);
     			$ps="*********************";
     			$error_email = "";	
     			mrr_send_login_email($e_addr,$us,$ps,$id, $reset_id);
     			if(trim($error_email)=="")	$email_success = 1;
			}
		}
		else
		{
			$error_email = $lang['login_emailer'];
		}	
		return $error_email;	
	}
	
	function mrr_find_user_roles($cust=0,$store=0,$id=0)
	{
		$roles=0;	
		if($cust > 0)
		{	//if no roles delete all in customer
			$sql2 = "
     			select id 
     			from users	
          		where merchant_id = '" . sql_friendly($cust) . "'
          			and access_level<='61'
          			and deleted=0
          	";			
          	$data2 = simple_query($sql2);
          	while($row2 = mysqli_fetch_array($data2))
          	{
          		$pos=0;
          		$uid=$row2['id'];
          		
          		//customer or group roles
          		$sql = "
          			select count(*) as mrr_cntr 
          			from merchants		
               		where (co_user_id = '" . sql_friendly($uid) . "' or group_user_id = '" . sql_friendly($uid) . "')
               			and id!='" . sql_friendly($cust) . "'
               			and deleted=0
               	";			
               	$data = simple_query($sql);
               	if($row = mysqli_fetch_array($data))
               	{
               		$pos+=$row['mrr_cntr'];	
               	}	
               	//store roles
               	$sql = "
          			select count(*) as mrr_cntr 
          			from store_locations		
               		where cm_user_id = '" . sql_friendly($uid) . "'
               			and merchant_id!='" . sql_friendly($cust) . "'
               			and deleted=0
               	";			
               	$data = simple_query($sql);
               	if($row = mysqli_fetch_array($data))
               	{
               		$pos+=$row['mrr_cntr'];	
               	}	
               	
               	if($pos==0)
               	{	//no positions, so flag for removal
               		$sql3 = "update users set deleted='1' where id = '" . sql_friendly($uid) . "'";			
          			simple_query($sql3);	
               	}
          	}	
		}
		elseif($store > 0)
		{	//if no roles, delete all in users in this store
			$sql2 = "
     			select id 
     			from users	
          		where store_id = '" . sql_friendly($store) . "'
          			and access_level<='40'
          			and deleted=0
          	";			
          	$data2 = simple_query($sql2);
          	while($row2 = mysqli_fetch_array($data2))
          	{
          		$pos=0;
          		$uid=$row2['id'];
          		
          		//customer or group roles
          		$sql = "
          			select count(*) as mrr_cntr 
          			from merchants		
               		where (co_user_id = '" . sql_friendly($uid) . "' or group_user_id = '" . sql_friendly($uid) . "')
               			and deleted=0
               	";			
               	$data = simple_query($sql);
               	if($row = mysqli_fetch_array($data))
               	{
               		$pos+=$row['mrr_cntr'];	
               	}	
               	//store roles
               	$sql = "
          			select count(*) as mrr_cntr 
          			from store_locations		
               		where cm_user_id = '" . sql_friendly($uid) . "'
               			and store_id!='" . sql_friendly($store) . "'
               			and deleted=0
               	";			
               	$data = simple_query($sql);
               	if($row = mysqli_fetch_array($data))
               	{
               		$pos+=$row['mrr_cntr'];	
               	}	
               	
               	if($pos==0)
               	{	//no positions, so flag for removal
               		$sql3 = "update users set deleted='1' where id = '" . sql_friendly($uid) . "'";			
          			simple_query($sql3);	
               	}
          	}
		}
		else
		{
			//customer or group roles
     		$sql = "
     			select count(*) as mrr_cntr 
     			from merchants		
          		where (co_user_id = '" . sql_friendly($id) . "' or group_user_id = '" . sql_friendly($id) . "')
          			and deleted=0
          	";			
          	$data = simple_query($sql);
          	if($row = mysqli_fetch_array($data))
          	{
          		$roles+=$row['mrr_cntr'];	
          	}	
          	//store roles
          	$sql = "
     			select count(*) as mrr_cntr 
     			from store_locations		
          		where cm_user_id = '" . sql_friendly($id) . "'
          			and deleted=0
          	";			
          	$data = simple_query($sql);
          	if($row = mysqli_fetch_array($data))
          	{
          		$roles+=$row['mrr_cntr'];	
          	}
		}     		
     	return $roles;
	}
	
	function mrr_remove_account($id)
	{
		$sql="update users set deleted='1'	where id='".sql_friendly($id)."'";
     	simple_query($sql);	
     	
     	//remove from merchant linkage
     	$sql="update merchants set co_user_id='0' where co_user_id='".sql_friendly($id)."'";
     	simple_query($sql);	
     	$sql="update merchants set group_user_id='0' where group_user_id='".sql_friendly($id)."'";
     	simple_query($sql);	
     	
     	//remove from store linkage
     	$sql="update store_locations set cm_user_id='0' where cm_user_id='".sql_friendly($id)."'";
     	simple_query($sql);	
	}
	function mrr_update_account($id,$level,$arch,$email,$first,$last,$merch,$store,$title,$logs,$phone1,$phone2)
	{		
		if($merch==0)		$store=0;
		
		
		$sql="
     		update users set 
     			
     			access_level='".sql_friendly($level)."',
     			archived='".sql_friendly($arch)."',
     			first_name='".sql_friendly($first)."',
     			last_name='".sql_friendly($last)."',
     			merchant_id='".sql_friendly($merch)."',
				store_id='".sql_friendly($store)."',
				title='".sql_friendly($title)."',
				monitor_logs='".sql_friendly($logs)."',
				contact_phone1='".sql_friendly($phone1)."',
				contact_phone2='".sql_friendly($phone2)."',
				contact_phone3='',
				contact_phone4='',
     			email='".sql_friendly($email)."' 
     			    		
     		where id = '".sql_friendly($id)."' 
     	";
     	simple_query($sql);
     	
     	if($id == $_SESSION['user_id']) {
     		// we're editing ourselves, make sure the other related session information is updated (if it changed)
     		$_SESSION['merchant_id'] = $merch;
     		$_SESSION['store_id'] = $store;
     	}
     	
     	if($level ==60 && $merch>0)
		{	//if selected update the merchant to use this user as the CO.
			$sql = "
     			update merchants set     			
     				co_user_id='".sql_friendly($id)."'     					
     			where id='".sql_friendly($merch)."'
     		";	
     		simple_query($sql);		
		}
		elseif($level ==70 && $merch>0)
		{	//if selected update the merchant to use this user as the Group Manager.
			$sql = "
     			update merchants set     			
     				group_user_id='".sql_friendly($id)."'     					
     			where id='".sql_friendly($merch)."'
     		";	
     		simple_query($sql);		
		}
		elseif($level ==40 && $merch>0 && $store > 0)
		{	//if selected update the merchant to use this user as the Group Manager.
			$sql = "
     			update store_locations set     			
     				cm_user_id='".sql_friendly($id)."'     					
     			where id='".sql_friendly($store)."'
     		";	
     		simple_query($sql);		
		}		
	}
	function mrr_update_account_pass($id,$user,$pass,$confirm)
	{		
		if(trim($pass)!="" && trim($confirm)!="" && trim($user)!="" && trim($pass)==trim($confirm) )
		{			
			$newpass= mrr_encryptor(trim($pass),trim($user) );
			$sql="
          		update users set 
          			password='".sql_friendly( $newpass )."',
          			reset_password=0,
          			linedate_failed='0000-00-00 00:00:00',
					failed_logins=0         			    		
          		where id = '".sql_friendly($id)."' 
          	";
          	simple_query($sql);	
          	//return 1;			
		}	
		return 0;
	}
	function mrr_create_account($user,$pass,$e_addr,$utype=0)
	{
		global $lang;
		
		$error_new="";
		$newid=0;		
		$auto_edit=0;
		
		if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0 && trim($_SESSION['username'])!="")				$auto_edit=1;		//current user is already logged in, so this new user can be edited by him/her.
		
		//set merchant if possible.
		$use_merchant=0;
		if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
		{
			$use_merchant=$_SESSION['selected_merchant_id'];	
		}
		elseif($_SESSION['merchant_id'] > 0)
		{
			$use_merchant=$_SESSION['merchant_id'];	
		}
		
		//set store if possible.
		$use_store=0;
		if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
		{
			$use_store=$_SESSION['selected_store_id'];	
		}
		elseif($_SESSION['store_id'] > 0)
		{
			$use_store=$_SESSION['merchant_id'];	
		}		
		
		if($e_addr!="" && $user!="")
		{	
			//check if account is already there. (even if deleted or archived to prevent duplicates.)
			$sql = "select users.*					
     			from users				
     			where (email = '" . sql_friendly($e_addr) . "' or username='".sql_friendly($user)."')
     				and deleted=0
     		";			
     		$data = simple_query($sql);
     		$row = mysqli_fetch_array($data);						
     		if(is_array($row))
     		{	
     			$us=trim($row['username']);
     			//$ps=trim($row['password']);
     			$em=trim($row['email']);
     			$del=0;	//$row['deleted'];
     			$arch=$row['archived'];
     			$error_new='';
				$new_success = 0;
				
				$newid=$row['id'];
				
				$newid=0;
				$auto_edit=0;
				
				if($del > 0 || $arch > 0)
				{
					$error_new='This account is no longer active.  Please create a new account or contact the administrator.';	
				}
				else
				{
					$error_new='This username or email address is already in use.  Please log in or create a new account.  You may also contact the administrator.';		
				}
			}
			else
			{	
				//create this account with lowest (free tools) user access level.
				$use_pswd=mrr_encryptor($pass,$user);
								
				$sql="
					insert into users
						(id,
						username,
						password,
						first_name,
						last_name,
						email,
						merchant_id,
						store_id,
						access_level,
						linedate_added,
						linedate_login,
						deleted,
						archived,
						uuid,
						security_question,
						security_answer,
						reset_password,
						linedate_failed,
						failed_logins)
					values
						(NULL,
						'".sql_friendly($user)."',
						'".sql_friendly($use_pswd)."',
						'',
						'',
						'".sql_friendly($e_addr)."',
						'".sql_friendly($use_merchant)."',
						'".sql_friendly($use_store)."',
						10,
						NOW(),
						'0000-00-00 00:00:00',
						0,
						0,
						'',
						'',
						'',
						0,
						'0000-00-00 00:00:00',
						0)
				";
				simple_query($sql);
				$newid=get_mysql_insert_id();
				
				if($utype > 0 && $newid > 0)
				{					
					if($utype ==1)
					{	//CO
						$sql="
							update users set 
								access_level='61' 
								".($_SESSION['selected_merchant_id'] > 0 ? ",merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'" : "")."
								".($_SESSION['selected_store_id'] > 0 ? ",store_id='".sql_friendly($_SESSION['selected_store_id'])."'" : "")."
							where id='".sql_friendly($newid)."'
						";
						simple_query($sql);
					}
					elseif($utype ==2)
					{	//GM
						$sql="
							update users set 
								access_level='70' 
								".($_SESSION['selected_merchant_id'] > 0 ? ",merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'" : "")."
								".($_SESSION['selected_store_id'] > 0 ? ",store_id='".sql_friendly($_SESSION['selected_store_id'])."'" : "")."
							where id='".sql_friendly($newid)."'
						";
						simple_query($sql);
					}
					elseif($utype ==3)
					{	//CM
						$sql="
							update users set 
								access_level='40' 
								".($_SESSION['selected_merchant_id'] > 0 ? ",merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'" : "")."
								".($_SESSION['selected_store_id'] > 0 ? ",store_id='".sql_friendly($_SESSION['selected_store_id'])."'" : "")."
							where id='".sql_friendly($newid)."'
						";
						simple_query($sql);
					}
				}
				
				$error_new="";	//mrr_send_login_email($e_addr,$user,$pass,$id);
				if(trim($error_new)=="")		$new_success = 1;				
			}
		}
		else
		{
			$error_new=$lang['login_creater'];
		}	
		
		$res['newid']=$newid;
		$res['msg']=$error_new;
		$res['auto_edit']=$auto_edit;
		
		return $res;
	}
	
	function mrr_get_user_email_address($id=0,$cd=0)
	{
		$email="";
		$sql = "
			select email			
			from users
			where id = '".sql_friendly($_SESSION['user_id'])."'
		";	
		
		if($id>0 || $cd>0)
		{
			$sql = "
				select email		
				from users
				where id = '".sql_friendly($id)."'
			";		
		}
		
		$data = simple_query($sql);
		while($row = mysqli_fetch_array($data))
		{
			$email=trim($row['email']);
		}
		return $email;	
	}
	function mrr_get_user_email_names($id=0,$cd=0)
	{
		$fullname="";
		$sql = "
			select first_name,last_name			
			from users
			where id = '".sql_friendly($_SESSION['user_id'])."'
		";
		
		if($id>0 || $cd>0)
		{
			$sql = "
				select first_name,last_name			
				from users
				where id = '".sql_friendly($id)."'
			";		
		}
			
		$data = simple_query($sql);
		while($row = mysqli_fetch_array($data))
		{
			$fullname=trim($row['first_name'])." ".trim($row['last_name']);
		}
		return $fullname;	
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
		
		if(!mysqli_num_rows($data)) return false;
		
		$row = mysqli_fetch_array($data);
		if($row['access_level'] <= $_SESSION['access_level']) 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}
	function mrr_get_merchant_teirs_validation($parent_id,$merchant_id,$store_id=0)
     {	//take user merchant id and validate if multi-manager can view this merchant_id (and store_id).  Assumes each store is assigned to a merchant.
     	$validate=0;
     	$sql = "
     		select * 
     		from merchants			
     		where deleted=0 
     			and parent_company_id='".sql_friendly($parent_id)."'    		
     		order by merchant asc,id asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		if($store_id==0 && $row['id']==$merchant_id)	
     		{
     			return 1;		//validated...merchant is part of conglomerate under parent (for multi-manager user)...and store is not needed.
     		}
     		elseif($store_id > 0 && $row['id']==$merchant_id)	
     		{	//take this merchant to validate the store as well.
     			$sql2 = "
               		select id 
               		from store_locations		
               		where deleted=0 
               			and merchant_id='".sql_friendly($row['id'])."'    		
               		order by store_name asc,id asc
               	";
               	$data2=simple_query($sql2);
               	while($row2=mysqli_fetch_array($data2))	
               	{
               		if($row2['id']==$store_id)		return 1;		//found store in under a valid merchant.  This store is un der control of multi-manager.
               	}
     		}
    		
     		//not found yet, so go another level deeper in chain of merchant parentage.
    			$validate=mrr_get_merchant_teirs_validation($row['id'],$merchant_id,$store_id);		//try to find same merchant/store as subsidiary of the current merchant...
    			if($validate > 0)	return 1;
     	}
     	
     	return $validate;
     }
	function check_user_edit_access($action_name,$action_id,$user_id)
	{
		//use the user permissions to validate if this action is valid 
		$valid=0;		//0=No View, 1=View Only, 2=Edit
		
		$has_access=0;
		$view_access=0;
		$merchant=0;
		$store=0;
		
		//Get Current User permissions
		$sql = "
			select access_level,
				(select view_access from user_levels where user_levels.access_level=users.access_level) as view_access_level,
				merchant_id,
				store_id
			
			from users
			where id = '".sql_friendly($user_id)."'
		";
		$data = simple_query($sql);	
		if($row = mysqli_fetch_array($data))
		{
			$has_access=$row['access_level'];
			$view_access=$row['view_access_level'];
			$merchant=$row['merchant_id'];
			$store=$row['store_id'];
		}	
		
		//merchant check
		if($action_name=='merchants')
		{
			$cur_user=0;
			$sql = "
				select user_id		
				from merchants
				where id = '".sql_friendly($action_id)."'
			";
			$data = simple_query($sql);	
			if($row = mysqli_fetch_array($data))
			{
				$cur_user=$row['user_id'];			
			}	
			
			if($has_access >= 90)
			{
				$valid=2;									//Edit all, skip the rest for this action.
			}
			elseif($has_access == 70)
			{	//multi-manager user
				if($merchant==$action_id)
				{
					$valid=2;				//can edit...this is the conglomerate merchant the user is in charge of...
				}
				else
				{
					$test_valid=mrr_get_merchant_teirs_validation($merchant,$action_id,0);	//see if the user's merchant ID is parent company to the checked merchant...	
					if($test_valid > 0)		$valid=2;
				}
			}
			elseif($has_access <= 60)
			{	
				if($merchant==$action_id)		$valid=1;		//too low to view user info page...if not his/her merchant. no point in checking target page.
			}	
			elseif($cur_user==$user_id && $merchant==$action_id)
			{
				$valid=2;									//belongs to this user...
			}
			return $valid;									//exit function now for important dates...
		}
		//store check
		if($action_name=='store_locations')
		{
			$cur_user=0;
			$cur_merch=0;
			$sql = "
				select user_id,merchant_id
				from store_locations
				where id = '".sql_friendly($action_id)."'
			";
			$data = simple_query($sql);	
			if($row = mysqli_fetch_array($data))
			{
				$cur_user=$row['user_id'];	
				$cur_merch=$row['merchant_id'];		
			}	
			
			if($has_access >= 90)
			{
				$valid=2;									//Edit all, skip the rest for this action.
			}
			elseif($has_access == 70)
			{	//multi-manager user
				if($merchant==$cur_merch)
				{
					$valid=2;				//can edit...this is the conglomerate merchant the user is in charge of...
				}
				else
				{
					$test_valid=mrr_get_merchant_teirs_validation($merchant,$cur_merch,$action_id);	//see if the user's merchant ID is parent company to the checked merchant/store combo...	
					if($test_valid > 0)		$valid=2;
				}
			}
			elseif($has_access <= 20)
			{	
				if($store==$action_id)		$valid=1;			//too low to view user info page...if not his/her store. no point in checking target page.
			}	
			elseif($cur_user==$user_id && $merchant==$cur_merch && $store==$action_id)
			{
				$valid=2;									//belongs to this user...
			}
			return $valid;									//exit function now for important dates...
		}
		
		//important date check
		if($action_name=='important_dates')
		{
			$cur_user=0;
			$sql = "
				select user_id			
				from important_dates
				where id = '".sql_friendly($action_id)."'
			";
			$data = simple_query($sql);	
			if($row = mysqli_fetch_array($data))
			{
				$cur_user=$row['user_id'];			
			}	
			
			if($has_access >= 100)
			{
				$valid=2;									//Edit all, skip the rest for this action.
			}
			elseif($has_access <= 20)
			{	
				if($action_id!=$user_id)		$valid=1;			//too low to view user info page...if not his/her own. no point in checking target page.
			}	
			elseif($cur_user==$user_id)
			{
				$valid=2;									//belongs to this user...
			}
			return $valid;									//exit function now for important dates...
		}
		
		
		//User check
		if($action_name=='users')
		{
			if($has_access >= 100)
			{
				$valid=2;									//Edit all, skip the rest for this action.
				return $valid;	
			}
			elseif($has_access <= 20)
			{	
				if($action_id!=$user_id)		return $valid;		//to low to view user info page...if not his/her own. no point in checking target page.
			}
			
			
			
			//not skipped yet, so check the remaining fields on target page.
			$checked_store=0;
			$checked_merchant=0;
			$checked_access=0;			
			
			$sql = "
				select access_level,
					merchant_id,
					store_id
			
				from users
				where id = '".sql_friendly($action_id)."'
			";
			$data = simple_query($sql);	
			if($row = mysqli_fetch_array($data))
			{
				$checked_access=$row['access_level'];
				$checked_merchant=$row['merchant_id'];
				$checked_store=$row['store_id'];				
			}	
			
			if($action_id==$user_id && $has_access >= 70)
			{
				$valid=2;		//current users own account...allow edit for now.
			}			
			elseif($merchant==0 && $view_access >=70)
			{	//general CCE or higher....
				if($view_access > $checked_access && $view_access >=70)
				{
					$valid=2;		//Can edit if higher, not equal
				}
				elseif($view_access > $checked_access && $view_access >=65)
				{
					$valid=1;		//can view if higher, not equal
				}
				else
				{
					$valid=0;		//no access.
				}				
			}
			elseif($merchant > 0)
			{
				if($view_access > $checked_access && $merchant==$checked_merchant)
				{
					//merchant matched, so validate from this level...
					if($store==0)
					{	//all stores....
						$valid=2;								//same merchant with higher access... edit option
						if($view_access < 50)		$valid=1;		//view only....
					}
					elseif($store > 0 && $store==$checked_store)
					{	//only in this store...
						$valid=2;								//same store with higher access... edit option
						if($view_access < 40)		$valid=1;		//view only....
					}
				}	
				else
				{
					if($has_access >= 70 && $merchant==$checked_merchant && $has_access >= $checked_access)
          			{	
          				$valid=2;			//multi-manager user
          			}	
          			elseif($has_access >= 70 && $merchant!=$checked_merchant && $has_access >= $checked_access)
          			{
          				$test_valid=mrr_get_merchant_teirs_validation($merchant,$checked_merchant,$checked_store);	//see if the user's merchant ID is parent company to the checked merchant/store combo...	
          				if($test_valid > 0)		$valid=2;	
          			}	
				}			
			}
		}		
		return $valid;
	}
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
	
	//redirect/xml
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
	function mrr_pull_image_created_date($imagePath)
 	{
 		$camDate="";
 		if(trim($imagePath)!="" && file_exists($imagePath))
 		{
 			$exif_ifd0 = read_exif_data($imagePath ,'IFD0' ,0);
			$camDate = $exif_ifd0['DateTime'];	
		}
		return $camDate;
 	}
	
	
	
	function mrr_get_default_trusted_email()
	{
		global $defaultsarray;		
		return $defaultsarray['emails_from'];	
	}
	function sendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles='', $replyName='',$replyAddr='') 
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

		$mrr_email=$replyAddr;
		$mrr_names=$replyName;
		if(trim($mrr_names)=="")		$mrr_names=$FromName;
		if(trim($mrr_email)=="")		$mrr_email=$From;
		$mail->AddReplyTo($mrr_email, $mrr_names);	
		
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
				attachment_id)
				
			values ('".sql_friendly($From)."',
				'".sql_friendly($To)."',
				'".sql_friendly($Subject)."',
				'".sql_friendly($AttmFiles)."',
				now(),
				'".(isset($_SESSION['user_id']) ? sql_friendly($_SESSION['user_id']) : "0")."',
				'".sql_friendly($mail_error)."',
				'".sql_friendly($mail_uuid)."',
				'".sql_friendly($email_notes)."',
				'".sql_friendly($email_xref_id)."',
				'".sql_friendly($email_section_id)."',
				'".sql_friendly($email_attachment_id)."')
		";
		simple_query($sql);		
	}
	
	function mrr_send_login_email($e_addr,$us,$ps,$id=0,$reset_id=0)
	{
		$error="";
		global $defaultsarray;
		
		$sql = "
			select *
			
			from users_reset_pass
			where id = '".sql_friendly($reset_id)."'
		";
		$data = simple_query($sql);
		$row = mysqli_fetch_array($data);
		
		$use_reset_link=", password=\"".$ps."\".";
		$use_reset_html=", password=\"<b>".$ps."</b>\".";
		if($ps=="*********************")
		{
			$use_reset_link=". Use this link to reset your password: ".$defaultsarray['secure_site']."login.php?reset=$row[uuid] or ignore if you do not need to reset your password.";
			$use_reset_html=". <br><br>Click <a href='".$defaultsarray['secure_site']."login.php?reset=$row[uuid]'>here</a> to reset your password or ignore if you do not need to reset your password.<br><br>";
		}		
		
		$mail = new PHPMailer();
		     		
		//$mail->IsSMTP(); 							// telling the class to use SMTP
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
		//$mail->AddReplyTo($defaultsarray['emails_from'], $defaultsarray['company_name']);	
		$mail->From = $defaultsarray['emails_from'];
		$mail->FromName = $defaultsarray['company_name'];	
		$mail->AddAddress($e_addr);
		$mail->AltBody = "Your ".$defaultsarray['company_name']." login info:  username - \"".$us."\"".$use_reset_link." Username and Password are case-sensitive.";		
		$mail->Subject = "".$defaultsarray['company_name']." Login Information";
		$mail->Body = "Your ".$defaultsarray['company_name']." login info:  username - \"<b>".$us."</b>\"".$use_reset_html." Username and Password are <b>case-sensitive</b>.";
		$mail->WordWrap = 50;
	
		if(!$mail->Send())
		{
	  		$error="Message was not sent. Mailer error: ".$mail->ErrorInfo."";	
		}	
		return $error;
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
		
		if(!mysqli_num_rows($data)) 
		{
			return false;
		} 
		else 
		{
			$row = mysqli_fetch_array($data);
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
		if(!mysqli_num_rows($data)) 
		{
			return 0;
		} 
		else 
		{
			$row = mysqli_fetch_array($data);
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
	

	function handle_quick_dates($use_all=0) 
	{
		global $quick_date_range_array;
		
		if(!isset($_POST['date_from'])) 					$_POST['date_from'] = '';
		if(!isset($_POST['date_to'])) 					$_POST['date_to'] = '';
		if(!isset($_POST['limit_records_to'])) 				$_POST['limit_records_to'] = 2000;				
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
				$_POST['date_from'] = date("01/01/2015", time());
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
				
		//var $show_cust_filter 		= false;
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
			<table class='input_area mrr_no_print' style='width:100%'>
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
					<td><input type='submit' name='quick_date' value='Build Quick Date Range' class='btn btn-default add_new_btn'></td>
				</tr>
				<tr>
					<td colspan='2'></td>
				</tr>
				<tr>
					<td style='width:120px'><span class='highlight'>Custom Date Range</span></td>
					<td colspan='2'>
						From <input name='date_from' id='date_from' value='<?=$_POST['date_from']?>' class='veryshort datepicker'>
						To <input name='date_to' id='date_to' value='<?=$_POST['date_to']?>' class='veryshort datepicker'>
					</td>
					<td>&nbsp; </td>
					<td><input type='submit' name='custom_date' value='Build Custom Date Range' class='buttonize btn btn-default add_new_btn'></td>
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
					<td><input type='submit' value='Submit' class='buttonize btn btn-default add_new_btn'></td>
				</tr>
			<? } ?>
			</table>
			<? if(!$this->leave_form_open) { ?>
				</form>
			<? } ?>			
			<script type='text/javascript'>
				$().ready(function() {
					//$('#report_filter_user').autocomplete('ajax.php?cmd=search_users',{formatItem:formatItem});
					$('#date_from').datepicker();
					$('#date_to').datepicker();
					//$('.buttonize').button();
					//$("select").selectmenu();
				});				
			</script>
			<?
		}
	}
		
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
		$row = mysqli_fetch_array($data);
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
			$row_last = mysqli_fetch_array($data_last);
			
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
		
		while($row = mysqli_fetch_array($data)) 
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
		if($row = mysqli_fetch_array($data))
		{
			$id=$row['id'];	
		}
		
		return $id;
	}
	
	function get_merchant_select_box($field,$pre=0,$cd=0,$prompt="",$classy="",$assign_files_section=0)
     {	 
     	$selbox="<select name='".$field."' id='".$field."'".$classy.">";
     	
     	if($pre==0)		$sel=" selected";		else	$sel="";
     	$selbox.="<option value='0'".$sel.">".$prompt."</option>";	
     			
     	$mrr_adder="where deleted=0";		// and access_level <= '".sql_friendly($_SESSION['view_access_level'])."' 
     	if($_SESSION['selected_merchant_id'] > 0)	
     	{
     		if($_SESSION['access_level'] < 70) 	$mrr_adder.=" and id='".sql_friendly($_SESSION['selected_merchant_id'])."'";
		} 
		elseif($_SESSION['merchant_id'] > 0)	
     	{
     		$mrr_adder.=" and id='".sql_friendly($_SESSION['merchant_id'])."'";
		}     	     
			
     	if($cd ==1)	$mrr_adder.=" and archived>0";		else		$mrr_adder.=" and archived=0";
     	     	
     	$sql = "
     		select * 
     		from merchants			
     		".$mrr_adder."
     		
     		order by merchant asc,id asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		$namer="".$row['merchant']."";	
     		
     		if($pre==$row['id'])		$sel=" selected";		else	$sel="";
     		$selbox.="<option value='".$row['id']."'".$sel.">".$namer."</option>";	
     		
     		//add subsidiaries to the list from this parent company
     		if($_SESSION['merchant_id'] > 0 && $_SESSION['access_level'] >= 70 )
     		{
     			$selbox.=mrr_get_merchant_teirs_selected($row['id'],$pre);	
     		}
     	}     	
     	
     	$selbox.="</select>";
     	
     	if($_SESSION['merchant_id']==0 && $_SESSION['selected_merchant_id']==0 && $pre==0 && $assign_files_section==0 && $_SESSION['access_level'] < 70)
     	{
     		$selbox="<select name='".$field."' id='".$field."'".$classy."><option value='0' selected>New Customer</option></select>";	
     	}    	
     	
     	return $selbox;		
     }
     function mrr_get_merchant_teirs_selected($parent_id,$pre=0)
     {	//adds the teired levels of merchants to the select box options....NOT DESIGNED to be a select box on its own.
     	$sel_adder="";
     	
     	$sql = "
     		select * 
     		from merchants			
     		where deleted=0 
     			and parent_company_id='".sql_friendly($parent_id)."'    		
     		order by merchant asc,id asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		$namer="".$row['merchant']."";	
     		
     		if($pre==$row['id'])		$sel=" selected";		else	$sel="";
     		$sel_adder.="<option value='".$row['id']."'".$sel."> - - > ".$namer."</option>";	
     		
     		
     		if($_SESSION['merchant_id'] > 0)
     		{
     			$sel_adder.=mrr_get_merchant_teirs_selected($row['id'],$pre);	
     		}
     	}     	
     	
     	return $sel_adder;
     }
	function get_store_select_box($field,$pre=0,$merchant=0,$cd=0,$prompt="",$classy="",$bypass_session=0)
     {	 
     	$selbox="<select name='".$field."' id='".$field."'".$classy.">";
     	
     	if($pre==0)		$sel=" selected";		else	$sel="";
     	$selbox.="<option value='0'".$sel.">".$prompt."</option>";	
     			
     	$mrr_adder="where deleted=0";		// and access_level <= '".sql_friendly($_SESSION['view_access_level'])."'
     	
     	if($merchant > 0 && $bypass_session > 0)
     	{
     		$mrr_adder.=" and merchant_id='".sql_friendly($merchant)."'";
     	}
     	elseif($_SESSION['selected_merchant_id'] > 0)
     	{
     		$mrr_adder.=" and merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'";	
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$mrr_adder.=" and merchant_id='".sql_friendly($_SESSION['merchant_id'])."'";
     	}     	
     	     	
     	if($_SESSION['store_id'] > 0)		$mrr_adder.=" and id='".sql_friendly($_SESSION['store_id'])."'";
     	     	
     	if($cd ==1)	$mrr_adder.=" and archived>0";		else		$mrr_adder.=" and archived=0";
     	     	
     	$sql = "
     		select * 
     		from store_locations			
     		".$mrr_adder."     		
     		order by store_name asc,store_number asc
     	";
     	//echo $sql;
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		$namer="".$row['store_name']." UID: ".$row['store_number']."";	
     		
     		if($pre==$row['id'])		$sel=" selected";		else	$sel="";
     		$selbox.="<option value='".$row['id']."'".$sel." data-merch='".$row['merchant_id']."'>".$namer."</option>";	
     		
     		//add subsidiaries' stores to the list from this parent company
     		if($_SESSION['merchant_id'] > 0 && $_SESSION['access_level'] >= 70 && $bypass_session==0)
     		{
     			$selbox.=mrr_get_merchant_store_teirs_selected($row['merchant_id'],$pre,$bypass_session);	
     		}
     	}     	
     	
     	$selbox.="</select>";     	 	
     	
     	// CS 5/27/2015 - added the below if statement since the 
     	// $selbox created below was overwriting the one from above which was causing the store list to not populate
     	if($selbox == '') {
	     	if($_SESSION['store_id']==0 && $_SESSION['selected_store_id']==0 && $pre==0)
	     	{
	     		$selbox="<select name='".$field."' id='".$field."'".$classy."><option value='0' selected data-merch='".$_SESSION['selected_merchant_id']."'>New Store Location</option></select>";	
	     	} 
	     }
     	
     	return $selbox;			
     }
     function mrr_get_merchant_store_teirs_selected($parent_id,$pre=0,$bypass_session=0)
     {	//adds the teired levels of merchant stores to the select box options....NOT DESIGNED to be a select box on its own.
     	$sel_adder="";
     	
     	$sql = "
     		select id 
     		from merchants			
     		where deleted=0 
     			and parent_company_id='".sql_friendly($parent_id)."'    		
     		order by merchant asc,id asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		$merchant_id=$row['id'];     		
     		
     		//get all stores for this merchant
     		$sql2 = "
     			select * 
     			from store_locations		
     			where deleted=0 
     				and merchant_id='".sql_friendly($merchant_id)."'    		
     			order by store_name asc,store_number asc
     		";
     		$data2=simple_query($sql2);
     		while($row2=mysqli_fetch_array($data2))
     		{
          		$namer="".$row2['store_name']." UID: ".$row2['store_number']."";	
          		
          		if($pre==$row2['id'])		$sel=" selected";		else	$sel="";
          		$sel_adder.="<option value='".$row2['id']."'".$sel." data-merch='".$row2['merchant_id']."'> - - >".$namer."</option>";	   		
     		}
     		
     		//now get any subsidiaries from this merchant...recursively.
     		if($_SESSION['merchant_id'] > 0 && $bypass_session==0)
     		{
     			$sel_adder.=mrr_get_merchant_store_teirs_selected($merchant_id,$pre,$bypass_session);	
     		}
     	}     	     	
     	return $sel_adder;
     }
     
     function mrr_get_store_template_id($store_id)
     {
     	$template_id=0;
     	$sql = "
     		select template_id 
     		from store_locations		
     		where id='".sql_friendly($store_id)."'
     	";
     	$data=simple_query($sql);
     	if($row=mysqli_fetch_array($data))
     	{
     		$template_id=$row['template_id'];
     	}     	
     	return $template_id;
     }
     function mrr_get_merchant_template_id($merchant_id)
     {
     	$template_id=0;
     	$sql = "
     		select template_id 
     		from merchants			
     		where id='".sql_friendly($merchant_id)."'
     	";
     	$data=simple_query($sql);
     	if($row=mysqli_fetch_array($data))
     	{
     		$template_id=$row['template_id'];
     	}     	
     	return $template_id;
     }
     
     function mrr_get_merchant_program_title($merchant_id)
     {
     	$tab="<div id='header_cce_portal_msg'><h3>Capital Compliance Experts</h3><p>Compliance Portal</p></div>";
     	
     	$heading="<div class='welcome_heading'></div>";
     	
     	$sql = "
     		select program_title,
     			program_subtitle 
     		from merchants			
     		where id='".sql_friendly($merchant_id)."'
     	";
     	$data=simple_query($sql);
     	if($row=mysqli_fetch_array($data))
     	{
     		$tab="<div id='header_cce_portal_msg'><h3>".trim($row['program_title'])."</h3><p>".trim($row['program_subtitle'])."</p></div>";
     		
     	}    
     	
     	if(!isset($_SESSION['user_id']))
     	{
     		$tab="<div id='header_cce_portal_msg'><h3>Login / Sign Up</h3><p>Login to your existing account or create a new one.</p></div>";
     	}
     	elseif($_SESSION['user_id'] == 0)
     	{     		
     		$tab="<div id='header_cce_portal_msg'><h3>Login / Sign Up</h3><p>Login to your existing account or create a new one.</p></div>";
     	}
     	else
     	{
     		$heading="<div class='welcome_heading'>".get_welcome_by_id($_SESSION['user_id'])."</div>";	
     	}
     	 	
     	return $heading."".$tab;
     }
     
     function get_template_item_label_from_item($id)
     {
     	$item="";
     	$sql = "
     		select item_label 
     		from template_items			
     		where id='".sql_friendly($id)."'
     	";
     	$data=simple_query($sql);
     	if($row=mysqli_fetch_array($data))
     	{
     		$item=trim($row['item_label']);
     	}     	
     	return $item;	
     }
     function get_template_item_sub_id_from_item($id)
     {
     	$item_id=0;
     	$sql = "
     		select sub_group_id 
     		from template_items			
     		where id='".sql_friendly($id)."'
     	";
     	$data=simple_query($sql);
     	if($row=mysqli_fetch_array($data))
     	{
     		$item_id=$row['sub_group_id'];
     	}     	
     	return $item_id;	
     }
     function get_template_item_select_box($field,$pre=0,$store=0, $merchant=0,$cd=0,$prompt="",$classy="",$group_id=0,$all_sub_groups=0,$groups_in_temp=0,$temp_manager=0)
     {     	
     	$selbox="<select name='".$field."' id='".$field."'".$classy.">";
     	
     	if($pre==0)		$sel=" selected";		else	$sel="";
     	$selbox.="<option value='0'".$sel.">".$prompt."</option>";	    	

     	$adderx="";
     	if($groups_in_temp > 0)	
     	{
     		$adderx=" or (template_items.template_id='".sql_friendly($groups_in_temp)."' and template_items.sub_group_id=0)";
     		$group_id=0;	
     		$all_sub_groups=0;
     	}	
     	$mrr_adder=" and (template_items.template_id=1".$adderx.")";			//master template is the default.
     	$temp_id=0;
     	
     	//find merchant template first...acts as a default.
     	if($_SESSION['merchant_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['merchant_id']);     		
     		if($temp_id > 0)	$mrr_adder=" and template_items.template_id='".sql_friendly($temp_id)."'";
     	}
     	elseif($merchant > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($merchant);     		
     		if($temp_id > 0)	$mrr_adder=" and template_items.template_id='".sql_friendly($temp_id)."'";
     	}
     	
     	//find store template next...override merchant if set.
     	if($_SESSION['store_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['store_id']);     		
     		if($temp_id > 0)	$mrr_adder=" and template_items.template_id='".sql_friendly($temp_id)."'";
     	}
     	elseif($store > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($store);     		
     		if($temp_id > 0)	$mrr_adder=" and template_items.template_id='".sql_friendly($temp_id)."'";
     	}
     	    	
     	if($cd ==1)	$mrr_adder.=" and template_items.archived>0";		else		$mrr_adder.=" and template_items.archived=0";
     	
     	$mrr_adder2=" and template_items.sub_group_id = 0";
     	if($group_id > 0)				$mrr_adder2=" and template_items.sub_group_id = '".sql_friendly($group_id)."'";  
     	elseif($all_sub_groups > 0)		$mrr_adder2=" and template_items.sub_group_id > 0"; 
     	
     	if($temp_manager > 0)
     	{
     		$mrr_adder=" and template_items.template_id='".sql_friendly($groups_in_temp)."' and template_items.archived=0 and template_items.sub_group_id=0";
     		$mrr_adder2="";
     	}	
     	    	    	
     	$sql = "
     		select template_items.* 
     		from template_items
     		where template_items.deleted=0			
     			".$mrr_adder."  
     			".$mrr_adder2."      			 	
     		order by zorder asc,template_items.item_label asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		$namer="".$row['item_label']."";	
     		
     		if($pre==$row['id'])		$sel=" selected";		else	$sel="";
     		$selbox.="<option value='".$row['id']."'".$sel." data-optgrp='".$row['sub_group_id']."'>".$namer."</option>";	
     	}     	
     	
     	$selbox.="</select>";
     	return $selbox;
     }
     function get_template_select_box($field,$pre=0,$store=0, $merchant=0,$cd=0,$prompt="",$classy="")
     {     	
     	$selbox="<select name='".$field."' id='".$field."'".$classy.">";
     	
     	if($pre==0)		$sel=" selected";		else	$sel="";
     	$selbox.="<option value='0'".$sel.">".$prompt."</option>";	
     			
     	$mrr_adder="";		
     	$temp_id=0;
     	
     	//find merchant template first...acts as a default.
     	if($_SESSION['merchant_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['merchant_id']);     		
     		if($temp_id > 0)	$mrr_adder=" and id='".sql_friendly($temp_id)."'";
     	}
     	elseif($merchant > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($merchant);     		
     		if($temp_id > 0)	$mrr_adder=" and id='".sql_friendly($temp_id)."'";
     	}
     	
     	//find store template next...override merchant if set.
     	if($_SESSION['store_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['store_id']);     		
     		if($temp_id > 0)	$mrr_adder=" and id='".sql_friendly($temp_id)."'";
     	}
     	elseif($store > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($store);     		
     		if($temp_id > 0)	$mrr_adder=" and id='".sql_friendly($temp_id)."'";
     	}
     	    	
     	if($cd ==1)	$mrr_adder.=" and archived>0";		else		$mrr_adder.=" and archived=0";
     	     	
     	$sql = "
     		select * 
     		from templates
     		where deleted=0			
     			".$mrr_adder."  
     		order by template_name asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		$namer="".$row['template_name']."";	
     		
     		if($pre==$row['id'])		$sel=" selected";		else	$sel="";
     		$selbox.="<option value='".$row['id']."'".$sel.">".$namer."</option>";	
     	}     	
     	
     	$selbox.="</select>";
     	return $selbox;
     }  
     
	function get_user_select_box($field,$pre=0,$cd=0,$prompt="",$classy="")
     {	
     	$selbox="<select name='".$field."' id='".$field."'".$classy.">";
     	
     	if($pre==0)		$sel=" selected";		else	$sel="";
     	$selbox.="<option value='0'".$sel.">".$prompt."</option>";	
     			
     	$mrr_adder="where deleted=0";		// and access_level <= '".sql_friendly($_SESSION['view_access_level'])."'
     	     	
     	//get preselected user id...for merchant and store selected
     	if($field=="ms_co_user_id" || $field=="ms_grp_user_id" || $field=="mst_cm_user_id")
     	{
			/*
			if($field=="ms_co_user_id" || $field=="ms_grp_user_id" || $field=="mst_cm_user_id")
			{
     			if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
               	{
               		$mrr_adder.=" and merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."' and merchant_id > 0";
               	}
               	elseif($_SESSION['merchant_id'] > 0)
               	{
               		$mrr_adder.=" and merchant_id='".sql_friendly($_SESSION['merchant_id'])."' and merchant_id > 0";
               	}   
               	else
               	{
               		$mrr_adder.=" and merchant_id = 0 and merchant_id > 0";	
               	} 
          	}      	
		
     		if($field=="mst_cm_user_id")
     		{
               	if($_SESSION['store_id'] ==0 && $_SESSION['selected_store_id'] > 0)
               	{
               		//$mrr_adder.=" and store_id='".sql_friendly($_SESSION['selected_store_id'])."' and store_id > 0";
               	}
               	elseif($_SESSION['store_id'] > 0)	
               	{
               		//$mrr_adder.=" and store_id='".sql_friendly($_SESSION['store_id'])."' and store_id > 0";
               	} 
               	else
               	{
               		//$mrr_adder.=" and store_id = 0 and store_id > 0";		//
               	} 
     		}
     		*/
     		
     		if($field=="ms_co_user_id")
			{
     			if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
               	{
               		$mrr_adder.=" and (
               						(merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."' and merchant_id > 0)
               						 or 
               						 (merchant_id=0 and access_level='61')
               						 or
               						 id='".sql_friendly($_SESSION['selected_user_id'])."'
               						 or 
               						 access_level>=80
               					)";
               	}
               	elseif($_SESSION['merchant_id'] > 0)
               	{
               		$mrr_adder.=" and ((merchant_id='".sql_friendly($_SESSION['merchant_id'])."' and merchant_id > 0) or access_level>=80)";
               	}   
               	else
               	{
               		$mrr_adder.=" and ((merchant_id = 0 and merchant_id > 0) or access_level>=80)";	
               	} 
               	
               	
               	if($_SESSION['selected_user_id'] > 0)
               	{
               		$mrr_adder=" where deleted=0 and (id='".sql_friendly($_SESSION['selected_user_id'])."' or (merchant_id=0 and access_level='61') or access_level>=80)";
               	}
          	}      	
			if($field=="ms_grp_user_id")
			{
     			if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
               	{
               		$mrr_adder.=" and (
               						(merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."' and merchant_id > 0)
               						 or 
               						 (merchant_id=0 and access_level='70')
               						 or
               						 id='".sql_friendly($_SESSION['selected_user_id'])."'
               						 or 
               						 access_level>=80
               					)";
               	}
               	elseif($_SESSION['merchant_id'] > 0)
               	{
               		$mrr_adder.=" and ((merchant_id='".sql_friendly($_SESSION['merchant_id'])."' and merchant_id > 0) or access_level>=80)";
               	}   
               	else
               	{
               		$mrr_adder.=" and ((merchant_id = 0 and merchant_id > 0)  or access_level>=80)";	
               	} 
               	
               	
               	if($_SESSION['selected_user_id'] > 0)
               	{
               		$mrr_adder=" where deleted=0 and (id='".sql_friendly($_SESSION['selected_user_id'])."' or (merchant_id=0 and access_level='70') or access_level>=80)";
               	}
          	}  
     		if($field=="mst_cm_user_id")
     		{
               	//merchant is set...
               	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
               	{
               		$mrr_adder.=" and ((merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."' and merchant_id > 0)  or access_level>=80)";
               	}
               	elseif($_SESSION['merchant_id'] > 0)
               	{
               		$mrr_adder.=" and ((merchant_id='".sql_friendly($_SESSION['merchant_id'])."' and merchant_id > 0)  or access_level>=80)";
               	}   
               	else
               	{
               		$mrr_adder.=" and ((merchant_id = 0 and merchant_id > 0)  or access_level>=80)";	
               	} 
               	
               	//...store
               	/*
               	if($_SESSION['store_id'] ==0 && $_SESSION['selected_store_id'] > 0)
               	{
               	   	$mrr_adder.=" and (
          							(store_id='".sql_friendly($_SESSION['selected_store_id'])."' or store_id='0')
          						 	
               				)";		// and access_level='40'	or id='".sql_friendly($_SESSION['selected_user_id'])."'	
               	}
               	elseif($_SESSION['store_id'] ==0 && $_SESSION['selected_store_id'] == 0)
               	{
               	   	$mrr_adder.=" and (
          							store_id=0
          						 	or
               					 	id='".sql_friendly($_SESSION['selected_user_id'])."'
               				)";
               	}
               	elseif($_SESSION['store_id'] > 0)	
               	{
               		$mrr_adder.=" and store_id='".sql_friendly($_SESSION['store_id'])."' and store_id > 0";
               	} 
               	*/
               	
               	if($_SESSION['selected_user_id'] > 0)
               	{
               		$mrr_adder=" where deleted=0 and (id='".sql_friendly($_SESSION['selected_user_id'])."' or (merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'))";	// and store_id=0 and access_level='40'
               	}
               	
               	/*               	
               	if($_SESSION['store_id'] ==0 && $_SESSION['selected_store_id'] > 0)
               	{
               		//$mrr_adder.=" and store_id='".sql_friendly($_SESSION['selected_store_id'])."' and store_id > 0";
               	}
               	elseif($_SESSION['store_id'] > 0)	
               	{
               		//$mrr_adder.=" and store_id='".sql_friendly($_SESSION['store_id'])."' and store_id > 0";
               	} 
               	else
               	{
               		//$mrr_adder.=" and store_id = 0 and store_id > 0";		//
               	} 
               	*/
     		}
		}
     	else
     	{          		
          	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
          	{
          		$mrr_adder.=" and (merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."' or access_level>=80)";
          	}
          	elseif($_SESSION['merchant_id'] > 0)
          	{
          		$mrr_adder.=" and (merchant_id='".sql_friendly($_SESSION['merchant_id'])."' or access_level>=80)";
          	}
          	else
          	{
          		$mrr_adder.=" and (merchant_id = 0 or access_level>=80)";	
          	} 
          	/*
          	if($_SESSION['store_id'] ==0 && $_SESSION['selected_store_id'] > 0)
          	{
          		$mrr_adder.=" and store_id='".sql_friendly($_SESSION['selected_store_id'])."'";
          	}
          	elseif($_SESSION['store_id'] > 0)	
          	{
          		$mrr_adder.=" and store_id='".sql_friendly($_SESSION['store_id'])."'";
          	}  
          	else
          	{
          		$mrr_adder.=" and store_id = 0";	
          	}     
          	*/    			
     	}
     	
     	if($cd ==1)	$mrr_adder.=" and archived>0";		else		$mrr_adder.=" and archived=0";  
     	   	
     	$sql = "
     		select * 
     		from users			
     		".$mrr_adder."     		
     		order by last_name asc,
     			first_name asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		$namer="".$row['first_name']." ".$row['last_name']."";	
     		if(trim($namer)=="")		$namer=$row['username'];
     		
     		if($pre==$row['id'])		$sel=" selected";		else	$sel="";
     		$selbox.="<option value='".$row['id']."'".$sel.">".$namer."</option>";	
     	}     	
     	
     	$selbox.="</select>";
     	return $selbox;			
     }
	
	
	function get_access_select_box($access,$field,$pre=0,$cd=0,$prompt="",$classy="")
     {	     	
     	$selbox="<select name='".$field."' id='".$field."'".$classy.">";
     	
     	if($pre==0)		$sel=" selected";		else	$sel="";
     	$selbox.="<option value='0'".$sel.">".$prompt."</option>";	
     			
     	$mrr_adder="and access_level <= '".$access."'";
     	
     	if($cd > 0)	$mrr_adder.=" and archived>0";     	    	
     	
     	$sql = "
     		select * 
     		from user_levels	
     		where user_levels.deleted=0		
     			".$mrr_adder."
     		order by access_level desc,level_name asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		if($pre==$row['access_level'])		$sel=" selected";		else	$sel="";
     		$selbox.="<option value='".$row['access_level']."'".$sel.">".$row['level_name']."</option>";	
     	}     	
     	
     	$selbox.="</select>";
     	return $selbox;			
     }
     function get_access_display_name($id)
     {
     	$name="";
     	$sql = "
     		select level_name 
     		from user_levels	
     		where id='".sql_friendly($id)."'
     	";
     	$data=simple_query($sql);
     	if($row=mysqli_fetch_array($data))
     	{
     		$name=trim($row['level_name']);
     	}     	
     	return $name;	
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
	
	
	function mrr_cookie_login_for_session($user_id=0)
	{
		global $defaultsarray;
		$secs=(int) $defaultsarray['session_timeout'];		
		$mrr_cookie_bake= time() +  $secs;				  		/* expires in SECS */
			
		if($user_id>0)
		{
			$sql = "
				select users.*,	
					(select view_access from user_levels where user_levels.access_level=users.access_level) as view_access_level				
				from users				
				where users.id = '" . sql_friendly($user_id) . "'
					and users.archived =0 
					and users.deleted = 0
			";			
			$data = simple_query($sql);
			$row = mysqli_fetch_array($data);	
			
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['username'] = $row['username'];
			$_SESSION['access_level'] = $row['access_level'];
			$_SESSION['view_access_level'] = 0;
			if($row['access_level'] > 0)			$_SESSION['view_access_level'] = $row['view_access_level'];
			
			$_SESSION['merchant_id']=$row['merchant_id'];
			$_SESSION['store_id']=$row['store_id'];
			
			$_SESSION['reset_password']=$row['reset_password'];
			
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
     			select users.* ,	
					(select view_access from user_levels where user_levels.access_level=users.access_level) as view_access_level    				
     			from users     			
     			where username = '" . sql_friendly($user) . "'     				
     				and users.archived=0 and users.deleted = 0
     		";		//and password = '" . sql_friendly(mrr_encryptor($pass,$user)) . "'     			
     		$data = simple_query($sql);
     		if($row = mysqli_fetch_array($data))
     		{
     			$mrr_location=$row['location_id'];
     			// be sure to put any additional SESSION variables on the order_review "successful login" section
				$_SESSION['user_id'] = $row['id'];				
				$_SESSION['username'] = $row['username'];
				$_SESSION['access_level'] = $row['access_level'];
				$_SESSION['view_access_level'] = 0;
				if($row['access_level'] > 0)			$_SESSION['view_access_level'] = $row['view_access_level'];
								
				$_SESSION['merchant_id']=$row['merchant_id'];
				$_SESSION['store_id']=$row['store_id'];
				
				$_SESSION['reset_password']=$row['reset_password'];
				
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
	
	function mrr_encryptor($pepper,$salt)
	{
		return crypt($pepper ,$salt);
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
     	while($row=mysqli_fetch_array($data))
     	{
     		if($pre==$row['state_code'])		$sel=" selected";		else	$sel="";
     		$selbox.="<option value='".$row['state_code']."'".$sel." style='text-align:left;'>".$row['state_description']."</option>";	
     	}     	
     	
     	$selbox.="</select>";
     	return $selbox;			
     }     
     
	function is_logged_in() {
		if(isset($_SESSION['access_level']) && isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
			$logged_in = true;
		} else {
			$logged_in = false;
		}
		
		return $logged_in;
	}
	
	
	class upload_section {
				
		var $section_id = 0; 			// what type of document this is (i.e. user avatar, main document, company attachment, etc...)
		var $xref_id; 					// stores the ID of the user this document is for, which could be different than who is uploading it (example, admin uploading for a user)
		var $display_style = 0;			// if specified, we'll use this as our main display (visual / styles, etc...
		var $display_text = "Upload Documents"; // name to show in the text field
		private $extra_params= array(); 	// stores all our extra params
		private $user_id; 				// stores the ID of the user uploading the document
		private $uuid;
		
		
		function __construct() {
			$this->param('show_success_notice', true); // default the "growl" notice on successful upload
			$this->user_id = $_SESSION['user_id']; 
			$this->xref_id = $_SESSION['user_id'];
			
			// create a uuid for this upload section to keep track of our sessions (in case the user has multiple tabs open, we need to have
			// some unique IDs so one tab session doesn't interfere with the other.
			$this->uuid = uniqid();
		}

		// public function to add to our 'extra_params' variable
		function param($param_name, $param_value) {
			$this->extra_params[$param_name] = $param_value;
		}
		
		// let's show it!
		function show() {
			
			// keep track of how many upload sections we've created
			global $upload_counter;
			$upload_counter++;
			
			// create a session variable to keep track of all the parameters set for this upload section
			$_SESSION['upload_params'][$this->uuid]['extra_params'] = $this->extra_params;
			$_SESSION['upload_params'][$this->uuid]['user_id'] = $this->user_id;
			$_SESSION['upload_params'][$this->uuid]['section_id'] = $this->section_id;
			$_SESSION['upload_params'][$this->uuid]['xref_id'] = $this->xref_id;
						
			//echo htmlspecialchars(serialize($_SESSION['upload_params'][$this->uuid]));
			
			$use_label=$this->display_text;
			if($this->section_id == 8)		$use_label="Upload Photo";		//Avatar....
		
			?>
			
			<form class="upload <?=($this->display_style == 0 ? "upload_style" : "")?>" name='form_upload_<?=$this->uuid?>' method="post" action="includes/mini_upload/upload.php?upcounter=<?=$upload_counter?>&uuid=<?=$this->uuid?>" enctype="multipart/form-data">

				<form class="upload" name='form_upload_<?=$this->uuid?>' method="post" action="includes/mini_upload/upload.php?upcounter=<?=$upload_counter?>&uuid=<?=$this->uuid?>" enctype="multipart/form-data">
					<div class="drop <?=($this->display_style == 0 ? "drop_style" : "")?>">
						<? if($this->display_style == 0) { ?>
							<span class='upload_document_label'><?= $use_label ?></span>
							<a>Browse</a>
						<? } else { ?>
							<button type='button' class='btn btn-default navbar-btn upload_btn'><?= $use_label ?></button>
						<? } ?>
						
						<input type="file" name="upl_<?=$this->uuid?>" id="upl_<?=$this->uuid?>" multiple />						
					</div>		
					<ul>
						<!-- The file uploads will be shown here -->
					</ul>		
				</form>

			<?
		}
	}
	
	
	function cce_system_messages($merchant_id,$store_id)
	{
		if($merchant_id==0 && $_SESSION['selected_merchant_id'] > 0)		$merchant_id= $_SESSION['selected_merchant_id'];
		if($store_id==0 && $_SESSION['selected_store_id'] > 0)				$store_id=$_SESSION['selected_store_id'];
				
		$mtab="";
		$mtab1="";		//holds system message so it can be removed without removing merchant/store messages.
		$mrr_adder1=" and section!='tagline' and merchant_id='0' and store_id='0'";
		$mrr_adder2=" and section!='tagline' and merchant_id>0 and merchant_id='".sql_friendly($merchant_id)."'";		
		$mrr_adder3=" and section!='tagline' and merchant_id>0 and merchant_id='".sql_friendly($merchant_id)."' and store_id>0 and store_id='".sql_friendly($store_id)."'";
		
		$cce=0;
		$merch=0;
		$store=0;
		$edit_only="";
		
		$sql = "
     		select * 
     		from cce_messages			
     		where deleted=0
     			".$mrr_adder1."     		
     		order by merchant_id asc,store_id asc,linedate_added desc
     		limit 1
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{     
     		//see if user can edit this...
     		$res_tab=mrr_display_cce_message_pad($row['id']);
     		$editor="";
     		if(trim($res_tab)!="")
     		{
     			$editor="
     				<div style='float:right; margin-right:1px;'>
     					<i class='fa fa-pencil' title='Edit CCE Message' onClick='allow_cce_message_edit(".$row['id'].",1);'></i>
     				</div>
     			";
     		}
     		
     		if(trim($row['subject'])!="")		$cce++;
     		
     		//display normal message...
     		$mtab1.="
     			<div class='cce_message_display'>
     				<div class='cce_message_display_hdr'><span id='cce_sub_".$row['id']."'>".$row['subject']."</span></div>
     				<div class='cce_message_display_txt'><span id='cce_msg_".$row['id']."'>".$row['message']."</span>".$editor."</div>
     			</div>
     			".$res_tab."
     		";	
     	}      	
     	
     	$sql = "
     		select * 
     		from cce_messages			
     		where deleted=0
     			".$mrr_adder2."     		
     		order by merchant_id asc,store_id asc,linedate_added desc
     		limit 1
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{     		
     		//$mtab1="";		//clear the system message if merchant message is available.
     		
     		//see if user can edit this...
     		$res_tab=mrr_display_cce_message_pad($row['id']);
     		$editor="";
     		if(trim($res_tab)!="")
     		{
     			$editor="
	     			<div style='float:right; margin-right:1px;'>
	     				<i class='fa fa-pencil' title='Edit Customer Message' onClick='allow_cce_message_edit(".$row['id'].",2);'></i> 
	     			</div>
     			";
     			$edit_only.=$editor.$res_tab;
     		}
     		
     		if(trim($row['subject'])!="")		$merch++;
     		
     		//display normal message...
     		if(trim($row['subject'])!="" || trim($row['message'])!="")
     		{
     			$mtab.="
     				<div class='cce_message_display'>
     					<div class='cce_message_display_hdr'><span id='cce_sub_".$row['id']."'>".$row['subject']."</span></div>
     					<div class='cce_message_display_txt'><span id='cce_msg_".$row['id']."'>".$row['message']."</span></div>
     				</div>
     			";
     		}	
     	} 
     	
     	$sql = "
     		select * 
     		from cce_messages			
     		where deleted=0
     			".$mrr_adder3."     		
     		order by merchant_id asc,store_id asc,linedate_added desc
     		limit 1
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{     		
     		//$mtab1="";		//clear the system message if store message is available.
     		
     		//see if user can edit this...
     		$res_tab=mrr_display_cce_message_pad($row['id']);
     		$editor="";
     		if(trim($res_tab)!="")
     		{
     			$editor=" 
	     			<div style='float:right; margin-right:1px;'>
	     				<i class='fa fa-pencil' title='Edit Store Location Message' onClick='allow_cce_message_edit(".$row['id'].",3);'></i> 
	     			</div>
     			";
     			$edit_only.=$editor.$res_tab;
     		}
     		
     		if(trim($row['subject'])!="")	{	$store++;		}
     		
     		//display normal message...
     		if(trim($row['subject'])!="" || trim($row['message'])!="")
     		{
     			$mtab.="
     				<div class='cce_message_display'>
     					<div class='cce_message_display_hdr'><span id='cce_sub_".$row['id']."'>".$row['subject']."</span></div>
     					<div class='cce_message_display_txt'><span id='cce_msg_".$row['id']."'>".$row['message']."</span></div>
     				</div>
     			";
     		}	
     	} 
     	
     	$res_tab="";
     	
     	if($merch > 0 || $store > 0)
     	{
     		 $res_tab="".$mtab."<div style='float:right; margin-right:1px;'>".$edit_only."</div>";
     	}
     	else
     	{
     		$res_tab="".$mtab1."<div class='cce_message_display'>".$edit_only."</div>";
     	}
     	
     	return $res_tab;
	}
	
	function mrr_search_docs($filter="",$view_user_access=0)
	{
		global $date_display;
		
		$tab="";
		
		$tab.="
			<table class='tablesorter'>
     		<thead>
     		<tr>	     			
     			<th>Public Name</th>
     			<th>File Name</th>
     			<th>Uploaded</th>
     			<th>Display</th>     			
     			<th>Username</th>
     			<th>First Name</th>
     			<th>Last Name</th>
     		</tr>
     		</thead>
     		<tbody>
		";
		
	     if($view_user_access==0)		$view_user_access=1;     
	     
	     $search_filer="";
	     
	     if(trim($_POST['search_doc'])!="")	
	     {
	     	$search_filer.=" and ( 
	     			attached_files.filename like '%".sql_friendly($_POST['search_doc'])."%'
	     			or 
	     			attached_files.public_name like '%".sql_friendly($_POST['search_doc'])."%'
	     			)";	
	     }
	     $merchant=0;
	     $store=0;
	     if(isset($_SESSION['merchant_id']))		$merchant=$_SESSION['merchant_id'];
		if(isset($_SESSION['store_id']))			$store=$_SESSION['store_id'];
	     
	     $cntr=0;
	     
	     $sql="
	     	select attached_files.*,
	     		users.first_name,
	     		users.last_name,
	     		users.username
	     	from attached_files
	     		left join users on users.id=attached_files.user_id
	     	where attached_files.deleted=0
	     		and attached_files.access_level <= '".sql_friendly($view_user_access)."' 
	     		".($merchant > 0 ? " and attached_files.merchant_id='".sql_friendly($merchant)."'" : "")."
	     		".($store > 0 ? " and attached_files.store_id='".sql_friendly($store)."'" : "")."
	     		".$search_filer."
	     	order by attached_files.public_name asc,attached_files.filename asc, attached_files.linedate_added desc
	     ";
	     $data = simple_query($sql);
	     while($row = mysqli_fetch_array($data))
	     {
	     	$edit_mode="";
			$valid_user=check_user_edit_access('users',$row['id'],$_SESSION['user_id']);
			if($valid_user==1)		$edit_mode="readonly";				
			
			if($valid_user > 0 || $row['id']==$_SESSION['user_id'])	
			{	     	     	
     	     	$dater="";
     	     	$dater2="";
     	     	if($row['linedate_added']!="0000-00-00 00:00:00")				$dater=date($date_display,strtotime($row['linedate_added']));	     	     	
     	     	if($row['linedate_display_start']!="0000-00-00 00:00:00")		$dater2=date($date_display,strtotime($row['linedate_display_start']));	
     	     	
     	     	//select_user_id(".$row['id'].",\"".$edit_mode."\")
     	     	
     	     	$name=trim($row['filename']);  	     	
     	     	if(trim($row['public_name'])!="")		$name=trim($row['public_name']);
     	     	
          		$path="javascript:view_attached_file(".$row['section_id'].",".$row['xref_id'].",".$row['id'].")";          	
          	
          		if($row['public_flag']==1)         	$path="documents/".$row['filename'];
          		
          		$click_link="<a href='".$path."' target='_blank' onClick='set_email_view_log(".$row['id'].");'>".$name."</a>";	
     	     	
     	     	$tab.="
     				<tr style='color:".($cntr%2==0 ? "#000000"  : "#aaaaaa" )."'>
     	     			<td valign='top'>".$click_link."</td>
     	     			<td valign='top'>".$row['filename']."</td>
     	     			<td valign='top'>".$dater."</td>
     	     			<td valign='top'>".$dater2."</td>
     	     			<td valign='top'>".$row['username']."</td>
     	     			<td valign='top'>".$row['first_name']."</td>
     	     			<td valign='top'>".$row['last_name']."</td>
     	     		</tr>
     			";     
     			
     			$cntr++;	     	
	     	}
	     }	
     	     
    		$tab.="
			</tbody>
		</table>
		";
		
		return $tab;
	}
	
	function mrr_merchant_archive()
	{
		global $date_display;	
		
		$merchant=0;
	     if($_SESSION['merchant_id']==0 && $_SESSION['selected_merchant_id'] > 0)
	     {
	     	//$merchant=$_SESSION['selected_merchant_id'];
	     }
	     elseif($_SESSION['merchant_id'] > 0)
	     {
	     	$merchant=$_SESSION['merchant_id'];	
	     }
	     $store=0;
	     if($_SESSION['store_id']==0 && $_SESSION['selected_store_id'] > 0)
	     {
	     	$store=$_SESSION['selected_store_id'];
	     }
	     elseif($_SESSION['store_id'] > 0)
	     {
	     	$store=$_SESSION['store_id'];	
	     }
	     
	     
	     //$_SESSION['access_level'] = $row['access_level'];
		//$_SESSION['view_access_level'] = 0;
	     
	     $tab="
	     	<table class='tablesorterx' width='1300'> 
			<thead> 
				<tr> 
					<th>CID<br>&nbsp;</th>
					<th>CUSTOMER<br>&nbsp;</th>
					<th>UID<br>&nbsp;</th>
					<th>DBA NAME<br>&nbsp;</th>
					<th nowrap>FIRST NAME<br>DATE</th>
					<th nowrap>LAST NAME<br>DESCRIPTION</th>
					<th nowrap>USERNAME<br>&nbsp;</th>
					<th>TYPE<br>&nbsp;</th>
					<th>&nbsp;</th>
				</tr> 
			</thead>
			<tbody>
		";	     
	     
	     $sql="
	     	select merchants.* 
	     	from merchants
	     	where merchants.deleted>=0
	     		".($merchant > 0 ? " and merchants.id='".sql_friendly($merchant)."'" : "")."	
	     	order by merchants.merchant asc
	     ";
	     $data = simple_query($sql);
	     while($row = mysqli_fetch_array($data))
	     {
	     	if($row['archived'] > 0 || $row['deleted'] > 0)
	     	{
	     	    	$tab.="
	     	    	<tr class='unarchive_merchant_".$row['id']."'>
	     			<td valign='top'>".$row['id']."</td>
	     			<td valign='top'>".trim($row['merchant'])."</td>
	     			<td valign='top'>&nbsp;</td>
	     			<td valign='top'>&nbsp;</td>
	     			<td valign='top'>&nbsp;</td>
	     			<td valign='top'>&nbsp;</td>
	     			<td valign='top'>&nbsp;</td>
	     			<td valign='top'>Customer</td>
	     			<td valign='top'><button type='submit' class='btn btn-default add_new_btn' onClick='edit_merchant(".$row['id'].",4);'>UN-ARCHIVE</button></td>
				</tr>
				";
			}	
			
			//get archived stores for this merchant
     		$sql2="
     	     	select store_locations.* 
     	     	from store_locations
     	     	where store_locations.merchant_id='".sql_friendly($row['id'])."'
     	     		".($store > 0 ? " and store_locations.id='".sql_friendly($store)."'" : "")."
     	     	order by store_locations.store_name asc,store_number asc
     	     ";
     	     $data2 = simple_query($sql2);
     	     while($row2 = mysqli_fetch_array($data2))
     	     {
     	     	if($row2['archived'] > 0 || $row2['deleted'] > 0)
     	     	{
     	     	    	$tab.="
     	     	    	<tr class='unarchive_store_".$row2['id']."'>
     	     			<td valign='top'>".$row['id']."</td>
     	     			<td valign='top'>".trim($row['merchant'])."</td>
     	     			<td valign='top'>".trim($row2['store_number'])."</td>
     	     			<td valign='top'>".trim($row2['store_name'])."</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>Store</td>
     	     			<td valign='top'><button type='submit' class='btn btn-default add_new_btn' onClick='edit_store_location(".$row2['id'].",4);'>UN-ARCHIVE</button></td>
     				</tr>
     				";
     			}
     			
     			//get archived dates for this store...
     			$sql3="
          	     	select important_dates.* 
          	     	from important_dates
          	     	where (important_dates.deleted>0 or important_dates.archived > 0)
          	     		and important_dates.merchant_id='".sql_friendly($row['id'])."'
          	     		and important_dates.store_id='".sql_friendly($row2['id'])."'
          	     	order by important_dates.linedate asc,important_dates.date_description asc
          	     ";
          	     $data3 = simple_query($sql3);
          	     while($row3 = mysqli_fetch_array($data3))
          	     {          	     	
          	     	$tab.="
          	     	    	<tr class='unarchive_date_".$row3['id']."'>
          	     			<td valign='top'>".$row['id']."</td>
          	     			<td valign='top'>".trim($row['merchant'])."</td>
          	     			<td valign='top'>".trim($row2['store_number'])."</td>
          	     			<td valign='top'>".trim($row2['store_name'])."</td>
          	     			<td valign='top'>".date("M j, Y",strtotime($row3['linedate']))."</td>
          	     			<td valign='top' colspan='2'>".trim($row3['date_description'])."</td>
          	     			<td valign='top'>Date</td>
          	     			<td valign='top'><button type='submit' class='btn btn-default add_new_btn' onClick='edit_important_date(".$row3['id'].",4);'>UN-ARCHIVE</button></td>
          				</tr>
          			";
          	     }
          	     
          	     //get archived users for this store...
     			$sql3="
          	     	select users.* 
          	     	from users
          	     	where (users.deleted>0 or users.archived > 0)
          	     		and users.merchant_id='".sql_friendly($row['id'])."'
          	     		and users.store_id='".sql_friendly($row2['id'])."'
          	     	order by users.last_name asc,users.first_name asc
          	     ";
          	     $data3 = simple_query($sql3);
          	     while($row3 = mysqli_fetch_array($data3))
          	     {          	     	
          	     	$tab.="
          	     	    	<tr class='unarchive_user_".$row3['id']."'>
          	     			<td valign='top'>".$row['id']."</td>
          	     			<td valign='top'>".trim($row['merchant'])."</td>
          	     			<td valign='top'>".trim($row2['store_number'])."</td>
          	     			<td valign='top'>".trim($row2['store_name'])."</td>
          	     			<td valign='top'>".trim($row3['first_name'])."</td>
          	     			<td valign='top'>".trim($row3['last_name'])."</td>
          	     			<td valign='top'>".trim($row3['username'])."</td>
          	     			<td valign='top'>User</td>
          	     			<td valign='top'><button type='submit' class='btn btn-default add_new_btn' onClick='edit_user_account(".$row3['id'].",4);'>UN-ARCHIVE</button></td>
          				</tr>
          			";
          	     }
     	     }
     	     
			//get archived dates for this merchant...
			$sql3="
     	     	select important_dates.* 
     	     	from important_dates
     	     	where (important_dates.deleted>0 or important_dates.archived > 0)
     	     		and important_dates.merchant_id='".sql_friendly($row['id'])."'
     	     		and important_dates.store_id=0
     	     	order by important_dates.linedate asc,important_dates.date_description asc
     	     ";
     	     $data3 = simple_query($sql3);
     	     while($row3 = mysqli_fetch_array($data3))
     	     {          	     	
     	     	$tab.="
     	     	    	<tr class='unarchive_date_".$row3['id']."'>
     	     			<td valign='top'>".$row['id']."</td>
     	     			<td valign='top'>".trim($row['merchant'])."</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>".date("M j, Y",strtotime($row3['linedate']))."</td>
     	     			<td valign='top' colspan='2'>".trim($row3['date_description'])."</td>
     	     			<td valign='top'>Date</td>
     	     			<td valign='top'><button type='submit' class='btn btn-default add_new_btn' onClick='edit_important_date(".$row3['id'].",4);'>UN-ARCHIVE</button></td>
     				</tr>
     			";
     	     }
     	     
     	     //get archived users for this merchant...
			$sql3="
     	     	select users.* 
     	     	from users
     	     	where (users.deleted>0 or users.archived > 0)
     	     		and users.merchant_id='".sql_friendly($row['id'])."'
     	     		and users.store_id=0
     	     	order by users.last_name asc,users.first_name asc
     	     ";
     	     $data3 = simple_query($sql3);
     	     while($row3 = mysqli_fetch_array($data3))
     	     {          	     	
     	     	$tab.="
     	     	    	<tr class='unarchive_user_".$row3['id']."'>
     	     			<td valign='top'>".$row['id']."</td>
     	     			<td valign='top'>".trim($row['merchant'])."</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>".trim($row3['first_name'])."</td>
     	     			<td valign='top'>".trim($row3['last_name'])."</td>
     	     			<td valign='top'>".trim($row3['username'])."</td>
     	     			<td valign='top'>User</td>
     	     			<td valign='top'><button type='submit' class='btn btn-default add_new_btn' onClick='edit_user_account(".$row3['id'].",4);'>UN-ARCHIVE</button></td>
     				</tr>
     			";
     	     }    
     	     
	     }
	     
	     
	     if($merchant==0 && $store==0)
	     {     
     		//get archived dates for no merchant/store...
     		$sql3="
     	     	select important_dates.* 
     	     	from important_dates
     	     	where (important_dates.deleted>0 or important_dates.archived > 0)
     	     		and important_dates.merchant_id=0
     	     		and important_dates.store_id=0
     	     	order by important_dates.linedate asc,important_dates.date_description asc
     	     ";
     	     $data3 = simple_query($sql3);
     	     while($row3 = mysqli_fetch_array($data3))
     	     {          	     	
     	     	$tab.="
     	     	    	<tr class='unarchive_date_".$row3['id']."'>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>".date("M j, Y",strtotime($row3['linedate']))."</td>
     	     			<td valign='top' colspan='2'>".trim($row3['date_description'])."</td>
     	     			<td valign='top'>Date</td>
     	     			<td valign='top'><button type='submit' class='btn btn-default add_new_btn' onClick='edit_important_date(".$row3['id'].",4);'>UN-ARCHIVE</button></td>
     				</tr>
     			";
     	     }
     	     
     	     //get archived users for no merchant/store...
     		$sql3="
     	     	select users.* 
     	     	from users
     	     	where (users.deleted=0 or users.archived > 0)
     	     		and users.merchant_id=0
     	     		and users.store_id=0
     	     		and users.access_level<='".$_SESSION['view_access_level']."'
     	     	order by users.last_name asc,users.first_name asc
     	     ";
     	     $data3 = simple_query($sql3);
     	     while($row3 = mysqli_fetch_array($data3))
     	     {          	     	
     	     	$tab.="
     	     	    	<tr class='unarchive_user_".$row3['id']."'>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>&nbsp;</td>
     	     			<td valign='top'>".trim($row3['first_name'])."</td>
     	     			<td valign='top'>".trim($row3['last_name'])."</td>
     	     			<td valign='top'>".trim($row3['username'])."</td>
     	     			<td valign='top'>User</td>
     	     			<td valign='top'><button type='submit' class='btn btn-default add_new_btn' onClick='edit_user_account(".$row3['id'].",4);'>UN-ARCHIVE</button></td>
     				</tr>
     			";
     	     }	
		} 	     	     
	     $tab.="
			</tbody>
		</table>
		</div>
		";		
		return $tab;	
	}
	
	
	function mrr_search_merchant_locs($filter="",$view_user_access=0,$reload_flag=0,$show_archived=0)
	{
		global $date_display;
		
		$tab="";
		
		// separated them out into two tables so that the header would always stay visible when the user is scrolling down the list		
		/*
		$tab.="
			<div style='border:0px blue solid;float:left;width:100%;background-color:#e69b23'>
				<table class='tablesorter search_cust_header'> 
				<thead> 
					<tr> 
						<th class='search_box_cid'><div>&nbsp;&nbsp;CID #</div></th> 
						<th class='search_box_cname'><div>LEGAL NAME</div></th> 
						<th class='search_box_uid'><div>UID #</div></th> 
						<th class='search_box_dba'><div>STORE NAME</div></th> 
						<th class='search_box_addr'><div>ADDRESS</div></th> 
						<th class='search_box_city'><div>CITY</div></th> 
						<th class='search_box_state'><div>STATE</div></th> 
						
					</tr> 
				</thead>
				</table>
			</div>
			
			<div style='border:0px red solid;float:left;width:100%;max-height:120px;overflow-y:scroll'>
			<table id='myTable' class='tablesorter search_cust_body'>
			<tbody> 
		";	
		*/
		
		
		$tab.="
			<div style='border:0px blue solid;float:left;width:100%;background-color:#e69b23'>
				<table class='tablesorter search_cust_header'> 
				<thead> 
					<tr> 
						<th class='search_box_dba'><div><span class='mrr_search_indent_none'></span>STORE NAME</div></th> 
						<th class='search_box_uid'><div>UID #</div></th>
						<th class='search_box_addr'><div>ADDRESS</div></th> 
						<th class='search_box_city'><div>CITY</div></th> 
						<th class='search_box_state'><div>STATE</div></th> 
						
					</tr> 
				</thead>
				</table>
			</div>
			
			<div style='border:0px red solid;float:left;width:100%;max-height:120px;overflow-y:scroll'>
			<table id='myTable' class='tablesorter search_cust_body'>
			<tbody> 
		";		//<th>ZIP</th> 
		
		$filter = "";
		if(isset($_POST['search_cust'])) $filter=trim($_POST['search_cust']);
		
	     if($view_user_access==0)		$view_user_access=1;     
	     
	     
	     $search_filer="";
	     $search_filer2="";
	     
	     if(trim($filter)!="")	
	     {
	     	$search_filer.=" and (
	     			store_locations.store_number like '%".sql_friendly($filter)."%'
	     			or 
	     			store_locations.store_name like '%".sql_friendly($filter)."%'
	     			or 
	     			store_locations.address1 like '%".sql_friendly($filter)."%'
	     			or 
	     			store_locations.city like '%".sql_friendly($filter)."%'
	     			or 
	     			store_locations.state like '%".sql_friendly($filter)."%'
	     			or 
	     			store_locations.zip like '%".sql_friendly($filter)."%'
	     			)";	
	     	
	     	$search_filer2.=" and (
	     					(
               	     			merchants.merchant like '%".sql_friendly($filter)."%'
               	     			or 	     			
               	     			merchants.address1 like '%".sql_friendly($filter)."%'
               	     			or 
               	     			merchants.city like '%".sql_friendly($filter)."%'
               	     			or 
               	     			merchants.state like '%".sql_friendly($filter)."%'
               	     			or 
               	     			merchants.zip like '%".sql_friendly($filter)."%'
               	     		)
               	     		or
               	     		(
               	     			(
               	     				select count(*) 
               	     				from store_locations 
               	     				where store_locations.deleted=0 
               	     					and store_locations.merchant_id=merchants.id
               	     					".$search_filer."
               	     			) > 0
               	     		)               	     		
	     			)";	
	     }
	     	     
	     $merchant=0;
	     if($_SESSION['merchant_id']==0 && $_SESSION['selected_merchant_id'] > 0)
	     {
	     	$merchant=$_SESSION['selected_merchant_id'];
	     }
	     elseif($_SESSION['merchant_id'] > 0)
	     {
	     	$merchant=$_SESSION['merchant_id'];	
	     }
	     $store=0;
	     if($_SESSION['store_id']==0 && $_SESSION['selected_store_id'] > 0)
	     {
	     	//$store=$_SESSION['selected_store_id'];
	     }
	     elseif($_SESSION['store_id'] > 0)
	     {
	     	$store=$_SESSION['store_id'];	
	     }
	     
	     $cntr=0;
	     
	     $last_cid=0;
	     
	     
	     //get all merchants that have no stores  (order switched to show all customers together regardless of store number.... May 2015).
	     $sqlm="
	     	select merchants.*   		     		
	     		
	     	from merchants
	     	where merchants.deleted=0
	     		and merchants.archived=0	     		
	     		".($merchant > 0 ? " and (merchants.id='".sql_friendly($merchant)."' or merchants.parent_company_id='".sql_friendly($merchant)."')" : "")."
	     		".$search_filer2."
	     	order by merchants.merchant asc,merchants.id asc
	     ";		
	     	//,(select count(*) from store_locations where store_locations.merchant_id=merchants.id and store_locations.archived=0 and store_locations.deleted=0) as store_count	
	     	//and (select count(*) from store_locations where store_locations.merchant_id=merchants.id and store_locations.archived=0 and store_locations.deleted=0) = 0
	         	
	     $datam = simple_query($sqlm);
	     while($rowm = mysqli_fetch_array($datam))
	     {
	     	$edit_mode1="";
			$valid_user1=check_user_edit_access('merchants',$rowm['id'],$_SESSION['user_id']);
			if($valid_user1==1)		$edit_mode1="readonly";				
			
			if($valid_user1 > 0)
			{				
				$click_link1="&nbsp;";
          		if($edit_mode1=="")
          		{
          			$click_link1="<span class='mrr_link_simulator'  onclick='edit_merchant(".$rowm['id'].",1);'>
          						<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this Customer'>
          					</span>";
     	     	}								
				
     			$tab.="     			
     			<tr class='search_merch_row'> 						
						<td colspan='6' style='width:100%'>
							<div class='search_box_legal_name'>
								<span class='mrr_search_indent'>&nbsp;</span>
								<span class='mrr_search_cid'>LEGAL NAME</span> 
								<span class='mrr_link_simulator_merch' id='merch_".$rowm['id']."_legal_name' onMouseOver='mrr_search_highlighter(".$rowm['id'].",1);' onMouseOut='mrr_search_highlighter(".$rowm['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$rowm['id'].",0);' title='".str_replace("'","",trim($rowm['merchant']))."'>
									".trim($rowm['merchant'])."
								</span>
							</div>

							<div class='search_box_cid2'> 
								<span class='mrr_search_cid'>CID#</span> 
								<span class='mrr_link_simulator_merch' id='merch_".$rowm['id']."_cid_number' onMouseOver='mrr_search_highlighter(".$rowm['id'].",1);' onMouseOut='mrr_search_highlighter(".$rowm['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$rowm['id'].",0);' title='".$rowm['id']."'>
									".$rowm['id']."
								</span>
							</div>
						</td>  					
					</tr>      							
     			";  
     			$cntr++;	     			
     			
     			$sql="
          	     	select store_locations.*
          	     	from store_locations  
          	     	where store_locations.archived=0
          	     		and store_locations.deleted=0 	     		
          	     		and store_locations.merchant_id='".sql_friendly($rowm['id'])."'
          	     		".($store > 0 ? " and store_locations.id='".sql_friendly($store)."'" : "")."
          	     		".$search_filer."
          	     	order by store_locations.store_name asc, store_locations.store_number asc, store_locations.id desc
          	     ";	
          	     $data = simple_query($sql);
          	     while($row = mysqli_fetch_array($data))
          	     {          	     	
          			$edit_mode2="";
          			$valid_user2=check_user_edit_access('store_locations',$row['id'],$_SESSION['user_id']);
          			if($valid_user2==1)		$edit_mode2="readonly";	
          			
          			if($store==0)
          			{	//no store selected, so use same access as the merchant.
          				$edit_mode2=$edit_mode1;	
          				$valid_user2=$valid_user1;
          				$edit_mode2=$edit_mode1;
          			}          			
          			
          			if($valid_user2 > 0)
          			{	
          				$click_link2="&nbsp;";
                    		if($edit_mode2=="")
                    		{
                    			$click_link2="<span class='mrr_link_simulator' onclick='edit_store_location(".$row['id'].",1);'>
                    						<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this merchant store Customer'>
                    					</span>";
               	     	}
          				
               			$tab.="
               	     	<tr class='search_store_row'> 
          					<td class='search_box_dba'>
          						<div><span class='mrr_search_indent_none'></span>
          							<span class='mrr_link_simulator_store' id='store_".$row['id']."_name' onMouseOver='mrr_search_highlighter_store(".$row['id'].",1);' onMouseOut='mrr_search_highlighter_store(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['store_name'])."'>
          								".$row['store_name']."
          							</span>
          						</div>
          					</td> 
          					<td class='search_box_uid'>
          						<div>
          							<span class='mrr_link_simulator_store' id='store_".$row['id']."_num' onMouseOver='mrr_search_highlighter_store(".$row['id'].",1);' onMouseOut='mrr_search_highlighter_store(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['store_number'])."'>
          								".$row['store_number']."
          							</span>
          						</div>
          					</td> 
          					<td class='search_box_addr'>
          						
          						<div>
          							<span class='mrr_link_simulator_store' id='store_".$row['id']."_addr' onMouseOver='mrr_search_highlighter_store(".$row['id'].",1);' onMouseOut='mrr_search_highlighter_store(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['address1'])."'>
          								".$row['address1']."
          							</span>
          						</div>
          					</td> 
          					<td class='search_box_city'>
          						
          						<div>
          							<span class='mrr_link_simulator_store' id='store_".$row['id']."_city' onMouseOver='mrr_search_highlighter_store(".$row['id'].",1);' onMouseOut='mrr_search_highlighter_store(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['city'])."'>
          								".$row['city']."
          							</span>
          						</div>
          					</td> 
          					<td class='search_box_state'>
          						
          						<div>
          							<span class='mrr_link_simulator_store' id='store_".$row['id']."_state' onMouseOver='mrr_search_highlighter_store(".$row['id'].",1);' onMouseOut='mrr_search_highlighter_store(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['state'])."'>
          								".$row['state']."
          							</span>
          						</div>
          					</td> 					
          				</tr> 
               			";     //<td>".$row['zip']."</td>".(trim($row['address2'])!="" ? "<br>".$row['address2'] : "")."
               			
               			
               			//$cntr++;	    
               			  			
          			}	//end store valid access				     	
          	     } 	//end stores for this merchant
     			     			  				
			}	//end valid merchant				  
	     }	//end merchant list...
	     
	     
	     //OLD CODE BELOW...remove when no longer needed for reference...	     
	     /*
	     $sql="
	     	select store_locations.*,
	     		merchants.merchant
	     	from merchants	     		
	     		left join store_locations on store_locations.merchant_id=merchants.id
	     	where merchants.deleted=0
	     		and merchants.archived=0	     		
	     		and store_locations.archived=0
	     		and store_locations.deleted=0 	     		
	     		".($merchant > 0 ? " and store_locations.merchant_id='".sql_friendly($merchant)."'" : "")."
	     		".($store > 0 ? " and store_locations.id='".sql_friendly($store)."'" : "")."
	     		".$search_filer."
	     	order by merchants.merchant asc,store_locations.merchant_id asc, store_locations.store_name asc, store_locations.store_number asc, store_locations.id desc
	     ";		// and (store_locations.merchant_id='".sql_friendly($merchant)."' or merchants.parent_company_id='".sql_friendly($merchant)."')
	     $data = simple_query($sql);
	     while($row = mysqli_fetch_array($data))
	     {
	     	$edit_mode1="";
			$valid_user1=check_user_edit_access('merchants',$row['merchant_id'],$_SESSION['user_id']);
			if($valid_user1==1)		$edit_mode1="readonly";				
			
			$edit_mode2="";
			$valid_user2=check_user_edit_access('store_locations',$row['id'],$_SESSION['user_id']);
			if($valid_user2==1)		$edit_mode2="readonly";	
			
			if($store==0)
			{	//no store selected, so use same access as the merchant.
				$edit_mode2=$edit_mode1;	
				$valid_user2=$valid_user1;
				$edit_mode2=$edit_mode1;
			}
						
			if($valid_user1 > 0 && $valid_user2 > 0)
			{				
				$click_link1="&nbsp;";
          		if($edit_mode1=="")
          		{
          			$click_link1="<span class='mrr_link_simulator'  onclick='edit_merchant(".$row['merchant_id'].",1);'>
          						<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this Customer'>
          					</span>";
     	     	}
				
				
				$click_link2="&nbsp;";
          		if($edit_mode2=="")
          		{
          			$click_link2="<span class='mrr_link_simulator' onclick='edit_store_location(".$row['id'].",1);'>
          						<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this merchant store Customer'>
          					</span>";
     	     	}
				
				//".$click_link1."".$click_link2."
				
				
				if($last_cid!=$row['merchant_id'])
				{
					/ *
					$tab.="
     	     		<tr class='odd2'> 
						<td class='search_box_cid'><div>&nbsp;&nbsp;<span class='mrr_link_simulator'  onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",0);' title='".$row['merchant_id']."'>".$row['merchant_id']."</span></div></td> 
						<td class='search_box_cname'><div><span class='mrr_link_simulator'  onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",0);' title='".str_replace("'","",trim($row['merchant']))."'>".trim($row['merchant'])."</span></div></td> 
						<td class='search_box_uid'><div>&nbsp;</div></td> 
						<td class='search_box_dba'><div>&nbsp;</div></td> 
						<td class='search_box_addr'><div>&nbsp;</div></td> 
						<td class='search_box_city'><div>&nbsp;</div></td> 
						<td class='search_box_state'><div>&nbsp;</div></td> 					
					</tr> 
     				";     //<td>".$row['zip']."</td>".(trim($row['address2'])!="" ? "<br>".$row['address2'] : "")."
     				* /
     				$tab.="
     	     		<tr class='search_merch_row'> 						
						<td colspan='6' style='width:100%'>
							<div class='search_box_legal_name'>
								<span class='mrr_search_cid'>LEGAL NAME</span> 
								<span class='mrr_link_simulator_merch' id='merch_".$row['merchant_id']."_legal_name' onMouseOver='mrr_search_highlighter(".$row['merchant_id'].",1);' onMouseOut='mrr_search_highlighter(".$row['merchant_id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",0);' title='".str_replace("'","",trim($row['merchant']))."'>
									".trim($row['merchant'])."
								</span>
							</div>

							<div class='search_box_cid2'> 
								<span class='mrr_search_cid'>CID#</span> 
								<span class='mrr_link_simulator_merch' id='merch_".$row['merchant_id']."_cid_number' onMouseOver='mrr_search_highlighter(".$row['merchant_id'].",1);' onMouseOut='mrr_search_highlighter(".$row['merchant_id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",0);' title='".$row['merchant_id']."'>
									".$row['merchant_id']."
								</span>
							</div>
						</td>  
					
					</tr> 
     				";     //<td>".$row['zip']."</td>".(trim($row['address2'])!="" ? "<br>".$row['address2'] : "")."
				}
				
				$last_cid=$row['merchant_id'];
				/ *
     	     	$tab.="
     	     	<tr class='even2'> 
					<td class='search_box_cid'><div>&nbsp;&nbsp;&nbsp;</div></td> 
					<td class='search_box_cname'><div>&nbsp;</div></td> 
					<td class='search_box_dba'><div><span class='mrr_link_simulator'  onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['store_name'])."'>".$row['store_name']."</span></div></td> 
					<td class='search_box_uid'><div><span class='mrr_link_simulator'  onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['store_number'])."'>".$row['store_number']."</span></div></td> 
					<td class='search_box_addr'><div title='".str_replace("'","",$row['address1'])."'>".$row['address1']."</div></td> 
					<td class='search_box_city'><div title='".str_replace("'","",$row['city'])."'>".$row['city']."</div></td> 
					<td class='search_box_state'><div title='".str_replace("'","",$row['state'])."'>".$row['state']."</div></td> 					
				</tr> 
     			";     //<td>".$row['zip']."</td>".(trim($row['address2'])!="" ? "<br>".$row['address2'] : "")."
     			* /
     			$tab.="
     	     	<tr class='search_store_row'> 
					<td class='search_box_dba'>
						<div><span class='mrr_search_indent'>&nbsp;</span>
							<span class='mrr_link_simulator_store' id='store_".$row['id']."_name' onMouseOver='mrr_search_highlighter_store(".$row['id'].",1);' onMouseOut='mrr_search_highlighter_store(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['store_name'])."'>
								".$row['store_name']."
							</span>
						</div>
					</td> 
					<td class='search_box_uid'>
						<div>
							<span class='mrr_link_simulator_store' id='store_".$row['id']."_num' onMouseOver='mrr_search_highlighter_store(".$row['id'].",1);' onMouseOut='mrr_search_highlighter_store(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['store_number'])."'>
								".$row['store_number']."
							</span>
						</div>
					</td> 
					<td class='search_box_addr'>
						
						<div>
							<span class='mrr_link_simulator_store' id='store_".$row['id']."_addr' onMouseOver='mrr_search_highlighter_store(".$row['id'].",1);' onMouseOut='mrr_search_highlighter_store(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['address1'])."'>
								".$row['address1']."
							</span>
						</div>
					</td> 
					<td class='search_box_city'>
						
						<div>
							<span class='mrr_link_simulator_store' id='store_".$row['id']."_city' onMouseOver='mrr_search_highlighter_store(".$row['id'].",1);' onMouseOut='mrr_search_highlighter_store(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['city'])."'>
								".$row['city']."
							</span>
						</div>
					</td> 
					<td class='search_box_state'>
						
						<div>
							<span class='mrr_link_simulator_store' id='store_".$row['id']."_state' onMouseOver='mrr_search_highlighter_store(".$row['id'].",1);' onMouseOut='mrr_search_highlighter_store(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['state'])."'>
								".$row['state']."
							</span>
						</div>
					</td> 					
				</tr> 
     			";     //<td>".$row['zip']."</td>".(trim($row['address2'])!="" ? "<br>".$row['address2'] : "")."
     			
     			
     			$cntr++;	      			
     			
     			//now get any merchants connected to this one....
	     		//if($_SESSION['access_level'] >=70)		$tab.=mrr_cascade_merchant_locs_search($row['merchant_id'],$edit_mode1,$edit_mode2,$reload_flag,$show_archived); 	
			}					     	
	     }       
	          
	     //now get all merchants that have no stores
	     $sql="
	     	select merchants.*,
	     		(select count(*) from store_locations where store_locations.merchant_id=merchants.id and store_locations.archived=0 and store_locations.deleted=0) as store_count	     		
	     		
	     	from merchants
	     	where merchants.deleted=0
	     		and merchants.archived=0
	     		and (select count(*) from store_locations where store_locations.merchant_id=merchants.id and store_locations.archived=0 and store_locations.deleted=0) = 0
	     		".($merchant > 0 ? " and (merchants.id='".sql_friendly($merchant)."' or merchants.parent_company_id='".sql_friendly($merchant)."')" : "")."
	     		".$search_filer2."
	     	order by merchants.merchant asc,merchants.id asc
	     ";		//
	     $data = simple_query($sql);
	     while($row = mysqli_fetch_array($data))
	     {
	     	$edit_mode1="";
			$valid_user1=check_user_edit_access('merchants',$row['id'],$_SESSION['user_id']);
			if($valid_user1==1)		$edit_mode1="readonly";				
			
			if($valid_user1 > 0)
			{				
				$click_link1="&nbsp;";
          		if($edit_mode1=="")
          		{
          			$click_link1="<span class='mrr_link_simulator'  onclick='edit_merchant(".$row['id'].",1);'>
          						<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this Customer'>
          					</span>";
     	     	}								
				
				//".$click_link1."".$click_link2."
				/ *
     	     	$tab.="
     	     	<tr class='odd2'> 
					<td class='search_box_cid'><div>&nbsp;&nbsp;<span class='mrr_link_simulator'  onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['id'].",0);' title='".$row['id']."'>".$row['id']."</span></div></td> 
					<td class='search_box_cname'><div><span class='mrr_link_simulator'  onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['id'].",0);' title='".str_replace("'","",trim($row['merchant']))."'>".trim($row['merchant'])."</span></div></td> 
					<td class='search_box_uid'><div>&nbsp;</div></td> 
					<td class='search_box_dba'><div>&nbsp;</div></td> 
					<td class='search_box_addr'><div>&nbsp;</div></td> 
					<td class='search_box_city'><div>&nbsp;</div></td> 
					<td class='search_box_state'><div>&nbsp;</div></td> 
				</tr> 
     			";     
     			//<div title='".str_replace("'","",$row['address1'])."'>".$row['address1']."</div>
     			//<div title='".str_replace("'","",$row['city'])."'>".$row['city']."</div>
     			//<div title='".str_replace("'","",$row['state'])."'>".$row['state']."</div>
     			//<td>".$row['zip']."</td>".(trim($row['address2'])!="" ? "<br>".$row['address2'] : "")."
     			* /
     			
     			$tab.="
     			
     			<tr class='search_merch_row'> 						
						<td colspan='6' style='width:100%'>
							<div class='search_box_legal_name'>
								<span class='mrr_search_cid'>LEGAL NAME</span> 
								<span class='mrr_link_simulator_merch' id='merch_".$row['id']."_legal_name' onMouseOver='mrr_search_highlighter(".$row['id'].",1);' onMouseOut='mrr_search_highlighter(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['id'].",0);' title='".str_replace("'","",trim($row['merchant']))."'>
									".trim($row['merchant'])."
								</span>
							</div>

							<div class='search_box_cid2'> 
								<span class='mrr_search_cid'>CID#</span> 
								<span class='mrr_link_simulator_merch' id='merch_".$row['id']."_cid_number' onMouseOver='mrr_search_highlighter(".$row['id'].",1);' onMouseOut='mrr_search_highlighter(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['id'].",0);' title='".$row['id']."'>
									".$row['id']."
								</span>
							</div>
						</td>  
					
					</tr> 
     							
     			";       		
     			/ *
     			
     			
     	     	<tr class='search_merch_row'> 
					<td class='search_box_dba'>
						<div>&nbsp;&nbsp; <span class='mrr_search_cid'>LEGAL NAME</span> 
							<span class='mrr_link_simulator_merch' id='merch_".$row['id']."_legal_name' onMouseOver='mrr_search_highlighter(".$row['id'].",1);' onMouseOut='mrr_search_highlighter(".$row['id'].",0);'  onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['id'].",0);' title='".str_replace("'","",trim($row['merchant']))."'>
								".trim($row['merchant'])."
							</span>
						</div>
					</td> 
					<td class='search_box_uid'><div>&nbsp;</div></td> 					
					<td class='search_box_addr' align='right'>
						<div><span class='mrr_search_cid'>CID#</span> 
							<span class='mrr_link_simulator_merch' id='merch_".$row['id']."_cid_number' onMouseOver='mrr_search_highlighter(".$row['id'].",1);' onMouseOut='mrr_search_highlighter(".$row['id'].",0);' onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['id'].",0);' title='".$row['id']."'>
								".$row['id']."
							</span>
						</div>
					</td> 
					<td class='search_box_city'><div>&nbsp;</div></td> 
					<td class='search_box_state'><div>&nbsp;</div></td> 
				</tr> 
     			* /	
     			
     			$cntr++;	      			
     			
     			//now get any merchants connected to this one....
	     		//if($_SESSION['access_level'] >=70)		$tab.=mrr_cascade_merchant_locs_search($row['id'],$edit_mode1,"",$reload_flag,$show_archived); 	
			}					     	
	     }
	     */
	     	     	     
    		$tab.="
			</tbody>
		</table>
		</div>
		";
		
		return $tab;	
	}
	function mrr_cascade_merchant_locs_search($parent_id,$edit_mode1,$edit_mode2,$reload_flag=0,$show_archived=0)
	{	//not intended to run alone... recursive looping for subsidiaries based on parent company ID.
		$store_list="";
		
		$store_adder="";
		//if($_SESSION['selected_merchant_id'] > 0)			$store_adder.=" and store_location.merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'";	
		//if($_SESSION['selected_store_id'] > 0)			$store_adder.=" and store_location.id='".sql_friendly($_SESSION['selected_store_id'])."'";
		
		$search_filer=" and merchants.archived=0";			
		if($show_archived > 0)							$search_filer2=" and merchants.archived>0";	
	     		
		$sql2 = "
			select merchants.*,
				corp.merchant as parent_company 		
			from merchants
				left join merchants corp on corp.id=merchants.parent_company_id and corp.deleted=0 and corp.archived=0
			where merchants.deleted=0
				and merchants.archived=0
				and merchants.parent_company_id='".sql_friendly($parent_id)."'		
				".$search_filer."
			order by merchants.merchant asc,merchants.id asc
     	";
     	
     	//echo "$sql2<hr>";
     			
     	//d($sql2);
     	$data2=simple_query($sql2);
     	while($row2=mysqli_fetch_array($data2))
     	{
     		$merchant_id=$row2['id'];
     		$store_affiliate=trim($row2['merchant']);
     		
     		$cntrx=0;
     		
          	$sql = "
     			select *			
     			from store_locations
     			where deleted=0
     				and archived=0
     				and merchant_id='".sql_friendly($merchant_id)."'	
     				".$store_adder."		
     			order by store_name asc,store_number asc
     		";
     		$data = simple_query($sql);
     		while($row = mysqli_fetch_array($data))
     		{    			
				$click_link1="&nbsp;";
          		if($edit_mode1=="")
          		{
          			$click_link1="<span class='mrr_link_simulator'  onclick='edit_merchant(".$row['merchant_id'].",1);'>
          						<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this Customer'>
          					</span>";
     	     	}
				
				
				$click_link2="&nbsp;";
          		if($edit_mode2=="")
          		{
          			$click_link2="<span class='mrr_link_simulator' onclick='edit_store_location(".$row['id'].",1);'>
          						<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this Customer store location'>
          					</span>";
     	     	}
				
				//".$click_link1."".$click_link2."
     	     	$store_list.="
          	     	<tr class='".($cntrx%2==0 ? "even3"  : "odd3" )."'> 
     					<td class='search_box_cid'><div><span class='mrr_link_simulator'  onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",0);' title='".$row['merchant_id']."'>".$row['merchant_id']."</span></div></td> 
     					<td class='search_box_cname'><div><span class='mrr_link_simulator'  onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",0);' title='".str_replace("'","",trim($store_affiliate))."'>".$store_affiliate."</span></div></td> 
     					<td class='search_box_uid'><div><span class='mrr_link_simulator'  onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['store_number'])."'>".$row['store_number']."</span></div></td> 
     					<td class='search_box_dba'><div><span class='mrr_link_simulator'  onclick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$row['merchant_id'].",".$row['id'].");' title='".str_replace("'","",$row['store_name'])."'>".$row['store_name']."</span></div></td> 
     					<td class='search_box_addr'><div title='".str_replace("'","",$row['address1'])."'>".$row['address1']."</div></td> 
     					<td class='search_box_city'><div title='".str_replace("'","",$row['city'])."'>".$row['city']."</div></td> 
     					<td class='search_box_state'><div title='".str_replace("'","",$row['state'])."'>".$row['state']."</div></td> 
     				</tr> 
     			";     //<td>".$row['zip']."</td>".(trim($row['address2'])!="" ? "<br>".$row['address2'] : "")."
     			
     			$cntrx++; 			
     		}		
     		
     		//now get any subsidiaries' stores from this merchant...recursively.
     		//the recursive lookup seems to be stuck in a loop, or is looping more times than intended - commented out by CS
     		//if($_SESSION['access_level'] >=70)		$store_list.=mrr_cascade_merchant_locs_search($merchant_id,$edit_mode1,$edit_mode2,$reload_flag);	     		
     	}
     	return $store_list;     	
	}	
	
	
	function mrr_search_custs($filter="",$view_user_access=0)
	{
		global $date_display;
		
		$tab="";
		
		$tab.="		
			<table class='tablesorter'>
     		<thead>
     		<tr>	     			
     			<th>Customer</th>
     			<th>Group</th>
     			<th>Address 1</th>
     			<th>Address 2</th>     			
     			<th>City</th>
     			<th>State</th>
     			<th>Zip</th>
     			<th>&nbsp;</th>
     		</tr>
     		</thead>
     		<tbody>
		";
		
	     if($view_user_access==0)		$view_user_access=1;     
	     
	     $search_filer="";
	     
	     if(trim($_POST['search_cust'])!="")	
	     {
	     	$search_filer.=" and (
	     			merchants.merchant like '%".sql_friendly($_POST['search_cust'])."%'
	     			or 
	     			merchants.id='".sql_friendly((int) $_POST['search_cust'])."'
	     			or 
	     			merchants.address1='".sql_friendly( $_POST['search_cust'])."'
	     			or 
	     			merchants.city='".sql_friendly( $_POST['search_cust'])."'
	     			or 
	     			merchants.state='".sql_friendly( $_POST['search_cust'])."'
	     			or 
	     			merchants.zip='".sql_friendly( $_POST['search_cust'])."'
	     			)";	
	     }
	     $merchant=0;
	     if(isset($_SESSION['merchant_id']))		$merchant=$_SESSION['merchant_id'];
	     
	     $cntr=0;
	     
	     $sql="
	     	select merchants.*,
	     		(select cust.merchant from merchants cust where cust.id=merchants.parent_company_id and cust.deleted=0) as parent_name
	     	from merchants
	     	where merchants.deleted=0
	     		".($merchant > 0 ? " and merchants.id='".sql_friendly($merchant)."'" : "")."
	     		".$search_filer."
	     	order by merchants.merchant asc, merchants.linedate_added desc
	     ";
	     $data = simple_query($sql);
	     while($row = mysqli_fetch_array($data))
	     {
	     	$edit_mode="";
			$valid_user=check_user_edit_access('merchants',$row['id'],$_SESSION['user_id']);
			if($valid_user==1)		$edit_mode="readonly";				
			
			if($valid_user > 0 || $row['id']==$_SESSION['user_id'])	
			{	     	     	
     	     	$dater="";
     	     	$dater2="";
     	     	if($row['linedate_added']!="0000-00-00 00:00:00")				$dater=date($date_display,strtotime($row['linedate_added']));
     	     	
     	     	$name=trim($row['merchant']);
     	     	$parent="&nbsp;";
     	     	if(isset($row['parent_name']) && trim($row['parent_name'])!="")	$parent=trim($row['parent_name']); 
          		
          		$click_link="&nbsp;";
          		if($edit_mode=="")
          		{
          			$click_link="<span class='mrr_link_simulator'  onclick='edit_merchant(".$row['id'].",1);'>
          						<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this Customer'>
          					</span>";
     	     	}
     	     	
     	     	$tab.="
     				<tr style='color:".($cntr%2==0 ? "#000000"  : "#aaaaaa" )."'>
     	     			<td valign='top'><span class='mrr_link_simulator'  onclick='pick_selected_item(0,".$row['id'].",0);'>".$name."</span></td>
     	     			<td valign='top'>".$parent."</td>
     	     			<td valign='top'>".$row['address1']."</td>
     	     			<td valign='top'>".$row['address2']."</td>
     	     			<td valign='top'>".$row['city']."</td>
     	     			<td valign='top'>".$row['state']."</td>
     	     			<td valign='top'>".$row['zip']."</td>
     	     			<td valign='top'>".$click_link."</td>
     	     		</tr>     	     	
     			";     
     			
     			$cntr++;	      			
     			
     			//now get any merchants connected to this one....
	     		if($_SESSION['access_level'] >=70)		$tab.=mrr_cascade_merchant_search($row['id'],$edit_mode); 	
	     	}		     	
	     }       	     
    		$tab.="
			</tbody>
		</table>
		";
		
		return $tab;
	}
	
	function mrr_cascade_merchant_search($parent_id,$edit_mode)
	{	//not intended to run alone... recursive looping for subsidiaries based on parent company ID.
		$merchant_info_list="";
		
		$sql = "
			select merchants.*,
				corp.merchant as parent_company 		
			from merchants
				left join merchants corp on corp.id=merchants.parent_company_id and corp.deleted=0
			where merchants.deleted=0
				and merchants.parent_company_id='".sql_friendly($parent_id)."'		
				and merchants.archived=0
			order by merchants.merchant asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
			$name=trim($row['merchant']);
     	     $parent="&nbsp;";
     	     if(isset($row['parent_company']) && trim($row['parent_company'])!="")		$parent=trim($row['parent_company']); 
			
			$click_link="&nbsp;";
     		if($edit_mode=="")
     		{
     			$click_link="<span class='mrr_link_simulator'  onclick='edit_merchant(".$row['id'].",1);'>
     						<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this Customer'>
     					</span>";
	     	}
	     	
			$merchant_info_list.="	
				<tr>
     	     		<td valign='top'><span class='mrr_link_simulator'  onclick='pick_selected_item(0,".$row['id'].",0);'>".$name."</span></td>
     	     		<td valign='top'>".$parent."</td>
     	     		<td valign='top'>".$row['address1']."</td>
     	     		<td valign='top'>".$row['address2']."</td>
     	     		<td valign='top'>".$row['city']."</td>
     	     		<td valign='top'>".$row['state']."</td>
     	     		<td valign='top'>".$row['zip']."</td>
     	     		<td valign='top'>".$click_link."</td>
     	     	</tr>
			";
			
			//now get any merchants connected to this one....
	     	if($_SESSION['access_level'] >=70)		$merchant_info_list.=mrr_cascade_merchant_search($row['id'],$edit_mode); 	
     	}
     	return $merchant_info_list;     	
	}
	function mrr_cascade_merchant_store_search($parent_id,$edit_mode)
	{	//not intended to run alone... recursive looping for subsidiaries based on parent company ID.
		$store_list="";
		
		$store_adder="";
		if($_SESSION['selected_merchant_id'] > 0)			$store_adder=" and store_location.merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'";	
		
		$sql2 = "
			select merchants.*,
				corp.merchant as parent_company 		
			from merchants
				left join merchants corp on corp.id=merchants.parent_company_id and corp.deleted=0
			where merchants.deleted=0
				and merchants.parent_company_id='".sql_friendly($parent_id)."'		
				and merchants.archived=0
			order by merchants.merchant asc
     	";
     	$data2=simple_query($sql2);
     	while($row2=mysqli_fetch_array($data2))
     	{
     		$merchant_id=$row2['id'];
     		$store_affiliate=trim($row2['merchant']);
     		
          	$sql = "
     			select *			
     			from store_locations
     			where deleted=0
     				and archived=0
     				and merchant_id='".sql_friendly($merchant_id)."'	
     				".$store_adder."		
     			order by store_number asc,store_name asc
     		";
     		$data = simple_query($sql);
     		while($row = mysqli_fetch_array($data))
     		{     			  			
     			$click_link="&nbsp;";
          		if($edit_mode=="")
          		{
          			$click_link="<span class='mrr_link_simulator' onclick='edit_store_location(".$row['id'].",1);'>
          						<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this Customer store location'>
          					</span>";
     	     	}    
     			
     			$store_list.="
     				<tr>
     	     			<td valign='top'><span class='mrr_link_simulator'  onclick='pick_selected_item(0,".$row['merchant_id'].",".$row['id'].");'>".trim($row['store_number'])."</span></td>
     	     			<td valign='top'><span class='mrr_link_simulator'  onclick='pick_selected_item(0,".$row['merchant_id'].",".$row['id'].");'>".trim($row['store_name'])."</span></td>
     	     			<td valign='top'>".trim($store_affiliate)."</td>
     	     			<td valign='top'>".$row['address1']."</td>
     	     			<td valign='top'>".$row['address2']."</td>
     	     			<td valign='top'>".$row['city']."</td>
     	     			<td valign='top'>".$row['state']."</td>
     	     			<td valign='top'>".$row['zip']."</td>     	     			
     	     			<td valign='top'>".$click_link."</td>
     	     		</tr>
     			";	
     		}		
     		
     		//now get any subsidiaries' stores from this merchant...recursively.
     		if($_SESSION['access_level'] >=70)		$store_list.=mrr_cascade_merchant_store_search($merchant_id,$edit_mode);	     		
     	}
     	return $store_list;     	
	}	
	
	function mrr_search_stores($filter="",$view_user_access=0)
	{
		global $date_display;
		
		$tab="";		
		$tab.="
			<table class='tablesorter'>
     		<thead>
     		<tr>	     			
     			<th>UID#</th>
     			<th>DBA Name</th>
     			<th>Customer</th>
     			<th>Address 1</th>
     			<th>Address 2</th>     			
     			<th>City</th>
     			<th>State</th>
     			<th>Zip</th>
     			<th>&nbsp;</th>
     		</tr>
     		</thead>
     		<tbody>
		";
		
	     if($view_user_access==0)		$view_user_access=1;     
	     
	     $search_filer="";
	     
	     if(trim($_POST['search_store'])!="")	
	     {
	     	$search_filer.=" and ( 
	     			store_locations.store_number like '%".sql_friendly($_POST['search_store'])."%'
	     			or 
	     			store_locations.store_name like '%".sql_friendly($_POST['search_store'])."%'
	     			or 
	     			store_locations.address1 like '%".sql_friendly($_POST['search_store'])."%'
	     			or 
	     			store_locations.city like '%".sql_friendly($_POST['search_store'])."%'
	     			or 
	     			store_locations.state like '%".sql_friendly($_POST['search_store'])."%'
	     			or 
	     			store_locations.zip like '%".sql_friendly($_POST['search_store'])."%'
	     			)";	
	     }
	     $merchant=0;
	     $store=0;
	     if(isset($_SESSION['merchant_id']))		$merchant=$_SESSION['merchant_id'];
		if(isset($_SESSION['store_id']))			$store=$_SESSION['store_id'];
	     
	     if($_SESSION['selected_merchant_id'] > 0)	$merchant=$_SESSION['selected_merchant_id'];
	     
	     $cntr=0;
	     
	     $sql="
	     	select store_locations.*,
	     		merchants.merchant
	     	from store_locations
	     		left join merchants on merchants.id=store_locations.merchant_id
	     	where store_locations.deleted=0
	     		".($merchant > 0 ? " and store_locations.merchant_id='".sql_friendly($merchant)."'" : "")."
	     		".($store > 0 ? " and store_locations.id='".sql_friendly($store)."'" : "")."
	     		".$search_filer."
	     	order by store_locations.store_number asc,store_locations.store_name asc, merchants.merchant desc, store_locations.id desc
	     ";
	     $data = simple_query($sql);
	     while($row = mysqli_fetch_array($data))
	     {
	     	$edit_mode="";
			$valid_user=check_user_edit_access('store_locations',$row['id'],$_SESSION['user_id']);
			if($valid_user==1)		$edit_mode="readonly";				
			
			if($valid_user > 0 || $row['id']==$_SESSION['user_id'])	
			{	     	     	
     	     	//$dater="";
     	     	//if($row['linedate_added']!="0000-00-00 00:00:00")				$dater=date($date_display,strtotime($row['linedate_added']));
     	     	
     	     	$click_link="&nbsp;";
          		if($edit_mode=="")
          		{
          			$click_link="<span class='mrr_link_simulator' onclick='edit_store_location(".$row['id'].",1);'>
          						<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this Customer store location'>
          					</span>";
     	     	}
     	     	    	     	
     	     	$tab.="
     	     		<tr style='color:".($cntr%2==0 ? "#000000"  : "#aaaaaa" )."'>
     	     			<td valign='top'><span class='mrr_link_simulator'  onclick='pick_selected_item(0,".$row['merchant_id'].",".$row['id'].");'>".trim($row['store_number'])."</span></td>
     	     			<td valign='top'><span class='mrr_link_simulator'  onclick='pick_selected_item(0,".$row['merchant_id'].",".$row['id'].");'>".trim($row['store_name'])."</span></td>
     	     			<td valign='top'>".trim($row['merchant'])."</td>
     	     			<td valign='top'>".$row['address1']."</td>
     	     			<td valign='top'>".$row['address2']."</td>
     	     			<td valign='top'>".$row['city']."</td>
     	     			<td valign='top'>".$row['state']."</td>
     	     			<td valign='top'>".$row['zip']."</td>     	     			
     	     			<td valign='top'>".$click_link."</td>
     	     		</tr>
     			";          			
     			$cntr++;	   
     			
     			//now get any subsidiaries stores from this merchant...recursively.
     			if($_SESSION['access_level'] >=70 && $_SESSION['selected_merchant_id']==0)
     			{
     				//$tab.=mrr_cascade_merchant_store_search($row['merchant_id'],$edit_mode);
     			}		
     			  	
	     	}
	     }	
     	     
    		$tab.="
			</tbody>
		</table>
		";
		
		return $tab;
	}
	
	function mrr_list_users()
	{
		global $date_display;
		global $user_thumb_width;
		global $user_thumb_height;
		
		$tab="";
		
		$merchant=0;
	     if($_SESSION['merchant_id'] == 0  && $_SESSION['selected_merchant_id'] > 0)
	     {
	         	$merchant=$_SESSION['selected_merchant_id'];
		}
	     elseif($_SESSION['merchant_id'] > 0)	
	     {
	         	$merchant=$_SESSION['merchant_id'];
		}
		
		$store=0;
		if($_SESSION['store_id'] == 0  && $_SESSION['selected_store_id'])	
		{
			$store=$_SESSION['selected_store_id'];
		}
		elseif($_SESSION['store_id'] > 0)	
		{
			$store=$_SESSION['store_id'];
		}
		
		$tab.="<ul class='add_user_list'>";
		
	     $search_filer = "";
	     if(isset($_POST['user_search_filter']) && $_POST['user_search_filter'] != '') {
	     	$search_filer .= " and 
	     					(users.username like '%".sql_friendly($_POST['user_search_filter'])."%'
	     					or users.first_name like '%".sql_friendly($_POST['user_search_filter'])."%'
	     					or users.last_name like '%".sql_friendly($_POST['user_search_filter'])."%')
	     	";
	     }
	     
	     $access_adder="";
	     //$access_adder=" and users.access_level <= '".sql_friendly($_SESSION['view_access_level'])."' ";
	     //if($_SESSION['access_level'] <= 70)		$access_adder=" and users.access_level <= '".sql_friendly($_SESSION['access_level'])."' ";
	     	     
	     $sql="
	     	select users.*,
	     		(select attached_files.filename from attached_files where attached_files.deleted=0 and attached_files.xref_id=users.id and attached_files.section_id=8 order by id desc limit 1) as user_image,
	     		store_locations.store_name,
	     		store_locations.store_number,
	     		merchants.merchant,
	     		user_levels.level_name as access_name
	     	from users 
	     		left join user_levels on user_levels.access_level=users.access_level
	     		left join merchants on merchants.id=users.merchant_id
	     		left join store_locations on store_locations.id=users.store_id
	     	where users.deleted=0
	     		and users.archived=0
	     		".$access_adder."	     		
	     		".$search_filer."
	     		".($merchant > 0 ? " and (users.merchant_id='".sql_friendly($merchant)."' or users.access_level>=80)" : "")."
	     		
	     	order by first_name, last_name
	     ";	//".($store > 0 ? " and users.store_id='".sql_friendly($store)."'" : "")."
	     	//order by archived asc,users.access_level desc,last_name asc,first_name asc
	     
	     $data = simple_query($sql);
	     while($row = mysqli_fetch_array($data))
	     {     	
	     	$edit_mode="";
			$valid_user=check_user_edit_access('users',$row['id'],$_SESSION['user_id']);
			if($valid_user==1)		$edit_mode="readonly";	
			
			/*			
			if($_SESSION['merchant_id'] > 0 && $_SESSION['access_level'] ==70)
			{
				$merchx=$row['merchant_id'];
				$storex=$row['store_id'];	
				$test_sub=mrr_get_merchant_teirs_validation($_SESSION['merchant_id'],$merchx,$storex);	//see if the user's merchant ID is parent company to the checked merchant...	
				if($test_sub==0)	$valid_user=0;
			}
			*/		
			
			if($valid_user > 0 || $row['id']==$_SESSION['user_id'])	
			{	     	     	
     	     	$dater="";
     	     	if($row['linedate_login']!="0000-00-00 00:00:00")			$dater=date($date_display,strtotime($row['linedate_login']));		
     	     	$dater2="";
     	     	if($row['linedate_failed']!="0000-00-00 00:00:00")		$dater2=date($date_display,strtotime($row['linedate_failed']));	
     	     	
     	     	$row_color="#000000";		if($row['archived'] > 0)		$row_color="#aaaaaa";	
     	     	
     	     	
     	     	$edit_linker1="&nbsp;";
	     		$edit_linker2="&nbsp;";
	     		$edit_linker3="&nbsp;";
     	     	if($valid_user ==2)
     	     	{
     	     		$edit_linker1="<i class='fa fa-pencil' title='edit this user' onClick='select_user_id(".$row['id'].",\"".$edit_mode."\");'></i>";
     	     		$edit_linker2="<i class='fa fa-trash' title='delete this user' onClick='return confirm_delete(".$row['id'].");'></i>";		// onClick='select_user_id(".$row['id'].",\"".$edit_mode."\");'
     	     		//$edit_linker3="<i class='fa fa-chevron-circle-down' title='archive this user' onClick='select_user_id(".$row['id'].",\"".$edit_mode."\");'></i>";	
     	     	}
     	     	 	
     	     	if($row['user_image'] != '' && file_exists("documents/".$row['user_image'])) {
     	     		$use_image = "documents/".$row['user_image'];
     	     	} else {
     	     		$use_image = "images/no-profile-image.png";
     	     	}
     	     	//select_user_id(".$row['id'].",\"".$edit_mode."\")
     	     	$tab.="
     	     		<li class='cce_users user_id_".$row['id']." cce_user_merch_".$row['merchant_id']." cce_user_store_".$row['store_id']."' onClick='pick_selected_item(".$row['id'].",".$row['merchant_id'].",".$row['store_id'].");'>
						<ul class='edit_icons'>
							<li><a href='javascript: void(0)'>".$edit_linker1."</a></li>
							<li><a href='javascript: void(0)'>".$edit_linker2."</a></li>
							<li><a href='javascript: void(0)'>".$edit_linker3."</a></li>
						</ul>
						<img src='$use_image' width='".$user_thumb_width."' alt=''>
						<p><span>NAME</span>".$row['first_name']." ".$row['last_name']."</p>												
						<p><span>E-MAIL</span>".$row['email']."</p>
					</li>
     			";
     			/*
     			<tr>
     	     			<td valign='top'><span class='mrr_link_simulator'>".$row['username']."</span></td>
     	     			<td valign='top' style='color:".$row_color.";'>".$row['first_name']."</td>
     	     			<td valign='top' style='color:".$row_color.";'>".$row['last_name']."</td>
     	     			<td valign='top' style='color:".$row_color.";'>".$row['title']."</td>
     	     			<td valign='top' style='color:".$row_color.";'>".(trim($row['merchant'])!="" ? $row['merchant'] : "&nbsp;")."</td>
     	     			<td valign='top' style='color:".$row_color.";'>".(trim("[".trim($row['store_number'])."] ".$row['store_name']."")!="[]" ? "[".trim($row['store_number'])."] ".$row['store_name']."" : "&nbsp;")."</td>
     	     			<td valign='top' style='color:".$row_color.";'>".$row['access_name']."</td>
     	     			<td valign='top' style='color:".$row_color.";'>
     	     				<span class='mrr_link_simulator' onClick='select_user_id(".$row['id'].",\"".$edit_mode."\");'>
     	     					<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this user'>
     	     				</span>
     	     			</td>
     	     		</tr>
     			*/
     			
	     	}
	     }	
     	     
    		$tab.="</ul>";
		
		return $tab;
	}
	
	function mrr_search_users($filter="",$view_user_access=0)
	{
		global $date_display;
		
		$tab="";
		
		$tab.="
			<table class='tablesorter'>
     		<thead>
     		<tr>	
     			<th>Username</th>
     			<th>First Name</th>
     			<th>Last Name</th>
     			<th>Title</th>
     			<th>Customer</th>
     			<th>Store</th>
     			<th>Access Level</th>
     			<th>&nbsp;</th>
     		</tr>
     		</thead>
     		<tbody>
		";
		
	     if($view_user_access==0)		$view_user_access=1;     
	     
	     $search_filer="";
	     
	     if(trim($_POST['search_universal'])!="")	
	     {
	     	$search_filer.=" and ( 
	     			users.username like '%".sql_friendly($_POST['search_universal'])."%'
	     			or 
	     			users.first_name like '%".sql_friendly($_POST['search_universal'])."%'
	     			or
	     			users.last_name like '%".sql_friendly($_POST['search_universal'])."%'
	     			or 
	     			CONCAT(users.first_name, ' ', users.last_name) like '%".sql_friendly($_POST['search_universal'])."%'
	     			or
	     			users.email like '%".sql_friendly($_POST['search_universal'])."%'
	     			)";	
	     }
	     $merchant=0;
	     $store=0;
	     if(isset($_SESSION['merchant_id']))		$merchant=$_SESSION['merchant_id'];
		if(isset($_SESSION['store_id']))			$store=$_SESSION['store_id'];
	     
	     $run_script="";
	     
	     $access_adder=" and users.access_level <= '".sql_friendly($view_user_access)."' ";
	     if($_SESSION['access_level'] <= 70)		$access_adder=" and users.access_level <= '".sql_friendly($_SESSION['access_level'])."' ";
	     	     
	     $sql="
	     	select users.*,
	     		store_locations.store_name,
	     		store_locations.store_number,
	     		merchants.merchant,
	     		user_levels.level_name as access_name
	     	from users 
	     		left join user_levels on user_levels.access_level=users.access_level
	     		left join merchants on merchants.id=users.merchant_id
	     		left join store_locations on store_locations.id=users.store_id
	     	where users.deleted=0
	     		".$access_adder."	     		
	     		".$search_filer."
	     	order by archived asc,users.access_level desc,last_name asc,first_name asc
	     ";
	     		//".($merchant > 0 ? " and users.merchant_id='".sql_friendly($merchant)."'" : "")."
	     		//".($store > 0 ? " and users.store_id='".sql_friendly($store)."'" : "")."
	     
	     $data = simple_query($sql);
	     while($row = mysqli_fetch_array($data))
	     {
	     	$edit_mode="";
			$valid_user=check_user_edit_access('users',$row['id'],$_SESSION['user_id']);
			if($valid_user==1)		$edit_mode="readonly";	
			
			if($_SESSION['merchant_id'] > 0 && $_SESSION['access_level'] ==70)
			{
				$merchx=$row['merchant_id'];
				$storex=$row['store_id'];	
				$test_sub=mrr_get_merchant_teirs_validation($_SESSION['merchant_id'],$merchx,$storex);	//see if the user's merchant ID is parent company to the checked merchant...	
				if($test_sub==0)	$valid_user=0;
			}
					
			
			if($valid_user > 0 || $row['id']==$_SESSION['user_id'])	
			{	     	     	
     	     	$dater="";
     	     	if($row['linedate_login']!="0000-00-00 00:00:00")			$dater=date($date_display,strtotime($row['linedate_login']));		
     	     	$dater2="";
     	     	if($row['linedate_failed']!="0000-00-00 00:00:00")		$dater2=date($date_display,strtotime($row['linedate_failed']));	
     	     	
     	     	$row_color="#000000";		if($row['archived'] > 0)		$row_color="#aaaaaa";	
     	     		     	
     	     	
     	     	//select_user_id(".$row['id'].",\"".$edit_mode."\")
     	     	$tab.="
     				<tr class='cce_users user_id_".$row['id']." cce_user_merch_".$row['merchant_id']." cce_user_store_".$row['store_id']."'>
     	     			<td valign='top'><span class='mrr_link_simulator' onClick='pick_selected_item(".$row['id'].",".$row['merchant_id'].",".$row['store_id'].");'>".$row['username']."</span></td>
     	     			<td valign='top' style='color:".$row_color.";'>".$row['first_name']."</td>
     	     			<td valign='top' style='color:".$row_color.";'>".$row['last_name']."</td>
     	     			<td valign='top' style='color:".$row_color.";'>".$row['title']."</td>
     	     			<td valign='top' style='color:".$row_color.";'>".(trim($row['merchant'])!="" ? $row['merchant'] : "&nbsp;")."</td>
     	     			<td valign='top' style='color:".$row_color.";'>".(trim("[".trim($row['store_number'])."] ".$row['store_name']."")!="[]" ? "[".trim($row['store_number'])."] ".$row['store_name']."" : "&nbsp;")."</td>
     	     			<td valign='top' style='color:".$row_color.";'>".$row['access_name']."</td>
     	     			<td valign='top' style='color:".$row_color.";'>
     	     				<span class='mrr_link_simulator' onClick='select_user_id(".$row['id'].",\"".$edit_mode."\");'>
     	     					<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this user'>
     	     				</span>
     	     			</td>
     	     		</tr>
     			";
     	     	  
     	     	// auto open the edit window if a single entry is found   	     	
     	     	//if(mysqli_num_rows($data) == 1)	 $run_script="<script>select_user_id(".$row['id'].",\"".$edit_mode."\");</script>";			//$_POST['id']=$row['id'];
	     	}
	     }	
     	     
    		$tab.="
			</tbody>
		</table>

		". $run_script."
		";
		
		return $tab;
	}
	
	function mrr_user_settings_form($id,$edit_mode="")
	{
		$view_user_access=$_SESSION['view_access_level'];
		if($edit_mode=="")
		{
			$valid_user=check_user_edit_access('users',$id,$_SESSION['user_id']);
			if($valid_user==1)		$edit_mode="readonly";	
		}
		
		$tab="";
		
		$reset_link="";
          if($edit_mode=="")					$reset_link="<input type='button' name='update-user-pass' id='update-user-pass' class='btn btn-default add_new_btn' style='width:150px;' value='Update Password'>";
		
		$sql="
          	select users.*
             	from users 
             	where id= '".sql_friendly($id)."' 
          ";
          $data = simple_query($sql);
                    
          if(!mysqli_num_rows($data)) 			return "Could not locate user";
          
          $row = mysqli_fetch_array($data);
          
          $_SESSION['selected_user_id']=$id;	
          $_SESSION['selected_merchant_id']=$row['merchant_id'];
		$_SESSION['selected_store_id']=$row['store_id'];
                         		
          $selbx=get_access_select_box($view_user_access,'user_access_level',$row['access_level'],0,"Select Access Level","");
          $sel_display=get_access_display_name($row['access_level']);
          
          //get_merchant_select_box($field,$pre=0,$cd=0,$prompt="",$classy="")
          $selbox1=get_merchant_select_box('merchant_id',$row['merchant_id'],0,"ALL","");          
          
          //get_store_select_box($field,$pre=0,$merchant=0,$cd=0,$prompt="",$classy="")
          $selbox2=get_store_select_box('store_id',$row['store_id'],$_SESSION['selected_merchant_id'],0,"ALL","");         
                    
          /*
     	if($id==0 && $_SESSION['selected_merchant_id']==0)
     	{
     		$selbox1="Add to New Customer <input type='hidden' name='merchant_id' id='merchant_id' value='0'>";	
     	}
     	if($id==0 && $_SESSION['selected_store_id']==0)
     	{
     		$selbox2="Add to New Store <input type='hidden' name='store_id' id='store_id' value='0'>";	
     	}
     	*/           
          
          $tab.="<input type='hidden' name='id' id='id' value='".$row['id']."'>";
          $tab.="<table class='mrr_input_table' style='width:100%;'>";
                    
          //$tab.="<tr><td valign='top' colspan='2' align='center'><hr></td></tr>";
          
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left'><b>First Name</b></td>";
          $tab.=	"<td valign='top' align='left'><input type='text' class='tooltipx' name='user_first' id='user_first' value=\"".$row['first_name']."\"".$edit_mode." title='First Name' size='40' placeholder='Enter First name'></td>";
          $tab.="</tr>";
          
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left'><b>Last Name</b></td>";
          $tab.=	"<td valign='top' align='left'><input type='text' class='tooltipx' name='user_last' id='user_last' value=\"".$row['last_name']."\"".$edit_mode." title='Last Name' size='40' placeholder='Enter Last Name'></td>";
          $tab.="</tr>";
          
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left'><b>Title</b></td>";
          $tab.=	"<td valign='top' align='left'><input type='text' class='tooltipx' name='user_title' id='user_title' value=\"".$row['title']."\"".$edit_mode." title='Title, like director or CFO' size='40' placeholder='Enter Title or Position'></td>";
          $tab.="</tr>";
          
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left'><b>Cell Phone #</b></td>";
          $tab.=	"<td valign='top' align='left'><input type='text' class='tooltipx' name='contact_phone1' id='contact_phone1' value=\"".$row['contact_phone1']."\"".$edit_mode." title='Cell Phone #' size='40' placeholder='Optional Cell #'></td>";
          $tab.="</tr>";
          
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left'><b>Phone Number</b></td>";
          $tab.=	"<td valign='top' align='left'><input type='text' class='tooltipx' name='contact_phone2' id='contact_phone2' value=\"".$row['contact_phone2']."\"".$edit_mode." title='Alternate/Office Phone #' size='40' placeholder='Optional Phone Number'></td>";
          $tab.="</tr>";
                   
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left'><b>E-mail Address</b></td>";
          $tab.=	"<td valign='top' align='left'><input type='text' class='tooltipx' name='user_email' id='user_email' value=\"".$row['email']."\"".$edit_mode." title='E-mail Address' size='40' placeholder='Enter E-mail Address'></td>";
          $tab.="</tr>";     
               
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left'><b>Customer</b></td>";
          $tab.=	"<td valign='top' align='left'>
          			<span id='user_merchant_box'>
                            	".$selbox1."  			
                         </span>          			
          		</td>";	//<input type='text' readonly class='tooltipx' name='merchant_id' id='merchant_id' value=\"".$row['merchant_id']."\"".$edit_mode." title='Customer for User, if restricted.' size='40' placeholder='Customer for User, if restricted.'>
          $tab.="</tr>";
          
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left'><b>Store</b></td>";
          $tab.=	"<td valign='top' align='left'>
          			<span id='user_store_box'>
          				".$selbox2."
          			</span> 
          		</td>";	//<input type='text' readonly class='tooltipx' name='store_id' id='store_id' value=\"".$row['store_id']."\"".$edit_mode." title='Store for User, if restricted.' size='40' placeholder='Store for User, if restricted.'>
          $tab.="</tr>";
          
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left'><b>Username</b> <input type='hidden' name='usernamer' id='usernamer' value=\"".$row['username']."\"></td>";
          $tab.=	"<td valign='top' align='left'>".$row['username']."</span></td>	";
          $tab.="</tr>";
          
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left' valign='center'><b>Password</b></td>";
          $tab.=	"<td valign='top' align='left'> ".($reset_link == '' ? "*********" : $reset_link)."</td>";
          $tab.="</tr>";
          
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left'><b><label for='user_archived'>Archived</label></b></td>";
          $tab.=	"<td valign='top' align='left'><input type='checkbox' name='user_archived' id='user_archived'".($row['archived'] > 0 ? " checked" : "")."".$edit_mode."></td>";
          $tab.="</tr>";
          
          $tab.="<tr>";
          $tab.=	"<td valign='top' align='left'><b>Access Level</b></td>";
          $tab.=	"<td valign='top' align='left'>".($edit_mode=="" ? $selbx : $sel_display)."</td>";
          $tab.="</tr>";
          if($_SESSION['access_level'] >=90)
          {
          	$tab.="<tr>";
          	$tab.=	"<td valign='top' align='left'><b>Customize</b></td>";
          	$tab.=	"<td valign='top' align='left'><div id='user_custom_access'></div></td>";
          	$tab.="</tr>";
          }
          
          //$tab.="<tr>";
          //$tab.=	"<td valign='top' align='left'><b><label for='monitor_logs'>Monitor Logs</label></b></td>";
          //$tab.=	"<td valign='top' align='left'><input type='checkbox' name='monitor_logs' id='monitor_logs'".($row['monitor_logs'] > 0 ? " checked" : "")."".$edit_mode."></td>";
          //$tab.="</tr>";
          
          
          $tab.="<tr><td valign='top' colspan='2' align='center'><hr><input type='hidden' name='monitor_logs' id='monitor_logs' value='0'></td></tr>";
          
          /*
          if($edit_mode=="") 
          { 
          	$tab.="<tr>";
          	$tab.=	"<td valign='top' align='left' colspan='2' class='edit_buttons'>";
          	$tab.=	"          			
          			<input type='button' name='user_delete' id='user_delete' value='Delete' class='btn btn-default add_new_btn' onClick='return confirm_delete(".$row['id'].");'>
               	    
               	     <input type='button' name='user_save' id='user_save' value='Update' class='btn btn-default add_new_btn' onclick='save_user(".$row['id'].");'>          			
          			";		// <input type='button' name='return_to_list' id='return_to_list' class='btn btn-default add_new_btn' value='Cancel' onclick=\"window.location='".$_SERVER['SCRIPT_NAME']."'\">
          	$tab.=	"</td>";
          	$tab.="</tr>";
          	
          	$tab.="<tr><td valign='top' colspan='2' align='center'><hr></td></tr>";
     	}
     	*/
     	
     	$tab.="
     	</table>	
          <div id='dialog_delete' title='Remove this User?' style='display:none;'>
          	<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>This User will be permanently deleted and cannot be recovered. Are you sure you want to delete it?</p>
          </div>
          <div id='dialog_reset' title='Reset Password for User?' style='display:none;'>
          	<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>This Password will be permanently reset and cannot be reversed. Are you sure you want to reet the password for this user?</p>
          </div>     	
     	";     	
     	echo $tab;     
     	
     	$tab2=create_uploader_section('user_image_holder',"Photo&nbsp&nbsp;",SECTION_AVATAR,$row['id']);
     	
     	echo $tab2;
     	
     	$tab3=create_uploader_section('cert_image_holder',"Certificate &nbsp&nbsp;",SECTION_CERTIFICATES,$row['id'],'show_user_cert', 'Upload Certificate');
     	
     	echo $tab3;

     	//echo create_uploader_section('training_certificate_holder',"Certificates &nbsp&nbsp;",SECTION_CERTIFICATES,$row['id']);
     	?>     	
		<!--Form for updating Passwords -->
          <div id="dialog-form-update22" title="Update Password" style='display:none;'>
          	<p class="validateTips">All form fields are required.</p>
          	<div class='field'>
          		<label for="user_name22">Account Username</label>
          		<span>
          			<?=get_welcome_by_id($row['id'],1) ?>
          			<input type='text' name='user_name22' id='user_name22' readonly value="<?=get_welcome_by_id($row['id'],1) ?>" style="display:none">
          		</span>
          	</div>
          	<div class='field'>
          		<label for="password2">Enter Password</label>
          		<span>
          			<input type="password" name="password22" id="password22" value="">
          		</span>
          	</div>
          	<div class='field'>
          		<label for="confirm_password22">Confirm Password</label>
          		<span>
          			<input type="password" name="confirm_password22" id="confirm_password22" value="">
          			<!-- Allow form submission with keyboard without duplicating the dialog button -->
          			<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
          		</span>
          	</div>
          </div>
          <style>
          	div.field {width:300px;margin:5px 0}
			div.field label {float:left;width:130px;font-weight:bold}
			div field span {float:left;width:160px}	
			.mrr_input_table {font-size:12px}
			.mrr_input_table tr {  padding-bottom:10px; height:35px; }
			.mrr_input_table input, .mrr_input_table select {width:90%}
          </style>
		<script>			
			$().ready(function() {
				$('input[type=button], input[type=submit]').button();
				$(".tooltip").tooltip();					
				
     			show_user_image(<?=$row['id'] ?>,'#user_image_holder');
     			show_user_cert(<?=$row['id'] ?>,'#cert_image_holder');
     			//$('#training_certificate_holder').html('coming soon!');	    
     			
     			//$("select").selectmenu().selectmenu('menuWidget').addClass('overflow');
     			
				//$('#user_access_level').selectmenu('destroy');
				//$('#user_access_level').selectmenu().selectmenu('menuWidget').addClass('overflow');
               	
               	//$('#merchant_id').selectmenu('destroy');
				//$('#merchant_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
			
				//$('#store_id').selectmenu('destroy');
				//$('#store_id').selectmenu().selectmenu('menuWidget').addClass('overflow'); 		
				
				$('#user_custom_access').html('Coming Soon...');		
				load_custom_user_access('#user_custom_access',<?=$row['id'] ?>);			
			});
			
			//update password for account form
               $(function() {
                    var dialog, form,              
                    dialog = $( "#dialog-form-update22" ).dialog({
                         autoOpen: false,
                         width: 'auto',
                         modal: true,
                         buttons: {
                         	"Update Password": 	function() {	modUser22();	dialog.dialog( "close" );  	},
                         	"Cancel": 		function() {	dialog.dialog( "close" );  	}
                         },
                         close: function() 
                         {
                         	$().removeClass( "ui-state-error" );
                         }
                    });
                    $( "#update-user-pass" ).button().on( "click", function() {
                    	dialog.dialog( "open" );
                    	
                    	$('#user_access_level').selectmenu('destroy');
     				$('#user_access_level').selectmenu().selectmenu('menuWidget').addClass('overflow');
                    	
                    	$('#merchant_id').selectmenu('destroy');
     				$('#merchant_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			
     				$('#store_id').selectmenu('destroy');
     				$('#store_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     				     				
                    });
               }); 
               
               function modUser22() 
               {
                    var valid = true;
                    $().removeClass( "ui-state-error" );
                    //valid = valid && checkLength( $( "#user_name22" ), "username", 3, 16 );
                    valid = valid && checkLength( $( "#confirm_password22" ), "confirm_password", 6, 80 );
                    valid = valid && checkLength( $( "#password22" ), "password", 6, 80 );
                    //valid = valid && checkRegexp( $( "#user_name22" ), /^[a-z]([0-9a-z_\s])+$/i, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter." );
                    valid = valid && checkRegexp( $( "#confirm_password22" ), /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9.  You must confirm your password."  );
                    valid = valid && checkRegexp( $( "#password22" ), /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9." );
                    
                    if($( "#confirm_password22" ).val() != $( "#password22" ).val() )	
                    {
                    	valid =false;          	
                    	msgbox("Password must match when you enter it and confirm it. Please try again.");
                    }
                    if ( valid ) 
                    {
                    	save_user_pass($( "#user_name22" ).val(),$( "#password22" ).val(),$( "#confirm_password22" ).val());	   	
                    	load_user_search();
                    }
                    return valid;
               }  
		</script>
		<?			
	}
	function mrr_display_cce_message_pad($id=0)
	{
		$mrr_adder="";
		
		//find merchant template first...acts as a default.
     	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$mrr_adder=" and cce_messages.merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'";
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$mrr_adder=" and cce_messages.merchant_id='".sql_friendly($_SESSION['merchant_id'])."'";
     	}
     	
     	//find store template next...override merchant if set.
     	if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
     	{
     		$mrr_adder=" and cce_messages.store_id='".sql_friendly($_SESSION['selected_store_id'])."'";
     	}     	
     	elseif($_SESSION['store_id'] > 0)
     	{
     		$mrr_adder=" and cce_messages.store_id='".sql_friendly($_SESSION['store_id'])."'";
     	}
		
		$reject=1;		//default to edit rejected....then allow those that are valid to edit the note below.
			
		if($_SESSION['access_level'] >= 90)
		{	//Always allowed to edit... (not limited to merchant or store).
			$reject=0;
		}
		elseif($_SESSION['merchant_id'] > 0 && $_SESSION['store_id'] == 0 && $_SESSION['access_level'] >= 80 && $_SESSION['access_level'] != 80)
		{	//not limited to a store... and access checks out (excluding CCE-2 restricted access)
			$reject=0;
		}
		elseif($_SESSION['merchant_id'] > 0 && $_SESSION['store_id'] > 0 && $_SESSION['access_level'] >= 80 && $_SESSION['access_level'] != 80  && $_SESSION['access_level'] != 45 && $_SESSION['access_level'] != 50 && $_SESSION['access_level'] != 55)
		{	//user can only see one store, so must be manager of store or higher without restricted access
			$reject=0;
		}
				
		if($reject==1)		return "";
		$tab="";
		
		$sql="
               	select cce_messages.*
                  	from cce_messages
                  	where deleted=0
                  		".($id > 0 ? " and id= '".sql_friendly($id)."'" : "")."                  		 
                  		".$mrr_adder."
                  	order by merchant_id asc,store_id asc
                  	limit 1
          ";
          $data = simple_query($sql);          
          if($row = mysqli_fetch_array($data))
          {
               $row['subject']=str_replace("'","&apos;",$row['subject']);
               $row['message']=str_replace("'","&apos;",$row['message']);
               
               
               $tab.="<div id='cce_message_editor_".$row['id']."' style='display:none;'>";
               //$tab.=	"<b>Headline</b> <input type='text' name='cce_msg_subject_".$row['id']."' id='cce_msg_subject_".$row['id']."' value=\"".$row['subject']."\" class='longshort'>";	//<div style='margin:10px 0;'>tooltip </div>
               $tab.=	"<input type='hidden' name='cce_msg_subject_".$row['id']."' id='cce_msg_subject_".$row['id']."' value=\"".$row['subject']."\">";
               //$tab.= 	"<div style='clear:both;'></div><br>&nbsp;<br>";
               $tab.=	"<textarea name='cce_msg_body_".$row['id']."' id='cce_msg_body_".$row['id']."' class='mceEditor'>".$row['message']."</textarea>";
               $tab.="</div>";
     	}
		return $tab;	
	}
	
	//customized access level functions.
	function get_access_value($level,$user,$template,$action)
	{
		$valid=-1;		//does not exist if not found.		
		$sql = "
     		select action_value 
     		from user_custom_access
     		where deleted=0	
     			".($template > 0 ? " and template_item_id='".sql_friendly($template)."'" : "")." 		
     			".($level > 0 ? " and access_level_id='".sql_friendly($level)."'" : "")."       			
     			".($user > 0 ? " and user_id='".sql_friendly($user)."'" : "")."      			
     			".($action !="" ? " and action_name='".sql_friendly($action)."'" : "")." 
     		order by id asc
     	";
     	$data=simple_query($sql);
     	if($row=mysqli_fetch_array($data))
		{
			$valid=$row['action_value'];	
		}		
		return $valid;	
	}
	function add_access_value($level,$user,$template,$action)
	{
		$sql = "
			insert into user_custom_access
				(id,
				template_item_id,
				access_level_id,
				user_id,
				action_name,
				action_value,
				deleted)
			values
				(NULL,
				'".sql_friendly($template)."',
				'".sql_friendly($level)."',
				'".sql_friendly($user)."',
				'".sql_friendly($action)."',
				0,
				0)
     	";
     	simple_query($sql);	
	}
	function update_access_value($level,$user,$template,$action,$value)
	{
		$sql = "
			update user_custom_access set
				action_value='".sql_friendly($value)."'			
			where deleted=0	
     			".($template > 0 ? " and template_item_id='".sql_friendly($template)."'" : "")." 		
     			".($level > 0 ? " and access_level_id='".sql_friendly($level)."'" : "")."       			
     			".($user > 0 ? " and user_id='".sql_friendly($user)."'" : "")."      			
     			".($action !="" ? " and action_name='".sql_friendly($action)."'" : "")." 
     	";
     	simple_query($sql);	
	}
	
	
	//counter functions...for sidebar
	function generate_sidebar_documents()
	{
		global $page_name;
		global $query_string;
		
		$cur_access=$_SESSION['access_level'];
		$my_base_id=0;
		$sql = "
     		select id 
     		from user_levels 
     		where access_level='".sql_friendly($cur_access)."'
     	";
     	$data=simple_query($sql);	
     	if($row = mysqli_fetch_array($data))
     	{
     		$my_base_id=$row['id'];
     	}	
		
		
		if(!isset($_SESSION['selected_doc_type_id']))		$_SESSION['selected_doc_type_id']=0;
		if(substr_count($_SERVER['SCRIPT_NAME'],"access_manager.php") > 0)	$_SESSION['selected_doc_type_id']=0;	
		
		
		$tab="";

		$temp_id=0;
     	$mrr_adder=" and template_id=1";		//default to master template
     	
     	//find merchant template first...acts as a default.
     	if($_SESSION['merchant_id'] ==0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['selected_merchant_id']);     		
     		if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['merchant_id']);     		
     		if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
     	}
     	//find store template next...override merchant if set.
     	if($_SESSION['store_id'] ==0 && $_SESSION['selected_store_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['selected_store_id']);     		
     		if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
     	}
     	elseif($_SESSION['store_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['store_id']);     		
     		if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
     	}
     	
		$sql = "
     		select * 
     		from template_items
     		where deleted=0			
     			".$mrr_adder."  
     			 and sub_group_id = 0	
     		order by zorder asc,item_label asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		$namer="".$row['item_label']."";	
     		$namer2=str_replace(" ","_",$namer);     	                         		
     		
     		$add_class="";	
     		
     		$use_page_id = 0;
     		if(isset($_GET['id']) && substr_count($_SERVER['SCRIPT_NAME'],"access_manager.php") == 0) $use_page_id = $_GET['id'];
     		if(isset($_GET['doc_page_id'])) 												$use_page_id = $_GET['doc_page_id'];
     		
     		if($_SESSION['selected_doc_type_id'] > 0)										$use_page_id=$_SESSION['selected_doc_type_id'];
     		
     		
     		if($use_page_id == $row['id']) 	$add_class="active_side_menu";		//	
     		
     		$titler=$row['title_text'];		//mrr_document_menu_title($namer);							//get tool/title tip for user.     	                         		
     		
     		$file_cntr=get_all_template_items_for_template_count($row['id']);
     		
     		//if($use_page_id != $row['id'] && $file_cntr==0 && trim($namer)!="Compliance Officer" && trim($namer)!="Compliance Manager") 	$add_class="inactive_side_menu";
     		
     		
     		$valid_access=0;
     		if($row['min_access_level'] <= $cur_access)		$valid_access=1;							//generic level to catch all...prior to more elaborate access level, but will catch those that are not set below.
     		  		
     		$xvalid1=get_access_value($my_base_id,0,$row['id'],'view_template_item');					//level,user,template-item,action
			$xvalid2=get_access_value($my_base_id,$_SESSION['user_id'],$row['id'],'view_template_item');	//level,user,template-item,action
     		if($xvalid1 >=0)
     		{
     			$valid_access=$xvalid1;			//access level default for all users of this level.
     			if($xvalid2 >=0)
     			{
     				$valid_access=$xvalid2;		//user has a specific setting....		
     			}
     			
     			//$valid_access=0;				//kill switch...
     		}
     		     		
     		if($valid_access > 0)
     		{
     			$tab.="
     				<li>
     					<a href='/documents.php?id=".$row['id']."' class='".$add_class."'".($titler!="" ? " title=\"".$titler."\"" : "").">
     						<i class='fa fa-arrow-circle-right'></i> ".$namer."
     					</a>
     				</li>
     			";
     		}
     		else
     		{	//block display of link
     			$tab.="
     				<li>
     					<a href='javascript: void(0);' class='inactive_side_menu'".($titler!="" ? " title=\"".$titler."\"" : "")." disabled>
     						<i class='fa fa-arrow-circle-right'></i> ".$namer."
     					</a>
     				</li>
     			";	
     		}
     		     		
     		
     		/*
     		//if($file_cntr > 0 || trim($namer)=="Compliance Officer" || trim($namer)=="Compliance Manager")
     		//{
     			$tab.="
     				<li>
     					<a href='/documents.php?id=".$row['id']."' class='".$add_class."'".($titler!="" ? " title=\"".$titler."\"" : "").">
     						<i class='fa fa-arrow-circle-right'></i> ".$namer."
     					</a>
     				</li>
     			";
     		//}	
     		*/
     	}  	
		return $tab;	
	}
	function get_all_template_items_for_template_count($id)
	{	//template_item id is coming in... so just get the files... and find sub groups....
		$cntr=0;	
		$cntr+=get_all_files_for_template_item_count($id);
		
		//now get only the sub items of this group. (Assumes one extra tier only)
		$sql2 = "
     		select * 
     		from template_items
     		where deleted=0	 
     			and sub_group_id = '".sql_friendly($id)."'   		
     		order by item_label asc
     	";
     	$data2=simple_query($sql2);
     	while($row2=mysqli_fetch_array($data2)) 
     	{
			$cntr+=get_all_files_for_template_item_count($row2['id']);	
     	}			
		return $cntr;
	}
	function get_all_files_for_template_item_count($template_item_id)
	{	//only gets the file COUNT of the documents to be displayed...not the file list... this is to use as a show/hide of sidebar menu options.		
		$mrr_adder="";
		
		//find merchant files
     	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$mrr_adder.=" and merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'";
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$mrr_adder.=" and merchant_id='".sql_friendly($_SESSION['merchant_id'])."'";
     	}
     	
     	//find store fies
     	if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
     	{
     		$mrr_adder.=" and store_id='".sql_friendly($_SESSION['selected_store_id'])."'";	
     	}     	
     	elseif($_SESSION['store_id'] > 0)
     	{
     		$mrr_adder.=" and store_id='".sql_friendly($_SESSION['store_id'])."'";
     	}
		
		$cntr=0;		
		
		$sql="
          	select *
             	from attached_files
             	where deleted=0
             		and template_item_id= '".sql_friendly($template_item_id)."'                  		 
             		".$mrr_adder."
             		and linedate_display_start <= NOW()
             	order by public_name asc,id asc
          ";
          $data = simple_query($sql);          
          while($row = mysqli_fetch_array($data))
          {	
          	$cntr++;
          }
		return $cntr;	
	}
	
	
	function get_all_template_items_for_template_preview($id)
	{
		$tab="";
		$mrr_adder="";		
     			
		$sql = "
			select *			
			from template_items
			where deleted = 0
				and archived=0
				and sub_group_id = 0					
				and template_id = '".sql_friendly($id)."'
			order by zorder asc,archived asc,item_label asc, id asc
		";
		$data = simple_query($sql);
		while($row = mysqli_fetch_array($data)) 
		{
			$subtab="";	
			
			$access=$row['min_access_level'];
			
			//now get only the sub items of this group. (Assumes one extra tier only)
     		$sql2 = "
          		select * 
          		from template_items
          		where deleted=0
          			and archived=0	
          			and sub_group_id = '".sql_friendly($row['id'])."'
          			and sub_group_id > 0   		
          		order by zorder asc,item_label asc, id asc
          	";
          	$data2=simple_query($sql2);
          	while($row2=mysqli_fetch_array($data2)) 
          	{     				
     			$subtab.="
     				<div class='template_item_subtitle'> - ".$row2['item_label']."</div>
     			";	
          	}
          	
     		$tab.="
				<div class='template_item_title'>".$row['item_label']."</div>
				<div class='template_item_subitems' style='padding-left:25px;'>".$subtab."</div>
			";
		}	
		return $tab;
	}
	
	
	function get_cust_store_listing($namer)
	{
		//find merchant first...acts as a default.
		$mrr_adder="";
     	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$mrr_adder.=" and id>0";
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$mrr_adder.=" and id='".sql_friendly($_SESSION['merchant_id'])."'";
     	}
     	//find store first...acts as a default.
     	$mrr_adder2="";
     	if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
     	{
     		$mrr_adder2.=" and id>0";
     	}
     	elseif($_SESSION['store_id'] > 0)
     	{
     		$mrr_adder2.=" and id='".sql_friendly($_SESSION['merchant_id'])."'";
     	}
     			
		$tab="<div id='".$namer."' class='doc_type_sect_holder'>";
		
		$tab.="<table cellspacing='0' cellpadding='0' border='0' width='100%' class='doc_type_sect'>";
		
		$sql = "
			select *			
			from merchants
			where deleted = 0				
				".$mrr_adder."
			order by merchant asc, id asc
		";
		$data = simple_query($sql);
		while($row = mysqli_fetch_array($data)) 
		{
			$name_val=trim($row['merchant']);
			$name_val=str_replace("'","&apos;",$name_val);
			
			$tab.="
				<tr>
					<td valign='top' colspan='2'><span class='doc_type_sect_hdr' onClick='mrr_sel_doc_cust_store(".$row['id'].",\"".$name_val."\",0,\"All Store Locations\");'>".$name_val."</span></td>
				</tr>
			";	
			
			$sql2 = "
     			select *     			
     			from store_locations
     			where deleted = 0
     				and merchant_id = '".(int) $row['id']."'
     				".$mrr_adder2."
     			order by store_name asc, id asc
     		";
     		$data2 = simple_query($sql2);
     		while($row2 = mysqli_fetch_array($data2)) 
     		{
     			$name_val2=trim($row2['store_name']);
     			$name_val2=str_replace("'","&apos;",$name_val2);
     			
     			$tab.="
     				<tr>
     					<td valign='top' width='25'>&nbsp;</td>
     					<td valign='top'><span class='doc_type_sect_item' onClick='mrr_sel_doc_cust_store(".$row['id'].",\"".$name_val."\",".$row2['id'].",\"".$name_val2."\");'>".$name_val2."</span></td>
     				</tr>
     			";	
     		}
		}	
		$tab.="</table></div>";
		
		return $tab;	
	}
	function get_template_item_listing($namer)
	{
		$temp_id=1;
		//find merchant template first...acts as a default.
     	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['selected_merchant_id']);     
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['merchant_id']);  
     	}
		$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
				
		$tab="<div id='".$namer."' class='doc_type_sect_holder'>";
		
		$tab.="<table cellspacing='0' cellpadding='0' border='0' width='100%' class='doc_type_sect'>";
		
		$sql = "
			select *
			
			from template_items
			where deleted = 0
				and sub_group_id = 0					
				".$mrr_adder."
			order by zorder asc,archived asc,item_label asc, id asc
		";
		$data = simple_query($sql);
		while($row = mysqli_fetch_array($data)) 
		{
			$name_val=trim($row['item_label']);
			$name_val=str_replace("'","&apos;",$name_val);
			
			$tab.="
				<tr>
					<td valign='top' colspan='2'><span class='doc_type_sect_hdr' onClick='mrr_sel_doc_section(".$row['id'].",\"".$name_val."\",0,\"None Selected\");'>".$name_val."</span></td>
				</tr>
			";	
			
			$sql2 = "
     			select *     			
     			from template_items
     			where deleted = 0
     				and sub_group_id = '".(int) $row['id']."'
     			order by zorder asc,archived asc,item_label asc, id asc
     		";
     		$data2 = simple_query($sql2);
     		while($row2 = mysqli_fetch_array($data2)) 
     		{
     			$name_val2=trim($row2['item_label']);
     			$name_val2=str_replace("'","&apos;",$name_val2);
     			
     			$tab.="
     				<tr>
     					<td valign='top' width='25'>&nbsp;</td>
     					<td valign='top'><span class='doc_type_sect_item' onClick='mrr_sel_doc_section(".$row['id'].",\"".$name_val."\",".$row2['id'].",\"".$name_val2."\");'>".$name_val2."</span></td>
     				</tr>
     			";	
     		}
		}	
		$tab.="</table></div>";
		
		return $tab;	
	}
	function get_all_template_items_for_template($id,$access=0,$editor=0,$show_auditor_control=0)
	{
		$tab="";
		$mrr_adder=" and template_id = '".sql_friendly($id)."'";
		
		//find merchant template first...acts as a default.
     	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['selected_merchant_id']);     		
     		if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		//$temp_id=mrr_get_merchant_template_id($_SESSION['merchant_id']);     		
     		//if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
     	}
     	
     	//find store template next...override merchant if set.
     	if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
     	{
     		$temp_id=mrr_get_merchant_template_id($_SESSION['selected_store_id']);     		
     		if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
     	}     	
     	elseif($_SESSION['store_id'] > 0)
     	{
     		//$temp_id=mrr_get_merchant_template_id($_SESSION['store_id']);     		
     		//if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
     	}
     	     	     		
		$sql = "
			select *
			
			from template_items
			where deleted = 0
				and sub_group_id = 0					
				".$mrr_adder."
			order by zorder asc,archived asc,item_label asc, id asc
		";
		$data = simple_query($sql);
		$cntr=0;	
		while($row = mysqli_fetch_array($data)) 
		{
			$files=get_all_files_for_template_item($row['id'],0,0,$show_auditor_control,0,0,0);
			
			$subtab="";	
			$show_header=0;	
			if($files!="")		$show_header=1;
			
			$access=$row['min_access_level'];
			
			//now get only the sub items of this group. (Assumes one extra tier only)
     		$sql2 = "
          		select * 
          		from template_items
          		where deleted=0			
          			".$mrr_adder."  
          			and sub_group_id = '".sql_friendly($row['id'])."'   		
          		order by item_label asc
          	";
          	$data2=simple_query($sql2);
          	while($row2=mysqli_fetch_array($data2)) 
          	{
     			$files2=get_all_files_for_template_item($row2['id'],0,0,$show_auditor_control,$show_header,0,1);
     			if($files2!="")
     			{
     				
     				$subtab.="
     					<!--<div class='template_item_subtitle'>".$row2['item_label']."</div>-->
     					<div class='template_item_subfile".($show_header==1 ? " file_display_line" : "")."'>".$files2."</div>
     				";
     				$show_header=1;
     			}		
          	}
          	
          	if($subtab=="" && $files=="")	
          	{
          		$files="<div class='template_item_file_name'>No Document are available at this time.</div>";
          	}
          	else
          	{
          		$tab.="
					<div class='template_item_title'>".$row['item_label']."</div>
					<div class='template_item_file'>".$files."</div>
					<div class='template_item_subitems'>".$subtab."</div>
				";
			}
		}	
		
		return $tab;
	}
	
	function get_all_files_for_template_item($template_item_id,$access=0,$editor=0,$show_auditor_control=0,$drop_header=0,$show_uploaded=0,$loop_num=0)
	{
		$list="";
		global $page_name;
		$file_namer_remover=0;		if(substr_count($page_name,"documents.php") > 0)	$file_namer_remover=1;	
		
		$mrr_adder="";
		
		//find merchant files
     	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$mrr_adder.=" and merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'";
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$mrr_adder.=" and merchant_id='".sql_friendly($_SESSION['merchant_id'])."'";
     	}
     	
     	//find store fies
     	if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
     	{
     		//$mrr_adder.=" and (store_id='".sql_friendly($_SESSION['selected_store_id'])."' or store_id=0)";	//
     	}     	
     	elseif($_SESSION['store_id'] > 0)
     	{
     		//$mrr_adder.=" and (store_id='".sql_friendly($_SESSION['store_id'])."' or store_id=0)";			//
     	}
				
		$show_auditor_control2=$show_auditor_control;
		
		if($_SESSION['access_level'] >= 60)	$show_auditor_control=1;
		
		
		$list.="<table class='tablesorterx' width='100%'>";		
		if($drop_header==0)
		{
			$list.="
				<thead>
				<tr>					
					".($show_uploaded > 0 ? "<th valign='top' width='90' nowrap>Upload Date</th>" : "")."
					<th valign='top'>Document Name</th>
					<th valign='top' width='90' nowrap>Document Date</th>
					".($show_auditor_control > 0 ? "<th valign='top' width='70'>&nbsp;</th>" : "")."
					".($show_auditor_control2 > 0 ? "<th valign='top' width='50'>&nbsp;</th>" : "")."
				</tr>
				</thead>
			";	
		}		
		$list.="<tbody>";		
		
		$cntr=0;		
		
		$sql="
          	select *
             	from attached_files
             	where deleted=0
             		and (template_item_id= '".sql_friendly($template_item_id)."' or template_item_id_sub= '".sql_friendly($template_item_id)."')               		 
             		".$mrr_adder."
             		and linedate_display_start <= NOW()
             	order by public_name asc,id asc
          ";
          $data = simple_query($sql);          
          while($row = mysqli_fetch_array($data))
          {
          	$name=$row['filename'];
          	if(trim($row['public_name'])!="")		$name=$row['public_name'];
          	
          	$allow_editor1="<i class='fa fa-pencil' style='color:#e19918; cursor:pointer;' title='Click to edit this document' onClick='mrr_file_renamer(".$row['id'].",".$file_namer_remover.");'></i>";
			$allow_editor2="<i class='fa fa-trash' style='color:#e19918; cursor:pointer;' title='Click to remove this document' onClick='delete_attachment(".$row['id'].");'></i>";
			//$allow_editor3="<i class='fa fa-chevron-circle-down' style='color:#e19918; cursor:pointer;' title='Click to check it off (archive).' onClick=''></i>";	
          	
          	
          	if($_SESSION['access_level'] < 40 || $_SESSION['access_level']==50 || $_SESSION['access_level']==45)
          	{	//cannot edit any files (or remove them).
          		$allow_editor1="&nbsp;";	
          		$allow_editor2="&nbsp;";
          	}
          	if($_SESSION['access_level']==40 && $row['store_id']!=$_SESSION['store_id'])
          	{	//can only manage files for his/her store... otherwise no edit/delete
          		$allow_editor1="&nbsp;";	
          		$allow_editor2="&nbsp;";
          	}
          	
          	$class_adder="";
          	//if($row['auditor2_viewable'] > 0)		$class_adder=" mrr_file_auditor_used";
          	
          	if($row['public_flag'] > 0)   
          	{   	
          		if(substr_count($list," id='attachment_row_".$row['id']."'")==0)
          		{               		
               		//".($cntr%2==0 ? "even" : "odd" )."           ".($cntr > 0 ? " file_display_line" : "")."
               		$list.="
               			<tr class='".$class_adder."' id='attachment_row_".$row['id']."'>          				
               				".($show_uploaded > 0 ? "<td width='90'>".date("M d, Y",strtotime($row['linedate_added']))."</td>" : "")."
               				<td>
                         			<a href='documents/".$row['filename']."' target='_blank' onClick='set_email_view_log(".$row['id'].");' title='View this document...'>
                         				<div class='template_item_file_name'>".$name."</div>
                         			</a>		
               				</td>
               				<td width='90'>".date("M d, Y",strtotime($row['linedate_display_start']))."</td>  
               				".($show_auditor_control > 0  ? "<td width='70' nowrap>".$allow_editor1." &nbsp; &nbsp; &nbsp; &nbsp; ".$allow_editor2."</td>" : "")."
               				".($show_auditor_control2 > 0  ? "<td valign='top' width='50'><img src='common/images/next_orange.png' alt='' border='0' onClick='update_auditor2_list(".$row['id'].",1);' style='cursor:pointer;;height:16px'></td>" : "")."
               			</tr>          		
               			";
          		}
          	}
          	else
          	{
          		
          		if(substr_count($list," id='attachment_row_".$row['id']."'")==0)
          		{          		
               		if($row['auditor2_viewable']) {
               			// show a checkmark instead of the arrow
               			$use_icon = "<img src='common/images/gray_checkmark.png' alt='' border='0' onClick='update_auditor2_list(".$row['id'].",1);' style='cursor:pointer;height:16px'>";
               		} else {
               			// show the arrow to assign
               			$use_icon = "<img src='common/images/next_orange.png' alt='' border='0' onClick='update_auditor2_list(".$row['id'].",1);' style='cursor:pointer;height:16px'>";
               		}
               		//".($cntr%2==0 ? "even" : "odd" )."        ".($cntr > 0 ? " file_display_line" : "")."
               		$list.="
               			<tr class='".$class_adder."' id='attachment_row_".$row['id']."'>          				
               				".($show_uploaded > 0 ? "<td valign='top' width='90'>".date("M d, Y",strtotime($row['linedate_added']))."</td>" : "")."
               				<td valign='top'>
                         			<span class='mrr_link_simulator' onclick='set_email_view_log(".$row['id']."); view_attached_file(".$row['section_id'].",".$row['xref_id'].",".$row['id'].");' title='View this document...'>
                         				<div class='template_item_file_name'>".$name."</div>
                         			</span>	
               				</td>
               				<td valign='top' width='90'>".date("M d, Y",strtotime($row['linedate_display_start']))."</td> 
               				".($show_auditor_control > 0  ? "<td valign='top' width='70' nowrap>".$allow_editor1." &nbsp; &nbsp; &nbsp; &nbsp; ".$allow_editor2."</td>" : "")."
               				".($show_auditor_control2 > 0  ? "<td valign='top' width='50'>$use_icon</td>" : "")."
               			</tr>
               			";          			
          		}
          	}	
          	$cntr++;
          }
		$list.="
			</tbody>
			</table>
		";
		if($cntr==0)				$list="";		//clean out table...blank anyway.		
		
		//if(trim($list)=="")		$list="<div class='template_item_file_name'>No Document are available at this time.</div>";
		
		return $list;	
	}
	
	function show_selected_reports_by_access($section=0)
	{
		$list="";
		$view_user_access=$_SESSION['view_access_level'];
		
		if($section==0 || $view_user_access==0)		return "";
		
		$list.="<ul class='reports_list'>";
		/*	
		$list.="
			<li>
				<img src='common/images/report-chart-icon1.png' alt=''>
				<p><span>Report Title Here</span>Typi non notare quam littera gothica, quam nunc putamus habent.</p>
				<ul class='edit_icons'>
					<li><a href='/'><i class='fa fa-pencil'></i></a></li>
					<li><a href='/'><i class='fa fa-trash'></i></a></li>
					<li><a href='/'><i class='fa fa-chevron-circle-down'></i></a></li>
				</ul>
			</li>
		";	
		*/
		$img_arr[0]="<img src='common/images/report-chart-icon1.png' alt='' class='mrr_rep_icons'>";
		$img_arr[1]="<img src='common/images/report-chart-icon2.png' alt='' class='mrr_rep_icons'>";
		$img_arr[2]="<img src='common/images/report-chart-icon3.png' alt='' class='mrr_rep_icons'>";
		
		$cntr=0;		
		$sql="
               	select *
                  	from security
                  	where access_level<='".sql_friendly($view_user_access)."'
                  		and section= '".sql_friendly($section)."'   
                  	order by zorder asc,action asc
          ";
          $data = simple_query($sql);          
          while($row = mysqli_fetch_array($data))
		{
			
			$list.="
				<li>
					".$img_arr[ ($cntr % 3 )]."
					<p>						
						<span>".$row['action_name']."</span>
						<a href='".$row['action']."' style='color:#FFFFFF;'><div class='btn btn-default add_new_btn' style='color:#FFFFFF;'>View</div></a>					
					</p>          			
          		</li>";	//template_item_title
          	$cntr++;
          }	
          $list.="</ul>";
                    
          return $list;	
	}
	
	
	//customers/merchants
	function show_merchant_teirs($start_comp_id,$disp_merchants=0,$disp_stores=0,$disp_users=0,$disp_files=0,$levels=0)
	{
		$list="";
		
		$mech_files="";
		$store_list="";
		$user_list="";
		
		$mrr_adder="";
		$mrr_adder2="";
		$mrr_adder3="";
		if($_SESSION['access_level'] >=80)	
		{
			//nothing...allow all to be viewed for merchant and store....	
		}
		elseif($_SESSION['merchant_id'] > 0)
		{
			$mrr_adder.=" and merchants.id='".sql_friendly($_SESSION['merchant_id'])."'";
			if($_SESSION['store_id'] > 0)
     		{
     			$mrr_adder2.=" and store_locations.id='".sql_friendly($_SESSION['store_id'])."'";
     			$mrr_adder3.=" and users.store_id='".sql_friendly($_SESSION['store_id'])."'";	
     		}
     		$mrr_adder3.=" and users.merchant_id='".sql_friendly($_SESSION['merchant_id'])."'";	
		}
		elseif($_SESSION['user_id'] > 0)		
		{
			$mrr_adder.=" and merchants.user_id='".sql_friendly($_SESSION['user_id'])."'";	
			$mrr_adder3.=" and users.id='".sql_friendly($_SESSION['user_id'])."'";				
		}
				
		$level_disp="";
		for($i=0; $i <= $levels; $i++)
		{
			$level_disp=" style='padding-left:".(25 * $i)."px;'";	
		}		
		
		$sql = "
			select merchants.*	
			from merchants
			where merchants.deleted=0
				".$mrr_adder."			
				and merchants.id='".sql_friendly($start_comp_id)."'
			order by merchants.merchant asc
		";
		$data = simple_query($sql);
		while($row = mysqli_fetch_array($data))
		{
			$list.="<div class='merchant_block'>";
			$template_id=$row['template_id'];
			$mech_files=get_all_template_items_for_template($template_id,0,0);		//,$access=0,$editor=0
			$store_list="";
			$user_list="";
			
			if($disp_merchants > 0)		$list.="<div class='merchant_name'".$level_disp.">[".$row['id']."] ".$row['merchant']."</div>";	
			
			//get users for this merchant only
			$sql2 = "
     			select users.*	
     			from users
     			where users.deleted=0
     				".$mrr_adder3."			
     				and users.merchant_id='".sql_friendly($row['id'])."'
     				and users.store_id=0
     			order by users.last_name asc,users.first_name asc
			";
			$data2 = simple_query($sql2);
			while($row2 = mysqli_fetch_array($data2))
			{
				if($disp_users > 0)		$user_list.="<div class='user_name'".$level_disp.">[".$row2['id']."] ".$row2['first_name']." ".$row2['last_name']." (".$row2['username'].")</div>";	
			}
			
			
			//get all stores for this merchant (NO store)
			$sql2 = "
     			select store_locations.*	
     			from store_locations
     			where store_locations.deleted=0
     				".$mrr_adder2."			
     				and store_locations.merchant_id='".sql_friendly($row['id'])."'
     			order by store_locations.store_name asc,store_locations.store_number asc
			";
			$data2 = simple_query($sql2);
			while($row2 = mysqli_fetch_array($data2))
			{
				if($disp_stores > 0)		$store_list.="<div class='store_name'".$level_disp.">[".$row2['id']."] ".$row2['store_number']." ".$row2['store_name']."</div>";	
								
				$template_id2=$row2['template_id'];
								
				//get store files if not the same as the current merchant...
				if($template_id2!=$template_id)	$mech_files.=get_all_template_items_for_template($template_id2,0,0);		//,$access=0,$editor=0
				
				//get users for this merchant AND store....
     			$sql3 = "
          			select users.*	
          			from users
          			where users.deleted=0
          				".$mrr_adder3."			
          				and users.merchant_id='".sql_friendly($row['id'])."'
          				and users.store_id='".sql_friendly($row2['id'])."'
          			order by users.last_name asc,users.first_name asc
     			";
     			$data3 = simple_query($sql3);
     			while($row3 = mysqli_fetch_array($data3))
     			{
     				if($disp_users > 0)		$user_list.="<div class='user_name'".$level_disp.">[".$row3['id']."] ".$row3['first_name']." ".$row3['last_name']." (".$row3['username'].")</div>";	
     			}				
			}			
			
			//get all files for this merchant
			if($disp_files > 0)		$list.="<div class='file_name'".$level_disp."> <div class='merch_store_files'>".$mech_files."</div><div style='clear:both'></div></div>";	
			
			$list.="<div style='clear:both'></div>
				".$store_list."
				<div style='clear:both'></div>
				".$user_list."
			</div>
			<div style='clear:both'></div>";
						
			//get subsidiaries
			$sql2 = "
     			select merchants.id
     			from merchants
     			where merchants.deleted=0
     				".$mrr_adder."			
     				and merchants.parent_company_id='".sql_friendly($row['id'])."'
     			order by merchants.merchant asc
			";
			$data2 = simple_query($sql2);
			while($row2 = mysqli_fetch_array($data2))
			{
				$comp_id=$row2['id'];	
				$levs=( $levels + 1);
				$list.=show_merchant_teirs($comp_id,$disp_merchants,$disp_stores,$disp_users,$disp_files,$levs);
			}
		}		
		return $list;
	}
	
	function mrr_display_user_section($id,$rank_override="")
	{
		$tab="";
		
		if($id==0)	return $tab;
		
		$sql="
			select users.*,
				(select filename from attached_files where attached_files.section_id='8' and attached_files.deleted='0' and attached_files.xref_id=users.id order by id desc limit 1) as user_image,
				(select level_name from user_levels where user_levels.access_level=users.access_level and user_levels.deleted=0) as user_rank
			from users 
			where users.id='".sql_friendly($id)."'
				and users.deleted=0
		";
		$data = simple_query($sql);
		if($row = mysqli_fetch_array($data))
		{
			if(trim($rank_override)!="")		$row['user_rank']=$rank_override;
			
			$tab.="
				<div class='user_display_card'>
					<div class='user_card_rank'>".$row['user_rank']."</div>
					<div class='user_card_avatar'>".(trim($row['user_image'])!="" ? "<img class='user_card_avatar_image' src='/documents/".trim($row['user_image'])."' alt=''>" : "")."</div>	
					<div class='user_card_name'>".(trim($row['first_name']." ".$row['last_name'])!="" ? "Name: ".trim($row['first_name']." ".$row['last_name']) : "Username: ".trim($row['username'])).", ".$row['title']."</div>
					<div class='user_card_email'>E-Mail Address: ".$row['email']."</div>
					<div class='user_card_cell'>Cell #:  <div class='phone_number_block'>".$row['contact_phone1']."</div></div>
					<div class='user_card_phone'>Phone #: <div class='phone_number_block'>".$row['contact_phone2']."</div></div>
				</div>
				<div style='clear:both'></div>
			";
		}		
		//   
			
		return $tab;
	}
	
	function mrr_show_merchants()
	{		
		$merchant_info_list="
			<div id='merchant_list_form'>
			<table width='100%' cellpadding='1' cellspacing='1' border='0'>
		";		
		$mrr_adder="";
		if($_SESSION['merchant_id'] > 0)		$mrr_adder.="and merchants.id='".sql_friendly($_SESSION['merchant_id'])."'";
		elseif($_SESSION['access_level'] >=80)	$mrr_adder.="";
		elseif($_SESSION['user_id'] > 0)		$mrr_adder.="and merchants.user_id='".sql_friendly($_SESSION['user_id'])."'";
				
		$sql = "
			select merchants.*,
				(select count(*) from merchants me where me.deleted=0 and me.id!=merchants.id and me.parent_company_id=merchants.id) as inherited_by,
				corp.merchant as parent_company 		
			from merchants
				left join merchants corp on corp.id=merchants.parent_company_id and corp.deleted=0
			where merchants.deleted=0
				".$mrr_adder."			
				and merchants.archived=0
			order by merchants.merchant asc
		";
		$data = simple_query($sql);
		while($row = mysqli_fetch_array($data))
		{
			$allow_editor1="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
			$allow_editor2="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
			$allow_editor3="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
			
			$edit_mode="";
               $valid_user=check_user_edit_access('merchants',$row['id'],$_SESSION['user_id']);
               if($valid_user > 1)
               {
               	$allow_editor1="<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this Customer' onClick='edit_merchant(".$row['id'].",1);'>";
				$allow_editor2="<img src='/images/delete_small.png' alt='Delete' border='0' width='17' height='15' class='tooltip' title='Click to remove this Customer' onClick='edit_merchant(".$row['id'].",2);'>";
				//$allow_editor3="<img src='/images/checkbox.png' alt='Check' border='0' width='17' height='15' class='tooltip' title='Click to check it off (archive).' onClick='edit_merchant(".$row['id'].",3);'>";	
               }					
			
			if(!isset($row['parent_company']))		$row['parent_company']="";
			
			$is_group="";
			$is_subsid="";	
			$co_display=mrr_display_user_section($row['co_user_id'],"Compliance Officer");
			$grp_display=mrr_display_user_section($row['group_user_id'],"Group Manager");
			
			if($row['inherited_by'] > 0)			$is_group="  &nbsp; &nbsp; &nbsp;<b>(GROUP)</b>";
			if(trim($row['parent_company'])!="")	$is_subsid=" <i><b>Group: ".trim($row['parent_company'])."</b></i><br>";	
			
			if($_SESSION['access_level'] < 70)		{	$is_group="";	$is_subsid="";	$grp_display="";	}
			
			$merchant_info_list.="				
				<tr class='merch_cust merchant_cid_".$row['id']."'>
					<td valign='top' nowrap width='100'>
						<div class='merchant_store_id'>
							CID
							<div class='cid_uid' onClick='pick_selected_item(0,".$row['id'].",0);'>".$row['id']."</div>
						</div>
					</td>					
					<td valign='top'>
						<div class='merchant_store_info'>
							<div class='merchant_store_title' onClick='pick_selected_item(0,".$row['id'].",0);'>".trim($row['merchant'])."".$is_group."</div>
							<div class='merchant_store_desc'>
								".$is_subsid."								
								Corporate Phone#: <div class='phone_number_block'>".trim($row['contact_phone3'])."</div>
								Corporate Fax#:  <div class='phone_number_block'>".trim($row['contact_phone4'])."</div> 
								<br>".trim($row['address1']."".$row['address2'])."
								<br>".$row['city'].", ".$row['state']." ".$row['zip']."								
								<br>".$co_display."".$grp_display."
							</div>
						</div>
					</td>					
					<td valign='top' nowrap width='100'>
						<div class='merchant_store_icons'>
							<div class='merchant_store_icons_edit'>".$allow_editor1."</div>
							<div class='merchant_store_icons_delete'>".$allow_editor2."</div>
							<div class='merchant_store_icons_check'>".$allow_editor3."</div>
						</div>
					</td>	
				</tr>
			";
			
			if($_SESSION['merchant_id'] > 0 && $_SESSION['access_level'] >=70)
			{
				$merchant_info_list.=mrr_cascade_merchant_display($row['id']);
			}			
		}	
		
		$merchant_info_list.="</table></div>";
		$tab="
			<div id='merchant_section'>
				<div class='merchant_store_info_title'>
					<div style='float:right; margin-right:25px;'>
					".($_SESSION['access_level'] >=90  ? "<input type='button' name='create-new-merchant' id='create-new-merchant' value='Add Customer' onClick='edit_merchant(0,1);' class='buttonize'>" : "&nbsp;")."
					</div>
					Customers
				</div>
				<div style='clear:both;'></div>
				<div class='merchant_store_info_body'>".$merchant_info_list."</div>
			</div>
			<div id='dialog_delete_merchant' title='Remove this Customer?' style='display:none;'>
          		<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>This Customer will be permanently removed and cannot be recovered. Are you sure you want to delete it?</p>
          	</div>	
		";
		
		//$tab="";
		return $tab;	
	}
	function mrr_cascade_merchant_display($parent_id)
	{	//not intended to run alone... recursive looping for subsidiaries based on parent company ID.
		$merchant_info_list="";
		
		$sql = "
			select merchants.*,
				(select count(*) from merchants me where me.deleted=0 and me.id!=merchants.id and me.parent_company_id=merchants.id) as inherited_by,
				corp.merchant as parent_company 		
			from merchants
				left join merchants corp on corp.id=merchants.parent_company_id and corp.deleted=0
			where merchants.deleted=0
				and merchants.parent_company_id='".sql_friendly($parent_id)."'		
				and merchants.archived=0
			order by merchants.merchant asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		$allow_editor1="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
			$allow_editor2="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
			$allow_editor3="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
			
			$edit_mode="";
               $valid_user=check_user_edit_access('merchants',$row['id'],$_SESSION['user_id']);
               if($valid_user > 1)
               {
               	$allow_editor1="<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this Customer' onClick='edit_merchant(".$row['id'].",1);'>";
				$allow_editor2="<img src='/images/delete_small.png' alt='Delete' border='0' width='17' height='15' class='tooltip' title='Click to remove this Customer' onClick='edit_merchant(".$row['id'].",2);'>";
				//$allow_editor3="<img src='/images/checkbox.png' alt='Check' border='0' width='17' height='15' class='tooltip' title='Click to check it off (archive).' onClick='edit_merchant(".$row['id'].",3);'>";	
               }					
			
			if(!isset($row['parent_company']))		$row['parent_company']="";
			
			
			$is_group="";
			$is_subsid="";	
			$co_display=mrr_display_user_section($row['co_user_id'],"Compliance Officer");
			$grp_display=mrr_display_user_section($row['group_user_id'],"Group Manager");
			
			if($row['inherited_by'] > 0)			$is_group=" &nbsp; &nbsp; &nbsp;<b>(GROUP)</b>";
			if(trim($row['parent_company'])!="")	$is_subsid=" <i><b>Group: ".trim($row['parent_company'])."</b></i><br>";	
			
			if($_SESSION['access_level'] < 70)		{	$is_group="";	$is_subsid="";	$grp_display="";	}
			
			
			$merchant_info_list.="				
				<tr class='merch_cust merchant_cid_".$row['id']."'>
					<td valign='top' nowrap width='100'>
						<div class='merchant_store_id'>
							CID
							<div class='cid_uid' onClick='pick_selected_item(0,".$row['id'].",0);'>".$row['id']."</div>
						</div>
					</td>					
					<td valign='top'>
						<div class='merchant_store_info'>
							<div class='merchant_store_title' onClick='pick_selected_item(0,".$row['id'].",0);'>".trim($row['merchant'])."".$is_group."</div>
							<div class='merchant_store_desc'>
								".$is_subsid."
								Corporate Phone#: <div class='phone_number_block'>".trim($row['contact_phone3'])."</div>
								Corporate Fax#:  <div class='phone_number_block'>".trim($row['contact_phone4'])."</div> 
								<br>".trim($row['address1']."".$row['address2'])."
								<br>".$row['city'].", ".$row['state']." ".$row['zip']."								
								<br>".$co_display."".$grp_display."
							</div>
						</div>
					</td>					
					<td valign='top' nowrap width='100'>
						<div class='merchant_store_icons'>
							<div class='merchant_store_icons_edit'>".$allow_editor1."</div>
							<div class='merchant_store_icons_delete'>".$allow_editor2."</div>
							<div class='merchant_store_icons_check'>".$allow_editor3."</div>
						</div>
					</td>	
				</tr>
			";
     		
     		//now get any subsidiaries from this merchant...recursively.
     		if($_SESSION['merchant_id'] > 0 && $_SESSION['access_level'] >=70)
     		{
     			$merchant_info_list.=mrr_cascade_merchant_display($row['id']);	
     		}
     	}
     	return $merchant_info_list;     	
	}
	
	
	//stores
	function mrr_show_store_locations()
	{		
		$store_list="
			<div id='store_list_form'>
			<table width='100%' cellpadding='1' cellspacing='1' border='0'>
		";		
		$mrr_adder="";
		if($_SESSION['store_id'] > 0)			$mrr_adder.="and store_locations.id='".sql_friendly($_SESSION['store_id'])."'";
		elseif($_SESSION['merchant_id'] > 0)	$mrr_adder.="and store_locations.merchant_id='".sql_friendly($_SESSION['merchant_id'])."'";
		elseif($_SESSION['access_level'] >=80)	$mrr_adder.="";
		elseif($_SESSION['user_id'] > 0)		$mrr_adder.="and store_locations.user_id='".sql_friendly($_SESSION['user_id'])."'";
				
		$sql = "
			select store_locations.*,
				(select merchant from merchants where merchants.id=store_locations.merchant_id) as comp_name	
			from store_locations
			where store_locations.deleted=0
				and store_locations.archived=0
				".$mrr_adder."				
			order by store_locations.store_number asc,store_locations.store_name asc
		";
		$data = simple_query($sql);
		while($row = mysqli_fetch_array($data))
		{
			$allow_editor1="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
			$allow_editor2="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
			$allow_editor3="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
			
			$edit_mode="";
               $valid_user=check_user_edit_access('store_locations',$row['id'],$_SESSION['user_id']);
               if($valid_user > 1)
               {
               	$allow_editor1="<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this store' onClick='edit_store_location(".$row['id'].",1);'>";
				$allow_editor2="<img src='/images/delete_small.png' alt='Delete' border='0' width='17' height='15' class='tooltip' title='Click to remove this store' onClick='edit_store_location(".$row['id'].",2);'>";
				//$allow_editor3="<img src='/images/checkbox.png' alt='Check' border='0' width='17' height='15' class='tooltip' title='Click to check it off (archive).' onClick='edit_store_location(".$row['id'].",3);'>";	
               }					
			
			$cm_display=mrr_display_user_section($row['cm_user_id'],"Compliance Manager");
			
			$store_list.="
				<tr class='store_locals store_location_id_".$row['id']." store_merch_".$row['merchant_id']."'>
					<td valign='top' nowrap width='100'>
						<div class='merchant_store_id'>
							UID
							<div class='cid_uid' onClick='pick_selected_item(0,".$row['merchant_id'].",".$row['id'].");'>".$row['store_number']."</div>
						</div>
					</td>					
					<td valign='top'>
						<div class='merchant_store_info'>
							<div class='merchant_store_title' onClick='pick_selected_item(0,".$row['merchant_id'].",".$row['id'].");'>".trim($row['store_name'])."</div>
							<div class='merchant_store_desc'>
								".(trim($row['comp_name']) !="" ? " <i>Affiliated with ".trim($row['comp_name'])."</i><br>" : "")."
								".trim($row['address1']."".$row['address2'])."
								<br>".$row['city'].", ".$row['state']." ".$row['zip']."
								<br>
								Store Phone#: <div class='phone_number_block'>".trim($row['contact_phone3'])."</div>
								Store Fax#:  <div class='phone_number_block'>".trim($row['contact_phone4'])."</div>															
								<br>".$cm_display."
							</div>
						</div>
					</td>					
					<td valign='top' nowrap width='100'>
						<div class='merchant_store_icons'>
							<div class='merchant_store_icons_edit'>".$allow_editor1."</div>
							<div class='merchant_store_icons_delete'>".$allow_editor2."</div>
							<div class='merchant_store_icons_check'>".$allow_editor3."</div>
						</div>
					</td>	
				</tr>
			";
			
			if($_SESSION['merchant_id'] > 0 && $_SESSION['access_level'] >=70)
			{
				$store_list.=mrr_cascade_merchant_store_display($row['merchant_id']);
			}	
		}	
		
		$store_list.="</table></div>";
		$tab="
			<div id='store_location_section'>
				<div class='merchant_store_info_title'>
					<div style='float:right; margin-right:25px;'>
						".($_SESSION['access_level'] >=60  ? "<input type='button' name='create-new-store' id='create-new-store' value='Add Store' onClick='edit_store_location(0,1);' class='buttonize'>" : "&nbsp;")."
					</div>
					Stores
				</div>
				<div style='clear:both;'></div>
				<div class='merchant_store_info_body'>".$store_list."</div>	
			</div>
			<div id='dialog_delete_store_location' title='Remove this Store?' style='display:none;'>
          		<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>This Store will be permanently removed and cannot be recovered. Are you sure you want to delete it?</p>
          	</div>	
		";
		
		//$tab="";
		return $tab;	
	}
	function mrr_cascade_merchant_store_display($parent_id)
	{	//not intended to run alone... recursive looping for subsidiaries based on parent company ID.
		$store_list="";
		
		$sql2 = "
			select merchants.*,
				corp.merchant as parent_company 		
			from merchants
				left join merchants corp on corp.id=merchants.parent_company_id and corp.deleted=0
			where merchants.deleted=0
				and merchants.parent_company_id='".sql_friendly($parent_id)."'		
				and merchants.archived=0
			order by merchants.merchant asc
     	";
     	$data2=simple_query($sql2);
     	while($row2=mysqli_fetch_array($data2))
     	{
     		$merchant_id=$row2['id'];
     		$store_affiliate=trim($row2['merchant']);
     		
          	$sql = "
     			select *			
     			from store_locations
     			where deleted=0
     				and archived=0
     				and merchant_id='".sql_friendly($merchant_id)."'			
     			order by store_number asc,store_name asc
     		";
     		$data = simple_query($sql);
     		while($row = mysqli_fetch_array($data))
     		{
     			$allow_editor1="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
     			$allow_editor2="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
     			$allow_editor3="<img src='/images/spacer.png' alt='' border='0' width='17' height='15'>";
     			
     			$edit_mode="";
                    $valid_user=check_user_edit_access('store_locations',$row['id'],$_SESSION['user_id']);
                    if($valid_user > 1)
                    {
                    	$allow_editor1="<img src='/images/edit_small.png' alt='Edit' border='0' width='17' height='15' class='tooltip' title='Click to edit this store' onClick='edit_store_location(".$row['id'].",1);'>";
     				$allow_editor2="<img src='/images/delete_small.png' alt='Delete' border='0' width='17' height='15' class='tooltip' title='Click to remove this store' onClick='edit_store_location(".$row['id'].",2);'>";
     				//$allow_editor3="<img src='/images/checkbox.png' alt='Check' border='0' width='17' height='15' class='tooltip' title='Click to check it off (archive).' onClick='edit_store_location(".$row['id'].",3);'>";	
                    }					
     			
     			$cm_display=mrr_display_user_section($row['cm_user_id'],"Compliance Manager");
     			
     			$store_list.="
     			<tr class='store_locals store_location_id_".$row['id']." store_merch_".$row['merchant_id']."'>
					<td valign='top' nowrap width='100'>
						<div class='merchant_store_id'>
							UID
							<div class='cid_uid' onClick='pick_selected_item(0,".$row['merchant_id'].",".$row['id'].");'>".$row['store_number']."</div>
						</div>
					</td>					
					<td valign='top'>
						<div class='merchant_store_info'>
							<div class='merchant_store_title' onClick='pick_selected_item(0,".$row['merchant_id'].",".$row['id'].");'>".trim($row['store_name'])."</div>
							<div class='merchant_store_desc'>
								".(trim($store_affiliate) !="" ? " <i>Affiliated with ".trim($store_affiliate)."</i>" : "")."
								".trim($row['address1']."".$row['address2'])."
								<br>".$row['city'].", ".$row['state']." ".$row['zip']."
								<br>
								Store Phone#: <div class='phone_number_block'>".trim($row['contact_phone3'])."</div>
								Store Fax#:  <div class='phone_number_block'>".trim($row['contact_phone4'])."</div>															
								<br>".$cm_display."
							</div>
						</div>
					</td>					
					<td valign='top' nowrap width='100'>
						<div class='merchant_store_icons'>
							<div class='merchant_store_icons_edit'>".$allow_editor1."</div>
							<div class='merchant_store_icons_delete'>".$allow_editor2."</div>
							<div class='merchant_store_icons_check'>".$allow_editor3."</div>
						</div>
					</td>	
				</tr>
     			";	
     		}		
     		
     		//now get any subsidiaries from this merchant...recursively.
     		if($_SESSION['merchant_id'] > 0 && $_SESSION['access_level'] >=70)
     		{
     			$store_list.=mrr_cascade_merchant_store_display($merchant_id);	
     		}
     	}
     	return $store_list;     	
	}
	
	//file types
	function get_file_types_form()
	{
		$list="";
		
		$list.="
			<table cellspacing='0' cellpadding='0' border='0' width='100%'>
			<tr>
				<td valign='top'><b>File Type</b></td>
				<td valign='top'><b>Extension</b></td>			
				<td valign='top'><b>Min Size</b></td>
				<td valign='top'><b>Max Size</b></td>
				<td valign='top'><b>Archived</b></td>
				<td valign='top'><b>&nbsp;</b></td>
			</tr>
		";
		
		$cntr=0;
		$sql = "
     		select template_file_types.*
     		from template_file_types
     		where template_file_types.deleted=0
     		order by template_file_types.file_type_name asc,template_file_types.id asc
		";
		$data=simple_query($sql);
		while($row=mysqli_fetch_array($data))
		{			
			$list.="
				<tr class='".($cntr%2==0  ? "odd" : "even")."'>				
					<td valign='top'><input name='file_type_".$row['id']."_name' id='file_type_".$row['id']."_name' value=\"".$row['file_type_name']."\" class='tooltipx' title='Enter a label for this file type?' placeholder='Enter a file type label'></td>
					<td valign='top'><input name='file_type_".$row['id']."_ext' id='file_type_".$row['id']."_ext' value=\"".$row['file_type_ext']."\" class='tooltipx' title='Enter the extension for this file type?' placeholder='Enter a file extension like \".png\"'></td>					
					<td valign='top'><input name='file_type_".$row['id']."_min' id='file_type_".$row['id']."_min' value=\"".$row['file_size_min']."\" class='tooltipx' title='What is the minimum file size in KB?' size='5'> KB</td>
					<td valign='top'><input name='file_type_".$row['id']."_max' id='file_type_".$row['id']."_max' value=\"".$row['file_size_max']."\" class='tooltipx' title='What is the maximum file size in KB?' size='5'> KB</td>					
					<td valign='top'><input type='checkbox' name='file_type_".$row['id']."_archived' id='file_type_".$row['id']."_archived' value='1'".($row['archived'] > 0 ? " checked" : "") ."></td>		
					<td valign='top'>
						<img src='/images/delete_small.png' alt='Delete' border='0' width='17' height='15' class='tooltipx' title='Click to remove this file type' onClick='confirm_delete_file(".$row['id'].");'>
						<input type='hidden' name='file_array_".$cntr."' id='file_array_".$cntr."' value='".$row['id']."'>
					</td>			
				</tr>
			";			
			$cntr++;
		}	
		
		$list.="
				<tr><td valign='top' colspan='8'><br><hr><br><div class='tempate_editor_adder'>Add New File Type</div></td></tr>
				<tr>
					<td valign='top'><b>File Type</b></td>
					<td valign='top'><b>Extension</b></td>			
					<td valign='top'><b>Min Size</b></td>
					<td valign='top'><b>Max Size</b></td>
					<td valign='top'><b>Archived</b></td>
					<td valign='top'><b>&nbsp;</b></td>
				</tr>
				<tr>				
					<td valign='top'><input name='file_type_new_name' id='file_type_new_name' value=\"\" class='tooltipx' title='Enter a label for this file type?' placeholder='Enter a file type label'></td>
					<td valign='top'><input name='file_type_new_ext' id='file_type_new_ext' value=\"\" class='tooltipx' title='Enter the extension for this file type?' placeholder='Enter a file extension like \".png\"'></td>					
					<td valign='top'><input name='file_type_new_min' id='file_type_new_min' value=\"0\" class='tooltipx' title='What is the minimum file size in KB?' size='5'> KB</td>
					<td valign='top'><input name='file_type_new_max' id='file_type_new_max' value=\"0\" class='tooltipx' title='What is the maximum file size in KB?' size='5'> KB</td>					
					<td valign='top'>&nbsp;</td>		
					<td valign='top'>&nbsp;</td>			
				</tr>
				</table><input type='hidden' name='tot_file_types' id='tot_file_types' value='".$cntr."'>
		";
		
		return $list;
	}
	
	
	//template items
	function get_template_items_form($id)
	{
		$list="";
		$view_user_access=$_SESSION['view_access_level'];
		
		$list.="
			<table cellspacing='0' cellpadding='0' border='0' width='100%'>
			<tr>
				<td valign='top'><b>Group Under</b></td>
				<td valign='top'><b>Item Name</b></td>
				<td valign='top'><b>File Type</b></td>
				<td valign='top'><b><span title='File Extension'>Ext</span></b></td>				
				<td valign='top'><b>Min Size</b></td>
				<td valign='top'><b>Max Size</b></td>
				<td valign='top'><b><span title='Archived'>Arch</span></b></td>
				<td valign='top'><b><span title='Display/Sort Order...0 to 9999... lower number is first, then alphabetical sort.  Item grouped under an item are only sorted among themselves within the group.'>Order</span></b></td>
				<td valign='top'><b>&nbsp;</b></td>
			</tr>
		";
		
		$cntr=0;
		$sql = "
     		select template_items.*,
     			template_file_types.file_size_min as min_size,
     			template_file_types.file_size_max as max_size,
     			template_file_types.file_type_ext
     		from template_items
     			left join template_file_types on template_file_types.id=template_items.file_type_id
     		where template_items.deleted=0
     			and template_id='".sql_friendly($id)."'	
     		order by template_items.sub_group_id asc,template_items.archived asc,template_items.item_label asc,template_items.id asc
		";
		$data=simple_query($sql);
		while($row=mysqli_fetch_array($data))
		{
			$selbx=get_template_file_type_select_box("temp_item_".$row['id']."_type",$row['file_type_id'],0,"","");			//.... CD,Prompt,Class/Style/Javascript
			
			//get_template_item_select_box($field,$pre=0,$store=0, $merchant=0,$cd=0,$prompt="",$classy="",$group_id=0,$all_sub_groups=0,$groups_in_temp=0)
			$selbx2=get_template_item_select_box("temp_item_".$row['id']."_sub_group",$row['sub_group_id'],0, 0,0,"","",0,0,$id,1);			
			
			if($row['file_size_min']==0)		$row['file_size_min']=$row['min_size'];		//inherit from file type table...
			if($row['file_size_max']==0)		$row['file_size_max']=$row['max_size'];		//inherit from file type table...
			
			$selbx0=get_access_select_box($view_user_access,'template_access_level',$row['min_access_level'],0,"Minimum Required Access","");
			
			$list.="
				<tr class='".($cntr%2==0  ? "even" : "even")."'>		
					<td valign='top'>".$selbx2."<input type='hidden' name='id_array_".$cntr."' id='id_array_".$cntr."' value='".$row['id']."'></td>			
					<td valign='top'><input name='temp_item_".$row['id']."_name' id='temp_item_".$row['id']."_name' value=\"".$row['item_label']."\" class='tooltipx' title='Enter a label for this template item?' placeholder='Enter a label here'></td>
					<td valign='top'>".$selbx."</td>
					<td valign='top'><i>".$row['file_type_ext']."</i></td>					
					<td valign='top'><input name='temp_item_".$row['id']."_min' id='temp_item_".$row['id']."_min' value=\"".$row['file_size_min']."\" class='tooltipx' title='What is the minimum file size in KB?' size='5'> KB</td>
					<td valign='top'><input name='temp_item_".$row['id']."_max' id='temp_item_".$row['id']."_max' value=\"".$row['file_size_max']."\" class='tooltipx' title='What is the maximum file size in KB?' size='5'> KB</td>					
					<td valign='top'><input type='checkbox' name='temp_item_".$row['id']."_archived' id='temp_item_".$row['id']."_archived' value='1'".($row['archived'] > 0 ? " checked" : "") ."></td>
					<td valign='top'><input name='temp_item_".$row['id']."_zorder' id='temp_item_".$row['id']."_zorder' value=\"".$row['zorder']."\" class='tooltipx' title='Display/Sort Order...0 to 9999' size='4'></td>						
				</tr>
				<tr class='".($cntr%2==0  ? "even" : "even")."'>		
					<td valign='top' align='right'>Tool Tip </td>			
					<td valign='top' colspan='3'><input name='temp_item_".$row['id']."_tip' id='temp_item_".$row['id']."_tip' value=\"".$row['title_text']."\" class='tooltipx' title='Enter a tip for this template item' placeholder='Enter any title text (hint) text here' style='width:700px;'></td>
					<td valign='top' colspan='3'>Min Access: ".$selbx0."</td>
					<td valign='top'><i class='fa fa-trash' style='color:#e19918; cursor:pointer;' title='Click to remove this template item' onClick='confirm_delete_item(".$row['id'].");'></i></td>			
				</tr>
				<tr>		
					<td valign='top' colspan='8'><hr></td>			
				</tr>
			";			
			$cntr++;
		}	
		
		$selbx=get_template_file_type_select_box("temp_item_new_type",0,0,"","");			
		$selbx2=get_template_item_select_box("temp_item_new_sub_group",0,0, 0,0,""," width='100'",0,0,$id,1);		
		
		$list.="
				<tr>
					<td valign='top' colspan='8'>
						<br>
						<div style='float:right; width:50%;'>
							To add a new grouping for document types on this template (sub-types), create the new template item first without selecting the Group Under.  
							Save it to the template, then you can select it for future sub-types.
						</div>
						<div class='tempate_editor_adder'>Add New Template Item</div>
					</td>
				</tr>
				<tr>
     				<td valign='top'>
     					<b>Group Under</b>
     					<span class='mrr_hint_link' onClick='module_template_hint();'>(Make Sub-Item)</span>
     				</td>
     				<td valign='top' nowrap>
     					<b>Item Name</b>
     					<span class='mrr_hint_link' onClick='module_template_hint();'>or Copy Module</span>
     				</td>
     				<td valign='top'><b>File Type</b></td>
     				<td valign='top'><b><span title='File Extension'>Ext</span></b></td>				
					<td valign='top'><b>Min Size</b></td>
					<td valign='top'><b>Max Size</b></td>
					<td valign='top'><b><span title='Archived'>Arch</span></b></td>
					<td valign='top'><b><span title='Display/Sort Order...0 to 9999... lower number is first, then alphabetical sort.  Item grouped under an item are only sorted among themselves within the group.'>Order</span></b></td>
				</tr>
				<tr>		
					<td valign='top'>".$selbx2."</td>			
					<td valign='top'><input name='temp_item_new_name' id='temp_item_new_name' value=\"\" class='tooltipx' title='Enter a label for this template item?' placeholder='Enter a label here'></td>
					<td valign='top'>".$selbx."</td>
					<td valign='top'><i>&nbsp;</i></td>					
					<td valign='top'><input name='temp_item_new_min' id='temp_item_new_min' value=\"0\" class='tooltipx' title='What is the minimum file size in KB?' size='5'> KB</td>
					<td valign='top'><input name='temp_item_new_max' id='temp_item_new_max' value=\"0\" class='tooltipx' title='What is the maximum file size in KB?' size='5'> KB</td>					
					<td valign='top'>&nbsp;</td>	
					<td valign='top'><input name='temp_item_new_zorder' id='temp_item_new_zorder' value=\"0\" class='tooltipx' title='Display/Sort Order...0 to 9999' size='4'></td>	
				</tr>
				<tr>		
					<td valign='top' align='right'>Tool Tip </td>			
					<td valign='top' colspan='6'><input name='temp_item_new_tip' id='temp_item_new_tip' value=\"\" class='tooltipx' title='Enter a tip for this template item' placeholder='Enter any title text (hint) text here' style='width:800px;'></td>					
					<td valign='top'>&nbsp;</td>			
				</tr>
				</table>
				<input type='hidden' name='tot_temp_items' id='tot_temp_items' value='".$cntr."'>
		";
		
		return $list;
	}
	function get_template_file_type_select_box($field,$pre=0,$cd=0,$prompt="",$classy="")
     {     	
     	$selbox="<select name='".$field."' id='".$field."'".$classy.">";
     	
     	if($pre==0)		$sel=" selected";		else	$sel="";
     	$selbox.="<option value='0'".$sel.">".$prompt."</option>";	
     	
     	$mrr_adder="";
     	if($cd ==1)	$mrr_adder.=" and archived>0";		else		$mrr_adder.=" and archived=0";
     	     	
     	$sql = "
     		select * 
     		from template_file_types
     		where deleted=0	
     			".$mrr_adder."
     		order by file_type_name asc
     	";
     	$data=simple_query($sql);
     	while($row=mysqli_fetch_array($data))
     	{
     		$namer="".$row['file_type_name']."";	
     		
     		if($pre==$row['id'])		$sel=" selected";		else	$sel="";
     		$selbox.="<option value='".$row['id']."'".$sel.">".$namer."</option>";	
     		
     	}     	
     	
     	$selbox.="</select>";
     	return $selbox;
     }
	
	
	function create_uploader_section($field_name,$label,$section,$xref_id,$call_back="", $display_text = "")
	{
		if($call_back=="")		$call_back="show_user_image";
		
		$img_class="";
		if($field_name == 'cm_cert_image_holder_'.$xref_id.'')
		{
			$img_class=" class='cm_cert_image_holder' user_id='".$xref_id."'";
		}
		
		$field_name2=$field_name;
		if(substr_count($field_name2,".") == 0 && substr_count($field_name2,"#") == 0)		$field_name2="#".$field_name;
		
				
		echo "
		<table>
			<tr>
				".($label != '' ? "<td valign='top' align='left' width='150'><b>".$label."</b></td>" : "")."
				<td valign='top' align='left'>					
					
					<div style='float:left;margin-right:20px'>	
		";
					$upload_section = new upload_section();
					$upload_section->section_id = $section;
					$upload_section->xref_id = $xref_id;
					if($display_text != '') $upload_section->display_text = $display_text;
					$upload_section->display_style = 1;
					$upload_section->param('callback_function', ''.$call_back.'('.$xref_id.', "'.$field_name2.'" )');
					$upload_section->show();
						
		echo "
					</div>
					<div style='clear:both'></div>
					<div style='float:left' id='".$field_name."'".$img_class."></div>
				</td>	
			</tr>
		</table>		
		";	
	}
	
	
	//various copy functions....
	function copy_contact_info_to_merchant($user_id,$merchant_id)
     {	//copy the base contact info from this user to the merchant...
     	$sql = "
     			select *	
     			from users
     			where id='".sql_friendly($user_id)."'
     		";
     	$data = simple_query($sql);
     	if($row = mysqli_fetch_array($data))	
     	{
     		$first=trim($row['first_name']);
     		$last=trim($row['last_name']);
     		$title=trim($row['title']);
     		$email=trim($row['email']);
     		$cell=trim($row['contact_phone1']);
     		$phone=trim($row['contact_phone2']);
     		
     		$sql = "
     			update merchants set
     				contact_title='".sql_friendly($title)."',
     				contact_first_name='".sql_friendly($first)."',
     				contact_last_name='".sql_friendly($last)."',
     				contact_phone1='".sql_friendly($cell)."',
     				contact_phone2='".sql_friendly($phone)."',     				
     				contact_email='".sql_friendly($email)."'
     				
     			where id='".sql_friendly($merchant_id)."'
     		";
     		simple_query($sql);	
     	}
     }
     function copy_merchant_info_to_store($merchant_id,$store_id)
     {
     	$sql = "
     			select *	
     			from merchants
     			where id='".sql_friendly($merchant_id)."'
     		";
     	$data = simple_query($sql);
     	if($row = mysqli_fetch_array($data))	
     	{
     		$first=trim($row['contact_title']);
     		$last=trim($row['contact_first_name']);
     		$title=trim($row['contact_last_name']);
     		$email=trim($row['contact_email']);
     		$phone1=trim($row['contact_phone1']);
     		$phone2=trim($row['contact_phone2']);
     		
     		$co_id=trim($row['co_user_id']);
     		
     		$name=trim($row['merchant']);
     		$addr1=trim($row['address1']);
     		$addr2=trim($row['address2']);
     		$city=trim($row['city']);
     		$state=trim($row['state']);
     		$zip=trim($row['zip']);
     		
     		$phone3=trim($row['contact_phone3']);
     		$phone4=trim($row['contact_phone4']);
     		
     		$msb_aud=trim($row['msb_auditor']);
     		$msb_ref=trim($row['msb_ref_number']);
     		$msb_cell=trim($row['msb_cell']);
     		$msb_phone=trim($row['msb_phone']);
     		$msb_email=trim($row['msb_email']);
     		$msb_addr=trim($row['msb_address']);
     		
     		$irs_addr=trim($row['irs_address']);
     		$irs_cell=trim($row['irs_cell']);
     		$irs_phone=trim($row['irs_phone']);
     		$irs_email=trim($row['irs_email']);
     		$irs_agent=trim($row['irs_agent']);
     		$irs_empid=trim($row['irs_employee_id']);
     		$irs_case=trim($row['irs_case_number']);   	
     		
     		$sql = "
     			update store_locations set
     				store_name='".sql_friendly($name)."',	
     				
     				address1='".sql_friendly($addr1)."',
     				address2='".sql_friendly($addr2)."',
     				city='".sql_friendly($city)."',
     				state='".sql_friendly($state)."',
     				zip='".sql_friendly($zip)."',
     				
     				contact_phone1='".sql_friendly($phone1)."',
     				contact_phone2='".sql_friendly($phone2)."',	
     				contact_title='".sql_friendly($title)."',
     				contact_first_name='".sql_friendly($first)."',
     				contact_last_name='".sql_friendly($last)."',
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
     				cm_user_id='".sql_friendly($co_id)."'     				
     				
     			where id='".sql_friendly($store_id)."'
     		";
     		//simple_query($sql);	
     	}	
     }
     
     function copy_all_template_items($master_id,$copy_id)
     {	//if $item_id > 0, only copy that item and sub items...
     	
     	$sql = "
     			update template_items set
     				deleted=1
     			where template_id='".sql_friendly($copy_id)."'
     		";
     	simple_query($sql);
     	
     	$sql = "
     			select *	
     			from template_items
     			where template_id='".sql_friendly($master_id)."'
     				and deleted=0
     				and archived=0
     			order by sub_group_id asc,id asc
     		";
     	$data = simple_query($sql);
     	while($row = mysqli_fetch_array($data))	
     	{
     		//find the new sub_group_id based on the new template
     		$sub_group_id=$row['sub_group_id'];		//start with the old one.
     		
     		if($sub_group_id > 0)
     		{
     			$sqlx = "
     				select item_label
     				from template_items
     				where id='".sql_friendly($sub_group_id)."'
     			";
     			$datax = simple_query($sqlx);
     			if($rowx = mysqli_fetch_array($datax))
     			{
     				$find_item=trim($rowx['item_label']);
     				//now get the new version of this for the new template
     				$sqly = "
     					select id
     					from template_items
     					where deleted=0
     						and template_id='".$copy_id."'
     					 	and item_label='".sql_friendly($find_item)."'
     				";
     				$datay = simple_query($sqly);
     				if($rowy = mysqli_fetch_array($datay))
     				{
     					$sub_group_id=$rowy['id'];		
     				}
     			}
     		}		
     		
     		$sql2="
     			insert into template_items
     				(id,
     				template_id,
     				item_label,
     				file_type_id,
     				file_size_min,
     				file_size_max,
     				linedate_added,
     				deleted,
     				archived,
     				user_id,
     				zorder,
     				title_text,
     				min_access_level,
     				sub_group_id)
     			values
     				(NULL,
     				'".sql_friendly($copy_id)."',
     				'".sql_friendly(trim($row['item_label']))."',
     				'".sql_friendly($row['file_type_id'])."',
     				'".sql_friendly($row['file_size_min'])."',
     				'".sql_friendly($row['file_size_max'])."',
     				NOW(),
     				0,
     				0,
     				'".sql_friendly($_SESSION['user_id'])."',
     				'".sql_friendly($row['zorder'])."',
     				'".sql_friendly($row['title_text'])."',  
     				'".sql_friendly($row['min_access_level'])."',   				
     				'".sql_friendly($sub_group_id)."')
     		";		
     		simple_query($sql2);
     	}	
     }
     function copy_all_template_item_subs($item_id,$temp_id)
     {	//copy items in $item_id (including itself) to template $temp_id.
     	$sub_group_id=0;
     	
     	if($temp_id==0 || $item_id==0)		return $sub_group_id;
     	     	
     	$sql = "
			select *	
			from template_items
			where id='".sql_friendly($item_id)."'
		";
     	$data = simple_query($sql);
     	if($row = mysqli_fetch_array($data))	
     	{
     		$sql2="
     			insert into template_items
     				(id,
     				template_id,
     				item_label,
     				file_type_id,
     				file_size_min,
     				file_size_max,
     				linedate_added,
     				deleted,
     				archived,
     				user_id,
     				zorder,
     				title_text,
     				min_access_level,
     				sub_group_id)
     			values
     				(NULL,
     				'".sql_friendly($temp_id)."',
     				'".sql_friendly(trim($row['item_label']))."',
     				'".sql_friendly($row['file_type_id'])."',
     				'".sql_friendly($row['file_size_min'])."',
     				'".sql_friendly($row['file_size_max'])."',
     				NOW(),
     				0,
     				0,
     				'".sql_friendly($_SESSION['user_id'])."',
     				'".sql_friendly($row['zorder'])."',
     				'".sql_friendly($row['title_text'])."',   
     				'".sql_friendly($row['min_access_level'])."',  				
     				'0')
     		";		
     		simple_query($sql2);
     		$sub_group_id=get_mysql_insert_id();	 		
     		
     		//now get any sub-items to copy to this template.
     		
     		$sqlx = "
				select template_items.*
				from template_items
				where template_items.deleted=0
					and template_items.archived=0
					and template_items.sub_group_id='".sql_friendly($item_id)."'
				order by template_items.zorder asc, template_items.item_label asc
			";
			$datax = simple_query($sqlx);
			while($rowx = mysqli_fetch_array($datax))
			{
				$sqly="
          			insert into template_items
          				(id,
          				template_id,
          				item_label,
          				file_type_id,
          				file_size_min,
          				file_size_max,
          				linedate_added,
          				deleted,
          				archived,
          				user_id,
          				zorder,
          				title_text,
          				min_access_level,
          				sub_group_id)
          			values
          				(NULL,
          				'".sql_friendly($temp_id)."',
          				'".sql_friendly(trim($rowx['item_label']))."',
          				'".sql_friendly($rowx['file_type_id'])."',
          				'".sql_friendly($rowx['file_size_min'])."',
          				'".sql_friendly($rowx['file_size_max'])."',
          				NOW(),
          				0,
          				0,
          				'".sql_friendly($_SESSION['user_id'])."',
          				'".sql_friendly($rowx['zorder'])."',
          				'".sql_friendly($rowx['title_text'])."',     	
          				'".sql_friendly($rowx['min_access_level'])."',  			
          				'".sql_friendly($sub_group_id)."')
          		";		
          		simple_query($sqly);
			}     		
     	}	
     	return $sub_group_id;
     }
     
     function show_selected_route_info()
     {	//this function is meant to display breadcrumb trail for user to see who/what is being viewed.
     	$display="";
     	
     	$reload_flag=0;
     	//$reload_flag=1;
     	
     	$user=$_SESSION['selected_user_id'];
     	$merchant=$_SESSION['selected_merchant_id'];
     	$store=$_SESSION['selected_store_id'];
     		
		if($user==0 && $merchant ==0 && $store==0)			return $display;
		
		$display.="<div class='breadcrumb_trail'>";			
		
		$display.="<span class='mrr_link_simulator buttonize btn btn-default add_new_btn' onClick='debread_crumb_trail(0);'>Clear All</span>";	
		
		if($merchant > 0)
		{
			$sql = "
     			select merchant	
     			from merchants
     			where id='".sql_friendly($merchant)."'
     		";
     		$data = simple_query($sql);
     		while($row = mysqli_fetch_array($data))	
     		{	
     			$display.=" <span class='mrr_link_simulator buttonize btn btn-default add_new_btn' onClick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$merchant.",0);'><span id='bct_merchant_id' style='display:none;'>".$merchant."</span>".trim($row['merchant'])."</span>";	
     			//$display.=" <span class='mrr_link_simulator buttonize btn btn-default add_new_btn' onClick='debread_crumb_trail(1);'><span id='bct_merchant_id' style='display:none;'>".$merchant."</span>".trim($row['merchant'])."</span>";	
     		}			
		}
		
		if($store > 0)
		{
			$sql = "
     			select store_name,store_number	
     			from store_locations
     			where id='".sql_friendly($store)."'
     		";
     		$data = simple_query($sql);
     		while($row = mysqli_fetch_array($data))	
     		{
     			$display.="  <span class='mrr_link_simulator buttonize btn btn-default add_new_btn' onClick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(0,".$merchant.",".$store.");'><span id='bct_store_id' style='display:none;'>".$store."</span>".trim($row['store_name'])." UID: ".trim($row['store_number'])."</span>";	
     			//$display.="  <span class='mrr_link_simulator buttonize btn btn-default add_new_btn' onClick='debread_crumb_trail(2);'><span id='bct_store_id' style='display:none;'>".$store."</span>".trim($row['store_name'])." UID: ".trim($row['store_number'])."</span>";	
     		}			
		}
		
		if($user > 0)
		{
			$sql = "
     			select first_name,last_name,username	
     			from users
     			where id='".sql_friendly($user)."'
     		";
     		$data = simple_query($sql);
     		while($row = mysqli_fetch_array($data))	
     		{
     			$display.=" <span class='mrr_link_simulator buttonize btn btn-default add_new_btn' onClick='pick_selected_item".($reload_flag > 0  ? "_v2" : "")."(".$user.",".$merchant.",".$store.");'><span id='bct_user_id' style='display:none;'>".$user."</span>".trim($row['first_name'])." ".trim($row['last_name'])."</span>";		//trim($row['username'])	
     			//$display.=" <span class='mrr_link_simulator buttonize btn btn-default add_new_btn' onClick='debread_crumb_trail(3);'><span id='bct_user_id' style='display:none;'>".$user."</span>".trim($row['first_name'])." ".trim($row['last_name'])."</span>";	
     		}			
		}	
			
		$display.="</div>";			
     	
     	return $display;
     }
     
     function display_auditor2_file_controls($id=0)
     {
     	$tab="";
     	
     	$all_files=get_all_template_items_for_template($id,0,0,1);		//files that can be used...
     	$viewable=display_auditor2_files(1);						//only the sleected files that can be seen by Auditor2
     	
     	$tab.="
     		<table cellpadding='0' cellspacing='0' border='0' width='100%'>
     		<tr>
     			<td valign='top' width='49%'>
     				<div class='auditor_file_cabinet portlet left_col'>
     					<div class='portlet-header mrr_aud_style'>Available Files</div>
						<div class='portlet-content'>
     					".$all_files."
     					</div>
     				</div>
     			</td>
     			<td valign='top'>&nbsp;</td>
     			<td valign='top' width='49%'>
     				<div class='auditor_file_cabinet portlet right_col'>     					
     					<div class='portlet-header mrr_aud_style'>Auditor 2 Files</div>
						<div class='portlet-content'>
     					".$viewable."
     					
     					<div style='text-align:center;'><br><br><span class='buttonize btn btn-default add_new_btn' onClick=\"$('.auditor2').toggle();\">Toggle Auditor2 View</span></div>
     					</div>
     				</div>
     			</td>
     		</tr>       		  		
     		</table>
     	";
     	
     	return $tab;	
     }
     
     function display_auditor2_files($show_auditor_control=0)
     {
     	$tab="";
     	
     	$mrr_adder="";
		
		//find merchant template first...acts as a default.
     	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$mrr_adder.=" and merchant_id='".sql_friendly($_SESSION['selected_merchant_id'])."'";
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$mrr_adder.=" and merchant_id='".sql_friendly($_SESSION['merchant_id'])."'";
     	}
     	
     	//find store template next...override merchant if set.
     	if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
     	{
     		$mrr_adder.=" and (store_id='".sql_friendly($_SESSION['selected_store_id'])."' or store_id=0)";
     	}     	
     	elseif($_SESSION['store_id'] > 0)
     	{
     		$mrr_adder.=" and (store_id='".sql_friendly($_SESSION['store_id'])."' or store_id=0)";
     	}
     	
     	$mrr_wide=250;				
     	if($show_auditor_control > 0 )		$mrr_wide=200;			// width='".$mrr_wide."'
     	
     	$tab.="<table class='tablesorterx' width='100%'>
				<thead>
				<tr>
					<th valign='top' width='100' nowrap>Upload Date</th>
					<th valign='top'>Document Name</th>
					<th valign='top' width='100' nowrap>Document Date</th>
					".($show_auditor_control > 0  ? "<th valign='top' width='50'>&nbsp;</th>" : "")."
				</tr>
				</thead>
				<tbody>
		";		//
     	   	
     	$cntr=0;			
		$sql="
          	select *
             	from attached_files
             	where deleted=0
             		and auditor2_viewable > 0               		 
             		".$mrr_adder."
             		and linedate_display_start <= NOW()
             	order by public_name asc,id asc
          ";
          $data = simple_query($sql);          
          while($row = mysqli_fetch_array($data))
          {
          	$name=$row['filename'];
          	if(trim($row['public_name'])!="")	$name=$row['public_name'];
          	
          	
          	
          	if($row['public_flag'] > 0)   
          	{   	
          		$tab.="
          			<tr class='".($cntr%2==0 ? "even" : "odd" )."'>
          				<td valign='top'>".date("m/d/Y",strtotime($row['linedate_added']))."</td>
          				<td valign='top'>
                    			<a href='documents/".$row['filename']."' target='_blank' onClick='set_email_view_log(".$row['id'].");' title='View this document...' class='".($show_auditor_control ? "auditor_download" : "")."' attachment_id='$row[id]'>
                    				<div class='template_item_file_name'>".$name."</div>
                    			</a>		
          				</td>
          				<td valign='top'>".date("m/d/Y",strtotime($row['linedate_display_start']))."</td>    
          				".($show_auditor_control > 0  ? "<td valign='top'><img src='common/images/prev_orange.png' alt='' border='0' onClick='update_auditor2_list(".$row['id'].",0);' style='cursor:pointer;height:16px'></td>" : "")."     				
          			</tr>          		
          			";
          	}
          	else
          	{
          		$tab.="
          			<tr class='".($cntr%2==0 ? "even" : "odd" )."'>
          				<td valign='top'>".date("m/d/Y",strtotime($row['linedate_added']))."</td>
          				<td valign='top'>
                    			<span class='mrr_link_simulator ".($show_auditor_control ? "auditor_download" : "")."' attachment_id='$row[id]' onclick='set_email_view_log(".$row['id']."); view_attached_file(".$row['section_id'].",".$row['xref_id'].",".$row['id'].");' title='View this document...'>
                    				<div class='template_item_file_name'>".$name."</div>
                    			</span>	
          				</td>
          				<td valign='top'>".date("m/d/Y",strtotime($row['linedate_display_start']))."</td>  
          				".($show_auditor_control > 0  ? "<td valign='top'><img src='common/images/prev_orange.png' alt='' border='0' onClick='update_auditor2_list(".$row['id'].",0);' style='cursor:pointer;;height:16px'></td>" : "")."
          			</tr>
          			";
          	}	
          	$cntr++;
          }
          
          if($show_auditor_control==0)
          {	//user is Auditor 2 and might need to download all files...
          	$tab.="
				<tr>
          				<td valign='top' colspan='3' align='center'>
          					<br>
          					<span class='buttonize btn btn-default add_new_btn' onClick='mrr_download_all_docs(1);'>Download All Files</span>
          					<br>
          					<br>
          				</td>  
          		</tr>
			";	
          }
          
		$tab.="
			</tbody>
			</table>
		";
		if($cntr==0)				$list="";		//clean out table...blank anyway.	
     	
     	return $tab;	
     }
  
  	function get_filename_without_unique($fname) 
  	{
     	// shows the filename without the unique numbers on the end
     	
     	$last_dash_pos = strrpos($fname, "-"); // location where the last dash is in the filename, since our unique number comes after that
     	if($last_dash_pos) 
     	{
     		$finfo = pathinfo($fname);
     		$ext = $finfo['extension']; // separate out the file extension
     		$fname_nounique = substr($fname, 0, $last_dash_pos).".$ext"; // build the full filename
     	} 
     	else 
     	{
     		// no dash found, so display the original filename
     		$fname_nounique = $fname;
     	}
     	
     	return $fname_nounique;
     }
  	
  	function show_image($image) {
  		// function to make sure the image exists, if it doesn't show a placeholder
  		if($image != '' && file_exists($image) && is_file($image)) {
  			return $image;
  		} else {
  			return "images/no-profile-image.png";
  		}
  	}   
  	
  	function cm_document_slot_filler($add_employees=0)
  	{
  		$tab="";
  		$merchant=0;
  		//find merchant 
     	if($_SESSION['merchant_id'] == 0 && $_SESSION['selected_merchant_id'] > 0)
     	{
     		$merchant=$_SESSION['selected_merchant_id'];
     	}
     	elseif($_SESSION['merchant_id'] > 0)
     	{
     		$merchant=$_SESSION['merchant_id'];
     	}
     	     	
     	
  		if($merchant==0)		return "";		//no merchant so don't bother...otherwise every single store would show.
  		
  		$mrr_adder="";	
  		$store=0;	
     	
     	//find store(s) to show officers
     	if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
     	{
     		$mrr_adder.=" and id='".sql_friendly($_SESSION['selected_store_id'])."'";
     		$store=$_SESSION['selected_store_id'];	
     	}     	
     	elseif($_SESSION['store_id'] > 0)
     	{
     		$mrr_adder.=" and id='".sql_friendly($_SESSION['store_id'])."'";
     		$store=$_SESSION['store_id'];	
     	}
  		
  		global $user_thumb_width;
  		global $user_thumb_height;
  		
  		$multi="";		if($store==0 && $add_employees==0)		$multi=" default_closed";
  		
  		$sql = "
			select store_locations.*,
				(select attached_files.filename from attached_files where attached_files.deleted=0 and attached_files.xref_id=store_locations.id and attached_files.section_id=10 order by id desc limit 1) as store_image,
				(select users.contact_phone1 from users where users.id=store_locations.cm_user_id) as cm_user_cell,
				(select users.contact_phone2 from users where users.id=store_locations.cm_user_id) as cm_user_phone,
				(select attached_files.filename from attached_files where attached_files.deleted=0 and attached_files.xref_id=store_locations.cm_user_id and attached_files.section_id=8 order by id desc limit 1) as cm_image
			from store_locations
			where store_locations.deleted=0
				and store_locations.archived=0
				and store_locations.merchant_id='".sql_friendly($merchant)."'
				".$mrr_adder."
			order by store_locations.store_number, store_locations.store_name,store_locations.address1
		";
		$data=simple_query($sql);	
		while($row = mysqli_fetch_array($data))
  		{
  			$image="images/no-profile-image.png";		$namer="";  	$cell="";		$phone="";  	$email="";		$store_loc="";
  			$cm_tab="";
  			
  			$store_loc=trim($row['store_name']);
  			
  			if($row['cm_user_id'] > 0)
  			{
  				if($row['cm_image']!="")		$image="documents/".$row['cm_image'].""; 
  				$email="".mrr_get_user_email_address($row['cm_user_id'],1)."";
  				$namer="".mrr_get_user_email_names($row['cm_user_id'],1)."";	
  				$cell="".$row['cm_user_cell']."";
  				$phone="".$row['cm_user_phone']."";	
  				
  				if($_SESSION['access_level'] >=40)
  				{
  					ob_start();				
  					
  					create_uploader_section('cm_cert_image_holder_'.$row['cm_user_id'].'',"",SECTION_CERTIFICATES,$row['cm_user_id'],'show_user_cert2', 'Upload Certificate');		//
  					  				
  					$cm_tab=ob_get_clean();  				
  				}
  				else
				{
					$cm_tab="<img src='images/no-profile-image.png' alt='' width='200'>";
				}
  			}	
  			
  			$employees="";
  			if($add_employees > 0)
  			{
  				//$employees.="<br><b> Employees go here... </b><br>";
  				
  				$employees.="<br>".cm_document_slot_filler_employees($row['id'],$merchant,$row['cm_user_id']);		//store ID , merchant ID
  				
  				$tab.=" 
     				<table style='width:100%;' class='internal_table'>			
     					<tr>
     					  	<td valign='top' width='30%'>
     							<table class='table table-striped internal_table'>
     								<tbody>
     									<tr><td>STORE LOCATION<br><span>".$store_loc."</span></td></tr>
     									<tr><td>&nbsp;</td></tr>
     									<tr><td>COMPLIANCE MANAGER<br><span>".$namer."</span></td></tr>
     									<tr><td>&nbsp;</td></tr>
     									<tr><td>CM EMAIL ADDRESS<br><span>".$email."</span></td></tr>
     									<tr><td>&nbsp;</td></tr>
     									<tr style='display:none;'><td>CM CELL NUMBER<br><span>".$cell."</span></td></tr>
     									<tr style='display:none;'><td>CM PHONE NUMBER<br><span>".$phone."</span></td></tr>
     									<tr><td valign='top' class='pos_rel cm_photo mrr_cust_info'>CM PHOTO<br><img src='".$image."' alt='' width='".$user_thumb_width."'></td></tr>
     								</tbody>
     							</table>
     						</td>
     					  	
     					  	<td valign='top' class='pos_rel cm_photo mrr_cust_info'>CM CERTIFICATE<br>".$cm_tab."</td>	
     					</tr>
     					".$employees."	
     				</table>
       			";
  			} 
  			else
  			{
  				$tab.="  			
       			<div class='portlet default_closed cust_info'>
       				<div class='portlet-header'>COMPLIANCE MANAGER</div>
          			<div class='portlet-content'>
          				<table style='width:100%;'>			
          					<tr>
          					  	<td valign='top' width='30%'>
          							<table class='table table-striped internal_table'>
          								<tbody>
          									<tr><td>STORE LOCATION<br><span>".$store_loc."</span></td></tr>
          									<tr><td>&nbsp;</td></tr>
          									<tr><td>COMPLIANCE MANAGER<br><span>".$namer."</span></td></tr>
          									<tr><td>&nbsp;</td></tr>
          									<tr><td>CM EMAIL ADDRESS<br><span>".$email."</span></td></tr>
          									<tr><td>&nbsp;</td></tr>
          									<tr><td>CM CELL NUMBER<br><span>".$cell."</span></td></tr>
          									<tr><td>OFFICE PHONE<br><span>".$phone."</span></td></tr>
          									<tr><td valign='top' class='pos_rel cm_photo mrr_cust_info'>CM PHOTO<br><img src='".$image."' alt='' width='".$user_thumb_width."'></td></tr>
          								</tbody>
          							</table>
          						</td>          					  	
          					  	<td valign='top' class='pos_rel cm_photo mrr_cust_info'>&nbsp;</td>	
          					</tr>          								
          				</table>				
          			</div>  	
          		</div>		
       			";
  			}
  		}
  		return $tab;
  	}
  	
  	function cm_document_slot_filler_employees($store,$merchant=0,$cm_id=0)
  	{
  		if($store==0)		return "";
  		
  		$tab="";
  		
  		global $user_thumb_width;
  		global $user_thumb_height;
  		
  		$sql = "
			select users.*,
				(select store_locations.store_name from store_locations where store_locations.id='".sql_friendly($store)."') as store_loc,
				(select user_levels.level_name from user_levels where user_levels.access_level=users.access_level) as level_desc,
				(select attached_files.filename from attached_files where attached_files.deleted=0 and attached_files.xref_id=users.id and attached_files.section_id=8 order by id desc limit 1) as user_image
				
			from users
			where users.deleted=0
				and users.archived=0
				and users.store_id='".sql_friendly($store)."'
				".($merchant > 0  ? " and users.merchant_id='".sql_friendly($merchant)."'" : "")."
				".($cm_id > 0  ? " and users.id!='".sql_friendly($cm_id)."'" : "")."
				and users.access_level =20
			order by users.first_name asc, users.last_name asc, users.username asc, users.id asc
		";
		$data=simple_query($sql);	
		while($row = mysqli_fetch_array($data))
		{
			if($_SESSION['access_level'] >=40)
			{
				ob_start();				
  					
  				create_uploader_section('cm_cert_image_holder_'.$row['id'].'',"",SECTION_CERTIFICATES,$row['id'],'show_user_cert2','Upload Certificate');		//
  					  				
  				$user_tab=ob_get_clean();
			}
			else
			{
				$user_tab="<img src='images/no-profile-image.png' alt='' width='200'>";	
			}
			
			$tab.="
				<tr><td colspan='3'>&nbsp;</td></tr>		
				<tr>
				  	<td valign='top' width='30%'>
						<table class='table table-striped internal_table'>
							<tbody>
								<tr><td>STORE LOCATION<br><span>".$row['store_loc']."</span></td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>EMPLOYEE<br><span>".$row['first_name']." ".$row['last_name']."</span></td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>EMAIL ADDRESS<br><span>".$row['email']."</span></td></tr>
								<tr style='display:none;'><td>CELL NUMBER<br><span>".$row['contact_phone1']."</span></td></tr>
								<tr style='display:none;'><td>PHONE NUMBER<br><span>".$row['contact_phone2']."</span></td></tr>
								<tr><td valign='top' class='pos_rel cm_photo mrr_cust_info'>PHOTO<br><img src='".create_thumbnail("documents/".$row['user_image']."", $user_thumb_width)."' alt='' width='".$user_thumb_width."'></td></tr>
							</tbody>
						</table>
					</td>				  	
				  	<td valign='top' class='pos_rel cm_photo mrr_cust_info'>CERTIFICATE<br>".$user_tab."</td>	
				</tr>
			";
		}		
		return $tab;
  	}
  	
  	function mrr_document_menu_title($menu_item="")
  	{
  		$test=strtolower(trim($menu_item));
  		$title="";
  		
  		//for ease, use lower case						//text used in title attribute for menu item. (Cannot use quote mark since used as attribute info.
  		if($test=="financial institution")					$title="(information regarding and correspondence from/to the financial institution)";
  		elseif($test=="irs")							$title="(information regarding and correspondence from/to the IRS)";
  		elseif($test=="training center")					$title="(Employee, compliance officer, and owner/board training programs and certificates)";
  		elseif($test=="licenses, registrations, permits")		$title="(All registrations, applications, licenses, permits)";
  		elseif($test=="legal/contracts")					$title="(contracts, articles of incorporation, money order money transfer and bill payment agent verification letters and agreements, etc.)";
  		//elseif($test=="")				$title="";
  		
  		return $title;
  	}
  	
	function create_thumbnail($source_file, $width = 0, $height = 0) {
		
		$na_image = 'images/no-profile-image.png';
		
	
		
		if(!file_exists($source_file)) return $na_image;
		
		$pinfo = pathinfo($source_file);
		
		if(!isset($pinfo['extension'])) return $na_image;
		$filename_ext = $pinfo['extension'];
		$filename_base = $pinfo['filename'];	
		
		$file_size = filesize($source_file);
		
		$cache_dir = './images/_cache';
		if(!is_dir($cache_dir)) mkdir($cache_dir);
		
		$thumbnail = $cache_dir.'/'.$filename_base.'_'.$width.'_'.$height.'_'.$file_size.'_thumb.'.$filename_ext;
		
		//echo "$source_file<p>$filename_base<p>$filename_ext<p>$thumbnail<p>";
		
		
		if(!file_exists($thumbnail)) {
			// create the new thumbnail
			$thumb = new Thumbnail($source_file);
			if($thumb->error) {
				$thumbnail = $na_image;
			} else {
				$thumb->resize($width,$height);
				$thumb->save($thumbnail);
			}
			
			
			$thumb->destruct();
		}
		
		return $thumbnail;
	}
?>