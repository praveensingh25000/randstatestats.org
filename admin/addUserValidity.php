<?php
/******************************************
* @Modified on 18 March 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://50.62.142.193/index.php
********************************************/

if(isset($_GET['notifyemail']) && isset($_GET['dbid'])) {

	$basedir=dirname(__FILE__)."/";
	include_once $basedir."/include/actionHeader.php";

	$user = new user();

	$notifyemail		 = $_GET['notifyemail'];
	$user_id			 = $_GET['userid'];
	$dbid   			 = $_GET['dbid'];
	$databasename   	 = $_GET['databaseToBeUse'];

	$checkemailalready=$user->checkemailNotify($notifyemail, $dbid ,$databasename);

	if(!empty($checkemailalready)) {
		echo 'Your Email has been already saved for Notification.<br>Thank You.';
	} else {
		$insertNotif = $user->insertNotification($user_id, $dbid, $notifyemail, $databasename);
		echo 'Your Email has been saved succussfully.<br>Thank You.';
	}
	return true;
	exit;
}
?>
<!-- NOTIFY WINDOW -->
<div class="header-popup login-popup adddays_box" id="login-box" style="display: none; margin-top: -180.5px; margin-left: -319px;">
	
	<a class="close adddaysdiv" href="#">
		<img alt="Close" title="Close Window" class="btn_close" src="<?php URL_SITE;?>/images/close_pop.png">
	</a> 

	<div id="notify">
		<h3>Add Validity in Days</h3>

		<form onsubmit="return fun_add_validity_users('<?php echo $_SESSION['user']['id'];?>');"method="POST" id="notifyupdatedform" name="notifyupdatedform" class="signin" action="">
			
			<div class="pT10 pB10 txtcenter display_succuss_msg" style="font-size:14px;color:#ffffff;display:none;"></div>

			<fieldset class="">
				<label class="useremail"> <span>Enter your Email</span>
					<input placeholder="email" type="text" id="notifyemail" name="notifyemail" class="email required"/>						
				</label>		
				<button type="submit" name="submitnotifyemail" class="submit button" id="submitnotifyemail">SUBMIT</button>
				<button type="button" class="button btn_close" >CANCEL</button>
				<br class="clear" />
				<label for="notifyemail" generated="true" style="padding-left:85px;display:none;" class="error pL30">This field is required.</label>
			</fieldset>

			<div style="display:none;" id="" class="saving_information txtcenter pT20 pB10"><h3>Saving Information...Please Wait....</h3></div>
		</form>

		<script language="javascript">
		jQuery(document).ready(function(){  
			jQuery('#notifyupdatedform').validate();
		});

		function fun_send_notify_users(userid, dbid,databaseToBeUse){

			var notifyemail=jQuery("#notifyemail").val();

			var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			var filter = regex.test(notifyemail);

			if(filter == true) {
				
			   jQuery(".saving_information").show();
			   jQuery(".display_succuss_msg").hide();
			   jQuery.ajax({
					type: "POST",
					data: "",
					url:URL_SITE+"/notify.php?notifyemail="+notifyemail+"&userid="+userid+"&dbid="+dbid+"&databaseToBeUse="+databaseToBeUse,
						
					success: function(msg) {
						jQuery(".display_succuss_msg").html(msg).show();						
						jQuery(".saving_information").hide();	
					}
				});			
				return false;
			}
			else
			{
				return false;
			}				   
		}
		</script>
	</div>
</div>
<!-- NOTIFY WINDOW -->