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

$user_plan_details	=	array();

$plansResult_str = '';

$admin = new admin();
$user_id		=	$_SESSION['user']['id'];

if(isset($_GET['action']) && $_GET['action']!=''){
	$user_id		=	$_SESSION['user']['id'];
	$action			=	$_GET['action'];
	$id				=	$_GET['id'];
	$plansResult	= $admin->activateusermembershipPlan($id,$action,$user_id);
	header('location: account.php');
	exit;
}

if((isset($_POST['filter_type']) && $_POST['filter_type']!='') || (isset($_SESSION['filter_type']) && $_SESSION['filter_type']!='')) {

	if(isset($_POST['filter_type']) && $_POST['filter_type']!='') {
		if(isset($_SESSION['filter_type'])) { unset($_SESSION['filter_type']);}
		$filter_type	=	trim($_POST['filter_type']);
		$_SESSION['filter_type']=trim($_POST['filter_type']);
	} else if(isset($_SESSION['filter_type']) && $_SESSION['filter_type']!='') {
		$filter_type	=	trim($_SESSION['filter_type']);
	}
	
	if(isset($filter_type) && $filter_type =='1'){
		$plansResult_id		=	$admin->selectValidPlanofUser($user_id,$filter_type);
		if(!empty($plansResult_id)){
			$plansResult_str	=	implode(',',$plansResult_id);			
		}
	}
	else if(isset($filter_type) && $filter_type =='0'){
		$plansResult_id		=	$admin->selectValidPlanofUser($user_id,$filter_type);
		if(!empty($plansResult_id)){
			$plansResult_str	=	implode(',',$plansResult_id);
		}
	} else if(isset($filter_type) && $filter_type =='all'){
		$plansResult_id		=	$admin->selectValidPlanofUser($user_id,$filter_type);
		if(!empty($plansResult_id)){
			$plansResult_str	=	implode(',',$plansResult_id);
		}
	}
} else {
	$filter_type = 'all';	
	$plansResult_id		    =	$admin->selectValidPlanofUser($user_id,$filter_type);
	
	if(!empty($plansResult_id)) {
		$plansResult_str	=	implode(',',$plansResult_id);
	}
}

if(isset($plansResult_str) && $plansResult_str !=''){
	$plansResult	=	$admin->selectAllsubscriptionPlansUserAll($plansResult_str);
	$total_plan		=	$dbDatabase->count_rows($plansResult);
	$plan_details	=	$dbDatabase->getAll($plansResult);
}

$total_plan_all_res	=	$admin->selectAllsubscriptionPlansUser($user_id);
$total_plan_all		=	$dbDatabase->count_rows($total_plan_all_res);
?>

<!-- container -->
<section id="container">
	 <div class="main-cell">
		<div id="container-1">

			<div class="wdthpercent100 left">
				<div class="wdthpercent60 left">
					<h3>
						All User Plans <?php if(isset($total_plan))echo '( '.$total_plan.' )';?>
					</h3>
				</div>
				<div class="wdthpercent30 right">
					<?php if(isset($total_plan_all) && $total_plan_all > 0) { ?>
						<span class="right">
							<form name="plan_filter_type_form" action = "" method="post" id="plan_filter_type_form">						
								<label class="fontbld">Filter:</label> 
								<select id="filter_type" name="filter_type">
									<option <?php if(isset($_SESSION['filter_type']) && $_SESSION['filter_type'] == 'all') {?> selected="selected" <?php } ?> value="all">All Plans</option>
									<!-- <option <?php if(isset($_SESSION['filter_type']) && $_SESSION['filter_type'] == '1') {?> selected="selected" <?php } ?> value="1">Active/Inactive Plans</option> -->
									<option <?php if(isset($_SESSION['filter_type']) && $_SESSION['filter_type'] == '0') {?> selected="selected" <?php } ?> value="0">Closed Plans</option>
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
					<?php } ?>
					<!-- <span class="wdthpercent30 left"><input type="submit" value="Buy Plan" onclick="javascript: window.location=URL_SITE+'/plansubscriptions.php';"></span> -->				
				</div>
			<div class="clear"></div>					
			</div>
			<br class="clear" />

			<div class="pT10">
				<table class="data-table">
					<!-- <th>Menber ID</th> -->
					<th>Plan Name</th>				
					<th>Plan Validity (Days)</th>	
					<th>Purchase Amount (USD)</th>	
					<th>Purchase Date</th>
					<!-- <th>Active Status</th>
					<th>Action</th> -->	
				
					<?php if(isset($total_plan) && $total_plan > 0) {

						foreach($plan_details as $key => $plans){ ?>
							<tr>
								<!-- <td><?php echo $plans['id'];?></td> -->
								<td><a href="?id=<?php echo $plans['id'];?>"><?php echo $plans['plan_name'];?></a></td>
								<td><?php echo $plans['validity'];?></td>
								<td><?php echo $plans['purchase_amt'];?></td>
								<td><?php echo $time=date('d M Y',strtotime($plans['buy_on']));?></td>
								
								<!-- <td>
									<?php 
									$validity_plan_status = $admin->selectvalidplanStatus($plans['id']);
									if(isset($validity_plan_status) && $validity_plan_status == '1'){?>
										<h4 style="color:green;">Active Plan</h4>
									<?php } else if(isset($validity_plan_status) && $validity_plan_status == '2'){?>										
										<h4 style="color:#F88E11;">Inactive Plan</h4>
									<?php } else if(isset($validity_plan_status) && $validity_plan_status == '3'){?>
										<h4 style="color:#F06015;">Closed Plan</h4>	
									<?php } ?>
								</td>								

								<td>
									<h4>
										<?php
										if(isset($validity_plan_status) && $validity_plan_status == '1') { ?>
											<a href="javascript:;" onclick="javascript: alert('This plan is activated.');">N/A</a>
										<?php } ?>

										<?php if(isset($validity_plan_status) && $validity_plan_status == '2') { ?>
											<a onclick="javascript: return confirm('Do you really want to activate this plan.NOTE: Activating this plan will deactivate the current plan.Click OK to confirm and Cancel to return.');" href="?action=activate&id=<?php echo $plans['id'];?>">activate</a> |
										<?php } ?>
										<?php if(isset($plans['is_active']) && $plans['is_active']=='0'){ ?>
											<a onclick="javascript: return confirm('Do you really want to delete this plan.Please click OK to confirm and CANCEL to return.');" href="?action=delete&id=<?php echo $plans['id'];?>">Delete</a> 
										<?php } ?>
									 <h4>
								</td> -->
							</tr>
						<?php }	?>
						<!-- <br class="clear" />
						<p>NOTE: *N/A-Not Applicable.</p> -->

					<?php } else { ?>
						<tr>
							<td><h4>No plan Yet.</h4></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<!-- <td>&nbsp;</td>
							<td>&nbsp;</td> -->
						<tr>

					<?php }	?>
				</table>
			</div>
			

		</div>
	</div>		
</section>
<!-- /container -->