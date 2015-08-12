				</div>
				</div>
			</div>	
				
			<div class="footer_menu">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<ul class="footer_nav">
						<li><a href="/">Home</a></li>
						<li><a href="http://www.capitalcomplianceexperts.com/compliance-industries/" target='_blank'>Industries</a></li>
						<li><a href="http://www.capitalcomplianceexperts.com/products/" target='_blank'>Products</a></li>
						<li><a href="http://www.capitalcomplianceexperts.com/free-tools/" target='_blank'>Free Tools</a></li>
						<li><a href="http://www.capitalcomplianceexperts.com/industry-news/">Industry News</a></li>
						<li><a href="http://www.capitalcomplianceexperts.com/faq/" target='_blank'>FAQ</a></li>		
						<li><a href="http://www.capitalcomplianceexperts.com/about/" target='_blank'>About</a></li>				
						<li><a href="http://www.capitalcomplianceexperts.com/contact-us/" target='_blank'>Contact Us</a></li>
						<li><a href="/login.php?out=1">Sign In</a></li>
					</ul>
				</div>
			</div>
			<div class="footer_middle_menu">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h4>Additional MSB services offered by Capital Retail Solutions</h4>
					<ul class="footer_middle_items">
						<li><a href="http://www.capitalretailsolutions.com/services/financial-services/check-cashing/" target='_blank'>Check Cashing</a></li>
						<li><a href="http://www.capitalretailsolutions.com/services/financial-services/bill-pay/" target='_blank'>Bill Pay</a></li>
						<li><a href="http://www.capitalretailsolutions.com/services/financial-services/prepaid-cards/" target='_blank'>Prepaid Cards</a></li>
						<li><a href="http://www.capitalretailsolutions.com/services/telecommunications/pinless-calling/" target='_blank'>Pinless International Calling</a></li>
						<li><a href="http://www.capitalretailsolutions.com/services/telecommunications/top-ups/" target='_blank'>Top-Ups</a></li>
						<li><a href="http://www.capitalretailsolutions.com/services/other-services/gift-card-buyback/" target='_blank'>Gift Card Buybacks</a></li>
					</ul>
				</div>
			</div>
			<div class="footer_bottom_section">
				<div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
					<ul class="social_icons">
						<li><a href="https://www.facebook.com/crsinc" target='_blank'><i class="fa fa-facebook"></i></a></li>
						<li><a href="https://twitter.com/crs_inc" target='_blank'><i class="fa fa-twitter"></i></a></li>
						<li><a href="https://www.linkedin.com/company/capital-retail-solutions" target='_blank'><i class="fa fa-linkedin-square"></i></a></li>
					</ul>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-1 col-xs-12">
					<div class="bottom_logo">
						<img src="common/images/footer_logo.gif" alt="" style='width:100px'>
					</div>	
				</div>
				<div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
					<div class="privacy_policy">
						<ul>
							<li><a href="http://www.capitalcomplianceexperts.com/privacy-policy/" target='_blank'>Privacy Policy</a></li>
							<li><a href="http://www.capitalcomplianceexperts.com/terms-of-use/" target='_blank'>Terms of Use</a></li>
						</ul>
						<p>Copyright &copy; 2015 Capital Compliance Experts. All rights reserved.</p>
						<p><a href='http://www.cruxdesign.com/' target='_blank'>Website by Crux Design</a></p>
					</div>
				</div>
			</div>				
	</div>
	
	<div class='modal_search' id='player_modal' style='display:none'>
		<div class='video_player_close' onclick="hidevideo()"><img src='images/close.png' alt='Close' title='Close'></div>
		<div class='video_player_object' id='video_player_object'></div>
	</div>
	
	<div class='dialog_box_content' id='dialog_box_content' style='display:none'></div>
	<!-- Our upload JS script-->
	<script src="includes/mini_upload/assets/js/script.js"></script>	
	
	<script>
		var scroll_pos_fix_working_flag = false;
		function mrr_search_highlighter(id,cd)
		{
			if(cd > 0)
			{
				$('#merch_'+id+'_cid_number').css('text-decoration','underline');
				$('#merch_'+id+'_legal_name').css('text-decoration','underline');
			}
			else
			{
				$('#merch_'+id+'_cid_number').css('text-decoration','none');
				$('#merch_'+id+'_legal_name').css('text-decoration','none');	
			}				
		}
		function mrr_search_highlighter_store(id,cd)
		{
			if(cd > 0)
			{
				$('#store_'+id+'_name').css('color','#e19918');
				$('#store_'+id+'_num').css('color','#e19918');
				$('#store_'+id+'_addr').css('color','#e19918');
				$('#store_'+id+'_city').css('color','#e19918');
				$('#store_'+id+'_state').css('color','#e19918');
				
				$('#store_'+id+'_name').css('text-decoration','underline');
				$('#store_'+id+'_num').css('text-decoration','underline');
				$('#store_'+id+'_addr').css('text-decoration','underline');
				$('#store_'+id+'_city').css('text-decoration','underline');
				$('#store_'+id+'_state').css('text-decoration','underline');
			}
			else
			{
				$('#store_'+id+'_name').css('color','#000000');
				$('#store_'+id+'_num').css('color','#000000');
				$('#store_'+id+'_addr').css('color','#000000');
				$('#store_'+id+'_city').css('color','#000000');
				$('#store_'+id+'_state').css('color','#000000');
				
				$('#store_'+id+'_name').css('text-decoration','none');
				$('#store_'+id+'_num').css('text-decoration','none');
				$('#store_'+id+'_addr').css('text-decoration','none');
				$('#store_'+id+'_city').css('text-decoration','none');
				$('#store_'+id+'_state').css('text-decoration','none');
			}				
		}
		
		//session keep alive code....or at least prompt user if the session drops...
		var heart_attack=0;
		function mrr_pulse_rate()
		{
			if(heart_attack==0)			setTimeout(mrr_check_heart_beat,(30 * 1000));
		}
		function mrr_check_heart_beat()
     	{		
     		$.ajax({
     				url: 'ajax.php?cmd=mrr_heart_beat',
     				data: {},
     				type: 'POST',
     				cache:false,
     				dataType: 'xml',
     				success: function(xml) {
     					result_val=$(xml).find('rslt').text();
     					
     					if(result_val == "0")
     					{
     						heart_attack=1;     						
     						msgbox("<span class='alert'><b>Warning:</b></span> Login Session Dropped... <a href='login.php' target='_blank'><b>Click here to log back in</b></a> before you loose your work.");	
     					}	
     					else
     					{
     						setTimeout(mrr_check_heart_beat,(30 * 1000));
     					}				
     				}
     		});
     	}
		
		$().ready(function() {
			
			<? if(trim($page_name) == "documents.php" && ($_GET['id'] ==36 || $_GET['id'] ==3)) {  ?>
				//show_user_cert(<?=$xref_id ?>,'cert_image_holder');
			<? } else { ?>	
     			show_waiting_files_status();
     			//$('.tablesorter').tablesorter();
     			
     			$('body').on('selectmenuchange', 'select', function() { $(this).trigger('change') })		
     			
     			update_bread_crumb_trail();
			<? } ?>
						
			<? if(trim($page_name) != "login.php") {  ?>
				mrr_pulse_rate();			
			<? } ?>
		});		
		
		<?
			if(isset($data_columns)) {
				$row_columns = mysqli_fetch_array($data_columns);
				$left_array = explode(",", $row_columns['home_column_left']);
				$right_array = explode(",", $row_columns['home_column_right']);
				foreach($left_array as $entry) {
					echo "
						$('#".$entry."').appendTo('.move_box_left');
					";
				}
				
				foreach($right_array as $entry) {
					echo "
						$('#".$entry."').appendTo('.move_box_right');
					";
				}			
			}
		?>
		
		$.datepicker.setDefaults({
			numberOfMonths: 2,
			changeYear: true
		});
		$('input[type=button], input[type=submit]').button();
		$("select").selectmenu();
		
		
		$('.linedate').datepicker();
		$(".tooltip").tooltip();
		$(".accordion_fixed").accordion();
		$(".accordion" ).accordion({
			heightStyle: "content"
		});
		
		$(function() {
			$( ".column" ).sortable({
				connectWith: ".column",
				handle: ".portlet-header",
				cancel: ".portlet-toggle",
				placeholder: "portlet-placeholder ui-corner-all",
				update: function( event, ui ) {
					console.log("class: " + $(this).attr('class'));
						
						if ( typeof afterPortletMove == 'function' ) { 
						    afterPortletMove(); 
						}
						
						if($(this).hasClass('move_box_left')) {
							use_column = "move_box_left";
						} else {
							use_column = "move_box_right";
						}
						var tmp_array = new Array();
						$(this).find('.portlet').each(function() {
							tmp_array.push($(this).attr('id'));
						});
				        //var sort_data = $(this).sortable('toArray').toString();
				        var sort_data = tmp_array.toString();
				        $.ajax({
				        	url: "ajax.php?cmd=save_sortable",
				        	type: "post",
				        	dataType: "xml",
				        	data: {
				        		"column": use_column,
				        		"sort_data": sort_data,
				        		"from_page": '<?=$page_name?>'
				        	}
				        });
				}
			});
			/*
			$( ".portlet" )
				.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
				.find( ".portlet-header" )
				.addClass( "ui-widget-header ui-corner-all" )
				.prepend( "<span class='ui-icon ui-icon-minusthick portlet-toggle'></span>");
			
			
			$( ".portlet-toggle" ).click(function() {
				var icon = $( this );
				//icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
				//icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();
			});
			*/
			
		});
	
	// This script table header 
	$(document).ready(function() { 
		//$("#myTable").tablesorter(); 
		//$("table").tablesorter({widthFixed: false, widgets: ['zebra']}).tablesorterPager({container: $("#pager")}); 
	}); 
	jQuery.browser = {};
	(function () {
		jQuery.browser.msie = false;
		jQuery.browser.version = 0;
		if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
			jQuery.browser.msie = true;
			jQuery.browser.version = RegExp.$1;
		}
	})();
	
	/*
	$('img').error(function() {
		$(this).hide();
	});
	*/

	$().ready(function() {
		setTimeout(function(){ 
			page_resize_calc();
		}, 1000);   
	});
	

	
	$(window ).resize(function() {
		page_resize_calc();
		/*
		setTimeout(function(){ 
			page_resize_calc();
		}, 500);
		*/
	});

	
	$(document).on('scroll', function() {
		if(!scroll_pos_fix_working_flag) {
			// set a flag so we don't call this over and over while we're still scrolling
			scroll_pos_fix_working_flag = true;
			setTimeout(fix_scroll, 500);
	  		
	  	}
	});	
	
	function fix_scroll() {
		scroll_pos_fix_working_flag = false;
		//console.log("Fixing scroll now");
		$(document).scrollLeft(0);
	}
	
	
	
	
    	$(document).on('change', '.file_merchant_selector', function() {
		file_id = $(this).attr('file_id');
		myoptgrp=parseInt($(this).val());          		
		mrr_update_waiting_file(file_id, 0);
		
		fieldnamer ='#file_'+file_id+'_store_id';	
		
		$.ajax({
			url: "ajax.php?cmd=refresh_store_selector",
			dataType: "xml",
			type: "post",
			data: {
				"merchant_id": myoptgrp
			},
			error: function() {
				msgbox("General error loading store list. Please try again");
			},
			success: function(xml) {
				$(fieldnamer).html(''); // clear out all the existing options
				
				$(fieldnamer).append("<option value='0'>All</option>");
				
				$(xml).find('StoreEntry').each(function() {
					$(fieldnamer).append("<option value='"+$(this).find('StoreID').text()+"'>"+$(this).find('StoreName').text()+"</option>");
				});
				
          		$(fieldnamer).selectmenu('destroy');
          		$(fieldnamer).selectmenu();
			}
		});          		
	});
     	
	$(document).on('change', '.file_store_selector', function() {
		file_id = $(this).attr('file_id');
		myoptgrp=parseInt($(this).val());
		mrr_update_waiting_file(file_id, 0);
		
	});

     	
	$(document).on('change', '.file_template_selector', function() {
	
		file_id = $(this).attr('file_id');
		
		fieldnamer1 ='#file_'+file_id+'_template_id';	
		fieldnamer ='#file_'+file_id+'_sub_id';			
		
		myoptgrp=parseInt($(this).val());   
		mygrpid=$(fieldnamer1).val();
		myitemid=$(fieldnamer).val();
		       		
		mrr_update_waiting_file(file_id, 0);
		
		$.ajax({
			url: "ajax.php?cmd=refresh_sub_item_selector",
			dataType: "xml",
			type: "post",
			data: {
				"item_id": myoptgrp,
				"item_grp": mygrpid,
				"my_item": myitemid
			},
			error: function() {
				msgbox("General error loading template sub items. Please try again");
			},
			success: function(xml) {
				$(fieldnamer).html(''); // clear out all the existing options
				
				$(fieldnamer).append("<option value='0'></option>");
				
				$(xml).find('ItemEntry').each(function() {
					$(fieldnamer).append("<option value='"+$(this).find('ItemID').text()+"'>"+$(this).find('ItemName').text()+"</option>");
				});
				
          		$(fieldnamer).selectmenu('destroy');
          		$(fieldnamer).selectmenu();
          		console.log($(fieldnamer).html());
			}
		});      		
	});  
	
	$(document).on('change', '.file_template_sub_selector', function() {
		file_id = $(this).attr('file_id');
		myoptgrp=parseInt($(this).val());
		mrr_update_waiting_file(file_id, 0);
	});   

	</script>
	<script src="//use.typekit.net/zwf3oaz.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="common/js/bootstrap.min.js"></script>
    <script src="common/js/custom.js"></script>
  </body>
</html>