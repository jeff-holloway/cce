<? include_once('application.php') ?>
<? 
	$mrr_bg_color="FFFFFF";
	$mrr_city_name="";

	if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) 
	{
		$sql = "
			update users
			set linedate_login = now()
			where id = '".sql_friendly($_SESSION['user_id'])."'
		";
		simple_query($sql);
	}
	
	
	if(!isset($use_title)) 	$use_title = $defaultsarray['company_name'];
	
	$sql = "
		select *		
		from menu
		where toplevel = 0
			and hidden = 0
			and deleted = 0
			and sidebar=0
		order by zorder
	";
	$data_menu = simple_query($sql);	
	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?=$use_title?></title>
	
	<link rel="stylesheet" href="includes/jquery.notice.css" type="text/css" />
	<link rel="stylesheet" href="includes/jquery-autocomplete-ajax.css" type="text/css" />
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="includes/jquery.notice.js" type="text/javascript"></script>
	<script src="includes/jquery-autocomplete-ajax.js" type="text/javascript"></script>
	<script src="functions.js?unique=<?=time()?>" type="text/javascript"></script>	

    <!-- Bootstrap -->
    <link href="common/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="common/css/bootstrap-theme.min.css" rel="stylesheet"> -->
    <link href="common/css/fonts.css" rel="stylesheet">
    <link href="common/css/font-awesome.css" rel="stylesheet">
    <link href="common/css/style.css" rel="stylesheet">
    <link href="common/css/responsive.css" rel="stylesheet">
	
	
	<!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
	<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> -->
	<!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script> -->
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
	<!-- <script src="common/js/jquery-latest.min.js"></script>  -->
	<script src="common/js/jquery.tablesorter.js"></script> 
	<script src="common/js/jquery.tablesorter.pager.js"></script> 
	<script src="common/js/placeholders.min.js"></script> 
	
	<link href="includes/mini_upload/assets/css/style.css" rel="stylesheet" />
	<!-- JavaScript Includes -->
	<script src="includes/mini_upload/assets/js/jquery.knob.js"></script>

	<!-- jQuery File Upload Dependencies -->
	<script src="includes/mini_upload/assets/js/jquery.ui.widget.js"></script>
	<script src="includes/mini_upload/assets/js/jquery.iframe-transport.js"></script>
	<script src="includes/mini_upload/assets/js/jquery.fileupload.js"></script>
	
	<script src='/tinymce/js/tinymce/tinymce.min.js'></script>
	
	<link rel="stylesheet" href="style_added.css?unique=<?=time()?>" type="text/css" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="common/css/ie8.css" />
<![endif]-->
<!--[if !IE]><!--><script>
if (/*@cc_on!@*/false) {
    document.documentElement.className+=' ie10';
}
</script><!--<![endif]-->
<!--[if lt IE 11]>
	<link rel="stylesheet" type="text/css" href="common/css/ie10.css" />
<![endif]-->
<style>
    @media all and (-ms-high-contrast:none)
     {
     *::-ms-backdrop, .chbox input[type="checkbox"]{display:inline!important;}
	 *::-ms-backdrop, .chbox td label {background: transparent!important;padding-left: 5px!important;} /* IE11 */
	 *::-ms-backdrop, tr.chbox td label{line-height:10px!important;padding-bottom:3px!important;} /* IE11 */
     }
  </style>
  </head>
  <body>  	
    <div class="container">
			<div class="top_header">
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-9">
					<div class="logo">
						<a href="/" name='top_of_page'><img src="common/images/logo-capitalcompliance-out-01.png" alt=""></a>
					</div>
				</div>
				<div class="col-lg-9 col-md-9 col-sm-8 col-xs-3 top_menu">
				
					<nav class="navbar navbar-default" role="navigation">
					   <div class="navbar-header">
						  <button type="button" class="navbar-toggle" data-toggle="collapse" 
							 data-target="#example-navbar-collapse">
							 <span class="sr-only">Toggle navigation</span>
							 <span class="icon-bar"></span>
							 <span class="icon-bar"></span>
							 <span class="icon-bar"></span>
						  </button>
					   </div>
					   <div class="collapse navbar-collapse" id="main_desktop_menu">
						  <ul class="nav navbar-nav">
							 <!--- <li class="active"><a href="/">Customer Menu</a></li> --->
							 <li><a href="http://www.capitalcomplianceexperts.com/services/aml-compliance-program/" target='_blank'>Services</a></li>
							 <li><a href="http://www.capitalcomplianceexperts.com/compliance-industries/" target='_blank'>Industries</a></li>
							 <li><a href="http://www.capitalcomplianceexperts.com/products/" target='_blank'>Products</a></li>
							 <li><a href="http://www.capitalcomplianceexperts.com/free-tools/" target='_blank'>Free Tools</a></li>
							 <li><a href="http://www.capitalcomplianceexperts.com/faq/" target='_blank'>FAQ</a></li>
							 <li><a href="http://www.capitalcomplianceexperts.com/contact-us/" target='_blank'>Contact</a></li>
							 <? if(is_logged_in()) { ?>
							 	<li><a href="login.php?out=1" <?=($page_name == 'login.php' ? "class='active'" : "")?>>Sign Out</a></li>
							<? } else { ?>
								<li><a href="login.php" <?=($page_name == 'login.php' ? "class='active'" : "")?>>Sign In</a></li>
								
							<? } ?>
							 <li class='menu_ending'>
								<img src='common/images/cta-header_@2x.gif' style='width:138px'>
							</li>
						  </ul>
					   </div>
					</nav>
				</div>				
			</div>
			<div class="mob_menu">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="collapse navbar-collapse" id="example-navbar-collapse">
							  <ul class="nav navbar-nav">
								 <li class="active"><a href="/">Customer Menu</a></li>
								 <li><a href="http://www.capitalcomplianceexperts.com/services/aml-compliance-program/" target='_blank'>Services</a></li>
								 <li><a href="http://www.capitalcomplianceexperts.com/compliance-industries/" target='_blank'>Industries</a></li>
								 <li><a href="http://www.capitalcomplianceexperts.com/products/" target='_blank'>Products</a></li>
								 <li><a href="http://www.capitalcomplianceexperts.com/free-tools/" target='_blank'>Free Tools</a></li>
								 <li><a href="http://www.capitalcomplianceexperts.com/faq/" target='_blank'>FAQ</a></li>
								 <li><a href="http://www.capitalcomplianceexperts.com/contact-us/" target='_blank'>Contact Us</a></li>
								 <li class="sign_in">
									<button type="button" class="btn btn-default navbar-btn" onClick="window.location.href = '/login.php?out=1';">Sign Out</button>
								</li>
							  </ul>
						   </div>
				</div>
			</div>
			<div class="banner_top">
				<img src="common/images/main_banner_img.png" alt="" style='max-height:101px;width:100%'>
				<div class="banner_txt">
					<h3></h3>
					<p></p>
				</div>				
			</div>
			<!--
			<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
				<div class='welcome_heading'>		
     			<?
     				if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) echo get_welcome_by_id($_SESSION['user_id']);
     			?>	
				</div>
			</div>	
			-->
			<? if(is_logged_in()) { ?>
				<div id='tagline_filler'>
				<?
					if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) echo get_tagline_trail($_SESSION['user_id']);	
				?>		
				</div>
				<div id='separator_line'></div>
			<? } ?>
			<div class="mainbody_content">
			<div class="row-offcanvas wrapper">
				<? if(is_logged_in() && $page_name != 'login.php') { ?>
					<div class="navbar navbar-static-top navbar-default">
						<div class="container">
							<div class="navbar-header">
								<button id="toggle" type="button" class="navbar-toggle">
								<i class="fa fa-align-justify"></i>
								</button>
								<a href="" class="navbar-brand">Side Menu</a>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 sidebar-offcanvas mrr_waiting_files_container">
						<!--
						<button type="button" class="btn btn-default navbar-btn upload_btn">Upload Documents</button>
						-->
						
						<div id='waiting_file_status'></div>			
						<div>
							<?
							if($page_name != 'login.php')
							{
								$upload_section = new upload_section();
								$upload_section->section_id = SECTION_WAITING;
								$upload_section->display_style = 1;
								$upload_section->param('callback_function', 'show_waiting_files_status()');
								$upload_section->show();
							}
							?>
						</div>
						
						
						<!--Form for New Users -->
		                    <div id="dialog-waiting-files" title="Files Awaiting Processing" style='display:none;'>
		                    	<div id='mrr_waiting_file_display'></div>
		                    </div>		
						
						<ul class="side_menu" data-role="panelbar">
							<?
							if($_SESSION['access_level']==45)
							{
								echo "<li><a href='/auditor_folder.php' class='active_side_menu'><i class='fa fa-arrow-circle-right'></i> Auditor Folder</a></li>";	
							}
     						else
							{ 	     							
     							$add_class="";
     							if($page_name=="" || $page_name=="/" || $page_name=="index.php" || $page_name=="index2.php")		$add_class=" active_side_menu";					
     	     					
     	     					echo "<li><a href='/' class='home_page".$add_class."'><i class='fa fa-arrow-circle-right'></i> Customer Profile</a></li>";		//#top_of_page
     	     					
     	     					
     	     					$add_class="";
     							if($page_name=="auditor_folder.php")		$add_class=" active_side_menu";	
     	     					
     	     					
     	     					if( $_SESSION['access_level'] >=50)	
     	     					{
     	     						echo "<li><a href='/auditor_folder.php' class='".$add_class."'><i class='fa fa-arrow-circle-right'></i> Auditor Folder</a></li>";
     	     					}							
     							
							    	/*	
							    	if(($page_name=="" || $page_name=="/" || $page_name=="index.php" || $page_name=="index2.php") && $_SESSION['access_level'] >=50)	
     	     					{
     	     						echo "<li><a href='/auditor_folder.php' class='audit_folder'><i class='fa fa-arrow-circle-right'></i> Auditor Folder</a></li>";	// href='javascript: void(0)' onClick='auditor_folder();'
     	     					}
							    	
							    					
     							$user_idst =strtolower($_SESSION['username']);
     							if($_SESSION['user_id']==1)		$user_idst =strtolower('Employee.test1');
     							if($_SESSION['user_id']==2)		$user_idst =strtolower('Employee.test2');
     							
     	     					$time =time();//time php
     	     					$key_sso = $defaultsarray['docebo_api_key_custom'];
     	      					$token =md5($user_idst.','.$time.','.$key_sso);
     	      					$url ='http://capitalcomplianceexperts.docebosaas.com/lms/index.php?r=site/sso&login_user='.$user_idst.'&time='.$time.'&token='.$token;
     							
     							echo "<li><a href='".$url."' target='_blank'><i class='fa fa-arrow-circle-right'></i> DOCEBO Account</a></li>";
     							*/
     							
     							$sql = "
     	     						select *
     	     						
     	     						from menu
     	     						where access_level <= '".$_SESSION['access_level']."'
     	     							and hidden = 0
     	     							and deleted = 0
     	     							 and sidebar > 0
     	     						order by zorder
     	     					";
     	     					$data_menu_sub = simple_query($sql);
     	     					
     	     					if(mysqli_num_rows($data_menu_sub) > 0) 
     	     					{				
     	     						while($row_menu_sub = mysqli_fetch_array($data_menu_sub)) 
     	     						{				
     	     							$add_class="";	
     	     							if($page_name==$row_menu_sub['link'])		$add_class="active_side_menu";	
     	     							
     	     							
     	     							echo "<li><a href='".$row_menu_sub['link']."' class='".$add_class."'><i class='fa fa-arrow-circle-right'></i> ".$row_menu_sub['menu_name']."</a></li>";
     	     						}
     	     					}	
     	     					
     							 echo "<div class='dynamic_sidebar'>".generate_sidebar_documents()."</div>";
	                         	}				
							?>
						</ul>
						<div class="photo_edit">
							<img id='sidebar_company_logo' src="/images/no-profile-image.png" alt="" width='200'>
							<!--<a class="photo_sidebar_edit" href="#"><i class="fa fa-pencil"></i></a>-->
						</div>
					</div>
				<? } ?>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 main_center_container">
					<div class="middle_main_content">