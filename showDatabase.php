<?php
/******************************************
* @Modified on Dec 26, 2012
* @Package: Rand
* @Developer: Saket Bisht
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/header.php";

if(!isset($_GET['cat'])){
	header('location:'.URL_SITE.'/index.php');
}

$cat = $_GET['cat'];

$catObj = new Category();
$admin = new admin();
$user = new user();


$allActiveDatabases = $catObj->showAllActiveDatabase($cat);

//echo "<pre>";print_r($allActiveDatabases);echo "</pre>";

$subCategories = $catObj->showSubCategories($cat);
$subCatArray = array_chunk($subCategories, 2, true);

$cat_detail_arr = $catObj->getCatById($cat);

$related_DB_Main = $catObj->databaseByCategory($cat);
?>
	<section id="container">
	<div class="main-cell">
		<div class="containerL">
			<h2>RAND Texas <?php echo $cat_detail_arr['category_title'];?> Statistics contains the following categories and databases: </h2>
			<div class="clear pT30"></div>
			<table width="100%" cellspacing="6" cellpadding="6">
			<?php if(!empty($subCatArray)) { 
			foreach($subCatArray as $categoriesArray){ ?>
				<tr>
					<?php foreach($categoriesArray as $subCat){ 
						$related_DB = $catObj->databaseByCategory($subCat['id']);
						
					?>
					<td valign="top">
							<b><?php echo ucfirst($subCat['category_title']);?></b>
							
							<?php if(!empty($subCatArray)) { ?>							
								<ul>
									<?php foreach($related_DB as $db_array){ 
										$checkifSearchCateria = $admin->selectAllSearchCriteria($db_array['id']);

										if(isset($checkifSearchCateria) && count($checkifSearchCateria) > 0) {
											if($db_array['is_static_form'] == 'Y' && $db_array['url']!='' ){ ?>
												<li><a href="<?php echo URL_SITE;?>/<?php echo $db_array['url'];?>"><?php echo ucfirst($db_array['db_name']);?></a></li>
											<?php } else { ?>
												<li><a href="form.php?id=<?php echo base64_encode($db_array['id']);?>"><?php echo ucfirst($db_array['db_name']);?></a></li> 
											<?php } 
										}
									} 
									?>
								</ul>
							<?php } ?>
					</td>
					<?php } ?>
				</tr>

			<?php }
			} else if(!empty($related_DB_Main)){ ?>
				
				<ul>
					<?php foreach($related_DB_Main as $db_array){ 						
						$checkifSearchCateria = $admin->selectAllSearchCriteria($db_array['id']);
						if(isset($checkifSearchCateria) && count($checkifSearchCateria) > 0) {?>		
							<li><a href="form.php?id=<?php echo base64_encode($db_array['id']);?>"><?php echo ucfirst($db_array['db_name']);?></a></li> 
					<?php }
					}
					?>
				</ul>
					

			<?php
			} else {

				echo '<tr><td>No Database Found</td></td>';
			}
			?>
			</table>
		</div>
	</div>
	</section>

<?php
include_once $basedir."/include/footer.php";
?>