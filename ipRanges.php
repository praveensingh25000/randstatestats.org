<?php
/******************************************
* @Created on June 29, 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

$toatl_ip_allowed = IP_RANGE;

checkSession(false);

$admin = new admin();
$user  = new user();

$action= '';

$instution_id = 5;
$userIPDetail = array();

if((!isset($_REQUEST['id']) || $_REQUEST['id']=='')) {
	header('location:index.php');
}

$user_id	  = base64_decode($_REQUEST['id']);

if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit') {
	$action	      = $_REQUEST['action'];
}

if(isset($_REQUEST['action']) && $_REQUEST['action']=='delete') {	
	$deleteid   = base64_decode($_REQUEST['ipid']);
	$userid     = base64_decode($_REQUEST['userid']);
	$deleteip	= $admin->deleteUserIPAdress($deleteid);	
	$_SESSION['successmsg'] = 'Your selected IP has been deleted successfully';
	header('location:ipRanges.php?action=edit&id='.base64_encode($userid).'');
	exit;
}

if((isset($user_id) && isset($_SESSION['user']['id']) && $user_id == $_SESSION['user']['id']) || $_SESSION['user']['parent_user_id']==$user_id) {
	$userid				=	base64_decode($_REQUEST['id']);
	$userDetail			=	$user->getUser($userid);
	$userIPDetail_res	=	$admin->selectUserIPAdressAll($userid);
	$totaluserIPDetail  =   $db->count_rows($userIPDetail_res);
	$userIPDetail		=   $db->getAll($userIPDetail_res);
	$instution_id       =   $userDetail['user_type'];
} else {
	header('location:index.php');
}
?>
<!-- Container -->
<section id="container">
	<div id="mainshell">
		<?php if(isset($action) && $action =='') { ?>
		<div class="profile" style="width:1001px;">

			<h2>
				Enter IPs allowed on your users<?php if(!empty($userIPDetail)) { ?> 
				<span class="font12 right"><a href="?action=edit&id=<?php echo base64_encode($userid)?>">Manage IPs</a></span><?php } else { ?>
				<span style="display:none;" class="editlink font12 right pR10"><a href="?action=edit&id=<?php echo base64_encode($userid)?>">Manage IPs</a></span>
				<?php } ?>  
			</h2><br />

			<div class="profile-outer wdthpercent100">
				  <form action="" id="addiprangesbyuserform" name="addiprangesbyuserform" method="post">

				    <div id="display_add_success_message" style="display:none;"></div>			  

					<div style="wdthpercent100 pT10 pB10">
							<div id="" class="wdthpercent20 left">Enter the IP address </div>
							<div id="" class="wdthpercent80 left">

								<span class="pT5 pB5">
									<textarea rows="5" placeholder="Enter the IPs" type="text" name="ips"  id="ips" class="wdthpercent90 required" value=""/></textarea>
								</span><br>

								<span class="pT5 pB5 left">
									<span>
										IP Formats: <br>
										xx.xxx.*.* <br class="pB5" />
										xx.xxx.*.* <br class="pB5" />
										xx.xxx.*.* <br class="pB5" />
										xx.xxx/xx <br class="pB5" />
										xx.xx.xxx.xx <br class="pB5" />
										x.x.x.x-x.x.xxx.xxx <br class="pB5" />
										xx.xxx.xx.x-xx.xxx.xxx.xxx <br class="pB5" />
										xx.xx.xxx.xx/x <br class="pB5" />
										xx.xx.xxx.xx/xx <br class="pB5" />
										xx.xx.xxx.xx/xxx.xxx.xxx.xxx <br class="pB5" />
										xx.xx.xxx.xx/xxx.xxx.xxx.* <br class="pB5" />
										xx.xx.xxx.xx/xx <br class="pB5" />
										xxx.xxx.x.x/xx  <br class="pB5" />
										xxx.x.x.x-xxx.x.x.x<br class="pB5" /> 
									</span> <br />
									<span>For only IP ranges,use dash seperator. e.g. xxx.xxx.xxx.xxxx-xxx.xxx.xxx.xxxx ,....</span> <br />
									<span>For only IPs,use comma seperator. e.g. xxx.xxx.xxx.xxxx,xxx.xxx.xxx.xxxx,....</span> <br />
									<span>For both Type,use dash as well as comma seperator. e.g. xxx.xxx.xxx.xxxx-xxx.xxx.xxx.xxxx,xxx.xxx.xxx.xxxx,....</span>
								</span><br />

								<span class="pT5 pB5 left">
									<label style="display:none;" for="ip_range_from<?php echo $i;?>" generated="true" class="pL40 error">This field is required.</label>
								</span><br>
								
							</div>
					</div>						
					
					<div id="" class="clear pB10"></div>

					<div style="wdthpercent100">
						<div id="" class="wdthpercent50 left">&nbsp;</div>
						<div id="" class="wdthpercent50 left">						
							<input type="submit" value="Submit" name="submitiprangesbyuser" class="submitbtn" >&nbsp;&nbsp;&nbsp;
							<input type="reset" value="Reset" name="" class="submitbtn">&nbsp;&nbsp;
							<input onclick="javascript:window.history.go(-1)" type="button" value="Back" class="submitbtn">
							<input type="hidden" name="user_id" value="<?php echo $_SESSION['user']['id']; ?>">
							<input type="hidden" name="instution_id" value="<?php echo $instution_id;?>">
							<input type="hidden" name="is_verified" value="0">
						</div>
					</div>	
					<div id="" class="clear"></div>
					
				 </form>
			 </div>
		</div>

		<?php } ?>

		<?php if(isset($action) && $action =='edit') { ?>

		<div class="profile" style="width:1001px;">

			<h2>Edit Your IP Ranges <span class="font12 right"><a href="<?php echo URL_SITE; ?>/ipRanges.php?id=<?php echo $_REQUEST['id'];?>">Add-IPs</a>&nbsp;&nbsp;&nbsp;<a href="javascript:window.history.go(-1);">Back</a></span></h2><br />
			
			<div class="profile-outer wdthpercent100">
				<?php if(empty($userIPDetail)) { ?>
					<h4>No IPs added Yet.</h4>
					<br class="clear pB20" />
				<?php } else { 
				
					$i=1;
					foreach($userIPDetail as $key => $ipDetails) { ?>
						<form action="" id="editiprangesbyuserform_<?php echo $ipDetails['id'];?>" name="editiprangesbyuserform_<?php echo $ipDetails['id'];?>" method="post">
							<div id="show_saved_result_<?php echo $ipDetails['id'];?>" class="wdthpercent100 pT10 pB10">
								<div  class="wdthpercent20 left">
									IP address&nbsp;<b><?php echo $i;?></b> :
									<?php if($ipDetails['is_verified']== '0') { ?> <h4 style="color:red;">Pending</h4> <?php } else if($ipDetails['is_verified']== '1'){?> <h4 style="color:green;">Verified</h4> <?php } else { ?> <h4 style="color:blue;">Unverified</h4> <?php } ?>							
								</div>
								<div id="" class="wdthpercent80 left">
									<div id="" class="wdthpercent100">
										<div id="" class="wdthpercent70 left">
											<input placeholder="Enter the IP<?php echo $i;?>" type="text" name="ips" id="ips" class="wdthpercent50 required" value="<?php if(isset($ipDetails)){ echo $ipDetails['ips']; } ?>"/><span class="show_already_ip_div_<?php echo $ipDetails['id'];?>"></span><br>
											<span class="pT5 pB5">IP Format: 1-255.0-255.0-255.0-255 </span>
											<input type="hidden" name="is_verified" value="0">
										</div>
										
										<div id="" class="wdthpercent20 left">
											<a id="submit_ip_range_form_<?php echo $ipDetails['id'];?>" href="javascript:;">Click to Save</a>&nbsp;&nbsp;&nbsp;&nbsp;
											<a onclick="javascript:return confirm('Do you really want to delete this IP ? Click OK to confirm and cancel to return.');" href="?action=delete&ipid=<?php echo base64_encode($ipDetails['id']);?>&userid=<?php echo base64_encode($ipDetails['user_id']);?>" href="javascript:;">Delete</a>
										</div>
									</div>
									<div id="" class="clear pB10"></div>
								</div>
							</div>	
							<div id="" class="clear pB10"></div>
							
							<script type="text/javascript">
							jQuery(document).ready(function(){

								jQuery("#editiprangesbyuserform_<?php echo $ipDetails['id'];?>").validate();

								jQuery("#submit_ip_range_form_<?php echo $ipDetails['id'];?>").click(function(e){								
									e.preventDefault();			
									var pass_msg = jQuery("#editiprangesbyuserform_<?php echo $ipDetails['id'];?>").valid();
									jQuery("#display_success_message").remove();
									
									//validations
									if(pass_msg == false){
										return false;
									} else {
										loader_show();
										
										jQuery.ajax({
											type: "POST",
											data: jQuery("#editiprangesbyuserform_<?php echo $ipDetails['id'];?>").serialize(),
											url : URL_SITE+"/admin/adminAction.php?editIPRanges=<?php echo $ipDetails['id'];?>&i=<?php echo $i;?>&userid=<?php echo $ipDetails['user_id'];?>",
											
											success: function(msg){
												loader_unshow();
												if(jQuery.trim(msg) == 'true'){
													jQuery(".show_already_ip_div_<?php echo $ipDetails['id'];?>").html('<font color="red">This IP already exists.</font>');		
												} else {
													if(!jQuery('#display_success_message').hasClass('succuss_message')){
														jQuery(".profile-outer").prepend('<div id="display_success_message" class="succuss_message txtcenter pB20"><h3>IP Ranges has been saved successfully.</h3></div>');
													}
													jQuery("#show_saved_result_<?php echo $ipDetails['id'];?>").html(msg);
													jQuery('#display_success_message').delay(2000).fadeOut();
													return true;
												}
											}
										});
									}
								});
							});
							</script>

						</form>
					<?php
					$i++;
					}
					?>	
				<?php } ?>
			 </div>
		</div>
		<?php } ?>

	</div>
	<div class="clear pT30"></div>

</section>
<!-- /Container -->
<div class="clear"></div>

<?php include_once $basedir."/include/footerHtml.php"; ?>