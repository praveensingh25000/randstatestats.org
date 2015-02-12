<?php
/******************************************
* @Modified on 25 JAN 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/";
include_once $basedir."/include/actionHeader.php";

$catObj					=	new Category();
$admin					=	new admin();
$user					=	new user();

if(isset($_GET['selectplan']) && $_GET['selectplan']!='') {

	$user_plan_details	=	$allInstutionTypeDetail	=	$allinstutionplanDetails	=	array();

	$subscriptionid			=	$_POST['subscriptionid'];	
	$user_type				=	strtolower($_POST['user_type']);	
	$plan_res				=	$admin->selectsubscriptionsPlansOfUser($subscriptionid);
	$user_plan_details		=	$dbDatabase->getAll($plan_res);
	?>

	<div class="planinner">			
			
			<h3>Select&nbsp;<?php echo ucwords($user_type);?>&nbsp;Plan </h3>
			<br/>
			<div id="" class="clear pB5"></div>			
			<?php if(!empty($user_plan_details)) {?>			
			
				<?php 
				foreach($user_plan_details as $key =>$plansResult) {?>	
					<div class="selectplan">
						<input onclick="selectdetailPlansforSubscription('<?php echo $plansResult['id'];?>','<?php echo $user_type;?>')" type="radio" value="<?php echo $plansResult['id'];?>" name="plan_name"><?php echo ucwords(stripslashes($plansResult['plan_name']));?>	
					</div>
				<?php } ?>
				<br class="clear" />

				<!-- SHOW_PLAN_AJAX_DETAIL -->
				<div id="show_plan_ajax_detail1_particular" style="display:none;"></div>
				<!-- SHOW_PLAN_AJAX_DETAIL -->	
			
			<?php } else { ?>
			<h3> No Plan available. </h3>
			<?php } ?>
			
	    </div>	
<?php } ?>

<?php
if(isset($_GET['plandetail']) && $_GET['plandetail']!='') {
	$planid				=	$_POST['planid'];	
	$user_type			=	strtolower($_POST['user_type']);
	$user_plan_details	=	$admin->SelectparticularSubscriptionDetail($planid);
	?>

	<div id="" class="pT10">
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
												if($databaseDetail['id']==4){
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
					}else{
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
	</div>
	<!-- Plan Detail -->	
<?php } ?>


<?php
if(isset($_GET['paymentmode']) && $_GET['paymentmode']!='') {

	if(!empty($_POST['db_name'])){
	$dbidstr			=	implode(',',$_POST['db_name']);
	}
	
	if(isset($_SESSION['user']) && isset($_POST['db_membership_id']) && $_POST['db_membership_id']!='') {
		$db_membership_id	=	$_POST['db_membership_id'];	
	}
	
	$planid				=	$_POST['planid'];
	$total_amount		=	trim($_POST['total_amount']);
	$user_plan_details	=	$admin->SelectparticularSubscriptionDetail($planid);
	?>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#make_payment_form_mode").validate();
		});
	</script>

	<form method="post" action="<?php echo URL_SITE;?>/paypal/postFormcheckoutData.php" id="make_payment_form_mode" name="make_payment_form_mode">
		
		<h3> Choose Your Payment Mode </h3>
		<br class="clear">

		<div class="selectplan">
			<input id="DoExpressCheckoutPayment" type="radio" value="DoExpressCheckoutPayment" name="mode">
			<span class="pL5"> Using PayPal. </span>&nbsp;&nbsp;&nbsp;&nbsp;

			<input id="credit_card_url" type="radio" value="DoDirectPayment" name="mode">
			<span class="pL5"> Using Credit Card. </span>
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
							<?php
							$databasesResult_res = $admin->showAllDatabasesDetail();
							$total = $db->count_rows($databasesResult_res);
							$databases = $db->getAll($databasesResult_res);
							?>
							<select class="required" id="countryCode" name="countryCode">
								<option value="">Select Country</option>
								<?php 
								if(!empty($databases)){
									foreach($databases as $databasename){?>
									<option value="<?php echo $databasename['db_code'];?>"><?php echo ucwords($databasename['database_label']);?></option>
									<?php }
								}
								?>
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
					
					<tr>
						<td align="right"><br>Amount:</td>
						<td align="left"><br><input id="amount_div" class="required" type="text" size="4" maxlength="7" name="amount" value=""> USD </td>
					</tr>
					
				</table>
			</div>


			<div class="submit1 txtcenter pT10">
				<label for="submit">
					<input id="Pay_palpal_pro_method" type="submit" name="Pay_palpal_pro_method" value="Pay" class="submitbtn" >
				</label>
				<label for="cancel pL10">					
					<input onclick="javascript: window.location=URL_SITE+'/index.php'" type="button" name="submit" value="Cancel" class="submitbtn" >
				</label>
				<input id="total_amount_click" type="hidden" name="total_amount" value="<?php echo $total_amount;?>">
				<?php 
				if(isset($_SESSION['user']) && isset($db_membership_id) && $db_membership_id!='') { ?>
			    <input type="hidden" name="db_membership_id" value="<?php echo $db_membership_id;?>">
				<?php } ?>
				<input id="db_name" type="hidden" name="db_name" value="<?php echo $dbidstr;?>">		
				<input type="hidden" name="planid" value="<?php echo $planid;?>">
			</div>
			
		</fieldset>

		<script type="text/javascript">
		jQuery(document).ready(function(){

			generateCC();
			
			jQuery("#make_payment_form_mode").click(function(){
				var radioValue=jQuery("input[type='radio'][name='mode']:checked").length;
				if(radioValue == '0'){
					alert('Please Select Payment Method');
					return false;
				}else{
					return true;					
				}
			});
			jQuery("#credit_card_url").click(function(){				
				if(jQuery("#credit_card_url").attr("checked")) {
					jQuery("#credit_card_url_div").show();
					total_amount=jQuery("#total_amount_click").val();
					jQuery("#amount_div").attr("value",total_amount);
				}else{
					jQuery("#credit_card_url_div").hide();
					jQuery("#amount_div").attr("value",'');					
				}			
			});

			jQuery("#DoExpressCheckoutPayment").click(function(){				
				if(jQuery("#DoExpressCheckoutPayment").attr("checked")) {
					jQuery("#credit_card_url_div").hide();					
					jQuery("#amount_div").attr("value",'');
				}			
			});
		});
		</script>

	</form>

<?php } ?>

<?php
if(isset($_GET['selectdbAmount']) && $_GET['selectdbAmount']!='') {
	
	$db_valuesArray		=	$dbidArray	=	$db_idArray = array();

	$decimalsettingstr  = '';

	$dbValuesUser	=	$decimalsetting = 0;
	
	if(!empty($_POST['db_name'])){

		$dbidArray			=	$_POST['db_name'];

		$planid				=	$_GET['selectdbAmount'];
		$usertype			=	trim($_GET['usertype']);
		$currentdbid		=   $_GET['currentdbid'];
		$number_of_users    =   $_GET['number_of_users'];
		
		if(isset($_SESSION['user'])) {

			$user_id			= $_SESSION['user']['id'];			
			$databaseUsersexits	= $admin->selectdatabaseUsers($currentdbid,$user_id);

			if(!empty($databaseUsersexits)) {
				$dbValuesUser	= 'Plan existing for this database already.You can choose other database.';
			} else {				
					$user_plan_details	=	$admin->SelectparticularSubscriptionDetail($planid);

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

						foreach($new_array as $key => $value11) {					
							if(!is_int($value11)) {					
								$decimalsettingstr .= $value11;
								break;
							}
						}

						if($decimalsettingstr!=''){
							$decimalsettingArray = explode('.',$decimalsettingstr);
							if(isset($decimalsettingArray[1])){
								$decimalsetting = strlen($decimalsettingArray[1]);
							}
						}						
					}	
					
					if(!empty($new_array) && !empty($dbidArray)) {
						foreach($dbidArray as $key1 => $dbidUser) {
							foreach($new_array as $dbkey => $dbvalues) {
								if($dbidUser == $dbkey){
								$db_valuesArray[$dbidUser]= $dbvalues;	
								}								
							}					
						}						
					}
					

					if(isset($db_valuesArray) and is_array($db_valuesArray) and count($db_valuesArray)>1 and isset($db_valuesArray[4]))
					{
						$db_valuesArray[4] = 0;						
					}
					
					if(!empty($db_valuesArray)) {

						if(isset($number_of_users) && $number_of_users!='0' && isset($decimalsetting) && $decimalsetting!=0) {
							$dbValuesUser=number_format((array_sum($db_valuesArray) * $number_of_users),$decimalsetting);
						} else {
							$dbValuesUser=round(array_sum($db_valuesArray) * $number_of_users);
						}
					}
				}
		
		} else {
				
			$user_plan_details	=	$admin->SelectparticularSubscriptionDetail($planid);

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

				foreach($new_array as $key => $value11) {					
					if(!is_int($value11)) {					
						$decimalsettingstr .= $value11;
						break;
					}
				}

				if($decimalsettingstr!=''){
					$decimalsettingArray = explode('.',$decimalsettingstr);
					if(isset($decimalsettingArray[1])){
						$decimalsetting = strlen($decimalsettingArray[1]);
					}
				}
			}	

			if(!empty($new_array) && !empty($dbidArray)) {
				foreach($dbidArray as $key1 => $dbidUser) {
					foreach($new_array as $dbkey => $dbvalues) {
						if($dbidUser == $dbkey)
						$db_valuesArray[$dbidUser]= $dbvalues;					
					}					
				}	
			}
			
			if(isset($db_valuesArray) and is_array($db_valuesArray) and count($db_valuesArray)>1 and isset($db_valuesArray[4])) {
				$db_valuesArray[4] = 0;					
			}

			if(!empty($db_valuesArray)) {
				if(isset($number_of_users) && $number_of_users!='0' && isset($decimalsetting) && $decimalsetting!=0) {
					$dbValuesUser=number_format((array_sum($db_valuesArray) * $number_of_users),$decimalsetting);
				} else {
					$dbValuesUser=round(array_sum($db_valuesArray) * $number_of_users);
				}
			}
		}
	}

	echo $dbValuesUser;
}
?>

<?php if(isset($_REQUEST['categoryid']) && $_REQUEST['categoryid']!='') {

	if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!=''){
		$siteMainDBDetail	= $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
		$db_name			= $siteMainDBDetail['database_label'];
	}

	$parent_id			= $_SESSION['categoryid']	= trim($_REQUEST['categoryid']);
	$_SESSION['cat']	= trim($_REQUEST['categoryid']);

	$allActiveDatabases			=	$catObj->showAllActiveDatabase($parent_id);
	$allActiveDatabaseschunk	=	array_chunk($allActiveDatabases, 2, true);
	$cat_detail_arr				=	$admin->getPatCategory($parent_id);
	$totalActiveForms			=	count($allActiveDatabases);

	//echo '<pre>';;print_r($allActiveDatabases);echo '</pre>';die;
	?>

	<div class="clear"></div>
	<div class="form-div">
		
		<h2 style="font-size:21px;"> 
			<?php if(isset($db_name)) echo $db_name;?> <?php echo $cat_detail_arr['category_title'];?> Statistics contains the following categories and Forms:
		</h2>
		<div class="clear pT30"></div>
		
<div class="from wdthpercent100">
<?php if(!empty($allActiveDatabaseschunk)) {

		foreach($allActiveDatabaseschunk as $allActiveDatabases) { 
			
			  foreach($allActiveDatabases as $categorykey => $formsDetail) {

				  if(!is_numeric($categorykey)) { ?>
						
						<div class="from-show">
							<h3>
								<?php if(isset($categorykey) && $categorykey !='0') { echo ucwords($categorykey); } ?>
							</h3>
							<div class="clear pT10"></div>
							
							<?php foreach($formsDetail as $key => $formsDetailAll) {
								
								$formsDetailArray = array_sort($formsDetailAll, 'share', $order=SORT_ASC);
								
								foreach($formsDetailArray as $key => $forms) { ?>

									<ul>
										<?php 
										if(isset($forms['is_static_form']) && $forms['is_static_form'] == 'Y' && $forms['url']!='' ){ 
											
											if(isset($forms['db_select']) && $forms['db_select'] != '' && $forms['form_id_us']!='' ) { 
											$url=$forms['url'];
											?>
												<li>
													<a href="?dbc=<?php echo base64_encode($forms['db_select']);?>&url=<?php echo $url ;?>"><?php if(isset($forms['form_name'])) { echo stripslashes($forms['form_name']); } else { echo stripslashes($forms['db_name']); } ?>&nbsp;(50 States)</a>
												</li>
											<?php } else { ?>
												<li>
													<a href="<?php echo URL_SITE;?>/<?php echo $forms['url'];?>"><?php echo stripslashes($forms['db_name']);?></a>
												</li>
											<?php } ?>

										<?php } else { ?>
												
												<?php if(isset($forms['db_select']) && $forms['db_select'] != '' && $forms['form_id_us']!='' ) {
													?>
													<li>
														<a href="?formid=<?php echo base64_encode($forms['form_id_us']);?>&dbc=<?php echo base64_encode($forms['db_select']);?>"><?php if(isset($forms['form_name'])) { echo stripslashes($forms['form_name']); } else { echo stripslashes($forms['db_name']); } ?>&nbsp;(50 States)</a>
													</li>
											<?php } else { ?>
													<li>
														<a href="form.php?id=<?php echo base64_encode($forms['id']);?>"><?php echo stripslashes($forms['db_name']);?></a>
													</li>
											<?php } ?>
										<?php } ?>
									</ul>
								<?php } ?>
							<?php } ?>
						</div>
						
					<?php  } else { ?>	
					<div class="from-show">
						
						<?php foreach($formsDetail as $key => $formsDetailAll) {											
							$formsDetailArray = array_sort($formsDetailAll, 'share', $order=SORT_ASC);
							
							foreach($formsDetailArray as $key => $forms) { ?>				
								<ul>
									<?php
									if($forms['is_static_form'] == 'Y' && $forms['url']!='' ){ ?>
										<li><a href="<?php echo URL_SITE;?>/<?php echo $forms['url'];?>"><?php echo stripslashes($forms['db_name']);?></a></li>
									<?php } else { ?>
										<li><a href="form.php?id=<?php echo base64_encode($forms['id']);?>"><?php echo stripslashes($forms['db_name']);?></a></li> 
									<?php } ?>
								</ul>
							<?php }	?>
						<?php }	?>
					</div>
					<?php }	?>								
				<?php } ?>					
		<?php } ?>
<?php } else {	echo '<div class="from-show"><h4>No Forms Found.</h4></div>'; } ?>		
</div>
	</div>
<?php } ?>


<?php
if(isset($_GET['addIPRangesqq']) && $_GET['addIPRangesqq']!='') {

	$ip_range_out		=	$ip_rangeExist		=	$ip_rangeallExist	=	$ip_rangeAll	=   $ip_range	=	$ipsstrArray	=	array();
	$toatl_ip_allowed	=	$totaluserIPDetail  =	$ipAdderSetter = 0;
	
	$toatl_ip_allowed   =   IP_RANGE;	
	$userid			    =	$_POST['user_id'];
	$ipsstr			    =   trim(str_replace(' ', '', $_POST['ips']));
	$instution_id	    =	$_POST['instution_id'];
	$is_verified	    =	0;

	$userIPDetail_res	=	$admin->selectUserIPAdressAll($userid);
	$totaluserIPDetail  =   $db->count_rows($userIPDetail_res);
	$userIPDetail		=   $db->getAll($userIPDetail_res);

	$ipAdderSetter		=	$toatl_ip_allowed - $totaluserIPDetail;

	if(isset($ipAdderSetter) && $ipAdderSetter != 0){	

		if(isset($ipsstr) && $ipsstr !=''){	
			$ipsstrmain = preg_replace('/[^.,0-9_-]/s', '', $ipsstr);
			
			if($ipsstrmain){
				$ipsstrArray = explode(',',$ipsstrmain);
				if(!empty($ipsstrArray)){
					foreach($ipsstrArray as $ipsstrarrayAll){
						$ipsstrArrayout = explode('-',$ipsstrarrayAll);
						if(isset($ipsstrArrayout[0]) && count($ipsstrArrayout) == '2'){					
							$iprange1 = ip2long($ipsstrArrayout[0]);					
							$iprange2 = ip2long($ipsstrArrayout[1]);					
							$diffips = ($iprange2 - $iprange1);	
							for($i=0;$i<=$diffips;$i++){
								$ip2long	= long2ip($iprange1 + $i);
								$ip_rangeAll[] = $ip2long;
							}					
						} else {
							$ip_rangeAll[] = $ipsstrarrayAll;
						}
					}
				}
			}
		}		
	
		if(!empty($ip_rangeAll)){
			foreach($ip_rangeAll as $ips){
				$preg = '#^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}' . '(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$#';			
				if(preg_match($preg, $ips)) {
					$ip_rangeallExist[] = $ips;
				}
			}
		}

		if(!empty($ip_rangeallExist)){
			$ip_rangeallExist_out1 = array_unique($ip_rangeallExist);
			foreach($ip_rangeallExist_out1 as $ipsone){
				$ipDetails			 = $admin->checkUserIPAddress($ipsone, $userid);		
				if(empty($ipDetails)){
					$ip_range_out[]	 = $ipsone;
				} else {
					$ip_rangeExist[] = $ipsone;
				}
			}
		}

		if(!empty($ip_range_out) && count($ip_range_out) > $ipAdderSetter){
			$ip_range			 = array_slice($ip_range_out,0,$ipAdderSetter);
		} else if(!empty($ip_range_out)) {
			$ip_range			 = array_unique($ip_range_out);
		}

		if(empty($ip_range) && empty($ip_rangeExist)) {
			echo '<div id="display_limit_message" class="succuss_message error txtcenter pB20"><b>Please enter a valid IP address.</b></div>';
			return true;
		} else if(empty($ip_range) && !empty($ip_rangeExist)) {
			echo '<div id="display_limit_message" class="succuss_message error txtcenter pB20"><b>IPs already exists.Please enter unique IPs.</b></div>';
			return true;
		} else {
			$addInstutionipAddr = $admin->addIPAddress($userid,$instution_id,$ip_range,$is_verified);
			$notice='';
			if(!empty($ip_rangeExist)){
			$notice ='<h3><b>Notice: </b>In the entered IPs,<font color="red">'.count($ip_rangeExist).'</font> IPs already exists.</h3> ';
			}
			echo '<div id="display_limit_message" class="succuss_message txtcenter pB20">'.$notice.'<h3 class="pT5">Thank you for submiting IPs.You will shortly get confirmation from adminstrator about verification.</h3></div>';
			return true;
		}
	} else {
		echo '<div id="display_limit_message" class="succuss_message txtcenter pB20"><h3>You had already added '.$totaluserIPDetail.' IPS and Only '.$toatl_ip_allowed.' IPs are allowed.For more please contact administrator</h3></div>';
		return true;	
	}
}
?>

<?php
if(isset($_GET['addIPRanges']) && $_GET['addIPRanges']!='') {

	$ip_range_out		=	$ip_rangeExist		=	$ip_rangeallExist	=	$ip_rangeAll	=   $ip_range	=	$ipsstrArray	=	array();
	$toatl_ip_allowed	=	$totaluserIPDetail  =	$ipAdderSetter = 0;
	$notice1			=	$notice2	= '';
	
	$toatl_ip_allowed   =   IP_RANGE;	
	$userid			    =	$_POST['user_id'];
	$ipsstr			    =   trim(str_replace(' ', '', $_POST['ips']));
	$instution_id	    =	$_POST['instution_id'];
	$is_verified	    =	$_POST['is_verified'];

	$userDetail		    =   $user->getUser($userid);

	$userIPDetail_res	=	$admin->selectUserIPAdressAll($userid);
	$totaluserIPDetail  =   $db->count_rows($userIPDetail_res);
	$userIPDetail		=   $db->getAll($userIPDetail_res);

	$ipAdderSetter		=	$toatl_ip_allowed - $totaluserIPDetail;

	if(isset($ipAdderSetter) && $ipAdderSetter != 0){	

		if(isset($ipsstr) && $ipsstr !=''){				
			$ipsstrArray = explode(',',$ipsstr);
			if(!empty($ipsstrArray)){
				foreach($ipsstrArray as $ipsstrarrayAll){						
					$ip_rangeallExist[] = $ipsstrarrayAll;						
				}
			}			
		}

		if(!empty($ip_rangeallExist)){
			$ip_rangeallExist_out1 = array_unique($ip_rangeallExist);
			foreach($ip_rangeallExist_out1 as $ipsone){
				$ipDetails			 = $admin->checkUserIPAddress($ipsone, $userid);		
				if(empty($ipDetails)){
					$ip_range_out[]	 = $ipsone;
				} else {
					$ip_rangeExist[] = $ipsone;
				}
			}
		}

		if(!empty($ip_range_out) && count($ip_range_out) > $ipAdderSetter){
			$ip_range			 = array_slice($ip_range_out,0,$ipAdderSetter);
		} else if(!empty($ip_range_out)) {
			$ip_range			 = array_unique($ip_range_out);
		}

		if(empty($ip_range) && empty($ip_rangeExist)) {
			echo '<div id="display_limit_message" class="succuss_message error txtcenter pB20"><b>Please enter a valid IP address.</b></div>';
			return true;
		} else if(empty($ip_range) && !empty($ip_rangeExist)) {
			echo '<div id="display_limit_message" class="succuss_message error txtcenter pB20"><b>IPs already exists.Please enter unique IPs.</b></div>';
			return true;
		} else {
			$addInstutionipAddr = $admin->addIPAddress($userid,$instution_id,$ip_range,$is_verified);		
			if(!empty($ip_rangeExist)){
				$notice1 ='<h3><b>Notice: </b>In the entered IPs,<font color="red">'.count($ip_rangeExist).'</font> IPs already exists.</h3> ';
			}
			if($is_verified ==0){
				$notice2 ='You will shortly get confirmation from adminstrator about verification.';
			}

			if(isset($mail_notification) && $mail_notification == '1' && isset($is_verified) && $is_verified == '0'){	
				$templateKey		=	11;
				//$receivermail		=	trim($userDetail['email']);
				$receivermail		=	trim(FROM_EMAIL);
				$receivename		=	ucwords(FROM_NAME);		
				$from_email		    =	trim(FROM_EMAIL);
				$from_name		    =	ucwords(FROM_NAME);
				//$receivename		=	ucwords($userDetail['name']." ".$userDetail['last_name']);		
				//$from_email		    =	trim($userDetail['email']);
				//$from_name		    =	ucwords($userDetail['name']." ".$userDetail['last_name']);	
				$userDefinedArray[] =   array('organisation' => $userDetail['organisation']);				
				
				$mailbody			=	getMailTemplate($templateKey, $receivename, $receivermail, $from_name, $from_email,$userDefinedArray);		
				
				$subject      ='IP Verfication Mail';	
				$send_mail    = mail_function($receivename,$receivermail,$from_name,$from_email,$mailbody,$subject, $attachments  = array(),$addcc=array());
			}

			echo '<div id="display_limit_message" class="succuss_message txtcenter pB20">'.$notice1.'<h3 class="pT5">Thank you for submiting IPs.'.$notice2.'</h3></div>';
			return true;
		}

	} else {
		echo '<div id="display_limit_message" class="succuss_message txtcenter pB20"><h3>You had already added '.$totaluserIPDetail.' IPS and Only '.$toatl_ip_allowed.' IPs are allowed.For more please contact administrator</h3></div>';
		return true;	
	}
}
?>