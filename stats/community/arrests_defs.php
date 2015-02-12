<?php
/******************************************
* @Modified on March 21, 2012
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
	
		<h2>Arrest Ethnicity Definitions</h2><br/>
		<p>Ethnicity Definitions according to Texas Department of Public Safety:</p><br/>
		<p>
			<table border="1" class="collapse" width="700">
				<tr>
					<td valign="top" class="thead_gr"><strong>Ethnicity</strong></td>
					<td valign="top" class="thead_gr"><strong>Definition</strong></td>
					</tr>
					<tr>
					<td valign="top">White</td>
					<td valign="top">
					A person having origins in any of the original peoples of Europe,
					North Africa, or the Middle East.
					</td>
				</tr>

				<tr>
					<td valign="top">Black</td>
					<td valign="top">
					A person having origins in any of the black racial groups of Africa.
					</td>
				</tr>

				<tr>
					<td valign="top">American Indian or Alaskan Native </td>
					<td valign="top">
					A person having origins in any of the
					original peoples of North America and who maintains cultural
					identification through tribal affiliation or community recognition.
					</td>
				</tr>
				   
				<tr>
				<td valign="top">Asian or Pacific Islander</td>
					<td valign="top">
					A person having origins in any of the original
					peoples of the Far East, Southeast Asia, the Indian subcontinent, or the
					Pacific Islands.  This area includes, for example, China, India, Japan,
					Korea, the Philippine Islands, and Samoa.
					</td>
				</tr>

				<tr>
					<td valign="top">Hispanic</td>
					<td valign="top">
					A person of Mexican, Puerto Rican, Cuban, Central or South
					American or other Spanish culture or origin, regardless of race.
					</td>
				</tr>
			</table>
		</p>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>