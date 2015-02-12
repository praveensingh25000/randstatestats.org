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
	$id = trim(base64_decode($_GET['id']));
	switch($action){
		case 'delete':
				$return = $admin->deleteNews($id);
				break;
		default:
	}

	header('location: news.php');
	exit;
}
if(isset($_POST['action'])){
	$action = strtolower($_POST['action']);
	$ids = implode(',', $_POST['ids']);
	switch($action){
		case 'active':
				$return = $admin->bulkActiveDeactiveNews($ids, 'Y');
				break;
		case 'de-active':
				$return = $admin->bulkActiveDeactiveNews($ids, 'N');
				break;
		case 'delete':
				$return = $admin->deleteNews($ids);
				break;
		default:
	}
	header('location: news.php');
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
			<div>
					<?php 
					if(!empty($categories_default)) {?>
						<div>
							<div class="wdthpercent10 left"><label> Search:</label></div>
							<div class="wdthpercent50 left">
								<input type="text" id="searchContent" style="width: 189px;"/>
								<br/>
								<script type="text/javascript">
								jQuery(document).ready(function(){
									jQuery('#searchContent').keyup(function() { 
										if(jQuery(this).val() == ''){
											jQuery("#grid_view td.newsTitle").parent().show();
											jQuery("#no_result").hide();
											jQuery('#actionDiv').show();
										} else {
											var value=jQuery("#grid_view td.newsTitle:contains('" + jQuery(this).val() + "')").html();
										
											if(typeof(value) =='undefined'){
												jQuery("#grid_view td.newsTitle:not(:contains('" + jQuery(this).val() + "'))").parent().hide();
												jQuery('#no_result').show();
												jQuery('#actionDiv').hide();
											}else{
												jQuery("#grid_view td.newsTitle:contains('" + jQuery(this).val() + "')").parent().show();
												jQuery("#grid_view td.newsTitle:not(:contains('" + jQuery(this).val() + "'))").parent().hide();
												jQuery('#no_result').hide();
												jQuery('#actionDiv').show();
											}
										}
									});
								});
								</script>
							</div>
						</div>
						<?php } ?>
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
						<th bgcolor="#eeeeee">Date</th>
						<th bgcolor="#eeeeee">Actions</th>
						
					</tr>
					<?php if($categories >0){ 
						foreach($categories as $key => $newsDetail){
					?>
						<tr>
							<td align="middle"><input type="checkbox" class="ids" name="ids[]" value="<?php echo $newsDetail['id'];?>"/></td>
							<td align="left" class="newsTitle"><?php echo stripslashes($newsDetail['news_title']); ?></td>
							<td align="left"><?php 
						$substr = substr($newsDetail['description'], 0, 25);
					if(strlen($substr) > 24){ 
					echo stripslashes($substr)."...";} else { echo stripslashes($substr);} ?></td>
							<td align="left">
								<?php if($newsDetail['is_active'] == 'N'){ echo "No"; } else { echo "Yes"; } ?>
							</td>
							<td align="left"><?php echo date('F j, Y', strtotime($newsDetail['date_added']));?></td>
							<td align="left"><a href="view_news.php?action=view&n_id=<?php echo base64_encode($newsDetail['id']);?>">View</a>&nbsp; <a href="add_news.php?action=edit&id=<?php echo base64_encode($newsDetail['id']);?>">Edit</a>&nbsp;
							<a onclick="javascript: return confirm('Are you sure you want to delete it?');" href="news.php?action=delete&id=<?php echo base64_encode($newsDetail['id']);?>">Delete</a>
							</td>
						</tr>

					<?php } ?>
					
					<tr id="no_result" style="display:none;" >
						<td></td>
						<td colspan="6">No results found</td>
					</tr>
					<tr id="actionDiv">
						<td colspan="6"><input type="submit" name="action" value="Active" onclick="javascript: return check('active');"/>&nbsp;<input type="submit" name="action" value="De-Active" onclick="javascript: return check('deactive');"/>&nbsp;<input type="submit" name="action" value="Delete" onclick="javascript: return check('delete');"/>
						<script type="text/javascript">

						jQuery(document).ready(function(){
							jQuery('#check_all').click(function () {
								jQuery('.ids').attr('checked', this.checked);
							});
						});
						function check(action){							
							var atLeastOneIsChecked = $('input[name="ids[]"]:checked').length > 0;
							if(action == "delete"){
								var confirmcheck = confirm("Are you sure you want to delete them all?");
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
