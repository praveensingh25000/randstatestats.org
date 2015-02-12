<?php
/******************************************
* @Modified on Feb 05, 2013
* @Package: Rand
* @Developer: Praveen Singh.
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

if(!isset($_GET['cat'])){
	header('location:'.URL_SITE.'/index.php');
}

$_SESSION['cat'] = $cat = $_GET['cat'];

$catObj = new Category();
$admin = new admin();
$user = new user();

$allActiveDatabases = $catObj->showAllActiveDatabase($cat);
$allActiveDatabaseschunk = array_chunk($allActiveDatabases, 2, true);
$cat_detail_arr = $admin->getPatCategory($cat);

if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!=''){
	$siteMainDBDetail	= $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
	$db_name			= $siteMainDBDetail['database_label'];
}
//echo "<pre>";print_r($allActiveDatabases);echo "</pre>";
?>
<section id="container">
	<div id="inner-mainshell">

		<h2>
			<?php if(isset($db_name)) echo $db_name;?>&nbsp;<?php echo $cat_detail_arr['category_title'];?> Statistics contains the following categories and databases: 
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
																<a href="form.php?id=<?php echo base64_encode($forms['id']);?>"><?php if($forms['db_name'] =='AIDS and HIV Cases'){
																	echo stripslashes($forms['db_name']);
																}else{
																	echo stripslashes($forms['db_name']);
																}?></a>
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
			<?php } else {	echo '<div class="from-show"><h4>No Forms Found.</h4></div>';	}	?>		
		</div>
	</div>
</section>
<div class="clear"></div>

<?php
include_once $basedir."/include/footerHtml.php";
?>