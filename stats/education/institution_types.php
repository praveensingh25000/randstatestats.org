<?php
/******************************************
* @Modified on June 25, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";
?>

 <!-- container -->
<section id="container">

	<section class="conatiner-full" id="inner-content">

		<h2>Institution Types </h2><br/>

		<p>NCES has changed the types of institution reported over this time series.  From 1995-1998, NCES reported two types of research or doctoral universities:</p>
		<ul>
			<li>Research universities: Committed to graduate education through the doctorate, give high priority to research, and receive more than $15.5 million in annual federal funds research.</li>
			<li>Doctoral universities: Offer a full range of baccalaureate programs and are committed to education through the doctorate.  They award at least 40 doctoral degrees annually in five or more disciplines.</li>
		</ul>
		
		<p>From 2000-2006, NCES reported two types of research or doctoral universities:</p>
		<ul>
			<li>Doctoral, extensive universities: Committed to graduate education through the doctorate, and award 50 or more doctor's degrees per year across at least 15 disciplines.</li>
			<li>Doctoral, intensive universities: Committed to education through the doctorate and award at least 10 doctor's degrees per year across 3 or more disciplines or at least 20 doctor's degrees overall.</li>
		</ul>

		<p>In 2007, NCES reported three research or doctoral categories:</p>
		<ul>
			<li>Research universities (very high level): Research universities with a very high level of research activity.</li>
			<li>Research universities (high level): Research universities with a high level of research activity.</li>
			<li>Doctoral, research universities: Institutions that award at least 20 doctor's degrees per year, but did not have a high level of research activity.</li>
		</ul>

		<p>In addition to the research or doctoral categories, NCES provides these definitions for the remaining categories shown:</p>
		<ul>
			<li>Masters: Award at least 50 master's degrees per year.</li>
			<li>Baccalaureate: Primarily emphasize undergraduate education.</li>
			<li>Special-focus: Award degrees primarily in single fields of study, such as medicine, business, fine arts, theology, and engineering. This final category includes some institutions that have 4-year programs, but have not reported sufficient data to identify program category. It also includes institutions classified as 4-year under the IPEDS system, which had been classified as 2-year in the Carnegie classification system because they primarily award associate's degrees.</li>
		</ul>

		<p>Because research and doctoral category definitions have changed over time, this database maintains the original NCES definitions.  Therefore, data are not reported in all years:</p>
		<ul>
			<li>The categories Research university and Doctoral university are reported only for 1995-1998</li>
			<li>The categories Doctoral, extensive universities and Doctoral, intensive universities are reported only for 2000-2006. (1999 data are not available.)</li>
			<li>The categories Research universities (very high level), Research universities (high level), and Doctoral, research universities are reported only for 2007-present.</li>
		</ul>

		<p>In each year, however, the sum total of research and doctoral universities are reported. </p>


	

	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>