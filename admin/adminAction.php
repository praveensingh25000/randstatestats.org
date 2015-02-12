<?php
/******************************************
* @Modified on 03 JAN 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
require_once($basedir.'../../include/actionHeader.php');
$emailObj = new emailTemp();
$admin = new admin();
$user  = new user();

if(isset($_GET['select_database']) && $_GET['select_database']!='') {

		$planid=$_POST['planid'];	
		$section_type=$_POST['section_type'];	

		$databasesResult_res = $admin->showAllDatabasesDetail();
		$total = $dbDatabase->count_rows($databasesResult_res);
		$databases = $dbDatabase->getAll($databasesResult_res);
		
		//selecting all values of plan
		$subscriptionPlansValueDetail = $admin->selectsubscriptionPlansValue($planid);
		$array	=	$array1	= $new_array	=	array();

		if(!empty($databases) && !empty($subscriptionPlansValueDetail)) {

			$subscriptionPlansValueDetail_out=explode('/',$subscriptionPlansValueDetail['db_amount']);
			foreach($subscriptionPlansValueDetail_out as $key => $scriptionPlans) { 
				$array[]=explode('-',$scriptionPlans);
			}			
			foreach($array as $key => $value1) { 
				if(count($value1)>1){
					$new_array[$value1['0']] = $value1[1];			
				}
			}
			?>

			<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Validity</legend>	
			<div style="padding: 10px 0;">
				<input onchange="checkPlanAvailability('<?php echo $section_type;?>','validity_<?php echo $section_type;?>');" id="validity_<?php echo $section_type;?>" type="text" placeholder="enter validity in days" name="validity" class="digits required" value="<?php echo $subscriptionPlansValueDetail['validity'];?>"/>&nbsp;Days
			</div>

			<legend style="background: #cccccc; font-size: 14px; padding: 5px;"> Discounts % </legend>
			<div style="padding: 10px 0;">				
				<input id="claculatediscountfunction_<?php echo $planid?>" onchange="claculateDiscountFunction(<?php echo $planid?>,'<?php echo $section_type;?>');" type="text" placeholder="enter discount amount" name="discounts" class="digits" value="<?php if(isset($_POST['discounts'])){ echo $_POST['discounts'];} else echo $subscriptionPlansValueDetail['discounts'];?>" /><span style="color:blue;font-style:oblique;">press tab for discount.</span>				
			</div>
			
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Databases</legend>
			<div style="padding: 10px 0;">
				<?php
				$databases_slice=array_slice($databases,0,4);
				foreach($databases_slice as $key => $databaseDetail) { 
					foreach($new_array as $key => $dbvalues) {
						if(isset($_POST['discounts'])){
							$discounts=$_POST['discounts'];
							if($key == $databaseDetail['id']){
							$discounts= round(($dbvalues * $discounts) / 100) ;
							$dbvalues_discount=$dbvalues-$discounts;
							}
						}
						else{
							if($key == $databaseDetail['id']) $dbvalues_discount= $dbvalues ;
						}
					}?>					
					<p class="pT5 pB5 pL20">
					<?php echo ucwords($databaseDetail['database_label']);?>&nbsp;<input placeholder="enter amount in USD" type="text" name="<?php echo $databaseDetail['id'];?>" class="required" value="<?php echo $dbvalues_discount;?>"/>
					</p>				
				<?php } ?>
			</div>
		<?php } else {
			echo '<h4 class="pT5 pB5">No Base plan Selected</h4>';
		} 		
} 

//-- DELETE ALL RELATED PLAN DATA -->
if(isset($_GET['confirm_delete']) && $_GET['confirm_delete']!='') {

	$id=$_GET['id'];
	$planid=$_GET['planid'];

	$delete_all_plan_data = $admin->deleteallrelatedplanData($planid);

	if($delete_all_plan_data){
		$_SESSION['msgsuccess'] = "Subscription Plan has been deleted successfully.";
		echo "true";
	}else{
		$_SESSION['msgerror'] = "Subscription Plan has not been deleted.";
		echo "false";
	}
}

if(isset($_GET['columnname']) && isset($_GET['tablename'])) {

	$columnname	=	trim($_GET['columnname']);	
	$tablename	=	trim($_GET['tablename']);

	$rowDetail_res = $admin->showColumns($tablename);
	$rowDetail	   = $dbDatabase->getAll($rowDetail_res);

	if(!empty($rowDetail)) {
		$hasColum=array();
		foreach($rowDetail as $key => $values){
			$hasColumArray[] = $values['Field'];
		}
		if(in_array($columnname,$hasColumArray)) 
		$true	=	'false';
		else 
		$true	=	'true';					
	} else {
		$true		=	'false';
	}

	echo $true;
}
?>

<?php if(isset($_GET['adddays']) && $_GET['adddays']!='') {

	if(isset($_GET['days']) && $_GET['days']!=0){
		
		$calnumberofdays		=	0;
		$userid					=	trim($_GET['userid']);
		$id						=	trim($_GET['adddays']);
		$numberofdays			=	trim($_GET['days']);	
		
		$action					= 	trim($_GET['action']);

		$updateUserValidity		=   $admin->updateDatabaseUserValidity($id, $numberofdays, $action);			
		echo $validity_on		=   $admin->selectIndividualDatabaseValidity($id);
		return true;
	}	
	?>
	
	<!-- Validity WINDOW -->
	<div class="header-popup login-popup adddays_box" id="login-box" style="display: none; margin-top: -180.5px; margin-left: -319px;">
		
		<a class="close adddaysdiv" href="#">
			<img alt="Close" title="Close Window" class="btn_close" src="<?php URL_SITE;?>/images/close_pop.png">
		</a> 

		<div id="notify">
			<h4 style="color:#ffffff;">Validity in Days</h4>

			<form onsubmit="return fun_add_validity_users('<?php echo $_GET['adddays'];?>','<?php echo $_GET['id'];?>');"method="POST" id="adddaysupdatedform" name="adddaysupdatedform" class="signin" action="">
				
				<div class="pT10 pB10 txtcenter display_succuss_msg" style="font-size:14px;color:#ffffff;display:none;"></div>

				<fieldset class="">
					<label class="useremail"> <span>Select number of days </span>
						<input placeholder="number of days" type="text" id="days" name="days" class="digits required"/>						
					</label>		
					<label for="days" generated="true" style="padding-left:85px;display:none;" class="error pL30">This field is required.</label><br/>

					<label class="useremail"> <span></span>
						<select id="daysAction" name="action">
							<option value="add">Add</option>
							<option value="minus">Minus</option>
						</select>
					</label>	<br/>

					<button type="submit" name="submitvalidity" class="submit button" id="submitvalidity">SUBMIT</button>
					<button type="button" class="button btn_close" >CANCEL</button>
					<br class="clear" />				
				</fieldset>

				<div style="display:none;" id="" class="saving_information txtcenter pT20 pB10"><h3 style="color:#ffffff;">Saving Information...Please Wait....</h3></div>
			</form>

			<script language="javascript">
			jQuery(document).ready(function(){  
				jQuery('#adddaysupdatedform').validate();
			});

			function fun_add_validity_users(id,userid){

				var days=jQuery("#days").val();

				if(days != 0) {
					
				   jQuery(".saving_information").show();
				   jQuery(".display_succuss_msg").hide();
				   var action = jQuery("#daysAction").val();
				   jQuery.ajax({
						type: "POST",
						data: "",
						url:URL_SITE+"/admin/adminAction.php?action="+action+"&adddays="+id+"&days="+days+"&userid="+userid,
							
						success: function(msg) {							
							jQuery(".display_succuss_msg").html('<p><h4>You had extented the User validity to <b>'+days+'</b> days more.</h4><p>').show();
							jQuery("table tr td #show_days_"+userid).html(msg);
							jQuery(".selected_"+userid).addClass("fontbld tab font14");
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
	<!-- Validity WINDOW -->
	
<?php } ?>

<?php if(isset($_REQUEST['addingDetail']) && $_REQUEST['addingDetail']!='') {
	
	$ip_range = array();
	$userid   = $addInstutionipAddr = 0;

	if(!empty($_REQUEST['ip_range_from'][0]) && !empty($_REQUEST['ip_range_to'][0])) {
		$ip_range = array_combine($_REQUEST['ip_range_from'],$_REQUEST['ip_range_to']);
	}

	$name				=	$_POST['name'];
	$user_type			=	$_POST['user_type'];
	$username			=	$_POST['username'];
	$password			=	$_POST['password'];	
	
	$instution_id		= $admin->insertUserType($user_type);
	$user_login_type	= trim('Institution.'.$instution_id);
	$userid				= $admin->addInstutionUserDetail($name,$username,$password,$user_login_type);

	if(!empty($ip_range) && count($ip_range) !='0'){
		$is_verified	= 1;
		$addInstutionipAddr = $admin->addInstutionipAddress($userid,$instution_id,$ip_range,$is_verified);
	} else {
		$Statement ='Please login and enter your IP Ranges for verification by the administrator.';
	}

	if($addInstutionipAddr > 0 || $userid > 0) { 

		$receivermail	=	$username;
		$receivename	=	$name;
		$fromname		=	FROM_NAME;
		$fromemail		=	FROM_EMAIL;
		
		$mailbody		=	'Hi '.$receivename.', <br /><p>'.FROM_NAME.' have successfully created a Rand Account for you on your request!.'.$Statement.'</p><p><b>Username :</b>'.$username.'</p><p><b>password :</b>'.$password.'</p><br><p>Thank you </p><p>Rand Team </p>';

		$subject='Registration Mail';	
		$send_mail= mail_function($receivename,$receivermail,$fromname,$fromemail,$mailbody,$subject, $attachments = array(),$addcc=array());
		?>

		<div id="" class="txtcenter">
			<h2>Your detail has been added successfully.</h2><br>
			<h4>Do you wany to add more.</h4><br>
			<span class="">
				<input onclick="javascript:location.reload();" type="button" value="Yes" class="submitbtn">&nbsp;&nbsp;&nbsp;
				<input onclick="javascript:window.location=URL_SITE+'/admin/userTypes.php'" type="button" value="No" class="submitbtn">
			</span>
		</div>

	<?php } else { ?>

		<div id="" class="txtcenter">
			<h2> <label class="error">Sorry! due to network problem,Your detail has not been saved.</label></h2><br>
			<h4>Do you wany to add again.</h4><br>
			<span class="">
				<input onclick="javascript:location.reload();" type="button" value="Yes" class="submitbtn">&nbsp;&nbsp;&nbsp;
				<input onclick="javascript:window.location=URL_SITE+'/admin/userTypes.php'" type="button" value="No" class="submitbtn">
			</span>
		</div>

	<?php
	}
	return true;
}

if(isset($_REQUEST['admin_ip_action']) && isset($_REQUEST['admin_ip_action'])) {

	$ipid			=	trim($_REQUEST['ipid']);	
	$status			=	trim($_REQUEST['status']);
	$userid			=	trim($_REQUEST['userid']);

	$instution_id	= $admin->ipActivateDeactivate($ipid,$status);

	if($instution_id > 0){
		if($status == '1') { ?>
			<a onclick="javascript: ApproveDissaprove('<?php echo $userid;?>','<?php echo $ipid;?>','2');"href="javascript:;">Dis-allow</a>
		<?php } else { ?>
			<a onclick="javascript: ApproveDissaprove('<?php echo $userid;?>','<?php echo $ipid;?>','1');" href="javascript:;">Allow</a>
		<?php }
	} else { ?>
		<a onclick="javascript: ApproveDissaprove('<?php echo $userid;?>','<?php echo $ipid;?>','1');" href="javascript:;">Allow</a> | <a onclick="javascript: ApproveDissaprove('<?php echo $userid;?>','<?php echo $ipid;?>','2');"href="javascript:;">Dis-allow</a>
	<?php }
	return true;
} 

if(isset($_REQUEST['editIPRanges']) && $_REQUEST['editIPRanges'] !='') {

	$i				=	trim($_REQUEST['i']);
	$userid			=	trim($_REQUEST['userid']);
	$ipid			=	trim($_REQUEST['editIPRanges']);
	//$ip_range_from=	trim($_REQUEST['ip_range_from']);
	//$ip_range_to	=	trim($_REQUEST['ip_range_to']);
	$ips			=	trim($_REQUEST['ips']);
	$is_verified	=	$_REQUEST['is_verified'];
	$ipDetails		=   $admin->checkUserIPAddress($ips, $userid);

	if(!empty($ipDetails)){
		echo 'true';return true;
	}
	$saved_ranges	=   $admin->editIPAddress($ipid,$ips,$is_verified);
	$userDetail	    =   $user->getUser($userid);

	if(isset($mail_notification) && $mail_notification == '1' && isset($is_verified) && $is_verified == '0'){	
		$templateKey		=	11;
		//$receivermail		=	trim($userDetail['email']);
		$receivermail		=	trim(FROM_EMAIL);
		$receivename		=	ucwords(FROM_NAME);		
		$from_email		    =	trim($userDetail['email']);
		$from_name		    =	ucwords($userDetail['name']." ".$userDetail['last_name']);
		$userDefinedArray	= array(array('organisation' => $userDetail['organisation']),array('receivername'=>$receivename));		
		
		$mailbody			=	getMailTemplate($templateKey, $receivename, $receivermail, $from_name, $from_email,$userDefinedArray);
		
		$tempid = $templateKey;
		$tempDetail = $emailObj->getTemp($tempid);
		if(!empty($tempDetail)){
			$emailSubject	 = stripslashes($tempDetail['subject']);
		}
		
		$subject     = $emailSubject;	
		$send_mail   = mail_function($receivename,$receivermail,$from_name,$from_email,$mailbody,$subject, $attachments = array(),$addcc=array());
	}
	$ipDetails		= $admin->selectUserIPAddress($ipid);
	?>
	<div  class="wdthpercent20 left">
		IP address&nbsp;<b><?php echo $i;?></b> :
		<?php if($ipDetails['is_verified']== '0') { ?> <h4 style="color:red;">Pending</h4> <?php } else if($ipDetails['is_verified']== '1'){?> <h4 style="color:green;">Verified</h4> <?php } else { ?> <h4 style="color:blue;">Unverified</h4> <?php } ?>							
	</div>
	<div id="" class="wdthpercent70 left">
		<div id="" class="wdthpercent100">
			<div id="" class="wdthpercent80 left">
				<input placeholder="Enter the IP<?php echo $i;?>" type="text" name="ips" id="ips" class="wdthpercent50 required" value="<?php if(isset($ipDetails)){ echo $ipDetails['ips']; } ?>"/><br>
				<span class="pT5 pB5">IP Format: 1-255.0-255.0-255.0-255 </span>	
				<input type="hidden" name="is_verified" value="<?php echo $is_verified;?>">	
			</div>

			<div id="" class="wdthpercent20 left">
				<a id="submit_ip_range_form_<?php echo $ipDetails['id'];?>" href="javascript:;">Click to Save</a>&nbsp;&nbsp;&nbsp;&nbsp;
				<a onclick="javascript:return confirm('Do you really want to delete this IP ? Click OK to confirm and cancel to return.');" href="?action=delete&ipid=<?php echo base64_encode($ipDetails['id']);?>&userid=<?php echo base64_encode($ipDetails['user_id']);?>" href="javascript:;">Delete</a>			
			</div>
		</div>
		<div id="" class="clear pB10"></div>
	</div>
	
	<script type="text/javascript">
	jQuery(document).ready(function(){

		jQuery("#editiprangesbyuserform_<?php echo $ipDetails['id'];?>").validate();

		jQuery("#submit_ip_range_form_<?php echo $ipDetails['id'];?>").click(function(e){								
			e.preventDefault();			
			var pass_msg = jQuery("#editiprangesbyuserform_<?php echo $ipDetails['id'];?>").valid();
			jQuery("#display_success_message").remove();
			
			//some validations
			if(pass_msg == false){
				return false;
			} else {
				loader_show();
				
				jQuery.ajax({
					type: "POST",
					data: jQuery("#editiprangesbyuserform_<?php echo $ipDetails['id'];?>").serialize(),
					url : URL_SITE+"/admin/adminAction.php?editIPRanges=<?php echo $ipDetails['id'];?>&i=<?php echo $i;?>&userid=<?php echo $ipDetails['user_id'];?>",
					
					success: function(msg){
						loader_unshow();
						if(jQuery.trim(msg) == 'true'){
							jQuery(".show_already_ip_div_<?php echo $ipDetails['id'];?>").html('<font color="red">This IP already exists.</font>');		
						} else {
							if(!jQuery('#display_success_message').hasClass('succuss_message')){
								jQuery(".profile-outer").prepend('<div id="display_success_message" class="succuss_message txtcenter pB20"><h3>IPs has been saved successfully.</h3></div>');
							}
							jQuery("#show_saved_result_<?php echo $ipDetails['id'];?>").html(msg);
							jQuery('#display_success_message').delay(2000).fadeOut();
							return true;
						}
					}
				});
			}
		});
	});
	</script>

	<?php
	return true;
} 

if(isset($_REQUEST['sortColoum']) && $_REQUEST['sortColoum'] !='') {

	$coloumArrayMain = $coloumArrayMainArray =array();

	if($_POST['sortColoum']!=''){
		$coloumArray = explode(' ',$_POST['sortColoum']);
		foreach($coloumArray as $key => $values){
			if($key!='' && $key!=NULL){
			$coloumArrayMain[$key]=$values;
			}
		}
		$coloumArrayMainArray=array_pop($coloumArrayMain);		
		$updatetable = $admin->savecoloumOrderList($coloumArrayMain);
		echo '<label><H4>Coloum order has been saved.</H4><label>';
	} else {
		echo '<H4>Network Problem occurs.</H4>';
	}
}

if(isset($_REQUEST['sortCategory']) && $_REQUEST['sortCategory'] !='') {

	$coloumArrayMain = $coloumArrayMainArray =array();

	if($_POST['sortCategory']!=''){
		$coloumArray = explode(' ',$_POST['sortCategory']);
		foreach($coloumArray as $key => $values){
			if($key!='' && $key!=NULL){
			$coloumArrayMain[$key]=$values;
			}
		}
		$coloumArrayMainArray=array_pop($coloumArrayMain);		
		$updatetable = $admin->saveCategoryOrderList($coloumArrayMain);
		echo '<label><H4>Category order has been saved.</H4><label>';
	} else {
		echo '<H4>Network Problem occurs.</H4>';
	}
}

if(isset($_REQUEST['tablename']) && isset($_REQUEST['coloumname']) && isset($_REQUEST['sortorder']) && $_REQUEST['tablename']!='' && $_REQUEST['coloumname'] !='' && $_REQUEST['sortorder'] !='') {

	$coloumArrayMain = $coloumArrayMainArray =array();
	
	$tablename		 = $_REQUEST['tablename'];
	$coloumname		 = $_REQUEST['coloumname'];

	if($_POST['sortorder']!=''){
		$coloumArray = explode(' ',$_POST['sortorder']);
		foreach($coloumArray as $key => $values){
			if($key!='' && $key!=NULL){
			$coloumArrayMain[$key]=$values;
			}
		}
		$coloumArrayMainArray=array_pop($coloumArrayMain);		
		$updatetable = $admin->saveOrderListUniversal($coloumArrayMain,$tablename,$coloumname);
		echo '<H4>List order has been saved succussfully.</H4>';
	} else {
		echo '<H4>Network Problem occurs.</H4>';
	}
}
?>