<?php
/******************************************
* @Modified on Sept 3, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";
require_once $basedir."/classes/emailTemp.class.php";

checkSession(true);

$admin = new admin();
$emailObj = new emailTemp();

if(isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['c_id']) && $_GET['c_id']!=''){

	$newsid = trim(base64_decode($_GET['c_id']));
	$tempDetail = $emailObj->getTemp($newsid);
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
		
		<!-- <div class="right">
			<a href="javascript:history.go(-1);">Back</a>
		</div> -->
		<div class="">
			<h3><a href="mail_content.php">List Of Templates</a> >></h3> Template Detail &nbsp;
		</div>
			
			<fieldset style="border: 1px solid #cccccc; padding: 10px;">
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Template Detail</legend>
				<table border='0'>
				<tr>
					<td width="16%" valign="top"><b>Template Name: </b></td>
					<td style="padding: 10px 0;">
						<?php echo stripslashes($tempDetail['title']); ?>
					</td>
				</tr>
				<tr>
					<td width="16%" valign="top"><b>Email Subject: </b></td>
					<td style="padding: 10px 0;">
						<?php echo stripslashes($tempDetail['subject']); ?>
					</td>
				</tr>
				<tr>
					<td width="16%" valign="top"><b>Email Body: </b></td>
					<td style="padding: 10px 0;">
						<?php echo stripslashes($tempDetail['body']); ?>
					</td>
				</tr>
				<tr>
					<td width="16%" valign="top"><b>CC E-mail: </b></td>
					<td style="padding: 10px 0;">
						<?php echo stripslashes($tempDetail['cc_email']);?>
					</td>
				</tr>
				</table>

				</fieldset>
			</div>

		 </div>
		<!-- left side -->

		
	</tr>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>


