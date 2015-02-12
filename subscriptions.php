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

if(isset($_POST['submit'])){
	$_SESSION['BUY_PLAN'] = $_POST;	
	$user = new user();
	$plan = $_POST['plan'];
	$amount = $_POST['amount'];
}
?>

<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">
		<!-- left side -->
		<div class="planouter">

			<?php if(!empty($user_type_details)) {?>
				<div style="clear: both; padding: 15px 0;">
				<fieldset style="border: 1px solid #cccccc; padding: 10px;">
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Buy Subscription Plan</legend>
					<form method="POST" action="<?php URL_SITE?>paypal/makepayment.php" enctype="multipart/form-data" id="make_payment_form" name="make_payment_form">

						<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Select Subscription Plan Type</legend>
						<div id="" class="clear"></div>
						
						<h3 style="background: #EFFEB9;padding: 5px;">
							<?php 
							foreach($user_type_details as $key =>$plansResult) {?>
								<input onclick="selectAllPlansforSubscription('<?php echo $plansResult['id'];?>','<?php echo $plansResult['user_type'];?>')" type="radio" value="<?php echo $plansResult['id'];?>" name="plan"><span class="pL5"> <?php echo $plansResult['user_type'];?> </span>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php } ?>
						</h3>

						<script type="text/javascript">
						function selectAllPlansforSubscription(subscriptionid,user_type){

							if(subscriptionid != ''){
								jQuery(".containerL").prepend('<div style="color:green;" class="bodyLoader blockUI blockMsg blockPage"><h1>Loading....Please wait.</h1></div>');		
								jQuery.ajax({
									type: "POST",
									data: "subscriptionid="+subscriptionid+"&user_type="+user_type,
									url : URL_SITE+"/frontAction.php?selectplan=1",						
									success: function(msg){				
										jQuery(".bodyLoader").remove();
										jQuery("#show_plan_ajax_detail").html(msg).show();					
									}							
								});	
							}else{
								return false;
							}							
						}
						</script>

						<!-- <div class="submit1 submitbtn-div">
							<label for="submit" class="left">
							<input type="submit" name="submit" value="Buy Now" class="submitbtn" >
							</label>
						</div> -->

					</form>
				</fieldset>

				<br class="clear" />
				<!-- SHOW_PLAN_AJAX_DETAIL -->
				<div id="show_plan_ajax_detail" style="display:none;"></div>
				<!-- SHOW_PLAN_AJAX_DETAIL -->
				<br class="clear" />

			</div>

			<?php } else { ?>
				<h3> No Paln added Yet </h3>
			<?php } ?>			
		</div>
		<!-- left side -->
	</div>
		
</section>
<!-- /container -->