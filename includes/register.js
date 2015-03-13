

	email_okay = false;
	password_okay = false;
	last_email_tested = "";
	
	$('#login_form input').blur(function() {
		check_fields();
	});
	

	
	function check_fields() {
		all_clear = true;
		
		if($('#name_first').val() != '') $('#name_first_status').html("");
		if($('#name_last').val() != '') $('#name_last_status').html("");
		
		if(!check_email()) all_clear= false;
		if(!check_passwords()) all_clear = false;
		
		return all_clear;
	}
	
	function check_required() {
		
		all_clear = true;
		
		// all fields required
		required_array = new Array();
		required_array[0] = 'email';
		required_array[1] = 'confirm_email';
		required_array[2] = 'password';
		required_array[3] = 'confirm_password';
		required_array[4] = 'name_first';
		required_array[5] = 'name_last';
		
		for(i=0;i<required_array.length;i++) {
			if($('#'+required_array[i]).val() == '') {
				$('#'+required_array[i]+'_status').html("<span class='error'>required</span>");
				all_clear = false;
			}
		}
		
		return all_clear;
	}
	
	$('#create_account').click(function() {
		
		if(!check_required() || !check_fields()) {
			$('#submit_status').html("<span class='error'>Please fix the errors on the form</span>");
			return;
		}

		$('#submit_status').html("<span class='success'>Creating Account... </span>");
		
	
		create_account();
	});


	function create_account() {
		$('#create_account').attr('disabled','disabled');
		
		loader_toggle('submit_status', true);
		

		$.ajax({
			type: "POST",
			url: "ajax.php?cmd=create_user",
			data: "email="+$('#email').val()+
				"&password="+$('#password').val()+
				"&name_first="+$('#name_first').val()+
				"&name_last="+$('#name_last').val()
			,
			cache: false,
			dataType: "xml",
			error: function() {
				loader_toggle('submit_status', false);
				$('#submit_status').html("Error... Please try again");
				$('#create_account').attr('disabled','');
			},
			success: function(xml) {
				loader_toggle('submit_status', false);
				
				if($('main_response',xml).text() == '0') {
					$('#submit_status').html("<span class='error'>"+$('main_response',xml).text()+"</span>");
					
				} else {
					$('#submit_status').html("<span class='success'>Success! Redirecting you to the member's page...</span>");
					loader_toggle('submit_status', true);
					
					window.location = 'register_confirm.php';
					
				}
			}
			
		});
		
	}
	
	
	$('#email').change(function() {
		email_okay = false;
	});
	
	
	
	function check_passwords() {
		if($('#password').val() == '') return false;
		
		if($('#password').val().length < 4) {
			$('#password_status').html("<span class='error'>Password must be at least 4 characters</span>");
			return false;
		} else {
			$('#password_status').html("");
		}
		
		if($('#confirm_password').val() == '') return false;
		
		if($('#confirm_password').val() != $('#password').val()) {
			$('#confirm_password_status').html("<span class='error'>Passwords don't match</span>");
			return false;
		}
		
		$('#confirm_password_status').html("<span class='error'></span>");
		
		password_okay = true;
		
		return true;
	}
	
	function check_email() {
		
		if($('#email').val() == '') return false;
		
		if(last_email_tested != $('#email').val()) $('#email_status').html("<span class='error'></span>");
		
		if($('#email').val() != $('#confirm_email').val() && $('#confirm_email').val() != '' && $('#email').val() != '') {
			$('#confirm_email_status').html("<span class='error'>E-mails do not match</span>");
			return false;
		} else if($('#confirm_email').val() != '' && $('#confirm_email').val() == $('#email').val()) {
			$('#confirm_email_status').html("");

		}
		
		if(!isValidEmailAddress($('#email').val())) {
			$('#email_status').html("<span class='error'>Invalid E-mail</span>");
			return false;
		}
		
		if($('#confirm_email').val() == '') return false;

		
		if(!email_okay && last_email_tested != $('#email').val()) {
			
			last_email_tested = $('#email').val();
			
			
			
			$('#email_status').html('');
			
			loader_toggle('email_status', true);
			$.ajax({
				type: "POST",
				url: "ajax.php?cmd=check_user_exists",
				data: "email="+$('#email').val(),
				cache: false,
				dataType: "xml",
				error: function() {
					loader_toggle('email_status', false);
					$('#email_status').html("Error...");
					
				},
				success: function(xml) {
					loader_toggle('email_status', false);
					if($('username_response',xml).text() == '0') {
						$('#email_status').html("<span class='error'>E-Mail is in use</span>");
						email_okay = false;
					} else {
						$('#email_status').html("<span class='success'>E-Mail is available</span>");
						email_okay = true;
					}
				}
				
			});
		}
		
		
		
		return email_okay;
		
		
	}