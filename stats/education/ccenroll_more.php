<?php
/******************************************
* @Modified on April 6, 2013
* @Package: RAND
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";
?>

 <!-- container -->
<section id="container">
	<section class="conatiner-full" id="inner-content">

		<h2>Community College Enrollment Race Definitions</h2><br/>

		<p>
			Beginning with 2009 data, the definition for race/ethnicity has been changed to comply with Integrated Post-secondary Education Data System (IPEDS) data reporting. The California Community Colleges Chancellor's Office collects ethnicity data according to <a href="http://www.cccco.edu/Portals/4/SB29.pdf" target="_blank">Form SB29</a> that counts multiple ethnicities per individual response where previous reporting included only one ethnicity per response. Based on SB29 data, <a href="http://www.cccco.edu/Portals/4/STD10.pdf" target="_blank">Format STD10</a> data are derived in accordance with IPEDS and are implemented here for ethnicity values.
		</p><br/>
		<p>
			<table border=1 class="collapse">
				<tr>
					<td>American Indian or Alaska Native</td>
					<td>A person having origins in any of the original peoples of North and South	America (including Central America), and who maintains a tribal affiliation or community attachment.</td>
				</tr>
				<tr>
					<td>Asian</td>
					<td>A person having origins in any of the original peoples of the Far East, Southeast Asia, or the Indian	subcontinent including, for example, Cambodia, China, India, Japan, Korea, Malaysia, Pakistan, the Philippine Islands, Thailand, and Vietnam.</td>
				</tr>
				<tr>
					<td>Black or African American</td>
					<td>A person having origins in any of the Black racial groups of Africa.</td>
				</tr>
				<tr>
					<td>Native Hawaiian or Other Pacific Islander</td>
					<td>A person having origins in any of the original peoples of Hawaii,	Guam, Samoa, or other Pacific Islands.</td>
				</tr>
				<tr>
					<td>White</td>
					<td>A person having origins in any of the original peoples of Europe, the Middle East, or North Africa. See1997 Standards, 62 FR 58789 (October 30, 1997).</td>
				</tr>
			</table>

	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>