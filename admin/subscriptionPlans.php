<?php
/******************************************
* @Modified on Dec 25, 2012
* @Package: Rand
* @Developer: Mamta Sharma
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();
$plansResult = $admin->showAllPlans();
$total = $db->count_rows($plansResult);
$plans = $db->getAll($plansResult);


/*if(isset($_GET['action'])){
	$action = strtolower($_GET['action']);
	switch($action){
		case 'delete':
				$return = $admin->deletePlan($id);
				break;
		default:
	}
	header('location: subscriptionPlans.php');
	exit;
}*/

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
	header('location: subscriptionPlans.php');
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
			<?php if(isset($plans) && $plans >0){ ?>

			<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
				<tbody>
					<tr>
						<!-- <th bgcolor="#eeeeee"><input type="checkbox" id="check_all" /></th> -->
						<th bgcolor="#eeeeee">Plan Name</th>
						<th bgcolor="#eeeeee">Description</th>
						<th bgcolor="#eeeeee">Amount</th>
						<th bgcolor="#eeeeee">Validity</th>
						
						<th bgcolor="#eeeeee">Actions</th>
						
					</tr>
					<?php if($plans >0){ 
						foreach($plans as $key => $planDetail){
							
							
					?>
						<tr>
							<!-- <td align="middle"><input type="checkbox" class="ids" name="ids[]" value="<?php echo $planDetail['id'];?>"/></td> -->
							<td align="left"><?php echo $planDetail['plan_name']; ?></td>
							<td align="left"><?php echo $planDetail['description']; ?></td>
								<td align="left">$<?php echo $planDetail['amount']; ?></td>
								<td align="left"><?php echo $planDetail['validity']; ?>&nbsp;Days</td>
							
							
							<td align="left"><a href="subscriptionPlan.php?action=edit&id=<?php echo base64_encode($planDetail['id']);?>">Edit</a>&nbsp;
							<a href="subscriptionPlan.php?id=<?php echo base64_encode($planDetail['id']);?>">Delete</a>
							</td>
						</tr>

					<?php } ?>
					
					<!-- <tr>
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
						
					</tr> -->
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
				if(confirm('Do you really want to delete this plan?'))
				{
				 
					return true;
				}
				else
				{
					return false;
				}
			}
</script>



