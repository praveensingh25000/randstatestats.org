<?php
/******************************************
* @Modified on September 6, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$user = new user();

if(isset($_POST['userids'])){
	$subject = (isset($_POST['subject']))?$_POST['subject']:'';
	if(isset($_POST['sento']) && count($_POST['sento']) >0){
		if(isset($_POST['userids']) && count($_POST['userids']) >0){
			$userids = $_POST['userids'];
			$sendtonamearray = $_POST['sento'];
			foreach($sendtonamearray as $sendtoname => $sendtonamestr){
				$emailsarray = $_POST[$sendtoname];
				foreach($userids as $keyUser => $userid){
					if(array_key_exists($userid, $emailsarray)){
						$email = $emailsarray[$userid];
						$mailbody = stripslashes($_POST['email_body']);
						$addbcc = array('nation@randstatestats.org');				
						mail_function('',$email,FROM_NAME,FROM_EMAIL,$mailbody,$subject, array(), $addbcc);
					}
				}
			}
			$_SESSION['successmsg'] = "Email sent successfully";
		} else {
			$_SESSION['errormsg'] = "Please choose the users to whom you want to send email";
		}
	} else {
		$_SESSION['errormsg'] = "Please choose the sent to customers checkboxes";
	}
	header('location: sendMails.php');
	exit;

}


$resultUsers = $user->showAllUsers();
$totalUsers = mysql_num_rows($resultUsers);

$typesResult = $admin->showAllUserTypes(1);
$types = $db->getAll($typesResult);

$mailTemplate = '<div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;">
							 
							<div style="width:100%;height:auto;overflow:hidden;text-align:left;padding-bottom:5px;display:block;">		
							<img src="'.$URL_SITE.'/images/logo.png" />
							<div style="clear:both; padding: 10px 0 10px 0;"><br/></div>
							</div>

							<div style="padding:10px 0px 10px 10px;">
							   Hello Dear <br/><br/>
							   Thanks for stopping by our booth last week in Chicago.  Your RAND State Statistics account has been created successfully. Your free trial period will last for 30 days.
							<div style="clear:both; padding: 10px 0;"></div>
							</div>									

							<div style="padding:10px 0px 10px 10px;">
							   With hundreds of detailed databases covering all 50 states (and more than 100 to be added over the next two months), we are sure you will like the site. At the end of the trial period, please subscribe to one of our plans through the Subscribe link at the top of our home page. You can see the plans listed through the link Subscription Plans.
							<div style="clear:both;"></div>
							</div>			

							<div style="padding:10px 0px 10px 10px;">
								<strong>Best regards</strong>,<br/><br/>
								Joe Nation, Ph.D.<br/>
								Director<br/>
								RAND State Statistics<br/>
								<a href="'.$URL_SITE.'">'.$URL_SITE.'</a><br/>
								<a href="mailto:nation@randstatestats.org">nation@randstatestats.org</a><br />
								Office: 415-785-4993<br />Mobile: 415-602-2973
							<div style="clear:both;"><br/><br/></div>
							</div>							
						</div>
					 ';
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
			<h2>Send Mails</h2><br>
			<p>From here you can send mails to multiple users in a single click. Please choose from below the list of users & then click send after creating your message.</p><br/>
			
			<form action="" method="post" id="sendMails" name="sendMails">

			<p class="pB5">Send To:</p>
			<div style="padding: 10px 0;">
			<input type="checkbox" name="sento[useremails]" value="1"/> User Email&nbsp;
			<input type="checkbox" name="sento[adminemails]" value="1"/> Admin Email&nbsp;
			<input type="checkbox" name="sento[techemails]" value="1"/> Techinical Email&nbsp;
			<input type="checkbox" name="sento[billemails]" value="1"/> Billing Email&nbsp;
			</div>

			<div style="padding: 10px 0;">
			<?php if(!empty($types)) { ?>
				Select User Type:&nbsp;<select class="required" id="user_type" name="user_type" style="width:355px;">
					<option value="0"> All Users </option>
					<option value="-1">All Users Except Single Users</option>
					<?php foreach($types as $userTypes) { ?>
						<option value="<?php echo $userTypes['id'];?>" ><?php echo ucwords($userTypes['user_type']);?></option>
					<?php } ?>							
				</select>
			<?php } ?>
			</div>

			<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('#user_type').change(function(){
					if(jQuery(this).val()!=''){

						loader_show();
						var user_type = jQuery(this).val();
						jQuery.ajax({
							url: 'getUsers.php',
							type: 'post',
							data: 'user_type='+user_type,
							success: function(msg){
								jQuery('#tbody').html(msg);
								loader_unshow();
							}
						});
					}
				});
			
			});
			</script>

			<table   class="data-table" id="grid_view" >
				<thead>
					<tr>
						<th bgcolor="#eeeeee"><span>Select All</span>&nbsp;<input type="checkbox" id="check_all_user" /></th>
						<th bgcolor="#eeeeee">Name</th>
						<th bgcolor="#eeeeee">Organisation</th>
						<th bgcolor="#eeeeee">User Email / Admin Email</th>
						<th bgcolor="#eeeeee">Billing Email / Technical Email</th>
					</tr>
				</thead>
				<tbody id="tbody">
					
					<?php if(count($totalUsers)>0){
						while($userDetail = mysql_fetch_assoc($resultUsers)){
							$additionalDetail = $user->getUserAdditionalFields($userDetail['id']);
					?>
					
					<input type="hidden" name="adminemails[<?php echo $userDetail['id']; ?>]" value="<?php if(!empty($additionalDetail)) { echo trim($additionalDetail['admin_email']); }?>"/>

					<input type="hidden" name="useremails[<?php echo $userDetail['id']; ?>]" value="<?php echo $userDetail['email']; ?>"/>

					<input type="hidden" name="techemails[<?php echo $userDetail['id']; ?>]" value="<?php if(!empty($additionalDetail)) { echo trim($additionalDetail['tech_email']); } ?>"/>

					<input type="hidden" name="billemails[<?php echo $userDetail['id']; ?>]" value="<?php if(!empty($additionalDetail)) { echo trim($additionalDetail['bill_email']); }?>"/>

					<tr>
						<td align="center"><input type="checkbox" class="check_all" name="userids[]" value="<?php echo $userDetail['id']; ?>"/></th>

						<td><?php echo stripslashes($userDetail['name'].' '.$userDetail['last_name']); ?></td>
						<td><?php echo stripslashes($userDetail['organisation']); ?></td>
						<td><?php echo $userDetail['email']; ?> / <?php if(!empty($additionalDetail)) { echo trim($additionalDetail['admin_email']); } else { echo "NA"; } ?></td>
	
						<td><?php if(!empty($additionalDetail) && $additionalDetail['bill_email']!='') { echo trim($additionalDetail['bill_email']); } else { echo "NA"; }?> / <?php if(!empty($additionalDetail) && $additionalDetail['tech_email']) { trim($additionalDetail['tech_email']); } else { echo "NA"; } ?></td>
					
					</tr>


					<?php
						}
					}
					?>
				</tbody>
			</table>
			
			<script type="text/javascript">
				jQuery(document).ready(function(){
						tinyMCE.init({
							// General options
							theme :		"advanced",
							mode:		"exact",
							elements :  "email_body",
							width: "400",
							height: "300",
							relative_urls: false,
							convert_urls: false,


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
				<br/>
				<p class="pB5">Subject</p>
				<div style="padding: 10px 0;">
				<input type="text" value="" name="subject" />
				</div>

				<p>E-mail body<em>*</em></p>
				<div style="padding: 10px 0;">
					<textarea id="email_body" name="email_body" class="required" rows = '15' cols="30"><?php if(isset($email_body)){ echo $email_body; } else { echo $mailTemplate; }  ?></textarea>

					<label for="email_body" generated="true" id="content_info" class="error" style="display: none;">This field is required.</label>
					
				</div>

				<div class="submit1 submitbtn-div">
					<label for="submit" class="left">									
						<input type="submit" value="Send Mail To Users" name="sendmails" class="submitbtn" >
					</label>
				</div>
				</form>


		 </div>
		<!-- left side -->

		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#check_all_user").change(function () {
				jQuery('.check_all').attr('checked', this.checked);
			});
		});
		</script>

		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>



