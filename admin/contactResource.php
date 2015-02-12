<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

$name = $organisation = $phnno = $email = $address = $remarks = '';


if(isset($_POST['addcontact']) || isset($_POST['updatecontact'])){

	$name			= (isset($_POST['name']))?trim(addslashes($_POST['name'])):'';
	$organisation	= (isset($_POST['organisation']))?trim(addslashes($_POST['organisation'])):'';
	$phnno			= (isset($_POST['phnno']))?trim(addslashes($_POST['phnno'])):'';
	$email			= (isset($_POST['email']))?trim(addslashes($_POST['email'])):'';
	$dbid			= (isset($_POST['dbid']))?trim(addslashes($_POST['dbid'])):'';
	$address		= (isset($_POST['address']))?trim(addslashes($_POST['address'])):'';
	$remarks		= (isset($_POST['remarks']))?trim(addslashes($_POST['remarks'])):'';
	
	if(isset($_POST['addcontact'])) {
		$cid = $admin->insertContactResource($name, $organisation, $phnno, $email, $address, $remarks, $dbid);
	} else if(isset($_POST['updatecontact'])){
		
		$cid			= (isset($_POST['cid']))?trim(addslashes($_POST['cid'])):'';
		$return = $admin->updateContactResource($name, $organisation, $phnno, $email, $address, $remarks, $dbid, $cid);
	}

	header('location: contactResources.php?tab=8&id='.base64_encode($dbid).'');
	exit;

}

if(isset($_GET['cid']) && $_GET['cid']!=''){
	$cid = trim(stripslashes($_GET['cid']));
	$contactResourceDetail = $admin->getContactResourceDetail($cid);

	$name			= stripslashes($contactResourceDetail['name']);
	$organisation	= stripslashes($contactResourceDetail['organisation']);
	$phnno			= stripslashes($contactResourceDetail['phnno']);
	$email			= stripslashes($contactResourceDetail['email']);
	$address		= stripslashes($contactResourceDetail['address']);
	$remarks		= stripslashes($contactResourceDetail['remarks']);
	$status			= stripslashes($contactResourceDetail['status']);
}

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(stripslashes(base64_decode($_GET['id'])));
	$databaseDetail = $admin->getDatabase($dbid, true);
	if(!empty($databaseDetail)){
		$dbname		= stripslashes($databaseDetail['db_name']);
		$contactResources = $admin->getContactResources($dbid);
	}else{
		header('location: databases.php');
	}
}else{
	header('location: databases.php');
}

$_GET['tab'] = '8';
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
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Contact Resource for '".$databaseDetail['db_name']."'"; } ?> </legend>
					

					<?php include("formNavigation.php"); ?>

					<form id="frmAllCat" name="frmAllCat" method="post">
					<p>Name<em>*</em></p>
					<div style="padding: 10px 0;">
						<input type="text" id="name" name="name" class="required" value="<?php if(isset($name)){ echo $name; } ?>"/>
					</div>

					<p>Organisation<em>*</em></p>
					<div style="padding: 10px 0;">
						<input type="text" id="organisation" name="organisation" class="required" value="<?php if(isset($organisation)){ echo $organisation; } ?>"/>
					</div>

					<p>Phone</p>
					<div style="padding: 10px 0;">
						<input type="text" id="phnno" name="phnno" class="digits" value="<?php if(isset($phnno)){ echo $phnno; } ?>"/>
					</div>
					
					<p>Email<em>*</em></p>
					<div style="padding: 10px 0;">
						<input type="text" id="email" name="email" class="required email" value="<?php if(isset($email)){ echo $email; } ?>"/>
					</div>

					<p>Address</p>
					<div style="padding: 10px 0;">
						<textarea name="address" rows = '3' cols="30"><?php if(isset($address)){ echo $address; } ?></textarea>
					</div>

					<p>Status</p>
					<div style="padding: 10px 0;">
						<select name="status">
							<option value="1" <?php if(isset($status) && $status == '1'){ echo "selected='selected'"; } ?>>Active</option>
							<option value="0" <?php if((isset($status) && $status == '0' && $status == '') || (!isset($status))){ echo "selected='selected'"; } ?>>In-Active</option>
							<option value="2" <?php if(isset($status) && $status == '2'){ echo "selected='selected'"; } ?>>Retired</option>
						</select>
					</div>

					<p>Remarks(Admin Only)</p>
					<div style="padding: 10px 0;">
						<textarea name="remarks" rows = '3' cols="30"><?php if(isset($remarks)){ echo $remarks; } ?></textarea>
					</div>

					<div class="submit1 submitbtn-div">
						<label for="submit" class="left">
						<?php if(isset($databaseDetail) && !empty($databaseDetail)){ ?>
							<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>

							<?php if(isset($_GET['cid']) && $_GET['cid']!=''){ ?>
								<input type="hidden" value="<?php echo $cid; ?>" name="cid"/>
								<input type="submit" value="Submit" name="updatecontact" class="submitbtn" >
							<?php } else { ?>
								<input type="submit" value="Submit" name="addcontact" class="submitbtn" >
							<?php } ?>

							<?php } ?>
						</label>
						
						<div style="display:none;" id="table_name_json"><?php echo $table_name; ?></div>
					</div>


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


