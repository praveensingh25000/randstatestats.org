<?php
/******************************************
* @Modified on March 22, 2012
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";
?>

 <!-- container -->
<section id="container">

	<section class="conatiner-full" id="inner-content">

		<p class="bblue">Texas High School Graduation
		Category Defintions</p><br/>

		<p>Graduate category programs are defined below.</p><br/>

		<table border="1" summary="Categories are shown in column one, definitions in column two.">

		<tr>
			<td valign="top"><strong>Category</strong></td>
			<td valign="top"><strong>Definition</strong></td>
		</tr>
		<tr>
			<td valign="top">Number of Graduates</td>
			<td valign="top">Count of students graduated during that academic year.</td>
		</tr>
		<tr>
			<td valign="top">Percentage of Graduates of Minimum High School Program</td>
			<td valign="top">Results of dividing the number of Graduates of Minimum High School Program by the Number of Graduates.</td>
		</tr>
		<tr>
			<td valign="top">Percentage of Graduates of Recommended High School Program</td>
			<td valign="top">Results of dividing the number of Graduates of Recommended High School Program by the Number of Graduates.</td>
		</tr>
		<tr>
			<td valign="top">Percentage of Graduates of Distinguished Achievement Program</td>
			<td valign="top">Results of dividing the number of Graduates of Distinguished Achievement Program by the Number of Graduates.</td>
		</tr>
		<tr>
			<td valign="top">Percentage of Graduates of Individual Education Plan</td>
			<td valign="top">Results of dividing the number of Graduates of Individual Education Plan by the Number of Graduates.</td>
		</tr>
		</table>

	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>