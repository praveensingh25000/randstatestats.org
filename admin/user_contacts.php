<?php
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";
checkSession(true);

$admin = new admin();
//echo base64_encode(161);
if(!isset($_SESSION['admin']))
{
	header("location: index.php");
}
	$querystring='';

	$sql=$admin->getUserContacts();
	$objPage = new PS_Pagination($db->conn,$sql,10,5,$querystring);
	$source = $objPage->paginate();
	$num=mysql_num_rows($source);

	if(isset($_GET['id']) && $_GET['id'] !=''){
		$del_id= base64_decode($_GET['id']);
		$return = $admin->deleteContact($del_id);
		if(mysql_affected_rows()!="-1")
		{
			$_SESSION['msgsuccess']="User contact Detail has been deleted successfully.";
		}else{
			$_SESSION['msgalert']="<center><font color='red'>User contact Detail can not be deleted due to some internal error.</font></center>";
		}
		header('location: user_contacts.php');
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
			<h3>List of Contacted Users</h3>

		<?php if(isset($num) && $num >0){ ?>

			<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
				<tbody>
					<tr>
						<th bgcolor="#eeeeee">Name</th>
						<th bgcolor="#eeeeee">Email</th>
						<th bgcolor="#eeeeee">Message</th>
						<th bgcolor="#eeeeee">Date Submitted</th>
						<th bgcolor="#eeeeee">Actions</th>

					</tr>
			<?php
			while($udetail=mysql_fetch_assoc($source))
			{
			?>
			<tr>
				<td><a href="viewContact.php?id=<?=base64_encode($udetail['cont_id'])?>"><?=$udetail['name']?></a></td>
				<td><?=stripslashes($udetail['email'])?></td>
				<td><?php $strlen = strlen($udetail['message']);
				if($strlen < 15){ echo stripslashes($udetail['message']);} else { echo substr(stripslashes($udetail['message']),0,15).'...';}?></td>
				
				<td><?php echo date('F j, Y', strtotime($udetail['send_on'])); ?></td>
				<td><a href="viewContact.php?id=<?=base64_encode($udetail['cont_id'])?>">view</a> | <a href="?id=<?=base64_encode($udetail['cont_id'])?>" onclick="javascript: return delete_action();">Delete</a></td>
			</tr>
			<?php
			}
			?>
		</table>

		<?php
		}
		else
		{
			echo "<center>There are no contacts.</center>";
		}
		?>
		<div align='center'><?php echo $objPage->renderfullnav();?></div>

</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>