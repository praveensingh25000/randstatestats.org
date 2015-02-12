<?php
/******************************************
* @Modified on Dec 18, 2012
* @Package: Rand
* @Developer: Mamta Sharma
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

if(isset($_POST['submit'])){
	
	$user = new user();
	$admin = new admin();
	$username = $_POST['email'];
	$password = md5($_POST['password']);
	$userDetail = $user->login($username, $password);

	if(!empty($userDetail)){
		
		$_SESSION['user']	=   $userDetail;	//setting session

		if($userDetail['parent_user_id'] != 0){	//  Case where multiple user is login & primary user details need to be check
			$plan_user_id = $userDetail['parent_user_id'];
			$userDetail = $user->getUser($plan_user_id);
		} else {
			$plan_user_id = $userDetail['id'];

		}

		$validity_on		=	$admin->Validity($plan_user_id,$userDetail['email']);

		if(isset($validity_on) && $validity_on == '0') {
			$_SESSION['msgsuccess'] = '0';
			header('location: plansubscriptions.php');
		} else {
			$_SESSION['msgsuccess'] = '1';
			header('location: index.php');
		}
	}else {
		$_SESSION['msgerror'] = '2';
		header('location: login.php');
	}
	exit;
}

if(isset($_GET['verification']))
{
	$email_v=$_GET['verification'];
	$email_v=base64_decode($email_v);
	$not_a_used_link=user::check_verification_of_account($email_v);
	
	if($not_a_used_link)
	{
		$_SESSION['msgsuccess'] = '3';
	}
	else
	{
		$_SESSION['msgerror'] = '4';
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
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;">User Login</legend>
				<form method="POST" id="userLogin" name="userLogin">
				<p>Email</p>
				<div style="padding: 10px 0;">
					<input type="text" id="email" name="email" class="required"/>
				</div>

				<p>Password</p>
				<div style="padding: 10px 0;">
					<input type="password" id="password" name="password"  class="required"/>
				</div>

				<div class="submit1 submitbtn-div">
					<label for="submit" class="left">
						<input type="submit" value="Submit" name="submit" class="submitbtn" >
					</label>
					<label for="reset" class="right">
						<input type="reset" id="reset" class="submitbtn">
					</label>
				</div>
				</form>
				<!-- Facebook Log In -->
			<div id="" class="">
					<?php
					include("facebookLogin.php");		
					?>
					<a href="<?php echo $loginUrl;?>">
						<img src="<?php echo URL_SITE ?>/images/facebooklogin.png" class="" />
					</a>
			</div>
			
				</fieldset>
			</div>
		 </div>
		<!-- left side -->		
</section>
<!-- /container -->
<div class="clear"></div>

<?php include_once $basedir."/include/footerHtml.php"; ?>