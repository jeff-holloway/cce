<? include_once('application.php') ?>
<? 
	$mrr_bg_color="FFFFFF";
	$mrr_city_name="";

	if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) 
	{
		$sql = "
			update users
			set linedate_login = now()
			where id = '".sql_friendly($_SESSION['user_id'])."'
		";
		simple_query($sql);
	}
	
	
	if(!isset($use_title)) 	$use_title = $defaultsarray['company_name'];
	
	$sql = "
		select *		
		from menu
		where toplevel = 0
			and hidden = 0
			and deleted = 0
			and sidebar=0
		order by zorder
	";
	$data_menu = simple_query($sql);	
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$use_title?></title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" href="style.css?unique=<?=time()?>" type="text/css" />
		<link rel="stylesheet" href="includes/jquery.notice.css" type="text/css" />
		<link rel="stylesheet" href="includes/jquery-autocomplete-ajax.css" type="text/css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link rel="stylesheet" href="includes/jquery-ui-1.11.4.custom/jquery-ui.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
		
		<script src="includes/jquery.notice.js" type="text/javascript"></script>
		
		<link rel="stylesheet" href="includes/tablesort_theme/style.css" type="text/css" />
		<link rel="stylesheet" href="includes/jquery.tablesorter.pager.css" type="text/css" />		
		<script src="includes/jquery.tablesorter.js" type="text/javascript"></script>
		<script src="includes/jquery.tablesorter.pager.js" type="text/javascript"></script>
		<script src="includes/jquery-autocomplete-ajax.js" type="text/javascript"></script>
		<script src="functions.js" type="text/javascript"></script>	
		
		<link href="includes/mini_upload/assets/css/style.css" rel="stylesheet" />
		<!-- JavaScript Includes -->
		<script src="includes/mini_upload/assets/js/jquery.knob.js"></script>

		<!-- jQuery File Upload Dependencies -->
		<script src="includes/mini_upload/assets/js/jquery.ui.widget.js"></script>
		<script src="includes/mini_upload/assets/js/jquery.iframe-transport.js"></script>
		<script src="includes/mini_upload/assets/js/jquery.fileupload.js"></script>
		
		<script src='/tinymce/js/tinymce/tinymce.min.js'></script>

	</head>
<body>
<? if(!isset($hide_header)) { ?>

		<div class='nav_bar'>
			<div class='company_logo'>
				<a href='./'><img src='/images/logo-capital-compliance.png' alt='Capital Compliance Experts' /></a>
			</div>
			
			<div id='header_company_name'><a href='/'><?= $defaultsarray['company_name'] ?></a></div>
			
			<?
			$restricted_menu=" and sidebar=0";
			if(isset($_SESSION['access_level'])) 
			{								
				while($row_menu = mysqli_fetch_array($data_menu)) 
				{
					$sql = "
						select *
						
						from menu
						where toplevel = $row_menu[id]
							and access_level <= '".$_SESSION['access_level']."'
							and hidden = 0
							and deleted = 0
							".$restricted_menu."
						order by zorder
					";
					$data_menu_sub = simple_query($sql);
					
					echo "<div class='menu_entry' onclick=\"window.location='$row_menu[link]'\">";
					echo "$row_menu[menu_name]";
					if(mysqli_num_rows($data_menu_sub)) {
						echo "<div class='menu_entry_sub'>";
							while($row_menu_sub = mysqli_fetch_array($data_menu_sub)) 
							{
								if($row_menu_sub['menu_name'] == '-') 
								{
									echo "<div class='menu_spacer'></div>";
								} 
								else if($row_menu_sub['master_level']) 
								{
									
									$sql = "
										select *										
										from menu
										where toplevel = '$row_menu_sub[id]'
											and deleted = 0
											and access_level <= '".$_SESSION['access_level']."'
											and hidden = 0
										order by zorder
									";
									$data_menu_sub1 = simple_query($sql);
									echo "
										<div class='menu_entry_sub_entry menu_master_level' master_level_id='$row_menu_sub[id]'>
											$row_menu_sub[menu_name] &nbsp;&nbsp;&nbsp; &gt;&gt;&gt;
										</div>
										<div style='border:1px #aaaaaa solid;background-color:#eee;position:absolute;padding-left:0;width:200px;display:none' class='menu_side_level menu_entry_sub_entry menu_sub_level_$row_menu_sub[id]'>
									";
									while($row_menu_sub1 = mysqli_fetch_array($data_menu_sub1)) 
									{
										
										$new_tabber="";
										if(trim($row_menu_sub1['menu_name'])=="Logout")	$new_tabber=" target='_blank'";
										
										echo "
											<div class='menu_side_level menu_entry_sub_entry'>
												<a href=\"$row_menu_sub1[link]\" style='color:black'".$new_tabber.">$row_menu_sub1[menu_name]</a>
											</div>
										";
									}
									echo "
										</div>
									";
									
								} 
								else 
								{
									echo "
										<div class='menu_entry_sub_entry'>
											<a href=\"$row_menu_sub[link]\" style='color:black'>$row_menu_sub[menu_name]</a>
										</div>
									";
								}
							}
						echo "</div>";
					}
					echo "</div>";	
				}				
			}			
			?>
		</div>
		<div style='clear:both'><a name='top_of_page'>&nbsp;</a></div>
		
		<div class='wrapper'>
		<div class='company_name_holder'></div>

		<div class='sidebar'>
			
			<? if(is_logged_in()) { ?>
			
				<div id='waiting_file_status'></div>			
				<div>
					<?
					$upload_section = new upload_section();
					$upload_section->section_id = SECTION_WAITING;
					$upload_section->param('callback_function', 'show_waiting_files_status()');
					$upload_section->show();
					?>
				</div>	
				<!--Form for New Users -->
                    <div id="dialog-waiting-files" title="Files Awaiting Processing" style='display:none;'>
                    	<div id='mrr_waiting_file_display'></div>
                    </div>		
                    
				<? 		
					$add_class="";
					if($page_name=="" || $page_name=="/" || $page_name=="index.php")		$add_class=" highlight_current_page";					
					
					echo "
						<a href='/'>
							<div class='sidebar_menu_entry_sub_entry home_page".$add_class."'>
								Customer Profile
							</div>
						</a>
					";
					
					if($page_name=="" || $page_name=="/" || $page_name=="index.php")	
					{
						echo "
							<div class='sidebar_menu_entry_sub_entry audit_folder' onClick='auditor_folder();'>
								Auditor Folder
							</div>
						";
					}
					
					$user_idst =strtolower($_SESSION['username']);
					$user_idst =strtolower('Employee.test1');
					//$user_idst =strtolower('Employee.test2');
					$time =time();//time php
					$key_sso = $defaultsarray['docebo_api_key_custom'];
 					$token =md5($user_idst.','.$time.','.$key_sso);
 					$url ='http://capitalcomplianceexperts.docebosaas.com/lms/index.php?r=site/sso&login_user='.$user_idst.'&time='.$time.'&token='.$token;
					
					echo "
						<a href='".$url."' target='_blank'>
							<div class='sidebar_menu_entry_sub_entry'>
								DOCEBO Account
							</div>
						</a>
					";
					
					//echo "<hr>";
					
					$sql = "
						select *
						
						from menu
						where access_level <= '".$_SESSION['access_level']."'
							and hidden = 0
							and deleted = 0
							 and sidebar > 0
						order by zorder
					";
					$data_menu_sub = simple_query($sql);
					
					if(mysqli_num_rows($data_menu_sub) > 0) 
					{				
						while($row_menu_sub = mysqli_fetch_array($data_menu_sub)) 
						{				
							$add_class="";	
							if($page_name==$row_menu_sub['link'])		$add_class=" highlight_current_page";	
							
							
							echo "
								<a href=\"$row_menu_sub[link]\">
								<div class='sidebar_menu_entry_sub_entry".$add_class."'>
									".$row_menu_sub['menu_name']."
								</div>
								</a>
							";
						}
					}		
					
					echo "<hr>";
					
					$temp_id=0;
                    	$mrr_adder=" and template_id=1";		//default to master template
                    	
                    	//find merchant template first...acts as a default.
                    	if($_SESSION['merchant_id'] > 0)
                    	{
                    		$temp_id=mrr_get_merchant_template_id($_SESSION['merchant_id']);     		
                    		if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
                    	}
                    	//find store template next...override merchant if set.
                    	if($_SESSION['store_id'] > 0)
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
                    		if($page_name=="documents.php" && $query_string=="id=".$row['id']."")		$add_class=" highlight_current_page";	
                    		
                    		echo "                    			
                    			<a href='documents.php?id=".$row['id']."'>
                    				<div class='sidebar_menu_entry_sub_entry".$add_class."'>".$namer."</div>
                    			</a>
                    		";	
                    	}   
				?>								
			<? } ?>		
		</div>	
		<div id='main_content'>
<? } ?>