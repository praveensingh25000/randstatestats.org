<?php
/******************************************
* @Created on Nov 22,2013
* @Package: RAND
* @Developer:Susanto Mahato
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

if(isset($_GET['show']) && $_GET['show']	    == 'active')  {
	$status  = 1;
} elseif(isset($_GET['show']) && $_GET['show']  == 'deactive') { 
	$status  = 2 ;
} else if(isset($_GET['show']) && $_GET['show'] == 'blocked')  { 
	$status  = 3; 
} else if(isset($_GET['show']) && $_GET['show'] == 'deleted')  { 
	$status  = 4; 
}  else {
	$_GET['show'] ='all';
	$status  = 5;
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
$usersAllSql   =	$admin->selectallTrialAcountUsersGlobal($status, $type, $dbUsercode, $searchstr);
$usersAll_obj  =	new PS_Pagination($db->conn, $usersAllSql, 5000, 5, $queryStr);
$users_all	   =	$usersAll_obj->paginate();
$total_users   =	$usersAll_obj->total_rows;


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
			<h3><b>Usage Login Statistics</b> :</h3><br />	
			<h3>List of<?php if(isset($_GET['show']) && $_GET['show']!='') echo '&nbsp;'.$_GET['show'].'&nbsp;'; ?><?php if(isset($type) && $type !='all') echo ucwords($type).'&nbsp;'; ?>Users <?php if(isset($total_users)) echo '('.$total_users.')'; ?><span class="right"><a href="javascript:window.history.go(-1)">Back</a></span></h3><br>

			<div class="tabnav left">		
				<div id="" class="wdthpercent50 left pL10 pT5">
					Show: 
					<a <?php if(isset($_GET['show']) && $_GET['show'] == 'all') { ?> class="active" <?php } ?> href="?<?php if(isset($type) && $type != '') { echo 'type='.$type.'&'; } ?>show=all">All</a>&nbsp;&nbsp;					
					<a <?php if(isset($_GET['show']) && $_GET['show'] == 'active') { ?> class="active" <?php } ?> href="?<?php if(isset($type) && $type != '') { echo 'type='.$type.'&'; } ?>show=active">Active</a>&nbsp;&nbsp;
					<a <?php if(isset($_GET['show']) && $_GET['show'] == 'deactive'){ ?> class="active" <?php } ?> href="?<?php if(isset($type) && $type != '') { echo 'type='.$type.'&'; } ?>show=deactive">Inactive</a>&nbsp;&nbsp;
					<a <?php if(isset($_GET['show']) && $_GET['show'] == 'blocked'){ ?> class="active" <?php } ?> href="?<?php if(isset($type) && $type != '') { echo 'type='.$type.'&'; } ?>show=blocked">Blocked</a>&nbsp;&nbsp;
					<a <?php if(isset($_GET['show']) && $_GET['show'] == 'deleted'){ ?> class="active" <?php } ?> href="?<?php if(isset($type) && $type != '') { echo 'type='.$type.'&'; } ?>show=deleted">Deleted</a>

					
				</div>

				<div id="" class="wdthpercent40 right pR10">
					<div class="wdthpercent20 left"><span class="listform">Search:</span></div>
					<div class="wdthpercent70 left">
						<form method="post" action="">
						<input type="text" placeholder="enter keyword" name="keyword" style="width: 189px;" class="left">			
						<input type="submit" value="Go" class="mL10 left" name="search">
					</div>							
				</div>
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
								<th bgcolor="#eeeeee">Organisation </th>
								<th colspan="3" bgcolor="#eeeeee">Action</th>							
							</tr>
						</thead>

						<tbody>
							<?php							
							while($userDetail = mysql_fetch_assoc($users_all)){
								$validity_on		=   $admin->selectIndividualDatabaseValidityAdmin($userDetail['user_db_id']);
								$userTypeDetail		= $admin->getUserType($userDetail['user_type']);	
								?>

								<tr id="remove_class selected_<?php echo $userDetail['id'];?>" class="remove_class selected_<?php echo $userDetail['id'];?>">
									<td align="middle">
										<input type="checkbox" class="ids" name="ids[]" value="<?php echo $userDetail['id'];?>"/>
									</td>

									<td align="left" class="">
										<?php echo ucwords($userDetail['name']); ?>
									</td>

									<td align="left" style="display:none;" class="dbname">
										<?php echo $userDetail['name'].' '.$userDetail['last_name']; ?>
									</td>

									<td align="left" class="">
										<?php if(!empty($userDetail['organisation'])) { 
											echo ucwords($userDetail['last_name']);
										} else {  
											echo '--'; 
										} ?>	
									</td>

									<td align="center">
										<?php if(!empty($userDetail['organisation'])) { ?>
											<?php echo stripslashes($userDetail['organisation']); ?> 
										<?php } else { ?> -- <?php } ?>								
									</td>

										
									 <td colspan="3" align="center">
										
										<label><a href="<?php echo URL_SITE; ?>/admin/loginStatistics.php?user_id=<?php echo base64_encode($userDetail['id']);?>">view</a></label> 
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
									<td bgcolor="#eeeeee" colspan="11">
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