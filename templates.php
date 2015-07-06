<?
     $use_title="Template Manager"; 
     
     $_SESSION['selected_doc_type_id']=0;
     
     include('header.php'); 
     
     $valid_access=check_access($page_name);
     
	$template_list="";	
	$template_form="";
	
	if(!isset($_GET['id']))			$_GET['id']=0;

	//list templates

	$template_list="<a href='templates.php?id=0'><div class='tempate_editor_adder'>Add New Template</div></a>";	
	$sql = "
		select * 
		from templates
		where deleted=0	
		order by archived asc,template_name asc,id asc
	";
	$data=simple_query($sql);
	while($row=mysqli_fetch_array($data))
	{
		$template_list.="<a href='templates.php?id=".$row['id']."'><div class='tempate_editor_list".($row['archived'] > 0 ? " archived" : "")."'>".$row['template_name']."</div></a>";	
	}
		
		
	//template set-up for selected template.
if($_GET['id']>=0)
{
	$namer="";
	$arch=0;
	
	if($_GET['id'] > 0)
	{
		$sql = "
			select * 
			from templates
			where id='".sql_friendly($_GET['id'])."'
		";
		$data=simple_query($sql);
		while($row=mysqli_fetch_array($data))	
		{
			$namer="".$row['template_name']."";
			$arch=$row['archived'];	
		}
	}	
	
	$template_form.="
		<div class='mrr_sector_container'>
			<input type='hidden' name='template_id' id='template_id' value='".$_GET['id']."'>
			<table cellpadding='0' cellspacing='0' border='0' style='width:100%'>			
			<tbody>	
			<tr>					
				<td valign='top'>
					<span class='template_pg_name'><b>Template Name</b></span>
				</td>
				<td valign='top'>
					<input name='template_name' id='template_name' value=\"".$namer."\" class='long tooltipx' title='Enter the label you want to identify this template.'>
				</td>
			</tr>
			<tr>					
				<td valign='top'>
					<label for='template_archived' class='template_pg_label'><b>Archived</b></label>
				</td>
				<td valign='top'>
					<input type='checkbox' name='template_archived' id='template_archived' value='1'".($arch > 0 ? " checked" : "").">
				</td>
			</tr>		
			<tr>					
				<td valign='top'>&nbsp;</td>
				<td valign='top'>
					<input type='button' class='buttonize btn btn-default add_new_btn' onclick='save_template();' value='Save Template'>
					<input type='button' class='buttonize btn btn-default add_new_btn' onclick='template_preview_toggle();' value='Preview'>
					<input type='button' class='buttonize btn btn-default add_new_btn' onclick='template_settings_toggle();' value='Settings'>
					
					".( $_GET['id'] > 1 ? "<input type='button' class='buttonize btn btn-default add_new_btn' onclick='confirm_copy_master_template_items(".$_GET['id'].");' value='Copy Items from Master Template'>" : "")."
					
					".( $_GET['id'] > 1 ? "<input type='button' class='buttonize btn btn-default add_new_btn' onclick='confirm_delete();' value='Delete'>" : "")."
				</td>
			</tr>			
			<tr>					
				<td valign='top' colspan='2'>
					<div id='cur_template_items'></div>
				</td>
			</tr>			
			</tbody>
			</table>			
		</div>
	";
	
	$file_type_form="		
		<div class='mrr_sector_container'>
			<table cellpadding='0' cellspacing='0' border='0' style='width:100%'>			
			<tbody>			
			<tr>					
				<td valign='top'><span class='template_pg_name'><b>Edit File Types</b></span></td>
				<td valign='top'>
					<input type='button' class='buttonize btn btn-default add_new_btn' onclick='save_all_file_types();' value='Save File Types'>
				</td>
			</tr>			
			<tr>					
				<td valign='top' colspan='2'>
					<div id='cur_template_file_types'></div>
				</td>
			</tr>			
			</tbody>
			</table>			
		</div>
	";
}
?>
<? if($valid_access) {?>
     <div class="column"  style='width:100%'>
       <div class="portlet">
         <div class="portlet-header">Templates</div>
         <div class="portlet-content"><?=$template_list ?></div>
       </div>
     <? if($_SESSION['access_level'] >=100) {?>
       <div class="portlet"><!-- no_collapse  default_closed -->
         <div class="portlet-header">Template Manager</div>
         <div class="portlet-content"><?=$template_form ?></div>
       </div>
  	<? } ?>
  	<? if($_SESSION['access_level'] >=100) {?>
       <div class="portlet">
         <div class="portlet-header">File Types</div>
         <div class="portlet-content"><?=$file_type_form ?></div>
       </div>
     <? } ?>
     </div>    
     <div class='clear'></div>
<? } else { ?>
	<br><input type='button' class='buttonize' onclick='go_home()' value='Home'><br><hr><br>&nbsp;<br>
	
	<h2>Sorry, you are not allowed to view this page.  </h2>
	
	<br><hr><br><input type='button' class='buttonize' onclick='go_home()' value='Home'><br>
<? } ?>

<div id='dialog_delete_template' title='Remove this Template?' style='display:none;'>
	<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>This Template will be permanently removed and cannot be recovered. <br><br> Are you sure you want to delete it?</p>
</div>

<div id='dialog_copy_template_module' title='Template Module Hints?' style='display:none; width:600px;'>
	<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>
		To copy a complete template section, a "module", type the name of the module.  As you type, the suggestions display available modules.  Select one to copy it and all sub-items into this template. You can modify it later for this template.
		<br><br>
		Each module must have a unique name, if the name typed matches an existing module, it will be included on this template. {Names are not case-sensitive when matched.} Type a new name to create a new template module.
		<br><br>
		Modules are not grouped under anything... i.e. they are not sub-items of any other template item.	Use the Group Under to place a new item as a sub-item of another template item. Sub-items are not matched and are not required to be unique. 
		To make a new item "Take Test" as a sub-item of a main item or module like "Training Center", type "Take Test" for the Item Name and select "Training Center" for the optional Group Under choice.  
		Only main items (not grouped under anything... "modules") will display sub-items.  Sub-items cannot have their own sub-items at this time.	
	</p>
</div>

<div id='dialog_copy_all_items' title='Copy Master Template Items?' style='display:none;'>
	<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>
		This operation will clear existing items from the template.  You can then add new template items (or sub-items) or remove selected template items from this template as you wish. <br><br>
		<b>Are you sure you want to copy all the items from the master template?</b></p>
</div>
     	
<div id='dialog_delete_item' title='Remove this Template Item?' style='display:none;'>
	<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>This Template Item will be permanently removed and cannot be recovered. <br><br> Are you sure you want to delete it?</p>
</div>
<div id='dialog_delete_file' title='Remove this Template File Type?' style='display:none;'>
	<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>This Template File Type will be permanently removed and cannot be recovered. <br><br> Are you sure you want to delete it?</p>
</div>

<!-- TinyMCE -->
<script src="/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
		
	// General options
	selector: "textarea.mceEditor",
	theme : 'modern',
	width: 600,
	height: 400,
     plugins: [
              "advlist print autolink link image lists charmap preview hr anchor pagebreak spellchecker",
              "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
              "table contextmenu directionality emoticons template paste textcolor"
        ],     
     toolbar: "nonbreaking undo redo | styleselect | bold italic fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fullpage print | forecolor backcolor emoticons", 
     nonbreaking_force_tab: true,
     style_formats: [
             {title: 'Bold text', inline: 'b'},
             {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
             {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
             {title: 'Example 1', inline: 'span', styles: {color: '#00ff00'}},
             {title: 'Example 2', inline: 'span', styles: {color: '#0000ff'}},
             {title: 'Table styles'},
             {title: 'Table row 1', selector: 'tr', styles: {color: '#ff0000'}}
     ]
});
</script>
<script>	
	
	var cur_id=<?= $_GET['id'] ?>;
	var doc_pg=0;				//used for search results display...template page should always be 0.
	
	$().ready(function() {	
		$('.buttonize').button();
		fetch_template_items();
		fetch_template_file_types();		
     });
     
     function go_back(id)
	{
		if(cur_id!=id && cur_id > 1 && id > 0)		id=cur_id;		
		
		window.location.href = "/templates.php?id="+id+"";
	}
	
	
	function confirm_copy_master_template_items(id)
     {     	 
     	$( "#dialog_copy_all_items" ).dialog({
               modal: true,
               buttons: {
               	"Okay": function() 
               	{                        
                        copy_master_template(id);
                                 
               		$( this ).dialog( "close" );   	 		
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
	
	function module_template_hint()
     {     	 
     	$( "#dialog_copy_template_module" ).dialog({
               modal: true,
               width: 600,
               buttons: {
               	"Okay": function() 
               	{             
               		$( this ).dialog( "close" );   	 		
               	}
               }
          });  
          return false;            
     }
	
	function copy_master_template(id)
	{		
		$.ajax({
     			url: "ajax.php?cmd=copy_template_items",
     			data: {
     				"id":id  				
     				},
     			type: "POST",
     			cache:false,
     			dataType: "xml",
     			error: function() {
					msgbox("General error making copy of master template items. Please try again.");
				},	
     			success: function(xml) {     				     				
     				window.location.href = "/templates.php?id="+id+"";		
     			}
     	});
	}
     
     function fetch_template_file_types()
     {
     	$('#cur_template_file_types').html('Loading...');	
     	
     	$.ajax({
     			url: "ajax.php?cmd=load_file_types",
     			data: {
     				"id":id  				
     				},
     			type: "POST",
     			cache:false,
     			dataType: "xml",
     			error: function() {
					msgbox("General error finding template file types. Please try again.");
				},	
     			success: function(xml) {
     				     				
     				mrr_tab=$(xml).find('mrrTab').text();
     				$('#cur_template_file_types').html(mrr_tab);			
     			}
     	});
     }
     
     function save_all_file_types()
     {
     	i=0;
          tot_types=parseInt($('#tot_file_types').val());
          for(i=0; i < tot_types; i++)
          {
          	typeid=parseInt( $('#file_array_'+i+'').val() );	
          	save_template_file_type(typeid);
          }
          	
          if($('#file_type_new_name').val()!="")		save_template_file_type(0);
          	
          show_notice('Template File Types have been saved.');	
          fetch_template_file_types();
          //go_back(0);
     }
     
     
     function save_template_file_type(id)
     {
     	new_id=0;
     	new_name=$('#file_type_new_name').val();
     	new_ext=$('#file_type_new_ext').val();
     	new_min=$('#file_type_new_min').val();
     	new_max=$('#file_type_new_max').val();
     	new_arch=0;
     	
     	if(id > 0)
     	{
     		new_id=id;
     		new_name=$('#file_type_'+id+'_name').val();
     		new_ext=$('#file_type_'+id+'_ext').val();
     		new_min=$('#file_type_'+id+'_min').val();
     		new_max=$('#file_type_'+id+'_max').val();
     		new_arch=($('#file_type_'+id+'_archived').is(':checked')  ? 1 : 0);	
     	}
     	
     	$.ajax({
     			url: "ajax.php?cmd=save_file_type",
     			data: {
     				"id":new_id,
	   				"name":new_name,
	   				"ext":new_ext,
	   				"min":new_min,
	   				"max":new_max,
	   				"archived":new_arch     				
     				},
     			type: "POST",
     			cache:false,
     			async: false,
     			dataType: "xml",
     			error: function() {
					msgbox("General error updating template file type. Please try again.");
				},	
     			success: function(xml) {
     				
     				if($(xml).find('rslt').text() == '0')	
          			{          				
          				msgbox("General error updating template file type item "+new_id+". Please try again.");
          			}
          			else
          			{
          				if(id==0)
          				{
          					$('#file_type_new_name').val('');
          					$('#file_type_new_ext').val('');
          					$('#file_type_new_min').val('0');
          					$('#file_type_new_max').val('0');	
          				}
          				//show_notice('Template File Types have been saved.');
          			}			
     				
     			}
     	});
     }
     
     
     function save_template_item(temp_id,id)
     {
     	new_id=0;
     	new_name=$('#temp_item_new_name').val();
     	new_type=$('#temp_item_new_type').val();
     	new_group=$('#temp_item_new_sub_group').val();
     	new_min=$('#temp_item_new_min').val();
     	new_max=$('#temp_item_new_max').val();
     	new_ord=$('#temp_item_new_zorder').val();
     	title_text=$('#temp_item_new_tip').val();
     	new_arch=0;
     	
     	if(id > 0)
     	{
     		new_id=id;
     		new_name=$('#temp_item_'+id+'_name').val();
     		new_type=$('#temp_item_'+id+'_type').val();
     		new_group=$('#temp_item_'+id+'_sub_group').val();
     		new_min=$('#temp_item_'+id+'_min').val();
     		new_max=$('#temp_item_'+id+'_max').val();
     		new_ord=$('#temp_item_'+id+'_zorder').val();
     		title_text=$('#temp_item_'+id+'_tip').val();
     		new_arch=($('#temp_item_'+id+'_archived').is(':checked')  ? 1 : 0);	
     	}
     	
     	//if(temp_id==0)				return;
     	//if(new_id==0 && new_name=="")	return;
     	
     	$.ajax({
     			url: "ajax.php?cmd=save_template_item",
     			data: {
     				"id":new_id,
     				"template_id":temp_id,
	   				"name":new_name,
	   				"type":new_type,
	   				"group":new_group,
	   				"min":new_min,
	   				"max":new_max,
	   				"zorder":new_ord,
	   				"title_text":title_text,
	   				"archived":new_arch     				
     				},
     			type: "POST",
     			cache:false,
     			async: false,
     			dataType: "xml",
     			error: function() {
					msgbox("General error updating template item. Please try again.");
				},	
     			success: function(xml) {
     				
     				myid=parseInt( $(xml).find('rslt').text() );
     				
     				if($(xml).find('rslt').text() == '0')	
          			{          				
          				//msgbox("General error updating template item "+new_id+". Please try again.");
          			}
          			else
          			{
          				if(id==0)
          				{
          					$('#temp_item_new_name').val('');
          					$('#temp_item_new_type').val(0);
          					$('#temp_item_new_sub_group').val(0);
          					$('#temp_item_new_min').val('0');
          					$('#temp_item_new_max').val('0');	
          					$('#temp_item_new_tip').val('');
          					//id=parseInt($(xml).find('rslt').text());
          				}
          				//show_notice('Template item has been saved.');
          				//fetch_template_items();          				
          				//go_back(temp_id);   
          			}	
          			return myid;		     				
     			}
     	});
     }
     
     
     function save_template()
     {
     	id=$('#template_id').val();
     	//if(id==0)		id=cur_id;
     	
     	template=$('#template_name').val();
     	
     	arch = ($('#template_archived').is(':checked')  ? 1 : 0);
     	
     	$.ajax({
     			url: "ajax.php?cmd=save_template",
     			data: {
     				"id":id,
	   				"template":template,
	   				"archived":arch     				
     				},
     			type: "POST",
     			cache:false,
     			dataType: "xml",
     			error: function() {
					msgbox("General error updating template. Please try again.");
				},	
     			success: function(xml) {
     				
     				if($(xml).find('rslt').text() == '0')	
          			{          				
          				msgbox("General error updating template. Please try again.");
          			}
          			else
          			{
          				totres=1;
          				
          				id=$(xml).find('rslt').text();
          				          				
          				i=0;
          				tot_items=parseInt($('#tot_temp_items').val());
          				for(i=0; i < tot_items; i++)
          				{
          					itemid=parseInt( $('#id_array_'+i+'').val() );	
          					thisres=save_template_item(id,itemid);
          					if(thisres==0)		totres=0;
          				}
          				
          				if($('#temp_item_new_name').val()!="")	
          				{
          					thisres=save_template_item(id,0);
          					if(thisres==0)		totres=0;
          				}
          					
          				show_notice('Template has been saved.');
          				if(totres > 0)	go_back(id);   	
          			}	    				
     			}
     	});
     }
     
     function fetch_template_items()
     {
     	$('#cur_template_items').html('Loading...');	
     	
     	id=$('#template_id').val();
     	
     	$.ajax({
     			url: "ajax.php?cmd=load_template_items",
     			data: {
     				"id":id  				
     				},
     			type: "POST",
     			cache:false,
     			dataType: "xml",
     			error: function() {
					msgbox("General error finding template items. Please try again.");
				},	
     			success: function(xml) {
     				     				
     				mrr_tab=$(xml).find('mrrTab').text();
     				$('#cur_template_items').html(mrr_tab);
     				//console.log($('#cur_template_items').html());
     				
     				$('#temp_item_new_name').autocomplete_old('ajax.php?cmd=search_template_items',{formatItem:formatItem});	
     				
     				//$('#template_item_preview').show();     				
     				//$('#template_item_settings').hide();   
     				
     				$('#template_item_preview').hide();     				
     				$('#template_item_settings').show(); 
     				
     				//$("select").selectmenu('destroy');  
     				//$("select").selectmenu();  		     					
     			}
     	});
     }
     
     function template_preview_toggle()
     {
     	$('#template_item_preview').toggle();  
     }
     function template_settings_toggle()
     {
     	$('#template_item_settings').toggle();  	
     }
     
     
     function confirm_delete_file(id)
     {   
     	
     	$( "#dialog_delete_file" ).dialog({
               modal: true,
               buttons: {
               	"Okay": function() 
               	{                        
                         $.ajax({
               			url: "ajax.php?cmd=delete_file_type",
               			type: "post",
               			dataType: "xml",
               			data: {
               				"id":id
               			},
               			error: function() {
               				msgbox("General error removing template file type. Please try again");
               			},
               			success: function(xml) 
               			{				
               				show_notice('Template File Type Removed');                  				
               				go_back(0); 
               			}
               		});   
               		$( this ).dialog( "close" );          	 		
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
     
     function confirm_delete()
     {     	 
     	id=$('#template_id').val();
     	
     	$( "#dialog_delete_template" ).dialog({
               modal: true,
               buttons: {
               	"Okay": function() 
               	{                        
                         $.ajax({
               			url: "ajax.php?cmd=delete_template",
               			type: "post",
               			dataType: "xml",
               			data: {
               				"id":id
               			},
               			error: function() {
               				msgbox("General error removing template. Please try again");
               			},
               			success: function(xml) 
               			{				
               				show_notice('Template Removed');                  				
               				go_back(0);             				
               			}
               		});       
               		$( this ).dialog( "close" );      	 		
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
     
     function confirm_delete_item(id)
     {     	 
     	$( "#dialog_delete_item" ).dialog({
               modal: true,
               buttons: {
               	"Okay": function() 
               	{                        
                         $.ajax({
               			url: "ajax.php?cmd=delete_template_item",
               			type: "post",
               			dataType: "xml",
               			data: {
               				"id":id
               			},
               			error: function() {
               				msgbox("General error removing Template Item. Please try again");
               			},
               			success: function(xml) 
               			{				
               				show_notice('Template Item Removed');                				
               				go_back(cur_id);                				         				
               			}
               		});          
               		$( this ).dialog( "close" );   	 		
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
     
</script>
<? include('footer.php'); ?>