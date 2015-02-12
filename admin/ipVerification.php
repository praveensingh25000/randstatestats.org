<?php
/******************************************
* @Created on April 19 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();
$user  = new user();

$unverifiedIpUsers    = array();

$select_all_users_ips_new	=	$admin->select_all_users_ips($status=0);
$total_selectall_userips	=	count($select_all_users_ips_new);

$select_unverified_users_ips =	$admin->select_all_users_ips($status=1);
$total_unverfied_userips	 =	count($select_unverified_users_ips);
if(!empty($select_unverified_users_ips)) { 
	foreach($select_unverified_users_ips as $useridkey => $unverified){
		$unverifiedIpUsers[]=$useridkey;
	}
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
			<fieldset style="border: 1px solid #cccccc; padding: 10px;">
				
				<div id="" class="pT5">
					<h2>IP Requests<?php if(isset($total_unverfied_userips) && $total_unverfied_userips!='0') { echo '<span class="font18 error pL5">('.$total_unverfied_userips.')</span>';} ?><span class="right"><a href="javascript:window.history.go(-1)">Back</a></span></h2>
				</div>			    
			    <div class="clear"></div>
				
				<div class="tabnav">
					<div class="wdthpercent60 left">&nbsp;</div>
					<div class="wdthpercent10 left"><span class="listform">Search:</span></div>
					<div class="wdthpercent25 left">
						<input class="wdthpercent100" placeholder="enter organisation" type="text" id="searchContent" style="width: 189px;"/>							
					</div>							
				 </div>				
				<br class="clear" />

			    <!-- HISTORY OF ALL USER IP -->
				<div class="wdthpercent100 pT10 pL10">
					
					<table cellspacing="0" cellpadding="5" border="1" width="100%" id="grid_view" class="collapse">

						<tr class="txtcenter">
							<th bgcolor="#eeeeee">First Name</th>
							<th bgcolor="#eeeeee">Last Name</th>
							<th bgcolor="#eeeeee">Organisation </th>
							<th bgcolor="#eeeeee">Email</th>						
							<th bgcolor="#eeeeee">Action</th>							
						</tr>

						<?php
						if(isset($total_selectall_userips) && $total_selectall_userips == '0'){
							echo '<tr><td><h4>No IP Request for verification Yet.</h4></td></tr>';
						} else { 
							foreach($select_all_users_ips_new as $key => $ipsDetailsAll) {

								$select_all_users_ips_obj	 =	new PS_PaginationArray($ipsDetailsAll,100,5);
								$select_all_users_ipsArray   =	$select_all_users_ips_obj->paginate();
								$total_select_users_ipsArray =	count($select_all_users_ipsArray);

								foreach($select_all_users_ipsArray as $userid => $ipsDetails) {
									$userDetail		= $user->getUser($userid);	
									$userTypeDetail	= $admin->getUserType($userDetail['user_type']);	
									?>
									<tr>
										<td align="center" class="">
											<?php echo ucwords($userDetail['name']); ?>
										</td>

										<td align="center" style="display:none;" class="dbname">
											<?php echo stripslashes($userDetail['organisation']); ?>
										</td>

										<td align="center" class="">
											<?php echo ucwords($userDetail['last_name']); ?>
										</td>

										<td align="left">
											<a href="<?php echo URL_SITE;?>/admin/user.php?action=view&id=<?php echo base64_encode($userDetail['id']);?>"><?php echo stripslashes($userDetail['organisation']); ?></a>
										</td>

										<td align="left" ><?php echo $userDetail['email']; ?></td>		
										
										<td align="center">
											<a href="javascript:;" title="IP Status" class="clicktoverifyip_<?php echo $userid;?>">IP Status<?php if(!empty($unverifiedIpUsers) && in_array($userid,$unverifiedIpUsers,TRUE)) {echo $classBlink = '<span id="hide_new_link_'.$userid.'" class="red pL5">New</span>';} ?></a>
										</td>
									</tr>

									<?php if(count($ipsDetails) > 0) { ?>

										<tr id="show_ips_div_<?php echo $userid;?>"  style="display:none;">
											<td colspan="4">
												<table align="center" cellspacing="0" cellpadding="5" border="1" width="100%" class="collapse" id="grid_view">
						
													<tr class="txtcenter">
														<!-- <th bgcolor="#eeeeee">IP RANGE FROM</th> -->		
														<!-- <th bgcolor="#eeeeee">IP RANGE TO</th>	-->
														<th bgcolor="#eeeeee">IPs</th>	
														<th bgcolor="#eeeeee">Added On</th>
														<th bgcolor="#eeeeee">Action</th>				
													</tr>

													<?php foreach($ipsDetails as $ipkey => $ipAll) { ?>
															<tr>
																<!-- <td><?php echo $ipAll['ip_range_from'];?></td> -->
																<!-- <td><?php echo $ipAll['ip_range_to'];?></td> -->	
																<td><?php echo $ipAll['ips'];?></td>
																<td align="center"><?php echo $date=date('M d, Y',strtotime($ipAll['added_on']));?></td>
																<td align="center" class="show_selected_result_<?php echo $ipAll['id'];?>">
																	<?php if($ipAll['is_verified']== '0') { ?>
																		
																		<a onclick="javascript: ApproveDissaprove('<?php echo $userid;?>','<?php echo $ipAll['id'];?>','1');" href="javascript:;">Allow</a>&nbsp;|&nbsp; <a onclick="javascript: ApproveDissaprove('<?php echo $userid;?>','<?php echo $ipAll['id'];?>','2');" href="javascript:;">Dis-allow</a>

																	
																	<?php } else if($ipAll['is_verified']== '1') { ?>
																		
																		<a onclick="javascript: ApproveDissaprove('<?php echo $userid;?>','<?php echo $ipAll['id'];?>','2');" href="javascript:;">Dis-allow</a>

																	<?php } else if($ipAll['is_verified']== '2') { ?>
																		
																		<a onclick="javascript: ApproveDissaprove('<?php echo $userid;?>','<?php echo $ipAll['id'];?>','1');" href="javascript:;">Allow</a>

																	<?php } ?>
																</td>
															</tr>										
													 <?php } ?>
												</table>
												<script type="text/javascript">
													jQuery(document).ready(function(){
													jQuery('.clicktoverifyip_<?php echo $userid;?>').click(function () {
														jQuery('#show_ips_div_<?php echo $userid;?>').toggle("slow");						
													});
												});
												</script>

											</td>
										</tr>
									<?php } ?>	
								<?php } ?>
							<?php } ?>

							<tr style="display:none;" id='no_result'>								
								<td colspan="4"><h4>No records found.</h4></td>
							</tr>

							<tr>																	
								<td bgcolor="#eeeeee" colspan="8">
									<!-- Pagination ----------->                      
									<div class="txtcenter pR20 pagination">
										<?php echo $select_all_users_ips_obj->renderFullNav();  ?>
									</div>
									<!-- /Pagination -----------> </td>							
								<td>&nbsp; </td>
							</tr>
							
						<?php
						}
						?>
					</table>
				  </div>			
				  <div class="clear"></div>
				 <!-- HISTORY OF ALL USER IP -->
	
			</fieldset>

		 </div>
		<!-- left side -->		
	</div>
		
</section>
<!-- /container -->

<?php include_once $basedir."/include/adminFooter.php"; ?>