<?php
/******************************************
* @Modified on 15 July, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once($basedir.'/include/headerHtml.php');

$admin = new admin();
$user = new user();

$transaction_id = (isset($_SESSION['trans_before_id']))?$_SESSION['trans_before_id']:'0';
if($transaction_id !=0 ){
	$admin->deleteTransaction($transaction_id);
}

?>

<div style="padding:10px;text-align:center;">
	
	<h2><font size="" color="red">You have cancelled your transaction on paypal</font></h2>
	<br class="clear" />
</div>

<?php
unset($_SESSION['trans_before_id']);
unset($_SESSION['payment_data']);
unset($_SESSION['plan_data']);
unset($_SESSION['db_membership_id']);
unset($_SESSION['data']);
unset($_SESSION['nvpReqArray']);
unset($_SESSION['nvpResArray']);
unset($_SESSION['db_name']);
unset($_SESSION['payment_upgrade']);
?>


<?php include_once($basedir.'/include/footerHtml.php'); ?>