<?php
/******************************************
* @Modified on Mar 06, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";
$admin = new admin();
$emailObj = new emailTemp();

if(isset($_POST['send'])) {
	$_SESSION['contact'] = $_POST;
	if(isset($_POST['captcha_code']) && ($_SESSION['security_number'] == $_POST['captcha_code'])){
		$name			=	ucfirst($_POST['name']);
		$email			=	$_POST['email'];
		$phone			=	$_POST['phone'];
		$address		=	$_POST['address'];
		$message		=	$_POST['message'];
		$contactid		=	$admin->contactUs($name,$phone,$email,$address,$message);
		
		if($contactid > 0) {		

			$receivename		= CONTACT_NAME;
			$receivermail		= CONTACT_EMAIL;
			//$receivermail		= 'praveensingh2500@gmail.com';
			$from_name			= $name;
			$from_email			= $email;
			$userDefinedvalue	= array(array('name' => $name), array('email'=>$email), array('phone'=>$phone), array('address'=>$address), array('message'=>$message));

			$tempid = '8';
			$tempDetail = $emailObj->getTemp($tempid);
			if(!empty($tempDetail)){
				$emailSubject	 = stripslashes($tempDetail['subject']);
			}
			
			$subject     = $emailSubject;	
			
			$mailbody		= getMailTemplate($key=8, $receivename, $receivermail, $from_name, $from_email,$userDefinedvalue);
				
				if(isset($mail_notification) && $mail_notification == '1'){
					//$subject='Enquiry!';	
					$send_mail= mail_function($receivename,$receivermail,$from_name,$from_email,$mailbody,$subject, $attachments = array(),$addcc=array());
				}

			$_SESSION['msgsuccess']="22";
			unset($_SESSION['contact']);
			header("location:contact_us.php");
			exit;
		}
	} else {
		$_SESSION['msgerror'] = "26";
		header("location:contact_us.php");
		exit;
	}
}
?>
<section id="container">		
	<div id="mainshell">	
       <h2>Contact Us</h2><br />
	   <div id="content">
        <form action="" method="post" name="contactusform" id="contactusform"  autocomplete="on">
			<div class="contact">
					<div class="inputshell">
						<p>Name<em>*</em></p>
						<input placeholder="Enter your name" type="text" name="name" id="name" class="required" value="<?php if(isset($_SESSION['user'])){ echo $_SESSION['user']['name'];} else if(isset($_SESSION['contact']) && isset($_SESSION['contact']['name'])){ echo $_SESSION['contact']['name']; }?>"/>
						<br class="clear" />
						<label style="display:none;padding-left: 127px;" for="name" generated="true" class="error">This field is required.</label>
					</div>
					<div class="clear"></div>

					<div class="inputshell">
						<p>Email<em>*</em></p>
						<input placeholder="Enter your email" type="text" name="email" id="email" class="required email" value="<?php if(isset($_SESSION['user'])){ echo $_SESSION['user']['email'];} else if(isset($_SESSION['contact']) && isset($_SESSION['contact']['email'])){ echo $_SESSION['contact']['email']; } ?>"/>
						<br class="clear" />
						<label style="display:none;padding-left: 127px;" for="email" generated="true" class="error">This field is required.</label>
					</div>
					<div class="clear"> </div>

					<div class="inputshell">
						<p>Phone<em>*</em></p>
						<input placeholder="Enter your phone number" type="text" name="phone" id="phone" class="required" value="<?php if(isset($_SESSION['user'])){ echo $_SESSION['user']['phone'];} else if(isset($_SESSION['contact']) && isset($_SESSION['contact']['phone'])){ echo $_SESSION['contact']['phone']; }?>" id="phone" onchange="chckphone()"/>
						<br class="clear" />
						<em style="padding-left: 127px;">eg:123-123-1234</em>
						<br class="clear" />
						<label style="display:none;padding-left: 127px;" for="phone" generated="true" class="error">This field is required.</label>
					</div>
					<div class="clear"></div>

					<div class="inputshell">
						<p>Address</p>
						<textarea placeholder="Enter your address" name="address" id="address"><?php if(isset($_SESSION['user'])){ echo $_SESSION['user']['address'];} else if(isset($_SESSION['contact']) && isset($_SESSION['contact']['address'])){ echo $_SESSION['contact']['address']; }?></textarea>
					</div>
					<div class="clear"> </div>

					<div class="inputshell">
						<p>Message<em>*</em></p>
						<textarea placeholder="Enter your message body" name="message" id="message" class="required"><?php if(isset($_SESSION['contact']) && isset($_SESSION['contact']['message'])){ echo $_SESSION['contact']['message']; }?></textarea>
						<br class="clear" />
						<label style="display:none;padding-left: 127px;" for="message" generated="true" class="error">This field is required.</label>
					</div>
					<div class="clear"> </div>

					<div class="inputshell">
						<p>Captcha<em>*</em></p>
						<div class="left">
							<span id="captcha_code"><?php  if(!isset($_SESSION['user'])){ require_once($DOC_ROOT.'captcha_code_file.php'); } ?>
							</span><br>
							<small>Can't read.<a href='javascript: refreshCaptchaCode();'>click here</a> to refresh</small>						
						</div>
					</div>
					<div class="clear"></div>


					<div class="inputshell">
						<p>&nbsp;</p>
						<input class="" type="submit" name="send" value="Submit"/>
						<input class="mL20" type="reset" name="reset" value="Reset"/>
						<input class="mL20" type="button" onclick="javascript: window.history.go(-1);" name="back" value="Back"/>
					</div>
			</div>
		</form>
		</div>
		<div id="sidebar">
			<h2 class="contact-title">We are located at:</h2> <br />
			<address>
				RAND State Statistics<br />
				1776 Main Street<br />
				P.O. Box 2138<br />
				Santa Monica, CA  90407-2138<br />
				<img src="<?php echo URL_SITE; ?>/images/phone.png" >&nbsp;&nbsp;(800) 492-7959 <br />
				<img src="<?php echo URL_SITE; ?>/images/email_icon.png" >&nbsp;&nbsp;<a href="mailto:info@randstatestats.org">info@randstatestats.org</a>
			<address>
		</div>
	</div>
	<!--/Application right-->
	</div>
	<!--/Application main-->
</section>
<!-- /container -->
<div class="clear"></div>

<?php 
include($basedir.'/include/footerHtml.php')
//end
?>
<script>
function chckphone(){
	var filter = /^[0-9]{3}-[0-9]{3}-[0-9]{4}$/;
	var number = $("#phone").val();
	
	 var test_bool = filter.test(number);

	 if(test_bool==false){
	  alert('Please enter phone number in US format');
	  $("#phone").val('');
	  return false; 
	 }
}
</script>