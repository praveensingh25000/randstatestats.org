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

$databasesArray = array();

if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!=''){
	$siteMainDBDetail	= $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
	$db_name			= $siteMainDBDetail['database_label'];
}

$categoriesresult_res = $admin->getAllParentCategories();
$total = $db->count_rows($categoriesresult_res);
$categories_default = $db->getAll($categoriesresult_res);

$cat = '';
if(isset($_GET['cat']) && $_GET['cat']!=''){
	$cat = $_GET['cat'];
	$databasesResult = $admin->getCategoryForms($cat);
	$parentCatDetail = $admin->getPatCategory($cat);
} else {
	$databasesResult = $admin->showAllDatabases();
	$parentCatDetail = array('category_title' => 'All');
}

if(isset($databasesResult))

$databasesArray = $db->getAll($databasesResult);
if(!empty($databasesArray)){
	foreach($databasesArray as $key => $databasesArrayAll){
		$checkifSearchCateria = $admin->selectAllSearchCriteria($databasesArrayAll['id']);
		if((isset($checkifSearchCateria) && count($checkifSearchCateria) > 0) || (isset($databasesArrayAll['is_static_form']) && $databasesArrayAll['is_static_form']=='Y')) {
			$databases[] = $databasesArrayAll;
		}
	}
	$total  = count($databases);
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
			
			<h3><?php if(isset($db_name)) echo $db_name;?> Database Descriptions</h3>
						
			<?php if(!empty($categories_default)){?>
			<div class="statistics">
				
				<br class="clear" />
				<h4>For each of the following statistics categories, click on the radio button for an overview of the databases available, the periodicity, data sources, time periods, and geographic regions covered. </h4>
				<br class="clear" />

				<form id="frmStatCat" name="frmStatCat">
					<table class="data-table" cellspacing="1" cellpadding="20">
						<tr>
							<?php 
							$categoriesArray = array_chunk($categories_default, 5, true);
							foreach($categoriesArray as $keypa => $categories_default){?>
							<td valign="top">
							<?php foreach($categories_default as $key => $categoryDetail){ ?>				
								  <p><input type="radio" onclick="javascript: loader_show();jQuery('#frmStatCat').submit();" value="<?php echo $categoryDetail['id'];?>" name="cat" <?php if($cat == $categoryDetail['id']){ echo "checked='checked'";} ?> >&nbsp;&nbsp;<?php if($cat == $categoryDetail['id']){ echo '<b>';}?><?php echo ucwords($categoryDetail['category_title']);?> <?php if($cat == $categoryDetail['id']){ echo '</b>';}?> </p><br/>
								<?php } ?>						
							</td>
							<?php } ?>
						</tr>
					</table>
				</form>
			</div>
			<br class="clear" />
			<?php } ?>			

			<div class="statistics">
			<?php if(!empty($databases)){?>
				
				<h3><?php if(isset($parentCatDetail)) { echo $parentCatDetail['category_title'].' Category';}?><!-- <?php if(isset($total)) echo '('.$total.')';?> --></h3>
				<br class="clear" />

				<table cellpadding="6" cellspacing="0" width="100%" border="1">
					<thead>
						<tr class="">
							<th class="thead_gr s11x">Description</th>
							<th class="thead_gr s11x">Geographic<br>Coverage</th>
							<th class="thead_gr s11x">Periodicity</th>
							<th class="thead_gr s11x">Data<br>Series</th>
							<th class="thead_gr s11x">Next<br>Update</th>
							<th class="thead_gr s11x">Data Source</th>
							<th class="thead_gr s11x">URL</th>							
						</tr>
					<thead>
					<?php foreach($databases as $Key => $formDetail){ ?>
					<tbody>
						<tr class="">
							<td valign="top">
							<?php if($formDetail['is_static_form'] == 'Y' && $formDetail['url']!='' ){ ?>
									<a target="_blank" href="<?php echo URL_SITE;?>/<?php echo $formDetail['url'];?>"><?php echo $formDetail['db_name']; ?></a>
								<?php } else { ?>
									<a target="_blank" href="<?php echo URL_SITE;?>/form.php?id=<?php echo base64_encode($formDetail['id']);?>"><?php echo $formDetail['db_name']; ?></a> 
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
								<?php if(isset($formDetail['db_nextupdate']) && $formDetail['db_nextupdate'] != '0000-00-00'){ echo $formDetail['db_nextupdate']; } else { echo "NA"; } ?>
							</td>
							<td valign="top"><?php echo stripslashes($formDetail['db_datasource']); ?></td>

							<td valign="top">
								<?php if($formDetail['is_static_form'] == 'Y' && $formDetail['url']!='' ){ ?>
									<a target="_blank" href="<?php echo URL_SITE;?>/<?php echo $formDetail['url'];?>">View</a>
								<?php } else { ?>
									<a target="_blank" href="<?php echo URL_SITE;?>/form.php?id=<?php echo base64_encode($formDetail['id']);?>">View</a> 
								<?php } ?>								
							</td>				
						</tr>
					</tbody>
					<?php } ?>
				</table>
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
