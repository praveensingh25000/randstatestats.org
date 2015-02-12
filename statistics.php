<?php
/******************************************
* @Modified on September 01, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://randstatestats.org
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

//checkSession(true);

$admin = new admin();

$catObj = new Category();

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
	$databasesArray = $catObj->showAllActiveDatabaseWithoutSharedCategory($cat, 1);
	$parentCatDetail = $admin->getPatCategory($cat);
} else {
	$databasesArray = $catObj->showAllActiveDatabaseWithoutSharedCategory();
	$parentCatDetail = array('category_title' => 'All');
}

?>

 <!-- container -->
<section id="container">
	
	<!-- main div -->
	<section id="inner-content" class="conatiner-full" >
			
		<h3><?php if(isset($db_name)) echo $db_name;?> Database Descriptions</h3>
		<br class="clear" />
					
		<?php if(!empty($categories_default)){?>
		<div class="statistics">			
			
			<h4>For each of the following statistics categories, click on the radio button for an overview of the databases available, the periodicity, data sources, time periods, and geographic regions covered. </h4>
			<br class="clear" />

			<form id="frmStatCat" name="frmStatCat">
				<table class="data-table">
					<tr>
						<?php 
						$categoriesArray = array_chunk($categories_default, 5, true);
						foreach($categoriesArray as $keypa => $categories_default){?>
						<td valign="top">
						<?php foreach($categories_default as $key => $categoryDetail){ ?>				
							  <p><input type="radio" onclick="javascript: loader_show();jQuery('#frmStatCat').submit();" value="<?php echo $categoryDetail['id'];?>" name="cat" <?php if($cat == $categoryDetail['id']){ echo "checked='checked'";} ?> >&nbsp;&nbsp;<?php if($cat == $categoryDetail['id']){ echo '<b>';}?><?php echo ucwords($categoryDetail['category_title']);?> <?php if($cat == $categoryDetail['id']){ echo '</b>';}?></p><br/>
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
		<?php if(!empty($databasesArray)){?>
			
			<h3><?php if(isset($parentCatDetail)) { 
				if($parentCatDetail['category_title']!='All'){
					echo $parentCatDetail['category_title'].' Category';
				}else{
					echo $parentCatDetail['category_title'].' Categories';
				}
			}?><!-- <?php if(isset($total)) echo '('.$total.')';?> --></h3>
			<br class="clear" />

			<table class="data-table">
				<thead>
					<tr class="">
						<th class="thead_gr s11x">Database Title</th>
						<th class="thead_gr s11x">Geographic<br>Coverage</th>
						<th class="thead_gr s11x">Periodicity</th>
						<th class="thead_gr s11x">Data<br>Series</th>
						<th class="thead_gr s11x">Next<br>Update</th>
						<th class="thead_gr s11x">Data Source</th>
						<th class="thead_gr s11x">URL</th>							
					</tr>
				</thead>
				<?php foreach($databasesArray as $Key => $formDetail){ ?>
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
							<?php if(isset($formDetail['db_nextupdate']) && $formDetail['db_nextupdate'] != '0000-00-00'){ echo $formDetail['db_nextupdate']; } else { echo "NA"; } ?>
						</td>

						<td valign="top">
							<?php if(isset($formDetail['db_sourcelink']) && $formDetail['db_sourcelink'] != ''){ ?><a target="_blank" href="<?php echo $formDetail['db_sourcelink']; ?>"><?php }?><?php echo stripslashes($formDetail['db_source']); ?><?php if(isset($formDetail['db_sourcelink']) && $formDetail['db_sourcelink'] != ''){ ?></a><?php } ?>
							<?php //echo stripslashes($formDetail['db_source']); ?>
						</td>

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
		<?php } else { ?>
			<h4>No database added Yet.</h4>
		<?php } ?>
		</div>	
	</section>	
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/footerHtml.php";
?>