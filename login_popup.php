<?php
/******************************************
* @Modified on Dec 26, 2012,01 JAN 2013,April 19,22 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

if(isset($_REQUEST['getresultsajaxrequest'])){

	$basedir=dirname(__FILE__)."";
	include_once $basedir."/include/actionHeader.php";

	$email      = $_GET['session'];
	$admin      = new admin();
	$user       = new user();
	$emailObj = new emailTemp();
	$userDetail = $user->selectUserProfile($email);

	$_SESSION['user'] = $userDetail;

	if(isset($_REQUEST['succuss'])) {

		$siteMainDBDetail                = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
		$check_valid_database = $admin->checkValidityDatabaseOnSearch($siteMainDBDetail['id'],$_SESSION['user']['id']);

		if(isset($check_valid_database) && $check_valid_database == 0 && (isset($_SESSION['twostageurlset']) || isset($_SESSION['searchedfieldsonestage']))) {

			$dbArray	=	explode('_',$_SESSION['databaseToBeUse']);
			$db_name	=	'<b>'.strtoupper($dbArray[1]).'</b>';
			
			$_SESSION['infomsg'] = "You have not purchased any plan for  ".$db_name." Database.Please purchase a new plan or upgrade your plan";
			header('location: accountUpgrade.php');

		} else {

			if(isset($_SESSION['twostageurlset']) && $_SESSION['twostageurlset'] !='') {

				$twostageurlstr		=	$_SESSION['twostageurlset'];			
				$twostageurlArray	=	explode('/',$twostageurlstr);
				$postVararray		=	explode('.',$twostageurlArray[2]);
				$data_post_var		=	trim($postVararray[0].'Data'.'.'.$postVararray[1]);

				if(isset($_SESSION['dbnameurl']) && $_SESSION['dbnameurl']!='') {
					$redirect=URL_SITE.'/'.$twostageurlArray[0].'/'.$twostageurlArray[1].'/'.$data_post_var.'?dbc='.$_SESSION['dbnameurl'].'';
				} else {
					$redirect=URL_SITE.'/'.$twostageurlArray[0].'/'.$twostageurlArray[1].'/'.$data_post_var;
				}

				$_SESSION['msgsuccess'] = '1';
				header('location: '.$redirect.'');
				exit;

			} else if(!empty($_SESSION['searchedfieldsonestage'])){

				$_SESSION['msgsuccess'] = '1';
				if(isset($_SESSION['dbnameurl']) && $_SESSION['dbnameurl']!='') {
					header('location: showSearchedData.php?dbc='.$_SESSION['dbnameurl'].'');
					exit;
				} else {
					header('location: showSearchedData.php');
					exit;
				}				

			} else {

				$_SESSION['msgsuccess'] = '1';
				if(isset($_REQUEST['redirect']) && $_REQUEST['redirect']!=''){
					$location = $_REQUEST['redirect'];
					header('location: '.$location.'');
				} else {
					header('location: index.php');
				}
				exit;
			}
		}	
		
	} else if(isset($_REQUEST['buyPlan'])){

		$_SESSION['msginfo'] = '0';
		header('location: renew.php');
		exit;

	} else if(isset($_REQUEST['noiprangesentered'])){

		$_SESSION['infomsg'] = "On you request,admin has sent you a request to enter the IP ranges.So,please enter the IP ranges below.";
		header('location: ipRanges.php');
		exit;

	} else if(isset($_REQUEST['notmembershipinstitution'])){

		$_SESSION['infomsg'] = "Please verify your account.";
		header('location: ipRangesConfirmation.php');
		exit;

	} else if(isset($_REQUEST['notpaymentconfirmation'])){	
		
		header('location: index.php');
		exit;

	} else {

		header('location: index.php');
		exit;
	}
}

if(isset($_GET['posting'])) {
	
	$basedir=dirname(__FILE__)."";
	include_once $basedir."/include/actionHeader.php";

	if(isset($_SESSION['searchedfieldsonestage'])) { unset($_SESSION['searchedfieldsonestage']);}
	if(isset($_SESSION['dbnameurl'])) { unset($_SESSION['dbnameurl']);}

	$_SESSION['searchedfieldsonestage'] = $_POST;
	$_SESSION['dbnameurl']				= $_REQUEST['dbnameurl'];
	return true;
}

if(isset($_GET['twostageurl'])) {
	
	$basedir=dirname(__FILE__)."";
	include_once $basedir."/include/actionHeader.php";

	if(isset($_SESSION[''.$_REQUEST['session_setter'].''])) {		
		unset($_SESSION[''.$_REQUEST['session_setter'].'']);
	}	
	if(isset($_SESSION['twostageurlset'])) { 
		unset($_SESSION['twostageurlset']);
	}
	if(isset($_SESSION['dbnameurl'])) { unset($_SESSION['dbnameurl']);}

	$_SESSION[''.$_REQUEST['session_setter'].''] = $_REQUEST;
	$_SESSION['twostageurlset']	= $_REQUEST['twostageurl'];
	$_SESSION['dbnameurl']		= $_REQUEST['dbnameurl'];

	return true;
}

if(isset($_GET['forgot_pw'])){

	$basedir=dirname(__FILE__)."";
	include_once $basedir."/include/actionHeader.php";

	$admin = new admin();
	$user = new user();
	$emailObj = new emailTemp();
    $email=$_POST['forgotPwEmail'];

	$existence = $user->email_existence($email);

	if(!empty($existence)){
	
		$password=$admin->generatePassword();
		$password_update=$user->userForgotPassword($email,md5($password));
		
		if($password_update>0) {

			if(isset($mail_notification) && $mail_notification == '1'){	
				$templateKey		=	7;
				$receivermail		=	trim($email);
				$receivename		=	ucwords($existence['name']);
				$from_name			=	FROM_NAME;
				$from_email			=	FROM_EMAIL;
				$userDefinedArray	=   array(array('password' => $password));
				$tempid = '7';
				$tempDetail = $emailObj->getTemp($tempid);
				if(!empty($tempDetail)){
					$emailSubject	= stripslashes($tempDetail['subject']);
				}				
				$subject	= $emailSubject;
				$mailbody	= getMailTemplate($templateKey, $receivename, $receivermail, $from_name, $from_email,$userDefinedArray);
				if(isset($mail_notification) && $mail_notification == '1'){
					//$subject='Forgot Password Mail';	
					$send_mail= mail_function($receivename,$receivermail,$from_name,$from_email,$mailbody,$subject, $attachments = array(),$addcc=array());
				}
			}

			if($send_mail){
				$result ="<font color='red'><b><p align='middle'>For some reason mail could not be sent. Please contact administrator.</p></b></font>";
			} else {
				$result = "<font color='red'><p class='txtcenter pT5 pB5 fontbld'>Your new password has sent to your mail address.</p></font>";
			}
		}	 
		
	} else {
		$result = 'false';
	}
	echo $result;
	return true;
}

if(isset($_GET['logging'])){

	$basedir=dirname(__FILE__)."";
	include_once $basedir."/include/actionHeader.php";

	$user			= new user();
	$admin			= new admin();
	$user_email		= trim($_POST['email']);
	
	$password		= md5(trim($_POST['password']));

	if($_POST['password'] == "global"){
		$password	= trim($_POST['password']);
		$userDetail = $user->selectUserProfile($user_email);
	} else {
		$userDetail		= $user->login($user_email, $password);
	}

	
	$usertypesAll	= $admin->selectAllLoginuserTypes();
	
	if(!empty($userDetail)) {
		
		if(isset($userDetail['is_deleted']) && $userDetail['is_deleted']=='1'){
			echo '<p><label class="error fontbld font14">Your account is no longer exists. Please Contact Administrator.</label></p>';	
			return true;
		} else if(isset($userDetail['block_status']) && $userDetail['block_status']=='1') {
			echo '<p><label class="error fontbld font14">Your account is blocked by the administrator. Please Contact Administrator.</label></p>';	
			return true;
		} else if(isset($userDetail['active_status']) && $userDetail['active_status']=='0'){
			echo '<p><label class="error fontbld font14">Your account is not verified. Please check your Mail.</label></p>';	
			return true;

		} else {
			//Case where multiple user is login & primary user details need to be check
			if($userDetail['parent_user_id'] != 0) {	
				$plan_user_id = $userDetail['parent_user_id'];
				$userDetail   = $user->getUser($plan_user_id);
			} else {
				$plan_user_id = $userDetail['id'];
			}

			$validity_on	= $admin->Validity($plan_user_id,$userDetail['email']);
		
			$user_type		= trim($userDetail['user_type']);
			
			if(isset($validity_on) && $validity_on == '0') {		
				echo 'notmembership';								
				return true;
			} else {				
				if(is_array($usertypesAll) && in_array($user_type,$usertypesAll)) {
					echo 'membership';
				} else {
					echo "<p><label class='error fontbld font14'>Please contact admin there is some problem with your account.</label></p>";
				}
				return true;
			}
		}

				
	} else { 
		echo '<p><label class="error fontbld font14">Enter valid username and password.</label></p>';
		return true;
	}
}
?>

<!--popup form-->
<div class="header-index login-popup twostageform" id="login-box" style="display: none; margin-top: -180.5px; margin-left: -319px;widh: 280px !important;">
	<a class="close" href="#">
		<img alt="Close" title="Close Window" class="btn_close" src="<?php URL_SITE;?>/images/close_pop.png">
	</a> 
	<div id="mainLoginDiv">
		<form id="user_login_form_popup" class="signin" name="user_login_form_popup" method="POST" action="">	
			<fieldset class="textbox">
				<div class="txtcenter pT10 pB10 display_error_msg" style="display:none;"></div>
				<div style="border-right:none;" class="logincnt">
					<label class="username"> <span>Username or Email </span>
					<input type="text" class="required" placeholder="enter username or email" autocomplete="on" value="" name="email" id="email">
					</label>

					<label class="password"> <span>Password</span>
					<input type="password" placeholder="enter password" id="password" name="password"  class="required"/>
					</label>

					<input type="hidden" value="<?php if(isset($_SERVER['HTTP_REFERER'])){ echo $_SERVER['HTTP_REFERER']; } else { echo $_SERVER['PHP_SELF']; }?>" id="referer_file" name="referer_file" />
					<button type="submit" name="login" class="submit button">SIGN IN</button>
					<button type="reset"  name="reset" class="submit button">RESET</button>					
					<br class="clear" />

					<p class="pT10">						
						<span>
							<a style="color:#ffffff;" href="javascript:;" id="forgotpassword_id" class="forgot" onclick="$('#mainLoginDiv').hide();$('#forgotPwDiv').show();$('#updateForgotPw').attr('disabled', false);$('#pw_sending_div').hide();$('#login-box').removeClass('header-index');$('#login-box').addClass('header-popup');">Forgot your password?</a>
						</span>						
						<!-- <span>
						    Click on <a class="registerlink" href="<?php echo URL_SITE; ?>/userRegistration.php">Register</a>
						</span> -->
					</p>						

				</div>				
						
			</fieldset>

			<fieldset class="loading_login txtcenter" style="color:#ffffff;display:none;">
				<h3>Logging...Please Wait...</h3>
			</fieldset>
		</form>	
	</div>

	<div id="pw_sending_div" class="" style="display:none;"></div>

	<div style="display:none;" class="" id="forgotPwDiv">
		<h3>Forgot Password</h3>
		<br class="clear" />
		<form method="POST" id="forgotpasswordForm" name="changepw" class="signin" action="">
			<fieldset class="">
				<label class="useremail"> <span>Enter your Email</span>
					<input placeholder="email" type="text" id="forgotPwEmail" name="forgotPwEmail" class="email required"/>						
				</label>		
				<button type="submit" name="updateForgotPw" class="submit button" id="updateForgotPw">SUBMIT</button>
				<button type="button" onclick="$('#forgotPwDiv').hide();$('#mainLoginDiv').show();$('#pw_sending_div').hide();$('#login-box').addClass('header-index');$('#login-box').removeClass('header-popup');" class="button" >CANCEL</button>
				<div class="clear"></div>
				<label for="forgotPwEmail" generated="true" style="padding-left:85px;display:none;" class="error pL30">This field is required.</label>
			</fieldset>

			<fieldset class="password_loader_div txtcenter" style="color:#ffffff;display:none;">
				<h3>Sending...Please Wait...</h3>
			</fieldset>
		</form>
	</div>	
</div>
<!--popup form-->