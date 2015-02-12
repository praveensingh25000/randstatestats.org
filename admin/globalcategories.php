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

$admin					= new admin();
$categoriesresult_res   = $admin->getAllParentCategories();
//$categoriesresult_res = $admin->showAllCategories();
$total_defalult         = $db->count_rows($categoriesresult_res);
$categories_default     = $db->getAll($categoriesresult_res);

if((isset($_POST['categoryid']) && isset($_POST['select_category_form']) && $_POST['categoryid']=='0') || (isset($_SESSION['categoryid']) && $_SESSION['categoryid'] =='0' )) {
	
	unset($_SESSION['categoryid']);
	header('location: globalcategories.php');

} else if((isset($_POST['categoryid']) && isset($_POST['select_category_form'])) || (isset($_SESSION['categoryid']) && $_SESSION['categoryid'] !='0' )) {
	
	if(isset($_POST['categoryid']) && $_POST['categoryid'] != '') {
		$parent_id			= $_SESSION['categoryid'] = trim($_POST['categoryid']);
	}else if(isset($_SESSION['categoryid']) && $_SESSION['categoryid'] != '') {
		$parent_id			= trim($_SESSION['categoryid']);
	}
	$categoriesResult		= $admin->selectAllCategory($parent_id);
	$total					= $db->count_rows($categoriesResult);
	$categories				= $db->getAll($categoriesResult);

}else {

	//$categoriesResult		= $admin->getAllParentCategories();
	$categoriesResult		= $admin->getAllParentCategories();
	$total					= $db->count_rows($categoriesResult);
	$categories				= $db->getAll($categoriesResult);
}

if(isset($_GET['action'])){
	$action = strtolower($_GET['action']);
	$id = trim($_GET['id']);
	switch($action){
		case 'delete':
				$return = $admin->deleteCategory($id);
				break;
		default:
	}

	header('location: globalcategories.php');
	exit;
}

if(isset($_POST['action'])){
	$action = strtolower($_POST['action']);
	$ids = implode(',', $_POST['ids']);
	switch($action){
		case 'active':
				$return = $admin->bulkActiveDeactive($ids, '1');
				break;
		case 'de-active':
				$return = $admin->bulkActiveDeactive($ids, '0');
				break;
		case 'feature':
				$return = $admin->bulkActiveFeature($ids, '1');
				break;
		case 'unfeature':
				$return = $admin->bulkActiveUnfeature($ids, '0');
				break;
		case 'delete':
				$return = $admin->deleteCategories($ids);
				break;
		default:
	}
	header('location: globalcategories.php');
	exit;
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
			
			<h2> List of Global Categories <?php if(isset($total)) echo '( '.$total.' )'; ?></h2><br>

			<!-- FORM TO SELECT DATABASE ACT TO CATEGORY -->
			<div id="" class="wdthpercent100 tabnav">
				<div class="wdthpercent60 left">&nbsp;</div>
				<div class="wdthpercent35 right listform" style="padding:0px;">
							
				</div>
			</div>
			<br class="clear" />
			<!-- /FORM TO SELECT DATABASE ACT TO CATEGORY -->

			<div id="" class="pT20">
				<?php if(!empty($categories)){ ?>
			
					<form id="frmAllCat" name="frmAllCat" method="post">
						<table cellspacing="0" cellpadding="7" border="1" class="collapse" id="grid_view" width="100%">
							<tbody>
								<tr>
									<th bgcolor="#eeeeee"><input type="checkbox" id="check_all" /></th>
									<th bgcolor="#eeeeee">Category Name</th>
									<th bgcolor="#eeeeee">Parent Category</th>
									<th bgcolor="#eeeeee">Is Feature</th>
									<th bgcolor="#eeeeee">Is Active</th>
									<th bgcolor="#eeeeee">Actions</th>
									
								</tr>
								<?php if($categories >0){ 
									foreach($categories as $key => $categoryDetail){
										//echo '<pre>';print_r($categoryDetail);echo '</pre>';
										$parentCat = "Global Category";
										if($categoryDetail['parent_id']!='0'){
											$parentCatDetail = $admin->getPatCategory($categoryDetail['parent_id']);
											$parentCat = $parentCatDetail['category_title'];
										}
										?>
										<tr class="remove_class selected_<?php echo $categoryDetail['id'];?>">
											<td align="middle"><input type="checkbox" class="ids" name="ids[]" value="<?php echo $categoryDetail['id'];?>"/></td>
											<td align="left"><?php echo $categoryDetail['category_title']; ?></td>
											<td align="center"><?php echo $parentCat; ?></td>
											<td align="center">
											<?php if($categoryDetail['is_feature']=='0'){ echo "No"; } else { echo "Yes"; } ?>
											</td>
											<td align="center">
												<?php if($categoryDetail['is_active'] == '0'){ echo "No"; } else { echo "Yes"; } ?>
											</td>
											<td align="center">
												
												<a href="category.php?action=edit&id=<?php echo base64_encode($categoryDetail['id']);?><?php if(isset($parentCat) && $parentCat == 'Global Category'){ ?>&parent=1<?php } ?>">Edit</a>&nbsp;|&nbsp;<a onclick="javascript: return confirm('Are you sure?');" href="?action=delete&id=<?php echo base64_encode($categoryDetail['id']);?><?php if(isset($parentCat) && $parentCat == 'Global Category') { ?>&parent=1<?php } ?>">Delete</a>
											</td>
											<script type="text/javascript">
												jQuery(document).ready(function(){
													jQuery(".selected_<?php echo $categoryDetail['id'];?>").hover(function () {
														jQuery(".remove_class").removeClass("tab");
														jQuery(".selected_<?php echo $categoryDetail['id'];?>").addClass("tab");
													});
													jQuery("body,.main-cell").hover(function () {
														jQuery(".remove_class").removeClass("tab");			
													});
												});
											</script>
										</tr>

								<?php } ?>
								
								<tr>
									<td colspan="5">
										<input type="submit" name="action" value="Active" onclick="javascript: return check('active');"/>&nbsp;
										<input type="submit" name="action" value="De-Active" onclick="javascript: return check('deactive');"/>&nbsp;
										<input type="submit" name="action" value="Feature" onclick="javascript: return check('feature');"/>&nbsp;
										<input type="submit" name="action" value="Unfeature" onclick="javascript: return check('unfeature');"/>&nbsp;
										<input type="submit" name="action" value="Delete" onclick="javascript: return check('delete');"/>
									<script type="text/javascript">

									jQuery(document).ready(function(){
										jQuery('#check_all').click(function () {
											jQuery('.ids').attr('checked', this.checked);
										});
									});
									function check(action){							
										var atLeastOneIsChecked = $('input[name="ids[]"]:checked').length > 0;
										if(action == "delete"){
											var confirmcheck = confirm("Are you sure you want to delete them");
											if(!confirmcheck){
												return false;
											}
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
								<?php }?>
								

							</tbody>
						</table>
					</form>

				<?php } else{ ?>
					<h4>No Sub Category added Yer</h4>
				<?php }?>

			</div>			
		 </div>
		<!-- left side -->		

	</div>
	
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>



