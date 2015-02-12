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

if(isset($_GET['id']) && $_GET['id']!='') {
	
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
	}
} else {
	header('location: adminStatistics.php');
	exit;
}

if(isset($_POST['save'])){
	$db_periodicity			= (isset($_POST['db_periodicity']))?trim(addslashes($_POST['db_periodicity'])):'';
	$db_update_periodicity	= (isset($_POST['updated_periodicity']))?trim(addslashes($_POST['updated_periodicity'])):'';
	$db_geographic			= (isset($_POST['db_geographic']))?trim(addslashes($_POST['db_geographic'])):'';
	$dbsource				= (isset($_POST['dbsource']))?trim(addslashes($_POST['db_contact'])):'';
	$s_desc					= (isset($_POST['s_desc']))?trim(addslashes($_POST['s_desc'])):'';
	$db_contacts			= (isset($_POST['db_contact']))?trim(addslashes($_POST['db_contact'])):'';
	$db_how_update			= (isset($_POST['db_howto_update']))?trim(addslashes($_POST['db_howto_update'])):'';
	$db_update_notes		= (isset($_POST['db_notes']))?trim(addslashes($_POST['db_notes'])):'';

	$db_sent_to_build_date	= (isset($_POST['db_sent_build']))?date('Y-m-d', strtotime($_POST['db_sent_build'])):'';
	$db_update_status		= (isset($_POST['p_status']))?trim(addslashes($_POST['p_status'])):'';

	$dbid					= $_POST['dbid'];

	$returnid = $admin->addFormUpdateInfo($db_update_periodicity, $db_contacts, $db_how_update, $db_sent_to_build_date, $db_update_status, $db_update_notes, $dbid);

	header('location: readme.php?id='.base64_encode($dbid).'');
	exit;
}

$updateInfo = $admin->getFormUpdateInfo($dbid);

if(!empty($updateInfo)){
		$db_update_periodicity	= stripslashes($updateInfo['db_update_periodicity']);
		$db_contacts			= stripslashes($updateInfo['db_contacts']);
		$db_how_update			= stripslashes($updateInfo['db_how_update']);
		$db_update_notes		= stripslashes($updateInfo['db_update_notes']);
		$db_sent_to_build_date	= stripslashes($updateInfo['db_sent_to_build_date']);
		$db_update_status		= stripslashes($updateInfo['db_sent_to_build_date']);
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

					<form action="" method="POST" name="myform">

						<table>
						<tbody>

						<tr>
							<td valign="top" align="right">
							<span class="label">periodicity</span>
							</td>
							<td class="ind1em">
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

								<span style="padding-left:4em">
									<span class="label">updated</span>
				
										<select name="updated_periodicity">
											<option value="">-- Select --</option>
											<option value="Monthly" <?php if(isset($db_update_periodicity) && $db_update_periodicity=='Monthly'){ echo "selected"; } ?>>Monthly</option>
											<option value="Quarterly" <?php if(isset($db_update_periodicity) && $db_update_periodicity=='Quarterly'){ echo "selected"; } ?>>Quarterly</option>
											<option value="Semi-Annually" <?php if(isset($db_update_periodicity) && $db_update_periodicity=='Semi-Annually'){ echo "selected"; } ?>>Semi-Annually</option>
											<option value="Biennially" <?php if(isset($db_update_periodicity) && $db_update_periodicity=='Biennially'){ echo "selected"; } ?>>Biennially</option>
											<option value="Annually" <?php if(isset($db_update_periodicity) && $db_update_periodicity=='Annually'){ echo "selected"; } ?>>Annually</option>
											<option value="Other" <?php if(isset($db_update_periodicity) && $db_update_periodicity=='Other'){ echo "selected"; } ?>>Other</option>
											<option value="N/A" <?php if(isset($db_update_periodicity) && $db_update_periodicity=='N/A'){ echo "selected"; } ?>>N/A</option>
										</select>
									</span>

									<span style="padding-left:4em">
										<span class="label">last:</span> <span class="blue"><?php if(isset($lastUpdate) && $lastUpdate!='0000-00-00'){ echo date('Y-m-d',strtotime($lastUpdate)); } ?></span> &mdash; <span class="label">next:</span> <span class="blue"><?php if(isset($db_nextupdate) && $db_nextupdate!='0000-00-00'){ echo $db_nextupdate; } ?></span>
									</span>

								</td>
							</tr>

							<tr>
								<td colspan="2">
									<hr style="color:#eee">
								</td>
							</tr>

							<tr>
								<td valign="top" align="right">
									<span class="label">Geographic Coverage</span>
								</td>
								<td class="ind1em">
									<input type="text" value="<?php if(isset($db_geographic)){ echo $db_geographic; } ?>" name="db_geographic" size="80">
								</td>
							</tr>

							<tr>
								<td valign="top" align="right">
									<span class="label">source</span>
								</td>
								<td class="ind1em">
									<input type="text" value="<?php if(isset($dbsource)){ echo $dbsource; } ?>" name="dbsource" size="80">
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<hr style="color:#eee">
								</td>
							</tr>


							<tr>
								<td valign="top" align="right">
									<span class="label">contacts</span>
								</td>
								<td class="ind1em">
									<textarea id="db_contact" class="sm" cols="75" rows="9" name="db_contact"><?php if(isset($db_contacts)){ echo $db_contacts; } ?></textarea>
								</td>
							</tr>

							<tr>
								<td valign="top" align="right">
									<span class="label">how to<br>update</span>
								</td>
								<td class="ind1em">
									<textarea class="sm" cols="100" rows="11" name="db_howto_update"><?php if(isset($db_how_update)){ echo $db_how_update; } ?></textarea>
								</td>
							</tr>

							<tr>
								<td valign="top" align="right">
									<span class="label">notes</span>
								</td>
								<td class="ind1em">
									<textarea id="description" class="sm" cols="100" rows="11" name="db_notes"><?php if(isset($db_update_notes)){ echo $db_update_notes; } ?></textarea>
								</td>
							</tr>


							<tr>
								<td valign="top" align="right">
									<span class="label">sent to build</span>
								</td>
								<td class="ind1em">

									<input type="text" value="<?php if(isset($db_sent_to_build_date)){ echo $db_sent_to_build_date; } ?>" maxlength="12" size="10" id="db_sent_build" name="db_sent_build" id="tobuild">
								</td>
							</tr>
							<tr>
								<td valign="top" align="right"><span class="label">status</span></td>
								<td>
									<select class="s10x" size="1" name="p_status">
										<option value="1" <?php if(isset($db_update_status) && $db_update_status==1){ echo "selected"; } ?>> Pending next update</option>
										<option value="2" <?php if(isset($db_update_status) && $db_update_status==2){ echo "selected"; } ?>> Sent to build</option>
										<option value="3" <?php if(isset($db_update_status) && $db_update_status==3){ echo "selected"; } ?>> Other, see notes</option>
									</select>
									
								</td>
							</tr>

							<tr>
								<td valign="top" align="right"></td>
								<td class="ind1em">
									<input type="hidden" name="dbid" value="<?php echo $dbid; ?>">
									<input type="submit" name="save" value="Submit">
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



