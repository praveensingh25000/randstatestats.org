<?php
/******************************************
* @Modified on Dec 21, 2012
* @Package: Rand
* @Developer: Mamta Sharma
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/header.php";
if(isset($_POST['submit'])){
	$admin = new admin();
	$user = new user();
    $email=$_POST['email'];
	$password=$admin->generatePassword();
	$password_update=$user->userForgotPassword($email,md5($password));
	if($password_update>0)
	{
		$msg="<p>Hello,</p>";
		$msg.="<p>Your changed password has given below.</p>";
		$msg.="<table width='50%'>";
	    $msg.="<tr><td>Password:  ".$password."</td></tr>";
		$msg.="</table>";
		$msg.="<p>You can <a href='".URL_SITE."/index.php'>login</a> in this link.</p>";
		$mail=new PHPmailer;
		$mail->isHtml(true);
		$mail->FromName='Rand Site';
		$mail->From='Rand Site';
		$mail->AddAddress("ideamamta@yahoo.com");
		//$mail->AddAddress(FROM_EMAIL);
		$mail->Subject="Forgot Password Mail.";
		$mail->Body = $msg;
		$mail->WordWrap=50;
		if(!$mail->Send()) 
		{
			echo "Message was not sent";
			echo "Mailer error".':'.$mail->ErrorInfo;
		}
		$_SESSION['msgsuccess']="<center><font color='red'>Your new password has sent to your mail.Please Check Your Mails.</font></center>";
		header("location: login_popup.php");
	}
}
?>
  <!-- container 
<section id="container">
	 <!-- main div 
	 <div class="main-cell">
		<!-- left side 
		<div class="containerL">
			
			<div style="clear: both; padding: 15px 0;">
			<fieldset style="border: 1px solid #cccccc; padding: 10px;">
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Forgot Password</legend>
				<form method="POST" id="changepw" name="changepw">
				
				<p>Enter your Email</p>
				<div style="padding: 10px 0;">
					<input type="text" id="email" name="email"  class="email required"/>
				</div>
				
          <div class="submit1 submitbtn-div">
					<label for="submit" class="left">
						<input type="submit" value="Update" name="submit" class="submitbtn" >
					</label>
					
				</div>
				</form>
				</fieldset>
			</div>
		 </div>
		<!-- left side 
		
</section>
<!-- /container -->
