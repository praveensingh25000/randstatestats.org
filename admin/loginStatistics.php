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

global $db;
$userid = (isset($_GET['user_id']))?base64_decode($_GET['user_id']):'0';
$userDetail = $user->getUser($userid);

if(isset($_GET['startDate']) && isset($_GET['endDate']) && $_GET['startDate']!="" && $_GET['endDate']!=""){
	$startDate = date("Y-m-d", strtotime($_GET['startDate']));
	$endDate = date("Y-m-d", strtotime($_GET['endDate']));
	$loginDetails = $user->getNumberOfLoginDetails($userDetail['id'], $startDate, $endDate);
} else {
	$loginDetails = $user->getNumberOfLoginDetails($userDetail['id'] );
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
			

			<div id="toPrintGrid" class="toPrintGrid">

				<h2><?php echo $userDetail['organisation']; ?>'s
				<?php if(isset($startDate) && isset($startDate)){ ?>

				>> Usage Statistics From <?php echo date("F j, Y", strtotime($startDate)); ?> To <?php echo date("F j, Y", strtotime($endDate)); ?>

				<?php } else { ?>
				>> Total Usage Statistics Since Sept. 5, 2013
				<?php } ?></h2>
				<br/>
				<form id="frmLoginStatistics" name="frmLoginStatistics">
				<table  cellpadding="6" width="50%">
					<tr>
						<td width="2%" valign="top">From</td>
						<td width="10%"><input type="text" name="startDate" id="startDate" class="required" style="width: 100%"></td>
						<td width="2%" valign="top">To</td>
						<td width="10%"><input type="text" name="endDate" style="width: 100%" class="required"  id="endDate"></td>
						<td width="15%" valign="top">
						<input type="hidden" name="user_id" value="<?php echo base64_encode($userid); ?>">
						<input type="submit" value="Go">
						<?php if(isset($startDate) && isset($startDate)){ ?>
						<a href="loginStatistics.php?user_id=<?php echo base64_encode($userid); ?>">Clear Search</a>
						<?php } ?>
						</td>
					</tr>
				</table>
				</form>
				<br/>

				<script type="text/javascript">
				$(function() {
					$( "#startDate" ).datepicker({
						defaultDate: "+1w",
						changeMonth: true,
						changeYear: true,
						format: 'yy-mm-dd',
						maxDate: 0,
						numberOfMonths: 1,
						selectors: true,
						offset: [10, 20],  // tweak the position of the calendar
						speed: 'fast',   // calendar reveal speed
						firstDay: 1,
						onClose: function( selectedDate ) {
							$( "#endDate" ).datepicker( "option", "minDate", selectedDate );
						}
					});
					$( "#endDate" ).datepicker({
						defaultDate: "+1w",
						changeMonth: true,
						changeYear: true,
						format: 'yy-mm-dd',
						numberOfMonths: 1,
						maxDate: 0,
						selectors: true,
						offset: [10, 20],  // tweak the position of the calendar
						speed: 'fast',   // calendar reveal speed
						firstDay: 1,
						onClose: function( selectedDate ) {
							$( "#startDate" ).datepicker( "option", "maxDate", selectedDate );
						}
					});
				});
				</script>

				<table id="grid_view" class="data-table">

					<tbody>

					<?php if(!empty($loginDetails)){ ?>
						
						<?php
						//db loop starts
						foreach($loginDetails as $dbidkey=>$loginDetailArray){ 

							$totalAccessDB = 0;
							$siteDBDetail = $admin->selectDatabases($dbidkey);
							$dbName  = $siteDBDetail['databasename'];
							$dbLabel = $siteDBDetail['database_label'];	?>

							
							
							<tr class="">
								<th class="thead_gr s11x">Accessed IPs</th>
								<th class="thead_gr s11x">Database Name </th>
								<th class="thead_gr s11x">Section (Category)</th>
								<th class="thead_gr s11x">Number of Searches</th>
							</tr>

							<tr>
								<td colspan="3"><h3><?php echo $dbLabel;?></h3></td>	
								<td><h3><span id="dbAccess_<?php echo $dbidkey;?>"><?php echo $totalAccessDB?></span></h3></td>
							</tr>

							<?php if(!empty($loginDetailArray)){?>
								
								<?php
								//IP loop starts
								foreach($loginDetailArray as $ipkey=>$ipDetailArray){ ?>
									
									<?php if(!empty($ipDetailArray)){?>
										
										<?php
										//Form loop starts
										foreach($ipDetailArray as $formkey=>$formDetailArray){

											$count = count($formDetailArray);

											if($formkey){
												$url = '';
												$explode = explode('.org/',$formkey);
												$formname='';							
												$explodedUriMain =  substr($explode['1'],0);
												 
												$exploded = explode('?id=',$explodedUriMain);
												
												if($exploded['0']=='form.php'){			
													$dbid = trim(base64_decode($exploded['1']));
													$databaseDetail = $user->urlFormName($dbName,$dbid,$url);
													$formname=$databaseDetail['db_name'];
												}else{						
													/*if($exploded['0']){
														$form_url = $exploded['0'];
														$exploded_url = explode('?dbc=',$form_url);*/
														$dbid = '';
														$url = trim($exploded['0']);
														//echo $url;die;
														$databaseDetail = $user->urlFormName($dbName,$dbid,$url);
														$formname=$databaseDetail['db_name'];
													//}
												} 
											//echo '<pre>';print_r($databaseDetail);echo '</pre>';
												//finding category and subcategory.
												$catDetail = $user->getDbCategories($dbName,$databaseDetail['id']);				
												foreach($catDetail as $catKey=>$category){
													if($category['cat_type']=='s'){
														$subcat = $user->getSubCatName($dbName,$category['category_id']);
													}elseif($category['cat_type']=='p'){
														$parentcat = $user->getParentCatName($category['category_id']);
													}
												}
											}

											?>

											<tr>
												<td><?php echo $ipkey; ?></td>
												<td><?php if(isset($formname)){echo $formname; }?>	</td>
												<td><?php echo $parentcat['category_title'].' ('.$subcat['category_title'].')'; ?></td>
												<td><?php echo count($formDetailArray); ?></td>
											</tr>									

										<?php $totalAccessDB += $count; }?>	
										
										<script>
											document.getElementById("dbAccess_<?php echo $dbidkey;?>").innerHTML="<?php echo $totalAccessDB;?>";
										</script>

									<?php }  ?>							

								<?php } ?>

							<?php } ?>

						<?php } ?>

					<?php } else { ?>
						<tr><td colspan= 4 >No records found for this search.</td></tr>
					<?php } ?>

				</table>
			</div>
		</div>
		<!-- left side -->	
	</div>	

</section>
<!-- /container -->

<?php include_once $basedir."/include/adminFooter.php"; ?>