<?php
/******************************************
* @Modified on Jan 25, 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

$admin = new admin();
$plansResult = $admin->selectAlluserTypes();
$total_user_type = $dbDatabase->count_rows($plansResult);
$user_type_details = $dbDatabase->getAll($plansResult);

//echo "<pre>";print_r($user_type_details);echo "</pre>";
?>
<script type="text/javascript">
$(function() {
	$('#container-1 ul').tabs();
});
</script>
<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">
		<!-- left side -->
		<div id="container-1">

			<?php if(!empty($user_type_details))
			{?>
				<h2> Buy Subscription Plan </h2>
						<br class="clear" />
						<form method="POST" action="<?php URL_SITE?>paypal/makepayment.php" enctype="multipart/form-data" id="make_payment_form" name="make_payment_form">

							<div class="clear pT5"></div>
							<div class="plantab">
								<ul class="ui-tabs-nav">							
									<?php 
									foreach($user_type_details as $key =>$plansResult) {?>
										<li class="ui-tabs-selected"><a href="javascript:;" onclick="selectAllPlansforSubscription('<?php echo $plansResult['id'];?>','<?php echo $plansResult['user_type'];?>')"><span class="pL5"> <?php echo $plansResult['user_type'];?> </span></a></li>
										<?php if(!isset($planid))
											{
													$planid=$plansResult['id'];
													$usertype=$plansResult['user_type'];
											}
											?>
									<?php } ?>
								</ul>
							</div>
							
							<?php
							$user_plan_details	=	$allInstutionTypeDetail	=	$allinstutionplanDetails	=	array();
								$subscriptionid			=	$planid;	
								$user_type				=	strtolower($usertype);	
								$plan_res				=	$admin->selectsubscriptionsPlansOfUser($subscriptionid);
								$user_plan_details		=	$dbDatabase->getAll($plan_res);

								//selecting all user Types of Instution
								$selectallinstutiontype_res   =		$admin->selectAllInstutionType($subscriptionid);
								$totalallinstutiontype		  =		$dbDatabase->count_rows($selectallinstutiontype_res);
								$allInstutionTypeDetail		  =		$dbDatabase->getAll($selectallinstutiontype_res);

								//selecting all plans of Instutions
								if(isset($_POST['institution_type_id']) && $_POST['institution_type_id'] !=''){ 
									$institution_type_id 	=	$_POST['institution_type_id'];
									$selectallinstutiontypeplan_res   =		$admin->selectinstitutionPlans($subscriptionid,$institution_type_id);
									$totalallinstutionplans		  =		$dbDatabase->count_rows($selectallinstutiontypeplan_res);
									$allinstutionplanDetails	  =		$dbDatabase->getAll($selectallinstutiontypeplan_res);
								}	
							?>
							<div class="ui-tabs-panel">
							<div id='default'>
									<div class="planinner">
									<?php if(isset($user_type)) { ?>
									
										<h3>Select&nbsp;<?php echo ucwords($user_type);?>&nbsp;Plan </h3>
										<div class='clear pT10'></div>						
										
										<?php if(!empty($user_plan_details)) {?>			
										    <ul class="ui-tabs-nav">
											<?php 
											foreach($user_plan_details as $key =>$plansResult) {?>	
											
											<li class="ui-tabs-selected selectplan">
												<input onclick="selectdetailPlansforSubscription('<?php echo $plansResult['id'];?>','<?php echo $user_type;?>')" type="radio" value="<?php echo $plansResult['id'];?>" name="plan_name" id="defaultplan_<?php echo $plansResult['id']?>">											
												<?php echo $plansResult['plan_name'];?>	
											</li>
											<?php } ?>
											</ul>
										<div class="clear pT15"></div>

										
										
										<?php } else { ?>
										<h4> No Plan available. </h4>
										<?php } ?>

									<?php } ?>
									<script type="text/javascript">									
									function selectAllPlansforSubscription(subscriptionid,user_type){

										if(subscriptionid != ''){
											jQuery(".main-cell").prepend('<div style="color:green;" class="bodyLoader blockUI blockMsg blockPage"><h1>Loading....Please wait.</h1></div>');		
											jQuery.ajax({
												type: "POST",
												data: "subscriptionid="+subscriptionid+"&user_type="+user_type,
												url : URL_SITE+"/frontAction.php?selectplan=1",						
												success: function(msg){				
													jQuery(".bodyLoader").remove();
													jQuery("#default").hide();
													jQuery("#show_plan_ajax_detail").html(msg).show();				
												}							
											});
											return false;
										}else{
											return false;
										}							
									}
								
							</script>

									<?php if(isset($user_type) && $user_type=='institution') { ?>

										  <?php if(!empty($allInstutionTypeDetail) && !isset($_POST['institution_type_id'])) {?>
												<!-- ALL INSTUTION TYPE DETAIL -->
												<legend style="background: #cccccc; font-size: 14px; padding: 5px;"> Select Instution Type </legend>
												<div style="background: #EFFEB9;" class="pT10 pB10">
													<select name="institution_type">
														<option onclick="selectinstitutionPlansforSubscription('<?php echo $subscriptionid	;?>','0','<?php echo $user_type;?>')" value="0">Select Instution Type</option>
														<?php foreach($allInstutionTypeDetail as $InstutionTypeDetail){?>		<option onclick="selectinstitutionPlansforSubscription('<?php echo $subscriptionid	;?>','<?php echo $InstutionTypeDetail['id'];?>','<?php echo $user_type;?>')" value="<?php echo $InstutionTypeDetail['id'];?>"><?php echo ucwords($InstutionTypeDetail['user_type']);?></option>				
														<?php } ?>
													</select>
												</div>

												<!-- institution_type_plan -->
												<div id="institution_type_plan_ajax_detail" style="display:none;"></div>
												<!-- institution_type_plan -->	

												<!-- /ALL INSTUTION TYPE DETAIL -->
										  <?php } ?>

										  <?php if(isset($_POST['institution_type_id'])) { ?>
											
												<legend style="background: #cccccc; font-size: 14px; padding: 5px;"> Select&nbsp;<?php echo ucwords($user_type);?>&nbsp;Plan </legend>
												<div id="" class="clear pB5"></div>			
												
												<?php if(!empty($allinstutionplanDetails)) {?>
												
													<h4 style="background: #EFFEB9;padding: 5px;">
														<?php 
														foreach($allinstutionplanDetails as $key =>$plansResult) {?>					
															<input onclick="selectdetailPlansforSubscription('<?php echo $plansResult['id'];?>','<?php echo $user_type;?>')" type="radio" value="<?php echo $plansResult['id'];?>" name="plan_name"><span class=""> <?php echo $plansResult['plan_name'];?> </span>&nbsp;&nbsp;&nbsp;					
														<?php } ?>
													</h4>

													<!-- SHOW_PLAN_AJAX_DETAIL -->
													<div id="show_plan_ajax_detail1_particular" style="display:none;"></div>
													<!-- SHOW_PLAN_AJAX_DETAIL -->	
												
												<?php } else { ?>
													<h4 style="background: #EFFEB9;padding: 5px;"> No Plan available. </h4>
												<?php } ?>				
												
										  <?php } ?>

									<?php }
									$user_plan_details	=	$admin->SelectparticularSubscriptionDetail($planid);
									?>		
								</div>
							<form method="post" action="" id="select_payment_form_mode" name="select_payment_form_mode">
			
									<h3>Plan Detail<h3> 
									<br class="clear" />
									<table class="data-table">
									
									<?php if(!empty($user_plan_details)) {?>			
									
									
										<tr>
											<th>Plan Name</th>				
											<td><?php echo $user_plan_details['plan_name']; ?></td>					
										</tr>
										<tr>
											<th>Plan Validity</th>				
											<td><?php echo $user_plan_details['validity'];?>&nbsp;Days</td>					
										</tr>					
										<?php if(isset($user_type) && ($user_type=='multiple' || $user_type=='institution')) { ?>
										<tr>
											<th>Number of use</th>				
											<td><?php echo $user_plan_details['number_of_users'];?></td>					
										</tr>					
										<?php } ?>

										<?php if(isset($user_type) && $user_type=='institution') { ?>	
										<tr>
											<th>Instution Type</th>				
											<td><?php
											$institution_type_id=$user_plan_details['institution_type_id'];
											$institution_type=$admin->selectuserTypes($institution_type_id);
											if(!empty($institution_type)) { echo $institution_type['user_type'];} ?></td>					
										</tr>					
										<?php } ?>	
										<tr>
											<th>Discounts %</th>				
											<td><?php echo $user_plan_details['discounts']?></td>					
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
															<span class="left"><?php echo ucwords($databaseDetail['database_label']);?></span>
														<span class="right">
															<?php
															if(!empty($new_array)) {
																foreach($new_array as $key => $dbvalues) {
																	if($key == $databaseDetail['id'])
																	echo $db_values= $dbvalues;
																} 
															}?>&nbsp;USD
														</span>
														<br class="clear" />							
													<?php } ?>
												<?php } ?>
											</td>					
										</tr>
										<tr>
											<th>Total Amount :</th>				
											<td><?php if(!empty($new_array)) echo $db_values= array_sum($new_array);?>&nbsp;USD
													<input type="hidden" name="total_amount" value="<?php echo $db_values;?>">
													<input type="hidden" name="planid" value="<?php echo $user_plan_details['id'];?>">
											</td>					
										</tr>
										</table>
										<script type="text/javascript">									
										jQuery(document).ready(function(){
										jQuery("#select_payment_form_mode").submit(function(e){
										
											e.preventDefault();							
											
											jQuery(".main-cell").prepend('<div style="color:green;" class="bodyLoader blockUI blockMsg blockPage"><h1>Loading....Please wait.</h1></div>');
											
											jQuery.ajax({
												type: "POST",
												data: jQuery("#select_payment_form_mode").serialize(),
												url : URL_SITE+"/frontAction.php?paymentmode=1",
												
												success: function(msg){	
													jQuery(".bodyLoader").remove();
													jQuery("#proceed_to_payment_type").remove();
													jQuery("#show_selected_payment_form_mode").html(msg).show();	
												}							
											});					  
										});	
									});	
									
									</script>

									

									<div id="proceed_to_payment_type" class="submit1 txtcenter pT10">
										<label for="submit">
											<input type="submit" name="submit" value="Proceed To Payment Type" class="submitbtn" >
										</label>
										<label for="cancel pL10">					
											<input onclick="javascript: window.location=URL_SITE+'/index.php'" type="button" name="submit" value="Cancel" class="submitbtn" >
										</label>
									</div>

									<?php } else { ?>
									<h4 style="background: #EFFEB9;padding: 5px;"> Detail not available this time. </h4>
									<?php } ?>

										
							</form>	

							</div>
					</form>
				<br class="clear" />
				<!-- SHOW_PLAN_AJAX_DETAIL -->
				<div id="show_plan_ajax_detail" style="display:none;"></div>
				<!-- SHOW_PLAN_AJAX_DETAIL -->
				<br class="clear" />
				</div>
			</div>

			<?php } else { ?>
				<h3> No Paln added Yet </h3>
			<?php } ?>			
		</div>
		<!-- left side -->
	</div>
		
</section>
<!-- /container -->
