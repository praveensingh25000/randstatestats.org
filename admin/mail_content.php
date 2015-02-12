<?php
/******************************************
* @Modified on Sept 3, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";require_once $basedir."/classes/emailTemp.class.php";

checkSession(true);

$emailObj = new emailTemp();
$admin = new admin();

$result_res = $emailObj->showAllContents();
$total_defalult = $db->count_rows($result_res);
$contents = $db->getAll($result_res);
//print_r($categories_default);

if(isset($_GET['action'])){
	$action = strtolower($_GET['action']);
	$id = trim(base64_decode($_GET['id']));
	switch($action){
		case 'delete':
				$return = $emailObj->deleteTemp($id);
				break;
		default:
	}

	header('location: mail_content.php');
	exit;
}
if(isset($_POST['action'])){
	$action = strtolower($_POST['action']);
	$ids = implode(',', $_POST['ids']);
	switch($action){
		case 'delete':
				$return = $emailObj->deleteTemp($ids);
				break;
		default:
	}
	header('location: mail_content.php');
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
			
			<h3> List of Templates </h3>

			<!-- FORM TO SELECT DATABASE ACT TO CATEGORY -->
			<div>
					<?php 
					if(!empty($conte____nts)) {?>
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
			<?php if(!empty($contents)){ ?>

			<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
				<tbody>
					<tr>
						<th bgcolor="#eeeeee"><input type="checkbox" id="check_all" /></th>
						<th bgcolor="#eeeeee">Template Name</th>
						<th bgcolor="#eeeeee">Email Subject</th>
						<th bgcolor="#eeeeee">Email Body</th>
						<th bgcolor="#eeeeee">Actions</th>
						
					</tr>
					<?php if($contents >0){ 
						foreach($contents as $key => $contentDetail){
					?>
						<tr>
							<td align="middle"><input type="checkbox" class="ids" name="ids[]" value="<?php echo $contentDetail['id'];?>"/></td>
							<td align="left" class="newsTitle"><?php echo stripslashes($contentDetail['title']); ?></td>
							<td align="left" class="newsTitle"><?php echo stripslashes($contentDetail['subject']); ?></td>
							<td align="left"><?php 
						$substr = substr($contentDetail['body'], 0, 25);
					if(strlen($substr) > 24){ 
					echo stripslashes(strip_tags($substr))."...";} else { echo stripslashes($substr);} ?></td>
						
							<td align="left"><a href="viewContent.php?action=view&c_id=<?php echo base64_encode($contentDetail['id']);?>">View</a>&nbsp; <a href="edit_content.php?action=edit&id=<?php echo base64_encode($contentDetail['id']);?>">Edit</a>&nbsp;
							<a onclick="javascript: return confirm('Are you sure you want to delete it?');" href="mail_content.php?action=delete&id=<?php echo base64_encode($contentDetail['id']);?>">Delete</a>
							</td>
						</tr>

					<?php } ?>
					
					<tr id="no_result" style="display:none;" >
						<td></td>
						<td colspan="6">No results found</td>
					</tr>
					<tr id="actionDiv">
						<td colspan="6">
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
			<h4>No Contents added Yet.</h4>
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
