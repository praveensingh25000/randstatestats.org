<?php
/******************************************
* @Modified on May 10, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(base64_decode($_GET['id']));
	$databaseDetail = $admin->getDatabase($dbid);

	$columnDisplaySettings = $admin->getTableColumnsDisplaySettings($dbid);
	
	if(!empty($databaseDetail)){
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description= stripslashes($databaseDetail['db_description']);
		$miscellaneous	= stripslashes($databaseDetail['db_misc']);
		################################ SELECTING ALL TABLES RELATED TO DB #########################################

		$allDbTables = $admin->getDatabaseTables($dbid);

		################################ SELECTING ALL TABLES RELATED TO DB #########################################
		if(isset($allDbTables[0]['table_name']))
		{
			$table = stripslashes($allDbTables[0]['table_name']);
		}
		else
		{
			 $table		= stripslashes($databaseDetail['table_name']);	// have to remove this When all data of DB tables goes to datasebae_tables
		}
	}
}

if(isset($_GET['table']) && $_GET['table']!=''){		// if only database is selected.
	 $table = trim(stripslashes(base64_decode($_GET['table'])));	
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
			<div style="clear: both; padding: 15px 0;">
				<fieldset style="border: 1px solid #cccccc; padding: 10px;">
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Edit Database '".stripslashes($databaseDetail['db_name'])."'"; } else { echo "Add Database"; } ?> </legend>
						
					<?php include("formNavigation.php"); ?><BR>

					<div class="pT10 pB10">

						<div style="display:none;" class="pT5 pB10 txtcenter show_order_msg"></div>

						<h4>Drag and drop the Coloums in order to display in the search result <span class="right"><A HREF="javascript:;" onclick="javascript: window.history.go(-1);">Back</A></span></h4><BR>

						<?php if(empty($columnDisplaySettings)){
							echo '<H4>There is no coloum added for sorting.</H4>';
						} else {?>
							<form id="sortcoloumdata" name="sortcoloumdata" method="post" action="">
								<table id="table-sort" cellspacing="0" cellpadding="6" border="1" class="collapse"width="50%">									
									<tr>
										<th bgcolor="#eeeeee">Order List</th>
										<th bgcolor="#eeeeee">Colum Name</th>
									</tr>
									<?php foreach($columnDisplaySettings as $keyColumnDisplay => $columnSettings){ ?>
										<tr id="<?php echo $columnSettings['id'];?>">
											<td class="dragHandle"><B><?php echo $columnSettings['orderby'];?></B></td>
											<td  class="dragHandle"><?php echo $columnSettings['column_name'];?></td>
										</tr>
									<?php } ?>
								</table>

								<SCRIPT LANGUAGE="JavaScript">
								jQuery(document).ready(function(){	
									$('#table-sort').tableDnD({
										onDrop: function(table, row) {
											var rows = table.tBodies[0].rows;
											var debugStr = ""
											for (var i=0; i<rows.length; i++) {
												debugStr += rows[i].id+" ";
											}
											loader_show();
											jQuery(".show_order_msg").hide();	
											jQuery.ajax({
												type: "POST",
												data: "tablename=columns_display_settings&coloumname=orderby&sortorder="+debugStr,											
												url : URL_SITE+"/admin/adminAction.php",												
												success: function(msg){
													loader_unshow();
													jQuery(".show_order_msg").html(msg).show();			
												}
											});																		
										},
										dragHandle: ".dragHandle"
									});
								});
								</SCRIPT>

							</form>
						<?php } ?>						
					</div>
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