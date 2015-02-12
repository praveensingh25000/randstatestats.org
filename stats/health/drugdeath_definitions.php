<?php
/******************************************
* @Modified on March 29, 2013
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
	<h2>Drug-Related Death Definitions</h2><br/>

	<div>
		<table border=1 class="collapse" cellpadding="6" width="100%">
			<tr class="thead">
				<th align=left>Drug definitions</th>
				<th align=left>Includes</th>
			</tr>
			<tr>
				<td valign="top">Analgesics</td>
				<td>
					4-aminophenol derivatives<br>
					antipyretics<br>
					antirheumatics<br>
					nonopioid analgesics<br>
					nonsteroidal anti-inflammatory drugs [NSAID]<br>
					pyrazolone derivatives<br>
					salicylates<br>
				</td>
			</tr>
			<tr>
				<td valign="top">Sedative-hypnotics</td>
				<td>
					antidepressants<br>
					anti-epileptic drugs not elsewhere classified<br>
					anti-parkinsonism drugs not elsewhere classified<br>
					barbiturates<br>
					hydantoin derivatives<br>
					iminostilbenes<br>
					methaqualone compounds<br>
					neuroleptics<br>
					psychostimulants<br>
					psychotropic drugs not elsewhere classified<br>
					sedative-hypnotics not elsewhere classified<br>
					succinimides and oxazolidinediones<br>
					tranquillizers<br>
				</td>
			</tr>
			<tr>
				<td valign="top">Narcotics</td>
				<td>
					cannabis (derivatives)<br>
					cocaine<br>
					codeine<br>
					heroin<br>
					lysergide [LSD]<br>
					mescaline<br>
					methadone<br>
					morphine<br>
					narcotics not elsewhere classified<br>
					opium (alkaloids)<br>
					psychodysleptics [hallucinogens] not elsewhere classified<br>
				</td>
			</tr>
			<tr>
				<td valign="top">Other drugs acting on the<br>autonomic nervous system</td>
				<td>
					parasympatholytics [anticholinergics and antimuscarinics] and spasmolytics<br>
					parasympathomimetics [cholinergics]<br>
					sympatholytics [antiadrenergics]<br>
					sympathomimetics [adrenergics]<br>
				</td>
			</tr>
			<tr>
				<td valign="top">Unspecified drugs</td>
				<td>
					agents primarily acting on smooth and skeletal muscles and the respiratory system<br>
					anaesthetics (general)(local)<br>
					biological substances<br>
					drugs affecting the:<br>
					<div class="ind1em">
						&bull; cardiovascular system<br>
						&bull; gastrointestinal system<br>
					</div>
					hormones and synthetic substitutes<br>
					medicaments<br>
					systemic and haematological agents<br>
					systemic antibiotics and other anti-infectives<br>
					therapeutic gases<br>
					topical preparations<br>
					vaccines<br>
					water-balance agents and drugs affecting mineral and uric acid metabolism<br>
				</td>
			</tr>
		</table>
		</div>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>