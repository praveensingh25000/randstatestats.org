<?php
/******************************************
* @Modified on March 18, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

if(isset($_GET['delete']) && $_GET['delete']!='') {
	$delete = trim(base64_decode($_GET['delete']));
	$deletenotify = $admin->deletNotification($delete);
	header('location:notifyUser.php');
}
$notifyresult_res = $admin->showAllNotification();
$total_notify	  = $db->count_rows($notifyresult_res);
$notify_details   = $db->getAll($notifyresult_res);
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
			
			<h3> Notify Users </h3>

			<table cellspacing="5" cellpadding="2" border="1" width="100%">
				<tbody>
					<tr>
						<th bgcolor="#eeeeee">Form ID</th>
						<th bgcolor="#eeeeee">Database Name</th>
						<th bgcolor="#eeeeee">Form Name</th>
						<th bgcolor="#eeeeee">User Email</th>						
						<th bgcolor="#eeeeee">Actions</th>
						
					</tr>

					<?php if(!empty($notify_details)){ ?>			
					<?php foreach($notify_details as $key => $notify){	
						$databaseDetail = $admin->getDatabase($notify['dbid'], true);
						$dbname			= stripslashes($databaseDetail['db_name']);
					?>
						<tr>
							<td align="center"><?php echo $notify['dbid']; ?></td>
							<td align="center"><?php echo $notify['databasename']; ?></td>
							<td align="center"><?php echo $dbname; ?></td>
							<td align="center"><?php echo $notify['notifyemail']; ?></td>
							<td align="center">
								<a onclick="javascript: return confirm('Are you sure?');" href="?delete=<?php echo base64_encode($notify['id']);?>">Delete</a>
							</td>
						</tr>

					<?php } ?>
				</tbody>
			</table>

			<?php } else{ ?>
				<tr>
					<td align="left"><h4>No Ntification added Yet</h4></td>
					<td align="left">&nbsp;</td>
				</tr>
			<?php }?>

			</form>
		 </div>
		<!-- left side -->		
	</div>		
</section>
<!-- /container -->

<?php 
include_once $basedir."/include/adminFooter.php";
?>