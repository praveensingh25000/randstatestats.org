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

$news_title = $active_status = $description = '';

$newsDetail = array();

if(isset($_GET['action']) && $_GET['action'] == 'add') {

	$news_title	 = (isset($_POST['news_title']))?trim(addslashes($_POST['news_title'])):'';
	$description	 = (isset($_POST['description']))?trim($_POST['description']):0;
	$active_status = (isset($_POST['active_status']))?trim(addslashes($_POST['active_status'])):'';
	$date = (isset($_POST['date']))?trim(addslashes($_POST['date'])):'';

	$newsid = $admin->insertNews($news_title, $description, $active_status, $date);
	if($newsid <=0){
		$news_title	 = stripslashes($_POST['news_title']);
		$description	 = stripslashes($_POST['description']);
		$active_status = stripslashes($_POST['active_status']);
		$date = stripslashes($_POST['date']);
	} else {
		unset($_SESSION['cat']);
		header('location: news.php');
		exit;
	}
}

if(isset($_GET['action']) && $_GET['action'] == 'update') {

	$news_title		= (isset($_POST['news_title']))?trim($_POST['news_title']):'';
	$description	= (isset($_POST['description']))?trim($_POST['description']):0;
	$active_status	= (isset($_POST['active_status']))?trim($_POST['active_status']):'';
	$date			= (isset($_POST['date']))?trim($_POST['date']):'';
	$newsid			= $_POST['newsid'];

	$return = $admin->updateNews($news_title, $description, $active_status, $date, $newsid);

	if($return <= 0){

		$news_title			= stripslashes($_POST['news_title']);
		$description		= $_POST['description'];
		$active_status		= stripslashes($_POST['active_status']);
		$date				= stripslashes($_POST['date']);

	} else {

		$_SESSION['msgsuccess'] = 'News has been updated.';
		unset($_SESSION['cat']);
		header('location: add_news.php?action=edit&id='.base64_encode($_POST['newsid']));
		exit;
	}
}

if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_GET['id']!=''){

	$newsid = trim(base64_decode($_GET['id']));
	$newsDetail = $admin->getNews($newsid);
	if(!empty($newsDetail)){
		$news_title	 = stripslashes($newsDetail['news_title']);
		$description	 = stripslashes($newsDetail['description']);
		$active_status = stripslashes($newsDetail['is_active']);
		$date = stripslashes($newsDetail['date_added']);
	}
}
?>
<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery-ui-1.9.2.js"></script>

<link rel="stylesheet" href="<?php echo URL_SITE; ?>/css/jquery.ui-1.9.2.css" type="text/css" />
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
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($newsDetail) && !empty($newsDetail)){ echo "Edit"; } else { echo "Add"; } ?> News</legend>
				<form <?php if(isset($_GET['action']) && $_GET['action'] == 'edit') { ?> id="frmAllNewsedit" name="frmAllNewsedit" <?php } else { ?> id="frmAllNewsadd" name="frmAllNewsadd" <?php }?> method="post" action="add_news.php?action=<?php if(isset($_GET['action']) && $_GET['action'] == 'edit') echo 'update'; else { echo 'add'; };?>">
				<p>News title<em>*</em></p>
				<div style="padding: 10px 0;">
					<input type="text" id="news_title" name="news_title" class="required" value="<?php if(isset($news_title)){ echo $news_title; } ?>"/>
					<?php if(isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
					<input type="hidden" id="current_news_title" name="current_news_title" value="<?php if(isset($news_title)){ echo $news_title; } ?>">
					<?php } ?>
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
						jQuery("#frmAllNews").submit(function(){
							var content	= tinyMCE.activeEditor.getContent();
							if(content == ''){								
								jQuery('#description_info').show();
								return false;
							} else {	
								jQuery('#description_info').hide();
								return true;
							}						
						});
					});
					</script>

				<p>Description<em>*</em></p>
				<div style="padding: 10px 0;">
					<textarea id="description" name="description" class="required" rows = '15' style="width: 650px;" cols="30"><?php if(isset($description)){ echo $description; } ?></textarea>

					<label for="description" generated="true" id="description_info" class="error" style="display: none;">This field is required.</label>
					
				</div>

				<p>Date</p>
				<div style="padding: 10px 0;">
					<input name="date" id="news_date" class="required" value="<?php if(isset($date)){ echo $date;}else { echo '';} ?>" type="text">(Format: yyyy-mm-dd)<br/>
				<label for="date" generated="true" class="error"></label>
				</div>

				<p>Active Status<em>*</em></p>
				<div style="padding: 10px 0;">
					<select name="active_status" class="required">
						<option value="">--Select Status--</option>			
						<option value="Y" <?php if(isset($active_status) && $active_status == 'Y'){ echo 'selected';}?>>Active</option>
						<option value="N" <?php if(isset($active_status) && $active_status == 'N'){ echo 'selected';}?>>In-Active</option>
					</select>
				</div>

				<div class="submit1 submitbtn-div">
					<label for="submit" class="left">
						<?php if(isset($newsDetail) && !empty($newsDetail)){ ?>
						<input type="hidden" value="<?php echo $newsid; ?>" name="newsid"/>
						<input type="submit" value="Submit" name="update_news" class="submitbtn" >
						<?php } else { ?>
						<input type="submit" value="Submit" name="add_news" class="submitbtn" >
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
<script>
	/*jQuery(document).ready(function(){
		jQuery('#frmAllNews').validate({
			rules:{
				news_title:{
					remote:"check_availability.php"
				}
			},
			messages:{
				news_title:{
					remote: jQuery.format("<font color='red'>News title Exists</font>")
				}
			}
		});
	});*/
	
	$( "#news_date" ).datepicker({ 
		minDate: 0, dateFormat: 'yy-mm-dd' });

</script>
<?php 
include_once $basedir."/include/adminFooter.php";
?>


