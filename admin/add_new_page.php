<?php
/******************************************
* @Modified on March 25, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();
$cms=new CmsPages();
$cmsPages=$cms->allCmsPages();


$pageDetail = array(); 

$page_name = $active_status = $description = '';

if(isset($_GET['edit']) && isset($_GET['p_id']) && $_GET['p_id']!=''){

	$pageid = trim(base64_decode($_GET['p_id']));
	$pageDetail = $cms->getPage($pageid);
	//print_r($pageDetail);
	if(!empty($pageDetail)){
		$page_name		= stripslashes($pageDetail['page_name']);
		$url			= stripslashes($pageDetail['url']);
		$keyword		= stripslashes($pageDetail['keyword']);
		$meta_data		= stripslashes($pageDetail['meta_data']);
		$tag			= stripslashes($pageDetail['tag']);
		$location		= $pageDetail['location'];
		$description	= stripslashes($pageDetail['description']);
		$active_status	= $pageDetail['is_active'];
		$date			= stripslashes($pageDetail['added_on']);
	}
}

if(isset($_POST['addpage'])){
	$pageStatus=$cms->savePage();
	if($pageStatus)
	{
		$_SESSION['msgsuccess'] = 'New page has been added successfully';
		header('Location: cms_pages.php');
	} else {
		unset($_SESSION['cat']);
		header('location: cms_pages.php');
		exit;
	}
}


if(isset($_POST['updatepage'])){
	$page_name		= (isset($_POST['page_name']))?trim(addslashes($_POST['page_name'])):'';
	$description		= (isset($_POST['description']))?trim($_POST['description']):0;
	$active_status	= (isset($_POST['active_status']))?trim(addslashes($_POST['active_status'])):'';
	//$date = (isset($_POST['date']))?trim(addslashes($_POST['date'])):'';
	$pageid			= $_POST['pageid'];
	$return = $cms->updatePage($pageid);
	if($return <=0){
		$page_name	 = stripslashes($_POST['page_name']);
		$description	 = $_POST['description'];
		$active_status = stripslashes($_POST['active_status']);
		//$date = stripslashes($_POST['date']);
	} else {
		$_SESSION['msgsuccess'] = 'News has been updated.';
		unset($_SESSION['cat']);
		header('location: cms_pages.php');
		exit;
	}
	
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
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($pageDetail) && !empty($pageDetail)){ echo "Edit"; } else { echo "Add"; } ?> Page</legend>
				<form id="frmAllPages" name="frmAllPages" method="post">
				<p>Page Name<em>*</em></p>
				<div style="padding: 10px 0;">
					<input type="text" id="page_name" name="page_name" class="required" value="<?php if(isset($page_name)){ echo $page_name; } ?>" <?php if(!isset($_GET['edit'])){ $edit ='edit';} else { $edit='';}?> onclick="javascript: checkExistence('<?php echo $edit;?>','<?php if(isset($pageid)){ echo $pageid;}else{ echo '';}?>');"/>
				</div>

				<p>URL<em>*</em></p>
				<div style="padding: 10px 0;">
					<input type="text" id="url" name="url" class="required" value="<?php if(isset($url)){ echo $url; } ?>"/>
				</div>

				<p>Parent Page<em>*</em></p>
				<div style="padding: 10px 0;">
					<select name="parent_page">
						<option value="">--Select Page--</option>
						<?php if(isset($cmsPages) && count($cmsPages)>0){
							foreach($cmsPages as $key => $pageName){?>
						<option value="<?php echo $pageName['id'];?>" <?php if(isset($pageDetail['parent_page_id']) && $pageDetail['parent_page_id']==$pageName['id']){ echo 'selected';}?>><?php echo $pageName['page_name'];?></option>
						<?php } 
				}?>
					</select>
				</div>

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
						jQuery("#frmAllPages").submit(function(){
							var content	= tinyMCE.activeEditor.getContent();
							if(content == ''){								
								jQuery('#description_page').show();
								return false;
							} else {	
								jQuery('#description_page').hide();
								return true;
							}						
						});
					});
					</script>

				<p>Description<em>*</em></p>
				<div style="padding: 10px 0;">
					<textarea id="description" name="description" class="required" rows = '15' cols="30"><?php if(isset($description)){ echo $description; } ?></textarea>
					<label for="description" generated="true" id="description_page" class="error" style="display: none;">This field is required.</label>
				</div>

				<!-- <p>Location<em>*</em></p>
				<div style="padding: 10px 0;">
					<select name="location" class="required">
						<option value="">-- Select Location --</option>
						<option value="H" <?php if(isset($location) && $location == 'H'){ echo 'selected';}?>>Header</option>
						<option value="F" <?php if(isset($location) && $location == 'F'){ echo 'selected';}?>>Footer</option>
					</select>
				</div> -->

				<p>Meta keyword<em>*</em></p>
				<div style="padding: 10px 0;">
					<input type="text" id="keyword" name="keyword" class="required" value="<?php if(isset($keyword)){ echo $keyword; } ?>"/>
				</div>

				<p>Meta tag<em>*</em></p>
				<div style="padding: 10px 0;">
					<input type="text" id="tag" name="tag" class="required" value="<?php if(isset($page_name)){ echo $page_name; } ?>"/>
				</div>


				<p>Page Status<em>*</em></p>
				<div style="padding: 10px 0;">
					<select name="active_status" class="required">
						<option value="">--Select Status--</option>			
						<option value="Y" <?php if($active_status == 'Y'){ echo 'selected';}?>>Publish</option>
						<option value="N" <?php if($active_status == 'N'){ echo 'selected';}?>>Unpublish</option>
					</select>
				</div>

				<div class="submit1 submitbtn-div">
					<label for="submit" class="left">
						<?php if(isset($pageDetail) && !empty($pageDetail)){ ?>
						<input type="hidden" value="<?php echo $pageid; ?>" name="pageid"/>
						<input type="submit" value="Submit" name="updatepage" class="submitbtn" >
						<?php } else { ?>
						<input type="submit" value="Submit" name="addpage" class="submitbtn" >
						<?php } ?>

					</label>
					<label for="reset" class="right">
						<input type="reset" id="reset_info" value="Reset" class="submitbtn">
					</label>
				</div>
				</form>
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
<script>
function checkExistence(editVar,PageId){
	var pagename = jQuery('#page_name').val();
	if(pagename!=''){
		jQuery.ajax({
			type: "GET",
			data: '',
			url : URL_SITE+"/admin/check_availability.php?page_name="+pagename,	
			success: function(msg){
				if($.trim(msg) == "false"){
					if(!jQuery("#page_already_exists").hasClass("coloumexits"))			
					jQuery("#page_name").after('<label id="page_already_exists" for="'+page_name+'" generated="true" class="error coloumexits">Page Name already Exists</label>');
				}else{
					jQuery('#page_already_exists').remove();
				}			
			}								
		});			
	}else{
	return false;
	}
}
</script>


