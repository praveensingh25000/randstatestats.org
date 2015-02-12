<?php
/******************************************
* @Modified on Dec 06, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/header.php";

if(isset($_POST['getresults'])){
	$_SESSION['search'] = $_POST;
	header('location: resultsAsylee.php');
	exit;
}
?>


<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">
		<!-- left side -->
		<div class="containerL">
			<h1>Asylees granted Affirmative Entry by Country of Nationality, 1998-Present</h1>
			<p>
				<strong>Contains:</strong> Individuals granted asylum affirmativels by country of nationality
			</p>
			<!-- -->
			<div class="additional">
				<div id="add">
				<a title="Click to expand section" class="plus" id="togglebutton" href="#"></a>
				<a href="#">Additional background</a></div>
				<div class="content-main" id="content" style="background: #ffffcc;">
					<p>This database estimates total resident population in U.S. states from 1790 to the present. The U.S. Bureau of the Census, the source for these data, provided decennial estimates from 1790-1890. 1790 estimates reflect the population on August 2; 1800 estimates reflect August 4; 1810 estimates reflect August 6; 1820 estimates reflect August 7. Estimates from 1830-1890 reflect June 1. </p>
				</div>
			</div>
			<div style="clear: both; padding: 15px 0;">
			<fieldset style="border: 1px solid #cccccc; padding: 10px;">
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Set your Search Criteria</legend>
				<form method="post" id="frmPost" name="frmPost">
				<p>Type in the Country(ies) and/or Region(s) of Nationality </p>
				 <div style="padding: 10px 0;">
					<input type="text" id="demo-input-prevent-duplicates" name="blah" />
					<script type="text/javascript">
					$(document).ready(function() {
						$("#demo-input-prevent-duplicates").tokenInput("<?php echo URL_SITE; ?>/asyleesearch.php", {
							preventDuplicates: true
						});
					});
					</script>
				</div>

				

				<table border="0" summary="Select the start month and year, and the end month and year.">
					<tbody>
						<tr><th rowspan="2" valign="middle" style="padding-right: 15px;">Define a Time Period for Data</th>
							<th class="rblue"><label for="syear">Start Year</label></th>
							<th class="rblue"><label for="eyear">End Year</label></th>
						</tr>
						<tr>
							<td>
								<div id="begYear"><select id="syear" size="1" name="syear">
								<option value="2001" selected="">2001</option>
								<option value="2002">2002</option>
								<option value="2003">2003</option>
								<option value="2004">2004</option>
								<option value="2005">2005</option>
								<option value="2006">2006</option>
								<option value="2007">2007</option>
								<option value="2008">2008</option>
								<option value="2009">2009</option>
								<option value="2010">2010</option>
								<option value="2011">2011</option>
								<option value="2012">2012</option>
								</select>
								</div>
							</td>
							<td>
								<div id="endYear"><select id="eyear" size="1" name="eyear">
								<option value="2001">2001</option>
								<option value="2002">2002</option>
								<option value="2003">2003</option>
								<option value="2004">2004</option>
								<option value="2005">2005</option>
								<option value="2006">2006</option>
								<option value="2007">2007</option>
								<option value="2008">2008</option>
								<option value="2009">2009</option>
								<option value="2010">2010</option>
								<option value="2011">2011</option>
								<option value="2012" selected="">2012</option>
								</select>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<br/>

				<div class="submit1 submitbtn-div">
					<label for="submit" class="left">
						<input type="submit" value="Submit" name="getresults" class="submitbtn">
					</label>
					<label for="reset" class="right">
						<input type="reset" id="reset" class="submitbtn">
					</label>
				</div>
				</form>
				</fieldset>
			</div>
		 </div>
			<!-- left side -->
		 <!-- right side -->
		<aside class="containerR">
			<h2>Related database</h2>
			<ul>
				<li><a href="#">Persons Naturalized by country of Birth in U.S State</a></li>
				<li><a href="#">Persons Naturalized by Gender, Age and Occupation</a></li>
				<li><a href="#">Nonimmigrant Admission by Category and Country of Citizenship</a></li>
			</ul>
		</aside>
		<!-- /right side -->
</section>
<!-- /container -->



