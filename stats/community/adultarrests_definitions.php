<?php
/******************************************
* @Modified on April 23, 2013
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

		<div class="form-div">
			<h2>Adult Arrests Glossary</h2><br/>

			<p>The Computerized Criminal History system includes adult arrests (ages 16 and older; and juvenile offenders prosecuted in adult courts) for fingerprintable offenses.</p><br/>

			<table border="1" class="table-div collapse">
				<tr>
					<th valign="top" class="bb thead_gr">Category</th>
					<th valign="top" class="txtcenter bb thead_gr">Definition</th>
				</tr>
				
				<tr>
					<td valign="top">Felony</td>
					<td valign="top">The Computerized Criminal History system includes adult arrests (ages 16 and older; and juvenile offenders prosecuted in adult courts) for fingerprintable offenses.</td>
				</tr>
				<tr>
					<td valign="top">Misdemeanor</td>
					<td valign="top">Offenses, other than traffic infractions, for which a sentence to a term of imprisonment in excess of 15 days may be imposed, but for which a sentence to a term of imprisonment in excess of one year cannot be imposed (NYS Penal Law Article 10.04).</td>
				</tr>
				<tr>
					<td valign="top">Drug</td>
					<td valign="top">Offenses include all charges listed under Penal Law Articles 220 (controlled substances) and 221 (marijuana).</td>
				</tr>
				<tr>
					<td valign="top">Violent Felony</td>
					<td valign="top">Offenses include all charges listed under Penal Law Article 70.02 and the Class A felonies of murder, arson, and kidnapping.</td>
				</tr>
				<tr>
					<td valign="top">Driving While Intoxicated</td>
					<td valign="top">Offenses include all charges listed under Vehicle and Traffic Law Section 1192.</td>
				</tr>
				<!--<tr>
					<td valign="top">Property</td>
					<td valign="top">Offenses include all misdemeanor charges listed under Penal Law Articles 140, 145, 150, 155 and 165.</td>
				</tr>-->
				<tr>
					<td valign="top">Other</td>
					<td valign="top">Offenses include all charges not specified above.</td>
				</tr>
			</table>

			<p>&nbsp;</p>
		</div>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>