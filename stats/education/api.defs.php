<?php
/******************************************
* @Modified on April 4, 2013
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
		<h2>API Background</h2><br/>
		<p>
		<table border="1" class="collapse">
			<tr class="gray">
				<td><strong>Category</strong></td>
				<td><strong>Definition(s)</strong></td>
			</tr>
			<tr>
				<td valign="top">Tested API (Base)</td>
				<td>API test result between 200 and 1000. The target for
				all schools is 800.</td>
			</tr>
			<tr>
				<td valign="top">Statewide Rank or Similar Schools Rank</td>
				<td>Similar schools rank indicates decile rank compared
				to 100 other schools with similar demographic
				characteristics<br>
				9 or 10: Well above average for elementary, middle,or
				high schools with similar characteristics<br>
				7 or 8: Above average for elementary,middle,or high
				schools with similar characteristics <br>
				5 or 6: About average for elementary,middle,or high
				schools with similar characteristics <br>
				3 or 4: Below average for elementary,middle,or high
				schools with similar characteristics <br>
				1 or 2: Well below average for elementary,middle,or high
				schools with similar characteristics <br>
				Similar schools are determined by a number of factors,
				including ethnicity, mobility, the number of teachers
				with credentials, socioeconomic status, class size, and
			other factors. See <a href="http://www.cde.ca.gov/ta/ac/ap/index.asp" target="_blank">California
				Department of Education</a> for more information.</td>
			</tr>
			<tr>
				<td valign="top">API Growth Target</td>
				<td>A school's growth target is calculated by taking five
				percent of the distance between a school's 2001 API and
				the interim statewide performance target of 800. For any
				school<br>
				with a 2001 API of 781 to 799, the annual growth target
				is one point. Any school with an API of 800 or more must
				maintain an API of at least 800. &quot;A&quot; means the
				school scored at or above the interim Statewide
			Performance Target of 800.
			<br>
			An 'A' in this field means the school or subgroup scored at or above the statewide performance target of 800,
			a 'B' means the school is an LEA, and a 'C' means this is a special education school.
			</td>
			</tr>
			<tr>
				<td valign="top">API Target</td>
				<td>The API target is the sum of the most recent API and
				the growth target, except for schools with a most recent
			API of 800 or more.
			<br>
			An 'A' in this field means the school or subgroup scored at or above the statewide performance target of 800,
			a 'B' means the school is an LEA, and a 'C' means this is a special education school.
			</td>
			</tr>
			<tr>
				<td valign="top">School Characteristic Index</td>
				<td>Used to determine similar schools to use in similar
			schools ranking. See <a href="http://www.cde.ca.gov/ta/ac/ap/index.asp" target="_blank">California
				Department of Education</a> for more information.</td>
			</tr>
		</table>
		</p>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>