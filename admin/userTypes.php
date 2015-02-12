<?php
/******************************************
* @Modified on Jan 02, 2013
* @Package: Rand
* @Developer: Mamta Sharma
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();
$userTypesResult = $admin->showAllUserTypes();

$total = $db->count_rows($userTypesResult );
$types = $db->getAll($userTypesResult);

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
				$return = $admin->deletePlans($ids);
				break;
		default:
	}
	header('location: userTypes.php');
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
						
			<h3>All User Types<span class="right font12"><a href="adduserType.php">Add User Type</a></span></h3><br>
			
			<form id="frmAllCat" name="frmAllCat" method="post">
			<?php if(isset($types) && $types >0){ ?>

			<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
				<tbody>
					<tr>
						<!-- <th bgcolor="#eeeeee"><input type="checkbox" id="check_all" /></th> -->
						<th bgcolor="#eeeeee">User Type</th>
						<!-- <th bgcolor="#eeeeee">Description</th>
						<th bgcolor="#eeeeee">Amount</th>
						<th bgcolor="#eeeeee">Validity</th>
						 -->
						<th bgcolor="#eeeeee">Actions</th>
						
					</tr>
					<?php if($types >0){ 
						foreach($types as $key => $typeDetail) { ?>
						<tr>
							<!-- <td align="middle"><input type="checkbox" class="ids" name="ids[]" value="<?php echo $planDetail['id'];?>"/></td> -->
							<td align="left"><?php echo $typeDetail['user_type']; ?></td>
							<!-- <td align="left"><?php echo $planDetail['description']; ?></td>
								<td align="left">$<?php echo $planDetail['amount']; ?></td>
								<td align="left"><?php echo $planDetail['validity']; ?>&nbsp;Days</td> -->
								<td align="left">
							<!-- <?php
							if($typeDetail['user_type']=='Institution') {?>
							<a href="userSubType.php?id=<?php echo base64_encode($typeDetail['id']);?>">Add User subtype</a>
							&nbsp;
							<a href="userSubTypes.php?id=<?php echo base64_encode($typeDetail['id']);?>">View Subtypes</a>
							<?php } ?>		 -->					
							<a href="userTypePrices.php?action=edit&id=<?php echo base64_encode($typeDetail['id']);?>">Edit</a>&nbsp;
							<!-- <a href="userType.php?id=<?php echo base64_encode($typeDetail['id']);?>">Delete</a> -->
							</td>
						</tr>

					<?php } ?>
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
<script>
 function delete1() 
			{
				if(confirm('Do you really want to delete this user type?'))
				{
				 
					return true;
				}
				else
				{
					return false;
				}
			}
</script>



