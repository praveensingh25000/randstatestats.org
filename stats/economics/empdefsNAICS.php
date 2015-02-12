<?php
/******************************************
* @Modified on March 21, 2012
* @Package: Rand
* @Developer: Pragati garg
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";
?>

 <!-- container -->
<section id="container">

	<section class="conatiner-full" id="inner-content">

	<h2>RAND Texas Employment and Unemployment Category Definitions</h2>
<br/>
		<table border="1" class="data-table">
			<tr>
				<td width="150" align="middle"><strong>Employment Category</strong></td>
				<td width="300" align="middle"><strong>Definition</strong></td>
				<td width="300" align="middle"><strong>Examples</strong></td>
			</tr>

			<tr>
				<td valign="top" width="150">Construction</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 23</td>
				<td width="300">Residential Building Construction (NAICS 2332)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Financial Activites</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 52-53</td>
				<td width="300">Activities Related to Real Estate (NAICS 5313)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Goods Producing</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major groups 23, 31-33, 21, and 1133</td></td>
				<td width="300"></td>
			</tr>

			<tr>
				<td valign="top" width="150">Government</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 91-93</td>
				<td width="300">Local Government Education (NAICS 931611)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Manufacturing</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 31-33</td>
				<td width="300">Sugar and Confectionery Product Manufacturing (NAICS 3113)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Natural Resources & Mining</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 21, 1133</td>
				<td width="300">Support Activities for Mining (NAICS 213)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Retail Trade</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 44-45</td>
				<td width="300">Book, Periodical, and Music Stores (NAICS 4512)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Service Producing</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group  220000, 420000-813000, 92000</td>
				<td width="300">Printing and Writing Paper Wholesalers (NAICS 42211)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Total Farm</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 111000-113200, 114000-115000</td>
				<td width="300">Potato Farming (NAICS 11121)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Total Nonfarm</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 113000, 210000-813000, 92000</td>
				<td width="300">NA</td>
			</tr>

			<tr>
				<td valign="top" width="150">Total All Industries</a>
				<td width="300">"Sum of Farm and Nonfarm. Contains <a href="naicslist.php">NAICS</a> major group 11-813, 92"</td>
				<td width="300">NA</td>
			</tr>

			<tr>
				<td valign="top" width="150">Transportation and Warehousing</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 22, 48-49</td>
				<td width="300">Air Transportation (NAICS 481)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Wholesale Trade</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 42</td>
				<td width="300">Beer and Ale Wholesalers (NAICS 42281)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Information</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 51</td>
				<td width="300">Motion Picture and Sound Recording (NAICS 512)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Professional and Business Services</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 54-56</td>
				<td width="300">Travel Arrangement and Reservation Services (NAICS 5615)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Educational & Health Services</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 61-62</td>
				<td width="300">Elementary and Secondary Schools (NAICS 6111)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Leisure & Hospitality</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 71-72</td>
				<td width="300">Performing Arts, Spectator Sports (NAICS 7111)</td>
			</tr>

			<tr>
				<td valign="top" width="150">Other Services</a>
				<td width="300">Contains <a href="naicslist.php">NAICS</a> major group 81</td>
				<td width="300">Religious, Grantmaking, Civic, Professional, and Similar Organizations (NAICS 813)</td>
			</tr>

		</table>
	</section>

</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>