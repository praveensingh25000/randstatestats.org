<?php
/******************************************
* @Modified on March 23, 2013
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

	<h2>Weather Statistics Glossary</h2><br/>

		<table cellpadding="6" border="1" class="collapse">
			<tr class="gray">
			<th valign="top" align="middle">Category</th>
			<th valign="top" align="middle">Definition</th>
			</tr>
			<tr>
				<td valign="top">Temperature normals, max.</td>
				<td valign="top">Normal maximum temperature, degrees F</td>
			</tr>
			<tr>
				<td valign="top">Temperature normals, mean</td>
				<td valign="top">Average of normal maximum and minimum
				temperatures, degrees F</td>
			</tr>
			<tr>
				<td valign="top">Temperature normals, min.</td>
				<td valign="top">Normal minimum temperature, degrees F</td>
			</tr>
			<tr>
				<td valign="top">Precipitation normals</td>
				<td valign="top">Normal precipitation in snow, rainfall,
				etc.</td>
			</tr>
			<tr>
				<td valign="top">Degree day normals, CDD</td>
				<td valign="top">Average temperature, degrees F, minus
				65; shows demand for fuel to cool buildings</td>
			</tr>
			<tr>
				<td valign="top">Degree day normals, HDD</td>
				<td valign="top">65 minus average temperature, degrees F;
				shows demand for fuel to heat buildings</td>
			</tr>
			<tr>
				<td valign="top">Highest mean</td>
				<td valign="top">Maximum Mean Monthly Value/Year,
				1971-2000</td>
			</tr>
			<tr>
				<td valign="top">Median</td>
				<td valign="top">Median Mean Monthly Value/Year,
				1971-2000</td>
			</tr>
			<tr>
				<td valign="top">Lowest mean</td>
				<td valign="top">Minimum Mean Monthly Value/Year,
				1971-2000</td>
			</tr>
			<tr>
				<td valign="top">Highest mean year</td>
				<td valign="top">Year of Maximum Mean, 1971-2000</td>
			</tr>
			<tr>
				<td valign="top">Lowest mean year</td>
				<td valign="top">Year of Minimum Mean, 1971-2000</td>
			</tr>
			<tr>
				<td valign="top">Max. adjustment</td>
				<td valign="top">Add to MAX to Get Midnight Obs. Schedule</td>
			</tr>
			<tr>
				<td valign="top">Min. adjustment</td>
				<td valign="top">Add to MIN to Get Midnight Obs. Schedule</td>
			</tr>
		</table>

	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>