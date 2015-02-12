<?php
/******************************************
* @Modified on Jan 30, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

$catObj					=	new Category();
$admin					=	new admin();
$user					=	new user();
$active					=	1;

$subcategoriesResult	=	$admin->showAllCategories($status=1);
$totalsubparentCategory	=	$db->count_rows($subcategoriesResult);
$subcategories			=	$db->getAll($subcategoriesResult);

$categoriesResult		=   $admin->getAllParentCategories();
$totalparentCategory	=	$db->count_rows($categoriesResult);
$categories				=	$db->getAll($categoriesResult);

$totalActiveDatabase	=	$totalActDatabaseshared	=	$totalActDatabasecommon = 0;

$subparentCatArray      =   array();

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

if(isset($totalActDatabaseshared) && isset($totalActDatabasecommon)) {
	$totalActiveDatabase = $totalActDatabaseshared	+	$totalActDatabasecommon;
}

if((isset($_POST['categoryid']) && $_POST['categoryid']!='') || (isset($_SESSION['categoryid']) && $_SESSION['categoryid'] !='' )) {

	if(isset($_POST['categoryid']) && $_POST['categoryid'] != '') {
		if(isset($_SESSION['categoryid'])) { unset($_SESSION['categoryid']);}
		$parent_id = $_SESSION['categoryid'] = trim($_POST['categoryid']);
	}else if(isset($_SESSION['categoryid']) && $_SESSION['categoryid'] != '') {
		$parent_id = trim($_SESSION['categoryid']);
	}

	$allActiveDatabases			=	$catObj->showAllActiveDatabase($parent_id);
	$allActiveDatabaseschunk	=	array_chunk($allActiveDatabases, 2, true);
	$cat_detail_arr				=   $admin->getPatCategory($parent_id);
	$totalActiveForms			=	count($allActiveDatabases);
}
?>

<!-- container -->
<section id="container">
	 <!-- main div -->
	 <!-- <div id="mainshell"> -->
		
		<div style="font-size:17px;" class="pL20 pT10">			
			<h2>
				<?php if(isset($db_name)) echo $db_name;?> Statistics contains <?php echo $totalparentCategory;?> main sections, <?php if(isset($totalsubparentCategory)) echo $totalsubparentCategory; ?> total categories, and <?php if(isset($totalActiveDatabase)) echo $totalActiveDatabase; ?> individual databases. 
			</h2>			
		</div>
		<br class="clear" />
			
		<div class="profile" style="width: 100%;">
			<div class="registr" style="width: 90%;">
											
				<div class="wdthpercent100 pT10">
					<div class="wdthpercent20 left"><h2>Select Section</h2></div>
					<div class="wdthpercent50 left">
						<?php 
						if(isset($categories) && count($categories) >0) {?>
							<form name="frmtoselectDatabaseActtoCategory" action = "<?php URL_SITE?>/changeDatabase.php" method="post" id="frmtoselectFormActtoCategory">
								<select class="" id="categoryid" name="categoryid">
									<option value="0"> Choose section </option>	
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
						<?php } ?>					
					</div>					
				</div>
								
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
								<?php } else {	echo '<div class="from-show"><h4>No Forms Found.</h4></div>';	} ?>	
							</div>
						</div>
					<?php } ?>					
				</div>				
		  </div>	
	<!-- </div>	 -->	
</section>
<!-- /container -->

<?php include_once $basedir."/include/footerHtml.php"; ?>