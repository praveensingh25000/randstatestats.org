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

if(!isset($_GET['group']) || (isset($_GET['group']) && $_GET['group'] == '' && !is_numeric($_GET['group']))){
	header("location: generalSettings.php");
}

$groupid = $_GET['group'];

if(isset($_POST['update_setting'])){

	$groupid = $_POST['groupid'];
	
	foreach($_POST['settings'] as $name => $value){
	$return = user::updateGeneralSettings($name, $value, $groupid);
	}

	if(isset($_FILES['site_logo'])){
		
		$imagename = $_FILES['site_logo']['name'];
		$image=$_FILES['site_logo']['tmp_name'];
		$ext = getExtension($imagename);
		$logo = 'logo.'.$ext;
		global $DOC_ROOT;
		$newimage = $DOC_ROOT.'images/' .$logo;
		
		if(move_uploaded_file($image, $newimage)){
			$return = user::updateGeneralSettings($name, $value, $groupid);
		} 
	}	

	$_SESSION['msgsuccess'] = "Settings updated successfully";
	header('location: editSetting.php?group='.$groupid.'');
}

$generalSettings =fetchGenralSettings($groupid);
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

			<form id="generalSettingsForm" name="generalSettingsForm" method="post" enctype="multipart/form-data">

				<div class="adminright">

					<h3>Edit <?php echo $groupsArray[$groupid]; ?></h3>

					<table width="100%">

						<?php foreach($generalSettings as $key => $groupsdata){
							
							foreach($groupsdata as $key => $setting){
								$name	= stripslashes($setting['name']);
								$text	= stripslashes($setting['text']);
								$value	= stripslashes($setting['value']);
								?>
								<tr>
									<td valign="top" width="150"><?php echo $text;?> (<?php if ($groupid == 10){ ?> % <?php } ?>)</td>
									<td>
										<?php if($name == 'site_logo'){ ?>

											<input type="file" name="<?php echo $name; ?>" accept="jpg|jpeg|png|gif" class="<?php if($value == '') { ?> required <?php } ?>">

										<?php } elseif($name == 'home_page_content') { ?>

											<script type="text/javascript">
											jQuery(document).ready(function(){
												tinyMCE.init({
													// General options
													theme :		"advanced",
													mode:		"exact",
													elements :  "description",
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
													content_css : "css/content.css",

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
													]
												});
												
											});
											</script>

											<textarea id="description" name="settings[<?php echo $name; ?>]" class="required" rows = '15' cols="30"><?php echo $value; ?></textarea>

										<?php } else { ?>

											<input size="50" type="text" class="required <?php if($name == 'from_email' || $name == 'email') { echo "email"; } ?> " value="<?php echo $value; ?>" name="settings[<?php echo $name; ?>]" /> 

										<?php }	?>
									</td>
								</tr>
							<?php			
							}
						}
						?>

						<?php if(count($generalSettings)>0){ ?>
						<tr>
							<td valign="top">&nbsp;</td>
							<td>
							<input  type="submit" value="Submit" name="update_setting" />
							<input type="hidden" name="groupid" value="<?php echo $groupid;?>">
							</td>
						</tr>
						<?php } ?>

					</table>
				</div>
				<!-- left side -->
			</form>
	</div>		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>