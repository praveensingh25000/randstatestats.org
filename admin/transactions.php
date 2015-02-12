<?php
/******************************************
* @Modified on July 16, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

global $db, $dbDatabase;

checkSession(true);

$user = new user();

$status = $payment_type = $append = '';

$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
if(!empty($siteMainDBDetail)) {
	$dbUsercode = $siteMainDBDetail['id']; 
} else { 
	$dbUsercode = 1; 
}

$tab = 1;

if(isset($_POST['action']) && $_POST['ids'] && count($_POST['ids'])>0){

	$ids = implode(',', $_POST['ids']);
	$return = $admin->updatePaymentStatusBulk($ids);
	if($return>0){
		$_SESSION['successmsg'] = "Transactions marked as paid successfully";
		header('location: transactions.php?status=1');
	} else {
		$_SESSION['errormsg'] = "Due to system error. Action could not be performed.";
		header('location: transactions.php?status=0&inabyance=1');
	}	
	exit;
}


if(isset($_GET['status'])){
	$check = $_GET['status'];
	switch($_GET['status']){
		case "1":
			$status = 1;
			$append .= "&status=".$status;
			$tab = 2;
			break;
		case "0":
			$status = 0;
			$tab = 3;
			$append .= "&status=".$status;
			break;
		case "2":
			$status = 2;
			$tab = 4;
			$append .= "&status=".$status;
			break;
		default:
			$status = '';
	}
}

$keyword = '';

if(isset($_POST['keyword'])){
	$keyword = trim($_POST['keyword']);
	$append .= "&keyword=".$keyword;
}


$query = $admin->getAllTransactionsDatabaseWise($status, $dbUsercode, $keyword);
$result = $db->run_query($query);

//$pagination = new PS_Pagination($db->conn, $query, 20, $links_per_page = 5, $append);
//$result = $pagination->paginate($db->conn);
//$total = $pagination->total_rows;
$total = mysql_num_rows($result);
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
			<h2>Transactions</h2>
			<div class="tabnav pT10 mT10">		
				<div class="wdthpercent50 left pL10 pT5" id="">
					Show: <a href="transactions.php" <?php if($tab == 1){ ?> class="active" <?php } ?>>All</a>&nbsp;&nbsp;
					<a href="transactions.php?status=1" <?php if($tab == 2){ ?> class="active" <?php } ?>>Completed</a>&nbsp;&nbsp;
					<a href="transactions.php?status=0" <?php if($tab == 3){ ?> class="active" <?php } ?>>Pending</a>&nbsp;&nbsp;

				</div>

				<div id="" class="wdthpercent40 right pR10">
					<div class="wdthpercent20 left"><span class="listform">Search:</span></div>
					<div class="wdthpercent70 left">
						<form method="post" action="">
						<input type="text" placeholder="enter name or organisation" name="keyword" style="width: 189px;" class="left">			
						<input type="submit" value="Go" class="mL10 left" name="search">
					</div>							
				</div>

			</div>
			
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

						<?php if(!is_integer($status)){ ?>
						<th bgcolor="#eeeeee">Status</th>
						<?php } ?>

						<th bgcolor="#eeeeee">Date</th>
						<th bgcolor="#eeeeee">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if($total >0){ 
					while($transactionDetail = mysql_fetch_assoc($result)){	
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
							 <td><a href="user.php?action=view&id=<?php echo base64_encode($transactionDetail['user_id']);?>"><?=$userDetail['organisation']?></a></td>
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
							
							<?php if(!is_integer($status)){ ?>
							<td><?php 
							if( $transactionDetail['pay_status'] == '2'){
								echo "In Abeyance";
							} else if($transactionDetail['pay_status'] == '0'){
								echo "Pending";
							} else {
								echo "Completed";
							}
							?>
							</td>
							<?php } ?>

							<td><?=date('F j, Y', strtotime($transactionDetail['buy_on']))?></td>
							<td><a href="invoice.php?id=<?=$transactionDetail['id']?>">View Details</a>
								<a href="editPaymentDetails.php?id=<?=$transactionDetail['id']?>&user_id=<?php echo base64_encode($transactionDetail['user_id']); ?>">Edit Details</a>
							</td>
						</tr>
						
					<?php } ?>

						<?php if(isset($_GET['inabyance'])){ ?>
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
		 </div>
		<!-- left side -->
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>



