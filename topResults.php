<?php
/******************************************
* @Modified on Jan 11, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

global $db;

$category = new category();

$searchstr   = (isset($_REQUEST['search']))?$_REQUEST['search']:'';
$resultData  = $category->getSearchedForms($searchstr);
$total       = count($resultData);
?>
<!-- container -->
<section id="container">
 <!-- main div -->
	<div class="main-cell">	
		<h1 >Search Results <?php if(isset($_REQUEST['search'])){ echo 'For "'.stripslashes($_REQUEST['search']).'"'; }?></h1>
		<div class="clear pT10"></div>

		<div class="search-table-data">
			<?php if($total>0){ ?>
			<table id="" class="data-table">
				
				<thead>
					<tr>
						<th class="" width="25%">Form Name</th>
						
						<th class="" width="20%">Geographic Coverage</th>
						<th class="" width="8%">Periodicity</th>
						<th class="" width="8%">Data<br>Series</th>
						<th class="" width="8%">Next<br>Update</th>
						<th class="" width="25%">Data Source</th>
						<th class="" width="5%">URL</th>
					</tr>
				</thead>

				<tbody>

				<?php foreach($resultData as $formDetail){
							if($formDetail['is_active']){

								$url = URL_SITE."/form.php?id=".base64_encode($formDetail['id']);
								if($formDetail['is_static_form'] == 'Y' && $formDetail['url']!='' ){ 
									  $url = URL_SITE."/".$formDetail['url'];
								}

								if(isset($formDetail['db_select']) && $formDetail['db_select'] != '' && $formDetail['share']=='shared' && $formDetail['is_static_form']=='Y' ) { 
									$url = "changeRegions.php?dbc=".base64_encode($formDetail['db_select'])."&url=".$formDetail['url'];
								} else if(isset($formDetail['db_select']) && $formDetail['db_select'] != '' && $formDetail['share']=='shared') { 
									$url = "changeRegions.php?dbc=".base64_encode($formDetail['db_select'])."&formid=".base64_encode($formDetail['id']);
								}

					?>
					<tr>
							<td valign="top"><?php echo stripslashes($formDetail['db_name']); ?></td>
							
							<td valign="top"><?php echo stripslashes($formDetail['db_geographic']); ?></td>
							<td valign="top"><?php echo stripslashes($formDetail['db_periodicity']); ?></td>
							<td valign="top">
								<?php if($formDetail['db_dataseries']!=''){
									echo stripslashes($formDetail['db_dataseries']);
								} else {
									echo "NA";
								}?>
							</td>
							<td valign="top"><?php if(isset($formDetail['db_nextupdate']) && $formDetail['db_nextupdate'] != '0000-00-00'){ echo $formDetail['db_nextupdate']; } else { echo "NA"; }  ?></td>
							<td valign="top"><?php echo stripslashes($formDetail['db_source']); ?></td>
							<td valign="top">							
								<a href="<?php echo $url; ?>">View</a>
							</td>
					</tr>
				<?php }
					} ?>

				</tbody>
			</table>
			<?php } else { ?>
			<p>No records found</p>
			<?php } ?>

		</div>
		<!-- /graph -->
	</div>		
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>