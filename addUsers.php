<?php
/******************************************
* @Modified on July 09, 2013.
* @Package: RAND
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

checkSession(false);
if($_SESSION['user']['user_type'] == 5 || $_SESSION['user']['parent_user_id']!='0'){ 
	header('location: index.php');
}

$admin			= new admin();
$user_id		=	$_SESSION['user']['id'];

$users = $admin->getMultipleUsers($user_id);

$total = count($users);

if(isset($_POST['userid'])){
	$name = (isset($_POST['uname']))?trim($_POST['uname']):'';
	$userid = (isset($_POST['userid']))?trim($_POST['userid']):'';
	$address = (isset($_POST['address']))?trim($_POST['address']):'';
	$organisation = (isset($_POST['organisation']))?trim($_POST['organisation']):'';
	$phone = (isset($_POST['phone']))?trim($_POST['phone']):'';
	$update = $admin->updateMultipleUser($name, $address, $organisation, $phone, $userid);
	if($update>0){
		$_SESSION['successmsg']="User has been update successfully.";
	} else {
		$_SESSION['errormsg']="User could not be update. Please try again.";
	}
	header('location: multipleUsers.php');
	exit;

}
if(!empty($_POST)){
	
	if(($total < $_SESSION['user']['number_of_users']) || $_SESSION['user']['user_type']!=6){

		$name = (isset($_POST['uname']))?trim($_POST['uname']):'';
		$email = (isset($_POST['email']))?trim($_POST['email']):'';
		$address = (isset($_POST['address']))?trim($_POST['address']):'';
		$organisation = (isset($_POST['organisation']))?trim($_POST['organisation']):'';
		$phone = (isset($_POST['phone']))?trim($_POST['phone']):'';

		$password = randomPassword();

		$user_login_type = $_SESSION['user']['user_type'];
		$expire_time = $_SESSION['user']['expire_time'];

		if($email != '' && $name!=''){

			$userid = $admin->addMultipleUser($name, '', $email, $password,$user_login_type, $expire_time, $address, $organisation, $phone, $user_id);
			
			if($userid>0){
				$fromname			=	FROM_NAME;
				$fromemail			=	FROM_EMAIL;
				$validity			=	VALIDITY;
				$mailbody			=	"Hi ".$name.", <br />
				<p>
					Your RAND State Statistics account has been created successfully.<br />
					Here are your login details:<br/>
					User ID: ".$email."<br/>
					Password: ".$password."<br/>
					You can change your password by editing your profile after you login at http://randstatestats.org.<br/>
					Please click the link below to login.<br/>
				</p>
				<p>
					<a href='http://randstatestats.org'>http://randstatestats.org.</a>
				</p>
				<p>
					If you are having trouble clicking on this link, please copy and paste it into your browser.<br/>Let us know what you think about our new site via email at info@randstatestats.org.
				</p>
				<p>Many thanks from the RAND State Statistics team.</p>";

				if(isset($mail_notification) && $mail_notification == '1'){
					$subject='Your Account Registration By '.$_SESSION['user']['name'] ;	
					$send_mail= mail_function($name,$email,$fromname,$fromemail,$mailbody,$subject, $attachments = array(),$addcc=array());
				}

				$_SESSION['successmsg']="User has been added successfully & mail sent with login details.";

				header('location: multipleUsers.php');

			} else {
				$_SESSION['errormsg']="User could not be added. Please try again.";
			}
		} else {
			$_SESSION['errormsg']="User could not be added because you have allready created your maximum users";
		}
	} else {
		$_SESSION['errormsg']="Please enter email & name.";
	}

	header('location: addUsers.php');
}

$name = $email = $organisation = $address = $phone = "";

if(isset($_GET['id'])){
	$userDetail = $admin->userDetail($_GET['id']);

	$name = $userDetail['name'];
	$email = $userDetail['email'];
	$organisation = $userDetail['organisation'];
	$address = $userDetail['address'];
	$phone = $userDetail['phone'];
}
?>

<!-- container -->
<section id="container">
	 <div class="main-cell">
		<div id="container-1">

			<div class="wdthpercent100 left">
				<div class="wdthpercent90 left">
					<h3>
						<?php if($_SESSION['user']['user_type'] == 6){ echo "Add User"; } else { echo "Add Admin"; } ?>
					</h3>
				</div>
				<div class="right">
					<h3>
						<a href="multipleUsers.php"><?php if($_SESSION['user']['user_type'] == 6){ echo "Multiple Users"; } else { echo "Multiple Admins"; } ?></a>
					</h3>
				</div>
				<div class="clear pB10"></div>					
			</div>
			<form method="post" action="" id="frmMultipleUser" name="frmMultipleUser">
			<!-- ALL DB DETAILS -->
			
				<div class="pT10">
					
					<table class="data-table">
						<tr>
							<th>Name*</th>
							<td><input type="text" name="uname" class="required" value="<?=$name;?>"/><br/>
							
							</td>
						</tr>
						
						<?php if($email == ''){ ?>
						<tr>
							<th>Email*</th>	
							<td><input type="text" class="email required" name="email" value="<?=$email;?>" /><br/>
							
							</td>
						</tr>
						<?php } ?>

						<tr>
							<th>Phone</th>	
							<td><input type="text" name="phone" id="mu_phone" value="<?=$phone;?>" class="" onchange="javascript: return chckphone('mu_phone');"/>&nbsp;&nbsp;<em>eg:123-123-1234</em></td>
						</tr>

						<tr>
							<th>Address</th>	
							<td><input type="text" name="address" value="<?=$address;?>" /></td>
						</tr>

						<tr>
							<th>Organisation</th>
							<td><input type="text" name="organisation" value="<?=$organisation;?>" /></td>
						</tr>

						<tr>
							<th>&nbsp;</th>
							<th>
							<?php if($email == ''){ ?>
							<input type="submit" name="save" Value="Save" />
							<?php } else { ?>
							<input type="submit" name="update" Value="update" />
							<input type="hidden" name="userid" Value="<?=$_GET['id']?>" />
							<?php } ?>
							</th>
						</tr>

					</table>
					
				</div>

				<br class="clear" />
			</div>
			</form>

		</div>
	</div>		
</section>
<!-- /container -->