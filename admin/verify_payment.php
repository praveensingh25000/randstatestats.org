<?php
/******************************************
* @Created on April 22 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();
$user  = new user();

if(isset($_GET['action']) && isset($_GET['id']) && $_GET['id']!=''){

	$action	   = $_GET['action'];
	$ids	   = $_GET['id'];
	$status	   = '';

	$tablename ='institution_payment_detail';	

	if($action =='verify'){
		$_SESSION['msgsuccess']="Selected User payment detail has been verified.";
		$status=1;  
	} else if($action =='unverify'){
		$_SESSION['msgsuccess']="Selected User payment detail has been unverified.";
		$status=2;  
	} else if($action =='delete'){
		$_SESSION['msgsuccess']="Selected User payment detail has been deleted.";
		$status=1; 
	}

	$return = $admin->activedeactiveStatus($tablename, $ids, $action,$status);
	header('location: verify_payment.php');
	exit;
}

$paymenttypes_res	= $admin->getPaymentTypes($active=1);
$paymenttypes		= $db->getAll($paymenttypes_res);

$users_payment_vefication		=	$admin->select_all_users_payment_vefication();
$users_payment_vefication_obj	=	new PS_PaginationArray($users_payment_vefication,5,5);
$users_payment_vefication_new	=	$users_payment_vefication_obj->paginate();
$total_payment_vefication		=	count($users_payment_vefication_new);
?>

<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">

		<aside class="containerRadmin">
			<?php include_once $basedir."/include/adminLeft.php"; ?>
		</aside>

		<!-- left side -->
		<div class="containerLadmin">
			<fieldset style="border: 1px solid #cccccc; padding: 10px;">
				
				<div id="" class="pT5">
					<h3>Payment Verification Requests <span class="right"><a href="javascript:window.history.go(-1)">Back</a></span></h3>
				</div>			    
			    <div class="clear"></div>

			    <!-- HISTORY OF ALL USER PAYMENT -->
				<div class="wdthpercent100 pT10 pL10">
					
					<table cellspacing="0" cellpadding="5" border="1" width="100%" id="grid_view" class="collapse">

						<tr class="txtcenter">
							<th bgcolor="#eeeeee">Username</th>
							<th bgcolor="#eeeeee">Email</th>
							<th bgcolor="#eeeeee">Instution Name</th>
							<th bgcolor="#eeeeee">Is verified</th>
							<th bgcolor="#eeeeee">Plan Selected</th>
							<th bgcolor="#eeeeee">Mode of Payment</th>							
							<th bgcolor="#eeeeee">Check Number</th>
							<th bgcolor="#eeeeee">Bank Drawn</th>
							<th bgcolor="#eeeeee">Date of Payment</th>
							<th bgcolor="#eeeeee">Added On </th>
							<th bgcolor="#eeeeee">Action</th>							
						</tr>

						<?php
						if(isset($total_payment_vefication) && $total_payment_vefication == '0'){
							echo '<tr><td>No Payment Request for verification Yet.</td></tr>';
						} else { 

							foreach($users_payment_vefication_new as $key => $paymentDetails) {

								$userDetail		= $user->getUser($paymentDetails['user_id']);	
								$UserTypeDetail	= $admin->getUserType($paymentDetails['instution_id']);
								?>
									<tr>
										<td align="left"><a href="<?php echo URL_SITE;?>/admin/user.php?action=view&id=<?php echo base64_encode($userDetail['id']);?>"><?php echo $userDetail['name']; ?></a></td>							
										<td><?php echo stripslashes($userDetail['email']);?></td>
										<td><?php echo stripslashes($UserTypeDetail['user_type']);?></td>
										<td><?php if($paymentDetails['is_verified'] == '0'){ echo '<font color="red">Pending</font>';} else if($paymentDetails['is_verified'] == '1'){ echo '<font color="green">Verified</font>';} else { echo '<font color="red">unverified</font>';}?></td>
										<td><?php echo ucwords(stripslashes($paymentDetails['plan_selected']));?></td>
										<td>
											<?php if(!empty($paymenttypes)){
												foreach($paymenttypes as $payments) {
													if($payments['payment_code'] == stripslashes($paymentDetails['mode_of_payment'])) {
													echo $payments['payment_type']; 
													}
												}
											}
											?> 
										</td>
										<td><?php echo stripslashes($paymentDetails['check_no']);?></td>
										<td><?php echo ucwords(stripslashes($paymentDetails['bank_drawn']));?></td>
										<td><?php echo date('d F, Y',strtotime($paymentDetails['date_of_payment']));?></td>
										<td><?php echo date('d F, Y',strtotime($paymentDetails['added_on']));?>
										</td>
										<td>
											<?php if($paymentDetails['is_verified'] == '0') { ?>
												<a onclick="return delete_action();" href="?action=verify&id=<?php echo $paymentDetails['id'];?>">Verify</a> | <a onclick="return delete_action();" href="?action=unverify&id=<?php echo $paymentDetails['id'];?>">Unverify</a> |
											<?php } else if($paymentDetails['is_verified'] == '1') { ?>
												<a onclick="return delete_action();" href="?action=unverify&id=<?php echo $paymentDetails['id'];?>">Unverify</a> | 				
											<?php } else if($paymentDetails['is_verified'] == '2') { ?>
												 <a onclick="return delete_action();" href="?action=verify&id=<?php echo $paymentDetails['id'];?>">Verify</a> |
											<?php } ?>
												<a onclick="return delete_action();" href="?action=delete&id=<?php echo $paymentDetails['id'];?>">Delete</a>
										</td>
									</tr>										
							<?php } ?>
							
							<tr>
								<td>&nbsp;</td>									
								<td>
									<!-- Pagination ----------->                      
									<div class="txtcenter pagination">
										<?php echo $users_payment_vefication_obj->renderFullNav();  ?>
									</div>
									<!-- /Pagination -----------> </td>							
								<td>&nbsp; </td>
							</tr>						
						<?php
						}
						?>
					</table>
				  </div>			
				  <div class="clear"></div>
				 <!-- HISTORY OF ALL USER PAYMENT -->
	
			</fieldset>

		 </div>
		<!-- left side -->		
	</div>
		
</section>
<!-- /container -->

<?php include_once $basedir."/include/adminFooter.php"; ?>