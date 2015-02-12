<?php
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";
checkSession(true);

$admin = new admin();

if(!isset($_SESSION['admin']))
{
	header("location: index.php");
}

$id = $_GET['id'];

$detail=admin::getContactDetail(base64_decode($id));

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
		<a href="user_contacts.php"><h3 class='left'>Contacted Users</h3></a>&nbsp;>>&nbsp;View

		<div class="clear"></div>

		<?php
		if(!empty($detail))
		{
		?>
		<table width="80%" cellpadding="4" cellspacing="4">
			<tr>
				<td><strong>Name: </strong></td><td><?php echo $detail['name']; ?></td>
			</tr>
			<tr>
				<td><strong>Email: </strong></td><td><?php echo $detail['email']; ?></td>
			</tr>
			<tr>
				<td><strong>Phone: </strong></td><td><?php echo $detail['phn_no']; ?></td>
			</tr>
			<tr>
				<td><strong>Address: </strong></td><td><?php echo $detail['address']; ?></td>

			</tr>
			<tr>
				<td><strong>Message: </strong></td><td><?php echo stripslashes($detail['message']); ?></td>
			</tr>
			<tr>
				<td><strong>Date Submitted: </strong></td><td><?php echo date('F j, Y', strtotime($detail['send_on'])); ?></td>
			</tr>
		</table>
		<?php
		}
		else
		{
			header('location: home.php');
		}
		?>
		</div>
	</div>
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>