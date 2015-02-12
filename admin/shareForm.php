<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

if(!isset($_GET['id']) && isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']=='rand_usa'){
	$_SESSION['msgalert'] = "Please select a USA Database to share this form.";
	header('location: databases.php');
}

checkSession(true);

$admin = new admin();

$categoryidArray = $selecteddbArray = $selectedCatArray = $selecteddbArrayAll = array();

if(isset($_GET['action']) && $_GET['action'] == 'share' && isset($_GET['id']) && $_GET['id']!='') {
	
	$dbid						= trim(base64_decode($_GET['id']));
	$databaseDetail				= $admin->getDatabase($dbid, true);
	$table						= $admin->getDatabaseTables($dbid,true);

	$allMainDatabases			= $admin->getMainDatabases('Y');
	$sharedetailall_res			= $admin->getshareDetail($dbid);
	$shareDetailAll				= $dbDatabase->getAll($sharedetailall_res);

	$categoryDetail_res 		= $admin->getAllDatabaseCategories($dbid);
	$categoryDetailArray		= $dbDatabase->getAll($categoryDetail_res);
	if(!empty($categoryDetailArray)){
		foreach($categoryDetailArray as $categoryDetail){
			if($categoryDetail['cat_type'] =='p'){
				$categoryidArray[]	= $categoryDetail['category_id'];
			}
		}
	}

	if(!empty($shareDetailAll)) {
		foreach($shareDetailAll as $values){
			$selecteddbArray[]	= $values['display_dbname'];
			$selectedCatArray[] = $values['category_id'];
		}
	}
}

if(isset($_POST['add_share_form'])){

	$shareArray				= $_POST['categories'];
	$form_id				= $_POST['form_id'];
	$form_name				= $_POST['form_name'];

	$is_static_form			= $_POST['is_static_form'];
	$url					= $_POST['url'];

	$select_dbname			= $_SESSION['databaseToBeUse'];
	$insert					= $admin->insertshareDetail($shareArray,$form_id,$form_name,$select_dbname,$is_static_form,$url);

	$_SESSION['msgsuccess']	= 'Share detail has been updated successfully';
	header('location: shareForm.php?tab=9&action=share&id='.base64_encode($form_id).'');
	exit;
}

//echo "<pre>";print_r($selecteddbArray);echo "</pre>";
//echo "<pre>";print_r($categoryidArray);echo "</pre>";
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

			<div style="clear: both; padding: 15px 0;">
				<fieldset style="border: 1px solid #cccccc; padding: 10px;">
				<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Edit Database '".$databaseDetail['db_name']."'"; } else { echo "Add Database"; } ?> </legend>
					
					<?php include("formNavigation.php"); ?>
					
					<?php if(!empty($allMainDatabases)) { ?>
						<div class="wdthpercent100">
							<h4>Select a database to display this Form</h4><br>						
														
							<form method="post" action="" id="select_share_db" name="select_share_db">
								<?php foreach($allMainDatabases as $rowMain => $mainDetail){
									  if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!= $mainDetail['databasename']) { ?>
										  <div class="wdthpercent20 left">
											<input <?php if(!empty($selecteddbArray) && in_array($mainDetail['databasename'],$selecteddbArray)){ ?> checked="checked" <?php } ?> name="databases[]" class="" id="db_select_<?php echo $mainDetail['id']; ?>" onclick="javascript: selectSubCategoryAll('<?php echo $_SESSION['databaseToBeUse']; ?>','<?php echo $mainDetail['databasename']; ?>','<?php echo $mainDetail['id']; ?>','<?php echo $dbid; ?>');" type="checkbox" title=""  value="<?php echo $mainDetail['id']; ?>">&nbsp;&nbsp;<?php echo $mainDetail['db_code']; ?>
										  </div>
									  <?php } ?>
								<?php } ?>	

								<br class="clear" />

								<div id="display_all_categoty_ajax_ca">
									
									<?php
									if(!empty($categoryidArray) && !empty($selectedCatArray) && !empty($selecteddbArray) && in_array('rand_california',$selecteddbArray)) { ?>
										
										<div style="margin: 12px 0px;padding:5px 0px 10px 10px;height:auto;overflow: auto;width: auto;border: 1px solid #cccccc;">
										<h2 class="pT10">California</h2>

											<?php
											$dbname='rand_california';
											
											$dbca = new db('localhost', 'root', 'j0eN@t!on', $dbname);

											$admin = new admin();

											$subCategoriesCAArray = array();

											foreach($categoryidArray as $category_id){

												$category_sqlca	= "SELECT * from category WHERE id = '".$category_id."' and is_active = '1' ";
												$category_resca	= mysql_query($category_sqlca, $db->conn);
												$Categoryca = mysql_fetch_assoc($category_resca);

												$sql="SELECT * from categories WHERE parent_id = '".$category_id."' and is_active = '1' order by id DESC";
												$res=mysql_query($sql,$dbca->conn);
												while($subCategories =mysql_fetch_assoc($res)){
													$subCategoriesCAArray[]=$subCategories;
												}
												?>
												<br class="clear" />
												<p>Select Sub Categories of <b><?php if(!empty($Categoryca['category_title'])) { echo ucwords($Categoryca['category_title']); } ?></b> for <b>California</b> to display this form<em>*</em></p>
												<div style="margin: 12px 0px;padding:5px 0px 10px 0px;max-height:200px;overflow: auto;width: 344px;border: 1px solid #cccccc;">
													<?php 
													
													if(!empty($subCategoriesCAArray)) { ?>
													<div class="pL10">
														<table border="0" cellpadding="4">
															<?php
															foreach($subCategoriesCAArray as $key => $categoryDetailCA) { 
															$checked = $admin->selectedCategoryCheck($dbid,$categoryDetailCA['id'], $dbname,$category_id);												
															?>
															<tr>
																<td width="10%">
																	<input <?php if(!empty($checked)) { echo 'checked="checked"'; } ?> class="required" type="radio" name="categories[<?php echo $category_id;?>][rand_california]" value="<?php echo $categoryDetailCA['id']; ?>" />
																</td>
																<td><!-- <?php echo $categoryDetailCA['id']; ?>&nbsp; --><?php echo $categoryDetailCA['category_title']; ?></td>
															</tr>
															<?php } ?>
														</table>
													</div>
													<?php } else {
														echo 'No Subcategory added';
													} ?>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
								
								<div id="display_all_categoty_ajax_ny">

									<?php									
									if(!empty($categoryidArray) && !empty($selectedCatArray) && !empty($selecteddbArray) && in_array('rand_newyork',$selecteddbArray)) { ?>
										<div style="margin: 12px 0px;padding:5px 0px 10px 10px;height:auto;overflow: auto;width: auto;border: 1px solid #cccccc;">
										<h2 class="pT10">Newyork</h2>

											<?php
											$dbname2='rand_newyork';
											
											$dbny = new db('localhost', 'root', 'j0eN@t!on', $dbname2);

											$admin = new admin();

											$subCategoriesNYArray = array();

											foreach($categoryidArray as $category_id){

												$category_sqlny	= "SELECT * from category WHERE id = '".$category_id."' and is_active = '1' ";
												$category_resny	= mysql_query($category_sqlny, $db->conn);
												$Categoryny = mysql_fetch_assoc($category_resny);

												$sql="SELECT * from categories WHERE parent_id = '".$category_id."' and is_active = '1' order by id DESC";
												$res=mysql_query($sql, $dbny->conn);
												while($subCategories =mysql_fetch_assoc($res)){
													$subCategoriesNYArray[]=$subCategories;
												}
												?>
												<br class="clear" />
												<p>Select Sub Categories of <b><?php if(!empty($Categoryny['category_title'])) { echo ucwords($Categoryny['category_title']); } ?></b> for <b>Newyork</b> to display this form<em>*</em></p>
												<div style="margin: 12px 0px;padding:5px 0px 10px 0px;max-height:200px;overflow: auto;width: 344px;border: 1px solid #cccccc;">
													<?php 
													if(!empty($subCategoriesNYArray)) { ?>
													<div class="pL10">
														<table border="0" cellpadding="4">
															<?php foreach($subCategoriesNYArray as $key => $categoryDetailNY){
															$checked = $admin->selectedCategoryCheck($dbid,$categoryDetailNY['id'], $dbname2,$category_id);											
															?>
															<tr>
																<td width="10%">
																	<input <?php if(!empty($checked)) { echo 'checked="checked"'; } ?> class="required" type="radio" name="categories[<?php echo $category_id;?>][rand_newyork]" value="<?php echo $categoryDetailNY['id']; ?>"/>
																</td>
																<td><!-- <?php echo $categoryDetailNY['id']; ?>&nbsp; --><?php echo $categoryDetailNY['category_title']; ?></td>
															</tr>
															<?php } ?>
														</table>
													</div>
													<?php } else {
														echo 'No Subcategory added';
													}?>
												</div>
											<?php } ?>
										</div>
									<?php } ?>								
								</div>

								<div id="display_all_categoty_ajax_tx">
									
									<?php									
									if(!empty($categoryidArray) && !empty($selectedCatArray) && !empty($selecteddbArray) && !empty($selecteddbArray) && in_array('rand_texas',$selecteddbArray)) { ?>

										<div style="margin: 12px 0px;padding:5px 0px 10px 10px;height:auto;overflow: auto;width: auto;border: 1px solid #cccccc;">
										<h2 class="pT10">Texas</h2>

											<?php
											$dbname1='rand_texas';

											$dbtx = new db('localhost', 'root', 'j0eN@t!on', $dbname1);

											$admin = new admin();

											$subCategoriesTXArray = array();

											foreach($categoryidArray as $category_id){

												$category_sqltx = "SELECT * from category WHERE id = '".$category_id."' and is_active = '1' ";
												$category_restx = mysql_query($category_sqltx, $db->conn);
												$Categorytx     = mysql_fetch_assoc($category_restx);

												$sql="SELECT * from categories WHERE parent_id = '".$category_id."' and is_active = '1' order by id DESC";
												$res=mysql_query($sql,$dbtx->conn);
												while($subCategories =mysql_fetch_assoc($res)){
													$subCategoriesTXArray[]=$subCategories;
												}
												?>
												<br class="clear" />
												<p>Select Sub Categories of <b><?php if(!empty($Categorytx['category_title'])) { echo ucwords($Categorytx['category_title']); } ?></b> for <b>Texas</b> to display this form<em>*</em></p>
												<div style="margin: 12px 0px;padding:5px 0px 10px 0px;max-height:200px;overflow: auto;width: 344px;border: 1px solid #cccccc;">
													<?php 
													if(!empty($subCategoriesTXArray)) { ?>
													<div class="pL10">
														<table border="0" cellpadding="4">
															<?php foreach($subCategoriesTXArray as $key => $categoryDetailTX){
															$checked = $admin->selectedCategoryCheck($dbid,$categoryDetailTX['id'], $dbname1,$category_id);									
															?>
															<tr>
																<td width="10%">
																	<input <?php if(!empty($checked)) { echo 'checked="checked"'; } ?> id="" class="required" type="radio" name="categories[<?php echo $category_id;?>][rand_texas]" value="<?php echo $categoryDetailTX['id']; ?>"/>
																</td>
																<td><!-- <?php echo $categoryDetailTX['id']; ?>&nbsp; --><?php echo $categoryDetailTX['category_title']; ?></td>
															</tr>
															<?php } ?>
														</table>
													</div>
													<?php } else {
														echo 'No Subcategory added';
													} ?>
												</div>
											<?php } ?>	
										</div>
									<?php } ?>
								</div>

								<br class="clear" />
		
								<div class="submit1 submitbtn-div" <?php if(empty($selecteddbArray)) { ?> style="display:none" <?php } ?>>
									<label for="submit" class="left">									
										<input type="hidden" name="form_id" value="<?php echo $dbid; ?>">
										<input type="hidden" name="form_name" value="<?php echo $databaseDetail['db_name'];?>">
										<input type="hidden" name="is_static_form" value="<?php echo $databaseDetail['is_static_form'];?>">
										<input type="hidden" name="url" value="<?php echo $databaseDetail['url'];?>">
										<input type="submit" value="Submit" name="add_share_form" class="submitbtn" id="add_share_form">
									</label>
									<label for="reset" class="right">
										<input type="reset" id="reset" class="submitbtn">
									</label>
								</div>								

							</form>

						</div>

					<?php } ?>	

				</fieldset>
			</div>

		</div>
		<!-- left side -->			

	</div>		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>