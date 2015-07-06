<? include('application.php') ?>
<div style='font-family:arial;border:1px black solid;background-color:#ecf1ff;padding:10px;width:700px;text-align:center;margin:0 auto'>
	<h2 style='margin-top:0'><?=$defaultsarray['company_name']?> - Attachment Viewer</h2>
	<?
		$tmp_dir = "temp";
	
		if(!file_exists($tmp_dir)) 		mkdir($tmp_dir);		
	
		if(!isset($_GET['id'])) 			die("<span style='color:red'>Error</span>: You have reached this page incorrectly");
			
		$sql = "
			select *
			
			from log_email
			where uuid = '".sql_friendly($_GET['id'])."'
		";
		$data = simple_query($sql);
		
		if(!mysqli_num_rows($data)) 		die("<span style='color:red'>Error</span>: Unable to locate message");
				
		$row = mysqli_fetch_array($data);
		
		if(!file_exists($row['attachment'])) 		die("<span style='color:red'>Error</span>: Attachment no longer exists (attachments are 'expired' after 45 days)");
				
		$sql = "
			insert into log_email_views
				(email_id,
				linedate_viewed,
				ip_address)
				
			values ($row[id],
				now(),
				'".sql_friendly($_SERVER['REMOTE_ADDR'])."')
		";
		simple_query($sql);
		
		if($row['linedate_viewed'] == "0000-00-00 00:00:00") 
		{
			$sql = "
				update log_email
				set linedate_viewed = now()
				where id = '$row[id]'
			";
			simple_query($sql);
		}
		
		if($row['tmp_filename'] == '') 
		{
			$file_uuid = createuuid();
			$file_ext = get_file_ext($row['attachment']);
			
			$tmp_filename = "$file_uuid.$file_ext";
			
			$sql = "
				update log_email
				set tmp_filename = '".sql_friendly($tmp_filename)."'
				where id = '".sql_friendly($row['id'])."'
			";
			simple_query($sql);
			
		} 
		else 
		{
			$tmp_filename = $row['tmp_filename'];
		}		
		copy($row['attachment'], $tmp_dir.'/'.$tmp_filename);
	?>	
	This E-mail was originally sent to: <span style='color:blue'><?=$row['email_to']?></span><br>
	Sent on <span style='color:red'><?=date("M j, Y", strtotime($row['linedate_sent']))?></span>
	at <span style='color:red'><?=date("h:i a", strtotime($row['linedate_sent']))?></span>	
	<?
		if($row['section_id'] > 0) 
		{
			echo "
				<br><br>
				Subject: <b>$row[subject]</b>
			";
		}
		if($row['email_notes'] != '') 
		{
			echo "
				<br><br>
				<div style='border:1px #aaa solid;width:600px;padding:10px;text-align:left;margin:0 auto'>
					Additional Notes: $row[email_notes]
				</div>
			";
		}
	?>	
	<br><br>	
	<a href='<?=$tmp_dir?>/<?=$tmp_filename?>'>Click here</a> to view your attachment	
	<br><br>
	<a href='<?=$tmp_dir?>/<?=$tmp_filename?>'><?=get_filename($row['attachment'])?></a>
</div>