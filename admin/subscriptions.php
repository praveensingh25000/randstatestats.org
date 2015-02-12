<?php
/******************************************
* @Modified on Dec 25, 2012
* @Package: Rand
* @Developer: Mamta sharma
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

$name = $description = $amount= $validity = '';

$active = 1;

if(isset($_GET['show']) && $_GET['show'] == 'deactive'){
	$active = 0;
} 

if(isset($_GET['type'])){
	$section_type	=	$_GET['type'];
} else {
	$section_type	=	'plan';
}

if(isset($_GET['type']) && ($_GET['type']!='single' && $_GET['type']!='multiple' && $_GET['type']!='plan')){
	header('location: subscription_others.php?type='.$_GET['type'].'&action='.$_GET['action'].'&id='.$_GET['id'].'');
}


if(isset($_GET['action'])){
	$action_type	=	$_GET['action'];
} else {
	$action_type	=	'viewall';
}

$plansResult_res = $admin->selectAlluserTypesActiveDeactive($active);
$total = $db->count_rows($plansResult_res);
$plans = $db->getAll($plansResult_res);

if(isset($_GET['id'])){

	$planid = trim(base64_decode($_GET['id']));

	//selecting one row of user Types
	$planDetail = $admin->selectuserTypes($planid);

	//selecting all plans of particular user Types
	$subscription_res   =	$admin->selectsubscriptionPlans();
	$totalsubscription	=	$db->count_rows($subscription_res);
	$subscriptionDetail =	$db->getAll($subscription_res);

	//selecting all user Types of Instution
	$selectallinstutiontype_res   =		$admin->selectAllInstutionType($planid);
	$totalallinstutiontype		  =		$db->count_rows($selectallinstutiontype_res);
	$allInstutionTypeDetail		  =		$db->getAll($selectallinstutiontype_res);
}	

if(isset($_GET['id']) && isset($_GET['planid']) && isset($action_type) && ($action_type=='view' || $action_type=='edit')) {
	$planid			=	trim(base64_decode($_GET['id']));
	$subscriptionid =	trim(base64_decode($_GET['planid']));

	//selecting one row of user Types
	$planDetail = $admin->selectuserTypes($planid);

	//selecting particular subscription Plan
	$particularSubscriptionDetail   =		$admin->SelectparticularSubscriptionDetail($subscriptionid);

	//selecting all user Types of Instution
	$selectallinstutiontype_res   =		$admin->selectAllInstutionType($planid);
	$totalallinstutiontype		  =		$db->count_rows($selectallinstutiontype_res);
	$allInstutionTypeDetail		  =		$db->getAll($selectallinstutiontype_res);
}	

if(isset($_POST['actionperform'])){

	$action = strtolower($_POST['actionperform']);
	$ids = implode(',', $_POST['ids']);
	
	switch($action){
		case 'active':
				$return = $admin->bulkActiveDeactivePlans($ids, '1');
				break;
		case 'in-active':
				$return = $admin->bulkActiveDeactivePlans($ids, '0');
				break;
		case 'delete':
				//$return = $admin->deleteDatabases($ids);
				break;
		default:
	}
	header('location: subscriptions.php?type=plan');
	exit;
}
?>

<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">

		<aside class="containerRadmin">
			<?php include_once $basedir."/include/adminLeft.php"; ?>
		</aside>

		<?php if(isset($section_type) && $section_type=='plan') {?>
			
			<!-- VIEW ALL SUBSCRIPTIONS PLAN  ---------------------------------->			
			<div class="containerLadmin">		

				<h3>All Subscription Plans <span class="right"><a href="javascript:window.history.go(-1)">Back</a></span></h3><br>

				<p>Show: <a <?php if(!isset($_GET['show'])){ ?> class="active" <?php } ?> href="?type=plan">Active</a>&nbsp;&nbsp;<a <?php if(isset($_GET['show']) && $_GET['show'] == 'deactive'){ ?> class="active" <?php } ?> href="?type=plan&show=deactive">Inactive</a></p><br>

				<?php if(isset($plans) && count($plans) > 0){ ?>				

					<form id="viewallplanfrmadmin" name="viewallplanfrmadmin" method="post">
						<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
							<tbody>
								<tr>
									<!-- <th bgcolor="#eeeeee">Subscription ID</th> -->
									<th bgcolor="#eeeeee"><input type="checkbox" id="check_all_plans" /></th>	
									<th bgcolor="#eeeeee">User Type </th>
									<th bgcolor="#eeeeee"> Action </th>
								</tr>

								<script type="text/javascript">
								jQuery(document).ready(function(){
									jQuery('#check_all_plans').click(function () {
										jQuery('.ids').attr('checked', this.checked);
									});
								});	
								</script>

								<?php foreach($plans as $key => $planDetail){?>
									<tr>
										<td align="middle"><input type="checkbox" class="ids" name="ids[]" value="<?php echo $planDetail['id']; ?>"/></td>
										<!-- <td align="left"><?php echo $planDetail['id']; ?></td> -->
										<td align="left">
											<a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']);?>&action=viewall&id=<?php echo base64_encode($planDetail['id']);?>"><?php echo $planDetail['user_type']; ?></a>
										</td>
										<td align="center">
											<a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']);?>&action=viewall&id=<?php echo base64_encode($planDetail['id']);?>">View Plans</a> | 
											<a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']); ?>&action=add&id=<?php echo base64_encode($planDetail['id']);?>">Add Plans</a>
										</td>
									</tr>
								<?php } ?>	

									<tr>
										<td colspan="5"><input type="submit" name="actionperform" value="Active" onclick="javascript: return checkAll('active');"/>&nbsp;<input type="submit" name="actionperform" value="In-Active" onclick="javascript: return checkAll('deactive');"/><!-- &nbsp;<input type="submit" name="actionperform" value="Delete" onclick="javascript: return checkAll('delete');"/> -->
										<script type="text/javascript">

										jQuery(document).ready(function(){
											jQuery('#check_all_plans').click(function () {
												jQuery('.ids').attr('checked', this.checked);
											});
										});							

										function checkAll(action){								
											var atLeastOneIsChecked = $('input[name="ids[]"]:checked').length > 0;
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
										</script>
									</td>
										
								</tr>
							</tbody>
						</table>
					</form>
				<?php } else {					
					echo "<h4>No Plan Type Yet.</h4>";				
				} ?>
			</div>			
			<!-- /VIEW ALL SUBSCRIPTIONS PLAN  ------------------------------->
		<?php } ?>

		<?php if(isset($section_type) && 
			($section_type=='single' || $section_type=='multiple' || $section_type=='institution') && ($action_type=='viewall')) {
			
			$selectalltypeofplans_res   =		$admin->selectallsubscriptionsPlans($section_type);
			$totalselectalltypeofplans  =		$db->count_rows($selectalltypeofplans_res);
			$alltypeofplansDetail		=		$db->getAll($selectalltypeofplans_res);				
			?>
			<!-- View SUBSCRIPTIONS PLAN  --->
			<div class="containerLadmin">
				<div style="clear: both; padding: 15px 0;">
					<h3><a href="subscriptions.php?type=plan">All Subscription Plans</a><span class="font14"> >> <a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']);?>&action=viewall&id=<?php echo base64_encode($planDetail['id']);?>"><?php echo ucwords($section_type);?></a> >> All Plan List	
					<a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']); ?>&action=add&id=<?php echo base64_encode($planDetail['id']);?>" style='float:right;'>Add Plans</a></span></h3>
					<fieldset class="pT15">
						<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
							<tbody>
								<tr>
									<!-- <th bgcolor="#eeeeee">	Plan ID		</th> -->
									<th bgcolor="#eeeeee">	Plan Name	</th>						
									<?php if($section_type=='multiple'){?>
									<th bgcolor="#eeeeee">	Number of User (more than) </th>
									<th bgcolor="#eeeeee">	Discounts (%) </th>
									<?php } ?>
									<?php if($section_type=='institution'){?>
									<th bgcolor="#eeeeee">	Institution Type </th>
									<th bgcolor="#eeeeee">	Number of User (more than) </th>
									<th bgcolor="#eeeeee">	Discounts (%)</th>
									<?php } ?>
									<th bgcolor="#eeeeee">	validity (Days)	</th>
									<th bgcolor="#eeeeee">	Action		</th>
								</tr>

								<?php if(!empty($alltypeofplansDetail)){							
									foreach($alltypeofplansDetail as $key => $allplanDetail){?>
									<tr>									
										<!-- <td align="left"><?php echo $allplanDetail['id']; ?></td> -->
										<td align="left">
											<a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']);?>&action=view&id=<?php echo base64_encode($planDetail['id']);?>&planid=<?php echo base64_encode($allplanDetail['id']);?>"><?php echo $allplanDetail['plan_name']; ?></a>
										</td>
										
										<?php if($section_type=='multiple'){?>					
										<td align="left"><?php echo $allplanDetail['number_of_users']; ?></td>
										<td align="left"><?php echo $allplanDetail['discounts']; ?></td>
										<?php } ?>		
										<?php if($section_type=='institution'){
										//selecting one row of user Types
										$institution_type = $admin->selectuserTypes($allplanDetail['institution_type_id']);
										?>					
										<td align="left"><?php echo $institution_type['user_type']; ?></td>
										<td align="left"><?php echo $allplanDetail['number_of_users']; ?></td>
										<td align="left"><?php echo $allplanDetail['discounts']; ?></td>
										<?php } ?>		

										<td align="left"><?php echo $allplanDetail['validity']; ?></td>
										<td align="center">
											<a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']);?>&action=view&id=<?php echo base64_encode($planDetail['id']);?>&planid=<?php echo base64_encode($allplanDetail['id']);?>">View</a> | 
											<a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']);?>&action=edit&id=<?php echo base64_encode($planDetail['id']);?>&planid=<?php echo base64_encode($allplanDetail['id']);?>">Edit</a> | 
											<a onclick="return deleteSubscriptionPlans(<?php echo $planDetail['id'];?>,<?php echo $allplanDetail['id']; ?>,'<?php echo $section_type;?>');" href="javascript:;">Delete</a>
											<span id="loader_<?php echo $allplanDetail['id'];?>" class="loading" style="display:none;"></span>
										</td>
									</tr>
									<?php } 
									}else{
										echo '<tr><td align="left"><h4>There is no '.$section_type.' plan yet.</h4></td></tr>';
									}?>																
							</tbody>
						</table>						
					</fieldset>
				</div>
			</div>
			<!-- View SUBSCRIPTIONS PLAN  --->
		<?php }?>

		<?php if(isset($section_type) && isset($action_type) && ($section_type=='single' || $section_type=='multiple' || $section_type=='institution') && ($action_type=='add' || $action_type=='edit' || $action_type=='view')) {?>
			<!-- ADD All SUBSCRIPTIONS PLAN  ---------------------------------------->
			<div class="containerLadmin">
				<div style="clear: both; padding: 15px 0;">
					<h3>
						<a href="subscriptions.php?type=plan">All Subscription Plans</a><span class="font14"> >> <a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']);?>&action=viewall&id=<?php echo base64_encode($planDetail['id']);?>"><?php echo ucwords($section_type);?></a> >> 
							<?php if($action_type=='add') {?>
								Add Plan Detail	
							<?php } else if($action_type=='view'){?> 
								<a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']);?>&action=view&id=<?php echo base64_encode($planDetail['id']);?>&planid=<?php echo $_GET['planid'];?>"><?php if(!empty($particularSubscriptionDetail)) { echo ucwords($particularSubscriptionDetail['plan_name']);}?> </a> >> View Plan Detail <a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']);?>&action=edit&id=<?php echo base64_encode($planDetail['id']);?>&planid=<?php echo $_GET['planid'];?>" style='float:right;'>Edit Plan Detail</a>
							<?php } else if($action_type=='edit'){?>
								<a href="subscriptions.php?type=<?php echo strtolower($planDetail['user_type']);?>&action=view&id=<?php echo base64_encode($planDetail['id']);?>&planid=<?php echo $_GET['planid'];?>"><?php if(!empty($particularSubscriptionDetail)) { echo ucwords($particularSubscriptionDetail['plan_name']);}?> </a> >> Edit Plan Detail  
							<?php } ?>
						</span>
					</h3>
					
					<fieldset class="pT15">			
						<form <?php if($action_type=='add'){?> name="frmaddall_subscription_plan" id="frmaddall_subscription_plan" <?php } else { ?> id="frmeditall_subscription_plan" name="frmeditall_subscription_plan" <?php } ?>  method="post" action="subscriptionsAction.php">
							
							<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Plan Name<em>*</em></legend>
							<div style="padding: 10px 0;">
								  <?php if($action_type=='add'){ ?>
								  <input id="plan_name" type="text" placeholder="enter plan name" name="plan_name" class="required" value="" />
								  <input type="hidden" id="plan_type" name="plan_type" value="<?php echo $section_type; ?>">
								  <?php } else if($action_type=='edit'){?>
								  <input id="plan_name" type="text" placeholder="enter plan name" name="plan_name" class="required" value="<?php if(!empty($particularSubscriptionDetail)) { echo $particularSubscriptionDetail['plan_name'];}?>" />
								  <input type="hidden" id="curent_plan_name" name="curent_plan_name" value="<?php if(!empty($particularSubscriptionDetail)) { echo $particularSubscriptionDetail['plan_name'];}?>">
								  <input type="hidden" id="plan_type" name="plan_type" value="<?php echo $section_type; ?>">
								  <?php } else if($action_type=='view'){ if(!empty($particularSubscriptionDetail)) echo $particularSubscriptionDetail['plan_name'];} ?>						  
							</div>							
							
							<!-- SINGLE SUBSCRIPTIONS FIELDS-->
							<?php if(isset($section_type) && $section_type=='single') {?>

							<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Validity<em>*</em></legend>	
							<div style="padding: 10px 0;">
								<?php if($action_type=='add'){ ?>
								<input type="text" onchange="checkPlanValidity('<?php echo $section_type;?>','validity_<?php echo $section_type;?>');" id="validity_<?php echo $section_type;?>" placeholder="enter validity in days" name="validity" class="digits required" value="" />&nbsp;Days
								<?php } else if($action_type=='edit'){?>
								<input type="text" onchange="checkPlanValidity('<?php echo $section_type;?>','validity_<?php echo $section_type;?>');" id="validity_<?php echo $section_type;?>" placeholder="enter validity in days" name="validity" class="digits required" value="<?php if(!empty($particularSubscriptionDetail)) { echo $particularSubscriptionDetail['validity'];}?>" />&nbsp;Days
								<?php } else if($action_type=='view'){ if(!empty($particularSubscriptionDetail)) echo $particularSubscriptionDetail['validity'];?>&nbsp;Days
								<?php } ?>
							</div>

							<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Databases<em>*</em></legend>
							<div style="padding: 10px 0;">
								<?php
								$databasesResult_res = $admin->showAllDatabasesDetail();
								$total = $db->count_rows($databasesResult_res);
								$databases = $db->getAll($databasesResult_res);

								if(!empty($particularSubscriptionDetail)) {

									$subscriptionPlansValueDetail_out=explode('/',$particularSubscriptionDetail['db_amount']);
									foreach($subscriptionPlansValueDetail_out as $key => $scriptionPlans) { 
										$array[]=explode('-',$scriptionPlans);
									}			
									foreach($array as $key => $value1) { 
										if(count($value1)>1){
										$new_array[$value1['0']] = $value1[1];			
										}
									}
								}
								
								if(!empty($databases)){ 
									$databases_slice=array_slice($databases,0,4);
									foreach($databases_slice as $key => $databaseDetail){?>
										<p class="pT5 pB5 pL20">
											<span style="background: #EAF6B0;"><?php echo ucwords($databaseDetail['database_label']);?></span>&nbsp;
											<?php if($action_type=='add'){ ?>
											<input placeholder="enter amount in USD" type="text" name="<?php echo $databaseDetail['id']; ?>" class="wdth10 digits required" value="" />
											<?php } else if($action_type=='edit'){?>
											<input placeholder="enter amount in USD" type="text" name="<?php echo $databaseDetail['id']; ?>" class="wdth10 digits required" value="<?php if(!empty($new_array)) {
											foreach($new_array as $key => $dbvalues) {
											if($key == $databaseDetail['id'])
											echo $db_values= $dbvalues;
											}}?>" />
											<?php } else if($action_type=='view'){?> <span class="fontbld right pR30"> <?php if(!empty($new_array)) {
											foreach($new_array as $key => $dbvalues) {
											if($key == $databaseDetail['id'])
											echo $db_values= $dbvalues;
											}}?>&nbsp;USD</span><?php } ?>
										</p>
									<?php } ?>	
								<?php } ?>							
							</div>
							<?php } ?>	
							<!-- /SINGLE SUBSCRIPTIONS FIELDS-->

							<!-- MULTIPLE SUBSCRIPTIONS FIELDS-->
							<?php if(isset($section_type) && $section_type=='multiple') {?>

							<legend style="background: #cccccc; font-size: 14px; padding: 5px;"> Enter number of user</legend>
							<div style="padding: 10px 0;">
								more than >	<?php if($action_type=='add'){ ?>
								<input type="text" onchange="checknumberofusersPlanAvailability('<?php echo $section_type;?>','number_of_users_<?php echo $section_type;?>');" id="number_of_users_<?php echo $section_type;?>" placeholder="enter number of users" name="number_of_users" class="digits required" value="" />
								<?php } else if($action_type=='edit'){?>
								<input type="text" onchange="checknumberofusersPlanAvailability('<?php echo $section_type;?>','number_of_users_<?php echo $section_type;?>');" id="number_of_users_<?php echo $section_type;?>" placeholder="enter number of users" name="number_of_users" class="digits required" value="<?php if(!empty($particularSubscriptionDetail)) { echo $particularSubscriptionDetail['number_of_users'];}?>" />
								<?php } else if($action_type=='view'){ if(!empty($particularSubscriptionDetail)) echo $particularSubscriptionDetail['number_of_users'];} ?>
							</div>							
							
							<?php if(!empty($subscriptionDetail)){?>
							<!-- BASE PLAN -->
							<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Select Base Plan</legend>
							<div class="pT10 pB10">
								<select name="base_plan" class="required" <?php if($action_type=='view'){?> disabled="true"<?php } ?>>
									<option onclick="javascript: selectDatabaseFunction(0,'<?php echo $section_type;?>');" id="select_base_plan_0" value="" <?php if($action_type=='add'){ echo 'selected="selected"';}?>>Select Base Plan</option>
									<?php foreach($subscriptionDetail as $basePlans){?>
									<?php if($action_type=='add'){ ?>
									<option onclick="javascript: selectDatabaseFunction(<?php echo $basePlans['id'];?>,'<?php echo $section_type;?>');" id="select_base_plan_<?php echo $basePlans['id'];?>" value="<?php echo $basePlans['id'];?>"><?php echo ucwords($basePlans['plan_name']);?></option>
									<?php } else if($action_type=='edit') {?>
									<option <?php if(!empty($particularSubscriptionDetail) && $particularSubscriptionDetail['plan_type_id'] == $basePlans['id']) { echo 'selected="selected"';}?> onclick="javascript: selectDatabaseFunction(<?php echo $basePlans['id'];?>,'<?php echo $section_type;?>');" id="select_base_plan_<?php echo $basePlans['id'];?>" value="<?php echo $basePlans['id'];?>"><?php echo ucwords($basePlans['plan_name']);?></option>
									<?php } else if($action_type=='view'){ ?>
									<option <?php if(!empty($particularSubscriptionDetail) && $particularSubscriptionDetail['plan_type_id'] == $basePlans['id']) { echo 'selected="selected"';}?> onclick="javascript: selectDatabaseFunction(<?php echo $basePlans['id'];?>,'<?php echo $section_type;?>');" id="select_base_plan_<?php echo $basePlans['id'];?>" value="<?php echo $basePlans['id'];?>"><?php echo ucwords($basePlans['plan_name']);?></option>
									<?php } ?>
									<?php } ?>
								</select>
								<span id="loader_<?php echo $basePlans['id'];?>" class="right loading" style="display:none;"></span>
							</div>							
							<!-- /BASE PLAN -->							
							<?php } ?>		

							<?php } ?>							
							<!-- /MULTIPLE SUBSCRIPTIONS FIELDS-->						
							
							<!-- INSTITUTION SUBSCRIPTIONS FIELDS-->
							<?php if(isset($section_type) && $section_type=='institution') {?>
							
							<legend style="background: #cccccc; font-size: 14px; padding: 5px;"> Enter number of user</legend>
							<div style="padding: 10px 0;">
								more than >	<?php if($action_type=='add'){ ?>
								<input type="text" onchange="checknumberofusersPlanAvailability('<?php echo $section_type;?>','number_of_users_<?php echo $section_type;?>');" id="number_of_users_<?php echo $section_type;?>" placeholder="enter number of users" name="number_of_users" class="digits required" value="" />
								<?php } else if($action_type=='edit'){?>
								<input type="text" onchange="checknumberofusersPlanAvailability('<?php echo $section_type;?>','number_of_users_<?php echo $section_type;?>');" id="number_of_users_<?php echo $section_type;?>" placeholder="enter number of users" name="number_of_users" class="digits required" value="<?php if(!empty($particularSubscriptionDetail)) { echo $particularSubscriptionDetail['number_of_users'];}?>" />
								<?php } else if($action_type=='view'){ if(!empty($particularSubscriptionDetail)) echo $particularSubscriptionDetail['number_of_users'];} ?>
							</div>

							<?php if(!empty($allInstutionTypeDetail)){?>
							<!-- ALL INSTUTION TYPE DETAIL -->
							<legend style="background: #cccccc; font-size: 14px; padding: 5px;"> Instution Type </legend>
							<div class="pT10 pB10">
								<select name="institution_type" class="required" <?php if($action_type=='view'){?> disabled="true"<?php } ?>>
									<option value="" <?php if($action_type=='add'){ echo 'selected="selected"';}?>>Select Instution Type</option>
									<?php foreach($allInstutionTypeDetail as $InstutionTypeDetail){?>
										<?php if($action_type=='add'){ ?>
										<option value="<?php echo $InstutionTypeDetail['id'];?>"><?php echo ucwords($InstutionTypeDetail['user_type']);?></option>
										<?php } else if($action_type=='edit') {?>
										<option <?php if(!empty($particularSubscriptionDetail) && $particularSubscriptionDetail['institution_type_id'] == $InstutionTypeDetail['id']) { echo 'selected="selected"';}?> value="<?php echo $InstutionTypeDetail['id'];?>"><?php echo ucwords($InstutionTypeDetail['user_type']);?></option>
										<?php } else if($action_type=='view'){ ?>
										<option <?php if(!empty($particularSubscriptionDetail) && $particularSubscriptionDetail['institution_type_id'] == $InstutionTypeDetail['id']) { echo 'selected="selected"';}?> value="<?php echo $InstutionTypeDetail['id'];?>"><?php echo ucwords($InstutionTypeDetail['user_type']);?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>							
							<!-- /ALL INSTUTION TYPE DETAIL -->
							<?php } ?>
							
							<?php if(!empty($subscriptionDetail)){?>
							<!-- BASE PLAN -->
							<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Select Base Plan</legend>
							<div class="pT10 pB10">
								<select name="base_plan" class="required" <?php if($action_type=='view'){?> disabled="true"<?php } ?>>
									<option onclick="javascript: selectDatabaseFunction(0,'<?php echo $section_type;?>');" id="select_base_plan_0" value="" <?php if($action_type=='add'){ echo 'selected="selected"';}?>>Select Base Plan</option>
									<?php foreach($subscriptionDetail as $basePlans){?>
									<?php if($action_type=='add'){ ?>
									<option onclick="javascript: selectDatabaseFunction(<?php echo $basePlans['id'];?>,'<?php echo $section_type;?>');" id="select_base_plan_<?php echo $basePlans['id'];?>" value="<?php echo $basePlans['id'];?>"><?php echo ucwords($basePlans['plan_name']);?></option>
									<?php } else if($action_type=='edit') {?>
									<option <?php if(!empty($particularSubscriptionDetail) && $particularSubscriptionDetail['plan_type_id'] == $basePlans['id']) { echo 'selected="selected"';}?> onclick="javascript: selectDatabaseFunction(<?php echo $basePlans['id'];?>,'<?php echo $section_type;?>');" id="select_base_plan_<?php echo $basePlans['id'];?>" value="<?php echo $basePlans['id'];?>"><?php echo ucwords($basePlans['plan_name']);?></option>
									<?php } else if($action_type=='view'){ ?>
									<option <?php if(!empty($particularSubscriptionDetail) && $particularSubscriptionDetail['plan_type_id'] == $basePlans['id']) { echo 'selected="selected"';}?> onclick="javascript: selectDatabaseFunction(<?php echo $basePlans['id'];?>,'<?php echo $section_type;?>');" id="select_base_plan_<?php echo $basePlans['id'];?>" value="<?php echo $basePlans['id'];?>"><?php echo ucwords($basePlans['plan_name']);?></option>
									<?php } ?>
									<?php } ?>
								</select>
								<span id="loader_<?php echo $basePlans['id'];?>" class="right loading" style="display:none;"></span>
							</div>							
							<!-- /BASE PLAN -->							
							<?php } ?>

							<?php } ?>
							<!-- INSTITUTION SUBSCRIPTIONS FIELDS-->
							
							<!-- SHOW SELECTED DATABASE VALUES AJAX REQUEST DIV-->
							<?php if(isset($section_type) && isset($action_type) && ($section_type=='multiple' || $section_type=='institution') && ($action_type=='edit' || $action_type=='view')) {?>

							<div id="show_selected_database_values" style="">
								
								<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Validity</legend>	
								<div style="padding: 10px 0;">
									<?php if($action_type=='edit'){?>
									<input type="text" onchange="checkPlanValidity('<?php echo $section_type;?>','validity_<?php echo $section_type;?>');" id="validity_<?php echo $section_type;?>" placeholder="enter validity in days" name="validity" class="digits required" value="<?php if(!empty($particularSubscriptionDetail)) { echo $particularSubscriptionDetail['validity'];}?>" />&nbsp;Days
									<?php } else if($action_type=='view'){ if(!empty($particularSubscriptionDetail)) echo $particularSubscriptionDetail['validity'];?>&nbsp;Days
									<?php } ?>
								</div>

								<legend style="background: #cccccc; font-size: 14px; padding: 5px;"> Discounts % </legend>
								<div style="padding: 10px 0;">
									<?php if($action_type=='add'){ ?>
									<input type="text" placeholder="enter discount amount" name="discounts" class="digits" value="" /><span style="color:blue;font-style:oblique;">press tab for discount.</span>	
									<?php } else if($action_type=='edit'){?>
									<input type="text" id="claculatediscountfunction_<?php echo $particularSubscriptionDetail['plan_type_id']?>" onchange="claculateDiscountFunction(<?php echo $particularSubscriptionDetail['plan_type_id']?>,'<?php echo $section_type;?>');" placeholder="enter discount amount" name="discounts" class="digits" value="<?php if(!empty($particularSubscriptionDetail)) { echo $particularSubscriptionDetail['discounts'];}?>" /><span style="color:blue;font-style:oblique;">press tab for discount.</span>	
									<?php } else if($action_type=='view'){ if(!empty($particularSubscriptionDetail)) echo $particularSubscriptionDetail['discounts'];} ?>						
								</div>

								<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Databases</legend>
								<div style="padding: 10px 0;">
									<?php
									$databasesResult_res = $admin->showAllDatabasesDetail();
									$total = $db->count_rows($databasesResult_res);
									$databases = $db->getAll($databasesResult_res);

									if(!empty($particularSubscriptionDetail)) {

										$subscriptionPlansValueDetail_out=explode('/',$particularSubscriptionDetail['db_amount']);
										foreach($subscriptionPlansValueDetail_out as $key => $scriptionPlans) { 
											$array[]=explode('-',$scriptionPlans);
										}			
										foreach($array as $key => $value1) { 
											if(count($value1)>1){
											$new_array[$value1['0']] = $value1[1];			
											}
										}
									}
									
									if(!empty($databases)){ 
										$databases_slice=array_slice($databases,0,4);
										foreach($databases_slice as $key => $databaseDetail){?>
											<p class="pT5 pB5 pL20">
												<span style="background: #EAF6B0;"><?php echo ucwords($databaseDetail['database_label']);?></span>&nbsp;
												<?php if($action_type=='edit'){?>
												<input placeholder="enter amount in USD" type="text" name="<?php echo $databaseDetail['id']; ?>" class="wdth10 digits required" value="<?php if(!empty($new_array)) {
												foreach($new_array as $key => $dbvalues) {
												if($key == $databaseDetail['id'])
												echo $db_values= $dbvalues;
												}}?>" />
												<?php } else if($action_type=='view'){?> <span class="fontbld right pR30"> <?php if(!empty($new_array)) {
												foreach($new_array as $key => $dbvalues) {
												if($key == $databaseDetail['id'])
												echo $db_values= $dbvalues;
												}}?>&nbsp;USD</span><?php } ?>
											</p>
										<?php } ?>	
									<?php } ?>							
								</div>
							</div>
							<?php } else {?>
							<div id="show_selected_database_values" style="display:none;"></div>
							<?php } ?>	
							<!-- /SHOW SELECTED DATABASE VALUES AJAX REQUEST DIV-->
							
							<div class="submit1 left pL5 pT10">
								<?php if($action_type=='add' || $action_type=='edit'){ ?>
								<label for="submit" class="pL15">									
									<input type="submit" value="Submit" name="<?php echo $section_type;?>" class="submitbtn">									
									<input type="hidden" name="submission_type" value="<?php if($action_type=='add') echo 'add_'.$section_type; else echo 'edit_'.$section_type; ?>">									
									<input type="hidden" name="plan_type" value="<?php echo $section_type;?>">
									<input type="hidden" value="<?php echo $planid ;?>" name="subscriptionid">
									<input type="hidden" value="<?php if(isset($subscriptionid)) echo $subscriptionid;?>" name="planid">									 
								</label>
								<label for="reset" class="pL15">
									<input type="reset" id="reset" class="submitbtn">
								</label>
								<?php } ?>
								<label for="back" class="pL15">
									<input onclick="javascript:window.history.go(-1)" type="button" value="Back" class="submitbtn">
								</label>	
							</div>
						</form>
					</fieldset>
				</div>
			</div>
			<!-- /ADD ALL SUBSCRIPTIONS PLAN  --------------------------------->
		<?php }	?>
		
	</div>		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>