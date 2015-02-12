<?php
/******************************************
* @Modified on Jan 25, 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

$user_type_details = $user_plan_details	=	array();

checkSession(false);

$admin = new admin();

header('location: accountUpgrade.php');
exit;


/******* Below content is for future user it will never run so there is no problem if it statys. Kindly not to remove below code thanks ***************/


echo '<h1>This upgrade subscription section will resume after 31 July 2013</h1>';die;

$plansResult_res = $admin->selectAlluserTypesActiveDeactive($active=1);
$totalplan		 = $db->count_rows($plansResult_res);

if(isset($_SESSION['user']['id']) && $_SESSION['user']['id']!='' && !isset($_REQUEST['confirmed'])) {
	
	$userDetail			=	$user->getUser($_SESSION['user']['id']);
	
	if(isset($userDetail['user_type']) && $userDetail['user_type']!='') {
		
		$instutionArray = explode('.',$userDetail['user_type']);
		
		if(isset($instutionArray[0]) && trim($instutionArray[0]) == 'Institution') {

			$confirmpaymentDetail = $admin->check_confirmpaymentDetail($userDetail['id']);

			if(!empty($confirmpaymentDetail) && in_array('0',$confirmpaymentDetail)) {				
				$_SESSION['infomsg'] = "<b>NOTE</b>:Please contact administrator for your payment confirmation.";
				//header('location: index.php');
				exit;				
			} else if(empty($confirmpaymentDetail)) {
				$_SESSION['infomsg'] = "Please verify your account.";
				//header('location: ipRangesConfirmation.php');
				exit;	
			}		
		}
	}
}

if(isset($totalplan) && $totalplan <= 0) {
	$_SESSION['infomsg'] = "Your trial period is over.";
	header('location: index.php');
	exit;
}

if(isset($_SESSION['user']) && $_SESSION['user']['user_type']!='') {

	$user_id			 =  $_SESSION['user']['id'];
	$user_type_id		 =  $_SESSION['user']['user_type'];
	$plansResult         =  $admin->selectAlluserTypes(trim($_SESSION['user']['user_type']));
	$check_upgratdation  =  $admin->selectValidDatabaseofUser($user_id);

} else {

	$plansResult         =  $admin->selectAlluserTypes();
}

$total_user_type		 =  $dbDatabase->count_rows($plansResult);
$user_type_details		 =  $dbDatabase->getAll($plansResult);
?>

<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">
		<!-- left side -->
		<div id="container-1">
		
			<?php
			if(isset($_SESSION['nvpResArray']) && strtoupper($_SESSION['nvpResArray']["ACK"])!='SUCCESS' && strtoupper($_SESSION['nvpResArray']["ACK"])!='SUCCESSWITHWARNING') { ?>
				<div style="background: #EFFEB9;padding: 5px;">
					<?php
					$resArray = $_SESSION['nvpResArray'];
					$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
					$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
					$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
					//$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

					echo "SetExpressCheckout API call failed. ";
					echo "Detailed Error Message: " . $ErrorLongMsg;
					echo "Short Error Message: " . $ErrorShortMsg;
					echo "Error Code: " . $ErrorCode;
					//echo "Error Severity Code: " . $ErrorSeverityCode;
					unset($_SESSION['nvpResArray']);
					?>					
				</div>
			<?php }	?>

			<?php if(!empty($user_type_details)) {	?>

				<div class="pT10 pB10">

					<h2> Buy Subscription Plan </h2><br>

					<form method="POST" action="<?php URL_SITE?>paypal/makepayment.php" enctype="multipart/form-data" id="make_payment_form" name="make_payment_form">

						<h3 class="pT20"> Choose Plan </h3>
						<br class="clear">

						<div class="plantab">
							<ul class="ui-tabs-nav">							
								<?php 
								$_SESSION['user_type']   =	$user_type_details[0]['user_type'];	
								foreach($user_type_details as $key =>$plansResult) { ?>
									<li id="active_<?php echo $plansResult['user_type'];?>" class="<?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']==$plansResult['user_type']) { echo "selected"; } ?> ui-tabs-selected">
										<a href="javascript:;" onclick="javascript: selectAllPlansforSubscription('<?php echo $plansResult['id'];?>','<?php echo $plansResult['user_type'];?>')"><span class="pL5"> <?php echo $plansResult['user_type'];?> </span></a>
									</li>
								<?php } ?>
								
								<?php if(!empty($check_upgratdation)) {
									  $_SESSION['infomsg'] = "Please select any plan listed below to upgrade your existing plan";
									 ?>
									 <span class="right"><input type="button" value="Upgrade Plan" onclick="javascript: window.location=URL_SITE+'/account.php';"></span>
								<?php } ?>							
							</ul>
						</div>
					</form>

					<br class="clear" />

					<!-- SHOW_PLAN_AJAX_DETAIL -->
					<div class="pL30" id="show_plan_ajax_detail" style="">

						<!-- All Plan Detail -->					
						<?php
							if(isset($user_type_details[0]['id'])) {

								$subscriptionid		=	$user_type_details[0]['id'];	
								$user_type			=	$_SESSION['user_type'] = strtolower($user_type_details[0]['user_type']);	
								$plan_res			=	$admin->selectsubscriptionsPlansOfUser($subscriptionid);
								$user_plan_details	=	$dbDatabase->getAll($plan_res);
						    }
							
							//echo "<pre>";print_r($user_plan_details);echo "</pre>";
							?>

							<div class="planinner">
																
								<h3>Choose&nbsp;<?php echo ucwords($user_type);?>&nbsp;Plan </h3>
								<br class="clear">			
								
								<?php if(!empty($user_plan_details)) { ?>			
								
									<div class="selectplan">									
										<?php 
										$_SESSION['palnid']		=	$user_plan_details[0]['id'];
										$_SESSION['plan_name']  =	$user_plan_details[0]['plan_name'];

										foreach($user_plan_details as $key =>$plansResults) { ?>		
											<input <?php if(isset($_SESSION['plan_name']) && $_SESSION['plan_name']==$plansResults['plan_name']) {?> checked <?php } ?> onclick="javascript: selectdetailPlansforSubscription('<?php echo $plansResults['id'];?>','<?php echo $user_type;?>')" type="radio" value="<?php echo $plansResults['id'];?>" name="plan_name"><?php echo ucwords(stripslashes($plansResults['plan_name']));?>	
										<?php } ?>	
									</div>

									<!-- SHOW_PLAN_AJAX_DETAIL -->
									<div class="clear pT15"></div>
									<div id="show_plan_ajax_detail1_particular" style="">
										<!-- Plan Detail ------>
										<?php
										$planid				=	$_SESSION['palnid'];	
										$user_type			=	strtolower($_SESSION['user_type']);
										$user_plan_details	=	$admin->SelectparticularSubscriptionDetail($planid);
										?>	
										
										<form method="post" action="" id="select_payment_form_mode" name="select_payment_form_mode">
												
											<h3 class="txtcenter">Plan Detail<h3> 
											<br class="clear" />

											<table class="data-table">
											
											<?php if(!empty($user_plan_details)) {
												
												if(isset($_SESSION['user']['number_of_users'])) {
													$number_of_users = $_SESSION['user']['number_of_users'];	
												} else if(isset($_SESSION['data']['number_of_users'])) {
												$_SESSION['data']['number_of_users'];
													$number_of_users = $_SESSION['data']['number_of_users'];
												} else {
													$number_of_users = 1;								
												}
												?>																		
												<tr>
													<th>Plan Name</th>				
													<td><?php echo stripslashes($user_plan_details['plan_name']); ?></td>					
												</tr>

												<tr>
													<th>Plan Validity</th>				
													<td><?php echo $user_plan_details['validity'];?>&nbsp;Days</td>					
												</tr>											
												
												<?php if(isset($number_of_users)) { ?>
												<tr>
													<th>Number of user</th>				
													<td><?php echo $number_of_users;?></td>
												</tr>	
												<?php } ?>		

												<?php if(isset($user_plan_details['discounts']) && $user_plan_details['discounts']!='0') { ?>
												<tr>
													<th>Discounts %</th>				
													<td><?php echo $user_plan_details['discounts']?></td>					
												</tr>
												<?php } ?>

												<tr>
													<th>&nbsp;</th>				
													<th>
														<div>
															<span class="left pL10">Databases</span>
															<span class="right pR10">Price/user</span>
														</div>
													</th>					
												</tr>

												<tr>
													<th>Database Amount </th>				
													<td>
														<?php
														$databasesResult_res = $admin->showAllDatabasesDetail();
														$total = $db->count_rows($databasesResult_res);
														$databases = $db->getAll($databasesResult_res);

														if(!empty($user_plan_details)) {
															$subscriptionPlansValueDetail_out=explode('/',$user_plan_details['db_amount']);
															foreach($subscriptionPlansValueDetail_out as $key => $scriptionPlans) { 
																$array[]=explode('-',$scriptionPlans);
															}			
															foreach($array as $key => $value1) { 
																if(count($value1)>1){
																$new_array[$value1['0']] = $value1[1];			
																}
															}
														}
														
														if(!empty($databases)) { 
															$databases_slice=array_slice($databases,0,4);
															foreach($databases_slice as $key => $databaseDetail){?>					
																
																<div class="left pT5 pB5">
																	<input id="checked_unchecked_<?php echo $databaseDetail['id']?>" onclick="javascript: selectDatabaseAmountFunction('<?php echo $user_plan_details['id']?>','<?php echo $user_type;?>','<?php echo $databaseDetail['id']?>','<?php echo $number_of_users;?>')" value="<?php echo $databaseDetail['id']?>" type="checkbox" class="dbToSelect" name="db_name[]">
																	<span class="pL20 "><?php echo $databaseDetail['db_code'];?></span>
																</div>
																
																<div class="right pT5 pB5">
																	<?php
																	if(!empty($new_array)) {
																		foreach($new_array as $key => $dbvalues) {
																			if($key == $databaseDetail['id'])
																			{
																				echo "<span class='db_".$databaseDetail['id']."'>". $db_values= $dbvalues."&nbsp;USD</span>";
																				if($databaseDetail['id']==4)
																				{
																					echo "<span style='display:none;' class='db_free_".$databaseDetail['id']."'>Free</span>";
																				}
																			}
																		} 
																	}?>
																</div>

																<br class="clear" />							
															<?php } ?>
														<?php } ?>

													</td>					
												</tr>

												<tr></tr>
												<tr></tr>

												<tr>
													<th>Total Amount :<br>(# of user * amt)</th>
													<td>
														<div class="left">&nbsp;</div>
														<div class="right">
															<span id="total_amount"> 0 </span>&nbsp;USD
															<input id="total_amount_input" type="hidden" name="total_amount" value="0">
															<input type="hidden" name="planid" value="<?php echo $user_plan_details['id'];?>">
														</div>
														<br class="clear" />
													</td>					
												</tr>
											</table>

											<script type="text/javascript">
											
												jQuery(document).ready(function(){
												jQuery("#select_payment_form_mode").submit(function(e){

													if(jQuery("#total_amount_input").val() !='0'){
														e.preventDefault();
														loader_show();								
														jQuery.ajax({
															type: "POST",
															data: jQuery("#select_payment_form_mode").serialize(),
															url : URL_SITE+"/frontAction.php?paymentmode=1",
															
															success: function(msg){	
																loader_unshow();
																jQuery("#proceed_to_payment_type").hide();
																jQuery("#show_selected_payment_form_mode").html(msg).show();	
															}							
														});	
													} else {
														alert('Please choose database in order to proceed to Payment Mode');
														return false;
													}			  
												});	
											});	
											
											</script>

											<br class="clear" />
											<div id="proceed_to_payment_type" class="submit1 txtcenter pT10">
												<label for="submit">
													<input type="submit" name="submit" value="Proceed To Payment Type" class="submitbtn" >
												</label>
												<label for="cancel pL10">					
													<input onclick="javascript: window.location=URL_SITE+'/index.php'" type="button" name="submit" value="Cancel" class="submitbtn" >
												</label>
											</div>

											<?php } else { ?>
											<h4> Plan detail not available this time. </h4>
											<?php } ?>									
										</form>
										
										<!-- Plan Detail -->
									</div>
									<!-- SHOW_PLAN_AJAX_DETAIL -->	
								
								<?php } else { ?>
									<h4> No Plan available. </h4>
								<?php } ?>									
							</div>						
						<!-- All Plan Detail -->									
					</div>
					<!-- SHOW_PLAN_AJAX_DETAIL -->
					
				</div>

			<?php } else { ?>
			<h3> No Plan added Yet </h3>
			<?php } ?>
			
			<!-- show_selected_payment_form_mode -->
			<div class="pL30" id="show_selected_payment_form_mode" style="display:none;"></div>
			<!-- show_selected_payment_form_mode -->	
		
		<br class="clear" />
		<br class="clear" />
		</div>
		<!-- left side -->

	</div>		
</section>
<!-- /container -->