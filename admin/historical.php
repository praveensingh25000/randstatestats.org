<?php
/******************************************
* @Modified on 18 July 2013.
* @Package: RAND
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$user = new user();


$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
if(!empty($siteMainDBDetail)) {
	$dbUsercode = $siteMainDBDetail['id']; 
} else { 
	$dbUsercode = 1; 
}

if( isset($_GET['id']) && $_GET['id']!=''){
	$userid				= trim(base64_decode($_GET['id']));
	$userDetail			= $user->getUser($userid);				
	$userTypeDetail		= $admin->getUserType($userDetail['user_type']);
	$dbUserDetail		= $admin->selectdatabaseUsers($dbUsercode,$userid);
}

$status = '';
$tab = 1;

$stringpay = "All Payments";

$append = '';

if(isset($_GET['status'])){
	$check = $_GET['status'];
	switch($_GET['status']){
		case "1":
			$status = 1;
			$append .= "&status=".$status;
			$tab = 2;
			$stringpay = "Historical Payments";
			break;
		case "0":
			$status = 0;
			$tab = 3;
			$append .= "&status=".$status;
			$stringpay = "Pending Payments";
			break;
		case "2":
			$status = 2;
			$tab = 4;
			$stringpay = "In-Abeyance Payments";
			$append .= "&status=".$status;
			break;
		default:
			$status = '';
	}
}

$query = $admin->getAllTransactions($status, $userid);
$resultTransactions = $db->run_query($query, $db->conn);
$totalTransactions = mysql_num_rows($resultTransactions);

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
			
			<h2><?php echo $userDetail['organisation']; ?>'s Account.<span class="right"><a href="javascript:window.history.go(-1)">Back</a></span></h2><br>

			<div class="tabnav mB10">		
				<div class="pL10 pT5" id="">
					Show: <a href="user.php?id=<?php echo base64_encode($userid); ?>&action=view" >Profile</a>&nbsp;&nbsp;
					<a href="user.php?id=<?php echo base64_encode($userid); ?>&action=edit">Edit Profile</a>&nbsp;&nbsp;
					<a href="<?php echo URL_SITE; ?>/admin/ipRanges.php?id=<?php echo base64_encode($userid)?>">IPs</a>&nbsp;&nbsp;
					<a href="historical.php?id=<?php echo base64_encode($userid); ?>&status=1" <?php if($tab == 2){ ?> class="active" <?php } ?>>Historical Payments</a>&nbsp;&nbsp;
					<a href="historical.php?id=<?php echo base64_encode($userid); ?>&status=0" <?php if($tab == 3){ ?> class="active" <?php } ?>>Pending Payments</a>&nbsp;&nbsp;
									
				
					<a href="userPayment.php?id=<?php echo base64_encode($userid); ?>">Generate Invoice</a>&nbsp;&nbsp;


					<?php if($userDetail['user_type'] == 6){ ?>
					<a href="user.php?id=<?php echo base64_encode($userid); ?>&action=view">Additional Users Login</a>
					<?php } ?>
				</div>
			</div>

			<fieldset style="border: 1px solid #cccccc; padding: 10px;">
				<h2><?=$stringpay?></h2><br>
				<form method="post" action="">
				<table cellspacing="0" cellpadding="2" border="1" class="data-table mT10">
					<thead>
						<tr>
							
							<?php if(is_integer($status) && ($status == '0' || $status == '2')){ ?>
							<th bgcolor="#eeeeee"><input type="checkbox" id="check_all" /></th>
							<?php } ?>

						
							<th bgcolor="#eeeeee">Name</th>
							<th bgcolor="#eeeeee">Organisation</th>
							<th bgcolor="#eeeeee">Plan</th>
							<th bgcolor="#eeeeee">Payment Type</th>

							<?php if(is_integer($status) && ($status == '0' || $status == '2')){ ?>
							<th bgcolor="#eeeeee">Payment Details</th>
							<?php } ?>

							<th bgcolor="#eeeeee" width="10%">Amount ($)</th>
							<th bgcolor="#eeeeee">Discount ($)</th>
							<th bgcolor="#eeeeee">Surcharge ($)</th>

						

							<th bgcolor="#eeeeee">Date</th>
							<th bgcolor="#eeeeee">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if($totalTransactions >0){ 
						while($transactionDetail = mysql_fetch_assoc($resultTransactions)){	
							$userid = $transactionDetail['user_id'];
							$userDetail = $user->getUser($userid);
						?>
							<tr>
								<?php if(is_integer($status) && ($status == '0' || $status == '2')){ ?>
								<td align="middle">
									<input type="checkbox" class="ids" name="ids[]" value="<?php echo $transactionDetail['id'];?>"/>
								</td>
								<?php } ?>

								<td><?=$userDetail['name']." ".$userDetail['last_name']?></td>
								<td><a href="user.php?action=view&id=<?php echo base64_encode($transactionDetail['user_id']);?>"><?=stripslashes($userDetail['organisation'])?></a></td>
								<td><?=$transactionDetail['plan_name']?></td>
								<td>
								<?php 
								if($transactionDetail['payment_type'] == 'expresscheckout'){
									echo "Paypal (Express Checkout)";
								} else if(trim($transactionDetail['creditCardNumber'])!='' && $transactionDetail['creditCardNumber']!=0){ 
									echo "Credit Card";
								} else {
									echo "Offline";
								}
								?>

								<?php if(is_integer($status) && ($status == '0' || $status == '2')){ ?>
								<td><?=$transactionDetail['payment_details']?></td>
								<?php } ?>

								</td>
								<td><?=number_format(trim($transactionDetail['amount']), 2)?></td>
								<td><?=number_format($transactionDetail['discount_amount'], 2)?></td>
								<td><?=number_format($transactionDetail['surcharge_amount'], 2)?></td>
							


								<td><?=date('F j, Y', strtotime($transactionDetail['buy_on']))?></td>
								<td>
								<a href="invoice.php?id=<?=$transactionDetail['id']?>&send">Send On Mail</a>&nbsp;|&nbsp;

								<a href="invoice.php?id=<?=$transactionDetail['id']?>">View Details</a>&nbsp;|&nbsp;
							
								<a href="editPaymentDetails.php?id=<?=$transactionDetail['id']?>&user_id=<?php echo base64_encode($userid); ?>">Edit Details</a>&nbsp;|&nbsp;
								
								<?php if($transactionDetail['payment_type'] == 'offline'){ ?>
								<a href="editPaymentDetails.php?id=<?=$transactionDetail['id']?>&user_id=<?php echo base64_encode($userid); ?>&delete=1<?php echo $append; ?>" onclick="javascript: confirm('Are you sure you want to delete it?\nOnce deleted it will not be recalled.')">Delete Details</a>
								<?php } ?>
								
								
								</td>
							</tr>
							
						<?php } ?>

							<?php if($status == '0' || $status == '2'){ ?>
								<tr>
									<td colspan="9">
										<input type="submit" name="action" value="Mark as Paid" onclick="javascript: return check('deactive');"/>
										<script type="text/javascript">

										jQuery(document).ready(function(){
											jQuery('#check_all').click(function () {
												jQuery('.ids').attr('checked', this.checked);
											});
										});							

										function check(action){								
											var atLeastOneIsChecked = $('input[name="ids[]"]:checked').length > 0;
											
											var confirmcheck = confirm("Are you sure you want to perform this action? This action will not be reverted.");
											if(!confirmcheck){
												return false;
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
								<?php } ?>						
						<?php } else { ?>
						
						<tr><td colspan="9">No records found</td></tr>

						<?php } ?>
					</tbody>
				</table>
				</form>
			</fieldset>
		</div><!-- left side -->
	</div>
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>


