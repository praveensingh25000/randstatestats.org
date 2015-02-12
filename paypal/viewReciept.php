<?php
/******************************************
* @Modified on 29 JAN, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once($basedir.'/include/headerHtml.php');

$admin = new admin();
$user = new user();

//echo "<pre>";print_r($_SESSION);echo "</pre>";die;

if(isset($_GET['id']) && $_GET['id']!='') {
	
	$transaction_table_id=$_GET['id'];	
	$transactiondetail			=	$admin->selecttransactionDetail($transaction_table_id);
	$userdetail					=	$user->getUser($transactiondetail['user_id']);
	
	$user_type = $userdetail['user_type'];
	$typeDetail = $admin->getUserType($user_type);

	$plan_name	=	$transactiondetail['plan_id'] . " year (".ucwords($typeDetail['user_type']).")";

	if(!empty($transactiondetail)) { ?>

		<div style="padding:10px;text-align:center;">
			
			<h2><font size="" color="green">Congratulations! <?php echo ucwords($userdetail['name']);?>. Your have bought <?php echo ucwords($plan_name);?> Plan successfully.</font></h2>
			<br class="clear" />
			
			<h3> Your Shopping Receipt.</h3>
			<br class="clear" />
			
			<table width="40%" border="1" align="center" cellpadding="5">
				
				<tr>
					<th>Plan Name</th> 
					<th>Transaction ID</th>
					<th>Total Amount</th>					
				</tr>
	
				<tr>	
					<td> <?php echo ucwords($plan_name);?> </td>
					<td> <?php echo $transactiondetail['paypal_transaction_id'];?> </td>				
					<td> $<?php echo $transactiondetail['amount'];?></td>
				</tr>
				
			</table>

			<!-- <br><br>
			
			 <h2>
				<?php if(isset($_SESSION['user']) && isset($_SESSION['searchedfieldsonestage'])) { ?>
					<a style="" href="<?php echo URL_SITE;?>/showSearchedData.php"> See Search Result </a>
				<?php } ?>
			</h2> -->

		</div>
	<?php } else {	
		header('location:'.URL_SITE.'/index.php');
	}
} else {	
	header('location:'.URL_SITE.'/index.php');
}
?>

<?php include_once($basedir.'/include/footerHtml.php'); ?>