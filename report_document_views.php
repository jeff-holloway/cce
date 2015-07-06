<?
     $use_title="Report - Document Views";     
     
     include('header.php'); 
     
     $valid_access=check_access($page_name);
     
     echo "<h2>Capital Compliance Experts Document Views Report</h2>";	
     
	handle_quick_dates();
	
	//$filter_list="Coming Soon";	
	$filters="";
	
	$result_list="
		<table class='tablesorterx'>
     		<thead>
     		<tr>	
     			<th width='150'>Viewed</th>
     			<th>Public/File Name</th>     			
     			<th width='150'>Name</th>
     			<th width='100'>User Name</th>
     			<th width='100'>IP Address</th>
     		</tr>
     		</thead>
     		<tbody>
	";
	$cntr=0;
	
	if($_POST['report_filter_user']!="")		$filters.=" and (users.username like '%".sql_friendly($_POST['report_filter_user'])."%' or CONCAT(users.first_name, ' ', users.last_name) like '%".sql_friendly($_POST['report_filter_user'])."%')";
	
	if($_POST['date_from']!="")				$filters.=" and log_email_views.linedate_viewed>='".date("Y-m-d",strtotime($_POST['date_from']))." 00:00:00'";
	if($_POST['date_to']!="")				$filters.=" and log_email_views.linedate_viewed<='".date("Y-m-d",strtotime($_POST['date_to']))." 23:59:59'";
	
	$sql = "
		select log_email_views.*,
			attached_files.filename,
			attached_files.public_name,
			users.first_name,
			users.last_name,
			users.username
			
		from log_email_views
			left join attached_files on attached_files.id=log_email_views.file_id
			left join users on users.id=log_email_views.user_id
		where attached_files.deleted=0
			and attached_files.access_level <= '".sql_friendly($_SESSION['view_access_level'])."'
			".$filters."
		order by log_email_views.linedate_viewed desc,id desc
		".(trim($_POST['report_filter_user'])!=""  ? "" : "limit 100")."
		";
	$data = simple_query($sql);          
     while($row = mysqli_fetch_array($data))
     {
     	$result_list.="
     		<tr class='".($cntr%2==0 ? "even" : "odd")."'>
     			<td valign='top' nowrap>".date("m/d/Y H:i", strtotime($row['linedate_viewed']))."</td>
     			<td valign='top'>".($row['public_name']!="" ?  $row['public_name'] : $row['filename'] )."</td>     			
     			<td valign='top'>".$row['first_name']." ".$row['last_name']."</td>
     			<td valign='top'>".$row['username']."</td>  
     			 <td valign='top'>".$row['ip_address']."</td> 			
     		</tr>";	
     		
     	$cntr++;
     }
	$result_list.="
			<tr>
     			<td valign='top' colspan='5'><b>".$cntr."</b> Results Found</td>
     		</tr>
		</tbody>
		</table>
	";
?>
<? if($valid_access) {?>
     
     <div class="column" style='width:100%;'>
       <div class="portlet">
         <div class="portlet-header">Filters</div>
         <div class="portlet-content">
         		<?
         		$rfilter=new report_filter();
			$rfilter->show_date_range			= true;
			$rfilter->show_user_filter			= true;
			//$rfilter->show_font_size			= true;
			$rfilter->show_filter();
         		?>	
         </div>
       </div>
       <div class="portlet"><!-- no_collapse  default_closed -->
         <div class="portlet-header">Results</div>
         <div class="portlet-content"><?=$result_list ?></div>
       </div>
     </div>
     <div class='clear'></div>
<? } else { ?>	
	<h2>Sorry, you are not allowed to view this page.  </h2>
<? } ?>

<script>		
	$().ready(function() {	
		//$('.buttonize').button();
		//$('.datepicker').datepicker();
		//$('#report_filter_user').autocomplete('ajax.php?cmd=search_users',{formatItem:formatItem});
     });     
</script>
<? include('footer.php'); ?>