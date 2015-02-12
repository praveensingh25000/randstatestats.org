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

$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_GET['del']) && $_GET['del']!='' && isset($_GET['id']) && $_GET['id']!=''){
	$dbid = trim(base64_decode($_GET['id']));	
	$labelId = trim(base64_decode($_GET['del']));
	$admin->deleteFormLabel($dbid, $labelId);
	header('location:'.URL_SITE.'/admin/formContent.php?tab=8&id='.base64_encode($dbid));
}

if(isset($_POST['label_submit'])){
	
	$dbid = $_POST['dbid'];

	if(isset($_POST['label_name'])){
		$variablename = $_POST['label_name'];
		$variablevalue = $_POST['label_value'];
		$returnInsert = $admin->insertFormLabelData($dbid, $variablename, $variablevalue);
		if($returnInsert>0){
			$_SESSION['msgsuccess'] = "Content added successfully";
		}

	}else {
		$_SESSION['msgerror'] = "Sorry there is no data to add";
	}

	header('location:'.URL_SITE.'/admin/formContent.php?tab=8&id='.base64_encode($dbid));
}

if(isset($_POST['updateColumnsSettings'])){
	
	$dbid = $_POST['dbid'];

	if(isset($_POST['variables'])){
		$set = '';
		foreach($_POST['variables'] as $variablename => $variablevalue){
			$returnUpdate = $admin->updateFormContentData($dbid, $variablename, $variablevalue);
		}
		$_SESSION['msgsuccess'] = "Content updated successfully";

	}else {
		$_SESSION['msgerror'] = "Sorry there is no data to be updated";
	}

	header('location:'.URL_SITE.'/admin/formContent.php?tab=8&id='.base64_encode($dbid));
}

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(stripslashes(base64_decode($_GET['id'])));
	$databaseDetail = $admin->getDatabase($dbid, true);
	if(!empty($databaseDetail)){
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description= stripslashes($databaseDetail['db_description']);
		$miscellaneous	= stripslashes($databaseDetail['db_misc']);
	}else{
		header('location: databases.php');
	}
}else{
	header('location: databases.php');
}


$formContentData = $admin->getFormContentData($dbid);

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
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Manage Form Content for '".$databaseDetail['db_name']."'"; } ?> </legend>
					
					<?php include("formNavigation.php"); ?>

					<a href="javascript:;" class="right" id="add_label">Add Label</a>
					<br class="clear" />
					<div id="add_label_div" style="display:none;">
						<form method="post" action="" name="addLabelForm">
							<table cellspacing="0" cellpadding="4" border="1" class="collapse" width="100%">
								<tbody>
									<tr>
										<th bgcolor="#eeeeee">Label Name</th>
										<th bgcolor="#eeeeee">Value</th>
									</tr>
									<tr>
										<td><input name="label_name" type="text" value="">
										<input name="dbid" type="hidden" value="<?php echo $dbid;?>"></td>
										<td><textarea name="label_value" type="text" value=""></textarea></td>
									</tr>
								</tbody>
							</table><br class="clear" />
							<div class="adminSubmitBtn">
								<label for="submit" >
									<input name="label_submit" type="submit" value="Add Label" class="submitbtn">
								</label>
								<label for="reset">
									<input type="button" id="cancel" class="submitbtn" value="cancel">
								</label>
							</div>
						</form>
						<br class="clear"/>
						<br class="clear"/>
					</div>

					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery('#add_label').click(function(){
							jQuery('#add_label_div').show();
						});
						jQuery('#cancel').click(function(){
							jQuery('#add_label_div').hide();
						});
					});
					</script>

					<form method="post" action="" id="frmTimeInterval">
					
						<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
							<tbody>
							<tr>
								<th bgcolor="#eeeeee">Label Name</th>
								<th bgcolor="#eeeeee">Value</th>
								<th bgcolor="#eeeeee">Action</th>
							</tr>

							<?php foreach($formContentData as $key => $contentData){ ?>

							<tr>
								<td valign="top"><?php echo $contentData['label_name']; ?></td>
								<td><textarea name="variables[<?php echo $contentData['label_name']; ?>]" rows = '3' cols="30"><?php if(isset($contentData['label_value'])){ echo stripslashes($contentData['label_value']); } ?></textarea></td>
								<td><a href="?del=<?php echo base64_encode($contentData['id']);?>&id=<?php echo base64_encode($dbid);?>" onclick="javascript: return confirm('Are you sure you want to delete?');">Delete</a></td>
							</tr>

							<?php } ?>

						</table>

						<div class="submit1 submitbtn-div">
							<label for="submit" class="left">
							<?php if(isset($databaseDetail) && !empty($databaseDetail)){ ?>
								<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
								<input type="submit" value="Submit" name="updateColumnsSettings" class="submitbtn" >
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


