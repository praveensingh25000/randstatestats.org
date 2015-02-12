<?php
/******************************************
* @Modified on Dec 18, 2012
* @Package: Rand
* @Developer: Mamta Sharma
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

header('location: signup.php');

die;



$admin = new admin();
$user  = new user();

$plansResult_res = $admin->selectAlluserTypesActiveDeactive($active=1);
$totalplan       = $db->count_rows($plansResult_res);

$typesResult	 = $admin->showAllUserTypes();
$usertypesAll	 = $db->getAll($typesResult);

if(isset($_GET['step'])) {
	$step = trim(base64_decode($_GET['step']));	
}

if(isset($_SESSION['user'])){
	header('location: index.php');
}

if(isset($_POST['user_registration_submit']) || isset($_POST['user_registration_hidden'])) {

	if($_SESSION['security_number'] == $_POST['captcha_code']) {

		if(isset($_POST['account_type']) && $_POST['account_type'] == 'TP') {
			
			$name				= mysql_real_escape_string($_POST['name']);
			$email				= mysql_real_escape_string(trim($_POST['email']));
			$endode_email		= base64_encode($email);
			$pw					= mysql_real_escape_string($_POST['password']);
			$phone				= $_POST['phone'];
			$address			= mysql_real_escape_string($_POST['address']);
			$organisation		= mysql_real_escape_string($_POST['organisation']);
			$org_address		= mysql_real_escape_string($_POST['organisation_address']);
			$user_type			= trim($_POST['user_type']);
			$number_of_users	= (isset($_POST['number_of_users']))?trim(addslashes($_POST['number_of_users'])):'0';
			
			$userid	= $user ->userRegistration($name,$email,$pw,$phone,$address,$organisation,$org_address,$user_type);
			$update_numbersofusers = $user ->updateNumberofUsers($userid, $number_of_users);
			
			$receivermail	=	$email;
			$receivename	=	$name;
			$fromname		=	FROM_NAME;
			$fromemail		=	FROM_EMAIL;
			
			$mailbody		=	'Hi '.$receivename.', <br /><p>You have successfully created a Rand Account! Please click the link below to verify your email address. </p><p><a href="'.URL_SITE.'/index.php?verification='.$endode_email.'">'.URL_SITE.'/index.php?verification='.$endode_email.'</a> </p>
			<p>If you are having trouble clicking on the link, please copy and paste it into your browser. </p>
			<p>Thank you </p>
			<p>Rand Team </p>';
			
			if(isset($mail_notification) && $mail_notification == '1'){
				$subject='Registration Verfication Mail';	
				$send_mail= mail_function($receivename,$receivermail,$fromname,$fromemail,$mailbody,$subject, $attachments = array(),$addcc=array());
			}
		
			$_SESSION['msgsuccess'] ="6";
			header("location:index.php");

		}else{
			$_SESSION['data'] = $_POST;
			$_SESSION['msgsuccess'] ="20";
			header("location:userRegistration.php?step=".base64_encode($_POST['account_type'])." ");
		}		
	  } else {
		$_SESSION['data'] = $_POST;
		$_SESSION['msgerror'] = '15';
		header("location:userRegistration.php");
	  }	
}
if(isset($_GET['cancel'])){
	unset($_SESSION['data']);
	header("location:userRegistration.php");
}

if(isset($step) && $step == 'PA') {
	$class1 = "conatiner-full"; 
	$class2 = "left"; 
	$class3 = "active";
} else { 
	$class2 = "profile"; 
	$class4 = "active";
}
?>
<section id="container">	

	<div id="inner-content" class="<?php if(isset($class1)) { echo  $class1; } ?>">	

		<!-- registration -->
		<div class="<?php if(isset($class2)) { echo  $class2; } ?>">
			
			<h2 style="padding:0px 0px 0px 18px;">		
				<span style="font-size:25px;">User Registration</span>&nbsp;>>&nbsp;<span id="activeInitial" class="activeclass <?php if(isset($class4)) { echo  $class4; } ?>" style="font-size:20px;">Initial Step</span>&nbsp;>>&nbsp;<span id="activeFinal" class="activeclass <?php if(isset($class3)) { echo  $class3; } ?>"style="font-size:20px;">Final Step</span>
			</h2>		
			<br class="clear" />

			<form method="POST" id="user_registration" name="user_registration" action="<?php echo URL_SITE;?>/userRegistration.php">
			
				<div class="registr" <?php if(isset($step) && $step == 'PA') { ?> style="width:550px;" <?php } ?>>
					<div class="inputshell">
						<p>Name<em>*</em></p>
						<input placeholder="Enter your name" name="name" type="text" value="<?php if(isset($_SESSION['data']['name'])){ echo $_SESSION['data']['name']; }?>" class="required" id="name" />
					</div>
					<div class="clear"></div>

					<div class="inputshell">
						<p>Username<!-- <em>*</em> --></p>
						<input placeholder="Enter your username" name="username" value="<?php if(isset($_SESSION['data']['username'])){ echo $_SESSION['data']['username']; }?>" type="text" class="" id="username" />							
					</div>
					<div class="clear"></div>

					<div class="inputshell">
						<p>Email<em>*</em></p>
						<input placeholder="Enter your email" name="email" value="<?php if(isset($_SESSION['data']['email'])){ echo $_SESSION['data']['email']; }?>" type="text" class="email required" id="email" />							
					</div>
					<div class="clear"></div>
					<div class="inputshell">
						<p>Password<em>*</em></p>
						<input placeholder="Enter a password" name="password" value="<?php if(isset($_SESSION['data']['password'])){ echo $_SESSION['data']['password']; }?>" type="password" class="required" id="password" />
					</div>
					<div class="clear"></div>
					<div class="inputshell">
						<p>Phone<em>*</em></p>
						<input placeholder="Enter your phone Number" name="phone" type="text" value="<?php if(isset($_SESSION['data']['phone'])){ echo $_SESSION['data']['phone'];}?>" class="required" id="phone" onchange="chckphone()"/>&nbsp;&nbsp;<em>eg:123-123-1234</em>
					</div>
					
					<div class="clear"></div>
					<div class="inputshell">
						<p>Type Of User<em>*</em></p>
						<?php if(!empty($usertypesAll)) { ?>
							<select class="required" id="user_type" name="user_type">
								<option value=""> Select User Type </option>
								<?php foreach($usertypesAll as $userTypes) { ?>
									<option value="<?php echo $userTypes['id'];?>" <?php if(isset($_SESSION['data']['user_type']) && $_SESSION['data']['user_type'] == $userTypes['id']){ echo "selected='selected'"; } ?> ><?php echo ucwords($userTypes['user_type']);?></option>
								<?php } ?>							
							</select>

							<?php
							if(!isset($_SESSION['data']['user_type'])) { ?>								
							<script type="text/javascript">
							jQuery(document).ready(function(){
								jQuery("#user_type").change(function(){								
									var user_type  = jQuery("#user_type").val();
									if(user_type != ''){
										if(user_type != '5'){	
											if(!jQuery("#number_of_users").hasClass('numberofusers')){
												jQuery("#number_of_users_show_div").after('<div id="number_of_users_div" class="inputshell"><p>Number of users<em>*</em></p><span class="left"><input placeholder="Enter number of users" name="number_of_users" value="" type="text" class="numberofusers digits required" id="number_of_users" />&nbsp;&nbsp;Value should be greater than 0.<br><label style="display:none;" for="number_of_users" generated="true" class="error">This field is required.</label></span></div><div class="clear"></div>');
											}
											jQuery("#number_of_users_hidden").remove();
										} else {
											jQuery("#number_of_users_div").remove();
											if(!jQuery("#number_of_users_hidden").hasClass('numberofusershidden')){
												jQuery("#number_of_users_show_div").after('<input name="number_of_users" value="1" class="numberofusershidden" type="hidden" id="number_of_users_hidden" />');
											}
										}
									} else {
										jQuery("#number_of_users_div").remove();
										jQuery("#number_of_users_hidden").remove();
									}
								});	
							});
							</script>
							<?php } ?>
						<?php } ?>
					</div>
					<div id="number_of_users_show_div" class="clear">
						<?php
						if(isset($_SESSION['data']['user_type'])) { ?> 
							<div id="number_of_users_div" class="inputshell">
								<p>Number of users<em>*</em></p>
								<span class="">
								<input placeholder="Enter number of users" name="number_of_users" value="<?php if(isset($_SESSION['data']['number_of_users'])){ echo $_SESSION['data']['number_of_users']; }?>" type="text" class="digits required" id="number_of_users" />&nbsp;&nbsp;Value should be greater than 0.<br>
								<label style="display:none;" for="number_of_users" generated="true" class="error">This field is required.</label>
								</span>
							</div>
							<div class="clear"></div>
						<?php } ?>
					</div>

					<div class="inputshell">
						<p>Address</p>
						<textarea rows="3" cols="24" placeholder="Enter your address" name="address" class="" id="address" /><?php if(isset($_SESSION['data']['address'])){ echo $_SESSION['data']['address']; }?></textarea>						
					</div>
					<div class="clear"></div>
					<div class="inputshell">
						<p>Organization<!-- <em>*</em> --></p>
						<input placeholder="Enter your organization name" name="organisation" type="text" value="<?php if(isset($_SESSION['data']['organisation'])){ echo $_SESSION['data']['organisation']; }?>" class="" id="organisation" />
					</div>
					<div class="clear"></div>
					<div class="inputshell">
						<p>Organization Address</p>
						<textarea placeholder="Enter your organisation address" name="organisation_address" class="" id="organisation_address" rows="3" cols="24" /><?php if(isset($_SESSION['data']['organisation_address'])){ echo $_SESSION['data']['organisation_address']; }?></textarea>
					</div>
					<div class="clear"></div>
					
					<div class="inputshell">
						<p>Select account Type<em>*</em></p>
						<input class="required" type="radio" <?php if(isset($_SESSION['data']['account_type']) && $_SESSION['data']['account_type'] == 'TP'){ echo 'checked'; }?> value="TP" id="account_type_tp" name="account_type">
						<span class="pL5"> Trial Account </span>
						<?php if(isset($totalplan) && $totalplan > 0) { ?>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input <?php if(isset($_SESSION['data']['account_type']) && $_SESSION['data']['account_type'] == 'PA'){ echo 'checked'; }?> type="radio" value="PA" id="account_type_pa" name="account_type">
						<span class="pL5"> Purchase Account </span>
						<?php } ?>						
					</div>
					
					<div class="clear"></div>
					<div class="inputshell">
						<p>Captcha<em>*</em></p>
						<div class="left">
							<span id="captcha_code"><?php  if(!isset($_SESSION['user'])){ require_once($DOC_ROOT.'captcha_code_file.php'); } ?>
							</span><br>
							<small>Can't read.<a href='javascript: refreshCaptchaCode();'>click here</a> to refresh</small>						
						</div>
					</div>
					<div class="clear"></div>
					<?php if(!isset($step)) { ?>
					<div class="inputshell">
						<p>&nbsp;</p>
						<input type="submit" value="Submit" name="user_registration_submit" class="" />
						<input type="hidden" value="user_registration_hidden" name="user_registration_hidden">
						<input onclick="window.location=URL_SITE+'/userRegistration.php?cancel=1'" type="button" value="Reset" name="cancel" class="mL20" />					
					</div>
					<?php } ?>
				</div>
			</form>
			
		</div>
		<!-- registration -->	

		<?php
		if(isset($step) && ($step == 'PA') && isset($_SESSION['data']['user_type'])) {
			
			$usertypeid  = $_SESSION['user_typeid'] = $_SESSION['data']['user_type'];
			$plansResult = $admin->selectuserTypes($usertypeid);
			$total_user_type = count($plansResult);
			?>
			
			<!-- Right side -->
			<div  style="padding-right:20px;" class="right">
				
				<?php if(!empty($plansResult)) {	?>					
					
					<h2> Buy Subscription Plan </h2><br>
					
					<div style="background:#F9F9F9;width:680px;">

						<form method="POST" action="<?php URL_SITE?>paypal/makepayment.php" enctype="multipart/form-data" id="make_payment_form" name="make_payment_form">

							<h3 class="pT20"> Choose Plan </h3>
							<br class="clear">

							<div class="plantab">
								<ul class="ui-tabs-nav">
								   <li id="active_<?php echo $plansResult['id'];?>" class="<?php if(isset($_SESSION['user_typeid']) && $_SESSION['user_typeid']==$plansResult['id']) { echo "selected"; } ?> ui-tabs-selected">
									<a href="javascript:;" onclick="javascript: selectAllPlansforSubscription('<?php echo $plansResult['id'];?>','<?php echo $plansResult['user_type'];?>')"><span class="pL5"> <?php echo ucwords($plansResult['user_type']);?> </span></a>
								   </li>
							</ul>
						</div>
					</form>

					<br class="clear" />
					<!-- SHOW_PLAN_AJAX_DETAIL -->
					<div class="pL30" id="show_plan_ajax_detail" style="">

						<!-- All Plan Detail -->					
						<?php
							$subscriptionid		=	$plansResult['id'];	
							$user_type			=	$_SESSION['user_type'] = strtolower($plansResult['user_type']);	
							$plan_res			=	$admin->selectsubscriptionsPlansOfUser($subscriptionid);
							$user_plan_details	=	$dbDatabase->getAll($plan_res);
							
							//echo "<pre>";print_r($user_plan_details);echo "</pre>";
							?>

							<div class="planinner">
																
								<h3>Choose&nbsp;<?php echo ucwords($user_type);?>&nbsp;Plan </h3>
								<br class="clear">			
								
								<?php if(!empty($user_plan_details)) {?>			
								
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
																			if($key == $databaseDetail['id']){
																				echo "<span class='db_".$databaseDetail['id']."'>". $db_values= $dbvalues."&nbsp;USD</span>";
																				if($databaseDetail['id']==4) {
																					echo "<span style='display:none;' class='db_free_".$databaseDetail['id']."'>Free</span>";
																				}
																			}
																		} 
																	}
																	?>
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
											<br class="clear pB20" />

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
					<h3> No Paln added Yet </h3>
				<?php } ?>
				
				<!-- show_selected_payment_form_mode -->
				<div class="pL30" id="show_selected_payment_form_mode" style="display:none;"></div>
				<!-- show_selected_payment_form_mode -->			
			
			</div>
		<!-- Right side -->	
		<?php } ?>

	</div>

</section>
<!-- /container -->
<div class="clear"></div>

<?php include_once $basedir."/include/footerHtml.php"; ?>