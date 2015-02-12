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

$categoriesResult		=	$admin->showAllCategories();
$totalCategory			=	$db->count_rows($categoriesResult);
$categories				=	$db->getAll($categoriesResult);

if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!=''){
	
	$dbArray	=	explode('_',$_SESSION['databaseToBeUse']);
	
	if($_SESSION['databaseToBeUse']=='rand_usa'){
		$db_name	=	strtoupper($dbArray[0]).' '.strtoupper(substr($dbArray[1],0,2));
	} else {
		$db_name	=	strtoupper($dbArray[0]).' '.ucwords($dbArray[1]);
	}
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
	$cat_detail_arr = $catObj->getCatById($parent_id);
}

echo "<pre>";
print_r($allActiveDatabaseschunk);
echo "</pre>";
?>

<!-- container -->
<section id="container">
	 <!-- main div -->
	 <section class="conatiner-full pL20" id="inner-content">
				
		<h2><?php if(isset($db_name)) echo $db_name;?> Statistics contains <?php if(isset($totalCategory)) echo $totalCategory; ?> categories and <?php if(isset($totalActiveDatabase)) echo $totalActiveDatabase; ?> Forms. </h2>
			
		<?php if(isset($totalparentCategory) && isset($totalsubparentCategory)) { ?><h3 class="pT10"><?php echo $totalparentCategory;?> Parent Categories and <?php echo $totalsubparentCategory;?> Sub Parent Categories. </h3> <?php } ?>
		<p class="pB10">Some content here...</p>
		<div class="clear"></div>

		<div class="categorie-data registr" style="width: 90%;">	
			<div style="width: 100%;">
				<div class="form-div left" style="width: 45%;">
						
						<p style="margin-right: 10px;padding-top: 0;width: 25%;">
							<span class="choose left pT10">Select Region</span>
							<?php
							if(isset($_SESSION['user']) && !empty($database_purchased_array)) {
								$database_purchased_array_unique=array_unique($database_purchased_array);
								$database_purchased_str=implode(',',$database_purchased_array_unique);
								$allMainDatabases = $admin->getMainDatabasesPurched($database_purchased_str,'Y');
							} else {
								$allMainDatabases = $admin->getMainDatabases('Y');
							}
								
							if(count($allMainDatabases)>0) { ?>							
								<form name="frmChangeDatabaseToBeUsed" action = "" method="post" id="frmChangeDatabaseToBeUsedfront" class="left pL30">
									<select name="databaseToBeUse" id="databaseToBeUse" style="">
										<option value="0"> Select Region </option>
										<?php foreach($allMainDatabases as $rowMain => $mainDetail){ 
											$databasename=explode('_',$mainDetail['databasename']);
											?>
											<option <?php if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse'] == $mainDetail['databasename']) { echo "selected='selected'"; } ?> value="<?php echo $mainDetail['databasename']; ?>"> <?php if(!empty($databasename)) { echo $dbnamemain=strtoupper($databasename[0].' '.$databasename[1]); } else { echo $dbnamemain=$mainDetail['databasename']; } ?> </option>
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
							
						</p>
					<!-- <div class="clear"></div> -->
				</div>
					
			<?php if((isset($_POST['databaseToBeUse']) && $_POST['databaseToBeUse']!='') || (isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!='')) { ?>
			
				<!-- <div class="clear"></div> -->
				<div class="form-div left" style="width: 45%;">
						<p style="margin-right: 10px;padding-top: 0;width: 25%;">
							<span class="choose left pT10">Select Category</span>
							
								<?php 
									if($categories >0) {?>
									
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
										<script type="text/javascript">
											jQuery(document).ready(function(){
												jQuery('#categoryid').change(function(){
													var values = jQuery('#categoryid').val();
													if(values != '0')										
													jQuery('#frmtoselectFormActtoCategory').submit();
												});
											});
										</script>
										
									<?php } ?>
							
						</p>
					<!-- <div class="clear"></div> -->
					</div>
				</div>
			<?php } ?>

			<?php if((isset($_POST['categoryid']) && $_POST['categoryid']!='') || (isset($_SESSION['categoryid']) && $_SESSION['categoryid']!='')) { ?>
				
				<div class="clear"></div>
				<div class="form-div">
				   <h2> <?php if(isset($db_name)) echo $db_name;?> <?php echo $cat_detail_arr['category_title'];?> Statistics contains the following categories and Forms </h2>
				<br class="clear" />
				<div class="from wdthpercent100">
					<?php if(!empty($allActiveDatabaseschunk)) {
							foreach($allActiveDatabaseschunk as $allActiveDatabases) { 
								
								  foreach($allActiveDatabases as $categorykey => $formsDetail) {

									    $formsDetailArray = array_sort($formsDetail, 'share', $order=SORT_ASC);

										if(!is_numeric($categorykey)) { ?>
											
											<div class="from-show">
												<h3>
													<?php if(isset($categorykey) && $categorykey !='0') { echo ucfirst($categorykey); } ?>
												</h3>
												
												<?php foreach($formsDetailArray as $key => $forms) { ?>
													<ul>
														<?php 
														if($forms['is_static_form'] == 'Y' && $forms['url']!='' ){ 
															
															if(isset($forms['db_select']) && $forms['db_select'] != '' && $forms['form_id_us']!='' ) { 
															$url=$forms['url'];
															?>
																<li>
																	<a href="?dbc=<?php echo base64_encode($forms['db_select']);?>&url=<?php echo $url ;?>"><?php if(isset($forms['form_name'])) { echo ucfirst($forms['form_name']); } else { echo ucfirst($forms['db_name']); } ?>&nbsp;(50 States)</a>
																</li>
															<?php } else { ?>
																<li>
																	<a href="<?php echo URL_SITE;?>/<?php echo $forms['url'];?>"><?php echo ucfirst($forms['db_name']);?></a>
																</li>
															<?php } ?>

														<?php } else { ?>
																
																<?php if(isset($forms['db_select']) && $forms['db_select'] != '' && $forms['form_id_us']!='' ) {
																	?>
																	<li>
																		<a href="?formid=<?php echo base64_encode($forms['form_id_us']);?>&dbc=<?php echo base64_encode($forms['db_select']);?>"><?php if(isset($forms['form_name'])) { echo ucfirst($forms['form_name']); } else { echo ucfirst($forms['db_name']); } ?>&nbsp;(50 States)</a>
																	</li>
															<?php } else { ?>
																	<li>
																		<a href="form.php?id=<?php echo base64_encode($forms['id']);?>"><?php echo ucfirst($forms['db_name']);?></a>
																	</li>
															<?php } ?>
														<?php } ?>
													</ul>	
												<?php } ?>
											</div>
											
										<?php  } else { ?>	
										<div class="from-show">
											
											<ul>
												<?php
												if($formsDetail['is_static_form'] == 'Y' && $formsDetail['url']!='' ){ ?>
													<li><a href="<?php echo URL_SITE;?>/<?php echo $formsDetail['url'];?>"><?php echo ucfirst($formsDetail['db_name']);?></a></li>
												<?php } else { ?>
													<li><a href="form.php?id=<?php echo base64_encode($formsDetail['id']);?>"><?php echo ucfirst($formsDetail['db_name']);?></a></li> 
												<?php } ?>
											</ul>
										</div>
										<?php }	?>	
										
									<?php } ?>
									<div class="clear">&nbsp;</div>
								
							<?php } ?>
					<?php } else {	echo '<tr><td>No Database Found</td></td>';	}	?>	
				</div>	
			</div>
			<?php } ?>	
		
	</div>

</section>
<!-- /container -->

<?php
include_once $basedir."/include/footerHtml.php";
?>