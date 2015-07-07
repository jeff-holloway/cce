<?
	$secs=(int) $defaultsarray['session_timeout'];		
	$mrr_cookie_bake= time() +  $secs;				  		/* expires in SECS */
	
	$login_pg=0;
	if($page_name == 'login.php')		$login_pg=1;	
	
	if(!is_logged_in()
				&& $page_name != 'login.php'
				&& $page_name != 'uploadify.php'
				&& $page_name != 'update.php'
				&& $page_name != 'cronjob.php'
				&& $page_name != 'view_attachment.php') 
	{
		
		if($login_pg==0) 
		{
			header("Location: login.php");
			die;
		}		
	}
	
	if(!isset($_SESSION['view_access_level']))
	{
		//header("Location: login.php?out=1");
		//die;	
	}
		
	if(isset($_COOKIE['uuid']))
	{
		$secs=(int) $defaultsarray['session_timeout'];		
		$mrr_cookie_bake= time() +  $secs;				  				/* expires in SECS */
					
		setcookie("uuid", $_COOKIE['uuid'], $mrr_cookie_bake);				//reset the cookie with 60 seconds
	}	
	
	
	if(!isset($_SESSION['selected_user_id']))			$_SESSION['selected_user_id']=0;	
	if(!isset($_SESSION['selected_merchant_id']))		$_SESSION['selected_merchant_id']=0;
	if(!isset($_SESSION['selected_store_id']))			$_SESSION['selected_store_id']=0;
	
	if(!isset($_SESSION['selected_doc_type_id']))		$_SESSION['selected_doc_type_id']=0;
	
	if(!isset($_SESSION['access_level']))				$_SESSION['access_level']=0;
	if(isset($_SESSION['user_id']) && $_SESSION['access_level'] <= 80)
	{
		$_SESSION['selected_user_id']=$_SESSION['user_id'];
		$_SESSION['selected_merchant_id']=$_SESSION['merchant_id'];
		
		if($_SESSION['store_id'] == 0 && $_SESSION['selected_store_id'] > 0)
		{
			//don't reset it...could still have more than one store to select from.	
		}
		if($_SESSION['store_id'] > 0)
		{	//can only see one store.
			$_SESSION['selected_store_id']=$_SESSION['store_id'];
		}
	}
?>