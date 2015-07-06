<? include('application.php') ?>
<?

// run every three hours from the CF scheduler on the s10 server (portal.cce - maint)

if(!isset($_GET['connect_key']) || $_GET['connect_key'] != 'bas82bad98fqhbnwga8shq34908asdhbn') {
	die("You have reached this page incorrectly.");
}

//reminders for important dates
$found_requests="<b>Reminder 1:</b><br>";

$From="reminders@capitalcomplianceexperts.com";
$FromName="Reminders";

//reminder 1
$sql="
	select * 
	from important_dates 
	where deleted=0
		and sent_reminder1=0
		and email_reminder1!=''
		and linedate_reminder1<='".date("Y-m-d",time())." 23:59:59'
		
	order by linedate_reminder1 asc
";	//and linedate_reminder1>='".date("Y-m-d",time())." 00:00:00'  
$data=simple_query($sql);	
while($row = mysqli_fetch_array($data))
{	
	$date=date("M j, Y",strtotime($row['linedate']));
	$sub=trim($row['date_description']);	
	
	$remind=date("M j, Y",strtotime($row['linedate_reminder1']));
	$email=trim($row['email_reminder1']);
	$message=trim($row['msg_reminder1']);
	
	//$email="michael@sherrodcomputers.com";
	
	$sqlu="update important_dates set sent_reminder1=1 where id='".sql_friendly($row['id'])."'";
	simple_query($sqlu);
		
	$found_requests.="<br>To: ".$email.". Date: ".$date.". Topic: ".$sub.". Reminder Date: ".$remind.". Msg: ".$message.".";	
	sendMail($From,$FromName,$email,$email,$sub,strip_tags($message),"<b>".$sub."</b><br><br>".$message,'', '','') ;	
}


$found_requests.="<br><br><b>Reminder 2:</b><br>";
//reminder 2
$sql="
	select * 
	from important_dates 
	where deleted=0
		and sent_reminder2=0
		and email_reminder2!=''
		and linedate_reminder2<='".date("Y-m-d",time())." 23:59:59'
		
	order by linedate_reminder2 asc
";	//and linedate_reminder2>='".date("Y-m-d",time())." 00:00:00'  
$data=simple_query($sql);	
while($row = mysqli_fetch_array($data))
{	
	$date=date("M j, Y",strtotime($row['linedate']));
	$sub=trim($row['date_description']);	
	
	$remind=date("M j, Y",strtotime($row['linedate_reminder2']));
	$email=trim($row['email_reminder2']);
	$message=trim($row['msg_reminder2']);
	
	//$email="michael@sherrodcomputers.com";
	
	$sqlu="update important_dates set sent_reminder2=1 where id='".sql_friendly($row['id'])."'";
	simple_query($sqlu);
	
	$found_requests.="<br>To: ".$email.". Date: ".$date.". Topic: ".$sub.". Reminder Date: ".$remind.". Msg: ".$message.".";
	sendMail($From,$FromName,$email,$email,$sub,strip_tags($message),"<b>".$sub."</b><br><br>".$message,'', '','') ;	
}

echo '<br><b>Email Reminders Sent:</b> <br>'.$found_requests.'.<br>';


//Remove older Temp Files....
echo '<br><b>Purging /TEMP/ Files</b><br>';
$path = "".$defaultsarray['base_path']."public_html/temp/";	
if($handle = opendir($path)) 
{
     while(false !== ($file = readdir($handle))) 
     {
        	if((time()-filectime($path.$file)) > (86400 * 1)) 
        	{  
           	if(trim($file)!="" && trim($file)!="." && trim($file)!="..")
               {                	
                	echo "<br>Date: ".date("Y-m-d H:i:s",filectime($path.$file)).", File Found: ".$path."".$file."";
                	if(unlink($path.$file))
                	{
                		echo " -- <span style='color:#0000CC;'>File Deleted.</span>";	
                	}
                	else
                	{
                		echo " -- <span style='color:#CC0000;'>ERROR DELETING FILE!!!</span>";	
                	}
          	}
        	}
     }
}
?>