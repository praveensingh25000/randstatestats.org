function loader_show(){
	jQuery.blockUI({ message:'<div style="background:#E8E8E8;width:30%;"><center><img src="/images/ajax-loader.gif" alt="Loading Please Wait..."></center><div>', css: {top:'40%',left:(jQuery(window).width() - 180) /2 + 'px',width: '400px', border: '5px  #ffffff', padding:'5px',fadeIn: '1000'}, overlayCSS: { backgroundColor: 'none',opacity:'0.4' }});
}

function loader_unshow(){
	jQuery.unblockUI(); 
}

function blockUI_object(msgobj){
	jQuery.blockUI({ message: msgobj, css: { width: '700px', border: '0px solid #cccccc', padding:'10px', height:'auto', overflow:'hidden', top: '10%', left:'30%' } });
}

function blockUI_divid(divid){
	jQuery.blockUI({ message: jQuery('#'+divid+''), css: { width: '700px', border: '0px solid #cccccc', padding:'10px', height:'auto', overflow:'hidden', top: '10%', left:'50%' } });
}

function showAttributes(div,divid){
	
	jQuery('.allGraph').hide();
	
	if($('#'+divid+'').attr('checked')) {
		jQuery('#'+div+'').show();
	}
}

function delete_action(){
	if(confirm("This Action cannot be undone. Are you sure you want to perform this action?")){		 
		return true;
	}else{
		return false;
	}
}

/*jQuery(document).ready(function(){
	jQuery("#login_link").click(function(){
		alert('true');
		$('#mainLoginDiv').show();
		$('#forgotPwDiv').hide();
	});
});*/

function login_link(){
	$('#mainLoginDiv').show();
	$('#forgotPwDiv').hide();
	$('#pw_sending_div').hide();
	return true;
}


//rand/login_popup.php
jQuery(document).ready(function(){

	jQuery("#forgotPwEmail").click(function(){
		jQuery('#showdiv').hide();
	});

	jQuery("#forgotpasswordForm").submit(function(e){
		
		e.preventDefault();			
		var pass_msg = jQuery("#forgotpasswordForm").valid();
		
		//some validations
		if(pass_msg == false){
			return false;
		} else {
			var user_email=jQuery("#forgotPwEmail").val();
			jQuery('#showdiv').remove();
			jQuery("#pw_sending_div").hide();
			jQuery(".password_loader_div").show();		
			
			jQuery.ajax({
				type: "POST",
				data: jQuery("#forgotpasswordForm").serialize(),
				url : URL_SITE+"/login_popup.php?forgot_pw=1",
				
				success: function(msg) {

					if($.trim(msg) == 'false') {
						if(!jQuery(".showdiv").hasClass("showerror"))
						jQuery("#forgotPwEmail").after('<label id="showdiv" for="name" generated="true" class="error showerror">Email doesnot Exists.</label>').show();
						return false;
					} else {
						jQuery("#pw_sending_div").html(msg).show();
						jQuery('#forgotPwDiv').show();
						jQuery('#showdiv').remove();
						jQuery(".password_loader_div").hide();
						return true;
					}
				}
			});
		}
	});
});

//rand/login_popup.php
jQuery(document).ready(function(){
	jQuery("#user_login_form_popup").submit(function(e){
		
		e.preventDefault();		
		var pass_msg = jQuery("#user_login_form_popup").valid();
		
		//some validations
		if(pass_msg == false){
			return false;
		} else {
			var user_email=jQuery("#email").val();			
			jQuery(".loading_login").show();			
			jQuery.ajax({
				type: "POST",
				data: jQuery("#user_login_form_popup").serialize(),
				url : URL_SITE+"/login_popup.php?logging=1",
				
				success: function(msg) {
					jQuery(".loading_login").hide();
					if(jQuery.trim(msg)=='notmembership') {						
						jQuery('.login-popup').fadeOut(300);
						loader_show();
						window.location=URL_SITE+"/login_popup.php?getresultsajaxrequest=1&buyPlan=1&session="+user_email;							
					} else if(jQuery.trim(msg)=='membership') {
						jQuery('.login-popup').fadeOut(300);	
						loader_show();
						var refererfile = '';
						if(jQuery('#referer_file')){
							if(jQuery('#referer_file').val() != ''){
								refererfile = jQuery('#referer_file').val();
							}
						}
					
						window.location=URL_SITE+"/login_popup.php?getresultsajaxrequest=1&succuss=1&session="+user_email+"&redirect="+refererfile;
						
						
					} else if(jQuery.trim(msg)=='noiprangesentered') {
						jQuery('.login-popup').fadeOut(300);	
						loader_show();
						window.location=URL_SITE+"/login_popup.php?getresultsajaxrequest=1&noiprangesentered=1&session="+user_email;						
					} else if(jQuery.trim(msg)=='notmembershipinstitution') {
						jQuery('.login-popup').fadeOut(300);	
						loader_show();
						window.location=URL_SITE+"/login_popup.php?getresultsajaxrequest=1&notmembershipinstitution=1&session="+user_email;						
					} else if(jQuery.trim(msg)=='notpaymentconfirmation') {
						jQuery('.login-popup').fadeOut(300);	
						loader_show();
						window.location=URL_SITE+"/login_popup.php?getresultsajaxrequest=1&notpaymentconfirmation=1&session="+user_email;						
					} else {
						jQuery(".display_error_msg").html(msg);	
						jQuery(".display_error_msg").show();	
					}					
				}							
			});	
		}
	});	 
});

function saveTable()
{
	var tablename = $('input[name="tablename"]').val();
	table_name_json = $('#table_name_json').html();
	table_name_json = jQuery.parseJSON(table_name_json);
	$.each(table_name_json,function (index,value){
		if(value==tablename)
		{
			table_exist = true;
			
		}
	});
	if(typeof table_exist =='boolean')
	{
		alert('Table Name Already Exist');
		return false;
		var table_exist='';
	}
	else
	{
		return true;
	}
}

function loadTableData(obj)
{
	id='';
		/*$.each($('.table_check'), function (index){
			if(jQuery(this).is(':checked'))
			{
				id+=jQuery(this).val()+',';				// names of table for a DB
			}
		});*/
	id+=jQuery(obj).val();	
	if(jQuery(obj).is(':checked'))
	{		
		
		queryString = '?ids='+id+',';
	//}
	//else
	//{
		//queryString = '?ids=null';
	//}
		var ajaxRequest;  // The variable that makes Ajax possible!
	jQuery('.otherTables').hide();
	jQuery('.otherTables').html('');
	try{		
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				return false;		// if any alien tries :-D
			}
		}
	}
	// Create a function that will receive data sent from the server
	var ajaxDisplay = document.getElementById('loading_div');
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			ajaxDisplay.innerHTML = '';
			//var result_div = document.getElementById('ajax_result');
			var result_div = document.getElementById('id_'+id);
			result_div.innerHTML=ajaxRequest.responseText;
			jQuery('#id_'+id+'').show();
			
		}
		if(ajaxRequest.readyState == 2)
		{			
			ajaxDisplay.innerHTML = 'Loding Data Please Wait...';
		}
	}
	
	ajaxRequest.open("GET", URL_SITE+"/admin/loadTableRows.php" + queryString, true);
	ajaxRequest.send(null);
	}
	else
	{
		
		var result_div = document.getElementById('id_'+id)
		result_div.innerHTML='';
		
	}
}

function selectDatabaseFunction(planid,section_type){
	jQuery("#show_selected_database_values").prepend('<div class="bodyLoader blockUI blockMsg blockPage"><h1>Loading....Please wait.</h1></div>');	

	jQuery.ajax({
		type: "POST",
		data: 'planid='+planid+"&section_type="+section_type,
		url : URL_SITE+"/admin/adminAction.php?select_database=1",		
		success: function(msg){										
		jQuery('.blockUI').remove();
		jQuery("#show_selected_database_values").html(msg).show();
		}							
	});	
}
//function URL: admin/subscriptions.php
function deleteSubscriptionPlans(id,planid,section_type){
	if(section_type=='single'){
		var msg_display='Deleting this plan will delete all the related multiple and other plans also. Are you sure you want to perform this action?';	
	}else if(section_type=='multiple'){
		var msg_display='Are you sure you want to delete this multiple plan?';	
	}else if(section_type!='single' && section_type!='multiple'){
		var msg_display='Are you sure you want to delete this '+section_type+' plan?';	
	}	
	if(confirm(msg_display)){
		jQuery("#show_selected_database_values").prepend('<div class="bodyLoader blockUI blockMsg blockPage"><h1>Loading....Please wait.</h1></div>');	
		jQuery.ajax({
			type: "GET",
			url:URL_SITE+"/admin/adminAction.php?confirm_delete=1&id="+id+"&planid="+planid,
			success: function(msg){								
				jQuery('.blockUI').remove();
				if($.trim(msg)=='true'){					
					location.reload();	
				}else{
					alert('Sorry! Error occurs due to network problem');
				}
			}
		});
	}else{
		return false;
	}
}

function claculateDiscountFunction(planid,section_type){	
	var discounts=jQuery("#claculatediscountfunction_"+planid).val();
	if(discounts <= 100){		
		jQuery("#show_selected_database_values").prepend('<div class="bodyLoader blockUI blockMsg blockPage"><h1>Loading....Please wait.</h1></div>');		
		jQuery.ajax({
			type: "POST",
			data: 'planid='+planid+"&discounts="+discounts+"&section_type="+section_type,
			url : URL_SITE+"/admin/adminAction.php?select_database=1",		
			success: function(msg){			
			jQuery('.blockUI').remove();
			jQuery("#show_selected_database_values").html(msg).show();
			}							
		});	
	}else{
	alert('Discount amount should be less than or equal to 100');
	return false;
	}
}

//body loader
jQuery(document).ready(function(){
	jQuery("#frmaddall_subscription_plan").submit(function(){
		var validate=jQuery("#frmaddall_subscription_plan").valid();
		if(validate){
			if(jQuery("#plan_already_exits").hasClass("planalreadyexits")){
			return false;
			}else if(jQuery("#number_of_user_already_exits").hasClass("numberofuseralreadyexits")) {
			return false;
			}else{
			jQuery(".containerLadmin").prepend('<div class="bodyLoader blockUI blockMsg blockPage"><h1>Loading....Please wait.</h1></div>');
			return true;
			}
		}else{
		return false;
		}
	});		
	//onload to remove loding div
	jQuery('.bodyLoader').remove();
});


//function for checking Subscription Plan Availability
function checkPlanValidity(validity_type,validity_div){
	var validity=jQuery("#"+validity_div).val();
	if(validity > '0' && validity_type !=''){	
		jQuery.ajax({
			type: "GET",
			data: '',
			url : URL_SITE+"/checkemailAvailability.php?validity_type="+validity_type+"&validity="+validity,	
			success: function(msg){		
			if($.trim(msg) == "false"){
			if(!jQuery("#plan_already_exits").hasClass("planalreadyexits"))			
			jQuery("#"+validity_div).after('<label id="plan_already_exits" for="'+validity_div+'" generated="true" class="planalreadyexits"><font color="red">Plan already Exist.</font></label>');			
			}else{
			jQuery('#plan_already_exits').remove();
			}			
			}							
		});	
	}
}

//function for checking Subscription Plan Availability
function checknumberofusersPlanAvailability(number_of_users_type,number_of_users_div){
	var numberofuser=jQuery("#"+number_of_users_div).val();
	if(numberofuser > '0' && number_of_users_type !=''){	
		jQuery.ajax({
			type: "GET",
			data: '',
			url : URL_SITE+"/checkemailAvailability.php?number_of_users_type="+number_of_users_type+"&numberofuser="+numberofuser,	
			success: function(msg){		
			if($.trim(msg) == "false"){
			if(!jQuery("#number_of_user_already_exits").hasClass("numberofuseralreadyexits"))			
			jQuery("#"+number_of_users_div).after('<label id="number_of_user_already_exits" for="'+number_of_users_div+'" generated="true" class="numberofuseralreadyexits"><font color="red">No. of user already Exits.</font></label>');			
			}else{
			jQuery('#number_of_user_already_exits').remove();
			}			
			}							
		});	
	}
}

//admin/browse.php
function checkColoumName(tablename) {
	var columnname = jQuery('#columnname').val();	
	if(columnname!='' && tablename !=''){
		jQuery.ajax({
			type: "GET",
			data: '',
			url : URL_SITE+"/admin/adminAction.php?columnname="+columnname+"&tablename="+tablename,	
			success: function(msg){		
				if($.trim(msg) == "false"){
					if(!jQuery("#coloum_already_exits").hasClass("coloumexits"))			
					jQuery("#columnname").after('<label id="coloum_already_exits" for="'+columnname+'" generated="true" class="error coloumexits">Colum name already Exist.</label>');			
				}else{
					jQuery('#coloum_already_exits').remove();
				}			
			}								
		});			
	}else{
	return false;
	}
}

jQuery(document).ready(function(){
	jQuery('#updaterowcoldata').click(function(){
		if(!jQuery("#coloum_already_exits").hasClass("coloumexits"))
		jQuery("#frmaddcolTable").submit();
		else
		return false;									
	});
});

function check(action, idarray){								
	var atLeastOneIsChecked = $('input[name="'+idarray+'[]"]:checked').length > 0;
	if(action == "delete"){
		var confirmcheck = confirm("Are you sure you want to delete them");
		if(!confirmcheck){
			return false;
		}
	}

	if(atLeastOneIsChecked){
		return true;
	} else {
		alert("Please tick the checkboxes first");
	}

	return false;
}

function selectAllPlansforSubscription(subscriptionid,user_type){
	if(subscriptionid != ''){
		
		//jQuery(".ui-tabs-selected").removeClass('selected');
		//jQuery("#active_"+subscriptionid).addClass('selected');
		loader_show();
		jQuery("#show_selected_payment_form_mode").hide();
		
		jQuery.ajax({
			type: "POST",
			data: "subscriptionid="+subscriptionid+"&user_type="+user_type,
			url : URL_SITE+"/frontAction.php?selectplan=1",						
			success: function(msg){				
				loader_unshow();
				jQuery("#show_plan_ajax_detail").html(msg).show();					
			}							
		});	
	}else{
		return false;
	}							
}

function selectinstitutionPlansforSubscription(subscriptionid ,institution_type_id ,user_type){

	if(subscriptionid != ''){
		loader_show();
		jQuery("#show_selected_payment_form_mode").hide();
		jQuery.ajax({
			type: "POST",
			data: "subscriptionid="+subscriptionid+"&user_type="+user_type+"&institution_type_id="+institution_type_id,
			url : URL_SITE+"/frontAction.php?selectplan=1",						
			success: function(msg){				
				loader_unshow();
				jQuery("#institution_type_plan_ajax_detail").html(msg).show();
			}							
		});	
	}else{
		return false;
	}							
}

function selectdetailPlansforSubscription(planid ,user_type){

	if(planid != ''){
		loader_show();
		jQuery("#show_selected_payment_form_mode").hide();
		jQuery.ajax({
			type: "POST",
			data: "planid="+planid+"&user_type="+user_type,
			url : URL_SITE+"/frontAction.php?plandetail=1",						
			success: function(msg){				
				loader_unshow();
				jQuery("#show_plan_ajax_detail1_particular").html(msg).show();
			}							
		});	
	}else{
		return false;
	}							
}
function selectdetailPlansforSubscriptionForAdmin(planid ,user_type){

	if(planid != ''){
		loader_show();
		jQuery("#show_selected_payment_form_mode").hide();
		jQuery.ajax({
			type: "POST",
			data: "planid="+planid+"&user_type="+user_type,
			url : URL_SITE+"/frontAction.php?plandetail=1&admin=1",						
			success: function(msg){				
				loader_unshow();
				jQuery("#show_plan_ajax_detail1_particular").html(msg).show();
			}							
		});	
	}else{
		return false;
	}							
}

function refreshCaptcha(){
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}

function generateCC(){
	var cc_number = new Array(16);
	var cc_len = 16;
	var start = 0;
	var rand_number = Math.random();

	switch(document.make_payment_form_mode.creditCardType.value)
	{
		case "Visa":
			cc_number[start++] = 4;
			break;
		case "Discover":
			cc_number[start++] = 6;
			cc_number[start++] = 0;
			cc_number[start++] = 1;
			cc_number[start++] = 1;
			break;
		case "MasterCard":
			cc_number[start++] = 5;
			cc_number[start++] = Math.floor(Math.random() * 5) + 1;
			break;
		case "Amex":
			cc_number[start++] = 3;
			cc_number[start++] = Math.round(Math.random()) ? 7 : 4 ;
			cc_len = 15;
			break;
	}

	for (var i = start; i < (cc_len - 1); i++) {
		cc_number[i] = Math.floor(Math.random() * 10);
	}

	var sum = 0;
	for (var j = 0; j < (cc_len - 1); j++) {
		var digit = cc_number[j];
		if ((j & 1) == (cc_len & 1)) digit *= 2;
		if (digit > 9) digit -= 9;
		sum += digit;
	}

	var check_digit = new Array(0, 9, 8, 7, 6, 5, 4, 3, 2, 1);
	cc_number[cc_len - 1] = check_digit[sum % 10];

	document.make_payment_form_mode.creditCardNumber.value = "";
	for (var k = 0; k < cc_len; k++) {
		document.make_payment_form_mode.creditCardNumber.value += cc_number[k];
	}
}


function sortDropDownListByText(selectID) {
	// Loop for each select element on the page.
	$("select#"+selectID).each(function() {
 
		// Keep track of the selected option.
		var selectedValue = $(this).val();
 
		// Sort all the options by text. I could easily sort these by val.
		$(this).html($("option", $(this)).sort(function(a, b) {
			return a.text == b.text ? 0 : a.text < b.text ? -1 : 1
		}));
 
		// Select one option.
		$(this).val(selectedValue);
	});
}

function showDiv(divID){
	$("#"+divID).show();
}

function hideDiv(divID){
	$("#"+divID).hide();
}

jQuery(document).ready(function(){
	jQuery("#frmAssociatedTables").submit(function(){
		jQuery("select#selectedTables").each(function() {
			 jQuery("select#selectedTables option").attr("selected","selected");
		});
	});
});

$(document).ready(function(){
  $("#eco").click(function(){
    $("#eco-detail").toggle("slow");
  });
	 $("#com").click(function(){
    $("#com-detail").toggle("slow");
  });
  $("#edu").click(function(){
    $("#edu-detail").toggle("slow");
  });
  $("#soc").click(function(){
    $("#soc-detail").toggle("slow");
  });
  $("#pop").click(function(){
    $("#pop-detail").toggle("slow");
  });
  $("#fin").click(function(){
    $("#fin-detail").toggle("slow");
  });
  $("#ene").click(function(){
    $("#ene-detail").toggle("slow");
  });
});
$(document).ready(function() {
	$('a.login-window').click(function() {
		
		// Getting the variable's value from a link 
		var loginBox = $(this).attr('href');

		//Fade in the Popup and add close button
		$(loginBox).fadeIn(300);
		//Set the center alignment padding + border
		var popMargTop = ($(loginBox).height() + 24) / 2; 
		var popMargLeft = ($(loginBox).width() + 24) / 2; 
		
		$(loginBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// Add the mask to body
		$('body').append('<div id="mask"> </div>');
		$('#mask').fadeIn(300);
		
		return false;
	});
	
	// When clicking on the button close or the mask layer the popup closed
	$('.btn_close,a.close, #mask').live('click', function() { 
	  $('#mask , .login-popup').fadeOut(300 , function() {
		  $('#mask').remove(); 
		  $("#popup_login_div").hide();
		  $("#notify_content_div").hide();
	}); 
	return false;
	});
});

function checkLoginUser(form_type,setting,control_type,key){

	if(form_type=='one_stage'){

		if(control_type=='select'){

			var validate_type	=	'ok';
			$( ".selectboxes" ).each(function( index ) {
				if(jQuery(this).hasClass('required')){
					jQuery('label#'+jQuery(this).attr('id')+'').remove();
					jQuery(this).after('<label id="'+jQuery(this).attr('id')+'" class="fieldrequired error" for="input" generated="true" class="error">This field is required</label>');
					validate_type	=	'';
				}else{
					jQuery('label#'+jQuery(this).attr('id')+'').remove();

				}
			});

		} else if(control_type=='checkbox'){
			var validate_type = new Array();
			jQuery("input:checked").each(function() {  validate_type.push(jQuery(this).val()); });
		} else if(control_type=='radio'){		
			var validate_type = new Array();
			jQuery("input:checked").each(function() {  validate_type.push(jQuery(this).val()); });
		} else {
			var validate_type	=	'';
		}

		if(setting=='true'){		
			if((validate_type==null || validate_type=='')){
				if(!jQuery("#field_required").hasClass('fieldrequired') && control_type!='select')
				jQuery(".field_required_div_"+key).after('<label id="field_required" class="fieldrequired error" for="input" generated="true" class="error">This field is required</label>');
				//alert('Please Select at least one field.');
				return false;
			}else{
				return true;
			}
		} else {	
			if(validate_type==null || validate_type==''){			
				if(!jQuery("#field_required").hasClass('fieldrequired') && control_type!='select')
				jQuery(".field_required_div_"+key).after('<label id="field_required" class="fieldrequired error" for="input" generated="true" class="error">This field is required</label>');
				//alert('Please Select at least one field.');
				return false;
			}else{
				jQuery(".fieldrequired").remove();
				loader_show();
				jQuery.ajax({
					type: "POST",
					data: jQuery("#mainsearchformPost").serialize(),
					url : URL_SITE+"/login_popup.php?posting=1",
					
					success: function(msg){						
						loader_unshow();
						
						//Fade in the Popup and add close button				
						jQuery(".twostageform").fadeIn(300);
						
						//Set the center alignment padding + border
						var popMargTop = (jQuery(".twostageform").height() + 100) / 2; 
						var popMargLeft = (jQuery(".twostageform").width() + 100) / 2; 
						
						jQuery(".twostageform").css({ 
							'margin-top' : -popMargTop,
							'margin-left' : -popMargLeft
						});
						
						// Add the mask to body
						jQuery('body').append('<div id="mask"> </div>');
						jQuery('#mask').fadeIn(300);				
						
						return false;
					}							
				});			
				return false;
			}
		}
	}

	if(form_type=='two_stage'){

		var validate_type	=	jQuery('#frmPost').valid();

		if(validate_type == true){

			if(setting=='true'){				
				return true;				
			} else {
				var redirect = control_type;
				loader_show();
				jQuery.ajax({
					type: "POST",
					data: jQuery("#frmPost").serialize(),
					url : URL_SITE+"/login_popup.php?twostageurl="+redirect,
					
					success: function(msg){
						
						loader_unshow();
												
						//Fade in the Popup and add close button				
						jQuery(".twostageform").fadeIn(300);
						
						//Set the center alignment padding + border
						var popMargTop = (jQuery(".twostageform").height() + 100) / 2; 
						var popMargLeft = (jQuery(".twostageform").width() + 100) / 2; 
						
						jQuery(".twostageform").css({ 
							'margin-top' : -popMargTop,
							'margin-left' : -popMargLeft
						});
						
						// Add the mask to body
						jQuery('body').append('<div id="mask"> </div>');
						jQuery('#mask').fadeIn(300);				
						
						return false;
					}							
				});			
				return false;
			}
		} else {
			return false;
		}
	}
}

$(document).ready(function(){
	$("#additional-deatil").hide();
	$("#add").click(function(){
		$("#additional-deatil").toggle('slow');
		$("#additional-deatil").toggleClass('close');
		if($("#additional-deatil").hasClass('close')){
			$("#togglebutton").addClass('minus');
			$("#togglebutton").removeClass('plus');
		} else {
			$("#togglebutton").addClass('plus');
			$("#togglebutton").removeClass('minus');
		}
	});
});

function checkyear(){
	var strtyear=parseInt(jQuery("#syear").val());
	var endyear=parseInt(jQuery("#eyear").val());
	if(strtyear>endyear){
		alert('Start year should be less than end year.');
		$("#syear").val($("#syear option:first").val());
		$("#eyear").val($('#eyear option:last-child').val());
		
		//$('.submitbtn').attr('disabled', 'disabled');
	}else{
		$('.submitbtn').removeAttr('disabled');
	}
}

function checkMonth(){
	var strtmth = parseInt(jQuery("#smonth").val());
	var endmth  = parseInt(jQuery("#emonth").val());

	if(strtmth > endmth){
		alert('Start Month should be less than End Month.');
		$("#smonth").val($("#smonth option:first").val());
		$("#emonth").val($('#emonth option:last-child').val());
	}else{
		$('.submitbtn').removeAttr('disabled');
	}
}

$(document).ready(function(){
	jQuery('#eyear').change(function(){
		checkyear();
	});

	jQuery('#syear').change(function(){
		checkyear();
	});
	
	$("#simplePrint").click(function() {
		printElem({ overrideElementCSS: true });
		//printElem({  leaveOpen: true, printMode: 'popup' });

		//var sOption="toolbar=yes,location=no,directories=yes,menubar=yes,scrollbars=yes,width=900,height=300,left=100,top=25";
		//var content = jQuery('.toPrint').html();

		//var headerinnershell = jQuery('#inner-mainshell').html();
		//var innernav = jQuery('.categorie-nav').html();

		//var winprint=window.open("","",sOption);
		//winprint.document.open();
		//winprint.document.write('<html><head><link rel="stylesheet" href="/css/style.css" type="text/css" media="print, projection, screen" />');
		//winprint.document.write('<style type="text/css" media="print"> @page{ size: landscape; margin: 2cm; } </style></head><body onload="window.print();"><div id="wrapper"><header><div id="inner-mainshell">'+headerinnershell+'</div><nav class="categorie-nav">'+innernav+'</nav></header><section id="container">');
		//winprint.document.write(content);
		//winprint.document.write('</section></div></body></html>');
		//winprint.document.close();
		//winprint.focus();

	});
	
	$("#simplePrintUrl").click(function() {
		printElemUrl({ overrideElementCSS: true });
	});

});

function enablesubmit()
{
  $('#submittypebtn').removeAttr('disabled');
}

function printElem(options){
	$('.toPrint').printElement(options);
}

function printElemUrl(options){
	$('.toPrintGrid').printElement(options);
}

function refreshCaptchaCode(){
	jQuery.ajax({
		type: "POST",
		data: "",
		url : URL_SITE+"/captcha_code_file.php",		
		success: function(msg){										
			jQuery("#captcha_code").html(msg);
		}							
	});	
}

function selectSubCategoryAll(sharedbname,dbname,dbid,formid){

	if(jQuery("#db_select_"+dbid).is(":checked")){
		dbnameVal=dbname;
	} else {
		dbnameVal='none';
	}

	if(dbid !='' && dbname != ''){	
		loader_show();
		jQuery(".submitbtn-div").hide();
		jQuery.ajax({
			type: "POST",
			data: "dbname="+dbnameVal+"&sharedbname="+sharedbname+"&dbid="+formid,
			url : URL_SITE+"/admin/selectCategotyAction.php",						
			success: function(msg){													
				loader_unshow();
				jQuery(".submitbtn-div").show();
				
				if(dbname =='rand_california'){
					jQuery("#display_all_categoty_ajax_ca").html(msg).show();
					
				} else if(dbname =='rand_newyork'){
					jQuery("#display_all_categoty_ajax_ny").html(msg).show();
				} else {
					jQuery("#display_all_categoty_ajax_tx").html(msg).show();
				}
			}							
		});	
	}else{
		return false;
	}							
}

function popup_window(content) {
	var w = window.open('', '', 'width=400,height=400,resizeable,scrollbars');
	w.document.write(content);
	w.document.close(); // needed for chrome and safari
}

function pop_window_url(url){
	var w = window.open(url, 'popUpWindow','height=400, width=400, left=300, top=100, resizable=yes, scrollbars=yes, toolbar=yes, menubar=no, location=no, directories=no, status=yes');
	w.document.close();
}

$(document).ready(function() {
	$("#video-div").click(function(){
		
		// Getting the variable's value from a link 
		var loginBox = $(".videoplaydiv");

		//Fade in the Popup and add close button
		$(loginBox).fadeIn(300);
		//Set the center alignment padding + border
		var popMargTop = ($(loginBox).height() + 24) / 2; 
		var popMargLeft = ($(loginBox).width() + 24) / 2; 
		
		$(loginBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// Add the mask to body
		$('body').append('<div id="mask"> </div>');
		$('#mask').fadeIn(300);
		
		return false;
	});
	
	// When clicking on the button close or the mask layer the popup closed
	$('.btn_close_video,a.closevideo, #mask').live('click', function() { 
	  $('#mask , .videoplaydiv').fadeOut(300 , function() {
		  $('#mask').remove(); 
	}); 
	return false;
	});
});

jQuery(document).ready(function(){
	jQuery('#categoryid').change(function(){
		var categoryid = jQuery('#categoryid').val();

		if(categoryid != '0'){			
			jQuery(".unselect_class").removeClass("active");
			jQuery("#select_class_"+categoryid).addClass("active");
			loader_show();										
			jQuery.ajax({
				type: "POST",
				data: jQuery("#frmtoselectFormActtoCategory").serialize(),
				url : URL_SITE+"/frontAction.php?categoryid="+categoryid,						
				success: function(msg){				
					loader_unshow();
					jQuery("#show_all_forms").html(msg);
				}							
			});	
		}else{
			return false;
		}
	});
});

jQuery(document).ready(function(){
	jQuery('#check_all_users').click(function () {
		jQuery('.ids').attr('checked', this.checked);
	});
});

jQuery(document).ready(function(){
	jQuery('#by_user_range_type').click(function () {
		jQuery('#show_ip_ranges_div').hide();
		jQuery("#ip_range_from").removeClass("required");
		jQuery("#ip_range_to").removeClass("required");
	});

	jQuery('#by_admin_range_type').click(function () {
		jQuery('#show_ip_ranges_div').show();
		jQuery("#ip_range_from").addClass("required");
		jQuery("#ip_range_to").addClass("required");
	});
});

function checkUsers(action){							
	var atLeastOneIsChecked = $('input[name="ids[]"]:checked').length > 0;	
	if(atLeastOneIsChecked){
		if(action == "delete"){
			var confirmcheck = confirm("Are you sure you want to delete them");
			if(!confirmcheck){
				return false;
			}
		}
		return true;
	} else {
		alert("Please tick the checkboxes first");
	}
	return false;
}

function addValidityofUsers(userid,id){

	if(userid != '0'){	

		loader_show();		
		
		jQuery.ajax({
			type: "POST",
			data: "",
			url : URL_SITE+"/admin/adminAction.php?adddays="+userid+"&id="+id,						
			
			success: function(msg){				
				
				loader_unshow();
				jQuery("#show_days_forms").html(msg);

				// Getting the variable's value from a link 
				var loginBox = $(".adddays_box");
				//$('#show_days_forms').show();

				//Fade in the Popup and add close button
				$(loginBox).fadeIn(300);
				//Set the center alignment padding + border
				var popMargTop = ($(loginBox).height() + 24) / 2; 
				var popMargLeft = ($(loginBox).width() + 24) / 2; 
				
				$(loginBox).css({ 
					'margin-top' : -popMargTop,
					'margin-left' : -popMargLeft
				});
				
				// Add the mask to body
				$('body').append('<div id="mask"> </div>');
				$('#mask').fadeIn(300);								
				return false;				
			}							
		});	

	} else {
		return false;
	}														
}

jQuery(document).ready(function(){
	jQuery("#addinstitutionusertype").submit(function(e){
		
		e.preventDefault();		
		var pass_msg = jQuery("#addinstitutionusertype").valid();
		
		//some validations
		if(pass_msg == false){
			return false;
		} else {
			loader_show();			
			jQuery.ajax({
				type: "POST",
				data: jQuery("#addinstitutionusertype").serialize(),
				url : URL_SITE+"/admin/adminAction.php?addingDetail=1",
				
				success: function(msg) {
					loader_unshow();									
					jQuery("#show_institution_result").html(msg);		
				}							
			});	
		}
	});	
});

//added by praveen singh on June 29, 2013.
function ApproveDissaprove(userid, ipid, status) {

	if(status=='1'){
		var message='Do you really want to Approve this IP';
	} else {
		var message='Do you really want to Dis-approve this IP';
	}

	if(confirm(message)) {
		loader_show();
		jQuery.ajax({
			type: "POST",
			data:"",
			url:URL_SITE+"/admin/adminAction.php?admin_ip_action=1&status="+status+"&ipid="+ipid+"&userid="+userid,
				
			success: function(msg) {
				loader_unshow();
				jQuery(".show_selected_result_"+ipid).html(msg);					
				jQuery("#hide_new_link_"+userid).hide();
			}
		});
	} else {
		return false;
	}														
}

jQuery(document).ready(function(){
	jQuery('#searchContent').keyup(function() { 
		if(jQuery(this).val() == ''){
			jQuery("#grid_view td.dbname").parent().show();
			jQuery("#no_result").hide();		
		} else {
			var value=jQuery("#grid_view td.dbname:contains('" + jQuery(this).val() + "')").html();
		
			if(typeof(value) =='undefined'){
				jQuery("#grid_view td.dbname:not(:contains('" + jQuery(this).val() + "'))").parent().hide();
				jQuery('#no_result').show();				
			}else{
				jQuery("#grid_view td.dbname:contains('" + jQuery(this).val() + "')").parent().show();
				jQuery("#grid_view td.dbname:not(:contains('" + jQuery(this).val() + "'))").parent().hide();
				jQuery('#no_result').hide();		
			}
		}
	});
});

jQuery(document).ready(function(){
	
	//jQuery('ul li a').click(function() {	 
		//if(!jQuery('#universal_loader').hasClass('universal-loader'))
		//jQuery('.headertop').after('<div id="universal_loader" style=""  class="universal-loader">Loading...</div>');
		//jQuery('#universal_loader').fadeIn(300);
		//setInterval(function(){ jQuery('#universal_loader').fadeOut(300); },5000);
		//return true;
	//});

	jQuery('#mainsearchformPost').submit(function() {
		var validateform = jQuery('#mainsearchformPost').valid();
		if(validateform){
			if(!jQuery('#universal_loader').hasClass('universal-loader'))
			jQuery('.headertop').after('<div id="universal_loader" class="universal-loader">Loading...</div>');
		}
		return true;
	});	

	jQuery('#frmPost').submit(function() {
		var validateform = jQuery('#frmPost').valid();
		if(validateform){
			if(!jQuery('#universal_loader').hasClass('universal-loader'))
			jQuery('.headertop').after('<div id="universal_loader" class="universal-loader">Loading...</div>');
		}	
		return true;
	});	

	jQuery('#user_registration').submit(function() {
		var validateform = jQuery('#user_registration').valid();
		if(validateform){
			if(!jQuery('#universal_loader').hasClass('universal-loader'))
			jQuery('.headertop').after('<div id="universal_loader" class="universal-loader">Loading...</div>');
		}	
		return true;
	});		
});

function chckphone(id){
	var filter = /^[0-9]{3}-[0-9]{3}-[0-9]{4}$/;
	
	if(id == ''){
		id = 'name';
	}

	var number = $("#"+id+"").val();							
	 var test_bool = filter.test(number);
	 if(test_bool==false){
	  alert('Please enter phone number in US format');
	  $("#"+id+"").val('');
	  return false; 
	 }
}

jQuery(document).ready(function(){
	jQuery("#account_type_tp").click(function(){
		if(jQuery("#account_type_tp").is(":checked")){						
			jQuery(".activeclass").removeClass('active');
			jQuery("#activeFinal").addClass('active');
		}									
	});	
	jQuery("#account_type_pa").click(function(){						
		if(jQuery("#account_type_pa").is(":checked")){						
			jQuery(".activeclass").removeClass('active');
			jQuery("#activeInitial").addClass('active');
		}									
	});	
});

function selectDatabaseAmountFunction(planid,usertype,currentdbid,number_of_users){
	if(planid != '' && number_of_users!=''){
		var numSelected = $(".dbToSelect:checked").length;
		
		if(numSelected == 1) {
			jQuery(".dbToSelect:checked").each(function(i,k) {
				if(this.value==4){
					jQuery('.db_4').show();
					jQuery('.db_free_4').hide();
				}
			});
		}
		
		jQuery(".dbToSelect:checked").each(function(i,k) {
			if(this.value!=4){
				jQuery('#checked_unchecked_4').attr('checked','checked');
				jQuery('.db_4').hide();
				jQuery('.db_free_4').show();
			}
		});
		
		loader_show();
		jQuery.ajax({
			type: "POST",
			data: jQuery("#select_payment_form_mode").serialize(),
			url : URL_SITE+"/frontAction.php?selectdbAmount="+planid+"&usertype="+usertype+"&currentdbid="+currentdbid+"&number_of_users="+number_of_users,						
			success: function(msg){				
				loader_unshow();
				jQuery("#total_amount").html(msg);
				jQuery("#total_amount_input").val(jQuery.trim(msg));
				jQuery("#proceed_to_payment_type").show();
				jQuery("#show_selected_payment_form_mode").hide();
			}							
		});	

	} else {
		return false;
	}		
}


/********* Below code written by baljinder *********/
function getTotalFunction(){
	
	var plan = $('input:radio[name="plan_name"]:checked').val();
	
	var pageurl = 'calculate.php';

	if($('#pageurl').length){
		if($('#pageurl').val() != ''){
			pageurl = $('#pageurl').val();
		}
	}

	var us = 'false';
	jQuery(".dbToSelect:checked").each(function(i,k) {
		if(this.value!=4){
			jQuery('#checked_unchecked_4').attr('checked','checked');
			jQuery('.db_price_4').hide();
			jQuery('.db_free_4').show();

			us = 'true';
		}
	});

	if(us == 'false'){
		jQuery('.db_price_4').show();
		jQuery('.db_free_4').hide();
	}

	var usonly = 'no';
	
	jQuery('#planval').html(""+plan+" year");

	loader_show();
	jQuery.ajax({
		type: "get",
		data: jQuery("#make_payment_form_mode").serialize(),
		url : URL_SITE + '/' + pageurl,						
		success: function(msg){				
			loader_unshow();
			var objmsg = jQuery.parseJSON(msg);
			
			jQuery('#total_amount_input').val(objmsg.totalamount);

			jQuery('#surcharge_amount').html(objmsg.surcharge);
			jQuery('#surcharge_amount_input').val(objmsg.surcharge);

			jQuery('#discount_amount').html(objmsg.discountamount);
			jQuery('#discount_amount_input').val(objmsg.discountamount);

			if(objmsg.surchargeapplicable >0 && objmsg.states >0){
				if(objmsg.states == 1){
					var txti = objmsg.txtdollar + objmsg.surchargeapplicable + objmsg.txtper + " fee for adding 1 state";
				} else {
					var txti = objmsg.txtdollar + objmsg.surchargeapplicable + objmsg.txtper + " fee for adding "+ objmsg.states +" states";
				}

				jQuery('#surchargetxt').html(txti);
				jQuery('#surchargeamounttr').show();
				
			} else {
				jQuery('#surchargeamounttr').hide();
			}

			if(objmsg.discountamount >0 && objmsg.discount >0){
				if(objmsg.states == 1){
					var txti = objmsg.txtdollar + objmsg.surchargeapplicable + objmsg.txtper + " fee for adding 1 state";
				} else {
					var txti = objmsg.txtdollar + objmsg.surchargeapplicable + objmsg.txtper + " fee for adding "+ objmsg.states +" states";
				}

				jQuery('#discounttxt').html(objmsg.discounttxt);
				jQuery('#discountamounttr').show();
				
			} else {
				jQuery('#discountamounttr').hide();
			}
			
			if(objmsg.minimumapplicable == 1){
				jQuery('#total_amount').html(objmsg.totalamount + "*");
				jQuery('#minimumamounttxt').html("<br/>* Minimum annual amount");
			} else {
				jQuery('#total_amount').html(objmsg.totalamount);
			}


			if(objmsg.totalamount > 0){
				jQuery('#detailsCheckout').show();
			}
			//jQuery('#details').html(msg);
		}							
	});	
		
}



/********* Below code written by baljinder *********/
function getTotalFunctionPurchased(){
	
	var plan = $('input:radio[name="plan_name"]:checked').val();
	
	var pageurl = 'calculate.php';

	if($('#pageurl').length){
		if($('#pageurl').val() != ''){
			pageurl = $('#pageurl').val();
		}
	}

	var us = 'false';
	jQuery(".dbToSelect:checked").each(function(i,k) {
		if(this.value!=4){
			jQuery('#checked_unchecked_4').attr('checked','checked');
			jQuery('.db_price_4').hide();
			jQuery('.db_free_4').show();

			us = 'true';
		}
	});

	if(us == 'false'){
		jQuery('.db_price_4').show();
		jQuery('.db_free_4').hide();
	}

	var usonly = 'no';
	
	jQuery('#planval').html(""+plan+" year");

	loader_show();
	jQuery.ajax({
		type: "get",
		data: jQuery("#make_payment_form_mode").serialize(),
		url : URL_SITE + '/' + pageurl,						
		success: function(msg){				
			loader_unshow();
			var objmsg = jQuery.parseJSON(msg);
			
			jQuery('#total_amount_input').val(objmsg.totalamount);

			if(objmsg.discountamount >0 && objmsg.discount >0){
				if(objmsg.states == 1){
					var txti = objmsg.txtdollar + objmsg.surchargeapplicable + objmsg.txtper + " fee for adding 1 state";
				} else {
					var txti = objmsg.txtdollar + objmsg.surchargeapplicable + objmsg.txtper + " fee for adding "+ objmsg.states +" states";
				}

				jQuery('#discounttxt').html(objmsg.discounttxt);
				jQuery('#discountamounttr').show();
				
			} else {
				jQuery('#discountamounttr').hide();
			}
			
			if(objmsg.minimumapplicable == 1){
				jQuery('#total_amount').html(objmsg.totalamount + "*");
				jQuery('#minimumamounttxt').html("<br/>* Minimum annual amount");
			} else {
				jQuery('#total_amount').html(objmsg.totalamount);
			}


			if(objmsg.totalamount > 0){
				jQuery('#detailsCheckout').show();
			}
			//jQuery('#details').html(msg);
		}							
	});	
		
}


function checkAmount(){
	var amount = jQuery('#total_amount_input').val();
	if(amount <= 0){
		alert("Pleae choose database to be ordered.");
		return false;
	}
}


function getTotalFunctionAdmin(){
	
	var account_type = $('input:radio[name="account_type"]:checked').val();

	
	if(account_type == 'PA'){
		var plan = $('input:radio[name="plan_name"]:checked').val();

		var discountoffered = $('#discount').val();

		var us = 'false';

		var db_names = "";

		jQuery(".dbToSelect:checked").each(function(i,k) {
			if(this.value!=4){
				jQuery('#checked_unchecked_4').attr('checked','checked');
				jQuery('.db_price_4').hide();
				jQuery('.db_free_4').show();

				us = 'true';
			}
		
			db_names = db_names + '&db_name[]='+this.value;

		});

		if(us == 'false'){
			jQuery('.db_price_4').show();
			jQuery('.db_free_4').hide();
		}
		
		jQuery('#planval').html(""+plan+" year");
		var user_type  = jQuery("#user_type").val();

		var number_of_users  = jQuery("#number_of_users").val();

		loader_show();
		jQuery.ajax({
			type: "get",
			data: "discountoffered="+discountoffered+"&number_of_users="+number_of_users+"&plan_name="+ plan + "&user_type="+ user_type +"" + db_names,
			url : URL_SITE+"/admin/calculateAdmin.php",						
			success: function(msg){				
				loader_unshow();
				var objmsg = jQuery.parseJSON(msg);
				
				jQuery('#total_amount_input').val(objmsg.totalamount);

				jQuery('#surcharge_amount').html(objmsg.surcharge);
				jQuery('#surcharge_amount_input').val(objmsg.surcharge);

				jQuery('#discount_amount').html(objmsg.discountamount);
				jQuery('#discount_amount_input').val(objmsg.discountamount);

				if(objmsg.surchargeapplicable >0 && objmsg.states >0){
					if(objmsg.states == 1){
						var txti = objmsg.txtdollar + objmsg.surchargeapplicable + objmsg.txtper + " fee for adding 1 state";
					} else {
						var txti = objmsg.txtdollar + objmsg.surchargeapplicable + objmsg.txtper + " fee for adding "+ objmsg.states +" states";
					}

					jQuery('#surchargetxt').html(txti);
					jQuery('#surchargeamounttr').show();
					
				} else {
					jQuery('#surchargeamounttr').hide();
				}

				if(objmsg.discountamount >0 && objmsg.discount >0){
					if(objmsg.states == 1){
						var txti = objmsg.txtdollar + objmsg.surchargeapplicable + objmsg.txtper + " fee for adding 1 state";
					} else {
						var txti = objmsg.txtdollar + objmsg.surchargeapplicable + objmsg.txtper + " fee for adding "+ objmsg.states +" states";
					}

					jQuery('#discounttxt').html(objmsg.discounttxt);
					jQuery('#discountamounttr').show();
					
				} else {
					jQuery('#discountamounttr').hide();
				}

				jQuery('#actualcost').val(objmsg.actualamount);
				
				if(objmsg.minimumapplicable == 1){
					jQuery('#total_amount').html(objmsg.totalamount + "*");
					jQuery('#minimumamounttxt').html("<br/>* Minimum annual amount");
				} else {
					jQuery('#total_amount').html(objmsg.totalamount);
				}


				if(objmsg.totalamount > 0){
					jQuery('#detailsCheckout').show();
				}
				//jQuery('#details').html(msg);
			}							
		});	
	}
		
}

function planDetail(){
	var user_type  = jQuery("#user_type").val();

	var number_of_users  = jQuery("#number_of_users").val();
	if(user_type != '' && number_of_users > 0){
		loader_show();

		jQuery.ajax({
			type: "get",
			data: "user_type="+user_type+"&number_of_users="+number_of_users,
			url : URL_SITE+"/admin/planDetail.php",						
			success: function(msg){
				loader_unshow();
				jQuery('#plandetails').html(msg);
				jQuery('#purchasedetails').show();
			}
		});

	} else {
		alert('Please select user type and enter no of users');
		$('input:radio[name="account_type"]').removeAttr('checked');
		jQuery('#plandetails').html("");
		jQuery('#purchasedetails').hide();
	}
}

function planDetailWithPaymentId(paymentid){
	var user_type  = jQuery("#user_type").val();

	var number_of_users  = jQuery("#number_of_users").val();
	if(user_type != '' && number_of_users > 0){
		loader_show();

		jQuery.ajax({
			type: "get",
			data: "user_type="+user_type+"&number_of_users="+number_of_users+"&invoiceid="+paymentid,
			url : URL_SITE+"/admin/planDetail.php",						
			success: function(msg){
				loader_unshow();
				jQuery('#plandetails').html(msg);
				jQuery('#purchasedetails').show();
			}
		});

	} else {
		alert('Please select user type and enter no of users');
		$('input:radio[name="account_type"]').removeAttr('checked');
		jQuery('#plandetails').html("");
		jQuery('#purchasedetails').hide();
	}
}

function changePlan(usertype, pagename){
	window.location=URL_SITE+'/'+pagename+'?usertype='+usertype;
}
/********* Below code written by baljinder ends here*********/

//added by praveen singh on 07-08-2013
function customReset(){
	var selectinput   = jQuery('.token-input-token').html();
	var checkboxradio = (jQuery('input[type=checkbox],input[type=radio]').is(':checked'))?'Y':'N';	
	if((selectinput!='' && selectinput!= null) || checkboxradio == 'Y') {
		jQuery('.token-input-token').remove();
		jQuery('input[type=checkbox],input[type=radio]').removeAttr('checked');
		jQuery( ".selectboxes" ).addClass('required');
	}
}

//added by praveen singh on June 29, 2013.
jQuery(document).ready(function(){
	jQuery("#addiprangesbyuserform").submit(function(e){							
		e.preventDefault();			
		var pass_msg = jQuery("#addiprangesbyuserform").valid();
		jQuery("#display_add_success_message").hide();
		
		//some validations
		if(pass_msg == false){
			return false;
		} else {
			loader_show();
			
			jQuery.ajax({
				type: "POST",
				data: jQuery("#addiprangesbyuserform").serialize(),
				url : URL_SITE+"/frontAction.php?addIPRanges=1",
				
				success: function(msg){
					loader_unshow();	
					jQuery('#display_add_success_message').html(msg).show();
					jQuery('.editlink').show();
					jQuery('#display_add_success_message').delay(4000).fadeOut();
					jQuery('#ips').val('');
					return true;						
				}
			});
		}
	});
});