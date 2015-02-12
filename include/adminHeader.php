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

include_once('connect.php');
require_once($DOC_ROOT.'include/functions.php');
require_once($DOC_ROOT.'include/mail_template.php');
require_once($DOC_ROOT.'classes/database.class.php');
require_once($DOC_ROOT.'classes/searchCriteriaClass.php');
require_once($DOC_ROOT.'classes/TempAdmin.php');
require_once($DOC_ROOT.'classes/admin.class.php');
require_once($DOC_ROOT.'classes/user.class.php');
require_once($DOC_ROOT.'classes/categoryClass.php');
require_once($DOC_ROOT.'classes/pagination.class.php');
require_once($DOC_ROOT.'classes/cms_pages.class.php');
require_once($DOC_ROOT.'classes/mailer.class.php');
require_once($DOC_ROOT.'classes/ps_paginationArray.php');

$db = new db(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DB);

$generalSettings = fetchGenralSettings();
if(!isset($_SESSION['generalSettings']))
{	
	foreach($generalSettings as $key => $groups)
	{
		foreach($groups as $key => $setting)
		{
			$name = strtoupper($setting['name']);
			define($name,$setting['value']);		
		}
	}
}

if(isset($_GET['db']) && $_GET['db']!='') {

	if(isset($_SESSION['databaseToBeUse'])) { unset($_SESSION['databaseToBeUse']);}
	if(isset($_SESSION['categoryid'])) { unset($_SESSION['categoryid']);}
	
	$_SESSION['databaseToBeUse'] = $_GET['db'];

	$dbArray		=	explode('_',$_SESSION['databaseToBeUse']);
	$db_nameadmin	=	'<b>'.strtoupper($dbArray[1]).'</b>';

	$_SESSION['infomsg'] = "You have selected ".$db_nameadmin." database.";

	if(isset($_SESSION['admin'])) {
		if($_SERVER['PHP_SELF'] != $_SERVER['HTTP_REFERER']){
			header('location: '.$_SERVER['HTTP_REFERER'].'');
		} else {
			header('location: databases.php');
		}
		exit;
	} else {		
		header('location: index.php');
		exit;
	}

} else if(isset($_POST['databaseToBeUse']) && $_POST['databaseToBeUse']!='') {

	$_SESSION['databaseToBeUse'] = trim($_POST['databaseToBeUse']);
	header('location: databases.php');

} else if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!=''){

	$_SESSION['databaseToBeUse'] = trim($_SESSION['databaseToBeUse']);

} else {

	$_SESSION['databaseToBeUse'] = 'rand_texas';
}

$dbDatabase = $dbtaxas =  new db(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, $_SESSION['databaseToBeUse']);

$googleKey = 'AIzaSyAgmdNJbGuHyDDQYns0RZxwVb1o8VBtADU';

$mail_notification = MAIL_NOTIFICATION;
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>RAND State Statistics -- U.S. Construction Permits for New Privately-Owned Construction</title>
	<link href="<?php echo URL_SITE; ?>/css/style.css" type="text/css" rel="stylesheet">
	<link href="<?php echo URL_SITE; ?>/css/popup.css" type="text/css" rel="stylesheet">
	<link href="<?php echo URL_SITE; ?>/css/jquery.ui-1.9.2.css" type="text/css" rel="stylesheet">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="<?php echo URL_SITE; ?>/js/html5.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo URL_SITE; ?>/css/demo.css" type="text/css" media="screen" />
	<script> var URL_SITE = '<?php echo URL_SITE; ?>'</script>
	<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.validations.js"></script>
	<script type="text/javascript" src="<?php echo URL_SITE;?>/js/jquery.blockUI.js"></script>
	<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/js_actions.js"></script>
	<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.tablednd.js"></script>

	<script type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery-ui-1.9.2.js"></script>

	<script type="text/javascript" src="<?php echo URL_SITE;?>/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>

    <script type="text/javascript" src="<?php echo URL_SITE; ?>/libraries/jquery.tokeninput.js"></script> 
	<link rel="stylesheet" href="<?php echo URL_SITE; ?>/css/token-input.css" type="text/css" />
    <!--[if lt IE 9]><script src="../excanvas/excanvas.js"></script><![endif]-->
    
	<meta name="description" content="RAND State Statistics contains more than 200 databases and is made up of four sites, each with a specific geographic focus. RAND State Statistics contains more than 80 databases that cover all 50 U.S. states. RAND California contains these 80 databases plus about 100 detailed databases on California. RAND Texas contains the same 80 databases found on RAND State Statistics plus about 50 detailed databases on Texas. RAND New York contains the same 80 databases found on RAND State Statistics plus about 30 detailed databases on New York. For information on subscribing, see <a href='<?php echo URL_SITE; ?>/userRegistration.php'>user Registration</a>. RAND State Statistics is sponsored by the RAND Corporation, the nation's leading think tank.">

</head>
<body class="admin">
	<!-- Wrapper -->
    <div id="wrapper">

		<!-- login form -->
		<?php  //if(!isset($_SESSION['user'])){ //require_once($DOC_ROOT.'login_popup.php'); } ?>		
		<!-- /popup form -->
		
    	<!-- header -->
        <!-- top navigation -->
        <div class="headertop">
            	<div class="quicklinks">
                	<ul class="login-nav">
						<?php if(isset($_SESSION['admin'])){ ?>							
							<li><a href="logout.php" title="Logout" class=""><i class="icon-unlock"></i>Logout</a></li>
							<li><a target="_blank" href="<?php echo $URL_SITE;?>" title="Access Site">Access Site</a></li>
						<?php } else { ?>
							<li><a class="login" href="index.php"><i class="icon-lock"></i>Login</a></li>
						<?php } ?>

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
					<p><?php echo $_SESSION['msginfo']; ?></p>
				</div><!-- notification msginfo -->		
				<?php unset($_SESSION['msginfo']); } ?>
				
				<?php
				if(isset($_SESSION['msgsuccess']) && $_SESSION['msgsuccess']!=''){
				?>
				<div class="notification msgsuccess">
					<a class="close"></a>
					<p><?php echo $_SESSION['msgsuccess']; ?></p>
				</div><!-- notification msgsuccess -->
				<?php unset($_SESSION['msgsuccess']); } ?>
				
				<?php
				if(isset($_SESSION['msgalert']) && $_SESSION['msgalert']!=''){
				?>
				<div class="notification msgalert">
					<a class="close"></a>
					<p><?php echo $_SESSION['msgalert']; ?></p>
				</div><!-- notification msgalert -->
				<?php unset($_SESSION['msgalert']); } ?>
				
				<?php
				if(isset($_SESSION['msgerror']) && $_SESSION['msgerror']!=''){
				?>
				<div class="notification msgerror">
					<a class="close"></a>
					<p><?php echo $_SESSION['msgerror']; ?></p>
				</div><!-- notification msgerror -->
				<?php unset($_SESSION['msgerror']); } ?>
				<br class="clear" />
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
				<br class="clear" />
			<?php } ?>

      	    <header>
        	<!-- Main div -->

			<?php 
		
			$idMain = "inner-mainshell";
	
			?>
    		<div id="<?php echo $idMain; ?>">
            	<!-- header left -->
                <section class="headerleft">
                	<!-- logo -->
                    <div class="logo">
                        <h3><a href="<?php echo $URL_SITE;?>/admin/databases.php">RAND.com</a></h3>
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
                        <!-- 	<li><a href="<?php echo URL_SITE; ?>/index.php">Home</a></li>
                            <li><a href="<?php echo URL_SITE; ?>/about.php">About RAND State Statistics</a></li>
                            <li><a href="<?php echo URL_SITE; ?>/contact_us.php" class="last">Contact Us</a></li>
                        </ul> -->
                    </nav>
					<div class="clear pT15"></div>

					<?php
					$admin = new admin();
					$allMainDatabases = $admin->getMainDatabases('Y');
			
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
		
			<nav class="categorie-nav categorie-navAdmin">
				<hr>
			</nav>
			
			</header>
			<!-- /header -->


