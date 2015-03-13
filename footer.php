	</td>
</tr>
<tr>
	<td colspan='3' class='footer_bar'>		
		<center><a href='#top_of_page' style='color:black;'><b>Top of Page</b></a></center>
	</td>
</tr>
</table>
<div class='modal_search' id='player_modal' style='display:none'>
	<div class='video_player_close' onclick="hidevideo()"><img src='images/close.png' alt='Close' title='Close'></div>
	<div class='video_player_object' id='video_player_object'>
	</div>
</div>
<script type='text/javascript'>
	/*
	var page_load_dt_update = false;
	function reset_page_load_dt_update_flag() {
		page_load_dt_update = true;
	}
	
	setTimeout(reset_page_load_dt_update_flag,(60 * 1000));
	<?
		//if($_SERVER['REMOTE_ADDR'] == '69.137.72.167') $defaultsarray['session_timeout'] = 5;
		if($page_name != 'login.php' && is_numeric($defaultsarray['session_timeout'])) {
			echo "
				var session_timeout = setTimeout(timeout_check,".($defaultsarray['session_timeout'] * 1000).");
			";
		}
	?>
	
	$('input').focus(function() {
		// the user clicked on a field, refresh out timeout
		
		if(page_load_dt_update) {
			$.ajax({
				url: 'ajax.php?cmd=update_page_load_dt',
				data: {},
				type: 'POST',
				cache:false,
				dataType: 'xml',
				success: function(xml) {
					page_load_dt_update = false;
					setTimeout(reset_page_load_dt_update_flag,(60 * 1000));
				}
			});
			
			
		}
	});
	*/
</script>
</body>
</html>