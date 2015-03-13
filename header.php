<? include_once('application.php') ?>
<? 
	$mrr_bg_color="FFFFFF";
	$mrr_city_name="";

	if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
		$sql = "
			update users
			set linedate_last_pageload = now()
			where id = '".sql_friendly($_SESSION['user_id'])."'
		";
		simple_query($sql);
	}
	
	if(!isset($use_title)) {
		$use_title = $defaultsarray['company_name'];
	} else {
		$use_title .= " - " . $defaultsarray['company_name'];
	}
	
	//if(!isset($_SESSION['sidebar_display'])) $_SESSION['sidebar_display'] = 1;

	$sql = "
		select *
		
		from menu
		where toplevel = 0
			and hidden = 0
			and deleted = 0
		order by zorder
	";
	$data_menu = simple_query($sql);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title><?=$use_title?></title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" href="style.css?unique=<?=time()?>" type="text/css" />
		<!--
		<link rel="stylesheet" href="includes/jquery.notice.css" type="text/css" />
		
		<link rel="stylesheet" href="includes/tablesort_theme/style.css" type="text/css" />		
		
		<link rel="stylesheet" href="includes/jquery-autocomplete-ajax.css" type="text/css" />
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
		
		<script src="includes/jquery.notice.js" type="text/javascript"></script>		
		
		<script src="includes/jquery.tools.min.js" type="text/javascript"></script>
		<script src="includes/autoresize.jquery.min.js" type="text/javascript"></script>		
		
		<script src="includes/jquery-impromptu.js" type='text/javascript'></script>		
		
		<script src="includes/jquery.tablesorter.min.js" type="text/javascript"></script>
		<script src="includes/jquery-autocomplete-ajax.js" type="text/javascript"></script>
		<link rel="stylesheet" href="includes/uploadify/uploadify.css" type="text/css" />
		<script type="text/javascript" src="includes/uploadify/swfobject.js"></script>
		<script type="text/javascript" src="includes/uploadify/jquery.uploadify.v2.1.0.min.js"></script>
		-->
		<script src="includes/functions.js" type="text/javascript"></script>	
	</head>
<body bgcolor='#<?= $mrr_bg_color ?>'>
<? if(!isset($hide_header)) { ?>
<table cellspacing='0' cellpadding='0' width='100%' border='0' id='main_container'>
<tr>
	<td class='header_bar' colspan='3'>
		<div class='nav_bar'>
			<?
			if(isset($_SESSION['access_level'])) {
								
				while($row_menu = mysql_fetch_array($data_menu)) {
					$sql = "
						select *
						
						from menu_alt
						where toplevel = $row_menu[id]
							and access_level <= '".$_SESSION['access_level']."'
							and hidden = 0
							and deleted = 0
							".$restricted_menu."
						order by zorder
					";
					$data_menu_sub = simple_query($sql);
					
					echo "<div class='menu_entry'>";
					echo "$row_menu[menu_name]";
					if(mysql_num_rows($data_menu_sub)) {
						echo "<div class='menu_entry_sub'>";
							while($row_menu_sub = mysql_fetch_array($data_menu_sub)) {
								if($row_menu_sub['menu_name'] == '-') {
									echo "<div class='menu_spacer'></div>";
								} else if($row_menu_sub['master_level']) {
									
									$sql = "
										select *										
										from menu
										where toplevel = '$row_menu_sub[id]'
											and deleted = 0
											and access_level <= '".$_SESSION['access_level']."'
											and hidden = 0
										order by zorder
									";
									$data_menu_sub1 = simple_query($sql);
									echo "
										<div class='menu_entry_sub_entry menu_master_level' master_level_id='$row_menu_sub[id]'>
											$row_menu_sub[menu_name] &nbsp;&nbsp;&nbsp; &gt;&gt;&gt;
										</div>
										<div style='border:1px #aaaaaa solid;background-color:#eee;position:absolute;padding-left:0;width:200px;display:none' class='menu_side_level menu_entry_sub_entry menu_sub_level_$row_menu_sub[id]'>
									";
									while($row_menu_sub1 = mysql_fetch_array($data_menu_sub1)) {
										
										$new_tabber="";
										if(trim($row_menu_sub1['menu_name'])=="Logout")	$new_tabber=" target='_blank'";
										
										echo "
											<div class='menu_side_level menu_entry_sub_entry'>
												<a href=\"$row_menu_sub1[link]\" style='color:black'".$new_tabber.">$row_menu_sub1[menu_name]</a>
											</div>
										";
									}
									echo "
										</div>
									";
									
								} else {
									echo "
										<div class='menu_entry_sub_entry'>
											<a href=\"$row_menu_sub[link]\" style='color:black'>$row_menu_sub[menu_name]</a>
										</div>
									";
								}
							}
						echo "</div>";
					}
					echo "</div>";
	
				}				
			}			
			?>
			<script type='text/javascript'>
				
				$('.menu_entry_sub_entry:not(.menu_side_level)').mouseover(function() {
					
					if($(this).hasClass('menu_side_level')) return false;
					
					master_level_id = parseFloat($(this).attr('master_level_id'));
					
					if(master_level_id > 0) {
						$('.menu_side_level:not(.menu_sub_level_'+master_level_id+')').hide();
					} else {
						$('.menu_side_level').hide();
					}
					
					if(parseFloat($(this).attr('master_level_id')) > 0) {
						
						obj_sub = $('.menu_sub_level_'+master_level_id);
						use_left = parseFloat($(this).width()) + 30;
						use_top = parseFloat($(this).position().top);
						//alert(use_left)
						$(obj_sub).css('left',use_left);
						$(obj_sub).css('top',use_top);
						$(obj_sub).show();
						$(obj_sub).children('.menu_side_level').show();
					}
				});				
				$('.menu_entry').hover(
					function() {
						//$(this).children(".menu_entry_sub").show();
						$(this).addClass("menu_entry_highlight");
					},
					function() {
						$(this).children(".menu_entry_sub").hide();
						$('.menu_side_level').hide();
						$(this).removeClass("menu_entry_highlight");
					}
				);
				$('.menu_entry').click(function() {
						$(this).children(".menu_entry_sub").show();
				});
				$('.menu_entry_sub_entry').hover(
					function() {
						$(this).addClass("menu_entry_highlight");
					},
					function() {
						$(this).removeClass("menu_entry_highlight");
					}
				);				
			</script>			
		</div>
		<div style='clear:both'><a name='top_of_page'>&nbsp;</a></div>
	</td>
</tr>
<tr>
	<td valign='top' style='padding:5px;'>
		<div style='width:100%;'></div>
<? } ?>