<?php
/******************************************
* @Modified on Dec 21, 2012
* @Package: Rand
* @Developer: Mamta Sharma
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/header.php";
checkSession(false);
$user_id = $_SESSION['user']['id'];
if(isset($_POST['submit'])){
	$user = new user();
	$oldpassword = $_POST['oldpassword'];
	$password = $_POST['password'];
	$cpassword = $_POST['cpassword'];
	if(md5($oldpassword) == $_SESSION['user']['password'])
	{
		$sql = "update users set password = '".md5($password)."' where id = '".$user_id."'";
		$result = mysql_query($sql);

		$_SESSION['user']['password'] = md5($password);
		$_SESSION['msgsuccess'] = 'Password changed successfully';
		header("location:".URL_SITE."/"."index.php");

	}
	else 
	{
		echo $_SESSION['msgerror'] = "Old Password doesn't match";
		header("location:".URL_SITE."/"."changePassword.php");
		//exit;
	}
	
}

?>
  <!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">
		<!-- left side -->
		<div class="containerL">
			
			<div style="clear: both; padding: 15px 0;">
			<fieldset style="border: 1px solid #cccccc; padding: 10px;">
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Change Password</legend>
				<form method="POST" id="changepw" name="changepw">
				
				<p>Old Password</p>
				<div style="padding: 10px 0;">
					<input type="password" id="password" name="oldpassword"  class="required"/>
				</div>
				<p>New Password</p>
				<div style="padding: 10px 0;">
					<input type="password" id="password" name="password"  class="required"/>
				</div>
				<p>New Password</p>
				<div style="padding: 10px 0;">
					<input type="password" id="cpassword" name="cpassword"  class="required"/>
				</div>


				<div class="submit1 submitbtn-div">
					<label for="submit" class="left">
						<input type="submit" value="Update" name="submit" class="submitbtn" >
					</label>
					<label for="reset" class="right">
						<input type="reset" id="reset" class="submitbtn">
					</label>
				</div>
				</form>
				</fieldset>
			</div>
		 </div>
		<!-- left side -->
		
</section>
<!-- /container -->
