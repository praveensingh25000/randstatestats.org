<?php if($pagename != 'index.php') {	
				
	$adminTop = new admin();
	
	if(isset($_GET['cat'])) { 
		$_SESSION['cat'] = $_GET['cat'];
	}
	
	if(isset($_GET['section'])) { 
		$section = $_SESSION['section'] = $_GET['section'];
	} else if(isset($_SESSION['section'])) { 
		$section = $_SESSION['section'];
	}

	if(isset($section) && $section == '1' ) {
		$featured	= $section; 
		$unfeatured	= 0;			
	} else if(isset($section) && $section== '0' ) {
		$featured	= $section; 
		$unfeatured	= 1;
	} else {
		$featured	= 1;
		$unfeatured	= 0;			
	}

	$allCatResultunf		= $adminTop->getAllFeaturedUnfeaturedCats($unfeatured);
	$totalCatsunfeatured	= $dbDatabase->count_rows($allCatResultunf);
	$allCatsunfeatured		= $dbDatabase->getAll($allCatResultunf);
	
	$allCatResult			= $adminTop->getAllFeaturedUnfeaturedCats($featured);
	$totalCats				= $dbDatabase->count_rows($allCatResult);
	$allCats				= $dbDatabase->getAll($allCatResult);	

	$catid					= $allCatsunfeatured['0']['id'];

	$pagesVar= $pagename!='userRegistration.php' && $pagename!='changePassword.php' && $pagename!='login.php' && $pagename!='profile.php' && $pagename!='plansubscriptions.php' && $pagename!='subscriptions.php' && $pagename!='topResults.php';
	?>

	<nav class="categorie-nav">
		<ul>
			<?php foreach($allCats as $keyCat => $categoryDetail){ ?>
			<li class="<?php if(count($allCats) == ($keyCat-1)) { echo "last"; } ?>">
				<a class="unselect_class <?php if(isset($_SESSION['cat']) && $_SESSION['cat'] == $categoryDetail['id'] && $pagesVar) { echo "active"; } ?>" id="select_class_<?php echo $categoryDetail['id']; ?>" href="<?php echo URL_SITE; ?>/forms.php?cat=<?php echo $categoryDetail['id']; ?>"><?php echo ucfirst(stripslashes($categoryDetail['category_title'])); ?></a>
			</li>
			<?php } ?>	

			<li class="moreSection">
			
				<a class="" href="<?php echo URL_SITE;?>/forms.php?cat=<?php echo $catid; ?>&section=<?php if(isset($unfeatured)) { echo $unfeatured;}?>">More >></a>				

				<div id="more_sections_data_div" class="allmenu">					
					<ul>
						<?php foreach($allCatsunfeatured as $keyCats => $categoryDetails){ ?>				
						<li>
							<a class="" id="" href="<?php echo URL_SITE; ?>/forms.php?cat=<?php echo $categoryDetails['id']; ?>&section=<?php if(isset($unfeatured)) { echo $unfeatured;}?>"><?php echo ucfirst(stripslashes($categoryDetails['category_title'])); ?></a>
						</li>			
						<?php } ?>
					</ul>				
				</div>

		    </li>
		</ul>
	</nav>
<?php } ?>