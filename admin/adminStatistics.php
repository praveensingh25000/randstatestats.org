<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

$databasesArray = array();

if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!=''){
	$siteMainDBDetail	= $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
	$db_name			= $siteMainDBDetail['database_label'];
}


if(isset($_GET['action']) && $_GET['action'] == "inactive"){
	$databasesResult = $admin->showAllDatabases('db_nextupdate', 'asc', 0);
	$active = 0;
} else {
	$databasesResult = $admin->showAllDatabases('db_nextupdate', 'asc', 1);
	$active = 1;
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
			<div class="wdthpercent100 tabnav">
				<div class="">
					<p class="listform">
						<span class="">Show:</span> 
						<a <?php if($active == 1){ ?> class="active" <?php } ?> href="adminStatistics.php">Active</a>&nbsp;&nbsp;
						<a <?php if($active == 0){ ?> class="active" <?php } ?> href="?action=inactive">Inactive</a>
					</p>
				</div>
			</div>

			<div class="statistics">
				<?php if(mysql_num_rows($databasesResult)>0){?>

					<table class="data-table">
						<thead>
							<tr class="">
								<th class="thead_gr s11x">Description</th>
								<th class="thead_gr s11x">Geographic<br>Coverage</th>
								<th class="thead_gr s11x">Periodicity</th>
								<th class="thead_gr s11x">Beg/<br>End</th>
								<th class="thead_gr s11x">Next<br>Update</th>
								<th class="thead_gr s11x">Data Source</th>
								<th class="thead_gr s11x">&nbsp;</th>	
								<th class="thead_gr s11x">&nbsp;</th>	
								<th class="thead_gr s11x">Read Me</th>	
							</tr>
						</thead>
						<?php while($formDetail = mysql_fetch_assoc($databasesResult)){
						
							$updateInfo = $admin->getFormUpdateInfo($formDetail['id']);
							
							$color = "";
							if($formDetail['db_nextupdate']!="0000-00-00" && $formDetail['db_nextupdate']!='NA'){

								if(strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($formDetail['db_nextupdate'])))){
									$color = "RED";
								} else {
									$day		 = 86400;
									$start_time  = strtotime(date('Y-m-d'));
									$end_time    = strtotime($formDetail['db_nextupdate']);
									$num_Days    = floor(($end_time - $start_time) / $day);
									
									
									if($num_Days<=31){
										$color = "Green";
									}
								}

							}
							
						?>
						<tbody>
							<tr class="">
								<td valign="top">
								<?php if($formDetail['is_static_form'] == 'Y' && $formDetail['url']!='' ){ ?>
										<a target="_blank" href="<?php echo URL_SITE;?>/<?php echo $formDetail['url'];?>"><?php echo stripslashes(trim($formDetail['db_name'])); ?></a>
									<?php } else { ?>
										<a target="_blank" href="<?php echo URL_SITE;?>/form.php?id=<?php echo base64_encode($formDetail['id']);?>"><?php echo stripslashes(trim($formDetail['db_name'])); ?></a> 
									<?php } ?>								
								</td>
								
								<td valign="top"><?php echo $formDetail['db_geographic']; ?></td>
								
								<td valign="top"><?php echo $formDetail['db_periodicity']; ?></td>
								
								<td valign="top">
									<?php if($formDetail['db_dataseries']!=''){
										echo $formDetail['db_dataseries'];
									} else {
										echo "NA";
									} ?>
								</td>

								<td valign="top">
									<span style="color:<?php echo $color; ?>"><?php if(isset($formDetail['db_nextupdate']) && $formDetail['db_nextupdate'] != '0000-00-00'){ echo $formDetail['db_nextupdate']; } else { echo "NA"; } ?></span>
								</td>
								<td valign="top"><?php echo stripslashes($formDetail['db_datasource']); ?></td>

								<td >
									<a href="database.php?action=edit&id=<?php echo base64_encode($formDetail['id']);?>">Edit</a>						
								</td>	
								<td><a href="source.php?id=<?php echo base64_encode($formDetail['id']);?>">Source</a></td>
								<td><a href="readme.php?id=<?php echo base64_encode($formDetail['id']);?>"><?php if(empty($updateInfo)){ echo "Add"; } else { echo "Read Me"; } ?>
								</a></td>
							</tr>
						</tbody>
						<?php } ?>
					</table>
				<?php } else { ?>
					<h4>No database added Yet.</h4>
				<?php } ?>
				</div>	


		 </div>
		<!-- left side -->

		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>



