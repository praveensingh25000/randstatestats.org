<?php
/******************************************
* @Modified on Dec 26, 2012
* @Package: Rand
* @Developer: Saket Bisht
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/header.php";
?>
<?php
$admin = new admin();
$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(base64_decode($_GET['id']));
	$databaseDetail = $admin->getDatabase($dbid);
	if(!empty($databaseDetail)){
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description= stripslashes($databaseDetail['db_description']);
		$miscellaneous	= stripslashes($databaseDetail['db_misc']);
		$table		= stripslashes($databaseDetail['table_name']);
	}else{
		header('location: databases.php');
	}

	$related_DB = $admin->getAllDatabaseRelatedDatabases($dbid);
	
}else{
	header('location: index.php');
}
$all_search_criteria = $admin->selectAllSearchCriteria($dbid);
#echo '<pre>';
#print_r($all_search_criteria);
#echo '</pre>';
?>
 <!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">

	
		<!-- left side -->
		<div class="containerL">
			<h1><?php echo ucfirst($dbname); ?></h1>
			<p>
				<strong>Contains:</strong> <?php echo $dbsource; ?>
			</p>
			<!-- -->
			<div class="additional">
				<div id="add">
				<a title="Click to expand section" class="plus" id="togglebutton" href="#"></a>
				<a href="#">Additional background</a></div>
				<div class="content-main" id="content" style="background: none repeat scroll 0% 0% rgb(255, 255, 204); display: none;">
					<p><?php echo ucfirst($description); ?> </p>
					<p><?php echo ucfirst($miscellaneous); ?> </p>
				</div>
			</div>
			<div style="clear: both; padding: 15px 0;">
				<fieldset style="border: 1px solid #cccccc; padding: 10px;">
				<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Set your Search Criteria</legend>
			
				<form method="post" id="frmPost" name="frmPost" action="">
				<?php foreach($all_search_criteria as $key=>$search_content){ ?>
					<p><?php echo $search_content['label_name']?></p>
					 <div style="padding: 10px 0;">
						<input type="text" id="demo-input-prevent-duplicates_<?php echo $search_content['group_name']?>" name="blah" />
						<label id="field_required" class="displaynone error" for="input" generated="true" class="error">This field is required</label>
						<script type="text/javascript">
						$(document).ready(function() {							
							$("#demo-input-prevent-duplicates_<?php echo $search_content['label_name']?>").tokenInput("<?php echo URL_SITE; ?>/search.php", {
									preventDuplicates: true												
							});
						});
						
						</script>
					</div>
					<?php } ?>
					<table summary="Select the start month and year, and the end month and year." border="0">
						<tbody>
							<tr>
								<th rowspan="2" style="padding-right: 15px;" valign="middle">Define a Time Period for Data</th>
								<th class="rblue"><label for="syear">Start Year</label></th>
								<th class="rblue"><label for="eyear">End Year</label></th>
							</tr>
							<tr>
								<td>
									<div id="begYear"><select id="syear" size="1" name="syear">
									<option value="2001" selected="">2001</option>
									<option value="2002">2002</option>
									<option value="2003">2003</option>
									<option value="2004">2004</option>
									<option value="2005">2005</option>
									<option value="2006">2006</option>
									<option value="2007">2007</option>
									<option value="2008">2008</option>
									<option value="2009">2009</option>
									<option value="2010">2010</option>
									<option value="2011">2011</option>
									<option value="2012">2012</option>
									</select>
									</div>
								</td>
								<td>
									<div id="endYear"><select id="eyear" size="1" name="eyear">
									<option value="2001">2001</option>
									<option value="2002">2002</option>
									<option value="2003">2003</option>
									<option value="2004">2004</option>
									<option value="2005">2005</option>
									<option value="2006">2006</option>
									<option value="2007">2007</option>
									<option value="2008">2008</option>
									<option value="2009">2009</option>
									<option value="2010">2010</option>
									<option value="2011">2011</option>
									<option value="2012" selected="">2012</option>
									</select>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<br>
					
					<?php
					if(!empty($all_search_criteria)){
					?>

					<div class="submit1 submitbtn-div">
						<label for="submit" class="left">
							<input value="Submit" name="getresults" class="submitbtn" type="submit">
						</label>
						<label for="reset" class="right">
							<input id="reset" class="submitbtn" type="reset">
						</label>
					</div>
					<?php } ?>

				  </form>
				</fieldset>
			</div>
		</div>
		<!-- left side -->
		<!-- right side -->
		<aside class="containerR">
			<h2>Related database</h2>
			<ul>
				<?php if(mysql_num_rows($related_DB)>0){ while($r_DB = mysql_fetch_assoc($related_DB)) {
					$db_detail = $admin->getDatabase($r_DB['related_database_id']);	
				?>
				<li><a href="dbDetails.php?id=<?php echo base64_encode($db_detail['id']);?>"><?php echo ucfirst($db_detail['db_name']);?></a></li>
				<?php } } else { echo '<li>No Related Database Found</li>';} ?>
				
			</ul>
		</aside>
		<!-- /right side -->


		
	
<!-- /container -->

</div>
		
</section>
<?php
include_once $basedir."/include/footer.php";
?>