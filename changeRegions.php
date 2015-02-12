<?php
/******************************************
* @Modified on Jan 30, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

$new_array	= array();

$catObj					=	new Category();
$admin					=	new admin();
$user					=	new user();
$active					=	1;
$totalActiveDatabase	=	$totalActDatabaseshared	=	$totalActDatabasecommon = 0;

$categoriesResult		=   $admin->getAllParentCategories();
//$categoriesResult		=	$admin->showAllCategories();
$totalCategory			=	$db->count_rows($categoriesResult);
$categories				=	$db->getAll($categoriesResult);

if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!=''){
	$siteMainDBDetail	= $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
	$db_name			= $siteMainDBDetail['database_label'];
}

$databasesResult		=	$admin->showAllActiveDeactiveDatabases($active);
$totalActDatabasecommon	=	$dbDatabase->count_rows($databasesResult);

if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!='rand_usa') {

	$siteMainDBDetailshared			=  $admin->getMainDbDetail('rand_usa');
	$dbDatabasessharted				=  new db(DATABASE_HOST, $siteMainDBDetailshared['databaseusername'], $siteMainDBDetailshared['databasepassword'], $siteMainDBDetailshared['databasename']);
	$sql_shared						=	$admin->showAllActiveDeactiveDatabasesAll($active);
	$databasesResultshared			=	$dbDatabasessharted->run_query($sql_shared);
	$totalActDatabaseshared			=	$dbDatabasessharted->count_rows($databasesResultshared);

}

$totalActiveDatabase = $totalActDatabaseshared	+	$totalActDatabasecommon;

if((isset($_POST['categoryid']) && $_POST['categoryid']!='') || (isset($_SESSION['categoryid']) && $_SESSION['categoryid'] !='' )) {

	if(isset($_POST['categoryid']) && $_POST['categoryid'] != '') {
		if(isset($_SESSION['categoryid'])) { unset($_SESSION['categoryid']);}
		$parent_id = $_SESSION['categoryid'] = trim($_POST['categoryid']);
	}else if(isset($_SESSION['categoryid']) && $_SESSION['categoryid'] != '') {
		$parent_id = trim($_SESSION['categoryid']);
	}

	$allActiveDatabases = $catObj->showAllActiveDatabase($parent_id);
	$allActiveDatabaseschunk = array_chunk($allActiveDatabases, 2, true);
	$cat_detail_arr = $admin->getPatCategory($parent_id);
}
?>

<!-- container -->
<section id="container">
	 <!-- main div -->
	 <section class="conatiner-full pL20" id="inner-content">
				
		<h2> <?php if(isset($db_name)) { echo $db_name; } ?> Statistics contains <?php if(isset($totalCategory)) echo $totalCategory; ?> categories and <?php if(isset($totalActiveDatabase)) echo $totalActiveDatabase; ?> Forms. </h2>
			
		<?php if(isset($totalparentCategory) && isset($totalsubparentCategory)) { ?><h3 class="pT10"><?php echo $totalparentCategory;?> Parent Categories and <?php echo $totalsubparentCategory;?> Sub Parent Categories. </h3> <?php } ?>
		<p class="pB10">Some content here...</p>
		<div class="clear"></div>

		<div class="categorie-data registr" style="width: 90%;">	
			<div style="width: 100%;">
				<div class="form-div left" style="width: 45%;">
						
						<p style="margin-right: 10px;padding-top: 0;width: 25%;">
							<span style="font-size:20px;" class="choose left pT10">Select Region</span></p>
							<?php
							if(isset($_SESSION['legalDatabaseUser']) && !empty($_SESSION['legalDatabaseUser'])) {
								$database_purchased_array_unique=array_unique($_SESSION['legalDatabaseUser']);
								$database_purchased_str=implode(',',$database_purchased_array_unique);
								$allMainDatabases = $admin->getMainDatabasesPurched($database_purchased_str,'Y');
							} else {
								$allMainDatabases = $admin->getMainDatabases('Y');
							}
								
							if(count($allMainDatabases)>0) { ?>							
								<form name="frmChangeDatabaseToBeUsed" action = "" method="post" id="frmChangeDatabaseToBeUsedfront" class="left pL30">
									<select name="databaseToBeUse" id="databaseToBeUse" style="">
										<option value="0"> SELECT REGION </option>
										<?php foreach($allMainDatabases as $rowMain => $mainDetail){ ?>
											<option <?php if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse'] == $mainDetail['databasename']) { echo "selected='selected'"; } ?> value="<?php echo $mainDetail['databasename']; ?>"> <?php echo ucwords($mainDetail['database_label']); ?> </option>
										<?php } ?>
									</select>
								</form>

								<script type="text/javascript">
									jQuery(document).ready(function(){
										jQuery('#databaseToBeUse').change(function(){
											var values = jQuery('#databaseToBeUse').val();
											if(values != '0')	
											jQuery('#frmChangeDatabaseToBeUsedfront').submit();
										});
									});
								</script>
							<?php } ?>
							
						
					<!-- <div class="clear"></div> -->
				</div>
					
			<?php if((isset($_POST['databaseToBeUse']) && $_POST['databaseToBeUse']!='') || (isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!='')) { ?>
			
				<!-- <div class="clear"></div> -->
				<div class="form-div left" style="width: 45%;">
						<p style="margin-right: 10px;padding-top: 0;width: 30%;">
							<span style="font-size:20px;" class="choose left pT10">Select Category</span></p>
							
							<?php if($categories >0) {?>
									
								<form name="frmtoselectDatabaseActtoCategory" action = "<?php URL_SITE?>/changeRegions.php" method="post" id="frmtoselectFormActtoCategory" class="left pL30">
									<select id="categoryid" name="categoryid">
										<option value="0"> Select Category </option>	
										<?php foreach($categories as $key => $categoryDetail){	
											$parentCat = "-";							
											if($categoryDetail['parent_id'] =='0'){
											$parentCatDetail = $admin->getCategory($categoryDetail['parent_id']);
											$parentCat = $parentCatDetail['category_title'];
											?>							
											<option <?php if(isset($_SESSION['categoryid']) && $_SESSION['categoryid'] == $categoryDetail['id']) { ?> selected="selected" <?php } else if(isset($_POST['categoryid']) && $_POST['categoryid'] == $categoryDetail['id']) {?> selected="selected" <?php } ?> value="<?php echo $categoryDetail['id'];?>"> <?php echo ucwords($categoryDetail['category_title']);?> </option>	
											<?php } ?>
										<?php } ?>
									</select>
								</form>
								<!-- <script type="text/javascript">
									jQuery(document).ready(function(){
										jQuery('#categoryid').change(function(){
											var values = jQuery('#categoryid').val();
											if(values != '0')										
											jQuery('#frmtoselectFormActtoCategory').submit();
										});
									});
								</script> -->								
							<?php } ?>							
											
					</div>
				</div>
			<?php } ?>

			<br class="clear pT10" />
				
			<div id="show_all_forms">
				<?php if((isset($_POST['categoryid']) && $_POST['categoryid']!='') || (isset($_SESSION['categoryid']) && $_SESSION['categoryid']!='')) { ?>
					
					<div class="clear"></div>
					<div class="form-div">
					   
					   <h2 style="font-size:20px;"> 
							<?php if(isset($db_name)) echo $db_name;?> <?php echo $cat_detail_arr['category_title'];?> Statistics contains the following categories and Forms 
					   </h2>
					   <div class="clear pT30"></div>
					   
					   <div class="from wdthpercent100">
						<?php if(!empty($allActiveDatabaseschunk)) {

								foreach($allActiveDatabaseschunk as $allActiveDatabases) { 
									
									  foreach($allActiveDatabases as $categorykey => $formsDetail) {

										  if(!is_numeric($categorykey)) { ?>
												
												<div class="from-show">
													<h3>
														<?php if(isset($categorykey) && $categorykey !='0') { echo ucwords($categorykey); } ?>
													</h3>
													<div class="clear pT10"></div>
													
													<?php foreach($formsDetail as $key => $formsDetailAll) {
														
														$formsDetailArray = array_sort($formsDetailAll, 'share', $order=SORT_ASC);
														
														foreach($formsDetailArray as $key => $forms) { ?>

															<ul>
																<?php 
																if(isset($forms['is_static_form']) && $forms['is_static_form'] == 'Y' && $forms['url']!='' ){ 
																	
																	if(isset($forms['db_select']) && $forms['db_select'] != '' && $forms['form_id_us']!='' ) { 
																	$url=$forms['url'];
																	?>
																		<li>
																			<a href="?dbc=<?php echo base64_encode($forms['db_select']);?>&url=<?php echo $url ;?>"><?php if(isset($forms['form_name'])) { echo stripslashes($forms['form_name']); } else { echo stripslashes($forms['db_name']); } ?>&nbsp;(50 States)</a>
																		</li>
																	<?php } else { ?>
																		<li>
																			<a href="<?php echo URL_SITE;?>/<?php echo $forms['url'];?>"><?php echo stripslashes($forms['db_name']);?></a>
																		</li>
																	<?php } ?>

																<?php } else { ?>
																		
																		<?php if(isset($forms['db_select']) && $forms['db_select'] != '' && $forms['form_id_us']!='' ) {
																			?>
																			<li>
																				<a href="?formid=<?php echo base64_encode($forms['form_id_us']);?>&dbc=<?php echo base64_encode($forms['db_select']);?>"><?php if(isset($forms['form_name'])) { echo stripslashes($forms['form_name']); } else { echo stripslashes($forms['db_name']); } ?>&nbsp;(50 States)</a>
																			</li>
																	<?php } else { ?>
																			<li>
																				<a href="form.php?id=<?php echo base64_encode($forms['id']);?>"><?php echo stripslashes($forms['db_name']);?></a>
																			</li>
																	<?php } ?>
																<?php } ?>
															</ul>
														<?php } ?>
													<?php } ?>
												</div>
												
											<?php  } else { ?>	
											<div class="from-show">
												
												<?php foreach($formsDetail as $key => $formsDetailAll) {											
													$formsDetailArray = array_sort($formsDetailAll, 'share', $order=SORT_ASC);
													
													foreach($formsDetailArray as $key => $forms) { ?>	
														<ul>
															<?php
															if($forms['is_static_form'] == 'Y' && $forms['url']!='' ){ ?>
																<li><a href="<?php echo URL_SITE;?>/<?php echo $forms['url'];?>"><?php echo stripslashes($forms['db_name']);?></a></li>
															<?php } else { ?>
																<li><a href="form.php?id=<?php echo base64_encode($forms['id']);?>"><?php echo stripslashes($forms['db_name']);?></a></li> 
															<?php } ?>
														</ul>
													<?php }	?>
												<?php }	?>
											</div>
											<?php }	?>								
										<?php } ?>					
								<?php } ?>
						<?php } else {	echo '<div class="from-show"><h4>No Forms Found.</h4></div>'; } ?>		
					</div>
				</div>
				<?php } ?>
			</div>		
	</div>
</section>
<!-- /container -->

<?php
include_once $basedir."/include/footerHtml.php";
?>