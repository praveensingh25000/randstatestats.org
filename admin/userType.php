<?php
/******************************************
* @Modified on Jan 02, 2013
* @Package: Rand
* @Developer: Mamta sharma
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

$user_type ='';

$addusertype = 'Single';

if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_GET['id']!=''){

	$typeid = trim(base64_decode($_GET['id']));
	$typeDetail = $admin->getUserType($typeid );
	if(!empty($typeDetail)){
		$user_type	 = stripslashes($typeDetail['user_type']);
		echo $addusertype = $_SESSION['addusertype'] = $user_type;
	}
}

if(isset($_POST['submitsinglemultipleusertype'])){
	$user_type	 = (isset($_POST['user_type']))?trim(addslashes($_POST['user_type'])):'';
	$typeid = $admin->insertUserType($user_type);
	if($typeid  <=0){
		$user_type	 = stripslashes($_POST['user_type']);		
	} else {
		unset($_SESSION['usertype']);
		$_SESSION['msgsuccess']="User Type has been added successfully";
		header('location: userTypes.php');
		exit;
	}	
}

if(isset($_POST['updatesinglemultipleusertype'])){

	$user_type	= (isset($_POST['user_type']))?trim(addslashes($_POST['user_type'])):'';
	$typeid		= $_POST['typeid'];
	$return		= $admin->updateUserType($user_type,$typeid);
	
	if($return <=0){
			$user_type	 = stripslashes($_POST['user_type']);		
	} else {
		unset($_SESSION['type']);
		$_SESSION['msgsuccess']="User Type has been updated successfully";
		header('location: userTypes.php');
		exit;
	}
	
}

$typesResult = $admin->showAllUserTypes();
$total = $db->count_rows($typesResult);
$types = $db->getAll($typesResult);

if((isset($_POST['addusertype']) && $_POST['addusertype']!='') || (isset($_SESSION['addusertype']) && $_SESSION['addusertype'] !='' )) {
		
		if(isset($_POST['addusertype'])) {
			$addusertype = $_SESSION['addusertype'] = trim($_POST['addusertype']);
		} else {
			$addusertype = $_SESSION['addusertype'];
		}
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
			<fieldset style="border: 1px solid #cccccc; padding: 10px;">

				<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($typeDetail) && !empty($typeDetail)){ echo "Edit"; } else { echo "Add"; } ?> User Type</legend>
				
				<div class="wdthpercent100 pT10 pB10">

					<div class="wdthpercent20 left">Select a User Type</div>
					<div class="wdthpercent50 left">
						<form name="frmaddusertypeform" action = "" method="post" id="frmaddusertypeform">
							<select class="required" <?php if(isset($_GET['action']) && $_GET['action'] == 'edit') { ?> disabled="true" <?php } ?> id="addusertype" name="addusertype">
								<option value="0">Select User Type </option>
								<!-- <option value="Single" <?php if(isset($addusertype) && $addusertype =='Single'){ echo "selected='selected'"; } ?>>Single</option>
								<option value="Multiple" <?php if(isset($addusertype) && $addusertype =='Multiple'){ echo "selected='selected'"; } ?>> Multiple </option> -->
								<option value="Institution" <?php if(isset($addusertype) && $addusertype =='Institution'){ echo "selected='selected'"; } ?>>Institution</option>
							</select>
						</form>						
					</div>

					<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery('#addusertype').change(function(){
								if(jQuery('#addusertype').val() != '0'){
									jQuery('#frmaddusertypeform').submit();
								}
							});
						});
					</script>				
				</div>
				<div id="" class="clear pB10"></div>

				<?php
				if(isset($addusertype) && ($addusertype == 'Single' || $addusertype == 'Multiple')) { ?>
					
					<div id="" class="pT10 pB10">
						  <form id="addsinglemultipleUserType" name="addsinglemultipleUserType" method="post">					
							
							<div style="wdthpercent100 pT10 pB10">
								<div id="" class="wdthpercent20 left">Enter the name:</div>
								<div id="" class="wdthpercent50 left">
									<input placeholder="Enter the name" type="text" id="name" name="user_type" class="required" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/>
								</div>
							</div>	
							<div id="" class="clear pB10"></div>
							
							<div style="wdthpercent100">
								<div id="" class="wdthpercent20 left">&nbsp;</div>
								<div id="" class="wdthpercent70 left">
									<?php if(isset($typeDetail) && !empty($typeDetail)){ ?>
									<input type="hidden" value="<?php echo $typeid; ?>" name="typeid"/>
									<input type="submit" value="Submit" name="updatesinglemultipleusertype" class="submitbtn" >
									<?php } else { ?>
									<input type="submit" value="Submit" name="submitsinglemultipleusertype" class="submitbtn" >
									<?php } ?>
									<input onclick="javascript:window.history.go(-1)" type="button" value="Back" class="submitbtn">
								</div>
							</div>	
							<div id="" class="clear"></div>
						</form>
					</div>
				<?php } ?>
				<div id="" class="clear pB10"></div>

				<?php
				if(isset($addusertype) && $addusertype == 'Institution') { ?>
					
					<div class="wdthpercent100" id="show_institution_result">

						 <form id="addinstitutionusertype" name="addinstitutionusertype" method="post">
							
							<div style="wdthpercent100 pT10 pB10">
								<div id="" class="wdthpercent20 left">Enter User Type:</div>
								<div id="" class="wdthpercent50 left">
									<input placeholder="Enter the name" type="text" id="user_type" name="user_type" class="required" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/>
								</div>
							</div>	
							<div id="" class="clear pB10"></div>
							
							<div style="wdthpercent100 pT10 pB10">
								<div id="" class="wdthpercent20 left">Enter User Name</div>
								<div id="" class="wdthpercent50 left">
									<input placeholder="Enter the name" type="text" name="name" class="required" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/>
								</div>
							</div>	
							<div id="" class="clear pB10"></div>
							
							<div style="wdthpercent100 pT10 pB10">
								<div id="" class="wdthpercent20 left">Enter the Email</div>
								<div id="" class="wdthpercent50 left">
									<input placeholder="Enter the email" type="text" id="username" name="username" class="required email" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/>
								</div>
							</div>	
							<div id="" class="clear pB10"></div>

							<div style="wdthpercent100 pT10 pB10">
								<div id="" class="wdthpercent20 left">Enter the Password:</div>
								<div id="" class="wdthpercent50 left">
									<input placeholder="Enter the Password" type="password" name="password" class="required" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/>
								</div>
							</div>	
							<div id="" class="clear pB10"></div>

							<div style="wdthpercent100 pT10 pB10">
								<div id="" class="wdthpercent20 left">IP ranges:</div>
								<div id="" class="wdthpercent50 left">
									<input type="radio" name="range_type" id="by_user_range_type" class="required" value="User"/> Set by User&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="range_type" id="by_admin_range_type" class="required" value="admin"/> Set by Admin
								</div>
							</div>	
							<div id="" class="clear pB20"></div>

							<div id="show_ip_ranges_div" style="display:none;">
								<?php
								$ip_range = IP_RANGE;
								for($i=1;$i<=$ip_range;$i++) { ?>
									<div style="wdthpercent100 pT10 pB10">
										<div id="" class="wdthpercent20 left">Enter the IP Range&nbsp;<b><?php echo $i;?></b> :</div>
										<div id="" class="wdthpercent50 left">
											<div id="" class="wdthpercent100">
												<div id="" class="wdthpercent50 left">
													From : <input placeholder="Enter the IP Range&nbsp;<?php echo $i;?>" type="text" name="ip_range_from[<?php echo $i;?>]" id="ip_range_from" class="wdthpercent70 required" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/>
													<span class="font12 pT5 pB5">IP Format: 1-255.0-255.0-255.0-255 </span>						
												</div>
												<div id="" class="wdthpercent50 left">
													To: <input placeholder="Enter the IP Range&nbsp;<?php echo $i;?>" type="text" name="ip_range_to[<?php echo $i;?>]" id="ip_range_to" class="wdthpercent70 required" value="<?php if(isset($user_type)){ echo $user_type; } ?>"/><span class="font12 pT5 pB5">IP Format: 1-255.0-255.0-255.0-255 </span>							
												</div>										
											</div>
											<div id="" class="clear pB10"></div>
										</div>
									</div>	
									<div id="" class="clear pB10"></div>
								<?php } ?>
							</div>

							<div style="wdthpercent100">
								<div id="" class="wdthpercent20 left">&nbsp;</div>
								<div id="" class="wdthpercent70 left">
									<?php if(isset($typeDetail) && !empty($typeDetail)){ ?>
									<input type="hidden" value="<?php echo $typeid; ?>" name="typeid"/>
									<input type="submit" value="Submit" name="updateinstitutionusertype" class="submitbtn" >
									<?php } else { ?>
									<input type="submit" value="Submit" name="submitinstitutionusertype" class="submitbtn" >
									<?php } ?>
									<input onclick="javascript:window.history.go(-1)" type="button" value="Back" class="submitbtn">
								</div>
							</div>	
							<div id="" class="clear"></div>
						 </form>

					</div>

				<?php } ?>

			</fieldset>

		 </div>
		<!-- left side -->		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>


