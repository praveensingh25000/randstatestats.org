<?php
/******************************************
* @Modified on April 16,2013
* @Package: Rand
* @Developer:Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$user = new user();
$admin = new admin();

$active = $is_deleted = 0;

$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);

if(!empty($siteMainDBDetail)) {
	$dbUsercode = $siteMainDBDetail['id'];
} else {
	$dbUsercode = 1;
}

if(isset($_GET['show']) && $_GET['show'] == 'deactive')     { $active = 1;     } 
else if(isset($_GET['show']) && $_GET['show'] == 'deleted') { $is_deleted = 1; }  
else { $_GET['show']= 'active';}

if(isset($_GET['type']) && $_GET['type'] != '') { 
	$type = trim($_GET['type']);     
} else {
	$type = 'all';
}

$queryString = $queryStr= '';

if(isset($_GET) && $_GET != '') {	
	foreach($_GET as $key => $vals) {
		$queryString.= $key.'='.$vals.'&';
	}
	$queryStr = substr($queryString,0,-1);
}

$usersAll	   =	$user->showUsersGlobalFunction($active,$is_deleted,$type,$dbUsercode);
$total		   =	count($usersAll);
$usersAll_obj  =	new PS_PaginationArray($usersAll,3000,5,$queryStr);
$users		   =	$usersAll_obj->paginate();
$total_users   =	count($users);

if(isset($_POST['actionperform'])){

	$action		= strtolower($_POST['actionperform']);
	$ids		= implode(',', $_POST['ids']);
	$showurl	= trim($_POST['show']);
	
	$tablename='users';	

	if($action =='active'){
		$_SESSION['msgsuccess']="Selected User has been unblocked.";
		$status=0;  
	} else if($action =='in-active'){	
		$_SESSION['msgsuccess']="Selected User has been blocked.";
		$status=1;  
	} else if($action =='delete'){
		$_SESSION['msgsuccess']="Selected User has been deleted.";
		$status=1; 
	}

	$return = $admin->activedeactiveStatus($tablename, $ids, $action,$status);
	header('location: users.php?show='.$showurl.' ');
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
			
			<h3>List of <?php if(isset($type)) echo ucwords($type); ?> Users <?php if(isset($total)) echo '('.$total.')'; ?><span class="right"><a href="javascript:window.history.go(-1)">Back</a></span></h3><br>

			<div class="tabnav left">		
				<div id="" class="wdthpercent30 left pL10 pT5">
					Show: <a <?php if(isset($_GET['show']) && $_GET['show'] == 'active') { ?> class="active" <?php } ?> href="?show=active">Active</a>&nbsp;&nbsp;
					<a <?php if(isset($_GET['show']) && $_GET['show'] == 'deactive'){ ?> class="active" <?php } ?> href="?show=deactive">Inactive</a>&nbsp;&nbsp;
					<a <?php if(isset($_GET['show']) && $_GET['show'] == 'deleted'){ ?> class="active" <?php } ?> href="?show=deleted">Deleted</a>
				</div>

				<div id="" class="wdthpercent40 right pR10">
					<div class="wdthpercent20 left"><span class="listform">Search:</span></div>
					<div class="wdthpercent70 left">
						<input class="wdthpercent100" placeholder="enter name" type="text" id="searchContent" style="width: 189px;"/>			
					</div>							
				</div>
			</div>			
			<br class="clear" />

			<div id="" class="pT20">
				<?php if(isset($total_users) && $total_users > 0) { ?>
			
				<form action="" id="frmAllCat" name="frmAllCat" method="post">	

					<table cellspacing="0" cellpadding="7" border="1" class="collapse" id="grid_view" width="100%">
						<tbody>
							
							<?php if(isset($total) && $total > 6000) { ?>	
							
							<tr>																	
								<td bgcolor="#eeeeee" colspan="9">
									<!-- Pagination ----------->                      
									<div class="txtcenter pR20 pagination">
										<?php echo $usersAll_obj->renderFullNav();  ?>
									</div>
									<!-- /Pagination -----------> </td>							
								<td>&nbsp; </td>
							</tr>
							
							<tr>
								<td colspan="8">
									<div id="" class="wdthpercent60 left">
									<?php if(isset($_GET['show']) && $_GET['show'] == 'active'){ ?>

										<input type="submit" name="actionperform" value="In-active" onclick="javascript: return checkUsers('deactive');"/>&nbsp;
										<input type="submit" name="actionperform" value="Delete" onclick="javascript: return checkUsers('delete');"/>

									<?php } ?>

									<?php if(isset($_GET['show']) && $_GET['show'] == 'deactive'){ ?>
										
										<input type="submit" name="actionperform" value="Active" onclick="javascript: return checkUsers('active');"/>&nbsp;
										<input type="submit" name="actionperform" value="Delete" onclick="javascript: return checkUsers('delete');"/>

									<?php } ?>

									<?php if(isset($_GET['show']) && $_GET['show'] == 'deleted'){ ?>
									
										<input type="submit" name="actionperform" value="Active" onclick="javascript: return checkUsers('active');"/>&nbsp;
										<input type="submit" name="actionperform" value="In-active" onclick="javascript: return checkUsers('deactive');"/>

									<?php } ?>
									</div>							
									 
								</td>						
							</tr>
							<?php } ?>

							<tr>
								<th bgcolor="#eeeeee"><input type="checkbox" id="check_all_users" /></th>
								<th bgcolor="#eeeeee">User Name</th>
								<th bgcolor="#eeeeee">Email</th>
								<th bgcolor="#eeeeee">User Type</th>
								<!-- <th bgcolor="#eeeeee">Account Type</th> -->
								<th bgcolor="#eeeeee">Is verified</th>
								<th bgcolor="#eeeeee">Is block</th>
								<th bgcolor="#eeeeee">Validity(In days Left)</th>
								<th colspan="3" bgcolor="#eeeeee">Actions</th>
								
							</tr>
							<?php foreach($users as $key => $userDetail){

								$validity_on		= $admin->Validity($userDetail['id'],$userDetail['email']);					
								$userTypeDetail		= $admin->getUserType($userDetail['user_type']);
								$puechased_account  = $admin->selectValidDatabaseofUser($userDetail['id']);
								?>
								<tr class="remove_class selected_<?php echo $userDetail['id'];?>">
									<td align="middle">
										<input type="checkbox" class="ids" name="ids[]" value="<?php echo $userDetail['id'];?>"/></td>
									<td align="left" class="dbname">
										<a href="<?php echo URL_SITE;?>/admin/user.php?action=view&id=<?php echo base64_encode($userDetail['id']);?>"><?php echo ucwords($userDetail['name']); ?></a>
									</td>
									<td align="left" ><?php echo $userDetail['email']; ?></td>
									<td align="center">
										<?php echo stripslashes($userTypeDetail['user_type']);?>
									</td>
									<!-- <td align="center">
										<?php if(empty($puechased_account)) { echo "Trial Account"; } else { echo "Purchased Account"; } ?>
									</td> -->
									<td align="center">
										<?php if($userDetail['active_status'] == '0'){ echo "No"; } else { echo "Yes"; } ?>
									</td>
									<td align="center">
										<?php if($userDetail['block_status'] == '0'){ echo "No"; } else { echo "Yes"; } ?>
									</td>
									<td align="center">
										<div id="show_days_<?php echo $userDetail['id'];?>">
											<?php if(isset($validity_on) && $validity_on>0){ echo $validity_on;} else { echo '0';}?>
										</div>
									</td>					
									<td colspan="3" align="center">
										<a href="<?php echo URL_SITE;?>/admin/user.php?action=edit&id=<?php echo base64_encode($userDetail['id']);?>">Edit</a>&nbsp;|&nbsp;
										<a href="<?php echo URL_SITE;?>/admin/user.php?action=view&id=<?php echo base64_encode($userDetail['id']);?>">View</a>
										<?php if(empty($puechased_account) && isset($_GET['show']) && $_GET['show'] == 'active'){ ?> 
										&nbsp;|&nbsp;<a onclick="javascript: addValidityofUsers('<?php echo $userDetail['id'];?>')" id="add-days-div-<?php echo $userDetail['id'];?>" href="javascript:;">Add days</a>
										<?php } ?> 
										&nbsp;|&nbsp;<a href="<?php echo URL_SITE; ?>/admin/ipRanges.php?id=<?php echo base64_encode($userDetail['id'])?>">IPs</a>
									</td>
									<script type="text/javascript">
										jQuery(document).ready(function(){
											jQuery(".selected_<?php echo $userDetail['id'];?>").hover(function () {
												jQuery(".remove_class").removeClass("tab");
												jQuery(".selected_<?php echo $userDetail['id'];?>").addClass("tab");
											});
											jQuery("body,.main-cell").hover(function () {
												jQuery(".remove_class").removeClass("tab");			
											});
										});
									</script>
								</tr>

							<?php } ?>
							
							<tr>
								<td colspan="8">
									<?php if(isset($_GET['show']) && $_GET['show'] == 'active'){ ?>

										<input type="submit" name="actionperform" value="In-active" onclick="javascript: return checkUsers('deactive');"/>&nbsp;
										<input type="submit" name="actionperform" value="Delete" onclick="javascript: return checkUsers('delete');"/>

									<?php } ?>

									<?php if(isset($_GET['show']) && $_GET['show'] == 'deactive'){ ?>
										
										<input type="submit" name="actionperform" value="Active" onclick="javascript: return checkUsers('active');"/>&nbsp;
										<input type="submit" name="actionperform" value="Delete" onclick="javascript: return checkUsers('delete');"/>

									<?php } ?>

									<?php if(isset($_GET['show']) && $_GET['show'] == 'deleted'){ ?>
									
										<input type="submit" name="actionperform" value="Active" onclick="javascript: return checkUsers('active');"/>&nbsp;
										<input type="submit" name="actionperform" value="In-active" onclick="javascript: return checkUsers('deactive');"/>

									<?php } ?>
									<input type="hidden" name="show" value="<?php echo $_GET['show'];?>">
												
								</td>						
							</tr>

							<tr>																	
								<td bgcolor="#eeeeee" colspan="9">
									<!-- Pagination ----------->                      
									<div class="txtcenter pR20 pagination">
										<?php echo $usersAll_obj->renderFullNav();  ?>
									</div>
									<!-- /Pagination -----------> </td>							
								<td>&nbsp; </td>
							</tr>
							
						</tbody>
					</table>

				</form>

				<?php } else { ?>
					<h4>No users Yet.</h4>				
				<?php } ?>

			</div>

			<br class="clear">
			<div id="show_days_forms" style=""></div>

		 </div>
		<!-- left side -->	
	</div>		
</section>
<!-- /container -->

<?php include_once $basedir."/include/adminFooter.php"; ?>