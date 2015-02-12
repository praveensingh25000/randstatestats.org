<?php
/******************************************
* @Modified on September 04, 2012
* @Package: Rand
* @Developer: Susanto Mahato
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

if(isset($_GET['id']) && $_GET['id']!='') {
	
	$dbid = trim(base64_decode($_GET['id']));
	$databaseDetail = $admin->getDatabase($dbid, true);
	$table = $admin->getDatabaseTables($dbid,true);

	if(!empty($databaseDetail)){
	
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description=  $databaseDetail['db_description'];
		$db_periodicity	= stripslashes($databaseDetail['db_periodicity']);
		$db_geographic	= stripslashes($databaseDetail['db_geographic']);

	}
} else {
	header('location: adminStatistics.php');
	exit;
}

if(isset($_POST['Update'])){
		//echo '<pre>';print_r($_POST);die;
		
	$db_periodicity         = (isset($_POST['periodicity']))?trim(addslashes($_POST['periodicity'])):'';
	
	$db_geographic			= (isset($_POST['coverage']))?trim(addslashes($_POST['coverage'])):'';
	$s_desc					= (isset($_POST['description']))?trim(addslashes($_POST['description'])):'';
	$source					= (isset($_POST['source']))?trim(addslashes($_POST['source'])):'';

	$dbid					= $_POST['dbid'];

	$returnid = $admin->sourceFormUpdateInfo($db_periodicity ,$db_geographic,$s_desc , $source,	$dbid);

	if($returnid){
		$_SESSION['msgsuccess']="Source  Detail has been updated successfully.";
		
	}else{
				$_SESSION['msgerror']="Source details have not updated.";
	}
	header('location: source.php?id='.base64_encode($dbid).'');
	exit;
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

					<form action="" method="POST" name="sourceUpdateForm">

						<table>
						<tbody>

						<tr>
							<td valign="top" align="right">
							<span class="label">periodicity</span>
							</td>
							<td class="ind1em">
								<select name="periodicity">
									<option value="">-- Select --</option>
									<option value="Monthly" <?php if(isset($db_periodicity) && $db_periodicity=='Monthly'){ echo "selected"; } ?>>Monthly</option>
									<option value="Quarterly" <?php if(isset($db_periodicity) && $db_periodicity=='Quarterly'){ echo "selected"; } ?>>Quarterly</option>
									<option value="Semi-Annually" <?php if(isset($db_periodicity) && $db_periodicity=='Semi-Annually'){ echo "selected"; } ?>>Semi-Annually</option>
									<option value="Biennially" <?php if(isset($db_periodicity) && $db_periodicity=='Biennially'){ echo "selected"; } ?>>Biennially</option>
									<option value="Annually" <?php if(isset($db_periodicity) && $db_periodicity=='Annually'){ echo "selected"; } ?>>Annually</option>
									<option value="Other" <?php if(isset($db_periodicity) && $db_periodicity=='Other'){ echo "selected"; } ?>>Other</option>
									<option value="N/A" <?php if(isset($db_periodicity) && $db_periodicity=='N/A'){ echo "selected"; } ?>>N/A</option>
								</select>

								</td>
							</tr>

							<tr>
								<td valign="top" align="right">
									<span class="label">Geographic Coverage</span>
								</td>
								<td class="ind1em">
									<input type="text" name="coverage" class="" value="<?php if(isset($db_geographic)){ echo $db_geographic; }?>" size="20">
								</td>
							</tr>

							<tr>
								<td valign="top" align="right">
									<span class="label">Description</span>
								</td>
								<td class="ind1em">
									<textarea id="description" class="required" name="description" rows="15" cols="80" style="width: 80%"><?php if(isset($description)){ echo stripslashes($description); } ?></textarea>
								</td>
							</tr>

							<tr>
								<td valign="top" align="right">
									<span class="label">source</span>
								</td>
								<td class="ind1em">
									<input type="text" name="source" class="" value="<?php if(isset($dbsource)){ echo $dbsource; }?>" size="20">
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<hr style="color:#eee">
								</td>
							</tr>


							<tr>
								<td valign="top" align="right"></td>
								<td class="ind1em">
									<input type="hidden" name="dbid" value="<?php echo $dbid; ?>">
									<input type="submit" name="Update" value="Update">
								</td>
							</tr>
							</tbody>
							</table>
						</form>
					</fieldset>
				</div>
		 </div>
		<!-- left side -->

		
		<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery-ui-1.9.2.js"></script>

		<link rel="stylesheet" href="<?php echo URL_SITE; ?>/css/jquery.ui-1.9.2.css" type="text/css" />

		<script type="text/javascript">

			jQuery(document).ready(function(){
				jQuery("#db_sent_build").datepicker({
					showOn: "button",
					buttonImage: "../images/calendar.jpg",
					dateFormat: 'yy-mm-dd',
					buttonImageOnly: true
				});
			});

			tinyMCE.init({
				// General options
				theme :		"advanced",
				selector: "textarea",
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
				onchange_callback: function (editor){
					tinyMCE.triggerSave();
					$("#description").valid();
				}
			});
		</script>

		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>







