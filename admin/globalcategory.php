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

$catname = $parentcat = $description = '';

if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_GET['id']!=''){

	$catid  = trim(base64_decode($_GET['id']));

	if(isset($_GET['parent']) && $_GET['parent'] !=''){
		$categoryDetail = $admin->getPatCategory($catid);
	} else {
		$categoryDetail = $admin->getCategory($catid);
	}

	if(!empty($categoryDetail)){
		$catname	 = stripslashes($categoryDetail['category_title']);
		$parentcat	 = $categoryDetail['parent_id'];
		$description = stripslashes($categoryDetail['description']);
	}
}

if(isset($_POST['addcat'])){

	$catname	 = (isset($_POST['catname']))?trim(addslashes($_POST['catname'])):'';
	$parentcat	 = (isset($_POST['parent_cat']))?trim($_POST['parent_cat']):0;
	$description = (isset($_POST['description']))?trim(addslashes($_POST['description'])):'';
	
	//$catid = $admin->insertCategory($catname, $parentcat, $description);
	$catid = $admin->insertintoCategory($catname, $parentcat, $description);

	if($parentcat == '0'){
		$_SESSION['categoryid'] = 'parent';		
	} else {
		if(isset($_SESSION['categoryid'])) { unset($_SESSION['categoryid']);}				
	}

	$_SESSION['msgsuccess'] = "Category has been added successfully.";
	header('location: categories.php');
	exit;	
}

if(isset($_POST['updatecat'])){

	$catname		= (isset($_POST['catname']))?trim(addslashes($_POST['catname'])):'';
	$parentcat	= (isset($_POST['parent_cat']))?trim($_POST['parent_cat']):0;
	$description	= (isset($_POST['description']))?trim(addslashes($_POST['description'])):'';
	$catid			= $_POST['catid'];

	//$return = $admin->updateCategory($catname, $parentcat, $description, $catid);
	$return = $admin->updateintoCategory($catname, $parentcat, $description, $catid);

	$_SESSION['msgsuccess'] = "Category has been updated successfully.";
	header('location: categories.php');
	exit;
}

$categoriesResult = $admin->getAllParentCategories();
//$categoriesResult = $admin->showAllParentCategories();
$total			  = $db->count_rows($categoriesResult);
$categories       = $db->getAll($categoriesResult);
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
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($categoryDetail) && !empty($categoryDetail)){ echo "Edit"; } else { echo "Add"; } ?> Category</legend>
				<form id="frmAllCat" name="frmAllCat" method="post">
				<p>Category Name<em>*</em></p>
				<div style="padding: 10px 0;">
					<input placeholder="enter category name" type="text" id="catname" name="catname" class="required" value="<?php if(isset($catname)){ echo $catname; } ?>"/>
				</div>
				
				<?php if(($total>0 && isset($categoryDetail['parent_id']) && $categoryDetail['parent_id'] != 0) || ($total>0 && !isset($categoryDetail))){ ?>
					<p>Parent Category</p>
					<div style="padding: 10px 0;">
						<select class="" name="parent_cat">
							<option value="0">--- Choose One ---</option>
							<?php foreach($categories as $key => $catDetail){ ?>
							<option value="<?php echo $catDetail['id']; ?>" <?php if(isset($parentcat) && $parentcat == $catDetail['id']){ echo "selected=selected"; } ?>><?php echo $catDetail['category_title']; ?></option>
							<?php } ?>
						</select>
					</div>
					<small>For adding Parent Category.please don't select any category.</small>
				<?php } else { ?>
					<input placeholder="enter Parent category name" type="hidden"  name="parent_cat"  value="0"/>
				<?php } ?>

				<p>&nbsp;</p>
				<p>Description</p>
				<div style="padding: 10px 0;">
					<textarea placeholder="enter category description" name="description" rows = '3' cols="30"><?php if(isset($description)){ echo $description; } ?></textarea>
				</div>

				<div class="submit1 submitbtn-div">
					<label for="submit" class="left">
						<?php if(isset($categoryDetail) && !empty($categoryDetail)){ ?>
						<input type="hidden" value="<?php echo $catid; ?>" name="catid"/>
						<input type="submit" value="Submit" name="updatecat" class="submitbtn" >
						<?php } else { ?>
						<input type="submit" value="Submit" name="addcat" class="submitbtn" >
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


