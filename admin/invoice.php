<?php
/******************************************
* @Modified on July 16, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$user = new user();

$transactionDetail = array();

if(isset($_GET['id']) && $_GET['id']!='') {
	
	$invoiceid			=	$_GET['id'];
	
	if(isset($_REQUEST['send'])){
	
		$admin->sendInvoice($invoiceid);
		$referer = $_SERVER['HTTP_REFERER'];
		$_SESSION['msgsuccess']="Invoice sent on mail";
		header('location: '.$referer.'');
	}

	$transactionDetail	=	$admin->selecttransactionDetail($invoiceid);
	
}

if(empty($transactionDetail)){
	header("location: transactions.php");
}

$userid = $transactionDetail['user_id'];

$userdetail	=	$user->getUser($transactionDetail['user_id']);


$databasesPurchased = $admin->getDatabasesPurchasedWithPayment($invoiceid, $transactionDetail['user_id']);

$labelinvoice = "INVOICE DETAILS";

if(isset($transactionDetail['pay_status']) && $transactionDetail['pay_status'] == 1){
	if($transactionDetail['date_paid'] != '' && $transactionDetail['date_paid'] != '0000-00-00'){
		$labelinvoice = "PAYMENT DETAILS";
	}
}

?>

<link href="<?php echo URL_SITE; ?>/css/invoice.css" rel="stylesheet" type="text/css" />

 <!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">

		<aside class="containerRadmin">
			<?php include_once $basedir."/include/adminLeft.php"; ?>
		</aside>

		<!-- left side -->
		<div class="containerLadmin">
			<h2><?php echo $userdetail['organisation']; ?>'s Account.<span class="right"><a href="javascript:window.history.go(-1)">Back</a></span></h2><br>

			<div class="tabnav mB10">		
				<div class="pL10 pT5" id="">
					Show: <a href="user.php?id=<?php echo base64_encode($userid); ?>&action=view" >Profile</a>&nbsp;&nbsp;
					<a href="user.php?id=<?php echo base64_encode($userid); ?>&action=edit">Edit Profile</a>&nbsp;&nbsp;
					<a href="<?php echo URL_SITE; ?>/admin/ipRanges.php?id=<?php echo base64_encode($userid)?>" >IPs</a>&nbsp;&nbsp;
					<a href="historical.php?id=<?php echo base64_encode($userid); ?>&status=1" >Historical Payments</a>&nbsp;&nbsp;
					<a href="historical.php?id=<?php echo base64_encode($userid); ?>&status=0" >Pending Payments</a>&nbsp;&nbsp;
					<a href="userPayment.php?id=<?php echo base64_encode($userid); ?>">Generate Invoice</a>&nbsp;&nbsp;


					<?php if($userdetail['user_type'] == 6){ ?>
					<a href="user.php?id=<?php echo base64_encode($userid); ?>&action=view">Additional Users Login</a>
					<?php } ?>
				</div>
			</div>

			<div id="page-wrap">
				<div id="headerInvoice"><?php echo $labelinvoice; ?></div>
				<div id="customer">
					<div id="customer-title">
						<div id="">
							<div id="billingAddress">
								<span class="name">Billing Address</span><br>
								<?=stripslashes($userdetail['name'])?> <?=stripslashes($userdetail['last_name'])?>, <?=stripslashes($userdetail['organisation'])?>,<br>
								<?=stripslashes($userdetail['address'])?><br>
								<?=stripslashes($userdetail['phone'])?><br>
								
							</div>
							<div id="billingAddress">
							<span class="name">Plan Details</span><br>
							<?=stripslashes($transactionDetail['plan_name'])?><br/>

							<?php if($transactionDetail['no_of_users'] >1){ ?>
							<span class="name">No. of Users: </span><?php echo $transactionDetail['no_of_users']; ?><br/>
							<?php } ?>
							
							<?php if($transactionDetail['original_rate'] >0){ ?>
							<span class="name">Price: </span>$<?php echo number_format($transactionDetail['original_rate'],4); ?><br/>
							<?php } ?>

							<?php if($transactionDetail['discounted_rate'] >0){ ?>
							<span class="name">Discounted Price: </span>$<?php echo number_format($transactionDetail['discounted_rate'],4); ?><br/>
							<?php } ?>

							<?php if(trim($transactionDetail['payment_details']) != ''){ ?>
							<span class="name">Payment Details:</span> 
							<br/><?=html_entity_decode(stripslashes($transactionDetail['payment_details']))?><br/>
							<?php } ?>

							</div>
							<div class="clear"></div>
						</div>
					</div>

					<table id="meta">
						<tbody><tr>
							<td class="meta-head">Invoice #</td>
							<td><div><?=$invoiceid?></div></td>
						</tr>
						<tr>

							<td class="meta-head">Invoice Date</td>
							<td><div id="date"><?php 
							
							if($transactionDetail['invoice_date'] != '0000-00-00' && $transactionDetail['invoice_date'] != ''){
								$transactionDate = $transactionDetail['invoice_date'];
							} else {
								$transactionDate = $transactionDetail['buy_on'];
							}
							echo date('F j, Y', strtotime($transactionDate)) ?></div></td>
						</tr>
						
						<?php if($transactionDetail['date_paid'] != '' && $transactionDetail['date_paid'] != '0000-00-00'){ ?>
						<tr>
							<td class="meta-head">Payment Date</td>
							<td><div id="date"><?php 
							echo date('F j, Y', strtotime($transactionDetail['date_paid'])) ?></div></td>
						</tr>
						<?php } ?>
						
						<tr>
							<td class="meta-head">Amount <?php if($transactionDetail['pay_status'] == '0'){ echo "Due"; } else { echo "Paid"; }?></td>
							<td><div class="due">$<?php echo number_format(trim($transactionDetail['amount']), 2); ?></div></td>
						</tr>

					</tbody></table>
				
				</div>
				
				<table id="items">
				
				  <tbody><tr>
					  <th>S.NO.</th>
					  <th>Database</th>
					  <th>Start Date</th>
					  <th>End Date</th>
					  <th>Days Left</th>
				  </tr>

				<?php if(count($databasesPurchased)>0){ 
					foreach($databasesPurchased as $key => $databaseDetails){
						$start_time			 = date("Y-m-d");					
						$expire_time		 = date("Y-m-d", strtotime($databaseDetails['expire_time']));				
						$validityleft = getnumberofDays($start_time,$expire_time);	
				?>
				  
				 
					 <tr class="item-row">
						  <td class="qty"><div style="text-align:center;"><?php echo $key+1; ?></div></td>
						  <td class="description"><div style="text-align:center;"><?php echo stripslashes( $databaseDetails['database_label']); ?></div></td>
						  <td class="item-name"><div style="text-align:center;"><?php echo date('F j, Y', strtotime($databaseDetails['start_time'])) ?></div></td>
						  <td class="qty"><div style="text-align:center;"><?php echo date('F j, Y', strtotime($databaseDetails['expire_time'])) ?></div></td>
						  <td class="qty"><div style="text-align:center;"><?php echo $validityleft;?> Days</span></td>
					  </tr>
				  <?php }
					 } else { ?>
					  <tr class="item-row">
					  <td colspan="5">Information not available</td>
					  </tr>
				  <?php } ?>
				  				  				  				  
				  <tr>

					  <td class="blank" colspan="3"> </td>
					  <td class="total-line" colspan="">Total</td>
					  <td class="total-value"><div style="text-align:right;" id="total">$<?php echo number_format(trim($transactionDetail['amount']+$transactionDetail['discount_amount']), 2); ?></div></td>
				  </tr>

				  <tr>
					  <td class="blank" colspan="3"> </td>
					  <td class="total-line" colspan="1">Discount</td>

					  <td class="total-value"><div style="text-align:right;" id="paid">$<?php  echo number_format(trim($transactionDetail['discount_amount']), 2); ?></div></td>
				  </tr>

				  <tr>
					  <td class="blank" colspan="3"> </td>
					  <td class="total-line" colspan="1">Amount Paid</td>

					  <td class="total-value"><div style="text-align:right;" id="paid">$<?php if($transactionDetail['pay_status'] == '1'){ echo number_format(trim($transactionDetail['amount']), 2); } else { echo "0.00"; }?></div></td>
				  </tr>
				  <?php if($transactionDetail['pay_status'] == '0'){ ?>
				  <tr>
					  <td class="blank" colspan="3"> </td>
					  <td class="total-line balance" colspan="1" style="background:#EBEBEB;">Balance Due</td>
					  <td class="total-value balance" style="background:#EBEBEB;"><div style="text-align:right;" class="due">$<?php echo number_format(trim($transactionDetail['amount']), 2); ?></div></td>
				  </tr>
				  <?php } ?>
				
				</tbody></table>		
			</div>

			
		 </div>
		<!-- left side -->

		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>



