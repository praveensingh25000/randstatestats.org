<?php
/******************************************
* @Modified on Dec 26, 2012,Jan 24 2013,April 08 2013
* @Package: Rand
* @Developer: Saket Bisht,Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

$admin = new admin();
$user = new user();

$users_default = $users_all = array();

if(isset($_GET['newid']) && $_GET['newid']!='') {
	$newsid		=	base64_decode($_GET['newid']);
	$newsDetail =	$admin->getNews($newsid);
}

if(isset($_GET['view']) && $_GET['view']!='') {
	$tablename			= 'dummy_users';
	$users_res			= $user->showAllUsersRamdomly($tablename,$all=1);
	$users_default_all  = $db->getAll($users_res);

	$users_obj	 =	new PS_PaginationArray($users_default_all,20,5,'view=all');
	$users_all	 =	$users_obj->paginate();
	$total_all_users =	count($users_all);
}

if(isset($_GET['verification']) && $_GET['verification']!='') {

	$email_v			=	base64_decode($_GET['verification']);
	$not_a_used_link	=	$user->check_verification_of_account($email_v);
	
	if($not_a_used_link) {
		$_SESSION['msgsuccess'] = '3';
		header('location: index.php');
	} else {
		$_SESSION['msgalert'] = '4';
		header('location: index.php');
	}
}

$allCats   = $admin->getorderedParentCategory();

if(isset($_GET['changeRegions']) && $_GET['changeRegions']!='') {
	$_SESSION['categoryid'] = $_GET['changeRegions'];
	$_SESSION['cat'] = $_GET['changeRegions'];		
	header('location: '.URL_SITE.'/changeRegions.php');
}

$newsresult_res = $admin->showAllNews();
$total = $db->count_rows($newsresult_res);
$news_default = $db->getAll($newsresult_res);

//selecting all users
//$tablename = 'users';
//$users_res = $user->showAllUsers();
$tablename = 'dummy_users';
$users_res = $user->showAllUsersRamdomly($tablename);
$total_users = $db->count_rows($users_res);
$users_default = $db->getAll($users_res);
?>

<script type="text/javascript">
$(document).ready(function() {
    $('#slideshow').cycle({
		fx: 'fade' 
	});
});
</script>

<!-- Container -->
<section id="container">
	<div id="bannermain">
		<div id="mainshell">

			<!-- banner left -->
			<aside class="banner-left" id="slideshow">				
				<img src="images/mapUS.png" />
				<img src="images/line_graph.png" />				
			</aside>
			<!-- /banner left -->

			<!-- banner right -->
			<aside class="banner-right">
				<font color="black"><?php echo  stripslashes(HOME_PAGE_CONTENT);?></font>
				<?php if(!isset($_SESSION['user']) && (isset($checksubmitType) && $checksubmitType == 'false')) { ?>
				<div class="clear pB20"></div>
				<div class="trail-btn-main">
					<a class="trial-btn" href="<?php echo URL_SITE; ?>/userRegistration.php"> Click to subscribe or for a free trial </a>
				 </div>
				 <?php } ?>
			</aside>
			<!-- /banner right -->
		</div>
	</div>
	<div class="clear pT30"></div>

	<div id="mainshell">
		<section id="content">
			<!-- About -->
			<?php 
			if(!empty($newsDetail)) { ?>
				<div class="about">
					<h2> News Detail Section </h2>
					<br class="clear" />
					<h3><?php echo stripslashes($newsDetail['news_title']);?></h3>
					<h4>Added On: <?php echo date('d F Y',strtotime($newsDetail['date_added']));?></h4>
					<br class="clear" />
					<?php echo stripslashes($newsDetail['description']);?>
			   </div>
			<?php }	?>
		   <!-- /About -->

		   <!-- All Users -->
			<?php 
			if(isset($total_all_users) && $total_all_users > 0) { ?>
				<div class="news">
					<h2>Our Subscribers</h2>				
					<ul>
						<?php foreach($users_all as $key => $user) { ?>
							<li>
								<p class=""><?php echo ucwords($user['name']);?></p>
							</li>
						<?php } ?>
							
						<!-- Pagination ----------->                      
						<div class="pT5 txtcenter pagination">
							<?php echo $users_obj->renderPrev();?>&nbsp;&nbsp;&nbsp;<?php echo $users_obj->renderNext();  ?>
						</div>
						<!-- /Pagination ----------->

					</ul>					
				</div>
			<?php }	?>
		   <!-- All Users -->
		   
		   <!-- Browse -->
		   <div class="browse additional">
				<h2>Available Sections</h2><br>
				<ul>
					<?php foreach($allCats as $keyCatAll => $categoryDetailAll){ ?>
						<?php foreach($categoryDetailAll as $keyCat => $categoryDetail){ ?>
							<li>
								<div id="click_<?php echo $keyCat;?> add" class="add-icon">
									<a title="Click to expand Categoty Detail" class="plus category-plus" id="toggleicon_<?php echo $keyCat;?>" href="javascript:;"></a>
									<a id="redirect_<?php echo $keyCat;?>" onclick="javascript:window.location=URL_SITE+'/index.php?changeRegions=<?php echo $categoryDetail['id'];?>'" href="javascript:;"><?php echo ucfirst(stripslashes($categoryDetail['category_title'])); ?></a>
								</div>	
								
								<div id="eco-detail_<?php echo $keyCat;?>" class="category" style="display:none">
									<p><?php echo stripslashes($categoryDetail['description']); ?></p>
								</div>	
								
								<script type="text/javascript">
									$(document).ready(function(){
										$("#toggleicon_<?php echo $keyCat;?>").click(function(){
											$("#eco-detail_<?php echo $keyCat;?>").toggle('slow');
											$("#eco-detail_<?php echo $keyCat;?>").toggleClass('close');
											
											if($("#eco-detail_<?php echo $keyCat;?>").hasClass('close')){
												$("#toggleicon_<?php echo $keyCat;?>").addClass('minus category-minus');
												$("#toggleicon_<?php echo $keyCat;?>").removeClass('plus category-plus');
											} else {
												$("#toggleicon_<?php echo $keyCat;?>").addClass('plus category-plus');
												$("#toggleicon_<?php echo $keyCat;?>").removeClass('minus category-minus');
											}
										});
									});
								</script>

							</li>
							<br class="clear" />
						<?php } ?>				
					<?php } ?>
				</ul>
		   </div>
		   <!-- /Browse -->
		   
		</section>

		<section id="sidebar">

			<div class="video-div" id="video-div"> 
				<h2>Video</h2>
				<img src="images/Video_icon.png" /> 
			</div>
			
			<div class="login-popup videoplaydiv" style="display: none; margin-top: -180.5px; margin-left: -319px;">				
				<a class="closevideo" href="javascript;"><img alt="Close" title="Close Window" class="btn_close_video btn_close" src="<?php URL_SITE;?>/images/close_pop.png"></a>

				<div id="video_play_div">
					<script type="text/javascript">				
					jwplayer("video_play_div").setup({ file: URL_SITE+"/uploads/using_rss.flv" });
					</script>	
				</div>
				
				<SCRIPT LANGUAGE="JavaScript">
				$( '.closevideo' ).click( function( event ) {
					jwplayer( 'video_play_div' ).stop();
				});
				</SCRIPT>
			</div>

			<div class="news">
				<h2>News & Updates</h2>
				<ul>
					<?php if($total> 0) { ?>
						<?php foreach($news_default as $key => $newsDetail) { ?>
							<?php if($newsDetail['is_active'] == 'Y') { ?>
								<li>
									<a href="index.php?newid=<?php echo base64_encode($newsDetail['id']);?>"><?php echo stripslashes($newsDetail['news_title']);?></a>
								</li>
							<?php } ?>
						<?php } ?>		
					<?php }	?>		
				</ul>
			</div>		

			<?php if(!empty($users_default)) { 
			$users_default_out=array_slice($users_default,0,10);			
			?>
				<!-- <div class="user">
					<h2>Our Users</h2>				
					<ul>
						<?php foreach($users_default_out as $key => $users) { ?>
							<li>
								<img title="<?php echo ucwords($users['name']);?>" alt="<?php echo ucwords($users['name']);?>" width="116px" height="116px" <?php if(!empty($users['image'])){ ?> src="<?php echo URL_SITE;?>/uploads/profiles/<?php echo $users['id']?>/<?php echo $users['image']?>" <?php } else { ?> src="<?php echo $URL_SITE;?>/images/profile.png" <?php } ?> />								
							</li>
						<?php } ?>				
					</ul>
					<div class="pT10 right pR10">View all</div>
				</div> -->

				<div class="news">
					<h2>Our Subscribers</h2>				
					<ul>
						<?php foreach($users_default_out as $key => $users) { ?>
							<li>
								<p class=""><?php echo ucwords($users['name']);?></p>
							</li>
						<?php } ?>
						<?php if(!empty($users_default) && count($users_default) > 10) { ?>
							<p><a class="right pR50" href="index.php?view=all">View all</a></p>
						<?php } ?>												
					</ul>					
				</div>

			<?php } ?>

		</section>
	   <div class="clear"></div>

	</div>
</section>
<!-- /Container -->
<div class="clear"></div>

<?php include_once $basedir."/include/footerHtml.php"; ?>