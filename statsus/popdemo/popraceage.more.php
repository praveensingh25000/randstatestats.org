<?php
/******************************************
* @Modified on march 14, 2013
* @Package: Rand
* @Developer: Baljinder Singh.
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

?>


<section id="container">
	<div id="inner-mainshell">
		<h2>Population by Race, Ethnic Origin, and Age Background</h2>
		<div class="clear pT30"></div>
		<div class="from wdthpercent100">
			<p style="text-align:justify">The categories reported for population by race, ethnic origin, and age have changed over time as the U.S. Census Bureau has changed its survey methods. From 1970-1989, available race categories were limited to Totals, White and Black. There was no ethnic origin code to indicate Hispanic or Latino origin. </p>
			<p>&nbsp;</p>
			<p style="text-align:justify">
			In 1990, the Census Bureau added two race categories: American Indian, Eskimo, or Aleut and Asian or Pacific Islander. In addition, the Bureau added two ethnic origin codes: Non-Hispanic origin or Latino and Hispanic or Latino origin. (This site shortens these categories to simply Non-Hispanic origin or Hispanic origin.) Thus, a person could be reported for any combination of race and ethnic origin, e.g., Black (Non-Hispanic origin) or Black (Hispanic origin).</p>
			<p>&nbsp;</p>
			<p style="text-align:justify">
			Beginning in 2000, the Census Bureau broke the American Indian, Eskimo, or Aleut and Asian or Pacific Islander category into two new race categories: Asian and Native Hawaiian, and Other Asian or Pacific Islander. In addition, the Bureau added "Race Alone" (e.g., White Alone, Asian Alone) and "Alone or in Combination" categories (e.g., White Alone or in Combination, American Indian and Alaska Native Alone or in Combination, and so on). Finally, the Bureau added a "Two or more races" category.</p>
			<p>&nbsp;</p>
			<p style="text-align:justify">
	
			Another significant change beginning in 2000 is the introduction of "Hispanic" ethnicity separate and apart from race. For example, a person may self identify as Hispanic ethnicity in addition to a race category, e.g., Hispanic and Black, or Hispanic and Asian. This form identifies All Hispanics (of any race) as the sum of all Hispanics for one race (i.e., White, Black, American Indian and Alaska Native, Asian, Native Hawaiian and Other Pacific Islander) plus Hispanic for two or more races. The same methodology applies for not-Hispanic categories.</p>
			<p>&nbsp;</p>
			<p style="text-align:justify">
			This form has aggregated many summary categories to preserve a data time series whenever possible. For example, All Whites (non-Hispanic origin) indicates the number of White Race, Non-Hispanic origin. However, the introduction of Race Alone and Race Alone or in Combination categories may lead to some inconsistency in data before and after the year 2000. Prior to 2000, the category "All Blacks" consisted of persons who self-identified as Black, but not as Black Alone or Black Alone or in Combination, categories which became available only in 2000. In building time series data, this site assumes the broader definition in each case where there is a Race Alone or in Combination category. For example, the category "All Blacks" includes Blacks from 1970-1999, and it includes "Black Alone or in Combination" beginning in 2000. In short, because of this change in race categories, data beginning in 2000 may double count some race categories. Users should be aware of these changes in categories and should exercise caution when interpreting these data.</p>
			<p>&nbsp;</p>
			<h2>General Information</h2>
					<p>&nbsp;</p>
			<p style="text-align:justify">
			Population by race/ethnicity and age group contains annual estimates of resident population of the U.S., California, and California counties by age, sex, and race as of July 1st of each year. The estimates use the 2000 Census as a baseline. For more information, see Bureau of the Census.</p>
			<p>&nbsp;</p>
			<p style="text-align:justify">
			County estimates are developed in a two-step procedure. First a set of state estimates by age, sex, race, and Hispanic origin (with the same categories as given above) are developed. These state estimates are developed using a cohort-component technique.</p>
			<p>&nbsp;</p>
			<p style="text-align:justify">
			The county detail estimates are produced in the second step using a ratio method, which adjusts data to sum to a pre-determined total. It consists of multiplying each element of the data by the ratio formed by dividing the desired total by the sum of the data. </p>
		</div>
	</div>
</section>
<div class="clear"></div>

<?php
include_once $basedir."/include/footerHtml.php";
?> 













