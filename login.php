<? include_once('application.php')?>
<?

//var_dump($_SESSION);
	//log_page_load();
	if(isset($_GET['timeout'])) $_GET['out'] = 1;
		
	//LOGOUT Process
	//$mrr_cookie_bake is defined in appication.php ...

	
	$error = '';
	
	// handle the password reset
	if(isset($_POST['reset_uuid'])) {
		// make sure the reset link used is valid
		$sql = "
			select urp.*,
				u.username
			
			from users_reset_pass urp
				inner join users u on u.id = urp.user_id
			where urp.uuid = '".sql_friendly($_POST['reset_uuid'])."'
				and urp.deleted = 0
		";
		$data_reset = simple_query($sql);
		//$error = mrr_reset_acct_pass($try_id,$try_user);
		
		if(!mysqli_num_rows($data_reset)) {
			$error = "Invalid password reset link";
		} else {
			$row_reset = mysqli_fetch_array($data_reset);
			
			if(strtotime($row_reset['linedate_used']) > 0) {
				$error = "This password reset link has already been used";
			}
			
			if(strtotime($row_reset['linedate_added']) < strtotime("-3 hour", time())) {
				$error = "This password reset link has expired";
			}
			

		}
		

		if($error == '' && isset($_POST['reset_password'])) {
			$sql = "
				update users_reset_pass
				set linedate_used = now()
				where uuid = '".sql_friendly($reset_uuid['reset'])."'
			";
			simple_query($sql);
			
			mrr_update_account_pass($row_reset['user_id'], $row_reset['username'], $_POST['reset_password'], $_POST['reset_password_confirm']);
			header('Location: login.php?resetpassdone=1');
			die;
		}

	}

	
	confirm_secure_page();	
	
	$error_email = '';
	$error_new='';
	$email_success = 0;
	$new_success = 0;
		
	$delay_failed_login=(int) $defaultsarray['failed_login_delay'];
	$delay_failed_attempts=(int) $defaultsarray['failed_login_attempts'];
	
	if(isset($_POST['create_login_info']))
	{	//create new account with low access level...for free tools section.
		$e_addr=trim($_POST['new_email']);
		$user=trim($_POST['new_username']);
		$pass=trim($_POST['new_pword']);
		
		$res=mrr_create_account($user,$pass,$e_addr);
		$error_new=$res['newid'];
		$newid=$res['msg'];
	}
	elseif(isset($_POST['email_login_info']))
	{	//Forgot Password process....
		
		$e_addr=trim($_POST['id_email_forgot']);		
		$error_email=mrr_reset_acct_pass_email($e_addr);
	}
	elseif(isset($_POST['username']))
	{	//login process...		
		
		$sql = "select users.*,	
				DATE_ADD(users.linedate_failed, INTERVAL ".$delay_failed_login." SECOND) as delayed_access,
				(select view_access from user_levels where user_levels.access_level=users.access_level order by id asc limit 1) as view_access_level
			from users				
			where username = '" . sql_friendly($_POST['username']) . "'
				and password = '" . sql_friendly(mrr_encryptor($_POST['pword'],$_POST['username'])) . "'
				and users.archived = 0 and users.deleted = 0
		";			
		$data = simple_query($sql);
		$row = mysqli_fetch_array($data);	
		
		$delay_blocker=0;
		
		if(isset($row['failed_logins']) && isset($row['delayed_access']))
		{
			if($row['failed_logins'] > $delay_failed_attempts)
			{
				if(date("Y-m-d H:i:s") > $row['delayed_access'])	$delay_blocker=1;
			}			
		}
								
		if(is_array($row) && $delay_blocker==0)
		{	//login and no delay from failures
			
			// be sure to put any additional SESSION variables on the order_review "successful login" section
			$_SESSION['user_id'] = $row['id'];			
			$_SESSION['username'] = $row['username'];
			$_SESSION['access_level'] = $row['access_level'];
			$_SESSION['view_access_level'] = 0;
			if($row['access_level'] > 0)			$_SESSION['view_access_level'] = $row['view_access_level'];
			
			$_SESSION['merchant_id']=$row['merchant_id'];
			$_SESSION['store_id']=$row['store_id'];
			
			$_SESSION['reset_password']=$row['reset_password'];	
			
			$_SESSION['special_merchant_id']=0;
			
			if($_SESSION['access_level'] == 70)
			{
				$_SESSION['selected_user_id']=0;				
				$_SESSION['selected_merchant_id']=$_SESSION['merchant_id'];
				$_SESSION['selected_store_id']=$_SESSION['store_id'];
				$_SESSION['merchant_id']=0;
				$_SESSION['store_id']=0;
			}
			elseif($_SESSION['access_level'] == 61 || $_SESSION['access_level'] == 60)
			{
				$_SESSION['selected_user_id']=0;				
				$_SESSION['selected_merchant_id']=$row['merchant_id'];
				$_SESSION['selected_store_id']=0;
				$_SESSION['merchant_id']=$row['merchant_id'];
				$_SESSION['store_id']=0;
				
				$_SESSION['special_merchant_id']=$row['merchant_id'];
			}
			else
			{
				//$_SESSION['selected_user_id']=$row['id'];	
				$_SESSION['selected_user_id']=0;
				$_SESSION['selected_merchant_id']=$_SESSION['merchant_id'];
				$_SESSION['selected_store_id']=$_SESSION['store_id'];
			}
			
			$_SESSION['selected_doc_type_id']=0;
			
			$invalid_password = '';
			$use_userid = $row['id'];
			
			setcookie("user", $_SESSION['user_id'], $mrr_cookie_bake);	//reset the cookie with new expiration date	
			setcookie("uuid", $mrr_cookie, $mrr_cookie_bake);		//reset the cookie with new expiration date		
			
			$sql="
				update users set 
					reset_password=0,
					linedate_failed='0000-00-00 00:00:00',
					failed_logins=0						
					
				where id='".sql_friendly($row['id'])."' 
					and username='".sql_friendly($_POST['username'])."'
			";
			simple_query($sql);
			
		}
		else
		{	//bad login or failure delay from bad login attempts.
			
			$sql="
				update users set 
					linedate_failed=NOW(),
					failed_logins=(failed_logins + 1)					
					
				where username='".sql_friendly($_POST['username'])."'
			";
			simple_query($sql);
			
			
			$error = $lang['login_error0'];
			$invalid_password = $_POST['pword'];
			$use_userid = 0;
			$use_location=0;
			
			unset($_COOKIE['uuid']);
			setcookie("uuid", 'novalue', $mrr_cookie_bake);		//reset the cookie with 60 seconds	
			
			unset($_COOKIE['user']);
			setcookie("user", '0', $mrr_cookie_bake);			//reset the cookie with 60 seconds	
		}
		
		mrr_add_login_attempt($use_userid,$_POST['username'],$invalid_password);
		
		if($error == '')
		{
			if(isset($_SESSION['reset_password']) && isset($_SESSION['user_id']))
			{
				if($_SESSION['reset_password'] > 0)
				{
					header("Location: index.php?id=".$_SESSION['user_id']."&force_reset=1");
					exit;	
				}	
			}			
			
			if(isset($_GET['redirect']))
			{		
				if(isset($_GET['querystring']))
				{									
					$uQueryString = str_replace('!','&',$_GET['querystring']);	
					
					if($uQueryString=="id=")		$uQueryString="";
													
													
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
		
	if($error == '' && isset($_GET['error']))
	{
		if($_GET['error'] == 1) $error = $lang['login_error1'];
		if($_GET['error'] == 2) $error = $lang['login_error2'];
		if($_GET['error'] == 3) $error = $lang['login_error3'];
	}		
		
	if(!isset($_POST['email'])) 			$_POST['email'] = "";
	if(!isset($_POST['id_email_forgot'])) 	$_POST['id_email_forgot'] = "";
	
?>
<? include('header.php')?>
	<? /* close out our header divs, since we're not using the standard content block for the login page */ ?>
	</div>
</div>

<? /* now, continue with normal code */ ?>

<style>
	.banner_top {display:none}
</style>

<center>
					<?
					$use_qs = query_string_remove($query_string,"out");
					$use_qs = query_string_remove($use_qs,"timeout");
					$use_qs = query_string_remove($use_qs,"resetpassdone");
					$use_qs = query_string_remove($use_qs,"reset");
					?>
					<div style='clear:both'></div>
					
					
					<div style='border:0px black solid;margin:0px 0;background-image:url(common/images/home-banner-1.jpg);background-repeat:no-repeat;padding-top:75px;background-size:100%;padding-bottom:100px'>
					
					
					
							<?
							//var_dump($_POST);
							if(isset($_GET['resetpassdone'])) $msg = "Reset password successful!";
							if(isset($_POST['id_email_forgot']) && $_POST['id_email_forgot'] != '') $msg = "Password reset link was sent to: " . $_POST['id_email_forgot'];

							
							if($error != '') echo "<div class='alert'>$error</div>";
							if(isset($msg) && $msg != '') echo "<div class='alert'>$msg</div>";

							if($error_email != '') {
								echo "<div class='alert'>$error_email</div>";
							} elseif($email_success == 1) {
								echo "<div class='alert_good'>".$lang['login_emailed']." <u>".$_POST['id_email_forgot']."</u></div>";
							}

							if($error_new != '') {
								echo "<div class='alert'>$error_new</div>";
							} elseif($new_success == 1) {
								echo "<div class='alert_good'>".$lang['login_created']." <u>".$_POST['new_username']."</u></div>";
							}
							
							if($email_success == 1) {
	     							echo "<b>$lang[login_emailed]'<u>$_POST[id_email_forgot]</u></b>";
	     					}
	     					

							?>
					
						<div class='accordion row-fluid login_boxes' style='max-width:400px;'>
							
							<? 
							//Password reset
							if(isset($_GET['reset']) && $error == '') {
	
								?>
								<h3>RESET PASSWORD - ENTER NEW PASSWORD</h3>
								<form name='reset_password_form' action="<?=$_SERVER['SCRIPT_NAME']?>?<?=$use_qs?>" onsubmit="if(!reset_password_action()) return false" method="post">
								<input type='hidden' name='reset_uuid' value="<?=$_GET['reset']?>">
								<div class='login_box'>
									
									
									
									<div class='field'>
										<label>PASSWORD</label>
										<span><input class='standard12' name='reset_password' id='reset_password' type='password'></span>
									</div>
									<div class='field'>
										<label>CONFIRM</label>
										<span><input class='standard12' name='reset_password_confirm' id='reset_password_confirm' type='password'></span>
									</div>
									
									<div class='field'>
										<label></label>
										<button class='btn btn-default add_new_btn'>RESET PASSWORD</button>
									</div>
									
									<script>
										$().ready(function() {
											$('#reset_password').focus();
										});
										
										function reset_password_action() {
											if($('#reset_password_confirm').val() != $('#reset_password').val()) {
												msgbox("The password and confirm passwords do not match");
												return false;
											}
											
											if($('#reset_password_confirm').val() == '' || $('#reset_password').val() == '') {
												msgbox("You must enter both the password and the confirm password");
												return false;
											}
											
											return true;
										}
									</script>
									
								</div>
								</form>
	
								
							<? } else { ?>
								<h3>LOGIN</h3>
								<form name='login_form' action="<?=$_SERVER['SCRIPT_NAME']?>?<?=$use_qs?>" method="post">
								<div class='login_box'>
									
									
									<div class='field'>
										<label>USERNAME</label>
										<span><input name='username' id='username' value="<?=(isset($_POST['username']) ? $_POST['username'] : "")?>"></span>
									</div>
									<div class='field'>
										<label>PASSWORD</label>
										<span><input name='pword' id='pword' type='password'></span>
									</div>
									<div class='field'>
										<label></label>
										<button class='btn btn-default add_new_btn' onclick='document.login_form.submit()'>LOGIN</button>
									</div>
									
									
								</div>
								</form>
								
								
								<h3>FORGOT PASSWORD</h3>
								<form name="forgot_password" action="<?=$_SERVER['SCRIPT_NAME']?>" method="post" onsubmit="if(!validateSubmit(1)) return false">
									<input type='hidden' name='email_login_info' value='1'>
								<div class='login_box'>
									
									<div class='notes'>
										Enter your e-mail address, and we will send you your login information.
									</div>
									<div class='field'>
										<label>EMAIL</label>
										<span><input class='standard12' name='id_email_forgot' id='id_email_forgot'></span>
									</div>
									<div class='field'>
										<label></label>
										<button class='btn btn-default add_new_btn' onclick='document.forgot_password.submit()'>SUBMIT</button>
									</div>
									<div style='clear:both'></div>
								</div>
								</form>
								<?
								/*
								<h3>CREATE ACCOUNT</h3>
								<form name="create_new_account" action="<?=$_SERVER['SCRIPT_NAME']?>" method="post" onsubmit="if(!validateSubmit(2)) return false;">
								<div class='login_box'>
									
									<div class='field'>
										<label>USERNAME</label>
										<span><input class='standard12' name='new_username' id='new_username'></span>
									</div>
									<div class='field'>
										<label>PASSWORD</label>
										<span><input class='standard12' name='new_pword' id='new_pword' type='password'></span>
									</div>
									<div class='field'>
										<label>EMAIL</label>
										<span><input class='standard12' name='new_email' id='new_email'></span>
									</div>
									<div class='field'>
										<label></label>
										<button name='create_login_info' id='create_login_info' class='btn btn-default add_new_btn' onclick='document.create_new_account.submit()'>CREATE NEW ACCOUNT</button>
									</div>
									<div style='clear:both'></div>
								</div>
								</form>
								*/
								?>
							<? } ?>
						</div>
					</div>
					
					<? /*
					<div class="accordion" style='max-width:550px'>
						<h3>Login</h3>
						<div>
							<?
							if($error != '') echo "<div class='alert'>$error</div>";	

							if($error_email != '') {
								echo "<div class='alert'>$error_email</div>";
							} elseif($email_success == 1) {
								echo "<div class='alert_good'>".$lang['login_emailed']." <u>".$_POST['id_email_forgot']."</u></div>";
							}

							if($error_new != '') {
								echo "<div class='alert'>$error_new</div>";
							} elseif($new_success == 1) {
								echo "<div class='alert_good'>".$lang['login_created']." <u>".$_POST['new_username']."</u></div>";
							}
							?>
							
		          			<table class='standard12' border='0' width='100%'>
		          			<tr>
		          				<td>
		          					<td rowspan='3'><img src='images/login.png'></td>
		          				</td>
		          				<td valign='top'>
		          					<form action="<?=$_SERVER['SCRIPT_NAME']?>?<?=$use_qs?>" method="post">
		          					<table class='standard12' border='0' style='width:100%;'>					
		          						<tr>
		          							<td colspan='3' nowrap>
		          							<? if(isset($_GET['timeout'])) { ?>
		          								<span class='alert heading'><?=$lang['login_timeout']?></span>
		          							<? } ?>
		          							</td>
		          						</tr>

		          						<tr>
		          							
		          							<td><?=$lang['login_user']?></td>
		          							<td><input class='standard12' name='username' id='username' style='width:100%'></td>
		          						</tr>
		          						<tr>
		          							<td><?=$lang['login_pass']?></td>
		          							<td><input class='standard12' name='pword' class='standard12' type='password' style='width:100%'></td>
		          						</tr>
		          						
	          						<tr>
		          							<td colspan='2' align='right'>
		          								<br>
		          								<input type="submit" value="<?=$lang['login_button']?>" class='standard12 btn btn-default add_new_btn' style='width:100%'>
		          							</td>
		          						</tr>				
		          					</table>
		          					</form>
		          				</td>
		          			</tr>
		          			</table>
		          		</div>
		          		
		          		<h3>Forgot Password</h3>
		          		<div>
	          			<table>
	          			<tr>
	          				<td valign='top'>
	          					<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post" onsubmit="if(!validateSubmit(1)) return false">
	     						<table class='standard12' id='forgot_password' style='width:100%;'>
	     						<tr>
	     							<td colspan='2'><br><br><font class='standard12' color='red'><b><?=$error_email ?></b></font></td>
	     						</tr>
	     						<? if($email_success == 1) { ?>
	     							<tr>
	     								<td colspan='2'><b><?=$lang['login_emailed']?>'<u><?=$_POST['id_email_forgot']?>.</u>'</b></td>
	     							</tr>
	     						<? } else { ?>
	     							
	     							<? 
	     							//if(isset($_GET['email']) && (!isset($_POST['email']) || $_POST['email'] == '')) 		$_POST['email'] = $_GET['email']; 
	     							if(!isset($_POST['id_email_forgot']))		$_POST['id_email_forgot']="";
	     							//if(isset($_POST['email'])
	     							?>
	     							
	     							<tr>
	     								<td colspan='2' align='right'><font class='standard18'><b><i><?=$lang['login_forgot']?><hr></td>
	     							</tr>
	     							<tr>
	     								<td colspan='2'><i><?=$lang['login_emailer']?></i></td>
	     							</tr>
	     							<tr>
	     								<td nowrap><?=$lang['login_email']?></td>
	     								<td><input class='standard12' size='40'  name='id_email_forgot' id="id_email_forgot" value="<?=$_POST['id_email_forgot']?>" placeholder='Enter E-mail Address'></td>
	     							</tr>
	     							<tr><td colspan='2'><hr></td></tr>
	     							<tr>
	     								<td colspan='2' align='right'><input type="submit" value="<?=$lang['login_send']?>" name='email_login_info' id='email_login_info' class='standard12 btn btn-default add_new_btn'></td>
	     							</tr>							
	     						<? } ?>
	     						</table>	
	     						<div id="dialog_forgot" style='display:none;'>
		 							 <p>Please enter the E-mail address for your account to send the login information.</p>
								</div>
	     						</form>	
	          				</td>	
	          			</tr>
	          			</table>
	          			</div>
	          			
	          			<h3>Create Account</h3>
	          			<div>
	          			<table>
	          			<tr>
	          				<td valign='top'>
	          					<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post" onsubmit="if(!validateSubmit(2)) return false;">
	     						<table class='standard12' id='new_account' style='width:100%;'>
	     						<tr>
	     							<td colspan='2'><br><br><font class='standard12' color='red'><b><?=$error_new ?></b></font></td>
	     						</tr>
	     						<? if($new_success == 1) { ?>
	     							<tr>
	     								<td colspan='2'><b><?=$lang['login_created']?>'<u><?=$_POST['new_username']?>.</u>'</b></td>
	     							</tr>
	     						<? } else { ?>
	     							
	     							<? 
	     							if(!isset($_POST['new_username']))		$_POST['new_username']="";
	     							if(!isset($_POST['new_pword']))		$_POST['new_pword']="";
	     							if(!isset($_POST['new_email']))		$_POST['new_email']="";
	     							?>
	     							<tr>
	     								<td colspan='2' align='right'><font class='standard18'><b><i><?=$lang['login_new']?><hr></td>
	     							</tr>
	     							<tr>
	          							<td><?=$lang['login_user']?></td>
	          							<td><input class='standard12' size='40' name='new_username' id='new_username' value="<?=$_POST['new_username']?>" placeholder='Enter Username'></td>
	          						</tr>
	          						<tr>
	          							<td><?=$lang['login_pass']?></td>
	          							<td><input class='standard12' size='40' name='new_pword' id='new_pword' value="<?=$_POST['new_pword']?>" type='password' placeholder='Enter Password'></td>
	          						</tr>
	     							<tr>
	     								<td nowrap><?=$lang['login_email']?></td>
	     								<td><input class='standard12' size='40' name='new_email' id='new_email' value="<?=$_POST['new_email']?>" placeholder='Enter E-mail Address'></td>
	     							</tr>     							
	     							<tr>
	     								<td colspan='2' align='right'><input type="submit" value="<?=$lang['login_create']?>" name='create_login_info' id='create_login_info' class='standard12 btn btn-default add_new_btn'></td>
	     							</tr>							
	     						<? } ?>
	     						</table>	
	     						<div id="dialog_new_acct" style='display:none;'>
		 							 <p>Please enter the a Username, Password, and an E-mail Address for your account to send the login information.</p>
								</div>
	     						</form>	
	          				</td>	
	          			</tr>
	          			</table>	
	          			</div>	
	          		</div>
	          		*/
	          		?>
		</center>


<script type='text/javascript'>
	
	$(document).ready(function() 
	    { 
	    		$('#username').focus();
	    		$('.buttonize').button();
	    }
	);
		
	function validateSubmit(formid)
	{
		if(formid==1 && $('#id_email_forgot').val()=="" ) 
		{
			$("#dialog_forgot").dialog();
			return false;
		}
		
		if(formid==2 && ( $('#new_username').val()=="" || $('#new_email').val()=="")) 
		{
			alert('got here...');
			
			$("#dialog_new_acct").dialog();
			return false;
		}
		return true;
	}	
</script>
<? include('footer.php') ?>