<div class="clear">&nbsp;</div>
	<footer>
       	<div id="mainshell">
 	       <div class="footernav">
            <ul>
            	<li class="first"><a href="http://www.rand.org/index.html">RAND Home</a></li>
            	<li><a href="http://www.rand.org/about.html">About RAND</a></li>
                <li><a href="http://www.rand.org/research_areas.html">Research Areas</a></li>
                <li><a href="http://www.rand.org/pubs.html">Books and Publications</a></li>
                <li><a href="http://www.rand.org/jobs.html">Opportunities</a></li>
            </ul>
        </div>
		<?php
		$adminTop = new admin();
		$allCatResult = $adminTop->getParentCategories();
		$totalCats = $dbDatabase->count_rows($allCatResult);
		$allCats = $dbDatabase->getAll($allCatResult);
		?>
        <div class="footernav">
            <ul>
				<li class="first"><a href="<?php echo URL_SITE; ?>/terms.php#Web%20Site%20Privacy%20Policy">Privacy Policy</a></li>
				<li><a href="<?php echo URL_SITE; ?>/terms.php">Terms and Conditions</a></li>
            </ul>
        </div>
        <div class="footernav">
            <ul>
            	<li class="first"><a href="#">Contact Info</a></li>
				<li><a href="<?php echo URL_SITE; ?>/contact_us.php">Contact Us</a></li>
            	<li><a href="#">randstatistics.org</a></li>
            </ul>
         </div>
       </div>
  </footer>
        



	</div>
	<!-- /Wrapper -->
</body>
</html>
