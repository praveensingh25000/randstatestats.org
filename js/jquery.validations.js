jQuery(document).ready(function(){

	jQuery('#frmAdminLogin').validate();
	
	jQuery('#frmPost').validate();

	jQuery('#frmgenerateinvoice').validate();

	$('#frmaddall_subscription_plan').validate({

		onkeyup: false,
		onclick: false,
		onchange:true,
		
		rules: {

			"plan_name": {
				required: true,
				remote: {
					url: URL_SITE+"/admin/check_availability.php",
					type: "post",
					async: false,
					data: {
						action: "add",
						plan_type: function () {
						return $("#plan_type").val();
						}  
					}
				}
			}			
		},
		messages: {
			"plan_name": {
				required: "Please enter a valid plan name.",
				remote: "Plan name already exist."
			}
		}
	});

	jQuery('#frmeditall_subscription_plan').validate({

		onkeyup: false,
		onclick: false,
		onchange:true,

		rules: {

			"plan_name": {
				required: true,
				remote: {
					url: URL_SITE+"/admin/check_availability.php",
					type: "post",
					async: false,
					data: {
						action: "edit",
						curent_plan_name: function () {
						return $("#curent_plan_name").val();
						},
						plan_type: function () {
						return $("#plan_type").val();
						}   
					}
				}
			}			
		},
		messages: {
			"plan_name": {
				required: "Please enter a valid plan name.",
				remote: "Plan name already exist."
			}
		}
	});

	jQuery('#addUserType').validate();
	jQuery('#addUserSubType').validate();
	jQuery('#frmAlterTable').validate();
	jQuery('#user_registration').validate({

		onkeyup: false,
		onclick: false,
		onchange:true,

		rules:{
			"email":{
				remote:"checkEmail.php"
			},
			"username":{
				remote:"checkEmail.php"
			}
		},
		messages:{
			"email":{
				remote: jQuery.format("Email already exists")
			},
			"username":{
				remote: jQuery.format("Username already exists")
			}
		}
	});

	jQuery('#user_registrationadmin').validate({
		
		onkeyup: false,
		onclick: false,
		onchange:true,

		rules:{
			"email":{
				remote: "check_availability.php",
			},
			"username":{
				remote:"check_availability.php"
			}
		},
		messages:{
			"email":{
				remote: jQuery.format("Email already exists")
			},
			"username":{
				remote: jQuery.format("Username already exists")
			}
		}
	});

	jQuery('#registrationadminedit').validate({
		
		onkeyup: false,
		onclick: false,
		onchange:true,

		rules:{
			"email":{
				remote: {
					url: URL_SITE+"/admin/check_availability.php",
					type: "post",
					data: {
						email: function() {
							return $( "#email" ).val();
						},
						oldemail: function() {
							return $( "#oldemail" ).val();
						}
					}
						
				}
			},
			"username":{
				minlength: 6,
				remote: {
					url: URL_SITE+"/admin/check_availability.php",
					type: "post",
					data: {
						username: function() {
							return $( "#username" ).val();
						},
						oldusername: function() {
							return $( "#oldusername" ).val();
						}
					}
				}
			}
		},
		messages:{
			"email":{
				remote: jQuery.format("Email already exists")
			},
			"username":{
				minlenght: "Username should be at least 6 characters long",
				remote: jQuery.format("Username already exists")
			}
		}
	});

	jQuery('#generalSettingsForm').validate({
		rules:{
			"settings[Validity]":{
				digits: true
			}
		},
		messages:{
			"settings[Validity]":{
				digits: "Enter Digits Only"
			}
		}
	});


	jQuery('#frmAllDatabase').validate({
		rules: { 
			tableFileUpload: { required: true, accept: "csv" }
		},
		messages: { 
			tableFileUpload: { accept: "File must be CSV " } 
		}
	});

	jQuery('#frmAlterTable').validate();
	jQuery('#editFrmData').validate();
	jQuery('#frmAllCat').validate();

	jQuery('#frmAllCatGraph').validate();
	jQuery('#frmAddGraphDetail').validate();

	jQuery('#user_login_form').validate();
	jQuery('#forgotpasswordForm').validate();

	//to validate change username at admin end 
	jQuery('#frmchangeusername').validate();

	jQuery('#user_change_password').validate({
		rules:{
			"confirm_password":{
				equalTo:"#new_password"
			},
			"old_password":{
				remote: URL_SITE+"/checkemailAvailability.php"
			}
		},
		messages:{
			"confirm_password":{
				equalTo:"<font color='red'>Confirm Password doesn't match</font>"
			},
			"old_password":{
				remote: jQuery.format("<font color='red'>Password doesn't match</font>")
			}
		}
	});
	
	//to validate admin change password form	
	$("#frmchangepassword").validate({
				rules: {					
					
					"confirmPass": {
						equalTo: "#newpass"
					}
					
				},
				messages: {					
					
					"confirmPass": {
						equalTo: "<font color='red'>New password & confirm password doesn't match.</font>"
					}
				}
       });

	jQuery('#search_criteria').validate();	
	jQuery('#frmTimeInterval').validate();
	jQuery('#addRowFrmData').validate();
	jQuery('#frmaddcolTable').validate();

	//idea@192.168.0.18:/rand/form.php
	jQuery('#mainsearchformPost').validate();	

	jQuery('#make_payment_form_mode').validate();

	//to validate user update profile
	jQuery('#updateuser').validate({
		
		onkeyup: false,
		onclick: false,
		onchange:true,

		rules:{
			"username":{
				minlength: 6,
				remote: {
					url: URL_SITE+"/admin/check_availability.php",
					type: "post",
					data: {
						username: function() {
							return $( "#username" ).val();
						},
						oldusername: function() {
							return $( "#oldusername" ).val();
						}
					}
				}
			}
		},
		messages:{
			"username":{
				minlenght: "Username should be at least 6 characters long",
				remote: jQuery.format("Username already exists")
			}
		}
	});
	//to validate user upload image form	

	jQuery('#proprofileimage').validate();
	jQuery('#editprofileimage').validate();

	jQuery('#frmAllNewsadd').validate({

		onkeyup: false,
		onclick: false,
		onchange:true,
		
		rules: {
			"news_title": {
				required: true,
				remote: {
					url: URL_SITE+"/admin/check_availability.php",
					type: "post",
					async: false,
					data: {
						action: "add"						 
					}
				}
			}			
		},
		messages: {
			"news_title": {
				required: "Please enter a valid News Title.",
				remote: "News Title already exist."
			}
		}
	});

	jQuery('#frmAllNewsedit').validate({

		onkeyup: false,
		onclick: false,
		onchange:true,
		
		rules: {
			"news_title": {
				required: true,
				remote: {
					url: URL_SITE+"/admin/check_availability.php",
					type: "post",
					async: false,
					data: {
						action: "edit",
						current_news_title: function () {
						return jQuery("#current_news_title").val();
						}  
					}
				}
			}			
		},
		messages: {
			"news_title": {
				required: "Please enter a valid News Title.",
				remote: "News Title already exist."
			}
		}
	});

	jQuery('#frmAllPages').validate(/*{
		rules:{
			page_name:{
				remote:URL_SITE+"/admin/check_availability.php"
			}
		},
		messages:{
			page_name:{
				remote: jQuery.format("<font color='red'>Page Name already Exists</font>")
			}
		}
	}*/);

	jQuery('#contactusform').validate();
	
	// Top Search Form Validations
	jQuery('#front_searchForm').validate({
		highlight: function(element, errorClass) {
			$(element).attr('style', 'border-color: red;');
		},
		errorPlacement: function(error, element) {
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).attr('style', 'border-color: #ccc;');
		}
	});

	jQuery('#select_share_db').validate();

	jQuery('#addsinglemultipleUserType').validate();
	jQuery('#addinstitutionusertype').validate({
		
		onkeyup: false,
		onclick: false,
		onchange:true,
		
		rules: {
			"username": {
				required: true,
				remote: {
					url: URL_SITE+"/admin/check_availability.php?fromip=1",
					type: "post",
					async: false,
					data: {
						action: "add",
						email: function () {
						return jQuery("#username").val();
						}  
					}
				}
			},
			"ip_range_from": {
				required: true,
				remote: {
					url: URL_SITE+"/admin/check_availability.php?fromip=1",
					type: "post",
					async: false,
					data: {
						action: "add"					 
					}
				}
			},
			"ip_range_to": {
				required: true,
				remote: {
					url: URL_SITE+"/admin/check_availability.php?toip=1",
					type: "post",
					async: false,
					data: {
						action: "add",
						ip_range_from: function () {
						return jQuery("#ip_range_from").val();
						}  
					}
				}
			}	
		},
		messages: {
			"username": {
				required: "Please enter a valid Email.",
				remote:   "Email Id already Exist."
			},
			"ip_range_from": {
				required: "Please enter a valid IP address.",
				remote:   "Please enter a valid IP address."
			},
			"ip_range_to": {
				required: "Please enter a valid IP address.",
				remote:   "Please enter a IP address greater than from IP address."
			}
		}	
		
	});

	jQuery('#addiprangesbyuserform').validate();

	jQuery('#addiprangesbyuserformdisable').validate({
		
		onkeyup: false,
		onclick: false,
		onchange:true,
		
		rules: {
			"ip_range_from": {
				required: true,
				remote: {
					url: URL_SITE+"/admin/check_availability.php?fromip=1",
					type: "post",
					async: false,
					data: {
						action: "add"					 
					}
				}
			},
			"ip_range_to": {
				required: true,
				remote: {
					url: URL_SITE+"/admin/check_availability.php?toip=1",
					type: "post",
					async: false,
					data: {
						action: "add",
						ip_range_from: function () {
						return jQuery("#ip_range_from").val();
						}  
					}
				}
			}	
		},
		messages: {
			"ip_range_from": {
				required: "Please enter a valid IP address.",
				remote:   "Please enter a valid IP address."
			},
			"ip_range_to": {
				required: "Please enter a valid IP address.",
				remote:   "Please enter a IP address greater than from IP address."
			}
		}	
		
	});

	jQuery('#addconfirmpaymentdetailform').validate();

	jQuery('#frmMultipleUser').validate({
		rules:{
			"email":{
				remote:"checkEmail.php"
			}
		},
		messages:{
			"email":{
				remote: jQuery.format("Email already exists")
			}
		}
	});

	jQuery('#frmContentadd').validate();
	jQuery('#frmContentedit').validate();
	
});
