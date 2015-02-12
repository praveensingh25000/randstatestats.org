<?php
/******************************************
* @Modified on Dec 06, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

ob_start();
session_start();

ini_set("display_errors","2");
ERROR_REPORTING(E_ALL);

$pagename = basename($_SERVER['PHP_SELF']);
require_once('connect.php');
require_once($DOC_ROOT.'classes/database.class.php');
require_once($DOC_ROOT.'classes/searchCriteriaClass.php');
require_once($DOC_ROOT.'classes/TempAdmin.php');
require_once($DOC_ROOT.'classes/admin.class.php');
require_once($DOC_ROOT.'classes/user.class.php');
require_once($DOC_ROOT.'classes/fish.class.php');
require_once($DOC_ROOT.'classes/mailer.class.php');
require_once($DOC_ROOT.'classes/categoryClass.php');
require_once($DOC_ROOT.'classes/pagination.class.php');
require_once($DOC_ROOT.'classes/ps_paginationArray.php');
require_once($DOC_ROOT.'classes/emailTemp.class.php');
require_once($DOC_ROOT.'include/functions.php');
require_once($DOC_ROOT.'include/mail_template.php');
require_once($DOC_ROOT.'include/ip_in_range.php');


$db    = new db(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DB);
$admin = new admin();
$user  = new user();

$generalSettings = fetchGenralSettings();
$current_ip      = getRealIpAddr();


if(!isset($_SESSION['generalSettings'])){	
	foreach($generalSettings as $key => $groups){
		foreach($groups as $key => $setting){
			$name = strtoupper($setting['name']);
			define($name,$setting['value']);		
		}
	}
}

require_once($DOC_ROOT.'include/db_connect.php');
require_once($DOC_ROOT.'include/ipconfig.php');

$mail_notification = MAIL_NOTIFICATION;

$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
if(!empty($siteMainDBDetail)) {
	$dbUserId = $siteMainDBDetail['id']; 
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php if(isset($database_label)) { echo $database_label; } ?></title>
	<link href="<?php echo URL_SITE; ?>/css/style.css" type="text/css" rel="stylesheet">
	<link href="<?php echo URL_SITE; ?>/css/popup.css" type="text/css" rel="stylesheet">
	<link href="<?php echo URL_SITE; ?>/css/ui.css" type="text/css" rel="stylesheet">
	<script src="<?php echo URL_SITE; ?>/js/html5.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo URL_SITE; ?>/css/dateinput.css">
	<script> var URL_SITE = '<?php echo URL_SITE; ?>'</script>
	<script src="<?php echo URL_SITE; ?>/print-preview/src/jquery.tools.min.js"></script>

	<!-- SHARING PAGE -->	
	<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/buttons.js"></script>
	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "79e7fce2-dfdd-41fe-96d0-2b632e09dc27", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

	<!-- /SHARING PAGE -->	

	<!-- Scripts &styles for Table Sorter -->
	<link rel="stylesheet" href="<?php echo URL_SITE;?>/themes/blue/style.css" type="text/css" media="print, projection, screen" />
	<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.tablesorter.js"></script>


	<script type="text/javascript">	
		var myTextExtraction = function(node)  
		{  
			// extract data from markup and return it  
			var textf =  node.innerHTML; 
			return textf.replace('$','').replace(/,/g,'');
		} 


		$(function() {
			$(".data-table").tablesorter({textExtraction: myTextExtraction});
		});
	</script>
	<!-- /Scripts for Table Sorter -->
	<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.validate.min.js"></script>	
	<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.validations.js"></script>

	<script type="text/javascript" src="<?php echo URL_SITE;?>/js/jquery.blockUI.js"></script>
	<script type="text/javascript" src="js/jwplayer.js" ></script>

	<!-- Script for print -->
	<script language="javascript" type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.printElement.min.js"></script>

	<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/js_actions.js"></script>

	<!-- js for pie line Bar Graph -->
	<!-- <script src="<?php echo URL_SITE; ?>/libraries/RGraph.common.core.js" ></script>
	<script src="<?php echo URL_SITE; ?>/libraries/RGraph.common.dynamic.js" ></script>    
	<script src="<?php echo URL_SITE; ?>/libraries/RGraph.common.tooltips.js" ></script>
	<script src="<?php echo URL_SITE; ?>/libraries/RGraph.common.key.js" ></script>	
	<script src="<?php echo URL_SITE; ?>/libraries/RGraph.line.js" ></script>
	<script src="<?php echo URL_SITE; ?>/libraries/RGraph.pie.js"></script>
	<script src="<?php echo URL_SITE; ?>/libraries/RGraph.bar.js"></script> -->
	<!-- /js for pie line Bar Graph -->

	<script type="text/javascript" src="<?php echo URL_SITE; ?>/libraries/jquery.tokeninput.js"></script> 
	<link rel="stylesheet" href="<?php echo URL_SITE; ?>/css/token-input.css" type="text/css" />

	<!-- <script src="js/jquery-1.8.2.min.js" type="text/javascript"></script> -->
	<script src="js/jquery.cycle.all.min.js" type="text/javascript"></script>

	<meta name="description" content="RAND State Statistics contains more than 200 databases and is made up of four sites, each with a specific geographic focus. RAND State Statistics contains more than 80 databases that cover all 50 U.S. states. RAND California contains these 80 databases plus about 100 detailed databases on California. RAND Texas contains the same 80 databases found on RAND State Statistics plus about 50 detailed databases on Texas. RAND New York contains the same 80 databases found on RAND State Statistics plus about 30 detailed databases on New York. For information on subscribing, see <a href='<?php echo URL_SITE; ?>/userRegistration.php'>user Registration</a>. RAND State Statistics is sponsored by the RAND Corporation, the nation's leading think tank.">

	
	<style type="text/css" media="print">
	@page
	{
		size: landscape;
		margin: 2cm;
	}
	</style>

</head>
<body>
	<!-- Wrapper -->
    <div id="wrapper">

		<!-- login form -->
		<?php  if(!isset($_SESSION['user'])){ require_once($DOC_ROOT.'login_popup.php'); } ?>		
		<!-- /popup form -->
		
    	<!-- header -->
        <!-- top navigation -->
        <div class="headertop">
				<div class="quicklinks">
                	<ul class="login-nav">
						    <?php
							if(isset($_SESSION['user']) && $_SESSION['user']['id']!='') { 
								
								$UserDetail	  =	$user->getUser($_SESSION['user']['id']);
								$is_trial     = $admin->selectdatabaseUsers($dbUserId,$UserDetail['id']);
								
								$brandname = ucwords($UserDetail['name']);

								if(isset($userDetail) && !empty($userDetail)){
									if(isset($userDetail['use_brand_name']) && $userDetail['use_brand_name']=='Y' && $userDetail['organisation']!=''){
										$brandname = $userDetail['organisation'];
									}
								}
								?>
								<li>
									<a class="" title="Logout" href="<?php echo URL_SITE; ?>/logout.php"><i class="icon-unlock"></i>Logout</a>
								</li>

								<li id="login-profile">
									<a href="<?php echo URL_SITE; ?>/profile.php?action=view">Hello<?php echo '&nbsp;'.ucwords($UserDetail['name']);?>
									</a>
									<script type="text/javascript"> jQuery("#login-profile").hover(function(){ jQuery(".notification").remove();});</script>
									
									<!-- hide - show -->
									<div class="login-main" id="login-main" style="display: none;">
										<div class="img-div">
											<a href="<?php echo URL_SITE; ?>/profile.php?action=view">
												<img title="<?php echo ucwords($UserDetail['name']);?>" alt="<?php echo ucwords($UserDetail['name']);?>" width="65px" height="65px" <?php if(!empty($UserDetail['image'])){ ?> src="<?php echo URL_SITE;?>/uploads/profiles/<?php echo $UserDetail['id']?>/<?php echo $UserDetail['image']?>" <?php } else { ?> src="<?php echo URL_SITE;?>/images/profile.png" <?php } ?> />
											</a>
										</div>
										<div class="right-side">
											<ul>
												<li><a href="<?php echo URL_SITE; ?>/profile.php?action=view">View profile</a></li>
												<li><a href="<?php echo URL_SITE; ?>/profile.php?action=edit">Edit profile</a></li>
												<?php 
													/*$typeDetail = $admin->getUserType($_SESSION['user']['user_type']);
													if($typeDetail['user_type']!='Single user' && $typeDetail['user_type']!='Multiple users'){?><li><a href="<?php echo URL_SITE; ?>/loginUsage.php">Usage Statistics</a></li>
												<?php }*/ ?>
												<?php 
													if($_SESSION['user']['user_type']!='5' && $_SESSION['user']['user_type']!='6'){?><li><a href="<?php echo URL_SITE; ?>/loginUsage.php">Usage Statistics</a></li>
												<?php } ?>

												<?php if($_SESSION['user']['parent_user_id'] == 0){ ?>
												<li><a href="<?php echo URL_SITE; ?>/account.php">My account</a></li>
												<?php } ?>

												<?php										
												$user  = new user();
												if(isset($_SESSION['user']['email']) && $_SESSION['user']['email'] !='') {
													$user_email	=	$_SESSION['user']['email'];
												} else {
													$user_email	=	$_SESSION['user']['username'];
												}

												$userDetail		=	$user->selectUserProfile($user_email);	
												
												$validity_on	=	$admin->Validity($userDetail['id'],$user_email);
												
												if((isset($validity_on) && $validity_on <= '7') && $_SESSION['user']['parent_user_id'] == 0) { ?>
													<li>
														<a href="<?php URL_SITE;?>/accountUpgrade.php">Account validity&nbsp;(<?php echo $validity_on;?>&nbsp;days)</a>
													</li>
												<?php } ?>			
											</ul>
										</div>
									</div>
									<script>
										$("#login-profile").hover(function(){
										$('#login-main').show();
									},function(){
										$('#login-main').hide();
									});
									</script>
									<!-- /hide - show -->
							</li>

							<?php if(!empty($is_trial) & $is_trial['is_trial']=='0'){?>
							<li>
								<a href="<?php echo URL_SITE; ?>/plans.php">Subscription Plans</a>
							</li>						

							<li>
								<a href="<?php echo URL_SITE; ?>/accountUpgrade.php">Subscribe</a>
							</li>
							<?php } ?>

						<?php } else if(isset($checksubmitType) && $checksubmitType == 'true'){ 
								
							$brandname = "Subscriber";
							if(isset($userDetail) && !empty($userDetail)){
								if(isset($userDetail['use_brand_name']) && $userDetail['use_brand_name']=='Y' && $userDetail['organisation']!=''){
									$brandname = $userDetail['organisation'];
								}
							}
							?>
							<li><a class="" title="Welcome" href="javascript:;">Welcome <?=$brandname?></a></li>
							<li><a class="login login-window" href="#login-box"><i class="icon-lock"></i>Login as Admin</a></li>
							
						<?php } else { ?>
							<li><a class="login login-window" href="#login-box"><i class="icon-lock"></i>Login</a></li>
							<li><a href="<?php echo URL_SITE; ?>/signup.php">Subscribe</a></li>
							<li><a href="<?php echo URL_SITE; ?>/plans.php">Subscription Plans</a></li>
						<?php } ?>
                    </ul>

                    <ul class="search-top">
                    	<li class="mT5">
							<a href="<?php echo URL_SITE; ?>/statistics.php">Summary of Databases</a>
						</li>

						<li>
                        	<form action="<?php echo URL_SITE; ?>/topResults.php" name="front_searchForm" id="front_searchForm">
								<input placeholder="keyword" class="seacrh-input required" type="text" for="search" name="search" value="" />
							</form>
							
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /top navigation -->

			<?php if((isset($_SESSION['msgsuccess']) && $_SESSION['msgsuccess']!='') || (isset($_SESSION['msgerror']) && $_SESSION['msgerror']!='') || (isset($_SESSION['msginfo']) && $_SESSION['msginfo']!='') || (isset($_SESSION['msgalert']) && $_SESSION['msgalert']!='')) { ?>

				<?php
				if(isset($_SESSION['msginfo']) && $_SESSION['msginfo']!=''){
				?>
				<div class="notification msginfo">
					<a class="close"></a>
					<p><?php echo $session_message[$_SESSION['msginfo']]; ?></p>
				</div><!-- notification msginfo -->		
				<?php unset($_SESSION['msginfo']); } ?>
				
				<?php
				if(isset($_SESSION['msgsuccess']) && $_SESSION['msgsuccess']!=''){
				?>
				<div class="notification msgsuccess">
					<a class="close"></a>
					<p><?php echo $session_message[$_SESSION['msgsuccess']]; ?></p>
				</div><!-- notification msgsuccess -->
				<?php unset($_SESSION['msgsuccess']); } ?>
				
				<?php
				if(isset($_SESSION['msgalert']) && $_SESSION['msgalert']!=''){
				?>
				<div class="notification msgalert">
					<a class="close"></a>
					<p><?php echo $session_message[$_SESSION['msgalert']]; ?></p>
				</div><!-- notification msgalert -->
				<?php unset($_SESSION['msgalert']); } ?>
				
				<?php
				if(isset($_SESSION['msgerror']) && $_SESSION['msgerror']!=''){
				?>
				<div class="notification msgerror">
					<a class="close"></a>
					<p><?php echo $session_message[$_SESSION['msgerror']]; ?></p>
				</div><!-- notification msgerror -->
				<?php unset($_SESSION['msgerror']); } ?>
				<br class="clear" /><br class="clear" />
			<?php } ?>

			<?php if((isset($_SESSION['successmsg']) && $_SESSION['successmsg']!='') || (isset($_SESSION['errormsg']) && $_SESSION['errormsg']!='') || (isset($_SESSION['infomsg']) && $_SESSION['infomsg']!='') || (isset($_SESSION['alertmsg']) && $_SESSION['alertmsg']!='')) { ?>

				<?php
				if(isset($_SESSION['infomsg']) && $_SESSION['infomsg']!=''){
				?>
				<div class="notification msginfo">
					<a class="close"></a>
					<p><?php echo $_SESSION['infomsg']; ?></p>
				</div><!-- notification msginfo -->		
				<?php unset($_SESSION['infomsg']); } ?>
				
				<?php
				if(isset($_SESSION['successmsg']) && $_SESSION['successmsg']!=''){
				?>
				<div class="notification msgsuccess">
					<a class="close"></a>
					<p><?php echo $_SESSION['successmsg']; ?></p>
				</div><!-- notification msgsuccess -->
				<?php unset($_SESSION['successmsg']); } ?>
				
				<?php
				if(isset($_SESSION['alertmsg']) && $_SESSION['alertmsg']!=''){
				?>
				<div class="notification alertmsg">
					<a class="close"></a>
					<p><?php echo $_SESSION['alertmsg']; ?></p>
				</div><!-- notification msgalert -->
				<?php unset($_SESSION['alertmsg']); } ?>
				
				<?php
				if(isset($_SESSION['errormsg']) && $_SESSION['errormsg']!=''){
				?>
				<div class="notification msgerror">
					<a class="close"></a>
					<p><?php echo $_SESSION['errormsg']; ?></p>
				</div><!-- notification msgerror -->
				<?php unset($_SESSION['errormsg']); } ?>
				<br class="clear" /><br class="clear" />
			<?php } ?>

      	   <!-- Main div -->

		   <!-- header -->
		   <header>    	
				<?php 
				if($pagename == 'index.php'){ 
					$idMain = "mainshell";
				} else {
					$idMain = "inner-mainshell";
				}					
				?>

				<div id="<?php echo $idMain; ?>">

					<!-- header left -->
					<section class="headerleft">
						<!-- logo -->
						<div class="logo">
							<h3><a href="<?php echo URL_SITE; ?>/index.php">RAND.com</a></h3>
						</div>
						<!-- /logo -->
						<h2>
							State Statistics <br> <span>A service of the rand corporation</span>
						</h2>
					</section>
					<!-- /header left -->

					<!-- header right -->
					<section class="headerright">
						<nav>
							<ul>
								<li><a href="<?php echo URL_SITE; ?>/index.php">Home</a></li>
								<li><a href="<?php echo URL_SITE; ?>/about.php">About RAND State Statistics</a></li>
								<li><a href="<?php echo URL_SITE; ?>/contact_us.php" class="last">Contact Us</a></li>
							</ul>
						</nav>
						<div class="clear pT15"></div>

						<?php
						if(isset($_SESSION['legalDatabaseUser']) && !empty($_SESSION['legalDatabaseUser'])){
							$database_purchased_array_unique=array_unique($_SESSION['legalDatabaseUser']);
							$database_purchased_str=implode(',',$database_purchased_array_unique);
							$allMainDatabases = $admin->getMainDatabasesPurched($database_purchased_str,'Y');
						} else {
							$allMainDatabases = $admin->getMainDatabases('Y');
						}						
				
						if(count($allMainDatabases)>0){
						?>
						<nav class="wdthpercent100">
							<ul class="right">
								<li class="brws">Browse:</li>
								<?php foreach($allMainDatabases as $rowMain => $mainDetail){ ?>
								<li>
									<a title="<?php echo strtoupper($mainDetail['database_label']);?>" <?php if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse'] == $mainDetail['databasename']){ ?> class="activedb" <?php } ?> href="?db=<?php echo $mainDetail['databasename']; ?>"><?php echo $mainDetail['db_code']; ?></a>
								</li>
								<?php } ?>
							</ul>
						</nav>
						<?php } ?>
					</section>
					<!-- /header right -->
				</div>
				<!-- /Main div -->

				<!-- Navigation Page -->
				<?php require_once($DOC_ROOT.'navigation.php'); ?>
				<!-- /Navigation Page -->		

			</header>
			<!-- /header -->