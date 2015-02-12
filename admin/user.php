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

if(isset($_GET['action'])){
	$action_type	=	$_GET['action'];
} else {
	$action_type	=	'viewall';
}

if($action_type == "delete"){
	$parent_id = $_GET['id'];
	$id = trim(base64_decode($_GET['deleteid']));
	$user->deleteUserPermanent($id, $parent_id);
	header('location:user.php?action=view&id='.base64_encode($parentid).'');
	exit;
}

$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
if(!empty($siteMainDBDetail)) {
	$dbUsercode = $siteMainDBDetail['id']; 
} else { 
	$dbUsercode = 1; 
}

if(isset($action_type) && ($action_type == 'view' || $action_type == 'edit') && isset($_GET['id']) && $_GET['id']!=''){
	$userid				= trim(base64_decode($_GET['id']));
	$userDetail			= $user->getUser($userid);				
	$userTypeDetail		= $admin->getUserType($userDetail['user_type']);
	$puechased_account  = $admin->selectValidDatabaseofUser($userDetail['id']);	
	$typesResult		= $admin->showAllUserTypes($status=0);
	$dbUserDetail		= $admin->selectdatabaseUsers($dbUsercode,$userid);
	$usertypesAll		= $db->getAll($typesResult);
	$validity_on		= $admin->selectIndividualDatabaseValidityAdmin($dbUserDetail['id']);
}

if(isset($_POST['name'])) {

	$userid		=	trim($_POST['userid']);
	//echo '<pre>';print_r($_POST);echo '</pre>';die;
	$status		=	$user->updateUserProfileByID($_POST);
	
	$billingcontactarray	=	$_POST['billing'];
	$technicalcontactarray	=	$_POST['technical'];
	$admincontactarray		=	$_POST['admincontact'];

	$additionalinformation = $user->updateUserAdditionalFields($billingcontactarray, $admincontactarray, $technicalcontactarray, $userid);

	$_SESSION['msgsuccess']="User Detail has been updated successfully.";

	header('location:user.php?action=edit&id='.base64_encode($userid).'');
	exit;
}

if(isset($action_type) && ($action_type == 'view') && isset($_GET['id']) && $_GET['id']!=''){

	$userid				= trim(base64_decode($_GET['id']));

	$users = $admin->getMultipleUsers($userid);

	$totalAdditional = count($users);
}

$additionalFields = $user->getUserAdditionalFields($userid);
//echo '<pre>';print_r($additionalFields);echo '</pre>';

$b_firstname = $b_lastname = $b_title = $b_phone = $b_email = $t_firstname = $t_lastname = $t_title = $t_phone = $t_email = $a_firstname = $a_lastname	= $a_title = $a_phone = $a_email = $b_firstname	= $b_lastname = $b_title = $b_phone = $b_email = $t_firstname = $t_lastname	= $t_title = $t_phone	= $t_email = $a_firstname = $a_lastname	= $a_title	= $a_phone = $a_email = "";

if(!empty($additionalFields)){
	$b_firstname	= stripslashes($additionalFields['bill_contact']);
	$b_lastname		= stripslashes($additionalFields['bill_contact_lastname']);
	$b_title		= stripslashes($additionalFields['bill_title']);
	$b_phone		= stripslashes($additionalFields['bill_phone']);
	$b_email		= stripslashes($additionalFields['bill_email']);
	//$b_address	= stripslashes($additionalFields['b_address']);

	$t_firstname	= stripslashes($additionalFields['tech_contact']);
	$t_lastname		= stripslashes($additionalFields['tech_contact_lastname']);
	$t_title		= stripslashes($additionalFields['tech_title']);
	$t_phone		= stripslashes($additionalFields['tech_phone']);
	$t_email		= stripslashes($additionalFields['tech_email']);
	//$t_address	= stripslashes($additionalFields['t_address']);

	$a_firstname	= stripslashes($additionalFields['admin_contact']);
	$a_lastname		= stripslashes($additionalFields['admin_contact_lastname']);
	$a_title		= stripslashes($additionalFields['admin_title']);
	$a_phone		= stripslashes($additionalFields['admin_phone']);
	$a_email		= stripslashes($additionalFields['admin_email']);
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
					Show: <a href="user.php?id=<?php echo base64_encode($userid); ?>&action=view" <?php if($action_type!='edit'){ ?> class="active" <?php } ?>>Profile</a>&nbsp;&nbsp;
					<a href="user.php?id=<?php echo base64_encode($userid); ?>&action=edit" <?php if($action_type=='edit'){ ?> class="active" <?php } ?>>Edit Profile</a>&nbsp;&nbsp;
					
					<a href="<?php echo URL_SITE; ?>/admin/ipRanges.php?id=<?php echo base64_encode($userid)?>">IPs</a>&nbsp;&nbsp;

					<a href="historical.php?id=<?php echo base64_encode($userid); ?>&status=1">Historical Payments</a>&nbsp;&nbsp;
					<a href="historical.php?id=<?php echo base64_encode($userid); ?>&status=0">Pending Payments</a>&nbsp;&nbsp;

					<?php if($userDetail['user_type'] == 6){ ?>
					<a href="user.php?id=<?php echo base64_encode($userid); ?>&action=view">Additional Users Login</a>&nbsp;&nbsp;
					<?php } ?>
					<a href="userPayment.php?id=<?php echo base64_encode($userid); ?>">Generate Invoice</a>&nbsp;&nbsp;
					<a href="addDays.php?id=<?php echo base64_encode($userid); ?>">Add Days</a>
				</div>
			</div>

			<?php if(!empty($userDetail) && isset($action_type) && ($action_type == 'view')) { ?>
			
				<fieldset style="border: 1px solid #cccccc; padding: 10px;">
				
				<table border="" class="data-table"width="100%">
						<tbody>
							<?php if(!empty($userDetail['name'])) {?>
							<tr>
								<th width="20%">First Name </th>
								<td style="padding: 5px;">
									<?php echo $userDetail['name']; ?>
								</td>
							</tr>
							<tr>
								<th>Last Name </th>
								<td style="padding: 5px;">
									<?php echo $userDetail['last_name']; ?>
								</td>
							</tr>
							<?php } ?>

							<?php if(!empty($userDetail['username'])){?>
							<tr>
								<th>Username </th>
								<td style="padding: 5px;">
									<?php echo $userDetail['username']; ?>
								</td>
							</tr>
							<?php } ?>

							<?php if(!empty($userDetail['email'])){?>
							<tr>
								<th>Email </th>
								<td style="padding: 5px;">
									<a href="mailto:<?php echo $userDetail['email']; ?>"><?php echo $userDetail['email']; ?></a>
								</td>
							</tr>
							<?php } ?>

							<?php if(!empty($userDetail['phone'])){?>
							<tr>
								<th>Phone </th>
								<td style="padding: 5px;">
									<?php echo $userDetail['phone']; ?>
								</td>
							</tr>
							<?php } ?>

							<?php if(!empty($userDetail['address'])){?>
							<tr>
								<th>Address </th>
								<td style="padding: 5px;">
									<?php echo $userDetail['address']; ?>
								</td>
							</tr>
							<?php } ?>

							<?php if(!empty($userDetail['organisation'])){?>
							<tr>
								<th>Organisation </th>
								<td style="padding: 5px;">
									<?php echo $userDetail['organisation']; ?>
								</td>
							</tr>
							<?php } ?>

							<?php if(!empty($userDetail['organisation_address'])){?>
							<tr>
								<th>Organisation Address </th>
								<td style="padding: 5px;">
									<?php echo $userDetail['organisation_address']; ?>
								</td>
							</tr>
							<?php } ?>

							<?php if(!empty($userDetail['user_type'])){?>
							<tr>
								<th>User Type </th>
								<td style="padding: 5px;">
									<?php echo $userTypeDetail['user_type']; ?>
								</td>
							</tr>
							<?php } ?>

							<?php if(!empty($userDetail['number_of_users'])){?>
							<tr>
								<th>Number of users </th>
								<td style="padding: 5px;">
									<?php echo $userDetail['number_of_users']; ?>					
								</td>
							</tr>
							<?php } ?>

							<tr>
								<th>Account Type </th>
								<td style="padding: 5px;">
									<?php if(isset($dbUserDetail) & $dbUserDetail['is_trial']=='0') { echo "Trial Account"; } else { echo "Purchased Account"; } ?>
								</td>
							</tr>
							
							

							<?php if(!empty($userDetail['active_status'])){?>
							<tr>
								<th>Active Status </th>
								<td style="padding: 5px;">
									<?php if($userDetail['active_status']=='0'){ echo 'Inactive';}
									else
									{ echo 'Active';}?>
								</td>
							</tr>
							<?php } ?>

							<?php if(!empty($userDetail['block_status'])){?>
							<tr>
								<th>Block Status </th>
								<td style="padding: 5px;">
									<?php if($userDetail['block_status']=='0'){ echo 'Unblocked';}
									else
									{ echo 'Blocked';}?>
								</td>
							</tr>
							<?php } ?>

							<?php if(isset($validity_on)) {?>
							<tr>
								<th>Expiry Time: </th>
								<td style="padding: 5px;">
									<?php echo $validity_on;?> days Left.
								</td>
							</tr>
							<?php } ?>
						
						</tbody>
				</table>

				<br/>
				<h2>Additional Details</h2><br/>
				
				<?php if(!empty($additionalFields)){ ?>
				<table border="" class="data-table">

					<tr>
						<th>&nbsp;</th>
						<th><Strong>Billing Contact</Strong></th>
						<th><Strong>Techincal Contact</Strong></th>
						<th><Strong>Admin Contact</Strong></th>
						
					</tr>

					<tr>
						<th>First Name</th>
						<td style="padding: 5px;">
							<?php echo $b_firstname; ?>
						</td>
						<td style="padding: 5px;">
							<?php echo $t_firstname; ?>
						</td>
						<td style="padding: 5px;">
							<?php echo $a_firstname; ?>
						</td>
					</tr>
					<tr>
						<th >Last Name </th>
						<td style="padding: 5px;">
							<?php echo $b_lastname; ?>
						</td>
						<td style="padding: 5px;">
							<?php echo $t_lastname; ?>
						</td>
						<td style="padding: 5px;">
							<?php echo $a_lastname; ?>
						</td>
					</tr>
					<tr>
						<th>Title </th>
						<td style="padding: 5px;">
							<?php echo $b_title; ?>
						</td>
						<td style="padding: 5px;">
							<?php echo $t_title; ?>
						</td>
						<td style="padding: 5px;">
							<?php echo $a_title; ?>
						</td>
					</tr>
					<tr>
						<th >Phone </th>
						<td style="padding: 5px;">
							<?php echo $b_phone; ?>
						</td>
						<td style="padding: 5px;">
							<?php echo $t_phone; ?>
						</td>
						<td style="padding: 5px;">
							<?php echo $a_phone; ?>
						</td>
					</tr>
					<tr>
						<th width="20%">Email </th>
						<td style="padding: 5px;">
							<?php echo $b_email; ?>
						</td>
						<td style="padding: 5px;">
							<?php echo $t_email; ?>
						</td>
						<td style="padding: 5px;">
							<?php echo $a_email; ?>
						</td>
					</tr>
				</table>
				<?php } else { ?>	
				<p>No records found</p>
				<?php } ?>


								
				<?php if($totalAdditional >0) { ?>
				<br/>
				<h2>Additional Logins</h2><br>

				<div class="pT10">
					<table class="data-table">
						
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>										
						<th>Phone</th>	
						<th>Address</th>	
						<th>Organisation</th>
						<th>Date Added</th>
						<th>Action</th>	
					
						<?php
						if($totalAdditional >0) {
							foreach($users as $key => $userDetail) {
					
							?>
								<tr>								
									<td>
										<h4><?php echo ucwords($userDetail['name']);?></h4>
									</td>
									<td>
										<h4><?php echo ucwords($userDetail['last_name']);?></h4>
									</td>
									<td>
										<h4><?php echo $userDetail['email'];?></h4>
									</td>								
									<td><h4><?php echo $userDetail['phone'];?></h4></td>
									<td><h4><?php echo $userDetail['address'];?></h4></td>
									<td><h4><?php echo $userDetail['organisation'];?></h4></td>
									<td>
										<h4><?php echo $time=date('d M Y',strtotime($userDetail['join_date']));?></h4>
									</td>
									<td>
										<a href="user.php?action=delete&id=<?php echo $userid; ?>&deleteid=<?php echo base64_encode($userDetail['id']); ?>" onclick="javascript: return confirm('Are you sure you want to delete it?');">Delete</a>
									</td>								
								</tr>
							<?php }	?>										

							
						<?php } else { ?>
							<tr>
								<td colspan="7"><h4>No additional logins yet.</h4></td>
								
							<tr>

						<?php }	?>
					</table>
				</div>
				<?php } ?>


				</fieldset>

				<?php } ?>

				<?php if(!empty($userDetail) && isset($action_type) && ($action_type == 'edit')) { ?>
					
				<form action="" id="registrationadminedit" name="registrationadminedit" method="post">
					<fieldset style="border: 1px solid #cccccc; padding: 10px;">
						<table cellspacing="0" cellpadding="7" border="1" class="data-table collapse" id="grid_view" width="100%">
						
							<tbody>

								<tr>
									<th colspan="2"><p class="pB5">Profile</p></th>
									
								</tr>

								<tr>
									<th><p class="pB5">First Name<em>*</em></p></th>
									<td style="padding: 5px;">
										<input placeholder="Enter your first name" name="name" type="text" value="<?php if(isset($userDetail['name'])){ echo stripslashes($userDetail['name']); }?>" class="required" id="name" />
									</td>
								</tr>

								<tr>
									<th><p class="pB5">Last Name<em>*</em></p></th>
									<td style="padding: 5px;">
										<input placeholder="Enter your last name" name="last_name" type="text" value="<?php if(isset($userDetail['last_name'])){ echo stripslashes($userDetail['last_name']); }?>" class="required" id="last_name" />
									</td>
								</tr>

								<tr>
									<th><p class="pB5">Username</p></th>
									<td style="padding: 5px;">
										<input  placeholder="Enter your username" name="username" value="<?php if(isset($userDetail['username'])){ echo stripslashes($userDetail['username']); }?>" type="text" class="" id="username" />
										<input  type="hidden" value="<?php echo stripslashes($userDetail['username']); ?>" name="oldusername" id="oldusername" />
									</td>
								</tr>


								<tr>
									<th><p class="pB5">Email<em>*</em></p></th>
									<td style="padding: 5px;">
										<input  placeholder="Enter your email" name="email" value="<?php if(isset($userDetail['email'])){ echo stripslashes($userDetail['email']); }?>" type="text" class="email required" id="email" />
									</td>
									<input  type="hidden" value="<?php echo stripslashes($userDetail['email']); ?>" name="oldemail" id="oldemail" />
								</tr>

								<tr>
									<th><p class="pB5">Password</p></th>
									<td style="padding: 5px;">
										<input  placeholder="Enter your password" name="password" value="" type="password" />
										<span><em>(Leave it blank if you donot want to change it)</em></span>
									</td>
								</tr>

								<tr>
									<th><p class="pB5">Organization<!-- <em>*</em> --></p></th>
									<td style="padding: 5px;">
										<textarea placeholder="Enter your organisation address" name="organisation" class="" id="organisation" rows="1" cols="24" /><?php if(isset($userDetail['organisation'])){ echo stripslashes($userDetail['organisation']); }?></textarea>
									</td>
								</tr>
								
								<tr>
									<th><p class="pB5">Phone<em>*</em></p></th>
									<td style="padding: 5px;">
										<input placeholder="Enter your phone Number" name="phone" type="text" value="<?php if(isset($userDetail['phone'])){ echo stripslashes($userDetail['phone']); }?>" class="required" id="phone" onchange="chckphone('phone')"/>&nbsp;&nbsp;<em>eg:123-123-1234</em>
									</td>
								</tr>

								<tr>
									<th><p class="pB5">Type Of User<em>*</em></p></th>
									<td style="padding: 5px;">
										<?php if(!empty($usertypesAll)) { ?>
											
												<select <?php if(isset($dbUserDetail) & $dbUserDetail['is_trial']!='0'){?>disabled="true"<?php } ?> class="required" id="user_type" name="user_type" style="width:355px;">
												<option value=""> Select User Type </option>
												<?php foreach($usertypesAll as $userTypes) { ?>
													<option value="<?php echo $userTypes['id'];?>" <?php if(isset($userDetail['user_type']) && $userDetail['user_type'] == $userTypes['id']){ echo "selected='selected'"; } ?> ><?php echo ucwords(stripslashes($userTypes['user_type']));?></option>
												<?php } ?>							
											</select>											
										<?php } ?>
										
									</td>
								</tr>

								<tr>
									<th><p class="pB5">Number of users<em>*</em></p></th>
									<td style="padding: 5px;">
										<input <?php /*if(isset($dbUserDetail) & $dbUserDetail['is_trial']!='0'){echo 'disabled="true"';} */?> placeholder="Enter number of users" name="number_of_users" value="<?php if(isset($userDetail['number_of_users'])){ echo stripslashes($userDetail['number_of_users']); }?>" type="text" class="digits required" id="number_of_users" />
									</td>
								</tr>

								<tr>
									<th><p class="pB5">Address</p></th>
									<td style="padding: 5px;">
										<textarea rows="3" cols="24" placeholder="Enter your address" name="address" class="" id="address" /><?php if(isset($userDetail['address'])){ echo stripslashes($userDetail['address']); }?></textarea>
									</td>
								</tr>

								

								<!-- <tr>
									<th><p class="pB5">Organization Address</p></th>
									<td style="padding: 5px;">
										<textarea placeholder="Enter your organisation address" name="organisation_address" class="" id="organisation_address" rows="3" cols="24" /><?php if(isset($userDetail['organisation_address'])){ echo stripslashes($userDetail['organisation_address']); }?></textarea>
									</td>
								</tr>
 -->
								
								<tr>
									<td colspan="2"><p class="pB5"><b>Billing Contact</b></p></td>
								</tr>

								<tr>
									<th><p>First Name</p></th>
									<td><input placeholder="Enter billing first name" name="billing[b_firstname]" type="text" value="<?php if(isset($b_firstname)){ echo $b_firstname; }?>" class="" id="b_name" /></td>
								</tr>
								
						

								<tr>
									<th><p>Last Name</p></th>
									<td><input placeholder="Enter billing last name" name="billing[b_lastname]" type="text" value="<?php if(isset($b_lastname)){ echo $b_lastname; }?>" class=""  /></td>
								</tr>
							

								<tr>
									<th><p>Title</p></th>
									<td><input placeholder="Enter billing title" name="billing[b_title]" type="text" value="<?php if(isset($b_title)){ echo $b_title; }?>" class=""  /></td>
								</tr>
						

									
								<tr>
									<th><p>Phone</p></th>
									<td><input placeholder="Enter billing phone" name="billing[b_phone]" type="text" value="<?php if(isset($b_phone)){ echo $b_phone; }?>" class="" id="b_phone" onchange="javascript: return chckphone('b_phone');"/>&nbsp;&nbsp;<em>eg:123-123-1234</em></td>
								</tr>
					

								<tr>
									<th><p>Email</p></th>
									<td><input placeholder="Enter billing email" name="billing[b_email]" value="<?php if(isset($b_email)){ echo $b_email; }?>" type="text" class="email " /></td>							
								</tr>

								<tr>
									<td colspan="2"><p class="pB5"><b>Technical Contact</b></p></td>
								</tr>

								<tr>
									<th><p class="pB5">First Name</p></th>
									<td style="padding: 5px;">
										<input placeholder="Enter technical person first name" name="technical[t_firstname]" type="text" value="<?php if(isset($t_firstname)){ echo $t_firstname; }?>" class="inputrequireds" id="t_name" />
									</td>
								</tr>

								<tr>
									<th><p class="pB5">Last Name</p></th>
									<td style="padding: 5px;">
										<input placeholder="Enter technical person last name" name="technical[t_lastname]" type="text" value="<?php if(isset($t_lastname)){ echo $t_lastname; }?>" class=""  />
									</td>
								</tr>

								<tr>
									<th><p class="pB5">Title</p></th>
									<td style="padding: 5px;">
										<input placeholder="Enter technical person title" name="technical[t_title]" type="text" value="<?php if(isset($t_title)){ echo $t_title; }?>" class=""  />
									</td>
								</tr>

								<tr>
									<th><p>Phone</p></th>
									<td><input placeholder="Enter technical person phone" name="technical[t_phone]" type="text" value="<?php if(isset($t_phone)){ echo $t_phone; }?>" class=" inputrequireds"  id="t_phone" onchange="javascript: return chckphone('t_phone');"/>&nbsp;&nbsp;<em>eg:123-123-1234</em></td>
								</tr>

								<tr>
									<th><p>Email</p></th>
									<td><input placeholder="Enter technical person email" name="technical[t_email]" value="<?php if(isset($t_email)){ echo $t_email; }?>" type="text" class="email" /></td>
								</tr>

								<tr>
									<td colspan="2"><p class="pB5"><b>Admin Contact</b></p></td>
								</tr>

								<tr>
									<th><p>First Name</p></th>
									<td><input placeholder="Enter admin person first name" name="admincontact[a_firstname]" type="text" value="<?php if(isset($a_firstname)){ echo $a_firstname; }?>" class="inputrequireds" id="a_name" /></td>
								</tr>
								

								<tr>
									<th><p>Last Name</p></th>
									<td><input placeholder="Enter admin person last name" name="admincontact[a_lastname]" type="text" value="<?php if(isset($a_lastname)){ echo $a_lastname; }?>" class=""  /></td>
								</tr>
							

								<tr>
									<th><p>Title</p></th>
									<td><input placeholder="Enter admin person title" name="admincontact[a_title]" type="text" value="<?php if(isset($a_title)){ echo $a_title; }?>" class=""  /></td>
								</tr>
								

								
								<tr>
									<th><p>Phone</p></th>
									<td><input placeholder="Enter admin person phone" name="admincontact[a_phone]" type="text" value="<?php if(isset($a_phone)){ echo $a_phone; }?>" class="inputrequireds"   id="a_phone" onchange="javascript: return chckphone('a_phone');"/>&nbsp;&nbsp;<em>eg:123-123-1234</em></td>
								</tr>
								

								<tr>
									<th><p>Email</p></th>
									<td><input placeholder="Enter admin person email" name="admincontact[a_email]" value="<?php if(isset($a_email)){ echo $a_email; }?>" type="text" class="email inputrequireds" />	</td>						
								</tr>
								
								<tr>
									<th>&nbsp;</th>
									<td style="padding: 5px;">

										<input type="submit" value="Submit" name="usereditFrmUpdate" class="" />&nbsp;&nbsp;&nbsp;
										<input type="hidden" value="<?php if(isset($userDetail['id'])){ echo trim($userDetail['id']); }?>" name="userid">
										<input type="hidden" name="organisation_address" />
										<input onclick="javascript:window.history.go(-1)" type="button" value="Back" class="submitbtn">
									</td>
								</tr>

							</tbody>
						</table>

					</fieldset>
				</form>

				<?php } ?>
			</div>

		 </div>
		<!-- left side -->
		
	</tr>
		
</section>
<!-- /container -->

<?php
include_once $basedir."/include/adminFooter.php";
?>