<? $use_title="Auditor Folder"; ?>
<?
$_SESSION['selected_doc_type_id']=0;
?>
<? include('header.php') ?>

<div class="column" style='width:100%;'>		
		
     <div id='auditor_folder_holder' style='width:100%;'>
     	<div class="auditor2 auditor_file_cabinet2">
     		
     		<div class='portlet-header mrr_portlet_auditor'>Auditor Documents Available:</div>
			<div class='portlet-content'>
     			<div id='auditor2_files_section'></div>
     		</div>   		
     		 <div class="clearfix"></div>
     	</div>
     	
     	<div class='auditor1'>
     		
     		<div id='assigned_files_section'></div> 
     	</div>
     </div>
	
</div>	


<!--Form for file removal confirmation -->
<div id="dialog-file_removal" title="Delete Document" style='display:none;'>
	<p class="validateTips">Are you sure you want to remove this file?</p>
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


<script type='text/javascript'>
	var doc_pg=0;				//used for search results display...index/auditor page should always be 0.
	
	$().ready(function() {			
		auditor_folder();		
     });
         
	function auditor_folder()
	{		
		$('#auditor_folder_holder').show();			
		<? if($_SESSION['access_level']==45) { ?>
			//Auditor 2 section "Filing Cabinet", only displays the files for download.
			
			$('.auditor2').show();		
			$('.auditor1').hide();
			//$('.portlet').hide();
			
			//$('.column').hide();
			
     		refresh_auditor2_files();  
			
		<? } else { ?>
			
			//shown auditor sections "Filing Cabinet"... hide normal page display.
			//$('.portlet').hide();	
			//$('.column').hide();
			$('.auditor2').hide();		
			$('.auditor1').show();
			
			refresh_auditor2_assignment(); 
     		refresh_auditor2_files();  
     			
		<? } ?>		
	}		 
</script>	
<? include('footer.php') ?>