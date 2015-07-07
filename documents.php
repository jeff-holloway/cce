<?
$use_title="Documents";

if(!isset($_GET['id']))
{
	$_SESSION['selected_doc_type_id']=0;
}
else
{
	$_SESSION['selected_doc_type_id']=$_GET['id'];
}

include('header.php'); 

$sql = "
	select doc_column_left as home_column_left,
		doc_column_right as home_column_right
	
	from users
	where id = '".sql_friendly($_SESSION['user_id'])."'
";
$data_columns = simple_query($sql);

if(!isset($_POST['search_cust']))	$_POST['search_cust']="";

if(!isset($_GET['id']))
{
	echo "Sorry, this page is not avaiable.";	
}
else
{	
	$item_id=$_GET['id'];		//template item category...
	$list="";
	
	$list1="";
	$list2="";
	$list3="";
	$list4="";
	
	$temp_id=0;
	$mrr_adder="";
	
	//find merchant template first...acts as a default.
	if($_SESSION['merchant_id'] > 0 && $_SESSION['selected_merchant_id'] == $_SESSION['merchant_id'])
	{
		$temp_id=mrr_get_merchant_template_id($_SESSION['merchant_id']);     		
		if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
	}
	elseif($_SESSION['selected_merchant_id'] > 0)
	{
		$temp_id=mrr_get_merchant_template_id($_SESSION['selected_merchant_id']);     		
		if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
	}
	//find store template next...override merchant if set.
	if($_SESSION['store_id'] > 0 && $_SESSION['selected_store_id']==$_SESSION['store_id'])
	{
		$temp_id=mrr_get_merchant_template_id($_SESSION['store_id']);     		
		if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
	}
	if($_SESSION['selected_store_id'] > 0)
	{
		$temp_id=mrr_get_merchant_template_id($_SESSION['selected_store_id']);     		
		if($temp_id > 0)	$mrr_adder=" and template_id='".sql_friendly($temp_id)."'";
	}
	$mrr_adder="";
	
	$sql = "
		select * 
		from template_items
		where deleted=0			
			".$mrr_adder."  
			 and id = '".sql_friendly($item_id)."'  	
		order by item_label asc
	";
	$data=simple_query($sql);
	while($row=mysqli_fetch_array($data))
	{
		$namer="".$row['item_label']."";	
		
		$files=get_all_files_for_template_item($row['id'],0,0,0,0,0,0);
		$subs="";
		$show_header=0;
		if($files!="")		$show_header=1;
		
		
		//get the files for this item
		
		//get sub items for this item
		$sql2 = "
     		select * 
     		from template_items
     		where deleted=0			
     			".$mrr_adder."  
     			and sub_group_id = '".sql_friendly($item_id)."'    		
     		order by item_label asc
     	";
     	$data2=simple_query($sql2);
     	while($row2=mysqli_fetch_array($data2)) 
     	{
     		$namer2="".$row2['item_label']."";	
     		
     		//get files for this sub_item
     		$show_uploaded=0;
     		if(trim($namer) == "Financial Institution")		$show_uploaded=1;
     		
     		$files2=get_all_files_for_template_item($row2['id'],0,0,0,$show_header,$show_uploaded,0);
     		if($files2!="")
     		{
     			if(trim($namer) != "Financial Institution")		$show_header=1;
     			
     			$subs.="
     				<!--<div class='template_item_subtitle'>".$namer2."</div>-->
					<div class='template_item_subfile'>".$files2."</div>
     			";
     			if(trim($namer) == "Financial Institution"  && trim($namer2)=="Bank Application")
				{
					$list1="
     					<div class='template_item'>
               				<div class='template_item_file'>".$files2."</div>
               			</div>
					";
				}
				elseif(trim($namer) == "Financial Institution"  && trim($namer2)=="Current Bank Statement")
				{
					$list2="
     					<div class='template_item'>
               				<div class='template_item_file'>".$files2."</div>
               			</div>
					";
				}
				elseif(trim($namer) == "Financial Institution"  && trim($namer2)=="Financial Institution/Merchant Correspondence")
				{
					$list3="
     					<div class='template_item'>
               				<div class='template_item_file'>".$files2."</div>
               			</div>
					";
				}
				elseif(trim($namer) == "Financial Institution"  && trim($namer2)=="Previous Bank Statement")
				{
					$list4="
     					<div class='template_item'>
               				<div class='template_item_file'>".$files2."</div>
               			</div>
					";
				}
     		}
     	}	
     	
     	if(trim($subs)!="")		$files="";		//...clear out the files in the main group only if the sub groups are not blank...otherwise...this is the group to print. 			
		
		if($files=="" && $subs=="")	
		{
			$files="<div class='template_item_file_name'>No Document are available at this time.</div>";
		}
		else
		{
			$list.="
			<div class='template_item'>
				<!--<div class='template_item_title'>".$namer."</div>-->
				<div class='template_item_file'>".$files."</div>
				<div class='template_item_subitems'>
					".$subs."
				</div>
			</div>";
		}
	} 
	$xref_id=0;
?>	
	<div class="column move_box_left" style='margin-bottom:0px; padding:0px;'>
		
		<div class="portlet left_col sort_tbl" id='portlet_doc_SearchBox'>
			<div class="Table_sort_main">
     			<div class="search_box">
     				<div class="input-group">
     					<input type="text" class="form-control" name='search_cust' id='search_cust' value="<?=$_POST['search_cust']?>" placeholder="Search...">
     					<span class="input-group-btn">
     						<button class="btn btn-default" type="button" name='search_custs' id='search_custs'><i class="fa fa-search"></i></button>											
     					</span>
     				</div>
     				
     				<!--<a href="#">advanced search <i class="fa fa-angle-double-right"></i></a>-->
     			</div>
				<div class="clearfix"></div>
				<div id='merchant_customers'></div>  			
			</div>
		</div>
		
		<? if(trim($namer) == "Training Center") {  ?>
          <?
          	//echo "<br>U".$_SESSION['selected_user_id']."M".$_SESSION['selected_merchant_id']."S".$_SESSION['selected_store_id']." ... U".$_SESSION['user_id']."M".$_SESSION['merchant_id']."S".$_SESSION['store_id']."<br>";
          ?>			
		<div id='cm_training_link' class='cust_info'>
			<?
			$user_idst =strtolower($_SESSION['username']);
     		if($_SESSION['user_id']==1)		$user_idst =strtolower('Employee.test1');
     		//if($_SESSION['user_id']==1)		$user_idst =strtolower('BillSouza');
     		if($_SESSION['user_id']==2)		$user_idst =strtolower('Employee.test2');
     		
     					
     	     $time =time();//time php
     	     $key_sso = $defaultsarray['docebo_api_key_custom'];
     	     $token =md5($user_idst.','.$time.','.$key_sso);
     	     $url ="http://training.capitalcomplianceexperts.com/lms/index.php?r=site/sso&login_user=".$user_idst."&time=".$time."&token=".$token;
     					
     		//echo "<li><a href='".$url."' target='_blank'><i class='fa fa-arrow-circle-right'></i> </a></li>";
			?>
			
				<button type="submit" id='go_docebo' name='go_docebo' class="btn btn-default add_new_btn" onClick="var win = window.open('<?=$url ?>', '_blank'); win.focus();" title='LOG IN TO TRAINING'>LOG IN TO TRAINING</button>
			<br>
			<br>
		</div>
		
		<? } ?>	
		
		
		<? if(trim($namer) == "Compliance Officer" || trim($namer) == "Training Center") {  ?>
		
		<!-- cust_info  -->
		<div class="portlet mrr_cust_info" id='portlet_doc_compliance_office'>
			<div class="portlet-header"><?=(trim($namer) == "Compliance Officer" ? "COMPLIANCE OFFICER" : "TRAINING") ?></div>
			<div class="portlet-content">
				<table style='width:100%;' class='internal_table'>
					<tr>
						<td valign='top' width='30%'>
							<table class="table table-striped internal_table">
								<tbody>
									<tr><td nowrap>COMPLIANCE OFFICER<br><span id='cust_co' style='color:black;'></span><br><br></td></tr>
									<tr><td>EMAIL ADDRESS<br><span id='cust_email'></span><br><br></td></tr>
									<tr<?=(trim($namer) == "Compliance Officer" ? "" : " style='display:none;'") ?>><td>CO CELL NUMBER<br><span id='cust_cell'></span></td></tr>
									<tr<?=(trim($namer) == "Compliance Officer" ? "" : " style='display:none;'") ?>><td>OFFICE PHONE<br><span id='cust_phone2'></span></td></tr>
									<tr><td valign='top' class="pos_rel co_photo mrr_cust_info">CO PHOTO<br><img id='cust_co_image' src="images/no-profile-image.png" alt="" width='<?=$user_thumb_width ?>'></td></tr>						
								</tbody>
							</table>
						</td>
						
						<?
          				if($_SESSION['selected_merchant_id'] > 0 && trim($namer) != "Compliance Officer")
          				{
          					echo "<td valign='top' class='pos_rel co_photo mrr_cust_info'>CO CERTIFICATE<br>";
          					
          					$xref_id=0;
          					$sql="
          						select co_user_id 
          						from merchants
          						where id='".sql_friendly($_SESSION['selected_merchant_id'])."'
          					";
               				$data=simple_query($sql);
               				if($row=mysqli_fetch_array($data))		$xref_id=$row['co_user_id'];
          					
          					if($xref_id > 0 && $_SESSION['access_level']>=60)
          					{
          						$co_tab=create_uploader_section('cert_image_holder',"",SECTION_CERTIFICATES,$xref_id,'show_user_cert2', 'Upload Certificate');
          						echo $co_tab;
          					}
          					echo "</td>";
          				}
          				elseif(trim($namer) == "Compliance Officer")
          				{
          					echo "<td valign='top' class='pos_rel co_photo mrr_cust_info'>&nbsp;</td>";
          				}
          				?>																						
					</tr>					
										
				</table>						
				<div id='cm_slots' class='cust_info'>
					<?					
					if(trim($namer) == "Training Center")
					{
						echo cm_document_slot_filler(1);
					}
					?>
				</div>
			</div>
		</div>
		<? } ?>		
		
		<? if(trim($namer) == "IRS") {  ?>
		
		<div class="portlet cust_info" id='portlet_doc_irs_information'>
			<div class="portlet-header">IRS INFORMATION</div>
			<div class="portlet-content">
				<table style='width:100%;'>						
					<tr>
						<td valign='top'>
							<table class="table table-striped internal_table">
								<tbody>
									<tr>
										<td>ADDRESS<br><span id='irs_addr'></span></td>
										<td>OFFICE PHONE<br><span id='irs_phone'></span></td>
									</tr>
									<tr>
										<td>IRS EXAMINER<br><span id='irs_agent'></span></td>
										<td>MOBILE PHONE<br><span id='irs_cell'></span></td>
									</tr>									
									<tr>
										<td>EMAIL ADDRESS<br><span id='irs_email'></span></td>
										<td>CASE CONTROL NUMBER<br><span id='irs_case'></span></td>
									</tr>
									<tr>
										<td>EMPLOYER ID NUMBER<br><span id='irs_emp_id'></span></td>
										<td>&nbsp;</td>
									</tr>									
								</tbody>
							</table>
						</td>												
					</tr>
				</table>					
			</div>
		</div>
		
		<? } ?>
		
		<? if(trim($namer) == "Financial Institution") {  ?>
		
		<div class="portlet cust_info" id='portlet_doc_financial_institution'>
			<div class="portlet-header">FINANCIAL INSTITUTION INFORMATION</div>
			<div class="portlet-content">
				<table style='width:100%;'>						
					<tr>
						<td valign='top'>
							<table class="table table-striped internal_table">
								<tbody>
									<tr>
										<td>FI NAME<br><span id='fi_name'></span></td>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>ADDRESS<br><span id='fi_addr'></span></td>
										<td>OFFICE PHONE<br><span id='fi_phone'></span></td>
									</tr>
									<tr>
										<td>RELATIONSHIP MANAGER<br><span id='fi_relation'></span></td>
										<td>MOBILE PHONE<br><span id='fi_cell'></span></td>
									</tr>
									<tr>
										<td>EMAIL ADDRESS<br><span id='fi_email'></span></td>
										<td>&nbsp;</td>
									</tr>
									
									<tr><td colspan='2'>&nbsp;</td></tr>
									
									<tr>
										<td>ADDRESS<br><span id='fi_addrx'></span></td>
										<td>OFFICE PHONE<br><span id='fi_aud_phone'></span></td>
									</tr>
									<tr>
										<td>MSB AUDITOR EXAMINER<br><span id='fi_audit'></span></td>
										<td>MOBILE PHONE<br><span id='fi_aud_cell'></span></td>
									</tr>									
									<tr>
										<td>EMAIL ADDRESS<br><span id='fi_aud_email'></span></td>
										<td>REFERENCE #<br><span id='fi_aud_refer'></span></td>
									</tr>									
								</tbody>
							</table>
						</td>												
					</tr>
				</table>					
			</div>
		</div>
		
		<? } ?>		
		<? if(trim($namer) == "Compliance Manager") {  ?>		
		<div id='cm_slots' class='cust_info'>
			<?= cm_document_slot_filler(0) ?>
		</div>		
		<? } ?>		
		
		
	</div>	
	

<!--Form for file removal confirmation -->
<div id="dialog-file_removal" title="Delete Document" style='display:none;'>
	<p class="validateTips">Are you sure you want to remove this document?</p>
</div>

<!--Form for New Document Name -->
<div id="dialog-form_file_rename" title="Rename Document" style='display:none; width:500px;'>
	<p class="validateTips">Give this file a New Document Name</p>
	<div class='field'>
		<label for="email">Old Name</label>
		<span><br><span id='old_doc_file_name'></span></span>
	</div>
	<div class='field'>
		<label for="user_name">New Name</label>
		<span>
			<input type="text" name="document_name" id="document_name" value="" style='width:300px;'>
			<input type="hidden" name="document_id" id="document_id" value="0">
		</span>		
	</div>
	
	<div class='field'>
		<label for="user_name">New Document Type</label>
		<span>
			<span id='document_type' onClick="$('#temp_sub_sel').show();" class='doc_type_sect_item'></span><input type="hidden" name="document_type_id_rename" id="document_type_id_rename" value="0">
		</span>		
	</div>
	<div class='field'>
		<label for="user_name">New Doc Sub Type</label>
		<span>
			<span id='document_sub' onClick="$('#temp_sub_sel').show();" class='doc_type_sect_item'></span><input type="hidden" name="document_sub_id_rename" id="document_sub_id_rename" value="0">
			<div id='temp_sub_sel_box'></div>
		</span>	
	</div>
	
	
	<div class='field'>
		<label for="user_name">New Customer</label>
		<span>
			<span id='display_customer_lock' onClick="$('#cust_store_sel').show();" class='doc_type_sect_item'></span><input type="hidden" name="document_cust_id_rename" id="document_cust_id_rename" value="0">
		</span>		
	</div>
	<div class='field'>
		<label for="user_name">New Store Location</label>
		<span>
			<span id='display_store_lock' onClick="$('#cust_store_sel').show();" class='doc_type_sect_item'></span><input type="hidden" name="document_store_id_rename" id="document_store_id_rename" value="0">
			<div id='cust_store_sel_box'></div>
		</span>		
	</div>
	
	<div class='field'>
		<label for="user_name">New Display Date</label>
		<span>
			<input type="text" name="document_date_rename" id="document_date_rename" value="" class='linedate' style='width:100px;'>
			<!-- Allow form submission with keyboard without duplicating the dialog button -->
			<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
		</span>		
	</div>
</div>

<div class='column move_box_right'>

<?	// mrr_cust_info
	if(trim($namer) == "Financial Institution")
	{
		echo "
			
                 	<div class='portlet mrr_aud_file_wrapper_marker' id='portlet_doc_correspondence'>
                   		<div class='portlet-header'>CORRESPONDENCE</div>
                   		<div class='portlet-content portlet-content-mrr'>".$list3."</div>
                 	</div>     
               
			
                 	<div class='portlet mrr_aud_file_wrapper_marker' id='portlet_doc_account_applications'>
                   		<div class='portlet-header'>ACCOUNT APPLICATIONS</div>
                   		<div class='portlet-content portlet-content-mrr'>".$list1."</div>
                 	</div>     
                 	<div class='portlet mrr_aud_file_wrapper_marker' id='portlet_doc_current_bank_statement'>
                   		<div class='portlet-header'>CURRENT BANK STATEMENTS</div>
                   		<div class='portlet-content portlet-content-mrr'>".$list2."</div>
                 	</div>     
                 	<div class='portlet mrr_aud_file_wrapper_marker' id='portlet_doc_previous_bank_statements'>
                   		<div class='portlet-header'>PREVIOUS BANK STATEMENTS</div>
                   		<div class='portlet-content portlet-content-mrr'>".$list4."</div>
                 	</div>     

          ";
	}
	else
	{
     	echo "
          	<div class='mrr_aud_file_wrapper'>
                 	<div class='auditor_file_cabinet portlet' id='portlet_doc_compliance_officer_2'>
                   		<div class='portlet-header'>".( (trim($namer) == "Compliance Officer" || trim($namer) == "Compliance Manager" || trim($namer)=="Training Center") ? "All" : trim($namer))." Documents</div>
                   		<div class='portlet-content'>".$list."</div>
                 	</div>     
               </div>     
               <div class='clear'></div>
     	";		//<div style='padding:5px; margin:5px; border:1px solid #ebebeb; border-top:0; margin-right:5px; padding-right:5px;'></div>
	}
}
?>
</div>
<script>	
	var doc_pg=0;
	
	$().ready(function() {	
		$('.buttonize').button();
		
		//load_co_slot_info();
		<? if(trim($namer) == "Training Center" && $_SESSION['selected_merchant_id'] > 0) {  ?>
			//update_bread_crumb_trail();	
			update_bread_crumb_trail_v2a();
			
			show_user_cert(<?=$xref_id ?>,'#cert_image_holder');
			
			$( ".cm_cert_image_holder" ).each(function( ) {
				thisid=$( this ).attr('user_id');
  				show_user_cert(thisid,'#cm_cert_image_holder_'+thisid+'');
			});
			
			//load_cust_search_v2();
			
			$('.upload_document_label').html('Upload Certificate');
			$('.upload_document_label').css('font-size','9px');
			
			
		<? } ?>	
		<? if(trim($namer) == "Compliance Officer" && $_SESSION['selected_merchant_id'] > 0) {  ?>
			//update_bread_crumb_trail();	
			update_bread_crumb_trail_v2a();
			show_user_cert(<?=$xref_id ?>,'#cert_image_holder');
		<? } ?>	
		
		<? if(trim($namer) == "Compliance Manager") {  ?>
			//update_bread_crumb_trail();	// || trim($namer) == "Compliance Officer"
			update_bread_crumb_trail_v2a();
		<? } ?>	
		<? if(trim($namer) == "Financial Institution") {  ?>	
			get_financial_inst_details_display();
		<? } ?>	
		
		doc_pg=<?= $_GET['id'] ?>;	
		
		<? if($_GET['id']==3 || $_GET['id']==36) { ?>
			update_bread_crumb_trail_stripped();
		<? } ?>
		
		fetch_tagline_filler();	
     });
     
     $('#search_cust').keypress(function(event) {
     	if(event.keyCode == 13) load_cust_search_v2();
     });
     
     
     function afterPortletMove() {
     	
     	$('.mrr_aud_file_wrapper').each(function() {
     		if($(this).find('.portlet').length == 0) $(this).remove();
     		//removeClass('mrr_aud_file_wrapper');
     	});
     	
     	$('.mrr_aud_file_wrapper_marker').each(function() {
     		if(!$(this).parent().is(".mrr_aud_file_wrapper")) $(this).wrap("<div class='mrr_aud_file_wrapper'></div>");
     		
     		
     	});

     	//$('.mrr_aud_file_wrapper_marker').unwrap(".mrr_aud_file_wrapper");
     	//$(".mrr_aud_file_wrapper_marker").wrap("<div class='mrr_aud_file_wrapper'></div>");
     	/*
     	

     	//$('.mrr_aud_file_wrapper').remove();
     	$('.mrr_aud_file_wrapper_marker').each(function() {
     		//if(!$(this).parent().is(".mrr_aud_file_wrapper")) $(this).unwrap(".mrr_aud_file_wrapper");
     		if(!$(this).parent().is(".mrr_aud_file_wrapper")) $(this).wrap("<div class='mrr_aud_file_wrapper'></div>");
     		
     		
     	});
     	*/
     }
     
     $().ready(function() {
     	$(".mrr_aud_file_wrapper_marker").wrap("<div class='mrr_aud_file_wrapper'></div>");
     });
</script>
<? include('footer.php'); ?>