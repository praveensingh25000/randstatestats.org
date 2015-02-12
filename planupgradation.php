<?php
/******************************************
* @Modified on Jan 25, 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

checkSession(false);

$user_plan_details	=	array();

$admin = new admin();

if(isset($_SESSION['user']) && isset($_GET['id']) && $_GET['id']!='') {

	$user_id		       =  $_SESSION['user']['id'];
	$db_user_id			   =  base64_decode($_GET['id']);
	$plan_exits			   =  $admin->selectdatabaseUsersID($db_user_id);
	$db_membership_id	   =  $plan_exits['membership_id'];
	$plansResultOne		   =  $admin->SelectparticularSubscriptionDetail($plan_exits['plan_id']);
	$user_type_details     =  $admin->selectuserTypes($plansResultOne['subscriptionid']);
	$userDatabaseArray	   =  $admin->selectValidDatabaseofUser($user_id);
	$_SESSION['user_type'] =  $user_type_details['user_type'];
	
} else {
	header('location:account.php');
}
?>

<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">
		<!-- left side -->
		<div id="container-1">

			<?php if(!empty($user_type_details)) {	?>
			<div class="pT10 pB10">

				<h2> Upgrade Subscription Plan <span class="right"></h2><br>

				<form method="POST" action="" enctype="multipart/form-data" id="make_payment_form" name="make_payment_form">

					<h3 class="pT20"> Your Base Plan </h3>
					<br class="clear">

					<div class="plantab">
						<ul class="ui-tabs-nav">
							
							<li id="active_<?php echo $user_type_details['id'];?>" class="<?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']==$user_type_details['user_type']) { echo "selected"; } ?> ui-tabs-selected">
							<a href="javascript:;" onclick="javascript:alert('You cannot change your base plan.In order to change,please register a new account.');"><span class="pL5"> <?php echo $user_type_details['user_type'];?> </span></a>
							</li>
							
						</ul>
					</div>
				</form>

				<br class="clear" />
				<!-- SHOW_PLAN_AJAX_DETAIL -->
				<div class="pL30" id="show_plan_ajax_detail" style="">

					<!-- All Plan Detail -->					
					<?php if(!empty($plansResultOne)) {
						
						$subscriptionid			=	$plansResultOne['subscriptionid'];	
						$user_type				=	strtolower($plansResultOne['plan_type']);
						$_SESSION['planid']		=	$plansResultOne['id'];
						$_SESSION['user_type']	=   $user_type;

						//echo "<pre>";print_r($plansResultOne);echo "</pre>";
						?>

						<div class="planinner">

							<h3>Selected&nbsp;<?php echo ucwords($user_type);?>&nbsp;Plan </h3>
							<br class="clear">			
							
							<?php if(!empty($plansResultOne)) {?>			
							
								<div class="selectplan">									
									<?php $_SESSION['plan_name']  =	$plansResultOne['plan_name']; ?>		
									<input <?php if(isset($_SESSION['plan_name']) && $_SESSION['plan_name']==$plansResultOne['plan_name']) {?> checked <?php } ?> type="radio" value="<?php echo $plansResultOne['id'];?>" name="plan_name"><?php echo $plansResultOne['plan_name'];?>	
										
								</div>

								<!-- SHOW_PLAN_AJAX_DETAIL -->
								<div class="clear pT15"></div>
								<div id="show_plan_ajax_detail1_particular" style="">
									<!-- Plan Detail ------>
									<?php
									$planid				=	$_SESSION['planid'];	
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
												<td><?php echo $user_plan_details['plan_name']; ?></td>					
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
																<input type="checkbox" name="db_name[]" <?php if(in_array($databaseDetail['id'],$userDatabaseArray)) { ?> disabled="true" <?php } else { ?> id="checked_unchecked_<?php echo $databaseDetail['id']?>" onclick="javascript: selectDatabaseAmountFunction('<?php echo $user_plan_details['id']?>','<?php echo $user_type;?>','<?php echo $databaseDetail['id']?>','<?php echo $number_of_users;?>')" value="<?php echo $databaseDetail['id']?>" <?php } ?>>
																<span class="pL20"><?php echo $databaseDetail['db_code'];?></span>
															</div>

															<div class="right pT5 pB5">
																<?php
																if(!empty($new_array)) {
																	foreach($new_array as $key => $dbvalues) {
																		if($key == $databaseDetail['id'])
																		echo $db_values= $dbvalues;
																	} 
																}?>&nbsp;USD
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
												<?php if(isset($_SESSION['user']) && isset($db_membership_id) && $db_membership_id!='') { ?>
												<input type="hidden" name="db_membership_id" value="<?php echo $db_membership_id;?>">
												<?php } ?>
											</label>
											<label for="cancel pL10">					
												<input onclick="javascript: window.location=URL_SITE+'/index.php'" type="button" name="submit" value="Cancel" class="submitbtn" >
											</label>
										</div>

										<?php } else { ?>
										<h4> Detail not available this time. </h4>
										<?php } ?>									
									</form>
									
									<!-- Plan Detail -->
								</div>
								<!-- SHOW_PLAN_AJAX_DETAIL -->	
							
							<?php } else { ?>
							<h4> No Plan available. </h4>
							<?php } ?>								
						</div>
					<?php } ?>
					<!-- All Plan Detail -->									
				</div>
				<!-- SHOW_PLAN_AJAX_DETAIL -->

			</div>

			<?php } else { ?>
			<h3> No Paln added Yet </h3>
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