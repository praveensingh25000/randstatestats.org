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

	<h2>Energy Source Abbreviations</h2><br/>

<div>
<table border="1" class="collapse">
<!--StartFragment-->
 <tbody><tr class="thead_gr">
  <th>Word</th>
  <th>Abbreviation</th>
 </tr>
 <tr height="15">
  <td height="15">Asphalt &amp; road oil </td>
  <td>Asph. &amp; road oil</td>
 </tr>
 <tr height="15">
  <td height="15">Average </td>
  <td>Avg.</td>
 </tr>
 <tr height="15">
  <td height="15">British Thermal Unit (energy needed to heat 1 pound of water 1&deg;F)
  </td>
  <td>BTU</td>
 </tr>
 <tr height="15">
  <td height="15">Commercial </td>
  <td>Comml.</td>
 </tr>
 <tr height="15">
  <td height="15">Distillate fuel oil </td>
  <td>Dist. fuel oil</td>
 </tr>
 <tr height="15">
  <td height="15">Electric power sector</td>
  <td>Elec. pwr. sector</td>
 </tr>
 <tr height="15">
  <td height="15">Excude(s) </td>
  <td>Excl.</td>
 </tr>
 <tr height="15">
  <td height="15">Expenditure</td>
  <td>Expend.</td>
 </tr>
 <tr height="15">
  <td height="15">Including </td>
  <td>Incl.</td>
 </tr>
 <tr height="15">
  <td height="15">Industrial </td>
  <td>Indus.</td>
 </tr>
 <tr height="15">
  <td height="15">Natural gas </td>
  <td>Nat. gas</td>
 </tr>
 <tr height="15">
  <td height="15">Negative </td>
  <td>Neg.</td>
 </tr>
<tr height="15">
  <td height="15">Other petroleum products </td>
  <td>Other petrol. products</td>
 </tr>
 <tr height="15">
  <td height="15">Petrochemical</td>
  <td>Petrochem.</td>
 </tr>
 <tr height="15">
  <td height="15">Photovoltaic</td>
  <td>PV</td>
 </tr>
 <tr height="15">
  <td height="15">Physical </td>
  <td>Phys.</td>
 </tr>
 <tr height="15">
  <td height="15">Positive </td>
  <td>Pos.</td>
 </tr>
 <tr height="15">
  <td height="15">Residential </td>
  <td>Resi.</td>
 </tr>
 <tr height="15">
  <td height="15">Supplemental gaseous fuels </td>
  <td>Supp. gas. fuels</td>
 </tr>
 <tr height="15">
  <td height="15">Transportation </td>
  <td>Transp.</td>
 </tr>
<!--EndFragment-->
</tbody></table><br>
</div>

<div style="width:100%">
<h2>Note on Negative Numbers</h2><br/>
<p>
In theory, all the consumption estimates should be non-negative. However, there are a few data points in the SEDS database that are negative.
</p><br/>
<p>
Occasionally, consumption for aviation gasoline blending components and unfinished oils will be negative. This can occur when such products have entered the primary supply channels with their production not having been reported (e.g., streams returned to refineries from petrochemical plants).
</p><br/>

<p>
In the case of hydroelectric power and nuclear electric power, consumption is derived from electricity generation. When the plant is closed for maintenance and is using its own electricity, a negative net generation number may be reported, and is carried over to the consumption series.
</p><br/>

<p>
For petroleum products, the top line consumption for the United States comes from a concept called "product supplied."  In the EIA glossary, product supplied is defined as:
</p><br/>


<p>
Product supplied:  Approximately represents consumption of petroleum products because it measures the disappearance of these products from primary sources, i.e., refineries, natural gas-processing plants, blending plants, pipelines, and bulk terminals. In general, product supplied of each product in any given period is computed as follows field production, plus refinery production, plus imports, plus unaccounted-for crude oil (plus net receipts when calculated on a PAD District basis) minus stock change, minus crude oil losses, minus refinery inputs, and minus exports.
</p>
</div>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>