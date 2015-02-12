<?php
/******************************************
* @Modified on July 09, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

$user = new user();

$user_type ='';

if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_GET['id']!=''){

	$typeid = trim(base64_decode($_GET['id']));
	$typeDetail = $admin->getUserType($typeid );
	if(!empty($typeDetail)){
		$user_type	 = stripslashes($typeDetail['user_type']);
		
	}
}

if(isset($_POST['save'])){

	$baseprice = (isset($_POST['baseprice']))?$_POST['baseprice']:0;	// Base Price that needs to be multiply with no of users
	$basepriceus = (isset($_POST['basepriceus']))?$_POST['basepriceus']:0;	// Base Price that needs to be multiply with no of users for ( US Only )

	//In Case Of Multiple
	
	$basepriceindividual = (isset($_POST['basepriceindividual']))?$_POST['basepriceindividual']:0;	// Base Price that needs to be multiply with no of users
	$basepriceusindividual = (isset($_POST['basepriceusindividual']))?$_POST['basepriceusindividual']:0;	// Base Price that needs to be multiply with no of users for ( US Only )


	$minimumprice = (isset($_POST['minimumprice']))?$_POST['minimumprice']:0;	// Base Price that needs to be multiply with no of users
	
	// All states except US
	$surchargeonestate = (isset($_POST['surchargeonestate']))?$_POST['surchargeonestate']:0;	// Surcharge One state price that needs to be calcualted as (base price * noofusers) + surcharegeonestate of (base price * noofusers)

	$surchargetwostate = (isset($_POST['surchargetwostate']))?$_POST['surchargetwostate']:0;	// Surcharge One state price that needs to be calcualted as (base price * noofusers) + surchargetwostate of (base price * noofusers)

	$surchargethreestate = (isset($_POST['surchargethreestate']))?$_POST['surchargethreestate']:0;	// Surcharge One state price that needs to be calcualted as (base price * noofusers) + surchargethreestate of (base price * noofusers)

	
	// US Only
	$surchargeonestateus = (isset($_POST['surchargeonestateus']))?$_POST['surchargeonestateus']:0;	// Surcharge One state price that needs to be calcualted as (base price US * noofusers) + surchargeonestateus of (base price * noofusers)

	$surchargetwostateus = (isset($_POST['surchargetwostateus']))?$_POST['surchargetwostateus']:0;	// Surcharge One state price that needs to be calcualted as (base price US * noofusers) + surchargetwostateus of (base price * noofusers)

	$surchargethreestateus = (isset($_POST['surchargethreestateus']))?$_POST['surchargethreestateus']:0;	// Surcharge One state price that needs to be calcualted as (base price US * noofusers) + surchargethreestate of (base price * noofusers)

	
	//In Case Of Multiple


	// All states except US
	$surchargeonestateindividual = (isset($_POST['surchargeonestateindividual']))?$_POST['surchargeonestateindividual']:0;	

	$surchargetwostateindividual = (isset($_POST['surchargetwostateindividual']))?$_POST['surchargetwostateindividual']:0;	

	$surchargethreestateindividual = (isset($_POST['surchargethreestateindividual']))?$_POST['surchargethreestateindividual']:0;	

	
	// US Only
	$surchargeonestateusindividual = (isset($_POST['surchargeonestateusindividual']))?$_POST['surchargeonestateusindividual']:0;	

	$surchargetwostateusindividual = (isset($_POST['surchargetwostateusindividual']))?$_POST['surchargetwostateusindividual']:0;	

	$surchargethreestateusindividual = (isset($_POST['surchargethreestateusindividual']))?$_POST['surchargethreestateusindividual']:0;	


	$pricepercentage = (isset($_POST['pricepercentage']))?0:1;

	$user_type = $typeDetail['id'];

	$id = $user->addUserTypeInfo($user_type, $baseprice, $basepriceus, $basepriceindividual, $basepriceusindividual, $minimumprice, $pricepercentage, $surchargeonestate, $surchargetwostate, $surchargethreestate, $surchargeonestateus, $surchargetwostateus, $surchargethreestateus, $surchargeonestateindividual, $surchargetwostateindividual, $surchargethreestateindividual, $surchargeonestateusindividual, $surchargetwostateusindividual, $surchargethreestateusindividual);
	if($id>0){
		$_SESSION['successmsg']="User type updated successfully.";
	} else {
		$_SESSION['errormsg']="User type could not be updated. Please try again";
	}

	header('location: userTypePrices.php?action=edit&id='.base64_encode($user_type).'');
	
}

$firstlogin = "";
if($typeDetail['id'] == 6){
	$firstlogin = "First Login";
}

$pricetxt = '%';

$user_type = $typeDetail['id'];

$baseprice = $basepriceus = $basepriceindividual = $basepriceusindividual = $minimumprice = $pricepercentage = $surchargeonestate = $surchargetwostate = $surchargethreestate = $surchargeonestateus = $surchargetwostateus = $surchargethreestateus = $surchargeonestateindividual = $surchargetwostateindividual = $surchargethreestateindividual = $surchargeonestateusindividual = $surchargetwostateusindividual = $surchargethreestateusindividual = '';

$userTypeInfo = $user->getUserTypeInfo($user_type);

$baseprice = (isset($userTypeInfo['baseprice']))?$userTypeInfo['baseprice']:'';
$basepriceus = (isset($userTypeInfo['basepriceus']))?$userTypeInfo['basepriceus']:'';

$basepriceindividual = (isset($userTypeInfo['basepriceindividual']))?$userTypeInfo['basepriceindividual']:'';

$basepriceusindividual = (isset($userTypeInfo['basepriceusindividual']))?$userTypeInfo['basepriceusindividual']:'';

$minimumprice = (isset($userTypeInfo['minimumprice']))?$userTypeInfo['minimumprice']:'';

$pricepercentage = (isset($userTypeInfo['pricepercentage']))?$userTypeInfo['pricepercentage']:'';

$surchargeonestate = (isset($userTypeInfo['surchargeonestate']))?$userTypeInfo['surchargeonestate']:'';

$surchargetwostate = (isset($userTypeInfo['surchargetwostate']))?$userTypeInfo['surchargetwostate']:'';

$surchargethreestate = (isset($userTypeInfo['surchargethreestate']))?$userTypeInfo['surchargethreestate']:'';

$surchargeonestateus = (isset($userTypeInfo['surchargeonestateus']))?$userTypeInfo['surchargeonestateus']:'';

$surchargetwostateus = (isset($userTypeInfo['surchargetwostateus']))?$userTypeInfo['surchargetwostateus']:'';

$surchargethreestateus = (isset($userTypeInfo['surchargethreestateus']))?$userTypeInfo['surchargethreestateus']:'';

$surchargeonestateindividual = (isset($userTypeInfo['surchargeonestateindividual']))?$userTypeInfo['surchargeonestateindividual']:'';

$surchargetwostateindividual = (isset($userTypeInfo['surchargetwostateindividual']))?$userTypeInfo['surchargetwostateindividual']:'';

$surchargethreestateindividual = (isset($userTypeInfo['surchargethreestateindividual']))?$userTypeInfo['surchargethreestateindividual']:'';

$surchargeonestateusindividual = (isset($userTypeInfo['surchargeonestateusindividual']))?$userTypeInfo['surchargeonestateusindividual']:'';

$surchargetwostateusindividual = (isset($userTypeInfo['surchargetwostateusindividual']))?$userTypeInfo['surchargetwostateusindividual']:'';

$surchargethreestateusindividual = (isset($userTypeInfo['surchargethreestateusindividual']))?$userTypeInfo['surchargethreestateusindividual']:'';

if($pricepercentage == 1){
	$pricetxt = 'USD';
}
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
				<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?=ucwords($typeDetail['user_type'])?> Pricing</legend>

				<form id="addUserType" name="addUserType" method="post">
				<table cellspacing="0" cellpadding="8" border = 1>
					<tr>
						<td>
						CA, TX, NY (Base Price) <?php echo $firstlogin; ?>
						</td>
						<td>
							<input type="text" name="baseprice" value="<?=$baseprice?>" class="left"/>&nbsp;<span>USD</span>
						</td>
					</tr>

					<tr>
						<td>
						US (Base Price) <?php echo $firstlogin; ?>
						</td>
						<td>
							<input type="text" name="basepriceus" value="<?=$basepriceus?>" class="left"/>&nbsp;<span>USD</span>
						</td>
					</tr>
					
						<?php if($typeDetail['id'] == 6){ ?>

					<tr>
						<td>
						CA, TX, NY (Base Price) Each Additional Login
						</td>
						<td>
							<input type="text" name="basepriceindividual" value="<?=$basepriceindividual?>" class="left"/>&nbsp;<span>USD</span>
						</td>
					</tr>

					<tr>
						<td>
						US (Base Price) Individual Login
						</td>
						<td>
							<input type="text" name="basepriceusindividual" value="<?=$basepriceusindividual?>" class="left"/>&nbsp;<span>USD</span>
						</td>
					</tr>

					<?php } ?>

					<tr>
						<td>
						Minimum Fee
						</td>
						<td>
							<input type="text" name="minimumprice" value="<?=$minimumprice?>" class="left"/>&nbsp;<span>USD</span>
						</td>
					</tr>

					<tr>
						<td>
						Surcharge Value In Percentage(%):
						</td>
						<td>
							<input type="checkbox" name="pricepercentage" id="pricepercentage" value="" <?php if($pricetxt == '%'){ ?> checked <?php } ?>/>
							<script type="text/javascript">
								jQuery(document).ready(function(){
									jQuery('#pricepercentage').change(function(){
										var checked = jQuery(this).attr('checked');
										if(checked){
											jQuery(".per").html("%");
										} else {
											jQuery(".per").html("USD");
										}
									});
								});
							</script>
						</td>
					</tr>

				

					<tr>
						<td>
						Surcharge (CA, TX, NY) <?php echo $firstlogin; ?>
						</td>
						<td>
							<table cellpadding="4" cellspacing="4">
								<tr>
									<td width="30%">One State</td>
									<td width="30%">Two State</td>
									<td width="30%">Three State</td>
								</tr>
								<tr>
									<td width="30%"><input type="text" name="surchargeonestate" value="<?=$surchargeonestate?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></span></td>
									<td width="30%">
										<input type="text" name="surchargetwostate" value="<?=$surchargetwostate?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></span>
									</td>
									<td width="30%">
										<input type="text" name="surchargethreestate" value="<?=$surchargethreestate?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></span>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td>
						Surcharge (US Only) <?php echo $firstlogin; ?>
						</td>

						<td>
							<table cellpadding="4" cellspacing="4">
								<tr>
									<td width="30%"><input type="text" name="surchargeonestateus" value="<?=$surchargeonestateus?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></td>
									<td width="30%">
										<input type="text" name="surchargetwostateus" value="<?=$surchargetwostateus?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></span>
									</td>
									<td width="30%">
										<input type="text" name="surchargethreestateus" value="<?=$surchargethreestateus?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></span>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<?php if($typeDetail['id'] == 6){ ?>


					<tr>
						<td>
						Surcharge (CA, TX, NY) Each Additional Login
						</td>
						<td>
							<table cellpadding="4" cellspacing="4">
								<tr>
									<td width="30%">One State</td>
									<td width="30%">Two State</td>
									<td width="30%">Three State</td>
								</tr>
								<tr>
									<td><input type="text" name="surchargeonestateindividual" value="<?=$surchargeonestateindividual?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></span>
									</td>
									<td>
										<input type="text" name="surchargetwostateindividual" value="<?=$surchargetwostateindividual?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></span>
									</td>
									<td>
										<input type="text" name="surchargethreestateindividual" value="<?=$surchargethreestateindividual?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></span>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td>
						Surcharge (US Only) Each Additional Login
						</td>
						<td>
							<table cellpadding="4" cellspacing="4">
								<tr>
									<td width="30%"><input type="text" name="surchargeonestateusindividual" value="<?=$surchargeonestateusindividual?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></span></td>

									<td width="30%"><input type="text" name="surchargetwostateusindividual" value="<?=$surchargetwostateusindividual?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></span></td>

									<td width="30%"><input type="text" name="surchargethreestateusindividual" value="<?=$surchargethreestateusindividual?>" class="left"/>&nbsp;<span class="per"><?php echo $pricetxt; ?></span></td>
								</tr>
							</table>
						</td>
					</tr>

					<?php } ?>

					<tr>
						<td>
						&nbsp;
						</td>
						<td>
							<input type="submit" name="save" value="Save" />
						</td>
					</tr>


				</table>
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