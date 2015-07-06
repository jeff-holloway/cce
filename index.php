<? $use_title="Home"; ?>
<?
$_SESSION['selected_doc_type_id']=0;
?>
<? include('header.php') ?>
<?
	if(!isset($_POST['search_cust']))	$_POST['search_cust']="";
	          
     $user_access=0;
     $view_user_access=0;
     if(isset($_SESSION['access_level']))		$user_access=$_SESSION['access_level'];
     if(isset($_SESSION['view_access_level']))	$view_user_access=$_SESSION['view_access_level'];
     
     if(isset($_GET['id']))		$_POST['id']=$_GET['id'];
     if(isset($_POST['id']))		$_GET['id']=$_POST['id'];
     
     if(!isset($_GET['id']))		$_GET['id']=0;
     if(!isset($_POST['id']))		$_POST['id']=0;
     
     $message="";
     $sql = "
     	select *
     	
     	from users
     	where id = '".sql_friendly($_SESSION['user_id'])."'
     ";
     $data_columns = simple_query($sql);
?>
<?
	//echo "<br>U".$_SESSION['selected_user_id']."M".$_SESSION['selected_merchant_id']."S".$_SESSION['selected_store_id']." ... U".$_SESSION['user_id']."M".$_SESSION['merchant_id']."S".$_SESSION['store_id']."<br>";
?>
<div class="column move_box_left">
		<div class="portlet left_col sort_tbl" id='portlet_SearchBox'>
			<div class="Table_sort_main">
     			<div class="search_box">
     				<div class="input-group">
     					<input type="text" class="form-control" name='search_cust' id='search_cust' value="<?=$_POST['search_cust']?>" placeholder="Search...">
     					<span class="input-group-btn">
     						<button class="btn btn-default" type="button" name='search_custs' id='search_custs'><i class="fa fa-search"></i></button>											
     					</span>
     				</div>
     				
     				<? if($_SESSION['access_level'] >=90) { ?>
     					<div style='border:0px black solid;float:right'>
     						
     						<button type="submit" id='create-merch' name='create-merch' class="btn btn-default add_new_btn"  style='margin-left:20px;' onClick='debread_crumb_trail(0); edit_merchant(0,1);'>ADD CUSTOMER</button>
     						
     						
     						<button type="submit" id='create-store' name='create-store' class="btn btn-default add_new_btn" style='margin-left:20px;' onClick='debread_crumb_trail(2); edit_store_location(0,1);'>ADD STORE</button>
     					
     					</div>     													
     				<? } ?>					
     				
     				<!--<a href="#">advanced search <i class="fa fa-angle-double-right"></i></a>-->
     			</div>
     			<div class="clearfix"></div>
     			<div id='merchant_customers'></div>  
     			
     			<div id="pager" class="pager">
     			<form style='visibility: hidden;'>
     				<img src="common/images/first.png" class="first" alt="">
     				<img src="common/images/prev.png" class="prev" alt="">
     				<input type="text" class="pagedisplay">
     				<img src="common/images/next.png" class="next" alt="">
     				<img src="common/images/last.png" class="last" alt="">
     				<select class="pagesize">
     					<option selected="selected" value="1">1</option>
     					<option value="2">2</option>
     					<option value="3">3</option>
     					<option value="4">4</option>
     				</select>
     			</form>			
     			</div>
			</div>
		</div>
		<div class="portlet left_col cust_info default_closed" id='portlet_CustInfo'>
			<div class="portlet-header">CUSTOMER INFORMATION</div>
			<div class="portlet-content">
					<table class="table table-striped" id='mrr_merchant_display'>
						  <tbody>
							<tr>
							  <td>TEMPLATE<br><span id='cust_template'></span></td>
							  <td>COMPLIANCE PROGRAM TITLE<br><span id='cust_title'></span></td>
							</tr>
							<tr>
							  <td>CID #<br><span id='cust_cid'></span></td>
							  <td>COMPLIANCE PROGRAM SUBTITLE<br><span id='cust_subtitle'></span></td>
							</tr>
							<tr>
							  <td colspan="2">LEGAL NAME<br><span id='cust_name'></span></td>
							</tr>
							<tr class="corp_add">
							  <td>CORPORATE ADDRESS<br><span id='cust_addr1'></span></td>
							  <td>CORPORATE PHONE<br><span id='cust_phone'></span></td>
							</tr>
							<tr class="corp_add">
							  <td>CORPORATE CITY<br><span id='cust_city'></span></td>
							  <td>CORPORATE FAX<br><span id='cust_fax'></span></td>
							</tr>
							<tr class="corp_add">
							  <td>								
								<table class="table table-striped internal_table">
									<tbody>
										<tr><td>CORPORATE STATE<br><span id='cust_state'></span></td></tr>
										<tr><td>CORPORATE ZIP<br><span id='cust_zip'></span></td></tr>
									</tbody>
								</table>
								</td>
							  <td class="pos_rel">CORPORATE LOGO<br><img id='cust_logo' src="images/no-profile-image.png" alt="" width='<?=$user_thumb_width ?>'></td>	
							  <!-- <a id='cust_edit_logo' href="#"><small><i class="fa fa-pencil"></i></small> </a>-->  
							  		
							</tr>
							<tr>
								<td>
									<table class="table table-striped internal_table">
										<tbody>
											<tr><td>COMPLIANCE OFFICER<br><span id='cust_co'>Kyle Oden</span></td></tr>
											<tr><td>CO EMAIL ADDRESS<br><span id='cust_email'>kyle@chavezsuper.com</span></td></tr>
											<tr><td>CO PASSWORD<br><span id='cust_pass'>************</span></td></tr>
										</tbody>
									</table>
								</td>
								<td class="pos_rel co_photo">CO PHOTO<br><img id='cust_co_image' src="images/no-profile-image.png" alt="" width='<?=$user_thumb_width ?>'></td>
								<!-- <a id='cust_edit_photo' href="#"> <small><i class="fa fa-pencil"></i></small></a>--> 
								
							</tr>
							<tr>
							  	<td colspan='2'>							  		
							  		<?
							  		if($_SESSION['access_level'] >=90)
							  		{
							  			echo "<div class='right_floater'>";							  			
							  			
							  			//echo "<input type='button' name='create-new-merchant' id='create-new-merchant' value='Add' onClick='debread_crumb_trail(0); edit_merchant(0,1);' class='btn btn-default add_new_btn'>";	
							  			
							  			echo "<input type='button' name='archived-in-merchant' id='archived-in-merchant' value='View Archived' onClick='view_merchant_archived();' class='btn btn-default add_new_btn'>";	
							  			
							  			echo "</div>";	
							  		}
							  		?>	
							  		<div id='merchant_edit_button'></div>
							  	</td>
							</tr>
						  </tbody>
					</table>
										
					<?         
                   		//get_merchant_select_box($field,$pre=0,$cd=0,$prompt="",$classy="")
                   		$selbox0=get_merchant_select_box('ms_parent_id',0,0,"","");
                   		//$selbox0="<input type='hidden' name='ms_parent_id' id='ms_parent_id' value='0'> 0";
                   		
                   		
                         //get_template_select_box($field,$pre=0,$store=0, $merchant=0,$cd=0,$prompt="",$classy="")
                         $selbox1=get_template_select_box('ms_template_id',0,0,0,0,"","");
                         
                         //get_state_select_box($field,$pre="",$cd=0,$prompt="",$classy="")
                         $selbox2=get_state_select_box('ms_state',"",0,"","");
                         
                         //get_user_select_box($field,$pre=0,$cd=0,$prompt="",$classy="")
                         $selbox3=get_user_select_box('ms_co_user_id',0,0,"Select Compliance Officer","");
                         $selbox4=get_user_select_box('ms_grp_user_id',0,0,"Select Group Manager","");
                         ?>
                         <!--Form for New Customers -->
                         <div id='dialog_merchant_form' title='Add/Edit Customer' style='display:none;'>
                         	<p class='validateTips'>*** Customer Legal Name field is required. If left blank, your info will be used as contact info.</p>
                         	<div class='field2'>
                         		<label for='date_date'>Template</label>
                         		<span>
                         			<?=$selbox1 ?>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_date'>Legal Name</label>
                         		<span>
                         			<input type='text' name='ms_merchant' id='ms_merchant' value='Customer Name' onBlur='quick_fill_program_info()'>
                         			<input type='hidden' name='ms_id' id='ms_id' value='0'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_date'>Group</label>
                         		<span>
                         			<?=$selbox0 ?>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>Program Title</label>
                         		<span>
                         			<input type='text' name='ms_program_title' id='ms_program_title' value="" class='tooltipx' title='Customer or Customer Program Title' placeholder='Company Program Title'>
                         		</span>
                         	</div>  
                         	<div class='field2'>
                         		<label for='date_title'>Program Subtitle</label>
                         		<span>
                         			<input type='text' name='ms_program_subtitle' id='ms_program_subtitle' value="" class='tooltipx' title='Customer or Customer Program Subtitle' placeholder='Company Program Subtitle'>
                         		</span>
                         	</div>         	
                         	          	
                         	<div class='field2'>
                         		<label for='date_title'>Address</label>
                         		<span>
                         			<input type='text' name='ms_address1' id='ms_address1' value="" class='tooltipx' title='Customer or Customer Address' placeholder='Address'>
                         		</span>
                         	</div>          	
                         	<div class='field2'>
                         		<label for='date_title'>Address 2</label>
                         		<span>
                         			<input type='text' name='ms_address2' id='ms_address2' value="" class='tooltipx' title='Customer or Customer Address line 2 (optional)' placeholder='Address (line 2)'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>City</label>
                         		<span>
                         			<input type='text' name='ms_city' id='ms_city' value="" class='tooltipx' title='Customer or Customer City' placeholder='City'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>State</label>
                         		<span>
                         			<?=$selbox2 ?>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>Zip Code</label>
                         		<span>
                         			<input type='text' name='ms_zip' id='ms_zip' value="" class='tooltipx' title='Customer or Customer Zip Code (XXXXX or XXXXX-XXXX format)' placeholder='Zip'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>Corporate Phone</label>
                         		<span>
                         			<input type='text' name='ms_contact_phone3' id='ms_contact_phone3' value="" class='tooltipx' title='Corporate Phone Number' placeholder='Phone'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>Corporate Fax#</label>
                         		<span>
                         			<input type='text' name='ms_contact_phone4' id='ms_contact_phone4' value="" class='tooltipx' title='Corporate Fax Number' placeholder='Fax'>
                         		</span>
                         	</div>
                         	
                         	<div id='logo_image_holder_holder'></div>	
                         	
                         	<? if($_SESSION['access_level'] >=70) { ?>
                              	<div class='field2'>
                              		<label for='date_title'>Compliance Officer</label>
                              		<span id='ms_co_user_id_box'>
                              			<?=$selbox3 ?>
                              		</span>
                              	</div>
                              	<div class='field2'>
                         			<label>&nbsp;</label>
                         			<span>
                         				<input type='button' name='create-user-co' id='create-user-co' value='Create CO User' class='btn btn-default add_new_btn'>
                         			</span>
                         		</div>
                         		
                              	<div class='field2'>
                              		<label for='date_title'>Group Manager {Optional}</label>
                              		<span id='ms_grp_user_id_box'>
                              			<?=$selbox4 ?>
                              		</span>
                              	</div>
                              	<div class='field2'>
                         			<label>&nbsp;</label>
                         			<span>
                         				<input type='button' name='create-user-gm' id='create-user-gm' value='Create GM User' class='btn btn-default add_new_btn'>
                         			</span>
                         		</div>
                         	<? } else { ?>
                         		<input type='hidden' name='ms_co_user_id' id='ms_co_user_id' value="0">
                         		<input type='hidden' name='ms_grp_user_id' id='ms_grp_user_id' value="0">
                         	<? } ?>
                         	          	
                         	<div class='field2'>
                         		<label for='date_title'>FI Name</label>
                         		<span>
                         			<input type='text' name='ms_contact_title' id='ms_contact_title' value="" class='tooltipx' title='Financial Institution Name' placeholder='Financial Institution Name'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>FI Address</label>
                         		<span>
                         			<input type='text' name='ms_contact_first_name' id='ms_contact_first_name' value="" class='tooltipx' title='FI Mailing Address' placeholder='FI Address'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>FI Manager</label>
                         		<span>
                         			<input type='text' name='ms_contact_last_name' id='ms_contact_last_name' value="" class='tooltipx' title='FI Relationship Manager'  placeholder='FI Relationship Manager'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>FI E-Mail Address</label>
                         		<span>
                         			<input type='text' name='ms_contact_email' id='ms_contact_email' value="" class='tooltipx' title='FI Relationship Manager E-Mail Address' placeholder='FI E-Mail Address'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>FI Cell Phone #</label>
                         		<span>
                         			<input type='text' name='ms_contact_phone1' id='ms_contact_phone1' value="" class='tooltipx' title='FI Relationship Manager Cell Phone Number' placeholder='FI Cell Number'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>FI Phone #</label>
                         		<span>
                         			<input type='text' name='ms_contact_phone2' id='ms_contact_phone2' value="" class='tooltipx' title='FI Relationship Manager Phone Number' placeholder='FI Phone'>
                         		</span>
                         	</div>         	
                         	                         	
                         	<div class='field2'>
                         		<label for='date_title'>MSB Auditor/Examiner</label>
                         		<span>
                         			<input type='text' name='ms_msb_name' id='ms_msb_name' value="" class='tooltipx' title='MSB Auditor Examiner Name' placeholder='MSB Auditor Examiner Name'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>MSB Reference #</label>
                         		<span>
                         			<input type='text' name='ms_msb_ref' id='ms_msb_ref' value="" class='tooltipx' title='MSB Auditor Examiner Reference Number' placeholder='MSB Reference Number'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>MSB Cell #</label>
                         		<span>
                         			<input type='text' name='ms_msb_cell' id='ms_msb_cell' value="" class='tooltipx' title='MSB Auditor Examiner Cell Number' placeholder='MSB Cell Number'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>MSB Phone #</label>
                         		<span>
                         			<input type='text' name='ms_msb_phone' id='ms_msb_phone' value="" class='tooltipx' title='MSB Auditor Examiner Phone Number' placeholder='MSB Phone Number'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>MSB E-Mail Address</label>
                         		<span>
                         			<input type='text' name='ms_msb_email' id='ms_msb_email' value="" class='tooltipx' title='MSB Auditor Examiner E-Mail Address' placeholder='MSB E-Mail Address'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>MSB Address</label>
                         		<span>
                         			<input type='text' name='ms_msb_addr' id='ms_msb_addr' value="" class='tooltipx' title='MSB Auditor Examiner Mailing Address' placeholder='MSB Mailing Address'>
                         		</span>
                         	</div> 
                         	
                         	<div class='field2'>
                         		<label for='date_title'>IRS Address</label>
                         		<span>
                         			<input type='text' name='ms_irs_addr' id='ms_irs_addr' value="" class='tooltipx' title='IRS Mailing Address' placeholder='IRS Mailing Address'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>Case Control Number</label>
                         		<span>
                         			<input type='text' name='ms_irs_case' id='ms_irs_case' value="" class='tooltipx' title='IRS Case Control Number' placeholder='IRS Case Control Number'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>IRS Agent Name</label>
                         		<span>
                         			<input type='text' name='ms_irs_agent' id='ms_irs_agent' value="" class='tooltipx' title='IRS Agent Name' placeholder='IRS Agent'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>IRS Employee ID</label>
                         		<span>
                         			<input type='text' name='ms_irs_employ_id' id='ms_irs_employ_id' value="" class='tooltipx' title='IRS Agent Employee ID' placeholder='IRS Employee ID'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>IRS E-Mail Address</label>
                         		<span>
                         			<input type='text' name='ms_irs_email' id='ms_irs_email' value="" class='tooltipx' title='IRS E-Mail Address' placeholder='IRS E-Mail Address'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>IRS Phone #</label>
                         		<span>
                         			<input type='text' name='ms_irs_phone' id='ms_irs_phone' value="" class='tooltipx' title='IRS Office Phone Number' placeholder='IRS Phone Number'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>IRS Mobile #</label>
                         		<span>
                         			<input type='text' name='ms_irs_cell' id='ms_irs_cell' value="" class='tooltipx' title='IRS Mobile/Cell Phone' placeholder='IRS Mobile Number'>
                         		</span>
                         	</div> 
                         	 
                         </div>
					
			</div>
		</div>
		<div class="portlet left_col store_location default_closed" id='portlet_StoreLocation'>
			<div class="portlet-header">STORE LOCATION INFORMATION</div>
			<div class="portlet-content">
					<table class="table table-striped" id='mrr_store_display'>
						  <tbody>
							<tr>
							  <td class="pos_rel store_photo">STORE IMAGE<br><img id='store_loc_logo' src="images/no-profile-image.png" alt="" width='<?=$user_thumb_width ?>'></td> 
							  <!--<a id='store_loc_logo_edit' href="#"><small><i class="fa fa-pencil"></i></small>  </a>-->
							  <td>
								<table class="table table-striped internal_table">
									<tbody>
										<tr><td>UID #<br><span id='store_loc_number'></span></td></tr>
										<tr><td>DBA NAME<br><span id='store_loc_name'></span></td></tr>
									</tbody>
								</table>
							  </td>
							</tr>
							<tr>
							  <td>ADDRESS<br><span id='store_loc_addr1'></span><span id='store_loc_addr2'></span></td>
							  <td>PHONE<br><span id='store_loc_phone'></span></td>
							</tr>
							<tr>
							  <td>CITY<br><span id='store_loc_city'></span></td>
							  <td>ZIP<br><span id='store_loc_zip'></span></td>
							</tr>
							<tr>
							  <td>STATE<br><span id='store_loc_state'></span></td>
							  <td>FAX<br><span id='store_loc_fax'></span></td>
							</tr>
							<tr class="corp_add">
							  <td>
								<table class="table table-striped internal_table">
									<tbody>
										<tr><td>COMPLIANCE MANAGER<br><span id='store_loc_cm'></span></td></tr>
										<tr><td>EMAIL ADDRESS<br><span id='store_loc_email'></span></td></tr>
										<tr><td>CM PASSWORD<br><span id='store_loc_pass'></span></td></tr>
									</tbody>
								</table>
							  </td>
							  <td class="pos_rel cm_photo">CM PHOTO<br><img id='store_loc_image' src="images/no-profile-image.png" alt="" width='<?=$user_thumb_width ?>'></td>
							  <!--<a id='store_loc_image_edit' href="#"><small><i class="fa fa-pencil"></i></small> </a> -->
							</tr>
							
							<tr>
							  	<td colspan='2'>	
							  		<div id='store_loc_edit_button'></div>							  		
							  		<?
							  		if($_SESSION['access_level'] >=60)
							  		{	// && ($_SESSION['merchant_id'] > 0 || ($_SESSION['merchant_id']==0 && $_SESSION['selected_merchant_id'] > 0) ) 
							  			echo "<div class='right_floater'><input type='button' name='create-new-store' id='create-new-store' value='Add' onClick='debread_crumb_trail(2); edit_store_location(0,1);' class='btn btn-default add_new_btn'></div>";	
							  		}
							  		?>	
							  							  		
							  	</td>
							</tr>
							<!--
							<tr class="corp_add">
							  <td colspan="2">CM ACCESS</td>
							</tr>
							<tr class="chbox">
								 <td>
									<input id="cb1" type="checkbox">
									<label for="cb1">Compliance Officer</label>
								</td>
								<td>
									<input id="cb2" type="checkbox">
									<label for="cb2">Monitoring Logs</label>
								</td>
							</tr>
							<tr class="chbox">
								 <td>
									<input id="cb3" type="checkbox">
									<label for="cb3">Compliance Manager</label>
								</td>
								<td>
									<input id="cb4" type="checkbox">
									<label for="cb4">Licenses, Registrations, Contracts</label>
								</td>
							</tr>
							<tr class="chbox">
								 <td>
									<input id="cb5" type="checkbox">
									<label for="cb5">Financial Institution</label>
								</td>
								<td>
									<input id="cb6" type="checkbox">
									<label for="cb6">Legal/ Contracts</label>
								</td>
							</tr>
							<tr class="chbox">
								 <td>
									<input id="cb7" type="checkbox">
									<label for="cb7">IRS</label>
								</td>
								<td>
									<input id="cb8" type="checkbox">
									<label for="cb8">CTR/ SARs</label>
								</td>
							</tr>
							<tr class="chbox">
								 <td>
									<input id="cb9" type="checkbox">
									<label for="cb9">AML Compliance Program</label>
								</td>
								<td>
									<input id="cb10" type="checkbox">
									<label for="cb10">Other Documents</label>
								</td>
							</tr>
							<tr class="chbox">
								 <td>
									<input id="cb11" type="checkbox">
									<label for="cb11">Independent Review</label>
								</td>
								<td>
									<input id="cb12" type="checkbox">
									<label for="cb12">Auditor Folder</label>
								</td>
							</tr>
							-->
						  </tbody>
					</table>				
					
					<?         
                         //get_template_select_box($field,$pre=0,$store=0, $merchant=0,$cd=0,$prompt="",$classy="")
                         $selbox1=get_template_select_box('mst_template_id',0,0,0,0,"Use Customer Template","");
                         
                         //get_state_select_box($field,$pre="",$cd=0,$prompt="",$classy="")
                         $selbox2=get_state_select_box('mst_state',"",0,"","");
                         
                         //get_user_select_box($field,$pre=0,$cd=0,$prompt="",$classy="")
                         $selbox3=get_user_select_box('mst_cm_user_id',0,0,"Select Compliance Manager","");
                         ?>
                         <!--Form for New Store Locations -->
                         <div id='dialog_store_form' title='Add/Edit Store Location' style='display:none;'>
                         	<p class='validateTips'>
                         		*** DBA Name and Store Number fields are required.
                         		<br>
                         	</p>
                         	<div class='field2'>
                         		<label for='date_date'>UID#</label>
                         		<span>
                         			<input type='text' name='mst_store_number' id='mst_store_number' value='' class='tooltipx' title='UID or Store Number' placeholder='UID'>
                         			<input type='hidden' name='mst_merchant_id' id='mst_merchant_id' value='0'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label>&nbsp;</label>
                         		<span>
                         			<input type='button' name='cust_info_copier' id='cust_info_copier' value='Copy Customer Information' onClick='copy_store_location_from_custom();' class='btn btn-default add_new_btn'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_date'>DBA Name</label>
                         		<span>
                         			<input type='text' name='mst_store_name' id='mst_store_name' value='' class='tooltipx' title='DBA or Store Name' placeholder='DBA Name'>
                         			<input type='hidden' name='mst_id' id='mst_id' value='0'>
                         		</span>
                         	</div>
                         	                      	
                         	<div class='field2'>
                         		<label for='date_title'>Address</label>
                         		<span>
                         			<input type='text' name='mst_address1' id='mst_address1' value="" class='tooltipx' title='Address of Store Location' placeholder='Store Address'>
                         		</span>
                         	</div>          	
                         	<div class='field2'>
                         		<label for='date_title'>Address 2</label>
                         		<span>
                         			<input type='text' name='mst_address2' id='mst_address2' value="" class='tooltipx' title='(Optional) More address info for Store Location' placeholder='Store Address (line 2)'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>City</label>
                         		<span>
                         			<input type='text' name='mst_city' id='mst_city' value="" class='tooltipx' title='Store Location City' placeholder='Store City'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>State</label>
                         		<span>
                         			<?=$selbox2 ?>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>Zip Code</label>
                         		<span>
                         			<input type='text' name='mst_zip' id='mst_zip' value="" class='tooltipx' title='Enter Store Location zip code (XXXXX or XXXXX-XXXX format)' placeholder='Store Zip'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>Store Phone #</label>
                         		<span>
                         			<input type='text' name='mst_contact_phone3' id='mst_contact_phone3' value="" class='tooltipx' title='Store Phone Number' placeholder='Store Phone'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>Store Fax #</label>
                         		<span>
                         			<input type='text' name='mst_contact_phone4' id='mst_contact_phone4' value="" class='tooltipx' title='Store Fax Number' placeholder='Store Fax'>
                         		</span>
                         	</div>
                         	
                         	<? if($_SESSION['access_level'] >=50) { ?>
                              	<div class='field2'>
                              		<label for='date_title'>Compliance Manager</label>
                              		<span id='mst_cm_user_id_box'>
                              			<?=$selbox3 ?>
                              		</span>                              		
                              	</div>
                              	<div class='field2'>
                         			<label>&nbsp;</label>
                         			<span>
                         				<input type='button' name='create-user-cm' id='create-user-cm' value='Create CM User' class='btn btn-default add_new_btn'>
                         			</span>
                         		</div>
                         	<? } else { ?>
                         		<input type='hidden' name='mst_cm_user_id' id='mst_cm_user_id' value="0">
                         	<? } ?>
                         	                         	
                         	<div id='store_image_holder_holder'></div>	
                         	
                         	<div class='field2'>
                         		<label for='date_title'>FI Name</label>
                         		<span>
                         			<input type='text' name='mst_contact_title' id='mst_contact_title' value="" class='tooltipx' title='Financial Institution Name' placeholder='Financial Institution Name'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>FI Address</label>
                         		<span>
                         			<input type='text' name='mst_contact_first_name' id='mst_contact_first_name' value="" class='tooltipx' title='FI Mailing Address' placeholder='FI Address'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>FI Manager</label>
                         		<span>
                         			<input type='text' name='mst_contact_last_name' id='mst_contact_last_name' value="" class='tooltipx' title='FI Relationship Manager' placeholder='Relationship Manager'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>FI E-Mail Address</label>
                         		<span>
                         			<input type='text' name='mst_contact_email' id='mst_contact_email' value="" class='tooltipx' title='E-Mail Address for Relationship Manager' placeholder='FI E-Mail Address'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>FI Cell Phone #</label>
                         		<span>
                         			<input type='text' name='mst_contact_phone1' id='mst_contact_phone1' value="" class='tooltipx' title='Cell Phone Number for Relationship Manager' placeholder='FI Cell'>
                         		</span>
                         	</div>
                         	<div class='field2'>
                         		<label for='date_title'>FI Phone #</label>
                         		<span>
                         			<input type='text' name='mst_contact_phone2' id='mst_contact_phone2' value="" class='tooltipx' title='Alternate Phone for Relationship Manager' placeholder='FI Phone'>
                         		</span>
                         	</div>
                         	
                         	
                         	<div class='field2'>
                         		<label for='date_title'>MSB Auditor/Examiner</label>
                         		<span>
                         			<input type='text' name='mst_msb_name' id='mst_msb_name' value="" class='tooltipx' title='MSB Auditor Examiner Name' placeholder='MSB Auditor Examiner Name'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>MSB Reference #</label>
                         		<span>
                         			<input type='text' name='mst_msb_ref' id='mst_msb_ref' value="" class='tooltipx' title='MSB Auditor Examiner Reference Number' placeholder='MSB Reference Number'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>MSB Cell #</label>
                         		<span>
                         			<input type='text' name='mst_msb_cell' id='mst_msb_cell' value="" class='tooltipx' title='MSB Auditor Examiner Cell Number' placeholder='MSB Cell Number'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>MSB Phone #</label>
                         		<span>
                         			<input type='text' name='mst_msb_phone' id='mst_msb_phone' value="" class='tooltipx' title='MSB Auditor Examiner Phone Number' placeholder='MSB Phone Number'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>MSB E-Mail Address</label>
                         		<span>
                         			<input type='text' name='mst_msb_email' id='mst_msb_email' value="" class='tooltipx' title='MSB Auditor Examiner E-Mail Address' placeholder='MSB E-Mail Address'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>MSB Address</label>
                         		<span>
                         			<input type='text' name='mst_msb_addr' id='mst_msb_addr' value="" class='tooltipx' title='MSB Auditor Examiner Mailing Address' placeholder='MSB Mailing Address'>
                         		</span>
                         	</div> 
                         	
                         	<div class='field2'>
                         		<label for='date_title'>IRS Address</label>
                         		<span>
                         			<input type='text' name='mst_irs_addr' id='mst_irs_addr' value="" class='tooltipx' title='IRS Mailing Address' placeholder='IRS Mailing Address'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>Case Control Number</label>
                         		<span>
                         			<input type='text' name='mst_irs_case' id='mst_irs_case' value="" class='tooltipx' title='IRS Case Control Number' placeholder='IRS Case Control Number'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>IRS Agent Name</label>
                         		<span>
                         			<input type='text' name='mst_irs_agent' id='mst_irs_agent' value="" class='tooltipx' title='IRS Agent Name' placeholder='IRS Agent'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>IRS Employee ID</label>
                         		<span>
                         			<input type='text' name='mst_irs_employ_id' id='mst_irs_employ_id' value="" class='tooltipx' title='IRS Agent Employee ID' placeholder='IRS Employee ID'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>IRS E-Mail Address</label>
                         		<span>
                         			<input type='text' name='mst_irs_email' id='mst_irs_email' value="" class='tooltipx' title='IRS E-Mail Address' placeholder='IRS E-Mail Address'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>IRS Phone #</label>
                         		<span>
                         			<input type='text' name='mst_irs_phone' id='mst_irs_phone' value="" class='tooltipx' title='IRS Office Phone Number' placeholder='IRS Phone Number'>
                         		</span>
                         	</div> 
                         	<div class='field2'>
                         		<label for='date_title'>IRS Mobile #</label>
                         		<span>
                         			<input type='text' name='mst_irs_cell' id='mst_irs_cell' value="" class='tooltipx' title='IRS Mobile/Cell Phone' placeholder='IRS Mobile Number'>
                         		</span>
                         	</div> 
                         	      	
                         	<div class='field2 mrr_hidden'>
                         		<label for='date_date'>Template</label>
                         		<span>
                         			<?=$selbox1 ?>
                         		</span>
                         	</div>          		 
                         </div>
					
			</div>
		</div>
		<div class="portlet left_col edit_user" id='portlet_EditUser'>
			
			<div class="search_box">
				<div class="input-group">
					
					<input type="text" class="form-control" name='search_universal' id='search_universal' value="" placeholder="Search...">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" name='search_button' id='search_button'><i class="fa fa-search"></i></button>
					</span>
										
				</div>
				<!--<a href="#">advanced search <i class="fa fa-angle-double-right"></i></a>-->
				<? if($_SESSION['access_level'] >=40 && $_SESSION['access_level'] !=45) { ?>
     				<div style='float:right;'>
     					<button type="submit" id='create-user' name='create-user' class="btn btn-default add_new_btn">ADD NEW USER</button>
     				</div>
     			<? } ?>
			</div>

			<div class="clearfix"></div>
			
			<div class="portlet-header default_closed" style='margin-top:-5px;'>Manage Users</div>
			<div class="portlet-content">
					
					<table class="table table-striped" id='mrr_user_display'>
					  <tbody>
						<tr>
						  <td style='width:25%;'>FIRST NAME<br><span id='cce_user_first'></span></td>
						  <td style='width:25%;'>LAST NAME<br><span id='cce_user_last'></span></td>
						  <td style='width:50%;'>TITLE<br><span id='cce_user_title'></span></td>
						</tr>
						<tr>						  
						  <td style='width:25%;'>USERNAME<br><span id='cce_user_name'></span></td>
						  <td style='width:25%;'>PASSWORD<br><span id='cce_user_pass'></span></td>
						  <td style='width:50%;'>STORE LOCATION<br><span id='cce_user_locations'></span></td>
						</tr>
						<tr>
						  <td style='width:25%;'>CELL#<br><span id='cce_user_cell'></span></td>
						  <td style='width:25%;'>PHONE#<br><span id='cce_user_phone'></span></td>
						  <td style='width:50%;'>USER LEVEL<br><span id='cce_user_level'></span></td>
						</tr>
						<tr>
						  <td colspan="3">MONITORING LOGS?<br><span id='cce_user_logs'></span></td>
						</tr>
					  </tbody>
					</table>
					<div id='user_list_display'></div>
			</div>
		</div>
	</div>
	<div class="column move_box_right">
		<div class="portlet welcome_msg right_col" id='portlet_WelcomeMsg'>
			<div class="portlet-content">
				<div id='cce_system_message_display'>Loading...</div>
			</div>
		</div>	
		<div class="portlet right_col reports default_closed" id='portlet_Reports'>
			<div class="portlet-header">Reports</div>
			<div class="portlet-content">				
				<div id='cce_reports_display'><?= show_selected_reports_by_access( SECTION_REPORT ) ?></div>				
			</div>
		</div>
		<div class="portlet right_col imp_dates default_closed" id='portlet_ImportantDates'>
			<div class="portlet-header">IMPORTANT DATES</div>
			<div class="portlet-content">				
				<div id='important_dates'></div>	
				
				<!--Form for New Important dates -->
                    <div id='dialog_date_form' title='Add/ Edit Important Date' style='display:none;'>
                    	<p class='validateTips'>*** Important Date, Title, and Description fields are required.</p>
                    	<div class='field2'>
                    		<label for='date_date'>Important Date ***</label>
                    		<span>
                    			<input type='text' name='date_date' id='date_date' value='' class='datepicker'>
                    			<input type='hidden' name='date_id' id='date_id' value='0'>
                    		</span>
                    	</div>
                    	<div class='field2'>
                    		<label for='date_date'>Date Type</label>
                    		<span>
                    			<?
                    			//mrr_build_option_box($option_cat_name, $selected_value = "", $field_name, $show_name = false, $show_blank_text = true, $class="") 
                    			echo mrr_build_option_box('important_date_types', 0 , 'date_type',false,true,""); 
                    			?>
                    		</span>
                    	</div>
                    	<div class='field2'>
                    		<label for='date_title'>Title ***</label>
                    		<span>
                    			<input type='text' name='date_title' id='date_title' value="" class='longshort' title='name of important date or event' placeholder='Important Date Title'>
                    		</span>
                    	</div>
                    	<div class='field2'>
                    		<label for='date_desc'>Description ***</label>
                    		<span>
                    			<textarea name='date_desc' id='date_desc' class='mceEditor'></textarea>
                    		</span>
                    	</div>
                    	<div class='field2'>
                    		<label for='date_date_remind1'>Reminder Date</label>
                    		<span>
                    			<input type='text' name='date_date_remind1' id='date_date_remind1' value='' class='datepicker'>
                    		</span>
                    	</div>
                    	<div class='field2'>
                    		<label for='date_email_remind1'>Reminder E-mail</label>
                    		<span>
                    			<input type='text' name='date_email_remind1' id='date_email_remind1' value=""  class='longshort' title='E-mail address for first reminder' placeholder='Reminder E-Mail Address'>
                    		</span>
                    	</div>
                    	<div class='field2'>
                    		<label for='date_msg_remind1'>Reminder Message</label>
                    		<span>
                    			<textarea name='date_msg_remind1' id='date_msg_remind1' class='mceEditor'></textarea>
                    		</span>
                    	</div>
                    	               	
                    	<div class='field2'>
                    		<label for='date_date_remind2'>Reminder 2 Date</label>
                    		<span>
                    			<input type='text' name='date_date_remind2' id='date_date_remind2' value='' class='datepicker'>
                    		</span>
                    	</div>
                    	<div class='field2'>
                    		<label for='date_email_remind2'>Reminder 2 E-mail</label>
                    		<span>
                    			<input type='text' name='date_email_remind2' id='date_email_remind2' value=""  class='longshort' title='E-mail address for second reminder' placeholder='Second Reminder E-Mail Address'>
                    		</span>
                    	</div>
                    	<div class='field2'>
                    		<label for='date_msg_remind2'>Reminder 2 Message</label>
                    		<span>
                    			<textarea name='date_msg_remind2' id='date_msg_remind2' class='mceEditor'></textarea>
                    			
                    			<!-- Allow form submission with keyboard without duplicating the dialog button -->
                    			<input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
                    		</span>
                    	</div>   
                    </div>
											
			</div>
		</div>
		<div class="portlet right_col acc_info default_closed" id='portlet_AccInfo'>
			<div class="portlet-header">ACCOUNT INFORMATION</div>
			<div class="portlet-content">
				<?=get_user_info_section_by_id($_SESSION['user_id']) ?>
			</div>
		</div>
	</div>
     
</div>	

<!--Form for Archived Section -->
<div id="dialog_merchant_archive_dispay" title="Archive" style='display:none; width:850px;'>
	<div id='merchant_archive_dispay'></div>
</div>

<!--Form for User Info -->
<div id="dialog-form-profile" title="User Settings" style='display:none; width:850px;'>
	<div class='mrr_user_info_display'><? mrr_user_settings_form($_POST['id'],$edit_mode) ?></div>
</div>

<!--Form for merchant/customer removal confirmation -->
<div id="dialog_delete_merchant" title="Delete Customer" style='display:none;'>
	<p class="validateTips">Are you sure you want to remove this Customer?</p>
</div>


<!--Form for store removal confirmation -->
<div id="dialog_delete_store_location" title="Delete Store" style='display:none;'>
	<p class="validateTips">Are you sure you want to remove this Store Location?</p>
</div>


<!--Form for New Users -->
<div id="dialog-form" title="Create New User" style='display:none;'>
	<p class="validateTips">All form fields are required.</p>
	<div class='field'>
		<label for="user_name">Username</label>
		<span>
			<input type="text" name="user_name" id="user_name" value="">
		</span>
	</div>
	<div class='field'>
		<label for="email">E-Mail Address</label>
		<span>
			<input type="text" name="email" id="email" value="">
		</span>
	</div>
	<div class='field'>
		<label for="password">Enter Password</label>
		<span>
			<input type="password" name="password" id="password" value="">			
		</span>
	</div>
	<div class='field'>
		<label for="password_confirmed">Confirm Password</label>
		<span>
			<input type="password" name="password_confirmed" id="password_confirmed" value="">
			<!-- Allow form submission with keyboard without duplicating the dialog button -->
			<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
		</span>
	</div>
</div>

<!--Form for updating Passwords -->
<div id="dialog-form-update" title="Update Password" style='display:none;'>
	<p class="validateTips">All form fields are required.</p>
	<div class='field'>
		<label for="user_name2">Account Username</label>
		<span>
			<?=get_welcome_by_id($_POST['id'],1) ?>
			<input type='text' name='user_name2' id='user_name2' readonly value="<?=get_welcome_by_id($_POST['id'],1) ?>" style="display:none">
		</span>
	</div>
	<div class='field'>
		<label for="password">Enter Password</label>
		<span>
			<input type="password" name="password2" id="password2" value="">
		</span>
	</div>
	<div class='field'>
		<label for="confirm_password">Confirm Password</label>
		<span>
			<input type="password" name="confirm_password" id="confirm_password" value="">
			<!-- Allow form submission with keyboard without duplicating the dialog button -->
			<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
		</span>
	</div>
</div>

<script type='text/javascript'>
	
	var loaded_user_id = 0;
	var doc_pg=0;				//used for search results display...index page should always be 0.
	var user_added_mode=0;
	
	
	$().ready(function() {			
		<? if($_SESSION['access_level']==45) { ?>
			window.location.href = "/auditor_folder.php";
		<? } else { ?>
     			
     		//load_user_search();
     		load_cust_search();
     		load_user_list();
     		
     		load_important_dates();
     		fetch_cce_messages();
          	
          	load_merchants();
          	
          	load_stores();   
          	
          	$('.display_off').hide();   
          	
          	load_dynamic_user_select('#ms_co_user_id_box','ms_co_user_id',0,0,'Select Compliance Officer','');
          	load_dynamic_user_select('#ms_grp_user_id_box','ms_grp_user_id',0,0,'Select Group Manager','');
          	load_dynamic_user_select('#mst_cm_user_id_box','mst_cm_user_id',0,0,'Select Compliance Manager','');
          	         	
          	//update_bread_crumb_trail();		
     	<? } ?>
     	
  	
     	
     });
         	
     // handle the user search when the enter key is pressed
     $('#search_universal').keypress(function(event) {
     	if(event.keyCode == 13) {
     		load_user_search();     		
     	}
     	
     });
     $('#search_doc').keypress(function(event) {
     	if(event.keyCode == 13) load_doc_search();
     });
     $('#search_cust').keypress(function(event) {
     	if(event.keyCode == 13) load_cust_search();
     });
     $('#search_store').keypress(function(event) {
     	if(event.keyCode == 13) load_store_search();
     });
     
     //search MERCHANT results
     $(function() {
          var dialog, form,              
          dialog = $( "#dialog-search-cust-results" ).dialog({
               autoOpen: false,
               width: 'auto',
               modal: true,
               buttons: {
               	"Okay":  function() {	dialog.dialog( "close" );  	}
               }
          });
          $( "#search_custs" ).button().on( "click", function() {
          	//dialog.dialog( "open" );          	
          	
          	load_cust_search();

          });
     }); 
     
     
     //search STORE results
     $(function() {
          var dialog, form,              
          dialog = $( "#dialog-search-store-results" ).dialog({
               autoOpen: false,
               width: 'auto',
               modal: true,
               buttons: {
               	"Okay":  function() {	dialog.dialog( "close" );  	}
               }
          });
          $( "#search_stores" ).button().on( "click", function() {
          	//dialog.dialog( "open" );          	
          	
          	load_store_search();

          });
     }); 
          
     //search DOCUMENT results
     $(function() {
          var dialog, form,              
          dialog = $( "#dialog-search-doc-results" ).dialog({
               autoOpen: false,
               width: 'auto',
               modal: true,
               buttons: {
               	"Okay":  function() {	dialog.dialog( "close" );  	}
               }
          });
          $( "#search_docs" ).button().on( "click", function() {
          	//dialog.dialog( "open" );          	
          	
          	load_doc_search();

          });
     }); 
     	
	//search results
     $(function() {
          var dialog, form,              
          dialog = $( "#dialog-search-results" ).dialog({
               autoOpen: false,
               width: 'auto',
               modal: true,
               buttons: {
               	"Okay":  function() {	dialog.dialog( "close" );  	}
               }
          });
          $( "#search_button" ).button().on( "click", function() {
          	//dialog.dialog( "open" );          	
          	
          	load_user_search();

          });
     }); 
         
     
     function reset_password(user_id)
     {
     	$( "#dialog_reset" ).dialog({
               modal: true,
               buttons: {
               	"Okay": function() {             	 		
              	 		
              	 		if($("#usernamer").val()!="" && $("#user_email").val()!="")
              	 		{
              	 			$.ajax({
                    			url: "ajax.php?cmd=user_password_reset",
                    			data: {
                    				'user_name':$("#usernamer").val(),
                    				'user_email':$("#user_email").val(),
                    				'user_id':$("#id").val()
                    				
                    				},
                    			type: "POST",
                    			cache:false,
                    			dataType: "xml",
                    			success: function(xml) {
                    				$.noticeAdd({text: "Success - Password has been reset.  Next login attempt will prompt the user to change it."});	
                    				load_user_list();
                    			}
                    		});
                    	}	
                    	$( this ).dialog( "close" );             	 		
               	},
               	"Cancel": function() {
               		$( this ).dialog( "close" );              		
               	}
               }
          });       	
     }   
     
     //update password for account form
     $(function() {
          var dialog, form,              
          dialog = $( "#dialog-form-update" ).dialog({
               autoOpen: false,
               width: 'auto',
               modal: true,
               buttons: {
               	"Update Password": 	function() {	res=modUser();	if(res)  {  load_user_list();	dialog.dialog( "close" ); }  	},
               	"Cancel": 		function() {	dialog.dialog( "close" );  	}
               },
               close: function() 
               {
               	$().removeClass( "ui-state-error" );
               }
          });
          $( "#update-user-pass" ).button().on( "click", function() {
          	dialog.dialog( "open" );
          });
     });     
     
     //create new account form
     $(function() {
     	var new_acct_type=0;
     	
          var dialog, form,
          
          emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
          
          dialog = $( "#dialog-form" ).dialog({
               autoOpen: false,
               width: 'auto',
               modal: true,
               buttons: {
               	"Create an account": 	function() {	res=addUser(new_acct_type);	if(res) {  load_user_list();	dialog.dialog( "close" ); }  	},
               	"Cancel": 			function() {	dialog.dialog( "close" );  	}
               },
               close: function() 
               {
               	$().removeClass( "ui-state-error" );
               }
          });
          $( "#create-user" ).button().on( "click", function() {
          	dialog.dialog( "open" );
          });
          $( "#create-user-co" ).button().on( "click", function() {
          	if(parseInt($("#ms_id").val()) ==0)
          	{          	
          		updateMerchant_auto();
          	}
          	user_added_mode=1;
          	new_acct_type=1;			//new compliance Officer (Merchant)
          	dialog.dialog( "open" );
          });
          $( "#create-user-gm" ).button().on( "click", function() {
          	if(parseInt($("#ms_id").val()) ==0)
          	{          	
          		updateMerchant_auto();
          	}
          	user_added_mode=2;
          	new_acct_type=2;			//new Group Manager (Merchant)
          	dialog.dialog( "open" );
          });
          $( "#create-user-cm" ).button().on( "click", function() {
          	if(parseInt($("#mst_id").val()) ==0)
          	{          	
          		updateStore_auto();
          	}
          	user_added_mode=3;
          	new_acct_type=3;			//new Compliance Manager (Store)
          	dialog.dialog( "open" );
          });
     });      
     
     function confirm_delete(id)
     {     	 
     	$( "#dialog_delete" ).dialog({
               modal: true,
               buttons: {
               	"Okay": function() 
               	{                        
                         $.ajax({
               			url: "ajax.php?cmd=delete_user",
               			type: "post",
               			dataType: "xml",
               			data: {
               				"id":id
               			},
               			error: function() {
               				msgbox("General error removing user. Please try again");
               			},
               			success: function(xml) 
               			{				
               				show_notice('User Removed');   
               				load_user_list();               				
               				close_all_dialogs();
               			}
               		});      
               		
               	},
               	"Cancel": function() 
               	{
               		$( this ).dialog( "close" );
               		return false;
               	}
               }
          });  
          return false;            
     }
     
          
     function addUser(new_acct_type) 
     {
          if(!new_acct_type)		new_acct_type=0;
          
          var valid = true;
          emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
          $().removeClass( "ui-state-error" );
          valid = valid && checkLength( $('#user_name'), "username", 3, 50 );
          valid = valid && checkLength( $('#email'), "email", 6, 150 );
          valid = valid && checkLength( $('#password'), "password", 6, 150 );         
          
          if($('#password').val() !=  $('#password_confirmed').val() )
          {
          	valid=false;
          	msgbox("You must confirm the password to create the account. Please try again");
          }
          else
          {
          	valid = valid && checkLength( $('#password_confirmed'), "confirmed password", 6, 150 );	
          }
          
          //valid = valid && checkRegexp( $('#user_name'), /^[a-z]([0-9a-z_\s])+$/i, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter." );
          //valid = valid && checkRegexp( $('#email'), emailRegex, "eg. ui@jquery.com" );
          //valid = valid && checkRegexp( $('#password'), /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
          if ( valid ) 
          {
          	$.ajax({
     			url: "ajax.php?cmd=new_user",
     			data: {
     				'new_acct_type':new_acct_type,
     				'new_username':$('#user_name').val(),
     				'new_pword': $('#password').val(),
     				'new_email':$('#email').val()          				
     				},
     			type: "POST",
     			cache:false,
     			async:false,
     			dataType: "xml",
     			error: function() {
          			msgbox("General error creating user. Please try again");
          			//$('#user_name').val('');
          			//$('#password').val('');
          			//$('#password_confirmed').val('');
          			//$('#email').val('');
          		},
     			success: function(xml) {     
     				
     				if(parseInt($(xml).find('newid').text()) >  0)
     				{
     					show_notice('Account Created'); 	
          				loaded_user_id=parseInt($(xml).find('newid').text());
          				
          				$('#user_name').val('');
          				$('#password').val('');
          				$('#password_confirmed').val('');
          				$('#email').val('');
          				
          				//if(new_acct_type ==1 || new_acct_type==2)	alert('DEBUG: Type='+new_acct_type+'.');
          				
          				if(new_acct_type >  0)
          				{
          					if(new_acct_type==1)
          					{	//CO
          						user_added_mode=1;
          						load_dynamic_user_select('ms_co_user_id_box','ms_co_user_id',loaded_user_id,0,'Select Compliance Officer','');
          					}
          					if(new_acct_type==2)
          					{	//GM
          						user_added_mode=2;
          						load_dynamic_user_select('ms_grp_user_id_box','ms_grp_user_id',loaded_user_id,0,'Select Group Manager','');
          					}
          					if(new_acct_type==3)
          					{	//CM
          						user_added_mode=3;
          						load_dynamic_user_select('mst_cm_user_id_box','mst_cm_user_id',loaded_user_id,0,'Select Compliance Manager','');
          					}          					
          				} 
          				    				
          				if(parseInt($(xml).find('AutoEdit').text()) == 1)
          				{
          					pick_selected_item(loaded_user_id,0,0);	
          					select_user_id(loaded_user_id,"");	
          				}
          				else
          				{
          					if(loaded_user_id > 0)	select_user_id(loaded_user_id,"");	
          				} 
          				 return true;       				    
     				}
     				else
     				{
     					msgbox("Sorry, but it looks like this Username or E-Mail Address already has an account associated with it. Please try again.");	
     					//$('#user_name').val('');
          				//$('#password').val('');
          				//$('#password_confirmed').val('');
          				//$('#email').val('');
          				return false;
     				}
     			}
     		});	        	
          	
          }
     }
     function modUser() 
     {
          var valid = true;
          $().removeClass( "ui-state-error" );
          //valid = valid && checkLength( $( "#user_name2" ), "username", 3, 50 );
          valid = valid && checkLength( $( "#confirm_password" ), "confirm_password", 6, 80 );
          valid = valid && checkLength( $( "#password2" ), "password", 6, 80 );
          //valid = valid && checkRegexp( $( "#user_name2" ), /^[a-z]([0-9a-z_\s])+$/i, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter." );
          valid = valid && checkRegexp( $( "#confirm_password" ), /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9.  You must confirm your password."  );
          valid = valid && checkRegexp( $( "#password2" ), /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9." );
          
          if($( "#confirm_password" ).val() != $( "#password2" ).val() )	
          {
          	valid =false;          	
          	msgbox("Password must match when you enter it and confirm it. Please try again.");
          }
          if ( valid ) 
          {
          	save_user_pass($( "#user_name2" ).val(),$( "#password2" ).val(),$( "#confirm_password" ).val());	   	
          }
          return valid;
     }
     
     function save_user_pass(user,pass,conf)
     {	
     	$.ajax({
     		url: "ajax.php?cmd=save_user_pass",
     		type: "post",
     		dataType: "xml",
     		async:false,
     		data: {
     			'id':loaded_user_id,
     			'user':  user,
     			'pass': pass,
     			'confirm':  conf
     		},
     		error: function() {
     			msgbox("General error updating password. Please try again");
     		},
     		success: function(xml) {
     			if($(xml).find('File').text() == '0')	
     			{
     				show_notice('Password error');
     			}
     			else
     			{
     				show_notice('Password saved');	
     			}
     		}
     	});	
     }
               
     function save_user(user_id) 
	{
		$.ajax({
			url: "ajax.php?cmd=save_user",
			type: "post",
			dataType: "xml",
			data: {
				'id':user_id,
				'user_access_level':  $('#user_access_level').val(),
				'archived': ($('#user_archived').is(':checked') ? '1' : '0'),
				'user_email':  $('#user_email').val(),
				'phone1':  $('#contact_phone1').val(),
				'phone2':  $('#contact_phone2').val(),
				'user_first':  $('#user_first').val(),
				'user_last':  $('#user_last').val(),
				'merchant_id':  $('#merchant_id').val(),
				'store_id':  $('#store_id').val(),
				'user_title':  $('#user_title').val(),
				'logs': ($('#monitor_logs').is(':checked') ? '1' : '0')				
			},
			error: function() {
				msgbox("General error saving changes. Please try again");
			},
			success: function(xml) {
				show_notice('Changes saved');
				load_user_list();
				pick_selected_item(user_id,0,0);
				if(user_added_mode==0)	
				{
					close_all_dialogs();
				}
				else
				{
					if(user_added_mode==1)		load_dynamic_user_select('ms_co_user_id_box','ms_co_user_id',user_id,0,'Select Compliance Officer','');
          			if(user_added_mode==2)		load_dynamic_user_select('ms_grp_user_id_box','ms_grp_user_id',user_id,0,'Select Group Manager','');
          			if(user_added_mode==3)		load_dynamic_user_select('mst_cm_user_id_box','mst_cm_user_id',user_id,0,'Select Compliance Manager','');     	
          			
          			user_added_mode=0;	//reset for next user.
				}
			}
		});
	}
	

	
</script>	
<? include('footer.php') ?>