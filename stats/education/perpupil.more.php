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

		<h2>Per Pupil Spending Background</h2><br/>

			<p>
			Per pupil spending statistics reflects the current expense of education and average daily attendance (ADA) in California school districts as defined by the <a href="http://www.cde.ca.gov" target="_blank">California Department of Education</a>.
			</p><br/>

			<p>The current expense of education includes the following Department of Education account codes and descriptions:</p><br/>

			<table cellspacing="6" border="1" class="data-table">
				<tr>
					<th height=18 bgcolor=#eeeeee><span class="blue">Account Code</th>
					<th height=18 bgcolor=#eeeeee><span class="blue">Description</th>
					<th height=18 bgcolor=#eeeeee><span class="blue">Includes</th>
				</tr>

				<tr>
					<td>1000</td>
					<td>Certified salaries</td>
					<td>Teacher, librarian, administrator, and other salaries</td>
				</tr>

				<tr>
					<td>2000</td>
					<td>Classified salaries</td>
					<td>Teacher aide, maintenance, clerical, and other salaries</td>
				</tr>

				<tr>
					<td>3000</td>
					<td>Employee benefits</td>
					<td>Health, workers compensation, and other benefits (excludes retirement and fringe benefits for retired persons)</td>
				</tr>

				<tr>
					<td>4000</td>
					<td>Books and supplies</td>
					<td>Textbook, transportation, and other supplies (excludes food service)</td>
				</tr>

				<tr>
					<td>6500</td>
					<td>Equipment replacement</td>
					<td>As defined (excludes facilities acquisition and construction)</td>
				</tr>

				<tr>
					<td>5000 and 7300</td>
					<td>Services and direct support</td>
					<td>Memberships, insurance, and other services and support (excludes nonagency, community services)</td>
				</tr>

			</table>
			</p><br/>

			<p>
				Average daily attendance (ADA) is defined as the total days of student attendance divided by the total days of instruction.  Per pupil spending is then calculated by dividing the current expense of education by ADA. For an alternative measure, divide total expenditures by enrollment.</a>.
			</p>

	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>