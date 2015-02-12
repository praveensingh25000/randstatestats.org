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

$admin					= new admin();
$categoriesresult_res   = $admin->getAllParentCategories();
//$categoriesresult_res = $admin->showAllCategories();
$total_defalult         = $db->count_rows($categoriesresult_res);
$categories_default     = $db->getAll($categoriesresult_res);

$featured = $total = 0;

if(isset($_REQUEST['featured'])) {
	$featured		= $_SESSION['featured'] = trim($_REQUEST['featured']);
} else if(isset($_SESSION['featured'])) {
	$featured = $_SESSION['featured'];
} else {
	$featured = 1;
}

$categoriesresult_res	= $admin->getAllFeaturedUnfeaturedCats($featured);
$total					= $db->count_rows($categoriesresult_res);
$categories			    = $db->getAll($categoriesresult_res);
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
			
			<h2> List of <?php if(isset($featured) && $featured == '1') { echo 'Global featured';} else { echo 'Global unfeatured'; } ?> Categories <?php if(isset($total)) echo '( '.$total.' )'; ?></h2><br>

			<!-- FORM TO SELECT DATABASE ACT TO CATEGORY -->
			<div id="" class="wdthpercent100 tabnav">
				<div class="wdthpercent50 left">&nbsp;</div>
				<div class="wdthpercent40 right listform" style="padding:0px;">
					<div class="wdthpercent40 left"> Select category type: </div>
					<div class="wdthpercent60 left">
						<?php 
						if(!empty($categories_default)) {?>
							<form name="frmtofeaturedCategory" action = "" method="post" id="frmtofeaturedCategory">
								<select class="" id="featured" name="featured">
									<option <?php if(isset($_SESSION['featured']) && $_SESSION['featured'] == '1') {?> selected="selected" <?php } ?> value="1"> Select Featured Category </option>
									<option <?php if(isset($_SESSION['featured']) && $_SESSION['featured'] == '0') {?> selected="selected" <?php } ?> value="0"> Select unfeatured Category </option>									
								</select>
								<input type="hidden" name="select_category_form">
							</form>
							<script type="text/javascript">
								jQuery(document).ready(function(){
									jQuery('#featured').change(function(){					
										jQuery('#frmtofeaturedCategory').submit();			
									});
								});
							</script>							
						<?php } ?>
					</div>				
				</div>
			</div>
			<br class="clear" />
			<!-- /FORM TO SELECT DATABASE ACT TO CATEGORY -->

			<div id="" class="pT20">
				<?php if(!empty($categories)){ ?>
					
					<h4>Drag and drop the Category in order to display in the Navigation <span class="right"><A HREF="javascript:;" onclick="javascript: window.history.go(-1);">Back</A></span></h4><BR>
					
					<div style="display:none;" class="pT5 pB10 txtcenter show_cat_order_msg"></div>
			
					<form id="frmAllCat" name="frmAllCat" method="post">
						<table id="table-category-sort" cellspacing="0" cellpadding="7" border="1" class="collapse" id="grid_view" width="100%">
							<tbody>
								<tr>
									<th bgcolor="#eeeeee">order ID</th>
									<th bgcolor="#eeeeee">Category Name</th>
									<th bgcolor="#eeeeee">Parent Category</th>								
								</tr>
								<?php if($categories >0){ 
									$sortorder=1;
									foreach($categories as $key => $categoryDetail){
										$parentCat = "Global Category";
										if($categoryDetail['parent_id']!='0'){
											$parentCatDetail = $admin->getPatCategory($categoryDetail['parent_id']);
											$parentCat = $parentCatDetail['category_title'];
										}
										?>
										<tr id="<?php echo $categoryDetail['id'];?>" class="remove_class selected_<?php echo $categoryDetail['id'];?>">
											<td class="dragHandle" align="middle"><?php echo $sortorder;?></td>
											<td class="dragHandle" align="left"><?php echo $categoryDetail['category_title']; ?></td>
											<td class="dragHandle" align="center"><?php echo $parentCat; ?></td>
																				
											<script type="text/javascript">
												jQuery(document).ready(function(){
													jQuery(".selected_<?php echo $categoryDetail['id'];?>").hover(function () {
														jQuery(".remove_class").removeClass("tab");
														jQuery(".selected_<?php echo $categoryDetail['id'];?>").addClass("tab");
													});
													jQuery("body,.main-cell").hover(function () {
														jQuery(".remove_class").removeClass("tab");			
													});
												});
											</script>
										</tr>

									<?php $sortorder++; } ?>							
								
								<?php } ?>	
								
								<SCRIPT LANGUAGE="JavaScript">
								jQuery(document).ready(function(){	
									$('#table-category-sort').tableDnD({
										onDrop: function(table, row) {
											var rows = table.tBodies[0].rows;
											var debugStr = ""
											for (var i=0; i<rows.length; i++) {
												debugStr += rows[i].id+" ";
											}
											loader_show();
											jQuery(".show_cat_order_msg").hide();	
											jQuery.ajax({
												type: "POST",
												data: "sortCategory="+debugStr,
												url : URL_SITE+"/admin/adminAction.php",												
												success: function(msg){
													loader_unshow();
													jQuery(".show_cat_order_msg").html(msg).show();						
												}
											});																		
										},
										dragHandle: ".dragHandle"
									});
								});
								</SCRIPT>

							</tbody>
						</table>
					</form>

				<?php } else{ ?>
					<h4>No Sub Category added Yer</h4>
				<?php }?>

			</div>			
		 </div>
		<!-- left side -->		

	</div>
	
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>