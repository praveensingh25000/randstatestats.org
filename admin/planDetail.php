<?php
/******************************************
* @Modified on July 9, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/actionHeader.php";

$usertypeid = $_REQUEST['user_type'];

$plansResult = $admin->getUserType($usertypeid);


if(empty($plansResult)){
	exit;
}

$user = new user();

$total_user_type = count($plansResult);

if(isset($_REQUEST['number_of_users'])) {
	$number_of_users = $_REQUEST['number_of_users'];
} else {
	$number_of_users = 1;								
}

$user_type = $purchaseuser_type = $plansResult['id'];

$databasesPurchased = array();

$totalPaid = $surcharge = $discount = $actualcost = 0;

if(isset($_GET['invoiceid']) && $_GET['invoiceid']!='') {
	
	$invoiceid			=	$_GET['invoiceid'];	
	$transactionDetail	=	$admin->selecttransactionDetail($invoiceid);

	$user_type = $transactionDetail['user_type'];

	if($purchaseuser_type == $user_type){
		$totalPaid = $transactionDetail['amount'];
		$surcharge = $transactionDetail['surcharge_amount'];
		$discount = $transactionDetail['discount_amount'];

		$actualcost = $totalPaid + $discount;
	}

	$databasesPurchasedArray = $admin->getDatabasesPurchasedWithPayment($invoiceid, $transactionDetail['user_id']);

	foreach($databasesPurchasedArray as $databasedet){
		$databasesPurchased[] = $databasedet['db_id'];
	}

	
}

$typeDetail = $admin->getUserType($user_type);

$userTypeInfo = $user->getUserTypeInfo($user_type);

?>

<h2>Purchase Subscription Plan</h2><br>
					
<div style="background:#F9F9F9;width:680px;">

						
	<!-- SHOW_PLAN_AJAX_DETAIL -->
	<div class="pL30" id="show_plan_ajax_detail" style="">
		<h3 class="pT20 pB10">Plan: <?php echo ucwords($typeDetail['user_type']);?> </h3>
		<hr><br/>
		<div class="planinner">		
			<div class="selectplan">										
				<h3 class="pB10">Choose a Plan Length</h3>	
				<input type="radio" name="plan_name" value="1"  checked="" onchange="javascript: getTotalFunctionAdmin();">One Year	
				<input type="radio" name="plan_name" value="2" onchange="javascript: getTotalFunctionAdmin();">Two Year
				<input type="radio" name="plan_name" value="3" onchange="javascript: getTotalFunctionAdmin();">Three Year	
			</div>

			<div class="clear pT15"></div>

	
			<!-- Plan Detail ------>												
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
								$checked = '';
								if(in_array($dbid, $databasesPurchased) && $purchaseuser_type == $user_type){
									$checked = "checked";
								}
								
						?>					
								
								<div class="left pT5 pB5">						
									<input onchange="javascript: getTotalFunctionAdmin();" id="checked_unchecked_<?php echo $databaseDetail['id']?>" value="<?php echo $dbid?>" type="checkbox" class="dbToSelect" name="db_name[]" <?=$checked?>>
									<span class="pL20 "><?php echo $databaseDetail['db_code'];?></span>
								</div>

								<div class="right pT5 pB5">
									<span class="db_price_<?php echo $databaseDetail['id']?>" <?php if($databaseDetail['db_code'] == 'US' && count($databasesPurchased)>1){ ?> style="display:none;"<?php } ?> >
									$
									<?php 
									if($databaseDetail['db_code'] == 'US'){
										echo $userTypeInfo['basepriceus'];
										
									} else {
										echo $userTypeInfo['baseprice'];
									}
									?>
									</span>


									<span <?php if($databaseDetail['db_code'] == 'US' && count($databasesPurchased)>1){ ?> style="display:block;" <?php } else { ?> style="display:none;"<?php } ?> class="db_free_<?php echo $databaseDetail['id']?>">Free</span>
									
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
							<input id="surcharge_amount_input" type="hidden" name="surcharge_amount" value="<?=$surcharge_amount?>">
						</div>
						<br class="clear" />
					</td>					
				</tr>

				<tr id="" style="">
					<th><span id="discounttxt">Discount</span></th>	
					<td>
						<div class="left">&nbsp;</div>
						<div class="right">
							$&nbsp;<input style="display:inline;" id="discount_amount_input" type="text" name="discount_amount" value="<?=$discount?>">
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
							$&nbsp;<input style="display:inline;" id="total_amount_input" type="text" name="total_amount" value="<?=$totalPaid?>">
							<span id="total_amount" style="display:none;"> 0 </span>&nbsp;
						</div>
						<br class="clear" />
					</td>					
				</tr>
			</table>
		
		<br class="clear pB20" />
	</div>
</div>

<input type="hidden" value="<?=$actualcost?>" id="actualcost" name="actualcost">

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#discount_amount_input').change(function(){
		var discount = jQuery('#discount_amount_input').val();
		var amount = jQuery('#actualcost').val();
		var total = parseFloat(amount) - parseFloat(discount);
		if(total > 0){
			jQuery('#total_amount_input').val(total);
		} else {
			jQuery('#total_amount_input').val('0');
		}
	});
});
</script>

			
										