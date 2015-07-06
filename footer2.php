		</div>
		<div style='clear:both'></div>
		<div class='footer_bar'>	<center><a href='#top_of_page'><b>Top of Page</b></a></center></div>
	</div>
<div class='modal_search' id='player_modal' style='display:none'>
	<div class='video_player_close' onclick="hidevideo()"><img src='images/close.png' alt='Close' title='Close'></div>
	<div class='video_player_object' id='video_player_object'></div>
</div>

<div class='dialog_box_content' id='dialog_box_content' style='display:none'></div>
<!-- Our upload JS script-->
<script src="includes/mini_upload/assets/js/script.js"></script>
<script>	
	$().ready(function() {
		show_waiting_files_status();
		$('.tablesorter').tablesorter();
		
		$('body').on('selectmenuchange', 'select', function() { $(this).trigger('change') })		
		
		update_bread_crumb_trail();
	});
	
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
	
	
	function auditor_folder()
	{		
		$('#auditor_folder_holder').toggle();			//toggle the setting...
		<? if($_SESSION['access_level']==45) { ?>
			//Auditor 2 section "Filing Cabinet", only displays the files for download.
			$('#auditor_folder_holder').show();
			$('.auditor2').show();		
			$('.auditor1').hide();
			$('.portlet').hide();
			
			$('.column').hide();
			
			$('.home_page').removeClass('highlight_current_page');
			$('.audit_folder').addClass('highlight_current_page');
			
			//$('.home_page').css("color","inherit");
			//$('.audit_folder').css("color","#e19918");
			
     		refresh_auditor2_files();  
			
		<? } else { ?>
			
			if($('#auditor_folder_holder').css('display') == "none" || $('#auditor_folder_holder').css('display') == "hidden" )
			{
				//display is now hidden, so show the normal index page again (without auditor sections "Filing Cabinet").
				$('.column').show();
				$('.portlet').show();
				$('.auditor2').hide();		
				$('.auditor1').hide();
				
				$('.home_page').addClass('highlight_current_page');
				$('.audit_folder').removeClass('highlight_current_page');
				
				//$('.home_page').css("color","#e19918");
				//$('.audit_folder').css("color","inherit");
			}
			else
			{
				//shown auditor sections "Filing Cabinet"... hide normal page display.
				$('.portlet').hide();	
				$('.column').hide();
				$('.auditor2').hide();		
				$('.auditor1').show();
				
				$('.home_page').removeClass('highlight_current_page');
				$('.audit_folder').addClass('highlight_current_page');
				
				//$('.home_page').css("color","inherit");
				//$('.audit_folder').css("color","#e19918");
			}	
			
			refresh_auditor2_assignment(); 
     		refresh_auditor2_files();  
     			
		<? } ?>
		$('.display_off').hide(); 
	}	
</script>


<? /*
				<script>
					$(function() {
						$( ".column" ).sortable({
						connectWith: ".column",
						handle: ".portlet-header",
						cancel: ".portlet-toggle",
						placeholder: "portlet-placeholder ui-corner-all"
						});
						$( ".portlet" )
						.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
						.find( ".portlet-header" )
						.addClass( "ui-widget-header ui-corner-all" )
						.prepend( "<span class='ui-icon ui-icon-minusthick portlet-toggle'></span>");
						$( ".portlet-toggle" ).click(function() {
						var icon = $( this );
						icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
						icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();
						});
					});
				</script>
				<!--  This script table header  -->
				<script>$(document).ready(function() 
					{ 
					$("#myTable").tablesorter(); 
					} 
				); 
				</script>
				<script>
				$(document).ready(function() { 
					$("table") 
					.tablesorter({widthFixed: false, widgets: ['zebra']}) 
					.tablesorterPager({container: $("#pager")}); 
				}); 
				</script>

				<script type="text/javascript">
				jQuery.browser = {};
				(function () {
					jQuery.browser.msie = false;
					jQuery.browser.version = 0;
					if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
						jQuery.browser.msie = true;
						jQuery.browser.version = RegExp.$1;
					}
				})();
				</script>
				*/
				?>


</body>
</html>