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
		<h2>Funding of delivery categories merged.</h2><br/>

			<p>The 1989-2004 funding of delivery categories have been merged to match the 2005+ categories as follows:</p><br/>

			<p>
			<table border=1 class="collapse pad4">
				<tr class="gray">
					<th>2005 and later:</th>
					<th>2004 and earlier:</th>
				</tr>
				<tr>
					<td>N/A or none</td>
					<td>
						Delivery payment source: Medically unattended birth<br>
						Delivery payment source: no charge
					</td>
				</tr>
				<tr>
					<td>Medi-Cal</td>
					<td>Delivery payment source: Medi-Cal</td>
				</tr>
				<tr>
					<td>Other Government Programs</td>
					<td>
						Delivery payment source: Medicare<br>
						Delivery payment source: Worker's compensation<br>
						Delivery payment source: Title V (MCH Funds)<br>
						Delivery payment source: other government programs<br>
					</td>
				</tr>
				<tr>
					<td>Private Insurance Company</td>
					<td>
						Delivery payment source: Blue Cross/Blue Shield<br>
						Delivery payment source: private insurance<br>
						Delivery payment source: HMO/PHP<br>
					</td>
				</tr>
				<tr>
					<td>Self Pay</td>
					<td>Delivery payment source: self pay</td>
				</tr>
				<tr>
					<td>Other non-government program</td>
					<td>
						Delivery payment source: other non-government program<br>
						Deliveries with medically indigent patient
					</td>
				</tr>
				<tr>
					<td>Unknown or unreported</td>
					<td>Delivery payment source: unknown</td>
				</tr>
			</table>
		</p>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>