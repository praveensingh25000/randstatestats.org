<?php
/******************************************
* @Modified on Mar 06, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();
$newsresult_res = $admin->showAllNews();
$total_defalult = $db->count_rows($newsresult_res);
$categories_default = $db->getAll($newsresult_res);
//print_r($categories_default);

$categoriesResult = $admin->showAllNews();
	$total = $db->count_rows($categoriesResult);
	$categories = $db->getAll($categoriesResult);

if(isset($_GET['action'])){
	$action = strtolower($_GET['action']);
	$id = trim($_GET['id']);
	switch($action){
		case 'delete':
				$return = $admin->deleteCategory($id);
				break;
		default:
	}

	header('location: news1.php');
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
		case 'delete':
				$return = $admin->deleteCategories($ids);
				break;
		default:
	}
	header('location: news1.php');
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
			
			<h3> List of News </h3>

			<!-- FORM TO SELECT DATABASE ACT TO CATEGORY -->
			<div class="right wdthpercent50 pB10">
				<div class="wdthpercent30 left"> Select News </div>
				<div class="wdthpercent70 left">
					<?php 
					if(!empty($categories_default)) {?>
						<form name="frmtoselectDatabaseActtoCategory" action = "" method="post" id="frmtoselectDatabaseActtoCategory">
							<select class="wdthpercent98" id="newsid" name="newsid">
								<option value="0"> Select all News </option>	
								<?php foreach($categories_default as $key => $newsDetail){	?>
																
									<option <?php if(isset($_SESSION['newsid']) && $_SESSION['newsid'] == $newsDetail['id']) {?> selected="selected" <?php } ?> value="<?php echo $newsDetail['id'];?>"> <?php echo ucwords($newsDetail['news_title']);?>&nbsp;<?php if(isset($total) && isset($_SESSION['newsid']) && $_SESSION['newsid'] == $newsDetail['id']) echo '('.$total.')'; ?></option>	
									<?php } ?>
								<?php } ?>
							</select>
							<input type="hidden" name="select_category_form">
						</form>
						<script type="text/javascript">
							jQuery(document).ready(function(){
								jQuery('#newsid').change(function(){					
									jQuery('#frmtoselectDatabaseActtoCategory').submit();			
								});
							});
						</script>
				</div>				
			</div>
			<br class="clear" />
			<!-- /FORM TO SELECT DATABASE ACT TO CATEGORY -->

			<form id="frmAllCat" name="frmAllCat" method="post">
			<?php if(!empty($categories)){ ?>

			<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
				<tbody>
					<tr>
						<th bgcolor="#eeeeee"><input type="checkbox" id="check_all" /></th>
						<th bgcolor="#eeeeee">News title</th>
						<th bgcolor="#eeeeee">Description</th>
						<th bgcolor="#eeeeee">Is Active</th>
						<th bgcolor="#eeeeee">Actions</th>
						
					</tr>
					<?php if($categories >0){ 
						foreach($categories as $key => $newsDetail){
					?>
						<tr>
							<td align="middle"><input type="checkbox" class="ids" name="ids[]" value="<?php echo $newsDetail['id'];?>"/></td>
							<td align="left"><?php echo $newsDetail['news_title']; ?></td>
							<td align="left"><?php echo $newsDetail['description'];; ?></td>
							<td align="left">
								<?php if($newsDetail['is_active'] == '0'){ echo "No"; } else { echo "Yes"; } ?>
							</td>
							<td align="left"><a href="category.php?action=edit&id=<?php echo base64_encode($newsDetail['id']);?>">Edit</a>&nbsp;
							<a onclick="javascript: return confirm('Are you sure?');" href="?action=delete&id=<?php echo base64_encode($newsDetail['id']);?>">Delete</a>
							</td>
						</tr>

					<?php } ?>
					
					<tr>
						<td colspan="5"><input type="submit" name="action" value="Active" onclick="javascript: return check('active');"/>&nbsp;<input type="submit" name="action" value="De-Active" onclick="javascript: return check('deactive');"/>&nbsp;<input type="submit" name="action" value="Delete" onclick="javascript: return check('delete');"/>
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
			<?php } else{ ?>
			<h4>No News added Yer</h4>
			<?php }?>

			</form>
		 </div>
		<!-- left side -->

		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>
