<?
     $use_title="Access Manager"; 
     
     $_SESSION['selected_doc_type_id']=0;
     
     include('header.php'); 
     
     $valid_access=check_access($page_name);
     
	$level_form="";
	
	if(!isset($_GET['id']))			$_GET['id']=0;

	//list access levels

	$level_list="";	
	$sql = "
		select * 
		from user_levels
		where deleted=0	
		order by access_level desc,archived asc,level_name asc,id asc
	";
	$data=simple_query($sql);
	while($row=mysqli_fetch_array($data))
	{
		$level_list.="<a href='access_manager.php?id=".$row['id']."'><div class='tempate_editor_list".($row['archived'] > 0 ? " archived" : "")."'>".$row['level_name']."</div></a>";	
	}
		
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

		
	//access set-up for selected template.
if($_GET['id']>=0)
{
	$namer="";
	$arch=0;
	
	$perm_list="";		
	
	if($_GET['id'] > 0)
	{
		$sql = "
			select * 
			from user_levels
			where id='".sql_friendly($_GET['id'])."'
		";
		$data=simple_query($sql);
		if($row=mysqli_fetch_array($data))	
		{
			$namer="".$row['level_name']."";
			$arch=$row['archived'];	
			
			$perm_list.="<table cellpadding='0' cellspacing='0' border='0' style='width:100%'>";
			$perm_list.="<tr>";
			$perm_list.=	"<td valign='top' align='center' colspan='6'><b>Standard Operations</b></td>"; 
			$perm_list.="</tr>";
			
			//standard operations...
			$perm_list.="<tr>";
			for($i=0; $i < count($standard_ops); $i++)
			{
				if($i%6==0 && $i > 0)
				{
					$perm_list.="</tr><tr>";	
				}
				
				$action=trim(strtolower($standard_ops[ $i ]));
				$action=str_replace(" ","_",$action);
				     			
     			$get_valid=get_access_value($row['id'],0,0,$action);					//level,user,template-item,action
				if($get_valid < 0)	
				{
					add_access_value($row['id'],0,0,$action);		//level,user,template-item,action
					$get_valid=0;
				}
				
     			$perm_list.=	"<td valign='top' align='right'><label for='".$action."'>".$standard_ops[ $i ]."</label> <input type='checkbox' name='".$action."' id='".$action."' value='1'".($get_valid > 0 ? " checked" : "")." class='standard_ops'></td>";    			
			}
			$perm_list.="</tr>";			
			
			//template sections...
			$perm_list.="<tr>";
			$perm_list.=	"<td valign='top' align='center' colspan='6'>&nbsp;<br><b>Document Access</b><br>&nbsp;</td>"; 
			$perm_list.="</tr>";
			
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
     			order by template_items.template_id asc,
     				template_items.zorder asc,
     				template_items.item_label asc
     				
     		";
     		$datat=simple_query($sqlt);
     		while($rowt=mysqli_fetch_array($datat))	
			{
				if($last_template!=trim($rowt['template_name']))	
				{
					if(trim($rowt['template_name'])=="")		$rowt['template_name']="(Template ".$rowt['template_id'].")";
										
					if($last_template!="")
					{
						$perm_list.="<tr>";
						$perm_list.=	"<td valign='top' align='center' colspan='6'>&nbsp;</td>"; 
						$perm_list.="</tr>";	
					}
					
					$perm_list.="<tr>";				
					$perm_list.=	"<td valign='top'><b>".trim($rowt['template_name'])."</b></td>";
					$perm_list.=	"<td valign='top' colspan='4'>&nbsp;</td>";
					$perm_list.=	"<td valign='top'><input type='button' class='buttonize btn btn-default add_new_btn' onclick='toggle_template(".$rowt['template_id'].");' value='Toggle'></td>";
					$perm_list.="</tr>";
					$perm_list.="<tr class='access_editor_hdr'>";				
					$perm_list.=	"<td valign='top' class='access_editor_cell'><b>&nbsp;</b></td>";				
					$perm_list.=	"<td valign='top' class='access_editor_cell'><b>Sort Order</b></td>";
					$perm_list.=	"<td valign='top' class='access_editor_cell'><b>Document Type</b></td>";
					$perm_list.=	"<td valign='top' class='access_editor_cell'><b>Tool Tip</b></td>";
					$perm_list.=	"<td valign='top' class='access_editor_cell'><b>Min Access</b></td>";				
					$perm_list.=	"<td valign='top' class='access_editor_cell'><b>Allow View</b></td>";
					$perm_list.="</tr>";
				}
				$last_template=trim($rowt['template_name']);
								     			
     			$get_valid=get_access_value($row['id'],0,$rowt['id'],'view_template_item');	//level,user,template-item,action
				if($get_valid < 0)	
				{
					add_access_value($row['id'],0,$rowt['id'],'view_template_item');			//level,user,template-item,action
					$get_valid=0;
				}
								
				$perm_list.="<tr>";				
				$perm_list.=	"<td valign='top'>&nbsp;</td>";				
				$perm_list.=	"<td valign='top'>".trim($rowt['zorder'])."</td>";
				$perm_list.=	"<td valign='top'>".trim($rowt['item_label'])."</td>";
				$perm_list.=	"<td valign='top'>".trim($rowt['title_text'])."</td>";
				$perm_list.=	"<td valign='top'>".trim($rowt['min_access'])."</td>";				
				$perm_list.=	"<td valign='top'>
								<label for='view_template_item_".$icntr."'>View Documents</label> 
								<input type='checkbox' name='view_template_item_".$icntr."' id='view_template_item_".$icntr."' value='".$rowt['id']."'".($get_valid > 0 ? " checked" : "")." class='template_item_views template_".$rowt['template_id']."_viewer'>
							</td>";
				$perm_list.="</tr>";
				$icntr++;
			}
			
			$perm_list.="</table>";		//<input type='hidden' name='tot_temp_items' id='tot_temp_items' value='".$icntr."'>
		}
	}	
	
	$level_form.="
		<div class='mrr_sector_container'>
			<input type='hidden' name='level_id' id='level_id' value='".$_GET['id']."'>
			<table cellpadding='0' cellspacing='0' border='0' style='width:100%'>			
			<tbody>	
			<tr>					
				<td valign='top'>
					<span class='template_pg_name'><b>Level Name</b></span>
				</td>
				<td valign='top'>
					<input name='level_name' id='level_name' value=\"".$namer."\" class='long tooltipx' title='Enter the label you want to identify this access level.'>
				</td>
			</tr>
			<tr>					
				<td valign='top'>
					<label for='level_archived' class='template_pg_label'><b>Archived</b></label>
				</td>
				<td valign='top'>
					<input type='checkbox' name='level_archived' id='level_archived' value='1'".($arch > 0 ? " checked" : "").">
				</td>
			</tr>		
			<tr>					
				<td valign='top'>&nbsp;</td>
				<td valign='top'>
					<input type='button' class='buttonize btn btn-default add_new_btn' onclick='save_level();' value='Save Level'>
				</td>
			</tr>			
			<tr>					
				<td valign='top' colspan='2'>".$perm_list."</td>
			</tr>						
			</tbody>
			</table>			
		</div>
	";
	/*
			<tr>					
				<td valign='top' colspan='2'>
					<div id='cur_access_levels'></div>
				</td>
			</tr>
	*/	
}
?>
<? if($valid_access) {?>
     <div class="column"  style='width:100%'>
       <div class="portlet">
         <div class="portlet-header">Standard Access Levels</div>
         <div class="portlet-content"><?=$level_list ?></div>
       </div>
       
       <div class="portlet">
         <div class="portlet-header">Access Level</div>
         <div class="portlet-content"><?=$level_form ?></div>
       </div>
     </div>    
     <div class='clear'></div>
<? } else { ?>
	<br><input type='button' class='buttonize' onclick='go_home()' value='Home'><br><hr><br>&nbsp;<br>
	
	<h2>Sorry, you are not allowed to view this page.  </h2>
	
	<br><hr><br><input type='button' class='buttonize' onclick='go_home()' value='Home'><br>
<? } ?>

<script>	
	var cur_id=<?= $_GET['id'] ?>;
	var doc_pg=0;				//used for search results display...access manager page should always be 0.
	function go_back(id)
	{
		if(cur_id!=id && cur_id > 1 && id > 0)		id=cur_id;		
		
		window.location.href = "/access_manager.php?id="+id+"";
	}
	
	$().ready(function() {	
		$('.buttonize').button();
			
     });
     
     function toggle_template(id)
     {
     	//toggle each item in the this template (ID)
     	$('.template_'+id+'_viewer').each(function() {
  			  			
  			if($(this).is(':checked')==true)
  			{
  				$(this).prop('checked', false);		//.attr('checked', false);
  			}
  			else
  			{
  				$(this).prop('checked', true);		//.attr('checked', true); 
  			}
		});
     }
     
     function save_access_level_items()
     {
     	lister="<div style='width:400px;'>";
     	
     	//find each standard operation on the page and see if checkbox is on.
     	$(".template_item_views").each(function() {
  			
  			itemid=$(this).val(); 	
  			stater=$(this).is(':checked');
  			  					
  			if(stater==true || stater=="true")		lister= lister + "<br>View Template Item "+itemid+": Status="+stater+".";
  			
  			
  			$.ajax({
          			url: "ajax.php?cmd=save_level_option",
          			data: {
          				"level_id":cur_id,
          				"user_id":0,
          				"template_id":itemid,          				
          				"name":'view_template_item',
     	   				"state":stater,
     	   				"value":1     				
          				},
          			type: "POST",
          			cache:false,
          			async:false,
          			dataType: "xml",
          			error: function() {
     					msgbox("General error updating Template Item Access Level Permision "+namer+". Please try again.");
     				},	
          			success: function(xml) {
          				
          				if($(xml).find('rslt').text() == '0')	
               			{          				
               				msgbox("General error updating Template Item Access Level Permission "+namer+". Please try again.");
               			}
               			else
               			{	
               				//show_notice('Template Item Access Level Permission has been saved.');
               			}	    				
          			}
          	});
          	/**/
		});
		
		lister= lister + "</div>";
		
		//msgbox("<b>Template Items Permissions:</b>"+lister+"<br>");	
     }
     
     function save_access_level_ops()
     {
     	lister="<div style='width:400px;'>";
     	
     	//find each standard operation on the page and see if checkbox is on.
     	$(".standard_ops").each(function() {
  			
  			namer=$(this).attr('id'); 
  			stater=$(this).is(':checked');	
  			valuer=$(this).val(); 			
  			
  			lister= lister + "<br>"+namer+": Status="+stater+".  Value is "+valuer+".";
  			
  			$.ajax({
          			url: "ajax.php?cmd=save_level_option",
          			data: {
          				"level_id":cur_id,
          				"user_id":0,
          				"template_id":0,          				
          				"name":namer,
     	   				"state":stater,
     	   				"value":valuer     				
          				},
          			type: "POST",
          			cache:false,
          			async:false,
          			dataType: "xml",
          			error: function() {
     					msgbox("General error updating Access Level Permision "+namer+". Please try again.");
     				},	
          			success: function(xml) {
          				
          				if($(xml).find('rslt').text() == '0')	
               			{          				
               				msgbox("General error updating Access Level Permission "+namer+". Please try again.");
               			}
               			else
               			{	
               				//show_notice('Access Level Permission has been saved.');
               			}	    				
          			}
          	});
		});
		
		lister= lister + "</div>";
		
		//msgbox("<b>Standard Ops Permissions:</b>"+lister+"<br>");
     }
     
	function save_level()
     {
     	id=$('#level_id').val();
     	//if(id==0)		id=cur_id;
     	
     	level=$('#level_name').val();
     	
     	arch = ($('#level_archived').is(':checked')  ? 1 : 0);
     	
     	$.ajax({
     			url: "ajax.php?cmd=save_level",
     			data: {
     				"id":id,
	   				"level":level,
	   				"archived":arch     				
     				},
     			type: "POST",
     			cache:false,
     			dataType: "xml",
     			error: function() {
					msgbox("General error updating Access Level. Please try again.");
				},	
     			success: function(xml) {
     				
     				if($(xml).find('rslt').text() == '0')	
          			{          				
          				msgbox("General error updating Access Level. Please try again.");
          			}
          			else
          			{
          				totres=1;          				
          				id=$(xml).find('rslt').text();
          				
          				save_access_level_ops();
          				save_access_level_items();
          				         					
          				show_notice('Access Level has been saved.');
          				if(totres > 0)	go_back(id);   	
          			}	    				
     			}
     	});
     }
     
	/*
	var cur_id=<?= $_GET['id'] ?>;
		
	$().ready(function() {	
		$('.buttonize').button();
		fetch_template_items();	
     });    
	   
     
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
     */
</script>
<? include('footer.php'); ?>