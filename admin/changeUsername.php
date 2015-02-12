<?php
/******************************************
* @Modified on Apr 02, 2013
* @Package: Rand
* @Developer: Sandeep kumar
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";
checkSession(true);
$admin = new admin();
$adminDetails = $admin->adminDetails($_SESSION['admin']['id']);
if(isset($_POST['save'])){	

		$id = $_SESSION['admin']['id'];
		
		$status = $admin->changeUsername($_POST['username'],$id);
		if($status){

		$receivermail	=	FROM_EMAIL;
		$receivename	=	FROM_NAME;
		$fromname		=	FROM_NAME;;
		$fromemail		=	FROM_EMAIL;;
		
		$mailbody		=	'Hi '.$receivename.', <br /><p>Username for admin login has updated and new username  is '.$_POST['username'].'. Please click on below link to login </p><p><a href="'.URL_SITE.'/admin/index.php">'.URL_SITE.'</a> </p>		
		<p>Thank you </p>
		<p>Rand Team. </p>';

		$subject='Change Username Mail!';	
		$send_mail= mail_function1($receivename,$receivermail,$fromname,$fromemail,$mailbody,$subject, $attachments = array(),$addcc=array());
		$_SESSION['msgsuccess'] = 'Admin Username has been changed successfully.';
		header("location:home.php");
		exit();
		}
	}
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
			
			<div style="clear: both; padding: 15px 0;">
				<fieldset style="border: 1px solid #cccccc; padding: 10px;">
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Change Username </legend>
					<br class="clear" />

					<form method="post" action=''  id="frmchangeusername" name="frmchangeusername">

						<p>Username*</p>
						<div style="padding: 10px 0;">
							<input value="<?php echo $adminDetails['username']; ?>"; type="text" id="username" name="username" class="required"/>
						</div>			

						<div class="submit1 submitbtn-div">
							<label for="submit" class="left">
								<input type="submit" value="Update" name="save" class="submitbtn" >
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