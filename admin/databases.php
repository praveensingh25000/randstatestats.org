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

$categoriesResult = $admin->getAllParentCategories();
$total = $db->count_rows($categoriesResult);
$categories = $db->getAll($categoriesResult);
$active = 1;

if(isset($_GET['show']) && $_GET['show'] == 'deactive'){
	$active = 0;
}

if(isset($_REQUEST['category_id'])) {
	$category_id	= $_SESSION['category_id'] = trim($_REQUEST['category_id']);
} else if(isset($_SESSION['category_id'])) {
	$category_id    = $_SESSION['category_id'];
} else {
	$category_id = 0;
}

$databases		= $admin->globalFunctionSelectionDatabases($category_id, $active);
$total			= count($databases);

if(isset($_GET['action'])){
	$action = strtolower($_GET['action']);
	switch($action){
		case 'delete':
				//$return = $admin->deleteDatabase($id);
				break;
		default:
	}
	header('location: databases.php');
	exit;
}

if(isset($_POST['action'])){
	
	$action = strtolower($_POST['action']);
	$ids = implode(',', $_POST['ids']);
	
	switch($action){
		case 'active':
				$return = $admin->bulkActiveDeactiveDatabase($ids, '1');
				break;
		case 'in-active':
				$return = $admin->bulkActiveDeactiveDatabase($ids, '0');
				break;
		case 'delete':
				//$return = $admin->deleteDatabases($ids);
				break;
		default:
	}
	header('location: databases.php');
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
		<h2>List of Forms <?php if(isset($total)) echo '('.$total.')'; ?></h2><br>
			
			<!-- FORM TO SELECT DATABASE ACT TO CATEGORY -->
			<div class="wdthpercent100 tabnav">
				<div class="left wdthpercent30">
					<p class="listform">
						<span class="">Show:</span> 
						<a href="databases.php" <?php if(!isset($_REQUEST['show'])){ echo 'class="active"';} ?>>Active</a>&nbsp;&nbsp;
						<a href="?show=deactive" <?php if(isset($_REQUEST['show'])){ echo 'class="active"';} ?>>Inactive</a>
					</p>
				</div>

				<div class="wdthpercent70 left">
					
					<div class="wdthpercent100 left">
						
						<div id="" class="wdthpercent60 <?php if(isset($total) && $total >0){ echo 'left';} else { echo 'right'; }?>">

							<div class="wdthpercent20 left"><span class="listform">Category:</span></div>
							<div class="wdthpercent60 left">
								<?php 
								if($categories >0) { ?>
									<form name="frmtoselectDatabaseActtoCategory" action = "" method="post" id="frmtoselectDatabaseActtoCategory">
										<select class="" id="category_id" name="category_id">
											<option value="0"> Select all category </option>	
											<?php foreach($categories as $key => $categoryDetail) { ?>	
												<option <?php if(isset($_SESSION['category_id']) && $_SESSION['category_id'] == $categoryDetail['id']) {?> selected="selected" <?php } ?> value="<?php echo $categoryDetail['id'];?>"> <?php echo ucwords($categoryDetail['category_title']);?>&nbsp;<?php if(isset($total) && isset($_SESSION['category_id']) && $_SESSION['category_id'] == $categoryDetail['id']) echo '( '.$total.' )'; ?> </option>	
											<?php } ?>
										</select>
										<input type="hidden" value="1" name="select_category_form">
									</form>

									<script type="text/javascript">
										jQuery(document).ready(function(){
											jQuery('#category_id').change(function(){
												//var values = jQuery('#category_id').val();
												//if(values != '0')
												jQuery('#frmtoselectDatabaseActtoCategory').submit();			
											});
										});
									</script>
								<?php } ?>
							</div>	
						
						 </div>

						 <?php if(isset($total) && $total >0) { ?>
						 
						 <div id="" class="wdthpercent40 left">
							<div class="wdthpercent25 left"><span class="listform">Search:</span></div>
							<div class="wdthpercent65 left">
								<input class="wdthpercent100" placeholder="enter keyword" type="text" id="searchContent" style="width: 189px;"/>							
							</div>							
						 </div>

						<?php } ?>
						
					</div>				
				</div>
				<br class="clear" />
			</div>
			<!-- /FORM TO SELECT DATABASE ACT TO CATEGORY -->

			<!-- Form List -->
			<div id="" class="pT20">
	
				<?php if(!empty($databases) && count($databases) > 0) { ?>

					<form id="frmAllCat" name="frmAllCat" method="post">

						<table cellspacing="0" cellpadding="6" border="1" class="collapse" id="grid_view" width="100%">
							
							<tbody>
								
								<tr>
									<th bgcolor="#eeeeee"><input type="checkbox" id="check_all" /></th>
									<th bgcolor="#eeeeee">Form Name</th>
									<th bgcolor="#eeeeee">Sub Category Name</th>
									<th bgcolor="#eeeeee">Level of Automation</th>
									<th bgcolor="#eeeeee">Is Active</th>
									<th bgcolor="#eeeeee">Actions</th>
									
								</tr>

								<?php foreach($databases as $key => $databaseDetail) {							
										$subparentCatDetail = $admin->getDatabaseCategorySub($databaseDetail['id']);				
										?>
										
										<tr class="remove_class_all selected_all_<?php echo $databaseDetail['id'];?>">
											<td align="middle">
												<input type="checkbox" class="ids" name="ids[]" value="<?php echo $databaseDetail['id'];?>"/>
											</td>
											<td align="left" class="dbname">
												<a href="database.php?action=edit&id=<?php echo base64_encode($databaseDetail['id']);?>"><?php echo ucfirst(stripslashes($databaseDetail['db_name'])); ?></a>
											</td>

											<td align="left" class="staticform">
												<?php if(!empty($subparentCatDetail)) { echo ucwords($subparentCatDetail['category_title']); } else { echo '-';} ?>
											</td>

											<td align="center" class="staticform">
												<?php if($databaseDetail['is_static_form'] == 'Y') { echo "Partial"; } else { echo "Complete"; } ?>
											</td>
											<td align="center">
												<span class=""><?php if($databaseDetail['is_active'] == '0') { echo "No"; } else { echo "Yes"; } ?></span>
											</td>
											<td align="center">
												<a href="database.php?action=edit&id=<?php echo base64_encode($databaseDetail['id']);?>">Edit</a>&nbsp;|&nbsp;<a href="browse.php?tab=4&id=<?php echo base64_encode($databaseDetail['id']);?>">View</a>
												<?php if($databaseDetail['is_static_form'] != "Y") { ?>
												<!-- &nbsp;|&nbsp;<a href="databases.php?action=delete&id=<?php echo base64_encode($databaseDetail['id']);?>">Delete</a> --> 
												<?php } ?>
												
											</td>
											<script type="text/javascript">
												jQuery(document).ready(function(){
													jQuery(".selected_all_<?php echo $databaseDetail['id'];?>").hover(function () {
														jQuery(".remove_class_all").removeClass("tab");
														jQuery(".selected_all_<?php echo $databaseDetail['id'];?>").addClass("tab");
													});
													jQuery("body,.main-cell").hover(function () {
														jQuery(".remove_class_all").removeClass("tab");			
													});
												});
											</script>
										</tr>
										<?php } ?>
								
										<tr style="display:none;" id='no_result'>
											<td></td>
											<td colspan="6"><h4>No records found.</h4></td>
										</tr>
									
										<tr>
											<td colspan="6">
												<input type="submit" name="action" value="Active" onclick="javascript: return check('active');"/>&nbsp;<input type="submit" name="action" value="In-Active" onclick="javascript: return check('deactive');"/>&nbsp;<input type="submit" name="action" value="Delete" onclick="javascript: return check('delete');"/>
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

								</tbody>
						</table>
					</form>	

				<?php } else { ?>
					<div class="left pT10 pB10"><h4>No Form added Yet</h4></div>
				<?php } ?>							
			</div>
			<!-- /Form List -->			

		 </div>
		<!-- left side -->
		
	 </div>
	 
</section>
<!-- /container -->

<?php include_once $basedir."/include/adminFooter.php"; ?>