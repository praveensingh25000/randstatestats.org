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

		<h2>RAND California School District Financial and Demographic Statistics Definitions</h2><br/>

			<h3 class="bblue">Number of dropouts from grades 9-12:</h3>
			<p>
			Reflects those students considered "dropouts" by the California Department of Education.<br>
			A dropout is a previously enrolled student, who pursued a high school diploma, or equivalent, in the past,
			but did not receive one.  The student has been absent for more than 45 consecutive days,
			is under the age of 21, and not re-enrolled in school or another institution. </br>
			<p>
			<br/>
			<u>Example</u>:<br>
			XYZ High School<br>
			Number of dropout from grades 9-12 = 9 students
			</p><br/>

			<h3 class="bblue">Grades 9-12 aggregated dropout rate:</h3>
			<p>
			The total number of dropouts from grades 9-12 are divided by the total enrollment of the school, or school district, then multiplied by 100.<br>
			No distinction is made between the grade levels. </p>
			<p>
			<br/>
			<u>Example</u>:<br>
			XYZ High School<br>
			(9 dropouts/900 total enrollment)*100 = 10%
			</p><br/>
					 
			<h3 class="bblue">Grades 9-12 multiplicative dropout rate:</h3>
			<p>
			This rate looks at the number of dropouts in relation to grade level.<br>
			(1-((1-(dropouts grade 9/enrollment grade 9))*(1-(dropouts grade10/enrollment grade 10))*<br>
			(1-(dropouts grade 11/enrollment grade 11))*(1-(dropouts grade 12/enrollment grade 12))) * 100
			</p>

			<p>
			<br/>
			<u>Example</u>:<br>
			XYZ High School<br>
			(1-((1-(2/275))*(1-(2/240))*(1-(2/205)))*(1-(3/180)))*100 = 4%
			</p>
		
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>