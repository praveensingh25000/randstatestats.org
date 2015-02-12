<?php
/******************************************
* @Modified on July 9, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

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
			
			$name				= mysql_real_escape_string($_POST['name']);	// First Name
			$last_name			= mysql_real_escape_string($_POST['last_name']);
			$email				= mysql_real_escape_string(trim($_POST['email']));
			$endode_email		= base64_encode($email);
			$pw					= mysql_real_escape_string($_POST['password']);
			$phone				= $_POST['phone'];
			$address			= mysql_real_escape_string($_POST['address']);
			$organisation		= mysql_real_escape_string($_POST['organisation']);
			$org_address		= mysql_real_escape_string($_POST['organisation_address']);
			$user_type			= trim($_POST['user_type']);
			$number_of_users	= (isset($_POST['number_of_users']))?trim(addslashes($_POST['number_of_users'])):'0';
			
			$userid	= $user->userRegistration($name,$email,$pw,$phone,$address,$organisation,$org_address,$user_type, 0, $last_name);

			$update_numbersofusers = $user ->updateNumberofUsers($userid, $number_of_users);
			
			$db_valuesArray = array(1 => '0', 2 => "0", 3 => "0", 4 => "0");
	
			$validity = VALIDITY;

			$is_trial = 0;
			
			$insert	= $admin->insertMembershipDatabaseDetail('0',$userid, '1', $db_valuesArray,$validity,$is_trial);

			if(isset($mail_notification) && $mail_notification == '1'){	
				$templateKey		=	2;
				$receivermail		=	trim($email);
				$receivename		=	ucwords($name." ".$last_name);
				$from_name			=	FROM_NAME;
				$from_email			=	FROM_EMAIL;
				$userDefinedArray[] =   array('endode_email' => $endode_email);						
				
				$mailbody			=	getMailTemplate($templateKey, $receivename, $receivermail, $from_name, $from_email,$userDefinedArray);		
				
				$subject='Registration Verfication Mail';	
				$send_mail= mail_function($receivename,$receivermail,$from_name,$from_email,$mailbody,$subject, $attachments = array(),$addcc=array());
			}
		
			$_SESSION['msgsuccess'] = "6";
			unset($_SESSION['data']);
			header("location:index.php");

		}else{
			$_SESSION['data'] = $_POST;
			$_SESSION['msgsuccess'] ="20";
			header("location:signupNew.php?step=".base64_encode($_POST['account_type'])." ");
		}		
	  } else {
		$_SESSION['data'] = $_POST;
		$_SESSION['msgerror'] = '15';
		header("location:signupNew.php");
	  }	
}
if(isset($_GET['cancel'])){
	unset($_SESSION['data']);
	header("location:signupNew.php");
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

			<form method="POST" id="user_registration" name="user_registration" action="<?php echo URL_SITE;?>/signup.php">
			
				<div class="registr" <?php if(isset($step) && $step == 'PA') { ?> style="display:none;width:550px;" <?php } ?> >
					<div class="inputshell">
						<p>First Name<em>*</em></p>
						<input placeholder="Enter your first name" name="name" type="text" value="<?php if(isset($_SESSION['data']['name'])){ echo $_SESSION['data']['name']; }?>" class="required" id="name" />
					</div>
					<div class="clear"></div>

					<div class="inputshell">
						<p>Last Name</p>
						<input placeholder="Enter your last name" name="last_name" type="text" value="<?php if(isset($_SESSION['data']['last_name'])){ echo $_SESSION['data']['last_name']; }?>" class=""  />
					</div>
					<div class="clear"></div>

					<!-- <div class="inputshell">
						<p>Username</p>
						<input placeholder="Enter your username" name="username" value="<?php if(isset($_SESSION['data']['username'])){ echo $_SESSION['data']['username']; }?>" type="text" class="" id="username" />							
					</div>
					<div class="clear"></div> -->

					<input name="username" type="hidden" class="" value="" />

						
					<div class="inputshell">
						<p>Organization<!-- <em>*</em> --></p>
						<input placeholder="Enter your organization name" name="organisation" type="text" value="<?php if(isset($_SESSION['data']['organisation'])){ echo $_SESSION['data']['organisation']; }?>" class="" id="organisation" />
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
						<input placeholder="Enter your phone Number" name="phone" type="text" value="<?php if(isset($_SESSION['data']['phone'])){ echo $_SESSION['data']['phone'];}?>" class="required" id="phone" onchange="chckphone('phone')"/>&nbsp;&nbsp;<em>eg:123-123-1234</em>
					</div>
					
					<div class="clear"></div>
					<div class="inputshell">
						<p>User Type<em>*</em></p>
						<?php if(!empty($usertypesAll)) { ?>
							<select class="required" id="user_type" name="user_type">
								<option value=""> Select User Type </option>
								<?php foreach($usertypesAll as $userTypes) { ?>
									<option value="<?php echo $userTypes['id'];?>" <?php if(isset($_SESSION['data']['user_type']) && $_SESSION['data']['user_type'] == $userTypes['id']){ echo "selected='selected'"; } ?> ><?php echo ucwords($userTypes['user_type']);?></option>
								<?php } ?>							
							</select>

							<?php
							if(!isset($_GET['step'])) { ?>								
							<script type="text/javascript">
							jQuery(document).ready(function(){
								jQuery("#user_type").change(function(){								
									var user_type  = jQuery("#user_type").val();
									if(user_type != ''){
										if(user_type != '5'){	
											jQuery("#number_of_users_show_div").show();
											jQuery("#number_of_users").val("");
										} else {
											jQuery("#number_of_users_show_div").hide();
											jQuery("#number_of_users").val("1");
										}
									} else {
										jQuery("#number_of_users_show_div").hide();
										jQuery("#number_of_users").val("1");
									}
								});	
							});
							</script>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="clear"></div>

					<?php 
					if(isset($_SESSION['data']['user_type']) && $_SESSION['data']['user_type']!=5){ 
						$showNoOfUsers = "block";
					} else {
						$showNoOfUsers = "none";
					}
					?>
			
					<div  class="inputshell" id="number_of_users_show_div" style="display:<?php echo $showNoOfUsers; ?>;">
						<p>Number of users<em>*</em></p>
						<span class="">
						<input placeholder="Enter number of users" name="number_of_users" value="<?php if(isset($_SESSION['data']['number_of_users'])){ echo $_SESSION['data']['number_of_users']; } else { echo "1"; }?>" type="text" class="digits required" id="number_of_users" />&nbsp;&nbsp;<a href="javascript:;" id="popupnoofusers"><img src="/images/question16x16.png"></a><br/><span style="padding-left: 180px;">Value should be greater than 0.</span>
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
			

					<div class="inputshell">
						<p>Address</p>
						<textarea rows="3" cols="24" placeholder="Enter your address" name="address" class="" id="address" /><?php if(isset($_SESSION['data']['address'])){ echo $_SESSION['data']['address']; }?></textarea>						
					</div>
					<div class="clear"></div>
					
					<input type="hidden" name="organisation_address" value="" />
				
					<!-- <div class="inputshell">
						<p>Organization Address</p>
						<textarea placeholder="Enter your organisation address" name="organisation_address" class="" id="organisation_address" rows="3" cols="24" /><?php if(isset($_SESSION['data']['organisation_address'])){ echo $_SESSION['data']['organisation_address']; }?></textarea>
					</div>
					<div class="clear"></div> -->
					
					<div class="inputshell">
						<p>Select Account Type<em>*</em></p>
						<input class="required" type="radio" <?php if(isset($_SESSION['data']['account_type']) && $_SESSION['data']['account_type'] == 'TP'){ echo 'checked'; }?> value="TP" id="account_type_tp" name="account_type">
						<span class="pL5"> Trial Account </span>
						<?php if(isset($totalplan) && $totalplan > 0) { ?>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input <?php if(isset($_SESSION['data']['account_type']) && $_SESSION['data']['account_type'] == 'PA'){ echo 'checked'; }?> type="radio" value="PA" id="account_type_pa" name="account_type">
						<span class="pL5"> Purchase Account </span>
						<?php } ?>						
					</div>
					
					<div class="clear"></div>

					<?php if(!isset($step)) { ?>

					<div class="inputshell">
						<p>Captcha<em>*</em></p>
						<div class="left">
							<span id="captcha_code"><?php  if(!isset($_SESSION['user'])){ require_once($DOC_ROOT.'captcha_code_file.php'); } ?>
							</span><br>
							<small>Can't read.<a href='javascript: refreshCaptchaCode();'>click here</a> to refresh</small>						
						</div>
					</div>
					<div class="clear"></div>
					
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
		
		<?php if(isset($step) && $step == 'PA' && isset($_SESSION['data']['user_type'])) { ?> 


		<div class="clear"></div>

		<form method="post" action="<?php echo URL_SITE;?>/paypal/postFormcheckoutDataNew.php" id="make_payment_form_mode" name="make_payment_form_mode">
			<div class="left">
				<?php include("billing_other_contacts.php");?>
			</div>


			<?php
			$usertypeid  = $_SESSION['user_typeid'] = $_SESSION['data']['user_type'];
			$plansResult = $admin->selectuserTypes($usertypeid);
			$total_user_type = count($plansResult);

			if(isset($_SESSION['user']['number_of_users'])) {
				$number_of_users = $_SESSION['user']['number_of_users'];	
			} else if(isset($_SESSION['data']['number_of_users'])) {
			$_SESSION['data']['number_of_users'];
				$number_of_users = $_SESSION['data']['number_of_users'];
			} else {
				$number_of_users = 1;								
			}

			$user_type = $plansResult['user_type'];

			$userTypeInfo = $user->getUserTypeInfo($usertypeid);

			?>
			
			<!-- Right side -->
			<div  style="padding-right:20px;" class="right">		
				<?php if(!empty($plansResult)) {	?>	
				<div style="background:#F9F9F9;width:680px;">
					<h2>Purchase Subscription Plan</h2><br>
					<div class="pL30" id="show_plan_ajax_detail" style="">
						<h3 class="pT20 pB10">Plan: <?php echo ucwords($user_type);?> </h3>
						<hr><br/>
						<div class="planinner">		
							<div class="selectplan">									
										
								<h3 class="pB10">Choose a Plan Length</h3>	
								<input type="radio" name="plan_name" value="1"  checked="" onchange="javascript: getTotalFunction();">One Year	
										
								<input type="radio" name="plan_name" value="2" onchange="javascript: getTotalFunction();">Two Year	
										
								<input type="radio" name="plan_name" value="3" onchange="javascript: getTotalFunction();">Three Year	
									
							</div>

							<div class="clear pT15"></div>
							<div id="show_plan_ajax_detail1_particular" style="">
								<h3 >Plan Detail<h3> 
								<br class="clear" />
								<table class="data-table">
									
									<tr>
										<th>Plan Validity</th>				
										<td><span id="planval">1 year</td>					
									</tr>	

									<?php if(isset($number_of_users)) { ?>
									<tr>
										<th>Number of Users</th>				
										<td><?php echo number_format($number_of_users);?></td>
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
										<th>Choose a Database or Multiple Databases</th>				
										<td>
											<?php
											$databasesResult_res = $admin->showAllDatabasesDetail();
											$total = $db->count_rows($databasesResult_res);
											$databases = $db->getAll($databasesResult_res);
											
											if(!empty($databases)) {							
												$databases_slice=array_slice($databases,0,4);
												foreach($databases_slice as $key => $databaseDetail){
													
													

													$dbid = $databaseDetail['id'];
													
											?>					
													
													<div class="left pT5 pB5">						
														<input onchange="javascript: getTotalFunction();" id="checked_unchecked_<?php echo $databaseDetail['id']?>" value="<?php echo $dbid?>" type="checkbox" class="dbToSelect" name="db_name[]">
														<span class="pL20 "><?php echo $databaseDetail['db_code'];?></span>
													</div>

													<div class="right pT5 pB5">
														<span class="db_price_<?php echo $databaseDetail['id']?>">
														$
														<?php 
														if($databaseDetail['db_code'] == 'US'){
															echo $userTypeInfo['basepriceus'];
															
														} else {
															echo $userTypeInfo['baseprice'];
														}
														?>
														</span>


														<span style="display:none;" class="db_free_<?php echo $databaseDetail['id']?>">Free</span>
														
													</div>
													<br class="clear" />							
												<?php } ?> 

											<?php } ?>

										</td>					
									</tr>

									<tr></tr>
									<tr></tr>

									<tr id="surchargeamounttr" style="display:none;">
										<th><span id="surchargetxt"></span></th>	
										<td>
											<div class="left">&nbsp;</div>
											<div class="right">
												$<span id="surcharge_amount"> 0 </span>&nbsp;
												<input id="surcharge_amount_input" type="hidden" name="surcharge_amount" value="0">
											</div>
											<br class="clear" />
										</td>					
									</tr>

									<tr id="discountamounttr" style="display:none;">
										<th><span id="discounttxt"></span></th>	
										<td>
											<div class="left">&nbsp;</div>
											<div class="right">
												$<span id="discount_amount"> 0 </span>&nbsp;
												<input id="discount_amount_input" type="hidden" name="discount_amount" value="0">
											</div>
											<br class="clear" />
										</td>					
									</tr>


									<tr>
										<th>Total Amount :<br>(Users times price/user)
										<span id="minimumamounttxt"></span>
										</th>	
										<td>
											<div class="left">&nbsp;</div>
											<div class="right">
												$<span id="total_amount"> 0 </span>&nbsp;
												<input id="total_amount_input" type="hidden" name="total_amount" value="0">
												<input id="user_type" type="hidden" name="user_type" value="<?=$usertypeid?>">
											</div>
											<br class="clear" />
										</td>					
									</tr>
								</table>
								<div id="details"></div>

								<div id="detailsCheckout" style="display:none;" class="pT10">
									<h3> Choose Your Payment Mode </h3>
									<br class="clear">

									<div class="selectplan">
										<input id="DoExpressCheckoutPayment" type="radio" value="DoExpressCheckoutPayment" name="mode" checked>
										<span class="pL5"> Using PayPal. </span>&nbsp;&nbsp;&nbsp;&nbsp;

										<input id="credit_card_url" type="radio" value="DoDirectPayment" name="mode">
										<span class="pL5"> Using Credit Card. </span>
										<SCRIPT LANGUAGE="JavaScript">
										jQuery(document).ready(function(){
											jQuery("#credit_card_url").click(function(){				
												if(jQuery("#credit_card_url").attr("checked")) {
													jQuery("#credit_card_url_div").show();
												}else{
													jQuery("#credit_card_url_div").hide();			
												}			
											});

											jQuery("#DoExpressCheckoutPayment").click(function(){				
												if(jQuery("#DoExpressCheckoutPayment").attr("checked")) {
													jQuery("#credit_card_url_div").hide();
												}			
											});
										});
										</SCRIPT>
									</div>
									<br class="clear" />
								</div>

								<div id="credit_card_url_div" class="" style="background: #EBEBEB;display:none;">
										
									<table width="600" border="0" cellspacing="5">
										<tr>
											<td align="right">First Name:</td>
											<td align="left"><input placeholder="firstName" type="text" size="30" maxlength="32" name="firstName" class="required" value=""></td>
										</tr>
										<tr>
											<td align="right">Last Name:</td>
											<td align="left"><input placeholder="lastName" type="text" size="30" maxlength="32" name="lastName" class="required" value=""></td>
										</tr>
										<tr>
											<td align="right">Card Type:</td>
											<td align="left">
												<select name="creditCardType" onChange="javascript:generateCC(); return false;">
													<option value="Visa" selected>Visa</option>
													<option value="MasterCard">MasterCard</option>
													<option value="Discover">Discover</option>
													<option value="Amex">American Express</option>
												</select>
											</td>
										</tr>
										<tr>
											<td align="right">Card Number:</td>
											<td align="left"><input class="creditcard" placeholder="creditCardNumber" type="text" size="19" maxlength="19" name="creditCardNumber" value=""></td>
										</tr>
										<tr>
											<td align="right">Expiration Date:</td>
											<td align="left"><p>
												<select name="expDateMonth">
													<option value="1">01</option>
													<option value="2">02</option>
													<option value="3">03</option>
													<option value="4">04</option>
													<option value="5">05</option>
													<option value="6">06</option>
													<option value="7">07</option>
													<option value="8">08</option>
													<option value="9">09</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12" selected >12</option>
												</select>
												<select name="expDateYear">
													<option value="2005">2005</option>
													<option value="2006">2006</option>
													<option value="2007">2007</option>
													<option value="2008">2008</option>
													<option value="2009">2009</option>
													<option value="2010">2010</option>
													<option value="2011">2011</option>
													<option value="2012">2012</option>
													<option value="2013" selected>2013</option>
													<option value="2014">2014</option>
													<option value="2015">2015</option>
													<option value="2016">2016</option>
													<option value="2017">2017</option>
													<option value="2018">2018</option>
													<option value="2019">2019</option>
												</select>
											</p></td>
										</tr>
										<tr>
											<td align="right">Card Verification Number:</td>
											<td align="left"><input class="required" type="text" size="3" maxlength="4" name="cvv2" value=""></td>
										</tr>
										<tr>
											<td align="right"><br><b>Billing Address:</b></td>
										</tr>
										<tr>
											<td align="right">Address 1:</td>
											<td align="left"><input class="required" type="text" size="25" maxlength="100" name="address1" value=""></td>
										</tr>
										<tr>
											<td align="right">Address 2:</td>
											<td align="left"><input type="text"  size="25" maxlength="100" name="address2">(optional)</td>
										</tr>
										<tr>
											<td align="right">City:</td>
											<td align="left"><input type="text" class="required" size="25" maxlength="40" name="city" value=""></td>
										</tr>
										<tr>
											<td align="right">Country:</td>
											<td align="left">
												<select class="required" id="countryCode" name="countryCode">
													<?php foreach($countries as $codeC => $cName){ ?>
													
													<option value="<?php echo $codeC;?>" <?php if($codeC == 'US'){ echo "selected"; } ?>><?php echo ucwords($cName);?></option>

													<?php } ?>
												</select>
											</td>
										</tr>
										<tr>
											<td align="right">State:</td>
											<td align="left">
												<select class="required" id="state" name="state">
													<option value="">Select State</option>
													<?php 
													if(!empty($stateToName)){
														foreach($stateToName as $statecode => $statename){?>
														<option value="<?php echo $statecode;?>"><?php echo ucwords($statename);?></option>
														<?php }
													}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td align="right">ZIP Code:</td>
											<td align="left"><input type="text" class="required" size="10" maxlength="10" name="zip" value="">(5 or 9 digits)</td>
										</tr>
										
												
									</table>
								</div>
								
								<br class="clear" />
								<div id="proceed_to_payment_type" class="submit1 txtcenter pT10">
									<label for="submit">
										<input type="submit" name="submit" value="Proceed To Payment" class="submitbtn" onclick="javascript: return checkAmount();">
									</label>
									<label for="cancel pL10">					
										<input onclick="javascript: window.location=URL_SITE+'/index.php'" type="button" name="submit" value="Cancel" class="submitbtn" >
									</label>
								</div>
								<br class="clear pB20" />

								<?php } else { ?>
								<h4> Plan detail not available this time. </h4>
								<?php } ?>									
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</form>
	</div>
</section>
<!-- /container -->
<div class="clear"></div>

<?php include_once $basedir."/include/footerHtml.php"; ?>