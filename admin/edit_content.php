<?php
/******************************************
* @Modified on Sept 3, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";
require_once $basedir."/classes/emailTemp.class.php";

checkSession(true);

$emailObj = new emailTemp();
$admin = new admin();

$title = $subject = $body = $tags = $cc_email = '';

$tempDetail = array();

if(isset($_GET['action']) && $_GET['action'] == 'add') {

	$title	 = (isset($_POST['email_title']))?trim(addslashes($_POST['email_title'])):'';
	$body	 = (isset($_POST['email_body']))?trim($_POST['email_body']):0;
	$subject = (isset($_POST['email_subject']))?trim(addslashes($_POST['email_subject'])):'';
	$cc_email = (isset($_POST['cc_email']))?trim(addslashes($_POST['cc_email'])):'';
	$tags = (isset($_POST['tags']))?trim(addslashes($_POST['tags'])):'';

	$tempid = $emailObj->insertTemp($title, $subject, $body, $tags, $cc_email);
	if($tempid <=0){
		$title	 = stripslashes($_POST['email_title']);
		$body	 = stripslashes($_POST['email_body']);
		$subject = stripslashes($_POST['email_subject']);
	} else {
		$_SESSION['msgsuccess'] = 'Email Content has been added.';
		unset($_SESSION['cat']);
		header('location: mail_content.php');
		exit;
	}
}

if(isset($_GET['action']) && $_GET['action'] == 'update') {

	$title		= (isset($_POST['email_title']))?trim($_POST['email_title']):'';
	$body	= (isset($_POST['email_body']))?trim($_POST['email_body']):0;
	$subject	= (isset($_POST['email_subject']))?trim($_POST['email_subject']):'';
	$tempid			= $_POST['tempid'];
	$cc_email = (isset($_POST['cc_email']))?trim(addslashes($_POST['cc_email'])):'';
	$tags = (isset($_POST['tags']))?trim(addslashes($_POST['tags'])):'';

	$return = $emailObj->updateTemp($title, $subject, $body, $tags,$cc_email, $tempid);

	if($return <= 0){

		//$title			= stripslashes($_POST['email_title']);
		$body			= stripslashes($_POST['email_body']);
		$subject		= stripslashes($_POST['email_subject']);
		$tags			= stripslashes($_POST['tags']);
		$cc_email		= stripslashes($_POST['cc_email']);
	} else {

		$_SESSION['msgsuccess'] = 'Email Content has been updated.';
		unset($_SESSION['cat']);
		header('location: edit_content.php?action=edit&id='.base64_encode($_POST['tempid']));
		exit;
	}
}

if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_GET['id']!=''){

	$tempid = trim(base64_decode($_GET['id']));
	$tempDetail = $emailObj->getTemp($tempid);
	if(!empty($tempDetail)){
		$email_title	 = stripslashes($tempDetail['title']);
		$email_subject = stripslashes($tempDetail['subject']);
		$email_body	 = stripslashes($tempDetail['body']);
		$email_cc	 = stripslashes($tempDetail['cc_email']);
		$email_tags	 = stripslashes($tempDetail['tags']);
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
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($tempDetail) && !empty($tempDetail)){ echo "Edit"; } else { echo "Add"; } ?> E-mail Content</legend>
				<form <?php if(isset($_GET['action']) && $_GET['action'] == 'edit') { ?> id="frmContentedit" name="frmContentedit" <?php } else { ?> id="frmContentadd" name="frmContentadd" <?php }?> method="post" action="edit_content.php?action=<?php if(isset($_GET['action']) && $_GET['action'] == 'edit') echo 'update'; else { echo 'add'; };?>">
				<p>Template Name<em>*</em></p>
				<div style="padding: 10px 0;">
					<input type="text" id="email_title" name="email_title" class="required" value="<?php if(isset($email_title)){ echo $email_title; } ?>" <?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'disabled'; };?>/>
					<?php if(isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
					<input type="hidden" id="current_title" name="current_title" value="<?php if(isset($email_title)){ echo $email_title; } ?>">
					<?php } ?>
				</div>

				<p>E-mail Subject<em>*</em></p>
				<div style="padding: 10px 0;">
					<input type="text" id="email_subject" name="email_subject" class="required" value="<?php if(isset($email_subject)){ echo $email_subject; } ?>"/>
					<?php if(isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
					<input type="hidden" id="current_email_subject" name="current_email_subject" value="<?php if(isset($email_subject)){ echo $email_subject; } ?>">
					<?php } ?>
				</div>

				<script type="text/javascript">
				jQuery(document).ready(function(){
						tinyMCE.init({
							// General options
							theme :		"advanced",
							mode:		"exact",
							elements :  "email_body",
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
						jQuery("#frmContentadd").submit(function(){
							var content	= tinyMCE.activeEditor.getContent();
							if(content == ''){								
								jQuery('#content_info').show();
								return false;
							} else {	
								jQuery('#content_info').hide();
								return true;
							}						
						});
					});
					</script>

				<p>E-mail body<em>*</em></p>
				<div style="padding: 10px 0;">
					<textarea id="email_body" name="email_body" class="required" rows = '15' style="width: 650px;" cols="30"><?php if(isset($email_body)){ echo $email_body; } ?></textarea>

					<label for="email_body" generated="true" id="content_info" class="error" style="display: none;">This field is required.</label>
				
				You can use only <?php 
					$tagsArray = explode(';', $email_tags);
					foreach($tagsArray as $key => $tag){			
						echo stripslashes($tag).', ';
					}?> tags in E-mail body
				<br/></div>

					<input name="tags" type="hidden" value="<?php if(isset($email_tags)){ echo stripslashes($email_tags); } ?>"/>

				<p>CC E-mail</p>
				<div style="padding: 10px 0;">
					<textarea name="cc_email" rows = '3' cols="30" style="width: 689px;"><?php if(isset($email_cc)){ echo stripslashes($email_cc); } ?></textarea>
					<br/>E-mails should be separated by semilcolon(;)
				</div>

				<div class="submit1 submitbtn-div">
					<label for="submit" class="left">
						<?php if(isset($tempDetail) && !empty($tempDetail)){ ?>
						<input type="hidden" value="<?php echo $tempid; ?>" name="tempid"/>
						<input type="submit" value="Submit" name="update_content" class="submitbtn" >
						<?php } else { ?>
						<input type="submit" value="Submit" name="add_content" class="submitbtn" >
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


