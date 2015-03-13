<? include_once('application.php')?>
<?
	//log_page_load();

	if(isset($_GET['timeout'])) $_GET['out'] = 1;
	
	//$mrr_cookie_bake is defined in appication.php ...
	if(isset($_GET['out'])) 
	{
		//session_destroy();		
		unset($_COOKIE['uuid']);
		setcookie("uuid", 'novalue', $mrr_cookie_bake, "/");	//reset the cookie with 60 seconds
		
		unset($_COOKIE['user']);
		setcookie("user", '0', $mrr_cookie_bake, "/");		//reset the cookie with 60 seconds
	}
	
	confirm_secure_page();
	
	$error = '';
	$error_email = '';
	$email_success = 0;
	
	if(!isset($defaultsarray))
	{
		/* load any default vars specified in the database */
     	$sql = "
     		select xname,
     			xvalue     		
     		from defaults
     	";
     	$data_defaults = mysql_query($sql,$datasource);
     	while($row_defaults = mysql_fetch_array($data_defaults)) 
     	{
     		$defaultsarray[$row_defaults['xname']] = $row_defaults['xvalue'];
     	}						
	}	
	
	if(isset($_POST['username']))
	{			
		$sql = "select users.*					
			from users				
			where username = '" . sql_friendly($_POST['username']) . "'
				and password = '" . sql_friendly($_POST['pword']) . "'
				and users.archived = 0 and users.deleted = 0
		";			
		$data = simple_query($sql);
		$row = mysql_fetch_array($data);						
		if(is_array($row))
		{
			// be sure to put any additional SESSION variables on the order_review "successful login" section
			$_SESSION['user_id'] = $row['id'];			
			$_SESSION['username'] = $row['username'];
			$_SESSION['access_level'] = $row['access_level'];
			$invalid_password = '';
			$use_userid = $row['id'];
			
			setcookie("user", $_SESSION['user_id'], $mrr_cookie_bake);	//reset the cookie with new expiration date	
			setcookie("uuid", $mrr_cookie, $mrr_cookie_bake);		//reset the cookie with new expiration date		
		}
		else
		{
			$error = $lang['login_error0'];
			$invalid_password = $_POST['pword'];
			$use_userid = 0;
			$use_location=0;
			
			unset($_COOKIE['uuid']);
			setcookie("uuid", 'novalue', $mrr_cookie_bake);		//reset the cookie with 60 seconds	
			
			unset($_COOKIE['user']);
			setcookie("user", '0', $mrr_cookie_bake);			//reset the cookie with 60 seconds	
		}
		
		$sql = "
			insert into log_login
				(user_id,
				username,
				ip_address,
				linedate_added,
				invalid_password)
				
			values ('".sql_friendly($use_userid)."',
				'".sql_friendly($_POST['username'])."',
				'".sql_friendly($_SERVER['REMOTE_ADDR'])."',
				now(),
				'".sql_friendly($invalid_password)."')
		";
		simple_query($sql);
		
		if($error == '')
		{
			if(isset($_GET['redirect']))
			{
		
				if(isset($_GET['querystring']))
				{									
					$uQueryString = str_replace('!','&',$_GET['querystring']);									
													
					header("Location: ". $_GET['redirect'] . "?" . $uQueryString);
					exit;
				}
				else
				{
					header("Location: ". $_GET['redirect']);
					exit;									
				}
			}
			else
			{					
				unset($_SESSION['admin']);
				header("Location: index.php");
				exit;
			}	
			$mrr_cookie=createuuid();								
		}
	}
		
		
	if($error == '' and isset($_GET['error']))
	{
		if($_GET['error'] == 1) $error = $lang['login_error1'];
		if($_GET['error'] == 2) $error = $lang['login_error2'];
		if($_GET['error'] == 3) $error = $lang['login_error3'];
	}		
		
	if(!isset($_POST['email'])) $_POST['email'] = "";
?>
<? include('header.php')?>
<center>
<table height='100%'>
<tr>
	<td>
		<center>
		<table class='standard12'>
			<tr>
				<td valign='top' width='300'>

					<script type='text/javascript'>
						$(document).ready(function() 
						    { 
						    		$('#username').focus();
 
						    }
						);
					</script>
					
					<?
					$use_qs = query_string_remove($query_string,"out");
					$use_qs = query_string_remove($query_string,"timeout")
					?>
					<form action="<?=$_SERVER['SCRIPT_NAME']?>?<?=$use_qs?>" method="post">
          			<table class='standard12' border='0'>	
          			<tr>
          				
          				<td valign='top'>
          					<table class='standard12' border='0'>					
          						<tr>
          							<td colspan='3' nowrap>
          							<? if(isset($_GET['timeout'])) { ?>
          								<span class='alert heading'><?=$lang['login_timeout']?></span>
          							<? } ?>
          							</td>
          						</tr>
          						<tr>
          							<td colspan='3' align='center'>
          								<font class='standard18'>
          									<b><?=$lang['login_header']?></b>
          								</font>
          							</td>
          						</tr>
          						<tr>
          							<td colspan='3'>
          								<hr>
          							</td>
          						</tr>
          						<tr>
          							<td rowspan='3'><img src='images/login.png'></td>
          							<td><?=$lang['login_user']?></td>
          							<td><input name='username' id='username' class='standard12' size='40'></td>
          						</tr>
          						<tr>
          							<td><?=$lang['login_pass']?></td>
          							<td><input name='pword' class='standard12' type='password' size='40'></td>
          						</tr>
          						
          						<tr><td colspan='3'>&nbsp;</td></tr>
          						<tr><td colspan='3'><hr></td></tr>
          						<tr>
          							<td colspan='3' align='right'>
          								<input type="submit" value="<?=$lang['login_button']?>" class='standard12'>
          							</td>
          						</tr>				
          					</table>
          				</td>	
          			</tr>	
          			
          					
          			</table>
					</form>
					
					<table class='standard12' id='forgot_password' style='display:none'>
						<tr>
							<td colspan='2'>
								<br><br>
								<font class='standard12' color='red'><b>
								<?=$error_email ?>
								</b></font>
							</td>
						</tr>
						<? if($email_success == 1) { ?>
							<tr>
								<td colspan='2'>
									<b><?=$lang['login_emailed']?>'<u><?=$_POST['email']?>.</u>'</b>
								</td>
							</tr>
						<? } else { ?>
							<? if(isset($_GET['email']) && (!isset($_POST['email']) || $_POST['email'] == '')) $_POST['email'] = $_GET['email']; ?>
							<form action="<?=$_SERVER['SCRIPT_NAME']?>?<?=$query_string?>" method="post">
							<tr>
								<td colspan='2' align='right'><font class='standard18'><b><i><?=$lang['login_forgot']?><hr></td>
							</tr>
							<tr>
								<td colspan='2'>
									<i><?=$lang['login_emailer']?></i>
								</td>
							</tr>
							<tr>
								<td nowrap><?=$lang['login_email']?></td>
								<td><input name='email_forgot' class='standard12' id="id_email_forgot" value="<?=$_POST['email']?>"></td>
							</tr>
							<tr><td colspan='2'><hr></td></tr>
							<tr>
								<td colspan='2' align='right'>
									<input type="submit" value="<?=$lang['login_send']?>" class='standard12'>
								</td>
							</tr>
							</form>
						<? } ?>
					</table>					
					<br>					
					<?=$error ?>
				</td>
			</tr>
		</table>
		</center>
</td></tr>
</table>
<script type='text/javascript'>
	function forgot_password() {
		$('#forgot_password').toggle('slow');
		$('#id_email_forgot').focus();
	}
</script>
<? include('footer.php') ?>