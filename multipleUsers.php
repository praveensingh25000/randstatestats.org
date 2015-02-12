<?php
/******************************************
* @Modified on July 09, 2013.
* @Package: RAND
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

checkSession(false);

if($_SESSION['user']['user_type'] == 5 || $_SESSION['user']['parent_user_id']!='0'){ 
	header('location: index.php');
}

$admin			= new admin();
$user_id		=	$_SESSION['user']['id'];

if(isset($_GET['delete']) && isset($_GET['id'])){
	$user = new user();
	$delete = $user->deleteUser($_GET['id']);
	if($delete){
		$_SESSION['successmsg']="User has been deleted successfully.";
	} else {
		$_SESSION['errormsg']="User could not be deleted. Please try again.";
	}
	header('location: multipleUsers.php');
}

$users = $admin->getMultipleUsers($user_id);

$total = count($users);
?>

<!-- container -->
<section id="container">
	 <div class="main-cell">
		<div id="container-1">

			<div class="wdthpercent100 left">
				<div class="wdthpercent90 left">
					<h3>
					<?php if($_SESSION['user']['user_type'] == 6){ echo "Multiple Users"; } else { echo "Multiple Admins"; } ?>
					</h3>
				</div>
				<div class="right">
					<h3>
						<a href="addUsers.php"><?php if($_SESSION['user']['user_type'] == 6){ echo "Add User"; } else { echo "Add Admin"; } ?></a>
					</h3>
				</div>
				<div class="clear pB10"></div>					
			</div>

			<!-- ALL DB DETAILS -->
			
				<div class="pT10">
					<table class="data-table">
						
						<th>Name</th>
						<th>Email</th>										
						<th>Phone</th>	
						<th>Address</th>	
						<th>Organisation</th>
						<th>Date Added</th>
						<th>Action</th>	
					
						<?php
						if($total >0) {
							foreach($users as $key => $userDetail) {
					
							?>
								<tr>								
									<td>
										<h4><?php echo ucwords($userDetail['name']);?></h4>
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
										<a href="addUsers.php?id=<?php echo $userDetail['id']; ?>">Edit</a>&nbsp;
										<a href="multipleUsers.php?id=<?php echo $userDetail['id']; ?>&delete" onclick="javascript: return confirm('Are you sure you want to delete?');">Delete</a>
									</td>								
								</tr>
							<?php }	?>										

							
						<?php } else { ?>
							<tr>
								<td colspan="7"><h4>No multiple users yet.</h4></td>
								
							<tr>

						<?php }	?>
					</table>
				</div>

				<br class="clear" />
			</div>
			
		</div>
	</div>		
</section>
<!-- /container -->