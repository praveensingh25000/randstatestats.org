<?php
/******************************************
* @Modified on 09 July 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$user = new user();

if(isset($_GET['action'])){
	$action_type	=	$_GET['action'];
} else {
	$action_type	=	'viewall';
}

$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
if(!empty($siteMainDBDetail)) {
	$dbUsercode = $siteMainDBDetail['id']; 
} else { 
	$dbUsercode = 1; 
}


if(isset($_GET['id']) && $_GET['id']!=''){
	$userid				= trim(base64_decode($_GET['id']));
	$userDetail			= $user->getUser($userid);				
	$userTypeDetail		= $admin->getUserType($userDetail['user_type']);
	$puechased_account  = $admin->selectValidDatabaseofUser($userDetail['id']);	
	$typesResult		= $admin->showAllUserTypes($status=0);
	$dbUserDetail		= $admin->selectdatabaseUsers($dbUsercode,$userid);
	$usertypesAll		= $db->getAll($typesResult);
	$validity_on		= $admin->selectIndividualDatabaseValidity($dbUserDetail['id']);
}

$usertype = $userDetail['user_type'];

if(isset($_POST['addinvoice']) || isset($_POST['addinvoicehidden'])) {

	$user_type			=	$_POST['user_type'];

	$payment_status		=	$_POST['payment_status'];
	$payment_details	=	mysql_real_escape_string(htmlentities($_POST['payment_details']));
	$total_amount		=	$_POST['total_amount'];
	$surchargeamount	=	$_POST['surcharge_amount'];
	$discountamount		=	$_POST['discount_amount'];

	$start_date			=	$_POST['startdate'];
	$end_date			=	$_POST['enddate'];
	$invoice_date		=	$_POST['invoicedate'];
	$date_paid			=	$_POST['datepaid'];
	$original_rate		=	$_POST['original_rate'];
	$discount_rate		=	$_POST['discount_rate'];
	$admin_notes		=	$_POST['admin_notes'];

	if($user_type == 5){
		$number_of_users	= 1;
	} else {
		$number_of_users	= (isset($_POST['number_of_users']))?trim(addslashes($_POST['number_of_users'])):'0';
	}
	
	if(isset($_POST['account_type']) && $_POST['account_type'] == 'PA') {
		if(!isset($_POST['db_name']) || (isset($_POST['db_name']) && count($_POST['db_name']) <= 0)){
			$_SESSION['errormsg']="All details related to subscription are necessary. Please try again";
			header("location: userPayment.php?id=".base64_encode($userid)."");
			exit;
		}

		$plan_id = $_POST['plan_name'];
		
		$validity = 365 * $_POST['plan_name'];

		$db_valuesArray = array();
		foreach($_POST['db_name'] as $keydb => $dbid){
			$db_valuesArray[$dbid] = 0;
		}

		$typeDetail = $admin->getUserType($user_type);

		$plan_name = $plan_id." year ( ".$typeDetail['user_type']." )";

		$trans_before_id	=	$admin->insertPlanTransaction($plan_id, $total_amount, $discountamount, $surchargeamount);

		$insert				= $admin->insertMembershipDatabaseDetail($trans_before_id, $userid, $plan_id, $db_valuesArray,$validity, '1');

		$txn_id = "OFFLINE".$trans_before_id;

		$payment_type = "offline";

		$updatePlanTransaction	=	$admin->updatePlanTransaction($userid, $txn_id, $payment_status, $trans_before_id, $payment_type,  $plan_name, $user_type, $payment_details, $number_of_users);
		
		$updateTransactionDates	=	$admin->updateTransactionDates($trans_before_id, $start_date, $end_date, $invoice_date, $date_paid, $original_rate, $discount_rate, $admin_notes);

		$update_numbersofusers = $user ->updateNumberofUsers($userid, $number_of_users);

		$_SESSION['successmsg']="Payment details successfully added. User now moved to paid user.";

		//$admin->sendInvoice($trans_before_id);

	} else {
		$_SESSION['errormsg']="All details related to subscription are necessary. Please try again.";

	}

	
	header("location: historical.php?id=".base64_encode($userid)."");

}


?>

<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery-ui-1.9.2.js"></script>

<link rel="stylesheet" href="<?php echo URL_SITE; ?>/css/jquery.ui-1.9.2.css" type="text/css" />


<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">

		<aside class="containerRadmin">
			<?php include_once $basedir."/include/adminLeft.php"; ?>
		</aside>

		<!-- left side -->
		<div class="containerLadmin">

			<h2><?php echo $userDetail['organisation']; ?>'s Account<span class="right"><a href="javascript:window.history.go(-1)">Back</a></span></h2><br>

			<div class="tabnav mB10">		
				<div class="pL10 pT5" id="">
					Show: <a href="user.php?id=<?php echo base64_encode($userid); ?>&action=view" >Profile</a>&nbsp;&nbsp;
					<a href="user.php?id=<?php echo base64_encode($userid); ?>&action=edit" >Edit Profile</a>&nbsp;&nbsp;
					<a href="<?php echo URL_SITE; ?>/admin/ipRanges.php?id=<?php echo base64_encode($userid)?>">IPs</a>&nbsp;&nbsp;
					<a href="historical.php?id=<?php echo base64_encode($userid); ?>&status=1" >Historical Payments</a>&nbsp;&nbsp;
					<a href="historical.php?id=<?php echo base64_encode($userid); ?>&status=0" >Pending Payments</a>&nbsp;&nbsp;

					<?php if($userDetail['user_type'] == 6){ ?>
					<a href="user.php?id=<?php echo base64_encode($userid); ?>&action=view">Additional Users Login</a>
					<?php } ?>

					<?php if(isset($dbUserDetail) & $dbUserDetail['is_trial']=='0') { ?>
						<a href="userPayment.php?id=<?php echo base64_encode($userid); ?>" class="active">Generate Invoice</a>
					<?php } ?>
				</div>
			</div>

			<fieldset style="border: 1px solid #cccccc;padding:10px;">
			<h2>Generate Invoice</h2><br>
			<p style="color:red;"><strong>Note:</strong> Generating a new invoice will change user type, no of users & databases validity of current plan user already purchased/pending/In-abeyance. If you wish to continue a new invoice will be generated & user will hold this as its current plan.</p>
			<br>
				<form method="POST" id="frmgenerateinvoice" name="frmgenerateinvoice" action="" style="width:350px;float:left;">
					<div style="padding: 10px 0px;width:700px;">
						<p class="pB5">User Type<em>*</em></p>
						<?php if(!empty($usertypesAll)) { ?>
							<select class="required" id="user_type" name="user_type" style="width:355px;">
								<option value=""> Select User Type </option>
								<?php foreach($usertypesAll as $userTypes) { ?>
									<option value="<?php echo $userTypes['id'];?>" <?php if(trim($usertype) == $userTypes['id']){ echo "selected='selected'"; } ?> ><?php echo ucwords($userTypes['user_type']);?></option>
								<?php } ?>							
							</select>
							<?php
						if(!isset($_GET['step'])) { ?>								
						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery("#user_type").change(function(){								
								var user_type  = jQuery("#user_type").val();
								var number_of_users  = jQuery("#number_of_users").val();
								if(user_type != ''){
									if(user_type != '5'){	
										jQuery("#number_of_users_show_div").show();
										jQuery("#number_of_users").val("");
									} else {
										jQuery("#number_of_users_show_div").hide();
										jQuery("#number_of_users").val("1");
									}
									
									var user_type  = jQuery("#user_type").val();

									var number_of_users1  = jQuery("#number_of_users").val();
									if(user_type != '' && number_of_users1 > 0){
										planDetail();
									}

								} else {
									jQuery("#number_of_users_show_div").hide();
									jQuery("#number_of_users").val("1");
								}
							});	
						});
						</script>
						<?php } } ?>
					</div>
					<div class="clear"></div>

					<?php if($usertype == 5){
						$nodisplay = "none";
					} else {
						$nodisplay = "block";
					}
					?>
					<div  id="number_of_users_show_div" style="padding: 10px 0px;width:700px;display:<?php echo $nodisplay; ?>;">
						<p>Number of users<em>*</em></p>
						<span class="">
						<input placeholder="Enter number of users" name="number_of_users" value="<?php if(isset($userDetail['number_of_users'])){ echo $userDetail['number_of_users']; } else { echo "1"; }?>" type="text" class="digits required" id="number_of_users" />&nbsp;&nbsp;<a href="javascript:;" id="popupnoofusers"><img src="/images/question16x16.png"></a><span >Value should be greater than 0.</span>
						<label style="display:none;" for="number_of_users" generated="true" class="error">This field is required.</label>
						</span>

						<SCRIPT LANGUAGE="JavaScript">
						$(document).ready(function() { 
							$('#popupnoofusers').click(function() { 
								 $.blockUI({ 
									message: $("<div class='login-popup' style='color: #ffffff;top:20%;left:30%;'><ul><li>Academic libraries: fall Full-Time Equivalent (FTE) students.</li><li>Public libraries: city or county population or the population in your service area.</li><li>K-12 libraries: FTE students.</li><li>Multiple users: the number of login IDs required.</li><li>All others: Total number of users with access to the site(s).</li></ul></div>")
								}); 
								$('.blockOverlay').attr('title','Click to unblock').click($.unblockUI); 
							}); 
						}); 
						</SCRIPT>
					</div>
					<div class="clear"></div>
					
					<div style="display:none;">
						<input  type="radio" value="PA" name="account_type" id="PA" checked>
					</div>

				
					<div id="purchasedetails" style="display:none;">
						<div id="plandetails"></div>

						<div style="padding: 10px 0px;width:700px;display:block;" >
							<p>Original Rate</p>
							<span class="">
							<input type="text" id=""  class=" " name="original_rate"><br/>
							</span>
						</div>

						<div style="padding: 10px 0px;width:700px;display:block;" >
							<p>Discounted Rate</p>
							<span class="">
							<input type="text" id=""  class=" " name="discount_rate"><br/>
							</span>
						</div>
						
						<div style="padding: 10px 0px;width:700px;display:block;" >
							<p>Start Date<em>*</em></p>
							<span class="">
							<input type="text" id="startdate" style="display:inline-block;padding-left:5px;" class="required date" value="<?php echo date('Y-m-d'); ?>" name="startdate" placeholder="Enter start date"><br/><span>(Format: YYYY-MM-DD)</span>
							</span>
						</div>

						<div style="padding: 10px 0px;width:700px;display:block;">
							<p>End Date<em>*</em></p>
							<span class="">
							<input type="text"   id="enddate" style="display:inline-block;padding-left:5px;" class="required date" value="<?php echo date('Y-m-d'); ?>" name="enddate" placeholder="Enter end date"><br/><span>(Format: YYYY-MM-DD)</span>
							</span>
						</div>

						<div style="padding: 10px 0px;width:700px;display:block;">
							<p>Invoice Date<em>*</em></p>
							<span class="">
							<input type="text"   id="invoicedate" style="display:inline-block;padding-left:5px;" class="required date" value="<?php echo date('Y-m-d'); ?>" name="invoicedate" placeholder="Enter invoice date"><br/><span>(Format: YYYY-MM-DD)</span>
							</span>
						</div>

						<div style="padding: 10px 0px;width:700px;display:block;">
							<p>Date Paid</p>
							<span class="">
							<input type="text"   style="display:inline-block;padding-left:5px;" id="datepaid" class="date" value="<?php echo date('Y-m-d'); ?>" name="datepaid" placeholder="Enter datepaid"><br/><span>(Format: YYYY-MM-DD)</span>
							</span>
						</div>

						<div style="padding: 10px 0px;width:700px;">
							<p class="pB5">Status of payment</p>
							<div class="selectplan">
								<input type="radio"  name="payment_status" value="1" checked><span class="pL5"> Paid</span>&nbsp;&nbsp;&nbsp;&nbsp;

								<input type="radio"  name="payment_status" value="0" checked><span class="pL5"> Pending</span>&nbsp;&nbsp;&nbsp;&nbsp;

								
							</div>

						</div>
						<div class="clear"></div>
						
						<div style="padding: 10px 0px;width:700px;">
							<p class="pB5">Details of payment</p>
							<textarea rows="3" cols="50" placeholder="e.g cheque no. , date, payer etc." name="payment_details" class="wdthpercent90"  /></textarea>
						</div>
						<div class="clear"></div>

						<div style="padding: 10px 0px;width:700px;">
							<p class="pB5">Admin Notes</p>
							<textarea rows="3" cols="50" placeholder="" name="admin_notes" class="wdthpercent90"  /></textarea>
						</div>
						<div class="clear"></div>

					</div>

					<div style="padding: 10px 0px;width:700px;display:none" id="submitbuttons">
						<label for="submit" class="left">
							<input type="submit" value="Submit" name="addinvoice" class="" />
							<input type="hidden" value="user_registration_hidden" name="addinvoicehidden">

							<input type="hidden" value="" id="actualcost" name="actualcost">

							<input onclick="window.location=URL_SITE+'/admin/user.php.php?id=<?php echo base64_encode($userid);?>&action=view'" type="button" value="Reset" name="cancel" class="mL20" />
						</label>							
					</div>

					<script type="text/javascript">
					jQuery(document).ready(function(){
						
						jQuery("#datepaid").datepicker({
							showOn: "button",
							buttonImage: "../images/calendar.jpg",
							dateFormat: 'yy-mm-dd',
							buttonImageOnly: true
						});

						jQuery("#invoicedate").datepicker({
							showOn: "button",
							dateFormat: 'yy-mm-dd',
							buttonImage: "../images/calendar.jpg",
							buttonImageOnly: true
						});

						jQuery( "#startdate" ).datepicker({
							showOn: "button",
							defaultDate: "+1w",
							changeMonth: true,
							numberOfMonths: 1,
							dateFormat: 'yy-mm-dd',
							showOn: "button",
							buttonImage: "../images/calendar.jpg",
							buttonImageOnly: true,
							onClose: function( selectedDate ) {
								jQuery( "#enddate" ).datepicker( "option", "minDate", selectedDate );
							}
						});
						jQuery( "#enddate" ).datepicker({
							showOn: "button",
							defaultDate: "+1w",
							changeMonth: true,
							numberOfMonths: 1,
							showOn: "button",
							buttonImage: "../images/calendar.jpg",
							buttonImageOnly: true,
							dateFormat: 'yy-mm-dd',
							onClose: function( selectedDate ) {
								jQuery( "#startdate" ).datepicker( "option", "maxDate", selectedDate );
							}
						});

						

					
						var user_type = jQuery('#user_type').val();
						var noofusers = jQuery('#number_of_users').val();
						if(user_type!='' && noofusers>0){
							planDetail();
							jQuery('#submitbuttons').show();
						}

						jQuery('#number_of_users').change(function(){	
							var noofusers = jQuery('#number_of_users').val();
							if(noofusers>0){
								planDetail();
								jQuery('#submitbuttons').show();
							} else {
								alert("please enter value greater than zero.");
								jQuery('#plandetails').html("");
								jQuery('#purchasedetails').hide();
								jQuery('#submitbuttons').hide();
								return false;
							}
						});	

						jQuery('#user_type').change(function(){
							var user_type = jQuery(this).val();
							if(user_type != 5){
								var noofusers = jQuery('#number_of_users').val();
								if(noofusers>0){
									planDetail();
									jQuery('#submitbuttons').show();
								} else {
									jQuery('#plandetails').html("");
									jQuery('#purchasedetails').hide();
									jQuery('#submitbuttons').hide();
								}
							} else {
								planDetail();
								jQuery('#submitbuttons').show();
							}
						});
					});
					</script>

				</form>
			</fieldset>
		 </div>
		<!-- left side -->

		
	</tr>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>


