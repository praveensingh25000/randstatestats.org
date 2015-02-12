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

$user_id = $_SESSION['user']['id'];

$userDetail	= $user->getUser($_SESSION['user']['id']);

if(isset($_REQUEST['usertype']) && $_REQUEST['usertype']!='' && is_numeric($_REQUEST['usertype'])){
	$typeid = $_REQUEST['usertype'];
} else {

	$typeid = $userDetail['user_type'];
}

$typeDetail = $admin->getUserType($typeid);

if(isset($_POST['submit'])){
	$_SESSION['payment_upgrade'] = $_POST;
	header('location:'.URL_SITE.'/paypal/upgradeFormCheckout.php');
	exit;
}

$pricetxt = '%';

$user_type = $typeDetail['id'];

$userTypeInfo = $user->getUserTypeInfo($user_type);

$number_of_users = $userDetail['number_of_users'];

//$daysleft = 90;

$usertypesResult = $admin->showAllUserTypes();

$databasesalreadypurchased	=	$admin->selectValidPlanofUser($user_id,'');

if(isset($_POST['submit'])){
	$_SESSION['payment_upgrade'] = $_POST;
	echo "<pre>";
	print_r($_SESSION['payment_upgrade']);
	die;
	header('location:'.URL_SITE.'/paypal/upgradeFormCheckout.php');
	exit;
}


?>

<section id="container">	
	<div id="inner-content" class="conatiner-full">
		<h2>Upgrade Subscription Plan</h2><br>
		<?php if(count($databasesalreadypurchased)>0){ ?>
			<div style="color:red"><strong>Note:</strong>&nbsp;You already have databases that are still not expired. Upgrading account will deactive those databases and your account will be modified with the selected plan type. If you are sure then only proceed otherwise wait for the databases to expire or you can contact admin.</div>
		<?php
		}
		?>
		<div style="background:#F9F9F9;width:680px;">
		
		<form method="post" action="" id="make_payment_form_mode" name="make_payment_form_mode">
			<div class="pL30" id="show_plan_ajax_detail" style="">
				<h3 class="pT20 pB10">Plan: <?php echo ucwords($typeDetail['user_type']);?> </h3><br/>
				<div>
				User Type: <select id="user_type" name="user_type" onchange="javascript: changePlan(this.value, 'upgrade.php');">
				<option value=""> --- Select User Type --- </option>
				<?php if(mysql_num_rows($usertypesResult)>0){ while($userTyped = mysql_fetch_assoc($usertypesResult)){?>
				<option value="<?=$userTyped['id']?>" <?php if($userTyped['id'] == $user_type){ echo "selected"; } ?>><?=$userTyped['user_type']?></option>								
				<?php } }?>
				</select>
				</div>
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
								<td><span id="planval">1 year</span></td>					
							</tr>	

							<?php if($user_type != 5){ ?>
							<tr>
								<th>Number of Users</th>				
								<td>
									<input type="text" value="<?=$number_of_users?>" name="number_of_users" onclick="" class="required digits" onchange="javascript: getTotalFunction();" />
								</td>
							</tr>	
							<?php } else { ?>
							
							<input type="hidden" value="1" name="number_of_users" />

							<?php }?>
					
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

											$readonly = '';											
									?>					
											
											<div class="left pT5 pB5">
												<input onchange="javascript: getTotalFunction();" id="checked_unchecked_<?php echo $databaseDetail['id']?>" value="<?php echo $dbid?>" type="checkbox" class="dbToSelect" name="db_name[]" />
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
						</div>
					</div>
				</div>	<!-- planinner div -->
			</div> <!-- show_plan_ajax_detail -->
		</form>
		</div>
	</div>
</section>
<!-- /container -->
<div class="clear"></div>

<?php include_once $basedir."/include/footerHtml.php"; ?>

