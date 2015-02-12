<?php
/******************************************
* @Modified on Dec 19, 2012
* @Package: Rand
* @Developer: Mamta Sharma
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

if(isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['n_id']) && $_GET['n_id']!=''){

	$newsid = trim(base64_decode($_GET['n_id']));
	$newsDetail = $admin->getNews($newsid);
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
			<h3><a href="news.php">List Of News</a> >></h3> News Detail &nbsp;
		</div>
			
			<fieldset style="border: 1px solid #cccccc; padding: 10px;">
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;">News Detail</legend>
				<table border='0'>
				<tr>
					<td><b>Title: </b></td>
					<td style="padding: 10px 0;">
						<?php echo stripslashes($newsDetail['news_title']); ?>
					</td>
				</tr>
				<tr>
					<td><b>Description: </b></td>
					<td style="padding: 10px 0;">
						<?php echo stripslashes($newsDetail['description']); ?>
					</td>
				</tr>
				<tr>
					<td><b>Active Status: </b></td>
					<td style="padding: 10px 0;">
						<?php if($newsDetail['is_active']=='N'){ echo 'Inactive';}
						else
						{ echo 'Active';}?>
					</td>
				</tr>
				<tr>
					<td><b>Date for News Publish: </b></td>
					<td style="padding: 10px 0;">
						<?php echo date('F j, Y', strtotime($newsDetail['date_added'])); ?>
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


