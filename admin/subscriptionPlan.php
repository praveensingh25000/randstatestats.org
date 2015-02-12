<?php
/******************************************
* @Modified on Dec 25, 2012
* @Package: Rand
* @Developer: Mamta sharma
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

$name = $description = $amount= $validity = '';

if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_GET['id']!=''){

	$planid = trim(base64_decode($_GET['id']));
	$planDetail = $admin->getPlan($planid);
	if(!empty($planDetail)){
		$name	 = stripslashes($planDetail['plan_name']);
		$description  = stripslashes($planDetail['description']);
		$amount = stripslashes($planDetail['amount']);
		$validity = stripslashes($planDetail['validity']);
	}
}

if(isset($_POST['addPlan'])){
	$name	 = (isset($_POST['name']))?trim(addslashes($_POST['name'])):'';
	$description	 = (isset($_POST['description']))?trim(addslashes($_POST['description'])):'';
	$amount = (isset($_POST['amount']))?trim(addslashes($_POST['amount'])):'';
	$validity = (isset($_POST['validity']))?trim(addslashes($_POST['validity'])):'';
	$planid = $admin->insertPlan($name,$description,$amount,$validity);
	if($planid <=0){
		$name	 = stripslashes($_POST['name']);
		$description	 = stripslashes($_POST['description']);
		
		$amount = stripslashes($_POST['amount']);
		
		$validity = stripslashes($_POST['validity']);
	} else {
		unset($_SESSION['plan']);
		header('location: subscriptionPlans.php');
		exit;
	}
	
}


if(isset($_POST['updatePlan'])){
	$name		= (isset($_POST['name']))?trim(addslashes($_POST['name'])):'';
	$description	 = (isset($_POST['description']))?trim(addslashes($_POST['description'])):'';
	$amount = (isset($_POST['amount']))?trim(addslashes($_POST['amount'])):'';
	$validity	= (isset($_POST['validity']))?trim(addslashes($_POST['validity'])):'';
	$planid		= $_POST['planid'];
	$return = $admin->updatePlan($name,$description,$amount,$validity, $planid);
	if($return <=0){
		$name	 = stripslashes($_POST['name']);
		
		$validity = stripslashes($_POST['validity']);
		$description	 = stripslashes($_POST['description']);
		
		$amount = stripslashes($_POST['amount']);
	} else {
		unset($_SESSION['plan']);
		header('location: subscriptionPlans.php');
		exit;
	}
	
}

//updateCategory($catname, $parent, $description, $id)

$plansResult = $admin->showAllPlans();
$total = $db->count_rows($plansResult);
$plans = $db->getAll($plansResult);


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
			<div style="clear: both; padding: 15px 0;">
			<fieldset style="border: 1px solid #cccccc; padding: 10px;">
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($planDetail) && !empty($planDetail)){ echo "Edit"; } else { echo "Add"; } ?> Plan</legend>
				<form id="frmAllplan" name="frmAllplan" method="post">
				<p>Plan Name<em>*</em></p>
				<div style="padding: 10px 0;">
					<input type="text" id="name" name="name" class="required" value="<?php if(isset($name)){ echo $name; } ?>"/>
				</div>
				<p>Description</p>
				<div style="padding: 10px 0;">
					<textarea name="description" rows = '3' cols="30"><?php if(isset($description)){ echo $description; } ?></textarea>
				</div>
				<p>Amount<em>*</em></p>
				<div style="padding: 10px 0;">
					<input type="text" id="amount" name="amount" class="digits required" value="<?php if(isset($amount)){ echo $amount ;} ?>"/>
				</div>
				<p>Validity<em>*</em></p>
				<div style="padding: 10px 0;">
					<input type="text" id="validity" name="validity" class="digits required" value="<?php if(isset($validity)){ echo $validity ;} ?>"/>Days
				</div>
				

				<div class="submit1 submitbtn-div">
					<label for="submit" class="left">
						<?php if(isset($planDetail) && !empty($planDetail)){ ?>
						<input type="hidden" value="<?php echo $planid; ?>" name="planid"/>
						<input type="submit" value="Submit" name="updatePlan" class="submitbtn" >
						<?php } else { ?>
						<input type="submit" value="Submit" name="addPlan" class="submitbtn" >
						<?php } ?>

					</label>
					<label for="reset" class="right">
						<input type="reset" id="reset" class="submitbtn">
					</label>
				</div>
				</form>
				</fieldset>
			</div>

		 </div>
		<!-- left side -->

		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>


