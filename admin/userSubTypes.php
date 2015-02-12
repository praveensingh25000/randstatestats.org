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
$userTypesResult = $admin->showAllUserSubTypes();

$total = $db->count_rows($userTypesResult );
$types = $db->getAll($userTypesResult);



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
			<?php if(isset($types) && $types >0){ ?>

			<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
				<tbody>
					<tr>
						<!-- <th bgcolor="#eeeeee"><input type="checkbox" id="check_all" /></th> -->
						<th bgcolor="#eeeeee">User Sub Type</th>
					<th bgcolor="#eeeeee">Actions</th>
						
					</tr>
					<?php if($types >0){ 
						foreach($types as $key => $typeDetail){
							
							
					?>
						<tr>
							<!-- <td align="middle"><input type="checkbox" class="ids" name="ids[]" value="<?php echo $planDetail['id'];?>"/></td> -->
							<td align="left"><?php echo $typeDetail['user_type']; ?></td>
							
								<td align="left">
							
							<a href="userSubType.php?action=edit&id=<?php echo base64_encode($typeDetail['id']);?>">Edit</a>&nbsp;
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



