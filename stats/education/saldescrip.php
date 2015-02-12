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

		<h2>Teacher salary descriptions</h2><br/>

		<div align="left">
		<table border="1" cellpadding="6" class="collapse">
			<tr class="gray">
				<th>Category</th>
				<th>Definition</th>
			</tr>
			<tr>
				<td>Total salary paid to certified employees</td>
				<td>The total salaries paid certificated employees on the regular teacher salary schedule.</td>
			</tr>
			<tr>
				<td>Total number of certified employees</td>
				<td>The total certificated full-time equivalent (FTE) employees that are paid on the salary schedule.</td>
			</tr>
			<tr>
				<td>Average salary paid to certified employees</td>
				<td>The total salary schedule cost divided by the FTE.</td>
			</tr>
			<tr>
				<td>Minimum salary offered to a certified employee</td>
				<td>The lowest salary that would be offered to an employee from the certificated salary schedule, generally column one, step one. The amount does not include salaries for extended year, bonuses for special accomplishments, or payment for extracurricular services such as coaching, drama or music. For most districts, this salary is the beginning teacher salary.</td>
			</tr>
			<tr>
				<td>Maximum salary offered to a certified employee</td>
				<td>The maximum salary that would be offered to an employee from the certificated salary schedule. It does not include salaries for extended year, bonuses for special accomplishments or payment for extracurricular services.
				</td>
			</tr>
			<tr>
				<td>Salary paid at Bachelor's degree plus 60 hours</td>
				<td>The amount that would be paid to an employee at step 10 with a requirement of a bachelor's degree plus 60 hours of additional credit. </td>
			</tr>
			<tr>
				<td>Number of service days required</td>
				<td>The number of scheduled/required service days for returning teachers. </td>
			</tr>
		</table>
		</div>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>