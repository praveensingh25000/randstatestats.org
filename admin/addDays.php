<?php
/******************************************
* @Modified on 09 July 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$user = new user();


if(isset($_GET['id']) && $_GET['id']!=''){
	$userid				= trim(base64_decode($_GET['id']));
	$userDetail			= $user->getUser($userid);				
	$userTypeDetail		= $admin->getUserType($userDetail['user_type']);
}

if(isset($_POST['save'])){
	$action = $_POST['operation'];
	$numberofdays = $_POST['noofdays'];
	$admin->updateDatabaseUserValidityAll($userid, $numberofdays, $action);
	$_SESSION['msgsuccess'] = "Days operation performed successfully";
	header('location: addDays.php?id='.base64_encode($userid).'');
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

			<h2><?php echo $userDetail['organisation']; ?>'s Profile.<span class="right"><a href="javascript:window.history.go(-1)">Back</a></span></h2><br>

			<div class="tabnav mB10">		
				<div class="pL10 pT5" id="">
					Show: <a href="user.php?id=<?php echo base64_encode($userid); ?>&action=view" >Profile</a>&nbsp;&nbsp;
					<a href="user.php?id=<?php echo base64_encode($userid); ?>&action=edit" >Edit Profile</a>&nbsp;&nbsp;
					
					<a href="<?php echo URL_SITE; ?>/admin/ipRanges.php?id=<?php echo base64_encode($userid)?>">IPs</a>&nbsp;&nbsp;

					<a href="historical.php?id=<?php echo base64_encode($userid); ?>&status=1">Historical Payments</a>&nbsp;&nbsp;
					<a href="historical.php?id=<?php echo base64_encode($userid); ?>&status=0">Pending Payments</a>&nbsp;&nbsp;

					<?php if($userDetail['user_type'] == 6){ ?>
					<a href="user.php?id=<?php echo base64_encode($userid); ?>&action=view">Additional Users Login</a>&nbsp;&nbsp;
					<?php } ?>

		
					<a href="userPayment.php?id=<?php echo base64_encode($userid); ?>">Generate Invoice</a>&nbsp;&nbsp;
					<a href="addDays.php?id=<?php echo base64_encode($userid); ?>" class="active">Add Days</a>
				</div>
			</div>

					
				<form action="" id="registrationadminedit" name="registrationadminedit" method="post">
					<fieldset style="border: 1px solid #cccccc; padding: 10px;">
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Validity In Days</legend>
						<table cellspacing="0" cellpadding="7" border="1" class="data-table collapse" id="grid_view" width="100%">
						
							<tbody>
								<tr>
									<th><p class="pB5">Enter number of days<em>*</em></p></th>
									<td style="padding: 5px;">
										<input class="required" name="noofdays" type="text"/>
									</td>
								</tr>

								<tr>
									<th><p class="pB5">Operation<em>*</em></p></th>
									<td style="padding: 5px;">
										<select name="operation" class="required">
											<option value="add">Add</option>
											<option value="minus">Minus</option>
										</select>
									</td>
								</tr>

								<tr>
									<td style="padding: 5px;" >&nbsp;</td>
									<td style="padding: 5px;" >
										<input type="submit" name="save" value="Submit">
									</td>
								</tr>

							</tbody>
						</table>
					</fieldset>
				</form>
		</div>
		<!-- left side -->
	</div>		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>


