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

$contactResources = array();

if(isset($_POST['action'])){
	$action = strtolower($_POST['action']);
	$ids = implode(',', $_POST['ids']);
	$dbid = $_POST['dbid'];
	switch($action){
		case 'active':
				$return = $admin->changeStatusBulkContact($ids, '1');
				break;
		case 'in-active':
				$return = $admin->changeStatusBulkContact($ids, '0');
				break;
		case 'retired':
				$return = $admin->changeStatusBulkContact($ids, '2');
				break;
		case 'delete':
				$return = $admin->deleteBulkContacts($ids);
				break;
		default:
	}
	header('location: contactResources.php?tab=8&id='.base64_encode($dbid).'');
	exit;
}

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(stripslashes(base64_decode($_GET['id'])));
	$databaseDetail = $admin->getDatabase($dbid, true);
	if(!empty($databaseDetail)){
		$dbname		= stripslashes($databaseDetail['db_name']);
		$contactResources = $admin->getContactResources($dbid);
	}else{
		header('location: databases.php');
	}
}else{
	header('location: databases.php');
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
			<div style="clear: both; padding: 15px 0;">
				<fieldset style="border: 1px solid #cccccc; padding: 10px;">
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Time Interval Settings for '".stripslashes($databaseDetail['db_name'])."'"; } ?> </legend>
					

					<?php include("formNavigation.php"); ?>


					<form method="post" action="" id="frmTimeInterval">
					

						<div class="pB10">
							<label class="pB10" style="display:block"><b><a href="contactResource.php?id=<?php echo base64_encode($dbid); ?>">Add Contact Resource</a></b></label>
							
						</div>

						<?php if(count($contactResources)>0){ ?>
						<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
							<tr>
								<th bgcolor="#eeeeee"><input type="checkbox" id="check_all" /></th>
								<th bgcolor="#eeeeee">Name</th>
								<th bgcolor="#eeeeee">Organisation</th>
								<th bgcolor="#eeeeee">Email</th>
								<th bgcolor="#eeeeee">Phone</th>
								<th bgcolor="#eeeeee">Address</th>
								<th bgcolor="#eeeeee">Remarks</th>
								<th bgcolor="#eeeeee">Status</th>
								<th bgcolor="#eeeeee">Actions</th>
							</tr>
							<?php foreach($contactResources as $key => $contactResourceDetail){ ?>

								<tr>
									<td align="middle"><input type="checkbox" class="ids" name="ids[]" value="<?php echo $contactResourceDetail['cid'];?>"/></td>
									<td><?php echo stripslashes($contactResourceDetail['name']); ?></td>
									<td><?php echo stripslashes($contactResourceDetail['organisation']); ?></td>
									<td><?php echo stripslashes($contactResourceDetail['email']); ?></td>
									<td><?php echo stripslashes($contactResourceDetail['phnno']); ?></td>
									<td><?php echo stripslashes($contactResourceDetail['address']); ?></td>
									<td><?php echo stripslashes($contactResourceDetail['remarks']); ?></td>
									<td>
									<?php 
									if($contactResourceDetail['status'] == '1'){
										echo "Active";
									} else if($contactResourceDetail['status'] == '2'){
										echo "Retired";
									} else {
										echo "Inactive";
									}; 
									
									?></td>
									<td><a href="contactResource.php?action=edit&cid=<?php echo $contactResourceDetail['cid']; ?>&id=<?php echo base64_encode($dbid); ?>">Edit</a></td>
									
								</tr>
	
							<?php } ?>

							<tr>
								<td colspan="9"><input type="submit" name="action" value="Active" onclick="javascript: return check('active', 'ids');"/>&nbsp;<input type="submit" name="action" value="In-Active" onclick="javascript: return check('deactive', 'ids');"/>&nbsp;<input type="submit" name="action" value="Retired" onclick="javascript: return check('retired', 'ids');"/>&nbsp;<input type="submit" name="action" value="Delete" onclick="javascript: return check('delete', 'ids');"/>

								<?php if(isset($databaseDetail) && !empty($databaseDetail)){ ?>
								<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
							
								<?php } ?>

								<script type="text/javascript">

								jQuery(document).ready(function(){
									jQuery('#check_all').click(function () {
										jQuery('.ids').attr('checked', this.checked);
									});
								});							

								
								</script>
							</td>
								
							</tr>

						</table>
						<?php } ?>

						
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


