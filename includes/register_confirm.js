	$('#confirm_code_submit').click(function() {
		
		if($('#confirm_code').val() == '') {
			$('#confirm_code_status').html("<span class='error'>Please enter the code into the box above</span>");
			return;
		}
			
			loader_toggle('confirm_code_status', false);
			
			$.ajax({
				type: "POST",
				url: "ajax.php?cmd=confirm_account",
				data: "confirm_code="+$('#confirm_code').val(),
				cache: false,
				dataType: "xml",
				error: function() {
					loader_toggle('confirm_code_status', false);
					$('#confirm_code_status').html("Error... Please try again");
					$('#create_account').attr('disabled','');
				},
				success: function(xml) {
					loader_toggle('confirm_code_status', false);
					if($('user_id',xml).text() == '0') {
						$('#confirm_code_status').html("<span class='error'>Error validating Code</span>");
						
					} else {
						$('#confirm_code_status').html("<span class='success'>Success! Redirecting you to the member's page...</span>");
						loader_toggle('confirm_code_status', true);
						
					}
				}
				
			});
			

	});