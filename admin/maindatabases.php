<?php
/******************************************
* @Modified on Jan 16, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();
$categoriesResult = $admin->showAllCategories();
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

	header('location: categories.php');
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
	header('location: categories.php');
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
			<form id="frmAllCat" name="frmAllCat" method="post">
			<?php if(isset($categories) && $categories >0){ ?>

			<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
				<tbody>
					<tr>
						<th bgcolor="#eeeeee"><input type="checkbox" id="check_all" /></th>
						<th bgcolor="#eeeeee">Category Name</th>
						<th bgcolor="#eeeeee">Parent Category</th>
						<th bgcolor="#eeeeee">Is Active</th>
						<th bgcolor="#eeeeee">Actions</th>
						
					</tr>
					<?php if($categories >0){ 
						foreach($categories as $key => $categoryDetail){
							$parentCat = "-";
							if($categoryDetail['parent_id']!='0'){
								$parentCatDetail = $admin->getCategory($categoryDetail['parent_id']);
								$parentCat = $parentCatDetail['category_title'];
							}
					?>
						<tr>
							<td align="middle"><input type="checkbox" class="ids" name="ids[]" value="<?php echo $categoryDetail['id'];?>"/></td>
							<td align="left"><?php echo $categoryDetail['category_title']; ?></td>
							<td align="left"><?php echo $parentCat; ?></td>
							<td align="left">
								<?php if($categoryDetail['is_active'] == '0'){ echo "No"; } else { echo "Yes"; } ?>
							</td>
							<td align="left"><a href="category.php?action=edit&id=<?php echo base64_encode($categoryDetail['id']);?>">Edit</a>&nbsp;
							<a onclick="javascript: return confirm('Are you sure?');" href="?action=delete&id=<?php echo base64_encode($categoryDetail['id']);?>">Delete</a>
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
			<?php } ?>

			</form>
		 </div>
		<!-- left side -->

		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>



