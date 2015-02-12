<?php
/******************************************
* @Modified on March 05, 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

checkSession(false);

$user_plan_details	=   array();

$plansResult_str = '';

$admin			= new admin();
$user_id		= $_SESSION['user']['id'];
$userDetail		= $user->getUser($user_id);
if($userDetail['user_type']=='6' && $userDetail['parent_user_id']!='0'){
	header('location:'.URL_SITE.'/index.php');
	exit;
}

$plansResult_res = $admin->selectAlluserTypesActiveDeactive($active=1);
$totalplan		 = $db->count_rows($plansResult_res);

if(isset($_GET['action']) && $_GET['action']!=''){

	$action			=	$_GET['action'];
	$id				=	$_GET['id'];
	$plansResult	= $admin->selectedactionPerform($id,$action);
	header('location: account.php');
	exit;
}


if((isset($_POST['filter_type']) && $_POST['filter_type']!='') || (isset($_SESSION['filter_type']) && $_SESSION['filter_type']!='')) {

	if(isset($_POST['filter_type']) && $_POST['filter_type']!='') {
		if(isset($_SESSION['filter_type'])) { unset($_SESSION['filter_type']);}
		$filter_type			=	trim($_POST['filter_type']);
		$_SESSION['filter_type']=   trim($_POST['filter_type']);
	} else if(isset($_SESSION['filter_type']) && $_SESSION['filter_type']!='') {
		$filter_type			=	trim($_SESSION['filter_type']);
	}
	
	if(isset($filter_type) && $filter_type =='all_db'){
		$content_details_array		=	$admin->selectValidPlanofUser($user_id,$filter_type);
	} else if(isset($filter_type) && $filter_type =='all_trans'){
		$content_details_array		=	$admin->selectValidPlanofUser($user_id,$filter_type);
	}
} else {
	$filter_type				=   'all_db';	
	$content_details_array		=	$admin->selectValidPlanofUser($user_id,$filter_type);
}

$content_details_obj = new PS_PaginationArray($content_details_array,10,5,''); 
$content_details     = $content_details_obj->paginate();
$total_result        = count($content_details);

$validity = $admin->Validity($user_id);
?>
<!-- container -->
<section id="container">
	 <div class="main-cell">
		<div id="container-1">

			<div class="wdthpercent100 left">
				<div class="wdthpercent50 left">
					<h3>
						<?php if(isset($filter_type) && $filter_type == 'all_db') {?> All Database Details <?php } ?><?php if(isset($filter_type) && $filter_type == 'all_trans') { ?> All Transaction Details <?php } ?><?php if(isset($total_result)) echo '( '.$total_result.' )';?>
					</h3>
				</div>
				<div class="wdthpercent50 right">

					<?php if(!empty($is_trial) & $is_trial['is_trial']=='1'){?>
						<span class="wdthpercent30 right">
					<?php } else {?>
						<span class="<?php if(isset($totalplan) && $totalplan <= 0 || isset($filter_type) && $filter_type == 'all_trans') { ?>right<?php } else { ?>wdthpercent30 right<?php } ?>">
					<?php } ?>				
					
						<form name="plan_filter_type_form" action = "" method="post" id="plan_filter_type_form">						
							<label class="fontbld">Filter:</label> 
							<select id="filter_type" name="filter_type">
								<option <?php if(isset($filter_type) && $filter_type == 'all_db') {?> selected="selected" <?php } ?> value="all_db">All Database</option>
								<option <?php if(isset($filter_type) && $filter_type == 'all_trans') {?> selected="selected" <?php } ?> value="all_trans">All Transaction</option>							
							</select>
							<script type="text/javascript">
								jQuery(document).ready(function(){
									jQuery('#filter_type').change(function(){
										loader_show();
										jQuery('#plan_filter_type_form').submit();			
									});
								});
							</script>							
						</form>
					</span>

					<?php if($_SESSION['user']['user_type'] != 5){
						if($_SESSION['user']['user_type'] == 6){
					?>
						<span class="wdthpercent30 left"><a href="multipleUsers.php"><h3>Multiple Users</h3></a></span>
					<?php
						} else {
					?>
						<span class="wdthpercent30 left"><a href="multipleUsers.php"><h3>Add Admin. Account(s)</h3></a></span>
					<?php
						}
					}
					?>

					<?php if(isset($filter_type) && $filter_type == 'all_db') { ?>

					<?php if(!empty($is_trial) & $is_trial['is_trial']=='0'){?>
					<span class="wdthpercent30 left"><input type="button" value="Change Account" onclick="javascript: window.location=URL_SITE+'/upgrade.php';"></span>
					<?php } 
					
					if($validity > 0){
					?>

					<span class="wdthpercent30 <?php if(!empty($is_trial) & $is_trial['is_trial']=='1'){?>right<?php } else { ?>left<?php } ?>"><input type="button" value="Add More Database" onclick="javascript: window.location=URL_SITE+'/accountUpgrade.php';"></span>
					<?php } }
					
					if($validity == 0){

					?>
						<span class="wdthpercent30 right"><input type="button" value="Renew Account" onclick="javascript: window.location=URL_SITE+'/renew.php';"></span>
					<?php

					}
					?>
					
				</div>
			<div class="clear pB10"></div>					
			</div>

			<!-- ALL DB DETAILS -->
			<?php if((isset($filter_type) && $filter_type =='all_db')) { ?>
				<div class="pT10">
					<table class="data-table">
						
						<th>Related Database</th>										
						<th>Plan Validity (Days)</th>	
						<th>Start Date</th>
						<th>End Date</th>
						<!-- <th>Action</th> -->	
					
						<?php if(isset($total_result) && $total_result > 0) {

							foreach($content_details as $key => $contents) {
								$databaseDetail	= $admin->selectDatabases($contents['db_id']);
								
							?>
								<tr>								
									<td>
										<h4><?php echo ucwords($databaseDetail['db_code']);?></h4>
									</td>								
									<td><h4><?php echo $contents['validity'];?></h4></td>
									<td>
										<h4><?php echo $time=date('d M Y',strtotime($contents['start_time']));?></h4>
									</td>
									<td>
										<h4><?php echo $time=date('d M Y',strtotime($contents['expire_time']));?></h4>
									</td>
								<!-- 	<td>
										<?php if(isset($total_result) && $total_result <= 3) { ?>
											<h4><a href="<?php echo URL_SITE;?>/planupgradation.php?id=<?php echo base64_encode($contents['id']);?>">Upgrade Plan</a></h4>
										<?php }	 else { ?>
											<h4>N/A</h4>
										<?php }	?>
									</td>		 -->						
								</tr>
							<?php }	?>										

								<tr>
																
									
									<td colspan="4">
										 <div class="txtcenter pagination">
										 <?php  echo $content_details_obj->renderFullNav();?>
										 </div>
									</td>
								
									
								</tr>			

						<?php } else { ?>
							<tr>
								<td colspan="4"><h4>No plan Yet.</h4></td>
								
							<tr>

						<?php }	?>
					</table>
				</div>

				<br class="clear" />

				<?php if(isset($total_result) && $total_result > 0) { ?>
					<!-- <div id="" class=""><h4>*N/A - Not Applicable.</h4></div> -->
				<?php } ?>

			<?php } ?>			
			<!-- ALL DB DETAILS -->
			
			<!-- ALL PLAN DETAILS -->
			<?php if((isset($filter_type) && $filter_type =='all_trans')) { ?>
			<div class="pT10">
				<table class="data-table">
					<th>Transaction ID</th>
					<th>Payment Type</th>				
					<th>Purchase Amount (USD)</th>	
					<th>Status</th>	
					<th>Purchase Date </th>	
					<!-- <th>Action</th>	 -->
				
					<?php if(isset($total_result) && $total_result > 0) {

						foreach($content_details as $key => $plans){
						
							?>
							<tr>
								<td><h4><?php echo $plans['paypal_transaction_id'];?></h4></td>
								<td><h4><?php echo $plans['payment_type'];?></h4></td>
				
								<td><h4><?php echo $plans['amount'];?></h4></td>
								
								<td>
									<h4><?php if($plans['pay_status'] == 0){ echo "Pending"; } else { echo "Paid"; }?></h4>
								</td>
								<td>
									<h4><?php echo $time=date('d M Y',strtotime($plans['buy_on']));?></h4>
								</td>	
								<!-- <td>
									<h4>
										<a onclick="javascript: return confirm('Do you really want to delete this Transaction.Please click OK to confirm and CANCEL to return.');" href="?action=delete&id=<?php echo $plans['id'];?>">Delete</a>
									</h4>
								</td> -->
							</tr>
						<?php }	?>

							<tr>
													
								<td colspan="5">
									<div class="txtcenter pagination">
										<?php  echo $content_details_obj->renderFullNav();?>
									</div>
								</td>
								
						
							</tr>
						
					<?php } else { ?>
						<tr>
							<td colspan="5"><h4>No Transaction Yet.</h4></td>
						
						
						<tr>

					<?php }	?>
				</table>
			</div>
			<br class="clear" />
			<?php } ?>
			<!-- ALL PLAN DETAILS -->
		</div>
	</div>		
</section>
<!-- /container -->