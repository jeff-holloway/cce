<?
     $use_title="Report - User Log";     
     
     include('header.php'); 
     
     $valid_access=check_access($page_name);
     
     echo "<h2>Capital Compliance Experts User Log Report</h2>";	
     
	handle_quick_dates();
	
	//$filter_list="Coming Soon";	
	$filters="";
	
	$result_list="
		<table class='tablesorterx'>
     		<thead>
     		<tr>	
     			<th width='150'>Date</th>
     			<th width='150'>Note</th>     			
     			<th>First Name</th>
     			<th>Last Name</th>
     			<th>User Name</th>
     			<th>IP Address</th>
     		</tr>
     		</thead>
     		<tbody>
	";
	$cntr=0;
	
	if($_POST['report_filter_user']!="")		$filters.=" and (users.username like '%".sql_friendly($_POST['report_filter_user'])."%' or CONCAT(users.first_name, ' ', users.last_name) like '%".sql_friendly($_POST['report_filter_user'])."%')";
	
	if($_POST['date_from']!="")				$filters.=" and log_login.linedate_added>='".date("Y-m-d",strtotime($_POST['date_from']))." 00:00:00'";
	if($_POST['date_to']!="")				$filters.=" and log_login.linedate_added<='".date("Y-m-d",strtotime($_POST['date_to']))." 23:59:59'";
	
	$sql = "
		select log_login.*,
			users.first_name,
			users.last_name,
			users.username
			
		from log_login
			left join users on users.id=log_login.user_id
		where users.access_level <= '".sql_friendly($_SESSION['view_access_level'])."'
			".$filters."
		order by linedate_added desc,id desc
		limit 100
		";
	$data = simple_query($sql);          
     while($row = mysqli_fetch_array($data))
     {
     	$result_list.="
     		<tr class='".($cntr%2==0 ? "even" : "odd")."'>
     			<td valign='top' nowrap>".date("m/d/Y H:i", strtotime($row['linedate_added']))."</td>
     			<td valign='top'>".(trim($row['invalid_password'])!="" ? "<span class='alert'>Failed Login</span>" : "")."</td>     			
     			<td valign='top'>".$row['first_name']."</td>
     			<td valign='top'>".$row['last_name']."</td>
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