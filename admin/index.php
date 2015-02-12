<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

$admin = new admin();

if(isset($_SESSION['admin'])){
	header('location: home.php');
}

if(isset($_POST['getresults'])){	
	
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	
	$adminDetail = $admin->login($username, $password);
	
	if(!empty($adminDetail)){

		$_SESSION['admin'] = $adminDetail;
		$_SESSION['msgsuccess'] = "You are logged in successfully.";
		header('location: databases.php');

	} else {

		$_SESSION['errormsg'] = "Username/Password is wrong.";
		header('location: index.php');
	}
	exit;
}
?>
<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">
		<!-- left side -->
		<div class="containerL loginAdmin">
			
			<div style="clear: both; padding: 15px 0;">
				<fieldset style="border: 1px solid #cccccc; padding: 10px;">
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Admin Login</legend>
					<br class="clear" />

					<form method="post" id="frmAdminLogin" name="frmAdminLogin">

						<p>Username</p>
						<div style="padding: 10px 0;">
							<input placeholder="enter username" type="text" id="username" name="username" class="required"/>
						</div>

						<p>Password</p>
						<div style="padding: 10px 0;">
							<input placeholder="enter password" type="password" id="password" name="password"  class="required"/>
						</div>

						<div class="submit1 submitbtn-div">
							<label for="submit" class="left">
								<input type="submit" value="Submit" name="getresults" class="submitbtn" >
							</label>
							<label for="reset" class="right">
								<input type="reset" id="reset" class="submitbtn">
							</label>
						</div>
					</form>
				</fieldset>
			</div>
		</div>
	</div>
	<!-- left side -->		
</section>
<!-- /container -->

<?php 
include_once $basedir."/include/adminFooter.php";
?>