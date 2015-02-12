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

$admin = new admin();

if(isset($databaseCategories)){ unset($databaseCategories); }

$dbname = $dbsource = $description = $miscellaneous = $db_graph = $db_geographic = $db_dataseries = $db_nextupdate = $db_datasource = $db_periodicity = $db_url = $dbsourcelink = $notes = $tags = '';

$databaseCategories = $databaseRelatedDatabases = $databaseRelatedDatabasesAdmin = $parentCat = $subCat = array();

if(isset($_GET['deletetable']) && $_GET['deletetable']!='' && isset($_GET['id']) && $_GET['id']!=''){
	$dbid = trim(base64_decode($_GET['id']));	
	$tablename = trim($_GET['deletetable']);	
	$admin->deleteAssociatedTable($dbid,  $tablename);
	header('location:database.php?tab=3&action=edit&id='.base64_encode($dbid));
}

if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_GET['id']!='') {
	
	$dbid = trim(base64_decode($_GET['id']));
	$databaseDetail = $admin->getDatabase($dbid, true);
	$table = $admin->getDatabaseTables($dbid,true);

	if(!empty($databaseDetail)){
		
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description= stripslashes($databaseDetail['db_description']);
		$miscellaneous	= stripslashes($databaseDetail['db_misc']);
		$db_datasource = stripslashes($databaseDetail['db_datasource']);
		$db_graph = explode(',',$databaseDetail['db_graph']);

		$db_geographic	= stripslashes($databaseDetail['db_geographic']);
		$db_dataseries	= stripslashes($databaseDetail['db_dataseries']);
		$db_nextupdate	= stripslashes($databaseDetail['db_nextupdate']);
		$db_periodicity	= stripslashes($databaseDetail['db_periodicity']);
		$db_title	= stripslashes($databaseDetail['db_title']);
		$dbsourcelink	= stripslashes($databaseDetail['db_sourcelink']);
		$notes	= stripslashes($databaseDetail['db_notes']);

		$formfootnotes = stripslashes($databaseDetail['formfootnotes']);
		
		$tagsResult = $admin->getFormTags($dbid);
		if(mysql_num_rows($tagsResult)>0){
			while($tagDet = mysql_fetch_assoc($tagsResult)){
				$tags .= $tagDet['tags'].";";
			}
			$tags = substr($tags,0,-1);
		}
		

		$decimal_settings = stripslashes($databaseDetail['decimal_settings']);

		$db_url			= stripslashes($databaseDetail['url']);
		$is_static_form = $databaseDetail['is_static_form'];
		$lastUpdate=$databaseDetail['date_added'];

		if(isset($databaseDetail['share_status'])) { $share_status=$databaseDetail['share_status'];}

		$databaseCategoriesResult = $admin->getAllDatabaseCategories($dbid);
		$dbtotal = $dbDatabase->count_rows($databaseCategoriesResult);
		$dbcategories = $dbDatabase->getAll($databaseCategoriesResult);


		foreach($dbcategories as $key => $dbcatDetail){
			$databaseCategories[] = $dbcatDetail['category_id'];
			if($dbcatDetail['cat_type'] == 's'){
				$subCat[] = $dbcatDetail['category_id'];
			} else if($dbcatDetail['cat_type'] == 'p'){
				$parentCat[] = $dbcatDetail['category_id'];
			}
		}

		$databaseRelatedResult = $admin->getAllDatabaseRelatedDatabases($dbid);
		$dbReltotal = $dbDatabase->count_rows($databaseRelatedResult);
		$dbRelatedDatabases = $dbDatabase->getAll($databaseRelatedResult);
		if(count($dbRelatedDatabases) >0){
			foreach($dbRelatedDatabases as $key => $dbRelDetail){
				$databaseRelatedDatabases[] = $dbRelDetail['related_database_id'];
			}
		}

		$databaseRelatedResultAdmin = $admin->getAllDatabaseRelatedDatabasesAdmin($dbid);
		$dbReltotal = $dbDatabase->count_rows($databaseRelatedResultAdmin);
		$dbRelatedDatabasesAdmin = $dbDatabase->getAll($databaseRelatedResultAdmin);
		if(count($dbRelatedDatabases) >0){
			foreach($dbRelatedDatabasesAdmin as $key => $dbRelDetail){
				$databaseRelatedDatabasesAdmin[] = $dbRelDetail['related_database_id'];
			}
		}

	}
}

if(isset($_POST['adddb'])){

	$dbname			= (isset($_POST['dbname']))?trim(addslashes($_POST['dbname'])):'';
	$db_datasource	= (isset($_POST['db_datasource']))?trim(addslashes($_POST['db_datasource'])):'';
	$dbsource		= (isset($_POST['dbsource']))?trim(addslashes($_POST['dbsource'])):'';
	$description	= (isset($_POST['description']))?trim(addslashes($_POST['description'])):'';
	$miscellaneous	= (isset($_POST['miscellaneous']))?trim(addslashes($_POST['miscellaneous'])):'';
	$databaseCategories		= $_POST['categories'];
	$databaseRelatedDatabases	= $_POST['databases'];
	
	$formfootnotes = addslashes($_POST['formfootnotes']);

	$db_title		= $dbname;
	//$db_title		= (isset($_POST['db_title']))?trim(addslashes($_POST['db_title'])):'';
	$dbsourcelink	= (isset($_POST['dbsourcelink']))?trim(addslashes($_POST['dbsourcelink'])):'';
	$db_geographic	= (isset($_POST['db_geographic']))?trim(addslashes($_POST['db_geographic'])):'';
	$db_dataseries	= (isset($_POST['db_dataseries']))?trim(addslashes($_POST['db_dataseries'])):'';
	$db_nextupdate	= (isset($_POST['db_nextupdate']))?trim(addslashes($_POST['db_nextupdate'])):'';

	$db_lastupdate	= (isset($_POST['db_lastupdate']))?trim(addslashes($_POST['db_lastupdate'])):'';

	$db_periodicity	= (isset($_POST['db_periodicity']))?trim(addslashes($_POST['db_periodicity'])):'Other';
	$notes			= (isset($_POST['notes']))?trim(addslashes($_POST['notes'])):'';
	
	$decimal_settings = (isset($_POST['show_decimal']))?trim(addslashes($_POST['show_decimal'])):'';

	$dbid = $admin->insertDatabase($dbname, $dbsource, $description, $miscellaneous, $db_title, $dbsourcelink, $db_geographic, $db_dataseries, $db_nextupdate, $db_periodicity, $notes, $db_datasource, $db_lastupdate, $decimal_settings, $formfootnotes);

	$databaseRelatedDatabasesAdmin	= $_POST['databases_admin'];

	if($dbid <=0){
		$dbname			= stripslashes($_POST['dbname']);
		$dbsource		= stripslashes($_POST['dbsource']);
		$description	= stripslashes($_POST['description']);
		$miscellaneous	= stripslashes($_POST['miscellaneous']);

	} else {
		$admin->updateDatabaseCategories($databaseCategories, $dbid);
		$admin->updateDatabaseRelatedDatabases($databaseRelatedDatabases, $dbid);
		$admin->updateDatabaseRelatedDatabasesAdmin($databaseRelatedDatabasesAdmin, $dbid);

		header('location: databases.php');
		exit;
	}
	
}

if(isset($_POST['addallgraphdetail'])){ //DONE BY PKS ON 12/24/2012
		
	$db_graph = implode(',',$_POST['graph']);
	$dbid = $_POST['dbid'];
	$dbid_enct = base64_encode($_POST['dbid']);

	$db_graph_array=array('bar','pie','line');
	$delete_graph_type=array();
	
	foreach($db_graph_array as $values){
		if(!in_array($values,$_POST['graph'])){
		$delete_graph_type[] = $values;
		}
	}
	if(!empty($delete_graph_type)){
	$delete_status = $admin->updateDatabasegraphTable($delete_graph_type, $dbid);
	}
	$return = $admin->updateDatabaseTable('db_graph', $db_graph, $dbid);
	header('location: database.php?tab=2&subtab=1&action=edit&id='.$dbid_enct.'');
	exit;
}

if(isset($_POST['editallgraphdetail'])){	//DONE BY PKS ON 12/24/2012

	$dbid	= $_POST['dbid'];
	$dbid_enct=base64_encode($_POST['dbid']);
	$return1 = $admin->updateDatabaseGraphAttributes($dbid);
	header('location: database.php?tab=2&subtab=1&action=edit&id='.$dbid_enct.'');
	exit;
}


if(isset($_POST['updatedb'])){

	$dbname			= (isset($_POST['dbname']))?trim(addslashes($_POST['dbname'])):'';
	$dbsource		= (isset($_POST['dbsource']))?trim(addslashes($_POST['dbsource'])):'';
	$description	= (isset($_POST['description']))?trim(addslashes($_POST['description'])):'';
	$miscellaneous	= (isset($_POST['miscellaneous']))?trim(addslashes($_POST['miscellaneous'])):'';
	$databaseCategories		= (isset($_POST['categories']))?$_POST['categories']:'';
	$databaseRelatedDatabases	= (isset($_POST['databases']))?$_POST['databases']:'';
	$dbid			= $_POST['dbid'];

	$formfootnotes = addslashes($_POST['formfootnotes']);

	//echo "<pre>";print_r($databaseRelatedDatabases);die;

	$db_title		= $dbname;
	//$db_title		= (isset($_POST['db_title']))?trim(addslashes($_POST['db_title'])):'';
	$db_datasource	= (isset($_POST['db_datasource']))?trim(addslashes($_POST['db_datasource'])):'';
	$dbsourcelink	= (isset($_POST['dbsourcelink']))?trim(addslashes($_POST['dbsourcelink'])):'';
	$db_geographic	= (isset($_POST['db_geographic']))?trim(addslashes($_POST['db_geographic'])):'';
	$db_dataseries	= (isset($_POST['db_dataseries']))?trim(addslashes($_POST['db_dataseries'])):'';
	$db_nextupdate	= (isset($_POST['db_nextupdate']))?trim(addslashes($_POST['db_nextupdate'])):'';
	$db_periodicity	= (isset($_POST['db_periodicity']))?trim(addslashes($_POST['db_periodicity'])):'Other';
	$notes			= (isset($_POST['notes']))?trim(addslashes($_POST['notes'])):'';

	$db_lastupdate	= (isset($_POST['db_lastupdate']))?trim(addslashes($_POST['db_lastupdate'])):'';

	$decimal_settings = (isset($_POST['show_decimal']))?trim(addslashes($_POST['show_decimal'])):'';

	$return = $admin->updateDatabase($dbname, $dbsource, $description, $miscellaneous, $db_title, $dbsourcelink, $db_geographic, $db_dataseries, $db_nextupdate, $db_periodicity, $notes, $dbid, $db_datasource, $db_lastupdate, $decimal_settings, $formfootnotes);

	$tags =   (isset($_POST['tags']))?trim(addslashes($_POST['tags'])):'';

	$databaseRelatedDatabasesAdmin	= (isset($_POST['databases_admin']))?$_POST['databases_admin']:'';
	 
	 
	$return1 = $admin->updateDatabaseCategories($databaseCategories, $dbid);
	$return2 = $admin->updateDatabaseRelatedDatabases($databaseRelatedDatabases, $dbid);
	$return3 = $admin->updateDatabaseRelatedDatabasesAdmin($databaseRelatedDatabasesAdmin, $dbid);
	$return5 = $admin->updateFormTags($tags, $dbid);
	
	if(isset($_POST['share_status']) && $_POST['share_status']=='1') {	
	$return4 = $admin->updateshareDetail($dbid,$dbname);
	}

	if($return <=0 && $return1 <=0 && $return2 <=0){
		$dbname			= stripslashes($_POST['dbname']);
		$dbsource		= stripslashes($_POST['dbsource']);
		$description	= stripslashes($_POST['description']);
		$miscellaneous	= stripslashes($_POST['miscellaneous']);

	} else {		
		$_SESSION['msgsuccess'] = 'Records has been updated';
		header('location:'.URL_SITE.'/admin/database.php?tab=1&action=edit&id='.base64_encode($dbid));
		exit;
	}
}

//$categoriesResult = $admin->getParentCategories();
$categoriesResult = $admin->getAllParentCategories();
$total = $dbDatabase->count_rows($categoriesResult);
$parentCategories = $dbDatabase->getAll($categoriesResult);

$catObj = new Category();

$categoriesData = array();
foreach($parentCategories as $catKey => $catDetail){
	
	$catid = $catDetail['id'];
	$categoriesData[$catid]['name'] = $catDetail['category_title'];
	$subCategoriesResult = $admin->selectAllCategory($catid);
	$subCategories = $dbDatabase->getAll($subCategoriesResult);
	
	$flagSub = 0;

	foreach($subCategories as $keySub => $subcatDetail){
		$categoriesData[$catid]['categories'][$subcatDetail['id']] = $subcatDetail;
		$related_DBSub = $catObj->databaseByCategory($subcatDetail['id']);

		if(count($related_DBSub)>0){
			$flagSub = 1;
			$categoriesData[$catid]['categories'][$subcatDetail['id']]['databases'] = $related_DBSub;
		}
	}
	if($flagSub==0){
		$related_DB = $catObj->databaseByCategory($catid);
		$categoriesData[$catid]['databases'] = $related_DB;
	}
	
}

if(isset($_GET['tab']) && $_GET['tab']>=1 && $_GET['tab']<=10){
	$tab = $_GET['tab'];
} else {
	$tab = 1;
}

if(isset($_GET['type'])) {
	$type = trim($_GET['type']);
} else {
	$type = 0;
}

$databasesResult = $admin->showAllDatabases();
$totalDatabases = $dbDatabase->count_rows($databasesResult);
$databases = $dbDatabase->getAll($databasesResult);

$attributes = array();
if($tab == 2){
	$attributesResult = $admin->getDatabaseGraphAttributes($dbid);
	$attributesArray = $dbDatabase->getAll($attributesResult);

	foreach($attributesArray as $key => $rowAttr){
		$attributes[$rowAttr['attribute_name']] = $rowAttr['attribute_value'];
	}
}

//echo "<pre>";print_r($categoriesData);echo "</pre>";
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
				
				<?php include("formNavigation.php"); ?>
				<br class="clear" />
				
				<?php if($tab==1){ ?>
				<div id="generalDetails" >
					<form id="frmAllCat" name="frmAllCat" method="post">

					<div class="submit1">
						<label for="submit" class="left pR10">
						<?php if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_GET['id']!=''){?>
							<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
							<input type="submit" value="Submit" name="updatedb" class="submitbtn" >
							<?php } else { ?>
							<input type="submit" value="Submit" name="adddb" class="submitbtn" >
							<?php } ?>
							
						</label>
						<label for="reset" class="left ">
							<input type="reset" id="reset" class="submitbtn">
						</label>
					</div>

					<div class="clear"></div><br/>

					<p>Form Name<em>*</em></p>
					<div style="padding: 10px 0;">
						<input type="text" id="catname" name="dbname" class="required" value="<?php if(isset($dbname)){ echo $dbname; } ?>"/>
					</div>

					<!-- <p>Form Title<em>*</em></p>
					<div style="padding: 10px 0;">
						<input type="text" id="db_title" name="db_title" class="required" value="<?php if(isset($db_title)){ echo $db_title; } ?>"/>
					</div> -->

					<p>Summary<em></em></p>
					<div style="padding: 10px 0;">
						<input type="text" id="db_datasource" name="db_datasource" class="" value="<?php if(isset($db_datasource)){ echo $db_datasource; } ?>"/>
					</div>
					
					<p>Data Source</p>
					<div style="padding: 10px 0;">
						<input type="text" id="" name="dbsource" class="" value="<?php if(isset($dbsource)){ echo $dbsource; } ?>"/>
					</div>

					<p>Data Source URL</p>
					<div style="padding: 10px 0;">
						<input type="text" id="dbsourcelink" name="dbsourcelink" class="url" value="<?php if(isset($dbsourcelink)){ echo $dbsourcelink; } ?>"/>
					</div>

					<p>Decimal Places</p>
					<div style="padding: 10px 0;">
						<select class="" name="show_decimal">
							<option value=""> -- As it is in database -- </option>
							<option <?php if(isset($decimal_settings) && $decimal_settings=='0'){ echo "selected"; } ?> value="0">No decimal (Round Off)</option>
							<option <?php if(isset($decimal_settings) && $decimal_settings=='1'){ echo "selected"; } ?> value="1">Upto 1 decimal place</option>
							<option <?php if(isset($decimal_settings) && $decimal_settings=='2'){ echo "selected"; } ?> value="2">Upto 2 decimal places</option>
							<option <?php if(isset($decimal_settings) && $decimal_settings=='3'){ echo "selected"; } ?> value="3">Upto 3 decimal places</option>
						</select>
					</div>

					<?php if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']=='rand_usa') { ?>

					<p>Select Share Status</p>
					<div style="padding: 10px 0;">
						<select class="required" name="share_status">
							<option value="">-- Select Share--</option>
							<option <?php if(isset($share_status) && $share_status=='1'){ echo "selected"; } ?> value="1">Shared</option>
							<option <?php if(isset($share_status) && $share_status=='0'){ echo "selected"; } ?> value="0">Unshared</option>
						</select>
					</div>

					<?php } ?>

					<script type="text/javascript">
						tinyMCE.init({
							// General options
							theme :		"advanced",
							

							mode:		"exact",
							elements :  "description",
							relative_urls : false,
							remove_script_host : false,
							inline_styles : true,
							plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

							// Theme options
							theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
							theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
							theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
							theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
							theme_advanced_toolbar_location : "top",
							theme_advanced_toolbar_align : "left",
							theme_advanced_statusbar_location : "bottom",
							theme_advanced_resizing : true,

							// Example content CSS (should be your site CSS)
							content_css : "/css/style.css",

							// Drop lists for link/image/media/template dialogs
							template_external_list_url : "lists/template_list.js",
							external_link_list_url : "lists/link_list.js",
							external_image_list_url : "lists/image_list.js",
							media_external_list_url : "lists/media_list.js",

							// Style formats
							style_formats : [
								{title : 'Bold text', inline : 'b'},
								{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
								{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
								{title : 'Example 1', inline : 'span', classes : 'example1'},
								{title : 'Example 2', inline : 'span', classes : 'example2'},
								{title : 'Table styles'},
								{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
							],
							relative_urls: false,
							convert_urls: false,
							onchange_callback: function (editor){
								tinyMCE.triggerSave();
								$("#description").valid();
							}
						});
					</script>

					<p>Description</p>
					<div style="padding: 10px 0;">
						<textarea id="description" class="required" name="description" rows="15" cols="80" style="width: 80%"><?php if(isset($description)){ echo stripslashes($description); } ?></textarea>
						<label for="description" generated="true" class="error"></label>
					</div>
					<p>Miscellaneous</p>
					<div style="padding: 10px 0;">
						<textarea name="miscellaneous" rows = '3' cols="30" style="width: 689px;"><?php if(isset($miscellaneous)){ echo stripslashes($miscellaneous); } ?></textarea>
					</div>

					<p>Form Tags </p>
					<div style="padding: 10px 0;">
						<textarea name="tags" rows = '3' cols="30" style="width: 689px;"><?php if(isset($tags)){ echo stripslashes($tags); } ?></textarea>
						<br/>Tags should be separated by semilcolon(;)
					</div>

					<p>Geographic Coverage<em>*</em></p>
					<div style="padding: 10px 0;">
						<input type="text" id="db_geographic" name="db_geographic" class="required" value="<?php if(isset($db_geographic)){ echo $db_geographic; } ?>"/>
					</div>

					<p>Periodicity</p>
					<div style="padding: 10px 0;">
						<select name="db_periodicity">
							<option value="">-- Select --</option>
							<option value="Monthly" <?php if(isset($db_periodicity) && $db_periodicity=='Monthly'){ echo "selected"; } ?>>Monthly</option>
							<option value="Quarterly" <?php if(isset($db_periodicity) && $db_periodicity=='Quarterly'){ echo "selected"; } ?>>Quarterly</option>
							<option value="Semi-Annually" <?php if(isset($db_periodicity) && $db_periodicity=='Semi-Annually'){ echo "selected"; } ?>>Semi-Annually</option>
							<option value="Biennially" <?php if(isset($db_periodicity) && $db_periodicity=='Biennially'){ echo "selected"; } ?>>Biennially</option>
							<option value="Annually" <?php if(isset($db_periodicity) && $db_periodicity=='Annually'){ echo "selected"; } ?>>Annually</option>
							<option value="Other" <?php if(isset($db_periodicity) && $db_periodicity=='Other'){ echo "selected"; } ?>>Other</option>
							<option value="N/A" <?php if(isset($db_periodicity) && $db_periodicity=='N/A'){ echo "selected"; } ?>>N/A</option>
						</select>
					</div>

					<p>Data Series</p>
					<div style="padding: 10px 0;">
						<input type="text" id="db_dataseries" name="db_dataseries" class="" value="<?php if(isset($db_dataseries)){ echo $db_dataseries; } ?>"/>
					</div>
					
					<p>Last Update</p>
					<div style="padding: 10px 0;">
					<input type="text" id="db_lastupdate" name="db_lastupdate" class="" value="<?php if(isset($lastUpdate) && $lastUpdate!='0000-00-00'){ echo date('Y-m-d',strtotime($lastUpdate)); } ?>"/ ><br/>(Format. YYYY-MM-DD)
					</div>	
					
					<p>Next Update</p>
					<div style="padding: 10px 0;">
						<input type="text" id="db_nextupdate" name="db_nextupdate" class="" value="<?php if(isset($db_nextupdate) && $db_nextupdate!='0000-00-00'){ echo $db_nextupdate; } ?>"/><br/>(Format. YYYY-MM-DD)
					</div>				

					<p>Assign Categories<em>*</em></p>
					<div class="assign">
						<?php						
						foreach($categoriesData as $catid => $pcategoryDetail){ 
						$display="none;"; 
						?>
						<div class="pL10">
							<div class="pT10">
								<input type="checkbox" id="cat_<?php echo $catid; ?>" name="categories[p][]" value="<?php echo $catid; ?>" class="required" <?php if(in_array($catid, $parentCat)){ $display="block;"; echo "checked=checked"; } ?> />&nbsp;<b><?php echo $pcategoryDetail['name']; ?></b>
							</div>

							<div style="display:<?php echo $display; ?>" id="sub_cat_<?php echo $catid; ?>" >
								<?php
								if(isset($pcategoryDetail['categories']) && count($pcategoryDetail['categories'])>0) { ?>
									<table border="0" cellpadding="4">
										<?php foreach($pcategoryDetail['categories'] as $key => $categoryDetail){ ?>
											<tr>
												<td width="10%">
													<input class="cat_<?php echo $catid;?>" type="checkbox" name="categories[s][]" value="<?php echo $categoryDetail['id']; ?>" <?php if(in_array($categoryDetail['id'], $subCat)){ echo "checked=checked"; } ?> />
												</td>
												<td><?php echo $categoryDetail['category_title']; ?></td>
											</tr>
										<?php } ?>
									</table>
								<?php } ?>
							</div>
						</div>

						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery('#cat_<?php echo $catid; ?>').click(function(){
								if(jQuery('#cat_<?php echo $catid; ?>').is(":checked")){
									jQuery("#sub_cat_<?php echo $catid; ?>").show();						
								} else {
									jQuery("#sub_cat_<?php echo $catid; ?>").hide();
									jQuery(".cat_<?php echo $catid; ?>").removeAttr('checked');
								}
							});
						});
						</script>

					<?php } ?>

					</div>

					<p>Related Forms For users</p>
					<div class="assign">						
						<?php						
						foreach($categoriesData as $catidall => $pcategoryDetail){							
						$display="display:none;"; ?>
							
							<div class="pT10 pL10">
								<input type="checkbox" id="related_dbs_<?php echo $catidall; ?>" name="" value="<?php echo $catidall; ?>" class="" />&nbsp;<b><?php echo $pcategoryDetail['name']; ?></b>
							</div>

							<div style="display:none;" id="sub_cat_relateds_<?php echo $catidall; ?>" >
							<?php
							if(isset($pcategoryDetail['categories']) && count($pcategoryDetail['categories'])>0) { ?>
								<div class="pT10 pL10">
									<table border="0" cellpadding="4">
										<?php foreach($pcategoryDetail['categories'] as $subkeyall => $categoryDetail) { ?>
											<tr>
												<td width="10%">
													<input type="checkbox" id="related_sub_dbs_<?php echo $subkeyall; ?>" />
												</td>
												<td>
													<b><?php echo $categoryDetail['category_title']; ?></b>
												</td>
											</tr>

											<script type="text/javascript">
											jQuery(document).ready(function(){
												jQuery('#related_sub_dbs_<?php echo $subkeyall; ?>').click(function(){
													if(jQuery('#related_sub_dbs_<?php echo $subkeyall; ?>').is(":checked")){
														jQuery("#related_sub_dbs_all_<?php echo $subkeyall; ?>").show();
													} else {
														jQuery("#related_sub_dbs_all_<?php echo $subkeyall; ?>").hide();
														jQuery(".has_checkedall_<?php echo $subkeyall; ?>").removeAttr('checked');
													}
												});
											});
											</script>

											<tr id="related_sub_dbs_all_<?php echo $subkeyall; ?>" style="display: none;">
												<td width="10%"></td>									
												<td width="100%">											
													<?php if(isset($categoryDetail['databases']) && count($categoryDetail['databases'])>0) { ?>			
													<table border="0" cellpadding="4">						
														<?php foreach($categoryDetail['databases'] as $key => $databaseDetail){ ?>
															<tr>						
																<td width="10%">
																	<input class="has_checkedall_<?php echo $subkeyall; ?>" id="has_checked_all_<?php echo $databaseDetail['id']; ?>" type="checkbox" name="databases[<?php echo $catidall;?>][]" value="<?php echo $databaseDetail['id']; ?>" <?php if(in_array($databaseDetail['id'], $databaseRelatedDatabases)){ echo "checked=checked"; } ?> />
																</td>
																<td><?php echo $databaseDetail['db_name']; ?></td>
															</tr>
															
															<script type="text/javascript">
															jQuery(document).ready(function(){	
																var checkedall =  jQuery("#has_checked_all_<?php echo $databaseDetail['id']; ?>").is(":checked") ? true : false;
																if(checkedall){
																	jQuery("#sub_cat_relateds_<?php echo $catidall; ?>").show();
																	jQuery("#related_sub_dbs_all_<?php echo $subkeyall; ?>").show();
																	jQuery("#related_dbs_<?php echo $catidall; ?>").attr("checked", "checked");
																	jQuery("#related_sub_dbs_<?php echo $subkeyall; ?>").attr("checked", "checked");
																}
															});												
															</script>
														<?php } ?>	
														</table>
													<?php } else { ?>							
														<span class="small">No form found.</span>		
													<?php } ?>
												</td>
											</tr>

											<script type="text/javascript">
											jQuery(document).ready(function(){
												jQuery('#related_dbs_<?php echo $catidall; ?>').click(function(){
													if(jQuery('#related_dbs_<?php echo $catidall; ?>').is(":checked")){
														jQuery("#sub_cat_relateds_<?php echo $catidall; ?>").show();
													} else {
														jQuery("#sub_cat_relateds_<?php echo $catidall; ?>").hide();
														jQuery(".has_checkedall_<?php echo $catidall; ?>").removeAttr('checked');
													}
												});
											});
											</script>
										<?php } ?>
									</table>
								</div>
							<?php } ?>
						</div>
						<?php } ?>
					</div>					

					<p>Related Forms (Admin Only)</p>
					<div class="assign">
						<table border="0" cellpadding="4">
							<?php 
							foreach($categoriesData as $catid => $pcategoryDetail){ 
								if(isset($pcategoryDetail['databases']) && count($pcategoryDetail['databases'])>0){
							?>
							<tr><td colspan="2"><b><?php echo $pcategoryDetail['name']; ?></b></td></tr>
							<?php
									foreach($pcategoryDetail['databases'] as $key => $databaseDetail){
							?>

									<tr><td width="10%"><input type="checkbox" name="databases_admin[]" value="<?php echo $databaseDetail['id']; ?>" <?php if(in_array($databaseDetail['id'], $databaseRelatedDatabasesAdmin)){ echo "checked=checked"; } ?> /></td><td><?php echo $databaseDetail['db_name']; ?></td></tr>
						
							<?php 
									} 
								} 

								if(isset($pcategoryDetail['categories']) && count($pcategoryDetail['categories'])>0){
									foreach($pcategoryDetail['categories'] as $subkey => $categoryDetail){
										if(isset($categoryDetail['databases']) && count($categoryDetail['databases'])>0){
							?>

											<tr><td colspan="2"><b><?php echo $categoryDetail['category_title']; ?></b></td></tr>
							<?php
											foreach($categoryDetail['databases'] as $key => $databaseDetail){
							?>
												<tr><td width="10%"><input type="checkbox" name="databases_admin[]" value="<?php echo $databaseDetail['id']; ?>" <?php if(in_array($databaseDetail['id'], $databaseRelatedDatabasesAdmin)){ echo "checked=checked"; } ?> /></td><td><?php echo $databaseDetail['db_name']; ?></td></tr>

							<?php
											}
										}
									}
								}
							}?>

						</table>
					</div>

					<script type="text/javascript">
						tinyMCE.init({
							// General options
							theme :		"simple",
							
							content_css : "/css/style.css",
							mode:		"exact",
							elements :  "formfootnotes",
							// Style formats
							style_formats : [
								{title : 'Bold text', inline : 'b'},
								{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
								{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
								{title : 'Example 1', inline : 'span', classes : 'example1'},
								{title : 'Example 2', inline : 'span', classes : 'example2'},
								{title : 'Table styles'},
								{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
							],
							relative_urls: false,
							convert_urls: false,
							onchange_callback: function (editor){
								tinyMCE.triggerSave();
								$("#formfootnotes").valid();
							}
						});
					</script>

					<p>Form Foot Notes</p>
					<div style="padding: 10px 0;">
						<textarea id="formfootnotes" class="" name="formfootnotes" cols="30" rows="3" style="width: 689px;"><?php if(isset($formfootnotes)){ echo $formfootnotes; } ?></textarea>
						<label for="formfootnotes" generated="true" class="error"></label>
					</div>

					<p>Notes (Admin Only)</p>
					<div style="padding: 10px 0;">
						<textarea id="notes" class="" name="notes" cols="30" rows="3" style="width: 689px;"><?php if(isset($notes)){ echo $notes; } ?></textarea>
						<label for="notes" generated="true" class="error"></label>
					</div>

					<div class="submit1 submitbtn-div">
						<label for="submit" class="left">
						<?php if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_GET['id']!=''){?>
							<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
							<input type="submit" value="Submit" name="updatedb" class="submitbtn" >
							<?php } else { ?>
							<input type="submit" value="Submit" name="adddb" class="submitbtn" >
							<?php } ?>
							
						</label>
						<label for="reset" class="right">
							<input type="reset" id="reset" class="submitbtn">
						</label>
					</div>
					</form>
				</div>
				<?php } ?>
				
				<?php if($tab==2){ ?>
				<div id="graphicalDetails" >
					
					<?php if($type=='0'){ ?>
						<form id="frmAllCatGraph" name="frmAllCatGraph" method="post">
							<!-- <p class="active">Select Graph</p> -->
							<div style="padding: 10px 0;" id="graphDiv">
								<p class="pT5 pB5">
									<input type="checkbox" id="bargraph" onclick="javascript: showAttributes('barDiv','bargraph');" name="graph[]" value="bar" class="required" <?php if(!empty($db_graph) && in_array('bar',$db_graph)) { echo "checked='checked'";} ?>/>&nbsp;Bar Graph&nbsp;
									<?php if(!empty($db_graph) && in_array('bar',$db_graph)) {?>
									<span class="right"><a href="?tab=2&action=edit&type=<?php if(!empty($db_graph) && in_array('bar',$db_graph)) { echo "bar";} ?>&id=<?php echo base64_encode($dbid); ?>">Add Detail</a>
									<?php } ?>	
								</p>
								<!-- <p class="pT5 pB5">
									<input type="checkbox" id="piegraph" name="graph[]" onclick="javascript: showAttributes('pieDiv','piegraph');" value="pie" class="required" <?php if(!empty($db_graph) && in_array('pie',$db_graph)) { echo "checked='checked'";} ?>/>&nbsp;Pie Graph&nbsp;
									<?php if(!empty($db_graph) && in_array('pie',$db_graph)) {?>
									<span class="right"><a href="?tab=2&action=edit&type=<?php if(!empty($db_graph) && in_array('pie',$db_graph)) { echo "pie";} ?>&id=<?php echo base64_encode($dbid); ?>">Add Detail</a>
									<?php } ?>	
								</p> -->
								<p class="pT5 pB5">
									<input type="checkbox" id="linegraph" onclick="javascript: showAttributes('lineDiv','linegraph');" name="graph[]" value="line" class="" <?php if(!empty($db_graph) && in_array('line',$db_graph)) { echo "checked='checked'";} ?>/>&nbsp;Show Line Graph&nbsp;
									<?php if(!empty($db_graph) && in_array('line',$db_graph)) {?>
									<span class="right"><a href="?tab=2&action=edit&type=<?php if(!empty($db_graph) && in_array('line',$db_graph)) { echo "line";} ?>&id=<?php echo base64_encode($dbid); ?>">Add Detail</a>
									<?php } ?>									
								</p>
								<br/>
								<label for="graph" generated="true" class="error" style="display:none;">This field is required.</label>
							</div>

							<div class="submit1 submitbtn-div">
								<label for="submit" class="left">									
									<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
									<input type="submit" value="Submit" name="addallgraphdetail" class="submitbtn" >
								</label>
								<label for="reset" class="right">
									<input onclick="javascript:window.history.go(-1)" type="button" value="Back" class="submitbtn">
								</label>
							</div>
						</form>
					<?php } ?>
					<?php if($type!='0'){ ?>
						<form id="frmAddGraphDetail" name="frmAddGraphDetail" method="post">
							<p class="active"><?php if(isset($type)) { echo ucwords($type)." Graph";}?><span class="right"><a href="javascript:window.history.go(-1)">Back</a></span></p>

							<div style="padding: 10px 0;<?php if($type != 'line'){ ?> display:none; <?php } ?> " id="lineDiv" class="allGraph">
								
								<p>Graph label</p>
								<div style="padding: 10px 0;">
									<input class="required" type="text" name="graph-line-label" value="<?php if(isset($attributes) && array_key_exists('graph_line_label', $attributes)){ echo $attributes['graph_line_label']; }?>" />
								</div>

								<p>Label (X-axis)</p>
								<div style="padding: 10px 0;">
									<input class="required" type="text" name="line-x-axis" value="<?php if(isset($attributes) && array_key_exists('x_axis_line_label', $attributes)){ echo $attributes['x_axis_line_label']; }?>" />
								</div>
								<p>Label (Y-axis)</p>
								<div style="padding: 10px 0;">
									<input class="required" type="text" name="line-y-axis" value="<?php if(isset($attributes) && array_key_exists('y_axis_line_label', $attributes)){ echo $attributes['y_axis_line_label']; }?>" />
								</div>
							</div>

							<div style="padding: 10px 0;<?php if($type != 'bar'){ ?> display:none; <?php } ?>" id="barDiv" class="allGraph">
								
								<p>Graph label</p>
								<div style="padding: 10px 0;">
									<input class="required" type="text" name="graph-bar-label" value="<?php if(isset($attributes) && array_key_exists('graph_bar_label', $attributes)){ echo $attributes['graph_bar_label']; }?>" />
								</div>

								<p>Label (X-axis)</p>
								<div style="padding: 10px 0;">
									<input class="required" type="text" name="bar-x-axis" value="<?php if(isset($attributes) && array_key_exists('x_axis_bar_label', $attributes)){ echo $attributes['x_axis_bar_label']; }?>" />
								</div>
								<p>Label (Y-axis)</p>
								<div style="padding: 10px 0;">
									<input class="required" type="text" name="bar-y-axis" value="<?php if(isset($attributes) && array_key_exists('y_axis_bar_label', $attributes)){ echo $attributes['y_axis_bar_label']; }?>" />
								</div>
							</div>

							<div style="padding: 10px 0;<?php if($type != 'pie'){ ?> display:none; <?php } ?>" id="pieDiv" class="allGraph">
								<p>Label Of Graph</p>
								<div style="padding: 10px 0;">
									<input class="required" type="text" name="pie-graph-label" value="<?php if(isset($attributes) && array_key_exists('pie_graph_label', $attributes)){ echo $attributes['pie_graph_label']; }?>" />
								</div>
							</div>

							<div class="submit1 submitbtn-div">
								<label for="submit" class="left">									
									<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
									<input type="hidden" value="<?php echo $type; ?>" name="graph"/>

									<input type="submit" value="Submit" name="editallgraphdetail" class="submitbtn" >
								</label>
								<label for="reset" class="right">
									<input onclick="javascript:window.history.go(-1)" type="button" value="Back" class="submitbtn">
								</label>
							</div>

						</form>
					<?php } ?>
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


