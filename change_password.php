<?php
/******************************************
* @Modified on Dec 06, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

checkSession(false);

if(isset($_POST['change_password'])){
	
	$new_password = $_POST['new_password'];
	$user_id=$_SESSION['user']['id'];

	$user = new user();
	$userDetail = $user->UpdateUserPassword($user_id,$new_password);	
	$_SESSION['msgsuccess'] = '14';
	header('location: index.php');
	exit;
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
				<h3 class="">CHANGE PASSWORD</h3>
					<form name="user_change_password" action="" method="post" id="user_change_password">
						<table class="table" cellpadding="5">
								<tr>
									<td>Old Password :</td>
									<td><input id="old_password" placeholder="Old Password" name="old_password" type="password" class="required"/></td>
								</tr>
								<tr>
									<td>New Password :</td>
									<td><input id="new_password" placeholder="New Password" name="new_password" type="password" class="required"></td>
								</tr>
								<tr>
									<td>Confirm Password :</td>
									<td><input id="confirm_password" placeholder="Confirm Password" name="confirm_password" type="password" class="required"></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>
										<input class="submitbtn" name="change_password" type="submit"  value="Submit">&nbsp;&nbsp;
										<input class="submitbtn" type="button" onclick="javascript:history.go(-1)" value="cancel" class="">
									</td>
								</tr>
							</table>
						</form>
				</fieldset>				
			</div>
		</div>
		<!-- left side -->
		<!-- right side -->
		<aside class="containerR">
			
		</aside>
		<!-- /right side -->
	</div>
</section>
<!-- /container -->
<?php 
include($basedir.'/include/footerHtml.php')
//end
?>