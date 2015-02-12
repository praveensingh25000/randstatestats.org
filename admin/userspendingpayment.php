<?php
/******************************************
* @Modified on July 10,2013
* @Package: RAND
* @Developer:Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$user  = new user();
$admin = new admin();

$status = '';

$queryString = $queryStr= '';

$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
if(!empty($siteMainDBDetail)) {
	$dbUsercode = $siteMainDBDetail['id']; 
} else { 
	$dbUsercode = 1; 
}

$status = '';

$queryString = $queryStr= '';

$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
if(!empty($siteMainDBDetail)) {
	$dbUsercode = $siteMainDBDetail['id']; 
} else { 
	$dbUsercode = 1; 
}

if(isset($_GET['show']) && $_GET['show']	    == 'active')  {
	$status  = 1;
} elseif(isset($_GET['show']) && $_GET['show']  == 'deactive') { 
	$status  = 2 ;
} else if(isset($_GET['show']) && $_GET['show'] == 'blocked')  { 
	$status  = 3; 
} else if(isset($_GET['show']) && $_GET['show'] == 'deleted')  { 
	$status  = 4; 
}  else {
	$_GET['show'] ='active';
	$status  = 1;
}

if(isset($_GET['type']) && $_GET['type'] != '') {
	$type = trim($_GET['type']); 
} else { 
	$type = 'all';
}

if(isset($_GET) && $_GET != '') {	
	foreach($_GET as $key => $vals) {
		if($key!='page' && $key!= 'keyword')
		$queryString.= $key.'='.$vals.'&';
	}
	
}

$searchstr = '';

if(isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
	
	$searchstr = trim($_REQUEST['keyword']);
	$queryString .= "&keyword=".$searchstr."&";

} 


$queryStr = substr($queryString,0,-1);

$usersAllSql	=	$admin->getPendingPaymentUsers($dbUsercode);
$usersAll_obj	=	new PS_Pagination($db->conn, $usersAllSql, 20, 5, $queryStr);
$users_all		=	$usersAll_obj->paginate();
$total_users	=	$usersAll_obj->total_rows;

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
			
			<h3>List of <?php if(isset($_GET['show']) && $_GET['show']!='') echo '&nbsp;'.$_GET['show'].'&nbsp;'; ?><?php if(isset($type) && $type !='all') echo ucwords($type).'&nbsp;'; ?>Users <?php if(isset($total)) echo '('.$total.')'; ?><span class="right"><a href="javascript:window.history.go(-1)">Back</a></span></h3><br>

			<div class="tabnav left">		
				<div id="" class="wdthpercent50 left pL10 pT5">
					Show: <a href="users.php?<?php if(isset($type) && $type != '') { echo 'type='.$type.'&'; } ?>show=active">Active</a>&nbsp;&nbsp;
					<a  href="users.php?<?php if(isset($type) && $type != '') { echo 'type='.$type.'&'; } ?>show=deactive">Inactive</a>&nbsp;&nbsp;
					<a  href="users.php?<?php if(isset($type) && $type != '') { echo 'type='.$type.'&'; } ?>show=blocked">Blocked</a>&nbsp;&nbsp;
					<a  href="users.php?<?php if(isset($type) && $type != '') { echo 'type='.$type.'&'; } ?>show=deleted">Deleted</a>

					&nbsp;&nbsp;<a href="userspendingpayment.php" class="active">Pending Payment</a>

				</div>

				<!-- <div id="" class="wdthpercent40 right pR10">
					<div class="wdthpercent20 left"><span class="listform">Search:</span></div>
					<div class="wdthpercent70 left">
						<form method="post" action="users.php?type=all&show=active">
						<input type="text" placeholder="enter keyword" name="keyword" style="width: 189px;" class="left">			
						<input type="submit" value="Go" class="mL10 left" name="search">
					</div>							
				</div> -->
			</div>			
			<br class="clear" />
			
			<div id="" class="pT20">
				<?php if(isset($total_users) && $total_users>0) { ?>
			
				<form action="" id="frmAllCat" name="frmAllCat" method="post">	

					<table cellspacing="0" cellpadding="7" border="1" class="collapse" id="grid_view" width="100%">
					
					<!-- <table class="data-table">	 -->						

						<thead>
							<tr>
								<th bgcolor="#eeeeee"><input type="checkbox" id="check_all_users" /></th>
								<th bgcolor="#eeeeee">First Name</th>
								<th bgcolor="#eeeeee">Last Name</th>
								<th  bgcolor="#eeeeee">Organisation </th>
								<th bgcolor="#eeeeee">Email</th>
								<th bgcolor="#eeeeee">User Type</th>
								<!-- <th  bgcolor="#eeeeee">Account Type</th> -->
								<th bgcolor="#eeeeee">Is verified</th>
								<th bgcolor="#eeeeee">Is block</th>
								
			
								<th bgcolor="#eeeeee">Amount Due</th>					
							</tr>
						</thead>

						<tbody>
							<?php 
							while($userDetail = mysql_fetch_assoc($users_all)){
										
								$userTypeDetail		= $admin->getUserType($userDetail['user_type']);

					

								?>

								<tr id="remove_class selected_<?php echo $userDetail['id'];?>" class="remove_class selected_<?php echo $userDetail['id'];?>">
									<td align="middle">
										<input type="checkbox" class="ids" name="ids[]" value="<?php echo $userDetail['id'];?>"/></td>
									<td align="left" class="dbname">
										<?php echo ucwords($userDetail['name']); ?>
									</td>

									<td align="left" class="dbname">
										<?php echo ucwords($userDetail['last_name']); ?>
									</td>

									<td align="left">
										<a href="<?php echo URL_SITE;?>/admin/user.php?action=view&id=<?php echo base64_encode($userDetail['id']);?>"><?php echo ucwords($userDetail['organisation']); ?></a>
									</td>

									<td align="left" ><?php echo $userDetail['email']; ?></td>
									<td align="center">
										<?php if($userDetail['parent_user_id'] == '0') {
											echo stripslashes($userTypeDetail['user_type']);
										} else {
											echo 'Additional Multiple User';
										}
										?>
									</td>
									
									<td align="center">
										<?php if($userDetail['active_status'] == '0'){ echo "No"; } else { echo "Yes"; } ?>
									</td>
									<td align="center">
										<?php if($userDetail['block_status'] == '0'){ echo "No"; } else { echo "Yes"; } ?>
									</td>
									
									
									<td align="center">
										$<?php echo number_format($userDetail['amount'],2);?>
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
									<td colspan="10">

										
											<input type="submit" name="actionperform" value="Block" onclick="javascript: return checkUsers('Blocked');"/>&nbsp;
					
									
											<input type="submit" name="actionperform" value="Delete" onclick="javascript: return checkUsers('delete');"/>
									

								

											<input type="hidden" name="show" value="<?php echo $_GET['show'];?>">
									</td>						
								</tr>

								<tr>																	
									<td bgcolor="#eeeeee" colspan="10">
										<!-- Pagination ----------->                      
										<div class="txtcenter pR20 pagination">
											<?php echo $usersAll_obj->renderFullNav();  ?>
										</div>
										<!-- /Pagination -----------> </td>							
									
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