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

if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_GET['id']!=''){

	$typeid = trim(base64_decode($_GET['id']));
	$typeDetail = $admin->getUserType($typeid );
	if(!empty($typeDetail)){
		$user_type	 = stripslashes($typeDetail['user_type']);
		
	}
}

if(isset($_POST['addType'])){

	$user_type	 = (isset($_POST['user_type']))?trim(addslashes($_POST['user_type'])):'';
	$typeid = $admin->insertUserType($user_type);

	if(in_array('hasuser',$typeid)) {
		$user_type = $_SESSION['usertype']=stripslashes($_POST['user_type']);
		$_SESSION['msgalert']="User Type has been already exists.";
		header('location: adduserType.php');
		exit;
				
	} else {
		unset($_SESSION['usertype']);
		$_SESSION['msgsuccess']="User Type has been added successfully";
		header('location: userTypes.php');
		exit;
	}
	
}


if(isset($_POST['updateUserType'])){
	$user_type		= (isset($_POST['user_type']))?trim(addslashes($_POST['user_type'])):'';
	$typeid		= $_POST['typeid'];
	$return = $admin->updateUserType($user_type,$typeid);
	if($return <=0){
			$user_type	 = stripslashes($_POST['user_type']);
		
		
	} else {
		unset($_SESSION['type']);
		$_SESSION['msgsuccess']="User Type has been updated successfully";
		header('location: userTypes.php');
		exit;
	}
	
}

//updateCategory($catname, $parent, $description, $id)

$typesResult = $admin->showAllUserTypes();
$total = $db->count_rows($typesResult);
$types = $db->getAll($typesResult);


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
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($typeDetail) && !empty($typeDetail)){ echo "Edit"; } else { echo "Add"; } ?> User Type</legend>
				<form id="addUserType" name="addUserType" method="post">
				<p>User Type<em>*</em></p>
				<div style="padding: 10px 0;">
					<input type="text" id="name" name="user_type" class="required" value="<?php if(isset($_SESSION['usertype'])){ echo stripslashes(trim($_SESSION['usertype'])); } ?>"/>
				</div>
				
            <div class="submit1 submitbtn-div">
					<label for="submit" class="left">
						<?php if(isset($typeDetail) && !empty($typeDetail)){ ?>
						<input type="hidden" value="<?php echo $typeid; ?>" name="typeid"/>
						<input type="submit" value="Submit" name="updateUserType" class="submitbtn" >
						<?php } else { ?>
						<input type="submit" value="Submit" name="addType" class="submitbtn" >
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