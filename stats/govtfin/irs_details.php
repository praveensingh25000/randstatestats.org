<?php
/******************************************
* @Modified on july 31, 2013
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";
?>
<!-- container -->
<section id="container">
	<section class="conatiner-full" id="inner-content">
		<h2>Detailed IRS Collections</h2><br/>			
		<ul>
			<li style="padding-top:5px;">The database shows gross IRS collections. Gross collections less refunds equals net collections.</li>
			<li style="padding-top:5px;">Beginning with Fiscal Year 2009, credits to taxpayer accounts are excluded from the category Total collections.</li>
			<li style="padding-top:5px;">Business income taxes include taxes on corporation income (Form 1120 series) and unrelated business income from tax-exempt organizations (Form 990-T).</li>
			<li style="padding-top:5px;">Income tax reported for estates and trusts is included in individual income tax in Fiscal Years 1960-2007. Beginning with Fiscal Year 2008, estate and trust income tax is reported separately.</li>
			<li style="padding-top:5px;">Employment taxes includes taxes for Old-Age, Survivors, Disability, and Hospital Insurance (OASDHI); unemployment insurance under the Federal Unemployment Tax Act (FUTA); and railroad retirement under the Railroad Retirement Tax Act (RRTA).</li>
			<li style="padding-top:5px;">Excise taxes exclude those collected by the U.S. Customs and Border Protection and the Alcohol and Tobacco Tax and Trade Bureau. The Internal Revenue Service collected taxes on alcohol and tobacco until Fiscal Year 1988, and taxes on firearms until Fiscal Year 1991.</li>
			<li style="padding-top:5px;">Years reflect Fiscal Years, currently Oct. 1-Sept. 30.  Prior to 1977, Fiscal Years reflected July 1-June 30.  The database excludes the 1976-1977 fiscal-year transitional period.</li>
			<li style="padding-top:5px;">The estate tax was temporarily repealed for deaths in Calendar Year 2010 before being reinstated retroactively with a $5-million exemption as part of the Tax Relief, Unemployment Insurance Reauthorization, and Job Creation Act of 2010. As a result of this legislation, the estates of 2010 decedents could elect to file either Form 706 (estate and generation-skipping transfer tax return), due September 19, 2011, or Form 8939 (allocation of increase in basis for property acquired from a decedent), due January 17, 2012. The law also provided a $5-million exemption for the estates of 2011 decedents. These tax law changes significantly reduced estate tax gross collections in Fiscal Year 2011 relative to other fiscal years.</li>
			<li style="padding-top:5px;">Gifts are taxed based on the Federal tax law in effect for the year in which they are given, and the majority of gifts given in one year are reported to the IRS in the following year. Gift tax collections decreased significantly between Fiscal Years 2011 and 2012, which reflects a decrease in the amount of gift tax collections on gifts made primarily in 2010 and 2011. Gifts made during Calendar Year 2010 were subject to a maximum unified credit amount of $330,800; gifts made during Calendar Year 2011 were subject to a maximum unified credit amount of $1,730,800. The unified credit, which applies to the sum of both taxable gifts made during life and a decedent's estate, is a credit to offset the amount of transfer tax that would be assessed on assets below the applicable exclusion amount.</li>
			<li style="padding-top:5px;">Partnership and S corporation data are not shown in this table since these entities generally do not have a tax liability. Instead, they pass any profits or losses to the underlying owners who include these profits or losses on their income tax returns.</li>		
		</ul>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>